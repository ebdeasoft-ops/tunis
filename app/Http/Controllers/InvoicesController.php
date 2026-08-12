<?php



namespace App\Http\Controllers; // تأكد من وجود الـ namespace الخاص بك هنا

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage; // يُفضل استخدام الـ Facade كاملاً
use Barryvdh\DomPDF\Facade\Pdf; // أو الكلاس المستعار الخاص بالـ PDF لديك
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use DOMDocument;

use DateTime;
use Ramsey\Uuid\Uuid;
use Auth;
use Illuminate\Http\Request;
// Laravel Localization

// Helpers & Services
use Hassanhelfi\NumberToArabic\NumToArabic;
use App\Services\Zatca\QRCode;
use App\Services\Zatca\QRCodeString;
use App\Services\Zatca\ZatcaConfig;

// ZATCA Invoice Services
use App\Services\Zatca\Invoice\Client;
use App\Services\Zatca\Invoice\Supplier;
use App\Services\Zatca\Invoice\Delivery;
use App\Services\Zatca\Invoice\PaymentType;
use App\Services\Zatca\Invoice\PIH;
use App\Services\Zatca\Invoice\ReturnReason;
use App\Services\Zatca\Invoice\BillingReference;
use App\Services\Zatca\Invoice\AdditionalDocumentReference;
use App\Services\Zatca\Invoice\LegalMonetaryTotal;
use App\Services\Zatca\Invoice\TaxesTotal;
use App\Services\Zatca\Invoice\TaxSubtotal;
use App\Services\Zatca\Invoice\LineTaxCategory;
use App\Services\Zatca\Invoice\InvoiceLine;
use App\Services\Zatca\Invoice\AllowanceCharge;
use App\Services\Zatca\Invoice\InvoiceGenerator;

// Models
use App\Models\Avt;
use App\Models\invoices;
use App\Models\products;
use App\Models\sales;
use App\Models\settings;
use App\Models\supllier; // تنبيه: تصحيح إملائي محتمل (supplier)
use App\Models\customers;
use App\Models\temp_sales;
use App\Models\return_sales;
use App\Models\temp_invoice;
use App\Models\orderDetails;
use App\Models\system_setting;
use App\Models\resource_purchases;
use App\Models\financial_accounts;
use App\Models\credittransactions;
use App\Models\products_mix_items;
use App\Models\sales_withoud_taxes;
use App\Models\return_sales_deliverys;
use App\Models\transactiontosuplliers;
use App\Models\offer_price_to_customer;
use App\Models\offer_price_to_customer_items;
use App\Models\Delivery_product_to_the_customer;
use App\Models\delivery_to_customer_withoud_tax_invoices;
use Carbon\Carbon;

class NumberToWord
{
    public $and = ' و';

    public function __construct() {}

    public function convert($number)
    {
        // إزالة الفراغات وتحويل المدخل إلى نص رقمي نظيف
        $number = trim((string)$number);

        if (empty($number) || !is_numeric($number) || $number == 0) {
            return 'صفر';
        }

        $_number = '';
        $length = strlen($number);

        if ($length <= 2) {
            $_number .= $this->twoLength($number);
        } elseif ($length == 3) {
            $_number .= $this->threeLength($number);
        } elseif ($length == 4) {
            $_number .= $this->moreThanThreeLength(substr($number, 0, 1), 4) . $this->and . $this->threeLength(substr($number, 1, 3), true);
        } elseif ($length == 5) {
            $_number .= $this->moreThanThreeLength(substr($number, 0, 2), 5) . $this->and . $this->threeLength(substr($number, 2, 3), true);
        } elseif ($length == 6) {
            $_number .= $this->moreThanThreeLength(substr($number, 0, 3), 6) . $this->and . $this->threeLength(substr($number, 3, 3), true);
        } elseif ($length == 7) {
            $_number .= $this->moreThanThreeLength(substr($number, 0, 1), 7) . $this->and . $this->threeLength(substr($number, 1, 3), true) . ' ' . $this->nameByDigit(6) . $this->and . $this->threeLength(substr($number, 4, 3));
        }

        // تنظيف الواو الزائدة وتنسيق المسافات
        return trim(preg_replace('/\s+/', ' ', $_number), $this->and . ' ');
    }

    private function oneLength($number)
    {
        return $this->forOneToTwenty((int)$number);
    }

    private function twoLength($number)
    {
        $number = (int)$number;
        if ($number <= 20) {
            return $this->forOneToTwenty($number);
        }

        $_number = null;
        $ones = $number % 10;
        $tens = (int)($number / 10);

        if ($ones > 0) {
            $_number .= $this->forOneToTwenty($ones) . $this->and;
        }
        $_number .= $this->betweenTwentyAndHundred($tens);

        return $_number;
    }

    private function threeLength($number, $isSub = false)
    {
        $number = str_pad($number, 3, '0', STR_PAD_LEFT);
        $_number = null;
        $firstNumber = (int)substr($number, 0, 1);
        $remainder = (int)substr($number, 1, 2);

        if ($firstNumber > 0) {
            $_number .= $this->moreThanHundred($firstNumber);
        }

        if ($remainder > 0) {
            if ($_number !== null) {
                $_number .= $this->and;
            }
            $_number .= $this->twoLength($remainder);
        }

        return $_number;
    }

    private function moreThanThreeLength($number, $length)
    {
        $_number = '';
        $number = (int)$number;

        switch ($length) {
            case 4: // ألوف أحاد
                if ($number == 1) return 'ألف';
                if ($number == 2) return 'ألفان';
                return $this->forOneToTwenty($number) . ' ' . $this->nameByDigit(4);

            case 5: // عشرات الألوف
                return $this->twoLength($number) . ' ' . $this->nameByDigit(6);

            case 6: // مئات الألوف
                return $this->threeLength($number) . ' ' . $this->nameByDigit(6);

            case 7: // ملايين
                if ($number == 1) return 'مليون';
                if ($number == 2) return 'مليونان';
                return $this->forOneToTwenty($number) . ' ' . $this->nameByDigit(7);

            default:
                return $_number;
        }
    }

    private function betweenTwentyAndHundred($digit)
    {
        $numbers = [
            2 => 'عشرون',
            3 => 'ثلاثون',
            4 => 'أربعون',
            5 => 'خمسون',
            6 => 'ستون',
            7 => 'سبعون',
            8 => 'ثمانون',
            9 => 'تسعون',
        ];
        return $numbers[(int)$digit] ?? '';
    }

    private function moreThanHundred($digit)
    {
        $numbers = [
            1 => 'مائة',
            2 => 'مائتان',
            3 => 'ثلاثمائة',
            4 => 'أربعمائة',
            5 => 'خمسمائة',
            6 => 'ستمائة',
            7 => 'سبعمائة',
            8 => 'ثمانمائة',
            9 => 'تسعمائة',
        ];
        return $numbers[(int)$digit] ?? '';
    }

    private function forOneToTwenty($digit)
    {
        $numbers = [
            1 => 'واحد',
            2 => 'اثنان',
            3 => 'ثلاثة',
            4 => 'أربعة',
            5 => 'خمسة',
            6 => 'ستة',
            7 => 'سبعة',
            8 => 'ثمانية',
            9 => 'تسعة',
            10 => 'عشرة',
            11 => 'أحد عشر',
            12 => 'إثنا عشر',
            13 => 'ثلاثة عشر',
            14 => 'أربعة عشر',
            15 => 'خمسة عشر',
            16 => 'ستة عشر',
            17 => 'سبعة عشر',
            18 => 'ثمانية عشر',
            19 => 'تسعة عشر',
            20 => 'عشرون',
        ];
        return $numbers[(int)$digit] ?? '';
    }

    private function nameByDigit($digitLength)
    {
        $numbers = [
            3 => 'مائة',
            4 => 'آلاف',
            6 => 'ألف',
            7 => 'ملايين',
        ];
        return $numbers[$digitLength] ?? '';
    }
}

class InvoicesController extends Controller
{


    public function showInvoiceRecent__pending($request)
    {
        //
        //  return $request;

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);

        $saleData = temp_sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get();
        $InvoiceData = temp_invoice::find($request);
  $totAL=round(($InvoiceData->Price - $InvoiceData->discount)+(($InvoiceData->Price - $InvoiceData->discount)*$avtSaleRate->AVT),2);
                 $totAL= number_format($totAL, 2);


        list($whole, $decimal) = explode('.',str_replace(",","",$totAL));
         $numberToWord = new NumberToWord();
         $check=str_split($decimal);
         if($check[0]=="0"){
           $decimal =(int)$check[1] ;
         }
         else{
        $decimal =$decimal ;

         }
      $setting=system_setting::find(1);
             $Total_Amount=$InvoiceData->Bank_transfer +  $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

         $data = [
            $setting->name_ar,
            $setting->Tax,
            (string)$InvoiceData->issue_date . 'T' . (string)$InvoiceData->issue_time,
            number_format(($Total_Amount),2,'.',''),
            number_format( (($Total_Amount*100/(100+($avtSaleRate->AVT*100)))) * $avtSaleRate->AVT,2,'.',''),
        ];
        $data[] = '';
        $data[] ='';
        $data[] = '';
        $data[] = '';

        $data = [
            "invoicetotal_price" =>  number_format(($Total_Amount*100/(100+($avtSaleRate->AVT*100))),2,'.',''),
            "invoicetotal_addedvalue" =>  number_format( (($Total_Amount*100/(100+($avtSaleRate->AVT*100)))) * $avtSaleRate->AVT,2,'.',''),
            "invoicetotal_discount" => $InvoiceData->discount,
            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
         'totatextlriyales'=>NumToArabic::number2Word(round((int)$whole,2)) .'  ريال',
            'totatextlrihalala'=>$decimal!='00'?NumToArabic::number2Word(round((int)$decimal,2)). '   هللة':'فقط',

        ];

        return  view('products.printInvoicesReturnToClientRecentSales_pending', compact('data'));
    }
    
  public function generate_return_sale_pdf  ($request) {


        $saleData = return_sales::where("invoice_id", $request)->get();
        $InvoiceData = invoices::find($request);
        $data = [

            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
        ];

         $tran = ['data'=>$data];
        // Get the current date and time.
        $dateTime = now();

        // Generate a unique filename.
    //   return view('pdf.salereturn', compact('data'));

        $fileName = $dateTime->format('Y-m-d H:i:s') ;
        $html = view('pdf.salereturn', $tran)->toArabicHTML();

        $pdf = PDF::loadHTML($html)->output();

        //Generate the pdf file
        $headers = array(
            "Content-type" => "application/pdf",
        );

        // Create a stream response as a file download
        return response()->streamDownload(
            fn () => print($pdf), // add the content to the stream
            "Invoice_No_".$request."_". $fileName.".pdf", // the name of the file/stream
            $headers
        );




    }


public function previousSales_not_sended_Invoices()
    {
        return view('previousSales_not_sended_Invoices');


    }

    public function previousSales_sended_Invoices()
    {

        return view('previousSales_sended_Invoices');

    }

    public function getAllinvicesajax_send_zatca()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('sent_to_zatca', 1)->where('status', 0)->orderby('id', 'desc')->paginate(40);
        return view('ajax_Recent_Invoices_send_or_not', compact('data'));
    }


    public function getAllinvicesajax_send_zatca_not()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('sent_to_zatca', 0)->where('status', 0)->orderby('id', 'desc')->paginate(40);
        return view('ajax_Recent_Invoices_send_or_not', compact('data'));
    }

public function previousSales_not_sended_Invoices_all_branchs()
{
    return view('previousSales_not_sended_Invoices_all_branchs');
}
    public function reciptprinter(Request $request)
    {
        //
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request->show_invoice_number == null) {
            $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);
            session()->flash('nodataprint', '');

            return view('products.sales', compact('products'));
        }
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avtSaleRate = Avt::find(1);


        $saleData = sales::where("invoice_id", $request->show_invoice_number)->where('quantity', '!=', 0)->get();
        $InvoiceData = invoices::find($request->show_invoice_number);
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,
            'salesData' => $saleData,
            'invoiceData' => $InvoiceData,
        ];



        // $saleData= sales::where("invoice_id",$invoicesid)->get();
        // $InvoiceData=invoices::find($invoicesid);
        // $data=[
        //     'salesData'=>$saleData ,
        //     'invoiceData'=>  $InvoiceData,
        // ];
        //return $data;
        return view('products.reciptprinter', compact('data'));
    }



public function getAllinvicesajax_send_zatca_not_all_beanchs()
{
    LaravelLocalization::setLocale(LaravelLocalization::getCurrentLocale());

    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfToday   = Carbon::now()->endOfDay();

    $data = invoices::where('save', 1)
        ->where('sent_to_zatca', 0)
        ->where('status', 0)
        ->whereBetween('created_at', [$startOfMonth, $endOfToday])
        ->orderBy('id', 'desc')
        ->paginate(40);

    return view('ajax_Recent_Invoices_send_or_not', compact('data'));
}

public function getquotebybranch($id)
{
    $data = offer_price_to_customer::where('branchs_id', $id)
        ->orderBy('id', 'desc')
        ->paginate(30);

    return view('searchPreviousQuotes', compact('data'));
}



public function save_delivery_sale(Request $request)
{
    return DB::transaction(function () use ($request) {
        $cashamount = 0;
        $bankamount = 0;
        $creaditamount = 0;
        $Bank_transfer = 0;

        $customerId = $request->clientnamesearch;
        $paymentMethod = $request->payment_type;
        $branchId = Auth::user()->branchs_id;
        $userBranchId = Auth::user()->branch->id;
        $now = Carbon::now()->addHours(3);

        // 1. تحديد قيم المبالغ بناءً على طريقة الدفع الجديدة
        if ($paymentMethod == 'Cash') {
            $cashamount = $request->grandTotal;
        } elseif ($paymentMethod == 'Shabka') {
            $bankamount = $request->grandTotal;
        } elseif ($paymentMethod == 'Bank_transfer') {
            $Bank_transfer = $request->grandTotal;
        } elseif ($paymentMethod == 'Partition') {
            $bankamount = $request->bankamount_form ?? 0;
            $cashamount = $request->cashamount_form ?? 0;
        } else {
            $creaditamount = $request->grandTotal;
        }

        // 2. معالجة الفاتورة (إنشاء جديد أو تحديث)
        if ($request->show_invoice_number_update == 0) {
            // إنشاء فاتورة جديدة
            $confirminvoice = delivery_to_customer_withoud_tax_invoices::create([
                'save' => 1,
                'customer_id' => $customerId,
                'user_id' => Auth::user()->id,
                'Price' => $request->totalSum,
                'Added_Value' => 0,
                'Pay' => $paymentMethod,
                'status' => $branchId == $request->branchs_id ? 0 : 1,
                'branchs_id' => $userBranchId,
                'discountOnProduct' => $request->totaldiscound - $request->discound_on_invoice ?? 0,
                'discount' => $request->totaldiscound,
                'Number_of_Quantity' => 0,
                'note' => $request->notes,
                'created_at' => $now,
                'updated_at' => $now,
                'morepayment_way' => 1,
                'cashamount' => $cashamount,
                'bankamount' => $bankamount,
                'creaditamount' => $creaditamount,
                'Bank_transfer' => $Bank_transfer,
                'issue_date' => substr($now, 0, 10),
                'issue_time' => substr($now, 12),
                'p_o' => $request->p_o,
                'display_number' => $request->shownumberproduct
            ]);
        } else {
            // تحديث فاتورة قائمة
            $invoiceNumber = $request->show_invoice_number_update;
            $InvoiceData = delivery_to_customer_withoud_tax_invoices::find($invoiceNumber);

            // حذف حركات العميل الآجل السابقة للفاتورة
            credittransactions::where('note', '  فاتورة تسليم منتج رقم :' . (string) $invoiceNumber)->delete();

            // أ: عكس رصيد العميل بناءً على قيم الفاتورة المخزنة قديماً
            if ($InvoiceData->Pay == 'Credit') {
                $customerdata = customers::find($InvoiceData->customer_id);
                if ($customerdata) {
                    $customerdata->update([
                        'Balance' => $customerdata->Balance - ($InvoiceData->Price - $InvoiceData->discount)
                    ]);
                }
            }

            // ب: إعادة كميات المنتجات إلى المستودع قبل الحذف
            $products = sales_withoud_taxes::where('invoice_id', $invoiceNumber)->get();
            foreach ($products as $item) {
                $updateProduct = products::find($item->product_id);
                if ($updateProduct) {
                    $updateProduct->update([
                        'numberofpice' => $updateProduct->numberofpice + $item->quantity
                    ]);
                }
            }

            // ج: حذف مبيعات الفاتورة القديمة
            sales_withoud_taxes::where('invoice_id', $invoiceNumber)->delete();

            // د: تسوية وعكس أرصدة الحسابات المالية القديمة (بناءً على قيم الفاتورة الأصلية وليس الـ Request)
            $total_withoud_tax_old = ($InvoiceData->Price - $InvoiceData->discount);

            if ($InvoiceData->bankamount + $InvoiceData->Bank_transfer) {
                $financial_accounts = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchId)->first();
                if ($financial_accounts) {
                    $financial_accounts->update(['debtor_current' => $financial_accounts->debtor_current - $total_withoud_tax_old]);
                }
            }

            if ($InvoiceData->cashamount) {
                $fin_acc_5 = financial_accounts::find(5);
                if ($fin_acc_5) {
                    $fin_acc_5->update(['debtor_current' => $fin_acc_5->debtor_current - $total_withoud_tax_old]);
                }

                $financial_accounts = financial_accounts::where('parent_account_number', 5)->where('branchs_id', $branchId)->first();
                if ($financial_accounts) {
                    $financial_accounts->update(['debtor_current' => $financial_accounts->debtor_current - $total_withoud_tax_old]);
                }
            }

            if ($InvoiceData->creaditamount) {
                $financial_accounts = financial_accounts::where('orginal_type', 1)->where('orginal_id', $InvoiceData->customer_id)->first();
                if ($financial_accounts) {
                    $financial_accounts->update(['debtor_current' => $financial_accounts->debtor_current - $total_withoud_tax_old]);
                }
            }

            // عكس مبيعات الحساب الرئيسي 112
            $fin_acc_112 = financial_accounts::find(112);
            if ($fin_acc_112) {
                $fin_acc_112->update(['creditor_current' => $fin_acc_112->creditor_current - $total_withoud_tax_old]);
            }

            $fin_acc_112_branch = financial_accounts::where('parent_account_number', 112)->where('branchs_id', $branchId)->first();
            if ($fin_acc_112_branch) {
                $fin_acc_112_branch->update(['creditor_current' => $fin_acc_112_branch->creditor_current - $total_withoud_tax_old]);
            }

            // هـ: تحديث بيانات الفاتورة بالقيم الجديدة
            $InvoiceData->update([
                'save' => 1,
                'customer_id' => $customerId,
                'user_id' => Auth::user()->id,
                'Price' => $request->totalSum,
                'Added_Value' => 0,
                'Pay' => $paymentMethod,
                'status' => $branchId == $request->branchs_id ? 0 : 1,
                'branchs_id' => $userBranchId,
                'discountOnProduct' => $request->totaldiscound - $request->discound_on_invoice ?? 0,
                'discount' => $request->totaldiscound,
                'Number_of_Quantity' => 0,
                'note' => $request->notes,
                'created_at' => $now,
                'updated_at' => $now,
                'morepayment_way' => 1,
                'cashamount' => $cashamount,
                'bankamount' => $bankamount,
                'creaditamount' => $creaditamount,
                'Bank_transfer' => $Bank_transfer,
                'issue_date' => substr($now, 0, 10),
                'issue_time' => substr($now, 12),
                'p_o' => $request->p_o,
                'display_number' => $request->shownumberproduct
            ]);

            $confirminvoice = $InvoiceData;
        }

        // 3. معالجة المنتجات الجديدة وحساب التكلفة الإجمالية للمخزون
        $total_cost = 0;
        foreach ($request->products as $sale) {
            $productdata = products::find($sale['product_id']);
            if (!$productdata) continue;

            $total_cost += $productdata->purchasingـprice * $sale['quentity'];

            if ($branchId == $productdata->branchs_id) {
                sales_withoud_taxes::create([
                    'user_id' => Auth::user()->id,
                    'save' => 1,
                    'product_id' => $sale['product_id'],
                    'invoice_id' => $confirminvoice->id,
                    'branch_id' => $userBranchId,
                    'Discount_Value' => $sale['discound'],
                    'Added_Value' => 0,
                    'Unit_Price' => $sale['price'],
                    'reamingQuantity' => $productdata->numberofpice - $sale['quentity'],
                    'quantity' => $sale['quentity'],
                    'created_at' => $now,
                ]);

                $productdata->update([
                    'numberofpice' => $productdata->numberofpice - $sale['quentity'],
                ]);
            } else {
                $confirminvoice->update(['status' => 1]);

                sales_withoud_taxes::create([
                    'user_id' => Auth::user()->id,
                    'save' => 1,
                    'product_id' => $sale['product_id'], // تم تصحيح الاعتماد على المصفوفة هنا بدلاً من الريكويست العام لضمان سلامة اللوب
                    'invoice_id' => $confirminvoice->id,
                    'branch_id' => $userBranchId,
                    'Discount_Value' => $sale['discound'],
                    'Added_Value' => 0,
                    'Unit_Price' => $sale['price'],
                    'reamingQuantity' => $productdata->numberofpice - $sale['quentity'],
                    'quantity' => $sale['quentity'],
                    'created_at' => $now,
                ]);

                Delivery_product_to_the_customer::create([
                    'branch_from' => $branchId,
                    'branch_to' => $productdata->branchs_id,
                    'user_from' => Auth::user()->id,
                    'product_id' => $productdata->id,
                    'invoice_id' => $confirminvoice->id,
                    'quantity' => $sale['quentity'],
                    'status' => 0,
                    'created_at' => $now,
                ]);
            }
        }

        // 4. بناء القيود المالية الجديدة للفاتورة الحالية
        if ($cashamount) {
            $financial_accounts = financial_accounts::where('parent_account_number', 5)->where('branchs_id', $branchId)->first();
            if ($financial_accounts) {
                credittransactions::create([
                    'user_id' => Auth::user()->id,
                    'customer_id' => $financial_accounts->id,
                    'recive_amount' => $cashamount,
                    'branchs_id' => $branchId,
                    'pay_method' => $paymentMethod,
                    'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                    'currentblance' => $financial_accounts->current_balance + $cashamount,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => $cashamount,
                ]);
            }

            $customer_fin = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
            if ($customer_fin) {
                credittransactions::create([
                    'user_id' => Auth::user()->id,
                    'customer_id' => $customer_fin->id,
                    'recive_amount' => $cashamount,
                    'branchs_id' => $branchId,
                    'pay_method' => $paymentMethod,
                    'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                    'currentblance' => $customer_fin->current_balance + $creaditamount,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => 0
                ]);
            }
        }

        if ($Bank_transfer + $bankamount) {
            $financial_accounts = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchId)->first();
            if ($financial_accounts) {
                credittransactions::create([
                    'user_id' => Auth::user()->id,
                    'customer_id' => $financial_accounts->id,
                    'recive_amount' => $Bank_transfer + $bankamount,
                    'branchs_id' => $branchId,
                    'pay_method' => $paymentMethod,
                    'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                    'currentblance' => $financial_accounts->current_balance + $Bank_transfer + $bankamount,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => $Bank_transfer + $bankamount,
                ]);
            }

            $customer_fin = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
            if ($customer_fin) {
                credittransactions::create([
                    'user_id' => Auth::user()->id,
                    'customer_id' => $customer_fin->id,
                    'recive_amount' => $Bank_transfer + $bankamount,
                    'branchs_id' => $branchId,
                    'pay_method' => $paymentMethod,
                    'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                    'currentblance' => $customer_fin->current_balance + $creaditamount,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => 0
                ]);
            }
        }

        // إدخال قيد حساب الإيرادات (المبيعات 112)
        $total_value = $Bank_transfer + $creaditamount + $bankamount + $cashamount;
        $financial_accounts_112 = financial_accounts::where('parent_account_number', 112)->where('branchs_id', $branchId)->first();
        if ($financial_accounts_112) {
            credittransactions::create([
                'user_id' => Auth::user()->id,
                'customer_id' => $financial_accounts_112->id,
                'recive_amount' => ($total_value ),
                'branchs_id' => $branchId,
                'pay_method' => $paymentMethod,
                'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance' => $financial_accounts_112->current_balance + ($total_value * 100 / 115),
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now,
                'updated_at' => $now,
                'orginal_id' => 0,
                'creditor' => ($total_value ),
                'debtor' => 0
            ]);
        }

        // قيود تكلفة البضاعة المباعة والمخزون (183 و 181)
        $financial_accounts_183 = financial_accounts::where('parent_account_number', 183)->where('branchs_id', $branchId)->first();
        if ($financial_accounts_183) {
            credittransactions::create([
                'user_id' => Auth::user()->id,
                'customer_id' => $financial_accounts_183->id,
                'recive_amount' => $total_cost,
                'branchs_id' => $branchId,
                'pay_method' => $paymentMethod,
                'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance' => $financial_accounts_183->current_balance + $total_cost,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now,
                'updated_at' => $now,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $total_cost
            ]);
        }

        $financial_accounts_181 = financial_accounts::where('parent_account_number', 181)->where('branchs_id', $branchId)->first();
        if ($financial_accounts_181) {
            credittransactions::create([
                'user_id' => Auth::user()->id,
                'customer_id' => $financial_accounts_181->id,
                'recive_amount' => $total_cost,
                'branchs_id' => $branchId,
                'pay_method' => $paymentMethod,
                'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance' => $financial_accounts_181->current_balance - $total_cost,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now,
                'updated_at' => $now,
                'orginal_id' => 0,
                'creditor' => $total_cost,
                'debtor' => 0
            ]);
        }

        // 5. معالجة حساب العميل الآجل في حال وجود مبالغ ذمم متبقية
        if ($creaditamount != 0 && $creaditamount != null) {
            $customerdata = customers::find($customerId);
            if ($customerdata) {
                $customerdata->update([
                    'Balance' => $customerdata->Balance + $creaditamount
                ]);
            }

            $customer_acc = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
            if ($customer_acc) {
                $customer_acc->update([
                    'current_balance' => $customer_acc->current_balance + $creaditamount,
                    'debtor_current' => $customer_acc->debtor_current + $creaditamount,
                ]);

                credittransactions::create([
                    'user_id' => Auth::user()->id,
                    'customer_id' => $customer_acc->id,
                    'recive_amount' => $creaditamount,
                    'branchs_id' => $branchId,
                    'pay_method' => $paymentMethod,
                    'note' => '  فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                    'currentblance' => $customer_acc->current_balance,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => $creaditamount
                ]);
            }

            $updateCustomer = customers::find($customerId);
            $confirminvoice->update([
                'currentblance' => $updateCustomer->Balance,
            ]);
        }

        return $confirminvoice->id;
    });
}










public function dashboard()
{
    $today = Carbon::today()->toDateString();
    $monthStart = Carbon::now()->startOfMonth()->toDateString();

    // جلب نسبة الضريبة
    $avtSetting = Avt::find(1);
    $vat = $avtSetting ? $avtSetting->AVT : 0;

    /* ================= TODAY SALES ================= */

    $todayInvoicesCount = invoices::whereDate('created_at', $today)
        ->where('save', 1)
        ->count();

    $todaySales = invoices::whereDate('created_at', $today)
        ->where('save', 1)
        ->sum(DB::raw('cashamount + bankamount + creaditamount + Bank_transfer'));

    $todayReturns = return_sales::whereDate('created_at', $today)
        ->sum(DB::raw('(return_Unit_Price * return_quantity) - discountvalue - discountoninvoice'));

    $todayEarnings = round($todaySales - ($todayReturns + ($todayReturns * $vat)), 2);


    /* ================= MONTH SALES (تعديل التواريخ) ================= */

    $monthInvoicesCount = invoices::whereDate('created_at', '>=', $monthStart)
        ->where('save', 1)
        ->count();

    // جلب جميع مبيعات الشهر شاملة ساعات اليوم الحالي
    $monthSales = invoices::whereDate('created_at', '>=', $monthStart)
        ->where('save', 1)
        ->sum(DB::raw('cashamount + bankamount + creaditamount + Bank_transfer'));


    /* ================= DELIVERY (NO TAX) ================= */

    $todayDeliveryCount = delivery_to_customer_withoud_tax_invoices::whereDate('created_at', $today)
        ->where('save', 1)->count();

    $todayDeliverySales = delivery_to_customer_withoud_tax_invoices::whereDate('created_at', $today)
        ->where('save', 1)
        ->sum(DB::raw('cashamount + bankamount + creaditamount + Bank_transfer'));

    $todayDeliveryReturns = return_sales_deliverys::whereDate('created_at', $today)
        ->sum(DB::raw('(return_Unit_Price * return_quantity) - discountvalue - discountoninvoice'));

    $todayDeliveryNet = round($todayDeliverySales - $todayDeliveryReturns, 2);

    $monthDeliveryCount = delivery_to_customer_withoud_tax_invoices::whereDate('created_at', '>=', $monthStart)
        ->where('save', 1)->count();

    $monthDeliverySales = delivery_to_customer_withoud_tax_invoices::whereDate('created_at', '>=', $monthStart)
        ->where('save', 1)
        ->sum(DB::raw('cashamount + bankamount + creaditamount + Bank_transfer'));


    /* ================= PURCHASES (تعديل التواريخ) ================= */

    $todayPurchasesCount = resource_purchases::whereDate('created_at', $today)
        ->where('save', 1)->count();

    $todayPurchasesTotal = resource_purchases::whereDate('created_at', $today)
        ->where('save', 1)
        ->sum(DB::raw('In_debt'));

    $monthPurchasesCount = resource_purchases::whereDate('created_at', '>=', $monthStart)
        ->where('save', 1)->count();

    // جلب جميع مشتريات الشهر شاملة اليوم الحالي
    $monthPurchasesTotal = resource_purchases::whereDate('created_at', '>=', $monthStart)
        ->where('save', 1)->sum(DB::raw('In_debt'));


    /* ================= RETURNS COUNT ================= */

    $uniqueReturnSalesCount = return_sales::whereDate('created_at', $today)
        ->distinct('invoice_id')
        ->count('invoice_id');

    $resourcePurchasesCount = resource_purchases::whereDate('created_at', '>=', $today)
        ->where('recoveredـpieces', '!=', 0)
        ->count();

   $latestInvoices = invoices::with(['customer', 'returnSales'])
    ->whereDate('created_at', $today)
    ->where('save', 1)
    ->latest()
    ->take(5)
    ->get();

    $customersCount = customers::count();
    $suppliersCount = supllier::count();

    return view('index', compact(
        'todayEarnings',
        'todayInvoicesCount',
        'monthInvoicesCount',
        'monthSales',
        'todayDeliveryCount',
        'todayDeliveryNet',
        'monthDeliveryCount',
        'monthDeliverySales',
        'todayPurchasesCount',
        'todayPurchasesTotal',
        'monthPurchasesCount',
        'monthPurchasesTotal',
        'uniqueReturnSalesCount',
        'resourcePurchasesCount',
        'latestInvoices',
        'customersCount',
        'suppliersCount'
    ));
}

public function getlastprice_delivery($product_id, $customer_id)
{
    $data_supplier = [];

    $invoices = delivery_to_customer_withoud_tax_invoices::where("customer_id", $customer_id)
        ->where('save', 1)
        ->orderBy('id', 'desc')
        ->get();

    foreach ($invoices as $invoice) {
        $saleData = sales_withoud_taxes::where("invoice_id", $invoice->id)
            ->where("product_id", $product_id)
            ->first();

        if ($saleData != null) {
            $totalQty = ($saleData->quantity + ($saleData->quantityreturn ?? 0));
            $discountPerUnit = ($totalQty > 0) ? round($saleData->Discount_Value / $totalQty, 2) : 0;
            $finalCost = $saleData->Unit_Price - $discountPerUnit;

            $data_supplier[] = [
                'invoiceid' => $invoice->id,
                'date'      => substr($invoice->created_at, 0, 10),
                'cost'      => round($finalCost, 2),
                'quantity'  => $saleData->quantity,
            ];
        }
    }

    return $data_supplier;
}

public function delivery_print_return_Invoice_return($request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $saleData = return_sales_deliverys::where("invoice_id", $request)->get();
    $InvoiceData = delivery_to_customer_withoud_tax_invoices::find($request);

    $data = [
        'salesData'   => $saleData,
        'invoiceData' => $InvoiceData,
    ];

    return view('products.delivery_print_return_Invoice_return', compact('data'));
}

public function return_sale_delivery(Request $request)
{
    $avtSaleRate = Avt::find(1);

    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $product = sales_withoud_taxes::where('invoice_id', $request->invoice_no)->where('save', 1)->get();
    $InvoiceData = delivery_to_customer_withoud_tax_invoices::find($request->invoice_no);

    if (!$InvoiceData || count($product) == 0) {
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? ' لم يتم العثور علي فاتورة بهذة الرقم' : 'No invoice with this number was found';
        session()->flash('notfountreturnproduct', $message);
        $data = [];
        return view('products.salesreturned_delivery', compact('data'));
    }

    $date1 = new DateTime($InvoiceData->created_at);
    $date2 = new DateTime(date("Y-m-d H:i:s"));
    $diff = $date1->diff($date2);
    $day = $diff->d;

    if ($day > 30) {
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? ' لم يمكن استرجاع الفاتورة بعد 30 ايام من تاريخ الاصدار ' : 'Refund the invoice after 30 days from the date of issuance';
        session()->flash('notfountreturnproduct', $message);
        $data = [];
        return view('products.salesreturned_delivery', compact('data'));
    } else {
        $data = [
            "invoicetotal_price"      => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount"   => $InvoiceData->discount,
            'product'                 => $product,
            'payment'                 => $InvoiceData->Pay,
            "invoice_id"              => $request->invoice_no
        ];
        session()->flash('foundinvoice', ' تم العثور علي فاتورة ');

        return view('products.salesreturned_delivery', compact('data'));
    }
}

public function delivery_product_to_customer()
{
    return view('products.sales_withoud_tax');
}
    public function printreturnInvoice($request)
    {
        //
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);

        $saleData = return_sales::where("invoice_id", $request)->get();
        $InvoiceData = invoices::find($request);
        $data = [

            'salesData' => $saleData,
            'invoiceData' => $InvoiceData,
        ];

        return view('products.printInvoicesToClientReturnSales', compact('data'));
    }



public function confirmpaymentconfirmpaymentdelivery_to_customer_withoud_tax_invoices($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod, $customerId, $date2, $date12)
{
    return DB::transaction(function () use ($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod, $customerId) {

        $cashamount     = $cashamount ?? 0;
        $bankamount     = $bankamount ?? 0;
        $creaditamount  = $creaditamount ?? 0;
        $Bank_transfer  = $Bank_transfer ?? 0;
        $total_cost     = 0;

        $invoice = temp_invoice::find($invoiceId);
        if ($invoice == null) {
            return [0];
        }

        $nowTime = Carbon::now()->addHours(3);

        if ($invoice->update_invoice) {
            delivery_to_customer_withoud_tax_invoices::find($invoice->update_invoice)->update([
                'save'               => 1,
                'customer_id'        => $customerId,
                'user_id'            => Auth()->user()->id,
                'Price'              => $invoice->Price,
                'Added_Value'        => $invoice->Added_Value,
                'Pay'                => $paymentMethod,
                'status'             => Auth()->user()->branchs_id == $invoice->branchs_id ? 0 : 1,
                'branchs_id'         => Auth()->user()->branch->id,
                'discountOnProduct'  => $invoice->discountOnProduct,
                'discount'           => $invoice->discount,
                'Number_of_Quantity' => $invoice->Number_of_Quantity,
                'note'               => $invoice->note,
                'created_at'         => $nowTime,
                'updated_at'         => $nowTime,
                'morepayment_way'    => 1,
                'cashamount'         => $cashamount,
                'bankamount'         => $bankamount,
                'creaditamount'      => $creaditamount,
                'Bank_transfer'      => $Bank_transfer,
                'issue_date'         => substr($nowTime, 0, 10),
                'issue_time'         => substr($nowTime, 12),
            ]);
            $confirminvoice = delivery_to_customer_withoud_tax_invoices::find($invoice->update_invoice);
        } else {
            $confirminvoice = delivery_to_customer_withoud_tax_invoices::create([
                'save'               => 1,
                'customer_id'        => $customerId,
                'user_id'            => Auth()->user()->id,
                'Price'              => $invoice->Price,
                'Added_Value'        => $invoice->Added_Value,
                'Pay'                => $paymentMethod,
                'status'             => Auth()->user()->branchs_id == $invoice->branchs_id ? 0 : 1,
                'branchs_id'         => Auth()->user()->branch->id,
                'discountOnProduct'  => $invoice->discountOnProduct,
                'discount'           => $invoice->discount,
                'Number_of_Quantity' => $invoice->Number_of_Quantity,
                'note'               => $invoice->note,
                'created_at'         => $nowTime,
                'updated_at'         => $nowTime,
                'morepayment_way'    => 1,
                'cashamount'         => $cashamount,
                'bankamount'         => $bankamount,
                'creaditamount'      => $creaditamount,
                'Bank_transfer'      => $Bank_transfer,
                'issue_date'         => substr($nowTime, 0, 10),
                'issue_time'         => substr($nowTime, 12),
            ]);
        }

        foreach (temp_sales::where('invoice_id', $invoiceId)->get() as $sale) {
            $productdata = products::find($sale->product_id);

            // تم تصحيح حرف الكشيدة بنجاح هنا دون أي تغيير في اسم الـ Model أو الحقول الأخرى
            $total_cost += $productdata->purchasing_price * $sale->quantity;

            if (Auth()->user()->branchs_id == $productdata->branchs_id) {
                sales_withoud_taxes::create([
                    'save'            => 1,
                    'product_id'      => $sale->product_id,
                    'invoice_id'      => $confirminvoice->id,
                    'branch_id'       => Auth()->user()->branch->id,
                    'Discount_Value'  => $sale->Discount_Value,
                    'Added_Value'     => $sale->Added_Value,
                    'Unit_Price'      => $sale->Unit_Price,
                    'reamingQuantity' => $sale->reamingQuantity,
                    'quantity'        => $sale->quantity,
                    'created_at'      => $nowTime,
                    'unit'            => $sale->unit,
                ]);

                if ($productdata->products_mix == 0) {
                    products::where('id', $sale->product_id)->update([
                        'numberofpice' => $productdata->numberofpice - $sale->quantity,
                    ]);
                } else {
                    foreach (products_mix_items::where('products_mix_id', $productdata->products_mix)->get() as $itemmix) {
                        $product_chect_mix = products::find($itemmix->product_id);
                        products::where('id', $itemmix->product_id)->update([
                            'numberofpice' => $product_chect_mix->numberofpice - $itemmix->quantity,
                        ]);
                    }
                }
            } else {
                sales_withoud_taxes::create([
                    'save'            => 1,
                    'product_id'      => $sale->product_id,
                    'invoice_id'      => $confirminvoice->id,
                    'branch_id'       => Auth()->user()->branch->id,
                    'Discount_Value'  => $sale->Discount_Value,
                    'Added_Value'     => $sale->Added_Value,
                    'Unit_Price'      => $sale->Unit_Price,
                    'reamingQuantity' => $sale->reamingQuantity,
                    'quantity'        => $sale->quantity,
                    'created_at'      => $nowTime,
                ]);

                Delivery_product_to_the_customer::create([
                    'branch_from' => Auth()->user()->branchs_id,
                    'branch_to'   => $productdata->branchs_id,
                    'user_from'   => Auth()->user()->id,
                    'product_id'  => $productdata->id,
                    'invoice_id'  => $confirminvoice->id,
                    'quantity'    => $sale->quantity,
                    'status'      => 0,
                    'created_at'  => $nowTime,
                ]);
            }
        }

        if ($cashamount) {
            $financial_accounts = financial_accounts::find(5);
            $financial_accounts->update([
                'current_balance' => $financial_accounts->current_balance + $cashamount,
                'debtor_current'  => $financial_accounts->debtor_current + $cashamount,
            ]);

            credittransactions::create([
                'user_id'         => Auth()->user()->id,
                'customer_id'     => 5,
                'recive_amount'   => $cashamount,
                'branchs_id'      => Auth()->user()->branchs_id,
                'pay_method'      => $paymentMethod,
                'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance'   => $financial_accounts->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at'      => $nowTime,
                'updated_at'      => $nowTime,
                'orginal_id'      => 0,
                'creditor'        => 0,
                'debtor'          => $cashamount,
            ]);

            $financial_accounts_branch = financial_accounts::where('parent_account_number', 5)->where('branchs_id', Auth()->user()->branchs_id)->first();
            $financial_accounts_branch->update([
                'current_balance' => $financial_accounts_branch->current_balance + $cashamount,
                'debtor_current'  => $financial_accounts_branch->debtor_current + $cashamount,
            ]);

            credittransactions::create([
                'user_id'         => Auth()->user()->id,
                'customer_id'     => $financial_accounts_branch->id,
                'recive_amount'   => $cashamount,
                'branchs_id'      => Auth()->user()->branchs_id,
                'pay_method'      => $paymentMethod,
                'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance'   => $financial_accounts_branch->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at'      => $nowTime,
                'updated_at'      => $nowTime,
                'orginal_id'      => 0,
                'creditor'        => 0,
                'debtor'          => $cashamount,
            ]);
        }

        if ($Bank_transfer + $bankamount) {
            $financial_accounts = financial_accounts::find(4);
            $financial_accounts->update([
                'current_balance' => $financial_accounts->current_balance + $Bank_transfer + $bankamount,
                'debtor_current'  => $financial_accounts->debtor_current + $Bank_transfer + $bankamount,
            ]);

            credittransactions::create([
                'user_id'         => Auth()->user()->id,
                'customer_id'     => 4,
                'recive_amount'   => $Bank_transfer + $bankamount,
                'branchs_id'      => Auth()->user()->branchs_id,
                'pay_method'      => $paymentMethod,
                'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance'   => $financial_accounts->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at'      => $nowTime,
                'updated_at'      => $nowTime,
                'orginal_id'      => 0,
                'creditor'        => 0,
                'debtor'          => $Bank_transfer + $bankamount,
            ]);

            $financial_accounts_branch = financial_accounts::where('parent_account_number', 4)->where('branchs_id', Auth()->user()->branchs_id)->first();
            $financial_accounts_branch->update([
                'current_balance' => $financial_accounts_branch->current_balance + $Bank_transfer + $bankamount,
                'debtor_current'  => $financial_accounts_branch->debtor_current + $Bank_transfer + $bankamount,
            ]);

            credittransactions::create([
                'user_id'         => Auth()->user()->id,
                'customer_id'     => $financial_accounts_branch->id,
                'recive_amount'   => $Bank_transfer + $bankamount,
                'branchs_id'      => Auth()->user()->branchs_id,
                'pay_method'      => $paymentMethod,
                'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance'   => $financial_accounts_branch->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at'      => $nowTime,
                'updated_at'      => $nowTime,
                'orginal_id'      => 0,
                'creditor'        => 0,
                'debtor'          => $Bank_transfer + $bankamount,
            ]);
        }

        $total_value = $Bank_transfer + $creaditamount + $bankamount + $cashamount;
        $taxable_value = ($total_value * 100 / 115);

        $financial_accounts112 = financial_accounts::find(112);
        $financial_accounts112->update([
            'current_balance'  => $financial_accounts112->current_balance + $taxable_value,
            'creditor_current' => $financial_accounts112->creditor_current + $taxable_value,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => 112,
            'recive_amount'   => $taxable_value,
            'branchs_id'      => Auth()->user()->branchs_id,
            'pay_method'      => $paymentMethod,
            'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
            'currentblance'   => $financial_accounts112->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $nowTime,
            'updated_at'      => $nowTime,
            'orginal_id'      => 0,
            'creditor'        => $taxable_value,
            'debtor'          => 0
        ]);

        $financial_accounts112_branch = financial_accounts::where('parent_account_number', 112)->where('branchs_id', Auth()->user()->branchs_id)->first();
        $financial_accounts112_branch->update([
            'current_balance'  => $financial_accounts112_branch->current_balance + $taxable_value,
            'creditor_current' => $financial_accounts112_branch->creditor_current + $taxable_value,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => $financial_accounts112_branch->id,
            'recive_amount'   => $taxable_value,
            'branchs_id'      => Auth()->user()->branchs_id,
            'pay_method'      => $paymentMethod,
            'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
            'currentblance'   => $financial_accounts112_branch->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $nowTime,
            'updated_at'      => $nowTime,
            'orginal_id'      => 0,
            'creditor'        => $taxable_value,
            'debtor'          => 0
        ]);

        $financial_accounts183 = financial_accounts::find(183);
        $financial_accounts183->update([
            'current_balance' => $financial_accounts183->current_balance + $total_cost,
            'debtor_current'  => $financial_accounts183->debtor_current + $total_cost,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => 183,
            'recive_amount'   => $total_cost,
            'branchs_id'      => Auth()->user()->branchs_id,
            'pay_method'      => $paymentMethod,
            'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
            'currentblance'   => $financial_accounts183->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $nowTime,
            'updated_at'      => $nowTime,
            'orginal_id'      => 0,
            'creditor'        => 0,
            'debtor'          => $total_cost
        ]);

        $financial_accounts183_branch = financial_accounts::where('parent_account_number', 183)->where('branchs_id', Auth()->user()->branchs_id)->first();
        $financial_accounts183_branch->update([
            'current_balance' => $financial_accounts183_branch->current_balance + $total_cost,
            'debtor_current'  => $financial_accounts183_branch->debtor_current + $total_cost,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => $financial_accounts183_branch->id,
            'recive_amount'   => $total_cost,
            'branchs_id'      => Auth()->user()->branchs_id,
            'pay_method'      => $paymentMethod,
            'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
            'currentblance'   => $financial_accounts183_branch->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $nowTime,
            'updated_at'      => $nowTime,
            'orginal_id'      => 0,
            'creditor'        => 0,
            'debtor'          => $total_cost
        ]);

        $financial_accounts181 = financial_accounts::find(181);
        $financial_accounts181->update([
            'current_balance'  => $financial_accounts181->current_balance - $total_cost,
            'creditor_current' => $financial_accounts181->creditor_current + $total_cost,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => 181,
            'recive_amount'   => $total_cost,
            'branchs_id'      => Auth()->user()->branchs_id,
            'pay_method'      => $paymentMethod,
            'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
            'currentblance'   => $financial_accounts181->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $nowTime,
            'updated_at'      => $nowTime,
            'orginal_id'      => 0,
            'creditor'        => $total_cost,
            'debtor'          => 0
        ]);

        $financial_accounts181_branch = financial_accounts::where('parent_account_number', 181)->where('branchs_id', Auth()->user()->branchs_id)->first();
        $financial_accounts181_branch->update([
            'current_balance'  => $financial_accounts181_branch->current_balance - $total_cost,
            'creditor_current' => $financial_accounts181_branch->creditor_current + $total_cost,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => $financial_accounts181_branch->id,
            'recive_amount'   => $total_cost,
            'branchs_id'      => Auth()->user()->branchs_id,
            'pay_method'      => $paymentMethod,
            'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
            'currentblance'   => $financial_accounts181_branch->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $nowTime,
            'updated_at'      => $nowTime,
            'orginal_id'      => 0,
            'creditor'        => $total_cost,
            'debtor'          => 0
        ]);

        if ($creaditamount != 0) {
            $customerdata = customers::find($customerId);
            $customerdata->update([
                'Balance' => $customerdata->Balance + $creaditamount
            ]);

            $financial_accounts_cust = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
            $financial_accounts_cust->update([
                'current_balance' => $financial_accounts_cust->current_balance + $creaditamount,
                'debtor_current'  => $financial_accounts_cust->debtor_current + $creaditamount,
            ]);

            credittransactions::create([
                'user_id'         => Auth()->user()->id,
                'customer_id'     => $financial_accounts_cust->id,
                'recive_amount'   => $creaditamount,
                'branchs_id'      => Auth()->user()->branchs_id,
                'pay_method'      => $paymentMethod,
                'note'            => ' فاتورة تسليم منتج رقم :' . (string) $confirminvoice->id,
                'currentblance'   => $financial_accounts_cust->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at'      => $nowTime,
                'updated_at'      => $nowTime,
                'orginal_id'      => 0,
                'creditor'        => 0,
                'debtor'          => $creaditamount
            ]);
        }

        $updateCustomer = customers::find($customerId);
        delivery_to_customer_withoud_tax_invoices::find($confirminvoice->id)->update([
            'currentblance' => $updateCustomer->Balance,
        ]);

        return $confirminvoice->id;
    });
}















public function showInvoiceRecentdelivery($request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $avtSaleRate = Avt::find(1);

    $saleData = sales_withoud_taxes::where("invoice_id", $request)->where('quantity', '!=', 0)->get();
    $InvoiceData = delivery_to_customer_withoud_tax_invoices::find($request);

    $totAL = round(($InvoiceData->Price - $InvoiceData->discount), 2);
    $totAL = number_format($totAL, 2);

    list($whole, $decimal) = explode('.', str_replace(",", "", $totAL));
    $numberToWord = new NumberToWord();
    $check = str_split($decimal);
    if ($check[0] == "0") {
        $decimal = (int) $check[1];
    } else {
        $decimal = $decimal;
    }

    $setting = system_setting::find(1);
    $Total_Amount = $InvoiceData->Bank_transfer + $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

    $data = [
        $setting->name_ar,
        $setting->Tax,
        (string) $InvoiceData->issue_date . 'T' . (string) $InvoiceData->issue_time,
        number_format(($Total_Amount), 2, '.', ''),
        number_format((($Total_Amount * 100 / (100 + ($avtSaleRate->AVT * 100)))) * $avtSaleRate->AVT, 2, '.', ''),
    ];
    $data[] = '';
    $data[] = '';
    $data[] = '';
    $data[] = '';

    $data = [
        "invoicetotal_price"      => number_format(($Total_Amount * 100 / (100 + ($avtSaleRate->AVT * 100))), 2, '.', ''),
        "invoicetotal_addedvalue" => number_format((($Total_Amount * 100 / (100 + ($avtSaleRate->AVT * 100)))) * $avtSaleRate->AVT, 2, '.', ''),
        "invoicetotal_discount"   => $InvoiceData->discount,
        'salesData'               => $saleData,
        'invoiceData'             => $InvoiceData,
        'totatextlriyales'        => NumToArabic::number2Word(round((int) $whole, 2)) . '  ريال',
        'totatextlrihalala'       => $decimal != '00' ? NumToArabic::number2Word(round((int) $decimal, 2)) . '   هللة' : 'فقط',
    ];

    return view('products.printInvoicesReturnToClientRecentSales_delivery', compact('data'));
}


public function print_Invoice_withod_tax(Request $request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    if ($request->show_invoice_number == null) {
        $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);
        session()->flash('nodataprint', '');

        return view('products.sales', compact('products'));
    }

    app()->setLocale(LaravelLocalization::getCurrentLocale());
    $avtSaleRate = Avt::find(1);

    $saleData = sales_withoud_taxes::where("invoice_id", $request->show_invoice_number)->where('quantity', '!=', 0)->get();
    $InvoiceData = delivery_to_customer_withoud_tax_invoices::find($request->show_invoice_number);

    $totAL = round($InvoiceData->Price - $InvoiceData->discount);
    $totAL = number_format($totAL, 2);

    list($whole, $decimal) = explode('.', str_replace(",", "", $totAL));
    $numberToWord = new NumberToWord();
    $check = str_split($decimal);
    if ($check[0] == "0") {
        $decimal = (int) $check[1];
    } else {
        $decimal = $decimal;
    }

    $setting = system_setting::find(1);
    $Total_Amount = $InvoiceData->Bank_transfer + $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

    $data = [
        $setting->name_ar,
        $setting->Tax,
        (string) $InvoiceData->issue_date . 'T' . (string) $InvoiceData->issue_time,
        number_format(($Total_Amount), 2, '.', ''),
        number_format((($Total_Amount * 100 / (100 + ($avtSaleRate->AVT * 100)))) * $avtSaleRate->AVT, 2, '.', ''),
    ];
    $data[] = '';
    $data[] = '';
    $data[] = '';
    $data[] = '';

    $data = [
        "invoicetotal_price"      => $InvoiceData->Price - $InvoiceData->discount,
        "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
        "invoicetotal_discount"   => $InvoiceData->discount,
        'salesData'               => $saleData,
        'invoiceData'             => $InvoiceData,
        'totatextlriyales'        => NumToArabic::number2Word(round((int) $whole, 2)) . '  ريال',
        'totatextlrihalala'       => $decimal != '00' ? NumToArabic::number2Word(round((int) $decimal, 2)) . '   هللة' : 'فقط',
    ];

    return view('products.print_Invoice_withod_tax', compact('data'));
}





















public function update_return_sale_delivery(Request $request)
{
    // Ensure request validation to prevent crashes
    $request->validate([
        'id' => 'required|integer',
        'return_quentity' => 'required|numeric|min:1',
        'pay_return_sale' => 'required|string',
    ]);

    // Set Locale
    $currentLocale = "ar";
            app()->setLocale(LaravelLocalization::getCurrentLocale());


    // Run everything inside a secure database transaction
    return DB::transaction(function () use ($request, $currentLocale) {

        $avtSaleRate = Avt::find(1);
        $returnshabkavalue = 0;

        $saleData = sales_withoud_taxes::findOrFail($request->id);
        $updateProduct = products::findOrFail($saleData->product_id);
        $invoiceData = delivery_to_customer_withoud_tax_invoices::findOrFail($saleData->invoice_id);

        $total_cost_value = $updateProduct->purchasingـprice * $request->return_quentity;
        $paymentMethod = $request->pay_return_sale;

        // 1. INVENTORY MANAGEMENT
        if ($updateProduct->branchs_id == $invoiceData->branchs_id) {
            $updateProduct->increment('numberofpice', $request->return_quentity);
            $updateProduct->decrement('numberـofـsales', $request->return_quentity);

            $message = $currentLocale == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
            session()->flash('success', $message);
        } else {
            Delivery_product_to_the_customer::where('invoice_id', $saleData->invoice_id)
                ->where('product_id', $saleData->product_id)
                ->update([
                    'quantity' => $saleData->quantity - $request->return_quentity,
                    'updated_at' => Carbon::now()
                ]);

            $mproduct = products::where('branchs_id', $invoiceData->branchs_id)
                ->where('Product_Code', $updateProduct->Product_Code)
                ->first();

            if ($mproduct) {
                $mproduct->update([
                    'numberofpice' => $mproduct->numberofpice + $request->return_quentity,
                    'purchasingـprice' => $updateProduct->purchasingـprice,
                ]);

                $message = $currentLocale == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
                session()->flash('success', $message);
            } else {
                // Registering an unlisted product into the current branch
                $newProduct = products::create([
                    'product_name' => $updateProduct->product_name,
                    'name_en' => $updateProduct->name_en,
                    'branchs_id' => $invoiceData->branchs_id,
                    'user_id' => Auth::user()->id,
                    'Product_Location' => $updateProduct->Product_Location,
                    'Product_Code' => $updateProduct->Product_Code,
                    'purchasingـprice' => $updateProduct->purchasingـprice,
                    'average_cost' => $updateProduct->purchasingـprice,
                    'Status' => 1,
                    'notes' => $updateProduct->notes,
                    'unit' => $updateProduct->unit,
                    'minmum_quantity_stock_alart' => $updateProduct->minmum_quantity_stock_alart,
                    'numberofpice' => $request->return_quentity,
                ]);

                $message = $currentLocale == 'ar'
                    ? "تم عملية الاسترجاع. المنتج المسترجع غير مسجل لديكم مسبقا تم تسجيل " . $updateProduct->product_name . " بنفس رقم المنتج شكرا"
                    : "The product is not previously registered. It has been registered with a name " . $updateProduct->product_name;

                session()->flash('createnewproduct', $message);
            }
        }

        // 2. CREATE RETURN LOG
        $hasSingleActiveItem = sales_withoud_taxes::where('invoice_id', $saleData->invoice_id)->where('quantity', '!=', 0)->count() == 1;
        $isReturningFullItemQty = ($saleData->quantity - $request->return_quentity) == 0;

        return_sales_deliverys::create([
            'product_id' => $saleData->product_id,
            'invoice_id' => $saleData->invoice_id,
            'branch_id' => Auth::user()->branch->id,
            'return_Added_Value' => $saleData->Added_Value,
            'return_Unit_Price' => $saleData->Unit_Price,
            'discountvalue' => $saleData->Discount_Value,
            'discountoninvoice' => ($hasSingleActiveItem && $isReturningFullItemQty) ? ($invoiceData->discount - $saleData->Discount_Value) : 0,
            'returnshabkavalue' => $returnshabkavalue,
            'return_quantity' => $request->return_quentity,
            'created_at' => Carbon::now()
        ]);

        // Update the sold item quantities
        $saleData->update([
            'quantity' => $saleData->quantity - $request->return_quentity,
            'quantityreturn' => $saleData->quantityreturn + $request->return_quentity,
            'Discount_Value' => 0
        ]);

        // 3. INVOICE CALCULATIONS & UPDATES
        $noticeNumber = $invoiceData->NOTICE_Number ?: (delivery_to_customer_withoud_tax_invoices::where('id', '!=', $saleData->invoice_id)->where('NOTICE_Number', '!=', 0)->max('NOTICE_Number') + 1);

        $invoiceData->update([
            'Price' => round($invoiceData->Price - ($saleData->Unit_Price * $request->return_quentity), 2),
            'Added_Value' => round($invoiceData->Added_Value - ((($saleData->Unit_Price * $request->return_quentity) - $saleData->Discount_Value) * $avtSaleRate->AVT), 2),
            'Number_of_Quantity' => $invoiceData->Number_of_Quantity - $request->return_quentity,
            'NOTICE_Number' => $noticeNumber,
            'discountOnInvoice' => $invoiceData->discount - $saleData->Discount_Value,
            'discount' => $invoiceData->discount - $saleData->Discount_Value,
            'payment_return' => $paymentMethod,
            'updated_at' => Carbon::now()
        ]);

        // Reset fields to 0 if all quantities are cleared
        if ($invoiceData->fresh()->Number_of_Quantity == 0) {
            $invoiceData->update(['Price' => 0, 'Added_Value' => 0, 'discount' => 0]);
        }

        // 4. FINANCIAL LEDGER ENTRIES
        $total_tax = 0;
        // Determine dynamic values depending on invoice exhaustion
        if ($invoiceData->fresh()->Number_of_Quantity == 0) {
            $total_value = (($request->return_quentity * $saleData->Unit_Price) - $invoiceData->discountOnInvoice);
        } else {
            $total_value = (($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value);
        }

        // Process payment method account mappings dynamically to avoid redundant chunks
        $accountId = null;
        if (in_array($paymentMethod, ["Shabka", "Bank_transfer"])) {
            $accountId = 4;
        } elseif ($paymentMethod == "Cash") {
            $accountId = 5;
        }

        if ($accountId) {
            // $this->updateFinancialAccountAndTransaction($accountId, ($total_tax + $total_value), 'creditor', $invoiceData->id, $paymentMethod, false);
            $this->updateFinancialAccountAndTransaction($accountId, ($total_tax + $total_value), 'creditor', $invoiceData->id, $paymentMethod, true, Auth::user()->branchs_id);
            $finAccount = financial_accounts::where('orginal_type', 1)->where('orginal_id', $invoiceData->customer_id)->firstOrFail();
            $this->createCreditTransaction($finAccount->id, 0, 0, 0, 0, $invoiceData->id, $paymentMethod);


            } else {
            // Credit/Debit adjustment to Customer accounts
            $customerData = customers::findOrFail($invoiceData->customer_id);
            $customerData->decrement('Balance', ($total_tax + $total_value));

            $finAccount = financial_accounts::where('orginal_type', 1)->where('orginal_id', $invoiceData->customer_id)->firstOrFail();
            $finAccount->decrement('current_balance', ($total_tax + $total_value));
            $finAccount->increment('creditor_current', ($total_tax + $total_value));

            $this->createCreditTransaction($finAccount->id, ($total_tax + $total_value), 0, ($total_tax + $total_value), $finAccount->current_balance, $invoiceData->id, $paymentMethod);
        }

        // If whole invoice context loop evaluates true, execute the December 2024 additions cleanly
      //  if ($invoiceData->fresh()->Number_of_Quantity == 0) {
            // Account 112 Actions
            // $this->updateFinancialAccountAndTransaction(112, $total_value, 'debtor', $invoiceData->id, $paymentMethod, false);
            $this->updateFinancialAccountAndTransaction(112, $total_value, 'debtor', $invoiceData->id, $paymentMethod, true, Auth::user()->branchs_id);

            // Account 183 Actions
            // $this->updateFinancialAccountAndTransaction(183, $total_cost_value, 'creditor', $invoiceData->id, $paymentMethod, false);
            $this->updateFinancialAccountAndTransaction(183, $total_cost_value, 'creditor', $invoiceData->id, $paymentMethod, true, Auth::user()->branchs_id);

            // Account 181 Actions
            // $this->updateFinancialAccountAndTransaction(181, $total_cost_value, 'debtor_add', $invoiceData->id, $paymentMethod, false);
            $this->updateFinancialAccountAndTransaction(181, $total_cost_value, 'debtor_add', $invoiceData->id, $paymentMethod, true, Auth::user()->branchs_id);
      //  }

 $InvoiceData = delivery_to_customer_withoud_tax_invoices::find($saleData->invoice_id);

        $productconvert = [];
        $product = sales_withoud_taxes::where('invoice_id', $saleData->invoice_id)->get();
        $InvoiceData = delivery_to_customer_withoud_tax_invoices::where('id', $saleData->invoice_id)->first();
        $i = 0;
        foreach ($product as $item) {
            $i++;
            if ($item->quantity > 0) {
                $productconvert[] = [
                    'count' => $i,
                    'Product_Code' => $item->productData->Product_Code,
                    'product_name' => $item->productData->product_name,
                    'quantity' => $item->quantity,
                    'Unit_Price' => $item->Unit_Price,
                    'Discount_Value' => $item->Discount_Value,
                    "id" => $item->id


                ];
            }
        }
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => round(($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT, 2),
            "invoicetotal_discount" => $InvoiceData->discount,
            'total' => round(($InvoiceData->Price - $InvoiceData->discount) + ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT, 2),
            'product' => $productconvert,
            "invoice_id" => $saleData->invoice_id,
            "message" => $message
        ];
        return $data;    });
}

/**
 * Helper to compress structural ledger lookups and balance definitions.
 */
private function updateFinancialAccountAndTransaction($accountKey, $amount, $type, $invoiceId, $paymentMethod, $useParentBranch = false, $branchId = null)
{
    if ($useParentBranch) {
        $account = financial_accounts::where('parent_account_number', $accountKey)->where('branchs_id', $branchId)->firstOrFail();
    } else {
        $account = financial_accounts::findOrFail($accountKey);
    }

    if ($type == 'creditor') {
        $account->decrement('current_balance', $amount);
        $account->increment('creditor_current', $amount);
        $debtorVal = 0; $creditorVal = $amount;
        $balanceResult = $account->current_balance;
    } elseif ($type == 'debtor') {
        $account->decrement('current_balance', $amount);
        $account->increment('debtor_current', $amount);
        $debtorVal = $amount; $creditorVal = 0;
        $balanceResult = $account->current_balance;
    } else { // debtor_add action
        $account->increment('current_balance', $amount);
        $account->increment('debtor_current', $amount);
        $debtorVal = $amount; $creditorVal = 0;
        $balanceResult = $account->current_balance;
    }

    $this->createCreditTransaction($account->id, $amount, $debtorVal, $creditorVal, $balanceResult, $invoiceId, $paymentMethod);
}

private function createCreditTransaction($customerId, $receiveAmount, $debtor, $creditor, $currentBalance, $invoiceId, $paymentMethod)
{
    credittransactions::create([
        'user_id' => Auth::user()->id,
        'customer_id' => $customerId,
        'recive_amount' => $receiveAmount,
        'branchs_id' => Auth::user()->branchs_id,
        'pay_method' => $paymentMethod,
        'note' => ' فاتورة مرتجع تسليمات رقم :' . (string) $invoiceId,
        'currentblance' => $currentBalance,
        'Pay_Method_Name' => $paymentMethod,
        'orginal_id' => 0,
        'creditor' => $creditor,
        'debtor' => $debtor,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ]);
}











public function previousSalesUnsentInvoices()
    {
        return view('previousSales_not_sended_Invoices');
    }

    public function previousSalesSentInvoices()
    {
        return view('previousSales_sended_Invoices');
    }

    public function getAllInvoicesAjaxSentZatca()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data =invoices::where('branchs_id', Auth::user()->branchs_id)
            ->where('save', 1)
            ->where('sent_to_zatca', 1)
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->paginate(40);

        return view('ajax_Recent_Invoices_send_or_not', compact('data'));
    }

    public function getAllInvoicesAjaxUnsentZatca()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data =invoices::where('branchs_id', Auth::user()->branchs_id)
            ->where('save', 1)
            ->where('sent_to_zatca', 0)
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->paginate(40);

        return view('ajax_Recent_Invoices_send_or_not', compact('data'));
    }

    public function saveUpdateDateInvoice($id, $date)
    {
        // استخدام توقيت الرياض بشكل مباشر عبر كربون بدلاً من addHours يدويًا
        $timeNow = Carbon::now('Asia/Riyadh')->format('H:i:s');
        $fullDateTime = $date . ' ' . $timeNow;

       invoices::where('id', $id)->update(['created_at' => $fullDateTime]);
        CreditTransactions::where('note', 'فاتورة مبيعات رقم :' . $id)->update(['created_at' => $fullDateTime]);

        return 1;
    }

    /**
     * دالة مساعدة لمنع التكرار (DRY) تقوم بحساب وتجهيز بيانات الدفع
     */
    private function preparePaymentData(Request $request)
    {
        $cashAmount = 0;
        $bankAmount = 0;
        $creditAmount = 0;
        $bankTransfer = 0;
        $grandTotal = $request->grandTotal;

        switch ($request->payment_type) {
            Case 'Cash':
                $cashAmount = $grandTotal;
                break;
            case 'Shabka':
                $bankAmount = $grandTotal;
                break;
            case 'Bank_transfer':
                $bankTransfer = $grandTotal;
                break;
            case 'Partition':
                $bankAmount = $request->bankamount_form ?? 0;
                $cashAmount = $request->cashamount_form ?? 0;
                break;
            default:
                $creditAmount = $grandTotal;
                break;
        }

        // توحيد معالجة الوقت والتاريخ بناءً على المدخلات
        $now = Carbon::now('Asia/Riyadh');
        $createdAt = $request->date != '0' ? $request->date . ' ' . $now->format('H:i:s') : $now;

        return [
            'cashamount' => $cashAmount,
            'bankamount' => $bankAmount,
            'creaditamount' => $creditAmount,
            'Bank_transfer' => $bankTransfer,
            'created_at' => $createdAt,
            'issue_date' => $now->format('Y-m-d'),
            'issue_time' => $now->format('H:i:s'),
        ];
    }

    private function saveAsDraft(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $pData = $this->preparePaymentData($request);

            $confirmInvoice = temp_invoice::create([
                'save' => 1,
                'customer_id' => $request->clientnamesearch,
                'user_id' => Auth::id(),
                'Price' => $request->totalSum,
                'Added_Value' => $request->totalTax,
                'Pay' => $request->payment_type,
                'status' => Auth::user()->branchs_id == $request->branchs_id ? 0 : 1,
                'branchs_id' => Auth::user()->branch->id,
                'discountOnProduct' => $request->totaldiscound - ($request->discound_on_invoice ?? 0),
                'discount' => $request->totaldiscound,
                'Number_of_Quantity' => 0,
                'note' => $request->notes,
                'created_at' => $pData['created_at'],
                'updated_at' => Carbon::now('Asia/Riyadh'),
                'morepayment_way' => 1,
                'cashamount' => $pData['cashamount'],
                'bankamount' => $pData['bankamount'],
                'creaditamount' => $pData['creaditamount'],
                'Bank_transfer' => $pData['Bank_transfer'],
                'issue_date' => $pData['issue_date'],
                'issue_time' => $pData['issue_time'],
            ]);

            foreach ($request->products as $sale) {
                $productData = Products::find($sale['product_id']);
                temp_sales::create([
                    'user_id' => Auth::id(),
                    'save' => 1,
                    'product_id' => $sale['product_id'],
                    'invoice_id' => $confirmInvoice->id,
                    'branch_id' => Auth::user()->branch->id,
                    'Added_Value' => $sale['tax'],
                    'Unit_Price' => $sale['price'],
                    'reamingQuantity' => $productData->numberofpice - $sale['quentity'],
                    'quantity' => $sale['quentity'],
                    'created_at' => $pData['created_at'],
                ]);
            }

            return $confirmInvoice->id;
        });
    }

    public function save_invoice_sale(Request $request)
    {
        if ($request->action == 'draft') {
            $draftId = $this->saveAsDraft($request);
            return response()->json(['success' => true, 'id' => $draftId, 'message' => 'تم حفظ الفاتورة كمسودة بنجاح']);
        }

        // بدء عملية المعاملات المالية الآمنة لضمان عدم تضارب القيود المحاسبية
        return DB::transaction(function () use ($request) {
            $customerId = $request->clientnamesearch;
            $customerData = Customers::find($customerId); // تم تقديم جلب بيانات العميل هنا لحل خطأ الـ Fatal Error

            $pData = $this->preparePaymentData($request);
            $confirmInvoice = null;

            if ($request->show_invoice_number_update == 0) {
                $confirmInvoice =invoices::create([
                    'save' => 1,
                    'customer_id' => $customerId,
                    'user_id' => Auth::id(),
                    'Price' => $request->totalSum,
                    'Added_Value' => $request->totalTax,
                    'Pay' => $request->payment_type,
                    'status' => Auth::user()->branchs_id == $request->branchs_id ? 0 : 1,
                    'branchs_id' => Auth::user()->branch->id,
                    'discountOnProduct' => $request->totaldiscound - ($request->discound_on_invoice ?? 0),
                    'discount' => $request->totaldiscound,
                    'Number_of_Quantity' => 0,
                    'note' => $request->notes,
                    'created_at' => $pData['created_at'],
                    'updated_at' => Carbon::now('Asia/Riyadh'),
                    'morepayment_way' => 1,
                    'cashamount' => $pData['cashamount'],
                    'bankamount' => $pData['bankamount'],
                    'creaditamount' => $pData['creaditamount'],
                    'Bank_transfer' => $pData['Bank_transfer'],
                    'issue_date' => $pData['issue_date'],
                    'issue_time' => $pData['issue_time'],
                    'p_o' => $request->p_o,
                    'display_number' => $request->shownumberproduct
                ]);
            }

            $totalCost = 0;

            foreach ($request->products as $sale) {
                $productData = Products::find($sale['product_id']);
                $totalCost += $productData->purchasingـprice * $sale['quentity'];

                $isSameBranch = Auth::user()->branchs_id == $productData->branchs_id;

                if (!$isSameBranch && $confirmInvoice) {
                    $confirmInvoice->update(['status' => 1]);
                }

               sales::create([
                    'user_id' => Auth::id(),
                    'save' => 1,
                    'product_id' => $sale['product_id'],
                    'invoice_id' => $confirmInvoice->id,
                    'branch_id' => Auth::user()->branch->id,
                    'Discount_Value' => $sale['discound'],
                    'Added_Value' => $sale['tax'],
                    'Unit_Price' => $sale['price'],
                    'reamingQuantity' => $productData->numberofpice - ($isSameBranch ? $sale['quentity'] : $request->quentity),
                    'quantity' => $sale['quentity'],
                    'created_at' => $pData['created_at'],
                    'tax_rate' => ($sale['tax_rate']),
                    'product_name' => ($sale['product_name']),

                ]);

                if ($isSameBranch) {
                    $productData->decrement('numberofpice', $sale['quentity']);
                } else {
                    Delivery_product_to_the_customer::create([
                        'branch_from' => Auth::user()->branchs_id,
                        'branch_to' => $productData->branchs_id,
                        'user_from' => Auth::id(),
                        'product_id' => $productData->id,
                        'invoice_id' => $confirmInvoice->id,
                        'quantity' => $sale['quentity'],
                        'status' => 0,
                        'created_at' => $pData['created_at'],
                    ]);
                }
            }

            // القيود المحاسبية لـ Cash
            if ($pData['cashamount']) {
                $financialAccount = financial_accounts::where('parent_account_number', 5)->where('branchs_id', Auth::user()->branchs_id)->first();
                CreditTransactions::create([
                    'user_id' => Auth::id(),
                    'customer_id' => $financialAccount->id,
                    'recive_amount' => $pData['cashamount'],
                    'branchs_id' => Auth::user()->branchs_id,
                    'pay_method' => $request->payment_type,
                    'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                    'currentblance' => $financialAccount->current_balance + $pData['cashamount'],
                    'Pay_Method_Name' => $request->payment_type,
                    'created_at' => $pData['created_at'],
                    'updated_at' => Carbon::now('Asia/Riyadh'),
                    'debtor' => $pData['cashamount'],
                ]);

                $customerAccount = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
                CreditTransactions::create([
                    'user_id' => Auth::id(),
                    'customer_id' => $customerAccount->id,
                    'recive_amount' => 0,
                    'branchs_id' => Auth::user()->branchs_id,
                    'pay_method' => $request->payment_type,
                    'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                    'currentblance' => $customerAccount->current_balance + $pData['creaditamount'],
                    'Pay_Method_Name' => $request->payment_type,
                    'created_at' => $pData['created_at'],
                    'updated_at' => Carbon::now('Asia/Riyadh'),
                ]);
            }

            // القيود المحاسبية للشبكة والتحويل البنكي
            $totalBank = $pData['Bank_transfer'] + $pData['bankamount'];
            if ($totalBank) {
                $financialAccount = financial_accounts::where('parent_account_number', 4)->where('branchs_id', Auth::user()->branchs_id)->first();
                CreditTransactions::create([
                    'user_id' => Auth::id(),
                    'customer_id' => $financialAccount->id,
                    'recive_amount' => $totalBank,
                    'branchs_id' => Auth::user()->branchs_id,
                    'pay_method' => $request->payment_type,
                    'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                    'currentblance' => $financialAccount->current_balance + $totalBank,
                    'Pay_Method_Name' => $request->payment_type,
                    'created_at' => $pData['created_at'],
                    'updated_at' => Carbon::now('Asia/Riyadh'),
                    'debtor' => $totalBank,
                ]);

                $customerAccount = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
                CreditTransactions::create([
                    'user_id' => Auth::id(),
                    'customer_id' => $customerAccount->id,
                    'recive_amount' => 0,
                    'branchs_id' => Auth::user()->branchs_id,
                    'pay_method' => $request->payment_type,
                    'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                    'currentblance' => $customerAccount->current_balance + $pData['creaditamount'],
                    'Pay_Method_Name' => $request->payment_type,
                    'created_at' => $pData['created_at'],
                    'updated_at' => Carbon::now('Asia/Riyadh'),
                ]);
            }

            // تحديث حساب ضريبة القيمة المضافة والإيرادات
            $totalValue = $pData['Bank_transfer'] + $pData['creaditamount'] + $pData['bankamount'] + $pData['cashamount'];
            $vatValue = $request->totalTax;
            $netRevenue = $totalValue -$request->totalTax;

            // حساب الضريبة (102)
            $vatAccount = financial_accounts::where('parent_account_number', 102)->where('branchs_id', Auth::user()->branchs_id)->first();
            $vatAccount->update([
                'current_balance' => $vatAccount->current_balance + $vatValue,
                'creditor_current' => $vatAccount->creditor_current + $vatValue,
            ]);

            CreditTransactions::create([
                'user_id' => Auth::id(),
                'customer_id' => $vatAccount->id,
                'recive_amount' => $vatValue,
                'branchs_id' => Auth::user()->branchs_id,
                'pay_method' => $request->payment_type,
                'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                'currentblance' => $vatAccount->current_balance, // تم الاعتماد على القيمة المحدثة في الأعلى تلقائياً
                'Pay_Method_Name' => $request->payment_type,
                'created_at' => $pData['created_at'],
                'updated_at' => Carbon::now('Asia/Riyadh'),
                'creditor' => $vatValue,
                'vat' => 1,
                'name' => $customerData->name ?? '',
                'tax' => $customerData->tax_no ?? '',
            ]);

            // حساب المبيعات والإيرادات (112)
            $revenueAccount = financial_accounts::where('parent_account_number', 112)->where('branchs_id', Auth::user()->branchs_id)->first();
            CreditTransactions::create([
                'user_id' => Auth::id(),
                'customer_id' => $revenueAccount->id,
                'recive_amount' => $netRevenue,
                'branchs_id' => Auth::user()->branchs_id,
                'pay_method' => $request->payment_type,
                'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                'currentblance' => $revenueAccount->current_balance + $netRevenue,
                'Pay_Method_Name' => $request->payment_type,
                'created_at' => $pData['created_at'],
                'updated_at' => Carbon::now('Asia/Riyadh'),
                'creditor' => $netRevenue,
            ]);

            // حساب تكلفة البضاعة المباعة (183)
            $costAccount = financial_accounts::where('parent_account_number', 183)->where('branchs_id', Auth::user()->branchs_id)->first();
            CreditTransactions::create([
                'user_id' => Auth::id(),
                'customer_id' => $costAccount->id,
                'recive_amount' => $totalCost,
                'branchs_id' => Auth::user()->branchs_id,
                'pay_method' => $request->payment_type,
                'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                'currentblance' => $costAccount->current_balance + $totalCost,
                'Pay_Method_Name' => $request->payment_type,
                'created_at' => $pData['created_at'],
                'updated_at' => Carbon::now('Asia/Riyadh'),
                'debtor' => $totalCost,
            ]);

            // حساب المخزن (181)
            $inventoryAccount = financial_accounts::where('parent_account_number', 181)->where('branchs_id', Auth::user()->branchs_id)->first();
            CreditTransactions::create([
                'user_id' => Auth::id(),
                'customer_id' => $inventoryAccount->id,
                'recive_amount' => $totalCost,
                'branchs_id' => Auth::user()->branchs_id,
                'pay_method' => $request->payment_type,
                'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                'currentblance' => $inventoryAccount->current_balance - $totalCost,
                'Pay_Method_Name' => $request->payment_type,
                'created_at' => $pData['created_at'],
                'updated_at' => Carbon::now('Asia/Riyadh'),
                'creditor' => $totalCost,
            ]);

            // في حال وجود مبالغ آجلة يتم تحديث رصيد العميل المحاسبي
            if ($pData['creaditamount'] != 0) {
                $customerData->increment('Balance', $pData['creaditamount']);

                $customerFinancialAccount = financial_accounts::where('orginal_type', 1)->where('orginal_id', $customerId)->first();
                $customerFinancialAccount->update([
                    'current_balance' => $customerFinancialAccount->current_balance + $pData['creaditamount'],
                    'debtor_current' => $customerFinancialAccount->debtor_current + $pData['creaditamount'],
                ]);

                CreditTransactions::create([
                    'user_id' => Auth::id(),
                    'customer_id' => $customerFinancialAccount->id,
                    'recive_amount' => $pData['creaditamount'],
                    'branchs_id' => Auth::user()->branchs_id,
                    'pay_method' => $request->payment_type,
                    'note' => 'فاتورة مبيعات رقم :' . $confirmInvoice->id,
                    'currentblance' => $customerFinancialAccount->current_balance,
                    'Pay_Method_Name' => $request->payment_type,
                    'created_at' => $pData['created_at'],
                    'updated_at' => Carbon::now('Asia/Riyadh'),
                    'debtor' => $pData['creaditamount']
                ]);
            }

            // تحديث الرصيد النهائي المسجل بالفاتورة
            $customerData->refresh(); // جلب البيانات المحدثة للعميل من قاعدة البيانات
            $confirmInvoice->update([
                'currentblance' => $customerData->Balance,
            ]);

            return $confirmInvoice->id;
        });
    }














    public function pending_invoice($invoiceId)
    {

        temp_invoice::find($invoiceId)->update(['pending_invoice' => 1]);

        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('products.sales');


    }

    public function update_pending_invoice($invoiceId)
    {


        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('products.sales_pending', compact('invoiceId'));


    }

   public function delete_product($productId)
{
    // استخدام exists() للتحقق السريع دون استهلاك الذاكرة
    $hasSales =sales::where('product_id', $productId)->exists();
    $hasPurchases = orderDetails::where('product_id', $productId)->exists();

    if (!$hasSales && !$hasPurchases) {
        $product = products::find($productId);
        if ($product) {
            $product->delete();
        }
    }

    $data = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);
    return view('ajax_search', compact('data'));
}

public function get_invoice_peeding($invoiceId)
{
    // جلب البيانات مع العلاقات مسبقاً لمنع مشكلة استعلامات N+1 داخل الحلقة
    $products = temp_sales::with(['productData', 'productData.product_group_data'])
        ->where('invoice_id', $invoiceId)
        ->where('quantity', '>=', 1)
        ->get();

    $allProdctsD = [];
    foreach ($products as $index => $product) {
        $updateProduct = $product->productData;

        if (!$updateProduct) continue;

        $allProdctsD[] = [
            'Product_Code'   => $updateProduct->Product_Code,
            'product_name'   => $updateProduct->product_name,
            'refnumber'      => $updateProduct->refnumber,
            'group_name'     => $updateProduct->product_group_data->group_ar ?? '-',
            'quantity'       => $product->quantity,
            'Unit_Price'     => $product->Unit_Price,
            'Discount_Value' => $product->Discount_Value,
            'reamingquantity'=> $updateProduct->numberofpice,
            'Added_Value'    => $product->Added_Value,
            'count'          => $index + 1,
            'id'             => $updateProduct->id,
        ];
    }

    $avtSaleRate = Avt::find(1)->AVT ?? 0.15;
    $InvoiceData = temp_invoice::find($invoiceId);
    $customer = customers::find($InvoiceData->customer_id);

    $netPrice = $InvoiceData->Price - $InvoiceData->discount;

    $data = [
        "invoicetotal_price"      => $netPrice,
        "invoicetotal_addedvalue" => $netPrice * $avtSaleRate,
        "invoicetotal_discount"   => $InvoiceData->discount,
        'invoice_number'          => $InvoiceData->id,
        'pay'                     => $InvoiceData->pay,
        'customer'                => $customer,
        'product'                 => $allProdctsD,
        "invoice_id"              => 0
    ];

    return $data;
}

public function update_sales_pending($invoiceId)
{
    LaravelLocalization::setLocale(LaravelLocalization::getCurrentLocale());
    return view('products.sales_pending', compact('invoiceId'));
}

public function pending_invoice_previes()
{
    LaravelLocalization::setLocale(LaravelLocalization::getCurrentLocale());
    return view('previousSalesInvoices_pending');
}

public function geta_jax_Recent_Invoices_pending()
{
    LaravelLocalization::setLocale(LaravelLocalization::getCurrentLocale());
    $data = temp_invoice::where('branchs_id', Auth()->user()->branchs_id)
        ->where('pending_invoice', 1)
        ->orderby('id', 'desc')
        ->paginate(20);
    return view('ajax_Recent_Invoices_pending', compact('data'));
}

public function updatepaymentconfirmpayment_in_quotation($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod)
{
    // إسناد القيم الافتراضية بشكل صحيح في حال كانت Null
    $cashamount = $cashamount ?? 0;
    $bankamount = $bankamount ?? 0;
    $creaditamount = $creaditamount ?? 0;
    $Bank_transfer = $Bank_transfer ?? 0;
    $total_cost = 0;

    $invoice = offer_price_to_customer::find($invoiceId);
    if (!$invoice) {
        return [0];
    }

    $avtSaleRate = Avt::find(1)->AVT ?? 0.15;
    $totalpricePurchases = 0;
    $totaldiscount = 0;
    $count = 0;

    // جلب عناصر عرض السعر
    $items = offer_price_to_customer_items::where('order_id', $invoiceId)->get();
    foreach ($items as $item) {
        $totalpricePurchases += ($item->PriceWithoudTax * $item->quantity);
        $totaldiscount += $item->discount;
        $count += $item->quantity;
    }

    $totalvat = round(($totalpricePurchases - ($totaldiscount + $invoice->discount)) * $avtSaleRate, 2);
    $total_invoice_on_invoice = $invoice->discount + $totaldiscount;

    // توقيت موحد وثابت للعملية بالكامل
    $currentDateTime = Carbon::now();

    // بدء المعاملة المالية الآمنة لمنع التضارب في قيود شجرة الحسابات
    DB::beginTransaction();

    try {
        $confirminvoice = invoices::create([
            'save'               => 1,
            'customer_id'        => $invoice->customer_id,
            'user_id'            => Auth()->user()->id,
            'Price'              => $totalpricePurchases,
            'Added_Value'        => $totalvat,
            'Pay'                => $paymentMethod,
            'status'             => Auth()->user()->branchs_id == $invoice->branchs_id ? 0 : 1,
            'branchs_id'         => Auth()->User()->branch->id,
            'discountOnProduct'  => $totaldiscount,
            'discount'           => $total_invoice_on_invoice,
            'Number_of_Quantity' => $count,
            'note'               => $invoice->notes ?? '-',
            'created_at'         => $currentDateTime,
            'updated_at'         => $currentDateTime,
            'morepayment_way'    => 1,
            'cashamount'         => $cashamount,
            'bankamount'         => $bankamount,
            'creaditamount'      => $creaditamount,
            'Bank_transfer'      => $Bank_transfer,
            'issue_date'         => $currentDateTime->toDateString(),
            'issue_time'         => $currentDateTime->toTimeString(),
        ]);

        foreach ($items as $sale) {
            $productdata = products::find($sale->product_id);
            if (!$productdata) continue;

            $total_cost += $productdata->PriceWithoudTax * $sale->quantity;

           sales::create([
                'user_id'        => Auth()->user()->id,
                'save'           => 1,
                'product_id'     => $sale->product_id,
                'invoice_id'     => $confirminvoice->id,
                'branch_id'      => Auth()->User()->branch->id,
                'Discount_Value' => $sale->discount,
                'Added_Value'    => ($sale->PriceWithoudTax * $avtSaleRate),
                'Unit_Price'     => $sale->PriceWithoudTax,
                'reamingQuantity'=> 0,
                'quantity'       => $sale->quantity,
                'created_at'     => $currentDateTime,
                'unit'           => $sale->unit,
            ]);

            if (Auth()->user()->branchs_id == $productdata->branchs_id) {
                if ($productdata->products_mix == 0) {
                    $productdata->decrement('numberofpice', $sale->quantity);
                } else {
                    $mixItems = products_mix_items::where('products_mix_id', $productdata->products_mix)->get();
                    foreach ($mixItems as $itemmix) {
                        products::where('id', $itemmix->product_id)->decrement('numberofpice', $itemmix->quantity);
                    }
                }
            }
        }

        // --- المعالجة المحاسبية لشجرة الحسابات عبر الدالة المساعدة بالأسفل ---
        $this->processAccountEntry(5, $cashamount, $confirminvoice->id, $paymentMethod, $currentDateTime, 'debtor');
        $this->processAccountEntry(4, $Bank_transfer + $bankamount, $confirminvoice->id, $paymentMethod, $currentDateTime, 'debtor');

        $total_value = $Bank_transfer + $creaditamount + $bankamount + $cashamount;
        $vat_amount = $total_value - ($total_value * 100 / 115);
        $net_sales_amount = $total_value * 100 / 115;

        $customerdata = customers::find($invoice->customer_id);

        // حساب الضريبة (102) وحساب المبيعات (112)
        $this->processAccountEntry(102, $vat_amount, $confirminvoice->id, $paymentMethod, $currentDateTime, 'creditor', $customerdata);
        $this->processAccountEntry(112, $net_sales_amount, $confirminvoice->id, $paymentMethod, $currentDateTime, 'creditor');

        // حساب تكلفة البضاعة المباعة (183) وحساب المخزون (181)
        $this->processAccountEntry(183, $total_cost, $confirminvoice->id, $paymentMethod, $currentDateTime, 'debtor');
        $this->processAccountEntry(181, $total_cost, $confirminvoice->id, $paymentMethod, $currentDateTime, 'creditor');

        // معالجة حساب العميل في حال البيع الآجل
        if ($creaditamount > 0 && $customerdata) {
            $customerdata->increment('Balance', $creaditamount);
            $customerAccount = financial_accounts::where('orginal_type', 1)->where('orginal_id', $invoice->customer_id)->first();

            if ($customerAccount) {
                $customerAccount->increment('current_balance', $creaditamount);
                $customerAccount->increment('debtor_current', $creaditamount);

                credittransactions::create([
                    'user_id'         => Auth()->user()->id,
                    'customer_id'     => $customerAccount->id,
                    'recive_amount'   => $creaditamount,
                    'branchs_id'      => Auth()->user()->branchs_id,
                    'pay_method'      => $paymentMethod,
                    'note'            => '  فاتورة مبيعات رقم :' . (string) $confirminvoice->id,
                    'currentblance'   => $customerAccount->current_balance,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at'      => $currentDateTime,
                    'updated_at'      => $currentDateTime,
                    'orginal_id'      => 0,
                    'creditor'        => 0,
                    'debtor'          => $creaditamount
                ]);
            }
        }

        // تحديث الرصيد النهائي على الفاتورة
        $confirminvoice->update(['currentblance' => customers::find($invoice->customer_id)->Balance]);

        // مسح عرض السعر بعد نجاح العملية
        $invoice->delete();

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }

    // جهوزية بيانات الطباعة والتفقيط
    $totAL = number_format($total_value, 2, '.', '');
    list($whole, $decimal) = explode('.', $totAL);

    $check = str_split($decimal);
    $decimal = ($check[0] == "0") ? (int)$check[1] : (int)$decimal;

    $setting = system_setting::find(1);

    $data = [
        "invoicetotal_price"      => number_format(($total_value * 100 / (100 + ($avtSaleRate * 100))), 2, '.', ''),
        "invoicetotal_addedvalue" => number_format((($total_value * 100 / (100 + ($avtSaleRate * 100)))) * $avtSaleRate, 2, '.', ''),
        "invoicetotal_discount"   => $confirminvoice->discount,
        'salesData'               =>sales::where("invoice_id", $confirminvoice->id)->where('quantity', '!=', 0)->get(),
        'invoiceData'             => $confirminvoice,
        'totatextlriyales'        => NumToArabic::number2Word(round((int) $whole, 2)) . '  ريال',
        'totatextlrihalala'       => $decimal != 0 ? NumToArabic::number2Word(round((int) $decimal, 2)) . '   هللة' : 'فقط لا غير',
    ];

    return view('products.printInvoicesToClient', compact('data'));
}

/**
 * دالة مساعدة موحدة لتحديث قيود شجرة الحسابات (الأب والفرع) وإنشاء حركات القيود
 */
private function processAccountEntry($accountId, $amount, $invoiceId, $paymentMethod, $dateTime, $entryType, $customerdata = null)
{
    if ($amount <= 0) return;

    $branchId = Auth()->user()->branchs_id;

    // 1. الحساب الرئيسي (الأب)
    $parentAccount = financial_accounts::find($accountId);
    if ($parentAccount) {
        $parentAccount->update([
            'current_balance'  => $entryType == 'debtor' ? $parentAccount->current_balance + $amount : $parentAccount->current_balance - $amount,
            'debtor_current'   => $entryType == 'debtor' ? $parentAccount->debtor_current + $amount : $parentAccount->debtor_current,
            'creditor_current' => $entryType == 'creditor' ? $parentAccount->creditor_current + $amount : $parentAccount->creditor_current,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => $accountId,
            'recive_amount'   => $amount,
            'branchs_id'      => $branchId,
            'pay_method'      => $paymentMethod,
            'note'            => '  فاتورة مبيعات رقم :' . (string) $invoiceId,
            'currentblance'   => $parentAccount->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $dateTime,
            'updated_at'      => $dateTime,
            'orginal_id'      => 0,
            'creditor'        => $entryType == 'creditor' ? $amount : 0,
            'debtor'          => $entryType == 'debtor' ? $amount : 0,
            'vat'             => $customerdata ? 1 : 0,
            'name'            => $customerdata->name ?? null,
            'tax'             => $customerdata->tax_no ?? null,
        ]);
    }

    // 2. الحساب الفرعي المرتبط بالفرع (الابن)
    $childAccount = financial_accounts::where('parent_account_number', $accountId)->where('branchs_id', $branchId)->first();
    if ($childAccount) {
        $childAccount->update([
            'current_balance'  => $entryType == 'debtor' ? $childAccount->current_balance + $amount : $childAccount->current_balance - $amount,
            'debtor_current'   => $entryType == 'debtor' ? $childAccount->debtor_current + $amount : $childAccount->debtor_current,
            'creditor_current' => $entryType == 'creditor' ? $childAccount->creditor_current + $amount : $childAccount->creditor_current,
        ]);

        credittransactions::create([
            'user_id'         => Auth()->user()->id,
            'customer_id'     => $childAccount->id,
            'recive_amount'   => $amount,
            'branchs_id'      => $branchId,
            'pay_method'      => $paymentMethod,
            'note'            => '  فاتورة مبيعات رقم :' . (string) $invoiceId,
            'currentblance'   => $childAccount->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at'      => $dateTime,
            'updated_at'      => $dateTime,
            'orginal_id'      => 0,
            'creditor'        => $entryType == 'creditor' ? $amount : 0,
            'debtor'          => $entryType == 'debtor' ? $amount : 0,
            'vat'             => $customerdata ? 1 : 0,
            'name'            => $customerdata->name ?? null,
            'tax'             => $customerdata->tax_no ?? null,
        ]);
    }
}












function sent_to_zatca_return_items($request)
    {
        // - Then Call Invoice required data from database depend on your query statment and required company id

        $setting = settings::where('branchs_id', 1)->first();

        $previous_invoice = null;
        $invoice = invoices::find($request);



        if ($invoice->document_type == 'standard') {
            if (
                is_null($invoice->customer->name) || is_null($invoice->customer->postcode) || is_null($invoice->customer->address) || is_null($invoice->customer->sub_city) ||
                is_null($invoice->customer->plot_identification) || is_null($invoice->customer->building_number) || is_null($invoice->customer->street_name) || is_null($invoice->customer->tax_no) || strlen($invoice->customer->tax_no) != 15
            ) {
                return "  \n Please enter the full national address and tax number information. Thank you يرجل ادخال بيانات العنوان الوطني و الرقم الضريبيي كاملا وشكرا";

            }

        }
        $invprevious = $setting->previous_hash_invoice;

        if ($invprevious == null) {
            $previous_invoice = 'X+zrZv/IbzjZUnhsbWlsecLbwjndTpG0ZynXOif7V+k=';

        } else {
            $previous_invoice = $invprevious;
        }
        $myuuid = Uuid::uuid4();

        $created_at = \Carbon\Carbon::now()->addHours(3);
        invoices::find($request)->update(
            [
                'issue_date_return' => substr($created_at, 0, 10),
                'issue_time_return' => substr($created_at, 11),
                'uuid' => $myuuid
            ]
        );

        $invoice = invoices::find($request);

        $rat_tax = 0;


        $total_withot_tax_sum = 0;
        $tax_sum = 0;
        $total_with_tax_sum = 0;
        $invoiceLines = [];



        foreach (return_sales::where("invoice_id", $request)->where('return_quantity', '!=', 0)->where('send_zatca', 0)->get() as $item) {
            return_sales::find($item->id)->update(['send_zatca' => 1]);
            $price_each_element_withoud_tax = number_format(($item->return_Unit_Price - ($item->discountvalue / $item->return_quantity)), 2, '.', '');
            $temp = ($item->return_Unit_Price - ($item->discountvalue / $item->return_quantity)) * $item->return_quantity;
            $total_withot_tax = number_format($temp, 2, '.', '');
            $temp = ($item->return_Added_Value) * $item->return_quantity;
            $tax = number_format($temp, 2, '.', '');
            $totlal_element = $total_withot_tax + $tax;

            $total_with_tax = number_format($totlal_element, 2, '.', '');
            $total_withot_tax_sum = $total_withot_tax_sum + $total_withot_tax * 1;
            $tax_sum = $tax_sum + $tax;
            $total_with_tax_sum = $total_with_tax_sum + $total_with_tax;
             $rat_tax = number_format($item->tax_rate, 2, '.', '') ;
            $taxCategoryCode = ($item->tax_rate == 0) ? 'E' : 'S';
            $itemTaxCategory = (new LineTaxCategory())
                ->setTaxCategory($taxCategoryCode)
                ->setTaxPercentage($rat_tax)
                ->getElement();
            $invoiceLines[] = (new InvoiceLine())
                ->setLineID($item->product_id)
                ->setLineName($item->productData->product_name)
                ->setLineCurrency('SAR')
                ->setLinePrice(number_format($price_each_element_withoud_tax, 2, '.', ''))
                ->setLineQuantity($item->return_quantity)
                ->setLineSubTotal($total_withot_tax)
                ->setLineTaxTotal($tax)
                ->setLineNetTotal($total_with_tax)
                ->setLineTaxCategories($itemTaxCategory)
                ->setLineDiscountReason('Discount on product')
                ->setLineDiscountAmount(0)
                ->getElement();
        }



        $total_withot_tax_sum = number_format($total_withot_tax_sum, 2, '.', '');
        $tax_sum = number_format($tax_sum, 2, '.', '');
        ;
        $total_with_tax_sum = number_format($total_with_tax_sum, 2, '.', '');






        // clients data


        $client = (new Client())
            ->setVatNumber($invoice->customer->tax_no)
            ->setStreetName($invoice->customer->street_name)
            ->setBuildingNumber($invoice->customer->building_number)
            ->setPlotIdentification($invoice->customer->plot_identification)
            ->setSubDivisionName($invoice->customer->sub_city)
            ->setCityName($invoice->customer->address)
            ->setPostalNumber($invoice->customer->postcode)
            ->setCountryName('SA')
            ->setClientName($invoice->customer->name);


        $supplier = (new Supplier())
            ->setCrn($setting->crn)
            ->setStreetName($setting->street_name)
            ->setBuildingNumber($setting->building_number)
            ->setPlotIdentification($setting->plot_identification)
            ->setSubDivisionName($setting->region)
            ->setCityName($setting->city)
            ->setPostalNumber($setting->postal_number)
            ->setCountryName('SA')
            ->setVatNumber($setting->trn)
            ->setVatName($setting->name);

        $delivery = (new Delivery())
            ->setDeliveryDateTime($invoice->issue_date);

        $paymentType = (new PaymentType())
            ->setPaymentType('10');

        $returnReason = (new ReturnReason())
            ->setReturnReason('Sales returns');

        $previous_hash = (new PIH())
            ->setPIH($previous_invoice);  // note this value it from step 3 , 4
        $billingReference = (new BillingReference())
            ->setBillingReference($request); // note this used when type credit or debit this value of parent invoice id

        $additionalDocumentReference = (new AdditionalDocumentReference())
            ->setInvoiceID($setting->invoices_count + 1); // note this value it from step 1

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setTotalCurrency('SAR')
            ->setLineExtensionAmount($total_withot_tax_sum)
            ->setTaxExclusiveAmount($total_withot_tax_sum)
            ->setTaxInclusiveAmount($total_with_tax_sum)
            ->setAllowanceTotalAmount(0)
            ->setPrepaidAmount(0)
            ->setPayableAmount($total_with_tax_sum);

        $taxesTotal = (new TaxesTotal())
            ->setTaxCurrencyCode('SAR')
            ->setTaxTotal($tax_sum);
      $current_item_tax_rate = ($tax_sum > 0) ? 15 : 0; // افترضنا 15، يمكن استبدالها بـ $item->tax_rate لو متوفر
      $taxCategoryCode = ($current_item_tax_rate == 0) ? 'E' : 'S';

        $taxSubtotal = (new TaxSubtotal())
            ->setTaxCurrencyCode('SAR')
            ->setTaxableAmount($total_withot_tax_sum)
            ->setTaxAmount($tax_sum)
            ->setTaxCategory( $taxCategoryCode)
            ->setTaxPercentage($rat_tax)
            ->getElement();


        $allowanceCharge = (new AllowanceCharge())
            ->setAllowanceChargeCurrency('SAR')
            ->setAllowanceChargeIndex('1')
            ->setAllowanceChargeAmount(0)
            ->setAllowanceChargeTaxCategory( $taxCategoryCode)
            ->setAllowanceChargeTaxPercentage($rat_tax)
            ->getElement();



        if (strlen($invoice->customer->tax_no) != 15) {



            $response = (new InvoiceGenerator())
                ->setZatcaEnv($setting->is_production ? 'core' : 'simulation')
                ->setZatcaLang('en')
                ->setInvoiceNumber($invoice->NOTICE_Number)
                ->setInvoiceUuid($myuuid) // this value from step 6
                ->setInvoiceIssueDate($invoice->issue_date_return)
                ->setInvoiceIssueTime($invoice->issue_time_return)
                ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000', "381")
                ->setInvoiceCurrencyCode('SAR')
                ->setInvoiceTaxCurrencyCode('SAR')
                ->setInvoiceBillingReference($billingReference) // use this when document type is credit or debit
                ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
                ->setInvoicePIH($previous_hash)
                ->setInvoiceSupplier($supplier)
                ->setInvoiceDelivery($delivery)
                ->setInvoicePaymentType($paymentType)
                ->setInvoiceReturnReason($returnReason) //use this when document type is credit or debit
                ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
                ->setInvoiceTaxesTotal($taxesTotal)
                ->setInvoiceTaxSubTotal($taxSubtotal)
                ->setInvoiceAllowanceCharges($allowanceCharge)
                ->setInvoiceLines(...$invoiceLines)
                ->setCertificateEncoded($setting->production_certificate)
                ->setPrivateKeyEncoded($setting->private_key)
                ->setCertificateSecret($setting->production_secret)
                ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)


        } else {
            $response = (new InvoiceGenerator())
                ->setZatcaEnv($setting->is_production ? 'core' : 'simulation')
                ->setZatcaLang('en')
                ->setInvoiceNumber($invoice->NOTICE_Number)
                ->setInvoiceUuid($myuuid) // this value from step 6
                ->setInvoiceIssueDate($invoice->issue_date_return)
                ->setInvoiceIssueTime($invoice->issue_time_return)
                ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000', "381")
                ->setInvoiceCurrencyCode('SAR')
                ->setInvoiceTaxCurrencyCode('SAR')
                ->setInvoiceBillingReference($billingReference) // use this when document type is credit or debit
                ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
                ->setInvoicePIH($previous_hash)
                ->setInvoiceSupplier($supplier)
                ->setInvoiceClient($client)
                ->setInvoiceDelivery($delivery)
                ->setInvoicePaymentType($paymentType)
                ->setInvoiceReturnReason($returnReason) //use this when document type is credit or debit
                ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
                ->setInvoiceTaxesTotal($taxesTotal)
                ->setInvoiceTaxSubTotal($taxSubtotal)
                ->setInvoiceAllowanceCharges($allowanceCharge)
                ->setInvoiceLines(...$invoiceLines)
                ->setCertificateEncoded($setting->production_certificate)
                ->setPrivateKeyEncoded($setting->private_key)
                ->setCertificateSecret($setting->production_secret)
                ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
        }

        if ($response['success']) {
            settings::where('branchs_id', 1)->update([
                'previous_hash_invoice' => $response['hash'],
                'invoices_count' => $setting->invoices_count + 1
            ]);
            invoices::find($request)->update(
                [
                    'qr_zatca_return' => \Carbon\Carbon::now(),
                    'sent_to_zatca_status_return' => "PASS",
                    'xmltags_return' => $response['xml'],
                    'xml_return' => $invoice->document_type == 'simplified' ? NULL : $response['response']->clearedInvoice

                ]
            );
            return 1;

        } else {

            return '|||' . $response['response']->reportingStatus . '   ||| ERROR MESSAGE    :-   ' . $response['response']->validationResults->errorMessages[0]->message;
        }
    }

    function sent_to_zatca($request)
    {
        // - Then Call Invoice required data from database depend on your query statment and required company id

        $setting = settings::where('branchs_id', 1)->first();

        ### Zatca Integration have two steps second : Send Invoices to zatca Step example :
        // - Add below line to start of controller file which used
        $previous_invoice = null;
        $invoice = invoices::find($request);
        $invprevious = $setting->previous_hash_invoice;

        if ($invprevious == null) {
            $previous_invoice = 'X+zrZv/IbzjZUnhsbWlsecLbwjndTpG0ZynXOif7V+k=';

        } else {
            $previous_invoice = $invprevious;
        }

        $myuuid = Uuid::uuid4();

        invoices::find($request)->update(
            [
                'invoice_counter' => $setting->invoices_count + 1,
                'invoice_number' => $invoice->id,
                'invoiceUUid' => $myuuid,
                'document_type' => strlen($invoice->customer->tax_no) != 15 ? 'simplified' : 'standard',
                'invoice_type' => "388", //  "388" NORMAL INVOICE , "383"  DEBIT_NOTE , "381" CREDIT_NOTE
                'issue_date' => substr($invoice->created_at, 0, 10),
                'issue_time' => substr($invoice->created_at, 11),
            ]
        );


        $invoice = invoices::find($request);

        if ($invoice->document_type == 'standard') {
            if (
                is_null($invoice->customer->name) || is_null($invoice->customer->postcode) || is_null($invoice->customer->address) || is_null($invoice->customer->sub_city) ||
                is_null($invoice->customer->plot_identification) || is_null($invoice->customer->building_number) || is_null($invoice->customer->street_name) || is_null($invoice->customer->tax_no) || strlen($invoice->customer->tax_no) != 15
            ) {
                return "  \n Please enter the full national address and tax number information. Thank you يرجل ادخال بيانات العنوان الوطني و الرقم الضريبيي كاملا وشكرا";

            }

        }



        $total_withot_tax_sum = 0;
        $tax_sum = 0;
        $total_with_tax_sum = 0;
        $invoiceLines = [];



        foreach (sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get() as $item) {
            $price_each_element_withoud_tax = number_format(($item->Unit_Price - ($item->Discount_Value / $item->quantity)), 2, '.', '');
            $temp = ($item->Unit_Price - ($item->Discount_Value / $item->quantity)) * $item->quantity;
            $total_withot_tax = number_format($temp, 2, '.', '');
            $temp = (($item->Added_Value ) ) * $item->quantity;
            $tax = number_format($temp, 2, '.', '');
            $totlal_element = $total_withot_tax + $tax;
            $current_item_tax_rate = ($item->Added_Value > 0) ? 15 : 0; // افترضنا 15، يمكن استبدالها بـ $item->tax_rate لو متوفر
            $taxCategoryCode = ($current_item_tax_rate == 0) ? 'E' : 'S';


            $total_with_tax = number_format($totlal_element, 2, '.', '');
            $total_withot_tax_sum = $total_withot_tax_sum + $total_withot_tax * 1;
            $tax_sum = $tax_sum + $tax;
            $total_with_tax_sum = $total_with_tax_sum + $total_with_tax;
             $rat_tax = number_format($item->tax_rate, 2, '.', '') * 100;

            $itemTaxCategory = (new LineTaxCategory())
                ->setTaxCategory($taxCategoryCode)
                ->setTaxPercentage($rat_tax)
                ->getElement();
            $invoiceLines[] = (new InvoiceLine())
                ->setLineID($item->product_id)
                ->setLineName($item->productData->product_name)
                ->setLineCurrency('SAR')
                ->setLinePrice(number_format($price_each_element_withoud_tax, 2, '.', ''))
                ->setLineQuantity($item->quantity)
                ->setLineSubTotal($total_withot_tax)
                ->setLineTaxTotal($tax)
                ->setLineNetTotal($total_with_tax)
                ->setLineTaxCategories($itemTaxCategory)
                ->setLineDiscountReason('Discount on product')
                ->setLineDiscountAmount(0)
                ->getElement();
        }
        //  return $invoiceLines;
        $total_withot_tax_sum = number_format($total_withot_tax_sum, 2, '.', '');
        $tax_sum = number_format($tax_sum, 2, '.', '');
        ;
        $total_with_tax_sum = number_format($total_with_tax_sum, 2, '.', '');



        // - If Invoice type is standard invoice (B2B) you must provide full buyer information as below :



        // clients data
        $client = (new Client())
            ->setVatNumber($invoice->customer->tax_no)
            ->setStreetName($invoice->customer->street_name)
            ->setBuildingNumber($invoice->customer->building_number)
            ->setPlotIdentification($invoice->customer->plot_identification)
            ->setSubDivisionName($invoice->customer->sub_city)
            ->setCityName($invoice->customer->address)
            ->setPostalNumber($invoice->customer->postcode)
            ->setCountryName('SA')
            ->setClientName($invoice->customer->name);


        //  return $client->getElement();
        $supplier = (new Supplier())
            ->setCrn($setting->crn)
            ->setStreetName($setting->street_name)
            ->setBuildingNumber($setting->building_number)
            ->setPlotIdentification($setting->plot_identification)
            ->setSubDivisionName($setting->region)
            ->setCityName($setting->city)
            ->setPostalNumber($setting->postal_number)
            ->setCountryName('SA')
            ->setVatNumber($setting->trn)
            ->setVatName($setting->name);
        // return $supplier->getElement();

        $delivery = (new Delivery())
            ->setDeliveryDateTime($invoice->issue_date);

        $paymentType = (new PaymentType())
            ->setPaymentType('10');

        $returnReason = (new ReturnReason())
            ->setReturnReason('SET_RETURN_REASON');

        $previous_hash = (new PIH())
            ->setPIH($previous_invoice);  // note this value it from step 3 , 4
        // $billingReference = (new BillingReference())
        // ->setBillingReference('23'); // note this used when type credit or debit this value of parent invoice id

        $additionalDocumentReference = (new AdditionalDocumentReference())
            ->setInvoiceID($setting->invoices_count + 1); // note this value it from step 1

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setTotalCurrency('SAR')
            ->setLineExtensionAmount($total_withot_tax_sum)
            ->setTaxExclusiveAmount($total_withot_tax_sum)
            ->setTaxInclusiveAmount($total_with_tax_sum)
            ->setAllowanceTotalAmount(0)
            ->setPrepaidAmount(0)
            ->setPayableAmount($total_with_tax_sum);

        $taxesTotal = (new TaxesTotal())
            ->setTaxCurrencyCode('SAR')
            ->setTaxTotal($tax_sum);
    $current_item_tax_rate = ($tax_sum > 0) ? 15 : 0; // افترضنا 15، يمكن استبدالها بـ $item->tax_rate لو متوفر
    $taxCategoryCode = ($current_item_tax_rate == 0) ? 'E' : 'S';

        $taxSubtotal = (new TaxSubtotal())
            ->setTaxCurrencyCode('SAR')
            ->setTaxableAmount($total_withot_tax_sum)
            ->setTaxAmount($tax_sum)
            ->setTaxCategory( $taxCategoryCode)
            ->setTaxPercentage($rat_tax)
            ->getElement();


        $allowanceCharge = (new AllowanceCharge())
            ->setAllowanceChargeCurrency('SAR')
            ->setAllowanceChargeIndex('1')
            ->setAllowanceChargeAmount(0)
            ->setAllowanceChargeTaxCategory( $taxCategoryCode)
            ->setAllowanceChargeTaxPercentage($rat_tax)
            ->getElement();
        if (strlen($invoice->customer->tax_no) != 15) {

            $response = (new InvoiceGenerator())
                ->setZatcaEnv($setting->is_production ? 'core' : 'simulation')
                ->setZatcaLang('en')
                ->setInvoiceNumber($request)
                ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
                ->setInvoiceIssueDate($invoice->issue_date)
                ->setInvoiceIssueTime($invoice->issue_time)
                ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000', $invoice->invoice_type)
                ->setInvoiceCurrencyCode('SAR')
                ->setInvoiceTaxCurrencyCode('SAR')
                //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
                ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
                ->setInvoicePIH($previous_hash)
                ->setInvoiceSupplier($supplier)
                ->setInvoiceDelivery($delivery)
                ->setInvoicePaymentType($paymentType)
                //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
                ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
                ->setInvoiceTaxesTotal($taxesTotal)
                ->setInvoiceTaxSubTotal($taxSubtotal)
                ->setInvoiceAllowanceCharges($allowanceCharge)
                ->setInvoiceLines(...$invoiceLines)
                ->setCertificateEncoded($setting->production_certificate)
                ->setPrivateKeyEncoded($setting->private_key)
                ->setCertificateSecret($setting->production_secret)
                ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
            //   return $response;


        } else {
            $response = (new InvoiceGenerator())
                ->setZatcaEnv($setting->is_production ? 'core' : 'simulation')
                ->setZatcaLang('en')
                ->setInvoiceNumber($request)
                ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
                ->setInvoiceIssueDate($invoice->issue_date)
                ->setInvoiceIssueTime($invoice->issue_time)
                ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000', $invoice->invoice_type)
                ->setInvoiceCurrencyCode('SAR')
                ->setInvoiceTaxCurrencyCode('SAR')
                //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
                ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
                ->setInvoicePIH($previous_hash)
                ->setInvoiceSupplier($supplier)
                ->setInvoiceClient($client)
                ->setInvoiceDelivery($delivery)
                ->setInvoicePaymentType($paymentType)
                //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
                ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
                ->setInvoiceTaxesTotal($taxesTotal)
                ->setInvoiceTaxSubTotal($taxSubtotal)
                ->setInvoiceAllowanceCharges($allowanceCharge)
                ->setInvoiceLines(...$invoiceLines)
                ->setCertificateEncoded($setting->production_certificate)
                ->setPrivateKeyEncoded($setting->private_key)
                ->setCertificateSecret($setting->production_secret)
                ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
        }

        if ($response['success']) {
            settings::where('branchs_id', 1)->update([
                'previous_hash_invoice' => $response['hash'],
                'invoices_count' => $setting->invoices_count + 1
            ]);
            invoices::find($request)->update(
                [
                    'signing_time' => \Carbon\Carbon::now(),
                    'hash' => $response['hash'],
                    'xml' => $response['xml'],
                    'sent_to_zatca_status' => "PASS",
                    'sent_to_zatca' => 1,
                    'clearedInvoice' => $invoice->document_type == 'simplified' ? NULL : $response['response']->clearedInvoice

                ]
            );

            return 1;
        } else {
            return $response;

            return '|||' . $response['response']->reportingStatus . '   ||| ERROR MESSAGE    :-   ' . $response['response']->validationResults->errorMessages[0]->message;
        }
    }











function sendzatca_fromsale($request)
{
        // - Then Call Invoice required data from database depend on your query statment and required company id

        $setting = settings::where('branchs_id', 1)->first();

        ### Zatca Integration have two steps second : Send Invoices to zatca Step example :
        // - Add below line to start of controller file which used
        $previous_invoice = null;
        $invoice = invoices::find($request);
        $invprevious = $setting->previous_hash_invoice;

        if ($invprevious == null) {
            $previous_invoice = 'X+zrZv/IbzjZUnhsbWlsecLbwjndTpG0ZynXOif7V+k=';

        } else {
            $previous_invoice = $invprevious;
        }

        $myuuid = Uuid::uuid4();

        invoices::find($request)->update(
            [
                'invoice_counter' => $setting->invoices_count + 1,
                'invoice_number' => $invoice->id,
                'invoiceUUid' => $myuuid,
                'document_type' => strlen($invoice->customer->tax_no) != 15 ? 'simplified' : 'standard',
                'invoice_type' => "388", //  "388" NORMAL INVOICE , "383"  DEBIT_NOTE , "381" CREDIT_NOTE
                'issue_date' => substr($invoice->created_at, 0, 10),
                'issue_time' => substr($invoice->created_at, 11),
            ]
        );


        $invoice = invoices::find($request);

        if ($invoice->document_type == 'standard') {
            if (
                is_null($invoice->customer->name) || is_null($invoice->customer->postcode) || is_null($invoice->customer->address) || is_null($invoice->customer->sub_city) ||
                is_null($invoice->customer->plot_identification) || is_null($invoice->customer->building_number) || is_null($invoice->customer->street_name) || is_null($invoice->customer->tax_no) || strlen($invoice->customer->tax_no) != 15
            ) {
                return "  \n Please enter the full national address and tax number information. Thank you يرجل ادخال بيانات العنوان الوطني و الرقم الضريبيي كاملا وشكرا";

            }

        }



        $total_withot_tax_sum = 0;
        $tax_sum = 0;
        $total_with_tax_sum = 0;
        $invoiceLines = [];



        foreach (sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get() as $item) {
            $price_each_element_withoud_tax = number_format(($item->Unit_Price - ($item->Discount_Value / $item->quantity)), 2, '.', '');
            $temp = ($item->Unit_Price - ($item->Discount_Value / $item->quantity)) * $item->quantity;
            $total_withot_tax = number_format($temp, 2, '.', '');
            $temp = (($item->Added_Value ) ) * $item->quantity;
            $tax = number_format($temp, 2, '.', '');
            $totlal_element = $total_withot_tax + $tax;
            $current_item_tax_rate = ($item->Added_Value > 0) ? 15 : 0; // افترضنا 15، يمكن استبدالها بـ $item->tax_rate لو متوفر
            $taxCategoryCode = ($current_item_tax_rate == 0) ? 'E' : 'S';


            $total_with_tax = number_format($totlal_element, 2, '.', '');
            $total_withot_tax_sum = $total_withot_tax_sum + $total_withot_tax * 1;
            $tax_sum = $tax_sum + $tax;
            $total_with_tax_sum = $total_with_tax_sum + $total_with_tax;
             $rat_tax = number_format($item->tax_rate, 2, '.', '') * 100;

            $itemTaxCategory = (new LineTaxCategory())
                ->setTaxCategory($taxCategoryCode)
                ->setTaxPercentage($rat_tax)
                ->getElement();
            $invoiceLines[] = (new InvoiceLine())
                ->setLineID($item->product_id)
                ->setLineName($item->productData->product_name)
                ->setLineCurrency('SAR')
                ->setLinePrice(number_format($price_each_element_withoud_tax, 2, '.', ''))
                ->setLineQuantity($item->quantity)
                ->setLineSubTotal($total_withot_tax)
                ->setLineTaxTotal($tax)
                ->setLineNetTotal($total_with_tax)
                ->setLineTaxCategories($itemTaxCategory)
                ->setLineDiscountReason('Discount on product')
                ->setLineDiscountAmount(0)
                ->getElement();
        }
        //  return $invoiceLines;
        $total_withot_tax_sum = number_format($total_withot_tax_sum, 2, '.', '');
        $tax_sum = number_format($tax_sum, 2, '.', '');
        ;
        $total_with_tax_sum = number_format($total_with_tax_sum, 2, '.', '');



        // - If Invoice type is standard invoice (B2B) you must provide full buyer information as below :



        // clients data
        $client = (new Client())
            ->setVatNumber($invoice->customer->tax_no)
            ->setStreetName($invoice->customer->street_name)
            ->setBuildingNumber($invoice->customer->building_number)
            ->setPlotIdentification($invoice->customer->plot_identification)
            ->setSubDivisionName($invoice->customer->sub_city)
            ->setCityName($invoice->customer->address)
            ->setPostalNumber($invoice->customer->postcode)
            ->setCountryName('SA')
            ->setClientName($invoice->customer->name);


        //  return $client->getElement();
        $supplier = (new Supplier())
            ->setCrn($setting->crn)
            ->setStreetName($setting->street_name)
            ->setBuildingNumber($setting->building_number)
            ->setPlotIdentification($setting->plot_identification)
            ->setSubDivisionName($setting->region)
            ->setCityName($setting->city)
            ->setPostalNumber($setting->postal_number)
            ->setCountryName('SA')
            ->setVatNumber($setting->trn)
            ->setVatName($setting->name);
        // return $supplier->getElement();

        $delivery = (new Delivery())
            ->setDeliveryDateTime($invoice->issue_date);

        $paymentType = (new PaymentType())
            ->setPaymentType('10');

        $returnReason = (new ReturnReason())
            ->setReturnReason('SET_RETURN_REASON');

        $previous_hash = (new PIH())
            ->setPIH($previous_invoice);  // note this value it from step 3 , 4
        // $billingReference = (new BillingReference())
        // ->setBillingReference('23'); // note this used when type credit or debit this value of parent invoice id

        $additionalDocumentReference = (new AdditionalDocumentReference())
            ->setInvoiceID($setting->invoices_count + 1); // note this value it from step 1

        $legalMonetaryTotal = (new LegalMonetaryTotal())
            ->setTotalCurrency('SAR')
            ->setLineExtensionAmount($total_withot_tax_sum)
            ->setTaxExclusiveAmount($total_withot_tax_sum)
            ->setTaxInclusiveAmount($total_with_tax_sum)
            ->setAllowanceTotalAmount(0)
            ->setPrepaidAmount(0)
            ->setPayableAmount($total_with_tax_sum);

        $taxesTotal = (new TaxesTotal())
            ->setTaxCurrencyCode('SAR')
            ->setTaxTotal($tax_sum);
$current_item_tax_rate = ($tax_sum > 0) ? 15 : 0; // افترضنا 15، يمكن استبدالها بـ $item->tax_rate لو متوفر
    $taxCategoryCode = ($current_item_tax_rate == 0) ? 'E' : 'S';

        $taxSubtotal = (new TaxSubtotal())
            ->setTaxCurrencyCode('SAR')
            ->setTaxableAmount($total_withot_tax_sum)
            ->setTaxAmount($tax_sum)
            ->setTaxCategory( $taxCategoryCode)
            ->setTaxPercentage($rat_tax)
            ->getElement();


        $allowanceCharge = (new AllowanceCharge())
            ->setAllowanceChargeCurrency('SAR')
            ->setAllowanceChargeIndex('1')
            ->setAllowanceChargeAmount(0)
            ->setAllowanceChargeTaxCategory( $taxCategoryCode)
            ->setAllowanceChargeTaxPercentage($rat_tax)
            ->getElement();
        if (strlen($invoice->customer->tax_no) != 15) {

            $response = (new InvoiceGenerator())
                ->setZatcaEnv($setting->is_production ? 'core' : 'simulation')
                ->setZatcaLang('en')
                ->setInvoiceNumber($request)
                ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
                ->setInvoiceIssueDate($invoice->issue_date)
                ->setInvoiceIssueTime($invoice->issue_time)
                ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000', $invoice->invoice_type)
                ->setInvoiceCurrencyCode('SAR')
                ->setInvoiceTaxCurrencyCode('SAR')
                //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
                ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
                ->setInvoicePIH($previous_hash)
                ->setInvoiceSupplier($supplier)
                ->setInvoiceDelivery($delivery)
                ->setInvoicePaymentType($paymentType)
                //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
                ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
                ->setInvoiceTaxesTotal($taxesTotal)
                ->setInvoiceTaxSubTotal($taxSubtotal)
                ->setInvoiceAllowanceCharges($allowanceCharge)
                ->setInvoiceLines(...$invoiceLines)
                ->setCertificateEncoded($setting->production_certificate)
                ->setPrivateKeyEncoded($setting->private_key)
                ->setCertificateSecret($setting->production_secret)
                ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
            //   return $response;


        } else {
            $response = (new InvoiceGenerator())
                ->setZatcaEnv($setting->is_production ? 'core' : 'simulation')
                ->setZatcaLang('en')
                ->setInvoiceNumber($request)
                ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
                ->setInvoiceIssueDate($invoice->issue_date)
                ->setInvoiceIssueTime($invoice->issue_time)
                ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000', $invoice->invoice_type)
                ->setInvoiceCurrencyCode('SAR')
                ->setInvoiceTaxCurrencyCode('SAR')
                //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
                ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
                ->setInvoicePIH($previous_hash)
                ->setInvoiceSupplier($supplier)
                ->setInvoiceClient($client)
                ->setInvoiceDelivery($delivery)
                ->setInvoicePaymentType($paymentType)
                //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
                ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
                ->setInvoiceTaxesTotal($taxesTotal)
                ->setInvoiceTaxSubTotal($taxSubtotal)
                ->setInvoiceAllowanceCharges($allowanceCharge)
                ->setInvoiceLines(...$invoiceLines)
                ->setCertificateEncoded($setting->production_certificate)
                ->setPrivateKeyEncoded($setting->private_key)
                ->setCertificateSecret($setting->production_secret)
                ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
        }

        if ($response['success']) {
            settings::where('branchs_id', 1)->update([
                'previous_hash_invoice' => $response['hash'],
                'invoices_count' => $setting->invoices_count + 1
            ]);
            invoices::find($request)->update(
                [
                    'signing_time' => \Carbon\Carbon::now(),
                    'hash' => $response['hash'],
                    'xml' => $response['xml'],
                    'sent_to_zatca_status' => "PASS",
                    'sent_to_zatca' => 1,
                    'clearedInvoice' => $invoice->document_type == 'simplified' ? NULL : $response['response']->clearedInvoice

                ]
            );

            return 1;
        } else {
            return $response;

            return '|||' . $response['response']->reportingStatus . '   ||| ERROR MESSAGE    :-   ' . $response['response']->validationResults->errorMessages[0]->message;
        }
    }

function dwonloadxml($id)
{
    $invoice = invoices::find($id);
    if (!$invoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

    $xml = new DOMDocument;
    // Safe-fallback context matching cleared vs standard xml parameters
    $rawXml = base64_decode($invoice->clearedInvoice ?? $invoice->xml, true);

    if (!$rawXml) {
        return "Failed to decode XML content.";
    }

    $xml->loadXML($rawXml);
    $xml->formatOutput = true;

    $namefile = "invoice_" . $invoice->id . '_' . date("Y_m_d") . 'T' . date("H_i") . ".xml";
    $filepath = public_path('result.xml');
    $xml->save($filepath);

    $headers = [
        'Content-Type' => 'application/xml',
    ];

    return response()->download($filepath, $namefile, $headers);
}


public function updateinvoicebyidforsaleupdate($invoiceNumber)
{
    $InvoiceData = invoices::find($invoiceNumber);
    if (!$InvoiceData) {
        return 0;
    }

    $avtSaleRate = Avt::find(1);

    // Eager load productData to avoid N+1 query performance bottleneck
    $products =sales::with('productData')->where('invoice_id', $invoiceNumber)->get();

    $allProdctsD = [];
    foreach ($products as $index => $product) {
        // Safe check if relationship exists
        $productCode = $product->productData ? $product->productData->Product_Code : '';
        $productName = $product->productData ? $product->productData->product_name : '';
        $numberOfPieces = $product->productData ? $product->productData->numberofpice : 0;

        $allProdctsD[] = [
            'Product_Code'     => $productCode,
            'product_name'     => $productName,
            'quantity'         => $product->quantity,
            'Unit_Price'       => $product->Unit_Price,
            'Discount_Value'   => $product->Discount_Value,
            'reamingquantity'  => $numberOfPieces, // Retained original key to avoid breaking frontend
            'Added_Value'      => $product->Added_Value,
            'count'            => $index + 1,
            'id'               => $product->product_id
        ];
    }

    $customer = customers::find($InvoiceData->customer_id);
    $netPrice = $InvoiceData->Price - $InvoiceData->discount;

    $data = [
        "invoicetotal_price"      => $netPrice,
        "invoicetotal_addedvalue" => $netPrice * ($avtSaleRate->AVT ?? 0),
        "invoicetotal_discount"   => $InvoiceData->discount,
        'invoice_number'          => $InvoiceData->id,
        'pay'                     => $InvoiceData->pay,
        'customer'                => $customer,
        'product'                 => $allProdctsD,
        "invoice_id"              => $InvoiceData->id
    ];

    return $data;
}

public function updatepaymentconfirmpaymentpurchases($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod)
{
    if (empty($invoiceId)) {
        return [0];
    }

    // Wrap all ledger and balance adjustments in a transaction for safety
    return DB::transaction(function () use ($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod) {
        $invoice = resource_purchases::where('orderId', $invoiceId)->first();
        if (!$invoice) {
            return [0];
        }

        $branchId = Auth::user()->branchs_id;
        $userId = Auth::user()->id;
        $now = Carbon::now()->addHours(3);

        $oldpayment = 0;
        $oldpayment_parent = 0;
        $totalAmount = $cashamount + $bankamount + $creaditamount + $Bank_transfer;

        // 1. Revert Old Payment Method Balance Effects
        if (in_array($invoice->Pay_Method_Name, ['Shabka', 'Bank_transfer'])) {
            $oldpayment = 4;
            $financial_account = financial_accounts::find(4);
            if ($financial_account) {
                $financial_account->update([
                    'current_balance'  => $financial_account->current_balance + $totalAmount,
                    'creditor_current' => $financial_account->creditor_current - $totalAmount,
                ]);
            }

            $parent_account = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchId)->first();
            if ($parent_account) {
                $oldpayment_parent = $parent_account->id;
                $parent_account->update([
                    'current_balance'  => $parent_account->current_balance + $totalAmount,
                    'creditor_current' => $parent_account->creditor_current - $totalAmount,
                ]);
            }
        }

        if ($invoice->Pay_Method_Name == 'Cash') {
            $oldpayment = 5;
            $financial_account = financial_accounts::find(5);
            if ($financial_account) {
                $financial_account->update([
                    'current_balance'  => $financial_account->current_balance + $totalAmount,
                    'creditor_current' => $financial_account->creditor_current - $totalAmount,
                ]);
            }

            $parent_account = financial_accounts::where('parent_account_number', 5)->where('branchs_id', $branchId)->first();
            if ($parent_account) {
                $oldpayment_parent = $parent_account->id;
                $parent_account->update([
                    'current_balance'  => $parent_account->current_balance + $totalAmount,
                    'creditor_current' => $parent_account->creditor_current - $totalAmount,
                ]);
            }
        }

        if ($invoice->Pay_Method_Name == 'Credit') {
            $supplierData = supllier::find($invoice->suplier_id);
            if ($supplierData) {
                $supplierData->decrement('In_debt', $totalAmount);
            }

            $financial_account = financial_accounts::where('orginal_type', 2)->where('orginal_id', $invoice->suplier_id)->first();
                $oldpayment = $financial_account->id;



            credittransactions::where('note', ' فاتورة مشتريات رقم :' . $invoiceId)
                ->where('customer_id', $oldpayment)
                ->update([
                    'user_id'         => $userId,
                    'recive_amount'   => $totalAmount,
                    'branchs_id'      => $branchId,
                    'pay_method'      => $paymentMethod,
                    'currentblance'   => 0,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                    'orginal_id'      => 0,
                    'creditor'        => 0,
                    'debtor'          => 0,
                ]);
        }

        // 2. Apply New Cash Payment Settings
        if ($cashamount) {

            $parent_account = financial_accounts::where('parent_account_number', 5)->where('branchs_id', $branchId)->first();
            if ($parent_account) {
                $parent_account->update([
                    'current_balance'  => $parent_account->current_balance - $cashamount,
                    'creditor_current' => $parent_account->creditor_current + $cashamount,
                ]);

                if ($oldpayment_parent == 0) {
                    credittransactions::create([
                        'user_id'         => $userId,
                        'customer_id'     => $parent_account->id,
                        'recive_amount'   => $cashamount,
                        'branchs_id'      => $branchId,
                        'pay_method'      => $paymentMethod,
                        'note'            => ' فاتورة مشتريات رقم :'. $invoiceId,
                        'currentblance'   => $parent_account->current_balance + $cashamount,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                        'orginal_id'      => 0,
                        'debtor'          => 0,
                        'creditor'        => $cashamount
                    ]);
                } else {
                    credittransactions::where('note',' فاتورة مشتريات رقم :' . $invoiceId)
                        ->where('customer_id', $oldpayment_parent)
                        ->update([
                            'user_id'         => $userId,
                            'customer_id'     => $parent_account->id,
                            'recive_amount'   => $cashamount,
                            'branchs_id'      => $branchId,
                            'pay_method'      => $paymentMethod,
                            'Pay_Method_Name' => $paymentMethod,
                            'updated_at'      => $now,
                            'orginal_id'      => 0,
                            'debtor'          => 0,
                            'creditor'        => $cashamount,
                        ]);
                }
            }
        }

        // 3. Apply New Bank/Network Settings
        $totalBank = $Bank_transfer + $bankamount;
        if ($totalBank) {
            if ($financial_account) {

            $parent_account = financial_accounts::where('parent_account_number', 4)->where('branchs_id', $branchId)->first();
            if ($parent_account) {
                $parent_account->update([
                    'current_balance'  => $parent_account->current_balance - $totalBank,
                    'creditor_current' => $parent_account->creditor_current + $totalBank,
                ]);

                if ($oldpayment_parent == 0) {
                    credittransactions::create([
                        'user_id'         => $userId,
                        'customer_id'     => $parent_account->id,
                        'recive_amount'   => $totalBank,
                        'branchs_id'      => $branchId,
                        'pay_method'      => $paymentMethod,
                        'note'            => ' فاتورة مشتريات رقم :'. $invoiceId,
                        'currentblance'   => $parent_account->current_balance + $totalBank,
                        'Pay_Method_Name' => $paymentMethod,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                        'orginal_id'      => 0,
                        'debtor'          => 0,
                        'creditor'        => $totalBank
                    ]);
                } else {
                    credittransactions::where('note', ' فاتورة مشتريات رقم :'. $invoiceId)
                        ->where('customer_id', $oldpayment_parent)
                        ->update([
                            'user_id'         => $userId,
                            'customer_id'     => $parent_account->id,
                            'recive_amount'   => $totalBank,
                            'branchs_id'      => $branchId,
                            'pay_method'      => $paymentMethod,
                            'Pay_Method_Name' => $paymentMethod,
                            'updated_at'      => $now,
                            'orginal_id'      => 0,
                            'debtor'          => 0,
                            'creditor'        => $totalBank,
                        ]);
                }
            }
        }
        }
        // 4. Apply New Credit Settings
        if ($creaditamount) {
            $supplierData = supllier::find($invoice->suplier_id);
            if ($supplierData) {
                $supplierData->increment('In_debt', $creaditamount);
            }

            credittransactions::where('note', ' فاتورة مشتريات رقم :' . $invoiceId)->where('customer_id', $oldpayment)->delete();
            if ($oldpayment_parent != 0) {
                credittransactions::where('note',' فاتورة مشتريات رقم :' . $invoiceId)->where('customer_id', $oldpayment_parent)->delete();

            }

            $financial_account = financial_accounts::where('orginal_type', 2)->where('orginal_id', $invoice->suplier_id)->first();
            if ($financial_account) {
                credittransactions::where('note',' فاتورة مشتريات رقم :' . $invoiceId)->where('customer_id', $financial_account->id)->delete();



                credittransactions::create([
                    'user_id'         => $userId,
                    'customer_id'     => $financial_account->id,
                    'recive_amount'   => $creaditamount,
                    'branchs_id'      => $branchId,
                    'pay_method'      => $paymentMethod,
                    'note'            =>' فاتورة مشتريات رقم :' . $invoiceId,
                    'currentblance'   => $financial_account->current_balance + $creaditamount,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                    'orginal_id'      => 0,
                    'debtor'          => 0,
                    'creditor'        => $creaditamount
                ]);
            }
        }

        // Update the main purchase layout invoice entry
        $invoice->update(['Pay_Method_Name' => $paymentMethod]);

        $data = resource_purchases::where('branchs_id', $branchId)->where('save', 1)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices_purchases', compact('data'));
    });
}






    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

public function PreviousQuotes()
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    // Ordered by id desc for quick retrieval
    $data = offer_price_to_customer::latest('id')->paginate(30);

    return view('PreviousQuotes', compact('data'));
}

public function searchPreviousQuotes($id)
{
    $data = offer_price_to_customer::where('id', $id)->latest('id')->paginate(30);

    return view('searchPreviousQuotes', compact('data'));
}

public function getquotebycustomer($id)
{
    $data = offer_price_to_customer::where('customer_id', $id)->latest('id')->paginate(30);

    return view('searchPreviousQuotes', compact('data'));
}

public function updateinvoicebyid($invoiceNumber)
{
    $quoute = offer_price_to_customer::find($invoiceNumber);
    if (!$quoute) {
        return 0;
    }
$branchId1 = auth()->user()->branch->id;
if ($quoute->branchs_id != $branchId1) {
        // نرجع رسالة نصية أو كود خاص ليتم التقاطه في الـ Ajax
        return response()->json(['error' => 'يلزم ادخال منتجات يدويا'], 403);
    }
    // Wrap everything in a transaction to guarantee data integrity
    return DB::transaction(function () use ($quoute, $invoiceNumber) {
                    $branchId = auth()->user()->branch->id;

        $userId = auth()->user()->id;
        $now = Carbon::now()->addHours(3);
        // 1. Create temporary invoice
        $Invoice = temp_invoice::create([
            'customer_id' => $quoute->customer_id ?? 1,
            'user_id' => $userId,
            'Price' => 0,
            'Added_Value' => 0,
            'Pay' => 'Cash',
            'status' => 0,
            'branchs_id' => $branchId,
            'discountOnProduct' => 0,
            'discount' => 0,
            'Number_of_Quantity' => 0,
            'note' => $quoute->notes ?? '-',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $totalprice = 0;
        $totaladdedvalue = 0;
        $totalquantity = 0;
        $totaldiscount = 0;
        $avtSaleRate = Avt::find(1); // Consider fallback or config for safety
        $taxRate = $avtSaleRate ? $avtSaleRate->AVT : 0;

        // Eager load products using a collection map to avoid N+1 queries
        $quoteItems = offer_price_to_customer_items::where('order_id', $invoiceNumber)->get();
        $productIds = $quoteItems->pluck('product_id')->unique();
        $productsMap = products::whereIn('id', $productIds)->get()->keyBy('id');

        $temp_salesData = [];

        foreach ($quoteItems as $item) {
            $product = $productsMap->get($item->product_id);
            $currentStock = $product ? $product->numberofpice : 0;

            $itemTotalPrice = $item->PriceWithoudTax * $item->quantity;
            $totalprice += $itemTotalPrice;
            $totaladdedvalue += ($itemTotalPrice * $taxRate);
            $totalquantity += $item->quantity;
            $totaldiscount += $item->discount;

            $temp_salesData[] = [
                'product_id' => $item->product_id,
                'invoice_id' => $Invoice->id,
                'branch_id' => $branchId,
                'Discount_Value' => $item->discount,
                'Added_Value' => $item->PriceWithoudTax * $taxRate,
                'Unit_Price' => $item->PriceWithoudTax,
                'reamingQuantity' => $currentStock - $item->quantity,
                'quantity' => $item->quantity,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Mass insert temp sales entries for rapid DB writing
        temp_sales::insert($temp_salesData);

        // Update the temporary invoice details directly using the existing model reference
        $Invoice->update([
            'Price' => $totalprice,
            'Added_Value' => $totaladdedvalue,
            'discountOnProduct' => $totaldiscount,
            'discount' => $totaldiscount + $quoute->discount,
            'Number_of_Quantity' => $totalquantity,
        ]);

        // Gather structural data response safely with relational data mapping
        $allProdctsD = [];
        $insertedSales = temp_sales::with('productData')->where('invoice_id', $Invoice->id)->get();

        foreach ($insertedSales as $index => $sale) {
            $productRef = $productsMap->get($sale->product_id);
            $allProdctsD[] = [
                'Product_Code' => $sale->productData->Product_Code ?? '',
                'product_name' => $sale->productData->product_name ?? '',
                'quantity' => $sale->quantity,
                'Unit_Price' => $sale->Unit_Price,
                'Discount_Value' => $sale->Discount_Value,
                'reamingquantity' => $productRef ? $productRef->numberofpice : 0,
                'Added_Value' => $sale->Added_Value,
                'count' => $index + 1,
                'id' => $sale->productData->id ?? null
            ];
        }

        // Reset older sales quotes targets safely
       sales::where('invoice_id', $invoiceNumber)->update(['quantity' => 0]);

        // $customer = customers::find($Invoice->customer_id);
        $financial_accounts=customers::find($Invoice->customer_id );

        return [
            "invoicetotal_price" => $Invoice->Price - $Invoice->discount,
            "invoicetotal_addedvalue" => ($Invoice->Price - $Invoice->discount) * $taxRate,
            "invoicetotal_discount" => $Invoice->discount,
            'invoice_number' => $Invoice->id,
            'pay' => $Invoice->pay,
            'customer' => $financial_accounts,
            'product' => $allProdctsD,
            "invoice_id" => $invoiceNumber
        ];
    });
}

public function updatecustomerDataInvoice(Request $request)
{
    $request->validate([
        'id' => 'required',
        'customerId' => 'required'
    ]);

    $invoices = invoices::findOrFail($request->id);

    // Database transaction block protects balances adjustments
    DB::transaction(function () use ($invoices, $request) {
        if ($invoices->Pay == 'Credit') {
            $avt = Avt::find(1);
            $saleavt = $avt ? $avt->AVT : 0;

            $invoiceTotalWithTax = ($invoices->Price - $invoices->discount) * (1 + $saleavt);

            // Deduct from old customer balance
            customers::where('id', $invoices->customer_id)->decrement('Balance', $invoiceTotalWithTax);

            // Add to new customer balance
            customers::where('id', $request->customerId)->increment('Balance', $invoiceTotalWithTax);

            // Rebalance financial accounts for old customer
            financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoices->customer_id)
                ->decrement('current_balance', $invoiceTotalWithTax);

            financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoices->customer_id)
                ->decrement('debtor_current', $invoiceTotalWithTax);

            // Rebalance financial accounts for new customer
            financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $request->customerId)
                ->increment('current_balance', $invoiceTotalWithTax);

            financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $request->customerId)
                ->increment('debtor_current', $invoiceTotalWithTax);
        }

        // Update target account linkages inside the ledger entries
        $financial_accounts_old = financial_accounts::where('orginal_type', 1)->where('orginal_id', $invoices->customer_id)->first();
        $financial_accounts_new = financial_accounts::where('orginal_type', 1)->where('orginal_id', $request->customerId)->first();

        if ($financial_accounts_old && $financial_accounts_new) {
            credittransactions::where('note', 'فاتورة مبيعات رقم :' . $request->id)
                ->where('customer_id', $financial_accounts_old->id)
                ->update([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $financial_accounts_new->id,
                    'updated_at' => Carbon::now()->addHours(3),
                ]);
        }

        // Finally update invoice owner
        $invoices->update(['customer_id' => $request->customerId]);
    });

    $data = invoices::where('branchs_id', auth()->user()->branchs_id)
        ->where('save', 1)
        ->where('status', 0)
        ->latest('id')
        ->paginate(20);

    return view('ajax_Recent_Invoices', compact('data'));
}












public function getByCode(Request $request)
{
    $updateProduct = products::where('Product_Code', $request->Code)->first();
    if (!$updateProduct) {
        return ["notfound"];
    }

    $avtSaleRate = Avt::find(1);
    $taxRate = $avtSaleRate ? $avtSaleRate->AVT : 0;
    $user = auth()->user();
    $now = Carbon::now()->addHours(3);

    // Guard against insufficient stock upfront
    if ($updateProduct->numberofpice < $request->quantity) {
        $locale = LaravelLocalization::getCurrentLocale();
        $message = $locale == 'ar' ? 'عدم وجود مخزون من هذا المنتج' : 'Out of stock of this product';
        session()->flash('delete', $message);
        return ["notfound"];
    }

    $invoiceNumber = $request->invoice_number;

    return DB::transaction(function () use ($request, $updateProduct, $taxRate, $user, $now, &$invoiceNumber) {
        $isSameBranch = ($user->branchs_id == $updateProduct->branchs_id);
        $calculatedPrice = $updateProduct->sale_price * $request->quantity;
        $calculatedTax = $calculatedPrice * $taxRate;

        // 1. Hand off invoice creation or incremental balance updates
        if (empty($invoiceNumber)) {
            $Invoice = temp_invoice::create([
                'customer_id' => $request->clientnamesearch ?? 1,
                'user_id' => $user->id,
                'Price' => $calculatedPrice,
                'Added_Value' => $calculatedTax,
                'Pay' => $request->pay,
                'status' => $isSameBranch ? 0 : 1,
                'branchs_id' => $user->branch->id,
                'discountOnProduct' => 0,
                'discount' => 0,
                'Number_of_Quantity' => $request->quantity,
                'note' => $request->note,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $invoiceNumber = $Invoice->id;
        } else {
            $InvoiceData = temp_invoice::findOrFail($invoiceNumber);

            // Fixed bitwise single '&' typo to standard logical '&&'
            $newStatus = ($InvoiceData->status != 1 && $isSameBranch) ? 0 : 1;

            temp_invoice::where('id', $invoiceNumber)->update([
                'discount' => 0,
                'Price' => $InvoiceData->Price + $calculatedPrice,
                'Added_Value' => $InvoiceData->Added_Value + $calculatedTax,
                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $request->quantity,
                'note' => $request->note,
                'status' => $newStatus,
                'updated_at' => $now,
            ]);
        }

        // 2. Manage Sales Line details itemizations
        $checksale = temp_sales::where('invoice_id', $invoiceNumber)
            ->where('product_id', $updateProduct->id)
            ->first();

        if ($checksale) {
            $checksale->increment('quantity', 1);
        } else {
            temp_sales::create([
                'product_id' => $updateProduct->id,
                'invoice_id' => $invoiceNumber,
                'branch_id' => $user->branch->id,
                'Discount_Value' => 0,
                'Added_Value' => $updateProduct->sale_price * $taxRate,
                'Unit_Price' => $updateProduct->sale_price,
                'quantity' => $request->quantity,
                'created_at' => $now,
            ]);
        }

        // 3. Collect output payloads
        $salesItems = temp_sales::with('productData')->where('invoice_id', $invoiceNumber)->get();
        $productIds = $salesItems->pluck('product_id')->unique();
        $productsMap = products::whereIn('id', $productIds)->get()->keyBy('id');

        $allProdctsD = [];
        foreach ($salesItems as $index => $product) {
            $currentProductMap = $productsMap->get($product->product_id);
            $allProdctsD[] = [
                'Product_Code' => $product->productData->Product_Code ?? '',
                'product_name' => $product->productData->product_name ?? '',
                'quantity' => $product->quantity,
                'Unit_Price' => $product->Unit_Price,
                'reamingquantity' => $currentProductMap ? ($currentProductMap->numberofpice - $request->quantity) : 0,
                'Discount_Value' => $product->Discount_Value,
                'Added_Value' => $product->Added_Value,
                'count' => $index + 1,
                'id' => $product->id
            ];
        }

        $customer = customers::find($request->clientnamesearch);
        $finalInvoiceData = temp_invoice::find($invoiceNumber);

        return [
            "invoicetotal_price" => $finalInvoiceData->Price - $finalInvoiceData->discount,
            "invoicetotal_addedvalue" => ($finalInvoiceData->Price - $finalInvoiceData->discount) * $taxRate,
            "invoicetotal_discount" => $finalInvoiceData->discount,
            'invoice_number' => $invoiceNumber,
            'pay' => $request->pay,
            'customer' => $customer,
            'product' => $allProdctsD,
            "invoice_id" => $invoiceNumber
        ];
    });
}

public function saveInvoice($invoiceId)
{
    $invoice = invoices::find($invoiceId);
    if (!$invoice) {
        return [0];
    }

    return DB::transaction(function () use ($invoiceId) {
        // Mark flags as saved
        invoices::where('id', $invoiceId)->update(['save' => 1]);
       sales::where('invoice_id', $invoiceId)->update(['save' => 1]);

        $sales =sales::where('invoice_id', $invoiceId)->get();
        $productIds = $sales->pluck('product_id')->unique();

        // Eager load products map to avoid N+1 issues during stock deduction
        $productsMap = products::whereIn('id', $productIds)->get()->keyBy('id');
        $userBranchId = auth()->user()->branchs_id;

        foreach ($sales as $sale) {
            $productdata = $productsMap->get($sale->product_id);
            if ($productdata && $userBranchId == $productdata->branchs_id) {
                products::where('id', $sale->product_id)->decrement('numberofpice', $sale->quantity);
            }
        }

        return [1];
    });
}

public function updatepaymentconfirmpaymentReciept($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod, $index)
{
    $invoice = invoices::find($invoiceId);
    if (!$invoice) {
        return [0];
    }

    return DB::transaction(function () use ($invoice, $invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod) {
        $totalPaidAmount = $cashamount + $bankamount + $creaditamount + $Bank_transfer;

        // Condense nested balance manipulation into one atomic block
        if ($invoice->Pay == 'Credit') {
            $netBalanceAdjustment = -$totalPaidAmount;

            if (!empty($creaditamount)) {
                $netBalanceAdjustment += $creaditamount;
            }

            if ($netBalanceAdjustment != 0) {
                customers::where('id', $invoice->customer_id)->increment('Balance', $netBalanceAdjustment);
            }
        } elseif (!empty($creaditamount)) {
            customers::where('id', $invoice->customer_id)->increment('Balance', $creaditamount);
        }

        invoices::where('id', $invoiceId)->update([
            'save' => 1,
            'morepayment_way' => 1,
            'cashamount' => $cashamount,
            'bankamount' => $bankamount,
            'creaditamount' => $creaditamount,
            'Bank_transfer' => $Bank_transfer,
            'Pay' => $paymentMethod
        ]);

        $data = invoices::where('branchs_id', auth()->user()->branchs_id)
            ->where('save', 1)
            ->where('status', 1)
            ->latest('id')
            ->paginate(20);

        return view('ajax_Recent_Invoices', compact('data'));
    });
}










// --- الدوال المساعدة لتقليل التكرار (Helper Functions) ---

private function getBranchAccount($parentAccountNumber, $branchId)
{
    return financial_accounts::where('parent_account_number', $parentAccountNumber)
        ->where('branchs_id', $branchId)
        ->first();
}


private function updateAccountAndLogTransaction($accountId, $branchId, $balanceDiff, $debtorAmount, $invoiceId, $paymentMethod, $userId, $now)
{
    $account = financial_accounts::find($accountId);
    if ($account) {
        $account->update([
            'debtor_current' => $account->debtor_current + $debtorAmount,
        ]);
    }

    $branchAccount = $this->getBranchAccount($accountId, $branchId);
    if ($branchAccount) {
        $branchAccount->update([
            'debtor_current' => $branchAccount->debtor_current + $debtorAmount,
        ]);

        // تسجيل الحركة المالية
        credittransactions::create([
            'user_id' => $userId,
            'customer_id' => $branchAccount->id,
            'recive_amount' => $debtorAmount,
            'branchs_id' => $branchId,
            'pay_method' => $paymentMethod,
            'note' => '  فاتورة مبيعات رقم :' . $invoiceId,
            'currentblance' => $branchAccount->current_balance + $debtorAmount,
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $now,
            'updated_at' => $now,
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $debtorAmount
        ]);
    }
}





public function updatepaymentconfirmpayment($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod, $another_bank)
{
    // 1. التحقق من وجود الفاتورة أولاً
    $invoice = invoices::find($invoiceId);
    if (!$invoice) {
return response("<div class='alert alert-danger'>الفاتورة غير موجودة</div>");
    }

    // تشغيل الـ Transaction لضمان عدم حدوث خطأ محاسبي
    DB::transaction(function () use ($invoice, $invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod, $another_bank) {

        $branchId = auth()->user()->branchs_id;
        $userId = auth()->user()->id;
        $now =$invoice->created_at;

        // ✔️ الإصلاح: جلب كائن الخزنة واستخراج الـ ID الرقمي مباشرة لمنع انهيار السيرفر
        $cashAccountObj = financial_accounts::where('parent_account_number', 5)
            ->where('branchs_id', $branchId)
            ->first();
        $cashAccountId = $cashAccountObj ? $cashAccountObj->id : 5;

        // جلب حساب البنك الخاص بالفرع ديناميكياً
        $branchBank = financial_accounts::where('parent_account_number', 4)
            ->where('branchs_id', $branchId)
            ->first();
        $bankAccountId = $branchBank ? $branchBank->id : 4;

        // =========================================================
        // أولاً: إلغاء وتصفير أثر الدفعة القديمة (تنظيف كامل للحسابات)
        // =========================================================

        // 1. عكس أثر الكاش القديم وتصفيره
        if ($invoice->cashamount > 0 || $invoice->Pay == 'Cash') {
            // إرجاع الرصيد الفعلي في شجرة الحسابات لحالته قبل هذه الفاتورة
            if (method_exists($this, 'adjustAccountBalance')) {
                $this->adjustAccountBalance($cashAccountId, $branchId, -$invoice->cashamount);
            }

            // تصفير القيود القديمة للكاش
            DB::table('credittransactions')
                ->where('note', 'فاتورة مبيعات رقم :' . $invoiceId)
                ->where('customer_id', $cashAccountId)
                ->update(['save' => 0, 'recive_amount' => 0, 'debtor' => 0, 'creditor' => 0]);

                   $customerFinancialAccount = financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoice->customer_id)
                ->first();
                      // تصفير القيود القديمة للكاش
            DB::table('credittransactions')
                ->where('note', 'فاتورة مبيعات رقم :' . $invoiceId)
                ->where('customer_id', $customerFinancialAccount->id)
                ->update(['save' => 0, 'recive_amount' => 0, 'debtor' => 0, 'creditor' => 0]);

        }

        // 2. عكس أثر البنك/الشبكة القديم وتصفيره
        if (in_array($invoice->Pay, ['Shabka', 'Bank_transfer', 'Partition'])) {
            $oldBankAmount = $invoice->Bank_transfer + $invoice->bankamount;
            if ($oldBankAmount > 0) {
                // تحديد البنك القديم الذي تم الحفظ عليه بناءً على الحقل المخزن تلافياً لأي تحديث خيارات
                $oldTargetBankId =  $bankAccountId;

                if (method_exists($this, 'adjustAccountBalance')) {
                    $this->adjustAccountBalance($oldTargetBankId, $branchId, -$oldBankAmount);
                }

                // تصفير قيود البنك القديمة المرتبطة بالفاتورة
                DB::table('credittransactions')
                    ->where('note', 'فاتورة مبيعات رقم :' . $invoiceId)
                    ->where('customer_id', $oldTargetBankId)
                    ->update(['save' => 0, 'recive_amount' => 0, 'debtor' => 0, 'creditor' => 0]);

                                  $customerFinancialAccount = financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoice->customer_id)
                ->first();
                      // تصفير القيود القديمة للكاش
            DB::table('credittransactions')
                ->where('note', 'فاتورة مبيعات رقم :' . $invoiceId)
                ->where('customer_id', $customerFinancialAccount->id)
                ->update(['save' => 0, 'recive_amount' => 0, 'debtor' => 0, 'creditor' => 0]);

            }
        }

        // 3. تصفير حساب ومصادقة مديونية العميل القديمة تماماً
        if ($invoice->creaditamount > 0 || $invoice->Pay == 'Credit' || $invoice->Pay == 'Partition') {
            $customer = customers::find($invoice->customer_id);
            if ($customer) {
                $customer->decrement('Balance', $invoice->creaditamount);
            }

            $customerFinancialAccount = financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoice->customer_id)
                ->first();

            if ($customerFinancialAccount) {
                $customerFinancialAccount->decrement('current_balance', $invoice->creaditamount);
                $customerFinancialAccount->decrement('debtor_current', $invoice->creaditamount);

                credittransactions::where('note', 'فاتورة مبيعات رقم :' . $invoiceId)
                    ->where('customer_id', $customerFinancialAccount->id) // تأمين الاستعلام للتصفير الدقيق
                    ->update([
                        'user_id' => $userId,
                        'customer_id' => 0,
                        'recive_amount' => 0,
                        'branchs_id' => $branchId,
                        'pay_method' => 'update',
                        'Pay_Method_Name' => 'update',
                        'updated_at' =>$now,
                        'orginal_id' => 0,
                        'creditor' => 0,
                        'debtor' => 0,
                    ]);
            }
        }

        // =========================================================
        // ثانياً: تطبيق وتوزيع المبالغ الجديدة بناءً على المدخلات
        // =========================================================

        // تحديد البنك المستهدف الفعلي بناءً على اختيار الكاشير الحالي
        $targetBankId = ($another_bank == 4) ? 1157 : $bankAccountId;

        // 1. إضافة حركة الكاش الجديدة (في حساب الخزنة الصواب)
        if ($cashamount > 0) {
            if (method_exists($this, 'adjustAccountBalance')) {
                $this->adjustAccountBalance($cashAccountId, $branchId, $cashamount);
            }

            credittransactions::create([
                'user_id' => $userId,
                'customer_id' => $cashAccountId, // ✔️ تعديل: الكاش يدخل في حساب الخزنة وليس البنك
                'recive_amount' => $cashamount,
                'branchs_id' => $branchId,
                'pay_method' => $paymentMethod,
                'note' => 'فاتورة مبيعات رقم :' . $invoiceId,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now,
                'updated_at' => $now,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $cashamount,
                'save' => 1
            ]);

                 $customerFinancialAccount = financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoice->customer_id)
                ->first();

            credittransactions::create([
                'user_id' => $userId,
                'customer_id' => $customerFinancialAccount->id, // ✔️ تعديل: الكاش يدخل في حساب الخزنة وليس البنك
                'recive_amount' => 0,
                'branchs_id' => $branchId,
                'pay_method' => $paymentMethod,
                'note' => 'فاتورة مبيعات رقم :' . $invoiceId,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now,
                'updated_at' => $now,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => 0,
                'save' => 1
            ]);

        }

        // 2. إضافة حركة البنك الجديدة (في حساب البنك الصواب)
        if (($Bank_transfer + $bankamount) > 0) {
            $totalBank = $Bank_transfer + $bankamount;

            if (method_exists($this, 'adjustAccountBalance')) {
                $this->adjustAccountBalance($targetBankId, $branchId, $totalBank);
            }

            credittransactions::create([
                'user_id' => $userId,
                'customer_id' => $targetBankId, // ✔️ البنك يدخل في حساب البنك المحدد
                'recive_amount' => $totalBank,
                'branchs_id' => $branchId,
                'pay_method' => $paymentMethod,
                'note' => 'فاتورة مبيعات رقم :' . $invoiceId,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now,
                'updated_at' => $now,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => $totalBank,
                'save' => 1
            ]);

                          $customerFinancialAccount = financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoice->customer_id)
                ->first();

            credittransactions::create([
                'user_id' => $userId,
                'customer_id' => $customerFinancialAccount->id, // ✔️ تعديل: الكاش يدخل في حساب الخزنة وليس البنك
                'recive_amount' => 0,
                'branchs_id' => $branchId,
                'pay_method' => $paymentMethod,
                'note' => 'فاتورة مبيعات رقم :' . $invoiceId,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now,
                'updated_at' => $now,
                'orginal_id' => 0,
                'creditor' => 0,
                'debtor' => 0,
                'save' => 1
            ]);
        }

        // =========================================================
        // ثالثاً: تحديث بيانات الفاتورة نفسها في قاعدة البيانات
        // =========================================================
        $invoice->update([
            'save' => 1,
            'morepayment_way' => 1,
            'cashamount' => $cashamount,
            'bankamount' => $bankamount,
            'creaditamount' => $creaditamount,
            'Bank_transfer' => $Bank_transfer,
            'Pay' => $paymentMethod,
            'another_bank' => $another_bank
        ]);

        // =========================================================
        // رابعاً: إثبات المديونية الآجلة الجديدة على العميل (إن وُجد باقٍ)
        // =========================================================
        if ($creaditamount > 0) {
            $customer = customers::find($invoice->customer_id);
            $customerFinancialAccount = financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $invoice->customer_id)
                ->first();

            if ($customerFinancialAccount && $customer) {
                $customerFinancialAccount->increment('current_balance', $creaditamount);
                $customerFinancialAccount->increment('debtor_current', $creaditamount);
                $customer->increment('Balance', $creaditamount);

                credittransactions::create([
                    'user_id' => $userId,
                    'customer_id' => $customerFinancialAccount->id,
                    'recive_amount' => $creaditamount,
                    'branchs_id' => $branchId,
                    'pay_method' => $paymentMethod,
                    'note' => 'فاتورة مبيعات رقم :' . $invoiceId,
                    'currentblance' => $customerFinancialAccount->current_balance,
                    'Pay_Method_Name' => $paymentMethod,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'orginal_id' => 0,
                    'creditor' => 0,
                    'debtor' => $creaditamount,
                    'save' => 1
                ]);
            }
        }
    });

    // =========================================================
    // خامساً: جلب وإرجاع الجدول بصيغة HTML ليتم تحديث الشاشة فوراً
    // =========================================================
    $data = invoices::where('branchs_id', auth()->user()->branchs_id)->orderBy('id', 'desc')->paginate(20);
    return view('ajax_Recent_Invoices', compact('data'))->render();
}




private function adjustAccountBalance($accountId, $branchId, $amount)
{}


private function getResetTransactionData($userId, $branchId, $now)
{
    return [
        'user_id' => $userId,
        'customer_id' => 0,
        'recive_amount' => 0,
        'branchs_id' => $branchId,
        'pay_method' => 'update',
        'Pay_Method_Name' => 'update',
        'updated_at' => $now,
        'orginal_id' => 0,
        'creditor' => 0,
        'debtor' => 0,
    ];
}




public function getlastprice($product_id, $customer_id)
{
    // استخدام الـ Join لجلب البيانات في استعلام واحد فقط بدلاً من N+1 queries
    $sales =sales::join('invoices', 'sales.invoice_id', '=', 'invoices.id')
        ->where('invoices.customer_id', $customer_id)
        ->where('invoices.save', 1)
        ->where('sales.product_id', $product_id)
        ->orderBy('invoices.id', 'desc')
        ->select('sales.*', 'invoices.id as inv_id', 'invoices.created_at as inv_created_at')
        ->get();

    $data_supplier = [];

    foreach ($sales as $saleData) {
        $totalQty = ($saleData->quantity + $saleData->quantityreturn);
        $discountPerUnit = ($totalQty > 0) ? round($saleData->Discount_Value / $totalQty, 2) : 0;

        $netUnitPrice = $saleData->Unit_Price - $discountPerUnit;
        $costWithTax = $netUnitPrice * 1.15;

        $data_supplier[] = [
            'invoiceid' => $saleData->inv_id,
            'date'      => substr($saleData->inv_created_at, 0, 10),
            'cost'      => round($costWithTax, 2),
            'quantity'  => $saleData->quantity,
        ];
    }

    return $data_supplier;
}

public function getlastprice_offer_price($product_id, $customer_id)
{
    // تحسين الدالة الثانية بنفس طريقة الـ Join لضمان السرعة
    $items = offer_price_to_customer_items::join('offer_price_to_customers', 'offer_price_to_customer_items.order_id', '=', 'offer_price_to_customers.id')
        ->where('offer_price_to_customers.customer_id', $customer_id)
        ->where('offer_price_to_customer_items.product_id', $product_id)
        ->orderBy('offer_price_to_customers.id', 'desc')
        ->select('offer_price_to_customer_items.*', 'offer_price_to_customers.id as offer_id', 'offer_price_to_customers.created_at as offer_created_at')
        ->get();

    $data_supplier = [];
    foreach ($items as $saleData) {
        $netPrice = $saleData->PriceWithoudTax - $saleData->Discount_Value;

        $data_supplier[] = [
            'invoiceid' => $saleData->offer_id,
            'date'      => substr($saleData->offer_created_at, 0, 10),
            'cost'      => $netPrice + round($netPrice * 0.15, 2),
            'quantity'  => $saleData->quantity,
        ];
    }

    return $data_supplier;
}
function convertNumber($num = false)
{
    $num = str_replace(array(',', ' '), '', trim($num));
    if (!$num && $num !== 0 && $num !== '0') {
        return false;
    }

    $num = (int) $num;
    if ($num === 0) {
        return 'صفر';
    }

    $list1 = array(
        '', 'واحد', 'اثنين', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة', 'عشرة',
        'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر', 'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'
    );

    $list2 = array('', 'عشرة', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون');

    $list3 = array(
        '',
        array('ألف', 'ألفان', 'أف'),
        array('مليون', 'مليونان', 'ملايين'),
        array('مليار', 'ملياران', 'مليارات')
    );

    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);

    $words = array();

    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $current_num = (int)$num_levels[$i];

        if ($current_num === 0) continue;

        $block_words = '';

        // 1. حساب المئات
        $hundreds = (int)($current_num / 100);
        if ($hundreds > 0) {
            if ($hundreds == 1) $block_words .= 'مائة';
            elseif ($hundreds == 2) $block_words .= 'مائتان';
            elseif ($hundreds >= 3 && $hundreds <= 9) {
                // إزالة التاء المربوطة من الرقم لإضافته للمئات (ثلاثمائة)
                $prefix = str_replace('ة', '', $list1[$hundreds]);
                if($hundreds == 8) $prefix = 'ثماني';
                $block_words .= $prefix . 'مائة';
            }
        }

        // 2. حساب الآحاد والعشرات
        $tens = $current_num % 100;
        if ($tens > 0) {
            if ($block_words !== '') $block_words .= ' و';

            if ($tens < 20) {
                $block_words .= $list1[$tens];
            } else {
                $singles = $tens % 10;
                $ten_digit = (int)($tens / 10);

                if ($singles > 0) {
                    $block_words .= $list1[$singles] . ' و' . $list2[$ten_digit];
                } else {
                    $block_words .= $list2[$ten_digit];
                }
            }
        }

        // 3. إضافة التمييز (آلاف، ملايين، مليارات) حسب مستوى الرقم برمجياً
        if ($levels > 0) {
            $suffix = '';
            $unit = $list3[$levels];

            if ($current_num == 1) {
                $block_words = $unit[0]; // ألف أو مليون
            } elseif ($current_num == 2) {
                $block_words = $unit[1]; // ألفان أو مليونان
            } elseif ($current_num >= 3 && $current_num <= 10) {
                $block_words .= ' ' . $unit[2]; // ملايين أو آلاف
            } else {
                $block_words .= ' ' . $unit[0]; // ألفاً أو مليوناً
            }
        }

        $words[] = $block_words;
    }

    // دمج المقاطع بأداة العطف "و" العربية بدلاً من الفراغات الإنجليزية
    $result = implode(' و', $words);
    return trim($result);
}

public function showInvoiceRecent($request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $avtSaleRate = Avt::find(1);
    $taxRate = $avtSaleRate->AVT ?? 0.15; // جعل الضريبة ديناميكية بناءً على الجدول

    // 1. جلب بيانات الفاتورة والمبيعات مع التأكد من وجود الفاتورة لمنع أخطاء الـ Null
    $InvoiceData = invoices::find($request);
    if (!$InvoiceData) {
        abort(404, 'Invoice not found');
    }

    $saleData =sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get();
    $setting = system_setting::find(1);

    // 2. حساب إجمالي الفاتورة الفعلي المدفوع (شامل الضريبة والخصومات)
    $Total_Amount = $InvoiceData->Bank_transfer + $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;
    $totAL = number_format($Total_Amount, 2, '.', ''); // توحيد صيغة الرقم بنقطة عشرية دائماً

    // 3. فصل الريالات عن الهلالات بشكل آمن يمنع خطأ الـ Explode في الأرقام الصحيحة
    $parts = explode('.', $totAL);
    $whole = isset($parts[0]) ? (int)$parts[0] : 0;
    $decimal = isset($parts[1]) ? (int)$parts[1] : 0;

    // 4. تجهيز مصفوفة متطلبات الـ QR للزكاة والدخل (ZATCA) لحفظها من المسح
    $zatcaQrData = [
        $setting->name_ar,
        $setting->Tax,
        (string)$InvoiceData->issue_date . 'T' . (string)$InvoiceData->issue_time,
        number_format($Total_Amount, 2, '.', ''),
        number_format(($Total_Amount * $taxRate / (1 + $taxRate)), 2, '.', ''),
    ];

    // 5. بناء المصفوفة النهائية وتفقيط المبالغ بالاعتماد على دالة convertNumber العربية التي أصلحناها
    $data = [
        "invoicetotal_price"      => number_format(($Total_Amount / (1 + $taxRate)), 2, '.', ''),
        "invoicetotal_addedvalue" => number_format(($Total_Amount * $taxRate / (1 + $taxRate)), 2, '.', ''),
        "invoicetotal_discount"   => $InvoiceData->discount,
        'salesData'               => $saleData,
        'invoiceData'             => $InvoiceData,
        'zatcaQrData'             => $zatcaQrData, // ممررة للاستخدام في كود الـ QR بصفحة الـ Blade
        'totatextlriyales'        => $this->convertNumber($whole) . ' ريال',
        'totatextlrihalala'       => $decimal > 0 ? $this->convertNumber($decimal) : 'صفر',
    ];

    // 6. عرض صفحة الطباعة مع تمرير البيانات النظيفة
    return view('products.printInvoicesReturnToClientRecentSales', compact('data'));
}



public function generate_pdf($request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $avtSaleRate = Avt::find(1);
    $taxRate = $avtSaleRate->AVT ?? 0.15; // الضريبة ديناميكية

    // 1. جلب بيانات الفاتورة والمبيعات مع التحقق من الوجود
    $InvoiceData = invoices::find($request);
    if (!$InvoiceData) {
         abort(404, 'Invoice not found');
    }

    $saleData =sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get();
    $setting = system_setting::find(1);

    // 2. حساب إجمالي الفاتورة الفعلي المدفوع (شامل الضريبة)
    $Total_Amount = $InvoiceData->Bank_transfer + $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;
    $totAL = number_format($Total_Amount, 2, '.', ''); // توحيد الصيغة بنقطة عشرية دائماً

    // 3. فصل الريالات عن الهلالات بطريقة آمنة تمنع خطأ الـ Explode
    $parts = explode('.', $totAL);
    $whole = isset($parts[0]) ? (int)$parts[0] : 0;
    $decimal = isset($parts[1]) ? (int)$parts[1] : 0;

    // 4. تجهيز مصفوفة الـ QR (حقول التكافؤ المخصصة لـ ZATCA المرحلة الأولى) في متغير مستقل
    $zatcaQrData = [
        $setting->name_ar,
        $setting->Tax,
        (string)$InvoiceData->issue_date . 'T' . (string)$InvoiceData->issue_time,
        number_format($Total_Amount, 2, '.', ''),
        number_format(($Total_Amount * $taxRate / (1 + $taxRate)), 2, '.', ''),
    ];

    // 5. بناء مصفوفة البيانات النهائية للـ View وتفقيط العملة بشكل صحيح
    $data = [
        "invoicetotal_price"      => number_format(($Total_Amount / (1 + $taxRate)), 2, '.', ''),
        "invoicetotal_addedvalue" => number_format(($Total_Amount * $taxRate / (1 + $taxRate)), 2, '.', ''),
        "invoicetotal_discount"   => $InvoiceData->discount,
        'salesData'               => $saleData,
        'invoiceData'             => $InvoiceData,
        'zatcaQrData'             => $zatcaQrData, // ممررة للـ Blade لإنشاء الـ QR بناءً عليها
        'totatextlriyales'        => $this->convertNumber($whole),
        'totatextlrihalala'       => $decimal > 0 ? $this->convertNumber($decimal) : 'zero',
    ];

    $tran = ['data' => $data];

    // 6. توليد اسم ملف فريد وآمن محاسبياً لأنظمة التشغيل
    $fileName = \Carbon\Carbon::now()->addHours(3)->format('Y-m-d_H-i-s');

    // 7. صب البيانات في الـ HTML وتحويلها إلى PDF
    $html = view('pdf.translation', $tran)->toArabicHTML();
    $pdf = PDF::loadHTML($html)->output();

    $headers = [
        "Content-type" => "application/pdf",
    ];

    // 8. تحميل الملف مباشرة في المتصفح
    return response()->streamDownload(
        fn() => print ($pdf),
        "Invoice_No_" . $request . "_" . $fileName . ".pdf",
        $headers
    );
}



public function showRecieptRecent($request)
{
    App::setLocale(LaravelLocalization::getCurrentLocale());

    $avtSaleRate = Avt::find(1);
    $saleData =sales::where("invoice_id", $request)->get();
    $InvoiceData = invoices::find($request);
    $setting = system_setting::find(1);

    $Total_Amount = $InvoiceData->Bank_transfer + $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

    $data = [
        "invoicetotal_price"       => $InvoiceData->Price - $InvoiceData->discount,
        "invoicetotal_addedvalue"   => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
        "invoicetotal_discount"     => $InvoiceData->discount,
        'salesData'                 => $saleData,
        'invoiceData'               => $InvoiceData,
    ];

    return view('products.printInvoicesToCustomer', compact('data'));
}

public function showInvoice($request)
{
    App::setLocale(LaravelLocalization::getCurrentLocale());

    $avtSaleRate = Avt::find(1);
    $saleData =sales::where("invoice_id", $request)->get();
    $InvoiceData = invoices::find($request);

    $data = [
        "invoicetotal_price"       => $InvoiceData->Price - $InvoiceData->discount,
        "invoicetotal_addedvalue"   => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
        "invoicetotal_discount"     => $InvoiceData->discount,
        'salesData'                 => $saleData,
        'invoiceData'               => $InvoiceData,
    ];

    return view('products.printInvoicesToClient', compact('data'));
}

public function changeCustomerInInvoice($orderId, $newUserId)
{
    // تعديل الـ typo من changechustomerInInvoice إلى changeCustomerInInvoice
    $invoice = invoices::find($orderId);

    if ($invoice && $invoice->Pay == 'Credit') {
        // الأكواد معطلة من قبلك بالـ comment، تركتها كما هي للرجوع إليها عند الحاجة
    }

    // تأكد هنا إذا كنت تريد تحديث جدول الفواتير المؤقتة أم الأساسية
    temp_invoice::where('id', $orderId)->update(['customer_id' => $newUserId]);

    return 'Done';
}

public function changePaymethodIninvoice($orderId, $paymentMethod)
{
    invoices::where('id', $orderId)->update(['Pay' => $paymentMethod]);
    return 'Done';
}

 public function printInvoice(Request $request)
    {
        //
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request->show_invoice_number == null) {
            $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);
            session()->flash('nodataprint', '');

            return view('products.sales', compact('products'));
        }
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avtSaleRate = Avt::find(1);


        $saleData = sales::where("invoice_id", $request->show_invoice_number)->where('quantity', '!=', 0)->get();
        $InvoiceData = invoices::find($request->show_invoice_number);
        $totAL = round(($InvoiceData->Price - $InvoiceData->discount) + (($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT), 2);
        $totAL = number_format($totAL, 2);


        list($whole, $decimal) = explode('.', str_replace(",", "", $totAL));
        $numberToWord = new NumberToWord();
        $check = str_split($decimal);
        if ($check[0] == "0") {
            $decimal = (int) $check[1];
        } else {
            $decimal = $decimal;

        }
        $setting = system_setting::find(1);
        $Total_Amount = $InvoiceData->Bank_transfer + $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

        $data = [
            $setting->name_ar,
            $setting->Tax,
            (string) $InvoiceData->issue_date . 'T' . (string) $InvoiceData->issue_time,
            number_format(($Total_Amount), 2, '.', ''),
            number_format((($Total_Amount * 100 / (100 + ($avtSaleRate->AVT * 100)))) * $avtSaleRate->AVT, 2, '.', ''),
        ];
        $data[] = '';
        $data[] = '';
        $data[] = '';
        $data[] = '';

        $data = [

            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,
            'salesData' => $saleData,
            'invoiceData' => $InvoiceData,
            'totatextlriyales' => NumToArabic::number2Word(round((int) $whole, 2)) . '  ريال',
            'totatextlrihalala' => $decimal != '00' ? NumToArabic::number2Word(round((int) $decimal, 2)) . '   هللة' : 'فقط',

        ];


        // $saleData= sales::where("invoice_id",$invoicesid)->get();
        // $InvoiceData=invoices::find($invoicesid);
        // $data=[
        //     'salesData'=>$saleData ,
        //     'invoiceData'=>  $InvoiceData,
        // ];
        return view('products.printInvoicesToClient', compact('data'));
    }
    public function index()
    {
        //
        $data = [];
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('products.salesreturned', compact('data'));
    }

public function increaseProduct(Request $request)
{
    $avtSaleRate = Avt::find(1);
    App::setLocale(LaravelLocalization::getCurrentLocale());

    $saleData = temp_sales::find($request->id);
    if (!$saleData) {
        return ["notfount"];
    }

    // تحديث كمية المبيعات المؤقتة
    $saleData->update([
        'quantity'   => $saleData->quantity + $request->increasequantity,
        'updated_at' => Carbon::now()->addHours(3),
    ]);

    $InvoiceData = temp_invoice::find($saleData->invoice_id);

    // 🌟 تصحيح الخطأ المطبعي هنا من AV إلى AVT
    $InvoiceData->update([
        'Price'              => round($InvoiceData->Price + ($saleData->Unit_Price * $request->increasequantity), 2),
        'Added_Value'        => round($InvoiceData->Added_Value + ($saleData->Unit_Price * $request->increasequantity * $avtSaleRate->AVT), 2),
        'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $request->increasequantity,
        'updated_at'         => Carbon::now()->addHours(3),
    ]);

    $products = temp_sales::where('invoice_id', $saleData->invoice_id)->get();
    $allProdctsD = [];
    $i = 0;

    foreach ($products as $product) {
        $updateProduct = Products::find($product->product_id);
        $i++;
        $allProdctsD[] = [
            'Product_Code'    => $product->productData->Product_Code ?? '',
            'product_name'    => $product->productData->product_name ?? '',
            'quantity'        => $product->quantity,
            'Unit_Price'      => $product->Unit_Price,
            'reamingquantity' => ($updateProduct->numberofpice ?? 0) - $product->quantity,
            'Discount_Value'  => $product->Discount_Value,
            'Added_Value'     => $product->Added_Value,
            'count'           => $i,
            'id'              => $product->id
        ];
    }

    return [
        "invoicetotal_price"       => $InvoiceData->Price - $InvoiceData->discount,
        "invoicetotal_addedvalue"   => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
        "invoicetotal_discount"     => $InvoiceData->discount,
        'product'                   => $allProdctsD,
        "invoice_id"                => $saleData->invoice_id
    ];
}

public function printReceiptToStorehouse(Request $request)
{
    App::setLocale(LaravelLocalization::getCurrentLocale());
    $avtSaleRate = Avt::find(1);

    if (!$request->show_invoice_number) {
        $products = Products::where('branchs_id', auth()->user()->branchs_id)->paginate(20);
        session()->flash('nodataprint', '');
        return view('products.Receipt', compact('products'));
    }

    $saleData =sales::where("invoice_id", $request->show_invoice_number)->where('quantity', '!=', 0)->get();
    $InvoiceData = invoices::find($request->show_invoice_number);

    $data = [
        "invoicetotal_price"       => $InvoiceData->Price - $InvoiceData->discount,
        "invoicetotal_addedvalue"   => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
        "invoicetotal_discount"     => $InvoiceData->discount,
        'salesData'                 => $saleData,
        'invoiceData'               => $InvoiceData,
    ];

    return view('products.printInvoicesToCustomer', compact('data'));
}

    public function editRecipt(Request $request)
    {
        //
        // return  $request;
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData =sales::find($request->id);
        $productSales =sales::where('id', $request->id)->update(
            [
                'quantity' => $saleData->quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );

        $productData = products::find($saleData->product_id);
        // products::where('id', $saleData->product_id)->Update([
        //     'numberofpice' => $productData->numberofpice + $request->return_quentity
        // ]);
        $InvoiceData = invoices::find($saleData->invoice_id);

        $Invoice = invoices::where('id', $saleData->invoice_id)->Update(
            [

                'Price' => round(($InvoiceData->Price - ($saleData->Unit_Price * $request->return_quentity)), 2),
                'Added_Value' => round(($InvoiceData->Added_Value - ($saleData->Unit_Price * $request->return_quentity * $avtSaleRate->AVT)), 2),

                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );


        $InvoiceData = invoices::find($saleData->invoice_id);
        if ($InvoiceData->Pay == "Credit") {
            $customerdata = customers::find($InvoiceData->customer_id);
            // return ($customerdata->Balance-(($request->return_quentity*$saleData->Unit_Price)+($request->return_quentity*$saleData->Added_Value)));
            // $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //     [
            //         'Balance' => $customerdata->Balance - (($request->return_quentity * $saleData->Unit_Price) + ($request->return_quentity * $saleData->Added_Value)),
            //         'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //     ]
            // );
        }

        $products =sales::where('invoice_id', $saleData->invoice_id)->get();
        $allProdctsD = [];
        $i = 0;
        foreach ($products as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->Product_Code,
                'product_name' => $product->productData->product_name,
                'quantity' => $product->quantity,
                'Unit_Price' => $product->Unit_Price,
                'Discount_Value' => $product->Discount_Value,
                'Added_Value' => $product->Added_Value,
                'count' => $i,
                'id' => $product->id
            ];
        }
        //return $product;
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,
            'product' => $allProdctsD,
            "invoice_id" => $saleData->invoice_id
        ];
        return $data;
        return view('products.sales', compact('data'));
    }


public function returnAll(Request $request)
{
    $locale = LaravelLocalization::getCurrentLocale();
    app()->setLocale($locale);

    $page = $request->pagename;
    $avtSaleRate = Avt::find(1);

    // الحالة الأولى: إذا لم يتم تحديد فاتورة للاسترجاع الكامل
    if (empty($request->invoice_no_delete_All)) {
        $products = products::where('branchs_id', auth()->user()->branchs_id)->paginate(10);

        // حماية أمنية: للتأكد من أن اسم الـ View الممرر آمن ولا يسبب خطأ 500
        if (!view()->exists($page)) {
            abort(404, "Page not found");
        }
        return view($page, compact('products'));
    }

    // البدء في معالجة الاسترجاع الكامل داخل Transaction لضمان سلامة القيود المحاسبية
    return DB::transaction(function () use ($request, $avtSaleRate, $locale) {
        $invoiceId = $request->invoice_no_delete_All;
        $InvoiceData = invoices::findOrFail($invoiceId);
        $discount_value_invoice = $InvoiceData->discount;
        $paymentMethod = $request->pay_return_sale ?? $InvoiceData->Pay;

        $saleData =sales::where('invoice_id', $InvoiceData->id)->get();
        $count = $saleData->count();
        $total_cost_value = 0;

        $successMessage = $locale == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";

        foreach ($saleData as $sale) {
            $updateProduct = products::findOrFail($sale->product_id);

            // حساب تكلفة البضاعة المباعة المرتجعة بناءً على سعر الشراء القديم
            $total_cost_value += $updateProduct->purchasingـprice * $sale->quantity;

            if ($updateProduct->branchs_id == $InvoiceData->branchs_id) {
                // تحديث الكميات في نفس الفرع
                $updateProduct->update([
                    'numberofpice' => $updateProduct->numberofpice + $sale->quantity,
                    'numberـofـsales' => $updateProduct->numberـofـsales - $sale->quantity
                ]);
                session()->flash('success', $successMessage);
            } else {
                // البحث عن المنتج في الفرع الآخر الخاص بالفاتورة
                $mproduct = products::where('branchs_id', $InvoiceData->branchs_id)
                                    ->where('Product_Code', $updateProduct->Product_Code)
                                    ->first();

                if ($mproduct != null) {
                    $mproduct->update([
                        'numberofpice' => $mproduct->numberofpice + $sale->quantity,
                        'purchasingـprice' => $updateProduct->purchasingـprice,
                    ]);
                    session()->flash('success', $successMessage);
                } else {
                    // إنشاء المنتج في الفرع الجديد إذا لم يكن موجوداً مسبقاً
                    $newproducts = products::create([
                        'product_name' => $updateProduct->product_name,
                        'name_en' => $updateProduct->name_en,
                        'branchs_id' => $InvoiceData->branchs_id,
                        'user_id' => auth()->user()->id,
                        'Product_Location' => $updateProduct->Product_Location,
                        'Product_Code' => $updateProduct->Product_Code,
                        'purchasingـprice' => $updateProduct->purchasingـprice,
                        'average_cost' => $updateProduct->purchasingـprice,
                        'Status' => 1,
                        'notes' => $updateProduct->notes,
                        'unit' => $updateProduct->unit,
                        'minmum_quantity_stock_alart' => $updateProduct->minmum_quantity_stock_alart,
                        'numberofpice' => $sale->quantity, // إسناد مباشر بدلاً من تحديث منفصل مكرر
                    ]);

                    $newProductMessage = $locale == 'ar'
                        ? "تم عملية الاسترجاع . المنتج المسترجع غير مسجل لديكم مسبقا تم تسجيل " . $updateProduct->product_name . " بنفس رقم المنتج شكرا"
                        : "The product is not previously registered. It has been registered with a name " . $updateProduct->product_name . " and a product number, such as the number below";

                    session()->flash('createnewproduct', $newProductMessage);
                }
            }

            // صفر كميات تسليم المنتجات للعميل
            Delivery_product_to_the_customer::where('invoice_id', $sale->invoice_id)
                ->where('product_id', $sale->product_id)
                ->update([
                    'quantity' => 0,
                    'updated_at' => Carbon::now()->addHours(3),
                ]);

            $discount_value_invoice -= $sale->Discount_Value;

            // تسجيل حركة مرتجعات المبيعات
            if ($sale->quantity > 0) {
                return_sales::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $sale->product_id,
                    'invoice_id' => $sale->invoice_id,
                    'branch_id' => auth()->user()->branch->id,
                    'discountvalue' => $sale->Discount_Value,
                    'discountoninvoice' => ($count == 1) ? $discount_value_invoice : 0, // الحفاظ على منطق الحقل الشرطي بناء على العدد
                    'return_Added_Value' => $sale->Added_Value,
                    'return_Unit_Price' => $sale->Unit_Price,
                    'return_quantity' => $sale->quantity,
                    'created_at' => Carbon::now()->addHours(3),
                    'tax_rate'=>$sale->tax_rate,

                ]);
            }

            // تحديث حركة البيع الأصلية لتصفير الكمية وتحديد المرتجع
           sales::where('id', $sale->id)->update([
                'quantity' => 0,
                'quantityreturn' => $sale->quantity,
                'updated_at' => Carbon::now()->addHours(3),
                'Discount_Value' => 0
            ]);
        }

        // الحسابات المالية الإجمالية للفاتورة المرتجعة
        $total_withoud_tax = $InvoiceData->Price - $InvoiceData->discount;
        $total_tax = $total_withoud_tax * $avtSaleRate->AVT;
        $net_total_amount = $total_withoud_tax + $total_tax; // الصافي الإجمالي المرتجع (شامل الضريبة)

        $note_text = ' فاتورة مرتجع مبيعات رقم :' . (string) $InvoiceData->id;
        $now_time = Carbon::now()->addHours(3);

        // 1. معالجة حسابات طرق الدفع (شبكة، تحويل، كاش، آجل)
        if (in_array($paymentMethod, ["Shabka", "Bank_transfer", "Cash"])) {
            $accountId = in_array($paymentMethod, ["Shabka", "Bank_transfer"]) ? 4 : 5;

            // الحساب الرئيسي
            $mainAccount = financial_accounts::findOrFail($accountId);
            $mainAccount->update([
                'current_balance' => $mainAccount->current_balance - $net_total_amount,
                'creditor_current' => $mainAccount->creditor_current + $net_total_amount,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $accountId,
                'recive_amount' => $net_total_amount,
                'branchs_id' => auth()->user()->branchs_id,
                'pay_method' => $paymentMethod,
                'note' => $note_text,
                'currentblance' => $mainAccount->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now_time,
                'updated_at' => $now_time,
                'orginal_id' => 0,
                'creditor' => $net_total_amount,
                'debtor' => 0,
            ]);

            // الحساب الفرعي التابع للفرع
            $subAccount = financial_accounts::where('parent_account_number', $accountId)
                ->where('branchs_id', auth()->user()->branchs_id)
                ->firstOrFail();

            $subAccount->update([
                'current_balance' => $subAccount->current_balance - $net_total_amount,
                'creditor_current' => $subAccount->creditor_current + $net_total_amount,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $subAccount->id,
                'recive_amount' => $net_total_amount,
                'branchs_id' => auth()->user()->branchs_id,
                'pay_method' => $paymentMethod,
                'note' => $note_text,
                'currentblance' => $subAccount->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now_time,
                'updated_at' => $now_time,
                'orginal_id' => 0,
                'creditor' => $net_total_amount,
                'debtor' => 0,
            ]);
        } elseif ($paymentMethod == "Credit") {
            $customerdata = customers::findOrFail($InvoiceData->customer_id);
            $customerdata->decrement('Balance', $net_total_amount);

            $custAccount = financial_accounts::where('orginal_type', 1)
                ->where('orginal_id', $InvoiceData->customer_id)
                ->firstOrFail();

            $custAccount->update([
                'current_balance' => $custAccount->current_balance - $net_total_amount,
                'creditor_current' => $custAccount->creditor_current + $net_total_amount,
            ]);

            credittransactions::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $custAccount->id,
                'recive_amount' => $net_total_amount,
                'branchs_id' => auth()->user()->branchs_id,
                'pay_method' => $paymentMethod,
                'note' => $note_text,
                'currentblance' => $custAccount->current_balance,
                'Pay_Method_Name' => $paymentMethod,
                'created_at' => $now_time,
                'updated_at' => $now_time,
                'orginal_id' => 0,
                'creditor' => $net_total_amount,
                'debtor' => 0,
            ]);
        }

        // جلب بيانات العميل لإدخالها في القيود الضريبية العامة
        $customerInfo = customers::find($InvoiceData->customer_id);

        // 2. قيد حساب ضريبة القيمة المضافة (مدين بالضريبة المسترجعة) - الحساب رقم 102
        $taxAccount = financial_accounts::where('parent_account_number', 102)
            ->where('branchs_id', auth()->user()->branchs_id)
            ->firstOrFail();

        $taxAccount->update([
            'current_balance' => ($taxAccount->debtor_current + $total_tax) - $taxAccount->creditor_current,
            'debtor_current' => $taxAccount->debtor_current + $total_tax,
        ]);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $taxAccount->id,
            'recive_amount' => $total_tax,
            'branchs_id' => auth()->user()->branchs_id,
            'pay_method' => $paymentMethod,
            'note' => $note_text,
            'currentblance' => $taxAccount->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $now_time,
            'updated_at' => $now_time,
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $total_tax,
            'vat' => 1,
            'name' => $customerInfo->name ?? null,
            'tax' => $customerInfo->tax_no ?? null,
        ]);

        // 3. قيد حساب مرتجعات المبيعات (جعل حساب المبيعات/المرتجع مديناً بالقيمة الإجمالية دون الضريبة) - الحساب رقم 184
        $returnAcc = financial_accounts::where('parent_account_number', 184)
            ->where('branchs_id', auth()->user()->branchs_id)
            ->firstOrFail();

        $returnAcc->update([
            'current_balance' => $returnAcc->current_balance - $total_withoud_tax,
            'debtor_current' => $returnAcc->debtor_current + $total_withoud_tax,
        ]);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $returnAcc->id,
            'recive_amount' => $total_withoud_tax,
            'branchs_id' => auth()->user()->branchs_id,
            'pay_method' => $paymentMethod,
            'note' => $note_text,
            'currentblance' => $returnAcc->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $now_time,
            'updated_at' => $now_time,
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $total_withoud_tax
        ]);

        // 4. قيد تكلفة البضاعة المباعة (دائن بقيمة التكلفة لتقليل المصروف) - الحساب رقم 183
        $cogsAcc = financial_accounts::where('parent_account_number', 183)
            ->where('branchs_id', auth()->user()->branchs_id)
            ->firstOrFail();

        $cogsAcc->update([
            'current_balance' => $cogsAcc->current_balance - $total_cost_value,
            'creditor_current' => $cogsAcc->creditor_current + $total_cost_value,
        ]);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $cogsAcc->id,
            'recive_amount' => $total_cost_value,
            'branchs_id' => auth()->user()->branchs_id,
            'pay_method' => $paymentMethod,
            'note' => $note_text,
            'currentblance' => $cogsAcc->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $now_time,
            'updated_at' => $now_time,
            'orginal_id' => 0,
            'creditor' => $total_cost_value,
            'debtor' => 0
        ]);

        // 5. قيد حساب المخزون (مدين لإعادة إدخال قيمة البضاعة المرتجعة للمخزن السلعي) - الحساب رقم 181
        $stockAcc = financial_accounts::where('parent_account_number', 181)
            ->where('branchs_id', auth()->user()->branchs_id)
            ->firstOrFail();

        $stockAcc->update([
            'current_balance' => $stockAcc->current_balance + $total_cost_value,
            'debtor_current' => $stockAcc->debtor_current + $total_cost_value,
        ]);

        credittransactions::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $stockAcc->id,
            'recive_amount' => $total_cost_value,
            'branchs_id' => auth()->user()->branchs_id,
            'pay_method' => $paymentMethod,
            'note' => $note_text,
            'currentblance' => $stockAcc->current_balance,
            'Pay_Method_Name' => $paymentMethod,
            'created_at' => $now_time,
            'updated_at' => $now_time,
            'orginal_id' => 0,
            'creditor' => 0,
            'debtor' => $total_cost_value
        ]);

        // حساب رقم الإشعار (NOTICE_Number) للاتساق المالي للمرتجعات
        if ($InvoiceData->NOTICE_Number != 0) {
            $NOTICE_Number = $InvoiceData->NOTICE_Number;
        } else {
            $recentreturn = invoices::where('id', '!=', $invoiceId)
                ->where('NOTICE_Number', '!=', 0)
                ->orderBy('NOTICE_Number', 'DESC')
                ->first();
            $NOTICE_Number = $recentreturn ? ($recentreturn->NOTICE_Number + 1) : 1;
        }

        // تصفير وتحديث الفاتورة الأصلية لتتحول إلى فاتورة مرتجعة بالكامل
        $InvoiceData->update([
            'Price' => 0,
            'Added_Value' => 0,
            'Number_of_Quantity' => 0,
            'discountOnInvoice' => $InvoiceData->discount - ($sale->Discount_Value ?? 0),
            'discount' => 0,
            'updated_at' => $now_time,
            'NOTICE_Number' => $NOTICE_Number,
            'payment_return' => $request->pay_return_sale,
        ]);

        return [
            'message' => $successMessage,
        ];
    });
}

public function updateproductallDataInvoices(Request $request)
{
    $avtSaleRate = Avt::find(1);
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    return DB::transaction(function () use ($request, $avtSaleRate) {
        $saleData = temp_sales::findOrFail($request->id);
        $productData = products::findOrFail($saleData->product_id);

        $InvoiceData = temp_invoice::findOrFail($saleData->invoice_id);

        // 1. خصم القيم القديمة لحركة البيع المؤقتة من إجمالي الفاتورة المؤقتة
        $priceBeforeOldSale = $InvoiceData->Price - ($saleData->Unit_Price * $saleData->quantity);
        $taxBeforeOldSale = $InvoiceData->Added_Value - ((($saleData->Unit_Price * $saleData->quantity) - $saleData->Discount_Value) * $avtSaleRate->AVT);
        $quantityBeforeOldSale = $InvoiceData->Number_of_Quantity - $saleData->quantity;
        $discountBeforeOldSale = $InvoiceData->discount - $saleData->Discount_Value;

        // 2. إضافة القيم الجديدة المرسلة من الطلب (Request) للفاتورة المؤقتة
        $newPrice = $priceBeforeOldSale + ($request->quentity * $request->price);
        $newAddedValue = $taxBeforeOldSale + (($request->quentity * $request->price) * $avtSaleRate->AVT);
        $newQuantity = $quantityBeforeOldSale + $request->quentity;
        $newDiscount = $discountBeforeOldSale + $request->discount;

        // تحديث جدول الفاتورة المؤقتة `temp_invoice` خطوة واحدة
        $InvoiceData->update([
            'Price' => $newPrice,
            'Added_Value' => $newAddedValue,
            'Number_of_Quantity' => $newQuantity,
            'discount' => ($newQuantity == 0) ? 0 : $newDiscount, // حماية تلقائية لتصفير الخصم إذا انعدمت الكمية
            'discountOnProduct' => $request->discount,
            'updated_at' => Carbon::now()->addHours(3),
        ]);

        // 3. تحديث حركة المبيعات المؤقتة الحالية `temp_sales`
        $saleData->update([
            'quantity' => $request->quentity,
            'Discount_Value' => $request->discount,
            'Unit_Price' => $request->price,
            'Added_Value' => $request->avt,
            'updated_at' => Carbon::now()->addHours(3),
        ]);

        // 4. تجميع كافة المنتجات الحالية داخل الفاتورة لإرسال الرد المحدث لـ Datatable / POS View
        $products = temp_sales::where('invoice_id', $saleData->invoice_id)->get();
        $allProdctsD = [];

        foreach ($products as $index => $product) {
            $associatedProduct = products::find($product->product_id);

            $allProdctsD[] = [
                'Product_Code'    => $product->productData->Product_Code ?? $associatedProduct->Product_Code,
                'product_name'    => $product->productData->product_name ?? $associatedProduct->product_name,
                'quantity'        => $product->quantity,
                'Unit_Price'      => $product->Unit_Price,
                'reamingquantity' => ($associatedProduct->numberofpice ?? 0) - $product->quantity,
                'Discount_Value'  => $product->Discount_Value,
                'Added_Value'     => $product->Added_Value,
                'count'           => $index + 1,
                'id'              => $product->id
            ];
        }

        // حساب صافي السعر بعد الخصم للمخرجات المباشرة
        $finalTotalPrice = $InvoiceData->Price - $InvoiceData->discount;

        return [
            "invoicetotal_price"      => $finalTotalPrice,
            "invoicetotal_addedvalue" => $finalTotalPrice * $avtSaleRate->AVT,
            "invoicetotal_discount"   => $InvoiceData->discount,
            'product'                 => $allProdctsD,
            "invoice_id"              => $saleData->invoice_id
        ];
    });
}


    public function edit(Request $request)
    {
        //
        // return  $request;
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData = temp_sales::find($request->id);


        $productData = products::find($saleData->product_id);
        if (true) {

            // products::where('id', $saleData->product_id)->Update([
            //     'numberofpice' => $productData->numberofpice + $request->return_quentity
            // ]);
        }



        $InvoiceData = temp_invoice::find($saleData->invoice_id);
        // if ($InvoiceData->Pay == "Credit") {
        //     $customerdata = customers::find($InvoiceData->customer_id);
        //     // return ($customerdata->Balance-(($request->return_quentity*$saleData->Unit_Price)+($request->return_quentity*$saleData->Added_Value)));
        //     $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
        //         [
        //             'Balance' => $customerdata->Balance - ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) + ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) * $avtSaleRate->AVT)),
        //             'updated_at' => \Carbon\Carbon::now()->addHours(3),

        //         ]
        //     );
        // }

        $Invoice = temp_invoice::where('id', $saleData->invoice_id)->Update(
            [

                'Price' => round($InvoiceData->Price - (($saleData->Unit_Price * $request->return_quentity)), 2),
                'Added_Value' => round($InvoiceData->Added_Value - ((($saleData->Unit_Price * $request->return_quentity)) * $avtSaleRate->AVT), 2),
                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );
        $InvoiceData = temp_invoice::find($saleData->invoice_id);

        if ($InvoiceData->Number_of_Quantity == 0 && $InvoiceData->Pay == "Credit") {
            $InvoiceData = invoices::find($saleData->invoice_id);

            // $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //     [
            //         'Balance' => $customerdata->Balance - $InvoiceData->discount,
            //         'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //     ]
            // );
        }


        if ($InvoiceData->Number_of_Quantity == 0) {
            $Invoice = temp_invoice::where('id', $saleData->invoice_id)->Update(
                [
                    'discount' => 0,
                ]
            );
        } else {
            $Invoice = temp_invoice::where('id', $saleData->invoice_id)->Update(
                [
                    'discount' => $InvoiceData->discount - $saleData->Discount_Value,
                    'discountOnProduct' => $InvoiceData->discountOnProduct - $saleData->Discount_Value,
                ]
            );
        }
        $productSales = temp_sales::where('id', $request->id)->update(
            [
                'quantity' => $saleData->quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'Discount_Value' => 0
            ]
        );

        $products = temp_sales::where('invoice_id', $saleData->invoice_id)->where('quantity', '>=', 1)->get();
        $allProdctsD = [];
        $i = 0;
        foreach ($products as $product) {
            $i++;
            $updateProduct = products::find($product->product_id);

            $allProdctsD[] = [
                'Product_Code' => $product->productData->Product_Code,
                'product_name' => $product->productData->product_name,
                'quantity' => $product->quantity,
                'Unit_Price' => $product->Unit_Price,
                'reamingquantity' => $updateProduct->numberofpice - $product->quantity,
                'Discount_Value' => $product->Discount_Value,
                'Added_Value' => $product->Added_Value,
                'count' => $i,
                'id' => $product->id
            ];
        }
        $InvoiceData = temp_invoice::find($saleData->invoice_id);

        //return $product;
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,
            'product' => $allProdctsD,
            "invoice_id" => $saleData->invoice_id
        ];
        return $data;
        return view('products.sales', compact('data'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */

    public function return_sale(Request $request)
    {
        //
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $product =sales::where('invoice_id', $request->invoice_no)->where('save', 1)->get();
        if (count($product) == 0) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? '  لم يتم العثور علي فاتورة بهذة الرقم' : 'No invoice with this number was found';

            session()->flash('notfountreturnproduct', $message);
            $data = [];
            return view('products.salesreturned', compact('data'));
        } else {
            $InvoiceData = invoices::find($request->invoice_no);

            $data = [
                "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
                "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
                "invoicetotal_discount" => $InvoiceData->discount,
                'product' => $product,
                'payment' => $InvoiceData->Pay,
                "invoice_id" => $request->invoice_no
            ];
            session()->flash('foundinvoice', __('home.invoice_found'));

            return view('products.salesreturned', compact('data'));
        }
    }

    public function update_return_Sale(Request $request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());
    $avtSaleRate = Avt::find(1);
    $returnshabkavalue = 0;

    // 1. جلب البيانات الأساسية
    $saleData =sales::find($request->id);
    $updateProduct = products::find($saleData->product_id);
    $InvoiceData = invoices::find($saleData->invoice_id);

    // توحيد استقبال كمية المرتجع لتفادي الأخطاء الإملائية في الطلب
    $returnQuantity = $request->return_quantity ?? $request->return_quentity;
    $total_cost_value = $updateProduct->purchasingـprice * $returnQuantity;
    $paymentMethod = $InvoiceData->Pay;

    // 2. معالجة حركة المخزون وحساب الفروع
    if ($updateProduct->branchs_id == $InvoiceData->branchs_id) {
        products::where('id', $saleData->product_id)->update([
            'numberofpice' => $updateProduct->numberofpice + $returnQuantity,
            'numberـofـsales' => $updateProduct->numberـofـsales - $returnQuantity
        ]);
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
        session()->flash('success', $message);
    } else {
        Delivery_product_to_the_customer::where('invoice_id', $saleData->invoice_id)
            ->where('product_id', $saleData->product_id)
            ->update([
                'quantity' => $saleData->quantity - $returnQuantity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]);

        $mproduct = products::where('branchs_id', $InvoiceData->branchs_id)
            ->where('Product_Code', $updateProduct->Product_Code)
            ->first();

        if ($mproduct != null) {
            products::where('branchs_id', $InvoiceData->branchs_id)
                ->where('Product_Code', $updateProduct->Product_Code)
                ->update([
                    'numberofpice' => $mproduct->numberofpice + $returnQuantity,
                    'purchasingـprice' => $updateProduct->purchasingـprice,
                ]);
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
            session()->flash('success', $message);
        } else {
            $newproducts = products::create([
                'product_name' => $updateProduct->product_name,
                'name_en' => $updateProduct->name_en,
                'branchs_id' => $InvoiceData->branchs_id,
                'user_id' => auth()->user()->id,
                'Product_Location' => $updateProduct->Product_Location,
                'Product_Code' => $updateProduct->Product_Code,
                'purchasingـprice' => $updateProduct->purchasingـprice,
                'average_cost' => $updateProduct->purchasingـprice,
                'Status' => 1,
                'notes' => $updateProduct->notes,
                'unit' => $updateProduct->unit,
                'minmum_quantity_stock_alart' => $updateProduct->minmum_quantity_stock_alart,
                'numberofpice' => $returnQuantity,
            ]);

            $productname = $updateProduct->product_name;
            $message = LaravelLocalization::getCurrentLocale() == 'ar'
                ? " تم عملية الاسترجاع. المنتج المسترجع غير مسجل لديكم مسبقا تم تسجيل " . $productname . " بنفس رقم المنتج شكرا "
                : "The product is not previously registered. It has been registered with a name " . $productname;

            session()->flash('createnewproduct', $message);
        }
    }

    // 3. تسجيل حركة مرتجع المبيعات (return_sales)
    $isLastItem = (sales::where('invoice_id', $saleData->invoice_id)->where('quantity', '!=', 0)->count() == 1)
                  && (($saleData->quantity - $returnQuantity) == 0);

    return_sales::create([
        'user_id' => auth()->user()->id,
        'product_id' => $saleData->product_id,
        'invoice_id' => $saleData->invoice_id,
        'branch_id' => auth()->user()->branch->id,
        'return_Added_Value' => $saleData->Added_Value,
        'return_Unit_Price' => $saleData->Unit_Price,
        'discountvalue' => $saleData->Discount_Value,
        'discountoninvoice' => $isLastItem ? ($InvoiceData->discount - $saleData->Discount_Value) : 0,
        'returnshabkavalue' => $returnshabkavalue,
        'return_quantity' => $returnQuantity,
        'created_at' => \Carbon\Carbon::now()->addHours(3),
        'tax_rate'=>$saleData->tax_rate,

    ]);

    // 4. تحديث جدول المبيعات (sales) للسطر الحالي
    $saleData->update([
        'quantity' => $saleData->quantity - $returnQuantity,
        'quantityreturn' => $saleData->quantityreturn + $returnQuantity,
        'Discount_Value' => 0
    ]);

    // 5. حسابات أرقام الإشعارات الدائنة وتحديث الفاتورة
    $NOTICE_Number = $InvoiceData->NOTICE_Number;
    if ($NOTICE_Number == 0) {
        $recentreturn = invoices::where('id', '!=', $saleData->invoice_id)
            ->where('NOTICE_Number', '!=', 0)
            ->orderBy('NOTICE_Number', 'DESC')
            ->first();
        $NOTICE_Number = $recentreturn == null ? 1 : $recentreturn->NOTICE_Number + 1;
    }

    // تحديث قيم الفاتورة الحالية
    $InvoiceData->update([
        'Price' => round($InvoiceData->Price - ($saleData->Unit_Price * $returnQuantity), 2),
        'Added_Value' => round($InvoiceData->Added_Value - ((($saleData->Unit_Price * $returnQuantity) - $saleData->Discount_Value) * $avtSaleRate->AVT), 2),
        'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $returnQuantity,
        'NOTICE_Number' => $NOTICE_Number,
        'discountOnInvoice' => $InvoiceData->discount - $saleData->Discount_Value,
        'discount' => $InvoiceData->discount - $saleData->Discount_Value,
        'payment_return' => $request->pay_return_sale,
        'updated_at' => \Carbon\Carbon::now()->addHours(3),
    ]);

    // إعادة تصفير القيم الفاتورة إذا أصبحت الكمية الإجمالية صفر
    if ($InvoiceData->Number_of_Quantity == 0) {
        $InvoiceData->update([
            'Price' => 0,
            'Added_Value' => 0,
            'discount' => 0,
        ]);
    }

    // 6. الحسابات المالية الموحدة (القيود اليومية المحاسبية)
    $customerdata = customers::find($InvoiceData->customer_id);
    $total_value = ($returnQuantity * $saleData->Unit_Price) - $saleData->Discount_Value;
    $total_tax = $total_value * $avtSaleRate->AVT;
    $total_with_tax = $total_value + $total_tax;

    $payReturnMethod = $request->pay_return_sale;
    $userId = auth()->user()->id;
    $userBranchId = auth()->user()->branchs_id;
    $now = Carbon::now()->addHours(3);
    $invoiceNote = ' فاتورة مرتجع مبيعات رقم :' . $InvoiceData->id;

    $commonTransactionData = [
        'user_id' => $userId,
        'branchs_id' => $userBranchId,
        'pay_method' => $payReturnMethod,
        'Pay_Method_Name' => $payReturnMethod,
        'note' => $invoiceNote,
        'created_at' => $now,
        'updated_at' => $now,
        'orginal_id' => 0,
    ];

    // أ: قيد الصندوق / البنك / العميل (حسب طريقة دفع المرتجع الحالية)
    $parentAccount = null;
    if (in_array($payReturnMethod, ["Shabka", "Bank_transfer"])) {
        $parentAccount = 4;
    } elseif ($payReturnMethod == "Cash") {
        $parentAccount = 5;
    }

    if ($parentAccount) {
        $account = financial_accounts::where('parent_account_number', $parentAccount)->where('branchs_id', $userBranchId)->first();
        if ($account) {
            $newBalance = $account->current_balance - $total_with_tax;
            $account->update([
                'current_balance' => $newBalance,
                'creditor_current' => $account->creditor_current + $total_with_tax
            ]);

            credittransactions::create(array_merge($commonTransactionData, [
                'customer_id' => $account->id,
                'recive_amount' => $total_with_tax,
                'currentblance' => $newBalance,
                'creditor' => $total_with_tax,
                'debtor' => 0,
            ]));
        }
    }

    // إذا كانت الفاتورة الأصلية آجلة ويتم إرجاعها لحساب العميل
    if ($InvoiceData->Pay == "Credit") {
        if ($customerdata) {
            $customerdata->update([
                'Balance' => $customerdata->Balance - $total_with_tax,
                'updated_at' => $now,
            ]);
        }

        $customerAccount = financial_accounts::where('orginal_type', 1)->where('orginal_id', $InvoiceData->customer_id)->first();
        if ($customerAccount) {
            $newCustBalance = $customerAccount->current_balance - $total_with_tax;
            $customerAccount->update([
                'current_balance' => $newCustBalance,
                'creditor_current' => $customerAccount->creditor_current + $total_with_tax
            ]);

            credittransactions::create(array_merge($commonTransactionData, [
                'customer_id' => $customerAccount->id,
                'recive_amount' => $total_with_tax,
                'currentblance' => $newCustBalance,
                'creditor' => $total_with_tax,
                'debtor' => 0,
            ]));
        }
    }

    // ب: قيد الحسابات الختامية (الضرائب 102، المبيعات 184، التكلفة 183، المخزن 181)
    $additionalAccounts = [
        102 => ['amount' => $total_tax, 'is_debtor' => true],
        184 => ['amount' => $total_value, 'is_debtor' => true],
        183 => ['amount' => $total_cost_value, 'is_debtor' => false],
        181 => ['amount' => $total_cost_value, 'is_debtor' => true],
    ];

    foreach ($additionalAccounts as $accountNum => $info) {
        $acc = financial_accounts::where('parent_account_number', $accountNum)->where('branchs_id', $userBranchId)->first();
        if (!$acc) continue;

        $amt = $info['amount'];

        if ($info['is_debtor']) {
            $newBal = ($accountNum == 181) ? $acc->current_balance + $amt : $acc->current_balance - $amt;
            $updateFields = [
                'current_balance' => $newBal,
                'debtor_current' => $acc->debtor_current + $amt
            ];
            $transFields = ['creditor' => 0, 'debtor' => $amt];
        } else {
            $newBal = $acc->current_balance - $amt;
            $updateFields = [
                'current_balance' => $newBal,
                'creditor_current' => $acc->creditor_current + $amt
            ];
            $transFields = ['creditor' => $amt, 'debtor' => 0];
        }

        $acc->update($updateFields);

        $vatFields = ($accountNum == 102) ? [
            'vat' => 1,
            'name' => $customerdata->name ?? '-',
            'tax' => $customerdata->tax_no ?? '-',
        ] : [];

        credittransactions::create(array_merge($commonTransactionData, $transFields, $vatFields, [
            'customer_id' => $acc->id,
            'recive_amount' => $amt,
            'currentblance' => $newBal,
        ]));
    }

    // 7. تجهيز مصفوفة الرد (Response Data)
    $productconvert = [];
    $allInvoiceProducts =sales::where('invoice_id', $saleData->invoice_id)->get();
    $i = 0;

    foreach ($allInvoiceProducts as $item) {
        if ($item->quantity > 0) {
            $i++;
            $productconvert[] = [
                'count' => $i,
                'Product_Code' => $item->productData->Product_Code ?? '-',
                'product_name' => $item->productData->product_name ?? '-',
                'quantity' => $item->quantity,
                'Unit_Price' => $item->Unit_Price,
                'Discount_Value' => $item->Discount_Value,
                "id" => $item->id
            ];
        }
    }

    return [
        "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
        "invoicetotal_addedvalue" => round(($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT, 2),
        "invoicetotal_discount" => $InvoiceData->discount,
        'total' => round(($InvoiceData->Price - $InvoiceData->discount) + ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT, 2),
        'product' => $productconvert,
        "invoice_id" => $saleData->invoice_id,
        "message" => $message
    ];
}






    public function update(Request $request)
    {
        //
        //   return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avtSaleRate = Avt::find(1);

        $saleData =sales::find($request->id);
        //return $saleData;
        $updateProduct = products::find($saleData->product_id);

        products::where('id', $saleData->product_id)->Update([
            'numberofpice' => $updateProduct->numberofpice + $request->return_quentity,
            'numberـofـsales' => $updateProduct->numberـofـsales - $request->return_quentity
        ]);


        $InvoiceData = invoices::find($saleData->invoice_id);
        if ($InvoiceData->Pay == "Credit") {
            $customerdata = customers::find($InvoiceData->customer_id);
            // return ($customerdata->Balance-(($request->return_quentity*$saleData->Unit_Price)+($request->return_quentity*$saleData->Added_Value)));
            $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
                [
                    'Balance' => $customerdata->Balance - ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) + ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) * $avtSaleRate->AVT)),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
        }
        if ($InvoiceData->Number_of_Quantity == 0) {
            $Invoice = invoices::where('id', $saleData->invoice_id)->Update(
                [
                    'discount' => 0,
                ]
            );
        } else {
            $Invoice = invoices::where('id', $saleData->invoice_id)->Update(
                [
                    'discount' => $InvoiceData->discount - $saleData->Discount_Value,
                ]
            );
        }

        $return_sales = return_sales::create([
            'user_id' => Auth()->user()->id,

            'product_id' => $saleData->product_id,
            'invoice_id' => $saleData->invoice_id,
            'branch_id' => Auth()->User()->branch->id,
            'return_Added_Value' => $saleData->Added_Value,
            'return_Unit_Price' => $saleData->Unit_Price,
            'return_quantity' => $request->return_quentity,
            'created_at' => \Carbon\Carbon::now()->addHours(3),
            'tax_rate'=>$saleData->tax_rate,

        ]);
        //  return  $return_sales;
        $invoicedatarecent = invoices::find($saleData->invoice_id);
        $NOTICE_Number = 0;
        if ($invoicedatarecent->NOTICE_Number != 0) {
            $NOTICE_Number = $invoicedatarecent->NOTICE_Number + 1;

        }

        $Invoice = invoices::where('id', $saleData->invoice_id)->Update(
            [

                'Price' => round($InvoiceData->Price - (($saleData->Unit_Price * $request->return_quentity)), 2),
                'Added_Value' => round($InvoiceData->Added_Value - ((($saleData->Unit_Price * $request->return_quentity)) * $avtSaleRate->AVT), 2),
                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'NOTICE_Number' => $NOTICE_Number

            ]
        );
        $productSales =sales::where('id', $request->id)->update(
            [
                'quantity' => $saleData->quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'Discount_Value' => 0
            ]
        );
        $InvoiceData = invoices::find($saleData->invoice_id);

        if ($InvoiceData->Number_of_Quantity == 0 && $InvoiceData->Pay == "Credit") {
            $InvoiceData = invoices::find($saleData->invoice_id);

            $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
                [
                    'Balance' => $customerdata->Balance - $InvoiceData->discount,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
        }
        $product =sales::where('invoice_id', $saleData->invoice_id)->get();
        //return $product;


        $InvoiceData = invoices::find($saleData->invoice_id);

        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,
            'product' => $product,
            "invoice_id" => $saleData->invoice_id
        ];
        return $data;
        return view('products.salesreturned', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */

 public function makeTotalDiscont($invoiceId, $discountValue)
{
    $avtSaleRate = Avt::find(1);
    // جلب الفاتورة مع التحقق من وجودها لحماية الكود من الانهيار
    $invoice = temp_invoice::find($invoiceId);

    if (!$invoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

    // نسبة الضريبة (مثال: إذا كانت 15% فالمتغير يحمل 0.15، وإذا كانت 5% يحمل 0.05)
    $taxRate = $avtSaleRate->AVT ?? 0.15;

    // 1. حساب الإجمالي الحالي للفاتورة (شامل الضريبة) قبل الخصم الجديد
    $currentPriceWithoutDiscount = $invoice->Price - $invoice->discount;
    $currentTax = round($currentPriceWithoutDiscount * $taxRate, 2);
    $currentTotalWithTax = $currentPriceWithoutDiscount + $currentTax;

    // 2. حساب الصافي المستهدف بعد طرح قيمة الخصم المطلوبة
    $targetTotalWithTax = $currentTotalWithTax - $discountValue;

    // 3. معادلة ديناميكية لاستخراج السعر الجديد قبل الضريبة مهما كانت نسبة الضريبة
    // السعر الجديد = الإجمالي المستهدف / (1 + نسبة الضريبة)
    $newPriceBeforeTax = round($targetTotalWithTax / (1 + $taxRate), 2);

    // 4. حساب قيمة الخصم الجديد الإضافي وضمه للخصم القديم
    $calculatedDiscountValue = $currentPriceWithoutDiscount - $newPriceBeforeTax;

    if ($calculatedDiscountValue != 0) {
        $invoice->update([
            'discount' => $invoice->discount + $calculatedDiscountValue
        ]);
        // إنعاش الكائن بالبيانات الجديدة من قاعدة البيانات مباشرة دون استعلام جديد
        $invoice->refresh();
    }

    return [
        'totalprice'              => round(($invoice->Price - $invoice->discount), 2),
        'addedvalueafterdiscount' => round((($invoice->Price - $invoice->discount) * $taxRate), 2),
        "discount"                => $invoice->discount
    ];
}

public function makenoteoninvoice($invoiceId, $notecontent)
{
    $invoice = temp_invoice::find($invoiceId);

    if ($invoice && $notecontent) {
        $invoice->update([
            'note' => $notecontent
        ]);
        return 1;
    }
    return 0;
}

public function cancelInvoiceDiscont($invoiceId)
{
    $avtSaleRate = Avt::find(1);
    $invoice = temp_invoice::find($invoiceId);

    if (!$invoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

    $taxRate = $avtSaleRate->AVT ?? 0.15;

    // حساب الخصم الذي تم تطبيقه على الفاتورة ككل (باستثناء خصومات المنتجات المنفردة)
    $discountOnInvoice = $invoice->discount - $invoice->discountOnProduct;

    if ($discountOnInvoice != 0) {
        $invoice->update([
            'discount' => $invoice->discount - $discountOnInvoice,
        ]);
        $invoice->refresh();
    }

    return [
        'totalprice'              => round(($invoice->Price - $invoice->discount), 2),
        'addedvalueafterdiscount' => round((($invoice->Price - $invoice->discount) * $taxRate), 2),
        "discount"                => $invoice->discount
    ];
}

    public function Receipt(Request $request)
    {
        //
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $updateProduct = products::find($request->productNo);
        // return $updateProduct;
        if ($updateProduct->numberofpice >= 1) {
            products::where('id', $request->productNo)->Update([
                'numberofpice' => $updateProduct->numberofpice - $request->quantity,
            ]);
            $invoiceNumber = $request->invoice_number;
            if ($request->invoice_number == null) {
                $Invoice = invoices::create(
                    [
                        'customer_id' => $request->clientnamesearch ?? 1,
                        'user_id' => Auth()->user()->id,
                        'Price' => $request->product_price - $request->product_price_after_dis,
                        'Added_Value' => ($request->product_price - $request->product_price_after_dis) * $avtSaleRate->AVT,
                        'Pay' => $request->pay,
                        'Number_of_Quantity' => $request->quantity,
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),
                        'issue_date' => substr(\Carbon\Carbon::now()->addHours(3), 0, 10),
                        'issue_time' => substr(\Carbon\Carbon::now()->addHours(3), 12),
                    ]
                );
                $invoiceNumber = $Invoice->id;
            } else {
                $InvoiceData = invoices::find($invoiceNumber);
                $Invoice = invoices::where('id', $invoiceNumber)->Update(
                    [

                        'Price' => round($InvoiceData->Price + ($request->product_price - $request->product_price_after_dis), 2),
                        'Added_Value' => round(($InvoiceData->Added_Value + (($request->product_price - $request->product_price_after_dis) * $avtSaleRate->AVT)), 2),
                        'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $request->quantity,
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),
                    ]
                );
            }
            $productSales =sales::create(
                [
                    'user_id' => Auth()->user()->id,

                    'product_id' => $request->productNo,
                    'invoice_id' => $invoiceNumber,
                    'Discount_Value' => $request->product_price_after_dis,
                    'Added_Value' => ($request->product_price - $request->product_price_after_dis) * $avtSaleRate->AVT,
                    'Unit_Price' => $request->product_price - $request->product_price_after_dis,
                    'quantity' => $request->quantity,
                    'branch_id' => Auth()->User()->branch->id,

                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
        } else {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عدم وجود مخزون من هذه المنتج' : 'No stock of this product';

            session()->flash('delete', $message);
            $data = [
                "invoice_id" => null
            ];

            return view('products.Receipt', compact('data'));
        }
        $product =sales::where('invoice_id', $invoiceNumber)->get();
        //return $product;
        $data = [
            'product' => $product,
            "invoice_id" => $invoiceNumber
        ];

        return view('products.Receipt', compact('data'));
    }
}
