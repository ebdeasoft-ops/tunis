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

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('home.Requestـpricesـofـproducts') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Requestـpricesـofـproducts') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
         <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                    <button style="background-color: #FF4F1F;" class="modal-effect btn btn-sm btn-info p-2 m-1 button-eng" data-effect="effect-scale" data-toggle="modal" href="#updateinvoicebyidmodale" title="تحديد"><i style=" height:80;font-weight:400 !important;
                                                 width: 115px;
                                                 font-size:13px" class="las"> {{ __('home.update_qutation') }}</i>
                                     
                                    </button>
                       
                                </div>
      
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- row -->
    <div class="row">
        
    
        <div class="col-xl-12">
            <div style="border-end-end-radius: 10px;border-end-start-radius:10px" class="card mg-b-20 pt-3">


                <div class="card-header pb-0">

                    <div class="row">

                        <div class="col-xl-12">
                            <div style="border-radius: 10px" class="card mg-b-20">


                                <div class="card-header pb-0 pt-1">

                                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'order_price_from_suppliers')) }}" method="POST" role="search" autocomplete="off">
                                        {{ csrf_field() }}


                                      
                                        <div class="col-lg-4 mg-t-20 mg-lg-t-0" id="type">
                                                <p class="mg-b-10  parent-label"> {{ __('home.shearchbysuppliername') }}</p>
                                                <select class="form-control select2" name="suppliernamesearch" id="suppliernamesearch">
                                                    <option value="-" selected>
                                                        {{ $supplierdata->suppliernamesearch ?? __('home.entersuppliername')}}
                                                    </option>

                                                    @foreach (App\Models\supllier::get() as $section)
                                                    <option style="font-size: 15px" value="{{ $section->id }}"> {{ $section->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div><!-- col-4 -->


                                        <br>

                                        <div class="row">
                              
                                        <div class="col-lg-3 col-md-8">
                                        <label for="inputName" class="control-label parent-label">
                                                   . </label>
                                                    <a style="background-color: #FBA10F;width:100%"class="modal-effect btn btn-sm btn-info py-2 px-1 button-eng" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">{{ __('home.chooose product') }}
                                                        <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                            <path d="M21 21l-6 -6"></path>
                                                        </svg>
                                                    </a>
                                            </div>


                                            <!-- col-4 -->
 <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.productNo') }} </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="product_code" name="product_code" 
                                onkeyup="getproduct()" dir=ltr title="  يرجي ادخال رقم المنتج  ">
                            </div>

                                            <div class="col-lg-3 col-md-8">
                                                <label for="inputName" class="control-label parent-label">
                                                    {{ __('home.productname') }} </label>
                                                <input type="text" class="form-control parent-input" id="productnameshow" name="productnameshow" readonly>

                                            </div>
                                            <div class="col-lg-3 col-md-4 mg-lg-t-0">
                                                <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }}
                                                </label>
                                                <input type="text" class="form-control parent-input" id="quentity" name="quentity" onkeyup="convertToNumber()">
                                            </div>
                                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                                            <input class="form-control parent-input " name="productNo" id="productNo" hidden>
                                            <input class="form-control parent-input " name="supplilerId" id="supplilerId" hidden>
                                            <input type="number" class="form-control" id="orderNo" name="orderNo" hidden>
                                        </div>
                                        <br>

                                        <input hidden=true class="form-control" id="branchs_id" name="branchs_id" value="{{Auth()->user()->branchs_id}}">

                                        <div class="d-flex justify-content-center">
                                            <button id="button_1" name="button_1" class="btn btn-success print-style p-1">
                                                {{ __('home.Add') }}
                                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <br>

                                    </form>
                                </div>
                            </div>

                            <br>

    <div class="col-lg-3 col-md-4 mg-lg-t-0">
                                                <label for="inputName" class="control-label parent-label"> {{ __('home.Invoice_no') }}
                                                </label>
                                                <input readonly type="text" class="form-control" id="qoutnumber" name="qoutnumber" onkeyup="convertToNumber()">
                                            </div>
                            <div class="col-xl-12">

                                <div class="table-responsive mg-t-40">
                                    <table class="table table-hover our-table text-center table-striped text-md-nowrap mb-0" id="example" width="100%">
                                        <col style="width:2%">
                                        <col style="width:20% ">
                                        <col style="width:30%">
                                        <col style="width:20%">

                                        <thead>
                                            <tr>
                                                <th style="padding: 8px;font-size:14px" class="border-bottom-0">#</th>
                                                <th style="padding: 8px;font-size:14px" dir=ltr class="border-bottom-0">{{ __('home.productNo') }} </th>
                                                <th style="padding: 8px;font-size:14px" class="border-bottom-0">{{ __('home.product') }}</th>
                                                <th style="padding: 8px;font-size:14px" class="border-bottom-0">{{ __('home.quantity') }}</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'printOrderPriceFromSupplier')) }}" method="POST" role="search" autocomplete="off">
                                        {{ csrf_field() }}


                                        <div class="d-flex justify-content-center">
                                            <input type="number" class="form-control " name="OrderNoprint" id="OrderNoprint" title=" رقم الفاتورة " readonly required=true hidden>

                                            <button style="background-color: #419BB2;font-size:17px" type="submit" class="btn btn-success mt-3 p-1">
                                                {{ __('home.print') }}
                                                <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                    <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <br>
                                </div>

                                </form>

                            </div>


                        </div>
                    </div>
                    <!-- row closed -->
                </div>
                <!-- Container closed -->
            </div>



            <br>



        </div>
        <!-- /row -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->

{{-- Update ( 24/4/2023 ) --}}
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

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    </div>

                </div>


            </div>
        </div>

    </div>


    {{-- End Update ( 24/4/2023 ) --}}




    <!-- main-content closed -->
</div>

    <div class="modal p-3" id="updateinvoicebyidmodale">
        <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.updateinvoicebyid') }} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label"> {{ __('home.purchase_invoice_no') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="updateinvoicebyid" name="name" title="{{ __('supprocesses.name') }}" required>
                        </div>


                    </div>


                    <br>
                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" id="getinvoiceupdate">
                            {{ __('home.search') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                    </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('js')

<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>




{{-- Update ( 24/4/2023 ) --}}



{{-- End Update ( 24/4/2023 ) --}}
<script>
    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
        var search_by_text = $("#search_by_text").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();

        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search
            },
            success: function(data) {
                console.log(data)
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });
      $('#SearchProduct').on('show.bs.modal', function(event) {
        branchs_id = $('#branchs_id').val();
        console.log(branchs_id)
        jQuery.ajax({
            url: " {{URL::to('ChooseProductpaginatenew')}}/" + branchs_id,
            type: 'get',
            dataType: 'html',
            cache: false,

            success: function(data) {
                console.log('done')
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });

    })

        function searchaboutproductfunction() {
            searchtext = $('#searchaboutproduct').val();
            branchs_id = $('#branchs_id').val();


            console.log(branchs_id)
            jQuery.ajax({
                url: " {{URL::to('searchChooseProductpaginatenew')}}/" + searchtext + "/" + branchs_id,
                type: 'get',
                dataType: 'html',
                cache: false,

                success: function(data) {
                    console.log('done')
                    $("#ajax_responce_serarchDiv").html(data);
                },
                error: function() {

                }
            });

        }

</script>
<script>
 
document.addEventListener('keydown', (e) => {

// this would test for whichever key is 40 (down arrow) and the ctrl key at the same time
if (e.ctrlKey && e.keyCode == '38') {
    // call your function to do the thing

    $('#SearchProduct').modal().show();
    
}
})

    function chooseProduct(code, name, price, sale_price, location, availablequantity) {
        $('#SearchProduct').modal().hide();
        $('#searchaboutproduct').val('');
        var Product_Code = code
       
        var product_sale_pice = sale_price
        var Product_Code = code
        var product_name = name
        var product_sale_pice = price
        $("#productname").val(code);
        $('#productnameshow').val(name);
        $('#sale_price').val(price);
        $('#product_code').val(location);
        $('#productNo').val(code);
        document.getElementById("quentity").focus();

    }
    
     function getproduct() {
        searchtext = $('#product_code').val();
        branchs_id = $('#branchs_id').val();

if(searchtext!=''){
     
        jQuery.ajax({
            url: " {{URL::to('detproductbycode')}}/" + searchtext + "/" + branchs_id,
            type: 'get',
            cache: false,
            dataType: "json",


            success: function(data) {
                if (data != 0) {
                             console.log(data['product_name'])
               
                    name=data['product_name']
                    id=data['id']
                     location1=data['Product_Location']
                    availablequantity=data['numberofpice']
                    price=data['purchasingـprice']
                    sale_price=data['sale_price']
                    code=data['id']
        $("#productname").val(code);
        // $("#productcode").val(code);
        $('#productnameshow').val(name);
        $('#sale_price').val(sale_price);
        
        
        var Product_Code = code
        var product_name = name
        var product_sale_pice = price
        $("#productname").val(id);
        $('#productnameshow').val(name);
        $('#sale_price').val(sale_price);
        $('#productNo').val(id);
        document.getElementById("quentity").focus();
     

                }
            },
            error: function() {

            }
        });
}

    }
    
</script>

{{-- End Update ( 24/4/2023 ) --}}


<script>
    function convertToNumber() {
        var input = document.getElementById("quentity");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>

<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>



<script>
    // Update ( 24/4/2023 )





    // End Update ( 24/4/2023 )


















    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });
        $('select[name="suppliertNosearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone'] ?? "05----------");
                        $('#notes').val(data['comp_name']);
                        $('#supplilerId').val(data['id']);


                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });

        $('select[name="suppliernamesearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone']);
                        $('#notes').val(data['comp_name']);
                        $('#supplilerId').val(data['id']);

                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

    $('select[name="productNo"]').on('change', function() {
        console.log('AJAX load   work 0000');

        var selectclientid = $(this).val();
        if (selectclientid) {
            console.log('AJAX load   work');
            $.ajax({
                url: "{{ URL::to('getproduct') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success123");
                    console.log(data);
                    console.log("{{ URL::to('getsupllier') }}/" + selectclientid);
                    $('#productnameshow').val(data['product_name']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
</script>




<script>



 $("#getinvoiceupdate").click(function(e) {

            event.preventDefault();
            var url = " {{ URL::to('update_offer_price_supplier') }}" + "/" + $('#updateinvoicebyid').val();
            console.log(url)
            jQuery.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                cache: false,
            success: function(data) {
                


                        // const map =(JSON.parse(response));
                        console.log('++++++')

                        $('#quentity').val('');
                        $('#productnameshow').val('');

                        console.log('++++++')
                        console.log(data)
                        let table = document.getElementById("example");
                        console.log('+++AFTER TABLE+++')



                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;
                        console.log('+++AFTER 3 TABLE+++')

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        console.log('+++AFTER 4 TABLE+++')


                        data.forEach(async (product) => {
                            
                            if (product['count'] == 1) {
                                                                $('#qoutnumber').val(product['order_id']);

                                $('#orderNo').val(product['order_id']);
                                $('#OrderNoprint').val(product['order_id']);

                            }

                            count1 = product['count'],
                            product_code = product['productCode']
                            product_name = product['productName']
                            quentity = product['productQuantity']

                            if (quentity > 0) {

                                console.log('+++AFTER 5 TABLE+++')

                                let row = table.insertRow(-
                                    1); // We are adding at the end
                                console.log('+++AFTER 5 TABLE+++')

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);

                                console.log('+++AFTER 6 TABLE+++')

                                // Add data to c1 and c2

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' +
                                    product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = quentity



                            }


                        });









                    
                
                
            },
error: function(response) {
alert("{{ __('home.sorryerror') }}")

}
                })})
                
                
                
                
                
                
    $(document).ready(function() {
        $("#button_1").click(function(e) {
            event.preventDefault();

            let table = document.getElementById("example");


            var _token = $("#token_search").val();

            var url = " {{ URL::to('order_price_from_suppliers') }}";


            supplierId = $('#supplilerId').val();

            clientName = $('#clientName').val();
            phonenumber = $('#phonenumber').val();
            address = $('#address').val();
            notes = $('#notes').val();
            productname = $('#productname').val();
            productNo = $('#productNo').val();
            orderNo = $('#orderNo').val();
            productnameshow = $('#productnameshow').val();
            quentity = $('#quentity').val();
            console.log('++++++++++MOHAMED+++++++++')

            console.log(supplierId)
            console.log(orderNo)
            console.log(quentity)
            console.log(_token);

            console.log('+++++++++++++++++++')


            if (productname == '') {
                alert("{{ __('home.pleaseChooseProduct') }}")

            } else if ($('#suppliernamesearch').val() == '-') {
                alert("{{ __('home.entersuppliername')}}")

            } else if (address == '' || quentity == '') {
                alert("{{ __('home.pleaseCompleteEmpty') }}")

            } else {
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        "_token": _token,
                        "supplierId": supplierId,
                        "clientName": clientName,
                        "phonenumber": phonenumber,
                        "address": address,
                        "notes": notes,
                        "productname": productname,
                        "productNo": productNo,
                        "orderNo": orderNo,
                        "productnameshow": productnameshow,
                        "quentity": quentity
                    },


                    success: function(data) {


                        // const map =(JSON.parse(response));
                        console.log('++++++')

                        $('#quentity').val('');
                        $('#productnameshow').val('');

                        console.log('++++++')
                        console.log(data)
                        let table = document.getElementById("example");
                        console.log('+++AFTER TABLE+++')



                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;
                        console.log('+++AFTER 3 TABLE+++')

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        console.log('+++AFTER 4 TABLE+++')


                        data.forEach(async (product) => {
                            if (product['count'] == 1) {
                                $('#orderNo').val(product['order_id']);
                                $('#qoutnumber').val(product['order_id']);
                                $('#OrderNoprint').val(product['order_id']);

                            }

                            count1 = product['count'],
                                product_code = product['productCode']
                            product_name = product['productName']
                            quentity = product['productQuantity']


                            if (quentity > 0) {

                                console.log('+++AFTER 5 TABLE+++')

                                let row = table.insertRow(-
                                    1); // We are adding at the end
                                console.log('+++AFTER 5 TABLE+++')

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);

                                console.log('+++AFTER 6 TABLE+++')

                                // Add data to c1 and c2

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' +
                                    product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = quentity



                            }


                        });









                    },
                    error: function(response) {
                        alert("{{ __('home.sorryerror') }}")

                    }
                });
            }
        });

    });
</script>


@endsection