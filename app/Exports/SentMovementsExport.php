<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SentMovementsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $movements;

    public function __construct($movements)
    {
        $this->movements = $movements;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->movements as $movement) {
            // 1. صف الحركة الرئيسي (يحتوي على الفرع المرسل والفرع المستلم وإجمالي التكلفة)
            $rows->push([
                'type'          => 'movement',
                'movement_id'   => $movement->id,
                'branch_from'   => optional($movement->branchfrom)->name ?? '---', // الفرع المرسل
                'branch_to'     => optional($movement->branchto)->name ?? '---',   // الفرع المستلم
                'user_from'     => optional($movement->userfrom)->name ?? '---',
                'status'        => $movement->status,
                'created_at'    => $movement->created_at ? $movement->created_at->format('Y-m-d H:i:s') : '---',
                'product_name'  => 'إجمالي تكلـفة الحركة الكلية:',
                'quantity'      => '',
                'unit_price'    => '',
                'total_price'   => $movement->Totalcost ?? $movement->items->sum(function($item) {
                    return $item->quantity * $item->cost_per_each_withoud_tax;
                }),
            ]);

            // 2. صفوف المنتجات التابعة للحركة
            if ($movement->items && $movement->items->count() > 0) {
                foreach ($movement->items as $item) {
                    $rows->push([
                        'type'          => 'item',
                        'movement_id'   => '',
                        'branch_from'   => '',
                        'branch_to'     => '',
                        'user_from'     => '',
                        'status'        => '',
                        'created_at'    => '',
                        'product_name'  => '   ↳ ' . (optional($item->product)->product_name ?? 'منتج غير محدد'),
                        'quantity'      => $item->quantity,
                        'unit_price'    => $item->cost_per_each_withoud_tax,
                        'total_price'   => $item->quantity * $item->cost_per_each_withoud_tax,
                    ]);
                }
            }
            
            // سطر فارغ فاصل بين الحركات
            $rows->push([
                'type'          => 'spacer',
                'movement_id'   => '',
                'branch_from'   => '',
                'branch_to'     => '',
                'user_from'     => '',
                'status'        => '',
                'created_at'    => '',
                'product_name'  => '',
                'quantity'      => '',
                'unit_price'    => '',
                'total_price'   => '',
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'رقم الحركة',
            'الفرع المرسل',
            'الفرع المرسل إليه',
            'المستخدم المرسل',
            'الحالة',
            'تاريخ الإنشاء',
            'بيان المنتج / الحركة',
            'الكمية',
            'سعر القطعة',
            'الإجمالي',
        ];
    }

    public function map($row): array
    {
        return [
            $row['movement_id'],
            $row['branch_from'],
            $row['branch_to'],
            $row['user_from'],
            $row['status'],
            $row['created_at'],
            $row['product_name'],
            $row['quantity'] !== '' ? $row['quantity'] : '',
            is_numeric($row['unit_price']) ? number_format($row['unit_price'], 2) : $row['unit_price'],
            is_numeric($row['total_price']) ? number_format($row['total_price'], 2) : $row['total_price'],
        ];
    }

    // دالة تنسيق الألوان والشكل الاحترافي للصفوف
    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()->getActiveSheet()->setRightToLeft(true);

        $styles = [
            // 1. تنسيق رأس الجدول (Header) باللون الأزرق الداكن وخط أبيض عريض
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '1F4E78']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ],
        ];

        $collection = $this->collection();
        $rowIndex = 2; 

        foreach ($collection as $row) {
            if ($row['type'] === 'movement') {
                // تلوين صف الحركة الرئيسي بالأزرق الفاتح المميز
                $styles[$rowIndex] = [
                    'font' => ['bold' => true, 'color' => ['argb' => '000000']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'D9E1F2']
                    ],
                ];
            } elseif ($row['type'] === 'item') {
                // تلوين صفوف المنتجات بخلفية بيضاء هادئة
                $styles[$rowIndex] = [
                    'font' => ['italic' => true, 'color' => ['argb' => '333333']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'F9FBFD']
                    ],
                ];
            }
            $rowIndex++;
        }

        return $styles;
    }
}