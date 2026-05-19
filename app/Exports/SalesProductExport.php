<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesProductExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        $data = [];
        $totalQty = 0;
        $totalAmount = 0;

        foreach ($this->products as $item) {
            $qty = (float)$item->total_quantity;
            $amount = (float)$item->total_sales_amount;
            
            $data[] = [
                'code' => $item->productData->Product_Code ?? '',
                'name' => $item->productData->product_name ?? '',
                'quantity' => $qty,
                'total_price' => $amount,
            ];

            $totalQty += $qty;
            $totalAmount += $amount;
        }

        // إضافة سطر الإجمالي في النهاية
        $data[] = [
            'code' => '',
            'name' => 'الإجمالي العام',
            'quantity' => $totalQty,
            'total_price' => $totalAmount,
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'كود المنتج',
            'اسم المنتج',
            'إجمالي الكمية المباعة',
            'إجمالي قيمة المبيعات',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // تنسيق العناوين
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCE5FF');

        // تنسيق آخر صف (الإجمالي)
        $lastRow = count($this->products) + 2; // +1 للعناوين و +1 لسطر الإجمالي
        $sheet->getStyle("A{$lastRow}:D{$lastRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$lastRow}:D{$lastRow}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFA9A9A9');
    }
}