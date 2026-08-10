<?php

namespace App\Http\Controllers;

use App\Models\credittransactions;
use App\Models\transactiontosuplliers;
use App\Models\acounts_type;

use App\Models\customers;
use App\Models\OpeningEntry;
use App\Models\financial_accounts;
use App\Models\Expenses_reasons;
use App\Models\expenses;
use App\Models\supllier;
use App\Models\DailyRecord;
use App\Models\Cost_centers;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use Illuminate\Support\Facades\DB; // هذا هو السطر الذي كان ينقصك وحل مشكلة الخطأ
use Illuminate\Support\Facades\Auth;
class CredittransactionsController extends Controller
{

public function general_budget()
{

    return view('acountes.general_budget_search');
}
public function budgetsheet(Request $request)
{
    // 1. استقبال البيانات القادمة من نموذج البحث
    $date = $request->input('date');
    $branch = $request->input('branch');

    // 2. جلب جميع الحسابات من جدول financial_accounts الأساسي
    $accountsQuery = DB::table('financial_accounts');

    if ($branch && $branch != '-') {
        $accountsQuery->where('branch_id', $branch);
    }

    $allAccounts = $accountsQuery->get();

    // جلب الـ IDs التي تُستخدم كـ parent لأي حساب آخر في الجدول
    $parentIds = $allAccounts->pluck('parent_account_number')->unique()->filter()->toArray();

    // تصفية الحسابات لتشمل فقط الحسابات النهائية التي ليس لها أبناء (is_parent = 0 ولا تظهر في قائمة الآباء)
   $finalAccounts = $allAccounts->filter(function ($account) use ($parentIds) {
    return !in_array($account->id, $parentIds);
});

    // 3. حساب الرصيد للحسابات النهائية فقط
    $finalAccounts->each(function ($account) use ($date, $branch) {
        
        // الحالة الثانية: حساب فرعي نهائي (ليس له أبناء) -> نأخذ حركاته مباشرة
        $transQuery = DB::table('credittransactions')
            ->where('customer_id', $account->id); // أو account_id حسب عمود الربط لديك

        // تصفية حركات الفرع إذا تم تحديده
        if ($branch && $branch != '-') {
            $transQuery->where('branchs_id', $branch);
        }

        // تصفية حركات التاريخ (حتى التاريخ المحدد)
        if ($date) {
            $transQuery->where('created_at', '<=', $date . ' 23:59:59');
        }

        $totals = $transQuery->select(
            DB::raw('SUM(debtor) as total_debtor'),
            DB::raw('SUM(creditor) as total_creditor')
        )->first();

        // جمع المدين (الافتتاحي + الحركات)
        $movDebit = $totals->total_debtor ?? 0;
        $totalDebit =  $movDebit;

        // جمع الدائن (الافتتاحي + الحركات)
        $movCredit = $totals->total_creditor ?? 0;
        $totalCredit =  $movCredit;

        // حساب الرصيد النهائي حسب طبيعة الحساب
        if ($account->account_type == 1) { 
            // الأصول (طبيعتها مدين: مدين - دائن)
            $account->current_balance = $totalDebit - $totalCredit;
        } else { 
            // الخصوم (2) وحقوق الملكية (5) (طبيعتها دائن: دائن - مدين)
            $account->current_balance = $totalCredit - $totalDebit;
        }
    });

    // 4. تصنيف الحسابات النهائية حسب أنواعها للرؤية في الواجهة (Blade)
    $assets = $finalAccounts->where('account_type', 1);       // الأصول
    $liabilities = $finalAccounts->where('account_type', 2);  // الخصوم
    $equity = $finalAccounts->where('account_type', 5);       // حقوق الملكية

    // جلب الفروع لعرضها في قائمة البحث بالواجهة
    $branches = DB::table('branchs')->get();

    // 5. إرسال البيانات إلى واجهة العرض (Blade)
    return view('acountes.budgetsheet', compact('assets', 'liabilities', 'equity', 'branches', 'date', 'branch'));
}
    /**
     * دالة موحدة لعكس حركات السند وتصفير تأثيرها على الأرصدة قبل الحذف أو التعديل
     * (تتعامل مع سندات القبض والصرف بناءً على نوع الحقل الممرر)
     */
    private function reverseVoucherBalances($columnName, $countValue)
    {
        $old_transactions = DB::table('credittransactions')->where($columnName, $countValue)->get();

        foreach ($old_transactions as $old_tx) {
            $account = financial_accounts::find($old_tx->customer_id);
            if ($account) {
                // عكس القيد: نطرح ما تم إضافته سابقاً للحساب
                $account->decrement('debtor_current', $old_tx->debtor);
                $account->decrement('creditor_current', $old_tx->creditor);

                // تحديث الرصيد الحالي للحساب
                $account->update([
                    'current_balance' => $account->debtor_current - $account->creditor_current
                ]);

                // مزامنة الحسابات الفرعية الخارجية (عملاء / موردين)
                $this->syncExternalBalances($account);
            }

            // حذف الملفات والمرفقات إن وجدت قبل حذف السجل
            if (!empty($old_tx->attachments)) {
                $file_path = public_path('assets/attachments/' . $old_tx->attachments);
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }
        }
    }

    /**
     * تحديث سند القبض (بالكامل)
     */
    public function updateAllVoucher(Request $request)
    {
        $payment_name = $request->pay_method_type;
        $user_id = auth()->id();

        $request->validate([
            'receipt_id' => 'required',
            'sent_abd_count' => 'required',
            'main_account_id' => 'required',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $sent_count = $request->sent_abd_count;

            // 1. تصفير وعكس الأرصدة القديمة بأمان
            $this->reverseVoucherBalances('sent_abd_count', $sent_count);

            // 2. حذف الحركات القديمة بعد عكس أرصدتها
            DB::table('credittransactions')->where('sent_abd_count', $sent_count)->delete();

            // 3. تسجيل البيانات الجديدة وتحديث الأرصدة
            $total_receipt_amount = 0;

            // الطرف الدائن (الحسابات الفرعية)
            foreach ($request->items as $item) {
                $amount = $item['amount'];
                if ($amount <= 0)
                    continue;

                $total_receipt_amount += $amount;
                $sub_account = financial_accounts::find($item['account_id']);

                if ($sub_account) {
                    $sub_account->increment('creditor_current', $amount);
                    $sub_account->update([
                        'current_balance' => $sub_account->debtor_current - $sub_account->creditor_current
                    ]);

                    DB::table('credittransactions')->insert([
                        'sent_abd_count' => $sent_count,
                        'customer_id' => $sub_account->id,
                        'debtor' => 0,
                        'creditor' => $amount,
                        'user_id' => $user_id,
                        'date_export' => $request->date ?? now()->format('Y-m-d'),
                        'pay_method' => $request->pay_method_type,
                        'Pay_Method_Name' => $payment_name,
                        'note' => ($item['note'] ?? '') . ' | تعديل سند قبض رقم: ' . $sent_count,
                        'currentblance' => $sub_account->current_balance,
                        'type_decument' => 1,
                        'created_at' => now(),
                        'branchs_id' => Auth::user()->branchs_id ?? 1,
                        'recive_amount' => $amount,
                    ]);

                    $this->syncExternalBalances($sub_account);
                }
            }

            // الطرف المدين (الصندوق أو البنك)
            $main_account = financial_accounts::find($request->main_account_id);
            if ($main_account) {
                $main_account->increment('debtor_current', $total_receipt_amount);
                $main_account->update([
                    'current_balance' => $main_account->debtor_current - $main_account->creditor_current
                ]);

                DB::table('credittransactions')->insert([
                    'sent_abd_count' => $sent_count,
                    'customer_id' => $main_account->id,
                    'debtor' => $total_receipt_amount,
                    'creditor' => 0,
                    'user_id' => $user_id,
                    'date_export' => $request->date ?? now()->format('Y-m-d'),
                    'pay_method' => $request->pay_method_type,
                    'Pay_Method_Name' => $payment_name,
                    'note' => 'إجمالي تعديل سند قبض رقم: ' . $sent_count,
                    'currentblance' => $main_account->current_balance,
                    'type_decument' => 1,
                    'created_at' => now(),
                    'branchs_id' => Auth::user()->branchs_id ?? 1,
                    'recive_amount' => $total_receipt_amount,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'تم التحديث بنجاح', 'count' => $sent_count]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * حفظ سند قبض جديد
     */
    public function store_kabt_Decument(Request $request)
    {
        $payment_name = $request->payment_method;

        $request->validate([
            'main_account_id' => 'required',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.account_id' => 'required',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $the_file_path = '';
            if ($request->hasFile('attachments')) {
                $folder = 'assets/attachments';
                $image = $request->file('attachments');
                $the_file_path = time() . rand(100, 999) . '.' . $image->extension();
                $image->move(public_path($folder), $the_file_path);
            }

            $recent_record = credittransactions::where('note', 'LIKE', '%سند قبض%')
                ->orderBy('sent_abd_count', 'desc')
                ->first();

            $sent_abd_count = $recent_record ? $recent_record->sent_abd_count + 1 : 1;
            $total_receipt_amount = 0;
            $createTransactionId = 0;

            foreach ($request->items as $item) {
                $amount = $item['amount'];
                if ($amount <= 0) continue;

                $total_receipt_amount += $amount;
                $sub_account = financial_accounts::find($item['account_id']);

                if ($sub_account) {
                    $sub_account->increment('creditor_current', $amount);
                    $sub_account->update([
                        'current_balance' => $sub_account->debtor_current - $sub_account->creditor_current
                    ]);

                    $tx = credittransactions::create([
                        'attachments' => $the_file_path,
                        'user_id' => auth()->id(),
                        'customer_id' => $sub_account->id,
                        'recive_amount' => $amount,
                        'branchs_id' => Auth::user()->branchs_id ?? 1,
                        'pay_method' => $payment_name,
                        'date_export' => $request->date,
                        'note' => ($item['note'] ?? $request->notes) . ' | سند قبض | رقم: ' . $sent_abd_count,
                        'debtor' => 0,
                        'creditor' => $amount,
                        'sent_abd_count' => $sent_abd_count,
                        'currentblance' => $sub_account->current_balance,
                        'type' => $request->payment_method,
                        'Pay_Method_Name' => $payment_name,
                        'type_decument' => 1,
                    ]);

                    $createTransactionId = $tx->id;
                    $this->syncExternalBalances($sub_account);
                }
            }

            // تسجيل الحساب الرئيسي المستلم للمال
            $main_account = financial_accounts::find($request->main_account_id);
            if ($main_account) {
                $main_account->increment('debtor_current', $total_receipt_amount);
                $main_account->update([
                    'current_balance' => $main_account->debtor_current - $main_account->creditor_current
                ]);

                credittransactions::create([
                    'attachments' => $the_file_path,
                    'user_id' => auth()->id(),
                    'customer_id' => $main_account->id,
                    'recive_amount' => $total_receipt_amount,
                    'branchs_id' => Auth::user()->branchs_id ?? 1,
                    'date_export' => $request->date,
                    'note' => 'إجمالي سند قبض | رقم: ' . $sent_abd_count,
                    'debtor' => $total_receipt_amount,
                    'creditor' => 0,
                    'sent_abd_count' => $sent_abd_count,
                    'decument_id' => $createTransactionId,
                    'currentblance' => $main_account->current_balance,
                    'Pay_Method_Name' => $payment_name,
                    'pay_method' => $payment_name,
                    'type_decument' => 1,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'تم حفظ سند القبض بنجاح', 'count' => $sent_abd_count]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'خطأ في الحفظ: ' . $e->getMessage()], 500);
        }
    }

    /**
     * تعديل السند الشامل (تم إصلاحها محاسبياً لاستخدام دالة العكس التلقائي للكميات)
     */
    public function edit_full_voucher(Request $request)
    {
        $request->validate([
            'sent_abd_count' => 'required',
            'account_ids' => 'required|array',
            'amounts' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            // إصلاح محاسبي: عكس الحركات والأرصدة القديمة قبل مسحها
            $this->reverseVoucherBalances('sent_abd_count', $request->sent_abd_count);
            credittransactions::where('sent_abd_count', $request->sent_abd_count)->delete();

            // إضافة الأسطر الجديدة مع معالجة الأرصدة
            foreach ($request->amounts as $key => $val) {
                if ($val <= 0)
                    continue;

                $account = financial_accounts::find($request->account_ids[$key]);
                if ($account) {
                    $account->increment('creditor_current', $val);
                    $account->update([
                        'current_balance' => $account->debtor_current - $account->creditor_current
                    ]);

                    credittransactions::create([
                        'sent_abd_count' => $request->sent_abd_count,
                        'date_export' => $request->date_export ?? now()->format('Y-m-d'),
                        'recive_amount' => $val,
                        'customer_id' => $account->id, // تعديل الحقل ليتناسب مع هيكلة جدول الحركات لديك
                        'debtor' => 0,
                        'creditor' => $val,
                        'user_id' => auth()->id(),
                        'note' => $request->notes[$key] ?? 'تعديل شامل للسند',
                        'currentblance' => $account->current_balance,
                        'branchs_id' => Auth::user()->branchs_id ?? 1
                    ]);

                    $this->syncExternalBalances($account);
                }
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * حذف سند قبض بالكامل (تم الإصلاح لمنع الخلل المحاسبي)
     */
    public function destroy_full_voucher($sent_abd_count)
    {
        try {
            DB::beginTransaction();

            // إصلاح محاسبي: عكس الأرصدة وتصفيرها قبل الحذف النهائي
            $this->reverseVoucherBalances('sent_abd_count', $sent_abd_count);
            $deleted = credittransactions::where('sent_abd_count', $sent_abd_count)->delete();

            if ($deleted) {
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'تم حذف السند وتصفير الحسابات المرتبطة بنجاح']);
            }

            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'السند غير موجود']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()]);
        }
    }

    /**
     * حذف سند صرف بالكامل (تم الإصلاح ليعكس الأرصدة المدنية والدائنة)
     */
    public function destroy_Serf_Decument($id)
    {
        try {
            DB::beginTransaction();

            $mainItem = credittransactions::findOrFail($id);
            $serfCount = $mainItem->sent_serf_count;

            // إصلاح محاسبي: عكس الأرصدة بناءً على رقم سند الصرف قبل الحذف
            $this->reverseVoucherBalances('sent_serf_count', $serfCount);
            credittransactions::where('sent_serf_count', $serfCount)->delete();

            DB::commit();
            return response()->json(['message' => __('home.deleted_successfully')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getDetailsJS_Kabd($serf_count)
    {
        $details = credittransactions::where('sent_abd_count', $serf_count)->get();
        if ($details->isEmpty()) {
            return response()->json(['message' => 'السند غير موجود'], 404);
        }
        return response()->json($details);
    }

    public function getReceiptDetails($serf_count)
    {
        $details = credittransactions::where('sent_serf_count', $serf_count)->get();
        return response()->json($details);
    }








    public function update_Serf_Decument(Request $request, $id)
    {
        
    // 1. العثور على السجل الأساسي لجلب رقم السند المشترك
        $main_entry = credittransactions::findOrFail($id);
        $serf_count = $main_entry->sent_serf_count;

        return \DB::transaction(function () use ($request, $serf_count) {
            $payment_name = $request->pay_method_type;

            // 2. عكس الأرصدة السابقة قبل الحذف
            $old_entries = credittransactions::where('sent_serf_count', $serf_count)->get();
            foreach ($old_entries as $old) {
                $acc = financial_accounts::find($old->customer_id);
                if ($acc) {
                    if ($old->debtor > 0) {
                        $acc->decrement('debtor_current', $old->debtor);
                        $acc->decrement('current_balance', $old->debtor);
                    } else {
                        $acc->decrement('creditor_current', $old->creditor);
                        $acc->increment('current_balance', $old->creditor);
                    }
                }
            }

            // 3. حذف كافة سجلات السند القديمة
            credittransactions::where('sent_serf_count', $serf_count)->delete();

            // 4. تجهيز بيانات حساب الدفع الجديد (الخزينة المختارة من الأعلى)
            $payment_id = $request->payment_account_id;
            $main_payment_acc = financial_accounts::findOrFail($payment_id);
            $total_amount = 0; // سيحمل إجمالي المبلغ الشامل للضريبة

            // 5. إنشاء القيود الجديدة لكل سطر في الجدول
            foreach ($request->items as $item) {
                $amount_inclusive = $item['amount']; // المبلغ الشامل
                $total_amount += $amount_inclusive;
                
                $tax_rate = $item['tax_rate'];
                $tax_val = ($tax_rate > 0) ? ($amount_inclusive - ($amount_inclusive / (1 + $tax_rate))) : 0;
                
                // حساب المبلغ الصافي للمصروف (بدون ضريبة)
                $amount_exclusive = $amount_inclusive - $tax_val;

                // أ- قيد الحساب المستلم (مدين) - نسجل الصافي فقط
                $target_acc = financial_accounts::find($item['client_account_id']);
                $target_acc->increment('debtor_current', $amount_exclusive);
                $target_acc->increment('current_balance', $amount_exclusive);

                credittransactions::create([
                    'user_id' => auth()->id(),
                    'customer_id' => $item['client_account_id'],
                    'recive_amount' => $amount_exclusive,
                    'branchs_id' => $main_payment_acc->branchs_id,
                    'pay_method' => $main_payment_acc->name,
                    'Pay_Method_Name' => $payment_name,
                    'note' => $item['notes'] . ' | سند صرف رقم: '  . $serf_count,
                    'date_export' => $request->date,
                    'created_at' => $request->date . ' ' . now()->format('H:i:s'),
                    'debtor' => $amount_exclusive,
                    'creditor' => 0,
                    'type_decument' => 2,
                    'sent_serf_count' => $serf_count,
                    'type' => $request->pay_method_type,
                    'cost_center' => $item['cost_center'],
                    'orginal_type' => $target_acc->orginal_type ?? 0,
                    'orginal_id' => $target_acc->orginal_id ?? 0,
                ]);
                
                if($target_acc->orginal_type == 3){
                    $reason_data = Expenses_reasons::find($target_acc->orginal_id);

                    $expense = expenses::create([
                        'user_id' => Auth()->user()->id,
                        'Pay_Method_Name' => $request->pay_method_type,
                        'branchs_id' => $main_payment_acc->branchs_id,
                        'Reasonforspendingmoney' => $reason_data->expenses_reason,
                        'reasonId_id' => $target_acc->orginal_id,
                        'notes' => $item['notes'] . ' | تعديل سند رقم: ' . $serf_count,
                        'expensesAvt' => $reason_data->expensesAvt,
                        'created_at' => $request->date . ' ' . now()->format('H:i:s'),
                        'date_export' => $request->date,
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),
                        'Theـamountـpaid' => $amount_exclusive, // المصروف يسجل بالصافي أيضاً
                        'type' => 2,
                    ]);
                }

                // ب- قيد الضريبة (إن وجد)
                if ($tax_val > 0) {
                    $tax_account = financial_accounts::where('parent_account_number', 102)->where('branchs_id', $main_payment_acc->branchs_id)->first();

                    if ($tax_account) {
                        // تحديث رصيد حساب الضريبة
                        $tax_account->increment('debtor_current', $tax_val);
                        $tax_account->increment('current_balance', $tax_val);
                    }

                    credittransactions::create([
                        'user_id' => auth()->id(),
                        'customer_id' => $tax_account ? $tax_account->id : null,
                        'recive_amount' => $tax_val,
                        'branchs_id' => $main_payment_acc->branchs_id,
                        'date_export' => $request->date,
                        'debtor' => $tax_val,
                        'creditor' => 0,
                        'created_at' => $request->date . ' ' . now()->format('H:i:s'),
                        'Pay_Method_Name' => $payment_name,
                        'pay_method' => $main_payment_acc->name,
                        'vat' => 1,
                        'note' => $item['notes'] . '| سند صرف | : ' . (string)$serf_count,
                        'sent_serf_count' => $serf_count,
                        'orginal_id' => $tax_account->orginal_id ?? 0,
                    ]);
                }
            }

            // 6. قيد الخزينة النهائي (دائن بالإجمالي الشامل)
            $main_payment_acc->increment('creditor_current', $total_amount);
            $main_payment_acc->decrement('current_balance', $total_amount);

            credittransactions::create([
                'user_id' => auth()->id(),
                'customer_id' => $payment_id,
                'recive_amount' => $total_amount,
                'branchs_id' => $main_payment_acc->branchs_id,
                'pay_method' => $main_payment_acc->name,
                'Pay_Method_Name' => $payment_name,
                'note' => '| سند صرف | : ' . $serf_count,
                'date_export' => $request->date,
                'debtor' => 0,
                'creditor' => $total_amount,
                'type_decument' => 2,
                'sent_serf_count' => $serf_count,
                'created_at' => $request->date . ' ' . now()->format('H:i:s'),
            ]);

            return response()->json(['status' => 'success', 'message' => __('home.updated_successfully')]);
        });
        
        
        }
    public function store_Serf_Decument(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        // 1. معالجة المرفق العام
        $the_file_path = '';
        if ($request->has('attachments')) {
            $folder = 'assets/attachments';
            $image = $request->attachments;
            $extension = $image->extension();
            $the_file_path = time() . rand(100, 999) . '.' . $extension;
            $image->move($folder, $the_file_path);
        }

        // 2. بيانات حساب الدفع
        $payment_id = $request->payment_account_id;
        $main_payment_acc = financial_accounts::findOrFail($payment_id);
        $payment_name = $request->pay_method_type;
        $branch_id = $main_payment_acc->branchs_id;
        $pay_method_type = $request->pay_method_type;

        // 3. تحديد رقم السند
     // 1. جلب السجل الذي يحتوي على أعلى رقم حالي
        $recent_id = credittransactions::where('sent_serf_count',"!=", 0)->orderby('sent_serf_count', 'desc')->first();


        // 2. إذا وجد سجل، أضف 1 على الرقم الحالي، وإذا لم يجد (أول عملية) ابدأ برقم 1
        $sent_serf_count = $recent_id ? $recent_id->sent_serf_count + 1 : 1;

        $all_transactions_data = [];
        $total_document_amount = 0; // متغيّر لتجميع إجمالي السند

        // 4. الدوران على أسطر السندات (الجانب المدين)
        foreach ($request->items as $item) {$clientId = $item['client_account_id'];
        $amount_inclusive = $item['amount']; // 115000
        $tax_rate = $item['tax_rate']; // e.g. 0.15
        $notes = $item['notes'];

        $total_document_amount += $amount_inclusive; 

        // 1. حساب قيمة الضريبة
        $tax_value = ($tax_rate > 0) ? ($amount_inclusive - ($amount_inclusive / (1 + $tax_rate))) : 0;
        
        // 2. حساب المبلغ الصافي (بدون ضريبة) لتسجيله كمصروف حقيقي
        $amount_exclusive = $amount_inclusive - $tax_value;

        $target_acc = financial_accounts::find($clientId);

        // --- أ: تحديث الحساب المستلم (مدين) بالمبلغ الشامل ---
        if ($target_acc->account_type == 1 || $target_acc->account_type == 4) {
            $target_acc->increment('current_balance', $amount_inclusive);
            $target_acc->increment('debtor_current', $amount_inclusive);
        } else {
            $target_acc->decrement('current_balance', $amount_inclusive);
            $target_acc->increment('debtor_current', $amount_inclusive);
        }

        // --- تسسجيل الحركة الأساسية للمصروف (بالقيمة الصافية بدون ضريبة) ---
        $transaction = credittransactions::create([
            'attachments' => $the_file_path,
            'user_id' => auth()->id(),
            'customer_id' => $clientId,
            'recive_amount' => $amount_exclusive, // تم تعديلها لتكون الصافي المحاسبي
            'branchs_id' => $branch_id,
            'pay_method' => $payment_name,
            'note' => $notes . ' | سند صرف رقم: ' . $sent_serf_count,
            'currentblance' => $target_acc->current_balance,
            'Pay_Method_Name' => $payment_name,
            'date_export' => $request->date,
            'debtor' => $amount_exclusive, // الصافي مدين
            'creditor' => 0,
            'type_decument' => 2,
            'sent_serf_count' => $sent_serf_count,
            'type' => $pay_method_type,
            'cost_center' => $item['cost_center'],
            'orginal_type' => $target_acc->orginal_type ?? 0,
            'orginal_id' => $target_acc->orginal_id ?? 0,
            'created_at' => $request->date . ' ' . now()->format('H:i:s'),
        ]);

        if($target_acc->orginal_type == 3){
            $reason_data = Expenses_reasons::find($target_acc->orginal_id);

            $expense = expenses::create([
                'attachments' => $the_file_path,
                'user_id' => Auth()->user()->id,
                'Pay_Method_Name' => $pay_method_type,
                'branchs_id' => $branch_id,
                'Reasonforspendingmoney' => $reason_data->expenses_reason,
                'reasonId_id' => $target_acc->orginal_id,
                'notes' => $notes . ' | سند صرف رقم: ' . $sent_serf_count,
                'expensesAvt' => $reason_data->expensesAvt,
                'date_export' => $request->date,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'Theـamountـpaid' => $amount_exclusive, // تسجيل المصروف بالصافي
                'type' => 2,
                'created_at' => $request->date . ' ' . now()->format('H:i:s'),
            ]);
        }

        // --- ب: معالجة الضريبة لكل سطر (تُسجل كمدين أيضاً لحساب ضريبة القيمة المضافة المدخلة) ---
        if ($tax_value > 0) {
            $financial_accounts = financial_accounts::where('parent_account_number', 102)->where('branchs_id', $main_payment_acc->branchs_id)->first();

            credittransactions::create([
                'sent_serf_count' => $sent_serf_count,
                'user_id' => auth()->id(),
                'customer_id' => $financial_accounts->id,
                'recive_amount' => $tax_value,
                'branchs_id' => $main_payment_acc->branchs_id,
                'note' => $notes . '| ضريبة سند صرف | : ' . (string) $sent_serf_count,
                'date_export' => $request->date,
                'debtor' => $tax_value, // قيمة الضريبة مدينة
                'creditor' => 0,
                'Pay_Method_Name' => $payment_name,
                'pay_method' => $main_payment_acc->name,
                'vat' => 1,
                'orginal_id' => $financial_accounts->orginal_id ?? 0,
                'created_at' => $request->date . ' ' . now()->format('H:i:s'),
            ]);
        }

        $all_transactions_data[] = [
            'name' => $target_account_name ?? $target_acc->name,
            'amount' => $amount_inclusive
        ];
        
        }

        // --- ج: تحديث حساب الخزينة/البنك (مرة واحدة بإجمالي المبلغ) ---
        if ($total_document_amount > 0) {
            $main_payment_acc->decrement('current_balance', $total_document_amount);
            $main_payment_acc->increment('creditor_current', $total_document_amount);

            credittransactions::create([
                'user_id' => auth()->id(),
                'customer_id' => $payment_id,
                'recive_amount' => $total_document_amount,
                'branchs_id' => $branch_id,
                'pay_method' => $payment_name,
                'note' => 'إجمالي صرف سند رقم: ' . $sent_serf_count,
                'currentblance' => $main_payment_acc->current_balance,
                'date_export' => $request->date,
                'debtor' => 0,
                'creditor' => $total_document_amount,
                'Pay_Method_Name' => $payment_name,
                'type_decument' => 2,
                'sent_serf_count' => $sent_serf_count,
                'created_at' => $request->date . ' ' . now()->format('H:i:s'),

            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ السند بإجمالي ' . $total_document_amount,
            'sent_serf_count' => $sent_serf_count
        ]);
    }













    /**
     * دالة مساعدة موحدة لعكس أرصدة أي قيد (يومي أو افتتاحي) من الشجرة قبل الحذف أو التعديل
     */
    private function reverseTransactionBalances($columnName, $idValue)
    {
        $old_transactions = credittransactions::where($columnName, $idValue)->get();
        foreach ($old_transactions as $old_row) {
            $account = financial_accounts::find($old_row->customer_id);
            if ($account) {
                // عكس المعادلة المحاسبية: طرح المبالغ التي سجلت سابقاً
                $account->debtor_current -= $old_row->debtor;
                $account->creditor_current -= $old_row->creditor;
                $account->current_balance = $account->debtor_current - $account->creditor_current;
                $account->save();

                // مزامنة الكيانات الخارجية (عملاء / موردين)
                $this->syncExternalBalances($account);
            }
        }
    }

    /**
     * جلب تفاصيل القيد الافتتاحي
     */
    public function getEntryDetails($id)
    {
        $items = credittransactions::where('Opening_entry', $id)->get();

        if ($items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'القيد غير موجود']);
        }

        $firstItem = OpeningEntry::where('entry_number', $id)->first();

        return response()->json([
            'success' => true,
            'entry' => [
                'id' => $id,
                'date' => $firstItem->entry_date ?? now()->format('Y-m-d'),
                'note' => $firstItem->general_note ?? '',
            ],
            'items' => $items->map(function ($item) {
                return [
                    'account_id' => $item->customer_id,
                    'cost_center_id' => $item->cost_center_id ?? $item->cost_center,
                    'debit' => $item->debtor,
                    'credit' => $item->creditor,
                    'note' => $item->note
                ];
            })
        ]);
    }

    /**
     * حذف القيد الافتتاحي (تم إصلاح الخلل وعكس الأرصدة قبل الحذف)
     */
    public function delete_Opening_entry($id)
    {
        try {
            DB::beginTransaction();

            // 1. عكس أرصدة الحسابات المتأثرة أولاً لمنع الفروقات المالية
            $this->reverseTransactionBalances('Opening_entry', $id);

            // 2. حذف الحركات من السجل المالي ورأس القيد
            $deleted = credittransactions::where('Opening_entry', $id)->delete();
            OpeningEntry::where('entry_number', $id)->delete();

            if ($deleted) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم حذف القيد الافتتاحي رقم ' . $id . ' وعكس كافة أرصدته بنجاح'
                ]);
            }

            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على القيد أو قد يكون محذوفاً مسبقاً'
            ], 404);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء وتعديل القيود الافتتاحية (تم تأمينها محاسبياً)
     */
    public function create_Opening_entry_new(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'account_id' => 'required|array|min:1',
            'debit' => 'required|array',
            'credit' => 'required|array',
        ]);

        try {
            DB::beginTransaction();


            $the_file_path_1 = '';
            if ($request->hasFile('attachments_1')) {
                $folder = 'assets/attachments';
                $image = $request->file('attachments_1');
                $the_file_path_1 = time() . rand(100, 999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($folder), $the_file_path_1);
            }

            if ($request->record_id == 0) {
                $last_entry = OpeningEntry::orderBy('id', 'desc')->first();
                $dely_record_count = $last_entry ? $last_entry->id + 1 : 1;
            } else {
                $dely_record_count = $request->record_id;

                // إصلاح محاسبي: عكس الحركات القديمة قبل المسح لإعادة بنائها
                $this->reverseTransactionBalances('Opening_entry', $dely_record_count);

                credittransactions::where('Opening_entry', $dely_record_count)->delete();
                OpeningEntry::where('entry_number', $dely_record_count)->delete();
            }

            OpeningEntry::create([
                'entry_number' => $dely_record_count,
                'entry_date' => $request->entry_date,
                'general_note' => $request->general_note,
                'created_by' => auth()->id(),
                'total_amount' => array_sum($request->debit),
            ]);

            $accountIds = $request->account_id;
            $debits = $request->debit;
            $credits = $request->credit;
            $notes = $request->line_note;
            $cost_centers = $request->cost_center_id;

            foreach ($accountIds as $key => $accountId) {
                if (empty($accountId) || ($debits[$key] == 0 && $credits[$key] == 0)) {
                    continue;
                }

                $financial_account = financial_accounts::find($accountId);
                if (!$financial_account)
                    continue;

                $transaction = credittransactions::create([
                    'attachments' => $the_file_path_1,
                    'orginal_type' => $financial_account->orginal_type ?? 0,
                    'user_id' => auth()->id(),
                    'customer_id' => $accountId,
                    'branchs_id' => auth()->user()->branchs_id ?? 1,
                    'cost_center_id' => $cost_centers[$key] ?? null,
                    'pay_method' => 'Cash',
                    'note' => ($notes[$key] ?? $request->general_note) . ' | قيد افتتاحي رقم: ' . $dely_record_count,
                    'currentblance' => $financial_account->current_balance ?? 0,
                    'Pay_Method_Name' => 'Cash',
                    'date_export' => $request->entry_date,
                    'created_at' => $request->entry_date . ' ' . now()->format('H:i:s'),
                    'updated_at' => now(),
                    'orginal_id' => $financial_account->orginal_id ?? 0,
                    'recive_amount' => $debits[$key] + $credits[$key],
                    'debtor' => $debits[$key],
                    'creditor' => $credits[$key],
                    'Opening_entry' => $dely_record_count,
                    'parent_Opening_entry' => 0,
                    'save' => 1,
                ]);

                // تحديث الأرصدة في الشجرة والمزامنة الخارجية
                $this->updateAccountBalance($financial_account, $debits[$key], $credits[$key], $transaction);
            }

            DB::commit();

            $savedTransactions = credittransactions::where('Opening_entry', $dely_record_count)
                ->where('parent_Opening_entry', 0)
                ->get();

            $items = $savedTransactions->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->financial_accounts_data->name ?? 'N/A',
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                    'note' => $item->note
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $dely_record_count,
                    'note' => $request->general_note,
                    'items' => $items
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * معالجة القيود اليومية الجديدة وتعديلها القديم
     */
    public function daily_record_new(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'accounts' => 'required|array|min:1',
            'debit' => 'required|array',
            'credit' => 'required|array',
        ]);

        $total_debit = array_sum($request->debit);
        $total_credit = array_sum($request->credit);
        $dely_record_count = $request->record_id;

        // مراجعة ميزان القيد قبل الحفظ (المدين يجب أن يساوي الدائن بدقة)
        if (abs($total_debit - $total_credit) > 0.0001) {
            return response()->json([0, "القيد غير متوازن محاسبياً! إجمالي المدين يجب أن يساوي إجمالي الدائن."]);
        }

        try {
            DB::beginTransaction();

            $the_file_path_1 = $request->existing_attachment ?? '';
            if ($request->hasFile('attachments_1')) {
                $folder = 'assets/attachments';
                $image = $request->file('attachments_1');
                $the_file_path_1 = time() . rand(100, 999) . '.' . $image->extension();
                $image->move(public_path($folder), $the_file_path_1);
            }

            if ($dely_record_count > 0) {
                $dailyRecord = DailyRecord::find($dely_record_count);
                if ($dailyRecord) {
                    $dailyRecord->update([
                        'date' => $request->date,
                        'general_notes' => $request->general_notes,
                        'total_debit' => $total_debit,
                        'total_credit' => $total_credit,
                        'user_id' => auth()->id(),
                    ]);
                }

                // تصفير وعكس الأرصدة القديمة للقيود اليومية بأمان
                $this->reverseTransactionBalances('dely_record', $dely_record_count);
                credittransactions::where('dely_record', $dely_record_count)->delete();
            } else {
                $dailyRecord = DailyRecord::create([
                    'date' => $request->date,
                    'general_notes' => $request->general_notes,
                    'total_debit' => $total_debit,
                    'total_credit' => $total_credit,
                    'user_id' => auth()->id(),
                ]);
                $dely_record_count = $dailyRecord->id;
            }

            foreach ($request->accounts as $key => $accountId) {
                $debitValue = $request->debit[$key] ?? 0;
                $creditValue = $request->credit[$key] ?? 0;
                $rowNote = $request->notes[$key] ?? $request->general_notes;
                $costCenter = $request->cost_centers[$key] ?? null;

                if ($debitValue == 0 && $creditValue == 0)
                    continue;

                $financial_account = financial_accounts::find($accountId);
                if (!$financial_account)
                    continue;

                $transaction = credittransactions::create([
                    'attachments' => $the_file_path_1,
                    'orginal_type' => $financial_account->orginal_type ?? 0,
                    'user_id' => auth()->id(),
                    'customer_id' => $accountId,
                    'branchs_id' => auth()->user()->branchs_id ?? 1,
                    'pay_method' => 'Cash',
                    'note' => $rowNote . ' | قيد رقم: ' . $dely_record_count,
                    'currentblance' => $financial_account->current_balance ?? 0,
                    'Pay_Method_Name' => 'Cash',
                    'date_export' => $request->date,
                    'created_at' => $request->date . ' ' . now()->format('H:i:s'),
                    'orginal_id' => $financial_account->orginal_id ?? 0,
                    'recive_amount' => $debitValue + $creditValue,
                    'debtor' => $debitValue,
                    'creditor' => $creditValue,
                    'dely_record' => $dely_record_count,
                    'save' => 1,
                    'cost_center' => $costCenter,
                ]);

                $this->updateAccountBalance($financial_account, $debitValue, $creditValue, $transaction);
            }

            DB::commit();
            return response()->json([1, "تمت العملية بنجاح للقيد رقم ($dely_record_count)", $dely_record_count]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([0, "حدث خطأ: " . $e->getMessage()]);
        }
    }

    /**
     * دالة تحديث حسابات الشجرة والحسابات الفرعية
     */


    /**
     * حذف القيد اليومي وعكس أرصده الشجرة بالكامل
     */
    public function journal_delete($id)
    {
        try {
            DB::beginTransaction();

            $dailyRecord = DailyRecord::find($id);
            if (!$dailyRecord) {
                return response()->json([0, "عفواً، هذا القيد غير موجود في النظام"]);
            }

            // عكس العمليات المالية بدقة قبل الحذف التام للبيانات
            $this->reverseTransactionBalances('dely_record', $id);

            credittransactions::where('dely_record', $id)->delete();
            $dailyRecord->delete();

            DB::commit();
            return response()->json([1, "تم حذف القيد رقم ($id) وعكس كافة الأرصدة المرتبطة به بنجاح"]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([0, "فشلت عملية الحذف: " . $e->getMessage()]);
        }
    }

    public function Opening_entry()
    {
        $accounts = financial_accounts::where('is_parent', 0)->where('active', 1)->get();
        $cost_centers = Cost_centers::all();
        $latest_entries = OpeningEntry::orderBy('id', 'desc')->paginate(3);
        return view('acountes.Opening_entry', compact('accounts', 'cost_centers'))->with('latest_entries', $latest_entries);
    }

    public function getLatestJournals(Request $request)
    {
        $search = $request->query('search');
        $records = DailyRecord::when($search, function ($query) use ($search) {
            return $query->where('id', 'like', "%$search%")
                ->orWhere('general_notes', 'like', "%$search%");
        })
            ->orderBy('id', 'desc')
            ->paginate(3);

        $records->appends(['search' => $search]);
        return view('acountes.latest_table', compact('records'))->render();
    }

    public function get_details($id)
    {
        $entry = DailyRecord::find($id);
        if ($entry) {
            $details = credittransactions::where('dely_record', $id)->get();
            return response()->json([
                'status' => 1,
                'data' => $entry,
                'details' => $details
            ]);
        }
        return response()->json(['status' => 0]);
    }











    private function updateAccountBalance($account, $debit, $credit, $transaction)
    {
        $amount = $debit + $credit;

        // النوع 1 و 4 (أصول ومصاريف) طبيعتها مدينة
        if ($account->account_type == 1 || $account->account_type == 4) {
            if ($debit > 0) {
                $account->increment('current_balance', $amount);
                $account->increment('debtor_current', $amount);
            } else {
                $account->decrement('current_balance', $amount);
                $account->increment('creditor_current', $amount);
            }
        }
        // النوع 2 و 3 و 5 (خصوم وحقوق ملكية وإيرادات) طبيعتها دائنة
        else {
            if ($debit > 0) {
                $account->decrement('current_balance', $amount);
                $account->increment('debtor_current', $amount);
            } else {
                $account->increment('current_balance', $amount);
                $account->increment('creditor_current', $amount);
            }
        }

        // تحديث رصيد الحركة الحالية بعد التعديل
        $transaction->update(['currentblance' => $account->current_balance]);

        // تحديث الحساب الأب (Recursive Update) إن وجد
        if ($account->parent_account_number) {
            $parent = financial_accounts::find($account->parent_account_number);
            if ($parent) {
                $this->updateAccountBalance($parent, $debit, $credit, $transaction);
            }
        }
    }





















    /**
     * دالة مساعدة لحساب الرصيد الافتتاحي وتأثيره على طبيعة الحساب
     */
    private function applyOpeningBalance($account, $item, $isDebtor, $dely_record_id)
    {
        $amount = $isDebtor ? $item->debtor : $item->creditor;

        // الحسابات ذات الطبيعة المدينة (أصول / مصروفات) = النوع 1 و 4
        $isAssetOrExpense = in_array($account->account_type, [1, 4]);

        if ($isDebtor) {
            $account->debtor_current += $amount;
            $account->debtor_opening = $amount;
            $account->current_balance += $isAssetOrExpense ? $amount : -$amount;
        } else {
            $account->creditor_current += $amount;
            $account->creditor_opening = $amount;
            $account->current_balance += $isAssetOrExpense ? -$amount : $amount;
        }
        $account->save();

        // تحديث رصيد الحركة اللحظي داخل السجل المالي
        credittransactions::where('Opening_entry', $dely_record_id)
            ->where('customer_id', $account->id)
            ->update(['currentblance' => $account->current_balance]);

        // مزامنة الأرصدة مع جدول العملاء والموردين وعكس قيمة الـ opening_balance لديهم
        if ($account->orginal_type == 1) {
            customers::where('id', $account->orginal_id)->update([
                'Balance' => $account->current_balance,
                'opeing_blance' => $amount
            ]);
        } elseif ($account->orginal_type == 2) {
            supllier::where('id', $account->orginal_id)->update([
                'In_debt' => $account->current_balance,
                'opeing_blance' => $amount,
                'updated_at' => Carbon::now()->addHours(3)
            ]);
        }
    }

    /**
     * جلب وعكس أرصدة القيود المحذوفة بالكامل
     */
    public function get_And_Delete_delyrecord($id)
    {
        try {
            DB::beginTransaction();

            $transactions = credittransactions::where('dely_record', $id)->get();
            $items = [];

            foreach ($transactions as $item) {
                $account = financial_accounts::find($item->customer_id);
                if ($account) {
                    // خصم الحركة من المجاميع الحالية للمدین والدائن
                    $account->debtor_current -= $item->debtor;
                    $account->creditor_current -= $item->creditor;
                    $this->syncExternalBalances($account);

                    // معالجة الحساب الأب وعكس الأرصدة التجميعية منه
                    if ($account->parent_account_number) {
                        $parentAccount = financial_accounts::where('account_number', $account->parent_account_number)->first();
                        if ($parentAccount) {
                            $parentTx = credittransactions::where('customer_id', $parentAccount->id)
                                ->where('parent_dely_record', $item->dely_record)
                                ->first();

                            if ($parentTx) {
                                $parentAccount->debtor_current -= $parentTx->debtor;
                                $parentAccount->creditor_current -= $parentTx->creditor;
                                $this->syncExternalBalances($parentAccount);
                                $parentTx->delete();
                            }
                        }
                    }
                }

                $items[] = [
                    'id' => $item->id,
                    'name' => $item->financial_accounts_data->name ?? 'N/A',
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                ];

                $item->delete();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'id' => $id, 'items' => $items]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * جلب وتجهيز القيد للتعديل مع تعطيل فاعليته مؤقتاً وعكس رصيده
     */
    public function getAndUpdate_delyrecord($id)
    {
        try {
            DB::beginTransaction();

            $transactions = credittransactions::where('dely_record', $id)->get();
            $items = [];

            foreach ($transactions as $item) {
                $item->update(['save' => 0]);

                $account = financial_accounts::find($item->customer_id);
                if ($account) {
                    $account->debtor_current -= $item->debtor;
                    $account->creditor_current -= $item->creditor;
                    $this->syncExternalBalances($account);

                    if ($account->parent_account_number) {
                        $parentAccount = financial_accounts::where('account_number', $account->parent_account_number)->first();
                        if ($parentAccount) {
                            $parentTx = credittransactions::where('customer_id', $parentAccount->id)
                                ->where('parent_dely_record', $item->dely_record)
                                ->first();

                            if ($parentTx) {
                                $parentTx->update(['save' => 0]);
                                $parentAccount->debtor_current -= $parentTx->debtor;
                                $parentAccount->creditor_current -= $parentTx->creditor;
                                $this->syncExternalBalances($parentAccount);
                            }
                        }
                    }
                }

                $items[] = [
                    'id' => $item->id,
                    'name' => $item->financial_accounts_data->name ?? 'N/A',
                    'account_id' => $item->customer_id,
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                ];
            }

            DB::commit();
            return ['id' => $id, 'items' => $items];

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * حذف حركة مفردة من داخل جدول القيد وإعادة موازنة الحساب
     */
    public function delete_record_by_id($id)
    {
        try {
            DB::beginTransaction();

            $tx = credittransactions::find($id);
            if (!$tx)
                return response()->json(['status' => 'error', 'message' => 'الحركة غير موجودة']);

            $account = financial_accounts::find($tx->customer_id);
            if ($account) {
                $account->debtor_current -= $tx->debtor;
                $account->creditor_current -= $tx->creditor;
                $this->syncExternalBalances($account);

                if ($account->parent_account_number) {
                    credittransactions::where('customer_id', $account->parent_account_number)
                        ->where('parent_dely_record', $tx->dely_record)
                        ->delete();
                }
            }

            $dely_record_id = $tx->dely_record;
            $tx->delete();

            $remaining_txs = credittransactions::where('dely_record', $dely_record_id)->get();
            $items = $remaining_txs->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->financial_accounts_data->name ?? 'N/A',
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                ];
            })->toArray();

            DB::commit();
            return ['id' => $dely_record_id, 'items' => $items];

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * تحديث سطر مالي مفرد داخل القيد اليومي وإعادة الموازنة الفورية للحسابين القديم والجديد
     */
    public function updatedelyrecord(Request $request)
    {
        try {
            DB::beginTransaction();

            $tx = credittransactions::find($request->transactionId);
            if (!$tx)
                return response()->json(['status' => 'error', 'message' => 'الحركة غير موجودة']);

            // 1. عكس الأرصدة القديمة من الحساب القديم قبل التعديل
            $oldAccount = financial_accounts::find($tx->customer_id);
            if ($oldAccount) {
                $oldAccount->debtor_current -= $tx->debtor;
                $oldAccount->creditor_current -= $tx->creditor;
                $this->syncExternalBalances($oldAccount);
            }

            // 2. تحديث بيانات السجل الحالي بالقيم والحساب الجديد
            $tx->update([
                'recive_amount' => $request->debit_update + $request->credit_update,
                'debtor' => $request->debit_update,
                'creditor' => $request->credit_update,
                'customer_id' => $request->clientnamesearch_update,
            ]);

            // 3. إضافة المبالغ الجديدة وتأثيرها للحساب الجديد
            $newAccount = financial_accounts::find($request->clientnamesearch_update);
            if ($newAccount) {
                $newAccount->debtor_current += $request->debit_update;
                $newAccount->creditor_current += $request->credit_update;
                $this->syncExternalBalances($newAccount);

                if ($newAccount->parent_account_number) {
                    credittransactions::where('customer_id', $newAccount->parent_account_number)
                        ->where('parent_dely_record', $tx->dely_record)
                        ->update([
                            'recive_amount' => $request->debit_update + $request->credit_update,
                            'debtor' => $request->debit_update,
                            'creditor' => $request->credit_update
                        ]);
                }
            }

            $items = credittransactions::where('dely_record', $tx->dely_record)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->financial_accounts_data->name ?? 'N/A',
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                ];
            })->toArray();

            DB::commit();
            return ['id' => $tx->dely_record, 'items' => $items];

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * اعتماد وحفظ القيود الافتتاحية مع المزامنة الشجرية للـ Types بالكامل
     */
    public function save_Opening_entry($dely_record_id)
    {
        $transactions = credittransactions::where('Opening_entry', $dely_record_id)->get();

        $credit_sum = $transactions->sum('creditor');
        $debit_sum = $transactions->sum('debtor');

        if (abs($credit_sum - $debit_sum) > 0.0001) {
            return [0, "يجب ان يكون طرفي القيد متساوين نرجو منك المراجعة قبل الضغط علي حفظ مجددا \n Both sides of the entry must be equal."];
        }

        try {
            DB::beginTransaction();

            foreach ($transactions as $item) {
                $account = financial_accounts::find($item->customer_id);
                if (!$account)
                    continue;

                if ($item->debtor > 0) {
                    $this->applyOpeningBalance($account, $item, true, $dely_record_id);
                }

                if ($item->creditor > 0) {
                    $this->applyOpeningBalance($account, $item, false, $dely_record_id);
                }
            }

            credittransactions::where('Opening_entry', $dely_record_id)->update(['save' => 1]);

            DB::commit();
            return [1, "تم حفظ القيد بنجاح \n The entry has been saved successfully"];

        } catch (\Exception $e) {
            DB::rollback();
            return [0, "حدث خطأ أثناء الحفظ الفعلي: " . $e->getMessage()];
        }
    }

    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('acountes.Daily_record');
    }

    public function print_Opening_entry(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $credittransactions = credittransactions::where('Opening_entry', $request->record_id_print)
            ->where('parent_Opening_entry', 0)
            ->get();

        $OpeningEntry = OpeningEntry::where('entry_number', $request->record_id_print)->first();
        $date = $credittransactions->first()->created_at ?? now();

        $data = $credittransactions->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->financial_accounts_data->name ?? 'N/A',
                'depit' => $item->debtor,
                'credit' => $item->creditor,
                'note' => $item->note
            ];
        })->toArray();

        return view('acountes.print_Opening_entry', compact('data'))
            ->with('date', $date)
            ->with('general_note', $OpeningEntry->general_note ?? '')
            ->with('dely_record', $request->record_id_print);
    }

    public function print_daily_record(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $credittransactions = credittransactions::where('dely_record', $request->record_id_print)->get();

        $date = $credittransactions->first()->created_at ?? now();
        $data = $credittransactions->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->financial_accounts_data->name ?? 'N/A',
                'depit' => $item->debtor,
                'credit' => $item->creditor,
                'created_at' => $item->created_at,
                'date' => $item->created_at,
                'date_export' => $item->date_export,
            ];
        })->toArray();

        return view('acountes.print_daily_record', compact('data'))
            ->with('date', $date)
            ->with('dely_record', $request->record_id_print);
    }


    /**
     * دالة مساعدة موحدة لمزامنة الكيانات الخارجية وتحديث الرصيد الحالي الصافي للحساب
     */
    private function syncExternalBalances($account)
    {
        // حساب الرصيد الحالي الفعلي للحساب بناءً على طبيعته الشجرية
        // الحسابات ذات الطبيعة المدينة (1 أو 4) = مدين - دائن | الحسابات الدائنة (2 أو 3 أو 5) = دائن - مدين
        if (in_array($account->account_type, [1, 4])) {
            $account->current_balance = $account->debtor_current - $account->creditor_current;
        } else {
            $account->current_balance = $account->creditor_current - $account->debtor_current;
        }
        $account->save();

        // تحديث جداول الموردين والعملاء الفرعية فوراً لضمان كشوف حسابات متطابقة
        if ($account->orginal_type == 1) { // عميل
            customers::where('id', $account->orginal_id)->update([
                'Balance' => $account->current_balance
            ]);
        } elseif ($account->orginal_type == 2) { // مورد
            supllier::where('id', $account->orginal_id)->update([
                'In_debt' => $account->current_balance
            ]);
        }
    }

    /**
     * اعتماد وحفظ القيود اليومية مع الترحيل لشجرة الحسابات والجهات الخارجية
     */
    public function save_Daily_record($dely_record_id)
    {
        $credittransactions = credittransactions::where('dely_record', $dely_record_id)->get();

        $credit_sum = $credittransactions->sum('creditor');
        $debit_sum = $transactions_sum = $credittransactions->sum('debtor');

        // التحقق الحرج والمطلق من توازن القيد قبل ترحيله دفترياً
        if (abs($credit_sum - $debit_sum) > 0.0001) {
            return [0, "يجب ان يكون طرفي القيد متساوين نرجو منك المراجعة قبل الضغط علي حفظ مجددا \n Both sides of the entry must be equal. Please review before clicking Save again"];
        }

        try {
            DB::beginTransaction();

            foreach ($credittransactions as $item) {
                $account = financial_accounts::find($item->customer_id);
                if (!$account)
                    continue;

                $isAssetOrExpense = in_array($account->account_type, [1, 4]);

                // معالجة الطرف المدين من الحركة
                if ($item->debtor > 0) {
                    $account->debtor_current += $item->debtor;
                    $account->current_balance += $isAssetOrExpense ? $item->debtor : -$item->debtor;
                }

                // معالجة الطرف الدائن من الحركة
                if ($item->creditor > 0) {
                    $account->creditor_current += $item->creditor;
                    $account->current_balance += $isAssetOrExpense ? -$item->creditor : $item->creditor;
                }

                $this->syncExternalBalances($account);

                // تحديث رصيد الحساب التاريخي واللحظي داخل سجل الحركة
                $item->update(['currentblance' => $account->current_balance]);

                // معالجة وتحديث الحساب الأب التجميعي في الشجرة (إن وُجد)
                if ($account->parent_account_number) {
                    $parentAccount = financial_accounts::where('account_number', $account->parent_account_number)->first();
                    if ($parentAccount) {
                        $isParentAssetOrExpense = in_array($parentAccount->account_type, [1, 4]);

                        if ($item->debtor > 0) {
                            $parentAccount->debtor_current += $item->debtor;
                            $parentAccount->current_balance += $isParentAssetOrExpense ? $item->debtor : -$item->debtor;
                        }

                        if ($item->creditor > 0) {
                            $parentAccount->creditor_current += $item->creditor;
                            $parentAccount->current_balance += $isParentAssetOrExpense ? -$item->creditor : $item->creditor;
                        }

                        $this->syncExternalBalances($parentAccount);

                        // تحديث رصيد الأب في الحركات المرتبطة بالأب
                        credittransactions::where('parent_dely_record', $dely_record_id)
                            ->where('customer_id', $parentAccount->id)
                            ->update(['currentblance' => $parentAccount->current_balance]);
                    }
                }
            }

            // تعديل حالة القيد بالكامل ليصبح معتمداً وغير قابل للتعديل المباشر دون إلغاء ترحيل
            credittransactions::where('dely_record', $dely_record_id)->update(['save' => 1]);

            DB::commit();
            return [1, "تم حفظ القيد بنجاح \n The entry has been saved successfully"];

        } catch (\Exception $e) {
            DB::rollback();
            return [0, "حدث خطأ كارثي أثناء ترحيل الحسابات: " . $e->getMessage()];
        }
    }

    /**
     * إنشاء وإضافة سطر مالي داخل القيد الافتتاحي
     */
    public function create_Opening_entry(Request $request)
    {
        $the_file_path_1 = '';
        if ($request->hasFile('attachments_1')) {
            $folder = 'assets/attachments';
            $image = $request->file('attachments_1');
            $the_file_path_1 = time() . rand(100, 999) . '.' . $image->extension();
            $image->move(public_path($folder), $the_file_path_1);
        }

        $clientId = $request->clientnamesearch_1;
        $financial_accounts = financial_accounts::find($clientId);

        $dely_record_count = $request->record_id;
        if ($request->record_id == 0) {
            $last_record = credittransactions::where('Opening_entry', '!=', 0)->orderBy('id', 'desc')->first();
            $dely_record_count = $last_record ? $last_record->Opening_entry + 1 : 1;
        }

        credittransactions::create([
            'attachments' => $the_file_path_1,
            'orginal_type' => $financial_accounts->orginal_type ?? 0,
            'user_id' => auth()->user()->id,
            'customer_id' => $clientId,
            'branchs_id' => auth()->user()->branchs_id,
            'pay_method' => 'Cash',
            'note' => $request->notes . ' | قيد افتتاحي رقم : ' . $dely_record_count,
            'currentblance' => $financial_accounts->current_balance ?? 0,
            'Pay_Method_Name' => 'Cash',
            'created_at' => $request->date,
            'updated_at' => Carbon::now()->addHours(3),
            'orginal_id' => $financial_accounts->orginal_id ?? 0,
            'recive_amount' => $request->debit_1 + $request->credit_1,
            'debtor' => $request->debit_1,
            'creditor' => $request->credit_1,
            'Opening_entry' => $dely_record_count,
            'save' => 1,
        ]);

        $items = credittransactions::where('Opening_entry', $dely_record_count)
            ->where('parent_Opening_entry', 0)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->financial_accounts_data->name ?? 'N/A',
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                ];
            })->toArray();

        return ['id' => $dely_record_count, 'items' => $items];
    }

    /**
     * إنشاء وإضافة سطر مالي داخل قيد يومية مسودة قبل الحفظ النهائي
     */
    public function daily_record(Request $request)
    {
        $the_file_path_1 = '';
        if ($request->hasFile('attachments_1')) {
            $folder = 'assets/attachments';
            $image = $request->file('attachments_1');
            $the_file_path_1 = time() . rand(100, 999) . '.' . $image->extension();
            $image->move(public_path($folder), $the_file_path_1);
        }

        $clientId = $request->clientnamesearch_1;
        $financial_accounts = financial_accounts::find($clientId);
        $date = $request->date;

        $dely_record_count = $request->record_id;
        if ($request->record_id == 0) {
            $last_record = credittransactions::where('dely_record', '!=', 0)->orderBy('id', 'desc')->first();
            $dely_record_count = $last_record ? $last_record->dely_record + 1 : 1;
        }

        $createdAt = $date != '0' ? $date . ' ' . substr(Carbon::now()->addHours(3), 12) : Carbon::now()->addHours(3);

        credittransactions::create([
            'attachments' => $the_file_path_1,
            'orginal_type' => $financial_accounts->orginal_type ?? 0,
            'user_id' => auth()->user()->id,
            'customer_id' => $clientId,
            'branchs_id' => auth()->user()->branchs_id,
            'pay_method' => 'Cash',
            'note' => $request->notes_1 . ' | قيد يومي رقم : ' . $dely_record_count,
            'currentblance' => $financial_accounts->current_balance ?? 0,
            'Pay_Method_Name' => 'Cash',
            'created_at' => $createdAt,
            'date_export' => $request->date,
            'updated_at' => Carbon::now()->addHours(3),
            'orginal_id' => $financial_accounts->orginal_id ?? 0,
            'recive_amount' => $request->debit_1 + $request->credit_1,
            'debtor' => $request->debit_1,
            'creditor' => $request->credit_1,
            'dely_record' => $dely_record_count,
            'save' => 0, // تنشأ كمسودة حتى يُضغط زر الحفظ النهائي للحفاظ على الأرصدة الحالية صافية
            'cost_center' => $request->cost_center,
        ]);

        $items = credittransactions::where('dely_record', $dely_record_count)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->financial_accounts_data->name ?? 'N/A',
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                    'account_id' => $item->customer_id,
                ];
            })->toArray();

        return ['id' => $dely_record_count, 'items' => $items];
    }










    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function updateVoncher(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // 1. جلب سجل الحركة الأساسي المراد تعديله
        $transaction = credittransactions::findOrFail($request->transactionId);
        $decument_id_recipt = $transaction->sent_abd_count;
        $type = $request->pay_type_desc;

        try {
            DB::beginTransaction();

            // --- المرحلة الأولى: إلغاء وعكس الأثر المالي القديم تماماً ---

            // أ. إلغاء أثر طريقة الدفع القديمة (الحساب الفرعي والأب)
            $oldPaymentAccount = financial_accounts::where('name', $transaction->Pay_Method_Name)->first();
            if ($oldPaymentAccount) {
                $oldPaymentAccount->decrement('current_balance', $transaction->recive_amount);
                $oldPaymentAccount->decrement('debtor_current', $transaction->recive_amount);
                $this->syncExternalBalances($oldPaymentAccount);

                if ($oldPaymentAccount->parent_account_number) {
                    $oldParentPayment = financial_accounts::where('account_number', $oldPaymentAccount->parent_account_number)->first();
                    if ($oldParentPayment) {
                        $oldParentPayment->decrement('current_balance', $transaction->recive_amount);
                        $oldParentPayment->decrement('debtor_current', $transaction->recive_amount);
                        $this->syncExternalBalances($oldParentPayment);
                    }
                }
            }

        // ب. إلغاء أثر العميل/المورد القديم (إرجاع حسابه للحالة السابقة)
        $oldCustomerAccount = financial_accounts::find($transaction->customer_id);
        if ($oldCustomerAccount) {
            $isAssetOrExpense = in_array($oldCustomerAccount->account_type, [1, 4]);

            if ($isAssetOrExpense) {
                $oldCustomerAccount->increment('current_balance', $transaction->recive_amount);
                $oldCustomerAccount->decrement('creditor_current', $transaction->recive_amount);
            } else {
                $oldCustomerAccount->increment('current_balance', $transaction->recive_amount);
                $oldCustomerAccount->decrement('debtor_current', $transaction->recive_amount);
            }
            $this->syncExternalBalances($oldCustomerAccount);
        }

            // --- المرحلة الثانية: تطبيق البيانات والأثر المالي الجديد ---

            // أ. جلب بيانات طريقة الدفع الجديدة وتحديث حساباتها
            $newPaymentAccount = financial_accounts::findOrFail($request->payupdate);
            $payment_new_text = $newPaymentAccount->name;

            $newPaymentAccount->increment('current_balance', $request->cashreceivedupdate);
            $newPaymentAccount->increment('debtor_current', $request->cashreceivedupdate);
            $this->syncExternalBalances($newPaymentAccount);

            if ($newPaymentAccount->parent_account_number) {
                $newParentPayment = financial_accounts::where('account_number', $newPaymentAccount->parent_account_number)->first();
                if ($newParentPayment) {
                    $newParentPayment->increment('current_balance', $request->cashreceivedupdate);
                    $newParentPayment->increment('debtor_current', $request->cashreceivedupdate);
                    $this->syncExternalBalances($newParentPayment);
                }
            }

            // ب. جلب بيانات العميل/المورد الجديد وتحديث حساباته
            $newCustomerAccount = financial_accounts::findOrFail($request->clientnamesearch_update);
            $isNewAssetOrExpense = in_array($newCustomerAccount->account_type, [1, 4]);

            if ($isNewAssetOrExpense) {
                $newCustomerAccount->decrement('current_balance', $request->cashreceivedupdate);
                $newCustomerAccount->increment('creditor_current', $request->cashreceivedupdate);
            } else {
                $newCustomerAccount->decrement('current_balance', $request->cashreceivedupdate);
                $newCustomerAccount->increment('debtor_current', $request->cashreceivedupdate);
            }
            $this->syncExternalBalances($newCustomerAccount);

            // --- المرحلة الثالثة: تحديث السجلات الفرعية لقيود السند ---

            // تحديث السجل المرتبط بحساب النقدية/البنك القديم ليتحول إلى الجديد
            credittransactions::where('customer_id', $oldPaymentAccount->id)
                ->where('decument_id', $request->transactionId)
                ->update([
                    'customer_id' => $newPaymentAccount->id,
                    'recive_amount' => $request->cashreceivedupdate,
                    'Pay_Method_Name' => $payment_new_text,
                    'pay_method' => $payment_new_text,
                    'debtor' => $request->cashreceivedupdate,
                    'type' => $type,
                ]);

            // تحديث السجل المرتبط بحساب الأب للنقدية/البنك القديم
            if (isset($oldParentPayment) && isset($newParentPayment)) {
                credittransactions::where('customer_id', $oldParentPayment->id)
                    ->where('decument_id', $request->transactionId)
                    ->update([
                        'customer_id' => $newParentPayment->account_number,
                        'recive_amount' => $request->cashreceivedupdate,
                        'Pay_Method_Name' => $payment_new_text,
                        'pay_method' => $payment_new_text,
                        'debtor' => $request->cashreceivedupdate,
                        'type' => $type,
                    ]);
            }

            // تحديث السجل الأساسي للسند بالبيانات والجهات الجديدة
            $transaction->update([
                'customer_id' => $request->clientnamesearch_update,
                'recive_amount' => $request->cashreceivedupdate,
                'Pay_Method_Name' => $payment_new_text,
                'pay_method' => $payment_new_text,
                'creditor' => $isNewAssetOrExpense ? $request->cashreceivedupdate : 0,
                'debtor' => !$isNewAssetOrExpense ? $request->cashreceivedupdate : 0,
                'type' => $type,
                'orginal_type' => $newCustomerAccount->orginal_type ?? 0,
                'orginal_id' => $newCustomerAccount->orginal_id ?? 0,
            ]);

            DB::commit();

            // جلب البيانات المحدثة لإرجاعها إلى الـ View أو طلب الـ AJAX
            $data = credittransactions::where('sent_abd_count', $decument_id_recipt)
                ->get()
                ->map(function ($item) use ($decument_id_recipt) {
                    return [
                        'sent_abd_count' => $decument_id_recipt,
                        'id' => $item->id,
                        'name' => $item->financial_accounts_data->name ?? 'N/A',
                        'method_pay' => $item->Pay_Method_Name,
                        'paid_amount' => $item->recive_amount,
                        'recipt_id' => $item->customer_id,
                    ];
                });

            return response()->json($data);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'حدث خطأ أثناء تعديل السند: ' . $e->getMessage()], 500);
        }
    }
    public function search_by_decoumentNo_send_abd($count)
    {
        $data = credittransactions::where('note', 'LIKE', '%سند قبض%')
            ->where('branchs_id', auth()->user()->branchs_id)
            ->where('decument_id', 0)
            ->where('sent_abd_count', $count)
            ->paginate(3);

        return view('sant_abd_ajax', compact('data'));
    }

    public function search_by_decoumentNo_send_serf($count)
    {
        $data = credittransactions::where('type_decument', 2)
            ->where('decument_id', 0)
            ->where('sent_serf_count', $count)->where('cost_center','!=', 0)

            ->paginate(3);

        return view('sant_serf_ajax', compact('data'));
    }

    public function delete_voncher($count)
    {
        $createTransaction = credittransactions::where('decument_id', 0)
            ->where('sent_abd_count', $count)
            ->first();

        if ($createTransaction) {
            credittransactions::where('note', $createTransaction->note)->delete();
        }

        $data = credittransactions::where('branchs_id', auth()->user()->branchs_id)
            ->where('note', 'LIKE', '%سند قبض%')
            ->where('decument_id', 0)
            ->orderBy('id', 'desc')
            ->paginate(3);

        return view('sant_abd_ajax', compact('data'));
    }

    public function getAndUpdatevoncher($count)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $transactions = credittransactions::where('decument_id', 0)
            ->where('sent_abd_count', $count)
            ->get();

        if ($transactions->isEmpty()) {
            return 0;
        }

        $data = [];
        foreach ($transactions as $item) {
            $data[] = [
                'sent_abd_count' => $count,
                'id' => $item->id,
                'name' => $item->financial_accounts_data->name ?? '-',
                'method_pay' => $item->Pay_Method_Name,
                'paid_amount' => $item->recive_amount,
                'recipt_id' => $item->financial_accounts_data->id ?? null,
            ];
        }

        return $data;
    }

    public function create(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $this->validate($request, [
            'cashreceived' => 'required|numeric',
        ]);

        $type = $request->pay;
        $the_file_path = '';

        if ($request->hasFile('attachments')) {
            $image = $request->file('attachments');
            $extension = $image->extension();
            $the_file_path = time() . rand(100, 999) . '.' . $extension;
            $image->move(public_path('assets/attachments'), $the_file_path);
        }

        $clientId = $request->clientnamesearch;
        $payment_id = $request->paymentmethod;

        $financial_accounts = financial_accounts::find($payment_id);
        $payment_name = $financial_accounts ? $financial_accounts->name : '';

        $sent_abd_count = $request->id_create;
        $recent_id = credittransactions::where('sent_abd_count', '!=', 0)->orderBy('id', 'desc')->first();
        if ($recent_id != null && $sent_abd_count == 1) {
            $sent_abd_count = $recent_id->sent_abd_count + 1;
        }

        $customerAccount = financial_accounts::find($clientId);
        $now = Carbon::now()->addHours(3);

        if (in_array($customerAccount->account_type, [1, 4])) {
            $customerAccount->update([
                'current_balance' => $customerAccount->debtor_current - ($customerAccount->creditor_current + $request->cashreceived),
                'creditor_current' => $customerAccount->creditor_current + $request->cashreceived,
            ]);

            $currentBalanceValue = $customerAccount->debtor_current - ($customerAccount->creditor_current + $request->cashreceived);
        } else {
            $customerAccount->update([
                'current_balance' => ($customerAccount->debtor_current + $request->cashreceived) - $customerAccount->creditor_current,
                'creditor_current' => $customerAccount->debtor_current + $request->cashreceived,
            ]);

            $currentBalanceValue = ($customerAccount->debtor_current + $request->cashreceived) - $customerAccount->creditor_current;
        }

        $createTransaction = credittransactions::create([
            'attachments' => $the_file_path,
            'orginal_type' => $customerAccount->orginal_type ?? 0,
            'user_id' => auth()->user()->id,
            'customer_id' => $clientId,
            'recive_amount' => $request->cashreceived,
            'branchs_id' => auth()->user()->branchs_id,
            'pay_method' => $payment_name,
            'date_export' => $request->date,
            'note' => $request->notes . '| سند قبض | : ' . (string) $sent_abd_count,
            'currentblance' => $currentBalanceValue,
            'Pay_Method_Name' => $payment_name,
            'created_at' => $now,
            'updated_at' => $now,
            'orginal_id' => $customerAccount->orginal_id ?? 0,
            'debtor' => 0,
            'creditor' => $request->cashreceived,
            'sent_abd_count' => $sent_abd_count,
            'type' => $type,
        ]);

        // Handled Payment Method updates logically relative to condition
        if ($financial_accounts) {
            $parent_account_number = $financial_accounts->parent_account_number;
            $financial_accounts->update([
                'current_balance' => ($financial_accounts->debtor_current + $request->cashreceived) - $financial_accounts->creditor_current,
                'debtor_current' => $financial_accounts->debtor_current + $request->cashreceived,
            ]);

            credittransactions::create([
                'attachments' => $the_file_path,
                'orginal_type' => 0,
                'user_id' => auth()->user()->id,
                'customer_id' => $payment_id,
                'recive_amount' => $request->cashreceived,
                'branchs_id' => auth()->user()->branchs_id,
                'pay_method' => $payment_name,
                'note' => $request->notes . '| سند قبض | : ' . (string) $sent_abd_count,
                'currentblance' => ($financial_accounts->debtor_current + $request->cashreceived) - $financial_accounts->creditor_current,
                'Pay_Method_Name' => $payment_name,
                'created_at' => $now,
                'date_export' => $request->date,
                'updated_at' => $now,
                'orginal_id' => $financial_accounts->orginal_id ?? 0,
                'debtor' => $request->cashreceived,
                'creditor' => 0,
                'decument_id' => $createTransaction->id,
                'type' => $type,
            ]);

            if ($parent_account_number) {
                $parentAccount = financial_accounts::find($parent_account_number);
                if ($parentAccount) {
                    $parentAccount->update([
                        'current_balance' => ($parentAccount->debtor_current + $request->cashreceived) - $parentAccount->creditor_current,
                        'debtor_current' => $parentAccount->debtor_current + $request->cashreceived,
                    ]);

                    credittransactions::create([
                        'attachments' => $the_file_path,
                        'orginal_type' => 0,
                        'user_id' => auth()->user()->id,
                        'customer_id' => $parent_account_number,
                        'recive_amount' => $request->cashreceived,
                        'branchs_id' => auth()->user()->branchs_id,
                        'pay_method' => $payment_name,
                        'note' => $request->notes . '| سند قبض | : ' . (string) $sent_abd_count,
                        'currentblance' => ($parentAccount->debtor_current + $request->cashreceived) - $parentAccount->creditor_current,
                        'Pay_Method_Name' => $payment_name,
                        'created_at' => $now,
                        'date_export' => $request->date,
                        'updated_at' => $now,
                        'orginal_id' => $parentAccount->orginal_id ?? 0,
                        'debtor' => $request->cashreceived,
                        'creditor' => 0,
                        'decument_id' => $createTransaction->id,
                        'type' => $type,
                    ]);
                }
            }
        }

        // External original entities synchronizations
        if ($customerAccount->orginal_type == 2) {
            supllier::where('id', $customerAccount->orginal_id)->update([
                'In_debt' => $customerAccount->current_balance,
                'updated_at' => $now,
            ]);
        } elseif ($customerAccount->orginal_type == 1) {
            customers::where('id', $customerAccount->orginal_id)->update([
                'Balance' => $customerAccount->current_balance,
            ]);
        } elseif ($customerAccount->orginal_type == 3) {
            $reason_data = Expenses_reasons::find($customerAccount->orginal_id);
            if ($reason_data) {
                expenses::create([
                    'attachments' => $the_file_path,
                    'user_id' => auth()->user()->id,
                    'Pay_Method_Name' => $payment_name,
                    'branchs_id' => auth()->user()->branchs_id,
                    'Reasonforspendingmoney' => $reason_data->expenses_reason,
                    'reasonId_id' => $customerAccount->orginal_id,
                    'notes' => $request->notes ?? '-',
                    'expensesAvt' => $reason_data->expensesAvt,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'Theـamountـpaid' => $request->cashreceived * (-1),
                    'Transaction_id' => $createTransaction->id,
                ]);
            }
        }

        $credittransactions = credittransactions::where('sent_abd_count', $sent_abd_count)->get();
        $data = [];
        foreach ($credittransactions as $item) {
            $data[] = [
                'sent_abd_count' => $sent_abd_count,
                'id' => $item->id,
                'name' => $item->financial_accounts_data->name ?? '-',
                'recipt_id' => $item->financial_accounts_data->id ?? null,
                'method_pay' => $item->Pay_Method_Name,
                'paid_amount' => $item->recive_amount
            ];
        }

        return $data;
    }

    public function getAndUpdate_reciptdecument($count)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $transactions = credittransactions::where('type_decument', 2)
            ->where('sent_serf_count', $count)
            ->get();

        if ($transactions->isEmpty()) {
            return 0;
        }

        $data = [];
        foreach ($transactions as $item) {
            $data[] = [
                'sent_serf_count' => $count,
                'id' => $item->id,
                'name' => $item->financial_accounts_data->name ?? '-',
                'method_pay' => $item->Pay_Method_Name,
                'paid_amount' => $item->recive_amount
            ];
        }

        return $data;
    }

    public function store(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $the_file_path = '';
        if ($request->hasFile('attachments')) {
            $image = $request->file('attachments');
            $extension = $image->extension();
            $the_file_path = time() . rand(100, 999) . '.' . $extension;
            $image->move(public_path('assets/attachments'), $the_file_path);
        }

        $financial_accounts = financial_accounts::find($request->paymentmethod);
        $payment_name = $financial_accounts ? $financial_accounts->name : '';
        $type = $request->pay;
        $payment_id = $request->paymentmethod;
        $breanchas_id = $financial_accounts ? $financial_accounts->branchs_id : auth()->user()->branchs_id;

        $clientId = $request->clientnamesearch;
        $customerdata = financial_accounts::find($clientId);

        $sent_serf_count = $request->id_create;
        $recent_id = credittransactions::where('type_decument', 2)
            ->where('decument_id', 0)
            ->orderBy('id', 'desc')
            ->first();

        if ($recent_id != null && $sent_serf_count == 1) {
            $sent_serf_count = $recent_id->sent_serf_count + 1;
        }

        $now = Carbon::now()->addHours(3);

        if (in_array($customerdata->account_type, [1, 4])) {
            $customerdata->update([
                'current_balance' => $customerdata->current_balance + $request->cashreceived,
                'debtor_current' => $customerdata->debtor_current + $request->cashreceived,
            ]);
            $currentBlanceVal = $customerdata->current_balance;
        } else {
            $customerdata->update([
                'current_balance' => $customerdata->current_balance - $request->cashreceived,
                'debtor_current' => $customerdata->debtor_current + $request->cashreceived,
            ]);
            $currentBlanceVal = $customerdata->current_balance;
        }

        $createTransaction = credittransactions::create([
            'attachments' => $the_file_path,
            'orginal_type' => $customerdata->orginal_type ?? 0,
            'user_id' => auth()->user()->id,
            'customer_id' => $clientId,
            'recive_amount' => $request->cashreceived,
            'branchs_id' => $breanchas_id,
            'pay_method' => $payment_name,
            'note' => $request->notes . '| سند صرف | : ' . (string) $sent_serf_count,
            'currentblance' => $currentBlanceVal,
            'Pay_Method_Name' => $payment_name,
            'created_at' => $now,
            'date_export' => $request->date,
            'updated_at' => $now,
            'orginal_id' => $customerdata->orginal_id ?? 0,
            'creditor' => 0,
            'debtor' => $request->cashreceived,
            'vat' => $request->AVT,
            'type_decument' => 2,
            'sent_serf_count' => $sent_serf_count,
            'type' => $type,
            'cost_center' => $request->cost_center,
        ]);

        // VAT handling
        if ($request->AVT) {
            $total_value = $request->cashreceived;
            $vat_amount = $total_value - ($total_value * 100 / 115);

            $vatAccount = financial_accounts::find(102);
            if ($vatAccount) {
                $vatAccount->update([
                    'current_balance' => ($vatAccount->debtor_current + $vat_amount) - $vatAccount->creditor_current,
                    'debtor_current' => $vatAccount->debtor_current + $vat_amount,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => 102,
                    'recive_amount' => $vat_amount,
                    'branchs_id' => $breanchas_id,
                    'pay_method' => $payment_name,
                    'note' => $request->notes . '| سند صرف | : ' . (string) $sent_serf_count,
                    'currentblance' => ($vatAccount->debtor_current) - $vatAccount->creditor_current,
                    'Pay_Method_Name' => $payment_name,
                    'created_at' => $now,
                    'date_export' => $request->date,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'debtor' => $vat_amount,
                    'creditor' => 0,
                    'vat' => 1,
                    'name' => $request->name_buyer ?? '-',
                    'tax' => $request->TaxـNumber ?? '0',
                    'decument_id' => $createTransaction->id,
                    'type' => $type,
                                'sent_serf_count' => $sent_serf_count,

                ]);
            }

            $subVatAccount = financial_accounts::where('parent_account_number', 102)->where('branchs_id', auth()->user()->branchs_id)->first();
            if ($subVatAccount) {
                $subVatAccount->update([
                    'current_balance' => ($subVatAccount->debtor_current + $vat_amount) - $subVatAccount->creditor_current,
                    'debtor_current' => $subVatAccount->debtor_current + $vat_amount,
                ]);

                credittransactions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $subVatAccount->id,
                    'recive_amount' => $vat_amount,
                    'branchs_id' => $breanchas_id,
                    'pay_method' => $payment_name,
                    'note' => $request->notes . '| سند صرف | : ' . (string) $sent_serf_count,
                    'currentblance' => ($subVatAccount->debtor_current) - $subVatAccount->creditor_current,
                    'Pay_Method_Name' => $payment_name,
                    'created_at' => $now,
                    'date_export' => $request->date,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'debtor' => $vat_amount,
                    'creditor' => 0,
                    'vat' => 1,
                    'name' => $request->name_buyer ?? '-',
                    'tax' => $request->TaxـNumber ?? '0',
                    'decument_id' => $createTransaction->id,
                    'type' => $type,
                ]);
            }
        }

        // Update Payment Source Accounts
        if ($financial_accounts) {
            $parent_account_number = $financial_accounts->parent_account_number;
            $financial_accounts->update([
                'current_balance' => $financial_accounts->debtor_current - ($financial_accounts->creditor_current + $request->cashreceived),
                'creditor_current' => $financial_accounts->creditor_current + $request->cashreceived,
            ]);

            credittransactions::create([
                'attachments' => $the_file_path,
                'orginal_type' => 0,
                'user_id' => auth()->user()->id,
                'customer_id' => $payment_id,
                'recive_amount' => $request->cashreceived,
                'branchs_id' => $breanchas_id,
                'pay_method' => $payment_name,
                'note' => $request->notes . '| سند صرف | : ' . (string) $sent_serf_count,
                'currentblance' => $financial_accounts->debtor_current - $financial_accounts->creditor_current,
                'Pay_Method_Name' => $payment_name,
                'created_at' => $now,
                'date_export' => $request->date,
                'updated_at' => $now,
                'orginal_id' => $financial_accounts->orginal_id ?? 0,
                'creditor' => $request->cashreceived,
                'name' => $request->name_buyer ?? '-',
                'tax' => $request->TaxـNumber ?? '0',
                'debtor' => 0,
                'decument_id' => $createTransaction->id,
                'type' => $type,
            ]);

            if ($parent_account_number) {
                $parentAccount = financial_accounts::find($parent_account_number);
                if ($parentAccount) {
                    $parentAccount->update([
                        'current_balance' => $parentAccount->debtor_current - ($parentAccount->creditor_current + $request->cashreceived),
                        'creditor_current' => $parentAccount->creditor_current + $request->cashreceived,
                    ]);

                    credittransactions::create([
                        'attachments' => $the_file_path,
                        'orginal_type' => 0,
                        'user_id' => auth()->user()->id,
                        'customer_id' => $parent_account_number,
                        'recive_amount' => $request->cashreceived,
                        'branchs_id' => $breanchas_id,
                        'pay_method' => $payment_name,
                        'note' => $request->notes . '| سند صرف | : ' . (string) $sent_serf_count,
                        'currentblance' => $parentAccount->debtor_current - $parentAccount->creditor_current,
                        'Pay_Method_Name' => $payment_name,
                        'created_at' => $now,
                        'date_export' => $request->date,
                        'updated_at' => $now,
                        'orginal_id' => $parentAccount->orginal_id ?? 0,
                        'creditor' => $request->cashreceived,
                        'name' => $request->name_buyer ?? '-',
                        'tax' => $request->TaxـNumber ?? '0',
                        'debtor' => 0,
                        'decument_id' => $createTransaction->id,
                        'type' => $type,
                    ]);
                }
            }
        }

        if ($customerdata->orginal_type == 2) {
            supllier::where('id', $customerdata->orginal_id)->update([
                'In_debt' => $customerdata->current_balance,
                'updated_at' => $now,
            ]);
        } elseif ($customerdata->orginal_type == 3) {
            $reason_data = Expenses_reasons::find($customerdata->orginal_id);
            if ($reason_data) {
                expenses::create([
                    'attachments' => $the_file_path,
                    'user_id' => auth()->user()->id,
                    'Pay_Method_Name' => $type,
                    'branchs_id' => $breanchas_id,
                    'Reasonforspendingmoney' => $reason_data->expenses_reason,
                    'reasonId_id' => $customerdata->orginal_id,
                    'notes' => $request->notes . '| سند صرف | : ' . (string) $sent_serf_count,
                    'expensesAvt' => $reason_data->expensesAvt,
                    'created_at' => $now,
                    'date_export' => $request->date,
                    'updated_at' => $now,
                    'Theـamountـpaid' => $request->cashreceived,
                    'type' => 2,
                ]);
            }
        }

        $credittransactions = credittransactions::where('sent_serf_count', $sent_serf_count)->where('type_decument', 2)->get();
        $data = [];
        foreach ($credittransactions as $item) {
            $data[] = [
                'customer_id' => $item->customer_id,
                'sent_serf_count' => $sent_serf_count,
                'id' => $item->id,
                'name' => $item->financial_accounts_data->name ?? '-',
                'method_pay' => $item->Pay_Method_Name,
                'paid_amount' => $item->recive_amount
            ];
        }

        return $data;
    }





    public function updaterecieptdecoument(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $newcustomer = $request->payupdate;
        $type = $request->pay_type_desc;
        $now = Carbon::now()->addHours(3);

        $transaction = credittransactions::find($request->transactionId);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // --- 1. Handle Client Account Switching Logic ---
        if ($request->clientnamesearch_update != $transaction->customer_id) {
            $customerdata_old_account = financial_accounts::find($transaction->customer_id);
            $customerdata_new_account = financial_accounts::find($request->clientnamesearch_update);

            $transaction->update([
                'customer_id' => $request->clientnamesearch_update
            ]);

            // Process New Account Balance
            if ($customerdata_new_account) {
                $customerdata_new_account->update([
                    'debtor_current' => $customerdata_new_account->debtor_current + $request->cashreceivedupdate
                ]);

                if ($customerdata_new_account->orginal_type == 2) {
                    $supplierNew = supllier::find($customerdata_new_account->orginal_id);
                    if ($supplierNew) {
                        $supplierNew->update([
                            'In_debt' => $supplierNew->In_debt + $request->cashreceivedupdate,
                            'updated_at' => $now
                        ]);
                    }
                } elseif ($customerdata_new_account->orginal_type == 3) {
                    expenses::where('type', 2)
                        ->where('Transaction_id', $request->transactionId)
                        ->update(['reasonId_id' => $customerdata_new_account->orginal_id]);
                }
            }

            // Process Old Account Balance safely
            if ($customerdata_old_account) {
                $customerdata_old_account->update([
                    'debtor_current' => $customerdata_old_account->debtor_current - $transaction->recive_amount
                ]);

                if ($customerdata_old_account->orginal_type == 2) {
                    $supplierOld = supllier::find($customerdata_old_account->orginal_id);
                    if ($supplierOld) {
                        $supplierOld->update([
                            'In_debt' => $supplierOld->In_debt - $transaction->recive_amount,
                            'updated_at' => $now
                        ]);
                    }
                }
            }
        }

        // --- 2. Handle Reversing Old Payment Methods ---
        $decument_id_recipt = $transaction->sent_serf_count;
        $clientId = $transaction->customer_id;

        $financial_accounts_payment = financial_accounts::find($newcustomer);
        $payment_new_text = $financial_accounts_payment ? $financial_accounts_payment->name : '';

    $old_payment_account = financial_accounts::where('name', $transaction->Pay_Method_Name)->first();

    if ($old_payment_account) {
        $old_payment_id = $old_payment_account->id;
        $parent_payment_old = $old_payment_account->parent_account_number;

            $old_payment_account->update([
                'current_balance' => $old_payment_account->current_balance + $transaction->recive_amount,
                'creditor_current' => $old_payment_account->creditor_current - $transaction->recive_amount,
            ]);

            if ($parent_payment_old) {
                $parentOldAccount = financial_accounts::find($parent_payment_old);
                if ($parentOldAccount) {
                    $parentOldAccount->update([
                        'current_balance' => $parentOldAccount->current_balance + $transaction->recive_amount,
                        'creditor_current' => $parentOldAccount->creditor_current - $transaction->recive_amount,
                    ]);
                }
            }

            // Update old transaction records referencing the old payment account
            credittransactions::where('customer_id', $old_payment_id)
                ->where('decument_id', $request->transactionId)
                ->update([
                    'customer_id' => $newcustomer,
                    'recive_amount' => $request->cashreceivedupdate,
                    'Pay_Method_Name' => $payment_new_text,
                    'creditor' => $request->cashreceivedupdate,
                    'type' => $type,
                ]);

            if ($financial_accounts_payment && $parent_payment_old) {
                credittransactions::where('customer_id', $parent_payment_old)
                    ->where('decument_id', $request->transactionId)
                    ->update([
                        'customer_id' => $financial_accounts_payment->parent_account_number,
                        'recive_amount' => $request->cashreceivedupdate,
                        'Pay_Method_Name' => $payment_new_text,
                        'creditor' => $request->cashreceivedupdate,
                        'type' => $type,
                    ]);
            }
        }

        // --- 3. Handle VAT Re-calculations ---
        if ($transaction->vat) {
            $total_value_old = $transaction->recive_amount;
            $total_value = $request->cashreceivedupdate;
            $old_vat_calc = $total_value_old - ($total_value_old * 100 / 115);
            $new_vat_calc = $total_value - ($total_value * 100 / 115);
            $vat_diff = $new_vat_calc - $old_vat_calc;

            $vatAccount = financial_accounts::find(102);
            if ($vatAccount) {
                $vatAccount->update([
                    'current_balance' => ($vatAccount->debtor_current + $vat_diff) - $vatAccount->creditor_current,
                    'debtor_current' => $vatAccount->debtor_current + $vat_diff,
                ]);

                credittransactions::where('customer_id', 102)
                    ->where('decument_id', $request->transactionId)
                    ->update([
                        'recive_amount' => $new_vat_calc,
                        'pay_method' => $payment_new_text,
                        'currentblance' => ($vatAccount->debtor_current) - $vatAccount->creditor_current,
                        'Pay_Method_Name' => $payment_new_text,
                        'debtor' => $new_vat_calc,
                        'type' => $type,
                    ]);
            }

        $subVatAccount = financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', auth()->user()->branchs_id)
            ->first();

        if ($subVatAccount) {
            $subVatAccount->update([
                'current_balance' => $subVatAccount->current_balance + $vat_diff,
                'debtor_current'  => $subVatAccount->debtor_current + $vat_diff,
            ]);
        }
    }

        // --- 4. Apply New Payment Method Impacts ---
        if ($financial_accounts_payment) {
            $financial_accounts_payment->update([
                'current_balance' => $financial_accounts_payment->current_balance - $request->cashreceivedupdate,
                'creditor_current' => $financial_accounts_payment->creditor_current + $request->cashreceivedupdate,
            ]);

            if ($financial_accounts_payment->parent_account_number) {
                $parentNewAccount = financial_accounts::find($financial_accounts_payment->parent_account_number);
                if ($parentNewAccount) {
                    $parentNewAccount->update([
                        'current_balance' => $parentNewAccount->current_balance - $request->cashreceivedupdate,
                        'creditor_current' => $parentNewAccount->creditor_current + $request->cashreceivedupdate,
                    ]);
                }
            }
        }

        // --- 5. Apply Client Dynamic Current Balances ---
        $customerdata = financial_accounts::find($clientId);

    if ($customerdata) {
        if (in_array($customerdata->account_type, [1, 4])) {
            $new_balance = $customerdata->current_balance - $transaction->recive_amount + $request->cashreceivedupdate;

            $customerdata->update([
                'current_balance' => $new_balance,
                'debtor_current'  => $customerdata->debtor_current - $transaction->recive_amount + $request->cashreceivedupdate,
            ]);

                credittransactions::where('id', $request->transactionId)->update([
                    'recive_amount' => $request->cashreceivedupdate,
                    'currentblance' => $new_balance,
                    'Pay_Method_Name' => $payment_new_text,
                    'debtor' => $request->cashreceivedupdate,
                    'type' => $type,
                ]);
            } else {
                $new_balance = $customerdata->current_balance + $transaction->recive_amount - $request->cashreceivedupdate;

                $customerdata->update([
                    'current_balance' => $new_balance,
                    'debtor_current' => $customerdata->debtor_current - $transaction->recive_amount + $request->cashreceivedupdate,
                ]);

                credittransactions::where('id', $request->transactionId)->update([
                    'recive_amount' => $request->cashreceivedupdate,
                    'currentblance' => $new_balance,
                    'Pay_Method_Name' => $payment_new_text,
                    'debtor' => $request->cashreceivedupdate,
                    'type' => $type,
                ]);
            }

            // --- 6. Sync Underlying Operational Subsystems ---
            if ($customerdata->orginal_type == 2) {
                supllier::where('id', $customerdata->orginal_id)->update([
                    'In_debt' => $customerdata->current_balance,
                    'updated_at' => $now,
                ]);
            } elseif ($customerdata->orginal_type == 3) {
                expenses::where('type', 2)
                    ->where('Transaction_id', $request->transactionId)
                    ->update([
                        'Pay_Method_Name' => $payment_new_text,
                        'updated_at' => $now,
                        'Theـamountـpaid' => $request->cashreceivedupdate,
                    ]);
            }
        }

        // --- 7. Build Standard Response Output ---
        $credittransactions = credittransactions::where('sent_serf_count', $decument_id_recipt)
            ->where('type_decument', 2)
            ->get();

        $data = [];
        foreach ($credittransactions as $item) {
            $data[] = [
                'sent_serf_count' => $decument_id_recipt,
                'id' => $item->id,
                'name' => $item->financial_accounts_data->name ?? '-',
                'method_pay' => $item->Pay_Method_Name,
                'paid_amount' => $item->recive_amount
            ];
        }

        return $data;
    }











    /**
     * Display the specified resource.
     *
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function show(credittransactions $credittransactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function edit(credittransactions $credittransactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, credittransactions $credittransactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\credittransactions  $credittransactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(credittransactions $credittransactions)
    {
        //
    }
}