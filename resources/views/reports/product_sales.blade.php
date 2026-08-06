@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    
    <!-- Internal Select2 & Spectrum CSS -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <!-- Custom Fager Style Additions -->
    <style>
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .form-control.parent-input, .custom-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            height: auto;
            font-size: 14px;
            transition: all 0.2s;
        }
        .form-control.parent-input:focus {
            border-color: #419BB2;
            box-shadow: 0 0 0 3px rgba(65, 155, 178, 0.15);
        }
        .parent-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .table th {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: 700;
            border-top: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfdfe;
        }
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .summary-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            border-right: 4px solid #419BB2;
        }
    </style>
@endsection

@section('title')
    {{ __('report.product_sales') }}
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between my-3">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <div class="bg-primary-transparent text-primary p-2 rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(65, 155, 178, 0.1);">
                    <i class="las la-chart-bar fs-20" style="color: #419BB2;"></i>
                </div>
                <h4 class="content-title mb-0 my-auto font-weight-bold text-dark">{{ __('report.product_sales') }}</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')

    {{-- عرض الأخطاء --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="las la-exclamation-triangle fs-20 mr-2"></i>
                <strong class="mr-2">خطأ:</strong>
            </div>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- رسائل التنبيه --}}
    @if (session()->has('notfountreturnproduct'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="las la-info-circle fs-18 mr-2"></i>
            <strong>{{ session()->get('notfountreturnproduct') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Card Search Filter -->
    <div class="card mg-b-20">
        <div class="card-body p-4">
            <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/product_sales') }}" 
                  method="POST" role="search" autocomplete="off">
                @csrf

                <div class="row">
                    {{-- تاريخ البداية --}}
                    <div class="col-lg-4 mb-3" id="start_at">
                        <label class="parent-label">{{ __('report.fromdate') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-light text-muted border-right-0" style="border-radius: 8px 0 0 8px;"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                            <input class="form-control parent-input fc-datepicker border-left-0" value="{{ $start_at ?? '' }}" 
                                   name="start_at" placeholder="YYYY-MM-DD"  type="date" required style="border-radius: 0 8px 8px 0;">
                        </div>
                    </div>

                    {{-- تاريخ النهاية --}}
                    <div class="col-lg-4 mb-3" id="end_at">
                        <label class="parent-label">{{ __('report.todate') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-light text-muted border-right-0" style="border-radius: 8px 0 0 8px;"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                            <input class="form-control parent-input native-datepicker border-left-0" type="date" name="end_at" 
                                   value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required style="border-radius: 0 8px 8px 0;">
                        </div>
                    </div>
                    
                    {{-- الفرع --}}
                    <div class="col-lg-4 mb-3" id="type">
                        <label class="parent-label">{{ __('users.branch') }}</label>
                        <select class="form-control parent-input" name="branch" required>
                            <option value="-" selected>{{ __('users.allbranchs') }}</option>
                            @foreach (App\Models\branchs::all() as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input class="form-control select2" name="productNo" id="productNo" value="-" hidden>
                </div>
                    <input hidden=true class="form-control" id="branchs_id" name="branchs_id"
                                value="{{Auth()->user()->branchs_id}}">
                <hr class="my-3 border-light">

                <div class="row align-items-end">
                    <div class="col-lg-3 mb-3 mb-lg-0">
                        <label class="parent-label d-block">&nbsp;</label>
                        <a style="background-color: #FBA10F;" class="modal-effect btn btn-md btn-info text-white w-100 py-2 shadow-sm" 
                           data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">
                            {{ __('home.chooose product') }} <i class="las la-search fs-15 ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 mb-3 mb-lg-0">
                        <label class="control-label parent-label">{{ __('home.productNo') }}</label>
                        <input dir="ltr" type="text" class="form-control parent-input bg-light" id="product_code" name="product_code" readonly required>
                    </div>

                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <label class="control-label parent-label">{{ __('home.productname') }}</label>
                        <input type="text" class="form-control parent-input bg-light" id="productnameshow" name="productnameshow" readonly required>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center mb-1 mt-4">
                    <button class="btn btn-success px-5 py-2 shadow-sm font-weight-bold" style="background-color: #10b981; border: none;">
                        {{ __('home.search') }} <i class="las la-search font-weight-bold ml-1" style="font-size:16px"></i>
                    </button>
                </div>
            </form>

            {{-- جدول النتائج --}}
            @if (isset($products))
                <hr class="my-4 border-light">
                <div class="card border p-3 shadow-none bg-white">
                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-striped text-center align-middle" id="example1" data-page-length="20">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('report.invoiceNo') }}</th>
                                    <th>{{ __('home.productNo') }}</th>
                                    <th>{{ __('home.product') }}</th>
                                    <th>{{ __('report.date') }}</th>
                                    <th>{{ __('home.quantity') }}</th>
                                    <th>{{ __('home.price') }}</th>
                                    <th>{{ __('home.discount') }}</th>
                                    <th>{{ __('home.priceafterDiscount') }}</th>
                                    <th>{{ __('home.addedValue') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                    $totaladdedvalue = 0;
                                    $totalprice = 0;
                                    $totaldiscount = 0;
                                    $avt = App\Models\Avt::find(1);
                                    $saleavt = $avt ? $avt->AVT : 0;
                                    $productId = 0;
                                @endphp

                                @foreach ($products as $invoice)
                                    @php
                                        $i++;
                                        $productId = optional($invoice->productData)->id;
                                        $subTotal = ($invoice->Unit_Price * $invoice->quantity) - $invoice->Discount_Value;
                                        $addedVal = $subTotal * $saleavt;
                                        
                                        $totaladdedvalue += $addedVal;
                                        $totalprice += $subTotal;
                                        $totaldiscount += $invoice->Discount_Value;
                                    @endphp
                                    <tr>
                                        <td class="font-weight-bold text-muted">{{ $i }}</td>
                                        <td><span class="badge badge-light px-2 py-1 border">{{ $invoice->invoice_id }}</span></td>
                                        <td dir="ltr" class="text-primary font-weight-bold">{{ optional($invoice->productData)->Product_Code }}</td>
                                        <td class="font-weight-bold">{{ optional($invoice->productData)->product_name }}</td>
                                        <td class="text-muted small">{{ $invoice->created_at }}</td>
                                        <td><span class="badge badge-info-transparent px-2">{{ $invoice->quantity }}</span></td>
                                        <td>{{ $invoice->Unit_Price * $invoice->quantity }}</td>
                                        <td class="text-danger">{{ $invoice->Discount_Value }}</td>
                                        <td class="font-weight-bold">{{ $subTotal }}</td>
                                        <td>{{ round($addedVal, 2) }}</td>
                                        <td class="font-weight-bold text-success">{{ round($subTotal + $addedVal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- جدول المجاميع الإجمالية --}}
                <div class="row justify-content-end mt-4">
                    <div class="col-lg-6">
                        <div class="card border shadow-sm p-3 bg-light">
                            <table class="table table-bordered bg-white text-center mb-0" style="border-radius: 8px; overflow: hidden;">
                                <tr>
                                    <th scope="col" class="w-50 text-right bg-light">{{ __('home.the amount') }}</th>
                                    <th scope="col" class="font-weight-bold text-dark">{{ $totalprice }}</th>
                                </tr>
                                <tr>
                                    <th class="text-right bg-light">{{ __('home.discount') }}</th>
                                    <th class="text-danger font-weight-bold">{{ $totaldiscount }}</th>
                                </tr>
                                <tr>
                                    <th class="text-right bg-light">{{ __('home.addedValue') }}</th>
                                    <th class="font-weight-bold">{{ round($totaladdedvalue, 2) }}</th>
                                </tr>
                                <tr class="bg-primary-transparent">
                                    <th class="text-right text-primary font-weight-bold" style="background-color: rgba(65, 155, 178, 0.1);">{{ __('home.total') }}</th>
                                    <th class="text-success font-weight-bold fs-16" style="background-color: rgba(65, 155, 178, 0.1);">{{ round($totaladdedvalue + $totalprice, 2) }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                @if ($products->count() > 0 && isset($branch_Id, $start_at, $end_at))
                    <div class="d-flex justify-content-center my-4">
                        <a style="background-color: #419BB2; font-size:16px;" class="btn btn-success px-5 py-2 text-white shadow-sm font-weight-bold"
                           href="{{ url('/' . 'printReportProductSales' . '/' . $branch_Id . '/' . $productId . '/' . $start_at . '/' . $end_at) }}">
                            {{ __('home.print') }} <i class="las la-print ml-1 fs-18"></i>
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
<div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl product-selection" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="card-body">

                    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                        <label for="inputName" style="font-weight: bold" class="control-label parent-label"> {{__('home.searchaboutproduct')}} </label>
                        <input dir="ltr" type="text" class="form-control parent-input" placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                    </div>
                    <br>
                    <div class="table-responsive" id="ajax_responce_serarchDiv">
                        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                            <col style="width:5%">
                            <col style="width:14%">
                            <col style="width:28%">
                            <col style="width:10%">
                            <col style="width:10%">
                            <col style="width:13%">
                            <col style="width:10%">
                            <col style="width:10%">

                            <thead>
                                <tr>
                                    <th style="font-size: 15px" class="border-bottom-0">#</th>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.productlocation')}}</th>

                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                    <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                                    <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



                                </tr>
                            </thead>
                            <tbody class="">
                                <?php $i = 0;
                                $data = 'm'; ?>

                                <?php $i++ ?>

                                <tr>
                                    <td id="tableData" dir=ltr>-</td>
                                    <td id="tableData" dir=ltr>-</td>
                                    <td id="tableData" data-target="product_name">-</td>
                                    <td id="tableData" data-target="product_name">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData">- </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>

                        </div>
                        <div class="row d-flex justify-content-between pagination-row">



                        </div>

                    </div>
                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    </div>

                </div>


            </div>
        </div>

    </div>


</div>
@endsection
@section('js')
    <script>

        
    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();

        jQuery.ajax({
                url:  "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "locale" : "{{ app()->getLocale() }}", // ✅ صح
                    "branchs_id": branchs_id,
                },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },

        });

    }
    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
           searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();
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
            },
            error: function() {

            }
        });
    });
    $('#SearchProduct').on('show.bs.modal', function(event) {
        searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();


        console.log(branchs_id)
        jQuery.ajax({
            url:  "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": '',
                    "locale" : "{{ app()->getLocale() }}", // ✅ صح
                    "branchs_id": branchs_id,
                },
                  success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });

    })

    function chooseProduct(code, name, price, sale_price, product_location, availablequantity, productcode,MAINproductname,maincode) {
         $('#productNo').val(code);
            $('#productnameshow').val(name);
            $('#product_code').val(product_location);
            

    }

</script>


@endsection