<?php

namespace App\Http\Controllers;

use App\Models\resource_purchases;
use App\Models\supllier; // تم الإبقاء عليه كما هو حالياً منعاً لأخطاء النظام
use App\Models\products;
use App\Models\orderDetails;
use App\Models\orderTosupllier;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PDF;

class SupllierController extends Controller
{
    public function __construct()
    {
        // توحيد اللغة على مستوى الكنترولر بالكامل لمنع التكرار
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    }

    /**
     * عرض قائمة طلبات شراء الموارد
     */
    public function index()
    {
        $products = products::where('branchs_id', auth()->user()->branchs_id)->paginate(20);

        return view('products.Purchase_order_of_resources', compact('products'));
    }

    /**
     * جلب بيانات المورد كـ JSON (استجابة متوافقة مع الـ AJAX)
     */
    public function show($id)
    {
        $supplierData = supllier::findOrFail($id);

        return response()->json($supplierData);
    }

    /**
     * طباعة أمر الشراء للمورد بصيغة PDF
     */
    public function printProductToSupllierOrder_pdf($id)
    {
        $resourcePurchases = orderTosupllier::findOrFail($id);
        $orderDetails = orderDetails::where('order_owner', $id)->get();
        $supplierData = supllier::find($resourcePurchases->suplier_id);

        $data = [
            'id'                 => $id,
            'pay'                => $resourcePurchases->Limit_credit,
            'resource_purchases' => $resourcePurchases,
            'supllierdata'       => $supplierData,
            'productsdata'       => $orderDetails
        ];

        $dateTime = now();
        $html = view('pdf.order_purshace_from_supplier', ['data' => $data])->toArabicHTML();
        $pdf = PDF::loadHTML($html)->output();

        $headers = [
            "Content-type" => "application/pdf",
        ];

        return response()->streamDownload(
            fn () => print($pdf),
            "Order_No_" . $id . "_" . $dateTime->format('Y-m-d_H-i-s') . ".pdf",
            $headers
        );
    }

    /**
     * عرض صفحة معاينة طباعة طلب المشتريات من المورد
     */
    public function printProductToSupllierOrder(Request $request)
    {
        if (blank($request->OrderNoprint)) {
            session()->flash('nodataprint', '');
            return redirect()->route('suppliers.index'); // يفضل عمل ريديراكت بدلاً من إعادة استدعاء الفيو يدوياً
        }

        $orderDetails = orderDetails::with('supllier')->where('order_owner', $request->OrderNoprint)->get();

        if ($orderDetails->isEmpty()) {
            session()->flash('error', 'Invoice not found');
            return redirect()->back();
        }

        $resourcePurchases = orderTosupllier::where('id', $request->OrderNoprint)->first();
        
        // التحقق الآمن من وجود العلاقة منعاً للـ Undefined Index
        $supplierId = $orderDetails->first()->supllier->suplier_id ?? null;
        $supplierData = supllier::find($supplierId);

        $data = [
            'pay'                => $resourcePurchases->Limit_credit ?? 0,
            'resource_purchases' => $resourcePurchases,
            'supllierdata'       => $supplierData,
            'productsdata'       => $orderDetails
        ];

        return view('supplier.print_order_purchases_to_supplier', compact('data'))->with('order', 1);
    }

    /**
     * واجهة تعديل الفاتورة وعرض بيانات الطباعة للمورد (سند الصرف الداخلي)
     */
    public function edit(Request $request)
    {
        if (blank($request->orderId)) {
            session()->flash('nodataprint', '');
            $products = products::where('branchs_id', auth()->user()->branchs_id)->paginate(20);
            return view('products.purchases', compact('products'));
        }

        $orderDetails = orderDetails::where('order_owner', $request->orderId)->get();

        if ($orderDetails->isEmpty()) {
            session()->flash('nodataprint', '');
            return redirect()->back();
        }

        $resourcePurchases = resource_purchases::where('orderId', $request->orderId)->first();
        
        $supplierId = $orderDetails->first()->supllier->suplier_id ?? null;
        $supplierData = supllier::find($supplierId);

        $data = [
            'pay'                => $resourcePurchases->Pay_Method_Name ?? '',
            'resource_purchases' => $resourcePurchases,
            'supllierdata'       => $supplierData,
            'productsdata'       => $orderDetails
        ];

        return view('supplier.print_products_to_supplier', compact('data'))->with('order', 0);
    }

    /**
     * طباعة الفاتورة عبر الـ ID مباشرة
     */
    public function prindorderToSupplier($id)
    {
        if (blank($id)) {
            $data = [];
            return view('products.purchases', compact('data'));
        }

        $orderDetails = orderDetails::where('order_owner', $id)->get();

        if ($orderDetails->isEmpty()) {
            return redirect()->back();
        }

        $resourcePurchases = resource_purchases::where('orderId', $id)->first();
        
        $supplierId = $orderDetails->first()->supllier->suplier_id ?? null;
        $supplierData = supllier::find($supplierId);

        $data = [
            'pay'                => $resourcePurchases->Pay_Method_Name ?? '',
            'resource_purchases' => $resourcePurchases,
            'supllierdata'       => $supplierData,
            'productsdata'       => $orderDetails
        ];

        return view('supplier.print_products_to_supplier', compact('data'))->with('order', 0);
    }

    /**
     * استعراض تفاصيل المشتريات
     */
    public function purchasesShow($id)
    {
        $orderDetails = orderDetails::where('order_owner', $id)->get();

        if ($orderDetails->isEmpty()) {
            return redirect()->back();
        }

        $resourcePurchases = resource_purchases::where('orderId', $id)->first();
        $supplierData = supllier::find($resourcePurchases->suplier_id ?? null);

        $data = [
            'pay'                => $resourcePurchases->Pay_Method_Name ?? '',
            'supllierdata'       => $supplierData,
            'productsdata'       => $orderDetails,
            'resource_purchases' => $resourcePurchases
        ];

        return view('supplier.print_products_to_supplier', compact('data'))->with('order', 0);
    }

    public function update(Request $request, supllier $supllier) {}
    public function destroy(supllier $supllier) {}
}