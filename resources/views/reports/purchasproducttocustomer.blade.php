@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
    
    <style>
        .search-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }
        .parent-label { 
            font-weight: 600; 
            color: #343a40; 
            margin-bottom: 8px; 
            display: block; 
            font-size: 13.5px; 
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
            background-color: #1e293b !important; 
            color: #fff !important; 
            border: none !important;
        }
        .form-control, .select2-container--default .select2-selection--single {
            height: 45px !important;
            padding: 8px 14px;
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
        }
        .btn-custom-search {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-custom-search:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .btn-choose-product {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
            transition: all 0.2s;
        }
        .btn-choose-product:hover {
            opacity: 0.95;
            color: #fff;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('title')
    {{ __('report.purchasproducttocustomer') }}
@endsection

@section('page-header')
    <!--breadcrumb-->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.purchasproducttocustomer') }}</h4>
            </div>
        </div>
    </div>
    <!--breadcrumb-->
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong><i class="fas fa-exclamation-triangle ml-1"></i> {{ __('home.error') ?? 'Error' }}</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('notfountreturnproduct'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
            <strong><i class="fas fa-info-circle ml-1"></i> {{ session()->get('notfountreturnproduct') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            
            <div class="card search-card mg-b-20 p-4">
                <div class="card-header pb-3 bg-transparent border-0 px-0 pt-0">
                    <h5 class="text-dark font-weight-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-filter text-primary mr-2"></i> {{ __('home.advanced_search_filters') ?? 'بحث متقدم وفلترة البيانات' }}
                    </h5>
                    <hr class="mt-3 mb-1 border-light">
                </div>
                
                <div class="card-body px-0 pb-0 pt-2">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/purchasproducttocustomer') }}" method="POST" role="search" autocomplete="off">
                        @csrf

                        <div class="row">
                            <!-- From Date -->
                            <div class="col-lg-3 mg-b-15">
                                <label class="parent-label">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <input class="form-control native-datepicker" value="{{ $start_at ?? '' }}" name="start_at" type="date" required>
                                    <div class="input-group-append" onclick="$(this).siblings('input').focus();" style="cursor: pointer;">
                                        <div class="input-group-text bg-light border-0" style="border-radius: {{ app()->getLocale() == 'ar' ? '8px 0 0 8px' : '0 8px 8px 0' }};">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- To Date -->
                            <div class="col-lg-3 mg-b-15">
                                <label class="parent-label">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <input class="form-control native-datepicker" name="end_at" value="{{ $end_at ?? '' }}" type="date" required>
                                    <div class="input-group-append" onclick="$(this).siblings('input').focus();" style="cursor: pointer;">
                                        <div class="input-group-text bg-light border-0" style="border-radius: {{ app()->getLocale() == 'ar' ? '8px 0 0 8px' : '0 8px 8px 0' }};">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Client Name -->
                            <div class="col-lg-6 mg-b-15">
                                <label class="parent-label">{{ __('home.clientname') }}</label>
                                <select class="form-control select2" id="branch" name="branch" required>
                                    <option value="">{{ __('home.clientname') }}</option>
                                    @foreach (App\Models\customers::all() as $customer)
                                        <option value="{{ $customer->id }}" {{ (isset($selected_branch) && $selected_branch == $customer->id) ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="productNo" id="productNo" value="{{ $productNo ?? '-' }}">
                        </div>
                        
                        <!-- اختيار المنتج وبياناته -->
                        <div class="row my-2 align-items-end">
                            <div class="col-lg-3 mg-b-15">
                                <label for="product_code" class="control-label parent-label">{{ __('home.productNo') }}</label>
                                <input dir="ltr" type="text" class="form-control text-center font-weight-bold" id="product_code" name="product_code" value="{{ $product_code ?? '' }}" required readonly>
                            </div>
                            <div class="col-lg-5 mg-b-15">
                                <label for="productnameshow" class="control-label parent-label">{{ __('home.productname') }}</label>
                                <input type="text" class="form-control" id="productnameshow" name="productnameshow" value="{{ $productnameshow ?? '' }}" required readonly>
                            </div>
                            <div class="col-lg-4 mg-b-15">
                                <label class="parent-label d-block">&nbsp;</label>
                                <a class="modal-effect btn btn-choose-product w-100" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">
                                    {{ __('home.chooose product') }} <i class="las la-search ml-2 fs-18"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- زر البحث الرئيسي -->
                        <div class="d-flex justify-content-center mt-4 pt-2">
                            <button type="submit" class="btn btn-custom-search text-white px-5 py-2.5 shadow-sm font-weight-bold" style="font-size: 15px;">
                                <i class="las la-search fs-18 ml-1"></i> {{ __('home.search') }}
                            </button>
                        </div>
                    </form>

                    <!-- عرض النتائج -->
                    @if (isset($Saleing))
                        <div class="card m-0 mt-5 p-3 shadow-none border bg-light" style="border-radius: 12px;">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered text-center align-middle mb-0 bg-white" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('home.date') }}</th>
                                            <th dir="ltr">{{ __('home.productNo') }}</th>
                                            <th>{{ __('home.product') }}</th>
                                            <th>{{ __('home.sellingproduct without tax') }}</th>
                                            <th>{{ __('home.quantity') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($Saleing as $index => $invoice)
                                            <tr>
                                                <td class="font-weight-bold text-primary">{{ $index + 1 }}</td>
                                                <td>{{ $invoice->created_at }}</td>
                                                <td dir="ltr" class="font-weight-bold text-muted">{{ $invoice->productData->Product_Code ?? '' }}</td>
                                                <td class="font-weight-bold">{{ $invoice->productData->product_name ?? '' }}</td>
                                                <td class="text-success font-weight-bold">{{ number_format($invoice->Unit_Price, 2) }}</td>
                                                <td><span class="badge badge-primary px-3 py-2" style="font-size: 13px;">{{ $invoice->quantity }}</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-4 text-muted">{{ __('home.no_data_available') ?? 'لا توجد بيانات متاحة' }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

   <!-- Modal Search Product -->
   <div class="modal fade" id="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir="rtl" aria-hidden="true">
        <div class="modal-dialog modal-xl product-selection" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
                <div class="modal-header bg-light" style="border-radius: 14px 14px 0 0;">
                    <h5 class="modal-title font-weight-bold text-primary">
                        <i class="fas fa-boxes ml-1"></i> {{ __('home.chooose product') }}
                    </h5>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
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

                    <div class="modal-footer px-0 pb-0 pt-3 border-top">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 8px;">{{ __('home.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });

        function searchaboutproductfunction() {
            var searchtext = $('#searchaboutproduct').val();
            var branchs_id = $('#branch').val();
            var token_search = $("#token_search").val();

            jQuery.ajax({
                url: "{{ URL::to('ChooseProductpaginatenewupdate') }}",
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
                }
            });
        }

        $(document).on('click', '#ajax_pagination_in_search a', function(e) {
            e.preventDefault();
            var searchtext = $('#searchaboutproduct').val();
            var branchs_id = $('#branch').val();
            var token_search = $("#token_search").val();
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
                }
            });
        });

        $('#SearchProduct').on('show.bs.modal', function(event) {
            var branchs_id = $('#branch').val();
            var token_search = $("#token_search").val();

            jQuery.ajax({
                url: "{{ URL::to('ChooseProductpaginatenewupdate') }}",
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
                }
            });
        });

        function chooseProduct(code, name, price, sale_price, product_location, availablequantity, productcode, MAINproductname, maincode) {
            $('#productNo').val(code);
            $('#productnameshow').val(name);
            $('#product_code').val(product_location);
            $('#SearchProduct').modal('hide');
        }
    </script>
@endsection