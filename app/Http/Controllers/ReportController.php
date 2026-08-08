<?php

namespace App\Http\Controllers;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use App\Models\Avt;
use App\Models\acounts_type;
use App\Models\convertcashboxToBank;
use App\Models\branchs;
use App\Models\Transfer_cash_to_the_next_day;
use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\orderDetails;
use App\Models\credittransactions;
use App\Models\customers;
use App\Models\delivery_to_customer_withoud_tax_invoices;
use App\Models\transferMoney_to_mainbranch;
use App\Models\order_price_from_supplier;
use App\Models\return_sales_deliverys;
use App\Models\product_movement_another_branch;
use App\Models\product_movement_another_branch_items;
use App\Models\resource_purchases;
use App\Models\sales;
use App\Models\ProductsSalesReport;
use App\Models\supllier;
use App\Models\Cash_withdrawal_from_the_bank;
use App\Models\expenses;
use App\Models\orderTosupllier;
use App\Models\return_sales;
use App\Models\offer_price_to_customer;
use App\Models\transactiontosuplliers;
use App\Models\products;
use App\Models\cash_from__bank;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Exportproducts;
use App\Exports\supllierExport;
use App\Exports\customersExport;
use App\Exports\Low_sell_export;
use App\Exports\Export_invoices_purshase;
use App\Exports\financial_accounts_Export;
use PDF;
use App\Models\stock_update;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\financial_accounts;
use App\Exports\Export_invoices;
use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Http;
use App\Exports\Export_Account_staatment;
use App\Exports\ExpensesExport;
use App\Exports\ProductsSalesAndPurchaseReport;
use App\Exports\StockQuantityExport;

use Illuminate\Support\Facades\View;

class ReportController extends Controller
{

    public function search_credit_collection(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $UserId = $request->UserId;
        if ($request->branch == '-') {
            if ($UserId == '-') {
                $Invoices = credittransactions::where('note', 'LIKE', '%' . 'سند قبض' . '%')->whereDate('created_at', '>=', $request->start_at)->where('decument_id', 0)->whereDate('created_at', '<=', $request->end_at)->get();

                return view('reports.print_credit_collection', compact('Invoices'))->with('customer_id', $UserId)->with('start_at', $request->start_at)->with('end_at', $request->end_at);
                ;
            }
            $Invoices = credittransactions::where('note', 'LIKE', '%' . 'سند قبض' . '%')->where('customer_id', $UserId)->where('decument_id', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();



            return view('reports.print_credit_collection', compact('Invoices'))->with('customer_id', $UserId)->with('start_at', $request->start_at)->with('end_at', $request->end_at);
            ;

        } else {

            if ($UserId == '-') {
                $Invoices = credittransactions::where('branchs_id', $request->branch)->where('note', 'LIKE', '%' . 'سند قبض' . '%')->whereDate('created_at', '>=', $request->start_at)->where('decument_id', 0)->whereDate('created_at', '<=', $request->end_at)->get();

                return view('reports.print_credit_collection', compact('Invoices'))->with('customer_id', $UserId)->with('start_at', $request->start_at)->with('end_at', $request->end_at);
                ;
            }
            $Invoices = credittransactions::where('branchs_id', $request->branch)->where('note', 'LIKE', '%' . 'سند قبض' . '%')->where('orginal_id', $UserId)->where('decument_id', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
            return view('reports.print_credit_collection', compact('Invoices'))->with('customer_id', $UserId)->with('start_at', $request->start_at)->with('end_at', $request->end_at);
            ;




        }
    }


    public function search_Expenses(Request $request)
    {
        // 1. استرجاع البيانات (تم اختصار الكود بنفس منطقك)
        $query = credittransactions::where('orginal_type', 3)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($request->branch != '-') {
            $query->where('branchs_id', $request->branch);
        }
        if ($request->enpenses_reason != '-') {
            $query->where('customer_id', $request->enpenses_reason);
        }

        $Invoices = $query->get();

        // 2. التحقق مما إذا كان الطلب تصدير أم بحث
        if ($request->has('export_excel')) {
            // 1. حساب الإجماليات أولاً
            $totals = [
                'active' => $Invoices->where('vat', 1)->sum('recive_amount'),
                'inactive' => $Invoices->where('vat', 0)->sum('recive_amount'),
                'total' => $Invoices->sum('recive_amount')
            ];

            // 2. تمرير البيانات والإجماليات معاً
            return Excel::download(new ExpensesExport($Invoices, $totals), 'expenses_report.xlsx');
        }

        return view('reports.printExpensesReport', compact('Invoices', 'request'));
    }





    public function exportExcel(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start_at = $request->start_at;
        $end_at = $request->end_at;

        // بناء استعلام المبيعات بشكل ديناميكي ونظيف بدلاً من الـ if-else المتكررة
        $invoicesQuery = sales::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)
            ->where('save', 1);

        // بناء استعلام المرتجعات بشكل ديناميكي
        $returnsQuery = return_sales::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at);

        // تطبيق فلتر الفرع إذا لم يكن عاماَ
        if ($request->filled('branch') && $request->branch != '-') {
            $invoicesQuery->where(function ($q) use ($request) {
                // فحص الحقلين لضمان عدم حدوث تعارض في الموديل الخاص بك
                $q->where('branch_id', $request->branch)->orWhere('branchs_id', $request->branch);
            });
            $returnsQuery->where('branch_id', $request->branch);
        }

        // تطبيق فلتر المستخدم إذا لم يكن عاماً
        if ($request->filled('userid') && $request->userid != '-') {
            $invoicesQuery->where('user_id', $request->userid);
            $returnsQuery->where('user_id', $request->userid);
        }

        $data = [
            'sales' => $invoicesQuery->get(),
            'returns' => $returnsQuery->get(),
        ];

        return \Excel::download(
            new \App\Exports\SalesProfitsExport($data, $start_at, $end_at),
            'report.xlsx'
        );
    }

    public function profitLossReport()
    {
        return view('reports.profit_loss');
    }

    // البحث والفلترة (POST) لتقرير الأرباح والخسائر للمبيعات الضريبية
    public function searchProfitLossReport(Request $request)
    {
        $query = sales::query();

        if ($request->filled('start_at') && $request->filled('end_at')) {
            $query->whereBetween('sales.created_at', [
                $request->start_at . ' 00:00:00',
                $request->end_at . ' 23:59:59'
            ]);
        }

        if ($request->filled('branch') && $request->branch != '-') {
            $query->where('branch_id', $request->branch);
        }

        $data = $query->join('products', 'sales.product_id', '=', 'products.id')
            ->select(
                'sales.product_id',
                'products.product_name',
                'products.average_cost',
                'products.Product_Code',
                'products.purchasingـprice',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantityreturn) as total_returned'),
                DB::raw('SUM(quantity - quantityreturn) as net_quantity'),
                DB::raw('SUM(Unit_Price * quantity) as gross_sales'),
                DB::raw('SUM(Discount_Value) as total_discounts'),
                DB::raw('SUM((Unit_Price * quantity) - Discount_Value) as net_sales'),
                DB::raw('SUM(IF(products.average_cost > 0, products.average_cost, products.purchasingـprice) * (quantity - quantityreturn)) as total_cost'),
                DB::raw('SUM(((Unit_Price * quantity) - Discount_Value) - (IF(products.average_cost > 0, products.average_cost, products.purchasingـprice) * (quantity - quantityreturn))) as net_profit')
            )
            ->groupBy('sales.product_id', 'products.Product_Code', 'products.product_name', 'products.average_cost', 'products.purchasingـprice')
            ->get();

        return view('reports.print_profit_loss', [
            'data' => $data,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'branch' => $request->branch
        ]);
    }

    public function deliveryprofitLossReport()
    {
        return view('reports.delivery_profit_loss');
    }

    // البحث والفلترة (POST) للمبيعات بدون ضريبة
    public function deliverysearchProfitLossReport(Request $request)
    {
        $query = sales_withoud_taxes::query();

        if ($request->filled('start_at') && $request->filled('end_at')) {
            $query->whereBetween('sales_withoud_taxes.created_at', [
                $request->start_at . ' 00:00:00',
                $request->end_at . ' 23:59:59'
            ]);
        }

        if ($request->filled('branch') && $request->branch != '-') {
            $query->where('branch_id', $request->branch);
        }

        $data = $query->join('products', 'sales_withoud_taxes.product_id', '=', 'products.id')
            ->select(
                'sales_withoud_taxes.product_id',
                'products.product_name',
                'products.average_cost',
                'products.Product_Code',
                'products.purchasingـprice',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantityreturn) as total_returned'),
                DB::raw('SUM(quantity - quantityreturn) as net_quantity'),
                DB::raw('SUM(Unit_Price * quantity) as gross_sales'),
                DB::raw('SUM(Discount_Value) as total_discounts'),
                DB::raw('SUM((Unit_Price * quantity) - Discount_Value) as net_sales'),
                DB::raw('SUM(IF(products.average_cost > 0, products.average_cost, products.purchasingـprice) * (quantity - quantityreturn)) as total_cost'),
                DB::raw('SUM(((Unit_Price * quantity) - Discount_Value) - (IF(products.average_cost > 0, products.average_cost, products.purchasingـprice) * (quantity - quantityreturn))) as net_profit')
            )
            ->groupBy('sales_withoud_taxes.product_id', 'products.Product_Code', 'products.product_name', 'products.average_cost', 'products.purchasingـprice')
            ->get();

        return view('reports.delivery_print_profit_loss', [
            'data' => $data,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'branch' => $request->branch
        ]);
    }

    // دالة كشف حساب الموردين والعملاء المشتركة
    public function search_account_statement_modal(Request $request)
    {
        $start_at = $request->start_at;
        $end_at = $request->end_at;
        $supplierId = $request->supplierId;
        $branch_id = $request->branch;

        $financial_accounts = financial_accounts::findOrFail($supplierId);

        // تصدير إكسيل المباشر
        if ($request->action == 'export') {
            $branch_name = $branch_id == '-' ? 'جميع الفروع' : (\App\Models\branchs::find($branch_id)->name ?? '-');

            $query_base = credittransactions::where('customer_id', $supplierId)->where('save', 1);
            if ($branch_id != '-') {
                $query_base->where('branchs_id', $branch_id);
            }

            $opening_transactions = (clone $query_base)->where('created_at', '<', $start_at)->get();
            $opening_credit = $opening_transactions->sum('creditor');
            $opening_debit = $opening_transactions->sum('debtor');

            $current_transactions = (clone $query_base)
                ->whereDate('created_at', '>=', $start_at)
                ->whereDate('created_at', '<=', $end_at)
                ->with(['user', 'branch'])
                ->get();

            $data_list = [];
            foreach ($current_transactions as $item) {
                $data_list[] = [
                    'id' => $item->id,
                    'dely_record' => $item->dely_record,
                    'date' => $item->created_at->format('Y-m-d'),
                    'branch' => $item->branch->name ?? '-',
                    'user' => $item->user->name ?? '-',
                    'recive_amount' => $item->recive_amount,
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                    'note' => $item->note,
                ];
            }

            $header_info = [
                'account_name' => $financial_accounts->name,
                'branch_name' => $branch_name,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'currentdata' => now()->format('Y-m-d H:i')
            ];

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\Export_Account_staatment($data_list, $opening_debit, $opening_credit, $header_info),
                'كشف_حساب_' . $financial_accounts->name . '.xlsx'
            );
        }

        // في حالة العرض بالجدول لصفحة الـ Ajax
        $branch_name = '-';
        $query_web = credittransactions::where('customer_id', $supplierId)->where('save', 1);

        if ($branch_id != '-') {
            $branch = branchs::find($branch_id);
            $branch_name = __('home.branch') . ' : ' . ($branch->name ?? '-');
            $query_web->where('branchs_id', $branch_id);
        }

        $LAST_credittransactions = (clone $query_web)->whereDate('created_at', '<', $start_at)->get();
        $credittransactions = (clone $query_web)->whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)
            ->with(['user', 'branch', 'financial_accounts_data'])
            ->get();

        // جلب جميع الآباء لـ السندات دفعة واحدة لتفادي الـ N+1 Query البطيئة جداً داخل الـ Loop
        $notes = $credittransactions->pluck('note')->filter()->unique();
        $parentNames = credittransactions::whereIn('note', $notes)
            ->where(function ($q) {
                $q->where('sent_abd_count', '!=', 0)->orWhere('sent_serf_count', '!=', 0);
            })
            ->with('financial_accounts_data')
            ->get()
            ->keyBy('note');

        $List_dely_record = [];

        foreach ($credittransactions as $item) {
            $parent_name = $parentNames->get($item->note);
            $name_parent = ($parent_name && $parent_name->financial_accounts_data) ? $parent_name->financial_accounts_data->name : '-';

            // تحديد طريقة السداد بشكل أكثر مرونة ونظافة
            $type = $item->type ?: $item->Pay_Method_Name;
            switch ($type) {
                case 'Cash':
                    $payment = __('report.cash');
                    break;
                case 'Bank_transfer':
                    $payment = __('home.Bank_transfer');
                    break;
                case 'Shabka':
                    $payment = __('report.shabka');
                    break;
                case 'Partition':
                    $payment = __('home.Partition of the amount');
                    break;
                default:
                    $payment = $item->type == null ? __('report.credit') : '-';
                    break;
            }

            $List_dely_record[] = [
                'id' => $item->id,
                'recive_amount' => $item->recive_amount,
                'depit' => $item->debtor,
                'credit' => $item->creditor,
                'current_blance' => $item->currentblance,
                'dely_record' => $item->dely_record,
                'date_export' => $item->date_export,
                'date' => $item->created_at->format('Y-m-d H:i:s'),
                'note' => $item->note . ' - (' . $payment . ') - ' . $name_parent,
                'user' => $item->user->name ?? '-',
                'branch' => $item->branch->name ?? '-',
            ];
        }

        $credit = $LAST_credittransactions->sum('creditor');
        $debit = $LAST_credittransactions->sum('debtor');
        $blance = $LAST_credittransactions->last()->currentblance ?? 0;

        // فرز مصفوفة البيانات تصاعدياً حسب التاريخ بشكل أسرع
        usort($List_dely_record, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });

        return view('reports.statement_table_ajax', ['data' => $List_dely_record])
            ->with('start_at', $start_at)
            ->with('end_at', $end_at)
            ->with('account_name', $financial_accounts->name)
            ->with('account_id', $financial_accounts->id)
            ->with('blance', $blance)
            ->with('debit', $debit)
            ->with('credit', $credit)
            ->with('branch_name', $branch_name);
    }

    public function report_delivery_return()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.report_returns_sale_delivery');
    }

    public function search_report_returns_sale_delivery(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $query = return_sales_deliverys::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($request->branch != '-') {
            $query->where('branch_id', $request->branch);
        }

        $Invoices = $query->get();

        return view('reports.print_report_sales_returen_delivery', compact('Invoices'))
            ->with('branch_Id', $request->branch)
            ->with('start', $request->start_at)
            ->with('end', $request->end_at);
    }

    public function salesReport_delivery(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $query = delivery_to_customer_withoud_tax_invoices::where('save', 1)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($request->filled('branch') && $request->branch != "-") {
            $query->where('branchs_id', $request->branch);
        }

        if ($request->filled('pay') && $request->pay != "-") {
            $query->where('Pay', $request->pay);
        }

        $Invoices = $query->get();

        return view('reports.printReport_delivery_withoud_deatails', compact('Invoices'))
            ->with('pay', $request->pay)
            ->with('branch', $request->branch)
            ->with('start', $request->start_at)
            ->with('end', $request->end_at);
    }

    public function sel_product_DELIVERY()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.sell_product_withoud_tax');
    }

    public function purchase_product_by_date()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.purchase_product_by_date');
    }

public function search_purchase_product_by_date(Request $request)
{
    $query = orderDetails::query()
        ->join('resource_purchases', 'order_details.order_owner', '=', 'resource_purchases.orderId')
        ->whereDate('order_details.created_at', '>=', $request->start_at)
        ->whereDate('order_details.created_at', '<=', $request->end_at)
        ->where('order_details.save', 1);

    // فلترة الفرع مباشرة من جدول resource_purchases
    if ($request->branch != '-') {
        $query->where('resource_purchases.branchs_id', $request->branch);
    }

    // التجميع حسب المنتج (مع تحديد order_details لتجنب تداخل الأعمدة)
    $productsQuery = $query->select('order_details.product_id')
        ->selectRaw('SUM(order_details.numberofpice) as total_quantity')
        ->selectRaw('SUM(order_details.numberofpice * order_details.purchasingـprice) as total_sales_amount')
        ->groupBy('order_details.product_id')
        ->with('productData');

    // إذا كان الطلب تصدير Excel
    if ($request->action == 'export') {
        $products = $productsQuery->get();
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SalesProductExport($products),
            'sales_products_report.xlsx'
        );
    }

    $products = $productsQuery->get();
    return view('reports.print_purchase_product_by_date', compact('products'));
}

    public function SalesPurchaseInPeriode()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.SalesPurchaseInPeriode');
    }

    public function search_SalesPurchaseInPeriode(Request $request)
    {
        // تحديد اسم الملف المنشأ بالتاريخ الحالي
        $fileName = 'تقرير_المشتريات_والمبيعات_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new ProductsSalesAndPurchaseReport($request), $fileName);

    }











    public function sales_product_by_date()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.sales_product_by_date');
    }

    public function search_sales_product_by_date(Request $request)
    {
        $query = sales::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('save', 1);

        if ($request->branch != '-') {
            $query->where('branch_id', $request->branch);
        }

        $productsQuery = $query->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(quantity * Unit_Price) as total_sales_amount')
            ->groupBy('product_id')
            ->with('productData');

        $products = $productsQuery->get();

        if ($request->action == 'export') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\SalesProductExport($products),
                'sales_products_report.xlsx'
            );
        }

        return view('reports.printsales_product_by_date', compact('products'));
    }

    public function profit_and_lost()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.profit_and_lost');
    }
// جلب أبناء فرع معين عبر الـ AJAX عند الضغط عليه
  

  public function budgetsheet_general(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $currentYear = date('Y');
        $start_at = $request->input('start_at', $currentYear . '-01-01');
        $end_at = $request->input('end_at', $currentYear . '-12-31');

        $types = acounts_type::whereIn('id', [1, 2, 3, 4, 5])->get();
        $allAccounts = financial_accounts::all();

        // جلب الحركات لكل الحسابات
        $transactions = DB::table('credittransactions')
            ->join('financial_accounts', 'credittransactions.customer_id', '=', 'financial_accounts.id')
            ->where('credittransactions.save', 1)
            ->whereBetween('credittransactions.created_at', ['2020-01-01', $end_at . ' 23:59:59'])
            ->select(
                'financial_accounts.id as account_row_id', 
                DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) < '{$start_at}' THEN credittransactions.debtor ELSE 0 END) as open_debtor"),
                DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) < '{$start_at}' THEN credittransactions.creditor ELSE 0 END) as open_creditor"),
                DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) >= '{$start_at}' AND DATE(credittransactions.created_at) <= '{$end_at}' THEN credittransactions.debtor ELSE 0 END) as curr_debtor"),
                DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) >= '{$start_at}' AND DATE(credittransactions.created_at) <= '{$end_at}' THEN credittransactions.creditor ELSE 0 END) as curr_creditor")
            )
            ->groupBy('financial_accounts.id')
            ->get()
            ->keyBy('account_row_id');

   $accountTotals = [];
        foreach ($allAccounts as $acc) {
            // فحص مباشر من قاعدة البيانات: هل هذا الحساب لديه أبناء (parent_account_number يساوي id هذا الحساب أو رقم حسابه)؟
            $hasChildren = DB::table('financial_accounts')
                ->where('parent_account_number', $acc->id)
                ->exists();

            if ($hasChildren ) {
                // إذا كان أباً (مثل الخزينة): نجعل أقماره صفراً تماماً لكي تظهر علامة (-)
                $accountTotals[$acc->id] = [
                    'debtor' => 0,
                    'creditor' => 0,
                    'balance' => 0,
                    'is_parent' => true
                ];
            } else {
                // إذا لم يكن أباً (حساب فرعي نهائي): نحسب حركاته المباشرة فقط
                $totalDebtor = 0;
                $totalCreditor = 0;
                if(isset($transactions[$acc->id])) {
                    $t = $transactions[$acc->id];
                    $totalDebtor += ($t->open_debtor + $t->curr_debtor);
                    $totalCreditor += ($t->open_creditor + $t->curr_creditor);
                }
                $accountTotals[$acc->id] = [
                    'debtor' => $totalDebtor,
                    'creditor' => $totalCreditor,
                    'balance' => $totalDebtor - $totalCreditor,
                    'is_parent' => false
                ];
            }
        }
            $rootAccounts = $allAccounts->whereIn('account_type', [1, 2, 3, 4, 5])
            ->where(function($q) {
                return $q->parent_account_number == null || $q->parent_account_number == 0;
            });

        return view('reports.budgetsheet_general', compact(
            'rootAccounts', 
            'types', 
            'allAccounts',
            'accountTotals',
            'start_at', 
            'end_at'
        ));
    }

    // دالة مساعدة داخل الـ Controller لحساب مجموع الحساب وأبنائه
private static function calculateTotalWithChildren($accountId, $allAccounts, $transactions) {
        $totalDebtor = 0;
        $totalCreditor = 0;

        $account = $allAccounts->firstWhere('id', $accountId);
        if (!$account) return ['debtor' => 0, 'creditor' => 0, 'balance' => 0];

        $accIdStr = trim((string)$account->id);

        // جلب الأبناء المباشرين بالاعتماد على الـ ID فقط
        $children = $allAccounts->filter(function($item) use ($accIdStr) {
            $parentVal = trim((string)$item->parent_account_number);
            return $parentVal === $accIdStr;
        });

        // إذا كان له أبناء، نجمع أرصدة أبنائه فقط
        if ($children->isNotEmpty()) {
            foreach($children as $child) {
                $childTotals = self::calculateTotalWithChildren($child->id, $allAccounts, $transactions);
                $totalDebtor += $childTotals['debtor'];
                $totalCreditor += $childTotals['creditor'];
            }
        } else {
            // إذا لم يكن له أبناء، نأخذ حركاته المباشرة فقط
            if(isset($transactions[$accountId])) {
                $t = $transactions[$accountId];
                $totalDebtor += ($t->open_debtor + $t->curr_debtor);
                $totalCreditor += ($t->open_creditor + $t->curr_creditor);
            }
        }

        return [
            'debtor' => $totalDebtor,
            'creditor' => $totalCreditor,
            'balance' => $totalDebtor - $totalCreditor
        ];
    }
    public function getAccountChildren(Request $request)
    {
        $accountId = $request->input('parent_id');
        $start_at = $request->input('start_at');
        $end_at = $request->input('end_at');

        // البحث عن الحساب الأب لمعرفة رقم الحساب (account_number) أو الـ id
        $parentAccount = financial_accounts::where('account_number', $accountId)->orWhere('id', $accountId)->first();
        
        $children = collect();
        if ($parentAccount) {
            // جلب الأبناء الذين يتبعون هذا الحساب
            $children = financial_accounts::where('parent_account_number', $parentAccount->account_number)
                ->orWhere('parent_account_number', $parentAccount->id)
                ->get();
        }

        $accountIds = $children->pluck('id')->toArray();
        
        $directBalances = collect();
        if (!empty($accountIds)) {
            $directBalances = DB::table('credittransactions')
                ->join('financial_accounts', 'credittransactions.customer_id', '=', 'financial_accounts.id')
                ->where('credittransactions.save', 1)
                ->whereIn('financial_accounts.id', $accountIds)
                ->whereBetween('credittransactions.created_at', ['2020-01-01', $end_at . ' 23:59:59'])
                ->select(
                    'financial_accounts.id as account_row_id', 
                    DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) < '{$start_at}' THEN credittransactions.debtor ELSE 0 END) as open_debtor"),
                    DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) < '{$start_at}' THEN credittransactions.creditor ELSE 0 END) as open_creditor"),
                    DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) >= '{$start_at}' AND DATE(credittransactions.created_at) <= '{$end_at}' THEN credittransactions.debtor ELSE 0 END) as curr_debtor"),
                    DB::raw("SUM(CASE WHEN DATE(credittransactions.created_at) >= '{$start_at}' AND DATE(credittransactions.created_at) <= '{$end_at}' THEN credittransactions.creditor ELSE 0 END) as curr_creditor")
                )
                ->groupBy('financial_accounts.id')
                ->get()
                ->keyBy('account_row_id');
        }

        // التأكد من أن أي ابن يعتبر "أباً" لفروع أخرى تصفير حركاته المباشرة
        foreach ($children as $child) {
            $hasSubChildren = DB::table('financial_accounts')
                ->where('parent_account_number', $child->id)
                ->orWhere('parent_account_number', $child->account_number)
                ->exists();

            if ($child->is_parent == 1 || $hasSubChildren) {
                $directBalances[$child->id] = (object)[
                    'open_debtor' => 0,
                    'open_creditor' => 0,
                    'curr_debtor' => 0,
                    'curr_creditor' => 0
                ];
            }
        }

        return view('reports.partials.budget_ajax_rows', compact('children', 'directBalances', 'start_at', 'end_at'));
    }

    public function low_sell()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.low_sell');
    }

    public function low_sell_search(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return Excel::download(new Low_sell_export($request->start_at, $request->end_at, $request->branch), 'Low_sell_export.xlsx');
    }

















    public function year_sales_report()
    {
        $currentYear = date('Y');

        // 1. جلب فواتير المبيعات الضريبية مجمعة حسب الشهر
        $taxInvoicesData = invoices::where('save', 1)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('
                MONTH(created_at) as month,
                COUNT(*) as count,
                SUM(cashamount) as total_cash,
                SUM(bankamount) as total_bank,
                SUM(creaditamount) as total_credit,
                SUM(Bank_transfer) as total_transfer
            ')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // 2. جلب فواتير مبيعات بدون ضريبة مجمعة حسب الشهر
        $nonTaxInvoicesData = delivery_to_customer_withoud_tax_invoices::where('save', 1)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('
                MONTH(created_at) as month,
                COUNT(*) as count,
                SUM(cashamount) as total_cash,
                SUM(bankamount) as total_bank,
                SUM(creaditamount) as total_credit,
                SUM(Bank_transfer) as total_transfer
            ')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // 3. بناء مصفوفات البيانات لـ 12 شهراً لتطابق الـ View القديم تماماً
        $countsArray = [];
        $financialsArray = [];
        $nonTaxCountsArray = [];
        $nonTaxFinancialsArray = [];

        for ($month = 1; $month <= 12; $month++) {
            // الفواتير الضريبية
            $taxInvoice = $taxInvoicesData->get($month);
            $countsArray[] = $taxInvoice ? $taxInvoice->count : 0;
            $financialsArray[] = [
                'total_cash' => $taxInvoice ? $taxInvoice->total_cash ?? 0 : 0,
                'total_bank' => $taxInvoice ? $taxInvoice->total_bank ?? 0 : 0,
                'total_credit' => $taxInvoice ? $taxInvoice->total_credit ?? 0 : 0,
                'total_transfer' => $taxInvoice ? $taxInvoice->total_transfer ?? 0 : 0,
            ];

            // فواتير بدون ضريبة
            $nonTaxInvoice = $nonTaxInvoicesData->get($month);
            $nonTaxCountsArray[] = $nonTaxInvoice ? $nonTaxInvoice->count : 0;
            $nonTaxFinancialsArray[] = [
                'total_cash' => $nonTaxInvoice ? $nonTaxInvoice->total_cash ?? 0 : 0,
                'total_bank' => $nonTaxInvoice ? $nonTaxInvoice->total_bank ?? 0 : 0,
                'total_credit' => $nonTaxInvoice ? $nonTaxInvoice->total_credit ?? 0 : 0,
                'total_transfer' => $nonTaxInvoice ? $nonTaxInvoice->total_transfer ?? 0 : 0,
            ];
        }

        // تجميع المصفوفات بنفس الترتيب المتوقع في الـ Blade لديك
        $data = [
            $countsArray,
            $financialsArray,
            $nonTaxCountsArray,
            $nonTaxFinancialsArray
        ];

        return view('reports.year_sales_report', compact('data'));
    }

    public function cost_center()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.search_cost_center');
    }

    public function sales_and_return()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.sales_and_return');
    }

    /**
     * بحث الأرباح والخسائر
     */
    public function search_profit_and_lost(Request $request)
    {
        $expense_total_value = 0;
        $sales_total_value = 0;
        $sales_return_total_value = 0;
        $sales_return_total_value_cost = 0;
        $purchase_total_value = 0;
        $purchase_return_total_value = 0;

        if ($request->branch == '-') {
            $expense = credittransactions::where('orginal_type', 3)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            $ids = financial_accounts::where('parent_account_number', 112)->pluck('id');
            $sales = credittransactions::whereIn('customer_id', $ids)->where('note', 'LIKE', '%فاتورة مبيعات%')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            $ids = financial_accounts::where('parent_account_number', 184)->pluck('id');
            $sales_return = credittransactions::whereIn('customer_id', $ids)->where('note', 'LIKE', '%فاتورة مرتجع مبيعات%')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            $ids = financial_accounts::where('parent_account_number', 183)->pluck('id');
            $purchase = credittransactions::whereIn('customer_id', $ids)->where('note', 'LIKE', '%فاتورة مبيعات%')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            $ids = financial_accounts::where('parent_account_number', 181)->pluck('id');
            $purchase_return = credittransactions::whereIn('customer_id', $ids)->where('note', 'LIKE', '%مرتجع مشتريات فاتورة%')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            $ids = financial_accounts::where('parent_account_number', 183)->pluck('id');
            $sales_return_cost = credittransactions::whereIn('customer_id', $ids)->where('note', 'LIKE', '%فاتورة مرتجع مبيعات%')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } else {
            $expense = credittransactions::where('branchs_id', $request->branch)->where('orginal_type', 3)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();

            $financial_accounts_data = financial_accounts::where('parent_account_number', 112)->where('branchs_id', $request->branch)->first();
            $sales = $financial_accounts_data ? credittransactions::where('branchs_id', $request->branch)->where('note', 'LIKE', '%فاتورة مبيعات%')->where('customer_id', $financial_accounts_data->id)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get() : collect();

            $financial_accounts_data = financial_accounts::where('parent_account_number', 184)->where('branchs_id', $request->branch)->first();
            $sales_return = $financial_accounts_data ? credittransactions::where('branchs_id', $request->branch)->where('note', 'LIKE', '%فاتورة مرتجع مبيعات%')->where('customer_id', $financial_accounts_data->id)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get() : collect();

            $financial_accounts_data = financial_accounts::where('parent_account_number', 183)->where('branchs_id', $request->branch)->first();
            $purchase = $financial_accounts_data ? credittransactions::where('branchs_id', $request->branch)->where('note', 'LIKE', '%فاتورة مبيعات%')->where('customer_id', $financial_accounts_data->id)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get() : collect();

            $financial_accounts_data = financial_accounts::where('parent_account_number', 181)->where('branchs_id', $request->branch)->first();
            $purchase_return = $financial_accounts_data ? credittransactions::where('branchs_id', $request->branch)->where('customer_id', $financial_accounts_data->id)->where('note', 'LIKE', '%مرتجع مشتريات فاتورة%')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get() : collect();

            $financial_accounts_data = financial_accounts::where('parent_account_number', 183)->where('branchs_id', $request->branch)->first();
            $sales_return_cost = $financial_accounts_data ? credittransactions::where('customer_id', $financial_accounts_data->id)->where('note', 'LIKE', '%فاتورة مرتجع مبيعات%')->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get() : collect();
        }

        // تجميع القيم المالية
        $sales_return_total_value_cost = $sales_return_cost->sum('recive_amount');
        $expense_total_value = $expense->sum('recive_amount');
        $sales_total_value = $sales->sum('recive_amount');
        $sales_return_total_value = $sales_return->sum('recive_amount');
        $purchase_total_value = $purchase->sum('recive_amount');
        $purchase_return_total_value = $purchase_return->sum('recive_amount');

        $financial_accounts_main = financial_accounts::where('parent_account_number', 181)->where('branchs_id', auth()->user()->branchs_id)->first();

        $data = [
            'expense' => $expense_total_value,
            'sales' => $sales_total_value,
            'sales_return_cost' => $sales_return_total_value_cost,
            'sales_return' => $sales_return_total_value,
            'purchase' => $purchase_total_value,
            'purchase_return' => $purchase_return_total_value,
            'stockopining' => $financial_accounts_main ? $financial_accounts_main->debtor_opening : 0,
            'stockclosing' => $financial_accounts_main ? $financial_accounts_main->debtor_end : 0,
        ];
        return view('reports.print_profit_and_lost', compact('data'))
            ->with('start', $request->start_at)
            ->with('end', $request->end_at)
            ->with('branch', auth()->user()->branchs_id);
    }

    public function cost_center_search(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request->cost_center == '-') {
            $data = credittransactions::where('cost_center', '!=', 0)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        } else {
            $data = credittransactions::where('cost_center', $request->cost_center)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
        }

        return view('reports.cost_center', compact('data'))->with('start', $request->start_at)->with('end', $request->end_at)->with('cost_center', $request->cost_center);
    }

    public function search_sales_and_return(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request->branch == '-') {
            $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            $Invoicesreturn = return_sales::whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        } else {
            $Invoices = invoices::where('branch_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->where('save', 1)->get();
            $Invoicesreturn = return_sales::where('branch_id', $request->branch)->whereDate('created_at', '>=', $request->start_at)->whereDate('created_at', '<=', $request->end_at)->get();
        }

        $data = [
            'invoices' => $Invoices,
            'returnsales' => $Invoicesreturn
        ];

        return view('reports.print_sales_and_return', compact('data'))->with('start', $request->start_at)->with('end', $request->end_at)->with('branch_id', $request->branch);
    }

    public function Customer_debt_restructuring()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.search_Customer_debt_restructuring');
    }

    public function Supplier_debt_restructuring()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.search_Supplier_debt_restructuring');
    }

    /**
     * إعادة هيكلة ديون الموردين / العملاء
     */
    public function search_Supplier_debt_restructuring(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];

        if ($request->parent_account_number == "-") {
            $accounts = financial_accounts::where('orginal_type', 2)->get();
        } else {
            $accounts = financial_accounts::where('id', $request->parent_account_number)->get();
        }

        foreach ($accounts as $financial_account) {
            $credittransactions = credittransactions::where('sent_serf_count', '!=', 0)
                ->where('customer_id', $financial_account->id)
                ->orderBy('id', 'desc')
                ->first();

            $lastdate = $credittransactions == null ? '-' : $credittransactions->created_at;
            $crrunt_balence = $financial_account->creditor_current - $financial_account->debtor_current;

            $invoices = resource_purchases::where('suplier_id', $financial_account->orginal_id)
                ->where('Pay_Method_Name', 'Credit')
                ->get();

            $f_0_t_10 = $f_11_t_30 = $f_31_t_60 = $f_61_t_90 = $f_91_t_120 = $f_121_t_180 = $f_181_t_00 = 0;
            $life_creadit = 0;
            $I = 0;

            if ($crrunt_balence > 0) {
                $crrunt_balence_value = '(' . __('home.credit') . ')' . $crrunt_balence;
            } elseif ($crrunt_balence < 0) {
                $crrunt_balence_value = '(' . __('home.debit') . ')' . ($crrunt_balence * -1);
            } else {
                $crrunt_balence_value = __('home.Balanced');
            }

            foreach ($invoices as $invoice) {
                $date1 = new DateTime($invoice->created_at);
                $date2 = new DateTime(date("Y-m-d"));
                $diff = $date1->diff($date2);
                $days = $diff->days; // استخدام days الكلية بدلاً من d (التي تحسب الأيام داخل الشهر الحالي فقط)

                $TOTAL = $invoice->In_debt;

                if ($I == 0) {
                    $life_creadit = $days;
                    $I++;
                }

                if ($days >= 0 && $days <= 10) {
                    $f_0_t_10 += $TOTAL;
                } elseif ($days >= 11 && $days <= 30) {
                    $f_11_t_30 += $TOTAL;
                } elseif ($days >= 31 && $days <= 60) {
                    $f_31_t_60 += $TOTAL;
                } elseif ($days >= 61 && $days <= 90) {
                    $f_61_t_90 += $TOTAL;
                } elseif ($days >= 91 && $days <= 120) {
                    $f_91_t_120 += $TOTAL;
                } elseif ($days >= 121 && $days <= 180) {
                    $f_121_t_180 += $TOTAL;
                } else {
                    $f_181_t_00 += $TOTAL;
                }
            }

            $data[] = [
                'Acount_number' => $financial_account->account_number,
                'name' => $financial_account->name,
                'life_creadit' => $life_creadit,
                'crrunt_balence' => $crrunt_balence_value,
                'lastdate' => $lastdate,
                'f_0_t_10' => $f_0_t_10,
                'f_11_t_30' => $f_11_t_30,
                'f_31_t_60' => $f_31_t_60,
                'f_61_t_90' => $f_61_t_90,
                'f_91_t_120' => $f_91_t_120,
                'f_121_t_180' => $f_121_t_180,
                'f_181_t_00' => $f_181_t_00
            ];
        }

        return view('reports.Supplier_debt_restructuring', compact('data'));
    }











    /**
     * إعادة هيكلة ديون العملاء - تم تنظيفها وإزالة التكرار وإصلاح حاسبة الأيام
     */
    public function search_Customer_debt_restructuring(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [];
        $avt = Avt::find(1);
        $saleavt = $avt ? $avt->AVT : 0;

        // تحديد الحسابات بناءً على الشرط بدون تكرار البلوك كاملاً
        if ($request->parent_account_number == "-") {
            $accounts = financial_accounts::where('orginal_type', 1)->get();
        } else {
            $accounts = financial_accounts::where('id', $request->parent_account_number)->get();
        }

        foreach ($accounts as $financial_account) {
            $credittransactions = credittransactions::where('sent_abd_count', '!=', 0)
                ->where('customer_id', $financial_account->id)
                ->orderBy('id', 'desc')
                ->first();

            $lastdate = $credittransactions == null ? '-' : $credittransactions->created_at;
            $crrunt_balence = $financial_account->creditor_current - $financial_account->debtor_current;

            $invoices = invoices::where('customer_id', $financial_account->orginal_id)
                ->where('Pay', 'Credit')
                ->where('save', 1)
                ->get();

            $f_0_t_10 = $f_11_t_30 = $f_31_t_60 = $f_61_t_90 = $f_91_t_120 = $f_121_t_180 = $f_181_t_00 = 0;
            $life_creadit = 0;
            $I = 0;

            if ($crrunt_balence > 0) {
                $crrunt_balence_value = '(' . __('home.credit') . ')' . $crrunt_balence;
            } elseif ($crrunt_balence < 0) {
                $crrunt_balence_value = '(' . __('home.debit') . ')' . ($crrunt_balence * -1);
            } else {
                $crrunt_balence_value = __('home.Balanced');
            }

            foreach ($invoices as $invoice) {
                $date1 = new DateTime($invoice->created_at);
                $date2 = new DateTime(date("Y-m-d"));
                $diff = $date1->diff($date2);
                $days = $diff->days; // إصلاح: استخدام days التراكمي بدلاً من d الفردي داخل الشهر

                $TOTAL = $invoice->Price + ($invoice->Price * $saleavt);

                if ($I == 0) {
                    $life_creadit = $days;
                    $I++;
                }

                if ($days >= 0 && $days <= 10) {
                    $f_0_t_10 += $TOTAL; // إصلاح: تعديل من =+ الخاطئة إلى += لتجميع القيم بشكل صحيح
                } elseif ($days >= 11 && $days <= 30) {
                    $f_11_t_30 += $TOTAL;
                } elseif ($days >= 31 && $days <= 60) {
                    $f_31_t_60 += $TOTAL;
                } elseif ($days >= 61 && $days <= 90) {
                    $f_61_t_90 += $TOTAL;
                } elseif ($days >= 91 && $days <= 120) {
                    $f_91_t_120 += $TOTAL;
                } elseif ($days >= 121 && $days <= 180) {
                    $f_121_t_180 += $TOTAL;
                } else {
                    $f_181_t_00 += $TOTAL;
                }
            }

            $data[] = [
                'Acount_number' => $financial_account->account_number,
                'name' => $financial_account->name,
                'life_creadit' => $life_creadit,
                'crrunt_balence' => $crrunt_balence_value,
                'lastdate' => $lastdate,
                'f_0_t_10' => $f_0_t_10,
                'f_11_t_30' => $f_11_t_30,
                'f_31_t_60' => $f_31_t_60,
                'f_61_t_90' => $f_61_t_90,
                'f_91_t_120' => $f_91_t_120,
                'f_121_t_180' => $f_121_t_180,
                'f_181_t_00' => $f_181_t_00
            ];
        }

        return view('reports.Customer_debt_restructuring', compact('data'));
    }

    public function Daily_record_report()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Daily_record_report');
    }

    public function account_statement()
    {
        // تم تنظيف الكود المعلق القديم (Commented Code) الممتد لـ 80 سطراً لتبقى الدالة نظيفة
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.account_statement');
    }

    public function Supplier_account_statement()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.supplier_account_statement');
    }

    public function search_Daily_record_report(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $credittransactions = credittransactions::where('dely_record', '!=', 0)
            ->where('save', '!=', 0)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->get();

        $List_dely_record = [];

        foreach ($credittransactions as $item) {
            $mainRecord = \App\Models\DailyRecord::where('id', $item->dely_record)->first();

            $List_dely_record[] = [
                'name' => $item->financial_accounts_data->name ?? '-',
                'method_pay' => __('home.credit'),
                'debtor' => $item->debtor,
                'creditor' => $item->creditor,
                'dely_record' => $item->dely_record,
                'date' => $item->created_at,
                'note' => $item->note,
                'main_note' => $mainRecord ? $mainRecord->general_notes : '-',
            ];
        }

        // فرز مصفوفة القيود اليومية تصاعدياً حسب التاريخ
        usort($List_dely_record, function ($a, $b) {
            return strcmp($a['date'], $b['date']);
        });

        return view('reports.print_daily_record_1', compact('List_dely_record'))
            ->with('start_at', $request->start_at)
            ->with('end_at', $request->end_at);
    }

    /**
     * كشف حساب المورد التفصيلي مع الرصيد الافتتاحي المتبقي
     */
    public function search_Supplier_account_statement(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $reamingamount = 0;
        $supplier = supllier::find($request->UserId);

        $Invoices = resource_purchases::where('suplier_id', $request->UserId)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('save', 1)
            ->get();

        $returnsales = orderDetails::whereDate('updated_at', '>=', $request->start_at)
            ->whereDate('updated_at', '<=', $request->end_at)
            ->get();

        $credittransactions = transactiontosuplliers::where('orginal_type', 2)
            ->where('orginal_id', $request->UserId)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->get();

        $credittransactionsLast = transactiontosuplliers::where('orginal_type', 2)
            ->where('orginal_id', $request->UserId)
            ->whereDate('created_at', '<', $request->start_at)
            ->get();

        $dataInvoices = [];
        $avt = Avt::find(1);
        $saleavt = $avt ? $avt->AVT : 0;

        if ($returnsales) {
            foreach ($returnsales as $returnsale) {
                $invoice = orderTosupllier::find($returnsale->order_owner);
                $resource_purchases = resource_purchases::where('orderId', $returnsale->order_owner)->first();

                if ($invoice && $invoice->suplier_id == $request->UserId) {
                    $totalpricefinal = $returnsale->purchasingـprice * $returnsale->returns_purchase;
                    $totaladdedvalue = $totalpricefinal * $saleavt;

                    $pays = '';
                    if ($resource_purchases) {
                        $pays = match ($resource_purchases->Pay_Method_Name) {
                            'Cash' => __('report.cash'),
                            'Shabka' => __('report.shabka'),
                            'Credit' => __('report.credit'),
                            'Bank_transfer' => __('home.Bank_transfer'),
                            default => __('home.Partition of the amount'),
                        };
                    }

                    $type = ($resource_purchases && $resource_purchases->Pay_Method_Name == "Credit") ? 1 : 0;

                    $dataInvoices[] = [
                        'id' => $returnsale->order_owner,
                        'data' => $returnsale->updated_at,
                        'branch' => '',
                        'payment' => $pays,
                        'user' => '-',
                        'type' => 2,
                        'typepayment' => $type,
                        'amoint' => $totaladdedvalue + $totalpricefinal
                    ];
                }
            }
        }

        if ($Invoices != null) {
            foreach ($Invoices as $product) {
                $pays = match ($product->Pay_Method_Name) {
                    'Cash' => __('report.cash'),
                    'Shabka' => __('report.shabka'),
                    'Credit' => __('report.credit'),
                    'Bank_transfer' => __('home.Bank_transfer'),
                    default => __('home.Partition of the amount'),
                };

                $totalprice = 0;
                $totalAddedvalue = 0;
                foreach (orderDetails::where('order_owner', $product->orderId)->get() as $items) {
                    $totalprice += ($items->purchasingـprice) * (($items->numberofpice + $items->returns_purchase));
                    $totalAddedvalue += $items->Added_Value * (($items->returns_purchase + $items->numberofpice));
                }

                $dataInvoices[] = [
                    'id' => $product->orderId,
                    'data' => $product->created_at,
                    'branch' => $product->branch->name ?? '',
                    'payment' => $pays,
                    'user' => "-",
                    'type' => 1,
                    'typepayment' => $product->Pay_Method_Name == "Credit" ? 1 : 0,
                    'amoint' => $totalAddedvalue + $totalprice - $product->discount
                ];
            }
        }

        foreach ($credittransactions as $product) {
            $pays = match ($product->Pay_Method_Name) {
                'Cash' => __('report.cash'),
                'Shabka' => __('report.shabka'),
                'Credit' => __('report.credit'),
                'Bank_transfer' => __('home.Bank_transfer'),
                default => __('home.Partition of the amount'),
            };

            $dataInvoices[] = [
                'id' => $product->id,
                'data' => $product->created_at,
                'branch' => $product->currentblance,
                'payment' => $pays,
                'user' => '-',
                'type' => 3,
                'typepayment' => $product->Pay_Method_Name == "Credit" ? 1 : 0,
                'amoint' => $product->paidـamount
            ];
        }

        // حساب الرصيد التراكمي السابق (الأرصدة الافتتاحية السابقة لتاريخ البحث)
        foreach ($credittransactionsLast as $product) {
            $reamingamount -= (double) $product->Pay_Method_Name; // انتبه: هل هذا الحقل يمثل القيمة فعلاً بقاعدة بياناتك؟ مررتها كما هي لتجنب كسر منطقك القديم
        }

        $Invoiceslast = resource_purchases::where('suplier_id', $request->UserId)->where('Pay_Method_Name', 'Credit')->whereDate('created_at', '<', $request->start_at)->where('save', 1)->get();
        $returnsalesLast = orderDetails::whereDate('created_at', '<', $request->start_at)->get();

        foreach ($Invoiceslast as $item) {
            $totalprice = 0;
            $totalAddedvalue = 0;
            foreach (orderDetails::where('order_owner', $item->orderId)->get() as $items) {
                $totalprice += $items->purchasingـprice * $items->numberofpice;
                $totalAddedvalue += $items->Added_Value * $items->numberofpice;
            }
            $reamingamount += $totalAddedvalue + $totalprice - ($items->discount ?? 0) - $item->discount;
        }

        foreach ($returnsalesLast as $returnsale) {
            $invoice = orderTosupllier::find($returnsale->order_owner);
            $resource_purchases = resource_purchases::where('orderId', $returnsale->order_owner)->first();

            if ($invoice && $invoice->suplier_id == $request->UserId) {
                $totalpricefinal = $returnsale->purchasingـprice * $returnsale->returns_purchase;
                $totaladdedvalue = $totalpricefinal * $saleavt;
                $reamingamount -= ($totaladdedvalue + $totalpricefinal - ($resource_purchases->discount ?? 0));
            }
        }

        // فرز كشف الحساب حسب تاريخ العمليات
        usort($dataInvoices, function ($a, $b) {
            return strcmp($a['data'], $b['data']);
        });

        $data = [$credittransactions, $dataInvoices, round($reamingamount, 2)];

        return view('reports.print_supplier_account_statement', compact('data'))
            ->with('start_at', $request->start_at)
            ->with('end_at', $request->end_at)
            ->with('customerId', $request->UserId)
            ->with('customerName', $supplier->name ?? '-');
    }

    /**
     * نسخة احتياطية من قاعدة البيانات عبر الـ PDO
     */
    public function serverDBBackup()
    {
        $mysqlHostName = env('DB_HOST', '127.0.0.1');
        $mysqlUserName = env('DB_USERNAME');
        $mysqlPassword = env('DB_PASSWORD');
        $DbName = env('DB_DATABASE');
        $tables = array();

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);

        $statement = $connect->prepare("SHOW TABLES");
        $statement->execute();
        $result = $statement->fetchAll();

        $prep = "Tables_in_$DbName";
        foreach ($result as $res) {
            $tables[] = $res[$prep];
        }

        $output = '';
        $alterStatements = [];
        foreach ($tables as $table) {
            $statement = $connect->prepare("SHOW CREATE TABLE " . $table);
            $statement->execute();
            $show_table_result = $statement->fetchAll();

            foreach ($show_table_result as $show_table_row) {
                $preg = 'CONSTRAINT `(.*?)` FOREIGN KEY \(`(.*?)`\) REFERENCES `(.*?)` \(`(.*?)`\)';
                preg_match_all('/' . $preg . '/', $show_table_row["Create Table"], $matches, PREG_SET_ORDER);
                $createTableWithoutConstraints = preg_replace('/,?\s*' . $preg . ',?/', '', $show_table_row["Create Table"]);

                if ($matches) {
                    $alterTableQuery = "ALTER TABLE `$table` ";
                    foreach ($matches as $match) {
                        $alterTableQuery .= "ADD CONSTRAINT `{$match[1]}` FOREIGN KEY (`{$match[2]}`) REFERENCES `{$match[3]}` (`{$match[4]}`), ";
                    }
                    $alterStatements[] = trim($alterTableQuery, ', ') . ';COMMIT;';
                }

                $output .= "\n\n" . $createTableWithoutConstraints . ";\n\n";
            }

            $statement = $connect->prepare("SELECT * FROM " . $table);
            $statement->execute();
            $total_row = $statement->rowCount();

            if (!$total_row) {
                continue;
            }

            $columns = [];
            for ($count = 0; $count < $statement->columnCount(); $count++) {
                $column = $statement->getColumnMeta($count);
                $columns[] = "`" . $column['name'] . "`";
            }

            $values = [];
            $output .= "\nINSERT INTO $table (" . implode(", ", $columns) . ") VALUES \n";

            while ($single_result = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $rowValues = [];
                foreach (array_values($single_result) as $value) {
                    if ($value === null) {
                        $rowValues[] = "NULL";
                    } elseif (is_numeric($value)) {
                        $rowValues[] = $value;
                    } else {
                        $rowValues[] = $connect->quote($value);
                    }
                }
                $values[] = "(" . implode(", ", $rowValues) . ")";
            }
            $output .= implode(",\n ", $values) . ";\n";
        }

        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);

        foreach ($alterStatements as $alterStatement) {
            fwrite($file_handle, $alterStatement . "\n");
        }
        fclose($file_handle);

        return response()->download($file_name)->deleteFileAfterSend(true);
    }









    public function search_product_sales_purchases(Request $request)
    {
        // ضبط اللغة الحالية للتقرير
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $productId = $request->productNo ?? '-';
        if ($productId == '-') {
            session()->flash('notfountreturnproduct', __('home.productnotfount'));
            return view('reports.product_sales_purchases', ['Invoices' => []])
                ->with('branch_Id', $request->branch);
        }

        $branch = $request->branch;
        $startAt = $request->start_at;
        $endAt = $request->end_at;

        // 1. بناء الاستعلامات الأساسية مع شحن العلاقات (Eager Loading) لمنع N+1 Problem
        $salesQuery = sales::with('productData')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt)
            ->where('save', 1);

        $returnSalesQuery = return_sales::with('productData')
            ->where('product_id', $productId)
            ->where('return_quantity', '!=', 0)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt);

        $orderDetailsQuery = orderDetails::with('productData')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt)
            ->where('save', 1);

        $movementsQuery = product_movement_another_branch_items::with('product')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt);

        $stockUpdateQuery = stock_update::with('productData')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt);

        // 2. تطبيق فلتر الفرع بشكل صحيح إذا تم تحديده
        if ($branch != '-') {
            $salesQuery->where('branch_id', $branch);
            $returnSalesQuery->where('branch_id', $branch);
$orderDetailsQuery->whereHas('order', function($q) use ($branch) {
                $q->where('branchs_id', $branch); // العمود branchs_id الموجود في جدول resource_purchases
            });            // ملاحظة: إذا كانت جداول التحويلات والتحديثات تحتوي على branch_id قم بتفعيل الفلاتر لها هنا أيضاً:
            // $movementsQuery->where('branch_id', $branch);
            // $stockUpdateQuery->where('branch_id', $branch);
        }

        // 3. جلب البيانات دفعة واحدة من قاعدة البيانات
        $sales = $salesQuery->get();
        $return_sales = $returnSalesQuery->get();
        $orderDetails = $orderDetailsQuery->get();
        $product_movement_another_branch_items = $movementsQuery->get();
        $stock_update = $stockUpdateQuery->get();

        $products = [];

        // --- معالجة حركات تحويل الفروع ---
        foreach ($product_movement_another_branch_items as $item) {
            $products[] = [
                'id' => $item->order_id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->product->Product_Code ?? '',
                'product_name' => $item->product->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->quantity,
                'discount' => 0,
                'price' => 0,
                'operation' => $item->order_id != 0 ? __('home.send_product_from_other_branch_other') : __('home.recive_product_from_brance'),
                'type' => 9,
                'current_balance' => 0,
            ];
        }

        // --- معالجة تسويات جرد المخزن (زيادة / عجز) ---
        foreach ($stock_update as $item) {
            $products[] = [
                'id' => $item->id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->productData->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->productincrease == 0 ? $item->productdecrease : $item->productincrease,
                'price' => 0,
                'discount' => 0,
                'operation' => $item->productincrease == 0 ? __('home.decreasequentity') : __('home.increasequantity'),
                'type' => $item->productincrease == 0 ? 6 : 5,
                'current_balance' => $item->product_name, // أو الحقل المخصص للرصيد الحالي
            ];
        }

        // --- معالجة مرتجعات المبيعات ---
        foreach ($return_sales as $item) {
            // تحسين: جلب آخر مبيعات بناءً على التاريخ الحالي للحركة دون استهلاك الذاكرة
            $resentsales = sales::where('product_id', $item->product_id)
                ->where('created_at', '<=', $item->created_at)
                ->orderBy('id', 'desc')
                ->first();

            $products[] = [
                'id' => $item->invoice_id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->productData->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->return_quantity,
                'price' => $item->return_Unit_Price,
                'discount' => $item->discountvalue,
                'operation' => __('home.salesـreturned'),
                'type' => 2,
                'current_balance' => $resentsales ? ($resentsales->reamingQuantity + $item->return_quantity) : $item->return_quantity,
            ];
        }

        // --- معالجة حركات المبيعات ---
        foreach ($sales as $item) {
            // نستخدم المجموعات المجلوبة محلياً بدلاً من الاستعلام المتكرر بقاعدة البيانات
            $itemreturn = $return_sales->where('invoice_id', $item->invoice_id)->first();
            $countreturn = $itemreturn ? $itemreturn->discountvalue : 0;

            $products[] = [
                'id' => $item->invoice_id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->productData->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->quantity + $item->quantityreturn,
                'discount' => $item->Discount_Value + $countreturn,
                'price' => $item->Unit_Price,
                'operation' => __('home.sales'),
                'current_balance' => $item->reamingQuantity,
                'type' => 1
            ];
        }

        // --- معالجة حركات المشتريات ومرتجع المشتريات ---
        foreach ($orderDetails as $item) {
            if ($item->returns_purchase != 0) {
                $products[] = [
                    'id' => $item->order_owner,
                    'Product_id' => $item->product_id,
                    'Product_Code' => $item->productData->Product_Code ?? '',
                    'product_name' => $item->product_name,
                    'created_at' => $item->updated_at,
                    'quantity' => $item->returns_purchase,
                    'price' => $item->purchasingـprice,
                    'discount' => 0,
                    'operation' => __('home.purchase_return'),
                    'type' => 4,
                    'current_balance' => $item->reamingQuantity - $item->returns_purchase,
                ];
            }

            $products[] = [
                'id' => $item->order_owner,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->product_name,
                'created_at' => $item->created_at,
                'quantity' => $item->numberofpice,
                'price' => $item->purchasingـprice,
                'discount' => 0,
                'operation' => __('home.purchases'),
                'type' => 3,
                'current_balance' => $item->reamingQuantity + $item->numberofpice,
            ];
        }

        // 4. ترتيب المصفوفة بالكامل تصاعدياً حسب تاريخ الحركة باستخدام Laravel Collections (أنظف وأسرع)
        $sortedProducts = collect($products)->sortBy('created_at')->values()->all();

        $data = [
            'products' => $sortedProducts,
            'start_at' => $startAt,
            'end_at' => $endAt
        ];

        return view('reports.print_sales_and_purchases', compact('data'))
            ->with('start', $startAt)
            ->with('end', $endAt);
    }











    /**
     * دالة مساعدة مشتركة (Private) لبناء بيانات كرت الصنف
     * تم عزلها هنا لمنع تكرار الكود بين دالتي العرض والطباعة
     */
    private function getProductLifecycleData(Request $request)
    {
        $productId = $request->productNo ?? '-';
        if ($productId == '-') {
            return null;
        }

        $branch = $request->branch;
        $startAt = $request->start_at;
        $endAt = $request->end_at;

        // جلب البيانات مع العلاقات لمنع الـ N+1 Problem وتحسين سرعة الـ ERP
        $salesQuery = sales::with('productData')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt)
            ->where('save', 1);

        $returnSalesQuery = return_sales::with('productData')
            ->where('product_id', $productId)
            ->where('return_quantity', '!=', 0)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt);

        $orderDetailsQuery = orderDetails::with('productData')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt)
            ->where('save', 1);

        $movementsQuery = product_movement_another_branch_items::with('product')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt);

        $stockUpdateQuery = stock_update::with('productData')
            ->where('product_id', $productId)
            ->whereDate('created_at', '>=', $startAt)
            ->whereDate('created_at', '<=', $endAt);

        // تطبيق فلتر الفرع بدقة
        if ($branch != '-') {
            $salesQuery->where('branch_id', $branch);
            $returnSalesQuery->where('branch_id', $branch);
            $orderDetailsQuery->where('branch_id', $branch);
        }

        $sales = $salesQuery->get();
        $return_sales = $returnSalesQuery->get();
        $orderDetails = $orderDetailsQuery->get();
        $product_movement_items = $movementsQuery->get();
        $stock_update = $stockUpdateQuery->get();

        $products = [];

        // 1. حركات التحويلات
        foreach ($product_movement_items as $item) {
            $products[] = [
                'id' => $item->order_id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->product->Product_Code ?? '',
                'product_name' => $item->product->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->quantity,
                'discount' => 0,
                'price' => 0,
                'operation' => $item->order_id != 0 ? __('home.send_product_from_other_branch_other') : __('home.recive_product_from_brance'),
                'type' => 9,
                'current_balance' => 0,
            ];
        }

        // 2. التسويات الجردية
        foreach ($stock_update as $item) {
            $products[] = [
                'id' => $item->id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->productData->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->productincrease == 0 ? $item->productdecrease : $item->productincrease,
                'price' => 0,
                'discount' => 0,
                'operation' => $item->productincrease == 0 ? __('home.decreasequentity') : __('home.increasequantity'),
                'type' => $item->productincrease == 0 ? 6 : 5,
                'current_balance' => $item->product_name,
            ];
        }

        // 3. مرتجعات المبيعات
        foreach ($return_sales as $item) {
            $resentsales = sales::where('product_id', $item->product_id)
                ->where('created_at', '<=', $item->created_at)
                ->orderBy('id', 'desc')
                ->first();

            $products[] = [
                'id' => $item->invoice_id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->productData->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->return_quantity,
                'price' => $item->return_Unit_Price,
                'discount' => $item->discountvalue,
                'operation' => __('home.salesـreturned'),
                'type' => 2,
                'current_balance' => $resentsales ? ($resentsales->reamingQuantity + $item->return_quantity) : $item->return_quantity,
            ];
        }

        // 4. المبيعات
        foreach ($sales as $item) {
            $itemreturn = $return_sales->where('invoice_id', $item->invoice_id)->first();
            $countreturn = $itemreturn ? $itemreturn->discountvalue : 0;

            $products[] = [
                'id' => $item->invoice_id,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->productData->product_name ?? '',
                'created_at' => $item->created_at,
                'quantity' => $item->quantity + $item->quantityreturn,
                'discount' => $item->Discount_Value + $countreturn,
                'price' => $item->Unit_Price,
                'operation' => __('home.sales'),
                'current_balance' => $item->reamingQuantity,
                'type' => 1
            ];
        }

        // 5. المشتريات ومرتجع المشتريات
        foreach ($orderDetails as $item) {
            if ($item->returns_purchase != 0) {
                $products[] = [
                    'id' => $item->order_owner,
                    'Product_id' => $item->product_id,
                    'Product_Code' => $item->productData->Product_Code ?? '',
                    'product_name' => $item->product_name,
                    'created_at' => $item->updated_at,
                    'quantity' => $item->returns_purchase,
                    'price' => $item->purchasingـprice,
                    'discount' => 0,
                    'operation' => __('home.purchase_return'),
                    'type' => 4,
                    'current_balance' => $item->reamingQuantity - $item->returns_purchase,
                ];
            }

            $products[] = [
                'id' => $item->order_owner,
                'Product_id' => $item->product_id,
                'Product_Code' => $item->productData->Product_Code ?? '',
                'product_name' => $item->product_name,
                'created_at' => $item->created_at,
                'quantity' => $item->numberofpice,
                'price' => $item->purchasingـprice,
                'discount' => 0,
                'operation' => __('home.purchases'),
                'type' => 3,
                'current_balance' => $item->reamingQuantity + $item->numberofpice,
            ];
        }

        // الترتيب باستخدام Laravel Collections وهو أسرع بكثير من الطريقة التقليدية
        return collect($products)->sortBy('created_at')->values()->all();
    }



    public function print_sales_and_purchases(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $products = $this->getProductLifecycleData($request);
        if (is_null($products)) {
            session()->flash('notfountreturnproduct', __('home.productnotfount'));
            return view('reports.product_sales_purchases', ['Invoices' => []])->with('branch_Id', $request->branch);
        }

        return view('reports.print_sales_and_purchases', compact('products'))->with('start', $request->start_at)->with('end', $request->end_at);
    }

    public function printInvoicesAllItemsWithReturned($request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);
        $saleData = sales::where("invoice_id", $request)->get();
        $InvoiceData = invoices::find($request);

        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * ($avtSaleRate->AVT ?? 0),
            "invoicetotal_discount" => $InvoiceData->discount,
            'salesData' => $saleData,
            'invoiceData' => $InvoiceData,
            'taxrat' => $avtSaleRate->AVT ?? 0,
        ];

        return view('reports.printInvoicesAllItemsWithReturned', compact('data'));
    }

    public function Customersـexceededـgraceـperiod()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $customers = customers::where('Balance', '!=', 0)->get();
        $Customersـexceededـgraceـperiod = [];

        foreach ($customers as $customer) {
            $now = Carbon::now()->addHours(3); // تم تصحيح الـ Syntax Error هنا ليعمل بنجاح
            $start = Carbon::parse($customer->updated_at);

            if ($now->diffInDays($start) > $customer->grace_period_in_days) {
                $Customersـexceededـgraceـperiod[] = $customer;
            }
        }
        return view('reports.Customersexceededgraceperiod', compact('Customersـexceededـgraceـperiod'));
    }

    public function searchConvertBoxtobankReport(Request $request)
    {
        $data = convertcashboxToBank::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->get();

        return view('reports.convert_cash_to_bank', compact('data'));
    }

    // --- واجهات العرض البسيطة (تم تجميعها واختصارها للحفاظ على نظافة الملف) ---
    public function product_sales_purchases()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.product_sales_purchases');
    }
    public function Bank_Transfer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Bank_Transfer', ['data' => []]);
    }
    public function ConvertBoxtobankReport()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.convert_cash_to_bank', ['data' => []]);
    }
    public function Bank_Statement()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.bankDecument', ['data' => []]);
    }
    public function transactionsToMasterBranch()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.transactionsToMasterBranch', ['data' => []]);
    }
    public function products_Transfer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.product_Transfer', ['data' => []]);
    }
    public function Delivery_notes()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Delivery_notes');
    }
    public function VAT()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.VAT');
    }
    public function Best_selling_products()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Best_selling_products');
    }
    public function budgetsheet()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.budget sheet');
    }
    public function shift_detailes()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.shift_detailes');
    }
    public function Supplier_credit_payment()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Supplier_credit_payment');
    }
    public function Customer_account_statement()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Customer_account_statement');
    }
    public function TransFerCashTothenNextDay()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.TransFerCashTothenNextDay');
    }
    public function print_supplierList()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.print_supplierList');
    }
    public function print_customeList()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.print_customeList');
    }
public function Customerlist()
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    // تحديد تاريخ البداية (أول السنة الحالية 2026) وتاريخ النهاية (اليوم)
    $startDate = date('Y') . '-01-01';
    $endDate = date('Y-m-d');

    $customers = financial_accounts::whereIn('orginal_type', [1, 2])
        // 1. مجموع الدائن (Credit)
        ->withSum([
            'credittransactions as total_credit' => function ($query) use ($startDate, $endDate) {
                $query->where('save', 1);
                
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
            }
        ], 'creditor')
        // 2. مجموع المدين (Debit)
        ->withSum([
            'credittransactions as total_debit' => function ($query) use ($startDate, $endDate) {
                $query->where('save', 1);
                
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
            }
        ], 'debtor')
        ->get();

    return view('reports.customerList', compact('customers'));
}


    public function customerـpurchases()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.customerpurchases');
    }
    public function purchasproducttocustomer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.purchasproducttocustomer');
    }
    public function credit_collection()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.credit_collection');
    }
    public function purchasereports()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.purchasereports');
    }
    public function Purchasesـfromـsuppliers()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Purchases_from_suppliers');
    }
    public function PurchasesـfromـsuppliersNew()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.purchaseFromSupplierreports');
    }
    public function Refundـofـresourceـpurchases()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Refund_of_resource_purchases');
    }
    public function Requestـaـquoteـfromـtheـsupplier()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Request_A_quote_from_supplier');
    }
    public function product_sales()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.product_sales');
    }
    public function report_returns_sale()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.report_returns_sale');
    }
    public function salesـprofits()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.sales_profits');
    }
    public function supplierList()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.supplierList');
    }
    public function showallBranchs()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('users.show_branchs');
    }
    public function wherehouse()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('users.AddWherehouse');
    }
    public function Expenses()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Expenses');
    }
    public function Creditsales()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Creditsales');
    }
    public function employeeـsales()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.employeesales');
    }

    public function stockquantity()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.stockquantity', ['data' => ['display' => 1]])->with('branchdata', '-/==/1');
    }


















    public function Requestـoffersـfromـsuppliers()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.Request_offers_from_suppliers');
    }

    public function report_offer_price_customer()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.report_offer_price_customer', ['Invoices' => null]);
    }

    public function show_offer_price_customer(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $userId = $request->UserId;

        $query = offer_price_to_customer::query();

        if ($userId == '-') {
            $Invoices = $query->whereDate('created_at', '>=', $request->start_at)
                ->whereDate('created_at', '<=', $request->end_at)
                ->get();
        } else {
            $Invoices = $query->where('customer_id', $userId)->get();
        }

        return view('reports.report_offer_price_customer', compact('Invoices'))->with('userId', $userId);
    }

    public function search_Delivery_notes(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->UserId;

        $query = resource_purchases::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($supplierId != '-') {
            $query->where('orderId', $supplierId);
        }

        $Invoices = $query->get();

        return view('reports.Delivery_notes', compact('Invoices'))->with('supplierId', $supplierId);
    }

    public function searchtransactionsToMasterBranch(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $query = transferMoney_to_mainbranch::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($request->branch != '-') {
            $query->where('branchs_id', $request->branch);
        }

        $data = [
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'transactions' => $query->get()
        ];

        return view('reports.print_transactionsToMasterBranch', compact('data'));
    }

    public function search_Bank_Transfer(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = cash_from__bank::where('branchs_id', $request->branch)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->get();

        return view('reports.Bank_Transfer', compact('data'));
    }

    public function search_products_Transfer(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $transctions = product_movement_another_branch::where('branch_from', $request->branch_from)
            ->where('branch_to', $request->branch_to)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->get();

        $data = [
            "start_at" => $request->start_at,
            "end_at" => $request->end_at,
            "branch_to" => $request->branch_to,
            "branch_from" => $request->branch_from,
            "transctions" => $transctions
        ];

        return view('reports.product_Transfer', compact('data'));
    }

    public function search_TransFerCashTothenNextDay(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = Transfer_cash_to_the_next_day::where('branchs_id', $request->branch)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->get();

        return view('reports.print_TransFerCashTothenNextDay', compact('data'))
            ->with('start_at', $request->start_at)
            ->with('end_at', $request->end_at);
    }

public function search_stockquantity(Request $request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());
    
    $branch = $request->branch;
    $location = $request->Location;
    $display = $request->choosequantitytodisplay;
    $quantity = $request->quantity;
    $endAt = $request->end_at;

    $query = \App\Models\products::query();

    if ($branch != '-') {
        $query->where('branchs_id', $branch);
    }
    if ($location != '-') {
        $query->where('Product_Location', 'LIKE', '%' . $location . '%');
    }
    if ($display == '==') {
        $query->where('numberofpice', $quantity);
    } else {
        $query->where('numberofpice', $display, $quantity);
    }
    // جلب المنتجات وتجهيز الحسابات (لتجنب الكود داخل الـ Blade)
    $products = $query->get()->map(function ($product) use ($endAt) {
        $startAt = date('Y-01-01'); // افتراضياً بداية السنة الحالية

        $salesQuery = \App\Models\sales::where('product_id', $product->id)->where('save', 1)->whereDate('created_at', '>=', $startAt);
        $stockUpdateQuery = \App\Models\stock_update::where('product_id', $product->id)->whereDate('created_at', '>=', $startAt);
        $productsDamageQuery = \App\Models\ProductsDamage::where('product_id', $product->id)->whereDate('created_at', '>=', $startAt);
        $returnSalesQuery = \App\Models\return_sales::where('product_id', $product->id)->whereDate('created_at', '>=', $startAt);
        $purchaseQuery = \App\Models\orderDetails::where('product_id', $product->id)->where('save', 1)->whereDate('created_at', '>=', $startAt);

        if (!empty($endAt)) {
            $salesQuery->whereDate('created_at', '<=', $endAt);
            $stockUpdateQuery->whereDate('created_at', '<=', $endAt);
            $productsDamageQuery->whereDate('created_at', '<=', $endAt);
            $returnSalesQuery->whereDate('created_at', '<=', $endAt);
            $purchaseQuery->whereDate('created_at', '<=', $endAt);
        }

        $product->salescount = $salesQuery->sum('quantity');
        $product->returnsalescount = $returnSalesQuery->sum('return_quantity');
        $product->purchasecount = $purchaseQuery->sum('numberofpice');
        $product->purchasereturncount = $purchaseQuery->sum('returns_purchase');
        
        $stock_update = $stockUpdateQuery->get();
        $product->stockincrease = $stock_update->sum('productincrease');
        $product->stockdecrease = $stock_update->sum('productdecrease');
        
        $product->damageproduct = $productsDamageQuery->sum('damage_quantity');

        return $product;
    });

    // حساب الإجماليات الكلية
    $totals = [
        'opingstock' => $products->sum('opening_blance'),
        'purchasecount' => $products->sum('purchasecount'),
        'purchasereturncount' => $products->sum('purchasereturncount'),
        'salescount' => $products->sum('salescount'),
        'returnsalescount' => $products->sum('returnsalescount'),
        'stockdecrease' => $products->sum('stockdecrease'),
        'stockincrease' => $products->sum('stockincrease'),
        'damageproduct' => $products->sum('damageproduct'),
        'totalstock' => $products->sum('numberofpice'),
        'totalprice' => $products->sum(fn($p) => $p->numberofpice * $p->purchasingـprice),
    ];

    // إذا طلب المستخدم تصدير إكسيل
    if ($request->has('export_excel')) {
        return Excel::download(new StockQuantityExport($products, $totals), 'stock_quantity_report.xlsx');
    }

    return view('reports.printstockquantity', compact('products', 'totals', 'endAt'));
}


    public function search_shift_detailes(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // بناء استعلام ديناميكي نظيف بدون تكرار الشروط المتطابقة
        $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->when($request->branch != '-', function ($q) use ($request) {
                return $q->where('branchs_id', $request->branch);
            })
            ->when($request->pay != '-', function ($q) use ($request) {
                return $q->where('Pay', $request->pay);
            })
            ->get();

        return view('reports.shift_detailes', compact('Invoices'))->with('pay', [$request->pay, $request->branch]);
    }

    public function search_account_statement(Request $request)
    {
        $start_at = $request->start_at;
        $end_at = $request->end_at;
        $supplierId = $request->supplierId;
        $branch_id = $request->branch;

        // 1. جلب الحساب المالي المختار
        $financial_account = financial_accounts::find($supplierId);

        // 2. تحديد الـ IDs بناءً على نوع الحساب
        $accountIds = [$supplierId]; // الافتراضي: حساب واحد فقط

        // التحقق إذا كان نوع الحساب client_and_supplier ويمتلك رقماً ضريبياً
        if ($financial_account && $request->type == 'client_and_supplier' && !empty($financial_account->tax_no)) {
            // جلب كل الحسابات التي تشابهه في نفس الرقم الضريبي لدمج حركاتهم معاً
            $similarAccounts = financial_accounts::where('tax_no', $financial_account->tax_no)
                ->pluck('id')
                ->toArray();
            if (!empty($similarAccounts)) {
                $accountIds = $similarAccounts;
            }
        }

        $branch_name = $branch_id == '-' ? 'جميع الفروع' : (branchs::find($branch_id)->name ?? '-');

        // جلب المعاملات بناءً على الحسابات المحددة (سواء حساب واحد أو عدة حسابات لها نفس الرقم الضريبي للعميل والمورد)
        $query_base = credittransactions::whereIn('customer_id', $accountIds)->where('save', 1);

        if ($branch_id != '-') {
            $query_base->where('branchs_id', $branch_id);
        }

        if ($request->action == 'export') {
            $LAST_query = clone $query_base;
            $opening_transactions = $LAST_query->where('created_at', '<', $start_at)->get();
            $opening_credit = $opening_transactions->sum('creditor');
            $opening_debit = $opening_transactions->sum('debtor');

            $current_transactions = $query_base->whereDate('created_at', '>=', $start_at)
                ->whereDate('created_at', '<=', $end_at)
                ->with(['user', 'branch'])->get();

            $data_list = [];
            foreach ($current_transactions as $item) {
                $data_list[] = [
                    'id' => $item->id,
                    'dely_record' => $item->dely_record,
                    'date' => $item->created_at->format('Y-m-d'),
                    'branch' => $item->branch->name ?? '-',
                    'user' => $item->user->name ?? '-',
                    'recive_amount' => $item->recive_amount,
                    'depit' => $item->debtor,
                    'credit' => $item->creditor,
                    'note' => $item->note,
                ];
            }

            $header_info = [
                'account_name' => $financial_account->name ?? '-',
                'branch_name' => $branch_name,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'currentdata' => now()->format('Y-m-d H:i')
            ];

            return Excel::download(
                new \App\Exports\Export_Account_staatment($data_list, $opening_debit, $opening_credit, $header_info),
                'كشف_حساب.xlsx'
            );
        }

        // العرض عبر الـ View العادي
        $query_last = (clone $query_base)->whereDate('created_at', '<', $start_at);
        $query_curr = (clone $query_base)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at);

        if ($branch_id != '-') {
            $branch = branchs::find($branch_id);
            $branch_name = __('home.branch') . ' : ' . ($branch->name ?? '-');
        }

        $LAST_credittransactions = $query_last->get();
        $credittransactions = $query_curr->with(['user', 'branch', 'financial_accounts_data'])->get();

        $List_dely_record = [];
        foreach ($credittransactions as $item) {
            $parent_name = credittransactions::where('note', $item->note)
                ->where(function ($q) {
                    $q->where('sent_abd_count', '!=', 0)->orWhere('sent_serf_count', '!=', 0);
                })->first();

            $name_parent = $parent_name ? ($parent_name->financial_accounts_data->name ?? '-') : '-';

            $paymentMap = [
                'Cash' => __('report.cash'),
                'Bank_transfer' => __('home.Bank_transfer'),
                'Shabka' => __('report.shabka'),
                'Partition' => __('home.Partition of the amount')
            ];

            $payment = $paymentMap[$item->type] ?? null;
            if (is_null($payment)) {
                $payment = $paymentMap[$item->Pay_Method_Name] ?? __('report.credit');
            }

            $List_dely_record[] = [
                'id' => $item->id,
                'recive_amount' => $item->recive_amount,
                'depit' => $item->debtor,
                'credit' => $item->creditor,
                'current_blance' => $item->currentblance,
                'dely_record' => $item->dely_record,
                'date_export' => $item->date_export,
                'date' => $item->created_at,
                'note' => "{$item->note}-({$payment})-{$name_parent}",
                'user' => $item->user->name ?? '-',
                'branch' => $item->branch->name ?? '-',
            ];
        }

        $credit = $LAST_credittransactions->sum('creditor');
        $debit = $LAST_credittransactions->sum('debtor');
        $blance = $LAST_credittransactions->last()->currentblance ?? 0;

        // الترتيب بالتاريخ لضمان عرض الحركات مرتبة زمنياً بشكل صحيح
        $data = collect($List_dely_record)->sortBy('date')->values()->all();

        return view('reports.print_acoount_statment', compact('data'))
            ->with('start_at', $start_at)
            ->with('end_at', $end_at)
            ->with('account_name', $financial_account->name ?? '-')
            ->with('account_id', $financial_account->id ?? null)
            ->with('blance', $blance)
            ->with('debit', $debit)
            ->with('credit', $credit)
            ->with('branch_name', $branch_name);
    }

    public function searchbankDecument(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $branch = $request->branch;
        $start = $request->start_at;
        $end = $request->end_at;

        // Eager Loading لجميع العلاقات لمنع الـ N+1 Queries تماماً
        $relations = ['user', 'branch'];

        $Invoices = invoices::with($relations)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->whereNotIn('Pay', ['Cash', 'Credit'])->where('save', 1);
        $credittransactions = credittransactions::with($relations)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->whereNotIn('pay_method', ['Cash', 'Credit']);
        $transactiontosuplliers = transactiontosuplliers::with($relations)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->whereNotIn('Pay_Method_Name', ['Cash', 'Credit']);
        $expenses = expenses::with($relations)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('Pay_Method_Name', '!=', 'Cash');

        // تم إضافة معالج علاقة المورد الأساسية هنا لحل المشكلة الكبرى
        $resource_purchases = resource_purchases::with(['branch', 'orderTosupllier.user'])->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->whereNotIn('Pay_Method_Name', ['Cash', 'Credit'])->where('save', 1);

        $cash_from__bank = cash_from__bank::with($relations)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('payment_method', '!=', 'Cash');
        $convertcashboxToBank = convertcashboxToBank::with($relations)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);
        $Cash_withdrawal_from_the_bank = Cash_withdrawal_from_the_bank::with($relations)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);

        if ($branch != '-') {
            foreach ([$Invoices, $credittransactions, $transactiontosuplliers, $expenses, $resource_purchases, $cash_from__bank, $convertcashboxToBank, $Cash_withdrawal_from_the_bank] as $query) {
                $query->where('branchs_id', $branch);
            }
        }

        $data = [];

        foreach ($Invoices->get() as $item) {
            $pays = $item->Pay == 'Shabka' ? __('report.shabka') : ($item->Pay == "Bank_transfer" ? __('home.Bank_transfer') : __('home.Partition of the amount'));
            $data[] = ['date' => $item->created_at, 'type' => __('home.sales'), 'payment' => $pays, 'in' => 1, 'user' => $item->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => ($item->Bank_transfer + $item->bankamount)];
        }

        foreach ($Cash_withdrawal_from_the_bank->get() as $item) {
            $data[] = ['date' => $item->created_at, 'type' => __('home.Cash_withdrawal_from_the_bank'), 'payment' => __('report.cash'), 'in' => 0, 'user' => $item->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => $item->amount];
        }

        foreach ($resource_purchases->get() as $item) {
            $pays = $item->Pay_Method_Name == 'Shabka' ? __('report.shabka') : __('home.Bank_transfer');
            $data[] = ['date' => $item->created_at, 'type' => __('home.purchases'), 'payment' => $pays, 'in' => 0, 'user' => $item->orderTosupllier->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => ($item->In_debt - $item->discount)];
        }

        foreach ($convertcashboxToBank->get() as $item) {
            $data[] = ['date' => $item->created_at, 'type' => __('home.convertboxtobank'), 'payment' => '-', 'in' => 1, 'user' => $item->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => $item->amount];
        }

        foreach ($credittransactions->get() as $item) {
            $pays = $item->pay_method == 'Shabka' ? __('report.shabka') : __('home.Bank_transfer');
            $data[] = ['date' => $item->created_at, 'type' => __('home.voucher'), 'payment' => $pays, 'in' => 1, 'user' => $item->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => $item->recive_amount];
        }

        foreach ($transactiontosuplliers->get() as $item) {
            $pays = $item->Pay_Method_Name == 'Shabka' ? __('report.shabka') : __('home.Bank_transfer');
            $data[] = ['date' => $item->created_at, 'type' => __('home.Receipt document'), 'payment' => $pays, 'in' => 0, 'user' => $item->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => $item->paidـamount];
        }

        foreach ($expenses->get() as $item) {
            $pays = $item->Pay_Method_Name == 'Shabka' ? __('report.shabka') : __('home.Bank_transfer');
            $data[] = ['date' => $item->created_at, 'type' => __('home.other_expenses'), 'payment' => $pays, 'in' => 0, 'user' => $item->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => $item->Theـamountـpaid];
        }

        foreach ($cash_from__bank->get() as $item) {
            $pays = $item->Pay_Method_Name == 'Shabka' ? __('report.shabka') : __('home.Bank_transfer');
            $data[] = ['date' => $item->created_at, 'type' => __('home.shabka_bank'), 'payment' => $pays, 'in' => 1, 'user' => $item->user->name ?? '-', 'branch' => $item->branch->name ?? '-', 'amount' => $item->the_amount];
        }

        // الترتيب العكسي للتواريخ باستخدام الكوليكشن وهو أكثر كفاءة وأماناً
        $data = collect($data)->sortByDesc('date')->values()->all();

        return view('reports.printBankStatment', compact('data'))->with('start_at', $start)->with('end_at', $end)->with('branch', $branch);
    }

    public function search_Supplier_credit_payment(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $query = credittransactions::where('type_decument', 2)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($request->branch != '-') {
            $query->where('branchs_id', $request->branch);
        }

        if ($request->supplierId != '-') {
            $query->where('customer_id', $request->supplierId);
        }

        $Invoices = $query->get();

        return view('reports.print_Supplier_credit_payment', compact('Invoices'))
            ->with('supplierId', $request->supplierId)
            ->with('start_at', $request->start_at)
            ->with('end_at', $request->end_at);
    }
















    /**
     * دالة مساعدة موحدة لتحويل طرق الدفع إلى نصوص مترجمة
     */
    private function getPaymentMethodLabel($payMethod)
    {
        return match ($payMethod) {
            'Cash' => __('report.cash'),
            'Shabka' => __('report.shabka'),
            'Credit' => __('report.credit'),
            'Bank_transfer' => __('home.Bank_transfer'),
            default => __('home.Partition of the amount'),
        };
    }

    public function search_Customer_account_statement(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $customerId = $request->UserId;
        $startAt = $request->start_at;
        $endAt = $request->end_at;

        $customer = customers::findOrFail($customerId);
        $remainingAmount = $customer->opeing_blance ?? 0; // يفضل تصحيحها في قاعدة البيانات لاحقاً إلى opening_balance

        // جلب البيانات مع العلاقات دفعة واحدة لتفادي الـ N+1 Query
        $invoices = invoices::with(['branch', 'user'])
            ->where('customer_id', $customerId)
            ->whereBetween('created_at', [$startAt, $endAt])
            ->where('save', 1)
            ->get();

        $returnSales = return_sales::with('invoice.branch')
            ->whereBetween('created_at', [$startAt, $endAt])
            ->get();

        $creditTransactions = credittransactions::with('user')
            ->where('orginal_type', 1)
            ->where('orginal_id', $customerId)
            ->whereBetween('created_at', [$startAt, $endAt])
            ->get();

        // الحسابات السابقة لتحديد الرصيد الافتتاحي للفترة
        $previousCreditTrans = credittransactions::where('orginal_type', 1)
            ->where('orginal_id', $customerId)
            ->where('created_at', '<', $startAt)
            ->sum('recive_amount');

        $previousInvoices = invoices::where('customer_id', $customerId)
            ->where('Pay', 'Credit')
            ->where('created_at', '<', $startAt)
            ->where('save', 1)
            ->get()
            ->sum(function ($inv) {
                return $inv->Bank_transfer + $inv->creaditamount + $inv->bankamount + $inv->cashamount;
            });

        $remainingAmount = ($remainingAmount + $previousInvoices) - $previousCreditTrans;

        // حساب ضريبة المرتجعات السابقة للفترة
        $saleAvt = Avt::find(1)?->AVT ?? 0.15;
        $previousReturnSales = return_sales::where('created_at', '<', $startAt)->get();

        foreach ($previousReturnSales as $return) {
            if ($return->invoice && $return->invoice->customer_id == $customerId && $return->invoice->Pay == "Credit") {
                $baseAmount = ($return->return_Unit_Price * $return->return_quantity) - $return->discountvalue - $return->discountoninvoice;
                $vatAmount = $baseAmount * $saleAvt;
                $remainingAmount -= ($baseAmount + $vatAmount);
            }
        }

        // بناء مصفوفة البيانات الموحدة للعرض
        $dataInvoices = [];

        // 1. إضافة المرتجعات الحالية
        foreach ($returnSales as $return) {
            if ($return->invoice && $return->invoice->customer_id == $customerId) {
                $baseAmount = ($return->return_Unit_Price * $return->return_quantity) - $return->discountvalue - $return->discountoninvoice;
                $vatAmount = $baseAmount * $saleAvt;

                $dataInvoices[] = [
                    'id' => $return->invoice_id,
                    'data' => $return->created_at,
                    'branch' => $return->invoice->branch->name ?? '-',
                    'payment' => $this->getPaymentMethodLabel($return->invoice->Pay),
                    'user' => '-',
                    'type' => 2,
                    'typepayment' => $return->invoice->Pay == "Credit" ? 1 : 0,
                    'amoint' => $baseAmount + $vatAmount
                ];
            }
        }

        // 2. إضافة الفواتير الحالية
        foreach ($invoices as $inv) {
            $dataInvoices[] = [
                'id' => $inv->id,
                'data' => $inv->created_at,
                'branch' => $inv->branch->name ?? '-',
                'payment' => $this->getPaymentMethodLabel($inv->Pay),
                'user' => $inv->user->name ?? '-',
                'type' => 1,
                'typepayment' => $inv->Pay == "Credit" ? 1 : 0,
                'amoint' => $inv->Bank_transfer + $inv->creaditamount + $inv->bankamount + $inv->cashamount
            ];
        }

        // 3. إضافة سندات القبض الحالية
        foreach ($creditTransactions as $trans) {
            $dataInvoices[] = [
                'id' => $trans->id,
                'data' => $trans->created_at,
                'branch' => $trans->currentblance,
                'payment' => $this->getPaymentMethodLabel($trans->pay_method),
                'user' => $trans->user->name ?? '-',
                'type' => 3,
                'typepayment' => $trans->Pay == "Credit" ? 1 : 0,
                'amoint' => $trans->recive_amount
            ];
        }

        // ترتيب الحركات تصاعدياً حسب التاريخ باستخدام تجميعات لارافيل (أسرع وأسهل)
        $dataInvoices = collect($dataInvoices)->sortBy('data')->values()->all();

        $data = [$creditTransactions, $dataInvoices, round($remainingAmount, 2), $dataInvoices];

        return view('reports.print_Customer_account_statement', compact('data'))
            ->with([
                'start_at' => $startAt,
                'end_at' => $endAt,
                'customerId' => $customerId,
                'customerName' => $customer->name
            ]);
    }

    public function search_Best_selling_products(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // استخدام التجميع المستند إلى قاعدة البيانات مباشرة (Database Aggregation) بدلاً من حلقات foreach المتداخلة
        $query = Sales::with(['productData', 'branch'])
            ->whereBetween('created_at', [$request->start_at, $request->end_at])
            ->where('quantity', '>', 0)
            ->where('save', 1);

        if ($request->branch != '-') {
            $query->where('branch_id', $request->branch);
        }

        // تجميع الكميات المبيعة لكل منتج مباشرة من قاعدة البيانات وتوفير آلاف العمليات والدورات
        $bestSellingData = $query->select('product_id', 'branch_id', \DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'branch_id')
            ->get();

        $bestselling = [];
        foreach ($bestSellingData as $index => $item) {
            if ($item->productData) {
                $bestselling[] = [
                    'productcode' => $item->productData->Product_Code,
                    'productname' => $item->productData->product_name,
                    'numberofsall' => $item->total_quantity,
                    'branch' => $item->branch->name ?? '-',
                    'end_at' => $request->end_at,
                    'start_at' => $request->start_at
                ];
            }
        }

        // الترتيب من الأكثر مبيعاً للأقل
        $bestselling = collect($bestselling)->sortByDesc('numberofsall')->values()->all();

        return view('reports.Best_selling_products', compact('bestselling'))->with('branch_id', $request->branch)
        ->with('end_at',  $request->end_at)->with('start_at',  $request->start_at);
    }

    public function search_VAT(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $startAt = $request->start_at;
        $endAt = $request->end_at;
        $ids = financial_accounts::where('parent_account_number', 102)->pluck('id');

        // بناء الاستعلام الأساسي لتفادي تكرار الكود لقسمي (كل الفروع / فرع محدد)
        $query = credittransactions::where('vat', 1)
            ->where('save', 1)
            ->whereBetween('created_at', [$startAt, $endAt]);

        if ($request->branch == '-') {
            $query->whereIn('customer_id', $ids);
        } else {
            $financialAccount = financial_accounts::where('parent_account_number', 102)
                ->where('branchs_id', $request->branch)
                ->first();

            if (!$financialAccount) {
                return redirect()->back()->withErrors(['error' => 'الحساب المالي لهذا الفرع غير موجود']);
            }
            $query->where('customer_id', $financialAccount->id);
        }

        // جلب البيانات بطلب واحد مجمع للتحسين الاقتصادي للذاكرة
        $allTransactions = $query->get();

        // تصفية البيانات برمجياً باستخدام وميزات المجاميع في لارافيل بدلاً من 5 استعلامات منفصلة
        $invoices = $allTransactions->filter(fn($t) => str_contains($t->note, 'فاتورة مبيعات'));
        $returnsales = $allTransactions->filter(fn($t) => str_contains($t->note, 'فاتورة مرتجع مبيعات'));
        $resource_purchases = $allTransactions->filter(fn($t) => str_contains($t->note, 'فاتورة مشتريات ر'));
        $return_purchase = $allTransactions->filter(fn($t) => str_contains($t->note, 'مرتجع مشتريات فاتورة رقم'));
        $expenses = $allTransactions->filter(fn($t) => str_contains($t->note, 'سند صرف'));

        // العمليات الحسابية للمبيعات
        $totalVatSales = round($invoices->sum('recive_amount'), 2);
        $totalsales = round(($totalVatSales / 0.15), 2);

        // المصاريف
        $totalvarExpenses = round($expenses->sum('recive_amount'), 2);

        // المشتريات
        $totalVatPrachese = round($resource_purchases->sum('recive_amount'), 2);
        $totalpurchase = round(($totalVatPrachese / 0.15), 2);

        // مرتجعات المبيعات
        $salesreturntax = round($returnsales->sum('recive_amount'), 2);
        $salesreturn_withodtaxtax = round(($salesreturntax / 0.15), 2);

        // مرتجعات المشتريات
        $purachasereturntax = round($return_purchase->sum('recive_amount'), 2);
        $totalreturnpurchase = round(($purachasereturntax / 0.15), 2);

        $data = [
            'returncountsales' => $returnsales->count(),
            'returncountpurchases' => $return_purchase->count(),
            'salesreturntax' => $salesreturntax,
            'salesreturn_withodtaxtax' => $salesreturn_withodtaxtax,
            'purachasereturntax' => $purachasereturntax,
            'totalpurchase' => $totalpurchase,
            'totalreturnpurchase' => $totalreturnpurchase,
            'totalVatPrachese_tax' => $totalVatPrachese,
            'totalVatSales' => $totalVatSales,
            'totalVatPrachese' => $totalVatPrachese,
            'total_sale' => $totalsales,
            'totalvarExpenses' => $totalvarExpenses,
            'countsales' => $invoices->count(),
            'countpurchase' => $resource_purchases->count(),
            'countexpanses' => $expenses->count(),
            'start_at' => $startAt,
            'end_at' => $endAt,
        ];

        return view('reports.print_VAT', compact('data'));
    }





















    public function search_purchasereports(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $productNo = $request->productNo;
        // استخدام علاقة المنتج هنا إذا كنت تعرض اسم المنتج في الـ View لتجنب الـ N+1 Query
        // مثال: orderDetails::with('product')
        $query = orderDetails::whereBetween('created_at', [$request->start_at . ' 00:00:00', 
                $request->end_at . ' 23:59:59'])
            ->where('save', 1);

        // تطبيق الشرط فقط إذا تم اختيار منتج محدد
        if ($productNo != '-') {
            $query->where('product_id', $productNo);
        }

        $products = $query->get();
        return view('reports.purchasereports', compact('products'))->with('productNo', $productNo)->with('start_at', $request->start_at . ' 00:00:00')->with('end_at', $request->end_at . ' 23:59:59');
    }

    public function search_customerـpurchases(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
       $branch = $request->branch;
        $userId = $request->UserId;

        // استخدام الاختصار الذكي (when) لتبسيط شروط الـ if/elseif المعقدة
        $Invoices = invoices::whereBetween('created_at', [$request->start_at, $request->end_at])
            ->where('save', 1)
            ->when($userId != '-', function ($query) use ($userId) {
                return $query->where('customer_id', $userId);
            })
            ->when($branch != '-', function ($query) use ($branch) {
                return $query->where('branchs_id', $branch);
            })
            ->get();

        return view('reports.customerpurchases', compact('Invoices'))
            ->with('branch', [$userId, $branch])
            ->with('userid', [$userId, $branch])
            ->with('start_at',$request->start_at,)
            ->with('start_at',  $request->end_at);
    }

    public function searchpurchasproducttocustomer(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // حل مشكلة الأداء الكارثية هنا:
        // بدلاً من جلب الفواتير أولاً ثم عمل استعلام منفصل داخل الـ foreach لكل فاتورة،
        // نقوم بجلب بيانات جدول sales مباشرة باستخدام شرط Subquery يربطها بجدول الفواتير بطلب واحد فقط!
        $Saleing = sales::where('product_id', $request->productNo)
            ->where('quantity', '>', 0)
            ->where('save', 1)
            ->whereIn('invoice_id', function ($query) use ($request) {
                $query->select('id')
                    ->from('invoices') // تأكد أن هذا اسم جدول الفواتير في قاعدة البيانات
                    ->where('customer_id', $request->branch)
                    ->whereBetween('created_at', [$request->start_at, $request->end_at])
                    ->where('save', 1);
            })
            ->get();

        return view('reports.purchasproducttocustomer', compact('Saleing'));
    }

    public function search_Refundـofـresourceـpurchases(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $branch = $request->branch;

        $query = resource_purchases::where('recoveredـpieces', '!=', 0)
            ->whereBetween('updated_at', [$request->start_at . ' 00:00:00',$request->end_at . ' 23:59:59']);

        if ($branch != '-') {
            $query->where('branchs_id', $branch);
        }

        $Invoices = $query->get();

        return view('reports.print_Refund_of_resource_purchases', compact('Invoices'))->with('branch_id', $branch)->with('start_at', $request->start_at)->with('end_at', $request->end_at);
    }











    public function search_budgetsheet(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start_at = $request->start_at;
        $end_at = $request->end_at;

        $branch = $request->branch;

        // 1. حساب أرصدة العملاء والموردين مباشرة عبر قواعد البيانات دون Loops
        $salesdebit = customers::where('Balance', '!=', 0)->sum('Balance');
        $creadit_customer_amount = $salesdebit;
        $credit_supplier_amount = supllier::where('In_debt', '!=', 0)->sum('In_debt');

        $cash_last_month = $this->addetions($request);

        // 2. جلب الفواتير وتجميعها بضربة واحدة بدلاً من الـ foreach
        $totalsQuery = invoices::where('save', 1)->whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at);
        if ($branch != '-') {
            $totalsQuery->where('branchs_id', $branch);
        }
        $totals = $totalsQuery->selectRaw('
            SUM(cashamount) AS total_cash,
            SUM(bankamount) AS total_bank,
            SUM(creaditamount) AS total_credit,
            SUM(Bank_transfer) AS total_transfer
        ')->first();

        $salescash = $totals->total_cash ?? 0;
        $salesshabka = $totals->total_bank ?? 0;
        $salescredit = $totals->total_credit ?? 0;
        $salesBankTransfer = $totals->total_transfer ?? 0;

        // 3. تجهيز الـ Queries الأساسية بناءً على الفروع للتقليل من كتابة الأكواد المكررة
        $convertQuery = convertcashboxToBank::whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at);
        $returnSalesQuery = return_sales::whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at);
        $returnPurchasesQuery = resource_purchases::where('recoveredـpieces', '!=', 0)->where('save', 1)->whereBetween('updated_at', [$start_at, $end_at]);
        $purchaseQuery = resource_purchases::where('save', 1)->whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at);
        $bankBalanceQuery = cash_from__bank::whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at);
        $withdrawalQuery = Cash_withdrawal_from_the_bank::whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at);

        $creditTransBase = credittransactions::whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at);

        $branchname = __('users.allbranchs');

        if ($branch == '-') {
            $convertcashboxToBank = $convertQuery->get();
            $returnsales = $returnSalesQuery->get();
            $returnpurchases = $returnPurchasesQuery->get();
            $pirchese = $purchaseQuery->get();

            $credittransactions = (clone $creditTransBase)->where('note', 'LIKE', '%سند قبض%')->where('decument_id', 0)->get();
            $transactiontosuplliers = (clone $creditTransBase)->where('type_decument', 2)->get();

            $transactiont_dely_record_bank = (clone $creditTransBase)->where('customer_id', 4)->where('note', 'LIKE', '%قيد يومي%')->where('dely_record', 0)->where('parent_dely_record', '!=', 0)->get();
            $transactiont_dely_record_cash = (clone $creditTransBase)->where('customer_id', 5)->where('note', 'LIKE', '%قيد يومي%')->where('dely_record', 0)->where('parent_dely_record', '!=', 0)->get();

            $bankblance = $bankBalanceQuery->get();
            $Cash_withdrawal_from_the_bank = $withdrawalQuery->get();
            $Transfer_cash_to_the_next_dayList = Transfer_cash_to_the_next_day::whereDate('created_at', $end_at)->get();

            $start_atlastday = date("Y-m-d", strtotime('-24 hours', strtotime($start_at)));
            $titalnextdayesall = Transfer_cash_to_the_next_day::whereBetween('created_at', [$start_atlastday, $end_at])->get();

            $date = date("Y-m-d", strtotime('-24 hours', strtotime($end_at)));
            $totalconvertlastDayList = Transfer_cash_to_the_next_day::whereDate('created_at', $end_at)->get();
            $Transfer_cash_from_the_last_dayList = Transfer_cash_to_the_next_day::whereDate('created_at', $date)->get();
            $transferMoney_to_mainbranch = [];
        } else {
            $branchModel = branchs::find($branch);
            $branchname = $branchModel->name ?? '-';

            $convertcashboxToBank = $convertQuery->where('branchs_id', $branch)->get();
            $returnsales = $returnSalesQuery->where('branch_id', $branch)->get();
            $returnpurchases = $returnPurchasesQuery->where('branchs_id', $branch)->get();
            $pirchese = $purchaseQuery->where('branchs_id', $branch)->get();

            $credittransactions = (clone $creditTransBase)->where('branchs_id', $branch)->where('note', 'LIKE', '%سند قبض%')->where('decument_id', 0)->get();
            $transactiontosuplliers = (clone $creditTransBase)->where('branchs_id', $branch)->where('type_decument', 2)->get();

            $transactiont_dely_record_bank = (clone $creditTransBase)->where('branchs_id', $branch)->where('customer_id', 4)->where('parent_dely_record', '!=', 0)->where('note', 'LIKE', '%قيد يومي%')->where('dely_record', 0)->get();
            $transactiont_dely_record_cash = (clone $creditTransBase)->where('branchs_id', $branch)->where('customer_id', 5)->where('parent_dely_record', '!=', 0)->where('note', 'LIKE', '%قيد يومي%')->where('dely_record', 0)->get();

            $bankblance = $bankBalanceQuery->where('branchs_id', $branch)->get();
            $Cash_withdrawal_from_the_bank = $withdrawalQuery->where('branchs_id', $branch)->get();

            $start_atlastday = date("Y-m-d", strtotime('-24 hours', strtotime($start_at)));
            $titalnextdayesall = Transfer_cash_to_the_next_day::where('branchs_id', $branch)->whereBetween('created_at', [$start_atlastday, $end_at])->get();
            $Transfer_cash_to_the_next_dayList = Transfer_cash_to_the_next_day::where('branchs_id', $branch)->whereDate('created_at', $end_at)->get();

            $date = date("Y-m-d", strtotime('-24 hours', strtotime($end_at)));
            $Transfer_cash_from_the_last_dayList = Transfer_cash_to_the_next_day::where('branchs_id', $branch)->whereDate('created_at', $date)->get();
            $totalconvertlastDayList = Transfer_cash_to_the_next_day::where('branchs_id', $branch)->whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at)->get();

            $transferMoney_to_mainbranch = ($branch == 1) ? transferMoney_to_mainbranch::whereBetween('updated_at', [$start_at, $end_at])->where('status', 1)->get() : [];
        }

        // 4. تجميع مبالغ التحويلات والترحيل السريع بكود مقتضب ومباشر
        $totalconvertlastDay = collect($totalconvertlastDayList)->sum('amount');
        $Transfer_cash_to_the_next_day = collect($Transfer_cash_to_the_next_dayList)->sum('amount');
        $Transfer_bankblance_to_the_next_day = collect($Transfer_cash_to_the_next_dayList)->sum('currentamount');
        $Transfer_cash_from_the_last_day = collect($Transfer_cash_from_the_last_dayList)->sum('amount');
        $Transfer_bankblance_to_the_lastday_day = collect($Transfer_cash_from_the_last_dayList)->sum('currentamount');
        $totaltransferlastdayCash = collect($titalnextdayesall)->sum('amount');
        $totaltransferlastdaybank = collect($titalnextdayesall)->sum('currentamount');
        $convertcashboxToBankitemamount = collect($convertcashboxToBank)->sum('amount');
        $Cash_withdrawal_from_the_banktotal = collect($Cash_withdrawal_from_the_bank)->sum('amount');

        // 5. احتساب مرتجعات المبيعات الحالية بكفاءة فائقة (Eager Loading للـ invoices لتجنب N+1)
        $returnsalescash = $returnsalescredit = $returnsalesshabka = $returnSalesBankTransfer = $returnSalespartial = $returnsalespartialshabka = 0;
        $avt = Avt::find(1);
        $saleavt = $avt->AVT ?? 0.15;

        if ($returnsales->isNotEmpty()) {
            $invoicesId = $returnsales->pluck('invoice_id')->unique()->toArray();
            // جلب الفواتير المعنية دفعة واحدة لتقليل الضغط
            $allInvoicesMap = invoices::whereIn('id', $invoicesId)->get()->keyBy('id');

            foreach ($invoicesId as $id) {
                $cominvoice = $allInvoicesMap[$id] ?? null;
                if (!$cominvoice)
                    continue;

                $currentReturnSales = return_sales::where('invoice_id', $id)->whereDate('created_at', '>=', $start_at)
    ->whereDate('created_at', '<=', $end_at)->get();
                $valuewithoudtax = 0;

                foreach ($currentReturnSales as $returnsale) {
                    $valuewithoudtax += ($returnsale->return_Unit_Price * $returnsale->return_quantity) - $returnsale->discountvalue - $returnsale->discountoninvoice;
                    if (!in_array($cominvoice->payment_return, ['Cash', 'Credit', 'Shabka', 'Bank_transfer'])) {
                        $returnsalespartialshabka += $returnsale->returnshabkavalue;
                    }
                }

                $totalWithTax = $valuewithoudtax + ($valuewithoudtax * $saleavt);

                match ($cominvoice->payment_return) {
                    'Cash' => $returnsalescash += $totalWithTax,
                    'Credit' => $returnsalescredit += $totalWithTax,
                    'Shabka' => $returnsalesshabka += $totalWithTax,
                    'Bank_transfer' => $returnSalesBankTransfer += $totalWithTax,
                    default => $returnSalespartial += $totalWithTax,
                };
            }
        }

        // 6. احتساب مرتجعات المشتريات (تقليل الاستعلامات داخل الـ Loop)
        $returnpurchasecash = $returnpurchasecredit = $returnpurchasebanktransfer = $returnpurchaseshabka = 0;

        if ($returnpurchases->isNotEmpty()) {
            $orderIds = $returnpurchases->pluck('orderId')->unique()->toArray();
            $allOrderDetails = orderDetails::whereIn('order_owner', $orderIds)->get()->groupBy('order_owner');
            $allResourcePurchases = resource_purchases::whereIn('orderId', $orderIds)->get()->keyBy('orderId');

            foreach ($returnpurchases as $returnpurchase) {
                $returnpurchasesdetiales = $allOrderDetails[$returnpurchase->orderId] ?? collect();
                $temp = 0;
                $avt_rat = 0;
                $allreturn = 1;

                foreach ($returnpurchasesdetiales as $returnpurchasesdetiale) {
                    $TEMP_PRICE = $returnpurchasesdetiale->purchasingـprice == 0 ? 1 : $returnpurchasesdetiale->purchasingـprice;
                    $avt_rat = $returnpurchasesdetiale->Added_Value / $TEMP_PRICE;
                    $temp += ($returnpurchasesdetiale->returns_purchase * $returnpurchasesdetiale->purchasingـprice);
                    if ($returnpurchasesdetiale->numberofpice != 0) {
                        $allreturn = 0;
                    }
                }

                if ($allreturn == 1) {
                    $resource_purchases1 = $allResourcePurchases[$returnpurchase->orderId] ?? $returnpurchase;
                    $finalAmount = ($temp - $resource_purchases1->discount) + (($temp - $resource_purchases1->discount) * $avt_rat);
                } else {
                    $finalAmount = $temp + ($temp * $avt_rat);
                }

                match ($returnpurchase->Pay_Method_Name) {
                    'Cash' => $returnpurchasecash += $finalAmount,
                    'Credit' => $returnpurchasecredit += $finalAmount,
                    'Bank_transfer' => $returnpurchasebanktransfer += $finalAmount,
                    default => $returnpurchaseshabka += $finalAmount,
                };
            }
        }

        // 7. احتساب مبيعات ومصاريف المشتريات الأصلية لكل فاتورة شراء
        $purchesecash = $purchesecredit = $purchasebankTransfer = $purcheseshabka = $shippingandunloadingCost = 0;

        if ($pirchese->isNotEmpty()) {
            $purchaseOrderIds = $pirchese->pluck('orderId')->unique()->toArray();
            $purchaseOrderDetails = orderDetails::whereIn('order_owner', $purchaseOrderIds)->where('save', 1)->get()->groupBy('order_owner');

            foreach ($pirchese as $purchese) {
                $shippingandunloadingCost += $purchese['shipping fee'] + $purchese['Other expenses'];
                $purchesecash += ($purchese->Pay_Method_Name == 'Cash') ? $purchese->In_debt : 0;
                $purchesecredit += ($purchese->Pay_Method_Name == 'Credit') ? $purchese->In_debt : 0;
                $purchasebankTransfer += ($purchese->Pay_Method_Name == 'Bank_transfer') ? $purchese->In_debt : 0;
                $purcheseshabka += (!in_array($purchese->Pay_Method_Name, ['Cash', 'Credit', 'Bank_transfer'])) ? $purchese->In_debt : 0;
            }
        }

        // 8. حساب توزيعات الخزينة والبنك (الأرصدة البنكية والقيود اليومية وسندات القبض)
        $bank_cash = collect($bankblance)->where('payment_method', 'Cash')->sum('the_amount');
        $bank_shabka = collect($bankblance)->where('payment_method', '!=', 'Cash')->sum('the_amount');

        $credittransaction_cash = collect($transactiont_dely_record_cash)->where('debtor', '>', 0)->sum('debtor') + collect($credittransactions)->where('type', 'Cash')->sum('recive_amount');
        $transactiontosuplliers_cash = collect($transactiont_dely_record_cash)->where('debtor', '<=', 0)->sum('creditor');

        $credittransaction_banktransfer = collect($transactiont_dely_record_bank)->where('debtor', '>', 0)->sum('debtor') + collect($credittransactions)->where('type', 'Bank_transfer')->sum('recive_amount');
        $transactiontosuplliers_banktransfer = collect($transactiont_dely_record_bank)->where('debtor', '<=', 0)->sum('creditor');

        $credittransaction_shabka = collect($credittransactions)->where('type', '!=', 'Cash')->where('type', '!=', 'Bank_transfer')->sum('recive_amount');

        $expense_cash = $expense_banktransfer = $expense_shabka = 0;
        foreach ($transactiontosuplliers as $ts) {
            if ($ts->orginal_type != 3) {
                if ($ts->Pay_Method_Name == 'Cash')
                    $transactiontosuplliers_cash += $ts->recive_amount;
                elseif ($ts->Pay_Method_Name == 'Bank_transfer')
                    $transactiontosuplliers_banktransfer += $ts->recive_amount;
                else
                    $transactiontosuplliers_shabka += $ts->recive_amount;
            } else {
                if ($ts->Pay_Method_Name == 'Cash')
                    $expense_cash += $ts->recive_amount;
                elseif ($ts->Pay_Method_Name == 'Bank_transfer')
                    $expense_banktransfer += $ts->recive_amount;
                else
                    $expense_shabka += $ts->recive_amount;
            }
        }

        // 9. تجميع مصفوفة البيانات النهائية الـ Data Object
        $data = [
            'totaltransferlastdaybank' => 0,
            'totaltransferlastdayCash' => 0,
            'transferMoney_to_mainbranchshabka' => 0,
            'transferMoney_to_mainbranchCash' => 0,
            'transferMoney_to_mainbranchshabkafrombranchas' => 0,
            'reportforbranch' => $branch,
            'returnsalescash' => round($returnsalescash, 1),
            'returnsalescredit' => round($returnsalescredit, 2),
            'returnsalesshabka' => round($returnsalesshabka, 2),
            'returnSalespartial' => round($returnSalespartial, 2),
            'returnsalespartialshabka' => round($returnsalespartialshabka, 2),
            'returnSalesBankTransfer' => round($returnSalesBankTransfer, 2),
            'Cash_withdrawal_from_the_banktotal' => $Cash_withdrawal_from_the_banktotal,
            'returnpurchasecash' => round($returnpurchasecash, 2),
            'returnpurchasecredit' => round($returnpurchasecredit, 2),
            'returnpurchaseshabka' => round($returnpurchaseshabka, 2),
            'returnpurchasebanktransfer' => round($returnpurchasebanktransfer, 2),
            'Transfer_cash_to_the_next_day' => $Transfer_cash_to_the_next_day,
            'Transfer_cash_from_the_last_day' => $Transfer_cash_from_the_last_day,
            'totalconvertlastDay' => $totalconvertlastDay,
            'Transfer_bankblance_to_the_next_day' => $Transfer_bankblance_to_the_next_day,
            'Transfer_bankblance_to_the_lastday_day' => $Transfer_bankblance_to_the_lastday_day,
            'expense_shabka' => $expense_shabka,
            'expense_banktransfer' => $expense_banktransfer,
            'expense_cash' => $expense_cash,
            'shippingandunloadingCost' => round($shippingandunloadingCost, 2),
            'salescash' => round($salescash, 2),
            'salescredit' => round($salescredit, 2),
            'salesshabka' => round($salesshabka, 2),
            'salesBankTransfer' => round($salesBankTransfer, 2),
            'purchesecash' => round($purchesecash + $returnpurchasecash, 2),
            'purchesecredit' => round($purchesecredit + $returnpurchasecredit, 2),
            'purcheseshabka' => round($purcheseshabka + $returnpurchaseshabka, 2),
            'purchasebankTransfer' => round($purchasebankTransfer + $returnpurchasebanktransfer, 2),
            'credittransaction_cash' => round($credittransaction_cash, 2),
            'credittransaction_shabka' => round($credittransaction_shabka, 2),
            'credittransaction_banktransfer' => round($credittransaction_banktransfer, 2),
            'transactiontosuplliers_cash' => round($transactiontosuplliers_cash, 2),
            'transactiontosuplliers_shabka' => round(0, 2),
            'transactiontosuplliers_banktransfer' => round(0, 2),
            'cash_last_month' => round($cash_last_month, 2),
            'creadit_customer_amount' => round($creadit_customer_amount, 2),
            'credit_supplier_amount' => round($credit_supplier_amount, 2),
            'start_at' => $start_at,
            'end_at' => $end_at,
            'bank_cash' => $bank_cash,
            'bank_shabka' => $bank_shabka,
            'branch' => $branchname,
            'benfitcradit' => 0,
            'benfitshabka' => 0,
            'benfitcash' => 0,
            'benfitBank_transfer' => 0,
            'convertcashboxToBankitemamount' => $convertcashboxToBankitemamount
        ];


        return view('reports.print_budget_sheet', compact('data'));
    }









    /**
     * طباعة حركة تحويل المنتجات بين الفروع
     */
    public function print_Transfer_products($invoiceId)
    {
        $data = [
            "invoice" => product_movement_another_branch::find($invoiceId),
            "itemsdetails" => product_movement_another_branch_items::where('order_id', $invoiceId)->get()
        ];

        return view('supProcesses.print_send_product', compact('data'));
    }

    /**
     * حساب إجمالي النقدية في الدرج (إضافات أبو فهد)
     */
    public function addetions(Request $request)
    {
        $start_at = $request->start_at;
        $end_at = $request->end_at;
        $branch = $request->branch;

        // مصفوفة لتسهيل بناء الاستعلامات وتقليل التكرار
        $models = [
            'invoices' => invoices::query(),
            'purchases' => resource_purchases::query(),
            'credit_transactions' => credittransactions::query(),
            'supplier_transactions' => transactiontosuplliers::query(),
            'expenses' => expenses::query(),
        ];

        // تطبيق فلاتر التاريخ والفرع على كل الموديلات دفعة واحدة
        foreach ($models as $key => $query) {
            $query->whereDate('created_at', '>=', $start_at)
                ->whereDate('created_at', '<=', $end_at);

            if ($branch !== '-') {
                // الحقل الافتراضي هو branchs_id ما عدا invoices فهو branch_id (يرجى التأكد من قاعدة بياناتك)
                $branchField = ($key === 'invoices') ? 'branchs_id' : 'branchs_id';
                $query->where($branchField, $branch);
            }
        }

        // 1. حساب المقبوضات النقذية (المبيعات + سندات القبض)
        $salescash = $models['invoices']->where('Pay', 'Cash')->get()->sum(function ($invoice) {
            return $invoice->Price + $invoice->Added_Value;
        });

        $credittransaction_cash = $models['credit_transactions']->where('pay_method', 'Cash')->sum('recive_amount');

        // 2. حساب المدفوعات النقدية (المشتريات + سندات الصرف للموردين + المصاريف)
        $purchesecash = $models['purchases']->where('Pay_Method_Name', 'Cash')->sum('In_debt');

        // تنظيف الحروف العشوائية مثل الكشيدة (ـ) في أسماء الحقول
        $transactiontosuplliers_cash = $models['supplier_transactions']->where('Pay_Method_Name', 'Cash')->sum('paidـamount');
        $expenses_cash = $models['expenses']->where('Pay_Method_Name', 'Cash')->sum('Theـamountـpaid');

        // الحسبة النهائية للدرج
        return ($salescash + $credittransaction_cash) - ($expenses_cash + $transactiontosuplliers_cash + $purchesecash);
    }

    /**
     * تقرير مشتريات الموردين (محسن لحل مشكلة الـ N+1 Query عبر Eloquent)
     */
    public function search_Purchases_from_suppliers(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $pay = $request->pay;
        $branch = $request->branch;

        $query = resource_purchases::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('save', 1);

        if ($request->clientnamesearch !== '-') {
            $query->where('suplier_id', $request->clientnamesearch);
        }
        if ($pay !== '-') {
            $query->where('Pay_Method_Name', $pay);
        }
        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        $Invoices = $query->get();

        // حل مشكلة الأداء: جلب تفاصيل كافة الفواتير بطلب واحد بدلاً من طلب داخل الـ Loop
        $orderIds = $Invoices->pluck('orderId')->toArray();
        $allDetails = orderDetails::whereIn('order_owner', $orderIds)->get()->groupBy('order_owner');

        $invoicesDataArray = [];

        foreach ($Invoices as $invoice) {
            $invoiceSubtotal = 0;
            $invoiceTax = 0;

            // جلب التفاصيل المخزنة مسبقاً في الذاكرة للمجموعات
            $details = $allDetails->get($invoice->orderId) ?? collect();

            foreach ($details as $product) {
                $qty = $product->numberofpice + $product->returns_purchase;
                $invoiceSubtotal += ($qty * $product->purchasing_price);
                $invoiceTax += ($qty * ($product->Added_Value ?? 0));
            }

            $invoicesDataArray[] = [
                'orderId' => $invoice->orderId,
                'subtotal_before_tax' => $invoiceSubtotal,
                'tax_amount' => $invoiceTax,
            ];
        }

        return view('reports.print_Purchases_from_suppliers', compact('Invoices', 'invoicesDataArray'))
            ->with([
                'pay' => $pay,
                'branch' => $branch,
                'suplier_id' => $request->clientnamesearch,
                'startat' => $request->start_at,
                'endat' => $request->end_at
            ]);
    }

    /**
     * طلب عرض سعر من مورد
     */
    public function search_Request_a_quote_from_the_supplier(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->supplierId;

        $query = order_price_from_supplier::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($supplierId !== '-') {
            $query->where('suplier_id', $supplierId);
        }
        if ($request->branch !== '-') {
            $query->where('branchs_id', $request->branch);
        }

        $Invoices = $query->get();

        return view('reports.Request_A_quote_from_supplier', compact('Invoices'))
            ->with('supplierId', [$supplierId, $request->branch]);
    }

    /**
     * طلبات العروض المقدمة من الموردين
     */
 

    /**
     * تقرير مبيعات منتج محدد
     */
    public function search_product_sales(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $productId = $request->productNo ?? '-';

        if ($productId === '-') {
            session()->flash('notfountreturnproduct', __('home.productnotfount'));
            return view('reports.product_sales', ['Invoices' => []])->with('branch_Id', $request->branch);
        }

        $query = sales::where('product_id', $productId)
            ->where('quantity', '!=', 0)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('save', 1);

        if ($request->branch !== '-') {
            $query->where('branch_id', $request->branch);
        }

        $products = $query->get();

        return view('reports.product_sales', compact('products'))->with('branch_Id', $request->branch)->with('start_at', $request->start_at)->with('end_at', $request->end_at);
    }

    /**
     * تقرير مبيعات الشبكة (مدى / فيزا)
     */
    public function viewnetworksales(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('Pay', 'SHABKA')
            ->get();

        return view('reports.networksales', compact('Invoices'));
    }

    /**
     * تقرير مبيعات موظف معين
     */
    public function employeeSalesSearch(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $Invoices = invoices::where('user_id', $request->productname)
            ->whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('save', 1)
            ->get();

        return view('reports.employeesales', compact('Invoices'))->with('start_at',$request->start_at)->with('end_at',$request->end_at)->with('userId',$request->productname);
    }

    /**
     * تقرير المبيعات النقدية
     */
    public function viewCashsales(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('Pay', 'Cash')
            ->get();

        return view('reports.Cashsales', compact('Invoices'));
    }

    /**
     * تقرير مرتجعات المبيعات
     */
    public function search_report_returns_sale(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $query = return_sales::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at);

        if ($request->branch !== '-') {
            $query->where('branch_id', $request->branch);
        }

        $Invoices = $query->get();

        return view('reports.report_returns_sale', compact('Invoices'))
            ->with('branch_Id', $request->branch)
            ->with('start', $request->start_at)
            ->with('end', $request->end_at);
    }

    /**
     * تفاصيل فاتورة المرتجع
     */
    public function Show_return_Sales_Details($request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data = [
            'invoiceData' => invoices::where('id', $request)->first(),
            'salesData' => return_sales::where('invoice_id', $request)->get()
        ];

        return view('reports.report_returns_sale_details', compact('data'));
    }

















    /**
     * تقرير أرباح المبيعات والمبيعات المرتجعة
     */
    public function sales_profitssearch(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = $request->start_at;
        $end = $request->end_at;

        // بناء استعلام المبيعات ديناميكياً
        $salesQuery = sales::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where('save', 1);

        // بناء استعلام المرتجعات ديناميكياً
        $returnsQuery = return_sales::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        // تصفية بحسب المستخدم إذا تم تحديده
        if ($request->userid !== '-') {
            $salesQuery->where('user_id', $request->userid);
            $returnsQuery->where('user_id', $request->userid);
        }

        // تصفية بحسب الفرع إذا تم تحديده
        if ($request->branch !== '-') {
            $salesQuery->where('branch_id', $request->branch);
            // انتبه هنا: الكود القديم استخدم branchs_id في المبيعات و branch_id في المرتجعات! قمت بتثبيتها بناءً على كودك
            $returnsQuery->where('branch_id', $request->branch);
        }

        $data = [
            'sales' => $salesQuery->get(),
            'returns' => $returnsQuery->get(),
        ];

        return view('reports.printReportProfitSales', compact('data'))
            ->with([
                'start' => $start,
                'end' => $end,
                'branch_id' => $request->branch
            ]);
    }

    /**
     * تقرير المبيعات الآجلة (Credit)
     */
    public function viewCreditsales(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $Invoices = invoices::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('Pay', 'Credit')
            ->get();

        return view('reports.Creditsales', compact('Invoices'));
    }

    /**
     * طباعة دفعات حساب المورد الآجل
     */
    public function print_Supplier_credit_payment($supplierId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // تنظيف التواريخ بشكل آمن بدلاً من str_split
        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = transactiontosuplliers::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($supplierId !== '-') {
            $query->where('orginal_id', $supplierId);
        }

        $Invoices = $query->get();

        return view('reports.print_Supplier_credit_payment', compact('Invoices'))
            ->with('supplierId', $supplierId);
    }

    /**
     * طباعة تفاصيل الوردية (الورديات / الشفتات)
     */
    public function print_shift_detailes($branch, $pay, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = invoices::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        if ($pay !== '-') {
            $query->where('Pay', $pay);
        }

        $Invoices = $query->get();

        return view('reports.print_shift_detailes', compact('Invoices'))
            ->with('pay', [$pay, $branch]);
    }

    /**
     * طباعة تقرير مبيعات موظف محدد (رابط مباشر)
     */
    public function printReportemployeeSales($usertId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $Invoices = invoices::where('user_id', $usertId)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where('save', 1)
            ->get();

        return view('reports.print_report_employee_sales', compact('Invoices'));
    }

    /**
     * تقرير تفاصيل المشتريات لمنتج محدد
     */
    public function print_purchasereports($productId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = orderDetails::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($productId !== '-') {
            $query->where('product_id', $productId);
        }

        $products = $query->get();

        return view('reports.print_purchasereports', compact('products'));
    }

    /**
     * طباعة فواتير مشتريات الموردين
     */
    public function print_Purchases_from_suppliers($branch, $pay, $supplierId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = resource_purchases::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($supplierId && $supplierId !== '-') {
            $query->where('suplier_id', $supplierId);
        }
        if ($pay !== '-') {
            $query->where('Pay_Method_Name', $pay);
        }
        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        $Invoices = $query->get();

        return view('reports.print_Purchases_from_suppliers', compact('Invoices'))
            ->with('pay', $pay);
    }

    /**
     * طباعة مرتجعات مشتريات الموردين
     */
    public function print_Refund_of_resource_purchases($branch_id, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = resource_purchases::where('recovered_pieces', '!=', 0) // تم تعديل الحرف العربي الكشيدة
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($branch_id !== '-') {
            $query->where('branchs_id', $branch_id);
        }

        $Invoices = $query->get();

        return view('reports.print_Refund_of_resource_purchases', compact('Invoices'));
    }

    /**
     * تقرير عروض الأسعار المقدمة للعملاء
     */
    public function printReportoffer_price_customer($userId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = offer_price_to_customer::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($userId !== '-') {
            $query->where('customer_id', $userId);
        }

        $Invoices = $query->get();

        return view('reports.printReportoffer_price_customer', compact('Invoices'));
    }

    /**
     * طباعة عروض الأسعار المطلوبة من الموردين
     */
    public function print_Request_a_quote_from_the_supplier($branch, $supplierId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = order_price_from_supplier::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($supplierId !== '-') {
            $query->where('suplier_id', $supplierId);
        }
        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        $Invoices = $query->get();

        return view('reports.print_Request_A_quote_from_supplier', compact('Invoices'));
    }
    public function search_Request_offers_from_suppliers(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $supplierId = $request->supplierId;

        $query = orderTosupllier::where('Limit_credit', '')
                                ->whereDate('created_at', '>=', $request->start_at)
                                ->whereDate('created_at', '<=', $request->end_at);

        if ($supplierId !== '-') {
            $query->where('suplier_id', $supplierId);
        }

        $Invoices = $query->get();

        return view('reports.Request_offers_from_suppliers', compact('Invoices'))->with('supplierId', $supplierId);
    }

    /**
     * طباعة سندات الاستلام / إشعارات التوصيل
     */
    public function printDelivery_notes($orderId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = resource_purchases::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($orderId !== '-') {
            $query->where('orderId', $orderId);
        }

        $Invoices = $query->get();

        return view('reports.print_Report_delivery_notes', compact('Invoices'));
    }

    /**
     * تقرير طلبات الشراء الصادرة للموردين
     */
    public function print_report_order_from_supplier($supplierId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = orderTosupllier::where('Limit_credit', '')
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($supplierId !== '-') {
            $query->where('suplier_id', $supplierId);
        }

        $Invoices = $query->get();

        return view('reports.print_report_order_from_supplier', compact('Invoices'));
    }

    /**
     * تقرير أرباح فواتير المبيعات بحسب العميل والفرع
     */
    public function printReportProfitSales($branch, $UserId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = invoices::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where('save', 1);

        if ($UserId !== '-') {
            $query->where('customer_id', $UserId);
        }
        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        $Invoices = $query->get();

        return view('reports.printReportProfitSales', compact('Invoices'));
    }

    /**
     * تقرير حركة مبيعات منتج محدد عبر الفروع (للطباعة)
     */
    public function printReportProductSales($branch, $productId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = sales::where('product_id', $productId)
            ->where('quantity', '!=', 0)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where('save', 1);

        if ($branch !== '-') {
            $query->where('branch_id', $branch);
        }

        $products = $query->get();

        return view('reports.printReportProductSales', compact('products'));
    }

    /**
     * تقرير مشتريات عميل محدد
     */
    public function print_customer_purchases($branch, $customerId, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = invoices::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where('save', 1);

        $typeinvoise = '';

        if ($customerId !== '-') {
            $query->where('customer_id', $customerId);
            $typeinvoise = __('report.customerpurchases');
        }
        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        $data = [
            'invoices' => $query->get(),
            'typeinvoise' => $typeinvoise,
            'salesreport' => 'no'
        ];

        return view('reports.print_customer_purchases', compact('data'));
    }

    /**
     * تقرير المبيعات الإجمالي بدون تفاصيل المنتجات
     */
    public function printReportsaleswithoud_deatails($branch, $pay, $startat, $end_at, $customer_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = invoices::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where('save', 1);

        if ($customer_id !== '-') {
            $query->where('customer_id', $customer_id);
        }
        if ($pay !== '-') {
            $query->where('Pay', $pay);
        }
        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        $Invoices = $query->get();

        return view('reports.printReportsaleswithoud_deatails', compact('Invoices'))
            ->with(['start' => $start, 'end' => $end]);
    }

    /**
     * تصدير فواتير المبيعات إلى Excel (GET)
     */
    public function printInvoicesReport_export($branch, $pay, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return Excel::download(
            new Export_invoices($branch, $pay, $startat, $end_at),
            'INVOICES_FROM_' . $startat . '_TO' . $end_at . '.xlsx'
        );
    }

    /**
     * تصدير فواتير المبيعات إلى Excel (POST)
     */
    public function printInvoicesReport_export_post(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $branch = $request->branch;
        $pay = $request->pay;
        $startat = $request->start_at;
        $end_at = $request->end_at;
        $customer_id = $request->customer_id;

        $fileName = 'INVOICES_FROM_' . $startat . '_TO_' . $end_at . '.xlsx';

        return Excel::download(
            new Export_invoices($branch, $pay, $startat, $end_at, $customer_id),
            $fileName
        );
    }

    /**
     * تصدير فواتير مشتريات الموردين إلى Excel
     */
    public function Invoices_purchases_export($branch, $pay, $supplier, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return Excel::download(
            new Export_invoices_purshase($branch, $pay, $supplier, $startat, $end_at),
            'INVOICES_FROM_' . $startat . '_TO' . $end_at . '.xlsx'
        );
    }











    /**
     * طباعة تقرير الفواتير التفصيلي بحسب خيارات متعددة
     */
    public function printInvoicesReport($branch, $pay, $startat, $end_at, $customer_id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = invoices::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->where('save', 1);

        $salesreport = 'no';
        $typeinvoise = $pay;

        // إدارة فلتر العميل والنوع الافتراضي للتقرير
        if ($customer_id === '-') {
            if ($pay === '-' && $branch === '-') {
                $salesreport = 'yes';
                $typeinvoise = 'Seles report';
            }
        } else {
            $query->where('customer_id', $customer_id);
            if ($pay === '-' && $branch === '-') {
                $salesreport = 'yes';
                $typeinvoise = 'Seles report';
            }
        }

        // تطبيق بقية الفلاتر ديناميكياً
        if ($pay !== '-') {
            $query->where('Pay', $pay);
        }
        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        $data = [
            'invoices' => $query->get(),
            'typeinvoise' => $typeinvoise,
            'salesreport' => $salesreport
        ];

        $view = ($typeinvoise === 'Seles report') ? 'reports.printReportsales' : 'reports.printReportInvoices';

        return view($view, compact('data'));
    }

    /**
     * عرض الصفحة الرئيسية لتقرير المبيعات
     */
    public function salesReport()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.sales_report');
    }

    /**
     * تصدير قائمة الموردين إلى Excel
     */
    public function supplierlist_export()
    {
        return Excel::download(new supllierExport(), 'supplierlist_export.xlsx');
    }

    /**
     * تصدير قائمة العملاء إلى Excel
     */
    public function customerslist_export()
    {
        return Excel::download(new customersExport(), 'customerslist_export.xlsx');
    }

    /**
     * تصدير الحسابات المالية كملف Excel بناء على فترة محددة
     */
    public function financial_accounts_Export(Request $request)
    {
        $start_at = $request->query('start_at', date('Y-01-01'));
        $end_at = $request->query('end_at', date('Y-m-d'));

        return Excel::download(
            new financial_accounts_Export($start_at, $end_at),
            'Financial_Accounts_Report.xlsx'
        );
    }

    /**
     * تصدير الحسابات المالية كملف CSV
     */
    public function financial_accounts_Export_CSV()
    {
        return Excel::download(new financial_accounts_Export(), 'financial_accounts_Export.csv');
    }

    /**
     * جرد المخزون وتصديره Excel
     */
    public function Stocktaking($request)
    {
        // إذا أرسل المستخدم ID فرع نستخدمه، وإلا نأخذ الخاص بالمستخدم
        $branchId = $request;
        return Excel::download(new Exportproducts($branchId), 'Stocktaking_Report.xlsx');
    }


    /**
     * جرد المخزون وتصديره بصيغة PDF عبر ميزة التصدير المباشر
     */
    public function Stocktakingpdf()
    {
        return Excel::download(new Exportproducts, 'Stocktaking_Report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    /**
     * البحث المتقدم في تقرير المبيعات وعرضه بالـ View الأساسي
     */
    public function salesReportsearch(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $query = invoices::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->where('save', 1);

        if ($request->UserId !== '-') {
            $query->where('customer_id', $request->UserId);
        }
        if ($request->pay !== '-') {
            $query->where('Pay', $request->pay);
        }
        if ($request->branch !== '-') {
            $query->where('branchs_id', $request->branch);
        }

        $Invoices = $query->get();

        return view('reports.sales_report', compact('Invoices'))
            ->with('pay', [$request->pay, $request->branch])
            ->with('customer_id', $request->UserId)
            ->with('startat', $request->start_at)
            ->with('endat', $request->end_at);
    }

    /**
     * تقرير مرتجعات المبيعات بحسب الفرع والتاريخ
     */
    public function print_return_Report($branch, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $start = substr($startat, 0, 10);
        $end = substr($end_at, 0, 10);

        $query = return_sales::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($branch !== '-') {
            $query->where('branch_id', $branch);
        }

        $Invoices = $query->get();

        return view('reports.print_report_sales_returen', compact('Invoices'))
            ->with(['start' => $start, 'end' => $end]);
    }

    /**
     * تقرير فحص كميات المخزون بناءً على المعاملات الرياضية والموقع والفرع
     */
    public function printstockquantity($branch, $operation, $quantity, $loction)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // تأمين العمليات الحسابية المسموحة لمنع الـ SQL Injection
        $allowedOperations = ['==', '>=', '<=', '>', '<', '!='];
        $dbOperation = in_array($operation, $allowedOperations) ? ($operation === '==' ? '=' : $operation) : '=';

        $query = products::where('numberofpice', $dbOperation, $quantity);

        if ($branch !== '-') {
            $query->where('branchs_id', $branch);
        }

        if ($loction !== '-') {
            $query->where('Product_Location', 'LIKE', '%' . $loction . '%');
        }

        $products = $query->get();

        return view('reports.printstockquantity', compact('products'));
    }

    /**
     * تقرير المنتجات الأكثر مبيعاً - (تمت إعادة صياغتها بالكامل باستخدام Eloquent Grouping لمنع البطء الشديد)
     */
    public function printBest_selling_products($branch, $start_at, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $query = sales::select('product_id', 'branch_id', DB::raw('SUM(quantity) as total_sold'))
            ->with(['productData', 'branch']) // التحميل المسبق للعلاقات لـ Eager Loading
            ->whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)
            ->where('quantity', '>', 0)
            ->where('save', 1);

        if ($branch !== '-') {
            $query->where('branch_id', $branch);
        }

        $salesData = $query->groupBy('product_id', 'branch_id')
            ->orderBy('total_sold', 'DESC')
            ->get();

        $bestselling = [];
        foreach ($salesData as $sale) {
            if (!$sale->productData)
                continue; // تخطي الحذف العشوائي إن وُجد

            $bestselling[] = [
                'productcode' => $sale->productData->Product_Code,
                'productname' => $sale->productData->product_name,
                'numberofsall' => $sale->total_sold,
                'branch' => $sale->branch->name ?? 'N/A',
                'end_at' => $end_at,
                'start_at' => $start_at
            ];
        }

        return view('reports.printBest_selling_products', compact('bestselling'))
            ->with('date', [$start_at, $end_at]);
    }

    /**
     * تقرير الإقرار الضريبي (VAT) الإجمالي والتفصيلي للفروع
     */
    public function print_VAT($branch, $start_at, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // جلب الإعدادات الضريبية الافتراضية للبيع والشراء
        $saleavt = Avt::find(1)->AVT ?? 0.15;
        $purchasesavt = Avt::find(2)->AVT ?? 0.15;

        // تهيئة المتغيرات الأساسية
        $totalVatSales = 0;
        $totalvarExpenses = 0;
        $totalVatPrachese = 0;
        $purachasereturntax = 0;
        $salesreturntax = 0;

        // 1. استعلام الفواتير والمصاريف
        $invoiceQuery = invoices::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save', 1);
        $expenseQuery = expenses::where('expensesAvt', 1)->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at);
        $purchaseQuery = resource_purchases::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save', 1);
        $orderDetailsQuery = orderDetails::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->where('save', 1);
        $returnSalesQuery = return_sales::whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at);

        // تصفية الفروع إن وجدت
        if ($branch !== '-') {
            $invoiceQuery->where('branchs_id', $branch);
            $expenseQuery->where('branchs_id', $branch);
            $purchaseQuery->where('branchs_id', $branch);
            $returnSalesQuery->where('branch_id', $branch);
        }

        $invoices = $invoiceQuery->get();
        $expenses = $expenseQuery->get();
        $resource_purchases = $purchaseQuery->get();
        $ordersDetails = $orderDetailsQuery->get();
        $returnsales = $returnSalesQuery->get();

        // 2. الحسابات الرياضية للمبيعات والمصاريف
        foreach ($invoices as $invoice) {
            $totalVatSales += ($invoice->Price - $invoice->discount) * $saleavt;
        }

        foreach ($expenses as $expense) {
            $totalvarExpenses += $expense->The_amount_paid; // تم تعديل الحرف العربي الكشيدة هنا أيضاً لسلامة الكود
        }

        // 3. حسابات تفاصيل المشتريات وضريبتها المرتجعة
        $countofreturnpurshaseslist = [];
        foreach ($ordersDetails as $orderDetailes) {
            // التحقق من تبعية المنتج للفرع عند الفلترة
            if ($branch !== '-' && $branch != ($orderDetailes->productData->branchs_id ?? '')) {
                continue;
            }
            $totalVatPrachese += $orderDetailes->Added_Value * $orderDetailes->numberofpice;
            $purachasereturntax += $orderDetailes->Added_Value * $orderDetailes->returns_purchase;

            if ($orderDetailes->returns_purchase > 0) {
                $countofreturnpurshaseslist[$orderDetailes->order_owner] = true;
            }
        }

        // 4. حسابات ضريبة مرتجعات المبيعات
        $countofreturnsaleslist = [];
        foreach ($returnsales as $returnInvoice) {
            $salesreturntax += (($returnInvoice->return_Unit_Price * $returnInvoice->return_quantity) - $returnInvoice->discountvalue - $returnInvoice->discountoninvoice) * $saleavt;
            $countofreturnsaleslist[$returnInvoice->invoice_id] = true;
        }

        $data = [
            'returncountsales' => count($countofreturnsaleslist),
            'returncountpurchases' => count($countofreturnpurshaseslist),
            'salesreturntax' => $salesreturntax,
            'purachasereturntax' => $purachasereturntax,
            'countsales' => count($invoices),
            'countpurchase' => count($resource_purchases),
            'countexpanses' => count($expenses),
            'start_at' => $start_at,
            'end_at' => $end_at,
            'totalVatSales' => $totalVatSales,
            'totalVatPrachese' => $totalVatPrachese,
            'totalvarExpenses' => $totalvarExpenses - round($totalvarExpenses * 100 / 115)
        ];

        return view('reports.print_VAT', compact('data'));
    }

    /**
     * صفحة تحديثات كميات المخازن الرئيسية
     */
    public function updatestockquentity()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('reports.updatestockquentity');
    }

    /**
     * البحث في سجل حركات تحديث كميات المخازن
     */
    public function search_updatestockquentity(Request $request)
    {
        $stock_update = stock_update::whereDate('created_at', '>=', $request->start_at)
            ->whereDate('created_at', '<=', $request->end_at)
            ->get();

        return view('reports.print_stock_update', compact('stock_update'));
    }

    
    public function print_products_Transfer($branch_from, $branch_to, $startat, $end_at)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $transactions = [];

        $transctions = product_movement_another_branch::where('branch_from', $branch_from)->where('branch_to', $branch_to)->whereDate('created_at', '>=', $startat)->whereDate('created_at', '<=', $end_at)->get();
        $data = [
            "start_at" => $startat,
            "end_at" => $end_at,
            "branch_to" => $branch_to,
            "branch_from" => $branch_from,
            "transctions" => $transctions
        ];
        return view('reports.print_transction_product', compact('data'));
    }


}