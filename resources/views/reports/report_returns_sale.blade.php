@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
    }
    .card-custom {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        margin-bottom: 25px;
    }
    .card-header-custom {
        background-color: #ffffff;
        border-bottom: 1px solid #edf2f7;
        padding: 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .table-official {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .table-official thead th {
        background-color: #1e293b;
        color: #ffffff;
        border: none;
        padding: 12px;
        font-weight: 600;
        text-align: center;
        font-size: 13px;
    }
    .table-official tbody td {
        padding: 12px;
        vertical-align: middle;
        color: #334155;
        border-top: 1px solid #edf2f7;
        text-align: center;
        background-color: #ffffff;
    }
    .table-official tbody tr:hover {
        background-color: #f8fafc;
    }
    .summary-box {
        width: 350px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .summary-box table {
        width: 100%;
        margin-bottom: 0;
    }
    .summary-box th, .summary-box td {
        padding: 8px 10px;
        border: none;
    }
    .summary-box th {
        color: #64748b;
        font-weight: 600;
        text-align: right;
    }
    .summary-box td {
        color: #0f172a;
        font-weight: bold;
        text-align: left;
        font-size: 15px;
    }
</style>
@endsection

@section('title')
{{ __('report.report_returns_sale') }}
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between my-3">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto font-weight-bold" style="color: #1e293b;">{{ __('report.report_returns_sale') }}</h4>
        </div>
    </div>
</div>
@endsection

@section('content')

@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>خطأ</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- row -->
<div class="row">
    <div class="col-xl-12">
        <!-- بطاقة نموذج البحث -->
        <div class="card card-custom">
            <div class="card-header-custom">
                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'search_report_returns_sale')) }}" method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}

                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3 mb-lg-0" id="start_at">
                            <label class="font-weight-bold text-muted mb-2" style="font-size: 13px;">{{ __('report.fromdate') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </div>
                                </div>
                                <input class="form-control fc-datepicker border-left-0" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required style="border-radius: 0 4px 4px 0;">
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3 mb-lg-0" id="end_at">
                            <label class="font-weight-bold text-muted mb-2" style="font-size: 13px;">{{ __('report.todate') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </div>
                                </div>
                                <input class="form-control fc-datepicker border-left-0" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required style="border-radius: 0 4px 4px 0;">
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3 mb-lg-0" id="type">
                            <label class="font-weight-bold text-muted mb-2" style="font-size: 13px;">{{ __('users.branch') }}</label>
                            <select class="form-control select2" name="branch" required>
                                <option value="-" selected>{{ __('users.allbranchs') }}</option>
                                @foreach (App\Models\branchs::get() as $branch)
                                <option value="{{ $branch->id }}"> {{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 text-center text-lg-right">
                            <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 6px;">
                                <i class="las la-search ml-1" style="font-size: 16px;"></i> {{ __('home.search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($Invoices))
        <?php
            $userId = 0;
            $count = 0;
            $totalprice = 0;
            $totaladdedvalue = 0;
            $i = 0;
            $invoiceIds = [];
            $totalpricefinal = 0;
        ?>

        <!-- بطاقة نتائج البحث والجدول -->
        <div class="card card-custom">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-official text-md-nowrap mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('report.date') }}</th>
                                <th>{{ __('report.invoiceNo') }}</th>
                                <th>اسم العميل</th>
                                <th>{{ __('home.paymentmethod') }}</th>
                                <th>{{ __('home.total') }}</th>
                                <th>{{ __('home.quantity') }}</th>
                                <th>{{ __('home.operations') }}</th>
                            </tr>
                        </thead>
                        <?php
                            $avt = App\Models\Avt::find(1);
                            $saleavt = $avt->AVT ?? 0.15;
                        ?>
                        @foreach ($Invoices as $invoice)
                            <?php
                                if ($count == 0) {
                                }
                                $count++;

                                if (!in_array($invoice->invoice_id, $invoiceIds)) {
                                    $invoiceIds[] = $invoice->invoice_id;
                                }
                            ?>
                        @endforeach

                        <?php
                            $returnsales = 0;
                            $totalprice = 0;
                        ?>

                        <tbody>
                            @foreach ($invoiceIds as $invoiceid)
                                <?php
                                    $datainvoice = App\Models\invoices::find($invoiceid);
                                    $InvoicesCollection = App\Models\return_sales::where('invoice_id', $invoiceid)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get();
                                    $date = 0;
                                    $numberofPice = 0;
                                    $total_addedvalue = 0;
                                    $eachreturn = 0;
                                    $Pay = '';

                                    foreach ($InvoicesCollection as $itemInvoice) {
                                        $eachreturn += ($itemInvoice->return_Unit_Price * $itemInvoice->return_quantity) - $itemInvoice->discountvalue - $itemInvoice->discountoninvoice;
                                        if($itemInvoice->Invoice) {
                                            $Pay = $itemInvoice->Invoice->Pay;
                                        }
                                        $date = $itemInvoice->created_at;
                                        $numberofPice += $itemInvoice->return_quantity;
                                        $total_addedvalue += ($itemInvoice->return_Unit_Price * $itemInvoice->return_quantity) - $itemInvoice->discountvalue - $itemInvoice->discountoninvoice;
                                        $totalprice += ($itemInvoice->return_Unit_Price * $itemInvoice->return_quantity) - $itemInvoice->discountvalue - $itemInvoice->discountoninvoice;
                                    }
                                    $i++;
                                    if($date) {
                                        $date = explode(' ', $date);
                                    }
                                    $eachreturn += $total_addedvalue * $saleavt;
                                    $totalprice += $total_addedvalue * $saleavt;
                                ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ is_array($date) ? $date[0] : $date }}</td>
                                    <td><span class="badge badge-light border px-2 py-1">{{ $invoiceid }}</span></td>
                                    <td class="font-weight-bold">{{ $datainvoice && $datainvoice->customer ? $datainvoice->customer->name : '-' }}</td>
                                    <td>
                                        @if ($Pay == 'Cash')
                                            <span class="badge badge-success px-2 py-1">{{ __('report.cash') }}</span>
                                        @elseif($Pay == 'Credit')
                                            <span class="badge badge-danger px-2 py-1">{{ __('report.credit') }}</span>
                                        @elseif($Pay == 'Bank_transfer')
                                            <span class="badge badge-info px-2 py-1">{{ __('home.Bank_transfer') }}</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1">{{ __('report.shabka') }}</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold text-dark">{{ number_format($eachreturn, 2) }}</td>
                                    <td>{{ number_format($numberofPice, 0) }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-dark px-3" href="Show_return_Sales_Details/{{ $invoiceid }}">
                                            <i class="fas fa-print ml-1"></i> {{ __('home.show') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- قسم الإجماليات وزر الطباعة -->
                <div class="d-flex justify-content-between align-items-end mt-4 flex-wrap">
                    <div class="summary-box mb-3 mb-md-0">
                        <table>
                            <tr>
                                <th>{{ __('report.totalallprice') }}</th>
                                <td>{{ number_format($totalprice, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <div>
                        <a class="btn btn-secondary px-4 py-2 font-weight-bold shadow-sm" href="{{ url('/' . ($page = 'printreturnInvoicesReport') . '/' . $branch_Id . '/' . $start . '/' . $end) }}" style="border-radius: 6px;">
                            <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
        @endif

    </div>
</div>
<!-- row closed -->
@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!-- Datepicker & Select2 -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

<script>
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        var timeout = 4000;
        $('.alert').delay(timeout).fadeOut(500);
    });
</script>
@endsection