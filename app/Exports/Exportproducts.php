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
    // اسم عمود المجموعة في جدول المنتجات (عدّله إن كان مختلفاً في قاعدة البيانات)
    const GROUP_COLUMN = 'product_group';

    private $totalRows = 2;
    private $branchId;
    private $groupId;
    private $search;
    private $location;

    public function __construct($branchId, $groupId = null, $search = null, $location = null)
    {
        $this->branchId = $branchId;
        $this->groupId  = $groupId;
        $this->search   = $search;
        $this->location = $location;
    }

    public function collection()
    {
        $query = products::where('numberofpice', '>', 0);

        // الفرع: "-" أو فارغ = كل الفروع
        if (!empty($this->branchId) && $this->branchId !== '-') {
            $query->where('branchs_id', $this->branchId);
        }

        // المجموعة
        if (!empty($this->groupId) && $this->groupId !== '-') {
            $query->where(self::GROUP_COLUMN, $this->groupId);
        }

        // البحث بالاسم أو رقم المنتج
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%')
                  ->orWhere('Product_Code', 'like', '%' . $search . '%');
            });
        }

        // الموقع
        if (!empty($this->location)) {
            $query->where('Product_Location', 'like', '%' . $this->location . '%');
        }

        $products = $query->get(['id', 'product_name', 'Product_Code', 'Product_Location', 'numberofpice', 'purchasingـprice']);

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
                $rowTotal,
            ];
        });

        // سطر الإجمالي
        $data->push([
            '', '', '', 'الاجمالي الكلي للمخزون المتوفر', '', '', $grandTotal,
        ]);

        // +1 لصف العناوين
        $this->totalRows = $data->count() + 1;

        return $data;
    }

    public function headings(): array
    {
        return ["ID", "اسم المنتج", "الباركود", "الموقع", "الكمية", "سعر الشراء", "إجمالي القيمة"];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],

            $this->totalRows => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
            ],

            'A1:G' . $this->totalRows => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'alignment' => ['horizontal' => 'right'],
            ],
        ];
    }
}