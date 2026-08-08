@extends('layouts.master')
@section('title') {{ __('leaves.balance_report_title') }} @stop

@section('content')
<br>
<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
        <h5 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-bar mr-2"></i> {{ __('leaves.balance_report_title') }}
        </h5>
        <!-- أزرار التصدير والطباعة -->
        <div class="print-hide">
            <button type="button" class="btn btn-success btn-sm px-3 shadow-sm" onclick="exportExcel()">
                <i class="fas fa-file-excel mr-1"></i> تصدير Excel
            </button>
            <button type="button" class="btn btn-info btn-sm px-3 shadow-sm ml-2 text-white" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> طباعة التقرير
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-nowrap" id="balanceTable" width="100%">
                <thead class="thead-light bg-light text-dark">
                    <tr>
                        <th class="py-3">{{ __('leaves.employee') }}</th>
                        <th class="py-3 text-center">{{ __('leaves.total_balance') }}</th>
                        <th class="py-3 text-center">{{ __('leaves.used_leaves') }}</th>
                        <th class="py-3 text-center">{{ __('leaves.remaining_balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                        @php
                            $used = $emp->leaves->sum('days_count');
                            $totalBalance = $emp->total_leave_days ?? 21;
                            $balance = $totalBalance - $used;
                        @endphp
                        <tr>
                            <td class="font-weight-bold align-middle">
                                <i class="fas fa-user-circle text-secondary mr-1"></i> {{ $emp->name_ar }}
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-info px-3 py-2 font-weight-normal">{{ $totalBalance }}</span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-danger px-3 py-2 font-weight-normal">{{ $used }}</span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge {{ $balance < 0 ? 'badge-danger' : 'badge-success' }} px-3 py-2 font-weight-normal">
                                    {{ $balance }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('css')
<!-- مكتبة SheetJS لتصدير الجدول إلى Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
    /* تنسيقات خاصة لطباعة التقرير بدون الهيدر والقائمة الجانبية */
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        .print-hide {
            display: none !important;
        }
    }
</style>
@endsection

@section('js')
<script>
    function exportExcel() {
        var table = document.getElementById("balanceTable");
        var wb = XLSX.utils.table_to_book(table, {sheet: "Leave Balance"});
        XLSX.writeFile(wb, "Leave_Balance_Report.xlsx");
    }
</script>
@endsection