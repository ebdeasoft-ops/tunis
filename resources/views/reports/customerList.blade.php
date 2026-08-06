@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.DataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

<style>
    body {
        background-color: #f4f6f9 !important;
    }

    /* تحسين حقل البحث */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px;
        padding: 6px 14px !important;
        margin-bottom: 15px;
        width: 280px !important;
        outline: none;
        background-color: #ffffff;
        font-size: 13px;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #047857 !important;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.1);
    }
    
    /* ستايل الجدول الاحترافي الواضح */
    .clean-table {
        width: 100% !important;
        background-color: #ffffff !important;
        border-collapse: collapse !important;
        border: 1px solid #e2e8f0;
    }
    
    .clean-table thead th {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 600;
        text-align: center;
        font-size: 13px;
        padding: 12px 10px !important;
        border: 1px solid #334155 !important;
    }

    .clean-table tbody tr {
        background-color: #ffffff;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .clean-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .clean-table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .clean-table tbody td {
        vertical-align: middle !important;
        padding: 12px 10px !important;
        color: #1e293b;
        font-size: 13px;
        border: 1px solid #e2e8f0 !important;
        text-align: center;
    }

    .clean-table tbody td.text-right {
        text-align: right !important;
    }

    .clean-total-row {
        background-color: #0f172a !important;
        color: #ffffff !important;
        font-weight: bold;
        font-size: 14px;
    }
    .clean-total-row td {
        color: #ffffff !important;
        padding: 14px 10px !important;
        border: 1px solid #1e293b !important;
        text-align: center;
    }

    .exec-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    .dt-buttons .btn {
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        padding: 7px 15px;
        margin-bottom: 15px;
        margin-left: 5px;
        border: none;
    }

    .type-switcher .btn {
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 20px;
    }
</style>
@endsection

@section('title')
{{ __('home.customer_supplier_account') }}
@stop

@section('page-header')
<br>
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px; background: #ffffff;">
        <div class="card-body py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center ml-3" style="width: 45px; height: 45px; background: rgba(4,120,87,0.1); min-width: 45px;">
                        <i class="fas fa-chart-pie text-success" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 font-weight-bold" style="color: #0f172a; font-size: 16px;">
                            {{ __('home.customer_supplier_account') }}
                        </h4>
                        <span class="text-muted d-block" style="font-size: 12px;">
                            {{ __('home.comprehensive_financial_statement') }}
                        </span>
                    </div>
                </div>

                @php
                    $currentType = request()->get('type', '1');
                @endphp
                <div class="type-switcher btn-group shadow-sm" role="group">
                    <a href="{{ request()->fullUrlWithQuery(['type' => '1']) }}" class="btn {{ $currentType == '1' ? 'btn-success text-white' : 'btn-light text-muted border' }}" style="{{ $currentType == '1' ? 'background-color: #047857;' : '' }}">
                        <i class="fas fa-users ml-1"></i> {{ __('home.customers') }}
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => '2']) }}" class="btn {{ $currentType == '2' ? 'btn-success text-white' : 'btn-light text-muted border' }}" style="{{ $currentType == '2' ? 'background-color: #047857;' : '' }}">
                        <i class="fas fa-truck ml-1"></i> {{ __('home.suppliers') }}
                    </a>
                </div>

                <div class="d-flex align-items-center">
                    <div class="badge border px-3 py-2 text-dark d-flex align-items-center" style="border-radius: 8px; font-size: 12px; background: #f8fafc;">
                        <i class="fas fa-calendar-alt ml-2 text-success" style="font-size: 14px;"></i>
                        <span>{{ __('home.period') }}: <strong class="text-dark">{{ date('Y') }}-01-01</strong> {{ __('home.to') }} <strong class="text-dark">{{ date('Y-m-d') }}</strong></span>
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
    $filteredCustomers = $customers->filter(function($item) use ($currentType) {
        return isset($item->orginal_type) && $item->orginal_type == $currentType;
    });

    $totalAllCredit = 0; 
    $totalAllDebit = 0; 
    $activeCount = 0;

    foreach($filteredCustomers as $customer) {
        $c = $customer->total_credit ?? 0;
        $d = $customer->total_debit ?? 0;
        if(($d - $c) != 0) {
            $totalAllCredit += $c;
            $totalAllDebit += $d;
            $activeCount++;
        }
    }
    $netBalance = $totalAllDebit - $totalAllCredit;
    
    $reportTitle = $currentType == '1' ? __('home.certified_customer_statement_report') : __('home.certified_supplier_statement_report');
    $nameColumnTitle = $currentType == '1' ? __('home.clietName') : __('home.supplier_name');
    $activeCountTitle = $currentType == '1' ? __('home.active_customers_with_transactions') : __('home.active_suppliers_with_transactions');
@endphp

<div class="row row-sm mb-3">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card exec-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 11px;">{{ __('home.total_debit') }}</h6>
                    <h4 class="mb-0 text-success font-weight-bold" style="font-size: 17px;">{{ number_format($totalAllDebit, 2) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(4, 120, 87, 0.1);">
                    <i class="fas fa-arrow-down text-success" style="font-size: 16px;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card exec-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 11px;">{{ __('home.total_credit') }}</h6>
                    <h4 class="mb-0 text-danger font-weight-bold" style="font-size: 17px;">{{ number_format($totalAllCredit, 2) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(220, 38, 38, 0.1);">
                    <i class="fas fa-arrow-up text-danger" style="font-size: 16px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card exec-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 11px;">{{ __('home.net_balances') }}</h6>
                    <h4 class="mb-0 font-weight-bold {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 17px;">
                        {{ number_format(abs($netBalance), 2) }} <small style="font-size: 10px;">{{ $netBalance >= 0 ? __('home.debit_status') : __('home.credit_status') }}</small>
                    </h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(217, 119, 6, 0.1);">
                    <i class="fas fa-wallet text-warning" style="font-size: 16px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card exec-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1 font-weight-bold" style="font-size: 11px;">{{ $activeCountTitle }}</h6>
                    <h4 class="mb-0 text-dark font-weight-bold" style="font-size: 17px;">
                        {{ $activeCount }} <small style="font-size: 10px;" class="text-muted">{{ __('home.account_unit') }}</small>
                    </h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(100, 116, 139, 0.1);">
                    <i class="fas {{ $currentType == '1' ? 'fa-users' : 'fa-truck' }} text-secondary" style="font-size: 16px;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
            <div class="card-header bg-white pb-2 pt-3 border-bottom">
                <h4 id="mainReportTitle" class="card-title mg-b-0 font-weight-bold" style="font-size: 15px; color: #0f172a;">
                    <i class="fas fa-file-invoice-dollar ml-2 text-success"></i> {{ $reportTitle }}
                </h4>
            </div>
            <div class="card-body px-3">
                <div class="table-responsive">
                    <table id="customerTable" class="table mb-0 clean-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th class="text-right">{{ $nameColumnTitle }}</th>
                                <th style="width: 140px;">{{ __('home.tax_number') }}</th>
                                <th style="width: 130px;">{{ __('home.debit') }}</th>
                                <th style="width: 130px;">{{ __('home.credit') }}</th>
                                <th style="width: 160px;">{{ __('home.current balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($filteredCustomers as $customer)
                                @php
                                    $credit = $customer->total_credit ?? 0;
                                    $debit = $customer->total_debit ?? 0;
                                    $balance = $debit - $credit;
                                @endphp

                                @if($debit - $credit != 0)
                                    <tr>
                                        <td class="text-muted font-weight-bold">{{ $i++ }}</td>
                                        <td class="font-weight-bold text-right text-dark">
                                            <span>{{ $customer->name }}</span>
                                            <small class="text-muted d-block" style="font-size: 10px;">#{{ $customer->id ?? __('home.not_available_abbr') }}</small>
                                        </td>
                                        <td><span class="badge badge-light border px-2 py-1" style="font-size: 11px;">{{ $customer->tax_no ?? __('home.not_available') }}</span></td>
                                        <td class="text-success font-weight-bold">{{ number_format($debit, 2) }}</td>
                                        <td class="text-danger font-weight-bold">{{ number_format($credit, 2) }}</td>
                                        <td>
                                            @if($balance > 0)
                                                <span class="text-success font-weight-bold" style="font-size: 12px;">
                                                    {{ __('home.debit') }} ({{ number_format($balance, 2) }})
                                                </span>
                                            @elseif($balance < 0)
                                                <span class="text-danger font-weight-bold" style="font-size: 12px;">
                                                    {{ __('home.credit') }} ({{ number_format(abs($balance), 2) }})
                                                </span>
                                            @else
                                                <span class="text-muted" style="font-size: 12px;">{{ __('home.Balanced') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="clean-total-row">
                                <td colspan="3" class="text-center">{{ __('home.general_total_visible_results') }}</td>
                                <td class="text-success">{{ number_format($totalAllDebit, 2) }}</td>
                                <td class="text-danger">{{ number_format($totalAllCredit, 2) }}</td>
                                <td>
                                    @php $final = $totalAllDebit - $totalAllCredit; @endphp
                                    @if($final > 0)
                                        <span class="text-success">{{ number_format($final, 2) }} ({{ __('home.debit_status') }})</span>
                                    @else
                                        <span class="text-danger">{{ number_format(abs($final), 2) }} ({{ __('home.credit_status') }})</span>
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
        var reportTitleName = "{{ $reportTitle }}";

        $('#customerTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                searchPlaceholder: "{{ __('home.datatable_search_placeholder') }}",
                sSearch: '',
                lengthMenu: '_MENU_',
                info: "{{ __('home.datatable_info') }}",
                infoEmpty: "{{ __('home.datatable_info_empty') }}",
                infoFiltered: "{{ __('home.datatable_info_filtered') }}",
                paginate: {
                    first: "{{ __('home.datatable_paginate_first') }}",
                    last: "{{ __('home.datatable_paginate_last') }}",
                    next: "{{ __('home.datatable_paginate_next') }}",
                    previous: "{{ __('home.datatable_paginate_previous') }}"
                }
            },
            dom: '<"row mb-3 align-items-center"<"col-md-6"B><"col-md-6 text-md-left"f>>rtip',
            buttons: [
                { 
                    extend: 'excel', 
                    className: 'btn btn-success text-white', 
                    text: '<i class="fas fa-file-excel ml-1"></i> {{ __('home.export_excel') }}',
                    title: reportTitleName 
                },
                { 
                    extend: 'print', 
                    className: 'btn btn-dark text-white', 
                    text: '<i class="fas fa-print ml-1"></i> {{ __('home.print_report') }}',
                    customize: function (win) {
                        $(win.document.body).find('.card-header, .card-title, h4, div:contains("أرصدة عملاء")').remove();

                        $(win.document.body)
                            .css('direction', 'rtl')
                            .css('font-family', 'Cairo, sans-serif')
                            .css('padding', '15px')
                            .prepend(
                                '<div style="text-align:center; margin-bottom: 20px; border-bottom: 2px solid #047857; padding-bottom: 10px;">' +
                                    '<h3 style="font-weight:bold; color:#047857; margin:0;">' + reportTitleName + '</h3>' +
                                    '<p style="color:#64748b; font-size:12px; margin: 3px 0 0 0;">{{ __("home.report_date") }}: {{ date("Y-m-d H:i") }}</p>' +
                                '</div>'
                            );
                        
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('width', '100%')
                            .css('border-collapse', 'collapse')
                            .css('font-size', '11px');

                        $(win.document.body).find('table th')
                            .css('background-color', '#1e293b')
                            .css('color', '#ffffff')
                            .css('padding', '8px');
                    }
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