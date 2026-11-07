<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            font-family: DejaVu Sans !important;
        }

        body {
            font-size: 12px;
            padding: 2px;
            margin: 10px;
            color: #000;
            text-align: right;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 3px 5px;
            text-align: center;
        }

        .border,
        .border td,
        .border th {
            border: 1px solid #000;
        }

        tr:nth-child(even) {
            background-color: #eef6f8;
        }

        @page {
            size: a4;
            margin: 4px;
            padding: 0;
        }

        .double {
            border: 3px solid #888;
            border-radius: 5px;
            width: 90%;
            font-size: 10px !important;
        }

        .row {
            display: block;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        /* --- professional styling additions --- */
        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #2e3d50;
        }

        .company-line {
            font-size: 11px;
            color: #333;
            display: block;
        }

        .invoice-banner {
            background-color: #2e3d50;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 0;
            text-align: center;
            border-radius: 4px;
            width: 90% !important;
        }

        .meta-table td,
        .customer-table td {
            border: 1px solid #000;
            font-size: 12px;
        }

        .meta-table tr:nth-child(odd) td,
        .customer-table tr:nth-child(odd) td {
            background-color: #ecf0fa;
        }

        .items-table thead td {
            background-color: #2e3d50;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
        }

        .totals-table td:nth-child(2) {
            background-color: #ecf0fa;
            font-weight: bold;
        }

        .net-total-row td {
            background-color: #2e3d50 !important;
            color: #fff;
            font-size: 13px;
        }

        .return-row td {
            color: #c0392b;
        }

        .footer-bar {
            position: fixed;
            bottom: 0px;
            width: 100%;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 4px;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>

<body>

    @php
        // Guard against a fatal "cannot redeclare function" error if this
        // template is ever rendered more than once in the same request.
        if (!function_exists('ConvertToHEX')) {
            function ConvertToHEX($value)
            {
                return pack('H*', sprintf('%02X', $value));
            }
        }

        $logo = camplogo;
    @endphp

    <!-- letterhead -->
    <table>
        <tr>
            <td style="width:35%">
                <center>
                    <span class="company-name">{{ Nameen }}</span><br>
                    <span class="company-line">{{ describtionen }}</span>
                    <span class="company-line">{{ STen }}</span>
                    <span class="company-line">{{ Taxen }}</span>
                </center>
            </td>
            <td style="width:30%">
                <center>
                    <img src="{{ public_path('assets/img/brand') . '/' . $logo }}" style="width: 150px; height: 100px;">
                </center>
            </td>
            <td style="width:35%">
                <center>
                    <span class="company-name">{{ Namear }}</span><br>
                    <span class="company-line">{{ describtionar }}</span>
                    <span class="company-line">{{ STar }}</span>
                    <span class="company-line">{{ Taxar }}</span>
                </center>
            </td>
        </tr>
    </table>

    <center>
        @if ($data['invoiceData']->customer->id == 1)
            <p class="invoice-banner">Simplified Tax Invoice - فاتورة ضريبية مبسطة</p>
        @else
            <p class="invoice-banner">Tax Invoice - فاتورة ضريبية</p>
        @endif
    </center>

    @php
        $pay = '';
        if ($data['invoiceData']->Pay == 'Cash') {
            $pay = __('report.cash');
        } elseif ($data['invoiceData']->Pay == 'Shabka') {
            $pay = __('report.shabka');
        } elseif ($data['invoiceData']->Pay == 'Credit') {
            $pay = __('report.credit');
        } elseif ($data['invoiceData']->Pay == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('home.Partition of the amount');
        }
    @endphp

<table dir="rtl" class="meta-table double" style="width:100%">
    <tbody>
        <tr>
            <td>{{ $data['invoiceData']->id }}</td>
            <td>INVOICE NUMBER<br>رقم الفاتورة</td>
            <td>{{ $data['invoiceData']->branch->name }}</td>
            <td>BRANCH NAME<br>اسم الفرع</td>
        </tr>
        <tr>
            <td>{{ $pay }}</td>
            <td>PAYMENT METHOD<br>طريقة الدفع</td>
            <td>{{ $data['invoiceData']->created_at }}</td>
            <td>INVOICE DATE<br>تاريخ الفاتورة</td>
        </tr>
        <tr>
            <td>{{ $data['invoiceData']->p_o ?: '-' }}</td>
            <td>PURCHASE ORDER<br>امر الشراء</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>

    <br>

    <!-- customer / seller details -->
    <table dir="rtl" class="customer-table double" style="width:100%">
        <tbody>
            <tr>
                <td>{{ Namear }}</td>
                <td>SELLER NAME اسم البائع</td>
                <td>{{ $data['invoiceData']->customer->name }}</td>
                <td>CLIENT NAME اسم العميل</td>
            </tr>

            <tr>
                <td>{{ Taxen }}</td>
                <td>TAX NUMBER الرقم الضريبي</td>
                <td>{{ $data['invoiceData']->customer->tax_no == 0 ? '-' : $data['invoiceData']->customer->tax_no }}</td>
                <td>TAX NUMBER الرقم الضريبي</td>
            </tr>

            @if ($data['invoiceData']->customer->address == '-')
                <tr>
                    <td>{{ defined('city') ? city : '-' }}</td>
                    <td>المدينة CITY</td>
                    <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->phone }}</td>
                    <td>رقم الجوال PHONE</td>
                </tr>
            @else
                <tr>
                    <td>{{ defined('city') ? city : '-' }}</td>
                    <td>المدينة CITY</td>
                    <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->address }}</td>
                    <td>العنوان ADDRESS</td>
                </tr>
            @endif

            <tr>
                <td>{{ defined('region') ? region : '-' }}</td>
                <td>المنطقة REGION</td>
                <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->sub_city }}</td>
                <td>المنطقة REGION</td>
            </tr>
            <tr>
                <td>{{ defined('street_name') ? street_name : '-' }}</td>
                <td>اسم الشارع STREET NAME</td>
                <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->street_name }}</td>
                <td>اسم الشارع STREET NAME</td>
            </tr>
            <tr>
                <td>{{ defined('postal_number') ? postal_number : '-' }}</td>
                <td>الرمز البريدي POSTAL NUMBER</td>
                <td>{{ $data['invoiceData']->customer->postcode }}</td>
                <td>الرمز البريدي POSTAL NUMBER</td>
            </tr>
            <tr>
                <td>{{ defined('building_number') ? building_number : '-' }}</td>
                <td>رقم المبنى BUILDING NUMBER</td>
                <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->building_number }}</td>
                <td>رقم المبنى BUILDING NUMBER</td>
            </tr>
        </tbody>
    </table>

    <br>

    <!-- items -->
    <div dir="ltr">
        <table class="border items-table">
            <thead>
                <tr>
                    <td>Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</td>
                    <td>DISCOUNT<br>الخصم</td>
                    <td>Total<br>الاجمالي</td>
                    <td>PRODUCT PRICE<br>سعر القطعة</td>
                    <td>QUANTITY<br>الكمية</td>
                    <td>ITEM NAME<br>اسم الصنف</td>
                    <td>Item NO<br>رقم منتج</td>
                    <td>NO<br>رقم</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                    $discountreturn = 0;
                @endphp

                @foreach (App\Models\sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                    @if ($product->quantity != 0)
                        @php $i++; @endphp
                        <tr>
                            <td>{{ number_format(($product->Unit_Price * $product->quantity) - $product->Discount_Value, 2, '.', '') }}</td>
                            <td>{{ number_format($product->Discount_Value, 2, '.', '') }}</td>
                            <td>{{ number_format($product->Unit_Price * $product->quantity, 2, '.', '') }}</td>
                            <td>{{ number_format($product->Unit_Price, 2, '.', '') }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->productData->product_name }}</td>
                            <td dir="rtl">{{ $product->productData->Product_Code }}</td>
                            <td>{{ $i }}</td>
                        </tr>
                    @endif
                @endforeach

                @foreach (App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                    @php
                        $i++;
                        $discountreturn += $product->discountvalue + $product->discountoninvoice;
                    @endphp
                    @if ($product->return_quantity != 0)
                        <tr class="return-row">
                            <td>{{ number_format((float) (($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue), 2, '.', '') }}</td>
                            <td>{{ number_format((float) $product->discountvalue, 2, '.', '') }}</td>
                            <td>{{ number_format((float) $product->return_Unit_Price * $product->return_quantity, 2, '.', '') }}</td>
                            <td>{{ number_format($product->return_Unit_Price, 2, '.', '') }}</td>
                            <td>{{ $product->return_quantity }}</td>
                            <td>{{ $product->productData->product_name }}</td>
                            <td dir="rtl">{{ $product->productData->Product_Code }}</td>
                            <td>{{ $i }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <br>

    <div class="row" dir="ltr">
        @php
            // ZATCA simplified tax invoice QR payload — logic preserved exactly as original.
            $invoice = App\Models\invoices::find($data['invoiceData']->id);
            $price = $invoice->cashamount + $invoice->Bank_transfer + $invoice->bankamount + $invoice->creaditamount;
            $avt = App\Models\Avt::find(1);

            $price_befor_tax = ($price * 100) / (100 + $avt->AVT * 100);
            $invoicetotal_addedvalue = $price_befor_tax * $avt->AVT;
            $invoicetotal_price = $price_befor_tax;
            $invoicetotal_discount = $invoice->discount + $discountreturn;

            $sellerName = sallerQrCode;
            $varNumber = TaxQrCode;
            $time = $invoice->created_at;
            $issue_time = substr($time, 11);
            $issue_date = substr($time, 0, 10);
            $time = (string) $issue_date . 'T' . (string) $issue_time;

            $total = number_format(round($invoicetotal_addedvalue + $invoicetotal_price, 2), 2, '.', '');
            $tax = number_format(round($invoicetotal_addedvalue, 2), 2, '.', '');

            $HexSeller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName));
            $seller = $HexSeller . $sellerName;
            $HexVAT = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber));
            $vat = $HexVAT . $varNumber;
            $HexTime = ConvertToHEX(3) . ConvertToHEX(strlen($time));
            $time = $HexTime . $time;
            $HexTotal = ConvertToHEX(4) . ConvertToHEX(strlen($total));
            $total = $HexTotal . $total;
            $HexVATN = ConvertToHEX(5) . ConvertToHEX(strlen($tax));
            $VATN = $HexVATN . $tax;

            $empty = '';
            $Hexempty = ConvertToHEX(6) . ConvertToHEX(strlen($empty));
            $empty6 = $Hexempty . $empty;
            $Hexempty = ConvertToHEX(7) . ConvertToHEX(strlen($empty));
            $empty7 = $Hexempty . $empty;
            $Hexempty = ConvertToHEX(8) . ConvertToHEX(strlen($empty));
            $empty8 = $Hexempty . $empty;
            $Hexempty = ConvertToHEX(9) . ConvertToHEX(strlen($empty));
            $empty9 = $Hexempty . $empty;

            $tobase = $seller . $vat . $time . $total . $VATN . $empty6 . $empty7 . $empty8 . $empty9;
            $dataforQRcode = base64_encode($tobase);
        @endphp

        <center>
<p class="double" dir="rtl" style="direction: rtl; text-align: center;">
    يمكن ارجاع القطع المباعة او استبدالها خلال 3 ايام من تاريخ الشراء وتكون بحالتها المباعة <br>
    والقطع الكهربائية لا ترد ولا تستبدل والطلبات الخاصة لا يمكن ارجاعها او استبدالها بعد تاكيد الطلب
</p>
        </center>

        <div class="row" dir="rtl">
            <table style="width:100%">
                <tr>
                    <td style="width:40%">
                        <table class="border totals-table">
                            <tbody>
                                <tr>
                                    <td>{{ number_format((float) (round($invoicetotal_price, 2) + round($invoicetotal_discount, 2)), 2, '.', '') }}</td>
                                    <td>الاجمالي - SUB TOTAL</td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(round($invoicetotal_discount, 2), 2, '.', '') }}</td>
                                    <td>الخصم - DISCOUNT</td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(round($invoicetotal_price, 2), 2, '.', '') }}</td>
                                    <td>الاجمالي بعد الخصم<br>SUB TOTAL AFTER DISCOUNT</td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(round($invoicetotal_addedvalue, 2), 2, '.', '') }}</td>
                                    <td>ضريبة القيمة المضافة ({{ $avt->AVT * 100 }}%)<br>VALUE ADDED TAX ({{ $avt->AVT * 100 }}%)</td>
                                </tr>
                                <tr class="net-total-row">
                                    <td>{{ number_format(round($invoicetotal_addedvalue + $invoicetotal_price, 2), 2, '.', '') }}</td>
                                    <td>الاجمالي الكلي - NET TOTAL</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <td style="width:35%">
                        @if (Auth()->user()->branchs_id == 1)
                            <center>
                                <p class="double">
                                    {{ bankname }}<br>
                                    Account Number : {{ bank_acount_number }}<br>
                                    IBAN Number : {{ bank_acount_iban }}
                                </p>
                            </center>
                        @endif
                    </td>

                    <td style="width:25%">
                        <center>
                            <img src="data:image/png;base64,{!! base64_encode(QrCode::size(110)->generate($dataforQRcode)) !!}">
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <br>
    <center >  <p  class="double">  البنك العربي الوطني   <br>Account Number :0108095553810016  <br>  IBAN Number : SA9830400108095553810016</P></center>


    <span class="tx-16">{{ __('home.notesClient') }} : {{ $data['invoiceData']->note }}</span>

    <div class="footer-bar">
            <center><span dir="rtl">{{ addressar }}</span></center>
            <center><span>{{ addressen }}</span></center>
    </div>

</body>

</html>