<?php

namespace App\Http\Controllers;

use App\Models\dlivery;
use App\Models\products;
use App\Models\dlivery_items;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Models\Avt;
use App\Models\invoices;
use App\Models\sales;
use App\Models\financial_accounts;
use App\Models\credittransactions;
use App\Models\customers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class DliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function previousdelivers()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('previousdelivers');
    }
    
    public function getAlldeliversajax()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = dlivery::where('blance', '!=', 0)->orderBy('blance', 'desc')->paginate();
        return view('ajax_Recent_delivers', compact('data'));
    }
    
    public function getAlldeliversajaxbycustomer($id)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = dlivery::where('to_dlivery_id', $id)->orderBy('blance', 'desc')->paginate();
        return view('ajax_Recent_delivers', compact('data'));
    }

    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('products.deliver_to_anoter_supplier');
    }

    /**
     * تفقيط الأرقام وتحويلها إلى كلمات إنجليزية
     */
    private function convertNumber($num = false)
    {
        $num = str_replace(array(',', ''), '', trim($num));
        if (!$num) return false;
        
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '');
            } elseif ($tens >= 20) {
                $tens = (int)($tens / 10);
                $tens = ' and ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int)($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        }
        
        $words = implode(' ', $words);
        $words = preg_replace('/^\s\b(and)/', '', $words);
        return ucfirst(trim($words));
    }

/**
     * معالجة واعتماد فاتورة التسليم محاسبياً ومخزنياً
     */
    public function print_delivery_invoice(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        
        $avtSaleRate   = Avt::find(1);
        $invoiceNo     = $request->OrderNoprint;
        $paymentMethod = $request->pay_sale;
        $branchId      = auth()->user()->branchs_id;
        $userId        = auth()->id();
        $currentTime   = Carbon::now()->addHours(3);

        $InvoiceData   = invoices::findOrFail($invoiceNo);
        $saleData      = sales::with('productData')->where("invoice_id", $invoiceNo)->get();
        $customerId    = $InvoiceData->customer_id;
        $customerdata  = customers::find($customerId);

        // الحسابات المالية الأساسية للفاتورة
        $vatMultiplier         = 1 + $avtSaleRate->AVT; 
        $net_sales_without_vat = round($InvoiceData->Price - $InvoiceData->discount, 2); 
        $totAL                 = round($net_sales_without_vat * $vatMultiplier, 2); 
        $vat_value             = round($totAL - $net_sales_without_vat, 2); 

        // توزيع مبالغ طرق السداد
        $cashamount    = ($paymentMethod == 'Cash') ? $totAL : 0;
        $Bank_transfer = in_array($paymentMethod, ['Bank_transfer', 'Shabka']) ? $totAL : 0;
        $creaditamount = (!in_array($paymentMethod, ['Cash', 'Bank_transfer', 'Shabka'])) ? $totAL : 0;

        // بدء المعاملة المالية المؤمنة (تمت إضافة $InvoiceData بشكل صريح إلى الـ use)
        DB::transaction(function () use (
            $saleData, $customerId, $avtSaleRate, $invoiceNo, $totAL, $net_sales_without_vat, $vat_value,
            $cashamount, $Bank_transfer, $creaditamount, $paymentMethod, $branchId, $userId, $currentTime, $customerdata, $InvoiceData
        ) {
            
            $total_delivery_balance_deduction = 0;
            $total_delivery_items_deduction   = 0;
            $total_actual_cost                = 0; 

            foreach ($saleData as $item) {
                $total_price_without_tax = ($item->Unit_Price * $item->quantity) - $item->Discount_Value;
                $total_delivery_balance_deduction += ($total_price_without_tax * (1 + $avtSaleRate->AVT));
                $total_delivery_items_deduction   += $item->quantity;

                // جلب تكلفة المنتج بناءً على الهيكل الظاهر في phpMyAdmin
                // تم استخدام 'purchasing-price' كاسم حقل، أو يمكنك استبداله بـ 'average_cost' إذا كنت تعتمد المتوسط المرجح
                $productCost = isset($item->productData) ? ($item->productData->{'purchasing-price'} ?? $item->productData->average_cost) : $item->Unit_Price; 
                $total_actual_cost += ($productCost * $item->quantity);

                // تحديث تفاصيل أصناف التسليم المعلقة
                dlivery_items::where('states', 0)
                    ->where('supplier_id', $customerId)
                    ->where('product_id', $item->product_id)
                    ->where('created_at', $item->created_at)
                    ->update(['states' => 1]);
            }

            // تحديث جدول التسليم الرئيسي للعميل
            $dlivery = dlivery::where('to_dlivery_id', $customerId)->first();
            if ($dlivery) {
                $dlivery->update([
                    'blance'       => max(0, $dlivery->blance - $total_delivery_balance_deduction), 
                    'number_items' => max(0, $dlivery->number_items - $total_delivery_items_deduction),
                    'last_payment' => $currentTime,
                    'updated_at'   => $currentTime,
                ]);
            }

            // تحديث حالة أصناف المبيعات إلى "محفوظة"
            sales::where("invoice_id", $invoiceNo)->update(['save' => 1]);

            // معالجة الحساب رقم 5 (الصندوق الرئيسي وصندوق الفرع)
            if ($cashamount > 0) {
       
                $subAcc5 = financial_accounts::where('parent_account_number', 5)->where('branchs_id', $branchId)->first();
                if ($subAcc5) {
                    $subAcc5->update([
                        'current_balance' => $subAcc5->current_balance + $cashamount,
                        'debtor_current'  => $subAcc5->debtor_current + $cashamount,
                    ]);
                    $this->createTransaction($userId, $subAcc5->id, $cashamount, $branchId, $paymentMethod, $invoiceNo, $subAcc5->current_balance, 0, $cashamount, $currentTime);
                }
            }

            // معالجة الحساب رقم 4 (البنك وشبكة التحويل)
            if ($Bank_transfer > 0) {
           
                $subAcc4 = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchId)->first();
                if ($subAcc4) {
                    $subAcc4->update([
                        'current_balance' => $subAcc4->current_balance + $Bank_transfer,
                        'debtor_current'  => $subAcc4->debtor_current + $Bank_transfer,
                    ]);
                    $this->createTransaction($userId, $subAcc4->id, $Bank_transfer, $branchId, $paymentMethod, $invoiceNo, $subAcc4->current_balance, 0, $Bank_transfer, $currentTime);
                }
            }

            // معالجة حساب مصلحة الضرائب والزكاة (حساب 102)
    
            $subAcc102 = financial_accounts::where('parent_account_number', 102)->where('branchs_id', $branchId)->first();
            if ($subAcc102) {
                $subAcc102->update([
                    'current_balance'  => $subAcc102->current_balance - $vat_value,
                    'creditor_current' => $subAcc102->creditor_current + $vat_value,
                ]);
                $this->createTransaction($userId, $subAcc102->id, $vat_value, $branchId, $paymentMethod, $invoiceNo, $subAcc102->current_balance, $vat_value, 0, $currentTime, 1, $customerdata);
            }

            // معالجة حساب المبيعات (حساب 112)
     
            $subAcc112 = financial_accounts::where('parent_account_number', 112)->where('branchs_id', $branchId)->first();
            if ($subAcc112) {
                $subAcc112->update([
                    'current_balance'  => $subAcc112->current_balance - $net_sales_without_vat,
                    'creditor_current' => $subAcc112->creditor_current + $net_sales_without_vat,
                ]);
                $this->createTransaction($userId, $subAcc112->id, $net_sales_without_vat, $branchId, $paymentMethod, $invoiceNo, $subAcc112->current_balance, $net_sales_without_vat, 0, $currentTime);
            }

            // معالجة حساب تكلفة البضاعة المباعة (حساب 183)
    
            $subAcc183 = financial_accounts::where('parent_account_number', 183)->where('branchs_id', $branchId)->first();
            if ($subAcc183) {
                $subAcc183->update([
                    'current_balance' => $subAcc183->current_balance + $total_actual_cost,
                    'debtor_current'  => $subAcc183->debtor_current + $total_actual_cost,
                ]);
                $this->createTransaction($userId, $subAcc183->id, $total_actual_cost, $branchId, $paymentMethod, $invoiceNo, $subAcc183->current_balance, 0, $total_actual_cost, $currentTime);
            }

            // معالجة حساب المخزون السلعي (حساب 181)
      
            $subAcc181 = financial_accounts::where('parent_account_number', 181)->where('branchs_id', $branchId)->first();
            if ($subAcc181) {
                $subAcc181->update([
                    'current_balance'  => $subAcc181->current_balance - $total_actual_cost,
                    'creditor_current' => $subAcc181->creditor_current + $total_actual_cost,
                ]);
                $this->createTransaction($userId, $subAcc181->id, $total_actual_cost, $branchId, $paymentMethod, $invoiceNo, $subAcc181->current_balance, $total_actual_cost, 0, $currentTime);
            }

            // معالجة حساب العميل في حالة البيع الآجل (Credit)
            if ($creaditamount > 0 && $customerdata) {
                $customerdata->increment('Balance', $creaditamount);

                $custAcc = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
                if ($custAcc) {
                    $custAcc->update([
                        'current_balance' => $custAcc->current_balance + $creaditamount,
                        'debtor_current'  => $custAcc->debtor_current + $creaditamount,
                    ]);
                    $this->createTransaction($userId, $custAcc->id, $creaditamount, $branchId, $paymentMethod, $invoiceNo, $custAcc->current_balance, 0, $creaditamount, $currentTime);
                }
            }

            // تحديث مستند الفاتورة النهائي
            $InvoiceData->update([
                'save'          => 1,
                'Pay'           => $paymentMethod,
                'cashamount'    => $cashamount,
                'bankamount'    => ($paymentMethod == 'Shabka') ? $totAL : 0,
                'creaditamount' => $creaditamount,
                'Bank_transfer' => ($paymentMethod == 'Bank_transfer') ? $totAL : 0,
            ]);
        });

        $updatedInvoice = invoices::find($invoiceNo);

        $data = [
            "invoicetotal_price"      => $updatedInvoice->Price - $updatedInvoice->discount,
            "invoicetotal_addedvalue" => ($updatedInvoice->Price - $updatedInvoice->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount"   => $updatedInvoice->discount,
            'salesData'               => $saleData,
            'invoiceData'             => $updatedInvoice,
            'totatextlriyales'        => '',
            'totatextlrihalala'       => '',
        ];

        return view('products.printInvoicesReturnToClientRecentSales', compact('data'));
    }



    /**
     * دالة مساعدة لتوليد السجلات الائتمانية والمالية (Refactoring لقصر الكود)
     */
    private function createTransaction($userId, $accountId, $amount, $branchId, $method, $invoiceId, $currentBalance, $creditor, $debtor, $time, $vat = 0, $customer = null)
    {
        credittransactions::create([
            'user_id'          => $userId,
            'customer_id'      => $accountId,
            'recive_amount'    => $amount,
            'branchs_id'       => $branchId,
            'pay_method'       => $method,
            'note'             => 'فاتورة مبيعات رقم :' . (string) $invoiceId,
            'currentblance'    => $currentBalance,
            'Pay_Method_Name'  => $method,
            'created_at'       => $time,
            'updated_at'       => $time,
            'orginal_id'       => 0,
            'creditor'         => $creditor,
            'debtor'           => $debtor,
            'vat'              => $vat,
            'name'             => $customer ? $customer->name : null,
            'tax'              => $customer ? $customer->tax_no : null,
        ]);
    }





    public function confirmdelivery()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('products.confirmdelivery');
    }

    /**
     * Confirms delivery items and converts them into an active sales invoice.
     */
    public function getitems($id)
    {
        return DB::transaction(function () use ($id) {
            $supplier_delivery = dlivery::where('to_dlivery_id', $id)->firstOrFail();
            $itemsRequest = dlivery_items::where('states', 0)->where('supplier_id', $id)->get();
            $avtSaleRate = Avt::find(1);

            // Dynamically calculate base price from total balance and tax rate
            $vatMultiplier = 1 + ($avtSaleRate->AVT); // e.g., 1.15 if AVT is 0.15
            $basePrice = $supplier_delivery->blance / $vatMultiplier;
            $addedValue = $basePrice * $avtSaleRate->AVT;

            $confirminvoice = invoices::create([
                'save' => 0,
                'customer_id' => $supplier_delivery->to_dlivery_id,
                'user_id' => Auth::id(),
                'Price' => $basePrice,
                'Added_Value' => $addedValue,
                'Pay' => 'Cash',
                'status' => 0,
                'branchs_id' => Auth::user()->branch->id,
                'discountOnProduct' => 0,
                'discount' => 0,
                'Number_of_Quantity' => $supplier_delivery->number_items,
                'note' => $supplier_delivery->note ?? '-',
                'morepayment_way' => 1,
                'cashamount' => $supplier_delivery->blance,
                'bankamount' => 0,
                'creaditamount' => 0,
                'Bank_transfer' => 0,
            ]);

            $discountonproduct = 0;
            $listProducts = [];

            foreach ($itemsRequest as $index => $item) {
                $discountonproduct += $item->discount;

                $sales = sales::create([
                    'save' => 1,
                    'product_id' => $item->productData->id,
                    'invoice_id' => $confirminvoice->id,
                    'branch_id' => Auth::user()->branch->id,
                    'Discount_Value' => $item->discount,
                    'Added_Value' => $item->Added_value,
                    'Unit_Price' => $item->cost,
                    'reamingQuantity' => 0,
                    'quantity' => $item->quantity,
                    'created_at' => $item->created_at,
                ]);

                $listProducts[] = $this->transformItemToArray($index + 1, $item, $confirminvoice->id, $sales->id);
            }

            // Sync invoice total discounts
            $confirminvoice->update([
                'discountOnProduct' => $discountonproduct,
                'discount' => $discountonproduct,
            ]);

            return $listProducts;
        });
    }

    /**
     * Removes an item from an active delivery confirmation receipt and re-balances the invoice.
     */
    public function deleteitemdeliveryconfirm($id)
    {
        return DB::transaction(function () use ($id) {
            $salesdelete = sales::findOrFail($id);
            $datainvoice = invoices::findOrFail($salesdelete->invoice_id);
            $avtSaleRate = Avt::find(1);

            // Safely calculate the updated price differences
            $removedItemCost = ($salesdelete->quantity * $salesdelete->Unit_Price) - $salesdelete->Discount_Value;
            $totalprice = $datainvoice->Price - $removedItemCost;
            $updatedTax = $totalprice * $avtSaleRate->AVT;

            $datainvoice->update([
                'Price' => $totalprice,
                'Added_Value' => $updatedTax,
                'discountOnProduct' => $datainvoice->discountOnProduct - $salesdelete->Discount_Value,
                'discount' => $datainvoice->discount - $salesdelete->Discount_Value,
                'Number_of_Quantity' => $datainvoice->Number_of_Quantity - $salesdelete->quantity,
                'cashamount' => $totalprice + $updatedTax,
            ]);

            $salesdelete->delete();

            $listProducts = [];
            $itemsRequest = sales::where('invoice_id', $salesdelete->invoice_id)->get();

            foreach ($itemsRequest as $index => $item) {
                $listProducts[] = [
                    "count" => $index + 1,
                    "productCode" => $item->productData->Product_Code,
                    "productName" => $item->productData->product_name,
                    "sale_price" => $item->Unit_Price,
                    "discount" => $item->Discount_Value,
                    "order_id" => $salesdelete->invoice_id,
                    "quantity" => $item->quantity,
                    "added_value" => $item->Added_Value,
                    'id' => $item->id,
                    'invoiceId' => $datainvoice->id,
                    "Product_Location" => $item->productData->Product_Location,
                ];
            }

            return $listProducts;
        });
    }

    /**
     * Stores pending delivery staging items and updates inventory metrics.
     */
    public function store(Request $request)
    {
        // Add basic validation to prevent script crashes
        $request->validate([
            'clientnamesearch' => 'required',
            'productNo' => 'required|exists:products,id',
            'quentity' => 'required|numeric|min:1',
            'saleprice' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($request) {
            $avtSaleRate = Avt::find(1);

            $supplier_delivery = dlivery::firstOrCreate(
                ['to_dlivery_id' => $request->clientnamesearch],
                [
                    'supplier_id' => $request->clientnamesearch,
                    'blance' => 0,
                    'number_items' => 0,
                    'last_payment' => Carbon::now(),
                    'note' => $request->notes,
                ]
            );

            $total_price_without_tax = ($request->saleprice * $request->quentity) - $request->discount;
            $taxAmount = $total_price_without_tax * $avtSaleRate->AVT;
            $lineTotalWithTax = $total_price_without_tax + $taxAmount;

            $supplier_delivery->increment('blance', $lineTotalWithTax);
            $supplier_delivery->increment('number_items', $request->quentity);
            $supplier_delivery->update([
                'note' => $request->notes,
                'last_payment' => Carbon::now()
            ]);

            $productdata = products::findOrFail($request->productNo);
            $productdata->decrement('numberofpice', $request->quentity);

            dlivery_items::create([
                'to_dlivery_id' => $request->clientnamesearch,
                'product_id' => $request->productNo,
                'product_name' => '-',
                'quantity' => $request->quentity,
                'Added_value' => ($request->saleprice * $avtSaleRate->AVT),
                'states' => 0,
                'cost' => $request->saleprice,
                'supplier_id' => $request->clientnamesearch,
                'discount' => $request->discount,
            ]);

            return $this->getcustomerproductsdelivery($request->clientnamesearch);
        });
    }

    public function print_delivery_to_anoter_supplier(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = [
            'items' => dlivery_items::where('supplier_id', $request->OrderNoprint)->where('states', 0)->get(),
            'supplier' => dlivery::where('to_dlivery_id', $request->OrderNoprint)->firstOrFail()
        ];
        return view('products.print_order_perice_to_dlivery', compact('data'));
    }

    /**
     * Modifies quantities and calculations for temporary delivery staging lines.
     */
    public function updateproductallDatadelivery(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $avtSaleRate = Avt::find(1);
            $dlivery_items = dlivery_items::findOrFail($request->id);
            $dlivery = dlivery::where('to_dlivery_id', $dlivery_items->supplier_id)->firstOrFail();

            // Reverse old totals
            $old_total_without_tax = ($dlivery_items->cost * $dlivery_items->quantity) - $dlivery_items->discount;
            $old_total_with_tax = $old_total_without_tax * (1 + $avtSaleRate->AVT);
            
            $dlivery->decrement('blance', $old_total_with_tax);
            $dlivery->decrement('number_items', $dlivery_items->quantity);

            // Apply new totals
            $new_total_without_tax = ($request->price * $request->quentity) - $request->discount;
            $new_total_with_tax = $new_total_without_tax * (1 + $avtSaleRate->AVT);

            $dlivery->increment('blance', $new_total_with_tax);
            $dlivery->increment('number_items', $request->quentity);

            // Re-adjust product warehouse stock
            $productdata = products::findOrFail($dlivery_items->product_id);
            $productdata->increment('numberofpice', $dlivery_items->quantity);
            $productdata->decrement('numberofpice', $request->quentity);

            $dlivery_items->update([
                'quantity' => $request->quentity,
                'Added_value' => ($request->price * $avtSaleRate->AVT),
                'cost' => $request->price,
                'discount' => $request->discount,
            ]);

            return $this->getcustomerproductsdelivery($dlivery_items->supplier_id);
        });
    }

    public function getcustomerproductsdelivery($dliveryID)
    {
        $itemsRequest = dlivery_items::where('states', 0)->where('supplier_id', $dliveryID)->get();
        $listProducts = [];

        foreach ($itemsRequest as $index => $item) {
            $listProducts[] = $this->transformItemToArray($index + 1, $item, $item->supplier_id);
        }
        return $listProducts;
    }

    public function deleteitemdelivery($dliveryID)
    {
        return DB::transaction(function () use ($dliveryID) {
            $avtSaleRate = Avt::find(1);
            $dlivery_items = dlivery_items::findOrFail($dliveryID);
            $dlivery = dlivery::where('to_dlivery_id', $dlivery_items->supplier_id)->firstOrFail();

            $total_price_without_tax = ($dlivery_items->cost * $dlivery_items->quantity) - $dlivery_items->discount;
            $total_price_with_tax = $total_price_without_tax * (1 + $avtSaleRate->AVT);

            $dlivery->decrement('blance', $total_price_with_tax);
            $dlivery->decrement('number_items', $dlivery_items->quantity);

            $productdata = products::findOrFail($dlivery_items->product_id);
            $productdata->increment('numberofpice', $dlivery_items->quantity);

            $supplierId = $dlivery_items->supplier_id;
            $dlivery_items->delete();

            return $this->getcustomerproductsdelivery($supplierId);
        });
    }

    /**
     * Helper to map repetitive delivery response objects cleanly.
     */
    private function transformItemToArray($count, $item, $orderId, $salesId = null)
    {
        return [
            "count" => $count,
            "productCode" => $item->productData->Product_Code ?? null,
            "productName" => $item->productData->product_name ?? null,
            "Product_Location" => $item->productData->Product_Location ?? null,
            "sale_price" => $item->cost ?? $item->Unit_Price,
            "discount" => $item->discount ?? $item->Discount_Value,
            "order_id" => $orderId,
            "quantity" => $item->quantity,
            "added_value" => $item->Added_value ?? $item->Added_Value,
            'id' => $salesId ?? $item->id,
            'invoiceId' => $orderId
        ];
    }


}
















