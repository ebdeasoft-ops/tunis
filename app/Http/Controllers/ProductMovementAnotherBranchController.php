<?php

namespace App\Http\Controllers;

use App\Models\product_movement_another_branch;
use App\Models\product_movement_another_branch_items;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Carbon\Carbon;

class ProductMovementAnotherBranchController extends Controller
{
    public function __construct()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    }

    public function index()
    {
        return view('supProcesses.send_product_to_branch');
    }

    /**
     * إنشاء أو تحديث أمر تحويل منتجات إلى فرع آخر (صرف من الفرع الحالي)
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'product_no' => 'required|exists:products,id',
            'branch' => 'required',
            'quentity' => 'required|numeric|min:0.01',
            'thecostProduct' => 'required|numeric',
            'order_id' => 'required|numeric'
        ]);

        $data = DB::transaction(function () use ($request) {
            $product = products::lockForUpdate()->findOrFail($request->product_no);
            $now = Carbon::now(); // يتم ضبط Timezone في config/app.php كـ Asia/Riyadh

            $orderId = $request->order_id;

            if ($orderId == 0) {
                // إنشاء فاتورة تحويل جديدة
                $movement = product_movement_another_branch::create([
                    'branch_from' => Auth::user()->branchs_id,
                    'branch_to' => $request->branch,
                    'user_from' => Auth::id(),
                    'reciveInvoiceNumber' => $request->reciveInvoiceNumber,
                    'user_to' => $request->userfrom,
                    'Totalcost' => $request->thecostProduct * $request->quentity,
                    'cost_withod_tax' => $product->purchasingـprice * $request->quentity,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $orderId = $movement->id;
            } else {
                // تحديث فاتورة تحويل قائمة (تجنب التكرار إذا كان المنتج مضافاً مسبقاً)
                $exists = product_movement_another_branch_items::where('product_id', $request->product_no)
                    ->where('order_id', $orderId)
                    ->exists();

                if (!$exists) {
                    $movement = product_movement_another_branch::lockForUpdate()->findOrFail($orderId);
                    $movement->update([
                        'cost_withod_tax' => $movement->cost_withod_tax + ($product->purchasingـprice * $request->quentity),
                        'Totalcost' => $movement->Totalcost + ($request->thecostProduct * $request->quentity),
                        'updated_at' => $now,
                    ]);
                }
            }

            // إضافة العنصر للفاتورة إن لم يكن موجوداً
            $itemExists = product_movement_another_branch_items::where('product_id', $request->product_no)
                ->where('order_id', $orderId)
                ->first();

            if (!$itemExists) {
                product_movement_another_branch_items::create([
                    'order_id' => $orderId,
                    'product_id' => $request->product_no,
                    'quantity' => $request->quentity,
                    'cost_per_each_withoud_tax' => $request->thecostProduct,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // خصم الكمية المحولة من مخزون الفرع الحالي (المصدر)
                $product->decrement('numberofpice', $request->quentity);
            }

            return $this->getMovementOrderData($orderId);
        });

        return $data;
    }

    /**
     * دالة استقبال المنتجات المحولة وتحديث مخزون الفرع المستلِم (أو إنشاء منتجات جديدة برقم كود مطابق)
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'invoiceId' => 'required|exists:product_movement_another_branch,id'
        ]);

        $data = DB::transaction(function () use ($request) {
            $mainInvoice = product_movement_another_branch::lockForUpdate()->find($request->invoiceId);

            if (!$mainInvoice || $mainInvoice->reciveInvoiceNumber == 10) {
                return [
                    'mesage' => "عذراً، هذه الفاتورة تم استلامها بالفعل أو غير موجودة.",
                    'status' => 'error'
                ];
            }

            $notregisterproductCount = 0;
            $now = Carbon::now();
            $currentBranchId = Auth::user()->branchs_id;

            // استخدام Eager Loading لتفادي مشكلة N+1 Queries أثناء جلب عناصر الحركة
            $items = product_movement_another_branch_items::where('order_id', $mainInvoice->id)->get();

            foreach ($items as $item) {
                $productSender = products::findOrFail($item->product_id);
                
                // البحث عن المنتج في الفرع المستلِم بواسطة الـ Product_Code
                $productReceiver = products::where('branchs_id', $currentBranchId)
                    ->where('Product_Code', $productSender->Product_Code)
                    ->first();

                if ($productReceiver) {
                    // زيادة كمية المخزون وتحديث الأسعار للفرع المستلم
                    $productReceiver->update([
                        'numberofpice' => $productReceiver->numberofpice + $item->quantity,
                        'purchasingـprice' => $productSender->purchasingـprice,
                        'sale_price' => $productSender->sale_price,
                    ]);
                } else {
                    $notregisterproductCount++;
                    // إنشاء كارت صنف جديد بالكامل في الفرع الحالي بنفس الكود والبيانات الأصلية
                    $productReceiver = products::create([
                        'product_name' => $productSender->product_name,
                        'name_en' => $productSender->name_en,
                        'branchs_id' => $currentBranchId,
                        'numberofpice' => $item->quantity,
                        'user_id' => Auth::id(),
                        'Product_Location' => 'Transfer',
                        'Product_Code' => $productSender->Product_Code,
                        'purchasingـprice' => $productSender->purchasingـprice,
                        'sale_price' => $productSender->sale_price,
                        'Status' => 1,
                        'notes' => $productSender->notes,
                        'unit' => $productSender->unit,
                        'minmum_quantity_stock_alart' => $productSender->minmum_quantity_stock_alart,
                    ]);
                }

                // توثيق حركة الاستلام للفرع المستلم بشكل منفصل
                product_movement_another_branch_items::create([
                    'reciver_branch' => $currentBranchId,
                    'order_id' => 0,
                    'order_id_sender' => $item->order_id,
                    'product_id' => $productReceiver->id,
                    'quantity' => $item->quantity,
                    'cost_per_each_withoud_tax' => $item->cost_per_each_withoud_tax,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // إغلاق الفاتورة وتغيير حالتها لـ (مستلمة بالكامل = 10)
            $mainInvoice->update([
                'reciveInvoiceNumber' => 10,
                'updated_at' => $now
            ]);

            $isAr = LaravelLocalization::getCurrentLocale() == 'ar';
            if ($notregisterproductCount > 0) {
                $message = $isAr 
                    ? "تم تزويد المخزون بنجاح وتحديث الأسعار. تم إنشاء {$notregisterproductCount} أصناف جديدة لعدم توفر أكوادها بالفرع." 
                    : "Inventory replenished successfully. Created {$notregisterproductCount} new items as their codes weren't found in this branch.";
                session()->flash('createnewproduct', $message);
            } else {
                $message = $isAr ? "تم تزويد المخزون بالمنتجات المستلمة بنجاح وتحديث أسعار التكلفة." : "Inventory has been replenished with successfully received products.";
                session()->flash('confirmed', $message);
            }

            return [
                'mesage' => $message,
                'invoice_number' => $mainInvoice->id,
                'invoices_Reciepts' => product_movement_another_branch::where('branch_to', $currentBranchId)->where('reciveInvoiceNumber', 1)->get()
            ];
        });

        return $data;
    }

    /**
     * إلغاء أو حذف صنف واحد من الفاتورة الصادرة وإعادة توازن المخزون وقيمتها المالية
     */
    public function deleteproduct($id)
    {
        $data = DB::transaction(function () use ($id) {
            $item = product_movement_another_branch_items::findOrFail($id);
            $order = product_movement_another_branch::lockForUpdate()->findOrFail($item->order_id);
            $product = products::lockForUpdate()->findOrFail($item->product_id);

            // منطق سليم: إعادة المنتج إلى مخزون الفرع الحالي لأن عملية التحويل لم تتم/أُلغيت
            $product->increment('numberofpice', $item->quantity);

            // تسوية التكلفة المالية الإجمالية للفاتورة الصادرة
            $order->update([
                'Totalcost' => $order->Totalcost - ($item->cost_per_each_withoud_tax * $item->quantity),
                'cost_withod_tax' => $order->cost_withod_tax - ($product->purchasingـprice * $item->quantity),
                'updated_at' => Carbon::now()
            ]);

            $item->delete();

            return $this->getMovementOrderData($order->id);
        });

        return $data;
    }

    /**
     * تدمير/حذف عنصر من حركة وإرجاع المخزون (متوافقة بالكامل مع الـ Resource)
     */
    public function destroy($id)
    {
        return $this->deleteproduct($id);
    }

    public function findinvoiceMovmevt($id)
    {
        $result = product_movement_another_branch::with(['branchfrom', 'userfrom'])->findOrFail($id);
        
        $senderData = [
            'barnch_id' => $result->branchfrom->id ?? null,
            'barnch_name' => $result->branchfrom->name ?? '',
            'user_id' => $result->userfrom->id ?? null,
            'user_name' => $result->userfrom->name ?? '',
            'created_at' => $result->created_at,
        ];

        // جلب العناصر مع المنتج الخاص بها لتفادي الـ N+1 Queries
        $items = product_movement_another_branch_items::with('product')->where('order_id', $id)->get();
        $dataitems = [];
        
        foreach ($items as $count => $item) {
            $dataitems[] = [
                "count" => $count + 1,
                'productname' => $item->product->product_name ?? '',
                "product_code" => $item->product->Product_Code ?? '',
                "details_items_no" => $item->id,
                'cost' => $item->cost_per_each_withoud_tax,
                'quantity' => $item->quantity,
                'total' => $item->quantity * $item->cost_per_each_withoud_tax,
            ];
        }

        return [
            'senderdata' => $senderData,
            'orderItems' => $dataitems
        ];
    }

    public function show()
    {
        return view('supProcesses.recive_product_from_another_branch', ['data' => 0]);
    }

    public function print_Transfer_items(Request $request)
    {
        if (!$request->sprint_invoice_number) {
            session()->flash('nodataprint', '');
            return view('supProcesses.send_product_to_branch');
        }
        $invoice = product_movement_another_branch::findOrFail($request->sprint_invoice_number);
        $items = product_movement_another_branch_items::with('product')->where('order_id', $invoice->id)->get();

        return view('supProcesses.print_send_product', ['data' => compact('invoice', 'items')]);
    }

    public function print_Recive_items(Request $request)
    {
        if (!$request->sprint_invoice_number) {
            session()->flash('nodataprint', '');
            return view('supProcesses.recive_product_from_another_branch');
        }
        $invoice = product_movement_another_branch::findOrFail($request->sprint_invoice_number);
        $items = product_movement_another_branch_items::with('product')->where('order_id', $invoice->id)->get();

        return view('supProcesses.print_recive_product', ['data' => compact('invoice', 'items')]);
    }

    /**
     * دالة مساعدة موحدة (Helper Method) لتجميع وهيكلة بيانات الفاتورة وعناصرها للعرض السريع
     */
    private function getMovementOrderData($orderId)
    {
        $order = product_movement_another_branch::find($orderId);
        $items = product_movement_another_branch_items::with('product')->where('order_id', $orderId)->get();
        
        $dataitems = [];
        foreach ($items as $index => $item) {
            $dataitems[] = [
                "count" => $index + 1,
                'productname' => $item->product->product_name ?? '',
                "product_code" => $item->product->Product_Code ?? '',
                "details_items_no" => $item->id,
                'cost' => $item->cost_per_each_withoud_tax,
                'quantity' => $item->quantity,
                'total' => $item->quantity * $item->cost_per_each_withoud_tax,
            ];
        }

        return [
            "orderData" => $order,
            "orderItems" => $dataitems
        ];
    }
}