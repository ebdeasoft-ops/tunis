@extends('layouts.master')
@section('css')
<style>
    @media print {
        @page { 
            size: A4 landscape;
            margin: 10mm;
        }
        
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* إخفاء القوائم والأزرار والعناصر غير المرغوبة عند الطباعة */
        .main-header, 
        .main-sidebar, 
        .main-footer, 
        .breadcrumb-header, 
        #print_Button,
        .no-print {
            display: none !important;
            visibility: hidden !important;
        }

        /* جعل محتوى الطباعة يملأ الشاشة بالعرض بدون ظلال أو حدود خارجية */
        #print, .report-box {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: #ffffff !important;
        }

        /* تنسيق الجداول لتناسب الورقة العرضية بوضوح */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 11px !important;
        }

        th, td {
            border: 1px solid #94a3b8 !important;
            padding: 5px !important;
            text-align: center !important;
        }

        thead th, .table-custom th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }

        /* منع انقسام البطاقات أو الصفوف بطريقة سيئة بين الصفحات */
        .invoice-card {
            break-inside: avoid;
            page-break-inside: avoid;
            border: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
            margin-bottom: 10px !important;
            box-shadow: none !important;
        }
    }

    /* التنسيقات العامة للشاشة العادية */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        color: #333;
    }

    .report-box {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        padding: 25px;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .billed-side {
        width: 33%;
    }

    .company-logo {
        width: 110px;
        height: 70px;
        object-fit: contain;
    }

    .voucher-title {
        color: #1e293b;
        font-weight: bold;
        margin: 15px 0;
        text-transform: uppercase;
        font-size: 18px;
        letter-spacing: 0.5px;
    }

    .table-custom th {
        background-color: #f8fafc !important;
        color: #1e293b !important;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        font-size: 12.5px;
    }

    .table-custom td {
        text-align: center;
        vertical-align: middle;
        font-size: 12px;
    }

    .invoice-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fafbfc;
        transition: all 0.2s ease;
    }
</style>
@endsection

@section('title')
    {{ __('home.voucher') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between no-print">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.voucher') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.index') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="report-box">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center mb-4 no-print">
                    <button class="btn btn-danger print-style px-4 py-2 font-weight-bold" id="print_Button" onclick="printDiv()" style="border-radius: 6px;">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body p-0">
                    <!-- رأس الفاتورة (بيانات الشركة بالإنجليزية يسار، الشعار بالمنتصف، وبيانات الشركة بالعربية يمين) -->
                    <div class="invoice-header">
                        <!-- بيانات الشركة بالإنجليزية (يسار) -->
                        <div class="billed-side text-left" dir="ltr">
                            <h4 style="font-size: 18px; font-weight: bold; color: #1e293b;">{{ Nameen ?? '' }}</h4>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ describtionen ?? '' }}</p>
                            <span class="d-block text-muted" style="font-size: 12px;">{{ STen ?? '' }}</span>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ Taxen ?? '' }}</p>
                        </div>

                        <!-- الشعار (منتصف) -->
                        <div class="billed-side text-center">
                            @php $logo = camplogo ?? ''; @endphp
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="company-logo" alt="logo">
                            </a>
                        </div>

                        <!-- بيانات الشركة بالعربية (يمين) -->
                        <div class="billed-side text-right" dir="rtl">
                            <h4 style="font-size: 18px; font-weight: bold; color: #1e293b;">{{ Namear ?? '' }}</h4>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ describtionar ?? '' }}</p>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ STar ?? '' }}</p>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ Taxar ?? '' }}</p>
                        </div>
                    </div><!-- invoice-header -->

                    <!-- عنوان السند -->
                    <div class="text-center">
                        <h5 class="voucher-title">{{ __('home.voucher') }}</h5>
                    </div>

                    <!-- جدول تواريخ الفلترة ووقت التصدير -->
                    @php
                        \Carbon\Carbon::setLocale('ar');
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                    @endphp
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-custom">
                            <tr>
                                <th>{{ __('report.fromdate') }}</th>
                                <td>{{ $start_at ?? '-' }}</td>
                                <th>{{ __('report.todate') }}</th>
                                <td>{{ $end_at ?? '-' }}</td>
                                <th>{{ __('home.exportTime') }}</th>
                                <td>{{ $currentdata }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- تفاصيل الفواتير / السندات -->
                    @if (isset($Invoices) && count($Invoices) > 0)
                        @php
                            $totalprice = 0;
                        @endphp

                        @foreach ($Invoices as $invoice)
                            @php
                                $totalprice += $invoice->recive_amount;
                            @endphp

                            <div class="invoice-card m-2 p-3 shadow-sm">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center mb-0 bg-white">
                                        <thead>
                                            <tr class="table-custom">
                                                <th>{{ __('report.invoiceNo') }}</th>
                                                <th>{{ $invoice->sent_abd_count ?? $invoice->id }}</th>
                                                <th>{{ __('report.reciver_name') }}</th>
                                                <th>{{ optional($invoice->user)->name }}</th>
                                                <th>{{ __('home.paymentmethod') }}</th>
                                                <th>-</th>
                                            </tr>
                                            <tr class="table-custom">
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.exportTime') }}</th>
                                                <th>{{ __('home.acount_name') }}</th>
                                                <th>{{ __('accountes.cashreceived') }}</th>
                                                <th>{{ __('home.paymentmethod') }}</th>
                                                <th>{{ __('home.notesClient') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">{{ $invoice->date_export ?? ($invoice->created_at ? $invoice->created_at->format('Y-m-d') : '-') }}</td>
                                                <td>{{ $invoice->created_at ?? '-' }}</td>
                                                <td class="font-weight-bold">{{ optional($invoice->financial_accounts_data)->name }}</td>
                                                <td class="font-weight-bold text-success" style="font-size: 14px;">{{ $invoice->recive_amount }}</td>
                                                <td>
                                                    @if ($invoice->pay_method == 'Cash')
                                                        <span class="text-success font-weight-bold">{{ __('report.cash') }}</span>
                                                    @elseif ($invoice->pay_method == 'Bank_transfer')
                                                        <span class="text-success font-weight-bold">{{ __('home.Bank_transfer') }}</span>
                                                    @else
                                                        <span class="text-warning font-weight-bold">{{ __('report.shabka') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $invoice->note ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <!-- جدول الإجمالي النهائي -->
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered text-center table-custom">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 10%">#</th>
                                        <th scope="col" style="width: 45%">{{ __('report.totalprice') }}</th>
                                        <th scope="col" style="width: 45%">{{ __('home.the amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="background-color: #f8fafc;">
                                        <th scope="row">1</th>
                                        <td class="font-weight-bold">{{ __('home.total') }}</td>
                                        <td class="font-weight-bold text-success" style="font-size: 16px;">{{ $totalprice }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <p>لا توجد بيانات متاحة</p>
                        </div>
                    @endif

                </div><!-- card-body -->
            </div><!-- report-box -->
        </div><!-- main-content-body-invoice -->
    </div><!-- COL-END -->
</div>
<!-- row closed -->
@endsection

@section('js')
<!-- Internal Chart.bundle js -->
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
</script>
@endsection