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

    public function collection()
    {
        $products = products::where('branchs_id', auth()->user()->branchs_id)
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

        // تخزين عدد الصفوف لتنسيق السطر الأخير لاحقاً
        $this->totalRows = $data->count() + 1; // +1 بسبب وجود الهيدر

        return $data;
    }

    public function headings(): array
    {
        return ["ID", "اسم المنتج", "الباركود", "الموقع", "الكمية", "سعر الشراء", "إجمالي القيمة"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // 1. تنسيق رأس الجدول (الصف الأول)
            1    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50'] // لون أخضر للرأس
                ],
                'alignment' => ['horizontal' => 'center']
            ],

            // 2. تنسيق سطر الإجمالي (آخر صف)
            $this->totalRows => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'] // لون أصفر لسطر الإجمالي
                ],
            ],

            // 3. إضافة حدود (Borders) لكل الجدول
            'A1:G' . $this->totalRows => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'alignment' => ['horizontal' => 'right'] // محاذاة النص لليمين للعربي
            ],
        ];
    }
}