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
        // 1. استعلام فرعي للمبيعات (لحساب إجمالي المبيعات، متوسط سعر البيع، وإجمالي الخصومات لكل منتج)
        $salesSub = \DB::table('sales')
            ->select(
                'product_id',
                \DB::raw('MAX(Unit_Price) as unit_price'),
                \DB::raw('SUM(quantity) as quantity'),
                \DB::raw('SUM(Discount_Value) as total_discount')
            )
            ->where('save', 1);

        // تطبيق شروط الفترة والفرع على المبيعات إذا رغبت في أن تكون المبيعات لنفس الفترة
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

        // 2. الاستعلام الأساسي (يبدأ من المشتريات لضمان ظهور كل المنتجات المشتراة، مع ربطها بالمبيعات)
        $query = \App\Models\orderDetails::query()
            ->join('resource_purchases', 'order_details.order_owner', '=', 'resource_purchases.orderId')
            ->leftJoinSub($salesSub, 'sales', function($join) {
                $join->on('order_details.product_id', '=', 'sales.product_id');
            });

        // فلترة تاريخ المشتريات الأساسي
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
        $total_cost      = $row->total_purchase_cost;
        $avg_purchase_price = $row->purchased_quantity > 0 ? $total_cost / $row->purchased_quantity : 0;
        $gross_sales     = $row->quantity * $row->unit_price;
        $total_discount  = $row->total_discount;
        $net_sales       = $gross_sales - $total_discount;
        $net_profit      = $net_sales - $total_cost;

        return [
            $row->productData->Product_Code,
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
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F497D']
                ]
            ],
        ];
    }

    // 5. فحص الصفوف، تلوين التي لم تقم بمبيعات، إضافة صف الإجمالي، وضبط حدود الجدول
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = 'N'; // آخر عمود في التقرير (بعد إضافة عمود قيمة المرتجع)
                $returnValueColumn = 'H'; // عمود قيمة المرتجع

                // مجاميع الأعمدة الرقمية (F إلى N)
                $totals = [
                    'F' => 0, // الكمية المشتراة
                    'G' => 0, // الكمية المرتجعة
                    'H' => 0, // قيمة المرتجع
                    'I' => 0, // الكمية المباعة
                    'J' => 0, // إجمالي التكلفة
                    'K' => 0, // إجمالي المبيعات
                    'L' => 0, // إجمالي الخصم
                    'M' => 0, // صافي المبيعات
                    'N' => 0, // صافي الربح
                ];

                // نبدأ من الصف الثاني لأن الصف الأول هو الهيدر (العناوين)
                for ($row = 2; $row <= $highestRow; $row++) {
                    // عمود الكمية المرتجعة G، وقيمة المرتجع H، والكمية المباعة بقت I
                    $returnedQuantity = $sheet->getCell("G{$row}")->getValue();
                    $soldQuantity = $sheet->getCell("I{$row}")->getValue();

                    // تجميع القيم الرقمية لكل عمود
                    foreach ($totals as $col => $value) {
                        $totals[$col] += (float) $sheet->getCell("{$col}{$row}")->getValue();
                    }

                    if ($returnedQuantity > 0) {
                        // يوجد مرتجع مشتريات لهذا المنتج - تلوين أخضر
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D4EDDA'] // أخضر فاتح هادئ
                            ],
                            'font' => [
                                'color' => ['rgb' => '155724'] // نص أخضر داكن وواضح
                            ]
                        ]);
                    } elseif ($soldQuantity == 0 || $soldQuantity === null) {
                        // لا توجد مبيعات لهذا المنتج - تلوين أحمر
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
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

                // إضافة صف الإجمالي أسفل الجدول
                $totalRow = $highestRow + 1;
                $sheet->setCellValue("A{$totalRow}", 'الإجمالي');
                $sheet->mergeCells("A{$totalRow}:E{$totalRow}");

                foreach ($totals as $col => $value) {
                    $sheet->setCellValue("{$col}{$totalRow}", $value);
                }

                $sheet->getStyle("A{$totalRow}:{$highestColumn}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F497D']
                    ],
                ]);

                // تمييز عمود "قيمة المرتجع" بالكامل بلون برتقالي مختلف عن باقي الجدول
                // (يُطبَّق بعد تلوين الصفوف وصف الإجمالي حتى يظل ظاهرًا فوقهم في هذا العمود فقط)
                $sheet->getStyle("{$returnValueColumn}2:{$returnValueColumn}{$highestRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FCE4D6'] // برتقالي فاتح مميز
                    ],
                    'font' => [
                        'color' => ['rgb' => '833C00'] // نص برتقالي داكن للوضوح
                    ]
                ]);
                $sheet->getStyle("{$returnValueColumn}{$totalRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'C65911'] // برتقالي أغمق لخلية الإجمالي
                    ],
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]
                ]);

                // ضبط حدود الجدول بالكامل (شامل الهيدر وصف الإجمالي)
                $sheet->getStyle("A1:{$highestColumn}{$totalRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // توسيط رأسي وأفقي لكل الخلايا
                $sheet->getStyle("A1:{$highestColumn}{$totalRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ملاحظة توضيحية أسفل الجدول: كل المبالغ الظاهرة قبل الضريبة
                $noteRow = $totalRow + 1;
                $sheet->setCellValue("A{$noteRow}", '* جميع المبالغ الموضحة في هذا التقرير قبل الضريبة (غير شامل الضريبة).');
                $sheet->mergeCells("A{$noteRow}:{$highestColumn}{$noteRow}");
                $sheet->getStyle("A{$noteRow}")->applyFromArray([
                    'font' => ['italic' => true, 'bold' => true, 'color' => ['rgb' => '842029']],
                ]);
                $sheet->getStyle("A{$noteRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}