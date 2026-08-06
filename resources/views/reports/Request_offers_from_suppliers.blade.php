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
@endsection

@section('title')
    {{ __('report.Requestـoffersـfromـsuppliers') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.Requestـoffersـfromـsuppliers') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
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
                    <h5 class="card-title mb-0 font-weight-bold text-primary">{{ __('home.search') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . 'search_Requestـoffersـfromـsuppliers') }}"
                        method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            <!-- من تاريخ -->
                            <div class="col-lg-3 mg-t-10">
                                <label class="form-label font-weight-bold">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- إلى تاريخ -->
                            <div class="col-lg-3 mg-t-10">
                                <label class="form-label font-weight-bold">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- اسم المورد -->
                            <div class="col-lg-4 mg-t-10">
                                <label class="form-label font-weight-bold">{{ __('home.searchbysuppliertno') }}</label>
                                <select class="form-control select2" name="supplierId" required>
                                    <option value="-"> {{ __('home.searchbysuppliertno') }}</option>
                                    @foreach (App\Models\supllier::get() as $section)
                                        <option value="{{ $section->id }}" {{ isset($supplierId) && $supplierId == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }} - {{ $section->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- زر البحث -->
                            <div class="col-lg-2 mg-t-30 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-block py-2">
                                    <i class="las la-search font-weight-bold" style="font-size:16px;"></i> {{ __('home.search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (isset($Invoices))
                @if (count($Invoices) > 0)
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <?php
                                $userId = 0;
                                $count = 0;
                                $startat = '';
                                $endat = '';
                                $totalprice = 0;
                                $totaladdedvalue = 0;
                            ?>

                            @foreach ($Invoices as $invoice)
                                <?php
                                    $totalEachInvoce = 0;
                                    if ($count == 0) {
                                        $userId = $invoice->user_id;
                                        $startat = $invoice->created_at;
                                    }
                                    $endat = $invoice->created_at;
                                    $count++;
                                ?>

                                <div class="border p-3 mb-4 rounded bg-light">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6 class="text-primary font-weight-bold">
                                                {{ __('home.suppliername') }} : <span class="text-dark">{{ $invoice->supllier->name }}</span>
                                            </h6>
                                        </div>
                                        <div class="col-md-6 text-md-right">
                                            <h6 class="text-primary font-weight-bold">
                                                {{ __('users.branch') }} : <span class="text-dark">{{ $invoice->user->branch->name ?? '' }}</span>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table text-center table-striped table-bordered bg-white">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th colspan="8" class="text-left bg-secondary text-white font-weight-bold">
                                                        {{ __('home.Invoice_no') }} : {{ $invoice->id }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('report.date') }}</th>
                                                    <th>{{ __('home.productNo') }}</th>
                                                    <th>{{ __('home.product') }}</th>
                                                    <th>{{ __('home.quantity') }}</th>
                                                    <th>{{ __('home.purchase') }}</th>
                                                    <th>{{ __('home.addedValue') }}</th>
                                                    <th>{{ __('home.total') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0; ?>
                                                @foreach (App\Models\orderDetails::where('order_owner', $invoice->id)->get() as $product)
                                                    <?php
                                                        $i++;
                                                        $totaladdedvalue += $product->Added_Value * $product->numberofpice;
                                                        $totalprice += $product->purchasingـprice * $product->numberofpice;
                                                        $totalEachInvoce += ($product->purchasingـprice + $product->Added_Value) * $product->numberofpice;
                                                        $date = explode(' ', $product->created_at);
                                                    ?>
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $date[0] }}</td>
                                                        <td dir="ltr">{{ $product->productData->Product_Code ?? '' }}</td>
                                                        <td>{{ $product->productData->product_name ?? '' }}</td>
                                                        <td>{{ $product->numberofpice }}</td>
                                                        <td>{{ $product->purchasingـprice }}</td>
                                                        <td>{{ $product->Added_Value }}</td>
                                                        <td class="font-weight-bold">{{ ($product->purchasingـprice + $product->Added_Value) * $product->numberofpice }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            <!-- جدول الإجماليات النهائية -->
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-6">
                                    <table class="table table-bordered table-striped text-center bg-light">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>{{ __('report.totalprice') }}</th>
                                                <th>{{ __('home.the amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('report.totalpricewithoudtax') }}</td>
                                                <td class="font-weight-bold">{{ $totalprice }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('report.totaltax') }}</td>
                                                <td class="font-weight-bold">{{ $totaladdedvalue }}</td>
                                            </tr>
                                            <tr class="table-success">
                                                <td class="font-weight-bold">{{ __('report.totalallprice') }}</td>
                                                <td class="font-weight-bold text-success">{{ $totaladdedvalue + $totalprice }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- زر الطباعة -->
                            <div class="d-flex justify-content-center mt-4">
                                <a class="btn btn-info px-4 py-2" style="background-color: #419BB2; font-size: 16px;"
                                    href="{{ url('/' . 'print_report_order_from_supplier' . '/' . $supplierId . '/' . $startat . '/' . $endat) }}">
                                    <i class="fas fa-print ml-1"></i> {{ __('home.print') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- رسالة لا توجد بيانات -->
                    <div class="card mg-b-20">
                        <div class="card-body text-center py-5">
                            <h5 class="text-muted font-weight-bold mb-0">لا توجد بيانات لعرضها</h5>
                        </div>
                    </div>
                @endif
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    
    <!-- Internal Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $(document).ready(function() {
            var timeout = 4000;
            $('.alert').delay(timeout).fadeOut(500);
        });
    </script>
@endsection