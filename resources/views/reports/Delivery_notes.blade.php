@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ __('report.Delivery_notes') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.Delivery_notes') }}</h4>
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

    <!-- رسائل الجلسة -->
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
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/Delivery_notes') }}" 
                          method="POST" role="search" autocomplete="off">
                        @csrf

                        <div class="row">
                            <!-- تاريخ البداية -->
                            <div class="col-lg-3">
                                <label class="form-label font-weight-bold">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- تاريخ النهاية -->
                            <div class="col-lg-3">
                                <label class="form-label font-weight-bold">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- اختيار رقم الفاتورة / المورد -->
                            <div class="col-lg-4">
                                <label class="form-label font-weight-bold">{{ __('home.enterinvoicenumber') }}</label>
                                <select class="form-control select2" name="UserId" required>
                                    <option value="-">{{ __('home.enterinvoicenumber') }}</option>
                                    @foreach (App\Models\resource_purchases::get() as $section)
                                        <option value="{{ $section->orderId }}" {{ isset($supplierId) && $supplierId == $section->orderId ? 'selected' : '' }}>
                                            {{ $section->orderId }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- زر البحث -->
                            <div class="col-lg-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-block">
                                    {{ __('home.search') }} <i class="las la-search fs-15 ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if (isset($Invoices) && count($Invoices) > 0)
                        @php
                            $count = 0;
                            $startat = '';
                            $endat = '';
                        @endphp

                        @foreach ($Invoices as $invoice)
                            @php
                                if ($count == 0) {
                                    $startat = $invoice->created_at;
                                }
                                $endat = $invoice->created_at;
                                $count++;
                            @endphp

                            <!-- معلومات الفاتورة الأساسية -->
                            <div class="card border custom-card mb-4 shadow-sm">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mg-b-0 text-md-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th class="w-25 bg-light">{{ __('report.invoiceNo') }}</th>
                                                    <td>{{ $invoice->orderId }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">{{ __('home.suppliername') }}</th>
                                                    <td>{{ $invoice->supllier->name ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">{{ __('home.notesClient') }}</th>
                                                    <td>{{ $invoice->notes ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">{{ __('users.branch') }}</th>
                                                    <td>{{ $invoice->branch->name ?? '---' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- جدول تفاصيل المنتجات -->
                                    <div class="table-responsive mt-3">
                                        <table class="table table-striped table-bordered text-center text-md-nowrap">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th>#</th>
                                                    <th>{{ __('report.date') }}</th>
                                                    <th>{{ __('home.productNo') }}</th>
                                                    <th>{{ __('home.product') }}</th>
                                                    <th>{{ __('home.quantity') }}</th>
                                                    <th>{{ __('home.saleprice') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 0; @endphp
                                                @foreach (App\Models\orderDetails::where('order_owner', $invoice->orderId)->where('numberofpice', '!=', 0)->get() as $product)
                                                    @php
                                                        $i++;
                                                        $date = explode(' ', $product->created_at);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $date[0] }}</td>
                                                        <td dir="ltr">{{ $product->productData->Product_Code ?? '' }}</td>
                                                        <td>{{ $product->productData->product_name ?? '' }}</td>
                                                        <td>{{ $product->numberofpice }}</td>
                                                        <td>{{ $product->productData->purchasingـprice ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- زر الطباعة -->
                        <div class="d-flex justify-content-center mt-4 mb-2">
                            <a href="{{ url('/printDelivery_notes/' . ($supplierId ?? '-') . '/' . $startat . '/' . $endat) }}" 
                               class="btn btn-info px-4 py-2 text-white" style="background-color: #419BB2; font-size: 16px;">
                                <i class="fas fa-print ml-1"></i> {{ __('home.print') }}
                            </a>
                        </div>
                    @endif
                </div>
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    
    <!-- Internal Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    
    <!-- Internal Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    
    <script>
        // تفعيل الـ Datepicker
        $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        // اختفاء التنبيهات تلقائياً
        $(document).ready(function() {
            var timeout = 4000;
            $('.alert').delay(timeout).fadeOut(500);
        });
    </script>
@endsection