<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Export_Account_staatment implements FromCollection, WithStyles
{
    protected $data_list, $opening_debit, $opening_credit, $header_info;

    public function __construct($data_list, $opening_debit, $opening_credit, $header_info)
    {
        $this->data_list = $data_list;
        $this->opening_debit = $opening_debit;
        $this->opening_credit = $opening_credit;
        $this->header_info = $header_info; // يشمل الاسم، الفرع، التواريخ
    }

    public function collection()
    {
        $exportData = [];

        // 1. إضافة الجزء الذي طلبته (الترويسة العلوية)
        $exportData[] = [__('home.account_statement'), $this->header_info['account_name'], __('report.from'), $this->header_info['start_at'], __('report.to'), $this->header_info['end_at'], __('home.exportTime'), now()->format('Y-m-d H:i')];
        $exportData[] = []; // سطر فارغ للفصل

        // 2. عناوين الجدول الأساسي
        $exportData[] = ["#", "رقم المستند", "التاريخ", "الفرع", "الموظف", "المبلغ المدفوع", "مدين", "دائن", "الرصيد الحالي", "ملاحظات"];

        // 3. رصيد أول المدة
        $running_total_debit = $this->opening_debit;
        $running_total_credit = $this->opening_credit;
        $exportData[] = ['-', '-', '-', '-', '-', '-', $this->opening_debit, $this->opening_credit, $this->calculateBalance($running_total_debit, $running_total_credit), 'رصيد أول المدة'];

        // 4. حركات الحساب (تأكد من إرسال البيانات كاملة من الكنترولر)
        foreach ($this->data_list as $index => $row) {
            $running_total_debit += $row['depit'];
            $running_total_credit += $row['credit'];

            $exportData[] = [
                $index + 1,
                $row['dely_record'] ?? '-',
                $row['date'],
                $row['branch'],
                $row['user'],
                $row['recive_amount'],
                $row['depit'],
                $row['credit'],
                $this->calculateBalance($running_total_debit, $running_total_credit),
                $row['note']
            ];
        }

        // 5. الإجمالي النهائي (TOTAL) كما ظهر في صورتك
        $exportData[] = ['TOTAL', '-', '-', '-', '-', '-', $running_total_debit, $running_total_credit, $this->calculateBalance($running_total_debit, $running_total_credit), '-'];

        return collect($exportData);
    }

    private function calculateBalance($d, $c) {
        $diff = $d - $c;
        return $diff > 0 ? "مدين (" . number_format($diff, 2) . ")" : "دائن (" . number_format(abs($diff), 2) . ")";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => '419BB2']]], // تنسيق الترويسة العلوية
            3 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E0E0E0']]], // عناوين الجدول
        ];
    }
}