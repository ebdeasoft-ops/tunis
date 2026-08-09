@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button, .breadcrumb-header, .main-header, .main-sidebar, .main-footer {
                display: none !important;
            }
            body {
                background: #fff !important;
                font-size: 11pt;
                color: #000;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .table th, .table td {
                border: 1px solid #ddd !important;
                padding: 5px !important;
                font-size: 9pt;
            }
        }

        .invoice-title {
            font-weight: 700;
            color: #333;
        }
        
        .company-logo {
            max-width: 150px;
            height: auto;
            object-fit: contain;
        }

        .table-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@section('title')
    {{ __('hr.salarydecoument') }}
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('hr.salarydecoument') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 ml-2">/ {{ $month ?? '' }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice table-card">
                    <div class="card-body">
                        
                        <!-- Header Section -->
                        <div class="invoice-header d-flex justify-content-between align-items-center flex-wrap pb-4 border-bottom">
                            <div class="billed-from text-left" style="width: 30%;">
                                <h4 class="invoice-title font-weight-bold">{{ $Nameen ?? 'Company Name' }}</h4>
                                <p class="mb-1 text-muted" dir="ltr">{{ $describtionen ?? '' }}</p>
                                <span class="d-block text-muted" dir="ltr">{{ $STen ?? '' }}</span>
                                <span class="d-block text-muted" dir="ltr">{{ $Taxen ?? '' }}</span>
                            </div>

                            <div class="text-center my-2" style="width: 30%;">
                                @php
                                    $logo = $camplogo ?? 'default-logo.png';
                                @endphp
                                <a href="https://ebdeasoft.com/" target="_blank">
                                    <img src="{{ asset('assets/img/brand/' . $logo) }}" class="company-logo" alt="Logo">
                                </a>
                            </div>

                            <div class="billed-from text-right" style="width: 30%;">
                                <h4 class="invoice-title font-weight-bold">{{ $Namear ?? 'اسم الشركة' }}</h4>
                                <p class="mb-1 text-muted">{{ $describtionar ?? '' }}</p>
                                <span class="d-block text-muted">{{ $STar ?? '' }}</span>
                                <span class="d-block text-muted">{{ $Taxar ?? '' }}</span>
                            </div>
                        </div>

                        <!-- Report Title & Month -->
                        <div class="text-center my-4">
                            <h2 class="font-weight-bold text-primary">{{ __('hr.salarydecoument') }}</h2>
                            <h4 class="badge badge-light px-3 py-2 tx-16 border text-secondary">{{ $month ?? '' }}</h4>
                        </div>

                        <!-- Table Section -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered text-center align-middle" id="example1" data-page-length="50">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('hr.name') }}</th>
                                        <th>{{ __('hr.Id') }}</th>
                                        <th>{{ __('hr.department') }}</th>
                                        <th>{{ __('hr.salary') }}</th>
                                        <th>{{ __('حضور / غياب') }}</th>
                                        <th>{{ __('إجازة بإذن') }}</th>
                                        <th>{{ __('إجازة بدون إذن') }}</th>
                                        <th>{{ __('غياب بدون إذن (خصم يومين)') }}</th>
                                        <th>{{ __('إضافي') }}</th>
                                        <th>{{ __('hr.increastotal') }}</th>
                                        <th>{{ __('hr.decreasetotal') }}</th>
                                        <th>{{ __('home.amountLoans') }}</th>
                                        <th class="bg-primary text-white">{{ __('صافي الراتب') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach ($list_salary_data as $data)
                                        <?php 
                                            $i++;
                                            $emp = $data['employeeData'];

                                            // حساب قيمة اليوم الواحد بناءً على الراتب الأساسي (على فرض الشهر 30 يوم)
                                            $dailyRate = ($emp->salary > 0) ? ($emp->salary / 30) : 0;

                                            // غياب بدون إذن يخصم عنه يومين لكل يوم غياب
                                            $unauthorizedAbsentDays = $data['unauthorized_absent_days'] ?? 0;
                                            $unauthorizedAbsentPenalty = $unauthorizedAbsentDays * 2 * $dailyRate;

                                            // إجمالي الخصومات شاملة الخصومات العادية + عقوبة الغياب بدون إذن (يومين عن كل يوم) + القروض
                                            $totalDiscounts = $data['discount'] + $unauthorizedAbsentPenalty;

                                            // حساب صافي الراتب النهائي
                                            $netSalary = ($emp->salary + $data['bounes'] + $data['overtime_amount']) - ($totalDiscounts + $data['Loans']);
                                        ?>
                                        <tr>
                                            <td class="font-weight-bold">{{ $i }}</td>
                                            <td>
                                                {{ (app()->getLocale() == 'ar' || __('hr.phone') == 'رقم الجوال') ? $emp->name_ar : $emp->name_en }}
                                            </td>
                                            <td>{{ $emp->personal_identification }}</td>
                                            <td>
                                                @if($emp->departments)
                                                    {{ (app()->getLocale() == 'ar' || __('hr.phone') == 'رقم الجوال') ? $emp->departments->name_ar : $emp->departments->name_en }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ number_format($emp->salary, 2) }}</td>
                                            <td>
                                                <span class="text-success" title="حضور">ح: {{ $data['present_days'] ?? 0 }}</span> / 
                                                <span class="text-danger" title="غياب">غ: {{ $data['absent_days'] ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $data['permission_leave_days'] ?? 0 }} يوم</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">{{ $data['without_permission_leave_days'] ?? 0 }} يوم</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">{{ $unauthorizedAbsentDays }} يوم</span>
                                                @if($unauthorizedAbsentDays > 0)
                                                    <br><small class="text-danger">(-{{ number_format($unauthorizedAbsentPenalty, 2) }})</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $data['overtime_hours'] ?? 0 }} س 
                                                <small class="text-muted">({{ number_format($data['overtime_amount'] ?? 0, 2) }})</small>
                                            </td>
                                            <td class="text-success font-weight-bold">+{{ number_format($data['bounes'] ?? 0, 2) }}</td>
                                            <td class="text-danger font-weight-bold">-{{ number_format($totalDiscounts, 2) }}</td>
                                            <td class="text-warning font-weight-bold">-{{ number_format($data['Loans'] ?? 0, 2) }}</td>
                                            <td class="font-weight-bold text-primary bg-light" style="font-size: 1.1rem;">
                                                {{ number_format($netSalary, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Print Button Section -->
                        <div class="mt-4 text-left">
                            <button class="btn btn-danger print-style px-4 py-2" id="print_Button" onclick="printDiv()">
                                <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
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

    <script>
        $(document).ready(function() {
            var timeout = 4000;
            $('.alert').delay(timeout).fadeOut(500);
        });
    </script>
@endsection