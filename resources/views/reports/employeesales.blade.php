@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ __('report.employeeـsales') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.employeeـsales') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ التقرير</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    <!-- عرض الأخطاء -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطأ!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- رسائل التنبيه -->
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
            <div class="card shadow-sm mg-b-20" style="border-radius: 12px; border: none;">
                <div class="card-header bg-transparent pb-3 pt-4 border-bottom-0">
                    
                    <!-- نموذج البحث -->
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/employeeSalesSearch') }}"
                          method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row align-items-end">
                            <!-- من تاريخ -->
                            <div class="col-lg-4 mg-t-10" id="start_at">
                                <label class="form-label font-weight-bold text-muted">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}"
                                           name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- إلى تاريخ -->
                            <div class="col-lg-4 mg-t-10" id="end_at">
                                <label class="form-label font-weight-bold text-muted">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at"
                                           value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- اختيار الموظف -->
                            <div class="col-lg-4 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('report.Enter_employeeـname') }}</label>
                                <select class="form-control select2" name="productname" required>
                                    <option value="-"> {{ __('report.Enter_employeeـname') }} </option>
                                    @foreach (App\Models\User::all() as $section)
                                        <option value="{{ $section->id }}" {{ (isset($userId) && $userId == $section->id) ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- زر البحث -->
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 8px;">
                                <i class="las la-search font-weight-bold ml-1" style="font-size: 16px;"></i>
                                {{ __('home.search') }}
                            </button>
                        </div>
                    </form>
                </div>

                @if (isset($Invoices))
                    <div class="card-body pt-0">
                        @php
                            $userId = 0;
                            $count = 0;
                            $totalprice = 0;
                            $avt = App\Models\Avt::find(1);
                            $saleavt = $avt ? $avt->AVT : 0;
                            $totaladdedvalue = 0;
                            $totaldiscount = 0;
                            $startat = '';
                            $endat = '';
                        @endphp

                        <!-- جدول الفواتير -->
                        <div class="table-responsive hoverable-table mt-3">
                            <table class="table text-md-nowrap table-hover border-top-0" id="example1" data-page-length='50' style="text-align: center;">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.Invoice_no') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.sallerName') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.clietName') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.date') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.branch') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.total') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.paymentmethod') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Invoices as $product)
                                        @php
                                            $totaladdedvalue += ($product->Price - $product->discount) * $saleavt;
                                            $totalprice += ($product->Price - $product->discount);
                                            $totaldiscount += $product->discount;
                                            if ($count == 0) {
                                                $userId = $product->user_id;
                                                $startat = $product->created_at;
                                            }
                                            $endat = $product->created_at;
                                            $count++;

                                            // تحديد طريقة الدفع
                                            $pay = match($product->Pay) {
                                                'Cash' => __('report.cash'),
                                                'Shabka' => __('report.shabka'),
                                                'Credit' => __('report.credit'),
                                                'Bank_transfer' => __('home.Bank_transfer'),
                                                default => __('home.Partition of the amount'),
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->user->name ?? '-' }}</td>
                                            <td dir="ltr">{{ $product->customer->name ?? '-' }}</td>
                                            <td>{{ $product->created_at }}</td>
                                            <td>{{ $product->branch->name ?? '-' }}</td>
                                            <td class="font-weight-bold text-success">
                                                {{ round(($product->Price - $product->discount) + (($product->Price - $product->discount) * $saleavt), 2) }}
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-light px-2 py-1">{{ $pay }}</span>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary" href="showInvoiceRecent/{{ $product->id }}">
                                                    <i class="fas fa-print ml-1"></i> {{ __('home.show') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- جدول الإجماليات -->
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center bg-light" style="border-radius: 8px; overflow: hidden;">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>البند</th>
                                                <th>المبلغ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ __('home.totaldiscount') }}</td>
                                                <td class="text-danger font-weight-bold">{{ $totaldiscount }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>{{ __('report.totalpricewithoudtax') }}</td>
                                                <td>{{ $totalprice }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>{{ __('report.totaltax') }}</td>
                                                <td>{{ round($totaladdedvalue, 2) }}</td>
                                            </tr>
                                            <tr class="font-weight-bold bg-warning-transparent">
                                                <td>4</td>
                                                <td>{{ __('report.totalallprice') }}</td>
                                                <td class="text-success font-weight-bold" style="font-size: 16px;">
                                                    {{ round($totaladdedvalue + $totalprice, 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- زر الطباعة -->
                        <div class="d-flex justify-content-center mt-4 mb-3">
                            <a class="btn btn-info px-4 py-2 shadow-sm d-flex align-items-center" style="background-color: #419BB2; border-radius: 8px; font-size: 16px;"
                               href="{{ url('/' . 'printReportemployeeSales' . '/' . $userId . '/' . $startat . '/' . $endat) }}">
                                <i class="fas fa-print ml-2" style="font-size: 18px;"></i>
                                {{ __('home.print') }}
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- row closed -->

@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!-- Datepicker & Select2 -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/form-elements.js') }}</td>"></script>

    <script>
        $(document).ready(function() {
            // تهيئة حقول التاريخ
            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            // تفعيل Select2 للقائمة المنسدلة للموظفين
            $('.select2').select2({
                width: '100%'
            });

            // إخفاء التنبيهات تلقائياً
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
        });
    </script>
@endsection