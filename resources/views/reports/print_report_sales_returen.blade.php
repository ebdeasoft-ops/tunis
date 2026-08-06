@extends('layouts.master')
@section('css')
<style>
    /* تنسيقات المستندات الرسمية والحكومية */
    body {
        font-family: 'Amiri', 'Traditional Arabic', 'Segoe UI', Tahoma, sans-serif;
        line-height: 1.8;
        background-color: #ffffff;
        color: #000000;
    }

    .official-document {
        background: #ffffff;
        padding: 40px;
        border: 1px solid #ccc;
        max-width: 1000px;
        margin: 20px auto;
    }

    /* هيدر الجهات الرسمية (شعار يمين، شعار/بيانات يسار، عنوان رئيسي في المنتصف) */
    .official-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px double #000;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .official-header .header-side {
        width: 30%;
        font-size: 13px;
        color: #333;
    }

    .official-header .header-center {
        width: 40%;
        text-align: center;
    }

    .official-header .header-center h3 {
        font-weight: bold;
        font-size: 20px;
        margin-bottom: 5px;
        color: #111;
    }

    .report-title-box {
        text-align: center;
        margin: 30px 0;
        border: 1px solid #333;
        padding: 10px;
        background-color: #f9f9f9;
    }

    .report-title-box h4 {
        margin: 0;
        font-weight: bold;
        font-size: 18px;
        color: #000;
    }

    /* جدول رسمي احترافي (أبيض وأسود عالي الوضوح للطباعة الرسمية) */
    .table-official {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .table-official th, .table-official td {
        border: 1px solid #222;
        padding: 10px 8px;
        text-align: center;
        font-size: 14px;
    }

    .table-official th {
        background-color: #e6e6e6 !important;
        color: #000;
        font-weight: bold;
    }

    /* قسم الإجماليات والتوقيعات الرسمية */
    .summary-signature-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 40px;
        page-break-inside: avoid;
    }

    .signature-box {
        width: 45%;
        text-align: center;
        margin-top: 20px;
    }

    .signature-box .line {
        margin-top: 60px;
        border-top: 1px dashed #000;
        width: 80%;
        margin-left: auto;
        margin-right: auto;
        padding-top: 5px;
        font-weight: bold;
    }

    .summary-official {
        width: 45%;
    }

    .summary-official table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary-official th, .summary-official td {
        border: 1px solid #222;
        padding: 8px 12px;
        font-size: 14px;
    }

    .summary-official th {
        background-color: #f2f2f2;
        text-align: right;
    }

    .summary-official td {
        text-align: left;
        font-weight: bold;
    }

    /* إعدادات الطباعة الرسمية للجهات */
    @media print {
        #print_Button {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
        }
        .official-document {
            border: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }
    }
</style>
@endsection

@section('title')
{{__('home.print')}}
@stop

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="official-document">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-end mb-4">
                    <button class="btn btn-secondary px-4 py-2" id="print_Button" onclick="printDiv()" style="border-radius: 0; font-weight: bold;">
                        <i class="mdi mdi-printer ml-1"></i> طباعة المستند 
                    </button>
                </div>

                <!-- الهيدر الرسمي المعتمد -->
                <div class="official-header">
                    <div class="header-side" style="text-align: right;">
                        <span style="font-size:16px; font-weight:bold;">{{ Namear ?? '' }}</span>
                        <p style="margin:2px 0;">{{ describtionar ?? '' }}</p>
                        <p style="margin:2px 0;"> {{ STar ?? '' }}</p>
                        <p style="margin:2px 0;">{{ Taxar ?? '' }}</p>
                    </div>
                    
                    <div class="header-center">
                        @php $logo = camplogo ?? 'logo.png'; @endphp
                        <img src="{{ asset('assets/img/brand/'.$logo) }}" alt="Logo" style="max-height: 70px; width: auto; margin-bottom: 5px;">
                        <div style="font-size: 12px; font-weight: bold;">المملكة العربية السعودية</div>
                    </div>

                    <div class="header-side" style="text-align: left;" dir="ltr">
                        <span style="font-size:16px; font-weight:bold;">{{ Nameen ?? '' }}</span>
                        <p style="margin:2px 0;">{{ describtionen ?? '' }}</p>
                        <p style="margin:2px 0;">{{ STen ?? '' }}</p>
                        <p style="margin:2px 0;"> {{ Taxen ?? '' }}</p>
                    </div>
                </div>

                <!-- عنوان التقرير -->
        <div class="report-title-box">
    <h4>تقرير مرتجعات المبيعات</h4>
    <span style="font-size: 13px; color: #555;">
        الفترة من: {{  $start ?? '' }} إلى: {{ $end ?? '' }} 
        | تاريخ الإصدار: {{ date('Y-m-d') }}
    </span>
</div>

                @php
                    $avt_data = App\Models\Avt::find(1);
                    $saleavt = $avt_data->AVT ?? 0.15;
                    
                    $grandTotalBeforeTax = 0;
                    $grandTotalTax = 0;
                    $i = 0;
                @endphp

                <!-- جدول البيانات الرئيسي -->
                <table class="table-official" id="example1">
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>{{__('report.date')}}</th>
                            <th>{{__('report.invoiceNo')}}</th>
                            <th>{{__('home.quantity')}}</th>
                            <th>{{__('home.price')}} (صافي)</th>
                            <th>{{__('home.addedValue')}}</th>
                            <th>{{__('home.discount')}}</th>
                            <th>{{__('home.total')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Invoices as $invoiceId => $items)
                            @php
                                $i++;
                                $invoiceQty = 0;
                                $invoiceDiscount = 0;
                                $subTotalBeforeTax = 0;
                                $invoiceDate = '';

                                $collectionItems = is_iterable($items) ? $items : [$items];

                                foreach($collectionItems as $item) {
                                    if($item && is_object($item)) {
                                        $qty = $item->return_quantity ?? 0;
                                        $price = $item->return_Unit_Price ?? 0;
                                        $dVal = $item->discountvalue ?? 0;
                                        $dInv = $item->discountoninvoice ?? 0;

                                        $invoiceQty += $qty;
                                        $invoiceDiscount += ($dVal + $dInv);
                                        $subTotalBeforeTax += ($price * $qty) - ($dVal + $dInv);

                                        if(empty($invoiceDate) && isset($item->created_at) && $item->created_at) {
                                            $invoiceDate = $item->created_at->format('Y-m-d');
                                        }
                                    }
                                }

                                $taxAmount = $subTotalBeforeTax * $saleavt;
                                $totalWithTax = $subTotalBeforeTax + $taxAmount;

                                $grandTotalBeforeTax += $subTotalBeforeTax;
                                $grandTotalTax += $taxAmount;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $invoiceDate }}</td>
                                <td>{{$item->invoice_id  }}</td>
                                <td>{{ number_format($invoiceQty, 0) }}</td>
                                <td>{{ number_format($subTotalBeforeTax, 2) }}</td>
                                <td>{{ number_format($taxAmount, 2) }}</td>
                                <td>{{ number_format($invoiceDiscount, 2) }}</td>
                                <td>{{ number_format($totalWithTax, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- قسم الإجماليات والتوقيعات الرسمية -->
                <div class="summary-signature-section">
                    <!-- جدول الإجماليات -->
                    <div class="summary-official">
                        <table>
                            <tr>
                                <th>{{__('report.totalpricewithoudtax')}}</th>
                                <td>{{ number_format($grandTotalBeforeTax, 2) }}</td>
                            </tr>
                            <tr>
                                <th>{{__('report.totaltax')}}</th>
                                <td>{{ number_format($grandTotalTax, 2) }}</td>
                            </tr>
                            <tr style="background-color: #e6e6e6;">
                                <th>{{__('report.totalallprice')}}</th>
                                <td>{{ number_format($grandTotalBeforeTax + $grandTotalTax, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- خانات التوقيع والختم الرسمي -->
                    <div class="signature-box">
                        <p style="margin-bottom: 5px; font-weight: bold; font-size: 14px;">ختم المنشأة / اعتماد الإدارة</p>
                        <div class="line">التوقيع والختم</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function printDiv() {
        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
@endsection