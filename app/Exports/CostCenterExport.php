<?php

namespace App\Exports;

use App\Models\credittransactions;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CostCenterExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_at;
    protected $end_at;
    protected $cost_center;

    public function __construct($start_at, $end_at, $cost_center)
    {
        // تأكد أن الصيغة القادمة من الطلب (Request) هي Y-m-d H:i:s
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        $this->cost_center = $cost_center;
    }

public function collection()
{
    return credittransactions::select(
        'cost_center',
        DB::raw('SUM(debtor) as total_debtor'),
        DB::raw('SUM(creditor) as total_creditor')
    )
    ->with('cost_center_data')
    // تأكد من تغيير 'created_at' للاسم الصحيح للعمود لديك في القاعدة
    ->when($this->start_at && $this->end_at, function ($query) {
        return $query->whereBetween('created_at', [
            $this->start_at, 
            $this->end_at
        ]);
    })
    ->when($this->cost_center, function ($query) {
        return $query->where('cost_center', $this->cost_center);
    })
    ->groupBy('cost_center')
    ->get();
}
    public function headings(): array
    {
        return [
            'ID مركز التكلفة',
            'اسم مركز التكلفة',
            'إجمالي المدين (Debtor)',
            'إجمالي الدائن (Creditor)',
            'الرصيد (Balance)'
        ];
    }

    public function map($row): array
    {
        return [
            $row->cost_center,
            $row->cost_center_data ? $row->cost_center_data->cost_center_ar : 'N/A',
            number_format($row->total_debtor, 2, '.', ''),
            number_format($row->total_creditor, 2, '.', ''),
            number_format($row->total_debtor - $row->total_creditor, 2, '.', ''),
        ];
    }
}