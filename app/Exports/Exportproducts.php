<?php

namespace App\Exports;

use App\Models\products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Exportproducts implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    private $totalRows;
    private $branchId; // المتغير لاستقبال رقم الفرع

    // المُنشئ لاستقبال رقم الفرع عند استدعاء الكلاس
    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    public function collection()
    {
        // استخدام رقم الفرع الممرر للكلاس
        $products = products::where('branchs_id', $this->branchId)
            ->where('numberofpice', '>', 0)
            ->get(['id', 'product_name', 'Product_Code', 'Product_Location', 'numberofpice', 'purchasingـprice']);

        $grandTotal = 0;
        
        $data = $products->map(function ($item) use (&$grandTotal) {
            $rowTotal = $item->numberofpice * $item->purchasingـprice;
            $grandTotal += $rowTotal;

            return [
                $item->id,
                $item->product_name,
                $item->Product_Code,
                $item->Product_Location,
                $item->numberofpice,
                $item->purchasingـprice,
                $rowTotal 
            ];
        });

        // إضافة سطر الإجمالي
        $data->push([
            '', '', '', 'الاجمالي الكلي للمخزون المتوفر', '', '', $grandTotal
        ]);

        // تخزين عدد الصفوف لتنسيق السطر الأخير
        $this->totalRows = $data->count() + 1; 

        return $data;
    }

    public function headings(): array
    {
        return ["ID", "اسم المنتج", "الباركود", "الموقع", "الكمية", "سعر الشراء", "إجمالي القيمة"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // 1. تنسيق رأس الجدول
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50']
                ],
                'alignment' => ['horizontal' => 'center']
            ],

            // 2. تنسيق سطر الإجمالي
            $this->totalRows => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00']
                ],
            ],

            // 3. إضافة حدود وتنسيق المحاذاة
            'A1:G' . $this->totalRows => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'alignment' => ['horizontal' => 'right']
            ],
        ];
    }
}