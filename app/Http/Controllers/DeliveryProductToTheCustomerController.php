<?php

namespace App\Http\Controllers;

use App\Models\Delivery_product_to_the_customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product_movement_another_branch;
use App\Models\product_movement_another_branch_items;
use App\Models\products;
use App\Models\Avt;
use App\Models\products_mix_items;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DeliveryProductToTheCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('products.confirm_delivery_another_branch', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentBranchId = auth()->user()->branchs_id;
        
        $Delivery_product_to_the_customer = Delivery_product_to_the_customer::where('invoice_id', $request->invoice_no)
            ->where('status', 0)
            ->where('branch_to', $currentBranchId)
            ->get();

        if ($Delivery_product_to_the_customer->isEmpty()) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' 
                ? 'لم يتم العثور علي فاتورة بهذا الرقم تابعة لفرعكم' 
                : 'No invoice with this number was found for your branch';

            session()->flash('notfountreturnproduct', $message);
            $data = [];
            return view('products.confirm_delivery_another_branch', compact('data'));
        }

        session()->flash('foundinvoice', 'تم العثور علي فاتورة');

        $datamain = [
            'branch_from' => $Delivery_product_to_the_customer[0]->branchfrom->name ?? '',
            'branch_to'   => $Delivery_product_to_the_customer[0]->branchto->name ?? '',
            'user_from'   => $Delivery_product_to_the_customer[0]->userfrom->name ?? '',
            'invoice_id'  => $Delivery_product_to_the_customer[0]->invoice_id,
        ];

        $data = [
            'products' => $Delivery_product_to_the_customer,
            'datamain' => $datamain
        ];

        return view('products.confirm_delivery_another_branch', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $currentBranchId = auth()->user()->branchs_id;
        $currentUserId = auth()->id();
        $currentTime = Carbon::now()->addHours(3);

        // 1. جلب البيانات المعلقة أولاً للتأكد من وجودها قبل التحديث
        $deliveryItems = Delivery_product_to_the_customer::where('invoice_id', $request->invoice_no)
            ->where('status', 0)
            ->where('branch_to', $currentBranchId)
            ->get();

        if ($deliveryItems->isEmpty()) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' 
                ? 'عذراً، لم يتم العثور على الفاتورة أو تم معالجتها مسبقاً' 
                : 'Sorry, invoice not found or already processed';

            session()->flash('notfountreturnproduct', $message);
            $data = [];
            return view('products.confirm_delivery_another_branch', compact('data'));
        }

        try {
            // استخدام الترانزأكشن لحماية حركات المخزون والخلطات (Mix) من التداخل أو الفشل الجزئي
            DB::transaction(function () use ($deliveryItems, $currentBranchId, $currentUserId, $currentTime, $request) {
                
                // تحديث حالة عناصر الفاتورة الأصلية إلى تم التسليم
                Delivery_product_to_the_customer::where('invoice_id', $request->invoice_no)
                    ->where('status', 0)
                    ->where('branch_to', $currentBranchId)
                    ->update(['status' => 1]);

                // حساب إجمالي تكلفة المنتجات بناءً على أسعار الشراء الحالية لتسجيلها في رأس الفاتورة
                $totalCost = 0;
                foreach ($deliveryItems as $item) {
                    $productData = products::find($item->product_id);
                    if ($productData) {
                        $totalCost += ($productData->purchasingـprice * $item->quantity);
                    }
                }

                // 2. إنشاء رأس حركة المخزون (Master Record) مرة واحدة فقط خارج الحلقة
                $movement = product_movement_another_branch::create([
                    'branch_from'         => $currentBranchId,
                    'branch_to'           => $deliveryItems[0]->branch_from,
                    'user_from'           => $currentUserId,
                    'reciveInvoiceNumber' => 10, // يمكن ربطها ديناميكياً لاحقاً
                    'user_to'             => $deliveryItems[0]->user_from,
                    'Totalcost'           => $totalCost,
                    'created_at'          => $currentTime,
                ]);

                // 3. تدوير العناصر لخصم المخزون وإنشاء تفاصيل الحركة (Items)
                foreach ($deliveryItems as $product) {
                    $productdata = products::find($product->product_id);
                    if (!$productdata) continue;

                    // إدخال تفاصيل حركة الصنف
                    product_movement_another_branch_items::create([
                        'order_id'                  => $movement->id,
                        'product_id'                => $product->product_id,
                        'quantity'                  => $product->quantity,
                        'cost_per_each_withoud_tax' => $productdata->purchasingـprice,
                        'created_at'                => $currentTime,
                        'updated_at'                => $currentTime,
                    ]);

                    // 4. معالجة خصم كميات المخزون (منتج عادي أم منتج مركب/مزيج)
                    if ($productdata->products_mix == 0) {
                        // صنف عادي: خصم مباشر من جدول المنتجات
                        products::where('id', $product->product_id)->decrement('numberofpice', $product->quantity);
                    } else {
                        // صنف مركب (Mix): جلب مكونات المزيج وخصم كمياتها التناسبية
                        $mixItems = products_mix_items::where('products_mix_id', $productdata->products_mix)->get();
                        
                        foreach ($mixItems as $itemmix) {
                            // استخدام دالة decrement المباشرة والسريعة بدلاً من البحث ثم التعديل لحماية أداء قاعدة البيانات
                            products::where('id', $itemmix->product_id)->decrement('numberofpice', $itemmix->quantity * $product->quantity);
                        }
                    }
                }
            });

            $message = LaravelLocalization::getCurrentLocale() == 'ar' 
                ? 'تم تسجيل عملية التسليم وتحديث المخزون بنجاح' 
                : 'Delivery registered and inventory updated successfully';

            session()->flash('foundinvoice', $message);

        } catch (\Exception $e) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' 
                ? 'حدث خطأ أثناء معالجة المخزون: ' . $e->getMessage()
                : 'An error occurred while updating stock: ' . $e->getMessage();

            session()->flash('notfountreturnproduct', $message);
        }

        $data = [];
        return view('products.confirm_delivery_another_branch', compact('data'));
    }
}