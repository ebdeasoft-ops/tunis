<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StockTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    // رؤوس الأعمدة المطلوبة
    public function headings(): array
    {
        return [
            'اسم المنتج (Product Name)', 
            'كود المنتج (Product Code)', 
            'المخزون / الكمية (Quantity)', 
            'سعر التكلفة (Purchasing Price)', 
            'سعر البيع (Sale Price)'
        ];
    }

    // صف تجريبي توضيحي لمنع ظهور الملف فارغاً ولتوضيح طريقة الادخال
    public function array(): array
    {
        return [
            [
                'منتج تجريبي 1', 
                'PRD-1001', 
                50, 
                100, 
                150
            ]
        ];
    }

    // تنسيق صف العناوين وإتجاه الشيت (RTL)
    public function styles(Worksheet $sheet)
    {
        // جعل اتجاه الورقة من اليمين لليسار
        $sheet->setRightToLeft(true);

        // ضبط ارتفاع الصف الأول
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['argb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['argb' => '4F46E5']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }
}