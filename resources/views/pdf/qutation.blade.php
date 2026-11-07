<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>Arabic Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            font-family: 'Cairo', 'DejaVu Sans', sans-serif !important;
        }

        body {
            font-size: 11px;
            padding: 2px;
            margin: 8px;
            color: #1f2937;
            text-align: right;
            line-height: 1.3;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 3px 6px;
            text-align: center;
        }

        .bordered,
        .bordered td,
        .bordered th {
            border: 1px solid #d7dee8;
        }

        tr:nth-child(even) {
            background-color: #f5f8fb;
        }

        @page {
            size: a4;
            margin: 6px;
            padding: 0;
        }

        .row {
            display: block;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        .letterhead-table td {
            vertical-align: middle;
            padding: 2px 4px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1b3358;
            margin: 0;
            line-height: 1.2;
        }

        .company-line {
            font-size: 12px;
            color: #6b7280;
            display: block;
            line-height: 1.15;
            margin: 0;
        }

        .top-accent {
            height: 4px;
            background-color: #c8933a;
            margin-bottom: 6px;
            border-radius: 2px;
        }

        .quote-banner {
            background-color: #1b3358;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            padding: 5px 0;
            text-align: center;
            margin: 6px 0 10px 0;
            border-radius: 4px;
            border-right: 4px solid #c8933a;
        }

        .quote-banner .label-ar {
            display: inline;
        }

        .quote-banner .label-en {
            display: inline;
            font-size: 10px;
            font-weight: normal;
            color: #f4e6cf;
        }

        .quote-banner .label-en:before {
            content: " - ";
            color: #f4e6cf;
        }

        .meta-table th {
            background-color: #f0f3f9;
            font-size: 10.5px;
            color: #1b3358;
            padding: 4px 10px;
            white-space: nowrap;
            text-align: right;
        }

        .meta-table th .label-ar {
            font-weight: bold;
        }

        .meta-table th .label-en {
            font-size: 9px;
            font-weight: normal;
            color: #6b7280;
            direction: ltr;
        }

        .meta-table th .label-en:before {
            content: " - ";
            color: #9ca3af;
        }

        .double {
            border: 2px solid #dfe4ec;
            border-radius: 4px;
            width: 90%;
            font-size: 9px !important;
        }

        .meta-table td.value-cell {
            font-weight: bold;
            padding: 4px 8px;
            font-size: 10.5px;
        }

        .items-table thead td {
            background-color: #1b3358;
            color: #fff;
            font-weight: bold;
            font-size: 9.5px;
            padding: 5px 4px;
        }

        .items-table thead td .label-en {
            display: block;
            line-height: 1.25;
        }

        .items-table thead td .label-ar {
            display: block;
            font-size: 8.5px;
            font-weight: normal;
            color: #cdd8e8;
            line-height: 1.25;
        }

        .items-table tbody td {
            font-size: 10.5px;
            padding: 4px 5px;
        }

        .totals-wrap {
            width: 55%;
            margin-right: 0;
            margin-left: auto;
        }

        .totals-table td:nth-child(2) {
            background-color: #f0f3f9;
            font-weight: bold;
            width: 60%;
            padding: 4px 12px 4px 4px;
            text-align: right;
        }

        .totals-table td:nth-child(2) .label-ar {
            display: inline;
            font-size: 10.5px;
        }

        .totals-table td:nth-child(2) .label-en {
            display: inline;
            font-size: 8.5px;
            font-weight: normal;
            color: #6b7280;
            direction: ltr;
        }

        .totals-table td:nth-child(2) .label-en:before {
            content: " - ";
            color: #9ca3af;
        }

        .totals-table td:nth-child(1) {
            width: 40%;
            font-weight: bold;
            font-size: 11px;
        }

        .net-total-row td {
            background-color: #1b3358 !important;
            color: #fff;
            padding: 7px 12px;
        }

        .net-total-row td:nth-child(1) {
            font-size: 13px;
        }

        .net-total-row td:nth-child(2) .label-ar {
            font-size: 11.5px;
        }

        .net-total-row td:nth-child(2) .label-en {
            color: #f4e6cf;
        }

        .net-total-row .amount-words {
            color: #f4e6cf !important;
            font-size: 9px;
        }

        .bank-box {
            border: 1.5px solid #1b3358;
            border-radius: 4px;
            width: 60%;
            margin: 10px auto 0 auto;
            padding: 5px;
            font-size: 9px;
            color: #1b3358;
            text-align: center;
        }

        .footer-bar {
            position: fixed;
            bottom: 2px;
            width: 100%;
            text-align: center;
            border-top: 1px solid #dfe4ec;
            padding-top: 3px;
            font-size: 8px;
            color: #8b93a1;
        }

        .footer-bar center {
            line-height: 1.5;
        }
    </style>
</head>

<body>

    @php
        // Number-to-words helper (used for both the riyal and halala portions below).
        // Extracted once instead of being duplicated inline.
        function numberToWords($num)
        {
            $num = (int) str_replace([',', ''], '', trim((string) $num));
            if (!$num) {
                return '';
            }

            $words = [];
            $list1 = [
                '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
                'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen',
            ];
            $list2 = ['', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred'];
            $list3 = [
                '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion',
                'septillion', 'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion',
                'quattuordecillion', 'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion',
                'novemdecillion', 'vigintillion',
            ];

            $numLength = strlen($num);
            $levels = (int) (($numLength + 2) / 3);
            $maxLength = $levels * 3;
            $num = substr('00' . $num, -$maxLength);
            $numLevels = str_split($num, 3);

            for ($i = 0; $i < count($numLevels); $i++) {
                $levels--;
                $hundreds = (int) ($numLevels[$i] / 100);
                $hundreds = $hundreds ? ' ' . $list1[$hundreds] . ' hundred ' : '';
                $tens = (int) ($numLevels[$i] % 100);
                $singles = '';

                if ($tens < 20) {
                    $tens = $tens ? ' and ' . $list1[$tens] . ' ' : '';
                } else {
                    $tensDigit = (int) ($tens / 10);
                    $singlesDigit = (int) ($numLevels[$i] % 10);
                    $tens = ' and ' . $list2[$tensDigit] . ' ';
                    $singles = ' ' . $list1[$singlesDigit] . ' ';
                }

                $words[] = $hundreds . $tens . $singles . (($levels && (int) $numLevels[$i]) ? ' ' . $list3[$levels] . ' ' : '');
            }

            $words = implode(' ', $words);
            $words = preg_replace('/^\s\b(and)/', '', $words);

            return ucfirst(trim($words));
        }
    @endphp

    @php
        $logo = camplogo;
    @endphp

    <div class="top-accent"></div>

    <!-- letterhead -->
    <table class="letterhead-table">
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
                    <img src="{{ public_path('assets/img/brand') . '/' . $logo }}" style="width: 100px; height: 68px;">
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

    <div class="quote-banner">
        <span class="label-ar">عرض تسعيرة للعميل</span>
        <span class="label-en">QUOTATION</span>
    </div>

    @php
        $offer_price_to_customer = App\Models\offer_price_to_customer::find($itemsRequest[0]->order_id);
        $avt = App\Models\Avt::find(1);
    @endphp

    <!-- بيانات العميل والتسعيرة: جدول واحد بعرض كامل -->
    <table class="bordered meta-table" style="width:100%">
        <tbody>
            <tr>
                <td class="value-cell">{{ $offer_price_to_customer->customer->name }}</td>
                <th><span class="label-ar">اسم العميل</span><span class="label-en">CLIENT NAME</span></th>
            </tr>
            <tr>
                <td class="value-cell">{{ $offer_price_to_customer->customer->tax_no }}</td>
                <th><span class="label-ar">الرقم الضريبي</span><span class="label-en">TAX NUMBER</span></th>
            </tr>
            <tr>
                <td class="value-cell">{{ $offer_price_to_customer->created_at }}</td>
                <th><span class="label-ar">تاريخ التسعيرة</span><span class="label-en">Quote DATE</span></th>
            </tr>
            <tr>
                <td class="value-cell">{{ $offer_price_to_customer->id }}</td>
                <th><span class="label-ar">رقم التسعيرة</span><span class="label-en">Quote NUMBER</span></th>
            </tr>
        </tbody>
    </table>

    <br>

    <!-- items -->
    <table class="bordered items-table">
        <thead>
            <tr>
                <td><span class="label-en">Total AFTER DISCOUNT</span><span class="label-ar">الاجمالي بعد الخصم</span></td>
                <td><span class="label-en">DISCOUNT</span><span class="label-ar">الخصم</span></td>
                <td><span class="label-en">Total</span><span class="label-ar">الاجمالي</span></td>
                <td><span class="label-en">QUANTITY</span><span class="label-ar">الكمية</span></td>
                <td><span class="label-en">PRODUCT PRICE</span><span class="label-ar">سعر القطعة</span></td>
                <td><span class="label-en">ITEM NAME</span><span class="label-ar">اسم الصنف</span></td>
                <td><span class="label-en">Item NO</span><span class="label-ar">رقم منتج</span></td>
                <td><span class="label-en">NO</span><span class="label-ar">رقم</span></td>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
                $totalpricePurchases = 0;
                $totaldiscount = 0;
            @endphp

            @foreach ($itemsRequest as $product)
                @php
                    $i++;
                    $totalpricePurchases += $product->PriceWithoudTax * $product->quantity;
                    $totaldiscount += $product->discount;
                @endphp
                <tr>
                    <td>{{ round(($product->quantity * $product->PriceWithoudTax) - $product->discount, 2) }}</td>
                    <td>{{ $product->discount }}</td>
                    <td>{{ $product->quantity * $product->PriceWithoudTax }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->PriceWithoudTax }}</td>
                    <td>{{ $product->productData->product_name }}</td>
                    <td dir="ltr">{{ $product->productData->Product_Code }}</td>
                    <td>{{ $i }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <!-- totals -->
    <div class="row" dir="rtl">
        <div class="totals-wrap">
            <table class="bordered totals-table">
                <tbody>
                    <tr>
                        <td>{{ round($totalpricePurchases, 2) }}</td>
                        <td><span class="label-ar">الاجمالي</span><span class="label-en">SUB TOTAL</span></td>
                    </tr>
                    <tr>
                        <td>{{ round($totaldiscount, 2) }}</td>
                        <td><span class="label-ar">الخصم</span><span class="label-en">DISCOUNT</span></td>
                    </tr>
                    <tr>
                        <td>{{ round($totalpricePurchases, 2) - round($totaldiscount, 2) }}</td>
                        <td><span class="label-ar">الاجمالي بعد الخصم</span><span class="label-en">SUB TOTAL AFTER DISCOUNT</span></td>
                    </tr>
                    <tr>
                        <td>{{ round((round($totalpricePurchases, 2) - round($totaldiscount, 2)) * $avt->AVT, 2) }}</td>
                        <td><span class="label-ar">ضريبة القيمة المضافة ({{ $avt->AVT * 100 }}%)</span><span class="label-en">VALUE ADDED TAX</span></td>
                    </tr>

                    @php
                        $total = round((round($totalpricePurchases, 2) - round($totaldiscount, 2)) * $avt->AVT, 2)
                            + (round($totalpricePurchases, 2) - round($totaldiscount, 2));

                        [$whole, $decimal] = explode('.', number_format((float) $total, 2, '.', ''));

                        $decimalDigits = str_split($decimal);
                        $decimalValue = $decimalDigits[0] === '0' ? (int) $decimalDigits[1] : (int) $decimal;

                        $decimalWords = $decimalValue !== 0 ? numberToWords($decimalValue) : 'Zero';
                        $wholeWords = numberToWords((int) $whole);
                    @endphp

                    <tr class="net-total-row">
                        <td>
                            <center>
                                {{ number_format(round($total, 2), 2, '.', '') }}
                              
                            </center>
                        </td>
                        <td><span class="label-ar">الاجمالي الكلي</span><span class="label-en">NET TOTAL</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bank-box">
        البنك العربي الوطني<br>
        Account Number : 0108095553810016<br>
        IBAN Number : SA9830400108095553810016
    </div>

    <div class="footer-bar">
        <center>{{ addressar }}</center>
        <center>{{ addressen }}</center>
    </div>

</body>

</html>