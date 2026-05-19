<?php

namespace App\Http\Controllers;

use App\Models\products_mix;
use App\Models\products;
use App\Models\products_mix_items;
use App\Models\Avt;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Carbon\Carbon;

class ProductsMixController extends Controller
{
    public function __construct()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    }

    /**
     * جلب تفاصيل المنتج المجمع بناءً على الكود
     */
    public function getmixproduct($id)
    {
        $products_mix = products_mix::where('mixcode', $id)->firstOrFail();
        
        return $this->formatMixItems($products_mix);
    }

    /**
     * إضافة عنصر جديد للمنتج المجمع أو إنشاء منتج مجمع جديد
     */
    public function store(Request $request)
    {
        $avt = Avt::find(2);
        $orderid = $request->orderNo;

        // إذا لم يكن هناك رقم طلب، نقوم بإنشاء المنتج المجمع والمنتج المرتبط به لأول مرة
        if (json_decode($orderid) == null) {
            $createorder = products_mix::create([
                'user_id'    => auth()->id(),
                'created_at' => Carbon::today(),
                'name'       => $request->mixproductname,
                'branchs_id' => auth()->user()->branchs_id 
            ]);

            $orderid = $createorder->id;
            $mixCode = 'M001' . $orderid;

            $createorder->update(['mixcode' => $mixCode]);

            products::create([
                'product_name'                => $request->mixproductname,
                'name_en'                     => $request->mixproductname,
                'branchs_id'                  => $createorder->branchs_id,
                'user_id'                     => auth()->id(),   
                'Product_Location'            => "MIX",
                'Product_Code'                => $mixCode,
                'Status'                      => 1,
                'notes'                       => "-",
                'minmum_quantity_stock_alart' => 2,
                'products_mix'                => $orderid,
            ]);
        }

        // جلب الموديل مرة واحدة للتعامل معه
        $products_mix = products_mix::findOrFail($orderid);

        // حساب التكلفة الجديدة بدون ضريبة
        $newCost = $request->mixproduct_cost == 0 
            ? $products_mix->cost_withoud_tax + ($request->quentityprice * $request->quentity) 
            : $request->mixproduct_cost;

        $products_mix->update(['cost_withoud_tax' => $newCost]);

        // تحديث سعر الشراء في جدول المنتجات الأساسي
        products::where('products_mix', $orderid)->update([
            'purchasingـprice' => $newCost
        ]);

        // إضافة العنصر الجديد في تفاصيل المكس
        products_mix_items::create([
            'created_at'      => Carbon::today(),
            'note'            => 'note',
            'product_id'      => $request->productNo,
            'cost'            => $request->quentityprice,
            'Added_Value'     => $avt->AVT * $request->quentityprice,
            'quantity'        => $request->quentity,
            'products_mix_id' => $orderid,
        ]);

        return $this->formatMixItems($products_mix);
    }

    /**
     * زيادة كمية عنصر داخل المكس
     */
    public function updateproduct_mix_Increase(Request $request)
    {
        $item = products_mix_items::findOrFail($request->id);
        $item->increment('quantity', $request->increasequentity);

        $products_mix = products_mix::findOrFail($request->ordernumber);
        $addedCost = $request->increasequentity * $item->cost;
        
        $products_mix->increment('cost_withoud_tax', $addedCost);

        products::where('products_mix', $products_mix->id)->update([
            'purchasingـprice' => $products_mix->cost_withoud_tax
        ]);

        return $this->formatMixItems($products_mix);
    }

    /**
     * تقليل كمية عنصر داخل المكس
     */
    public function updateproduct_mix_decrease(Request $request)
    {
        $item = products_mix_items::findOrFail($request->id);
        $item->decrement('quantity', $request->return_quentity);

        $products_mix = products_mix::findOrFail($request->ordernumber);
        $deductedCost = $request->return_quentity * $item->cost;

        $products_mix->decrement('cost_withoud_tax', $deductedCost);

        products::where('products_mix', $products_mix->id)->update([
            'purchasingـprice' => $products_mix->cost_withoud_tax
        ]);

        return $this->formatMixItems($products_mix);
    }

    /**
     * دالة مساعدة موحدة لمنع التكرار وحساب المجاميع (Eager Loading للـ productData)
     */
    private function formatMixItems(products_mix $products_mix)
    {
        // استخدام with('productData') لمنع الـ N+1 Query تماماً وسرعة تحميل المنتجات
        $orderdetails = products_mix_items::with('productData')
            ->where('products_mix_id', $products_mix->id)
            ->get();

        $ListProducts = [];
        $totalAdded_value = 0;
        $totalPrice = 0;
        $count = 0;

        foreach ($orderdetails as $orderitem) {
            $totalAdded_value += $orderitem->quantity * $orderitem->Added_Value;
            $totalPrice += $orderitem->quantity * $orderitem->cost;
            $count++;

            $ListProducts[] = [
                "count"             => $count,
                "productCode"       => $orderitem->productData->Product_Code ?? '',
                "product_name"      => $orderitem->productData->product_name ?? '',
                "product_id"        => $orderitem->id,
                "quantity"          => $orderitem->quantity,
                "purchasingـprice"  => $orderitem->cost,
                "Added_Value"       => $orderitem->Added_Value,
                "total"             => ($orderitem->quantity * $orderitem->Added_Value) + ($orderitem->quantity * $orderitem->cost),
                "orderNo"           => $orderitem->products_mix_id,
                "totalAdded_Value"  => $totalAdded_value,
                "totalPrice"        => $totalPrice,
                'productcode_mix'   => $products_mix->mixcode,
                'product_name_mix'  => $products_mix->name,
            ];
        }

        return $ListProducts;
    }
}