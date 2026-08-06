@extends('layouts.master')

@section('css')
<style>
    /* تنسيقات عامة لشكل الفاتورة الاحترافي */
    .invoice-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        font-family: 'Cairo', sans-serif, Arial, sans-serif;
    }
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .billed-from {
        width: 33%;
    }
    .company-logo {
        width: 110px;
        height: 70px;
        object-fit: contain;
    }
    .voucher-title-badge {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 6px 12px;
        display: inline-block;
        text-align: center;
    }
    .voucher-title-badge h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #333;
    }
    .budgetSheet-table th {
        background-color: #f8f9fa !important;
        color: #2c3e50 !important;
        font-weight: bold;
        font-size: 13px;
        vertical-align: middle !important;
        border-top: 1px solid #dee2e6;
        border-bottom: 2px solid #dee2e6 !important;
    }
    .budgetSheet-table td {
        font-size: 13px;
        vertical-align: middle !important;
        color: #333;
    }
    .badge-time {
        background-color: #e9ecef;
        padding: 8px 15px;
        border-radius: 4px;
        font-weight: 600;
        color: #419BB2;
    }

    /* تنسيقات الطباعة الاحترافية */
    @media print {
        @page {
            size: A4 landscape;
            margin: 5mm;
        }
        body {
            background: white !important;
            -webkit-print-color-adjust: exact;
        }
        #print_Button, .breadcrumb-header, .main-parent > div:first-child, .main-header, .main-sidebar, .main-footer {
            display: none !important;
        }
        .invoice-container {
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .budgetSheet-table th {
            background-color: #e9ecef !important;
            color: #000 !important;
        }
    }
</style>
@endsection

@section('title')
{{ __('home.Supplier_debt_restructuring') }}
@stop

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h5 style="color: white" class="mt-1">معاينة طباعة الفاتورة</h5>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12 px-0">
        <div class="main-content-body-invoice" id="print">
            <div class="card invoice-container p-4 pt-4">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary px-4 py-2" id="print_Button" onclick="printDiv()" style="background-color: #419BB2; border: none; border-radius: 6px;">
                        <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                    </button>
                </div>

                <div class="card-body pt-2">
                    <!-- رأس الفاتورة (بيانات الشركات والشعار) -->
                    <div class="invoice-header" dir="rtl">
                        <!-- بيانات الشركة العربية (يمين) -->
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

                        <!-- بيانات الشركة الإنجليزية (يسار) -->
                        <div class="billed-from text-left" dir="ltr">
                            <h4 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">{{ Nameen ?? '' }}</h4>
                            <p class="mb-1" style="font-size: 13px; color: #64748b;">{{ describtionen ?? '' }}</p>
                            <span class="d-block mb-1" style="font-size: 13px; color: #64748b;">{{ STen ?? '' }}</span>
                            <p class="mb-0" style="font-size: 13px; color: #64748b;">{{ Taxen ?? '' }}</p>
                        </div>
                    </div><!-- invoice-header -->

                    @php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                    @endphp

                    <!-- عنوان التقرير -->
                    <div class="text-center my-4">
                        <h4 style="color: #2c3e50; font-weight: bold; border-bottom: 2px solid #419BB2; display: inline-block; padding-bottom: 5px;">
                            {{ __('home.Supplier_debt_restructuring') }}
                        </h4>
                    </div>

                    <!-- وقت الاستخراج -->
                    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                        <div class="badge-time">
                            <span style="color: #555;">{{ __('home.exportTime') }}:</span> 
                            <span style="color: #2c3e50;">{{ $currentdata }}</span>
                        </div>
                    </div>

                    <!-- الجدول الرئيسي -->
                    <div class="table-responsive">
                        <table class="table text-center table-bordered budgetSheet-table" style="border-collapse: collapse !important; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">اسم المورد <br><small class="text-muted">Supplier Name</small></th>
                                    <th class="border-bottom-0">اخر سداد <br><small class="text-muted">Last payment</small></th>
                                    <th class="border-bottom-0">الرصيد <br><small class="text-muted">Balance</small></th>
                                    <th class="border-bottom-0">عمر الدين <br><small class="text-muted">Credit Life</small></th>
                                    <th class="border-bottom-0">0 : 10</th>
                                    <th class="border-bottom-0">11 : 30</th>
                                    <th class="border-bottom-0">31 : 60</th>
                                    <th class="border-bottom-0">61 : 90</th>
                                    <th class="border-bottom-0">91 : 120</th>
                                    <th class="border-bottom-0">121 : 180</th>
                                    <th class="border-bottom-0">اكبر من 180 <br><small class="text-muted">> 180</small></th>
                                    <th class="border-bottom-0" style="background-color: #e2e8f0 !important;">الاجمالي <br><small class="text-muted">Total</small></th>
                                </tr>
                            </thead>
                            <tbody>
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
                                @forelse ($data as $item)
                                    @php 
                                        $i++; 
                                        $rowTotal = (float)($item['f_0_t_10'] ?? 0) + 
                                                    (float)($item['f_11_t_30'] ?? 0) + 
                                                    (float)($item['f_31_t_60'] ?? 0) + 
                                                    (float)($item['f_61_t_90'] ?? 0) + 
                                                    (float)($item['f_91_t_120'] ?? 0) + 
                                                    (float)($item['f_121_t_180'] ?? 0) + 
                                                    (float)($item['f_181_t_00'] ?? 0);

                                        $sum_0_10 += (float)($item['f_0_t_10'] ?? 0);
                                        $sum_11_30 += (float)($item['f_11_t_30'] ?? 0);
                                        $sum_31_60 += (float)($item['f_31_t_60'] ?? 0);
                                        $sum_61_90 += (float)($item['f_61_t_90'] ?? 0);
                                        $sum_91_120 += (float)($item['f_91_t_120'] ?? 0);
                                        $sum_121_180 += (float)($item['f_121_t_180'] ?? 0);
                                        $sum_gt_180 += (float)($item['f_181_t_00'] ?? 0);
                                        $sum_total += $rowTotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td style="font-weight: 600; text-align: right; padding-right: 10px;">{{ $item['name'] ?? '' }}</td>
                                        <td>{{ $item['lastdate'] ?? '-' }}</td>
                                        <td>{{ number_format((float)($item['crrunt_balence'] ?? 0), 2) }}</td>
                                        <td>{{ $item['life_creadit'] ?? '-' }}</td>
                                        <td>{{ number_format((float)($item['f_0_t_10'] ?? 0), 2) }}</td>
                                        <td>{{ number_format((float)($item['f_11_t_30'] ?? 0), 2) }}</td>
                                        <td>{{ number_format((float)($item['f_31_t_60'] ?? 0), 2) }}</td>
                                        <td>{{ number_format((float)($item['f_61_t_90'] ?? 0), 2) }}</td>
                                        <td>{{ number_format((float)($item['f_91_t_120'] ?? 0), 2) }}</td>
                                        <td>{{ number_format((float)($item['f_121_t_180'] ?? 0), 2) }}</td>
                                        <td>{{ number_format((float)($item['f_181_t_00'] ?? 0), 2) }}</td>
                                        <td style="font-weight: bold; background-color: #f8f9fa; color: #059669;">{{ number_format($rowTotal, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center py-4 text-muted">لا توجد بيانات متاحة لعرضها</td>
                                    </tr>
                                @endforelse

                                @if(isset($data) && count($data) > 0)
                                    <!-- صف إجمالي الأعمدة -->
                                    <tr style="background-color: #f1f5f9; border-top: 2px solid #cbd5e1; font-weight: bold;">
                                        <td colspan="5" class="text-right text-dark" style="padding-right: 10px;">{{ __('home.total') }} :</td>
                                        <td>{{ number_format($sum_0_10, 2) }}</td>
                                        <td>{{ number_format($sum_11_30, 2) }}</td>
                                        <td>{{ number_format($sum_31_60, 2) }}</td>
                                        <td>{{ number_format($sum_61_90, 2) }}</td>
                                        <td>{{ number_format($sum_91_120, 2) }}</td>
                                        <td>{{ number_format($sum_121_180, 2) }}</td>
                                        <td>{{ number_format($sum_gt_180, 2) }}</td>
                                        <td style="color: #059669; font-size: 14px;">{{ number_format($sum_total, 2) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
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