@extends('layouts.master')
@section('css')
<style>
    /* تنسيقات الطباعة الرسمية والنظيفة */
    @media print {
        /* إلغاء هوامش ورقة الطباعة الافتراضية بالمتصفح لمنع المسافات الفارغة */
        @page {
            size: A4;
            margin: 0mm; 
        }

        body, html {
            background-color: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
            overflow: visible !important;
        }

        .main-header, 
        .main-sidebar, 
        .main-footer, 
        .breadcrumb-header, 
        .no-print, 
        nav, 
        aside, 
        header, 
        footer,
        #print_Button {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
        }

        body * {
            visibility: hidden !important;
        }

        #print, #print * {
            visibility: visible !important;
        }

        /* جعل العنصر يبدأ من أعلى الصفحة تماماً وبدون أي ترحيل */
        #print {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0px !important;
            background-color: #fff !important;
            box-shadow: none !important;
            border: none !important;
        }

        .report-container {
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            padding: 5px !important;
        }

        .card-invoice {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        
        .table-responsive {
            overflow: visible !important;
        }
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        color: #334155;
    }

    .report-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        padding: 30px;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    /* رأس الفاتورة والشركة */
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .billed-from {
        width: 33%;
    }

    .company-logo {
        width: 100px;
        height: 80px;
        object-fit: contain;
    }

    .voucher-title-badge {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 20px;
        display: inline-block;
        text-align: center;
        margin: 15px 0;
    }

    .voucher-title-badge h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    /* الجداول الرسمية */
    .table-official {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        margin-bottom: 20px;
    }

    .table-official thead th {
        background-color: #1e293b !important;
        color: #ffffff !important;
        border: none;
        padding: 12px 10px;
        font-weight: 600;
        text-align: center;
        font-size: 13.5px;
    }

    .table-official tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        color: #334155;
        border-top: 1px solid #e2e8f0;
        text-align: center;
        font-size: 13px;
        background-color: #ffffff;
    }

    .table-official tbody tr:hover {
        background-color: #f8fafc;
    }

    /* جدول الفلترة المدمج */
    .filter-info-table td, .filter-info-table th {
        padding: 10px !important;
        font-size: 13px;
    }
    .filter-info-table th {
        background-color: #f8fafc !important;
        color: #475569 !important;
    }

    .text-success-custom { color: #059669 !important; font-weight: 600; }
    .text-warning-custom { color: #d97706 !important; font-weight: 600; }
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
            <div class="report-container">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center mb-4 no-print">
                    <button class="btn btn-danger px-5 py-2 font-weight-bold shadow-sm" id="print_Button" onclick="printDiv()" style="border-radius: 6px;">
                        <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                    </button>
                </div>

                <div class="card-body p-0">
                    <!-- رأس الفاتورة (بيانات الشركة يمين ويسار والشعار بالمنتصف) -->
                    <div class="invoice-header" dir="rtl">
                        <!-- بيانات الشركة بالعربية (يمين) -->
                        <div class="billed-from text-right">
                            <h4 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">{{ Namear ?? '' }}</h4>
                            <p class="mb-1" style="font-size: 13px; color: #64748b;">{{ describtionar ?? '' }}</p>
                            <p class="mb-1" style="font-size: 13px; color: #64748b;">{{ STar ?? '' }}</p>
                            <p class="mb-0" style="font-size: 13px; color: #64748b;">{{ Taxar ?? '' }}</p>
                        </div>

                        <!-- الشعار وعنوان التقرير (منتصف) -->
                        <div class="billed-from text-center" style="width: 34%;">
                            @php $logo = camplogo ?? ''; @endphp
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="company-logo" alt="logo">
                            </a>
                            <div class="mt-2">
                                <div class="voucher-title-badge">
                                    <h4>{{ __('home.voucher') }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- بيانات الشركة بالإنجليزية (يسار) -->
                        <div class="billed-from text-left" dir="ltr">
                            <h4 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">{{ Nameen ?? '' }}</h4>
                            <p class="mb-1" style="font-size: 13px; color: #64748b;">{{ describtionen ?? '' }}</p>
                            <span class="d-block mb-1" style="font-size: 13px; color: #64748b;">{{ STen ?? '' }}</span>
                            <p class="mb-0" style="font-size: 13px; color: #64748b;">{{ Taxen ?? '' }}</p>
                        </div>
                    </div><!-- invoice-header -->

                    <!-- جدول تواريخ الفلترة ووقت التصدير -->
                    @php
                        \Carbon\Carbon::setLocale('ar');
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                    @endphp
                    <div class="table-responsive my-3">
                        <table class="table table-bordered text-center table-official filter-info-table">
                            <tr>
                                <th style="width: 15%;">{{ __('report.fromdate') }}</th>
                                <td style="width: 18%;" class="font-weight-bold text-primary">{{ $start_at ?? '-' }}</td>
                                <th style="width: 15%;">{{ __('report.todate') }}</th>
                                <td style="width: 18%;" class="font-weight-bold text-primary">{{ $end_at ?? '-' }}</td>
                                <th style="width: 15%;">{{ __('home.exportTime') }}</th>
                                <td style="width: 19%;" class="font-weight-bold text-dark">{{ $currentdata }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- جدول تفاصيل السندات والفواتير -->
                    <div class="table-responsive mt-4">
                        <table class="table table-official">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 12%;">{{ __('report.invoiceNo') }}</th>
                                    <th scope="col" style="width: 15%;">{{ __('home.exportTime') }}</th>
                                    <th scope="col" style="width: 12%;">{{ __('home.date') }}</th>
                                    <th scope="col" style="width: 14%;">{{ __('report.reciver_name') }}</th>
                                    <th scope="col" style="width: 17%;">{{ __('home.acount_name') }}</th>
                                    <th scope="col" style="width: 12%;">{{ __('accountes.cashreceived') }}</th>
                                    <th scope="col" style="width: 13%;">{{ __('home.paymentmethod') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($Invoices) && count($Invoices) > 0)
                                    @php
                                        $i = 0;
                                        $totalprice = 0;
                                    @endphp
                                    @foreach ($Invoices as $invoice)
                                        @php
                                            $i++;
                                            $totalprice += $invoice->recive_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td class="font-weight-bold">{{ $invoice->sent_abd_count ?? $invoice->id }}</td>
                                            <td>{{ $invoice->created_at ?? '-' }}</td>
                                            <td>{{ $invoice->date_export ?? ($invoice->created_at ? $invoice->created_at->format('Y-m-d') : '-') }}</td>
                                            <td>{{ optional($invoice->user)->name }}</td>
                                            <td class="font-weight-bold">{{ optional($invoice->financial_accounts_data)->name }}</td>
                                            <td class="font-weight-bold text-success-custom">{{ number_format($invoice->recive_amount, 2) }}</td>
                                            <td>
                                                @if ($invoice->pay_method == 'Cash')
                                                    <span class="badge badge-success px-2 py-1" style="font-size: 12px;">{{ __('report.cash') }}</span>
                                                @elseif ($invoice->pay_method == 'Bank_transfer')
                                                    <span class="badge badge-primary px-2 py-1" style="font-size: 12px;">{{ __('home.Bank_transfer') }}</span>
                                                @else
                                                    <span class="badge badge-warning px-2 py-1" style="font-size: 12px;">{{ __('report.shabka') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    <!-- صف الإجمالي النهائي -->
                                    <tr style="background-color: #f8fafc; border-top: 2px solid #cbd5e1;">
                                        <td colspan="6" class="text-right font-weight-bold text-dark" style="font-size: 14px;">{{ __('home.total') }} :</td>
                                        <td colspan="2" class="text-success-custom" style="font-size: 16px;">{{ number_format($totalprice, 2) }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">لا توجد بيانات متاحة</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div><!-- card-body -->
            </div><!-- report-container -->
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
    window.print();
}
</script>
@endsection