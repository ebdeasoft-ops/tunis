<?php

namespace App\Http\Controllers;

use App\Models\financial_accounts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\supllier;
use App\Models\customers;
use App\Models\acounts_type;
use App\Models\credittransactions;
use App\Models\Expenses_reasons;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use Illuminate\Support\Facades\DB;

class FinancialAccountsController extends Controller
{
    public function updateDetails(Request $request)
    {
        $account = \App\Models\financial_accounts::findOrFail($request->id);

        $account->update([
            'name' => $request->name,
        ]);



        return response()->json(['success' => true]);
    }




    public function updateStatus(Request $request)
    {
        $account = financial_accounts::findOrFail($request->id);
        $account->active = $request->active;
        $account->save();

        return response()->json(['success' => true]);
    }

    public function destroyOrder(Request $request)
    {
        try {
            // 1. جلب الحساب
            $account = financial_accounts::findOrFail($request->id);

            // 2. التحقق من وجود عمليات مرتبطة (جدول credittransactions)
            // افترضنا أن العلاقة موجودة في الموديل أو نستخدم DB مباشرة
            $hasTransactions = credittransactions::where('customer_id', $account->id)->exists();

            if ($hasTransactions) {
                return response()->json([
                    'success' => false,
                    'status' => 'has_data',
                    'message_ar' => 'عذراً لا يمكن حذف الحساب، يوجد عمليات مسجلة به',
                    'message_en' => 'Sorry, the account cannot be deleted, there are transactions recorded in it'
                ], 422); // كود 422 يعني فشل منطقي (Validation)
            }

            // 3. إذا لم يوجد عمليات، يتم الحذف
            $account->delete();

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error'
            ], 500);
        }
    }

    public function searchaboutaccountByname_numberfunction(Request $request)
    {

        app()->setLocale(\LaravelLocalization::getCurrentLocale());

        $search_text = $request->search_text;
        $start_at = $request->start_at ?: date('Y-01-01');
        $end_at = $request->end_at ?: date('Y-m-d');
        $type_id = $request->type_id;

        // جلب كافة الحسابات مرة واحدة لتقليل الضغط على قاعدة البيانات أثناء العملية التكرارية
        $allAccounts = financial_accounts::all();

        // 1. جلب الأرصدة المباشرة للحسابات الفرعية من جدول الحركات
        // افترضنا هنا أن customer_id في جدول الحركات يقابل account_number في جدول الحسابات
        $directBalances = credittransactions::where('save', 1)
            ->select(
                'customer_id',
                DB::raw("SUM(CASE WHEN DATE(created_at) < '{$start_at}' THEN debtor ELSE 0 END) as open_debtor"),
                DB::raw("SUM(CASE WHEN DATE(created_at) < '{$start_at}' THEN creditor ELSE 0 END) as open_creditor"),
                DB::raw("SUM(CASE WHEN DATE(created_at) >= '{$start_at}' AND DATE(created_at) <= '{$end_at}' THEN debtor ELSE 0 END) as curr_debtor"),
                DB::raw("SUM(CASE WHEN DATE(created_at) >= '{$start_at}' AND DATE(created_at) <= '{$end_at}' THEN creditor ELSE 0 END) as curr_creditor")
            )
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');

        $query = financial_accounts::query();

        // فلترة الحسابات بناءً على البحث والنوع
        if (!empty($search_text) && $search_text != 'خ') {
            $query->where(function ($q) use ($search_text) {
                $q->where('name', 'LIKE', "%{$search_text}%")
                    ->orWhere('account_number', 'LIKE', "%{$search_text}%");
            });
        }

        if (!empty($type_id)) {
            $query->where('account_type', $type_id);
        }

        $accounts = $query->orderBy('account_number', 'asc')->get();

        $finalData = [];
        foreach ($accounts as $account) {
            // نستخدم الدالة التكرارية (المعرفة بالأسفل) لحساب المجموع الصافي للحساب وأبنائه
            $sums = $this->calculateRecursiveBalance($account->id, $allAccounts, $directBalances);

            $net = ($sums['o_d'] + $sums['c_d']) - ($sums['o_c'] + $sums['c_c']);

            // إضافة الحساب فقط إذا كان هناك حركة أو رصيد (اختياري)
            $finalData[] = [
                'account' => $account,
                'sums' => $sums,
                'net_debtor' => $net > 0 ? $net : 0,
                'net_creditor' => $net < 0 ? abs($net) : 0,
            ];
        }

        return view('ajax_choose_account', compact('finalData'));
    }

    /**
     * الدالة التكرارية لحساب الأرصدة بناءً على هيكلية الشجرة
     */
    private function calculateRecursiveBalance($accountNumber, $allAccounts, $directBalances)
    {
        $totals = ['o_d' => 0, 'o_c' => 0, 'c_d' => 0, 'c_c' => 0];

        // 1. ابحث عن الأبناء المباشرين لهذا الحساب
        $children = $allAccounts->where('parent_account_number', $accountNumber);

        if ($children->isEmpty()) {
            /**
             * الحالة الأولى: حساب فرعي (ليس له أبناء)
             * نأخذ رصيده المباشر من جدول الحركات
             */
            $direct = $directBalances[$accountNumber] ?? null;
            if ($direct) {
                $totals['o_d'] = (float) $direct->open_debtor;
                $totals['o_c'] = (float) $direct->open_creditor;
                $totals['c_d'] = (float) $direct->curr_debtor;
                $totals['c_c'] = (float) $direct->curr_creditor;
            }
        } else {
            /**
             * الحالة الثانية: حساب رئيسي (أب)
             * نتجاهل رصيده المباشر (حتى لو وجد) ونجمع أرصدة كل الأبناء
             */
            foreach ($children as $child) {
                // نداء تكراري للابن للحصول على إجمالياته (سواء كان هو بدوره أب أو ابن نهائي)
                $childSum = $this->calculateRecursiveBalance($child->account_number, $allAccounts, $directBalances);

                $totals['o_d'] += $childSum['o_d'];
                $totals['o_c'] += $childSum['o_c'];
                $totals['c_d'] += $childSum['c_d'];
                $totals['c_c'] += $childSum['c_c'];
            }
        }

        return $totals;
    }
    // الدالة المساعدة تعمل الآن برقم الحساب كمرجع وحيد
// private function calculateRecursiveBalance($accountNumber, $allAccounts, $directBalances)
// {
//     $totals = ['o_d' => 0, 'o_c' => 0, 'c_d' => 0, 'c_c' => 0];

    //     // البحث عن الأبناء باستخدام رقم حساب الأب
//     $children = $allAccounts->where('parent_account_number', $accountNumber);

    //     if ($children->isEmpty()) {
//         $direct = $directBalances[$accountNumber] ?? null;
//         if ($direct) {
//             $totals['o_d'] = (float)$direct->open_debtor;
//             $totals['o_c'] = (float)$direct->open_creditor;
//             $totals['c_d'] = (float)$direct->curr_debtor;
//             $totals['c_c'] = (float)$direct->curr_creditor;
//         }
//     } else {
//         foreach ($children as $child) {
//             $childSum = $this->calculateRecursiveBalance($child->account_number, $allAccounts, $directBalances);
//             $totals['o_d'] += $childSum['o_d'];
//             $totals['o_c'] += $childSum['o_c'];
//             $totals['c_d'] += $childSum['c_d'];
//             $totals['c_c'] += $childSum['c_c'];
//         }
//     }
//     return $totals;
// }
// لا تنسى إضافة الدالة المساعدة calculateRecursiveBalance داخل نفس الكلاس


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('acountes.accounts');

        //
    }
    public function tree()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('acountes.tree');

        //
    }




    public function searchMaster_account_function($searchtext)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = financial_accounts::where('parent_account_number', $searchtext)->paginate(20);
        return view('ajax_choose_account', compact('data'));

        //
    }


    public function ajax_choose_account()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = financial_accounts::paginate(15);
        return view('ajax_choose_account', compact('data'));

        //
    }
    public function create_new_acount()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('acountes.create_acount');

        //
    }

    public function getfinancialaccount($id)
    {

        $financial_accounts = financial_accounts::find($id);


        return $financial_accounts;

    }

    public function update_acount($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('acountes.update_acount');

        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_new_acount_finance(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // التحقق من عدم تكرار الاسم
                $checkExists_name = financial_accounts::where('name', $request->name)
                    ->where('name_en', $request->name)
                    ->first();

                $dataid = NULL;
                $supplierid = NULL;
                $data_insert['orginal_type'] = NULL;

                if ($request->parent_account_number == 1) {
                    $data_insert['orginal_type'] = 2;
                    $supllier = supllier::create([
                        'name' => $request->name,
                        'phone' => $request->phone ?? '05*********',
                        'comp_name' => $request->name,
                        'email' => 'Email@gmail.com',
                        'location' => $request->address ?? '-',
                        'notes' => $request->notes ?? "لا توجد",
                        'TaxـNumber' => $request->TaxـNumber ?? 0
                    ]);
                    $dataid = $supllier->id;
                    $supplierid = $supllier->id;

                } elseif ($request->parent_account_number == 3 || $request->parent_account_number == 2) {
                    $data_insert['orginal_type'] = ($request->parent_account_number == 3) ? 4 : 1;
                    $customer = customers::create([
                        'name' => $request->name,
                        'comp_name' => $request->name,
                        'address' => $request->address ?? "Client Address",
                        'tax_no' => $request->TaxـNumber ?? 0,
                        "Balance" => $request->start_balance ?? 0,
                        'phone' => $request->phone ?? '05*********',
                        'email' => 'Email@gmail.com',
                        'notes' => $request->notes ?? "لا توجد ملاحظات ",
                        'Limit_credit' => 10000,
                        'grace_period_in_days' => 30
                    ]);
                    $dataid = $customer->id;

                } elseif ($request->account_type == 4) {
                    $data_insert['orginal_type'] = 3;
                    $createexpenses_reasons = Expenses_reasons::create([
                        'expenses_reason' => $request->name,
                        'expenses_reason_en' => $request->name,
                        'expensesAvt' => $request->AVT,
                        'created_at' => \Carbon\Carbon::now()->addHours(3)
                    ]);
                    $dataid = $createexpenses_reasons->id;
                }

                // تعيين رقم الحساب الأولي المؤقت
                $row = financial_accounts::latest()->first();
                $data_insert['account_number'] = !empty($row) ? $row['account_number'] + 1 : 1;

                $data_insert['orginal_id'] = $dataid;
                $data_insert['orginal_supplier'] = $supplierid;
                $data_insert['name'] = $request->name;
                $data_insert['account_type'] = $request->account_type;
                $data_insert['is_parent'] = $request->is_parent;

                if ($data_insert['is_parent'] == 0) {
                    $data_insert['parent_account_number'] = $request->parent_account_number;
                }

                $data_insert['start_balance_status'] = $request->start_balance_status;
                if ($data_insert['start_balance_status'] == 1) {
                    $data_insert['start_balance'] = $request->start_balance * (-1);
                    $data_insert['debtor_current'] = $request->start_balance;
                    $data_insert['debtor_opening'] = $request->start_balance;
                } elseif ($data_insert['start_balance_status'] == 2) {
                    $data_insert['start_balance'] = $request->start_balance;
                    $data_insert['creditor_opening'] = $request->start_balance;
                    $data_insert['creditor_current'] = $request->start_balance;
                    if ($data_insert['start_balance'] < 0) {
                        $data_insert['start_balance'] = $data_insert['start_balance'] * (-1);
                    }
                } else {
                    $data_insert['start_balance_status'] = 3;
                    $data_insert['start_balance'] = 0;
                }

                $data_insert['current_balance'] = $data_insert['start_balance'];
                $data_insert['notes'] = $request->notes;
                $data_insert['active'] = $request->active;
                $data_insert['added_by'] = auth()->user()->id;
                $data_insert['date'] = \Carbon\Carbon::now()->addHours(3);
                $data_insert['com_code'] = 0;

                $data = financial_accounts::create($data_insert);

                if ($request->parent_account_number == 3 && !empty($supplierid)) {
                    supllier::find($supplierid)->update(['mantob_account_id' => $data->id]);
                    customers::find($dataid)->update(['mantob_account_id' => $data->id]);
                }

                // معالجة الحالات الخاصة (2026)
                if ($request->parent_account_number == 74 || $data->parent_account_number == 74) {
                    $row = financial_accounts::latest()->first();
                    $data_insert['account_number'] = !empty($row) ? $row['account_number'] + 1 : 1;
                    $data_insert['orginal_id'] = $dataid;
                    $data_insert['orginal_supplier'] = $supplierid;
                    $data_insert['name'] = $request->name;
                    $data_insert['account_type'] = 2;
                    $data_insert['is_parent'] = 0;
                    $data_insert['parent_account_number'] = 1239;
                    financial_accounts::create($data_insert);

                    $row = financial_accounts::latest()->first();
                    $data_insert['account_number'] = !empty($row) ? $row['account_number'] + 1 : 1;
                    $data_insert['account_type'] = 4;
                    $data_insert['parent_account_number'] = 1242;
                    financial_accounts::create($data_insert);
                }

                // خوارزمية ترقيم الشجرة المحاسبية المحسنة (سريعة وخفيفة)
                $types = acounts_type::get();
                $typeIndex = 0;

                foreach ($types as $type) {
                    $typeIndex++;
                    $baseX = $typeIndex * 1000;

                    $mainAccounts = financial_accounts::where('account_type', $type->id)
                        ->whereNull('parent_account_number')
                        ->get();

                    $counterX = 0;
                    foreach ($mainAccounts as $main) {
                        $counterX++;
                        $newAccountNumber = $baseX + ($counterX * 100);
                        $main->update(['account_number' => $newAccountNumber]);

                        $subAccounts1 = financial_accounts::where('parent_account_number', $main->id)->get();
                        $counterY = 0;

                        foreach ($subAccounts1 as $sub1) {
                            $counterY++;
                            $sub1AccountNumber = $newAccountNumber + $counterY;
                            $sub1->update(['account_number' => $sub1AccountNumber]);

                            $subAccounts2 = financial_accounts::where('parent_account_number', $sub1->id)->get();
                            $counterZ = 0;

                            foreach ($subAccounts2 as $sub2) {
                                $counterZ++;
                                $sub2AccountNumber = ($sub1AccountNumber * 100) + $counterZ;
                                $sub2->update(['account_number' => $sub2AccountNumber]);

                                // المستوى الرابع إن وجد
                                $subAccounts3 = financial_accounts::where('parent_account_number', $sub2->id)->get();
                                $counterA = 0;
                                foreach ($subAccounts3 as $sub3) {
                                    $counterA++;
                                    $sub3AccountNumber = ($sub2AccountNumber * 100) + $counterA;
                                    $sub3->update(['account_number' => $sub3AccountNumber]);
                                }
                            }
                        }
                    }
                }
            });

            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم اضافة الحساب المالي بنجاح' : 'Active financial account added';
            session()->flash('create_acount', $message);
            return view('acountes.create_acount');

        } catch (\Exception $ex) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عذرا لم يتم التسجيل نرجو المحاولة مرة اخري' : 'Sorry, you have not registered. Please try again';
            session()->flash('error_mastik', $message);

            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما ' . $ex->getMessage()])
                ->withInput();
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\financial_accounts  $financial_accounts
     * @return \Illuminate\Http\Response
     */
    public function show(financial_accounts $financial_accounts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\financial_accounts  $financial_accounts
     * @return \Illuminate\Http\Response
     */
    public function edit(financial_accounts $financial_accounts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\financial_accounts  $financial_accounts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, financial_accounts $financial_accounts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\financial_accounts  $financial_accounts
     * @return \Illuminate\Http\Response
     */
    public function destroy(financial_accounts $financial_accounts)
    {
        //
    }
}
