@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

<style>
    :root {
        --primary-color: #004d44;
        --secondary-color: #00695c;
    }

    /* تنسيق البحث والجدول */
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e2e8f0 !important;
        border-radius: 8px;
        padding: 6px 15px !important;
        margin-bottom: 15px;
        width: 280px !important;
        outline: none;
        transition: all 0.3s ease;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(0,77,68,0.15);
    }
    
    .invoice-table {
        width: 100% !important;
    }
    .invoice-table thead th {
        background-color: #f1f5f9;
        color: #1e293b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 12px !important;
        border-bottom: 2px solid #cbd5e1 !important;
        vertical-align: middle !important;
    }

    .invoice-table tbody td {
        vertical-align: middle !important;
        padding: 12px !important;
        color: #334155;
        font-size: 13px;
    }

    .total-row {
        background-color: #f8fafc !important;
        font-weight: bold;
        font-size: 14px;
        border-top: 2px solid #cbd5e1 !important;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08)!important;
    }

    .dt-buttons .btn {
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        padding: 6px 14px;
        margin-bottom: 15px;
        margin-left: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* تنظيم شريط التنقل العلوي السريع لعدم التداخل */
    .quick-menu-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
    }
</style>
@endsection

@section('title')
{{__('home.customer_supplier_account')}}
@stop
@section('page-header')
<br>

<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: #ffffff;">
        <div class="card-body py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                
                <!-- القسم الأيمن: الأيقونة وعنوان الصفحة -->
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center ml-3 shadow-sm" style="width: 48px; height: 48px; background: rgba(0,77,68,0.08); min-width: 48px;">
                        <i class="fas fa-chart-line text-success" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 font-weight-bold" style="color: #1e293b; font-size: 17px;">
                            {{__('home.customer_supplier_account')}}
                        </h4>
                        <span class="text-muted d-block" style="font-size: 12px;">
                            كشف الحسابات والحركات المالية الشاملة للعملاء والموردين
                        </span>
                    </div>
                </div>

                <!-- القسم الأيسر: شارة الفترة الزمنية -->
                <div class="d-flex align-items-center">
                    <div class="badge border px-3 py-2 text-dark shadow-sm d-flex align-items-center" style="border-radius: 8px; font-size: 12px; background: #f8fafc; border-color: #e2e8f0 !important;">
                        <i class="fas fa-calendar-alt ml-2 text-primary" style="font-size: 14px;"></i>
                        <span>الفترة: <strong class="text-success">{{ date('Y') }}-01-01</strong> إلى <strong class="text-success">{{ date('Y-m-d') }}</strong></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
    <i class="fas fa-check-circle ml-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@php 
    $totalAllCredit = 0; 
    $totalAllDebit = 0; 
    $activeCustomersCount = 0;

    foreach($customers as $customer) {
        $c = $customer->total_credit ?? 0;
        $d = $customer->total_debit ?? 0;
        if(($d - $c) != 0) {
            $totalAllCredit += $c;
            $totalAllDebit += $d;
            $activeCustomersCount++;
        }
    }
    $netBalance = $totalAllDebit - $totalAllCredit;
@endphp
<br>
<!-- بطاقات الإحصائيات -->
<div class="row row-sm">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stat-card bg-white shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 12px;">إجمالي المدين</h6>
                        <h4 class="mb-0 text-success font-weight-bold" style="font-size: 18px;">{{ number_format($totalAllDebit, 2) }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(40, 167, 69, 0.1);">
                        <i class="fas fa-arrow-down text-success" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stat-card bg-white shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 12px;">إجمالي الدائن</h6>
                        <h4 class="mb-0 text-danger font-weight-bold" style="font-size: 18px;">{{ number_format($totalAllCredit, 2) }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(220, 53, 69, 0.1);">
                        <i class="fas fa-arrow-up text-danger" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stat-card bg-white shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 12px;">صافي الأرصدة</h6>
                        <h4 class="mb-0 font-weight-bold {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 18px;">
                            {{ number_format(abs($netBalance), 2) }} <small style="font-size: 11px;">{{ $netBalance >= 0 ? '(مدين)' : '(دائن)' }}</small>
                        </h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(0, 123, 255, 0.1);">
                        <i class="fas fa-wallet text-primary" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stat-card bg-white shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 12px;">العملاء ذوو الحركات</h6>
                        <h4 class="mb-0 text-dark font-weight-bold" style="font-size: 18px;">{{ $activeCustomersCount }} <small style="font-size: 11px;" class="text-muted">عميل</small></h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(108, 117, 125, 0.1);">
                        <i class="fas fa-users text-secondary" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- جدول البيانات -->
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white pb-3 pt-4 border-0">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mg-b-0 font-weight-bold" style="font-size: 16px; color: #1e293b;">
                        <i class="fas fa-list-alt ml-2 text-primary"></i> قائمة تفاصيل أرصدة العملاء
                    </h4>
                </div>
            </div>
            <div class="card-body px-4">
                <div class="table-responsive">
                    <table id="customerTable" class="table text-md-nowrap mb-0 table-hover invoice-table text-center" style="border: 1px solid #f1f5f9;">
                        <thead>
                            <tr>
                                <th class="border-bottom-0" style="width: 50px;">#</th>
                                <th class="border-bottom-0 text-right">{{__('home.clietName')}}</th>
                                <th class="border-bottom-0">{{__('home.tax_number')}}</th>
                                <th class="border-bottom-0 text-success">{{__('home.debit')}} (مدين)</th>
                                <th class="border-bottom-0 text-danger">{{__('home.credit')}} (دائن)</th>
                                <th class="border-bottom-0">{{__('home.current balance')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($customers as $customer)
                                @php
                                    $credit = $customer->total_credit ?? 0;
                                    $debit = $customer->total_debit ?? 0;
                                    $balance = $debit - $credit;
                                @endphp

                                @if($debit - $credit != 0)
                                    <tr>
                                        <td class="text-muted font-weight-bold">{{ $i++ }}</td>
                                        <td class="font-weight-bold text-right text-dark">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle text-primary d-flex align-items-center justify-content-center ml-2" style="width: 30px; height: 30px; font-size: 12px; font-weight: bold; min-width: 30px;">
                                                    {{ mb_substr($customer->name, 0, 1) }}
                                                </div>
                                                <span>{{ $customer->name }}</span>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-light border text-muted px-2 py-1">{{ $customer->tax_no ?? '-' }}</span></td>
                                        <td class="text-success font-weight-bold">{{ number_format($debit, 2) }}</td>
                                        <td class="text-danger font-weight-bold">{{ number_format($credit, 2) }}</td>
                                        <td>
                                            @if($balance > 0)
                                                <span class="badge badge-success px-2 py-1" style="font-size: 11px;">
                                                    <i class="fas fa-plus-circle ml-1"></i> {{__('home.debit')}} ({{ number_format($balance, 2) }})
                                                </span>
                                            @elseif($balance < 0)
                                                <span class="badge badge-danger px-2 py-1" style="font-size: 11px;">
                                                    <i class="fas fa-minus-circle ml-1"></i> {{__('home.credit')}} ({{ number_format(abs($balance), 2) }})
                                                </span>
                                            @else
                                                <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 11px;">{{__('home.Balanced')}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" class="text-center font-weight-bold text-dark">الإجمالي العام للنتائج الظاهرة</td>
                                <td class="text-success font-weight-bold">{{ number_format($totalAllDebit, 2) }}</td>
                                <td class="text-danger font-weight-bold">{{ number_format($totalAllCredit, 2) }}</td>
                                <td>
                                    @php $final = $totalAllDebit - $totalAllCredit; @endphp
                                    @if($final > 0)
                                        <span class="text-success font-weight-bold">{{ number_format($final, 2) }} (مدين)</span>
                                    @else
                                        <span class="text-danger font-weight-bold">{{ number_format(abs($final), 2) }} (دائن)</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#customerTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                searchPlaceholder: 'ابحث باسم العميل أو الرقم الضريبي...',
                sSearch: '',
                lengthMenu: '_MENU_',
                info: "عرض _START_ إلى _END_ من أصل _TOTAL_ سجل",
                infoEmpty: "لا توجد سجلات متاحة",
                infoFiltered: "(تم التصفية من _MAX_ إجمالي السجلات)",
                paginate: {
                    first: "الأول",
                    last: "الأخير",
                    next: "التالي",
                    previous: "السابق"
                }
            },
            dom: '<"row mb-3 align-items-center"<"col-md-6"B><"col-md-6 text-md-left"f>>rtip',
            buttons: [
                { 
                    extend: 'excel', 
                    className: 'btn btn-success text-white', 
                    text: '<i class="fas fa-file-excel ml-1"></i> تصدير Excel' 
                },
                { 
                    extend: 'print', 
                    className: 'btn btn-primary text-white', 
                    text: '<i class="fas fa-print ml-1"></i> طباعة الجدول' 
                }
            ],
            pageLength: 25,
            order: [[1, 'asc']]
        });

        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 4000);
    });
</script>
@endsection