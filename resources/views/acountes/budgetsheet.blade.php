@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <style>
        /* تنسيقات خاصة بالطباعة لإخفاء كل ما هو خارج التقرير */
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-section, #printable-section * {
                visibility: visible;
            }
            #printable-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            /* إظهار التفاصيل عند الطباعة تلقائياً */
            .collapse:not(.show) {
                display: block !important;
            }
        }
    </style>
@endsection

@section('title')
    {{ __('home.general_budget') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading no-print">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.general_budget') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger no-print">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($assets))
    <!-- row for results & tables -->
    <div class="row" id="printable-section">
        <div class="col-xl-12">
            <div class="card">
                
                <!-- رأس الفاتورة والشركة -->
                <div class="card-header border-bottom-0 pb-0">
                    <div class="invoice-header d-flex justify-content-between align-items-center w-100">
                        <!-- بيانات الشركة عربي (اليمين) -->
                        <div class="company-info text-right" style="width: 33%;">
                            <h4 style="font-size: 16px; font-weight: bold;">{{ Namear ?? '' }}</h4>
                            <p style="margin-bottom: 3px; font-size: 13px;">{{ describtionar ?? '' }}</p>
                            <p style="margin-bottom: 3px; font-size: 13px;">{{ STar ?? '' }}</p>
                            <p style="margin-bottom: 3px; font-size: 13px;">{{ Taxar ?? '' }}</p>
                        </div>

                        <!-- الشعار والعنوان بالمنتصف -->
                        <div class="text-center" style="width: 33%;">
                            @php
                                $logo = camplogo ?? '';
                            @endphp
                            @if($logo)
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="max-height: 60px; object-fit: contain;">
                            </a>
                            @endif
                            <div class="mt-2">
                                <span class="document-title font-weight-bold" style="font-size: 15px;">
                                    الميزانية العامة<br>
                                    <span style="font-size: 11px; color: #666;">General Budget</span>
                                </span>
                            </div>
                        </div>

                        <!-- بيانات الشركة إنجليزي (اليسار) -->
                        <div class="company-info text-left" style="width: 33%;">
                            <h4 style="font-size: 16px; font-weight: bold;">{{ Nameen ?? '' }}</h4>
                            <p dir="ltr" style="margin-bottom: 3px; font-size: 13px;">{{ describtionen ?? '' }}</p>
                            <span dir="ltr" style="display: block; margin-bottom: 3px; font-size: 13px;">{{ STen ?? '' }}</span>
                            <p dir="ltr" style="margin-bottom: 3px; font-size: 13px;">{{ Taxen ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-header pb-0 pt-3 d-flex justify-content-between align-items-center no-print border-top">
                    <h5 class="card-title mb-0 text-primary font-weight-bold">تقرير الميزانية العامة</h5>
                    <button onclick="window.print()" class="btn btn-sm btn-secondary">
                        <i class="fas fa-print"></i> طباعة التقرير
                    </button>
                </div>

                <div class="card-body">
                    <div class="row">
                        
                        <!-- حساب الأصول (Assets) -->
                        <div class="col-md-6">
                            @php 
                                $totalAssets = 0;
                                foreach($assets as $acc) { $totalAssets += ($acc->current_balance ?? 0); }
                            @endphp
                            <div class="card border mb-3">
                                <div class="card-body d-flex justify-content-between align-items-center bg-light">
                                    <div>
                                        <h5 class="text-success font-weight-bold mb-1">الأصول</h5>
                                        <span class="text-muted tx-13">الإجمالي: <strong>{{ number_format($totalAssets, 2) }}</strong></span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-success no-print" type="button" data-toggle="collapse" data-target="#assetsDetails" aria-expanded="false">
                                        <i class="fas fa-list"></i> التفاصيل
                                    </button>
                                </div>
                                <div class="collapse" id="assetsDetails">
                                    <div class="table-responsive p-3 border-top">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>اسم الحساب</th>
                                                    <th>الرصيد</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($assets as $account)
                                                    <tr>
                                                        <td>{{ $account->name }}</td>
                                                        <td>{{ number_format($account->current_balance ?? 0, 2) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center">لا توجد أصول مضافة</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الخصوم وحقوق الملكية (Liabilities & Equity) -->
                        <div class="col-md-6">
                            
                            <!-- الخصوم -->
                            @php 
                                $totalLiabilities = 0;
                                foreach($liabilities as $acc) { $totalLiabilities += ($acc->current_balance ?? 0); }
                            @endphp
                            <div class="card border mb-3">
                                <div class="card-body d-flex justify-content-between align-items-center bg-light">
                                    <div>
                                        <h5 class="text-danger font-weight-bold mb-1">الخصوم</h5>
                                        <span class="text-muted tx-13">الإجمالي: <strong>{{ number_format($totalLiabilities, 2) }}</strong></span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger no-print" type="button" data-toggle="collapse" data-target="#liabilitiesDetails" aria-expanded="false">
                                        <i class="fas fa-list"></i> التفاصيل
                                    </button>
                                </div>
                                <div class="collapse" id="liabilitiesDetails">
                                    <div class="table-responsive p-3 border-top">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>اسم الحساب</th>
                                                    <th>الرصيد</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($liabilities as $account)
                                                    <tr>
                                                        <td>{{ $account->name }}</td>
                                                        <td>{{ number_format($account->current_balance ?? 0, 2) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center">لا توجد خصوم مضافة</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- حقوق الملكية -->
                            @php 
                                $totalEquity = 0;
                                foreach($equity as $acc) { $totalEquity += ($acc->current_balance ?? 0); }
                            @endphp
                            <div class="card border mb-3">
                                <div class="card-body d-flex justify-content-between align-items-center bg-light">
                                    <div>
                                        <h5 class="text-warning font-weight-bold mb-1">حقوق الملكية</h5>
                                        <span class="text-muted tx-13">الإجمالي: <strong>{{ number_format($totalEquity, 2) }}</strong></span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-warning no-print" type="button" data-toggle="collapse" data-target="#equityDetails" aria-expanded="false">
                                        <i class="fas fa-list"></i> التفاصيل
                                    </button>
                                </div>
                                <div class="collapse" id="equityDetails">
                                    <div class="table-responsive p-3 border-top">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>اسم الحساب</th>
                                                    <th>الرصيد</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($equity as $account)
                                                    <tr>
                                                        <td>{{ $account->name }}</td>
                                                        <td>{{ number_format($account->current_balance ?? 0, 2) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center">لا توجد حسابات حقوق ملكية مضافة</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- إجمالي الخصوم وحقوق الملكية -->
                            <div class="card bg-dark text-white text-center p-3">
                                <h6 class="mb-0 font-weight-bold">إجمالي الخصوم وحقوق الملكية: {{ number_format($totalLiabilities + $totalEquity, 2) }}</h6>
                            </div>

                        </div>
                    </div>

                    <!-- قسم الرسم البياني الدائري (لا يظهر عند الطباعة بفضل no-print) -->
                    <div class="row no-print mt-5">
                        <div class="col-xl-6 mx-auto">
                            <div class="card border">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0 text-center text-primary font-weight-bold">توزيع الميزانية العامة (رسم بياني)</h5>
                                </div>
                                <div class="card-body">
                                    <div style="height: 280px; position: relative;">
                                        <canvas id="budgetPieChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    @endif

@endsection

@section('js')
    <!-- Internal Data tables js -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>

    <!--Internal pickerjs js -->
    <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
    
    <!-- تضمين مكتبة Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // التأكد من وجود العنصر قبل تطبيق الـ datepicker لمنع الأخطاء
        if ($.fn.datepicker) {
            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        }

        // كود الرسم البياني الدائري
        @if(isset($assets))
        document.addEventListener("DOMContentLoaded", function() {
            var canvasElement = document.getElementById('budgetPieChart');
            if (canvasElement) {
                var ctx = canvasElement.getContext('2d');
                
                var totalAssets = {{ $totalAssets ?? 0 }};
                var totalLiabilities = {{ $totalLiabilities ?? 0 }};
                var totalEquity = {{ $totalEquity ?? 0 }};

                var budgetChart = new Chart(ctx, {
                    type: 'doughnut', 
                    data: {
                        labels: ['الأصول', 'الخصوم', 'حقوق الملكية'],
                        datasets: [{
                            data: [totalAssets, totalLiabilities, totalEquity],
                            backgroundColor: [
                                '#28a745', // أخضر
                                '#dc3545', // أحمر
                                '#ffc107'  // أصفر
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }
        });
        @endif
    </script>
@endsection