@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
    
    <style>
        .parent-label { 
            font-weight: 600; 
            margin-bottom: 8px; 
            display: block; 
        }
        .native-datepicker {
            appearance: none;
            -webkit-appearance: none;
            direction: ltr !important;
            text-align: right !important;
            background-color: #fff !important;
            cursor: pointer;
        }
        table.dataTable thead th { 
            text-align: center !important; 
        }
    </style>
@endsection

@section('title')
    {{ __('home.purchasereports') }}
@endsection

@section('page-header')
    <!--breadcrumb-->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.purchasereports') }}</h4>
            </div>
        </div>
    </div>
    <!--breadcrumb-->
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
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
                <div class="card-body">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'purchasereports')) }}"
                        method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            <!-- From Date -->
                            <div class="col-lg-3 mg-b-15" id="start_at">
                                <label for="start_at_input" class="parent-label">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <input class="form-control native-datepicker fc-datepicker" value="{{ $start_at ?? '' }}"
                                        name="start_at" placeholder="YYYY-MM-DD" type="date" required>
                                    <div class="input-group-append" onclick="$(this).siblings('input').focus();" style="cursor: pointer;">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- To Date -->
                            <div class="col-lg-3 mg-b-15" id="end_at">
                                <label for="end_at_input" class="parent-label">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <input class="form-control native-datepicker fc-datepicker" name="end_at"
                                        value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="date" required>
                                    <div class="input-group-append" onclick="$(this).siblings('input').focus();" style="cursor: pointer;">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- حقل البحث السريع واختيار المنتج من المودال -->
                            <div class="col-lg-6 mg-b-15">
                                <label class="parent-label d-block">{{ __('home.searchbyproductname') }}</label>
                                <div class="row align-items-end">
                                    <div class="col-lg-5 mg-b-15">
                                        <input type="hidden" name="productNo" id="productNo" value="{{ $type ?? '' }}" required>
                                        <a class="modal-effect btn btn-primary w-100" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد" style="height: 38px; display: flex; align-items: center; justify-content: center;">
                                            {{ __('home.chooose product') }} <i class="las la-search ml-2"></i>
                                        </a>
                                    </div>
                                    <div class="col-lg-7 mg-b-15">
                                        <input dir="ltr" type="text" class="form-control text-center" id="product_code" name="product_code_show" placeholder="{{ __('home.productNo') }}" readonly required value="{{ $product_code_val ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- زر البحث الرئيسي -->
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="las la-search ml-1"></i> {{ __('home.search') }}
                            </button>
                        </div>
                    </form>

                    @if (isset($products) && count($products) > 0)
                        <div class="card m-0 mt-4">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center text-nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('report.invoiceNo') }}</th>
                                            <th>{{ __('report.date') }}</th>
                                            <th>{{ __('home.suppliername') }}</th>
                                            <th dir='ltr'> {{ __('home.productNo') }}</th>
                                            <th> {{ __('home.product') }}</th>
                                            <th> {{ __('home.quantity') }}</th>
                                            <th>{{ __('home.purchase') }}</th>
                                            <th> {{ __('home.addedValue') }}</th>
                                            <th> {{ __('home.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 0;
                                        $extractedSupplierId = '-';
                                        ?>
                                        @foreach ($products as $invoice)
                                            <?php 
                                            $i++;
                                            if ($i == 1) {
                                                $supplierOrder = App\Models\orderTosupllier::find($invoice->order_owner);
                                                if ($supplierOrder && $supplierOrder->supllier) {
                                                    $extractedSupplierId = $supplierOrder->supllier->id;
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td>{{ $invoice->order_owner }}</td>
                                                <td>{{ $invoice->created_at }}</td>

                                                <?php
                                                $supplierName = App\Models\orderTosupllier::find($invoice->order_owner);
                                                ?>
                                                <td>{{ optional(optional($supplierName)->supllier)->name ?? '---' }}</td>
                                                <td dir="ltr">{{ optional($invoice->productData)->Product_Code }}</td>
                                                <td>{{ optional($invoice->productData)->product_name }}</td>
                                                <td>{{ $invoice->numberofpice }}</td>
                                                <td>{{ $invoice->purchasingـprice }}</td>
                                                <td>{{ $invoice->Added_Value }}</td>
                                                <td>
                                                    {{ ($invoice->purchasingـprice * $invoice->numberofpice) + ($invoice->Added_Value * $invoice->numberofpice) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-info px-4"
                                    href="{{ url('/' . ($page = 'print_purchasereports') . '/' . $productNo . '/' . $start_at . '/' . $end_at) }}">
                                    {{ __('home.print') }}
                                    <svg style="width: 16px !important; fill: #fff; margin-left: 5px;" viewBox="0 0 20 20">
                                        <path d="M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <input hidden=true class="form-control" id="branchs_id" name="branchs_id" value="{{Auth()->user()->branchs_id}}">

    <!-- Modal البحث عن المنتجات -->
    <div class="modal fade" id="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        {{ __('home.chooose product') }}
                    </h5>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-5">
                            <label for="searchaboutproduct" class="control-label parent-label">{{ __('home.searchaboutproduct') }}</label>
                            <input dir="ltr" type="text" class="form-control" placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                        </div>
                    </div>
                    
                    <div class="table-responsive" id="ajax_responce_serarchDiv">
                        <!-- Ajax Dynamic Content Goes Here -->
                    </div>

                    <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!--Internal Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/form-classes.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            var timeout = 4000;
            $('.alert').delay(timeout).fadeOut(500);
        });

        function searchaboutproductfunction() {
            var searchtext = $('#searchaboutproduct').val();
            var branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search").val();

            jQuery.ajax({
                url: "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "locale": "{{ app()->getLocale() }}",
                    "branchs_id": branchs_id,
                },
                success: function(data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },
            });
        }

        $(document).on('click', '#ajax_pagination_in_search a', function(e) {
            e.preventDefault();
            var searchtext = $('#searchaboutproduct').val();
            var branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search`").val();
            var url = $(this).attr("href");

            jQuery.ajax({
                url: url,
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "branchs_id": branchs_id,
                },
                success: function(data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },
            });
        });

        $('#SearchProduct').on('show.bs.modal', function(event) {
            $('#searchaboutproduct').val('');
            var branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search").val();

            jQuery.ajax({
                url: "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": '',
                    "locale": "{{ app()->getLocale() }}",
                    "branchs_id": branchs_id,
                },
                success: function(data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },
            });
        });

        function chooseProduct(id, code, name) {
            $('#productNo').val(id);        
            $('#product_code').val(code);    
            $('#SearchProduct').modal('hide'); 
        }
    </script>
@endsection