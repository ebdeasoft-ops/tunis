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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
        $salesSub = \DB::table('sales')
            ->select(
                'product_id',
                \DB::raw('MAX(Unit_Price) as unit_price'),
                \DB::raw('SUM(quantity) as quantity'),
                \DB::raw('SUM(Discount_Value) as total_discount')
            )
            ->where('save', 1);

        if (!empty($this->request->start_at)) {
            $salesSub->whereDate('created_at', '>=', $this->request->start_at);
        }
        if (!empty($this->request->end_at)) {
            $salesSub->whereDate('created_at', '<=', $this->request->end_at);
        }
        if (!empty($this->request->branch) && $this->request->branch != '-') {
            $salesSub->where('branch_id', $this->request->branch);
        }

        $salesSub->groupBy('product_id');

        $query = \App\Models\orderDetails::query()
            ->join('resource_purchases', 'order_details.order_owner', '=', 'resource_purchases.orderId')
            ->leftJoinSub($salesSub, 'sales', function($join) {
                $join->on('order_details.product_id', '=', 'sales.product_id');
            });

        if (!empty($this->request->start_at)) {
            $query->whereDate('resource_purchases.created_at', '>=', $this->request->start_at);
        }

        if (!empty($this->request->end_at)) {
            $query->whereDate('resource_purchases.created_at', '<=', $this->request->end_at);
        }

        $query->where('resource_purchases.save', 1);

        if (!empty($this->request->branch) && $this->request->branch != '-') {
            $query->where('resource_purchases.branchs_id', $this->request->branch);
        }

        return $query->select(
            'order_details.product_id',
            'order_details.product_name',
            \DB::raw('IFNULL(sales.unit_price, 0) as unit_price'),
            \DB::raw('SUM(IFNULL(order_details.numberofpice, 0) + IFNULL(order_details.returns_purchase, 0)) as purchased_quantity'),
            \DB::raw('IFNULL(sales.quantity, 0) as quantity'),
            \DB::raw('IFNULL(sales.total_discount, 0) as total_discount'),
            \DB::raw('SUM((IFNULL(order_details.numberofpice, 0) + IFNULL(order_details.returns_purchase, 0)) * IFNULL(order_details.purchasingـprice, 0)) as total_purchase_cost'),
            \DB::raw('SUM(IFNULL(order_details.returns_purchase, 0)) as returned_quantity'),
            \DB::raw('SUM(IFNULL(order_details.returns_purchase, 0) * IFNULL(order_details.purchasingـprice, 0)) as returned_value'),
            \DB::raw('GROUP_CONCAT(DISTINCT resource_purchases.orderId ORDER BY resource_purchases.orderId SEPARATOR ", ") as purchase_invoice_numbers')
        )
        ->groupBy(
            'order_details.product_id',
            'order_details.product_name',
            'sales.unit_price',
            'sales.quantity',
            'sales.total_discount'
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
            'رقم فاتورة المشتريات',
            'سعر الشراء (للحبة)',
            'سعر البيع (للحبة)',
            'الكمية المشتراة',
            'الكمية المرتجعة',
            'قيمة المرتجع',
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
        $total_cost          = $row->total_purchase_cost;
        $avg_purchase_price = $row->purchased_quantity > 0 ? $total_cost / $row->purchased_quantity : 0;
        $gross_sales         = $row->quantity * $row->unit_price;
        $total_discount      = $row->total_discount;
        $net_sales           = $gross_sales - $total_discount;
        $net_profit          = $net_sales - $total_cost;

        return [
            $row->productData->Product_Code ?? '-',
            $row->product_name,
            $row->purchase_invoice_numbers,
            $avg_purchase_price,
            $row->unit_price,
            $row->purchased_quantity,
            $row->returned_quantity,
            $row->returned_value,
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
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '203764'] // كحلي ملكي فخم للهيدر
                ]
            ],
        ];
    }

    // 5. الأحداث والتنسيقات الجمالية المتقدمة
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // إدراج صف تعريفي علوي للعنوان (Dashboard Title Banner)
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells('A1:N1');
                $sheet->setCellValue('A1', '📊 تقرير حركة ومبيعات ومشتريات المنتجات الشامل');
                
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F497D']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                $sheet->getRowDimension(1)->setRowHeight(35);
                $sheet->getRowDimension(2)->setRowHeight(15); // صف فارغ فاصل

                $highestRow = $sheet->getHighestRow();
                $highestColumn = 'N'; 
                $returnValueColumn = 'H'; 

                // مجاميع الأعمدة الرقمية (F إلى N) - تبدأ الصفوف الآن من 3 للعناوين و 4 للبيانات
                $totals = [
                    'F' => 0, 'G' => 0, 'H' => 0, 'I' => 0, 
                    'J' => 0, 'K' => 0, 'L' => 0, 'M' => 0, 'N' => 0
                ];

                // نبدأ من الصف 4 (بعد الهيدر الجديد) إلى نهاية البيانات
                for ($row = 4; $row <= $highestRow; $row++) {
                    $returnedQuantity = $sheet->getCell("G{$row}")->getValue();
                    $soldQuantity = $sheet->getCell("I{$row}")->getValue();

                    foreach ($totals as $col => $value) {
                        $totals[$col] += (float) $sheet->getCell("{$col}{$row}")->getValue();
                    }

                    // تلوين وتصميم الصفوف بناءً على حالتها (تنسيق شرطي فخم وهادئ)
                    if ($returnedQuantity > 0) {
                        // منتج به مرتجعات (أخضر زاهي مريح للأعين)
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2EFDA']],
                            'font' => ['color' => ['rgb' => '375623']]
                        ]);
                    } elseif ($soldQuantity == 0 || $soldQuantity === null) {
                        // منتج راكد بلا مبيعات (أحمر خفيف هادئ)
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FCE4D6']],
                            'font' => ['color' => ['rgb' => 'C65911']]
                        ]);
                    } else {
                        // صفوف عادية بتخطيط تفاعلي Zebra Striping (تبادل ألوان خفيف جداً)
                        if ($row % 2 == 0) {
                            $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]
                            ]);
                        }
                    }
                    
                    // ضبط ارتفاع الصفوف لتبدو مريحة للقراءة
                    $sheet->getRowDimension($row)->setRowHeight(22);
                }

                // إضافة صف الإجمالي أسفل الجدول
                $totalRow = $highestRow + 1;
                $sheet->setCellValue("A{$totalRow}", 'إجمالي الحسابات');
                $sheet->mergeCells("A{$totalRow}:E{$totalRow}");

                foreach ($totals as $col => $value) {
                    $sheet->setCellValue("{$col}{$totalRow}", $value);
                }

                // تصميم فخم لصف الإجماليات (لون داكن مميز مع خط عريض)
                $sheet->getStyle("A{$totalRow}:{$highestColumn}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '16365C']
                    ],
                ]);
                $sheet->getRowDimension($totalRow)->setRowHeight(28);

                // تمييز عمود "قيمة المرتجع" بلون برتقالي مميز ليظهر بوضوح
                $sheet->getStyle("{$returnValueColumn}4:{$returnValueColumn}{$highestRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8CBAD']],
                    'font' => ['color' => ['rgb' => '833C00'], 'bold' => true]
                ]);
                
                $sheet->getStyle("{$returnValueColumn}{$totalRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C65911']],
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]
                ]);

                // ضبط الحدود (Borders) لكل جدول البيانات بلمسة احترافية
                $sheet->getStyle("A3:{$highestColumn}{$totalRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '203764'],
                        ]
                    ],
                ]);

                // المحاذاة (توسيط كامل)
                $sheet->getStyle("A3:{$highestColumn}{$totalRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // تنسيق الأرقام والعملات لتبدو احترافية (تجنب الأرقام العشوائية)
                $sheet->getStyle("D4:N{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                // ملاحظة توضيحية أسفل التقرير
                $noteRow = $totalRow + 2;
                $sheet->setCellValue("A{$noteRow}", '💡 ملاحظة: جميع المبالغ الموضحة في هذا التقرير محسوبة قبل احتساب ضريبة القيمة المضافة.');
                $sheet->mergeCells("A{$noteRow}:{$highestColumn}{$noteRow}");
                $sheet->getStyle("A{$noteRow}")->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '7F7F7F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                ]);
            },
        ];
    }
}