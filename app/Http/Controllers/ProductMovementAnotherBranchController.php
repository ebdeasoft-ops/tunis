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
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SentMovementsExport; // سنقوم بإنشائها أو يمكنك استخدام الكود المباشر
use App\Exports\ReceivedMovementsExport; // سنقوم بإنشائها أو يمكنك استخدام الكود المباشر
class ProductMovementAnotherBranchController extends Controller
{






public function exportReceivedPdf(Request $request)
{
    $currentBranchId = $request->get('branch_id', auth()->user()->branchs_id);

    $query = product_movement_another_branch::with([
        'branchfrom',
        'branchto',
        'userfrom',
        'userto',
        'items.product'
    ])
    ->where('branch_to', $currentBranchId); // حركات واردة للفرع

    if ($request->filled('movement_id')) {
        $query->where('id', $request->movement_id);
    }

    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $movements = $query->latest()->get();
// return $movements;
    // فتح صفحة الـ Blade الخاصة بـ PDF الوارد
    return view('pdf.received_movements_pdf', compact('movements'));
}


public function exportReceivedExcel(Request $request)
{
    $currentBranchId = $request->get('branch_id', auth()->user()->branchs_id);

    $query = product_movement_another_branch::with([
        'branchfrom',
        'branchTo',
        'userFrom',
        'userTo',
        'items.product'
    ])
    ->where('branch_to', $currentBranchId); // حركات واردة للفرع

    if ($request->filled('movement_id')) {
        $query->where('id', $request->movement_id);
    }

    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $movements = $query->latest()->get();

    $fileName = 'received_movements_' . date('Y-m-d') . '.xlsx';

    return Excel::download(new ReceivedMovementsExport($movements), $fileName);
}










public function exportPdf(Request $request)
{
    $currentBranchId = $request->get('branch_id', auth()->user()->branchs_id);

    $query = product_movement_another_branch::with([
        'branchfrom',
        'branchto',
        'userfrom',
        'items.product'
    ])
    ->where('branch_from', $currentBranchId)
    ->where('status', 'completed');

    if ($request->filled('movement_id')) {
        $query->where('id', $request->movement_id);
    }

    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $movements = $query->latest()->get();

    // 🌟 فتح صفحة الـ Blade مباشرة في المتصفح وبدون مكتبة PDF
    return view('pdf.sent_movements_pdf', compact('movements'));
}


    public function exportExcel(Request $request)
{
    $currentBranchId = $request->get('branch_id', auth()->user()->branchs_id);

    $query = product_movement_another_branch::with([
        'branchTo',
        'userFrom',
        'userTo',
        'items.product'
    ])
    ->where('branch_from', $currentBranchId)
    ->where('status', 'completed');

    if ($request->filled('movement_id')) {
        $query->where('id', $request->movement_id);
    }

    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $movements = $query->latest()->get();

    // يمكنك تصدير البيانات مباشرة عبر CSV أو استخدام حزمة Excel
    $fileName = 'sent_movements_' . date('Y-m-d') . '.xlsx';

    // مثال بسيط للتصدير (إذا كنت تستخدم Maatwebsite\Excel):
    return Excel::download(new SentMovementsExport($movements), $fileName);

    // أو يمكنك توجيه الطلب بالطريقة المناسبة لديك.
}
    public function __construct()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    }


  public function index(Request $request)
{
    $draftMovement = null;
    $draftItems = [];
    $branchId = $request->get('branch_id', auth()->user()->branchs_id);

    if ($request->has('draft_id')) {
        $draftMovement = product_movement_another_branch::where('id', $request->draft_id)
            ->where('branch_from', $branchId)
            ->where('status', 'draft')
            ->firstOrFail();

        $draftItems = product_movement_another_branch_items::with('product')
            ->where('order_id', $draftMovement->id)
            ->get();
    }

    // جلب الـ branch_id من الرابط، وإذا لم يوجد، يتم استخدام فرع المستخدم الحالي

    return view('supProcesses.send_product_to_branch', compact('draftMovement', 'draftItems', 'branchId'));
}
    /**
     * إنشاء أو تحديث أمر تحويل منتجات إلى فرع آخر (صرف من الفرع الحالي)
     */

public function showMyDrafts(Request $request)
    {
        $branchId = $request->input('branch_id');

        // جلب المسودات المعلقة بناءً على فرع المستخدم أو الفرع الفرعي المحدد من الرابط
        $myDrafts = product_movement_another_branch::where('branch_from',$branchId?? auth()->user()->branchs_id)

            ->where('status', 'draft')
            ->orderBy('id', 'desc')
            ->get();

        // نقوم بتمرير المسودات ومعرف الفرع إلى صفحة العرض
        return view('supProcesses.my_drafts_list', compact('myDrafts', 'branchId'));
    }


    public function create(Request $request)
    {
        // 1. التحقق من البيانات
        $this->validate($request, [
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'branch' => 'required',
            'employeereciver' => 'required|exists:users,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|numeric|min:0.01',
            'prices' => 'required|array',
            'status' => 'required|in:completed,draft',
            'draft_id' => 'nullable|exists:product_movement_another_branches,id',
        ]);

        return DB::transaction(function () use ($request) {
            $now = Carbon::now();
            $user = Auth::user();

            $isUpdatingDraft = $request->has('draft_id') && !empty($request->draft_id);

            if ($isUpdatingDraft) {
                $movement = product_movement_another_branch::findOrFail($request->draft_id);

                // 🌟 [مهم جداً] إذا كان الاعتماد النهائي لحركة كانت مسودة سابقاً،
                // نقوم أولاً بمسح بنود المسودة القديمة من جدول البنود بعد جلبها لإعادة البناء النظيف.
                product_movement_another_branch_items::where('order_id', $movement->id)->delete();

                $movement->update([
                    'branch_to' => $request->branch,
                    'user_to' => $request->employeereciver,
                    'status' => $request->status,
                    'updated_at' => $now,
                ]);
            } else {
                // إنشاء حركة جديدة تماماً لأول مرة
                $movement = product_movement_another_branch::create([
                    'branch_from' => $user->branchs_id,
                    'branch_to' => $request->branch,
                    'user_from' => $user->id,
                    'user_to' => $request->employeereciver,
                    'status' => $request->status,
                    'reciveInvoiceNumber' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'Totalcost' => 0,
                    'cost_withod_tax' => 0,
                ]);
            }

            $orderId = $movement->id;
            $totalCost = 0;
            $totalCostWithoutTax = 0;

            // 3. معالجة جدول المنتجات الحالي والجديد الماثل أمامنا على الشاشة
            foreach ($request->products as $index => $product_id) {
                $quantity = $request->quantities[$index];
                $price = $request->prices[$index];

                $product = products::lockForUpdate()->findOrFail($product_id);

                // 🌟 منطق توازن المخزن المحدث:
                // الخصم الفعلي من المخزن يتم "فقط" إذا كانت الحالة الحالية للملف هي اعتماد نهائي (completed)
                if ($request->status === 'completed') {
                    $product->decrement('numberofpice', $quantity);
                }

                // إعادة إنشاء البند بالوضع والكمية الجديدة المحدثة على الشاشة
                product_movement_another_branch_items::create([
                    'order_id' => $orderId,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'cost_per_each_withoud_tax' => $price,
                    'created_at' => $now,
                ]);

                // إعادة حساب الإجماليات المالية بناءً على المدخلات الأخيرة المعدلة
                $totalCost += ($price * $quantity);
                $purchasingPriceField = isset($product->purchasingـprice) ? $product->purchasingـprice : $product->purchasing_price;
                $totalCostWithoutTax += ($purchasingPriceField * $quantity);
            }

            // 4. تحديث الإجمالي النهائي والدقيق في الفاتورة الرئيسية
            $movement->update([
                'Totalcost' => $totalCost,
                'cost_withod_tax' => $totalCostWithoutTax
            ]);

            $message = $request->status === 'draft'
                ? __('home.Movement_Draft_Updated_Successfully')
                : __('home.Movement_Approved_Successfully');
            return redirect()->back()->with('productupdatedlocation', $message)->with('saved_movement_id', $orderId);
        });
    }



    /**
     * دالة استقبال المنتجات المحولة وتحديث مخزون الفرع المستلِم (أو إنشاء منتجات جديدة برقم كود مطابق)
     */
    public function create_sendProduct(Request $request)
    {
        // 1. التحقق من البيانات القادمة من الفورم
        $this->validate($request, [
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'branch' => 'required',
            'employeereciver' => 'required|exists:users,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|numeric|min:0.01',
            'prices' => 'required|array',
            'status' => 'required|in:completed,draft', // 🌟 هنا نستقبل الحالة (draft أو completed)
        ]);
        return DB::transaction(function () use ($request) {
            $now = Carbon::now();
            $user = Auth::user();

            // 2. التحقق: هل نحن نقوم بتحديث مسودة قديمة أم ننشئ حركة جديدة؟
            if ($request->has('draft_id') && !empty($request->draft_id)) {
                // إذا كانت مسودة قديمة، نحذف بنودها القديمة لإعادة بنائها بالكميات الجديدة
                product_movement_another_branch_items::where('order_id', $request->draft_id)->delete();

                $movement = product_movement_another_branch::findOrFail($request->draft_id);
                $movement->update([
                    'branch_to' => $request->branch,
                    'user_to' => $request->employeereciver,
                    'status' => $request->status, // 🌟 قد تتحول هنا من draft إلى completed لو ضغط حفظ نهائي
                    'updated_at' => $now,
                ]);
            } else {
                // إذا كانت حركة جديدة تماماً
                $movement = product_movement_another_branch::create([
                    'branch_from' => $user->branchs_id,
                    'branch_to' => $request->branch,
                    'user_from' => $user->id,
                    'user_to' => $request->employeereciver,
                    'status' => $request->status, // 🌟 تخزين الحالة القادمة من الزر (draft أو completed)
                    'reciveInvoiceNumber' => 1, // الفاتورة مفتوحة بمجرد إنشائها
                    'created_at' => $now,
                    'updated_at' => $now,
                    'Totalcost' => 0,
                    'cost_withod_tax' => 0,
                ]);
            }

            $orderId = $movement->id;
            $totalCost = 0;
            $totalCostWithoutTax = 0;

            // 3. لف على المنتجات المختره وحفظ بنودها
            foreach ($request->products as $index => $product_id) {
                $quantity = $request->quantities[$index];
                $price = $request->prices[$index];

                $product = products::lockForUpdate()->findOrFail($product_id);

                // 🌟 السيستم يخصم من مخزنك الحالي فقط إذا كان الحفظ نهائي (completed).
                // أما لو كان الحفظ كمسودة (draft)، فلن يتم خصم أي قطعة من المخزن!
                if ($request->status === 'completed') {
                    $product->decrement('numberofpice', $quantity);
                }

                // حفظ البند في جدول البنود (يتحفظ في الحالتين عشان لما تفتح المسودة تلاقيهم)
                product_movement_another_branch_items::create([
                    'order_id' => $orderId,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'cost_per_each_withoud_tax' => $price,
                    'created_at' => $now,
                ]);

                $totalCost += ($price * $quantity);
                $totalCostWithoutTax += ($product->purchasingـprice * $quantity);
            }

            // 4. تحديث إجمالي التكلفة للفاتورة
            $movement->update([
                'Totalcost' => $totalCost,
                'cost_withod_tax' => $totalCostWithoutTax
            ]);

            // تحديد الرسالة المناسبة بناءً على الزر المضغوط
            $message = $request->status === 'draft'
            ? __('home.Movement_Saved_As_Draft_Successfully')
            : __('home.Movement_Transferred_Successfully');

            return redirect()->back()->with('productupdatedlocation', $message);
        });
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
    public function store(Request $request)
    {

        app()->setLocale(LaravelLocalization::getCurrentLocale());
        // 1. التحقق من وجود بيانات
        if (!$request->has('product_code')) {
            return response()->json(['message' => 'البيانات غير موجودة'], 400);
        }

        $notregisterproductCount = 0;
        $branchId = $request->branchId??Auth()->user()->branchs_id;

        // 2. المرور على مصفوفة المنتجات القادمة من الجدول
        // نستخدم index للوصول للكمية والسعر المقابل لكل منتج
        foreach ($request->product_code as $index => $pCode) {

            $qty = $request->quantity[$index];
            $cost = $request->cost[$index];

            // البحث عن المنتج في المخزن الخاص بالفرع
            $updateProduct = products::where('branchs_id', $branchId)
                ->where('Product_Code', $pCode)
                ->first();

            if ($updateProduct) {
                // تحديث الكمية والسعر للمنتج الموجود
                $updateProduct->update([
                    'numberofpice' => $updateProduct->numberofpice + $qty,
                    'purchasingـprice' => $cost,
                ]);
                $productId = $updateProduct->id;
            } else {
                // إنشاء منتج جديد إذا لم يكن موجوداً
                $notregisterproductCount++;
                $newProduct = products::create([
                    'product_name' => $request->product_name[$index], // استخدام الاسم الحقيقي                'branchs_id' => $branchId,
                    'numberofpice' => $qty,
                    'user_id' => Auth()->id(),
                    'Product_Code' => $pCode,
                    'purchasingـprice' => $cost,
                    'Status' => 1,
                    'Product_Location' => "TRANSFER",
                    'branchs_id'=>$branchId
                ]);
                $productId = $newProduct->id;
            }

            // 3. تسجيل حركة المخزون لكل عنصر
            product_movement_another_branch_items::create([
                'reciver_branch' => $branchId,
                'order_id' => 0,
                'order_id_sender' => $request->reciveInvoiceNumber, // رقم الفاتورة المرسل
                'product_id' => $productId,
                'quantity' => $qty,
                'cost_per_each_withoud_tax' => $cost,
                'created_at' => \Carbon\Carbon::now()->addHours(3),
            ]);
        }

        // 4. تحديث حالة الفاتورة الرئيسية
        product_movement_another_branch::where('id', $request->reciveInvoiceNumber)->update([
            'reciveInvoiceNumber' => 10,
            'updated_at' => \Carbon\Carbon::now()->addHours(3),
        ]);

        // 5. إعداد رسالة النجاح
        $message = ($notregisterproductCount > 0)
            ? __('home.Products_Received_With_New_Items', ['count' => $notregisterproductCount])
            : __('home.Products_Received_Successfully');

        return response()->json([
            'invoice_number' => $request->invoiceId,
            'message' => $message
        ]);
    }





public function show(\Illuminate\Http\Request $request)
    {
        $branchId = $request->input('branch_id'); // استقبال الـ branch_id من الرابط

        return view('supProcesses.recive_product_from_another_branch', compact('branchId'));
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

public function sentReport(Request $request)
{
    // جلب فرع المستخدم الحالي إما من Request أو من Auth مباشرة
    $currentBranchId = $request->get('branch_id', auth()->user()->branchs_id);

    $query = product_movement_another_branch::with([
        'branchTo',
        'userFrom',
        'userTo',
        'items.product'
    ])
    ->where('branch_from', $currentBranchId)
    ->where('status', 'completed');

    // 🌟 إضافة شرط البحث برقم الحركة (Movement No.)
    if ($request->filled('movement_id')) {
        $query->where('id', $request->movement_id);
    }

    // إصلاح التاريخ: تحويل from_date و to_date
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $sentMovements = $query->latest()->paginate(5);

    if ($request->ajax()) {
        return view('reports.movements.partials.sent_movements_table', compact('sentMovements'))->render();
    }

    return view('reports.movements.sent_movements', compact('sentMovements'));
}
    /**
     * 2. تقرير المنتجات المَسْتَلَمَة في الفرع الحالي من الفروع الأخرى
     */
 public function receivedReport(Request $request)
{
    // جلب فرع المستخدم الحالي إما من Request أو من Auth مباشرة
    $currentBranchId = $request->get('branch_id', auth()->user()->branchs_id);

    $query = product_movement_another_branch::with([
        'branchFrom',
        'branchTo',
        'userFrom',
        'userTo',
        'items.product'
    ])
    ->where('branch_to', $currentBranchId)
    ->where('status', 'completed');

    // 🌟 إضافة شرط البحث برقم الحركة (Movement No.)
    if ($request->filled('movement_id')) {
        $query->where('id', $request->movement_id);
    }

    // إصلاح التاريخ: تحويل from_date و to_date
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $receivedMovements = $query->latest()->paginate(5);

    if ($request->ajax()) {
        return view('reports.movements.partials.received_movements_table', compact('receivedMovements'))->render();
    }

    return view('reports.movements.received_movements', compact('receivedMovements'));
}
    /**
     * 3. تقرير شامل وحركة منتج محدد (إرسال واستلام) تفصيلي
     */
    public function productSummaryReport(Request $request)
    {
        $currentBranchId = auth()->user()->branchs_id;

        // جلب البنود الخاصة بحركات الفرع فقط (إما كان فرع إرسال أو استلام)
        $itemsQuery = product_movement_another_branch_items::whereHas('movement', function ($q) use ($currentBranchId) {
            $q->where('status', 'completed')
            ->where(function ($bQuery) use ($currentBranchId) {
                $bQuery->where('branch_from', $currentBranchId)
                        ->orWhere('branch_to', $currentBranchId);
            });
        })->with(['movement.branchFrom', 'movement.branchTo', 'product']);

        // فلترة بمنتج معين (إذا حدد المستخدم منتج)
        if ($request->filled('product_id')) {
            $itemsQuery->where('product_id', $request->product_id);
        }

        $movementItems = $itemsQuery->latest()->paginate(20);

        return view('reports.movements.product_summary', compact('movementItems', 'currentBranchId'));
    }
}
