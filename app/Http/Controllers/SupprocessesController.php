<?php

namespace App\Http\Controllers;

use App\Models\ProductsDamage;
use App\Models\products;
use App\Models\supllier;
use App\Models\Cost_centers;
use App\Models\customers;
use App\Models\Expenses_reasons;
use App\Models\stock_update;
use App\Models\products_group;
use App\Models\financial_accounts;
use App\Models\branchs;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\StockUpdateTempleteExport;
use App\Imports\StockUpdateImport;
use Maatwebsite\Excel\Facades\Excel;

class SupprocessesController extends Controller
{


    public function importStockAjax(Request $request)
    {
        try {
            $rows = Excel::toCollection(new StockUpdateImport, $request->file('excel_file'))->first();
            $selectedBranchId = $request->input('branch_id');
            $allProdctsD = [];

            foreach ($rows as $row) {
                // البحث عن المنتج في الفرع المحدد
                $product = products::where('Product_Code', $row['product_code'])
                    ->where('branchs_id', $selectedBranchId)
                    ->first();

                if ($product) {
                    $allProdctsD[] = [
                        'product_id' => $product->id,
                        'Product_Code' => $product->Product_Code,
                        'product_name' => $product->product_name,
                        'available_quantity' => $product->numberofpice, // الكمية الحالية في البرنامج
                        'inventory_quantity' => $row['quantity'] ?? 0,   // الكمية التي تم جردها في الإكسيل
                    ];
                }
            }

            return response()->json(['success' => true, 'data' => $allProdctsD]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new StockUpdateTempleteExport, 'purchase_template.xlsx');
    }




















    public function __construct()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    }

    public function index()
    {
        return view('supProcesses.addProduct');
    }

    public function Goupdatecustomer()
    {
        return view('supProcesses.updatecustomer');
    }

    public function Goupdatesupplier()
    {
        return view('supProcesses.updatesupplier');
    }

    public function show_groups()
    {
        return view('supProcesses.show_groups');
    }

    public function expenses_reason()
    {
        return view('supProcesses.enpenses_reason');
    }

    /**
     * إنشاء مركز التكلفة بطريقة آمنة مع الـ Redirect
     */
    public function create_expenses_reason(Request $request)
    {
        $request->validate([
            'breanchName' => 'required|string|max:255',
        ]);

        $costCenter = Cost_centers::create([
            'cost_center_ar' => $request->breanchName,
            'cost_center_en' => $request->expenses_reason_en ?? $request->breanchName,
            'expensesAvt' => 0,
            'created_at' => Carbon::now()->addHours(3)
        ]);

        if ($costCenter) {
            session()->flash('Cost_center_created_successfully', 'تم انشاء مركز التكلفة بنجاح');
        } else {
            session()->flash('notcreate', 'حدثت مشكلة اثناء انشاء مركز التكلفة');
        }

        return redirect()->back();

    }

    /**
     * دالة رفع الصور المحسنة والمؤمنة
     */
    protected function uploadImage($folder, $image)
    {
        $extension = strtolower($image->getClientOriginalExtension());
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $image->move(public_path($folder), $filename);
        return $filename;
    }

    public function create_products_group(Request $request)
    {
        $request->validate([
            'groub_ar' => 'required|string',
            'groub_en' => 'nullable|string',
        ]);

        return products_group::create([
            'group_en' => $request->groub_en,
            'group_ar' => $request->groub_ar
        ]);
    }

    /**
     * إضافة منتج جديد لكافة الفروع بشكل متزامن
     */
    public function create_addnewProduct(Request $request)
    {
        $request->validate([
            'product_name_ar' => 'required',
            'Section' => 'required',
            'product_location' => 'required',
            'type' => 'nullable|string',
            'brand' => 'nullable|string',
            'minmum_quantity_stock_alart' => 'required|numeric',
            'Item_img' => 'nullable|mimes:png,jpg,jpeg|max:2000',
        ]);

        $photo = 'productunKnown.png';
        if ($request->hasFile('Item_img')) {
            $photo = $this->uploadImage('assets/admin/uploads', $request->file('Item_img'));
        }

        DB::transaction(function () use ($request, $photo) {
            foreach (branchs::all() as $branch) {
                $checkProduct = products::where('branchs_id', $branch->id)
                    ->where('Product_Code', $request->product_code)
                    ->exists();

                if (!$checkProduct) {
                    $lastProduct = products::orderBy('id', 'DESC')->first();
                    $productCode = $request->product_code ?? ($lastProduct ? $lastProduct->id + 1 : 1);

                    $newProduct = products::create([
                        'product_name' => $request->product_name_ar . " " . $request->product_name_en,
                        'name_en' => $request->product_name_en ?? '',
                        'branchs_id' => $branch->id,
                        'user_id' => auth()->user()->id,
                        'Product_Location' => $request->product_location,
                        'Product_Code' => $productCode,
                        'Status' => 1,
                        'notes' => $request->product_notes ?? "-",
                        'unit' => $request->unit,
                        'type' => $request->type,
                        'brand' => $request->brand,
                        'product_group' => $request->product_group,
                        'refnumber' => $request->refnumber,
                        'minmum_quantity_stock_alart' => $request->minmum_quantity_stock_alart,
                        'photo' => $photo,
                    ]);

                    $newProduct->update([
                        'main_product' => $request->MAINproduct == 0 ? $newProduct->id : $request->MAINproduct,
                    ]);
                }
            }
        });

        $message = app()->getLocale() == 'ar' ? 'تمت العملية بنجاح' : 'Process completed successfully';
        session()->flash('addProduct', $message);

        return redirect()->back();
    }

    /**
     * إضافة منتج جديد عبر الـ Ajax مع حماية الاستعلامات
     */
    public function addnewProductajax(Request $request)
    {
        $request->validate([
            'product_name_ar' => 'required',
            'Section' => 'required',
            'product_location' => 'required',
            'type' => 'nullable|string',
            'brand' => 'nullable|string',
            'minmum_quantity_stock_alart' => 'required',
        ]);

        $addedSuccessfully = false;

        DB::transaction(function () use ($request, &$addedSuccessfully) {
            foreach (branchs::all() as $branch) {
                $checkProduct = products::where('branchs_id', $branch->id)
                    ->where('Product_Code', $request->product_code)
                    ->exists();

                if ($checkProduct) {
                    continue;
                }

                $lastProduct = products::orderBy('id', 'DESC')->first();
                $productCode = $request->product_code ?? ($lastProduct ? $lastProduct->id + 1 : 1);

                $newProduct = products::create([
                    'product_name' => $request->product_name_ar . " " . $request->product_name_en,
                    'name_en' => $request->product_name_en,
                    'branchs_id' => $branch->id,
                    'user_id' => auth()->user()->id,
                    'Product_Location' => $request->product_location,
                    'Product_Code' => $productCode,
                    'Status' => 1,
                    'product_group' => $request->product_group,
                    'notes' => $request->product_notes ?? '-',
                    'refnumber' => $request->refnumber,
                    'unit' => $request->unit,
                    'type' => $request->type,
                    'brand' => $request->brand,
                    'minmum_quantity_stock_alart' => $request->minmum_quantity_stock_alart,
                    'purchasingـprice' => $request->cost_price, // تم الإبقاء على مسمى عمود قاعدة بياناتك الحالي
                    'sale_price' => $request->sale_price_create,
                    'numberofpice' => $request->numberofpice,
                ]);

                $newProduct->update([
                    'main_product' => $request->MAINproduct == 0 ? $newProduct->id : $request->MAINproduct,
                ]);

                $addedSuccessfully = true;
            }
        });

        if ($addedSuccessfully) {
            return [app()->getLocale() == 'ar' ? 'تم اضافة المنتج بنجاح' : 'Product added successfully'];
        }

        return 0;
    }

    /**
     * تحديث وتسوية كميات المخازن - محمية بالكامل بالـ Database Transactions
     */
    public function stock_update(Request $request)
    {
        if (!$request->has('productNo') || !is_array($request->productNo)) {
            return redirect()->back()->withErrors(['error' => 'No products selected']);
        }

        $updatedproduct = false;

        DB::transaction(function () use ($request, &$updatedproduct) {
            foreach ($request->productNo as $key => $productId) {
                // استخدام Lock للسطر الحالي لمنع الـ Race Condition أثناء الجرد والتعديل بالتزامن
                $productdata = products::lockForUpdate()->find($productId);

                if (!$productdata)
                    continue;

                $decrease = $request->productdecrease[$key] ?? 0;
                $increase = $request->productincrease[$key] ?? 0;
                $note = $request->note[$key] ?? '';

                if ($decrease > 0) {
                    $newQty = $productdata->numberofpice - $decrease;

                    stock_update::create([
                        'productdecrease' => $decrease,
                        'productincrease' => 0,
                        'product_id' => $productId,
                        'product_name' => $newQty,
                        'branchs_id' => auth()->user()->branchs_id,
                        'user_id' => auth()->user()->id,
                        'created_at' => Carbon::now(),
                        'note' => $note,
                    ]);

                    $productdata->update(['numberofpice' => $newQty]);
                    $updatedproduct = true;
                }

                if ($increase > 0) {
                    $newQty = $productdata->numberofpice + $increase;

                    $productdata->update(['numberofpice' => $newQty]);

                    stock_update::create([
                        'productdecrease' => 0,
                        'productincrease' => $increase,
                        'product_id' => $productId,
                        'product_name' => $newQty,
                        'branchs_id' => auth()->user()->branchs_id,
                        'user_id' => auth()->user()->id,
                        'created_at' => Carbon::now(),
                        'note' => $note,
                    ]);
                    $updatedproduct = true;
                }
            }
        });

        if ($updatedproduct) {
            $message = app()->getLocale() == 'ar' ? 'تم تعديل كميات المنتجات بنجاح' : "Products quantities modified successfully.";
            session()->flash('productupdated', $message);
        }

        return redirect()->back();
    }

    /**
     * تحديث بيانات المنتج ومزامنة الفروع
     */
    public function update_product_movement(Request $request)
    {
        $productId = $request->product_no;
        $request->validate([
            'Item_img' => 'nullable|mimes:png,jpg,jpeg|max:2000',
        ]);

        $photo = $request->hasFile('Item_img')
            ? $this->uploadImage('assets/admin/uploads', $request->file('Item_img'))
            : ($request->old_photo ?? 'productunKnown.png');

        $updateData = [
            'Product_Location' => $request->new_location,
            'product_name' => $request->productnameshow,
            'main_product' => $request->MAINproduct == 0 ? $productId : $request->MAINproduct,
            'Product_Code' => $request->productcode,
            'refnumber' => $request->refnumber,
            'notes' => $request->product_notes ?? ' ',
            'photo' => $photo,
        ];

        if ($request->filled('product_group')) {
        $updateData['product_group'] = $request->product_group;
        }

        if ($request->has('product_price'))
            $updateData['sale_price'] = $request->product_price;
        if ($request->has('purachesepice'))
            $updateData['purchasingـprice'] = $request->purachesepice;
        if ($request->has('Wholesale_price'))
            $updateData['Wholesale_price'] = $request->Wholesale_price;

        DB::transaction(function () use ($productId, $updateData, $request) {
            products::where('id', $productId)->update($updateData);

            if ($request->hasAny(['product_price', 'purachesepice'])) {
                $sharedUpdate = [];
                if ($request->has('product_price'))
                    $sharedUpdate['sale_price'] = $request->product_price;
                if ($request->has('purachesepice'))
                    $sharedUpdate['purchasingـprice'] = $request->purachesepice;

                products::where('Product_Code', $request->productcode)->update($sharedUpdate);
            }
        });

        $message = app()->getLocale() == 'ar' ? 'تم تعديل بيانات المنتج والمنتجات المرتبطة بنجاح' : 'Product data modified successfully';
        session()->flash('productupdatedlocation', $message);

        return redirect()->back();
    }

    /**
     * إهلاك المنتجات (التالف) بطريقة محمية محاسبياً
     */
    public function product_damage_add(Request $request)
    {
        $productId = $request->productNo;

        DB::transaction(function () use ($productId, $request) {
            $product = products::lockForUpdate()->findOrFail($productId);

            $product->update([
                'numberofpice' => $product->numberofpice - $request->newquentity
            ]);

            ProductsDamage::create([
                'damage_quantity' => $request->newquentity,
                'product_id' => $productId,
                'product_name' => $product->product_name,
                'branchs_id' => $product->branchs_id,
                'user_id' => auth()->user()->id,
                'created_at' => Carbon::now()->addHours(3),
            ]);
        });

        $message = app()->getLocale() == 'ar' ? 'تم اتلاف المنتج بنجاح' : "Product damage successfully.";
        session()->flash('damageproduct', $message);

        return redirect()->back();
    }

    public function getcustomerdata($id)
    {
        return response()->json(customers::findOrFail($id));
    }

    public function getsupplierdata($id)
    {
        return response()->json(supllier::findOrFail($id));
    }

    /**
     * تحديث بيانات المورد والحساب المالي التابع له بالتزامن
     */
    public function updatesupplier(Request $request)
    {
        DB::transaction(function () use ($request) {
            supllier::where('id', $request->clientnamesearch)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'comp_name' => $request->name,
                'email' => $request->email ?? "supplier@gmail.com",
                'location' => $request->loction,
                'TaxـNumber' => $request->TaxـNumber ?? '0'
            ]);

            // تحديث الاسم والرقم الضريبي في جدول الشجرة المالية أيضاً
            financial_accounts::where('orginal_id', $request->clientnamesearch)
                ->where('orginal_type', 2)
                ->update([
                    'name' => $request->name,
                    'tax_no' => $request->TaxـNumber ?? '0'
                ]);
        });

        $message = app()->getLocale() == 'ar' ? 'تم تعديل البيانات بنجاح' : 'Data modified successfully';
        session()->flash('updateseccess', $message);

        return redirect()->back();
    }
    /**
     * تحديث بيانات العميل والحساب المالي التابع له بالتزامن
     */
    public function updatecustomer(Request $request)
    {
        $request->validate([
            'TaxـNumber' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            customers::where('id', $request->clientnamesearch)->update([
                'name' => $request->nameclient,
                'comp_name' => $request->nameclient,
                'street_name' => $request->StreetName,
                'building_number' => $request->buildnumber,
                'plot_identification' => $request->plot_identification,
                'address' => $request->city ?? "Client Address",
                'sub_city' => $request->sub_city ?? "Client Address",
                'tax_no' => $request->TaxـNumber ?? 0,
                'phone' => $request->phone ?? '05----------',
                'email' => $request->email ?? 'Email@gmail.com',
                'notes' => $request->product_notes ?? "لا توجد ملاحظات ",
                'Limit_credit' => $request->credit_limit,
                'grace_period_in_days' => $request->grace_period_in_days,
                'postcode' => $request->postcode,
                'CRN' => $request->CRN,
            ]);

            // تحديث الاسم والرقم الضريبي في جدول الشجرة المالية أيضاً
            financial_accounts::where('orginal_id', $request->clientnamesearch)
                ->where('orginal_type', 1)
                ->update([
                    'name' => $request->nameclient,
                    'tax_no' => $request->TaxـNumber ?? 0
                ]);
        });

        $message = app()->getLocale() == 'ar' ? 'تم تعديل البيانات بنجاح' : 'Data modified successfully';
        session()->flash('updateseccess', $message);

        return redirect()->back();
    }
    /**
     * إنشاء العميل محاسبياً وربطه التلقائي بشجرة الحسابات العامة (منع تعارض أرقام الحسابات)
     */
    public function createnewcustomerajax(Request $request)
    {
        $customer = DB::transaction(function () use ($request) {
            $newCustomer = customers::create([
                'name' => $request->name,
                'comp_name' => $request->name,
                'tax_no' => $request->tax_no ?? 0,
                'Balance' => $request->Balance ?? 0,
                'phone' => $request->phone ?? '05----------',
                'email' => $request->email ?? 'Email@gmail.com',
                'notes' => $request->notes ?? "لا توجد ملاحظات ",
                'Limit_credit' => $request->Limit_credit,
                'grace_period_in_days' => $request->grace_period_in_days,
                'street_name' => $request->StreetName,
                'building_number' => $request->buildnumber,
                'plot_identification' => $request->plot_identification,
                'address' => $request->city ?? "Client Address",
                'sub_city' => $request->sub_city ?? "Client Address",
                'postcode' => $request->postcode,
                'CRN' => $request->CRN,
            ]);

            // تحديد الـ id الخاص بالحساب الأب للعملاء
            // 1. جلب حساب الأب الفعلي للعملاء ديناميكياً (استبدل الـ ID بالـ ID الخاص بالحساب الرئيسي للعملاء في جدولك)
            $parentAccount = financial_accounts::find(2); // مثال: الـ ID الخاص بحساب العملاء الرئيسي
            $parentAccountNumber = $parentAccount ? $parentAccount->account_number : 12; // 12 كقيمة افتراضية لو غير موجود

            // البحث الآمن عن أكبر رقم حساب فرعي يتبع للأب رقم 2
            $maxAccountNumber = financial_accounts::where('parent_account_number', 2)
                ->max('account_number');

            if (!$maxAccountNumber) {
                $nextAccountNumber = $parentAccountNumber . '1';
            } else {
                $nextAccountNumber = $maxAccountNumber + 1;
            }

            financial_accounts::create([
                'name' => $request->name,
                'account_type' => 1,
                'parent_account_number' => 2,
                'account_number' => $nextAccountNumber,
                'start_balance' => 0,
                'current_balance' => 0,
                'start_balance_status' => 3,
                'other_table_FK' => NULL,
                'notes' => NULL,
                'added_by' => auth()->user()->id ?? 1,
                'updated_by' => NULL,
                'com_code' => 1,
                'date' => Carbon::now()->addHours(3),
                'active' => 1,
                'is_parent' => 0,
                'orginal_id' => $newCustomer->id,
                'orginal_type' => 1,
                'tax_no' => $request->tax_no ?? 0,
            ]);

            return $newCustomer;
        });

        return response()->json($customer);
    }


    public function create_addnewcustomer(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'TaxـNumber' => 'required|numeric',
            'balance' => 'required|numeric',
            'timeout_periodـinـdays' => 'required|numeric',
            'credit_limit' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {
            $newCustomer = customers::create([
                'name' => $request->name,
                'name_en' => $request->name_en,
                'comp_name' => $request->name,
                'tax_no' => $request->TaxـNumber ?? 0,
                'Balance' => $request->balance ?? 0,
                'phone' => $request->phone ?? '05----------',
                'email' => $request->email ?? 'Email@gmail.com',
                'notes' => $request->product_notes ?? "لا توجد ملاحظات ",
                'Limit_credit' => $request->credit_limit,
                'grace_period_in_days' => $request->grace_period_in_days,
                'street_name' => $request->StreetName,
                'building_number' => $request->buildnumber,
                'plot_identification' => $request->plot_identification,
                'address' => $request->city ?? "Client Address",
                'sub_city' => $request->sub_city ?? "Client Address",
                'postcode' => $request->postcode,
                'CRN' => $request->CRN,
            ]);

            // 1. جلب حساب الأب الفعلي للعملاء ديناميكياً (استبدل الـ ID بالـ ID الخاص بالحساب الرئيسي للعملاء في جدولك)
            $parentAccount = financial_accounts::find(2); // مثال: الـ ID الخاص بحساب العملاء الرئيسي
            $parentAccountNumber = $parentAccount ? $parentAccount->account_number : 12; // 12 كقيمة افتراضية لو غير موجود

            // 2. البحث عن أكبر رقم حساب فرعي يتبع هذا الأب لتوليد التسلسل التالي
            $maxAccountNumber = financial_accounts::where('parent_account_number', 2)
                ->max('account_number');

            if (!$maxAccountNumber) {
                // إذا لم تكن هناك فروع تابعة، يبدأ بدمج كود الأب مع 1 (مثلاً 121)
                $nextAccountNumber = $parentAccountNumber . '1';
            } else {
                // إذا وجد، يزيد واحد على الرقم الأكبر
                $nextAccountNumber = $maxAccountNumber + 1;
            }

            financial_accounts::create([
                'name' => $request->name,
                'account_type' => 1, // 1 للعملاء
                'parent_account_number' => 2, // كود الأب ديناميكي
                'account_number' => $nextAccountNumber,   // الرقم التسلسلي الجديد
                'start_balance' => 0,
                'current_balance' => 0,
                'start_balance_status' => 3,
                'added_by' => auth()->user()->id ?? 1,
                'com_code' => 1,
                'date' => Carbon::now()->addHours(3),
                'active' => 1,
                'is_parent' => 0,
                'orginal_id' => $newCustomer->id,
                'orginal_type' => 1,
                'tax_no' => $request->TaxـNumber ?? 0,
            ]);
        });

        $message = app()->getLocale() == 'ar' ? 'تم اضافة العميل بنجاح' : 'Client added successfully';
        session()->flash('newcustomer', $message);

        return redirect()->back();
    }


    public function create_addnewsupplier(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'loction' => 'required',
            'TaxـNumber' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $supplier = supllier::create([
                'name' => $request->name,
                'name_en' => $request->name_en,
                'phone' => $request->phone,
                'comp_name' => $request->name,
                'email' => $request->email,
                'location' => $request->loction,
                'notes' => $request->notes ?? "لا توجد",
                'TaxـNumber' => $request->TaxـNumber
            ]);

            // 1. جلب حساب الأب الفعلي (مثلاً بالـ ID الخاص به وليكن الأب الذي يمثل الموردين)
            $parentAccount = financial_accounts::find(1); // استبدل الـ 2 بـ ID الأب الحقيقي للموردين في جدولك
            $parentAccountNumber = $parentAccount->account_number; // أو أي account_number خاص بالأب لديك (مثل 2 أو 21 إلخ)

            // 2. البحث عن أكبر رقم حساب فرعي يتبع لهذا الأب
            $maxAccountNumber = financial_accounts::where('parent_account_number', 1)
                ->max('account_number');

            if (!$maxAccountNumber) {
                // إذا لم يكن هناك فروع، يبدأ بدمج كود الأب مع 1 (مثل 211 أو 21)
                $nextAccountNumber = $parentAccountNumber . '1';
            } else {
                // إذا وجد، يزيد واحد على الرقم الأكبر
                $nextAccountNumber = $maxAccountNumber + 1;
            }

            financial_accounts::create([
                'name' => $request->name,
                'account_type' => 2,
                'parent_account_number' => 1, // استخدام كود الأب الصحيح
                'account_number' => $nextAccountNumber,
                'start_balance' => 0,
                'current_balance' => 0,
                'start_balance_status' => 3,
                'added_by' => auth()->id() ?? 1,
                'com_code' => 1,
                'date' => Carbon::now()->addHours(3),
                'active' => 1,
                'is_parent' => 0,
                'orginal_id' => $supplier->id,
                'orginal_type' => 2,
                'tax_no' => $request->TaxـNumber
            ]);
        });

        $message = app()->getLocale() == 'ar' ? 'تم اضافة المورد بنجاح' : 'Supplier added successfully';
        session()->flash('addnewsupplier', $message);

        return redirect()->back();
    }
    public function create_addnewsupplierajax(Request $request)
    {
        $supplier = DB::transaction(function () use ($request) {
            $newSupplier = supllier::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'comp_name' => $request->name,
                'email' => $request->email ?? "supplier@gmail.com",
                'location' => $request->loction,
                'notes' => $request->notes ?? "لا توجد",
                'TaxـNumber' => $request->TaxـNumber
            ]);

            // 1. تحديد الحساب الأب مباشرة باستخدام account_number الخاص به (مثلاً حساب الموردين الرئيسي لديك في الشجرة)
            // يمكنك وضع الـ account_number الخاص بالأب هنا مباشرة

            // التأكد من أن الأب موجود بالـ account_number الفعلي
            $parentAccount = financial_accounts::find(1);
            $parentAccountNumber = $parentAccount->account_number; // أو أي account_number خاص بالأب لديك (مثل 2 أو 21 إلخ)

            // 2. البحث عن أكبر رقم حساب فرعي يتبع هذا الأب لتوليد الرقم التسلسلي التالي بناءً عليه
            $maxAccountNumber = financial_accounts::where('parent_account_number', 1)
                ->max('account_number');

            if (!$maxAccountNumber) {
                // إذا لم تكن هناك فروع تابعة له بعد، يبدأ بكود الأب مضافاً إليه 1 (مثل 2 مفصول أو مضاف كـ 21)
                $nextAccountNumber = $parentAccountNumber . '1';
            } else {
                // إذا وجدت فروع سابقة، يأخذ الرقم الأكبر ويزيد عليه 1
                $nextAccountNumber = $maxAccountNumber + 1;
            }

            financial_accounts::create([
                'name' => $request->name,
                'account_type' => 2,
                'parent_account_number' => 1, // استخدام account_number الخاص بالأب
                'account_number' => $nextAccountNumber,          // الرقم التسلسلي الجديد المبني على account_number الأب
                'start_balance' => 0,
                'current_balance' => 0,
                'start_balance_status' => 3,
                'added_by' => auth()->user()->id ?? 1,
                'com_code' => 1,
                'date' => Carbon::now()->addHours(3),
                'active' => 1,
                'is_parent' => 0,
                'orginal_id' => $newSupplier->id,
                'orginal_type' => 2,
                'tax_no' => $request->TaxـNumber
            ]);

            return $newSupplier;
        });

        return response()->json($supplier);
    }



    public function product_movement()
    {
        return view('supProcesses.product_movement');
    }
    public function product_damage()
    {
        return view('supProcesses.product damage');
    }
    public function addnewcustomer()
    {
        return view('supProcesses.addnewcustomer');
    }
    public function addnewsupplier()
    {
        return view('supProcesses.addnewsupplier');
    }
    public function stockAdjastment()
    {
        return view('supProcesses.stockAdjastment');
    }
}
