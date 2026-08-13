<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>Invoice Voucher</title>
    <meta charset="utf-8">

    <style>
        body {
            font-size: 13px;
            font-family: 'DejaVu Sans', sans-serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 10px;
            text-align: right;
            line-height: 1.6;
        }

        .voucher-box {
            border: 2px solid #333;
            padding: 15px;
            width: 100%;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .thick {
            font-weight: bold;
        }

        .voucher-title {
            text-align: center;
            margin: 10px 0 20px 0;
        }

        .voucher-title span {
            background-color: #f2f2f2;
            padding: 6px 25px;
            font-size: 15px;
            font-weight: bold;
            border: 1px solid #333;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .content-table th {
            background-color: #419BB2;
            color: #fff;
            font-weight: bold;
            padding: 8px;
            text-align: center;
            font-size: 12px;
            border: 1px solid #333;
        }

        .content-table td {
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #333;
            font-size: 12px;
        }

        .signatures-table {
            width: 100%;
            border: none;
            margin-top: 20px;
        }

        .signatures-table td {
            border: none;
            width: 100%;
            font-weight: bold;
            font-size: 12px;
            padding: 6px 0;
            text-align: right;
        }

        .signature-line {
            border-bottom: 1px dashed #333;
            display: inline-block;
            width: 250px;
            margin-right: 10px;
            vertical-align: bottom;
        }

        .footer-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            width: 100%;
        }

        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
</head>

<body>

    <div class="voucher-box">
        <?php $logo = camplogo; ?>

        <!-- ترويسة الفاتورة -->
        <table class="header-table">
            <tr>
     
             <!-- بيانات الشركة بالإنجليزية (يسار) -->
                <td style="text-align: left; width: 35%;">
                    <div style="font-size: 14px; font-weight: bold;">{{Nameen}}</div>
                    <div class="thick" style="font-size: 10px;">{{describtionen}}</div>
                    <div style="font-size: 10px;">{{STen}}</div>
                    <div style="font-size: 10px;">{{Taxen}}</div>
                </td>
                <!-- اللوجو (منتصف) -->
                <td style="text-align: center; width: 30%;">
                    <img src="{{ public_path('assets/img/brand').'/'.$logo }}" style="width: 100px; height: 60px;">
                </td>
           <!-- بيانات الشركة بالعربي (يمين) -->
                <td style="text-align: right; width: 35%;">
                    <div style="font-size: 15px; font-weight: bold;">{{Namear}}</div>
                    <div class="thick" style="font-size: 10px;">{{describtionar}}</div>
                    <div style="font-size: 10px;">{{STar}}</div>
                    <div style="font-size: 10px;">{{Taxar}}</div>
                </td>
   
            </tr>
        </table>

        <!-- عنوان السند -->
        <div class="voucher-title">
            <span>{{__('home.voucher')}}</span>
        </div>

        <!-- جدول بيانات السند -->
        <table class="content-table">
            <thead>
                <tr>
                    <th>{{ __('home.decoumentNo') }}</th>
                    <th>{{ __('home.name') }}</th>
                    <th>{{ __('home.date') }}</th>
                    <th>{{ __('home.paymentmethod') }}</th>
                    <th>{{ __('accountes.Remainingamount') }}</th>
                    <th>{{ __('accountes.Theamountpaid') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="thick">{{$data['transaction']['id']}}</td>
                    <td>{{$data['transaction']['name']}}</td>
                    <td>{{$data['transaction']['date']}}</td>
                    <td>
                        <?php
                        $paymethod = '';
                        if($data['transaction']['method_pay'] == "Cash"){
                            $paymethod = __('report.cash');
                        }elseif ($data['transaction']['method_pay'] == 'Bank_transfer') {
                            $paymethod = __('home.Bank_transfer');
                        } else {
                            $paymethod = __('report.shabka');
                        }
                        ?>
                        {{$paymethod}}
                    </td>
                    <td>{{$data['transaction']['Balance']}}</td>
                    <td class="thick" style="color: #0d9488;">{{$data['transaction']['paid_amount']}}</td>
                </tr>
            </tbody>
        </table>



    </div>

    <!-- تذييل الصفحة -->
    <div class="footer-section">
        @if(Auth()->user()->branchs_id == 1)
            <span>{{addressar}}</span> &nbsp;|&nbsp; <span>{{addressen}}</span>
        @endif
    </div>

</body>

</html>