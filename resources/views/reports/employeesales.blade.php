@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@section('title')
    {{ __('report.employeeـsales') }}
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

    @if (session()->has('notfountreturnproduct'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('notfountreturnproduct') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
                        <?php
                            $totalprice = 0;
                            $avt = App\Models\Avt::find(1);
                            $saleavt = $avt ? $avt->AVT : 0;
                            $totaladdedvalue = 0;
                            $totaldiscount = 0;
                        ?>

                        <div class="table-responsive hoverable-table">
                            <table class="table text-md-nowrap table-hover" id="example1" data-page-length='50' style="text-align: center;">
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
                                        <?php
                                            $netPrice = $product->Price - $product->discount;
                                            $totaladdedvalue += $netPrice * $saleavt;
                                            $totalprice += $netPrice;
                                            $totaldiscount += $product->discount;
                                        ?>
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->user->name ?? '---' }}</td>
                                            <td dir="ltr">{{ $product->customer->name ?? '---' }}</td>
                                            <td>{{ $product->created_at }}</td>
                                            <td>{{ $product->branch->name ?? '---' }}</td>
                                            <td>{{ round($netPrice + ($netPrice * $saleavt), 2) }}</td>
                                            <td>
                                                @php
                                                    $payMap = [
                                                        'Cash' => __('report.cash'),
                                                        'Shabka' => __('report.shabka'),
                                                        'Credit' => __('report.credit'),
                                                        'Bank_transfer' => __('home.Bank_transfer')
                                                    ];
                                                @endphp
                                                <span class="badge badge-pill badge-light px-2 py-1">
                                                    {{ $payMap[$product->Pay] ?? __('home.Partition of the amount') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-primary-gradient" href="showInvoiceRecent/{{ $product->id }}">
                                                    <i class="fas fa-print"></i> {{ __('home.show') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Table -->
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-5">
                                <table class="table table-bordered text-center table-striped">
                                    <tbody>
                                        <tr>
                                            <td><strong>{{ __('home.totaldiscount') }}</strong></td>
                                            <td class="text-danger font-weight-bold">{{ number_format($totaldiscount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('report.totalpricewithoudtax') }}</strong></td>
                                            <td>{{ number_format($totalprice, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('report.totaltax') }}</strong></td>
                                            <td>{{ number_format($totaladdedvalue, 2) }}</td>
                                        </tr>
                                        <tr class="bg-success text-white">
                                            <td><strong>{{ __('report.totalallprice') }}</strong></td>
                                            <td><strong>{{ number_format($totaladdedvalue + $totalprice, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-3 mt-4">
                            <a class="btn btn-info px-4 py-2" href="{{ url('/printReportemployeeSales/' . ($userId ?? 0) . '/' . $start_at . '/' . $end_at) }}">
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
    <!--Internal Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
    <script>
        $(document).ready(function() {
            // تفعيل الـ Select2 للعنصر الخاص باسم الموظف ليكون البحث فيه أسهل وأرتب
            $('.select2').select2({
                placeholder: "{{ __('report.Enter_employeeـname') }}",
                width: '100%'
            });

            // تاريخ البداية والنهاية
            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                maxDate: new Date()
            });

            // إخفاء الـ alerts تلقائياً
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
        });
    </script>
@endsection