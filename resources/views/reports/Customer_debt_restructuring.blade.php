@extends('layouts.master')

@section('css')
<style>
    /* تنسيقات الطباعة الرسمية والنظيفة */
    @media print {
        @page {
            size: A4 landscape; /* وضع العرض ضروري لتقارير أعمار الديون لكثرة الأعمدة */
            margin: 5mm; 
        }

        body, html {
            background-color: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
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

    /* رأس التقرير والشركة */
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

    /* جدول أعمار الديون الاحترافي */
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
        padding: 12px 8px;
        font-weight: 600;
        text-align: center;
        font-size: 12.5px;
        vertical-align: middle;
    }

    .table-official tbody td {
        padding: 10px 8px;
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

    .filter-info-table td, .filter-info-table th {
        padding: 10px !important;
        font-size: 13px;
    }
    .filter-info-table th {
        background-color: #f8fafc !important;
        color: #475569 !important;
    }

    .text-success-custom { color: #059669 !important; font-weight: 600; }
</style>
@endsection

@section('title')
    {{ __('home.Customer_debt_restructuring') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between no-print">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Customer_debt_restructuring') }}</h4>
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
                    <!-- رأس التقرير (بيانات الشركة يمين ويسار والشعار بالمنتصف) -->
                    <div class="invoice-header" dir="rtl">
                        <!-- بيانات الشركة بالإنجليزية (يسار بالصفحة / يمين بالكود) -->
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
                                    <h4>{{ __('home.Customer_debt_restructuring') }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- بيانات الشركة بالعربية (يمين بالصفحة / يسار بالكود) -->
                        <div class="billed-from text-left" dir="ltr">
                            <h4 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">{{ Nameen ?? '' }}</h4>
                            <p class="mb-1" style="font-size: 13px; color: #64748b;">{{ describtionen ?? '' }}</p>
                            <span class="d-block mb-1" style="font-size: 13px; color: #64748b;">{{ STen ?? '' }}</span>
                            <p class="mb-0" style="font-size: 13px; color: #64748b;">{{ Taxen ?? '' }}</p>
                        </div>
                    </div><!-- invoice-header -->

                    <!-- جدول وقت التصدير المدمج -->
                    @php
                        \Carbon\Carbon::setLocale('ar');
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                    @endphp
                    <div class="table-responsive my-3">
                        <table class="table table-bordered text-center table-official filter-info-table" style="max-width: 400px; margin: 0 auto;">
                            <tr>
                                <th style="width: 40%;">{{ __('home.exportTime') }}</th>
                                <td style="width: 60%;" class="font-weight-bold text-dark">{{ $currentdata }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- جدول بيانات أعمار الديون للعملاء الاحترافي -->
                    <div class="table-responsive mt-4">
                        <table class="table table-official">
                            <thead>
                                <tr>
                                    <th style="width: 4%;">#</th>
                                    <th style="width: 14%;">اسم العميل <br><span style="font-size: 11px; font-weight: normal; opacity: 0.8;">Client Name</span></th>
                                    <th style="width: 10%;">اخر سداد <br><span style="font-size: 11px; font-weight: normal; opacity: 0.8;">Last payment</span></th>
                                    <th style="width: 8%;">الرصيد <br><span style="font-size: 11px; font-weight: normal; opacity: 0.8;">Balance</span></th>
                                    <th style="width: 9%;">عمر الدين <br><span style="font-size: 11px; font-weight: normal; opacity: 0.8;">Age</span></th>
                                    <th style="width: 7%;">0 : 10</th>
                                    <th style="width: 7%;">11 : 30</th>
                                    <th style="width: 7%;">31 : 60</th>
                                    <th style="width: 7%;">61 : 90</th>
                                    <th style="width: 7%;">91 : 120</th>
                                    <th style="width: 7%;">121 : 180</th>
                                    <th style="width: 9%;">اكبر من 180 <br><span style="font-size: 11px; font-weight: normal; opacity: 0.8;">> 180</span></th>
                                    <th style="width: 10%;">الاجمالي <br><span style="font-size: 11px; font-weight: normal; opacity: 0.8;">Total</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($data) && count($data) > 0)
                                    @php 
                                        $i = 0; 
                                        $sum_0_10 = 0;
                                        $sum_11_30 = 0;
                                        $sum_31_60 = 0;
                                        $sum_61_90 = 0;
                                        $sum_91_120 = 0;
                                        $sum_121_180 = 0;
                                        $sum_gt_180 = 0;
                                        $sum_total = 0;
                                    @endphp
                                    @foreach ($data as $item)
                                        @php 
                                            $i++; 
                                            $row_total = ($item['f_0_t_10'] ?? 0) + 
                                                         ($item['f_11_t_30'] ?? 0) + 
                                                         ($item['f_31_t_60'] ?? 0) + 
                                                         ($item['f_61_t_90'] ?? 0) + 
                                                         ($item['f_91_t_120'] ?? 0) + 
                                                         ($item['f_121_t_180'] ?? 0) + 
                                                         ($item['f_181_t_00'] ?? 0);

                                            $sum_0_10 += ($item['f_0_t_10'] ?? 0);
                                            $sum_11_30 += ($item['f_11_t_30'] ?? 0);
                                            $sum_31_60 += ($item['f_31_t_60'] ?? 0);
                                            $sum_61_90 += ($item['f_61_t_90'] ?? 0);
                                            $sum_91_120 += ($item['f_91_t_120'] ?? 0);
                                            $sum_121_180 += ($item['f_121_t_180'] ?? 0);
                                            $sum_gt_180 += ($item['f_181_t_00'] ?? 0);
                                            $sum_total += $row_total;
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td class="font-weight-bold text-right">{{ $item['name'] ?? '' }}</td>
                                            <td>{{ $item['lastdate'] ?? '-' }}</td>
                                            <td>-</td>
                                            <td>{{ $item['life_creadit'] ?? 0 }}</td>
                                            <td>{{ number_format($item['f_0_t_10'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($item['f_11_t_30'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($item['f_31_t_60'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($item['f_61_t_90'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($item['f_91_t_120'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($item['f_121_t_180'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($item['f_181_t_00'] ?? 0, 2) }}</td>
                                            <td class="font-weight-bold text-success-custom">{{ number_format($row_total, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    <!-- صف إجمالي الأعمدة -->
                                    <tr style="background-color: #f8fafc; border-top: 2px solid #cbd5e1; font-weight: bold;">
                                        <td colspan="5" class="text-right text-dark" style="font-size: 13.5px;">{{ __('home.total') }} :</td>
                                        <td>{{ number_format($sum_0_10, 2) }}</td>
                                        <td>{{ number_format($sum_11_30, 2) }}</td>
                                        <td>{{ number_format($sum_31_60, 2) }}</td>
                                        <td>{{ number_format($sum_61_90, 2) }}</td>
                                        <td>{{ number_format($sum_91_120, 2) }}</td>
                                        <td>{{ number_format($sum_121_180, 2) }}</td>
                                        <td>{{ number_format($sum_gt_180, 2) }}</td>
                                        <td class="text-success-custom" style="font-size: 15px;">{{ number_format($sum_total, 2) }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="13" class="text-center text-muted py-4">لا توجد بيانات متاحة</td>
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