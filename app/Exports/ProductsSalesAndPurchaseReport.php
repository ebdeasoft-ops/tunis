<?php
namespace App\Exports;

use App\Models\Sales;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsSalesAndPurchaseReport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    // 1. الاستعلام المربوط مع جدول المشتريات بناءً على التاريخ والفرع
public function query()
{
    // 1. نبدأ من جدول التفاصيل (order_details) ونربطه بجدول فواتير المشتريات الكلي للوصول للفرع
    $query = \App\Models\OrderDetails::join('resource_purchases', 'order_details.order_owner', '=', 'resource_purchases.orderId')
        
        // 2. نقوم بعمل leftJoin مع جدول المبيعات بنفس الشروط السابقة
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
        
        // 3. الفلترة التاريخية للمشتريات بناءً على تاريخ الفاتورة الرئيسية
        ->whereDate('resource_purchases.created_at', '>=', $this->request->start_at)
        ->whereDate('resource_purchases.created_at', '<=', $this->request->end_at)
        // التأكد من أن فاتورة المشتريات محفوظة أيضاً
        ->where('resource_purchases.save', 1);

    // 4. تطبيق فلترة الفرع على المشتريات بناءً على حقل branch_id الصحيح
    if ($this->request->branch != '-') {
        $query->where('resource_purchases.branchs_id', $this->request->branch);
    }

    // 5. اختيار الحقول والتجميع لحساب المبيعات والخصومات بدون تكرار الصفوف
    return $query->select(
        'order_details.product_id',
        'order_details.product_name',
        'order_details.purchasingـprice', 
        \DB::raw('IFNULL(sales.Unit_Price, 0) as unit_price'),
        \DB::raw('SUM(IFNULL(sales.quantity, 0)) as quantity'),
        \DB::raw('SUM(IFNULL(sales.Discount_Value, 0)) as total_discount')
    )
    ->groupBy(
        'order_details.product_id', 
        'order_details.product_name', 
        'order_details.purchasingـprice',
        'sales.Unit_Price'
    );
}


    // 2. عناوين الأعمدة في ملف الإكسيل
public function headings(): array
{
    return [
        'كود المنتج',
        'اسم المنتج',
        'سعر الشراء (للحبة)',
        'سعر البيع (للحبة)',
        'الكمية المباعة',
        'إجمالي التكلفة',
        'إجمالي المبيعات (قبل الخصم)',
        'إجمالي الخصم الممنوح',
        'صافي المبيعات',
        'صافي الربح'
    ];
}

    // 3. طريقة حساب وتوزيع الحقول داخل الخلايا
public function map($row): array
{
    // 1. حساب إجمالي تكلفة المشتريات للكمية التي بيعت
    $total_cost = $row->quantity * $row->purchasingـprice;
    
    // 2. إجمالي المبيعات قبل الخصم
    $gross_sales = $row->quantity * $row->unit_price;
    
    // 3. إجمالي الخصم القادم من قاعدة البيانات
    $total_discount = $row->total_discount;
    
    // 4. صافي المبيعات بعد الخصم
    $net_sales = $gross_sales - $total_discount;
    
    // 5. صافي الربح = صافي المبيعات - إجمالي التكلفة
    $net_profit = $net_sales - $total_cost;

    return [
        $row->product_id,
        $row->product_name,
        $row->purchasingـprice,
        $row->unit_price,
        $row->quantity,
        $total_cost,
        $gross_sales,
        $total_discount,
        $net_sales,
        $net_profit
    ];
}

    // 4. تنسيق مظهر ملف الإكسيل (اختياري لجعل المظهر احترافي)
    public function styles(Worksheet $sheet)
    {
        // جعل اتجاه الشيت من اليمين لليسار ليناسب اللغة العربية
        $sheet->setRightToLeft(true);

        return [
            // تنسيق الصف الأول (العناوين) باللون الكحلي والخط العريض
            1    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F497D']
                ]
            ],
        ];
    }
}