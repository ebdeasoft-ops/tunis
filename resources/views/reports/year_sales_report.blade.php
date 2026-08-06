@extends('layouts.master')

@section('css')
<!-- Internal DataTables and Chart.js Custom Styles -->
<link href="{{ URL::asset('assets/plugins/chart.js/Chart.min.css') }}" rel="stylesheet">
<style>
    @media print {
        #print_Button, .sidebar, .main-header, .breadcrumb-header {
            display: none !important;
        }
        body {
            background-color: #fff !important;
            font-size: 12pt;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    body {
        background-color: #f4f6f9 !important;
        font-family: 'Cairo', 'Times New Roman', Times, serif;
    }

    .report-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        background: #ffffff;
    }

    .table-custom th {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 600;
        text-align: center;
        padding: 12px !important;
        font-size: 13px;
        border: 1px solid #334155 !important;
    }

    .table-custom td {
        vertical-align: middle !important;
        text-align: center;
        padding: 10px !important;
        font-size: 13px;
        color: #334155;
        border: 1px solid #e2e8f0 !important;
    }

    .table-custom tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .table-custom tbody tr:hover {
        background-color: #f1f5f9;
    }

    .total-row {
        background-color: #0f172a !important;
        color: #ffffff !important;
        font-weight: bold;
        font-size: 14px;
    }

    .total-row td {
        color: #ffffff !important;
        border: 1px solid #1e293b !important;
    }

    .stat-card {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('title')
{{ __('home.year_sales_report') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto font-weight-bold" style="color: #0f172a;">{{ __('home.sales_reports') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.annual_comprehensive_report') }}</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<?php
    $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
    $totalcount = 0;
    $totalamount = 0;
    
    // جلب أسماء الشهور والأرباع المترجمة مباشرة من ملف الترجمة
    $localizedMonths = __('home.months');
    $localizedQuarters = __('home.quarters');

    $chartDataAmounts = [];
    $chartDataCounts = [];

    for ($i = 0; $i < 12; $i++) {
        $monthlyTotal = round(($data[1][$i]['total_cash'] ?? 0) + ($data[1][$i]['total_bank'] ?? 0) + ($data[1][$i]['total_credit'] ?? 0) + ($data[1][$i]['total_transfer'] ?? 0) + ($data[3][$i]['total_cash'] ?? 0) + ($data[3][$i]['total_bank'] ?? 0) + ($data[3][$i]['total_credit'] ?? 0) + ($data[3][$i]['total_transfer'] ?? 0), 2);
        $monthlyCount = ($data[0][$i] ?? 0) + ($data[2][$i] ?? 0);
        
        $chartDataAmounts[] = $monthlyTotal;
        $chartDataCounts[] = $monthlyCount;
    }
?>

<div class="row row-sm" id="print">
    <div class="col-md-12 col-xl-12">
        <div class="card report-card p-4">
            
            <!-- رأس التقرير -->
            <div class="invoice-header d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
                <div class="billed-from text-right" style="width:33%;">
                    <h3 class="font-weight-bold text-dark mb-1" style="font-size:22px;">{{Namear ?? 'اسم الشركة'}}</h3>
                    <p class="text-muted mb-1" style="font-size: 13px;">{{describtionar ?? ''}}</p>
                    <p class="text-muted mb-1" style="font-size: 12px;">{{STar ?? ''}}</p>
                    <p class="text-muted mb-0" style="font-size: 12px;">{{Taxar ?? ''}}</p>
                </div>
                
                <div class="text-center" style="width:33%;">
                    <?php $logo = camplogo ?? 'default.png'; ?>
                    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1 mb-2" alt="logo" style="max-height: 70px;"></a>
                    <h4 class="font-weight-bold text-success mb-0" style="font-size: 18px;">{{ __('home.year_sales_report') }}</h4>
                    <span class="text-muted" style="font-size: 12px;">Sales Report {{ date('Y') }}</span>
                </div>

                <div class="billed-from text-left" style="width:33%; direction: ltr;">
                    <h3 class="font-weight-bold text-dark mb-1" style="font-size:22px;">{{Nameen ?? 'Company Name'}}</h3>
                    <p class="text-muted mb-1" style="font-size: 13px;">{{describtionen ?? ''}}</p>
                    <p class="text-muted mb-1" style="font-size: 12px;">{{STen ?? ''}}</p>
                    <p class="text-muted mb-0" style="font-size: 12px;">{{Taxen ?? ''}}</p>
                </div>
            </div>

            <!-- بطاقات الملخص السريع -->
            <div class="row mb-4">
                <div class="col-xl-4 col-lg-4 col-md-4 mb-3">
                    <div class="stat-card p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 12px;">{{ __('home.total_annual_sales') }}</h6>
                            <h4 class="mb-0 text-success font-weight-bold" style="font-size: 20px;">
                                {{ number_format(array_sum($chartDataAmounts), 2) }} <small style="font-size: 11px;">SAR</small>
                            </h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(4, 120, 87, 0.1);">
                            <i class="fas fa-chart-line text-success" style="font-size: 20px;"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 mb-3">
                    <div class="stat-card p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 12px;">{{ __('home.total_invoices_count') }}</h6>
                            <h4 class="mb-0 text-dark font-weight-bold" style="font-size: 20px;">
                                {{ array_sum($chartDataCounts) }} <small style="font-size: 11px;" class="text-muted">{{ __('home.invoices') }}</small>
                            </h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(14, 116, 144, 0.1);">
                            <i class="fas fa-file-invoice text-info" style="font-size: 20px;"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 mb-3">
                    <div class="stat-card p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 12px;">{{ __('home.average_monthly_sales') }}</h6>
                            <h4 class="mb-0 text-primary font-weight-bold" style="font-size: 20px;">
                                {{ number_format(array_sum($chartDataAmounts) / 12, 2) }} <small style="font-size: 11px;">SAR</small>
                            </h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(37, 99, 235, 0.1);">
                            <i class="fas fa-wallet text-primary" style="font-size: 20px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم الرسوم البيانية -->
            <div class="row mb-5" id="print_Button_hide">
                <div class="col-xl-8 col-lg-12 mb-4">
                    <div class="card border p-3" style="border-radius: 10px; background: #fff;">
                        <h6 class="font-weight-bold mb-3 text-dark"><i class="fas fa-chart-bar text-success ml-2"></i> {{ __('home.monthly_sales_chart') }} (SAR)</h6>
                        <div style="height: 280px;">
                            <canvas id="salesBarChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 mb-4">
                    <div class="card border p-3" style="border-radius: 10px; background: #fff;">
                        <h6 class="font-weight-bold mb-3 text-dark"><i class="fas fa-chart-pie text-info ml-2"></i> {{ __('home.performance_distribution') }}</h6>
                        <div style="height: 280px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="salesDoughnutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات وتاريخ إصدار التقرير -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered text-center" style="background: #f8fafc; border-radius: 8px;">
                    <tr>
                        <td style="background-color: #e2e8f0; font-weight: bold; color: #1e293b;">{{ __('report.fromdate') }}</td>
                        <td>{{ date('Y') . '-01-01' }}</td>
                        <td style="background-color: #e2e8f0; font-weight: bold; color: #1e293b;">{{ __('report.todate') }}</td>
                        <td>{{ date('Y') . '-12-31' }}</td>
                        <td style="background-color: #e2e8f0; font-weight: bold; color: #1e293b;">{{ __('home.exportTime') }}</td>
                        <td>{{ $currentdata }}</td>
                    </tr>
                </table>
            </div>

            <!-- جدول البيانات الرئيسي -->
            <div class="table-responsive">
                <table class="table table-custom mb-4">
                    <thead>
                        <tr>
                            <th style="width: 60px;">NO</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'الشهر / MONTH' : 'MONTH / الشهر' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'عدد الفواتير / Number of invoices' : 'Number of invoices / عدد الفواتير' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'الإجمالي / TOTAL (SAR)' : 'TOTAL / الإجمالي (SAR)' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 12; $i++)
                            @php
                                $cnt = ($data[0][$i] ?? 0) + ($data[2][$i] ?? 0);
                                $amt = round(($data[1][$i]['total_cash'] ?? 0) + ($data[1][$i]['total_bank'] ?? 0) + ($data[1][$i]['total_credit'] ?? 0) + ($data[1][$i]['total_transfer'] ?? 0) + ($data[3][$i]['total_cash'] ?? 0) + ($data[3][$i]['total_bank'] ?? 0) + ($data[3][$i]['total_credit'] ?? 0) + ($data[3][$i]['total_transfer'] ?? 0), 2);
                                
                                $totalamount += $amt;
                                $totalcount += $cnt;
                            @endphp
                            <tr>
                                <td class="font-weight-bold text-muted">{{ $i + 1 }}</td>
                                <td class="font-weight-bold text-dark">{{ $localizedMonths[$i] }}</td>
                                <td><span class="badge badge-light border px-2 py-1">{{ $cnt }}</span></td>
                                <td class="font-weight-bold text-success">{{ number_format($amt, 2) }}</td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="2" class="text-center">الإجمالي العام / TOTAL</td>
                            <td>{{ $totalcount }}</td>
                            <td>{{ number_format($totalamount, 2) }} SAR</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- التوقيعات والاعتماد -->
            <div class="row mt-4 pt-3 border-top">
                <div class="col-md-6">
                    <p class="font-weight-bold text-dark">{{ __('home.employeereciver') }} : <span class="text-muted font-weight-normal">{{ Auth()->user()->name }}</span></p>
                </div>
                <div class="col-md-6 text-md-left">
                    <p class="font-weight-bold text-dark">{{ __('home.thesignature') }} : ______________________</p>
                </div>
            </div>

            <!-- زر الطباعة -->
            <div class="mt-4 text-left">
                <button class="btn btn-danger px-4 py-2 font-weight-bold shadow-sm" id="print_Button" onclick="printDiv()">
                    <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Internal Chart.js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>

<script>
    function printDiv() {
        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut(500);
        }, 4000);

        // الرسم البياني للأعمدة مع الشهور المترجمة
        var ctxBar = document.getElementById('salesBarChart').getContext('2d');
        var salesBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($localizedMonths) !!},
                datasets: [{
                    label: 'إجمالي المبيعات (SAR)',
                    data: {!! json_encode($chartDataAmounts) !!},
                    backgroundColor: 'rgba(4, 120, 87, 0.85)',
                    borderColor: 'rgba(4, 120, 87, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // الرسم البياني الدائري مع الأرباع المترجمة
        var ctxDoughnut = document.getElementById('salesDoughnutChart').getContext('2d');
        
        var q1 = {!! array_sum(array_slice($chartDataAmounts, 0, 3)) !!};
        var q2 = {!! array_sum(array_slice($chartDataAmounts, 3, 3)) !!};
        var q3 = {!! array_sum(array_slice($chartDataAmounts, 6, 3)) !!};
        var q4 = {!! array_sum(array_slice($chartDataAmounts, 9, 3)) !!};

        var salesDoughnutChart = new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($localizedQuarters) !!},
                datasets: [{
                    data: [q1, q2, q3, q4],
                    backgroundColor: ['#047857', '#0ea5e9', '#f59e0b', '#64748b'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection