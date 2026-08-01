<?php

namespace App\Exports;

use App\Models\Sales;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsSalesAndPurchaseReport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    // 1. الاستعلام لاستخراج بيانات المشتريات والمبيعات
    public function query()
    {
        $query = \App\Models\orderDetails::join('resource_purchases', 'order_details.order_owner', '=', 'resource_purchases.orderId')
            
            ->leftJoin('sales', function($join) {
                $join->on('order_details.product_id', '=', 'sales.product_id')
                     ->where('sales.save', '=', 1);
                     
                if ($this->request->start_at) {
                    $join->whereDate('sales.created_at', '>=', $this->request->start_at);
                }
                if ($this->request->end_at) {
                    $join->whereDate('sales.created_at', '<=', $this->request->end_at);
                }
                if ($this->request->branch != '-') {
                    $join->where('sales.branch_id', '=', $this->request->branch);
                }
            })
            
            ->whereDate('resource_purchases.created_at', '>=', $this->request->start_at)
            ->whereDate('resource_purchases.created_at', '<=', $this->request->end_at)
            ->where('resource_purchases.save', 1);

        if ($this->request->branch != '-') {
            $query->where('resource_purchases.branchs_id', $this->request->branch);
        }

        return $query->select(
            'order_details.product_id',
            'order_details.product_name',
            'order_details.purchasingـprice', 
            \DB::raw('MAX(IFNULL(sales.Unit_Price, 0)) as unit_price'),
            \DB::raw('SUM(IFNULL(order_details.numberofpice, 0)) as purchased_quantity'), // كمية المشتريات من العمود الصحيح
            \DB::raw('SUM(IFNULL(sales.quantity, 0)) as quantity'),                  // الكمية المباعة
            \DB::raw('SUM(IFNULL(sales.Discount_Value, 0)) as total_discount')
        )
        ->groupBy(
            'order_details.product_id', 
            'order_details.product_name', 
            'order_details.purchasingـprice'
        )
        ->orderBy(
            \DB::raw('MIN(order_details.id)'), 'ASC'
        );
    }

    // 2. عناوين الأعمدة
    public function headings(): array
    {
        return [
            'كود المنتج',
            'اسم المنتج',
            'سعر الشراء (للحبة)',
            'سعر البيع (للحبة)',
            'الكمية المشتراة',
            'الكمية المباعة',
            'إجمالي التكلفة',
            'إجمالي المبيعات (قبل الخصم)',
            'إجمالي الخصم الممنوح',
            'صافي المبيعات',
            'صافي الربح'
        ];
    }

    // 3. تنسيق البيانات داخل السطور
    public function map($row): array
    {
        $total_cost = $row->quantity * $row->purchasingـprice;
        $gross_sales = $row->quantity * $row->unit_price;
        $total_discount = $row->total_discount;
        $net_sales = $gross_sales - $total_discount;
        $net_profit = $net_sales - $total_cost;

        return [
            $row->product_id,
            $row->product_name,
            $row->purchasingـprice,
            $row->unit_price,
            $row->purchased_quantity,
            $row->quantity,
            $total_cost,
            $gross_sales,
            $total_discount,
            $net_sales,
            $net_profit
        ];
    }

    // 4. تنسيق الهيدر الأساسي
    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F497D']
                ]
            ],
        ];
    }

    // 5. فحص الصفوف وتلوين التي لم تقم بمبيعات باللون الأحمر
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // نبدأ من الصف الثاني لأن الصف الأول هو الهيدر (العناوين)
                for ($row = 2; $row <= $highestRow; $row++) {
                    // عمود الكمية المباعة هو العمود السادس (F)
                    $soldQuantity = $sheet->getCell("F{$row}")->getValue();

                    // إذا كانت الكمية المباعة 0 أو فارغة، يتم تلوين الصف بالكامل باللون الأحمر
                    if ($soldQuantity == 0 || $soldQuantity === null) {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8D7DA'] // أحمر فاتح هادئ
                            ],
                            'font' => [
                                'color' => ['rgb' => '721C24'] // نص أحمر داكن وواضح
                            ]
                        ]);
                    }
                }
            },
        ];
    }
}