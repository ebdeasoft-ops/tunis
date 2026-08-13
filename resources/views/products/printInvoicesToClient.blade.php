@extends('layouts.master')

@section('css')
<style>
/* تنسيقات عامة للشاشة */
.report-container {
    width: 100%;
}
.thick {
    font-weight: bold;
}
.double {
    border: 2px solid #6c757d;
    border-radius: 5px;
    width: 90%;
    font-size: 15px !important;
}

/* تنسيقات خاصة للطباعة */
@media print {
    body {
        background-color: #fff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    @page {
        size: A4;
        margin: 10mm 10mm 10mm 10mm;
    }

    .breadcrumb-header, 
    #print_Button, 
    #reciptprinter, 
    .main-header, 
    .main-sidebar, 
    .main-footer {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .report-container {
        width: 100% !important;
        margin: 0 !important;
    }

    table.report-container {
        page-break-after: auto;
    }

    thead.report-header {
        display: table-header-group;
    }

    tfoot.report-footer {
        display: table-footer-group;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 12px;
    }

    /* أحجام الخطوط والتنسيق أثناء الطباعة */
    .tx-18 { font-size: 14px !important; }
    .tx-16 { font-size: 13px !important; }
    
    .table th, .table td {
        padding: 6px !important;
        vertical-align: middle;
    }

    .double {
        border: 2px solid #333 !important;
        width: 100% !important;
        font-size: 14px !important;
    }

    .text {
        white-space: pre-wrap;
        word-break: break-word;
    }
}
</style>
@endsection

@section('title')
معاينة طباعة الفاتورة
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
<div class="row row-sm table-responsive" dir="ltr">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center mb-3">
                    <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body">
                    <table class="report-container">
                        <thead class="report-header">
                            <tr>
                                <th class="report-header-cell" style="border: none; background: transparent;">
                                    <!-- رأس الفاتورة (البيانات واللوجو) -->
                                    <div class="invoice-header" style="display: flex; justify-content: space-between; align-items: center; width: 100%; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;" dir="rtl">
                                        <div class="billed-from" style="width: 33%; text-align: right;">
                                            <span class="thick" style="font-size: 16px;">{{ Namear }}</span><br>
                                            <p class="tx-16 thick mb-1">{{ describtionar }}</p>
                                            <p class="tx-16 thick mb-1">{{ STar }}</p>
                                            <p class="tx-16 thick mb-0">{{ Taxar }}</p>
                                        </div>

                                        <div style="width: 34%; text-align: center;">
                                            <?php $logo = camplogo; ?>
                                            <a href="https://ebdeasoft.com/">
                                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 110px; object-fit: contain;">
                                            </a>
                                        </div>

                                        <div class="billed-from" style="width: 33%; text-align: left;">
                                            <span class="thick" style="font-size: 16px;">{{ Nameen }}</span><br>
                                            <p class="tx-16 thick mb-1">{{ describtionen }}</p>
                                            <span class="tx-16 thick mb-1">{{ STen }}</span>
                                            <p class="tx-16 thick mb-0">{{ Taxen }}</p>
                                        </div>
                                    </div>

                                    <!-- نوع الفاتورة -->
                                    <div class="my-3">
                                        @if(strlen($data['invoiceData']->customer->tax_no) == 15)
                                            <center><p class="double p-2 bg-light font-weight-bold">Tax Invoice - فاتورة ضريبية</p></center>
                                        @else
                                            <center><p class="double p-2 bg-light font-weight-bold">Simplified tax invoice - فاتورة ضريبية مبسطة</p></center>
                                        @endif
                                    </div>

                                    <input type="number" class="form-control" name="show_invoice_number" id="show_invoice_number" value="{{ $data['invoiceData']->id }}" hidden>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="report-content">
                            <tr>
                                <td class="report-content-cell" style="border: none;">
                                    
                                    <!-- جدول معلومات الفاتورة الأساسية -->
                                    <?php
                                    $pay = '';
                                    if ($data['invoiceData']->Pay == "Cash") { $pay = __('report.cash'); }
                                    elseif ($data['invoiceData']->Pay == "Shabka") { $pay = __('report.shabka'); }
                                    elseif ($data['invoiceData']->Pay == "Credit") { $pay = __('report.credit'); }
                                    elseif ($data['invoiceData']->Pay == "Bank_transfer") { $pay = __('home.Bank_transfer'); }
                                    else { $pay = __('home.Partition of the amount'); }
                                    ?>

                                    <div class="row mb-3" dir="rtl">
                                        <table dir="rtl" class="table table-bordered double mx-auto" style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td class="thick tx-16 bg-light" style="width: 25%;">طريقة الدفع PAYMENT METHOD</td>
                                                    <td class="tx-16" style="width: 25%;">{{ $pay }}</td>
                                                    <td class="thick tx-16 bg-light" style="width: 25%;">تاريخ الفاتورة INVOICE DATE</td>
                                                    <td class="tx-16" style="width: 25%;">{{ $data['invoiceData']->created_at }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="thick tx-16 bg-light">رقم الفاتورة INVOICE NUMBER</td>
                                                    <td class="tx-16" colspan="3">{{ $data['invoiceData']->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="thick tx-16 bg-light" colspan="2">اسم الفرع BRANCH NAME</td>
                                                    <td class="tx-16" colspan="2">{{ $data['invoiceData']->branch->name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- جدول بيانات العميل -->
                                    <div class="row mb-3" dir="rtl">
                                        <table dir="rtl" class="table table-bordered double mx-auto" style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td class="thick tx-16 bg-light" style="width: 25%;">اسم العميل CLIENT NAME</td>
                                                    <td class="tx-16" style="width: 75%;" colspan="3">{{ $data['invoiceData']->customer->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="thick tx-16 bg-light">الرقم الضريبي TAX NUMBER</td>
                                                    <td class="tx-16">{{ $data['invoiceData']->customer->tax_no == 0 ? '-' : $data['invoiceData']->customer->tax_no }}</td>
                                                    <td class="thick tx-16 bg-light">المنطقة REGION</td>
                                                    <td class="tx-16">{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->address }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="thick tx-16 bg-light">المدينة CITY</td>
                                                    <td class="tx-16">{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->sub_city }}</td>
                                                    <td class="thick tx-16 bg-light">اسم الشارع STREET NAME</td>
                                                    <td class="tx-16">{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->street_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="thick tx-16 bg-light">الرمز البريدي POSTAL CODE</td>
                                                    <td class="tx-16">{{ $data['invoiceData']->customer->postcode }}</td>
                                                    <td class="thick tx-16 bg-light">رقم المبنى BUILDING NUMBER</td>
                                                    <td class="tx-16">{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->building_number }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- جدول المنتجات -->
                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered table-striped text-center" style="border: 2px solid #333; width: 100%;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="tx-18">NO<br>رقم</th>
                                                    @if($data['invoiceData']->display_number)
                                                    <th class="tx-18">Item NO<br>رقم منتج</th>
                                                    @endif
                                                    <th class="tx-18">ITEM NAME<br>اسم الصنف</th>
                                                    <th class="tx-18">PRICE<br>سعر</th>
                                                    <th class="tx-18">QUANTITY<br>الكمية</th>
                                                    <th class="tx-18">TOTAL<br>الإجمالي</th>
                                                    <th class="tx-18">DISCOUNT<br>الخصم</th>
                                                    <th class="tx-18">TAX RATE<br>نسبة الضريبة</th>
                                                    <th class="tx-18">TAX<br>الضريبة</th>
                                                    <th class="tx-18">TOTAL<br>الصافي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $i = 0;
                                                $invoicetotal_addedvalue = 0;
                                                $total_withoud_tax_row = 0;
                                                $discountreturn = 0;
                                                $avt = App\Models\Avt::find(1);
                                                ?>
                                                @foreach (App\Models\sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                                                    @if($product->quantity != 0)
                                                        <?php $i++; ?>
                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            @if($data['invoiceData']->display_number)
                                                            <td>{{ $product->productData->Product_Code }}</td>
                                                            @endif
                                                            <td class="text-right text">{{ $product->product_name ?? $product->productData->product_name }}</td>
                                                            <td>{{ number_format($product->Unit_Price, 2, '.', '') }}</td>
                                                            <td>{{ $product->quantity }}</td>
                                                            <td>{{ number_format($product->Unit_Price * $product->quantity, 2, '.', '') }}</td>
                                                            <td>{{ number_format($product->Discount_Value, 2, '.', '') }}</td>
                                                            <?php
                                                            $total_row_befor_tax = round(($product->Unit_Price * $product->quantity) - $product->Discount_Value, 2);
                                                            $added_value_row = round($total_row_befor_tax * $product->tax_rate, 2);
                                                            $invoicetotal_addedvalue += $added_value_row;
                                                            $total_withoud_tax_row += $total_row_befor_tax;
                                                            ?>
                                                            <td>{{ number_format($product->tax_rate * 100, 2, '.', '') }} %</td>
                                                            <td>{{ number_format($added_value_row, 2, '.', '') }}</td>
                                                            <td>{{ number_format($added_value_row + $total_row_befor_tax, 2, '.', '') }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- قسم الـ QR Code والإجماليات -->
                                    <div class="row justify-content-between align-items-start mt-4" dir="rtl">
                                        <!-- QR Code والبانك -->
                                        <div class="col-md-5 text-center d-flex flex-column align-items-center">
                                            <?php
                                            function ConvertToHEX($value) {
                                                return pack("H*", sprintf("%02X", $value));
                                            }
                                            $invoice = App\Models\invoices::find($data['invoiceData']->id);
                                            $price = $invoice->cashamount + $invoice->Bank_transfer + $invoice->bankamount + $invoice->creaditamount;
                                            $price_befor_tax = $price * 100 / (100 + ($avt->AVT * 100));
                                            $invoicetotal_price = $price_befor_tax;
                                            $invoicetotal_discount = $invoice->discount + $discountreturn;

                                            $sellerName = sallerQrCode;
                                            $varNumber = TaxQrCode;
                                            $time = $invoice->created_at;
                                            $issue_time = substr($time, 11);
                                            $issue_date = substr($time, 0, 10);
                                            $time = (string)$issue_date . 'T' . (string)$issue_time;
                                            $total = number_format((round($invoicetotal_addedvalue + $total_withoud_tax_row, 2)), 2, '.', '');
                                            $tax = number_format(round($invoicetotal_addedvalue, 2), 2, '.', '');
                                            
                                            $seller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName)) . $sellerName;
                                            $vat = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber)) . $varNumber;
                                            $timeStr = ConvertToHEX(3) . ConvertToHEX(strlen($time)) . $time;
                                            $totalStr = ConvertToHEX(4) . ConvertToHEX(strlen($total)) . $total;
                                            $VATN = ConvertToHEX(5) . ConvertToHEX(strlen($tax)) . $tax;
                                            $empty = '';
                                            $empty6 = ConvertToHEX(6) . ConvertToHEX(strlen($empty)) . $empty;
                                            $empty7 = ConvertToHEX(7) . ConvertToHEX(strlen($empty)) . $empty;
                                            $empty8 = ConvertToHEX(8) . ConvertToHEX(strlen($empty)) . $empty;
                                            $empty9 = ConvertToHEX(9) . ConvertToHEX(strlen($empty)) . $empty;
                                            
                                            $tobase = $seller . $vat . $timeStr . $totalStr . $VATN . $empty6 . $empty7 . $empty8 . $empty9;
                                            $dataforQRcode = base64_encode($tobase);
                                            ?>
                                            <div class="mb-2">
                                                {!! QrCode::size(110)->generate($dataforQRcode) !!}
                                            </div>

                                            @if(Auth()->user()->branchs_id == 1)
                                            <div class="p-2 border rounded bg-light mt-2" style="font-size: 12px; width: 100%;">
                                                <strong>{{ bankname }}</strong><br>
                                                Account Number: {{ bank_acount_number }}<br>
                                                IBAN: {{ bank_acount_iban }}
                                            </div>
                                            @endif
                                        </div>

                                        <!-- جدول الإجماليات النهائية -->
                                        <div class="col-md-6">
                                            <table class="table table-bordered text-center" style="border: 2px solid #333;">
                                                <tr>
                                                    <th class="tx-16 bg-light text-right">الاجمالي - SUB TOTAL</th>
                                                    <th class="tx-16">{{ number_format((float)($total_withoud_tax_row), 2, '.', '') }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="tx-16 bg-light text-right">الخصم - DISCOUNT</th>
                                                    <th class="tx-16">{{ number_format(round($invoicetotal_discount, 2), 2, '.', '') }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="tx-16 bg-light text-right">الاجمالي بعد الخصم<br>SUB TOTAL AFTER DISCOUNT</th>
                                                    <th class="tx-16">{{ number_format(round($total_withoud_tax_row, 2), 2, '.', '') }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="tx-16 bg-light text-right">ضريبة القيمة المضافة - VAT</th>
                                                    <th class="tx-16">{{ number_format(round($invoicetotal_addedvalue, 2), 2, '.', '') }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="tx-16 bg-light text-right">الاجمالي الكلي - NET TOTAL</th>
                                                    <th class="tx-16">
                                                        {{ number_format(round($total_withoud_tax_row + $invoicetotal_addedvalue, 2), 2, '.', '') }}
                                                        <br>
                                                        <p class="mb-0 mt-1" style="color: red; font-size: 11px;">
                                                            <span>{{ $data['totatextlriyales'] }}</span> 
                                                            <span>{{ $data['totatextlrihalala'] }}</span>
                                                        </p>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ملاحظات العميل -->
                                    @if(!empty($data['invoiceData']->note))
                                    <div class="mt-3 p-2 border rounded bg-light" dir="rtl">
                                        <span class="tx-16 thick">{{ __('home.notesClient') }}: </span>
                                        <span class="tx-16">{!! $data['invoiceData']->note !!}</span>
                                    </div>
                                    @endif

                                </td>
                            </tr>
                        </tbody>

                        <tfoot class="report-footer">
                            <tr>
                                <td style="border: none;">
                                    <div class="footer" style="text-align: center; width: 100%; border-top: 1px solid #ddd; padding-top: 5px;">
                                        <span>{{ addressar }}</span> | <span>{{ addressen }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>



<script type="text/javascript">
    function printDiv() {
        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }


    $(document).ready(function() {

        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        setTimeout(() => {
            window.print();
        }, 500);
        setTimeout(() => {
            window.close();
        }, 10000);

    })
</script>
</script>

@endsection