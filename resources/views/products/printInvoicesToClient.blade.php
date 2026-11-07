@extends('layouts.master')

@section('title')
معاينة طباعة الفاتورة - Simplified Tax Invoice
@stop

@section('css')
<style>
    /* تنسيقات الطباعة */
    @media print {
        #print_Button, .breadcrumb-header { display: none !important; }
        body { margin: 0; padding: 0; }
        @page { size: A4; margin: 10mm; }
        .invoice-container { border: none !important; width: 100% !important; }
        .gray-bg, .table-bordered thead th {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* تنسيقات التصميم العام */
    .invoice-container {
        width: 95%;
        margin: auto;
        font-family: 'Arial', sans-serif;
        color: #333;
        direction: rtl;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: -1px; /* لدمج الحدود بين الجداول */
    }

    .table-custom th, .table-custom td {
        border: 1px solid #777;
        padding: 6px;
        font-size: 15px;
        text-align: center;
        font-weight: bold;
    }

    .double {
        font-size: 16px;
        text-align: center;
        font-weight: bold;
    }

    .gray-bg {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .text-right { text-align: right !important; }
    .text-left { text-align: left !important; }

    .header-section td { border: none !important; }

    .invoice-title {
        border: 2px solid #333;
        padding: 5px;
        font-weight: bold;
        margin: 10px 0;
        text-align: center;
        border-radius: 5px;
        font-size: 14px;
    }

    .qr-box {
        border: 1px solid #777;
        width: 120px;
        height: 120px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .footer-note {
        font-weight: bold;
        font-size: 18px;
        text-align: center;
        margin-top: 15px;
        border-top: 1px solid #ccc;
        padding-top: 10px;
    }

    /* جدول البيانات الأساسية */
    .table-invoice-header {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0px;
    }

    .table-invoice-header td {
        border: 1px solid #777;
        padding: 2px 4px !important;
        font-size: 13px !important;
        text-align: center;
        font-weight: bold;
        line-height: 1.1;
        height: 25px;
    }

    .table-invoice-header .gray-bg {
        background-color: #f2f2f2;
        font-size: 15px !important;
        color: #333;
    }

    /* جدول الأصناف */
    .table-bordered th, .table-bordered td {
        border: 1px solid #777 !important;
        padding: 4px 3px !important;
        font-size: 15px !important;
        vertical-align: middle;
        font-weight: bold;
        overflow: visible;               /* كان hidden وبيقص الاسم */
    }

    .table-bordered thead th {
        font-weight: bold;
        background-color: #eee !important;
        color: #000;
    }

    /* خلية اسم الصنف */
    .table-bordered td.item-name {
        text-align: right;
        word-break: normal;              /* كان break-all */
        overflow-wrap: anywhere;         /* يكسر فقط لو الكلمة أطول من الخلية */
        unicode-bidi: plaintext;         /* ترتيب صحيح للعربي والإنجليزي */
        line-height: 1.5;
        white-space: normal;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 text-center mt-3">
        <button class="btn btn-danger" id="print_Button" onclick="printDiv()">
            {{ __('home.print') }} <i class="mdi mdi-printer"></i>
        </button>
    </div>
</div>

<div class="invoice-container mt-4" id="print">
    <table class="table-custom header-section">
        <tr>
            <td width="35%" class="text-right">
                <h4 class="mb-1">{{ Namear }}</h4>
                <p class="mb-0">{{ describtionar }}</p>
                <p class="mb-0">{{ STar }}</p>
                <p class="mb-0">{{ Taxar }}</p>
            </td>
            <td width="30%">
                @php $logo = camplogo; @endphp
                <div style="display: flex; align-items: center; justify-content: center; gap: 9px; margin-bottom: 5px;">
                    <img src="{{ asset('assets/img/brand/rzoil.jpeg') }}" style="width: 160px; height: auto;">
                    <img src="{{ asset('assets/img/brand') . '/' . $logo }}" alt="logo" style="width: 140px; height: auto;">
                    <img src="{{ asset('assets/img/brand/op.jpeg') }}" style="width: 160px; height: auto;">
                </div>
            </td>
            <td width="35%" class="text-left" dir="ltr">
                <h4 class="mb-1">{{ Nameen }}</h4>
                <p class="mb-0">{{ describtionen }}</p>
                <p class="mb-0">{{ STen }}</p>
                <p class="mb-0">{{ Taxen }}</p>
            </td>
        </tr>
    </table>

    <div class="gray-bg">
        @if(strlen($data['invoiceData']->customer->tax_no) == 15)
            <center><p class="double">Tax Invoice - فاتورة ضريبية</p></center>
        @else
            <center><p class="double">Simplified tax invoice فاتورة ضريبية مبسطة</p></center>
        @endif
    </div>

    <table class="table-invoice-header" dir="rtl" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td class="gray-bg text-right" width="20%">تاريخ الفاتورة</td>
            <td class="text-center" width="60%">{{ $data['invoiceData']->created_at->format('Y-m-d H:i') }}</td>
            <td class="gray-bg text-left" width="20%">DATE</td>
        </tr>

        <tr>
            <td class="gray-bg text-right">طريقة الدفع</td>
            <td class="text-center">
                @php
                    $payMap = [
                        'Cash'   => 'نقدي / Cash',
                        'Shabka' => 'شبكة / Network',
                        'Credit' => 'آجل / Credit'
                    ];
                    $pay = $payMap[$data['invoiceData']->Pay] ?? $data['invoiceData']->Pay;
                @endphp
                {{ $pay }}
            </td>
            <td class="gray-bg text-left">PAYMENT</td>
        </tr>

        <tr>
            <td class="gray-bg text-right">رقم الفاتورة</td>
            <td class="text-center">{{ $data['invoiceData']->id }}</td>
            <td class="gray-bg text-left">INVOICE NO</td>
        </tr>

        <tr>
            <td class="gray-bg text-right">رقم أمر الشراء</td>
            <td class="text-center">{{ $data['invoiceData']->p_o ?: '-' }}</td>
            <td class="gray-bg text-left">PURCHASE ORDER (PO)</td>
        </tr>

        <tr>
            <td class="gray-bg text-right">اسم العميل</td>
            <td class="text-center">{{ $data['invoiceData']->customer->name }}</td>
            <td class="gray-bg text-left">CLIENT NAME</td>
        </tr>

        <tr>
            <td class="gray-bg text-right">الرقم الضريبي</td>
            <td class="text-center">{{ $data['invoiceData']->customer->tax_no ?: '-' }}</td>
            <td class="gray-bg text-left">TAX NUMBER</td>
        </tr>

        <tr>
            <td class="gray-bg text-right">السجل التجاري</td>
            <td class="text-center">{{ $data['invoiceData']->customer->CRN }}</td>
            <td class="gray-bg text-left">COMMERCIAL REG</td>
        </tr>

        <tr>
            <td class="gray-bg text-right">العنوان / الحي</td>
            <td class="tx-16 text-center">
                @if($data['invoiceData']->customer->id == 1)
                    -
                @else
                    {{ $data['invoiceData']->customer->address }}, {{ $data['invoiceData']->customer->sub_city }},
                    {{ $data['invoiceData']->customer->building_number }} {{ $data['invoiceData']->customer->street_name }}
                @endif
                | {{ $data['invoiceData']->customer->postcode }}
            </td>
            <td class="gray-bg text-left">ADDRESS</td>
        </tr>
    </table>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center mb-0" style="width: 100%; border: 1px solid #777; table-layout: fixed;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="width: 45px;">م <br> NO</th>
                    @if($data['invoiceData']->display_number)
                        <th style="width: 13%;">رقم المنتج <br> Item NO</th>
                    @endif
                    {{-- بدون width ليأخذ المساحة المتبقية --}}
                    <th>اسم الصنف <br> ITEM NAME</th>
                    <th style="width: 9%;">السعر <br> PRICE</th>
                    <th style="width: 7%;">الكمية <br> QTY</th>
                    <th style="width: 8%;">الخصم <br> DISC</th>
                    <th style="width: 13%;">الاجمالي قبل الضريبة <br> TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                    $avt = App\Models\Avt::find(1);
                @endphp

                @foreach (App\Models\sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                    @if($product->quantity != 0)
                        @php $i++ @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            @if($data['invoiceData']->display_number)
                                <td dir="ltr">{{ $product->productData->Product_Code }}</td>
                            @endif
                            <td class="item-name">{{ $product->productData->product_name }}</td>
                            <td>{{ number_format($product->Unit_Price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ number_format($product->Discount_Value, 2) }}</td>

                            @php
                                $total_row_befor_tax = round(($product->Unit_Price * $product->quantity) - $product->Discount_Value, 2);
                                $added_value_row = round($total_row_befor_tax, 2);
                            @endphp

                            <td>{{ number_format($total_row_befor_tax, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <br>

    <div style="border: 1px solid #777; padding: 5px; text-align: center; font-size: 18px; margin-top: 5px;">
        يمكن ارجاع القطع المباعة او استبدالها خلال 3 يام من تاريخ الشراء وتكون بحالتها المباعة و القطع الكهربائية لا ترد ولا تستبدل و الطلبات الخاص لا يمكن ارجاعها او استبدلها بعد تاكيد الطلب
    </div>

    @php
        $invoice = App\Models\invoices::find($data['invoiceData']->id);
        $total_price = $invoice->total_amount;
        $tax_amount = $total_price * 0.15;
        $net_total = $total_price + $tax_amount;
    @endphp

    <table class="table-custom" style="margin-top: 10px;">
        <tr>
            <td width="50%" class="text-right" style="vertical-align: top;">
                <div style="display: flex; align-items: flex-start; gap: 15px;">
                    <div class="qr-box">
                        <?php
                            $avtSaleRate = App\Models\Avt::find(1);
                            $avtSaleRate = $avtSaleRate->AVT;

                            if (!function_exists('ConvertToHEX')) {
                                function ConvertToHEX($value)
                                {
                                    return pack("H*", sprintf("%02X", $value));
                                }
                            }

                            $sellerName = sallerQrCode;
                            $varNumber = TaxQrCode;
                            $time = \Carbon\Carbon::now()->addHours(3);

                            $total = (round($data['invoicetotal_addedvalue'] + $data['invoicetotal_price'], 2));
                            $tax = round($data['invoicetotal_addedvalue'], 2);
                            $HexSeller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName));
                            $seller  = $HexSeller . $sellerName;
                            $HexVAT  = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber));
                            $vat  = $HexVAT . $varNumber;
                            $HexTime = ConvertToHEX(3) . ConvertToHEX(strlen($time));
                            $time  = $HexTime . $time;
                            $HexTotal = ConvertToHEX(4) . ConvertToHEX(strlen($total));
                            $total  = $HexTotal . $total;
                            $HexVATN = ConvertToHEX(5) . ConvertToHEX(strlen($tax));
                            $VATN  = $HexVATN . $tax;

                            $tobase = $seller . $vat . $time . $total . $VATN;
                            $dataforQRcode = base64_encode($tobase);
                        ?>
                        {!! QrCode::size(100)->generate($dataforQRcode) !!}
                    </div>
                    <div style="font-size: 11px;">
                        <strong>بيانات الحساب البنكي:</strong><br>
                        @if(Auth()->user()->branchs_id == 1)
                            &nbsp;
                            <center><p class="double">البنك السعودي الاول <br>Account Number : 353375439001 <br> IBAN Number : SA0945000000353375439001</p></center>
                            &nbsp;
                        @else
                            &nbsp;
                            <center><p class="double">البنك العربي الوطني <br>Account Number :0108095553810016 <br> IBAN Number : SA9830400108095553810016</p></center>
                            &nbsp;
                        @endif
                    </div>
                </div>
            </td>

            <td width="50%" style="padding: 0;">
                <table class="table-custom" style="border: none;">
                    <tr>
                        <td class="gray-bg text-right" width="60%">الإجمالي (غير شامل الضريبة) SUB TOTAL</td>
                        <td class="text-left">
                            {{ number_format($data['invoicetotal_price'] + $data['invoicetotal_discount'], 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="gray-bg text-right">إجمالي الخصم DISCOUNT</td>
                        <td class="text-left">
                            {{ number_format($data['invoicetotal_discount'], 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="gray-bg text-right"><strong>الإجمالي بعد الخصم TOTAL AFTER DISCOUNT</strong></td>
                        <td class="text-left">
                            <strong>{{ number_format($data['invoicetotal_price'], 2) }}</strong>
                        </td>
                    </tr>

                    <tr>
                        <td class="gray-bg text-right">إجمالي الضريبة (15%) TOTAL VAT</td>
                        <td class="text-left">
                            {{ number_format($data['invoicetotal_addedvalue'], 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="gray-bg text-right"><strong>المبلغ المستحق THE AMOUNT</strong></td>
                        <td class="text-left">
                            <strong>{{ number_format($data['invoicetotal_addedvalue'] + $data['invoicetotal_price'], 2) }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        <p class="mb-0">{{ addressar }}</p>
        <p class="mb-0" dir="ltr">{{ addressen }}</p>
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
    });
</script>
@endsection