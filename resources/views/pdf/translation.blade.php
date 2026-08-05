<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif !important;
        }

        body {
            font-size: 11px;
            padding: 5px;
            margin: 5px;
            color: #222;
            text-align: right;
            background: #fff;
        }

        .tx-center {
            text-align: center !important;
        }

        .tx-left {
            text-align: left !important;
        }

        .tx-right {
            text-align: right !important;
        }

        .bold {
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 5px 8px;
            text-align: center;
            vertical-align: middle;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .invoice-title {
            background-color: #f8f9fa;
            border: 2px solid #2c3e50;
            border-radius: 6px;
            padding: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin: 10px 0;
        }

        .info-table {
            border: 1px solid #bdc3c7;
            width: 100%;
            margin-bottom: 10px;
            background-color: #fff;
        }

        .info-table td {
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .products-table {
            width: 100%;
            border: 1px solid #2c3e50;
            margin-top: 10px;
        }

        .products-table th {
            background-color: #2c3e50;
            color: #ffffff;
            border: 1px solid #2c3e50;
            font-size: 11px;
            padding: 6px;
        }

        .products-table td {
            border: 1px solid #cbd5e1;
            font-size: 10.5px;
        }

        .products-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .totals-table td {
            border: 1px solid #cbd5e1;
            padding: 5px;
            font-size: 11px;
        }

        .double-box {
            border: 2px solid #7f8c8d;
            padding: 6px;
            font-size: 10px;
            background-color: #fdfdfd;
        }

        @page {
            size: a4;
            margin: 10px;
        }

        .footer-note {
            position: fixed;
            text-align: center;
            bottom: 5px;
            width: 100%;
            font-size: 9px;
            color: #555;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>

<body>

        <?php
      $logo = camplogo;
      ?>
    <div dir="rtl">
        <table class="header-table">
            <tr>
                <td style="width:35%; text-align: right;">
                    <span class="bold" style="font-size:12px;">{{Namear}}</span><br>
                    <span style="font-size:10px; color:#555;">{{describtionar}}</span><br>
                    <span style="font-size:10px; color:#555;">{{STar}}</span><br>
                    <span style="font-size:10px; color:#555;">{{Taxar}}</span>
                </td>
          <td style="width:35%">
                    <center>
                        <img src="{{ public_path('assets/img/brand').'/'.$logo }}" style="width: 150px; height: 100px;">
                    </center>
                </td>

                <td style="width:35%; text-align: left;" dir="ltr">
                    <span class="bold" style="font-size:12px;">{{Nameen}}</span><br>
                    <span style="font-size:10px; color:#555;">{{describtionen}}</span><br>
                    <span style="font-size:10px; color:#555;">{{STen}}</span><br>
                    <span style="font-size:10px; color:#555;">{{Taxen}}</span>
                </td>
            </tr>
        </table>
    </div>

    @if($data['invoiceData']->customer->id == 1)
        <div class="invoice-title">Simplified Tax Invoice — فاتورة ضريبية مبسطة</div>
    @else
        <div class="invoice-title">Tax Invoice — فاتورة ضريبية</div>
    @endif

    @php
        $pay = '';
        $payMethod = $data['invoiceData']->Pay ?? '';
        if ($payMethod == "Cash") {
            $pay = __('report.cash');
        } elseif ($payMethod == "Shabka") {
            $pay = __('report.shabka');
        } elseif ($payMethod == "Credit") {
            $pay = __('report.credit');
        } elseif ($payMethod == "Bank_transfer") {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('home.Partition of the amount');
        }
    @endphp

    <table class="info-table" dir="rtl">
        <tr>
            <td class="bold" style="background:#f1f5f9; width:20%;">رقم الفاتورة <br>INVOICE NO</td>
            <td style="width:30%;">{{ $data['invoiceData']->id }}</td>
            <td class="bold" style="background:#f1f5f9; width:20%;">اسم الفرع <br>BRANCH NAME</td>
            <td style="width:30%;">{{ $data['invoiceData']->branch->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="bold" style="background:#f1f5f9;">طريقة الدفع <br>PAYMENT METHOD</td>
            <td>{{ $pay }}</td>
            <td class="bold" style="background:#f1f5f9;">تاريخ الفاتورة <br>INVOICE DATE</td>
            <td>{{ $data['invoiceData']->created_at }}</td>
        </tr>
    </table>

    <table class="info-table" dir="rtl">
        <tr>
            <td class="bold" style="background:#f1f5f9; width:15%;">اسم البائع <br>SELLER</td>
            <td style="width:35%;">{{ $Namear ?? '-' }}</td>
            <td class="bold" style="background:#f1f5f9; width:15%;">اسم العميل <br>CLIENT</td>
            <td style="width:35%;">{{ $data['invoiceData']->customer->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="bold" style="background:#f1f5f9;">الرقم الضريبي <br>TAX NO</td>
            <td>{{ $Taxen ?? '-' }}</td>
            <td class="bold" style="background:#f1f5f9;">الرقم الضريبي <br>TAX NO</td>
            <td>{{ $data['invoiceData']->customer->tax_no == 0 ? '-' : $data['invoiceData']->customer->tax_no }}</td>
        </tr>
        <tr>
            <td class="bold" style="background:#f1f5f9;">المدينة <br>CITY</td>
            <td>{{ defined('city') ? city : ($city ?? '-') }}</td>
            <td class="bold" style="background:#f1f5f9;">العنوان / الجوال <br>ADDRESS</td>
            <td>
                @if($data['invoiceData']->customer->address == '-')
                    {{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->phone }}
                @else
                    {{ $data['invoiceData']->customer->id == 1 ? '-' : $data['invoiceData']->customer->address }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="bold" style="background:#f1f5f9;">المنطقة <br>REGION</td>
            <td>{{ defined('region') ? region : ($region ?? '-') }}</td>
            <td class="bold" style="background:#f1f5f9;">المنطقة الفرعية <br>SUB-REGION</td>
            <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : ($data['invoiceData']->customer->sub_city ?? '-') }}</td>
        </tr>
        <tr>
            <td class="bold" style="background:#f1f5f9;">الشارع <br>STREET</td>
            <td>{{ defined('street_name') ? street_name : ($street_name ?? '-') }}</td>
            <td class="bold" style="background:#f1f5f9;">الشارع <br>STREET</td>
            <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : ($data['invoiceData']->customer->street_name ?? '-') }}</td>
        </tr>
        <tr>
            <td class="bold" style="background:#f1f5f9;">الرمز البريدي <br>POSTAL</td>
            <td>{{ defined('postal_number') ? postal_number : ($postal_number ?? '-') }}</td>
            <td class="bold" style="background:#f1f5f9;">الرمز البريدي <br>POSTAL</td>
            <td>{{ $data['invoiceData']->customer->postcode ?? '-' }}</td>
        </tr>
        <tr>
            <td class="bold" style="background:#f1f5f9;">رقم المبنى <br>BLDG NO</td>
            <td>{{ defined('building_number') ? building_number : ($building_number ?? '-') }}</td>
            <td class="bold" style="background:#f1f5f9;">رقم المبنى <br>BLDG NO</td>
            <td>{{ $data['invoiceData']->customer->id == 1 ? '-' : ($data['invoiceData']->customer->building_number ?? '-') }}</td>
        </tr>
    </table>

    <table class="products-table" dir="ltr">
        <thead>
            <tr>
                <th style="width:5%;">NO<br>رقم</th>
                <th style="width:12%;">Item NO<br>رقم المنتج</th>
                <th style="width:25%;">ITEM NAME<br>اسم الصنف</th>
                <th style="width:8%;">QUANTITY<br>الكمية</th>
                <th style="width:12%;">PRODUCT PRICE<br>سعر القطعة</th>
                <th style="width:12%;">Total<br>الإجمالي</th>
                <th style="width:11%;">DISCOUNT<br>الخصم</th>
                <th style="width:15%;">Total AFTER DISCOUNT<br>الإجمالي بعد الخصم</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $i = 0;
                $discountreturn = 0;
            @endphp

            @foreach (App\Models\sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                @if($product->quantity != 0)
                    @php $i++; @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td dir="rtl">{{ $product->productData->Product_Code ?? '-' }}</td>
                        <td class="tx-right">{{ $product->productData->product_name ?? '-' }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ number_format($product->Unit_Price, 2, '.', '') }}</td>
                        <td>{{ number_format($product->Unit_Price * $product->quantity, 2, '.', '') }}</td>
                        <td>{{ number_format($product->Discount_Value, 2, '.', '') }}</td>
                        <td>{{ number_format(($product->Unit_Price * $product->quantity) - $product->Discount_Value, 2, '.', '') }}</td>
                    </tr>
                @endif
            @endforeach

            @foreach (App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                @php 
                    $discountreturn += ($product->discountvalue ?? 0) + ($product->discountoninvoice ?? 0);
                @endphp
                @if($product->return_quantity != 0)
                    @php $i++; @endphp
                    <tr>
                        <td style="color:red;">{{ $i }}</td>
                        <td dir="rtl" style="color:red;">{{ $product->productData->Product_Code ?? '-' }}</td>
                        <td class="tx-right" style="color:red;">{{ $product->productData->product_name ?? '-' }} (مرتجع)</td>
                        <td style="color:red;">{{ $product->return_quantity }}</td>
                        <td style="color:red;">{{ number_format($product->return_Unit_Price, 2, '.', '') }}</td>
                        <td style="color:red;">{{ number_format($product->return_Unit_Price * $product->return_quantity, 2, '.', '') }}</td>
                        <td style="color:red;">{{ number_format($product->discountvalue, 2, '.', '') }}</td>
                        <td style="color:red;">{{ number_format(($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue, 2, '.', '') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    @php
        function ConvertToHEX($value) {
            return pack("H*", sprintf("%02X", $value));
        }
        $invoice = App\Models\invoices::find($data['invoiceData']->id);
        $price = ($invoice->cashamount ?? 0) + ($invoice->Bank_transfer ?? 0) + ($invoice->bankamount ?? 0) + ($invoice->creaditamount ?? 0);
        $avt = App\Models\Avt::find(1);
        $avtRate = $avt ? $avt->AVT : 0.15;

        $price_befor_tax = $price * 100 / (100 + ($avtRate * 100));
        $invoicetotal_addedvalue = $price_befor_tax * $avtRate;
        $invoicetotal_price = $price_befor_tax;
        $invoicetotal_discount = ($invoice->discount ?? 0) + $discountreturn;

        $sellerName = defined('sallerQrCode') ? sallerQrCode : 'Seller';
        $varNumber = defined('TaxQrCode') ? TaxQrCode : '000000000000000';
        $time = $invoice->created_at ?? now();
        $issue_time = substr($time, 11);
        $issue_date = substr($time, 0, 10);
        $formattedTime = (string)$issue_date . 'T' . (string)$issue_time;

        $totalVal = number_format((round($invoicetotal_addedvalue + $invoicetotal_price, 2)), 2, '.', '');
        $taxVal = number_format(round($invoicetotal_addedvalue, 2), 2, '.', '');

        $HexSeller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName));
        $sellerData = $HexSeller . $sellerName;
        $HexVAT = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber));
        $vatData = $HexVAT . $varNumber;
        $HexTime = ConvertToHEX(3) . ConvertToHEX(strlen($formattedTime));
        $timeData = $HexTime . $formattedTime;
        $HexTotal = ConvertToHEX(4) . ConvertToHEX(strlen($totalVal));
        $totalData = $HexTotal . $totalVal;
        $HexVATN = ConvertToHEX(5) . ConvertToHEX(strlen($taxVal));
        $VATNData = $HexVATN . $taxVal;
        
        $empty = '';
        $tobase = $sellerData . $vatData . $timeData . $totalData . $VATNData . 
                  ConvertToHEX(6).ConvertToHEX(strlen($empty)).$empty . 
                  ConvertToHEX(7).ConvertToHEX(strlen($empty)).$empty . 
                  ConvertToHEX(8).ConvertToHEX(strlen($empty)).$empty . 
                  ConvertToHEX(9).ConvertToHEX(strlen($empty)).$empty;

        $dataforQRcode = base64_encode($tobase);
    @endphp

    <div style="margin-top: 10px;">
        <table style="width:100%; border:none;">
            <tr>
                <td style="width: 45%; vertical-align: top; padding: 0;">
                    <table class="totals-table" dir="rtl">
                        <tr>
                            <td class="bold" style="background:#f1f5f9;">الإجمالي — SUB TOTAL</td>
                            <td>{{ number_format(round($invoicetotal_price, 2) + round($invoicetotal_discount, 2), 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="background:#f1f5f9;">الخصم — DISCOUNT</td>
                            <td>{{ number_format(round($invoicetotal_discount, 2), 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="background:#f1f5f9;">الإجمالي بعد الخصم — SUB TOTAL AFTER DISCOUNT</td>
                            <td>{{ number_format(round($invoicetotal_price, 2), 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td class="bold" style="background:#f1f5f9;">ضريبة القيمة المضافة ({{ $avtRate * 100 }}%)</td>
                            <td>{{ number_format(round($invoicetotal_addedvalue, 2), 2, '.', '') }}</td>
                        </tr>
                        <tr style="background:#e2e8f0; font-weight:bold;">
                            <td>الإجمالي الكلي — NET TOTAL</td>
                            <td>{{ number_format(round($invoicetotal_addedvalue + $invoicetotal_price, 2), 2, '.', '') }}</td>
                        </tr>
                    </table>
                </td>

                <td style="width: 30%; vertical-align: top; padding: 0 5px;">
                    @if(Auth()->user()->branchs_id == 1)
                        <div class="double-box tx-center">
                            <strong>{{ defined('bankname') ? bankname : 'Bank Name' }}</strong><br>
                            Account: {{ defined('bank_acount_number') ? bank_acount_number : '-' }}<br>
                            IBAN: {{ defined('bank_acount_iban') ? bank_acount_iban : '-' }}
                        </div>
                    @endif
                </td>

                <td style="width: 25%; vertical-align: top; text-align: center; padding: 0;">
                    <div style="background: #fff; padding: 5px; border: 1px solid #cbd5e1; display: inline-block; border-radius: 4px;">
                        <img src="data:image/png;base64,{!! base64_encode(QrCode::size(100)->generate($dataforQRcode)) !!}" style="width:95px; height:95px;">
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 8px;">
        <div class="double-box tx-center" style="font-size: 9.5px; color: #444;">
            يمكن إرجاع القطع المباعة أو استبدالها خلال 7 أيام من تاريخ الشراء بشرط أن تكون بحالتها الأصلية.<br>
            <strong>القطع الكهربائية لا ترد ولا تستبدل.</strong>
        </div>
    </div>

    @if(!empty($data['invoiceData']->note))
        <div style="margin-top: 5px; font-size: 11px;">
            <strong>{{ __('home.notesClient') }}:</strong> {{ $data['invoiceData']->note }}
        </div>
    @endif

    <div class="footer-note">
        @if(Auth()->user()->branchs_id == 1)
            <div>{{ defined('addressar') ? addressar : '' }}</div>
            <div>{{ defined('addressen') ? addressen : '' }}</div>
        @endif
    </div>

</body>
</html>