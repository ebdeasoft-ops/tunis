@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.DataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.DataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@section('title')
    تقرير مبيعات الموظف - Employee Sales Report
@stop
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.employeeـsales') }}</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <!-- عنوان التقرير الرئيسي في المنتصف بالعربية والإنجليزية -->
    <div class="text-center my-4">
        <h2 style="font-weight: bold; color: #2c3e50; font-family: 'Cairo', sans-serif; margin-bottom: 5px;">
            تقرير مبيعات الموظف
        </h2>
        <h4 style="font-weight: 600; color: #7f8c8d; font-family: 'Times New Roman', Times, serif;">
            Employee Sales Report
        </h4>
        <hr style="width: 150px; border-top: 2px solid #419BB2; margin: 15px auto;">
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطأ</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm mg-b-20">
                <div class="card-header pb-0 bg-transparent">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/employeeSalesSearch') }}"
                          method="POST" role="search" autocomplete="off" id="searchForm">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-lg-4" id="start_at">
                                <label class="font-weight-bold"> {{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <div class="col-lg-4" id="end_at">
                                <label class="font-weight-bold"> {{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label class="font-weight-bold"> {{ __('report.Enter_employeeـname') }} </label>
                                <select class="form-control select2" name="productname" required>
                                    <option value="-"> {{ __('report.Enter_employeeـname') }} </option>
                                    @foreach (App\Models\User::get() as $section)
                                        <option value="{{ $section->id }}" {{ (isset($userId) && $userId == $section->id) ? 'selected' : '' }}> 
                                            {{ $section->name }} 
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-success px-4 py-2 shadow-sm" id="searchBtn">
                                <i class="las la-search font-weight-bold" style="font-size:16px"></i> {{ __('home.search') }}
                            </button>
                        </div>
                    </form>
                </div>

                @if (isset($Invoices))
                    <div class="card-body">
                        @php
                            $totaldiscount = 0;
                            $totalpriceall = 0;
                            $total = 0;
                            $count = 0;
                            $startat = '';
                            $endat = '';
                        @endphp

                        <div class="table-responsive hoverable-table">
                            <table class="table table-hover table-bordered align-middle" id="example1" data-page-length='50' style="text-align: center; width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                        <th class="border-bottom-0">{{ __('home.sallerName') }}</th>
                                        <th class="border-bottom-0">{{ __('home.clietName') }}</th>
                                        <th class="border-bottom-0">{{ __('home.date') }}</th>
                                        <th class="border-bottom-0">{{ __('home.branch') }}</th>
                                        <th class="border-bottom-0">{{ __('home.total') }}</th>
                                        <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th class="border-bottom-0">{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Invoices as $product)
                                        @php
                                            $totaldiscount += $product->discount;
                                            $totalpriceall += ($product->cashamount + $product->bankamount + $product->Bank_transfer);
                                            
                                            if ($count == 0) {
                                                $startat = $product->created_at;
                                            }
                                            $endat = $product->created_at;
                                            $count++;

                                            $invoiceTotal = ($product->cashamount + $product->bankamount + $product->Bank_transfer + $product->creaditamount);
                                            $total += $invoiceTotal;

                                            $pays = match($product->Pay) {
                                                'Cash' => __('report.cash'),
                                                'Shabka' => __('report.shabka'),
                                                'Credit' => __('report.credit'),
                                                'Bank_transfer' => __('home.Bank_transfer'),
                                                default => __('home.Partition of the amount')
                                            };
                                        @endphp
                                        <tr id="{{ $product->id }}">
                                            <td class="font-weight-bold text-dark">#{{ $product->id }}</td>
                                            <td>{{ optional($product->user)->name }}</td>
                                            <td dir="ltr" class="font-weight-semibold">{{ optional($product->customer)->name }}</td>
                                            <td class="text-muted small">{{ $product->created_at }}</td>
                                            <td><span class="badge badge-light border">{{ optional($product->branch)->name }}</span></td>
                                            <td class="font-weight-bold text-success">{{ round($invoiceTotal, 2) }}</td>
                                            <td><span class="badge badge-info badge-pay">{{ $pays }}</span></td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary px-3" style="border-radius: 6px;" href="showInvoiceRecent/{{ $product->id }}">
                                                    <i class="fas fa-print mr-1"></i>&nbsp;&nbsp;{{ __('home.show') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Table / جدول الملخص -->
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-5">
                                <table class="table table-bordered text-center table-striped">
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold text-right">{{ __('home.totaldiscount') }}</td>
                                            <td class="text-danger font-weight-bold">{{ number_format($totaldiscount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold text-right">{{ __('report.totalpricewithoudtax') }}</td>
                                            <td>{{ number_format($totalpriceall, 2) }}</td>
                                        </tr>
                                        <tr class="bg-success text-white">
                                            <td class="font-weight-bold text-right"><strong>{{ __('report.totalallprice') }}</strong></td>
                                            <td><strong>{{ number_format($total, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-3 mt-4">
                            <a class="btn btn-info px-4 py-2" href="{{ url('/printReportemployeeSales/' . ($userId ?? 0) . '/' . ($start_at ?? 'all') . '/' . ($end_at ?? 'all')) }}">
                                <i class="fas fa-print ml-1"></i> {{ __('home.print') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "{{ __('report.Enter_employeeـname') }}",
                width: '100%'
            });

            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                maxDate: new Date()
            });

            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
        });
    </script>
@endsection