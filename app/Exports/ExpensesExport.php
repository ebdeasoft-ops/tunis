<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExpensesExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;
    protected $totals;

    public function __construct($data, $totals)
    {
        $this->data = $data;
        $this->totals = $totals;
    }

    public function collection()
    {
        return $this->data->map(function($invoice) {
            return [
                $invoice->id,
                $invoice->created_at,
                $invoice->branch->name ?? '-',
                $invoice->user->name ?? '-',
                $invoice->vat == 1 ? 'يخضع للضريبة' : 'غير خاضع',
                $invoice->recive_amount,
                $invoice->financial_accounts_data->name ?? '-',
                $invoice->Pay_Method_Name,
                $invoice->note ?? '-'
            ];
        });
    }

    public function headings(): array
    {
        return ['#', 'التاريخ', 'الفرع', 'المستخدم', 'الضريبة', 'المبلغ', 'السبب', 'طريقة الدفع', 'الملاحظات'];
    }

    // إضافة الإجماليات بشكل منظم أسفل الجدول
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $this->data->count() + 2;
                $sheet = $event->sheet;

                // كتابة عناوين الإجماليات
                $sheet->setCellValue('E' . ($lastRow + 1), 'مجموع الخاضع');
                $sheet->setCellValue('F' . ($lastRow + 1), $this->totals['active']);
                
                $sheet->setCellValue('E' . ($lastRow + 2), 'مجموع غير الخاضع');
                $sheet->setCellValue('F' . ($lastRow + 2), $this->totals['inactive']);
                
                $sheet->setCellValue('E' . ($lastRow + 3), 'الإجمالي العام');
                $sheet->setCellValue('F' . ($lastRow + 3), $this->totals['total']);
            },
        ];
    }
}