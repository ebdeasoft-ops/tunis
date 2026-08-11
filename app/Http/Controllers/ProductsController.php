<?php

namespace App\Http\Controllers;

// 1. المكتبات الخارجية وحزم المطورين (Packages & Packages Facades)
use Maatwebsite\Excel\Facades\Excel;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf; // أو الكلاس المستعار الخاص بالـ PDF لديك

// 2. كلاسات إطار عمل لارافيل الافتراضية (Laravel Core Classes)
use Illuminate\Http\Request;
use Carbon\Carbon;

// 3. ملفات التصدير والاستيراد (Exports & Imports)
use App\Exports\ExportProductAllBranchs;
use App\Exports\PurchaseTemplateExport;
use App\Imports\PurchasesImport;

// 4. موديلات النظام (Eloquent Models)
use App\Models\User;
use App\Models\Avt;
use App\Models\products;
use App\Models\supllier;
use App\Models\customers;
use App\Models\sales;
use App\Models\sales_withoud_taxes;
use App\Models\orderDetails;
use App\Models\return_sales;
use App\Models\return_sales_deliverys;
use App\Models\invoices;
use App\Models\temp_invoice;
use App\Models\orderTosupllier;
use App\Models\order_price_from_supplier;
use App\Models\order_price_from_supplier_items;
use App\Models\offer_price_to_customer;
use App\Models\offer_price_to_customer_items;
use App\Models\resource_purchases;
use App\Models\credittransactions;
use App\Models\financial_accounts;
use App\Models\stock_update;
use App\Models\product_movement_another_branch;
use App\Models\product_movement_another_branch_items;
use App\Models\delivery_to_customer_withoud_tax_invoices;
use App\Models\branchs;
use App\Exports\StockTemplateExport;
class ProductsController extends Controller
{
    public function upload_stock()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('products.upload_stock');
    }
public function downloadStockTemplate()
{
    // استخدام مكتبة Laravel Excel لتحميل الملف
    return Excel::download(new \App\Exports\StockTemplateExport, 'stock_template.xlsx');
}

public function importStockExcel(Request $request)
{
    $request->validate([
        'branch' => 'required',
        'excel_file' => 'required|mimes:xlsx,xls'
    ]);

    $selectedBranchId = $request->input('branch');

    try {
        // قراءة الملف كمصفوفة عادية لمنع مشاكل مفاتيح العناوين
        $data = Excel::toArray([], $request->file('excel_file'));

        if (empty($data) || empty($data[0])) {
            return response()->json([
                'status' => 'error',
                'message' => 'ملف الإكسيل فارغ / Excel file is empty'
            ], 422);
        }

        $rows = $data[0];

        // استبعاد صف العناوين الأول
        $header = array_shift($rows);

        $productsCount = 0;
        $totalCost = 0;

        foreach ($rows as $row) {
            // تخطي الصفوف الفارغة تماماً
            if (empty(array_filter($row))) {
                continue;
            }

            $productName  = $row[0] ?? 'منتج بدون اسم';
            $productCode  = $row[1] ?? null;
            $quantity     = $row[2] ?? 0;
            $costPrice    = $row[3] ?? 0;
            $salePrice    = $row[4] ?? 0;

            if (!$productCode) {
                continue; // تخطي السطر إذا لم يوجد كود منتج
            }

            // حساب إجمالي التكلفة للمخزون
            $totalCost += $costPrice * $quantity;

            // التحقق إذا كان المنتج موجوداً مسبقاً في نفس الفرع (تحديث أو إنشاء)
            $product = products::updateOrCreate(
                [
                    'Product_Code' => $productCode,
                    'branchs_id'   => $selectedBranchId
                ],
                [
                    'product_name'     => $productName,
                    'user_id'          => Auth()->id(),
                    'unit'             => 'pices',
                    'Status'           => 1,
                    'Product_Location' => '-',
                    'type'             => '1',
                    'brand'            => '1',
                    'product_group'    => 1,
                    'opening_balance'  => $quantity,
                    'quantity'         => $quantity,
                    'purchasing_price' => $costPrice,
                    'saleperpice'      => $salePrice,
                ]
            );

            $productsCount++;
        }

        // جلب حساب المخزون للفرع المحدد
        $inventoryAccount = financial_accounts::where('parent_account_number', 181)->where('branchs_id', $selectedBranchId)->first();

        // تسجيل المعاملة المالية للمخزون الافتتاحي إذا وجد الحساب
        if ($inventoryAccount) {
            CreditTransactions::create([
                'user_id'         => Auth::id(),
                'customer_id'     => $inventoryAccount->id,
                'recive_amount'   => $totalCost,
                'branchs_id'      => $selectedBranchId, // استخدام فرع الاختيار بدلاً من فرع المستخدم
                'pay_method'      => "Cash",
                'note'            => 'قيد المخزون الافتتاحي',
                'currentblance'   => $totalCost,
                'Pay_Method_Name' => "Cash",
                'created_at'      => Carbon::now('Asia/Riyadh'),
                'updated_at'      => Carbon::now('Asia/Riyadh'),
                'debtor'        => $totalCost,
            ]);
        }


                $inventoryAccount = financial_accounts::find( 162);

        // تسجيل المعاملة المالية للمخزون الافتتاحي إذا وجد الحساب
        if ($inventoryAccount) {
            CreditTransactions::create([
                'user_id'         => Auth::id(),
                'customer_id'     => $inventoryAccount->id,
                'recive_amount'   => $totalCost,
                'branchs_id'      => $selectedBranchId, // استخدام فرع الاختيار بدلاً من فرع المستخدم
                'pay_method'      => "Cash",
                'note'            => 'قيد المخزون الافتتاحي',
                'currentblance'   => $totalCost,
                'Pay_Method_Name' => "Cash",
                'created_at'      => Carbon::now('Asia/Riyadh'),
                'updated_at'      => Carbon::now('Asia/Riyadh'),
                'creditor'        => $totalCost,
            ]);
        }



        return response()->json([
            'status'         => 'success',
            'products_count' => $productsCount,
            'message'        => 'تم رفع وتحديث البيانات بنجاح'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'حدث خطأ أثناء المعالجة: ' . $e->getMessage()
        ], 500);
    }
}
    public function save_purchase_order(Request $request)
    {
        // 1. جلب معرف المورد من الريكويست القادم من السيرش
        $supplierId = $request->clientnamesearch;

        // 2. جلب نسبة ضريبة القيمة المضافة الخاصة بالمشتريات (تساوي 0.15 مثلاً)
        $avtPurcheseRate = Avt::find(2);
        $vatPercent = $avtPurcheseRate ? $avtPurcheseRate->AVT : 0.15;

        // 3. التحقق: هل العملية حفظ جديد أم تحديث لأمر شراء قائم؟
        // إذا كانت القيمة 0 تعني فاتورة جديدة (أمر شراء جديد)
        if ($request->show_invoice_number_update == 0) {
            $create_order = orderTosupllier::create([
                'suplier_id' => $supplierId,
                'user_id' => auth()->user()->id,
                'branchs_id' => auth()->user()->branchs_id, // مضاف إذا كان جدول المشتريات يدعم الفروع
                'notes' => $request->notes,
                'discount' => $request->totaldiscound ?? 0,
                'created_at' => \Carbon\Carbon::now()->addHours(3),
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]);
        } else {
            // في حالة التحديث: نجلب رأس الفاتورة الحالي
            $orderId = $request->show_invoice_number_update;
            $create_order = orderTosupllier::findOrFail($orderId);

            // مسح العناصر القديمة التابعة لأمر الشراء هذا لإعادة بنائها من المصفوفة الجديدة
            orderDetails::where('order_owner', $orderId)->delete();

            // تحديث البيانات الأساسية لرأس أمر الشراء
            $create_order->update([
                'suplier_id' => $supplierId,
                'notes' => $request->notes,
                'discount' => $request->totaldiscound ?? 0,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]);
        }

        // 4. الدوران حول مصفوفة المنتجات القادمة لحفظها في جدول تفاصيل المشتريات
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $productItem) {

                // حساب قيمة الضريبة للمنتج بناءً على السعر المرسل (سعر الحبة الواحده بدون ضريبة)
                $priceWithoutTax = $productItem['price'];
                $addedValuePerPiece = $priceWithoutTax * $vatPercent;

                orderDetails::create([
                    'order_owner' => $create_order->id,
                    'product_id' => $productItem['product_id'],
                    'product_name' => $productItem['product_name'] ?? '-', // إذا كنت ترسل الاسم أو تسحبه لاحقاً
                    'purchasingـprice' => $priceWithoutTax,                     // السعر بدون ضريبة
                    'Added_Value' => $addedValuePerPiece,                 // قيمة الضريبة للحبة
                    'numberofpice' => $productItem['quentity'],            // الكمية المطلوبة
                    'discount' => $productItem['discound'] ?? 0,       // الخصم على مستوى الصنف إن وجد
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]);
            }
        }

        // 5. إرجاع معرف أمر الشراء للاستفادة منه في الفرونت إند (للطباعة أو عرض رسالة نجاح)
        return $create_order->id;
    }



    public function importPurchasesAjax(Request $request)
    {

        try {
            $rows = Excel::toCollection(new PurchasesImport, $request->file('excel_file'))->first();

            // جلب الفرع المرسل من الطلب
            $selectedBranchId = $request->input('branch_id');

            $allProdctsD = [];

            foreach ($rows as $row) {
                $product = products::firstOrCreate(
                    [
                        'Product_Code' => $row['product_code'],
                        'branchs_id' => $selectedBranchId // تمت إضافتها هنا لتصبح جزءاً من معيار البحث
                    ],
                    [
                        'product_name' => ($row['product_name_ar'] ?? '') . "  " . ($row['product_name_en'] ?? ''),
                        'name_en' => $row['name_en'] ?? '',
                        'user_id' => Auth()->id(),
                        'unit' => 'pices',
                        'Status' => 1,
                        'Product_Location' => $row['location'] ?? '-',
                        'type' => '1',
                        'brand' => '1',
                        'product_group' => 1,
                    ]
                );


                $allProdctsD[] = [
                    'product_id' => $product->id,
                    'Product_Code' => $product->Product_Code,
                    'product_name' => $product->product_name,
                    'purchasingـprice' => $row['price'] ?? 0,
                    'saleperpice' => $row['sale_price'] ?? 0,
                    'quantity' => $row['quantity'] ?? 1,
                ];
            }

            return response()->json(['success' => true, 'data' => $allProdctsD]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function processExcelImport(Request $request)
    {
        // 1. قراءة الملف
        $rows = Excel::toCollection(new PurchasesImport, $request->file('excel_file'))->first();

        foreach ($rows as $row) {
            // تجهيز الاسم المدمج
            $fullName = ($row['name_ar'] ?? '') . "  " . ($row['name_en'] ?? '');

            // 2. البحث عن المنتج بالكود أو الاسم، وإنشاؤه إذا لم يوجد
            $product = products::firstOrCreate(
                ['Product_Code' => $row['product_code']], // شرط البحث الأساسي (الكود)
                [
                    'product_name' => $fullName,
                    'name_en' => $row['name_en'] ?? '',
                    'branchs_id' => auth()->user()->branchs_id, // جلب الفرع من المستخدم المسجل
                    'user_id' => auth()->id(),
                    'Product_Location' => '-',
                    'Status' => 1,
                    'notes' => "-",
                    'unit' => 'pices',
                    'type' => '1',
                    'brand' => '1',
                    'product_group' => 1,
                    'refnumber' => $row['refnumber'] ?? null,
                    'minmum_quantity_stock_alart' => 2,
                    'photo' => '-',
                ]
            );
        }

        return redirect()->back()->with('success', 'تمت معالجة الملف بنجاح');
    }

    public function downloadTemplate()
    {
        return Excel::download(new PurchaseTemplateExport, 'purchase_template.xlsx');
    }

    public function save_invoice_purchase_roken(Request $request)
    {
        $supplier = $request->clientnamesearch;
        $payment = "Credit";
        $shipping = $request->shippingfee;
        $invoice_number_supplier = $request->Purchase_invoice_number_supplier;
        $invoice_order_purshase = $request->purchase_invoice_no;
        $branchs_id = $request->branchs_id;
        $total_Net = $request->grandTotal;
        $totalTax = $request->totalTax;
        $total_discount = $request->totaldiscound;
        $total = $request->totalSum;
        $date = $request->date;

        // تحديد الوقت والتاريخ بدقة
        $createdAtDate = $date != '0' ? $date . ' ' . substr(Carbon::now()->addHours(3), 12) : Carbon::now()->addHours(3);

        if ($request->orderNo == 0) {
            $createorder = orderTosupllier::create([
                'user_id' => auth()->user()->id,
                'suplier_id' => $supplier,
                'Limit_credit' => $payment,
                'purchaseـamount' => 0,
                'added_value' => $totalTax,
                'created_at' => $createdAtDate,
                'updated_at' => Carbon::now()->addHours(3),
            ]);
        } else {
            $createorder = orderTosupllier::find($request->orderNo);
            orderDetails::where('order_owner', $request->orderNo)->delete();
        }

        $resource_purchases = resource_purchases::create([
            "Other expenses" => 0,
            "shipping fee" => $shipping,
            "purchase_invoice_no" => $invoice_order_purshase,
            "Purchase_invoice_number" => $invoice_number_supplier,
            'orderId' => $createorder->id,
            'suplier_id' => $supplier,
            'In_debt' => $total_Net,
            'Pay_Method_Name' => $payment,
            'notes' => $request->notes,
            'discount' => $request->totaldiscound,
            'save' => 1,
            'branchs_id' => $request->branchs_id,
            'created_at' => $createdAtDate,
            'updated_at' => Carbon::now()->addHours(3),
        ]);

        $cost_shipping_per_item = 0;

        foreach ($request->products as $item) {
            $updateProduct = products::where('Product_Code', $item['code'])
                ->where('branchs_id', $request->branchs_id)
                ->first();

            $cost = 0;
            if ($updateProduct) {
                if ($updateProduct->numberofpice + $item['quentity'] == 0 || $updateProduct->numberofpice < 0) {
                    $cost = $item['price'] + $cost_shipping_per_item;
                } else {
                    $cost = round(((($item['price']) * $item['quentity']) + ($updateProduct->purchasingـprice * $updateProduct->numberofpice)) / ($updateProduct->numberofpice + $item['quentity']), 2);
                }

                products::where('Product_Code', $item['code'])
                    ->where('branchs_id', $request->branchs_id)
                    ->update([
                        'purchasingـprice' => $item['price'] + $cost_shipping_per_item,
                        'average_cost' => $cost,
                        'sale_price' => $item['saleprice'] > 0 ? $item['saleprice'] : $updateProduct->sale_price,
                        'numberofpice' => $updateProduct->numberofpice + $item['quentity'],
                    ]);
            } else {
                $updateProduct = products::create([
                    'product_name' => $item['product_name'],
                    'name_en' => '-',
                    'branchs_id' => $request->branchs_id,
                    'numberofpice' => $item['quentity'],
                    'user_id' => auth()->user()->id,
                    'Product_Location' => 'Transfer',
                    'Product_Code' => $item['code'],
                    'purchasingـprice' => $item['price'] + $cost_shipping_per_item,
                    'sale_price' => 0,
                    'Status' => 1,
                    'notes' => '-',
                    'unit' => '-',
                    'minmum_quantity_stock_alart' => 2,
                ]);
            }

            orderDetails::create([
                'save' => 1,
                'product_id' => $updateProduct->id,
                'order_owner' => $resource_purchases->orderId,
                'product_name' => $updateProduct->product_name,
                'purchasingـprice' => $item['price'],
                'Added_Value' => $item['tax'] / $item['quentity'],
                'numberofpice' => $item['quentity'],
                'sale_price' => 0,
                'unit' => '-',
                'created_at' => Carbon::now()->addHours(3),
                'updated_at' => Carbon::now()->addHours(3),
                'reamingQuantity' => $updateProduct->numberofpice + $item['quentity']
            ]);
        }

        $resource_purchases = resource_purchases::where('orderId', $resource_purchases->orderId)->first();
        $paymentMethod = $payment;

        // حسابات مصاريف الشحن
        if ($shipping > 0) {
            $financial_accounts = financial_accounts::find(133);
            if ($financial_accounts) {
                $financial_accounts->update([
                    'current_balance' => $financial_accounts->current_balance + $shipping,
                    'debtor_current' => $financial_accounts->debtor_current + $shipping,
                ]);
            }

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 133,
                'recive_amount' => $shipping,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
                'currentblance' => ($financial_accounts ? $financial_accounts->current_balance : 0),
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $createdAtDate,
                'updated_at' => Carbon::now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $shipping,
                'cost_center' => 1
            ]);
        }

        $total_value = $resource_purchases->In_debt;
        $avtSaleRate = Avt::find(2);
        $vat_purchase_rate = $avtSaleRate ? $avtSaleRate->AVT : 0;
        $tax_value = 0;
        $total_cost = 0;

        if ($vat_purchase_rate == 0) {
            $total_cost = $total_value;
        } else {
            $total_cost = round($total_value * 100 / (($vat_purchase_rate * 100) + 100), 2);
            $tax_value = $totalTax;
        }

        // الدفع الآجل (Credit)
        if ($payment == "Credit") {
            $supplierData = supllier::find($resource_purchases->suplier_id);
            if ($supplierData) {
                $supplierData->update([
                    'In_debt' => $supplierData->In_debt + $resource_purchases->In_debt
                ]);
            }

            $financial_accounts = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->first();

            if ($financial_accounts) {
                $financial_accounts->update([
                    'current_balance' => $financial_accounts->current_balance + $resource_purchases->In_debt,
                    'creditor_current' => $financial_accounts->creditor_current + $resource_purchases->In_debt,
                ]);
            }

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts ? $financial_accounts->id : 0,
                'recive_amount' => $resource_purchases->In_debt,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
                'currentblance' => $financial_accounts ? ($financial_accounts->current_balance) : 0,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $createdAtDate,
                'updated_at' => Carbon::now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $resource_purchases->In_debt,
                'debtor' => 0,
            ]);

            $financial_accounts = financial_accounts::where('parent_account_number', 4)
                ->where('branchs_id', $branchs_id)
                ->first();

            if ($financial_accounts) {
                $financial_accounts->update([
                    'current_balance' => $financial_accounts->current_balance - $shipping,
                    'creditor_current' => $financial_accounts->creditor_current + $shipping,
                ]);
            }

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts ? $financial_accounts->id : 0,
                'recive_amount' => $shipping,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
                'currentblance' => $financial_accounts ? $financial_accounts->current_balance : 0,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $createdAtDate,
                'updated_at' => Carbon::now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $shipping,
                'debtor' => 0,
            ]);
        }

        $value_total = ($resource_purchases->In_debt + $shipping);

        // الدفع النقدي (Cash)
        if ($resource_purchases->Pay_Method_Name == 'Cash') {
            $financial_accounts = financial_accounts::find($request->paymentmethod);
            if ($financial_accounts) {
                $financial_accounts->update([
                    'current_balance' => $financial_accounts->current_balance - $value_total,
                    'creditor_current' => $financial_accounts->creditor_current + $value_total,
                ]);
            }

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts ? $financial_accounts->id : 0,
                'recive_amount' => $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
                'currentblance' => $financial_accounts ? $financial_accounts->current_balance : 0,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $createdAtDate,
                'updated_at' => Carbon::now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $value_total,
                'debtor' => 0,
            ]);
        }

        // التحويل البنكي أو الشبكة (Bank_transfer / Shabka)
        if ($resource_purchases->Pay_Method_Name == 'Bank_transfer' || $resource_purchases->Pay_Method_Name == 'Shabka') {
            $customer_id_suplier = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->first();

            $value_total = ($resource_purchases->In_debt + $shipping);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer_id_suplier ? $customer_id_suplier->id : 0,
                'recive_amount' => $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $createdAtDate,
                'updated_at' => Carbon::now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => 0,
            ]);

            $financial_accounts = financial_accounts::where('parent_account_number', 4)
                ->where('branchs_id', $branchs_id)
                ->first();

            if ($financial_accounts) {
                $financial_accounts->update([
                    'current_balance' => $financial_accounts->current_balance - $value_total,
                    'creditor_current' => $financial_accounts->creditor_current + $value_total,
                ]);
            }

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts ? $financial_accounts->id : 0,
                'recive_amount' => $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
                'currentblance' => $financial_accounts ? $financial_accounts->current_balance : 0,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $createdAtDate,
                'updated_at' => Carbon::now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $value_total,
                'debtor' => 0,
            ]);
        }

        // الحساب المالي 181
        $financial_accounts = financial_accounts::where('parent_account_number', 181)
            ->where('branchs_id', $branchs_id)
            ->first();

        if ($financial_accounts) {
            $financial_accounts->update([
                'debtor_current' => $financial_accounts->debtor_current + $total_cost,
            ]);
        }

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $financial_accounts ? $financial_accounts->id : 0,
            'recive_amount' => $total_cost,
            'branchs_id' => $branchs_id,
            'pay_method' => $payment,
            'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
            'currentblance' => 0,
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $createdAtDate,
            'updated_at' => Carbon::now()->addHours(3),
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $total_cost,
        ]);

        // الحساب المالي 102 (الضريبة)
        $financial_accounts_102 = financial_accounts::find(102);
        if ($financial_accounts_102) {
            $financial_accounts_102->update([
                'current_balance' => $financial_accounts_102->debtor_current - ($financial_accounts_102->creditor_current - $tax_value),
                'debtor_current' => $financial_accounts_102->debtor_current + $tax_value,
            ]);
        }

        $customerdata = supllier::find($resource_purchases->suplier_id);

        $financial_accounts_parent_102 = financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', $branchs_id)
            ->first();

        if ($financial_accounts_parent_102) {
            $financial_accounts_parent_102->update([
                'current_balance' => $financial_accounts_parent_102->debtor_current - ($financial_accounts_parent_102->creditor_current - $tax_value),
                'debtor_current' => $financial_accounts_parent_102->debtor_current + $tax_value,
            ]);
        }

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $financial_accounts_parent_102 ? $financial_accounts_parent_102->id : 0,
            'recive_amount' => $tax_value,
            'branchs_id' => $branchs_id,
            'pay_method' => $paymentMethod,
            'note' => ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId,
            'currentblance' => $financial_accounts_parent_102 ? ($financial_accounts_parent_102->debtor_current - ($financial_accounts_parent_102->creditor_current - $tax_value)) : 0,
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $createdAtDate,
            'updated_at' => Carbon::now()->addHours(3),
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $tax_value,
            'vat' => 1,
            'name' => $customerdata ? $customerdata->name : '',
            'tax' => $customerdata ? $customerdata->TaxـNumber : '',
            'cost_center' => 1
        ]);

        return $resource_purchases->orderId;
    }






    public function showAllProducts_roken()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('showAllProducts_roken');
    }

    public function purchases_roken()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('products.purchases_roken');
    }

    public function searchaboutproduct_location_function(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = products::where('Product_Location', 'LIKE', '%' . $request->searchtext . '%')->paginate(20);

        return view('ajax_search', compact('data'));
    }

    public function exportAllBranchs($branch_id)
    {
        // ملاحظة: الـ branch_id قد يكون رقم (مثل 5) أو علامة "-" للكل
        $fileName = 'products_report_' . ($branch_id == '-' ? 'all' : 'branch_' . $branch_id) . '.xlsx';

        return Excel::download(new ExportProductAllBranchs($branch_id), $fileName);
    }

    public function previous_deliver_Invoices()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = delivery_to_customer_withoud_tax_invoices::where('branchs_id', auth()->user()->branchs_id)
            ->where('save', 1)
            ->where('status', 0)
            ->paginate(20);

        return view('previous_deliver_Invoices', compact('data'));
    }

    public function getAllinvices_deliveryajax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = delivery_to_customer_withoud_tax_invoices::where('branchs_id', auth()->user()->branchs_id)
            ->where('save', 1)
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('ajax_delivery_Invoices', compact('data'));
    }

    public function searchaboutinvoiceByIdfunction_delivery($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = delivery_to_customer_withoud_tax_invoices::where('branchs_id', auth()->user()->branchs_id)
            ->where('save', 1)
            ->where('status', 0)
            ->where('id', $date)
            ->paginate(20);

        return view('ajax_delivery_Invoices', compact('data'));
    }

    public function getinvoicesbycustomerdelivery($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = delivery_to_customer_withoud_tax_invoices::where('branchs_id', auth()->user()->branchs_id)
            ->where('save', 1)
            ->where('status', 0)
            ->where('customer_id', $date)
            ->paginate(20);

        return view('ajax_delivery_Invoices', compact('data'));
    }

    public function searchfinancial_accounts(Request $request)
    {
        return financial_accounts::where(function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->q . '%')
                ->orWhere('account_number', 'like', '%' . $request->q . '%');
        })
            ->where('account_type', $request->type)
            ->limit(20)
            ->get(['id', 'account_number', 'name']);
    }

    public function search(Request $request)
    {
        return products::where('product_name', 'like', '%' . $request->q . '%')
            ->limit(20)
            ->get(['id', 'product_name']);
    }

    public function clientnamesearch(Request $request)
    {
        return customers::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('tax_no', 'like', '%' . $request->q . '%')
            ->orWhere('phone', 'like', '%' . $request->q . '%')
            ->limit(20)
            ->get(['id', 'name', 'tax_no']);
    }

    public function suppliernamesearch(Request $request)
    {
        return supllier::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('TaxـNumber', 'like', '%' . $request->q . '%')
            ->limit(20)
            ->get(['id', 'name', 'TaxـNumber']);
    }

    public function getByCodenew($barcode)
    {
        $data = products::where('branchs_id', auth()->user()->branchs_id)
            ->where('Product_Code', $barcode)
            ->first();

        if (!$data) {
            return 0;
        }

        return $data->toArray();
    }

    public function generate_barcode($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = products::where('branchs_id', auth()->user()->branchs_id)
            ->where('id', $id)
            ->first();

        if (empty($data)) {
            return redirect()->route('admin.itemcard.index')->with(['error' => 'عفواً، غير قادر على الوصول إلى البيانات المطلوبة !!']);
        }

        return view("generate_barcode", ['data' => $data]);
    }

    public function updateofficebyidforupdate($invoiceNumber)
    {
        $InvoiceData = offer_price_to_customer::find($invoiceNumber);

        if ($InvoiceData != null) {
            // استخدام التحميل المسبق لمنع مشكلة الاستعلامات المتكررة N+1
            $products = offer_price_to_customer_items::with('productData')
                ->where('order_id', $invoiceNumber)
                ->get();

            $allProdctsD = [];
            $i = 0;

            foreach ($products as $product) {
                $i++;
                $allProdctsD[] = [
                    'Product_Code' => $product->productData->Product_Code ?? '-',
                    'product_name' => $product->productData->product_name ?? '-',
                    'quantity' => $product->quantity,
                    'Unit_Price' => $product->PriceWithoudTax,
                    'Discount_Value' => $product->discount,
                    'Added_Value' => 0,
                    'count' => $i,
                    'id' => $product->product_id
                ];
            }

            $customer = customers::find($InvoiceData->customer_id);

            $data = [
                'invoice_number' => $InvoiceData->id,
                'customer' => $customer,
                'product' => $allProdctsD,
                'invoice_id' => $InvoiceData->id
            ];

            return $data;
        }

        return 0;
    }

    public function save_invoice_qutation(Request $request)
    {
        $customertid = $request->clientnamesearch;
        $avt = Avt::find(1);

        if ($request->show_invoice_number_update == 0) {
            $create_order = offer_price_to_customer::create([
                'customer_id' => $customertid,
                'branchs_id' => auth()->user()->branchs_id,
                'created_at' => \Carbon\Carbon::now()->addHours(3),
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'notes' => $request->notes,
                'discount' => $request->totaldiscound,
                'numbershowstatus' => $request->shownumberproduct,
            ]);
        } else {
            $create_order = offer_price_to_customer::find($request->show_invoice_number_update);

            offer_price_to_customer_items::where('order_id', $request->show_invoice_number_update)->delete();

            $create_order->update([
                'customer_id' => $customertid,
                'branchs_id' => auth()->user()->branchs_id,
                'notes' => $request->notes,
                'p_o' => $request->p_o,
                'type' => $request->typeedecument,
                'discount' => $request->totaldiscound,
                'numbershowstatus' => $request->shownumberproduct,
            ]);
        }

        foreach ($request->products as $sale) {
            offer_price_to_customer_items::create([
                'notes' => $request->notes,
                'product_id' => $sale['product_id'],
                'quantity' => $sale['quentity'], // مطابقة لترميز الـ request المرسل من الفرونت
                'PriceWithoudTax' => $sale['price'],
                'discount' => $sale['discound'],
                'order_id' => $create_order->id,
                'created_at' => \Carbon\Carbon::now()->addHours(3),
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]);
        }

        return $create_order->id;
    }






    public function save_invoice_purchase(Request $request)
    {
        // التحقق الأساسي من وجود المنتجات لتجنب أخطاء النظام
        if (!$request->has('products') || !is_array($request->products)) {
            return response()->json(['error' => 'No products found'], 400);
        }

        $supplier = $request->clientnamesearch;
        $payment = $request->payment_type;
        $shipping = $request->shippingfee ?? 0;
        $invoice_number_supplier = $request->Purchase_invoice_number_supplier;
        $invoice_order_purshase = $request->purchase_invoice_no;
        $branchs_id = $request->branchs_id;
        $total_Net = $request->grandTotal;
        $totalTax = $request->totalTax;
        $total_discount = $request->totaldiscound;
        $total = $request->totalSum;
        $date = $request->date;

        // تجهيز الوقت الموحد بناءً على شرطك الحالي
        $nowWithOffset = \Carbon\Carbon::now()->addHours(3);
        $createdAt = $date != '0' ? $date . ' ' . substr($nowWithOffset, 12) : $nowWithOffset;
        $updatedAt = $nowWithOffset;

        // استخدام الـ Transaction لضمان سلامة البيانات المحاسبية
        return DB::transaction(function () use ($request, $supplier, $payment, $shipping, $invoice_number_supplier, $invoice_order_purshase, $branchs_id, $total_Net, $totalTax, $total_discount, $total, $date, $createdAt, $updatedAt) {
         $branchMainId= $request->branchs_id;
        $branchsData=branchs::find($request->branchs_id);

        if ($branchsData->type == 1) {
            $branchMainId = $branchsData->branch_id;
        } else {
            $branchMainId = $request->branchs_id;
        }
            // 1. إدارة أمر التوريد (Order to Supplier)
            if ($request->orderNo == 0) {
                $createorder = orderTosupllier::create([
                    'user_id' => auth()->user()->id,
                    'suplier_id' => $supplier,
                    'Limit_credit' => $payment,
                    'purchaseـamount' => 0,
                    'added_value' => $totalTax,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
            } else {
                $createorder = orderTosupllier::find($request->orderNo);
                orderDetails::where('order_owner', $request->orderNo)->delete();
            }

            // 2. إنشاء فاتورة المشتريات الرئيسية
            $resource_purchases = resource_purchases::create([
                "Other expenses" => 0,
                "shipping fee" => $shipping,
                "purchase_invoice_no" => $invoice_order_purshase,
                "Purchase_invoice_number" => $invoice_number_supplier,
                'orderId' => $createorder->id,
                'suplier_id' => $supplier,
                'In_debt' => $total_Net,
                'Pay_Method_Name' => $payment,
                'notes' => $request->notes,
                'discount' => $total_discount,
                'save' => 1,
                'branchs_id' => $branchs_id,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'branchMainId'=>$branchMainId==$branchs_id?0:$branchMainId
            ]);
            // 3. معالجة المنتجات وحساب التكاليف (توزيع الشحن وحساب متوسط التكلفة)
            $total_invoice_value = $total;
            $total_extra_costs = $shipping;
            $orderDetailsData = [];

            // جلب جميع المنتجات المشمولة في الفاتورة دفعة واحدة لتسريع العملية وتقليل الاستعلامات
            $productIds = collect($request->products)->pluck('product_id')->toArray();
            $dbProducts = products::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($request->products as $item) {
                $updateProduct = $dbProducts->get($item['product_id']);
                if (!$updateProduct)
                    continue;

                // حساب نصيب الحبة من الشحن
                $line_total = $item['price'] * $item['quentity'];
                $item_ratio = ($total_invoice_value > 0) ? ($line_total / $total_invoice_value) : 0;
                $line_extra_cost = $item_ratio * $total_extra_costs;
                $extra_cost_per_unit = ($item['quentity'] > 0) ? ($line_extra_cost / $item['quentity']) : 0;

                // حساب التكلفة المتوسطة (Average Cost) الجديدة للمخزن
                $new_quantity = $updateProduct->numberofpice + $item['quentity'];
                if ($new_quantity <= 0) {
                    $cost = $item['price'] + $extra_cost_per_unit;
                } else {
                    $current_stock_value = $updateProduct->purchasingـprice * $updateProduct->numberofpice;
                    $new_items_value = ($item['price'] + $extra_cost_per_unit) * $item['quentity'];
                    $cost = round(($new_items_value + $current_stock_value) / $new_quantity, 2);
                }

                // تحديث بيانات المنتج
                $updateProduct->update([
                    'purchasingـprice' => $item['price'] + $extra_cost_per_unit,
                    'average_cost' => $cost,
                    'sale_price' => $item['saleprice'] > 0 ? $item['saleprice'] : $updateProduct->sale_price,
                    'numberofpice' => $new_quantity,
                ]);

                // تجميع بيانات تفاصيل الفاتورة للإدخال الجماعي لاحقاً لقاعدة البيانات
                $orderDetailsData[] = [
                    'save' => 1,
                    'product_id' => $item['product_id'],
                    'order_owner' => $resource_purchases->orderId,
                    'product_name' => $updateProduct->product_name,
                    'purchasingـprice' => $item['price'],
                    'Added_Value' => $item['quentity'] > 0 ? ($item['tax'] / $item['quentity']) : 0,
                    'numberofpice' => $item['quentity'],
                    'sale_price' => 0,
                    'unit' => '-',
                    'created_at' => $updatedAt,
                    'updated_at' => $updatedAt,
                    'reamingQuantity' => $new_quantity
                ];
            }

            // إدخال تفاصيل المنتجات دفعة واحدة (أسرع بكثير)
            if (!empty($orderDetailsData)) {
                orderDetails::insert($orderDetailsData);
            }

            $paymentMethod = $payment;
            $invoice_note = ' فاتورة مشتريات رقم :' . (string) $resource_purchases->orderId;

            // 4. معالجة حساب الشحن محاسبياً (إذا وجد شحن)
            if ($shipping > 0) {
                $financial_accounts_133 = financial_accounts::lockForUpdate()->find(133);
                if ($financial_accounts_133) {
                    $financial_accounts_133->update([
                        'current_balance' => $financial_accounts_133->current_balance + $shipping,
                        'debtor_current' => $financial_accounts_133->debtor_current + $shipping,
                    ]);

                    credittransactions::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => 133,
                        'recive_amount' => $shipping,
                        'branchs_id' => $branchs_id,
                        'pay_method' => $payment,
                        'note' => $invoice_note,
                        'currentblance' => $financial_accounts_133->current_balance, // تم تعديلها لتأخذ القيمة المحدثة مباشرة
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                        'orginal_id' => 0,
                        'creditor' => 0,
                        'debtor' => $shipping,
                        'cost_center' => $request->cost_center
                    ]);
                }
            }
           $branchs_id=$branchMainId;

            // 5. حساب قيم الضريبة والصافي المحاسبي
            $total_value = $resource_purchases->In_debt;
            $vat_purchase_rate = $request->avtValue;
            $tax_value = 0;
            $total_cost = 0;
            if ($vat_purchase_rate == 0) {
                $total_cost = $total_value;

            } else {


                $total_cost = round($total_value * 100 / (($vat_purchase_rate * 100) + 100), 2);
                $tax_value = $totalTax;


            }

            // 6. المعالجة المالية في حالة الدفع الآجل (Credit)
            if ($payment == "Credit") {
                $supplierModel = supllier::find($resource_purchases->suplier_id);
                if ($supplierModel) {
                    $supplierModel->increment('In_debt', $resource_purchases->In_debt);
                }

                $financial_accounts_sup = financial_accounts::where('orginal_type', 2)
                    ->where('orginal_id', $resource_purchases->suplier_id)
                    ->first();
                if ($financial_accounts_sup) {
                    $financial_accounts_sup->update([
                        'current_balance' => $financial_accounts_sup->current_balance + $resource_purchases->In_debt,
                        'creditor_current' => $financial_accounts_sup->creditor_current + $resource_purchases->In_debt,
                    ]);

                    credittransactions::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => $financial_accounts_sup->id,
                        'recive_amount' => $resource_purchases->In_debt,
                        'branchs_id' => $branchs_id,
                        'pay_method' => $payment,
                        'note' => $invoice_note,
                        'currentblance' => $financial_accounts_sup->current_balance,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                        'orginal_id' => 0,
                        'creditor' => $resource_purchases->In_debt,
                        'debtor' => 0,
                    ]);
                }

                // تحديث حساب الأب رقم 4 (الصناديق/البنوك) بقيمة الشحن في حالة الآجل
                $financial_accounts_p4 = financial_accounts::where('parent_account_number', 4)
                    ->where('branchs_id', $branchs_id)
                    ->first();
                if ($financial_accounts_p4) {
                    $financial_accounts_p4->update([
                        'current_balance' => $financial_accounts_p4->current_balance - $shipping,
                        'creditor_current' => $financial_accounts_p4->creditor_current + $shipping,
                    ]);

                    credittransactions::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => $financial_accounts_p4->id,
                        'recive_amount' => $shipping,
                        'branchs_id' => $branchs_id,
                        'pay_method' => $payment,
                        'note' => $invoice_note,
                        'currentblance' => $financial_accounts_p4->current_balance,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                        'orginal_id' => 0,
                        'creditor' => $shipping,
                        'debtor' => 0,
                    ]);
                }
            }

            // 7. المعالجة المالية في حالة الدفع النقدي (Cash)
            $value_total = ($resource_purchases->In_debt + $shipping);

            if ($resource_purchases->Pay_Method_Name == 'Cash') {
                $financial_accounts_cash = financial_accounts::find($request->paymentmethod);
                if ($financial_accounts_cash) {
                    $financial_accounts_cash->update([
                        'current_balance' => $financial_accounts_cash->current_balance - $value_total,
                        'creditor_current' => $financial_accounts_cash->creditor_current + $value_total,
                    ]);

                    credittransactions::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => $financial_accounts_cash->id,
                        'recive_amount' => $value_total,
                        'branchs_id' => $branchs_id,
                        'pay_method' => $payment,
                        'note' => $invoice_note,
                        'currentblance' => $financial_accounts_cash->current_balance,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                        'orginal_id' => 0,
                        'creditor' => $value_total,
                        'debtor' => 0,
                    ]);
                }
                $financial_accounts_sup = financial_accounts::where('orginal_type', 2)
                    ->where('orginal_id', $resource_purchases->suplier_id)
                    ->first();


                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $financial_accounts_sup->id,
                    'recive_amount' => $value_total,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $payment,
                    'note' => $invoice_note,
                    'currentblance' => $financial_accounts_sup->current_balance,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => 0,
                ]);
            }

            // 8. المعالجة المالية في حالة (Bank_transfer أو Shabka)
            if ($resource_purchases->Pay_Method_Name == 'Bank_transfer' || $resource_purchases->Pay_Method_Name == 'Shabka') {
                $customer_id_suplier = financial_accounts::where('orginal_type', 2)
                    ->where('orginal_id', $resource_purchases->suplier_id)
                    ->first();
                if ($customer_id_suplier) {
                    credittransactions::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => $customer_id_suplier->id,
                        'recive_amount' => $value_total,
                        'branchs_id' => $branchs_id,
                        'pay_method' => $payment,
                        'note' => $invoice_note,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                        'orginal_id' => 0,
                        'creditor' => 0,
                        'debtor' => 0,
                    ]);
                }

                $financial_accounts_bank = financial_accounts::where('parent_account_number', 4)
                    ->where('branchs_id', $branchs_id)
                    ->first();
                if ($financial_accounts_bank) {
                    $financial_accounts_bank->update([
                        'current_balance' => $financial_accounts_bank->current_balance - $value_total,
                        'creditor_current' => $financial_accounts_bank->creditor_current + $value_total,
                    ]);

                    credittransactions::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => $financial_accounts_bank->id,
                        'recive_amount' => $value_total,
                        'branchs_id' => $branchs_id,
                        'pay_method' => $payment,
                        'note' => $invoice_note,
                        'currentblance' => $financial_accounts_bank->current_balance,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                        'orginal_id' => 0,
                        'creditor' => $value_total,
                        'debtor' => 0,
                    ]);
                }
            }

            // 9. قيد المخزون / المشتريات (حساب 181 الخاص بـ المخزون السلعي أو المشتريات)
            $financial_accounts_p181 = financial_accounts::where('parent_account_number', 181)
                ->where('branchs_id', $branchs_id)
                ->first();
            if ($financial_accounts_p181) {
                $financial_accounts_p181->update([
                    'debtor_current' => $financial_accounts_p181->debtor_current + $total_cost,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $financial_accounts_p181->id,
                    'recive_amount' => $total_cost,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $payment,
                    'note' => $invoice_note,
                    'currentblance' => 0,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => $total_cost,
                ]);
            }

            // 10. معالجة ضريبة القيمة المضافة المدخلة (حساب 102)
            $financial_accounts_102 = financial_accounts::find(102);
            if ($financial_accounts_102) {
                $financial_accounts_102->update([
                    'current_balance' => ($financial_accounts_102->debtor_current + $tax_value) - $financial_accounts_102->creditor_current,
                    'debtor_current' => $financial_accounts_102->debtor_current + $tax_value,
                ]);
            }

            $customerdata = supllier::find($resource_purchases->suplier_id);

            $financial_accounts_p102 = financial_accounts::where('parent_account_number', 102)
                ->where('branchs_id', $branchs_id)
                ->first();
            if ($financial_accounts_p102) {
                $new_debtor = $financial_accounts_p102->debtor_current + $tax_value;
                $financial_accounts_p102->update([
                    'current_balance' => $new_debtor - $financial_accounts_p102->creditor_current,
                    'debtor_current' => $new_debtor,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $financial_accounts_p102->id,
                    'recive_amount' => $tax_value,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $paymentMethod,
                    'note' => $invoice_note,
                    'currentblance' => $new_debtor - $financial_accounts_p102->creditor_current,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => $tax_value,
                    'vat' => 1,
                    'name' => $customerdata ? $customerdata->name : '-',
                    'tax' => $customerdata ? $customerdata->TaxـNumber : '-',
                    'cost_center' => $request->cost_center
                ]);
            }

            return $resource_purchases->orderId;
        });
    }


    public function show_or_not_number($id, $status)
    {
        offer_price_to_customer::where('id', $id)->update(['numbershowstatus' => $status]);
        return 1;
    }

    public function searchaboutinvoice_pendding_ByIdfunction($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = temp_invoice::where('branchs_id', Auth::user()->branchs_id)
            ->where('pending_invoice', 1)
            ->where('id', $date)
            ->paginate(20);

        return view('ajax_Recent_Invoices_pending', compact('data'));
    }

    public function getinvoices_bending_bycustomer($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = temp_invoice::where('branchs_id', Auth::user()->branchs_id)
            ->where('pending_invoice', 1)
            ->where('customer_id', $date)
            ->paginate(20);

        return view('ajax_Recent_Invoices_pending', compact('data'));
    }

    public function getinvoices_bending_bydate($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = temp_invoice::where('branchs_id', Auth::user()->branchs_id)
            ->where('pending_invoice', 1)
            ->where('created_at', $date)
            ->paginate(20);

        return view('ajax_Recent_Invoices_pending', compact('data'));
    }

    public function OfferPricesTocustomer_for_update($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = $id;
        return view('products.OfferPricesTocustomer_for_update', compact('data'));
    }

    public function delete_offer_price($id)
    {
        if ($id != NULL) {
            offer_price_to_customer::where('id', $id)->delete();
        }

        $data = offer_price_to_customer::orderby('id', 'desc')->paginate(30);
        return view('searchPreviousQuotes', compact('data'));
    }

    public function make_Note($id, $note)
    {
        offer_price_to_customer::where('id', $id)->update(['notes' => $note]);
    }

    public function find_account($id)
    {
        $accountdata = financial_accounts::find($id);
        if (!$accountdata)
            return 0;

        return round($accountdata->debtor_current - $accountdata->creditor_current, 2);
    }

public function ChooseProductpaginatenewupdate(Request $request)
{
    // 1. تحديد لغة التطبيق من الـ Request القادم عبر AJAX
    if ($request->has('locale') && !empty($request->locale)) {
        app()->setLocale($request->locale);
    } else {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    }

    $searchtext = $request->searchtext;
    $branchs_id = $request->branchs_id;

    $query = products::query();

    // تنظيم الشروط البرمجية لضمان عدم تداخل الفروع عند البحث
    if ($branchs_id && $branchs_id !== '-') {
        $query->where('branchs_id', $branchs_id);
    }

    // تطبيق البحث فقط إذا كان المتغير يحتوي على نص
    if (!empty($searchtext)) {
        $query->where(function ($q) use ($searchtext) {
            $q->where('product_name', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('refnumber', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('notes', 'LIKE', '%' . $searchtext . '%');
        });
    }

    $data = $query->paginate(20);

    return view('ajax_choose_product', compact('data'));
}



    public function getallpurshasesfromsupplier($id)
    {
        // استخدام eager loading لعلاقة المورد لتجنب بطء الاستعلامات N+1
        $data = orderDetails::with('supllier')->where('product_id', $id)->where('save', 1)->orderBy('id', 'desc')->paginate(5);
        $data_supplier = [];

        foreach ($data as $purchases_item) {
            $data_supplier[] = [
                'invoiceid' => $purchases_item->order_owner,
                'date' => substr($purchases_item->created_at, 0, 10),
                'supplier_name' => $purchases_item->supllier->name ?? '-', // تم تعديل اختصار العلاقة ليكون آمناً
                'cost' => $purchases_item->purchasingـprice,
                'quantity' => $purchases_item->numberofpice,
            ];
        }
        return $data_supplier;
    }

    public function generate_pdf_qoute($request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $itemsRequest = offer_price_to_customer_items::where('order_id', $request)->get();
        $tran = ['itemsRequest' => $itemsRequest];
        $dateTime = now();

        $fileName = $dateTime->format('Y-m-d_H-i-s');
        $html = view('pdf.qutation', $tran)->toArabicHTML();
        $pdf = PDF::loadHTML($html)->output();

        $headers = [
            "Content-type" => "application/pdf",
        ];

        return response()->streamDownload(
            fn() => print ($pdf),
            "Quote_No_" . $request . "_" . $fileName . ".pdf",
            $headers
        );
    }

    function uploadImage($folder, $image)
    {
        $extension = strtolower($image->extension());
        $filename = time() . rand(100, 999) . '.' . $extension;
        $image->move($folder, $filename);
        return $filename;
    }

    public function uploadfilepurchases(Request $request)
    {
        if ($request->hasFile('attachments')) {
            $request->validate([
                'attachments' => 'required|mimes:png,jpg,jpeg,pdf|max:2000',
            ]);

            $photo = $this->uploadImage('assets/attachments', $request->attachments);
            resource_purchases::where('orderId', $request->orderidpurchase)->update(['attachments' => $photo]);
        }

        app()->setLocale(LaravelLocalization::getCurrentLocale());
$userBranchId = Auth::user()->branchs_id;

$data = resource_purchases::where('save', 1)
    ->where(function ($query) use ($userBranchId) {
        $query->where('branchs_id', $userBranchId)
              ->orWhere('branchMainId', $userBranchId);
    })
    ->orderBy('id', 'desc')
    ->paginate(20);

    return view('ajax_Recent_Invoices_purchases', compact('data'));
    }

    public function openfilefile($path)
    {
        return redirect(asset('/assets/attachments') . '/' . $path);
    }

    public function detproductbycode($code, $branch)
    {
        $product = products::where('Product_Code', $code)->where('branchs_id', $branch)->first();
        return $product ? $product : 0;
    }

    public function updatequtation($id)
    {
        $avt = Avt::find(1);
        $offer_price_to_customer = offer_price_to_customer::find($id);
        if (!$offer_price_to_customer)
            return [];

        // جلب علاقة المنتجات دفعة واحدة منعاً للتكرار داخل الـ loop
        $itemsRequest = offer_price_to_customer_items::with('product')->where('order_id', $id)->get();
        $ListProducts = [];
        $count = 0;

        foreach ($itemsRequest as $item) {
            $count++;
            $ListProducts[] = [
                'customer_id' => $offer_price_to_customer->customer_id,
                "count" => $count,
                "productCode" => $item->product->Product_Code ?? '-',
                "productName" => $item->product->product_name ?? '-',
                "sale_price" => $item->PriceWithoudTax,
                "discount" => $item->discount,
                "order_id" => $item->order_id,
                'id' => $item->id,
                "quantity" => $item->quantity,
                "added_value" => ($avt ? $avt->AVT : 0) * $item->PriceWithoudTax,
                'totaldiscount' => $offer_price_to_customer->discount,
                'note' => $offer_price_to_customer->notes
            ];
        }
        return $ListProducts;
    }

    public function replaceproducts($branchs_id, $productId)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = products::where('branchs_id', $branchs_id)->where('main_product', $productId)->paginate();
        return view('ajax_choose_product_replace2', compact('data'));
    }

 public function operationproducts($branchs_id, $productId)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    // جلب البيانات مع تحميل العلاقات المرتبطة مسبقاً لتجنب الأخطاء
    $orderDetails = orderDetails::with(['supllier', 'productData'])
        ->where('product_id', $productId)
        ->where('save', 1)
        ->get();

    $sales = sales::with(['invoice.customer', 'productData'])
        ->where('product_id', $productId)
        ->where('save', 1)
        ->get();

    $return_sales = return_sales::with(['invoice.customer', 'productData'])
        ->where('product_id', $productId)
        ->get();

    $product_movement_another_branch_items = product_movement_another_branch_items::with(['product', 'order'])
        ->where('product_id', $productId)
        ->get();

    $sales_withoud_taxes = sales_withoud_taxes::with(['invoice.customer', 'productData'])
        ->where('product_id', $productId)
        ->where('save', 1)
        ->get();

    $return_sales_deliverys = return_sales_deliverys::with(['invoice.customer', 'productData'])
        ->where('product_id', $productId)
        ->get();

    $products = [];

    foreach ($product_movement_another_branch_items as $item) {
        $invoice = $item->order; // استخدام العلاقة المتاحة بدلاً من البحث المتكرر find()

        $products[] = [
            'id' => $item->order_id,
            'Product_Code' => $item->product->Product_Code ?? '-',
            'product_name' => $item->product->product_name ?? '-',
            'created_at' => $item->created_at,
            'quantity' => $item->quantity,
            'price' => $item->cost_per_each_withoud_tax,
            'operation' => $item->order_id != 0 ? __('home.send_product_from_brance') : __('home.recive_product_from_other_branch_other'),
            'type' => 3,
            'man' => $item->order_id != 0 ? ($invoice->branchto->name ?? '-') : ($invoice->branchfrom->name ?? '-'),
        ];
    }

    foreach ($return_sales as $item) {
        $invoice = $item->invoice;

        $products[] = [
            'id' => $item->invoice_id,
            'Product_Code' => $item->productData->Product_Code ?? '-',
            'product_name' => $item->productData->product_name ?? '-',
            'created_at' => $item->created_at,
            'quantity' => $item->return_quantity,
            'price' => $item->return_Unit_Price,
            'operation' => __('home.salesـreturned'),
            'type' => 2,
            'man' => $invoice->customer->name ?? '-',
        ];
    }

    foreach ($sales as $item) {
        $invoice = $item->invoice;

        $products[] = [
            'id' => $item->invoice_id,
            'Product_Code' => $item->productData->Product_Code ?? '-',
            'product_name' => $item->productData->product_name ?? '-',
            'created_at' => $item->created_at,
            'quantity' => $item->quantity + $item->quantityreturn,
            'price' => $item->Unit_Price,
            'operation' => __('home.sales'),
            'type' => 1,
            'man' => $invoice->customer->name ?? '-',
        ];
    }

    foreach ($return_sales_deliverys as $item) {
        $invoice = $item->invoice;

        $products[] = [
            'id' => $item->invoice_id,
            'Product_Code' => $item->productData->Product_Code ?? '-',
            'product_name' => $item->productData->product_name ?? '-',
            'created_at' => $item->created_at,
            'quantity' => $item->return_quantity,
            'price' => $item->return_Unit_Price,
            'operation' => __('home.delivery_return'),
            'type' => 2,
            'man' => $invoice->customer->name ?? '-',
        ];
    }

    foreach ($sales_withoud_taxes as $item) {
        $invoice = $item->invoice;

        $products[] = [
            'id' => $item->invoice_id,
            'Product_Code' => $item->productData->Product_Code ?? '-',
            'product_name' => $item->productData->product_name ?? '-',
            'created_at' => $item->created_at,
            'quantity' => $item->quantity + $item->quantityreturn,
            'price' => $item->Unit_Price,
            'operation' => __('home.sel_product_withoud_tax'),
            'type' => 1,
            'man' => $invoice->customer->name ?? '-',
        ];
    }

    foreach ($orderDetails as $item) {
        // استخدام العلاقة suplier التي عرفتها مسبقاً في موديل orderDetails
        $supplierName = $item->supllier->supllier->name ?? '-';

        if ($item->returns_purchase > 0) {
            $products[] = [
                'id' => $item->invoice_id ?? $item->id,
                'Product_Code' => $item->productData->Product_Code ?? '-',
                'product_name' => $item->productData->product_name ?? '-',
                'created_at' => $item->updated_at,
                'quantity' => $item->returns_purchase,
                'price' => $item->purchasingـprice,
                'operation' => __('home.purchase_return'),
                'type' => 5,
                'man' => $supplierName,
            ];
        }

        $products[] = [
            'id' => $item->order_owner,
            'Product_Code' => $item->productData->Product_Code ?? $item->product_id,
            'product_name' => $item->productData->product_name ?? $item->product_name,
            'created_at' => $item->created_at,
            'quantity' => $item->numberofpice,
            'price' => $item->purchasingـprice,
            'man' => $supplierName,
            'operation' => __('home.purchases'),
            'type' => 4
        ];
    }

    // ترتيب العمليات حسب التاريخ تصاعدياً
    if (!empty($products)) {
        $dates = array_column($products, 'created_at');
        array_multisort(array_map('strtotime', $dates), SORT_ASC, $products);
    }

    return view('ajax_choose_product_replace', compact('products'));
}


    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = [
            "allcustomers" => customers::all(),
            "allproduct" => products::where('branchs_id', Auth::user()->branchs_id)->get()
        ];

        return view('products.transactions', compact('data'));
    }

    public function updateproductallDataofferprice(Request $request)
    {
        offer_price_to_customer_items::where('id', $request->id)->update([
            'PriceWithoudTax' => $request->price,
            'quantity' => $request->quentity,
            'discount' => $request->discount
        ]);

        return $this->getOfferPriceItemsList($request->id);
    }

    public function deleteitem($id)
    {
        $item = offer_price_to_customer_items::find($id);
        if ($item) {
            $orderId = $item->order_id;
            $item->delete();
            return $this->getOfferPriceItemsList(null, $orderId);
        }
        return [];
    }

    // دالة داخلية خاصة (Helper) لمنع تكرار كود بناء مصفوفة الـ items المعروضة
    private function getOfferPriceItemsList($itemId = null, $orderId = null)
    {
        $avt = Avt::find(1);

        if ($itemId) {
            $itemRequestSingle = offer_price_to_customer_items::find($itemId);
            $orderId = $itemRequestSingle ? $itemRequestSingle->order_id : null;
        }

        if (!$orderId)
            return [];

        $itemsRequest = offer_price_to_customer_items::with('product')->where('order_id', $orderId)->get();
        $offer_price_to_customer = offer_price_to_customer::find($orderId);

        $ListProducts = [];
        $count = 0;

        foreach ($itemsRequest as $item) {
            $count++;
            $ListProducts[] = [
                "count" => $count,
                "productCode" => $item->product->Product_Code ?? '',
                "productName" => $item->product->product_name ?? '',
                "sale_price" => $item->PriceWithoudTax,
                "discount" => $item->discount,
                "order_id" => $item->order_id,
                'id' => $item->id,
                "quantity" => $item->quantity,
                "added_value" => ($avt ? $avt->AVT : 0) * $item->PriceWithoudTax,
                'totaldiscount' => $offer_price_to_customer->discount ?? 0
            ];
        }
        return $ListProducts;
    }







    public function showAllProducts()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('showAllProducts');
    }
     public function showAllProducts_IN_Wherehouse(Request $request)
    {
        //
        $branchId = $request->input('branch_id');

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('showAllProducts_IN_Wherehouse', compact( 'branchId'));
    }
    public function ShowAllNotifications()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $data;
        return view('Notifications_Products');
    }
    public function searchaboutinvoiceByIdfunctionpurchases($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
$userBranchId = Auth()->user()->branchs_id;

$data = resource_purchases::where('save', 1)
    ->where(function ($query) use ($userBranchId) {
        $query->where('branchs_id', $userBranchId)
              ->orWhere('branchMainId', $userBranchId);
    })
    ->where(function ($query) use ($date) {
        $query->where('orderId', $date)
              ->orWhere('Purchase_invoice_number', $date);
    })
    ->orderBy('id', 'desc')
    ->paginate(20);
            return view('ajax_Recent_Invoices_purchases', compact('data'));
    }




    public function getinvoicesbyspplluer($date)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
$userBranchId = Auth()->user()->branchs_id;

$data = resource_purchases::where('save', 1)
    ->where(function ($query) use ($userBranchId) {
        $query->where('branchs_id', $userBranchId)
              ->orWhere('branchMainId', $userBranchId);
    })
    ->where('suplier_id', $date)
    ->orderBy('id', 'desc')
    ->paginate(20);
            return view('ajax_Recent_Invoices_purchases', compact('data'));
    }


    public function product_mix()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);
        return view('supProcesses.product_mix', compact('products'));
    }

    public function profile()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $data;
        return view('profile.show');
    }
public function getAllinvicesapurchasesjax()
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $query = resource_purchases::where('save', 1);

    // التحقق من المستخدمين المستثنين أو تطبيق شرط الفروع
    if (!in_array(auth()->user()->id, [17, 30, 11])) {
        $userBranchId = auth()->user()->branchs_id;

        $query->where(function ($q) use ($userBranchId) {
            $q->where('branchs_id', $userBranchId)
              ->orWhere('branchMainId', $userBranchId);
        });
    }

    $data = $query->orderBy('id', 'desc')->paginate(20);

    return view('ajax_Recent_Invoices_purchases', compact('data'));
}





    public function previousPurchasesInvoices()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //return $data;
        return view('previousPurchasInvoicesNew');
    }








public function previousSalesInvoices()
    {
        $query = Invoices::where('save', 1)
            ->where('status', 0);

        if (!in_array(auth()->user()->id, [17, 30])) {
            $query->where('branchs_id', auth()->user()->branchs_id);
        }

        $data = $query->paginate(20);

        return view('previousSalesInvoices', compact('data'));
    }
public function getAllinvicesajax()
    {
        // Start building the query using the base method
        $query = $this->getBaseInvoiceQuery(0);

        // Apply branch restriction if the user's branch matches 17 or 30
      if (!in_array(auth()->user()->id, [17, 30])) {
                $query->where('branchs_id', auth()->user()->branchs_id);
        }

        // Order, paginate, and fetch the results
        $data = $query->orderBy('id', 'desc')->paginate(20);

        return view('ajax_Recent_Invoices', compact('data'));
    }

public function searchAllInvoicespaginatenew($date)
    {
        $query = $this->getBaseInvoiceQuery(0)
            ->where('created_at', 'LIKE', '%' . $date . '%');

       if (!in_array(auth()->user()->id, [17, 30])) {
                $query->where('branchs_id', auth()->user()->branchs_id);
        }

        $data = $query->paginate(20);

        return view('ajax_Recent_Invoices', compact('data'));
    }

    public function searchaboutinvoiceByIdfunction($date)
    {
        $query = $this->getBaseInvoiceQuery(0)
            ->where('id', $date);

       if (!in_array(auth()->user()->id, [17, 30])) {
                $query->where('branchs_id', auth()->user()->branchs_id);
        }

        $data = $query->paginate(20);

        return view('ajax_Recent_Invoices', compact('data'));
    }

    public function getinvoicesbycustomer($date)
    {
        $query = $this->getBaseInvoiceQuery(0)
            ->where('customer_id', $date);

       if (!in_array(auth()->user()->id, [17, 30])) {
                $query->where('branchs_id', auth()->user()->branchs_id);
        }

        $data = $query->orderBy('id', 'desc')->paginate(20);

        return view('ajax_Recent_Invoices', compact('data'));
    }















    public function previousRecieptInvoices()
    {
        return view('previousRecieptInvoices');
    }

    public function getAllRecieptsjax()
    {
        $data = $this->getBaseInvoiceQuery(1)->orderBy('id', 'desc')->paginate(20);
        return view('ajax_Recent_Reciepts', compact('data'));
    }

    public function searchaboutReciptByIdfunction($date)
    {
        $data = $this->getBaseInvoiceQuery(1)->where('id', $date)->paginate(20);
        return view('ajax_Recent_Reciepts', compact('data'));
    }

    public function searchAllRecieptspaginatenew($date)
    {
        $data = $this->getBaseInvoiceQuery(1)
            ->where('created_at', 'LIKE', '%' . $date . '%')
            ->paginate(20);

        // Note: Corrected layout view reference here if it was supposed to point to Receipts
        return view('ajax_Recent_Invoices', compact('data'));
    }

    /**
     * Helper to eliminate redundant queries for Invoices
     */
    private function getBaseInvoiceQuery($status)
    {
        return Invoices::where('branchs_id', auth()->user()->branchs_id)
            ->where('save', 1)
            ->where('status', $status);
    }


    // --- PURCHASES & SUPPLIERS SECTION ---

    public function changePaymethodIPurchases($orderId, $paymentMethod)
    {
        resource_purchases::where('orderId', $orderId)->update([
            'Pay_Method_Name' => $paymentMethod,
        ]);

        orderTosupllier::where('id', $orderId)->update([
            'Limit_credit' => $paymentMethod,
        ]);

        return 'Done';
    }


    // --- QUOTATIONS & OFFER PRICES SECTION ---

    public function makeTotalDiscontOferprice($id, $discountvalue)
    {
        $offerPrice = offer_price_to_customer::findOrFail($id);
        $offerPrice->update(['discount' => $discountvalue]);

        // Eager load products to avoid N+1 querying inside the loop
        $itemsRequest = offer_price_to_customer_items::with('product')->where('order_id', $id)->get();

        $totalsale_price = 0;
        $totaldiscount = (float) $discountvalue;

        foreach ($itemsRequest as $item) {
            $totalsale_price += ($item->PriceWithoudTax * $item->quantity);
            $totaldiscount += $item->discount;
        }

        $avt = Avt::find(1);
        $totaladdedvalue = ($totalsale_price - $totaldiscount) * ($avt->AVT ?? 0);

        return [
            'total_purchases_AddedValue' => round($totaladdedvalue, 2),
            'totaldiscount' => round($totaldiscount, 2),
            'total_purchases' => round($totalsale_price, 2),
            'totalafterdiscount' => round($totalsale_price - $totaldiscount, 2)
        ];
    }

    public function set_customer_quotation($orderid, $customerid)
    {
        offer_price_to_customer::where('id', $orderid)->update([
            'customer_id' => $customerid,
        ]);
    }

    public function AddproductPriceToCustomer(Request $request)
    {
        $avt = Avt::find(1);
        $orderNo = $request->orderNo;

        if ($orderNo == null) {
            $order = offer_price_to_customer::create([
                'customer_id' => $request->clientnamesearch,
                'branchs_id' => auth()->user()->branchs_id,
                'created_at' => Carbon::now()->addHours(3),
                'updated_at' => Carbon::now()->addHours(3),
                'notes' => $request->notes,
                'numbershowstatus' => $request->numbershowstatus,
            ]);
            $orderNo = $order->id;
        } else {
            // Check if product already exists in this order sequence
            $exists = offer_price_to_customer_items::where('order_id', $orderNo)
                ->where('product_id', $request->productNo)
                ->exists();

            if ($exists) {
                return '404';
            }

            offer_price_to_customer::where('id', $orderNo)->update([
                'notes' => $request->notes,
            ]);
        }

        // Create the individual line item record
        offer_price_to_customer_items::create([
            'notes' => $request->notes,
            'product_id' => $request->productNo,
            'quantity' => $request->quentity, // Keep input name matching your form
            'PriceWithoudTax' => $request->saleprice,
            'discount' => $request->discount,
            'order_id' => $orderNo,
            'created_at' => Carbon::now()->addHours(3),
            'updated_at' => Carbon::now()->addHours(3),
            'note' => $request->notes,
        ]);

        return $this->getFormattedCustomerOfferItems($orderNo, $avt);
    }

    private function getFormattedCustomerOfferItems($orderId, $avt)
    {
        $offerPrice = offer_price_to_customer::find($orderId);
        $items = offer_price_to_customer_items::with('product')->where('order_id', $orderId)->get();

        $listProducts = [];
        foreach ($items as $index => $item) {
            $listProducts[] = [
                "count" => $index + 1,
                "productCode" => $item->product->Product_Code ?? '',
                "productName" => $item->product->product_name ?? '',
                "sale_price" => $item->PriceWithoudTax,
                "discount" => $item->discount,
                "order_id" => $item->order_id,
                "quantity" => $item->quantity,
                "added_value" => ($avt->AVT ?? 0) * $item->PriceWithoudTax,
                'id' => $item->id,
                'totaldiscount' => $offerPrice->discount ?? 0
            ];
        }
        return $listProducts;
    }


    // --- PRODUCT SEARCH & PAGINATION SECTION ---

    public function showAllproductpaginatepurchase($branchId)
    {
        $products = Products::with('branch')->where('branchs_id', $branchId)->paginate(50);

        $resultProducts = [];
        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->Product_Code,
                'product_name' => $product->product_name,
                'purchasingـprice' => $product->purchasingـprice, // Note: watch out for hidden characters in this field name
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name ?? '',
            ];
        }

        // Appends transformations cleanly into the paginator collection instance
        $products->setCollection(collect($resultProducts));
        return $products;
    }

    public function searchChooseProductpaginatenewpurchaseBypost(Request $request)
    {
        if ($request->has('locale') && !empty($request->locale)) {
        app()->setLocale($request->locale);
    }
        $searchtext = $request->searchtext;
        $branchId = $request->branchs_id;

        $data = Products::where('branchs_id', $branchId)
            ->where(function ($query) use ($searchtext) {
                $query->where('product_name', 'LIKE', '%' . $searchtext . '%')
                    ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%');
            })->paginate(50);

        return view('ajax_choose_product', compact('data'));
    }

    public function searchAllproductpaginatepurchase($branchId, $searchtext)
    {
        $products = Products::with('branch')->where('branchs_id', $branchId)
            ->where(function ($query) use ($searchtext) {
                $query->where('product_name', 'LIKE', '%' . $searchtext . '%')
                    ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%');
            })->paginate(100);

        $resultProducts = [];
        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->Product_Code,
                'product_name' => $product->product_name,
                'purchasingـprice' => $product->purchasingـprice,
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name ?? '',
            ];
        }

        $products->setCollection(collect($resultProducts));
        return $products;
    }

    public function update_offer_price_supplier($id)
    {
        return $this->getFormattedSupplierOrderItems($id);
    }

    public function order_price_from_suppliers(Request $request)
    {
        $orderNo = $request->orderNo;

        if ($orderNo == null) {
            $create_order = order_price_from_supplier::create([
                'suplier_id' => $request->supplierId,
                'branchs_id' => auth()->user()->branchs_id,
                'created_at' => Carbon::now()->addHours(3),
                'updated_at' => Carbon::now()->addHours(3),
            ]);
            $orderNo = $create_order->id;
        }

        order_price_from_supplier_items::create([
            'product_id' => $request->productNo,
            'quantity' => $request->quentity,
            'order_id' => $orderNo,
            'created_at' => Carbon::now()->addHours(3),
            'updated_at' => Carbon::now()->addHours(3),
        ]);

        return $this->getFormattedSupplierOrderItems($orderNo);
    }

    private function getFormattedSupplierOrderItems($orderId)
    {
        $itemsRequest = order_price_from_supplier_items::with('product')->where('order_id', $orderId)->get();
        $listProducts = [];

        foreach ($itemsRequest as $index => $item) {
            $listProducts[] = [
                "count" => $index + 1,
                "productCode" => $item->product->Product_Code ?? '',
                "productName" => $item->product->product_name ?? '',
                "productQuantity" => $item->quantity,
                "order_id" => $item->order_id
            ];
        }

        return $listProducts;
    }



    public function print_preinvoice_to_customer($order_id)
    {
        $itemsRequest = offer_price_to_customer_items::where('order_id', $order_id)->get();

        return view('products.print_preinvoice_to_customer', compact('itemsRequest'))
            ->with('id', $order_id);
    }

    public function print_order_perice_to_customer($order_id)
    {
        $itemsRequest = offer_price_to_customer_items::where('order_id', $order_id)->get();

        return view('products.print_order_perice_to_customer', compact('itemsRequest'));
    }

    public function printOrderPriceFromSupplier($order_id)
    {
        $itemsRequest = order_price_from_supplier_items::where('order_id', $order_id)->get();

        return view('products.print_order_perice_from_supplier', compact('itemsRequest'))
            ->with('id', $order_id);
    }

    public function print_order_perice_to_customerByPost(Request $request)
    {
        $orderId = $request->OrderNoprint;
        $itemsRequest = offer_price_to_customer_items::where('order_id', $orderId)->get();

        return view('products.print_order_perice_to_customer', compact('itemsRequest'))
            ->with('id', $orderId);
    }

    public function printOrderPriceFromSupplierBypost(Request $request)
    {
        $orderId = $request->OrderNoprint;
        $itemsRequest = order_price_from_supplier_items::where('order_id', $orderId)->get();

        return view('products.print_order_perice_from_supplier', compact('itemsRequest'))
            ->with('id', $orderId);
    }

    // --- PURCHASES & RETURNS ---

    public function purchases()
    {
        $products = Products::where('branchs_id', auth()->user()->branchs_id)->paginate(50);

        return view('products.purchases', compact('products'));
    }

    public function Purchase_returns()
    {
        $data = [];
        return view('products.purchase_return', compact('data'));
        // Cleaned up dead database calls that were placed after the return statement
    }

    public function Purchase_returns_Data(Request $request)
    {
        $orderId = $request->clientName; // Assuming clientName contains the order ID input

        // Find supplier order alongside its user and the user's branch relation elegantly
        $orderOwner = orderTosupllier::with('user.branch')->find($orderId);

        $resource_purchases = resource_purchases::where('orderId', $orderId)
            ->where('save', 1)
            ->first();

        if (!$resource_purchases || !$orderOwner) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar'
                ? 'لم يتم العثور علي فاتورة بهذة الرقم'
                : 'No invoice with this number was found';

            session()->flash('notfountreturnpuracheseproduct', $message);

            $data = [];
            return view('products.purchase_return', compact('data'));
        }

        $orderdetails = OrderDetails::where('order_owner', $orderId)->get();
        $branchName = $orderOwner->user->branch->name ?? '';

        $data = [
            'branch' => $branchName,
            'supllier' => $orderOwner,
            'resource_purchases' => $resource_purchases,
            'product' => $orderdetails
        ];

        return view('products.purchase_return', compact('data'));
    }












    public function printReturnpurchases($id)
    {
        $orderOwner = orderTosupllier::find($id);
        $resource_purchases = resource_purchases::where('orderId', $id)->first();
        $orderdetails = orderDetails::where('order_owner', $id)->where('returns_purchase', '!=', 0)->get();

        if ($orderOwner == null) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar'
                ? ' لم يتم العثور علي فاتورة بهذة الرقم'
                : 'No invoice with this number was found';

            session()->flash('notfountreturnpuracheseproduct', $message);
            $data = [];
            return view('products.purchase_return', compact('data'));
        }

        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name ?? '';

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'product' => $orderdetails,
            'resource_purchases' => $resource_purchases
        ];

        return view('products.print_purchase_return', compact('data'));
    }

    public function create(Request $request)
    {
        $product = products::get();

        $data = [
            "date" => date("Y-m-d", time()),
            'product' => $product,
            'clientnote' => $request["notes"],
            'clientphone' => $request["phonenumber"],
            'clientname' => $request["clientName"],
            'clientaddress' => $request["address"],
            'print' => 'print quentity'
        ];

        return view('products.print_products', compact('data'));
    }

    public function getProductsPriceFromSupplier()
    {
        $products = products::where('branchs_id', auth()->user()->branchs_id)->paginate(20);
        return view('products.Requestpricesofproductsfromsupplier', compact('products'))->with('order_id', "-");
    }

    public function showProductsPrice(Request $request)
    {
        $products = products::where('branchs_id', auth()->user()->branchs_id)->paginate(20);
        return view('products.OfferPricesTocustomer', compact('products'))->with('order_id', '-');
    }

    public function print_all_products_price(Request $request)
    {
        $product = products::get();
        $data = [
            "date" => date("Y-m-d", time()),
            'product' => $product,
        ];
        return view('products.print_all_products_price', compact('data'));
    }

    public function printProductPriceToCustomer($request)
    {
        $product = products::get();
        $data = [
            "date" => date("Y-m-d", time()),
            'product' => $product,
            'clientnote' => $request["notes"],
            'clientphone' => $request["phonenumber"],
            'clientname' => $request["clientName"],
            'clientaddress' => $request["address"],
            'print' => 'printprice'
        ];
        return view('products.print_products', compact('data'));
    }

    public function printProductPrice(Request $request)
    {
        $product = products::get();
        $data = [
            "date" => date("Y-m-d", time()),
            'product' => $product,
            'clientnote' => $request["notes"],
            'clientphone' => $request["phonenumber"],
            'clientname' => $request["clientName"],
            'clientaddress' => $request["address"],
            'print' => 'printprice'
        ];
        return view('products.print_products', compact('data'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($products)
    {
        return products::find($products);
    }

    public function getproductbyid($products)
    {
        return products::find($products);
    }

    /**
     * دالة مساعدة عامة لإلغاء وتحديث الحسابات المشتركة بين الحذف والتعديل منعا لتكرار الكود الضخم
     */
    private function processInvoiceReversal($id)
    {
        $resources = resource_purchases::where('orderId', $id)->first();
        if (!$resources) {
            return null;
        }

        $recentsupllier = supllier::find($resources->suplier_id);
        $orderdetails = orderDetails::where('order_owner', $id)->get();

        resource_purchases::where('orderId', $id)->update(['save' => 0]);
        orderDetails::where('order_owner', $id)->update(['save' => 0]);

        $find_supplier_account = financial_accounts::where('orginal_type', 2)
            ->where('orginal_id', $resources->suplier_id)
            ->first();

        if ($find_supplier_account) {
            $credittransactions = credittransactions::where('customer_id', $find_supplier_account->id)
                ->where('note', 'LIKE', '%' . 'فاتورة مشتريات رقم :' . $id . '%')
                ->first();

            if ($credittransactions) {
                credittransactions::where('note', $credittransactions->note)->delete();
            }
        }

        $total_value = $resources->In_debt - $resources->discount;
        $avtSaleRate = Avt::find(2);
        $vat_purchase_rate = $avtSaleRate->AVT ?? 0;
        $tax_value = 0;
        $total_cost = 0;

        if ($vat_purchase_rate == 0) {
            $total_cost = $total_value;
        } else {
            $vat_purchase_rate = ($vat_purchase_rate * 100) + 100;
            $total_cost = $total_value * 100 / $vat_purchase_rate;
            $tax_value = $total_value - $total_cost;
        }

        foreach ($orderdetails as $product) {
            $productreturnq = products::find($product->product_id);
            if ($productreturnq) {
                products::where('id', $product->product_id)->update([
                    'numberofpice' => $productreturnq->numberofpice - $product->numberofpice
                ]);
            }
        }

        $branchId = auth()->user()->branchs_id;

        if ($resources->Pay_Method_Name == "Credit") {
            $supplier = supllier::find($resources->suplier_id);
            if ($supplier) {
                supllier::where('id', $resources->suplier_id)->update([
                    'In_debt' => $supplier->In_debt - $resources->In_debt - $resources->discount
                ]);
            }

            $financial_accounts = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resources->suplier_id)
                ->first();
            if ($financial_accounts) {
                $financial_accounts->update([
                    'creditor_current' => $financial_accounts->creditor_current - ($total_cost + $tax_value),
                ]);
            }
        } elseif ($resources->Pay_Method_Name == "Cash") {
            $fin_5 = financial_accounts::find(5);
            if ($fin_5) {
                $fin_5->update(['debtor_current' => $fin_5->debtor_current - ($total_cost + $tax_value)]);
            }

            $financial_accounts = financial_accounts::where('parent_account_number', 5)->where('branchs_id', $branchId)->first();
            if ($financial_accounts) {
                $financial_accounts->update(['creditor_current' => $financial_accounts->creditor_current - ($total_cost + $tax_value)]);
            }
        } else {
            $fin_4 = financial_accounts::find(4);
            if ($fin_4) {
                $fin_4->update(['creditor_current' => $fin_4->return_purchase - ($total_cost + $tax_value)]);
            }

            $financial_accounts = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchId)->first();
            if ($financial_accounts) {
                $financial_accounts->update(['creditor_current' => $financial_accounts->creditor_current - ($total_cost + $tax_value)]);
            }
        }

        $fin_102 = financial_accounts::find(102);
        if ($fin_102) {
            $fin_102->update(['debtor_current' => $fin_102->debtor_current - $tax_value]);
        }

        $financial_accounts = financial_accounts::where('parent_account_number', 102)->where('branchs_id', $branchId)->first();
        if ($financial_accounts) {
            $financial_accounts->update(['debtor_current' => $financial_accounts->debtor_current - $tax_value]);
        }

        $fin_181 = financial_accounts::find(181);
        if ($fin_181) {
            $fin_181->update(['debtor_current' => $fin_181->debtor_current - $total_cost]);
        }

        $financial_accounts = financial_accounts::where('parent_account_number', 181)->where('branchs_id', $branchId)->first();
        if ($financial_accounts) {
            $financial_accounts->update(['debtor_current' => $financial_accounts->debtor_current - $total_cost]);
        }

        return [
            'resources' => $resources,
            'recentsupllier' => $recentsupllier,
            'orderdetails' => $orderdetails
        ];
    }

    public function delete_purchase_invoice($id)
    {
        $reversalData = $this->processInvoiceReversal($id);

        if ($reversalData) {
         $userBranchId = auth()->user()->branchs_id;

$data = resource_purchases::where('save', 1)
    ->where(function ($query) use ($userBranchId) {
        $query->where('branchs_id', $userBranchId)
              ->orWhere('branchMainId', $userBranchId);
    })
    ->orderBy('id', 'desc')
    ->paginate(20);


            return view('ajax_Recent_Invoices_purchases', compact('data'));
        }

        return 0;
    }

    public function updatepurchasesbyid($id)
    {
        $reversalData = $this->processInvoiceReversal($id);

        if ($reversalData) {
            $resources = $reversalData['resources'];
            $orderdetails = $reversalData['orderdetails'];

            $allProdctsD = [];
            foreach ($orderdetails as $i => $product) {
                $allProdctsD[] = [
                    'Product_Code' => $product->productData->Product_Code ?? '',
                    'product_name' => $product->productData->product_name ?? '',
                    'quantity' => $product->numberofpice,
                    'purchasingـprice' => $product->purchasingـprice, // تم الحفاظ على الاسم الكلمة والحرف الخاص بها
                    'Added_Value' => $product->Added_Value,
                    'saleperpice' => $product->sale_price,
                    'count' => $i + 1,
                    'product_id' => $product->product_id,
                    'id' => $product->id
                ];
            }

            return [
                "Purchase_invoice_number_supplier" => $id,
                "Other expenses" => $resources['Other expenses'],
                "shipping_fee" => $resources['shipping fee'],
                'orderNo' => $id,
                "purchase_invoice_no" => $resources['purchase_invoice_no'],
                'pay' => $resources->pay,
                'In_debt' => $resources->In_debt,
                'discount' => $resources->discount,
                'recentsupllier' => $reversalData['recentsupllier'],
                "product" => $allProdctsD
            ];
        }

        return 0;
    }

    public function Addproducttopurchases(Request $request)
    {
        $avtPurcheseRate = Avt::find(2);
        $clientNo = $request->clientnamesearch;
        $orderNo = $request->orderNo;

        if ($orderNo == null) {
            $createorder = orderTosupllier::create([
                'user_id' => auth()->user()->id,
                'suplier_id' => $clientNo,
                'Limit_credit' => $request->pay,
                'purchaseـamount' => $request->quentity * $request->quentityprice,
                'added_value' => $request->quentity * $request->quentityprice * ($avtPurcheseRate->AVT ?? 0),
                'created_at' => Carbon::now()->addHours(3),
                'updated_at' => Carbon::now()->addHours(3),
            ]);

            $orderId = $createorder->id;

            resource_purchases::create([
                "Other expenses" => $request->Otherexpenses,
                "shipping fee" => $request->shippingfee,
                "purchase_invoice_no" => $request->purchase_invoice_no,
                "Purchase_invoice_number" => $request->Purchase_invoice_number_supplier,
                'orderId' => $orderId,
                'suplier_id' => $clientNo,
                'In_debt' => ($request->quentity * $request->quentityprice) + ($request->quentityprice * ($avtPurcheseRate->AVT ?? 0) * $request->quentity),
                'Pay_Method_Name' => $request->pay,
                'notes' => $request->notes,
                'branchs_id' => $request->branchs_id,
                'created_at' => Carbon::now()->addHours(3),
                'updated_at' => Carbon::now()->addHours(3),
            ]);
        } else {
            $orderId = $orderNo;
            $existingDetail = orderDetails::where('product_id', $request->productname)
                ->where('order_owner', $orderId)
                ->where('numberofpice', '>=', 1)
                ->first();

            if ($existingDetail == null) {
                $getrecientorder = orderTosupllier::find($orderId);
                if ($getrecientorder) {
                    $getrecientorder->update([
                        'purchaseـamount' => $getrecientorder->purchaseـamount + ($request->quentity * $request->quentityprice),
                        'added_value' => $getrecientorder->added_value + ($request->quentity * $request->quentityprice * ($avtPurcheseRate->AVT ?? 0))
                    ]);
                }

                $resource_purchases = resource_purchases::where('orderId', $orderId)->first();
                if ($resource_purchases) {
                    $resource_purchases->update([
                        'In_debt' => $resource_purchases->In_debt + ($request->quentity * $request->quentityprice) + ($request->quentityprice * ($avtPurcheseRate->AVT ?? 0) * $request->quentity),
                        "shipping fee" => $request->shippingfee,
                    ]);
                }
            }
        }

        $productno = $request->productNo ?? $request->productname;
        $getProduct = products::find($productno);
        $productno = $getProduct->id ?? $productno;

        $Added_value = $request->quentityprice * ($avtPurcheseRate->AVT ?? 0);
        $product_Price = $request->quentityprice;

        $checkDetails = orderDetails::where('product_id', $productno)
            ->where('order_owner', $orderId)
            ->where('numberofpice', '>=', 1)
            ->first();

        $Pre_existing = 1;
        if ($checkDetails == null) {
            $Pre_existing = 0;
            orderDetails::create([
                'product_id' => $productno,
                'order_owner' => $orderId,
                'product_name' => $request->productnameshow,
                'purchasingـprice' => $product_Price,
                'Added_Value' => $Added_value,
                'numberofpice' => $request->quentity,
                'sale_price' => $request->sale_price,
                'unit' => $request->unit_pice,
                'created_at' => Carbon::now()->addHours(3),
                'updated_at' => Carbon::now()->addHours(3),
            ]);
        }

        $recentsupllier = supllier::find($clientNo);
        $orderdetails = orderDetails::where('order_owner', $orderId)->get();
        $resource_purchases_final = resource_purchases::where('orderId', $orderId)->first();

        $allProdctsD = [];
        foreach ($orderdetails as $i => $product) {
            $allProdctsD[] = [
                'Product_Code' => $product->productData->Product_Code ?? '',
                'product_name' => $product->productData->product_name ?? '',
                'quantity' => $product->numberofpice,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i + 1,
                'id' => $product->id
            ];
        }

        return [
            'Pre_existing' => $Pre_existing,
            "Purchase_invoice_number_supplier" => $resource_purchases_final['Purchase_invoice_number'] ?? '',
            "Other expenses" => $resource_purchases_final['Other expenses'] ?? '',
            "shipping_fee" => $resource_purchases_final['shipping fee'] ?? '',
            'orderNo' => $orderId,
            "purchase_invoice_no" => $resource_purchases_final['purchase_invoice_no'] ?? '',
            'pay' => $request->pay,
            'In_debt' => $resource_purchases_final->In_debt ?? 0,
            'discount' => $resource_purchases_final->discount ?? 0,
            'recentsupllier' => $recentsupllier,
            "product" => $allProdctsD
        ];
    }

    public function get_all_products_in_orderto_supplier($id)
    {
        return orderDetails::where('order_owner', $id)->get();
    }

    public function updateorder_purchase($id)
    {
        $orderdetails = orderDetails::where('order_owner', $id)->get();
        $listOfProduct = [];
        $totalAdded_value = 0;
        $totalPrice = 0;

        foreach ($orderdetails as $count => $orderitem) {
            $totalAdded_value += $orderitem->numberofpice * $orderitem->Added_Value;
            $totalPrice += $orderitem->numberofpice * $orderitem->purchasingـprice;

            $listOfProduct[] = [
                "count" => $count + 1,
                "productCode" => $orderitem->productData->Product_Code ?? '',
                "product_name" => $orderitem->productData->product_name ?? '',
                "product_id" => $orderitem->product_id,
                "quantity" => $orderitem->numberofpice,
                "purchasingـprice" => $orderitem->purchasingـprice,
                "Added_Value" => $orderitem->Added_Value,
                "total" => ($orderitem->numberofpice * $orderitem->Added_Value) + ($orderitem->numberofpice * $orderitem->purchasingـprice),
                "orderNo" => $orderitem->order_owner,
                "totalAdded_Value" => $totalAdded_value,
                "totalPrice" => $totalPrice
            ];
        }

        return $listOfProduct;
    }

















    public function ChooseProductpaginatenewSale($branchs_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        if ($branchs_id == '-') {
            $data = products::paginate(20);
            return view('ajax_choose_product_sale', compact('data'))->with('getLocale', LaravelLocalization::getCurrentLocale());
        }
        $data = products::where('branchs_id', $branchs_id)->paginate(20);
        // return $data;

        return view('ajax_choose_product_sale', compact('data'))->with('currentrow', 1)->with('getLocale', value: LaravelLocalization::getCurrentLocale());
    }



    public function searchaboutproductwithBranchId($searchtext, $branchId)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $products = [];

        $products = products::where('product_name', 'LIKE', '%' . $searchtext . '%')->orwhere('Product_Code', 'LIKE', '%' . $searchtext . '%')->where('branchs_id', $branchId)->paginate(50);

        return $products;
    }
    public function goToReceipt()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        //
        $products = products::paginate(50);
        // return $products;
        return view('products.Receipt', compact('products'));
    }








    public function savepurchase($request, $payment, $supplier, $shipping, $date, $another_bank)
    {
        $resource_purchases = resource_purchases::where('orderId', $request)->first();
        $branchs_id = $resource_purchases->branchs_id;
        $currentTime = now()->addHours(3);

        // صياغة تاريخ ووقت إنشاء الفاتورة بدقة
        $createdAt = $date != '0' ? $date . ' ' . substr($currentTime, 12) : $currentTime;

        // 1. تحديث بيانات المشتريات الرئيسية
        resource_purchases::where('orderId', $request)->update([
            'save' => 1,
            'suplier_id' => $supplier,
            'Pay_Method_Name' => $payment,
            'shipping fee' => $shipping,
            'created_at' => $createdAt,
        ]);

        // 2. تحديث المورد والحد الائتماني في جدول الطلب بأمر واحد
        orderTosupllier::where('id', $request)->update([
            'Limit_credit' => $payment,
            'suplier_id' => $supplier
        ]);

        // 3. اعتماد الأصناف النشطة في الفاتورة كمحفوظة
        orderDetails::where('order_owner', $request)->where('numberofpice', '!=', 0)->update([
            'save' => 1
        ]);

        // 4. جلب الأصناف النشطة لحساب إجمالي القطع وتوزيع مصاريف الشحن
        $activeItems = orderDetails::where('order_owner', $request)->where('numberofpice', '!=', 0)->get();
        $total_number_items = $activeItems->sum('numberofpice');

        $cost_shipping_per_item = $total_number_items > 0 ? round($shipping / $total_number_items, 3) : 0;

        // 5. حلقة التحديث المالي والمخزني للأصناف (المتوسط المرجح)
        foreach ($activeItems as $item) {
            $updateProduct = products::find($item->product_id);
            $newQuantity = $updateProduct->numberofpice + $item->numberofpice;

            if ($newQuantity <= 0) {
                $cost = $item->purchasingـprice + $cost_shipping_per_item;
            } else {
                $cost = round(((($item->purchasingـprice + $cost_shipping_per_item) * $item->numberofpice) + ($updateProduct->purchasingـprice * $updateProduct->numberofpice)) / $newQuantity, 2);
            }

            // تحديث كرت الصنف الرئيسي
            products::where('id', $item->product_id)->update([
                'purchasingـprice' => $item->purchasingـprice + $cost_shipping_per_item,
                'average_cost' => $cost,
                'sale_price' => $item->sale_price,
                'numberofpice' => $newQuantity,
            ]);

            // [تم الإصلاح] نقل تحديث الكمية المتبقية ليكون داخل الحلقة لكل صنف برقم معرفه الخاص
            orderDetails::where('id', $item->id)->update([
                'reamingQuantity' => $newQuantity
            ]);
        }

        $paymentMethod = $payment;

        // 6. معالجة مصاريف الشحن والتفريغ محاسبياً (الحساب 133)
        if ($shipping > 0) {
            $financial_accounts = financial_accounts::find(133);
            $new_balance = $financial_accounts->current_balance + $shipping;

            financial_accounts::find(133)->update([
                'current_balance' => $new_balance,
                'debtor_current' => $financial_accounts->debtor_current + $shipping,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 133,
                'recive_amount' => $shipping,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $new_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $shipping,
            ]);
        }

        // 7. المعالجة المحاسبية في حال الدفع الآجل (Credit)
        if ($payment == "Credit") {
            $supplier_data = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update([
                'In_debt' => $supplier_data->In_debt + $resource_purchases->In_debt
            ]);

            // تحديث حساب المورد في شجرة الحسابات المالية
            $fin_supplier = financial_accounts::where('orginal_type', 2)->where('orginal_id', $resource_purchases->suplier_id)->first();
            $new_supplier_balance = $fin_supplier->current_balance + $resource_purchases->In_debt;

            financial_accounts::where('id', $fin_supplier->id)->update([
                'current_balance' => $new_supplier_balance,
                'creditor_current' => $fin_supplier->creditor_current + $resource_purchases->In_debt,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $fin_supplier->id,
                'recive_amount' => $resource_purchases->In_debt,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $new_supplier_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => $resource_purchases->In_debt,
                'debtor' => 0,
            ]);

            // خصم مصاريف الشحن من حساب الصندوق أو البنك الرئيسي (الحساب رقم 4)
            $fin_acc_4 = financial_accounts::find(4);
            financial_accounts::find(4)->update([
                'current_balance' => $fin_acc_4->current_balance - $shipping,
                'creditor_current' => $fin_acc_4->creditor_current + $shipping,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 4,
                'recive_amount' => $shipping,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات مصريف شحن و تفريغ رقم :' . (string) $request,
                'currentblance' => $fin_acc_4->current_balance - $shipping,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => $shipping,
                'debtor' => 0,
            ]);

            // تحديث الحساب الفرعي المرتبط بالفرع تحت الحساب رقم 4
            $fin_acc_4_branch = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchs_id)->first();
            if ($fin_acc_4_branch) {
                financial_accounts::where('id', $fin_acc_4_branch->id)->update([
                    'current_balance' => $fin_acc_4_branch->current_balance - $shipping,
                    'creditor_current' => $fin_acc_4_branch->creditor_current + $shipping,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $fin_acc_4_branch->id,
                    'recive_amount' => $shipping,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $payment,
                    'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                    'currentblance' => $fin_acc_4_branch->current_balance - $shipping,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                    'orginal_id' => 0,
                    'creditor' => $shipping,
                    'debtor' => 0,
                ]);
            }
        }

        $value_total = ($resource_purchases->In_debt + $shipping);

        // 8. المعالجة المحاسبية في حال الدفع النقدي (Cash)
        if ($resource_purchases->Pay_Method_Name == 'Cash') {
            $fin_acc_5 = financial_accounts::find(5);
            financial_accounts::find(5)->update([
                'current_balance' => $fin_acc_5->current_balance - $value_total,
                'creditor_current' => $fin_acc_5->creditor_current + $value_total,
            ]);

            $customer_id_suplier = financial_accounts::where('orginal_type', 2)->where('orginal_id', $resource_purchases->suplier_id)->first();

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer_id_suplier->id,
                'recive_amount' => $resource_purchases->In_debt,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $fin_acc_5->current_balance + $resource_purchases->In_debt,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => 0,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 5,
                'recive_amount' => $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $fin_acc_5->current_balance - $value_total,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => $value_total,
                'debtor' => 0,
            ]);

            $fin_acc_5_branch = financial_accounts::where('parent_account_number', 5)->where('branchs_id', $branchs_id)->first();
            if ($fin_acc_5_branch) {
                financial_accounts::where('id', $fin_acc_5_branch->id)->update([
                    'current_balance' => $fin_acc_5_branch->current_balance - $value_total,
                    'creditor_current' => $fin_acc_5_branch->creditor_current + $value_total,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $fin_acc_5_branch->id,
                    'recive_amount' => $value_total,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $payment,
                    'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                    'currentblance' => $fin_acc_5_branch->current_balance - $value_total,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                    'orginal_id' => 0,
                    'creditor' => $value_total,
                    'debtor' => 0,
                ]);
            }
        }

        // 9. المعالجة المحاسبية في حال الدفع عبر الشبكة أو التحويل البنكي (Bank_transfer / Shabka)
        if ($resource_purchases->Pay_Method_Name == 'Bank_transfer' || $resource_purchases->Pay_Method_Name == 'Shabka') {
            $customer_id_suplier = financial_accounts::where('orginal_type', 2)->where('orginal_id', $resource_purchases->suplier_id)->first();

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer_id_suplier->id,
                'recive_amount' => $resource_purchases->In_debt,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => 0,
            ]);

            $fin_acc_4 = financial_accounts::find(4);
            financial_accounts::find(4)->update([
                'current_balance' => $fin_acc_4->current_balance - $value_total,
                'creditor_current' => $fin_acc_4->creditor_current + $value_total,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 4,
                'recive_amount' => $resource_purchases->In_debt - $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $fin_acc_4->current_balance - $value_total,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => $value_total,
                'debtor' => 0,
            ]);

            if ($another_bank == 4) {
                $fin_acc_1157 = financial_accounts::find(1157);
                financial_accounts::find(1157)->update([
                    'current_balance' => $fin_acc_1157->current_balance - $value_total,
                    'creditor_current' => $fin_acc_1157->creditor_current + $value_total,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $fin_acc_1157->id,
                    'recive_amount' => $value_total,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $payment,
                    'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                    'currentblance' => $fin_acc_1157->current_balance - $value_total,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                    'orginal_id' => 0,
                    'creditor' => $value_total,
                    'debtor' => 0,
                ]);
            } else {
                $fin_acc_4_branch = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchs_id)->first();
                if ($fin_acc_4_branch) {
                    financial_accounts::where('id', $fin_acc_4_branch->id)->update([
                        'current_balance' => $fin_acc_4_branch->current_balance - $value_total,
                        'creditor_current' => $fin_acc_4_branch->creditor_current + $value_total,
                    ]);

                    credittransactions::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => $fin_acc_4_branch->id,
                        'recive_amount' => $value_total,
                        'branchs_id' => $branchs_id,
                        'pay_method' => $payment,
                        'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                        'currentblance' => $fin_acc_4_branch->current_balance - $value_total,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'orginal_id' => 0,
                        'creditor' => $value_total,
                        'debtor' => 0,
                    ]);
                }
            }
        }

        // 10. تفكيك واحتساب ضريبة القيمة المضافة المدخلة (الحساب رقم 102)
        $total_value = $resource_purchases->In_debt;
        $avtSaleRate = Avt::find(2);
        $vat_purchase_rate = $avtSaleRate->AVT;
        $tax_value = 0;

        if ($vat_purchase_rate > 0) {
            $vat_percentage = ($vat_purchase_rate * 100) + 100;
            $total_cost = $total_value * 100 / $vat_percentage;
            $tax_value = $total_value - $total_cost;
        }

        $fin_acc_102 = financial_accounts::find(102);
        financial_accounts::find(102)->update([
            'current_balance' => $fin_acc_102->debtor_current - ($fin_acc_102->creditor_current - $tax_value),
            'debtor_current' => $fin_acc_102->debtor_current + $tax_value,
        ]);

        $customerdata = supllier::find($resource_purchases->suplier_id);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => 102,
            'recive_amount' => $tax_value,
            'branchs_id' => $branchs_id,
            'pay_method' => $paymentMethod,
            'note' => ' فاتورة مشتريات رقم :' . (string) $request,
            'currentblance' => $fin_acc_102->debtor_current - ($fin_acc_102->creditor_current - $tax_value),
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $tax_value,
            'vat' => 1,
            'name' => $customerdata->name,
            'tax' => $customerdata->TaxـNumber,
        ]);

        $fin_acc_102_branch = financial_accounts::where('parent_account_number', 102)->where('branchs_id', $branchs_id)->first();
        if ($fin_acc_102_branch) {
            financial_accounts::where('id', $fin_acc_102_branch->id)->update([
                'current_balance' => $fin_acc_102_branch->debtor_current - ($fin_acc_102_branch->creditor_current - $tax_value),
                'debtor_current' => $fin_acc_102_branch->debtor_current + $tax_value,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $fin_acc_102_branch->id,
                'recive_amount' => $tax_value,
                'branchs_id' => $branchs_id,
                'pay_method' => $paymentMethod,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $fin_acc_102_branch->debtor_current - ($fin_acc_102_branch->creditor_current - $tax_value),
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $tax_value,
                'vat' => 1,
                'name' => $customerdata->name,
                'tax' => $customerdata->TaxـNumber,
            ]);
        }

        return 1;
    }

    public function goToSale()
    {
        $products = products::where('branchs_id', auth()->user()->branchs_id)->paginate(50);
        return view('products.sales', compact('products'));
    }

    public function goToSaleByPage()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return products::where('branchs_id', auth()->user()->branchs_id)->paginate(50);
    }

    public function searchaboutproduct($searchtext)
    {
        $branchId = auth()->user()->branchs_id;

        return products::where('branchs_id', $branchId)
            ->where(function ($query) use ($searchtext) {
                $query->where('product_name', 'LIKE', '%' . $searchtext . '%')
                    ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%');
            })->paginate(50);
    }

    public function showAllproductpaginate()
    {
        $products = products::with('branch')->paginate(50);
        $resultProducts = [];

        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->Product_Code,
                'product_name' => $product->product_name,
                'purchasingـprice' => $product->purchasingـprice,
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name ?? '',
            ];
        }

        $products['otherdata'] = $resultProducts;
        return $products;
    }

    public function searchAllproductpaginate($searchtext)
    {
        $products = products::with('branch')
            ->where('product_name', 'LIKE', '%' . $searchtext . '%')
            ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%')
            ->paginate(100);

        $resultProducts = [];

        foreach ($products as $product) {
            $resultProducts[] = [
                'id' => $product->id,
                'Product_Code' => $product->Product_Code,
                'product_name' => $product->product_name,
                'purchasingـprice' => $product->purchasingـprice,
                'sale_price' => $product->sale_price,
                'numberofpice' => $product->numberofpice,
                'Product_Location' => $product->Product_Location,
                'branch' => $product->branch->name ?? '',
            ];
        }

        $products['otherdata'] = $resultProducts;
        return $products;
    }

    public function Allproductpaginatenew()
    {
        $data = products::paginate(20);
        return view('ajax_search', compact('data'));
    }

    public function product_group_ajax(Request $request)
    {
        $searchtext = $request->searchtext;

        if ($searchtext == '') {
            $data = products::where('product_group', $request->group_id)->paginate(20);
        } else {
            $data = products::where('product_group', $request->group_id)
                ->where(function ($query) use ($searchtext) {
                    $query->where('product_name', 'LIKE', '%' . $searchtext . '%')
                        ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%');
                })->paginate(20);
        }

        return view('ajax_search', compact('data'));
    }

    public function product_branchs_id_ajax(Request $request)
    {
        if ($request->branchs_id == '-') {
            $data = products::paginate(30);
            return view('ajax_search', compact('data'));
        }

        $data = products::where('branchs_id', $request->branchs_id)
            ->orderBy('numberofpice', 'desc')
            ->paginate(20);

        return view('ajax_search', compact('data'));
    }

    public function product_sale_group_ajax(Request $request)
    {
        $data = products::where('product_group', $request->group_id)->paginate(20);
        return view('ajax_choose_product_sale', compact('data'))
            ->with('currentrow', $request->currentrow)
            ->with('getLocale', LaravelLocalization::getCurrentLocale());
    }

    public function makeTotalDiscontpurchases($invoiceId, $discountValue)
    {
        $data = resource_purchases::where('orderId', $invoiceId)->first();
        $orderDetails = orderDetails::where('order_owner', $invoiceId)->get();
        $totalpurcgaseswithoudTax = 0;

        foreach ($orderDetails as $item) {
            $totalpurcgaseswithoudTax += $item->purchasingـprice * $item->numberofpice;
        }

        $avtSaleRate = Avt::find(2);
        $avtRate = $avtSaleRate->AVT ?? 0;
        $discountValue = round(($discountValue * 100) / (($avtRate * 100) + 100), 2);
        $totalcost_withoud_tax = ($totalpurcgaseswithoudTax - $discountValue);

        resource_purchases::where('orderId', $invoiceId)->update([
            'discount' => $discountValue,
            'In_debt' => $totalcost_withoud_tax + round($totalcost_withoud_tax * $avtRate, 2)
        ]);

        $updatedData = resource_purchases::where('orderId', $invoiceId)->first();

        return [
            'discount' => $updatedData->discount ?? 0,
            'totalpurcgaseswithoudTax' => $totalpurcgaseswithoudTax,
            'Addedvalue' => $totalpurcgaseswithoudTax * $avtRate,
            'In_debt' => $updatedData->In_debt ?? 0,
            "shipping_fee" => $updatedData['shipping fee'] ?? 0,
        ];
    }

    public function cancelInvoiceDiscontpurcgases($invoiceId)
    {
        $data = resource_purchases::where('orderId', $invoiceId)->first();
        $avtPurcheseRate = Avt::find(2);
        $avtRate = $avtPurcheseRate->AVT ?? 0;

        $orderDetails = orderDetails::where('order_owner', $invoiceId)->get();
        $totalpurcgaseswithoudTax = 0;
        foreach ($orderDetails as $item) {
            $totalpurcgaseswithoudTax += $item->purchasingـprice * $item->numberofpice;
        }

        resource_purchases::where('orderId', $invoiceId)->update([
            'discount' => 0,
            'In_debt' => $totalpurcgaseswithoudTax + round($totalpurcgaseswithoudTax * $avtRate, 2),
        ]);

        $updatedData = resource_purchases::where('orderId', $invoiceId)->first();

        return [
            'discount' => 0,
            'totalpurcgaseswithoudTax' => $totalpurcgaseswithoudTax,
            'Addedvalue' => $totalpurcgaseswithoudTax * $avtRate,
            'In_debt' => $updatedData->In_debt ?? 0,
            "shipping_fee" => $updatedData['shipping fee'] ?? 0,
        ];
    }

    public function searchAllproductpaginatenew($searchtext)
    {
        $data = products::where('product_name', 'LIKE', '%' . $searchtext . '%')
            ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%')
            ->orWhere('notes', 'LIKE', '%' . $searchtext . '%')
            ->orWhere('refnumber', 'LIKE', '%' . $searchtext . '%')
            ->paginate(20);

        return view('ajax_search', compact('data'));
    }

  public function searchAllproductpaginatenew_by_post(Request $request)
    {
        $searchTerm = '%' . $request->searchtext . '%';

        $data = products::query()
            // 1. فلترة بالفرع
            ->when($request->filled('branchs_id') && $request->branchs_id != '-', function ($query) use ($request) {
                $query->where('branchs_id', $request->branchs_id);
            })
            // 2. فلترة بالفئة/المجموعة
            ->when($request->filled('group_id'), function ($query) use ($request) {
                $query->where('product_group', $request->group_id);
            })
            // 3. البحث بالنص
            ->when($request->filled('searchtext'), function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('product_name', 'LIKE', $searchTerm)
                    ->orWhere('Product_Code', 'LIKE', $searchTerm)
                    ->orWhere('notes', 'LIKE', $searchTerm)
                    ->orWhere('refnumber', 'LIKE', $searchTerm);
                });
            })
            // تدميج كل مدخلات Request داخل روابط الصفحات لضمان عدم ضياع أي فلتر عند الضغط على أرقام الصفحات
            ->paginate(20)
            ->appends($request->all())
            ;

        return view('ajax_search', compact('data'));
    }

    public function searchChooseProductpaginatenew($searchtext, $branchs_id)
    {
        $data = products::where('branchs_id', $branchs_id)
            ->where(function ($query) use ($searchtext) {
                $query->where('product_name', 'LIKE', '%' . $searchtext . '%')
                    ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%')
                    ->orWhere('refnumber', 'LIKE', '%' . $searchtext . '%');
            })->paginate(20);

        return view('ajax_choose_product', compact('data'));
    }

    public function ChooseProductpaginatenew($branchs_id)
    {
        $data = products::where('branchs_id', $branchs_id)->paginate(20);
        return view('ajax_choose_product', compact('data'));
    }

    public function searchChooseProductpaginatenewSale($searchtext, $branchs_id)
    {

        if ($branchs_id == '-') {
            $data = products::where('product_name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('refnumber', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('notes', 'LIKE', '%' . $searchtext . '%')
                ->paginate(20);
        } else {
            $data = products::where('branchs_id', $branchs_id)
                ->where(function ($query) use ($searchtext) {
                    $query->where('product_name', 'LIKE', '%' . $searchtext . '%')
                        ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%')
                        ->orWhere('refnumber', 'LIKE', '%' . $searchtext . '%');
                })->paginate(20);
        }

        return view('ajax_choose_product_sale', compact('data'))
            ->with('currentrow', 1)
            ->with('getLocale', LaravelLocalization::getCurrentLocale());
    }















    public function searchChooseProductpaginatenewSaleBypost(Request $request)
    {

        $searchtext = $request->searchtext;
        $branchs_id = $request->branchs_id;
        $searchTerm = '%' . $searchtext . '%';

        if ($branchs_id == '-') {
            $data = products::where(function ($query) use ($searchTerm) {
                $query->where('product_name', 'LIKE', $searchTerm)
                    ->orWhere('Product_Code', 'LIKE', $searchTerm)
                    ->orWhere('refnumber', 'LIKE', $searchTerm)
                    ->orWhere('notes', 'LIKE', $searchTerm);
            })->paginate(20);

            return view('ajax_choose_product_sale', compact('data'))
                ->with('currentrow', $request->currentrow);
        }

        $data = products::where('branchs_id', $branchs_id)
            ->where(function ($query) use ($searchTerm) {
                $query->where('product_name', 'LIKE', $searchTerm)
                    ->orWhere('Product_Code', 'LIKE', $searchTerm)
                    ->orWhere('refnumber', 'LIKE', $searchTerm)
                    ->orWhere('notes', 'LIKE', $searchTerm);
            })->paginate(20);

        // إرجاع الـ view مرة واحدة فقط في النهاية لكلتا الحالتين
 return view('ajax_choose_product_sale', compact('data'))
    ->with('currentrow', $request->currentrow)
    ->with('getLocale', $request->input('locale'));
    }

    public function processOrderPurchase(Request $request, $date, $payment, $supplier, $shipping, $branchs_id, $another_bank)
    {
        // إعداد التاريخ والوقت بناءً على قيمة المتغير الممرر
        $createdAt = $date != '0' ? $date . ' ' . substr(now()->addHours(3), 12) : now()->addHours(3);

        orderTosupllier::find($request)->update([
            'Limit_credit' => $payment,
            'suplier_id' => $supplier
        ]);

        orderDetails::where('order_owner', $request)
            ->where('numberofpice', '!=', 0)
            ->update(['save' => 1]);

        // جلب تفاصيل الطلب مرة واحدة لتجنب تكرار الاستعلام داخل الحلقات
        $activeOrderDetails = orderDetails::where('order_owner', $request)
            ->where('numberofpice', '!=', 0)
            ->get();

        $total_number_items = $activeOrderDetails->sum('numberofpice');
        $cost_shipping_per_item = $total_number_items > 0 ? round($shipping / $total_number_items, 3) : 0;

        foreach ($activeOrderDetails as $item) {
            $updateProduct = products::find($item->product_id);

            if (($updateProduct->numberofpice + $item->numberofpice) <= 0) {
                $cost = $item->purchasingـprice + $cost_shipping_per_item;
            } else {
                $cost = round(((($item->purchasingـprice + $cost_shipping_per_item) * $item->numberofpice) + ($updateProduct->purchasingـprice * $updateProduct->numberofpice)) / ($updateProduct->numberofpice + $item->numberofpice), 2);
            }

            products::where('id', $item->product_id)->update([
                'purchasingـprice' => $item->purchasingـprice + $cost_shipping_per_item,
                'average_cost' => $cost,
                'sale_price' => $item->sale_price,
                'numberofpice' => $updateProduct->numberofpice + $item->numberofpice,
            ]);

            // تحديث الكمية المتبقية بناءً على آخر عنصر يتم معالجته في الحلقة
            orderDetails::find($item->id)->update([
                'reamingQuantity' => $updateProduct->numberofpice + $item->numberofpice
            ]);
        }

        $resource_purchases = resource_purchases::where('orderId', $request)->first();
        $paymentMethod = $payment;

        // معالجة مصاريف الشحن والتفريغ
        if ($shipping > 0) {
            $financial_accounts = financial_accounts::find(133);
            financial_accounts::find(133)->update([
                'current_balance' => $financial_accounts->current_balance + $shipping,
                'debtor_current' => $financial_accounts->debtor_current + $shipping,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 133,
                'recive_amount' => $shipping,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance + $shipping,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $shipping,
            ]);
        }

        // معالجة الدفع الآجل (Credit)
        if ($payment == "Credit") {
            $supplierData = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update([
                'In_debt' => $supplierData->In_debt + $resource_purchases->In_debt
            ]);

            $financial_accounts = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->first();

            financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->update([
                    'current_balance' => $financial_accounts->current_balance + $resource_purchases->In_debt,
                    'creditor_current' => $financial_accounts->creditor_current + $resource_purchases->In_debt,
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $resource_purchases->In_debt,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance + $resource_purchases->In_debt,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $resource_purchases->In_debt,
                'debtor' => 0,
            ]);

            $financial_accounts = financial_accounts::find(4);
            financial_accounts::find(4)->update([
                'current_balance' => $financial_accounts->current_balance - $shipping,
                'creditor_current' => $financial_accounts->creditor_current + $shipping,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 4,
                'recive_amount' => $shipping,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات مصريف شحن و تفريغ رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance - $shipping,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $shipping,
                'debtor' => 0,
            ]);

            $financial_accounts = financial_accounts::where('parent_account_number', 4)
                ->where('branchs_id', $branchs_id)
                ->first();

            financial_accounts::where('parent_account_number', 4)
                ->where('branchs_id', $branchs_id)
                ->update([
                    'current_balance' => $financial_accounts->current_balance - $shipping,
                    'creditor_current' => $financial_accounts->creditor_current + $shipping,
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $shipping,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance - $shipping,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $shipping,
                'debtor' => 0,
            ]);
        }

        $value_total = ($resource_purchases->In_debt + $shipping);

        // معالجة الدفع النقدي (Cash)
        if ($resource_purchases->Pay_Method_Name == 'Cash') {
            $financial_accounts = financial_accounts::find(5);
            financial_accounts::find(5)->update([
                'current_balance' => $financial_accounts->current_balance - $value_total,
                'creditor_current' => $financial_accounts->creditor_current + $value_total,
            ]);

            $customer_id_suplier = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->first();

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer_id_suplier->id,
                'recive_amount' => $resource_purchases->In_debt,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance + $resource_purchases->In_debt,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => 0,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 5,
                'recive_amount' => $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance - $value_total,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $value_total,
                'debtor' => 0,
            ]);

            $financial_accounts = financial_accounts::where('parent_account_number', 5)
                ->where('branchs_id', $branchs_id)
                ->first();

            financial_accounts::where('parent_account_number', 5)
                ->where('branchs_id', $branchs_id)
                ->update([
                    'current_balance' => $financial_accounts->current_balance - $value_total,
                    'creditor_current' => $financial_accounts->creditor_current + $value_total,
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance - $value_total,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $value_total,
                'debtor' => 0,
            ]);
        }

        // معالجة الدفع عن طريق التحويل البنكي أو الشبكة
        if ($resource_purchases->Pay_Method_Name == 'Bank_transfer' || $resource_purchases->Pay_Method_Name == 'Shabka') {
            $customer_id_suplier = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->first();

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer_id_suplier->id,
                'recive_amount' => $resource_purchases->In_debt,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => 0,
            ]);

            $financial_accounts = financial_accounts::find(4);
            $value_total = ($resource_purchases->In_debt + $shipping);

            financial_accounts::find(4)->update([
                'current_balance' => $financial_accounts->current_balance - $value_total,
                'creditor_current' => $financial_accounts->creditor_current + $value_total,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => 4,
                'recive_amount' => $resource_purchases->In_debt - $value_total,
                'branchs_id' => $branchs_id,
                'pay_method' => $payment,
                'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                'currentblance' => $financial_accounts->current_balance - $value_total,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => now()->addHours(3),
                'updated_at' => now()->addHours(3),
                'orginal_id' => 0,
                'creditor' => $value_total,
                'debtor' => 0,
            ]);

            if ($another_bank == 4) {
                $financial_accounts = financial_accounts::where('id', 1157)->first();
                financial_accounts::where('id', 1157)->update([
                    'current_balance' => $financial_accounts->current_balance - $value_total,
                    'creditor_current' => $financial_accounts->creditor_current + $value_total,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $financial_accounts->id,
                    'recive_amount' => $value_total,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $payment,
                    'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                    'currentblance' => $financial_accounts->current_balance - $value_total,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => now()->addHours(3),
                    'updated_at' => now()->addHours(3),
                    'orginal_id' => 0,
                    'creditor' => $value_total,
                    'debtor' => 0,
                ]);
            } else {
                $financial_accounts = financial_accounts::where('parent_account_number', 4)
                    ->where('branchs_id', $branchs_id)
                    ->first();

                financial_accounts::where('parent_account_number', 4)
                    ->where('branchs_id', $branchs_id)
                    ->update([
                        'current_balance' => $financial_accounts->current_balance - $value_total,
                        'creditor_current' => $financial_accounts->creditor_current + $value_total,
                    ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $financial_accounts->id,
                    'recive_amount' => $value_total,
                    'branchs_id' => $branchs_id,
                    'pay_method' => $payment,
                    'note' => ' فاتورة مشتريات رقم :' . (string) $request,
                    'currentblance' => $financial_accounts->current_balance - $value_total,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => now()->addHours(3),
                    'updated_at' => now()->addHours(3),
                    'orginal_id' => 0,
                    'creditor' => $value_total,
                    'debtor' => 0,
                ]);
            }
        }

        // حسابات الضرائب والقيم المضافة المحدثة
        $total_value = $resource_purchases->In_debt;
        $avtSaleRate = Avt::find(2);
        $vat_purchase_rate = $avtSaleRate->AVT;
        $tax_value = 0;
        $total_cost = 0;

        if ($vat_purchase_rate == 0) {
            $total_cost = $total_value;
        } else {
            $vat_purchase_rate = ($vat_purchase_rate * 100) + 100;
            $total_cost = $total_value * 100 / $vat_purchase_rate;
            $tax_value = $total_value - $total_cost;
        }

        $financial_accounts = financial_accounts::find(102);
        financial_accounts::find(102)->update([
            'current_balance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current - $tax_value),
            'debtor_current' => $financial_accounts->debtor_current + $tax_value,
        ]);

        $customerdata = supllier::find($resource_purchases->suplier_id);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => 102,
            'recive_amount' => $tax_value,
            'branchs_id' => $branchs_id,
            'pay_method' => $paymentMethod,
            'note' => ' فاتورة مشتريات رقم :' . (string) $request,
            'currentblance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current - $tax_value),
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => now()->addHours(3),
            'updated_at' => now()->addHours(3),
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $tax_value,
            'vat' => 1,
            'name' => $customerdata->name,
            'tax' => $customerdata->TaxـNumber, // الحفاظ على الحرف الخاص بالكلمة
        ]);

        $financial_accounts = financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', $branchs_id)
            ->first();

        financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', $branchs_id)
            ->update([
                'current_balance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current - $tax_value),
                'debtor_current' => $financial_accounts->debtor_current + $tax_value,
            ]);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $financial_accounts->id,
            'recive_amount' => $tax_value,
            'branchs_id' => $branchs_id,
            'pay_method' => $paymentMethod,
            'note' => ' فاتورة مشتريات رقم :' . (string) $request,
            'currentblance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current - $tax_value),
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => now()->addHours(3),
            'updated_at' => now()->addHours(3),
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $tax_value,
            'vat' => 1,
            'name' => $customerdata->name,
            'tax' => $customerdata->TaxـNumber,
        ]);

        return 1;
    }


















    public function returnAllpurchase(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderNumber = $request->ordernumber;
        $resource_purchases = resource_purchases::where('orderId', $orderNumber)->first();
        $discount = $resource_purchases->discount;

        $payment = 'Cash';
        $paymentMethod = $payment;
        $total_cost = 0;
        $tax_value = 0;
        $vatrat = 0;
        $total_pieces = 0;
        $current_user_branch = auth()->user()->branchs_id;
        $current_time = now()->addHours(3);

        // جلب تفاصيل المنتجات النشطة في الطلب لتجنب تكرار الاستعلامات
        $activeOrderDetails = orderDetails::where('numberofpice', '!=', 0)
            ->where('order_owner', $orderNumber)
            ->get();

        foreach ($activeOrderDetails as $item) {
            $productData = products::find($item->product_id);

            // تحديث مخزون المنتج الأصلي بالخصم منه
            products::where('id', $item->product_id)->update([
                'numberofpice' => $productData->numberofpice - $item->numberofpice,
            ]);

            $total_pieces += $item->numberofpice;

            // تحديث تفاصيل الطلب لإثبات المرتجع وتصفير الكمية الحالية
            orderDetails::where('product_id', $item->product_id)
                ->where('order_owner', $orderNumber)
                ->update([
                    'returns_purchase' => $item->returns_purchase + $item->numberofpice,
                    'numberofpice' => 0,
                    'updated_at' => $current_time,
                ]);

            // التحقق من السعر لمنع خطأ القسمة على صفر
            $vatrat = $item->purchasingـprice > 0 ? round($item->Added_Value / $item->purchasingـprice, 2) : 0;
            $total_cost += round(($item->purchasingـprice * $item->numberofpice), 2);
        }

        $total_cost -= round($discount, 2);
        $tax_value = round($total_cost * $vatrat, 2);
        $grand_total = $total_cost + $tax_value;

        // معالجة المرتجع بناءً على طريقة دفع الفاتورة الأصلية
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $orderNumber)->update([
                'recoveredـpieces' => $total_pieces,
                'In_debt' => $resource_purchases->In_debt - $grand_total,
                'updated_at' => $current_time,
            ]);

            $resource_purchases = resource_purchases::where('orderId', $orderNumber)->first();
            $financial_accounts = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->first();

            supllier::where('id', $resource_purchases->suplier_id)->update([
                'In_debt' => $financial_accounts->current_balance - $grand_total
            ]);

            financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->update([
                    'current_balance' => $financial_accounts->current_balance - $grand_total,
                    'debtor_current' => $financial_accounts->debtor_current + $grand_total,
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $grand_total,
                'branchs_id' => $current_user_branch,
                'pay_method' => $payment,
                'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
                'currentblance' => $financial_accounts->current_balance - $grand_total,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $current_time,
                'updated_at' => $current_time,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $grand_total,
            ]);



        } else {
            resource_purchases::where('orderId', $orderNumber)->update([
                'recoveredـpieces' => $total_pieces,
                'In_debt' => $resource_purchases->In_debt - $grand_total,
                'updated_at' => $current_time,
            ]);


            $financial_accounts = financial_accounts::where('parent_account_number', 5)
                ->where('branchs_id', $current_user_branch)
                ->first();

            financial_accounts::where('parent_account_number', 5)
                ->where('branchs_id', $current_user_branch)
                ->update([
                    'current_balance' => $financial_accounts->current_balance + $grand_total,
                    'debtor_current' => $financial_accounts->debtor_current + $grand_total
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $grand_total,
                'branchs_id' => $current_user_branch,
                'pay_method' => 'Cash',
                'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
                'currentblance' => $financial_accounts->current_balance + $grand_total,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $current_time,
                'updated_at' => $current_time,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $grand_total,
            ]);

            $financial_accounts = financial_accounts::where('parent_account_number', 181)
                ->where('branchs_id', $current_user_branch)
                ->first();

            financial_accounts::where('parent_account_number', 181)
                ->where('branchs_id', $current_user_branch)
                ->update([
                    'creditor_current' => $financial_accounts->creditor_current + $total_cost
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $total_cost,
                'branchs_id' => $current_user_branch,
                'pay_method' => 'Cash',
                'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
                'currentblance' => $financial_accounts->current_balance + $total_cost,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $current_time,
                'updated_at' => $current_time,
                'orginal_id' => 0,
                'creditor' => $total_cost,
                'debtor' => 0,
            ]);
        }

        // تسوية حسابات الضرائب والقيمة المضافة للمرتجعات


        $customerdata = supllier::find($resource_purchases->suplier_id);


        $financial_accounts = financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', $current_user_branch)
            ->first();

        financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', $current_user_branch)
            ->update([
                'current_balance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current + $tax_value),
                'creditor_current' => $financial_accounts->creditor_current + $tax_value,
            ]);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $financial_accounts->id,
            'recive_amount' => $tax_value,
            'branchs_id' => $current_user_branch,
            'pay_method' => 'Cash',
            'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
            'currentblance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current + $tax_value),
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $current_time,
            'updated_at' => $current_time,
            'orginal_id' => 0,
            'creditor' => $tax_value,
            'debtor' => 0,
            'vat' => 1,
            'name' => $customerdata->name,
            'tax' => $customerdata->TaxـNumber,
        ]);



        // تجهيز بيانات العرض للمرتجع
        $orderOwner = orderTosupllier::find($orderNumber);
        $orderdetails = orderDetails::where('order_owner', $orderNumber)->get();
        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name;
        $resource_purchases = resource_purchases::where('orderId', $orderNumber)->first();

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'resource_purchases' => $resource_purchases,
            'product' => $orderdetails
        ];

        return view('response_return_purchases', compact('data'));
    }
















    public function update(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $productId = $request->id;
        $orderNumber = $request->ordernumber;
        $returnQuantity = $request->return_quentity;

        $orderDetails = orderDetails::where('product_id', $productId)
            ->where('order_owner', $orderNumber)
            ->first();

        $resource_purchases = resource_purchases::where('orderId', $orderNumber)->first();

        $payment = 'Cash';
        $paymentMethod = $payment;
        $discount = 0;
        $current_user_branch = auth()->user()->branchs_id;
        $current_time = now()->addHours(3);

        // تحديث مخزون المنتج الأصلي
        $productData = products::find($productId);
        products::where('id', $productId)->update([
            'numberofpice' => $productData->numberofpice - $returnQuantity,
        ]);

        // تحديث تفاصيل الطلب بالكميات المرتجعة الجديدة
        orderDetails::where('product_id', $productId)
            ->where('order_owner', $orderNumber)
            ->update([
                'returns_purchase' => $orderDetails->returns_purchase + $returnQuantity,
                'numberofpice' => $orderDetails->numberofpice - $returnQuantity,
                'updated_at' => $current_time,
            ]);

        $i = 1;
        $vatrat = 0;

        foreach (orderDetails::where('order_owner', $orderNumber)->get() as $item) {
            // فحص أمان لتجنب خطأ القسمة على صفر
            $vatrat = $item->purchasingـprice > 0 ? round($item->Added_Value / $item->purchasingـprice, 2) : 0;

            if ($item->numberofpice >= 1) {
                $i = 0;
            }
        }

        // تطبيق الخصم بالكامل في حال تم إرجاع الفاتورة بالكامل
        if ($i == 1) {
            $discount = $resource_purchases->discount;
        }

        $total_cost = round(($orderDetails->purchasingـprice * $returnQuantity) - $discount, 2);
        $tax_value = round($total_cost * $vatrat, 2);

        // معالجة الحسابات المالية بناءً على طريقة دفع الفاتورة الأصلية
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            resource_purchases::where('orderId', $orderNumber)->update([
                'recoveredـpieces' => $resource_purchases->recoveredـpieces + $returnQuantity,
                'In_debt' => ($resource_purchases->In_debt - ($tax_value + $total_cost)),
                'updated_at' => $current_time,
            ]);

            $resource_purchases = resource_purchases::where('orderId', $orderNumber)->first();

            $financial_accounts = financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->first();

            supllier::where('id', $resource_purchases->suplier_id)->update([
                'In_debt' => $financial_accounts->current_balance - ($total_cost + $tax_value)
            ]);

            financial_accounts::where('orginal_type', 2)
                ->where('orginal_id', $resource_purchases->suplier_id)
                ->update([
                    'current_balance' => $financial_accounts->current_balance - ($total_cost + $tax_value),
                    'debtor_current' => $financial_accounts->debtor_current + ($total_cost + $tax_value),
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => ($total_cost + $tax_value),
                'branchs_id' => $current_user_branch,
                'pay_method' => $payment,
                'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
                'currentblance' => $financial_accounts->current_balance - ($total_cost + $tax_value),
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $current_time,
                'updated_at' => $current_time,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => ($total_cost + $tax_value),
            ]);



            $financial_accounts = financial_accounts::where('parent_account_number', 181)
                ->where('branchs_id', $current_user_branch)
                ->first();

            financial_accounts::where('parent_account_number', 181)
                ->where('branchs_id', $current_user_branch)
                ->update([
                    'creditor_current' => $financial_accounts->creditor_current + $total_cost
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $total_cost,
                'branchs_id' => $current_user_branch,
                'pay_method' => 'Cash',
                'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
                'currentblance' => $financial_accounts->current_balance + $total_cost,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $current_time,
                'updated_at' => $current_time,
                'orginal_id' => 0,
                'creditor' => $total_cost,
                'debtor' => 0,
            ]);
        } else {
            resource_purchases::where('orderId', $orderNumber)->update([
                'recoveredـpieces' => $resource_purchases->recoveredـpieces + $returnQuantity,
                'In_debt' => $resource_purchases->In_debt - ($total_cost + $tax_value),
                'updated_at' => $current_time,
            ]);


            $financial_accounts = financial_accounts::where('parent_account_number', 5)
                ->where('branchs_id', $current_user_branch)
                ->first();

            financial_accounts::where('parent_account_number', 5)
                ->where('branchs_id', $current_user_branch)
                ->update([
                    'current_balance' => $financial_accounts->current_balance + ($total_cost + $tax_value),
                    'debtor_current' => $financial_accounts->debtor_current + ($total_cost + $tax_value)
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => ($total_cost + $tax_value),
                'branchs_id' => $current_user_branch,
                'pay_method' => 'Cash',
                'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
                'currentblance' => $financial_accounts->current_balance + ($total_cost + $tax_value),
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $current_time,
                'updated_at' => $current_time,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => ($total_cost + $tax_value),
            ]);

            $financial_accounts = financial_accounts::where('parent_account_number', 181)
                ->where('branchs_id', $current_user_branch)
                ->first();

            financial_accounts::where('parent_account_number', 181)
                ->where('branchs_id', $current_user_branch)
                ->update([
                    'creditor_current' => $financial_accounts->creditor_current + $total_cost
                ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $total_cost,
                'branchs_id' => $current_user_branch,
                'pay_method' => 'Cash',
                'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
                'currentblance' => $financial_accounts->current_balance + ($total_cost + $tax_value),
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $current_time,
                'updated_at' => $current_time,
                'orginal_id' => 0,
                'creditor' => $total_cost,
                'debtor' => 0,
            ]);
        }

        // تسوية ضرائب القيمة المضافة لعملية المرتجع الحالي

        $customerdata = supllier::find($resource_purchases->suplier_id);



        $financial_accounts = financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', $current_user_branch)
            ->first();

        financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', $current_user_branch)
            ->update([
                'current_balance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current + $tax_value),
                'creditor_current' => $financial_accounts->creditor_current + $tax_value,
            ]);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $financial_accounts->id,
            'recive_amount' => $tax_value,
            'branchs_id' => $current_user_branch,
            'pay_method' => 'Cash',
            'note' => ' مرتجع مشتريات فاتورة رقم :' . (string) $orderNumber,
            'currentblance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current + $tax_value),
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $current_time,
            'updated_at' => $current_time,
            'orginal_id' => 0,
            'creditor' => $tax_value,
            'debtor' => 0,
            'vat' => 1,
            'name' => $customerdata->name,
            'tax' => $customerdata->TaxـNumber,
        ]);

        // تجهيز مصفوفة البيانات لإرسالها لعرض المرتجع (الـ View)
        $orderOwner = orderTosupllier::find($orderNumber);
        $orderdetails = orderDetails::where('order_owner', $orderNumber)->get();

        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name;
        $resource_purchases = resource_purchases::where('orderId', $orderNumber)->first();

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'resource_purchases' => $resource_purchases,
            'product' => $orderdetails
        ];

        return view('response_return_purchases', compact('data'));
    }







    public function updateproductalldatapurchases(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::find($request->id);
        $orderOwner = $orderDetails->order_owner;
        $resource_purchases = resource_purchases::where('orderId', $orderOwner)->first();

        // خصم القيمة القديمة من المديونية (تطبق على النقدي والآجل حسب منطق الكود القديم)
        $old_total = ($orderDetails->purchasingـprice * $orderDetails->numberofpice) + ($orderDetails->Added_Value * $orderDetails->numberofpice);

        resource_purchases::where('orderId', $orderOwner)->update([
            'In_debt' => $resource_purchases->In_debt - $old_total
        ]);

        // تصفير الكمية الحالية مؤقتاً قبل التحديث الجديد
        orderDetails::where('id', $request->id)->update([
            'numberofpice' => 0
        ]);

        $avtPurcheseRate = Avt::find(2);

        // إعادة قراءة السجل المحدث
        $resource_purchases = resource_purchases::where('orderId', $orderOwner)->first();

        // حساب القيمة الإجمالية والضريبية الجديدة بناءً على المدخلات الجديدة
        $new_subtotal = $request->pricepurchases * $request->quantity;
        $new_tax = $new_subtotal * $avtPurcheseRate->AVT;
        $new_total = $new_subtotal + $new_tax;

        resource_purchases::where('orderId', $orderOwner)->update([
            'In_debt' => $resource_purchases->In_debt + $new_total
        ]);

        // تحديث تفاصيل الصنف بالبيانات المدخلة الجديدة
        orderDetails::where('id', $request->id)->update([
            'numberofpice' => $request->quantity,
            'purchasingـprice' => $request->pricepurchases,
            'Added_Value' => $request->pricepurchases * $avtPurcheseRate->AVT,
        ]);

        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم التعديل بنجاح' : 'has been modified successfully';
        session()->flash('editpurchasein', $message);

        $recentsupllier = supllier::find($resource_purchases->suplier_id);
        $orderdetails = orderDetails::where('order_owner', $orderOwner)->where('numberofpice', '>', 0)->get();

        $allProdctsD = [];
        $i = 0;
        foreach ($orderdetails as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->Product_Code,
                'product_name' => $product->productData->product_name,
                'quantity' => $product->numberofpice,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i,
                'id' => $product->id
            ];
        }

        return [
            'message' => $message,
            'pay' => $resource_purchases->Pay_Method_Name,
            'recentsupllier' => $recentsupllier,
            'product' => $allProdctsD,
            'discount' => $resource_purchases->discount,
            'shipping_fee' => $resource_purchases['shipping fee'],
        ];
    }

    public function increasePurchase(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::find($request->id);
        $orderOwner = $orderDetails->order_owner;
        $resource_purchases = resource_purchases::where('orderId', $orderOwner)->first();

        // حساب قيمة الزيادة الطارئة على الفاتورة
        $increase_cost = ($orderDetails->purchasingـprice * $request->increasequentity) + ($orderDetails->Added_Value * $request->increasequentity);

        resource_purchases::where('orderId', $orderOwner)->update([
            'In_debt' => $resource_purchases->In_debt + $increase_cost
        ]);

        orderDetails::where('id', $request->id)->update([
            'numberofpice' => $orderDetails->numberofpice + $request->increasequentity
        ]);

        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم التعديل بنجاح' : 'has been modified successfully';
        session()->flash('editpurchasein', $message);

        $recentsupllier = supllier::find($resource_purchases->suplier_id);
        $orderdetails = orderDetails::where('order_owner', $orderOwner)->where('numberofpice', '>', 0)->get();

        $allProdctsD = [];
        $i = 0;
        foreach ($orderdetails as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->Product_Code,
                'product_name' => $product->productData->product_name,
                'quantity' => $product->numberofpice,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i,
                'id' => $product->id,
            ];
        }

        return [
            'pay' => $resource_purchases->Pay_Method_Name,
            'recentsupllier' => $recentsupllier,
            'In_debt' => $resource_purchases->In_debt,
            'discount' => $resource_purchases->discount,
            'product' => $allProdctsD,
            'shipping_fee' => $resource_purchases['shipping fee'],
        ];
    }

    public function updatePurchaseOrder(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::where('order_owner', $request->ordernumber)
            ->where('product_id', $request->id)
            ->first();

        orderDetails::where('order_owner', $request->ordernumber)
            ->where('product_id', $request->id)
            ->update([
                'numberofpice' => $orderDetails->numberofpice - $request->return_quentity
            ]);

        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم التعديل بنجاح' : 'has been modified successfully';
        session()->flash('editpurchasein', $message);

        $orderdetails = orderDetails::where('order_owner', $request->ordernumber)->where('numberofpice', '>', 0)->get();

        $listOfProduct = [];
        $count = 0;
        $totalAdded_value = 0;
        $totalPrice = 0;

        foreach ($orderdetails as $orderitem) {
            $totalAdded_value += $orderitem->numberofpice * $orderitem->Added_Value;
            $totalPrice += $orderitem->numberofpice * $orderitem->purchasingـprice;
            $count++;

            $listOfProduct[] = [
                "count" => $count,
                "productCode" => $orderitem->productData->Product_Code,
                "product_name" => $orderitem->productData->product_name,
                "product_id" => $orderitem->product_id,
                "quantity" => $orderitem->numberofpice,
                "purchasingـprice" => $orderitem->purchasingـprice,
                "Added_Value" => $orderitem->Added_Value,
                "total" => ($orderitem->numberofpice * $orderitem->Added_Value) + ($orderitem->numberofpice * $orderitem->purchasingـprice),
                "orderNo" => $orderitem->order_owner,
                "totalAdded_Value" => $totalAdded_value,
                "totalPrice" => $totalPrice
            ];
        }

        return $listOfProduct;
    }

    public function updatePurchaseOrderToIncrease(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::where('order_owner', $request->ordernumber)
            ->where('product_id', $request->id)
            ->first();

        orderDetails::where('order_owner', $request->ordernumber)
            ->where('product_id', $request->id)
            ->update([
                'numberofpice' => $orderDetails->numberofpice + $request->increasequentity
            ]);

        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم التعديل بنجاح' : 'has been modified successfully';
        session()->flash('editpurchasein', $message);

        $orderdetails = orderDetails::where('order_owner', $request->ordernumber)->where('numberofpice', '>', 0)->get();

        $listOfProduct = [];
        $count = 0;
        $totalAdded_value = 0;
        $totalPrice = 0;

        foreach ($orderdetails as $orderitem) {
            $totalAdded_value += $orderitem->numberofpice * $orderitem->Added_Value;
            $totalPrice += $orderitem->numberofpice * $orderitem->purchasingـprice;
            $count++;

            $listOfProduct[] = [
                "count" => $count,
                "productCode" => $orderitem->productData->Product_Code,
                "product_name" => $orderitem->productData->product_name,
                "product_id" => $orderitem->product_id,
                "quantity" => $orderitem->numberofpice,
                "purchasingـprice" => $orderitem->purchasingـprice,
                "Added_Value" => $orderitem->Added_Value,
                "total" => ($orderitem->numberofpice * $orderitem->Added_Value) + ($orderitem->numberofpice * $orderitem->purchasingـprice),
                "orderNo" => $orderitem->order_owner,
                "totalAdded_Value" => $totalAdded_value,
                "totalPrice" => $totalPrice
            ];
        }

        return $listOfProduct;
    }

    public function updatePurchase(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $orderDetails = orderDetails::find($request->id);
        $orderOwner = $orderDetails->order_owner;
        $resource_purchases = resource_purchases::where('orderId', $orderOwner)->first();

        // حساب القيمة المستقطعة من المديونية جراء المرتجع الجزئي
        $deducted_amount = ($orderDetails->purchasingـprice * $request->return_quentity) + ($orderDetails->Added_Value * $request->return_quentity);

        resource_purchases::where('orderId', $orderOwner)->update([
            'In_debt' => $resource_purchases->In_debt - $deducted_amount
        ]);

        orderDetails::where('id', $request->id)->update([
            'numberofpice' => $orderDetails->numberofpice - $request->return_quentity
        ]);

        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم التعديل بنجاح' : 'has been modified successfully';
        session()->flash('editpurchasein', $message);

        $recentsupllier = supllier::find($resource_purchases->suplier_id);
        $orderdetails = orderDetails::where('order_owner', $orderOwner)->where('numberofpice', '>', 0)->get();

        $allProdctsD = [];
        $i = 0;
        foreach ($orderdetails as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->Product_Code,
                'product_name' => $product->productData->product_name,
                'quantity' => $product->numberofpice,
                'purchasingـprice' => $product->purchasingـprice,
                'Added_Value' => $product->Added_Value,
                'saleperpice' => $product->sale_price,
                'count' => $i,
                'id' => $product->id
            ];
        }

        return [
            'pay' => $resource_purchases->Pay_Method_Name,
            'recentsupllier' => $recentsupllier,
            'product' => $allProdctsD,
            'In_debt' => $resource_purchases->In_debt,
            'discount' => $resource_purchases->discount,
            'shipping_fee' => $resource_purchases['shipping fee'],
        ];
    }

    public function destroy(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $current_time = now()->addHours(3);

        $orderDetails = orderDetails::where('product_id', $request->id)
            ->where('product_name', $request->product_name)
            ->where('order_owner', $request->ordernumber)
            ->first();

        $resource_purchases = resource_purchases::where('orderId', $request->ordernumber)->first();
        $total_item_value = ($orderDetails->purchasingـprice * $orderDetails->numberofpice) + ($orderDetails->Added_Value * $orderDetails->numberofpice);

        // تحديث الفاتورة والمورد في حال الدفع الآجل
        if ($resource_purchases->Pay_Method_Name == 'Credit') {
            $supplier = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update([
                'In_debt' => $supplier->In_debt - $total_item_value
            ]);
        }

        resource_purchases::where('orderId', $request->ordernumber)->update([
            'recoveredـpieces' => $resource_purchases->recoveredـpieces + $orderDetails->numberofpice,
            'In_debt' => $resource_purchases->In_debt - $total_item_value,
            'updated_at' => $current_time,
        ]);

        // تحديث المخزون الفعلي للصنف الصادر
        $productData = products::find($request->id);
        products::where('id', $request->id)->update([
            'numberofpice' => $productData->numberofpice - $orderDetails->numberofpice,
        ]);

        // تحديث سجل تفاصيل الفاتورة وتصفير الكمية الحالية للصنف المحذوف
        orderDetails::where('product_id', $request->id)
            ->where('product_name', $request->product_name)
            ->where('order_owner', $request->ordernumber)
            ->update([
                'returns_purchase' => $orderDetails->returns_purchase + $orderDetails->numberofpice,
                'numberofpice' => 0,
                'updated_at' => $current_time,
            ]);

        // التحقق مما إذا كانت الفاتورة فارغة تماماً الآن لتسوية الخصم الممنوح
        $is_empty_invoice = 1;
        foreach (orderDetails::where('order_owner', $request->ordernumber)->get() as $item) {
            if ($item->numberofpice > 0) {
                $is_empty_invoice = 0;
            }
        }

        if ($resource_purchases->Pay_Method_Name == 'Credit' && $is_empty_invoice == 1) {
            $supplier = supllier::find($resource_purchases->suplier_id);
            supllier::where('id', $resource_purchases->suplier_id)->update([
                'In_debt' => $supplier->In_debt + $resource_purchases->discount
            ]);
        }

        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم حذف بنجاح' : 'has been deleted successfully';
        session()->flash('delete', $message);

        $orderOwner = orderTosupllier::find($request->ordernumber);
        $orderdetails = orderDetails::where('order_owner', $request->ordernumber)->get();
        $user = User::find($orderOwner->user_id);
        $branch = $user->branch->name;
        $resource_purchases = resource_purchases::where('orderId', $request->ordernumber)->first();

        $data = [
            'branch' => $branch,
            'supllier' => $orderOwner,
            'resource_purchases' => $resource_purchases,
            'product' => $orderdetails
        ];

        return view('products.purchase_return', compact('data'));
    }

    public function getProductdJsonDecode($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $product = products::find($id);

        return response()->json($product);
    }
}
