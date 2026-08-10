<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Models\transactiontosuplliers;
use App\Models\supllier;
use App\Models\User;
use App\Models\expenses;
use App\Models\convertcashboxToBank;
use App\Models\customers;
use App\Models\credittransactions;
use App\Models\invoices;
use App\Models\financial_accounts;
use Hassanhelfi\NumberToArabic\NumToArabic;
use App\Models\Cost_centers;
use App\Models\cash_from__bank;
use App\Models\Transfer_cash_to_the_next_day;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use Illuminate\Support\Facades\DB;
class AcountesController extends Controller
{
    function uploadImage($folder, $image)
    {
        $extension = strtolower($image->extension());
        $filename = time() . rand(100, 999) . '.' . $extension;
        $image->getClientOriginalName = $filename;
        $image->move($folder, $filename);
        return $filename;
    }
    public function Statement_of_Changes_in_Equity_Report()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());



        return view('reports.Statement_of_Changes_in_Equity_Report');
    }
    public function cashFlowStatement()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());



        return view('reports.cashFlowStatement');
    }

public function cashFlowStatementSearch(Request $request){
// 1. استقبال تواريخ البحث
    $fromDate = $request->input('start_at', date('Y-01-01'));
    $toDate = $request->input('end_at', date('Y-m-d'));

    // 2. النقدية في بداية الفترة (حسابات الصندوق والبنوك - بناءً على طبيعة جدولك)
    // يمكنك تحديد رقم حساب الأب للصندوق/البنوك أو جلبها بالنوع المناسب
    $cashAccountIds = DB::table('financial_accounts')
        ->whereIn('parent_account_number', [4,5]) // الأصول المتداولة أو النقدية
        ->pluck('id');

    $beginningCash = 0;
    foreach ($cashAccountIds as $accId) {
        $totals = DB::table('credittransactions')
            ->where('customer_id', $accId)
            ->where('save', 1)
            ->where('created_at', '<', $fromDate . ' 00:00:00')
            ->select(DB::raw('SUM(debtor) as total_debtor'), DB::raw('SUM(creditor) as total_creditor'))
            ->first();
        
        $beginningCash += (($totals->total_debtor ?? 0) - ($totals->total_creditor ?? 0));
    }

    // 3. الأنشطة التشغيلية (حركة النقد الداخل والخارج خلال الفترة)
    $operatingInflows = DB::table('credittransactions')
        ->whereIn('customer_id', $cashAccountIds)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->sum('debtor');

    $operatingOutflows = DB::table('credittransactions')
        ->whereIn('customer_id', $cashAccountIds)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->sum('creditor');

    $operatingCashFlow = $operatingInflows - $operatingOutflows;

    // 4. الأنشطة الاستثمارية (المرتبطة بالأصول الثابتة التي رقم الأب لها 74 أو الأصول نفسها)
    $fixedAssetsIds = DB::table('financial_accounts')
        ->where('parent_account_number', 74)
        ->orWhere('id', 74)
        ->pluck('id');

    $investingInflows = DB::table('credittransactions')
        ->whereIn('customer_id', $fixedAssetsIds)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->sum('creditor'); // بيع أصول (تدفق داخل)

    $investingOutflows = DB::table('credittransactions')
        ->whereIn('customer_id', $fixedAssetsIds)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->sum('debtor'); // شراء أصول (تدفق خارج)

    $investingCashFlow = $investingInflows - $investingOutflows;

    // 5. الأنشطة التمويلية (المرتبطة بحقوق الملكية - account_type = 5 كما رأينا في جدول الأنواع)
    $equityAccountIds = DB::table('financial_accounts')
        ->where('account_type', 5)
        ->pluck('id');

    $financingInflows = DB::table('credittransactions')
        ->whereIn('customer_id', $equityAccountIds)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->sum('creditor'); // إضافات رأس المال أو قروض (داخل)

    $financingOutflows = DB::table('credittransactions')
        ->whereIn('customer_id', $equityAccountIds)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->sum('debtor'); // مسحوبات شخصية (خارج)

    $financingCashFlow = $financingInflows - $financingOutflows;

    // 6. صافي التغير والنهاية
    $netCashChange = $operatingCashFlow + $investingCashFlow + $financingCashFlow;
    $endingCash = $beginningCash + $netCashChange;

    return view('reports.cash_flows', compact(
        'fromDate',
        'toDate',
        'beginningCash',
        'operatingCashFlow',
        'investingCashFlow',
        'financingCashFlow',
        'netCashChange',
        'endingCash'
    ));


}
public function changesInEquity(Request $request)
{
    // 1. استقبال تاريخي البحث
    $fromDate = $request->input('start_at', date('Y-01-01'));
    $toDate = $request->input('end_at', date('Y-m-d'));

    // 2. جلب الحسابات الخاصة بحقوق الملكية (Account Type = 5 حسب جدول acountes_types)
    $equityAccounts = DB::table('financial_accounts')
        ->where('account_type', 5)
        ->get();

    // 3. حساب رصيد حقوق الملكية في بداية الفترة
    $beginningCapital = 0;
    foreach ($equityAccounts as $account) {
        $transQuery = DB::table('credittransactions')
            ->where('customer_id', $account->id)
            ->where('save', 1);

        if ($fromDate) {
            $transQuery->where('created_at', '<', $fromDate . ' 00:00:00');
        }

        $totals = $transQuery->select(
            DB::raw('SUM(debtor) as total_debtor'),
            DB::raw('SUM(creditor) as total_creditor')
        )->first();

        $debit = $totals->total_debtor ?? 0;
        $credit = $totals->total_creditor ?? 0;

        $openingBalance = ($account->opening_balance ?? 0) + ($credit - $debit);
        $beginningCapital += $openingBalance;
    }

    // 4. حساب عناصر قائمة الدخل (الربح أو الخسارة) بناءً على طريقة الـ search_profit_and_lost الخاصة بك
    $expense = DB::table('credittransactions')
        ->where('orginal_type', 3)
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->get();

    $idsSales = DB::table('financial_accounts')->where('parent_account_number', 112)->pluck('id');
    $sales = DB::table('credittransactions')
        ->whereIn('customer_id', $idsSales)
        ->where('note', 'LIKE', '%فاتورة مبيعات%')
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->get();

    $idsSalesReturn = DB::table('financial_accounts')->where('parent_account_number', 184)->pluck('id');
    $sales_return = DB::table('credittransactions')
        ->whereIn('customer_id', $idsSalesReturn)
        ->where('note', 'LIKE', '%فاتورة مرتجع مبيعات%')
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->get();

    $idsPurchase = DB::table('financial_accounts')->where('parent_account_number', 183)->pluck('id');
    $purchase = DB::table('credittransactions')
        ->whereIn('customer_id', $idsPurchase)
        ->where('note', 'LIKE', '%فاتورة مبيعات%')
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->get();

    $idsPurchaseReturn = DB::table('financial_accounts')->where('parent_account_number', 181)->pluck('id');
    $purchase_return = DB::table('credittransactions')
        ->whereIn('customer_id', $idsPurchaseReturn)
        ->where('note', 'LIKE', '%مرتجع مشتريات فاتورة%')
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('save', 1)
        ->get();

    // تجميع القيم المالية تماماً كما في نظامك
    $expense_total_value = $expense->sum('recive_amount');
    $sales_total_value = $sales->sum('recive_amount');
    $sales_return_total_value = $sales_return->sum('recive_amount');
    $purchase_total_value = $purchase->sum('recive_amount');
    $purchase_return_total_value = $purchase_return->sum('recive_amount');

    // حساب صافي الدخل (المبيعات والمرتفعات والمشتريات والمصروفات)
    // صافي الإيرادات / المبيعات = (المبيعات - مرتجع المبيعات)
    // صافي المشتريات = (المشتريات - مرتجع المشتريات)
    // صافي الربح = إجمالي الدخل أو الإيرادات التشغيلية مطروحاً منه المصروفات
    $netSales = $sales_total_value - $sales_return_total_value;
    $netPurchases = $purchase_total_value - $purchase_return_total_value;
    
    // صافي الدخل للفترة (يمكنك تعديل المعادلة الرياضية لصافي الربح بما يطابق تفصيل تقرير الأرباح والخسائر لديك)
    $netIncome = $netSales - $netPurchases - $expense_total_value;

    // 5. الإضافات والمسحوبات الشخصية خلال الفترة
    $capitalAdditions = 0;
    $drawings = 0;

    foreach ($equityAccounts as $account) {
        $periodTotals = DB::table('credittransactions')
            ->where('customer_id', $account->id)
            ->where('save', 1)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->select(
                DB::raw('SUM(debtor) as period_debtor'),
                DB::raw('SUM(creditor) as period_creditor')
            )->first();

        $capitalAdditions += ($periodTotals->period_creditor ?? 0);
        $drawings += ($periodTotals->period_debtor ?? 0);
    }

    // 6. الرصيد الختامي لحقوق الملكية في نهاية الفترة
    $endingCapital = $beginningCapital + $capitalAdditions - $drawings + $netIncome;

    // إرسال البيانات لواجهة الـ Blade
    return view('reports.changes_in_equity', compact(
        'beginningCapital',
        'capitalAdditions',
        'drawings',
        'netIncome',
        'endingCapital',
        'fromDate',
        'toDate'
    ));}










    public function voncher()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // الحسابات التي سنقبض منها (العملاء/الموردين/الخ..)
        $accounts = financial_accounts::where('active', 1)->where('is_parent', 0)->where('orginal_type','!=',2)->where('id','!=',1)->get();
        
        // الحسابات التي سيتم الإيداع فيها (الصناديق والبنوك فقط)
        $main_accounts = financial_accounts::whereIn('orginal_type', [1, 2])->get(); 

        // جلب مراكز التكلفة
        $cost_centers = Cost_centers::all(); 

        return view('acountes.voncher', compact('accounts', 'main_accounts', 'cost_centers'));
    }

    public function convertcashboxToBank()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];

        return view('acountes.convertcashboxToBank', compact('data'));
    }

    public function Cash_withdrawal_from_the_bank()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];

        return view('acountes.Cash_withdrawal_from_the_bank', compact('data'));
    }

    public function Transfer_cash_to_next_day()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];

        return view('acountes.Transfer_cash_to_next_day', compact('data'));
    }

    public function transferMainBranch()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('acountes.Transfertomainbranch', compact('data'));
    }

    public function go_to_bank()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('acountes.cash_from_bank', compact('data'));
    }

    public function confirmTransfertomainbranch()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        return view('acountes.confirm_transferTomainBranch', compact('data'));
    }

    public function Transfercashto_the_next_day(Request $request)
    {
        $data = Transfer_cash_to_the_next_day::create([
            'user_id' => Auth()->user()->id,
            'branchs_id' => Auth()->user()->branchs_id,
            'amount' => $request->The_amount_transferred_amount,
            'currentamount'=>$request->bank_balance_amount,
            'note' => $request->notes,
            'created_at' =>$request->date,
        ]);

        $data1 = [
            'id' => $data->id,
            'user' => $data->user->name,
            'branch' => $data->branch->name,
            'the_amount' => $data->amount,
            'currentamount'=>$data->currentamount,
            'date' => $data->created_at->format('d/m/Y'),
        ];
        return $data1;
    }

    public function updatedecoumentcashNextDay(Request $request)
    {
        Transfer_cash_to_the_next_day::find($request->transactionId)->update([
            'amount' => $request->The_amount_transferred_amount,
            'currentamount'=>$request->bank_balance_amount,
            'note' => $request->notes,
        ]);

        $data = Transfer_cash_to_the_next_day::find($request->transactionId);
        $data1 = [
            'id' => $data->id,
            'user' => $data->user->name,
            'branch' => $data->branch->name,
            'the_amount' => $data->amount,
            'currentamount' => $data->currentamount,
            'date' => $data->created_at->format('d/m/Y'),
        ];
        return $data1;
    }

    public function Add_blance_from_bank(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = cash_from__bank::create([
            'user_id' => Auth()->user()->id,
            'branchs_id' => $request->branchs_id,
            'the_amount' => $request->cashreceived,
            'payment_method' => $request->pay,
            'created_at' => $request->start_at,
            'notes' => $request->notes,
        ]);

        $pay = '-';
        if ($request->pay == 'Cash') {
            $pay = __('report.cash');
        } elseif ($request->pay == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }

        $dataResponse = [
            'id' => $data->id,
            'user' => $data->user->name,
            'branchs' => $data->branch->name,
            'the_amount' => $request->cashreceived,
            'payment_method' => $pay,
            'created_at' => $request->start_at,
        ];
        return $dataResponse;
    }

    public function updateAdd_blance_from_bank(Request $request)
    {
        cash_from__bank::find($request->transactionId)->update([
            'user_id' => Auth()->user()->id,
            'branchs_id' => $request->updatebranchs_id,
            'the_amount' => $request->cashreceivedupdate,
            'payment_method' => $request->payupdate,
        ]);

        $pay = '-';
        if ($request->payupdate == 'Cash') {
            $pay = __('report.cash');
        } elseif ($request->payupdate == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }

        $data1 = cash_from__bank::find($request->transactionId);

        $data = [
            'id' => $data1->id,
            'user' => $data1->user->name,
            'branchs' => $data1->branch->name,
            'the_amount' => $data1->the_amount,
            'payment_method' => $pay,
            'created_at' => $data1->created_at->format('d/m/Y'),
        ];
        return $data;
    }

    public function SearchconvertcashboxToBank(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        
        // 1. إنشاء حركة التحويل أولاً وحفظها بمتغير فريد لتجنب التداخل والتسمية الخاطئة للـ Object
        $convertLog = convertcashboxToBank::create([
            'from_user_id' => Auth()->user()->id,
            'amount' => $request->cashreceived,
            'branchs_id' => $request->branchs_id,
            'note' => $request->notes ?? "-",
            'created_at' => $request->start_at,
        ]);

        // 2. تحديث الحساب الرئيسي رقم 5
        $account5 = financial_accounts::find(5);
        if($account5) {
            $account5->update([
                'current_balance' => $account5->current_balance - $request->cashreceived
            ]); 
        }
        
        // 3. قيد الحساب رقم 5
        credittransactions::create([
            'attachments'=>'-',
            'orginal_type'=>$account5->orginal_type ?? 0,
            'user_id' => Auth()->user()->id,
            'customer_id' => 5,
            'recive_amount' => $request->cashreceived,
            'branchs_id' => Auth()->user()->branchs_id,
            'pay_method' => 'Cash',
            'note' => 'تحويل نقدي الي البنك Convert cash to bank',
            'currentblance'=>0,
            'Pay_Method_Name' => 'Cash',
            'date_export' => date("Y-m-d"),
            'created_at' => \Carbon\Carbon::now()->addHours(3), 
            'updated_at' => \Carbon\Carbon::now()->addHours(3),
            'orginal_id'=>$account5->orginal_id ?? 0,
            'debtor'=> 0, 
            'creditor'=>$request->cashreceived,
            'name'=> '-', 
            'tax'=> 0, 
            'vat'=> 0, 
            'type_decument'=>0,
            'sent_serf_count'=>0,
        ]);
     
        // 4. تحديث الحساب الفرعي المرتبط بالأب رقم 5 وحسب الفرع الحالي
        $subAccount5 = financial_accounts::where('parent_account_number', 5)->where('branchs_id', Auth()->user()->branchs_id)->first();
        if($subAccount5) {
            $subAccount5->update([
                'current_balance'=>$subAccount5->current_balance - $request->cashreceived,
                'debtor_current'=>$subAccount5->debtor_current + $request->cashreceived,
            ]);

            credittransactions::create([
                'attachments'=>'-',
                'orginal_type'=>$subAccount5->orginal_type ?? 0,
                'user_id' => Auth()->user()->id,
                'customer_id' => $subAccount5->id,
                'recive_amount' => $request->cashreceived,
                'branchs_id' => Auth()->user()->branchs_id,
                'pay_method' => 'Cash',
                'note' => 'تحويل نقدي الي البنك Convert cash to bank',
                'currentblance'=>0,
                'Pay_Method_Name' => 'Cash',
                'date_export' => date("Y-m-d"),
                'created_at' => \Carbon\Carbon::now()->addHours(3), 
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'orginal_id'=>$subAccount5->orginal_id ?? 0,
                'debtor'=> 0, 
                'creditor'=>$request->cashreceived,
                'name'=> '-', 
                'tax'=> 0, 
                'vat'=> 0, 
                'type_decument'=>0,
                'sent_serf_count'=>0,
            ]);
        }
        
        // 5. تحديث الحساب الرئيسي رقم 4
        $account4 = financial_accounts::find(4);
        if($account4) {
            $account4->update([
                'current_balance'=> $account4->current_balance + $request->cashreceived
            ]);

            credittransactions::create([
                'attachments'=>'-',
                'orginal_type'=>$account4->orginal_type ?? 0,
                'user_id' => Auth()->user()->id,
                'customer_id' => 4,
                'recive_amount' => $request->cashreceived,
                'branchs_id' => Auth()->user()->branchs_id,
                'pay_method' => 'Cash',
                'note' => 'تحويل نقدي الي البنك Convert cash to bank',
                'currentblance'=>0,
                'Pay_Method_Name' => 'Cash',
                'date_export' => date("Y-m-d"),
                'created_at' => \Carbon\Carbon::now()->addHours(3),  
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'orginal_id'=>$account4->orginal_id ?? 0,
                'debtor'=> $request->cashreceived, 
                'creditor'=>0,
                'name'=> '-', 
                'tax'=> 0, 
                'vat'=> 0, 
                'type_decument'=>0,
                'sent_serf_count'=>0,
            ]);
        }
        
        // 6. تحديث الحساب الفرعي المرتبط بالأب رقم 4 وحسب الفرع الحالي
        $subAccount4 = financial_accounts::where('parent_account_number', 4)->where('branchs_id', Auth()->user()->branchs_id)->first();
        if($subAccount4) {
            $subAccount4->update([
                'current_balance'=>$subAccount4->current_balance - $request->cashreceived,
                'debtor_current'=>$subAccount4->debtor_current + $request->cashreceived,
            ]); 

            credittransactions::create([
                'attachments'=>'-',
                'orginal_type'=>$subAccount4->orginal_type ?? 0,
                'user_id' => Auth()->user()->id,
                'customer_id' => $subAccount4->id,
                'recive_amount' => $request->cashreceived,
                'branchs_id' => Auth()->user()->branchs_id,
                'pay_method' => 'Cash',
                'note' => 'تحويل نقدي الي البنك Convert cash to bank',
                'currentblance'=>0,
                'Pay_Method_Name' => 'Cash',
                'date_export' => date("Y-m-d"),
                'created_at' => \Carbon\Carbon::now()->addHours(3), 
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'orginal_id'=>$subAccount4->orginal_id ?? 0,
                'debtor'=> $request->cashreceived, 
                'creditor'=>0,
                'name'=> '-', 
                'tax'=> 0, 
                'vat'=> 0, 
                'type_decument'=>0,
                'sent_serf_count'=>0,
            ]);
        }
        
        // تجهيز بيانات الـ Return المرجعة للاجاكس بشكل سليم بدون الاعتماد على كروت ممسوحة
        $returnData = [
            'user' => Auth()->user()->name,
            'amount' => $request->cashreceived,
            'branchs_id' => optional($convertLog->branch)->name ?? '-',
            'note' => $request->notes ?? "-",
            'created_at' => $request->start_at,
            'id' => $convertLog->id
        ];

        return $returnData;
    }

    public function printconvertcashboxToBank(Request $request)
    {
        if ($request == null || !$request->has('id')) {
            $data = [];
            session()->flash('nodataprint', '');
            return view('acountes.convertcashboxToBank', compact('data'));
        }
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = convertcashboxToBank::find($request->id);
        return view('acountes.printconvertcashboxToBank', compact('data'));
    }

    public function cashEcprnse()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $allcustomers = User::get();
        $data = [
            'transaction' => [],
            "allusers" =>  $allcustomers,
        ];
        return view('acountes.cash expense', compact('data'));
    }

    public function income()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [
            "transaction" =>  [],
        ];
        return view('acountes.Expensesowner', compact('data'));
    }

    public function reciept_decoument()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());   
        return view('acountes.reciept_decoment');
    }

    public function opining_balnce_ajax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = credittransactions::where('branchs_id',Auth()->user()->branchs_id)
            ->whereDate('created_at', '<=',date('Y-m-d'))
            ->whereDate('created_at', '>=',date('Y'). '-1-1' )
            ->where('parent_Opening_entry', 0)
            ->where('save', 1)
            ->where('Opening_entry','>=', 1)
            ->orderby('id', 'desc')->paginate(3);

        return view('opining_balnce_ajax', compact('data'));
    }

    public function get_all_send_kabd_jax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = credittransactions::where('branchs_id',Auth()->user()->branchs_id)
            ->where('note', 'LIKE', '%' . 'سند قبض' . '%')
            ->where('decument_id', 0)
            ->orderby('id', 'desc')->where('save', 1)->paginate(3);

        return view('sant_abd_ajax', compact('data'));
    }
        
    public function get_all_kid_yaomy_jax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = credittransactions::where('branchs_id',Auth()->user()->branchs_id)
            ->where('dely_record','!=',0)
            ->where('note', 'LIKE', '%' . 'قيد يومي رقم' . '%')
            ->whereDate('created_at', '<=',date('Y-m-d'))
            ->whereDate('created_at', '>=',date('Y'). '-1-1' )
            ->where('decument_id', 0 )
            ->where('save',1)
            ->orderby('id', 'desc')->paginate(4);
       
        return view('ajax_dely_record', compact('data'));
    }
    
    public function search_by_decoumentNo_kid_yomy($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = credittransactions::where('branchs_id',Auth()->user()->branchs_id)
            ->where('dely_record',$id)
            ->where('note', 'LIKE', '%' . 'قيد يومي رقم' . '%' )
            ->where('save',1)
            ->orderby('id', 'desc')->paginate(4);
       
        return view('ajax_dely_record', compact('data'));
    }

    public function get_all_send_serf_jax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = credittransactions::where('type_decument', 2)
            ->whereDate('created_at', '<=',date('Y-m-d'))
            ->whereDate('created_at', '>=',date('Y'). '-1-1' )
            ->where('decument_id', 0)->where('cost_center','!=', 0)

            ->orderby('id', 'desc')->paginate(3);
   
        return view('sant_serf_ajax', compact('data'));
    }

    public function createTransfertomainbranch($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $transactiontosupllier = transactiontosuplliers::find($id);
        $supllierdata = supllier::find($transactiontosupllier->suplier_id);

        $data = [
            "transaction" => [
                'sent_serf_count' => $transactiontosupllier->sent_serf_count,
                'name' => $supllierdata->name,
                'Limit_credit' => $supllierdata->Limit_credit,
                'Balance' => $supllierdata->In_debt,
                'camp_name' => $supllierdata->comp_name,
                'camp_phone' => $supllierdata->phone,
                'created_at' => $transactiontosupllier->created_at,
                'date' => $transactiontosupllier->created_at,
                'date_export' => $transactiontosupllier->date_export,
                'method_pay' => $transactiontosupllier->Pay_Method_Name,
                'paid_amount' => $transactiontosupllier->paidـamount
            ],
        ];
        return view('acountes.print_voucher_to_supplier', compact('data'));
    }

    public function print_voucher(Request $request)
    {
        $transactiontocustomer = credittransactions::find($request->id);
        $credittransactions = credittransactions::where('sent_serf_count', $transactiontocustomer->sent_serf_count)
            ->where('type_decument', 2) 
            ->get();

        $transaction = [];
        $total_spent = 0; 

        foreach ($credittransactions as $item) {
            if ($item->debtor > 0) { 
                $total_spent += $item->debtor;
                $transaction[] = [
                    "sent_serf_count" => $transactiontocustomer->sent_serf_count,
                    'name'            => optional($item->financial_accounts_data)->name ?? 'حساب غير معروف',
                    'created_at'      => $item->created_at,
                    'date'            => $item->created_at,
                    'date_export'     => $item->date_export, 
                    'method_pay'      => $item->Pay_Method_Name,
                    'paid_amount'     => $item->debtor, 
                    'note'            => $item->note
                ];
            }
        }

        $data = [
            "transaction"  => $transaction,
            "total_amount" => $total_spent, 
        ];
        return view('acountes.print_voucher_to_supplier', compact('data'));
    }

    public function print_expansedecoument(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $expense = expenses::find($request->id);

        if ($expense->Pay_Method_Name == 'Cash') {
            $pay = __('report.cash');
        } elseif ($expense->Pay_Method_Name == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }

        $data = [
            'id' =>  $expense->id,
            'user' => Auth()->user()->name,
            'Pay_Method_Name' => $pay,
            'Theـamountـpaid' => $expense->Theـamountـpaid,
            'Reasonforspendingmoney' => LaravelLocalization::getCurrentLocale()=='ar' ? $expense->Expenses_reasons->expenses_reason : ($expense->Expenses_reasons->expenses_reason_en=='-' ? $expense->Expenses_reasons->expenses_reason : $expense->Expenses_reasons->expenses_reason_en),
            'notes' => $expense->notes,
        ];
        return view('acountes.print_cashexpenswe', compact('data'));
    }

public function print_reciept_ducoument(Request $request)
    {
        $credittransactions = credittransactions::where('sent_abd_count', $request->id)->get();
        $transaction = [];
        $total = 0;

        foreach ($credittransactions as $item) {
            if ($item->creditor > 0) { 
                $total += $item->creditor; 
                $transaction[] = [
                    "sent_abd_count" => $request->id,
                    'name'           => optional($item->financial_accounts_data)->name ?? 'حساب غير معروف',
                    'created_at'     => $item->created_at,
                    'date'           => $item->created_at,
                    'date_export'    => $item->date_export,
                    'method_pay'     => $item->Pay_Method_Name,
                    'paid_amount'    => $item->creditor, 
                    'note'           => $item->note,
                    'id'             => $item->sent_abd_count
                ];
            }
        }

        $formattedTotal = number_format($total, 2, '.', '');
        list($whole, $decimal) = explode('.', $formattedTotal);

        // التفقيط بالعربي
        $arabic_riyales = NumToArabic::number2Word((int)$whole) . ' ريال';
        $arabic_halala = ($decimal > 0) ? NumToArabic::number2Word((int)$decimal) . ' هللة' : 'لا غير';

    // التفقيط بالإنجليزي بدون الاعتماد على intl
        $english_riyales = ucfirst(self::convertNumberToWordsEnglish((int)$whole)) . ' Riyals';
        if ((int)$decimal > 0) {
            $english_halala = ucfirst(self::convertNumberToWordsEnglish((int)$decimal)) . ' Halalas';
        } else {
            $english_halala = 'Only';
        }

        
        $data = [
            "transaction"       => $transaction,
            "total_val"         => $total, 
            'totatextlriyales'  => $arabic_riyales,
            'totatextlrihalala' => $arabic_halala,
            'totatext_en_riyales' => $english_riyales,
            'totatext_en_halala'  => $english_halala,
        ];

        return view('acountes.print_reciept_decoment_to_customer', compact('data'));
    }


    
    public function generate_pdf(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $transactiontocustomer = credittransactions::find($request->id);

        $data = [
            "transaction" => [
                "sent_abd_count" => $transactiontocustomer->sent_abd_count,
                'name' => $transactiontocustomer->financial_accounts_data->name,
                'Balance' => 0,
                'created_at' => $transactiontocustomer->created_at,
                'date' => $transactiontocustomer->created_at,
                'date_export' => $transactiontocustomer->date_export, 
                'method_pay' => $transactiontocustomer->Pay_Method_Name,
                'paid_amount' => $transactiontocustomer->recive_amount,
                'id' => $transactiontocustomer->sent_abd_count
            ],
        ];
        
        $tran = ['data' => $data];
        $dateTime = now();
        $fileName = $dateTime->format('Y-m-d H:i:s');
        $html = view('pdf.pdf_reciept_ducoument', $tran)->toArabicHTML();
        
        $pdf = PDF::loadHTML($html)->output();
        
        $headers = array(
            "Content-type" => "application/pdf",
        );
        return response()->streamDownload(
            fn () => print($pdf), 
            "Invoice_No_".$transactiontocustomer->id."_". $fileName.".pdf",
            $headers
        );
    }
}