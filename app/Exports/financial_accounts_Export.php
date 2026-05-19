<?php

namespace App\Exports;

use App\Models\financial_accounts;
use App\Models\acounts_type;
use App\Models\credittransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class financial_accounts_Export implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $rows = [];
    protected $styles = [];
    protected $currentRow = 2; 
    protected $start_at;
    protected $end_at;
    protected $allAccounts;
    protected $directBalances;
    
    protected $grand_o_d = 0;
    protected $grand_o_c = 0;
    protected $grand_c_d = 0;
    protected $grand_c_c = 0;
    protected $grand_f_d = 0;
    protected $grand_f_c = 0;

    private $colorMainParent = 'FFCCE5FF'; 

    public function __construct($start_at, $end_at)
    {
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $this->allAccounts = financial_accounts::all();
        $this->directBalances = credittransactions::where('save', 1)
            ->select('customer_id', 
                DB::raw("SUM(CASE WHEN DATE(created_at) < '{$this->start_at}' THEN debtor ELSE 0 END) as open_debtor"),
                DB::raw("SUM(CASE WHEN DATE(created_at) < '{$this->start_at}' THEN creditor ELSE 0 END) as open_creditor"),
                DB::raw("SUM(CASE WHEN DATE(created_at) >= '{$this->start_at}' AND DATE(created_at) <= '{$this->end_at}' THEN debtor ELSE 0 END) as curr_debtor"),
                DB::raw("SUM(CASE WHEN DATE(created_at) >= '{$this->start_at}' AND DATE(created_at) <= '{$this->end_at}' THEN creditor ELSE 0 END) as curr_creditor")
            )
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');
    }

    private function getOnlyChildrenSum($accountId)
    {
        $totals = ['o_d' => 0, 'o_c' => 0, 'c_d' => 0, 'c_c' => 0];
        $children = $this->allAccounts->where('parent_account_number', $accountId);

        if ($children->isEmpty()) {
            $direct = $this->directBalances[$accountId] ?? null;
            if ($direct) {
                // استخدام round هنا لضمان دقة الأرصدة المباشرة
                $totals['o_d'] = round((float)$direct->open_debtor, 2);
                $totals['o_c'] = round((float)$direct->open_creditor, 2);
                $totals['c_d'] = round((float)$direct->curr_debtor, 2);
                $totals['c_c'] = round((float)$direct->curr_creditor, 2);
            }
        } else {
            foreach ($children as $child) {
                $childSum = $this->getOnlyChildrenSum($child->id);
                $totals['o_d'] += $childSum['o_d'];
                $totals['o_c'] += $childSum['o_c'];
                $totals['c_d'] += $childSum['c_d'];
                $totals['c_c'] += $childSum['c_c'];
            }
        }
        
        // تقريب النتائج النهائية لكل مستوى
        $totals['o_d'] = round($totals['o_d'], 2);
        $totals['o_c'] = round($totals['o_c'], 2);
        $totals['c_d'] = round($totals['c_d'], 2);
        $totals['c_c'] = round($totals['c_c'], 2);
        
        return $totals;
    }

    public function collection()
    {
        $this->rows = [];
        $this->styles = [];
        $this->currentRow = 2; 

        $types = acounts_type::all();
        
        foreach ($types as $type) {
            $topAccounts = $this->allAccounts->where('account_type', $type->id)
                ->whereIn('parent_account_number', [null, 0, '']);

            $initialCount = count($this->rows);
            $placeholderIdx = count($this->rows);
            $typeTotals = ['o_d' => 0, 'o_c' => 0, 'c_d' => 0, 'c_c' => 0, 'f_d' => 0, 'f_c' => 0];
            foreach ($topAccounts as $acc) {
                $this->processRow($acc, 0); 
                
                $accVals = $this->getOnlyChildrenSum($acc->id);
                $typeTotals['o_d'] += $accVals['o_d'];
                $typeTotals['o_c'] += $accVals['o_c'];
                $typeTotals['c_d'] += $accVals['c_d'];
                $typeTotals['c_c'] += $accVals['c_c'];
                $accNet =round(($accVals['o_d'] + $accVals['c_d']) - ($accVals['o_c'] + $accVals['c_c']), 2);
                if ($accNet > 0) $typeTotals['f_d'] += $accNet;
                if ($accNet < 0) $typeTotals['f_c'] += abs($accNet);
            }

            if (count($this->rows) > $initialCount) {
                $typeName = app()->getLocale() == 'ar' ? $type->name_ar : $type->name_en;
                $net = round(( $typeTotals['c_d']) - ( $typeTotals['c_c']), 2);

                array_splice($this->rows, $placeholderIdx, 0, [[
                    'account_number' => $type->id, 
                    'name'           => 'إجمالي ' . $typeName, 
                    'account_type'   => 'تصنيف الرئيسي', 
                    'status'         => '---',
                    'o_d' => $typeTotals['o_d']==0?"0":round($typeTotals['o_d'], 2), 
                    'o_c' => $typeTotals['o_c']==0?"0":round($typeTotals['o_c'], 2), 
                    'c_d' => $typeTotals['c_d']==0?"0":round($typeTotals['c_d'], 2), 
                    'c_c' => $typeTotals['c_c']==0?"0":round($typeTotals['c_c'], 2), 
                    'f_d' => $typeTotals['f_d']==0?"0" : round($typeTotals['f_d'], 2), 
                    'f_c' => $typeTotals['f_c']==0?"0" : round($typeTotals['f_c'], 2),
                    'net_amount' => $net==0?'0':abs($net),
                    'balance_case' => $net > 0 ? 'مدين' : ($net < 0 ? 'دائن' : 'متزن')
                ]]);

                $targetRow = $this->currentRow - (count($this->rows) - $placeholderIdx) + 1;
                $this->styles[$targetRow] = ['fill' => $this->colorMainParent, 'bold' => true];
                
                $this->currentRow++;

                $this->grand_o_d += $typeTotals['o_d'];
                $this->grand_o_c += $typeTotals['o_c'];
                $this->grand_c_d += $typeTotals['c_d'];
                $this->grand_c_c += $typeTotals['c_c'];
                $this->grand_f_d += $typeTotals['f_d'];
                $this->grand_f_c += $typeTotals['f_c'];
            }
        }

        $final_net =round($this->grand_f_d, 2) -round($this->grand_f_c, 2);
        $this->rows[] = [
            'account_number'   => '', 
            'name'             => 'الإجمالي العام', 
            'account_type'     => '', 
            'status'           => '',
            'o_d' => $this->grand_o_d==0?"0":round($this->grand_o_d, 2), 
            'o_c' => $this->grand_o_c==0?"0":round($this->grand_o_c, 2),
            'c_d' => round($this->grand_c_d, 2), 
            'c_c' => round($this->grand_c_c, 2),
            'f_d' => round($this->grand_f_d, 2), 
            'f_c' => round($this->grand_f_c, 2),
            'net_amount' => $final_net==0?'0':abs($final_net),
            'balance_case' => $final_net > 0 ? 'مدين' : ($final_net < 0 ? 'دائن' : 'متزن')
        ];
        $this->styles[$this->currentRow] = ['fill' => 'FFA9A9A9', 'bold' => true];

        return new Collection($this->rows);
    }

    private function processRow($account, $level) 
    {
        $vals = $this->getOnlyChildrenSum($account->id);
        $activity = array_sum($vals);

        if ($activity != 0 || $vals['o_d'] != 0 || $vals['o_c'] != 0) {
            
            $isTargetMainAccount = in_array($account->account_number, [1, 2, 3, 4, 5]);
            $isFinalChild = $this->allAccounts->where('parent_account_number', $account->id)->isEmpty();

            if ($isTargetMainAccount || $isFinalChild) {
                // حساب الصافي مع التقريب
                $net = round(($vals['o_d'] + $vals['c_d']) - ($vals['o_c'] + $vals['c_c']), 2);
                $indent = str_repeat('    ', $level);

                $this->rows[] = [
                    'account_number'   => $account->account_number,
                    'name'             => $indent . $account->name,
                    'account_type'     => $account->acounts_type->name_ar ?? '',
                    'status'           => $account->is_parent == 1 ? 'رئيسي' : 'فرعي',
                    'debtor_opening'   => $vals['o_d']==0?'0':round($vals['o_d'], 2),
                    'creditor_opening' => $vals['o_c']==0?'0':round($vals['o_c'], 2),
                    'debtor_current'   => $vals['c_d']==0?'0':round($vals['c_d'], 2),
                    'creditor_current' => $vals['c_c']==0?'0':round($vals['c_c'], 2),
                    'final_debtor'     => $net > 0 ? $net : '0',
                    'final_creditor'   => $net < 0 ? abs($net) : '0',
                    'net_amount'       => $net==0?'0':abs($net),
                    'balance_case'     => $net > 0 ? 'مدين' : ($net < 0 ? 'دائن' : 'متزن')
                ];

                $this->currentRow++;
            }

            $children = $this->allAccounts->where('parent_account_number', $account->id)->sortBy('account_number');
            foreach ($children as $child) {
                $this->processRow($child, $level + 1);
            }
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        foreach ($this->styles as $row => $format) {
            $range = "A{$row}:L{$row}";
            if (isset($format['bold'])) $sheet->getStyle($range)->getFont()->setBold(true);
            if (isset($format['fill'])) {
                $sheet->getStyle($range)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($format['fill']);
            }
        }
    }

    public function headings(): array
    {
        return [
            "رقم الحساب", "اسم الحساب", "التصنيف", "النوع", 
            "مدين أول", "دائن أول", "حركة مدين", "حركة دائن", 
            "رصيد مدين", "رصيد دائن", "صافي المبلغ", "حالة الرصيد"
        ];
    }
}