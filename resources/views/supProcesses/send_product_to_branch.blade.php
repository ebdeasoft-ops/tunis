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
{{__('home.send_product_from_other_branch_other')}}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{__('home.send_product_from_other_branch_other')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    @if (session()->has('nodataprint'))
    <div class="alert alert-warning  alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ __('home.nodataprint') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
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
    @if (session()->has('productupdatedlocation'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>

        <strong>{{ session()->get('productupdatedlocation') }}</strong>
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/'. ($page = 'create_sendProduct')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}




                        <input type="hidden" id="token_search" value="{{ csrf_token() }}" hidden>
                        <input type="hidden" id="order_id" name="order_id" hidden>




                        <div class="row">
                        <div class="col-lg-6 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.choosebranch_reciver') }}</label>
                                <select name="branch" id="branch" class="form-control parent-input">
                                    <!--placeholder-->
                                    @foreach (App\Models\branchs::get() as $section)
                                    @if($section->id!=Auth()->user()->branchs_id)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.chooseemployeereciver') }}</label>
                                <select name="userfrom" id="userfrom" class="form-control parent-input">
                                    <!--placeholder-->
                                    @foreach (App\Models\user::get() as $section)
                                    @if($section->id!=Auth()->user()->id)

                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>
                        </div>


                            <div class="row" style="justify-content: center;">
                              <div  >

                            <a style="background-color: #FBA10F;justify-content: center;" class="modal-effect btn py-2 px-0 btn-info button-eng" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد"><i style=" height: 100;
                 width: 100px;font-size:13px" class="las la-search p-0"> {{ __('home.chooose product') }}</i></a>
                        </div>
                        </div>
                        <div class="row">
                                <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.productNo') }} </p>
                                <input class="form-control parent-input parent-input" name="productname" id="productname" list="productsList" dir="ltr" readonly hidden>
                                <input type="text" onkeyup="getproduct()"  class="form-control parent-input" id="productcode" name="productcode"  dir=ltr>
                            </div>
                            <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{__('home.productname')}} </label>
                                <input type="text" class="form-control" id="product_name" name="product_name" readonly required>

                            </div>

                            <div class="col-lg-3">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }}
                                </label>
                                <input type="number" class="form-control parent-input" id="quentity" name="quentity" value=1>
                            </div>

                            <div class="col-lg-3">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.sellingproduct without tax') }}
                                </label>
                                <input type="number"  class="form-control parent-input" id="thecostProduct" name="thecostProduct">
                            </div>



                            <div class="col">
                                <input type="number" class="form-control" id="product_no" name="product_no" hidden>
                            </div>
                        </div>

                        <br>

                        <div class="d-flex justify-content-center">
                            <button style="background-color: #419BB2" id="button_1" name="button_1" class="btn btn-success">
                                {{ __('home.Add') }}
                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                            </button>
                        </div>

                    </form>

                    <br>



                </div>
            </div>



            <br>





            <?php $i = 0; ?>


            <div class="col">
                <div class="card" style="border-radius: 10px">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 mg-t-20 mg-lg-t-0 mt-5 mb-2">

                                <div class="col-lg-3 mg-t-10 mg-lg-t-0">
                                    <label for="recipient-name" class="control-label parent-label">{{ __('home.Invoice_no') }}</label>
                                    <input type="text" class="form-control parent-input" value=0 onkeyup="convertToNumber()" id="showInvoiceNumber" name="showInvoiceNumber" title="{{ __('home.Invoice_no') }}" readonly>
                                </div>
                            </div>

                        </div>
                        <br>

                        <table id="example" class="table our-table border mb-0 table-responsive text-center" style="border: 1px solid black;border-collapse: collapse !important;">
                            <col style="width:2%">
                            <col style="width:15%">
                            <col style="width:20%">
                            <col style="width:10%">
                            <col style="width:20%">
                            <col style="width:10%">
                            <col style="width:20%">

                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>{{ __('home.productNo') }} </th>
                                    <th>{{ __('home.product') }}</th>
                                    <th>{{ __('home.quantity') }}</th>
                                    <th>{{ __('home.thecostProduct') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                    <th>{{ __('home.operations') }}</th>
                                </tr>
                            </thead>

                            <body style="color: black">
                                <tr style="color: black">
                                    <td style="color:black">-</td>
                                    <td style="color:black">-</td>
                                    <td style="color:black">-</td>
                                    <td style="color:black">-</td>
                                    <td style="color:black">-</td>
                                    <td style="color:black">-</td>
                                    <td style="color:black">-</td>
                                </tr>

                            </body>
                        </table>
                        <br>

                        <form action="{{  url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() .'/' . ($page = 'print_Transfer_items')) }}" method="POST" role="search" autocomplete="off">
                            {{ csrf_field() }}



                            <div class='row d-flex justify-content-center mt-3'>
                                <input type="number" class="form-control " name="sprint_invoice_number" id="sprint_invoice_number" title=" رقم الفاتورة " readonly required=true hidden>


                                <button style="background-color: #419BB2;font-size:17px" type="submit" class="btn btn-success p-1 px-2 fw-bolder">
                                    {{ __('home.print') }}
                                    <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                        <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                    </svg>
                                </button>

                                <input hidden=true class="form-control" id="branchs_id" name="branchs_id" value="{{Auth()->user()->branchs_id}}">




                            </div>


                        </form>

                    </div>
                    <br>



                </div>
            </div>
            <!-- row closed -->
        </div>
        <!-- Container closed -->
    </div>

    <div class="modal fade product-selection" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body">


                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                                <label for="inputName" style="font-weight: bold" class="control-label parent-label">
                                    {{__('home.searchaboutproduct')}} </label>
                                <input autocomplete="off" dir="ltr" type="text" autofocus class="form-control parent-input"
                                    placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct"
                                    name="searchaboutproduct" onkeyup="searchaboutproductfunction()" autofocus>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.groups') }}</label>
                                <select style="width:100%!important" name="product_group" id="product_group"
                                    class="form-control select2">
                                    <!--placeholder-->
                                    @foreach (App\Models\products_group::get() as $section)
                                        <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive" id="ajax_responce_serarchDiv">
                            <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%"
                                style="border: 2px solid rgba(0,0,0,.3);">
                                <col style="width:5%">
                                <col style="width:14%">
                                <col style="width:28%">
                                <col style="width:10%">
                                <col style="width:18%">
                                <col style="width:15%">
                                <col style="width:10%">

                                <thead>
                                    <tr>
                                        <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}}
                                        </th>
                                        <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                            {{__('home.product')}}
                                        </th>
                                        <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                            {{__('home.branch')}}
                                        </th>
                                        <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                            {{__('home.productlocation')}}
                                        </th>

                                        <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}
                                        </th>
                                        <th style="font-size: 13px" class="border-bottom-0">
                                            {{__('home.purchaseproductwithouttax')}}
                                        </th>
                                        <th style="font-size: 13px" class="border-bottom-0">
                                            {{__('home.sellingproduct without tax')}}
                                        </th>
                                        <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



                                    </tr>
                                </thead>


                                <tbody class="">
                                    <?php $i = 0;
    $data = 'm'; ?>

                                    <?php $i++ ?>

                                    <tr>
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
                            <td id="tableData">- </td>
                            </tr>

                            </tbody>
                            </table>
                            <div>

                            </div>



                        </div>

                    </div>

                    <div class="modal-footer">
                        {{-- <button id="added_product" name="added_product" id="added_product"
                            class="btn btn-primary">{{__('home.confirm')}}</button>
                        --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    </div>

                </div>


            </div>
        </div>

    </div>

<!-- delete -->
<div class="modal" id="modaldemo9">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.Retrieveall') }} </h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'EditInvoices')) }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <p>{{ __('home.Are_you_sure') }}</p><br>
                    <input type="hidden" name="id_delete" id="id_delete">
                    <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button id="deleteproduct" name="deleteproduct" class="btn btn-danger">{{ __('home.confirm') }}</button>
                </div>
        </div>
        </form>
    </div>
</div>

@endsection
@section('js')
<!-- Internal Data tables -->

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
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>
<script>
 document.addEventListener('keydown', (e) => {
    if (e.key === "F9") {
        $('#SearchProduct').modal().show();

    }})
     document.addEventListener('keydown', (e) => {
    searchtext = $('#product_code').val();

    if (e.key === "Enter") {
                searchtext = $('#product_code').val();
$('#searchaboutproduct').val(searchtext);
// document.getElementById("searchaboutproduct").focus();
$('#SearchProduct').modal().show();



    }})
    $('#SearchProduct').on('shown.bs.modal', function () {
    $('#searchaboutproduct').focus();
})
    function getproduct() {
        searchtext = $('#productcode').val();
        branchs_id = $('#branchs_id').val();

if(searchtext!=''){

        jQuery.ajax({
            url: " {{URL::to('detproductbycode')}}/" + searchtext + "/" + branchs_id,
            type: 'get',
            cache: false,
                            dataType: "json",


            success: function(data) {
                if (data != 0) {

                    name=data['product_name']
                     location1=data['Product_Location']
                    availablequantity=data['numberofpice']
                    price=data['purchasingـprice']
                    sale_price=data['sale_price']
                    code=data['id']
                  $('#product_name').val(name);
                 $('#thecostProduct').val(sale_price);

        $('#product_no').val(code);
        // $('#productcode').val(product_code);


                }
            },
            error: function() {

            }
        });
}

    }



    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();


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




        function doc_keyUp(e) {

    // this would test for whichever key is 40 (down arrow) and the ctrl key at the same time
    if (e.ctrlKey && e.code === 'ArrowDown') {
        // call your function to do the thing

        $('#SearchProduct').modal().show();

    }
}
// register the handler
document.addEventListener('keyup', doc_keyUp, false);




    $('#SearchProduct').on('shown.bs.modal', function () {

    $('#searchaboutproduct').focus();
    $('#searchaboutproduct').val($('#productcode').val());
           $('#searchaboutproduct').keyup()



})



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
</script>

<script>
    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var section_name = button.data('section_name')
        section_name = section_name.replaceAll("?", " ")

        console.log('lololoooo')
        console.log(id)
        console.log('lololoooo')
        var modal = $(this)
        modal.find('.modal-body #id_delete').val(id);
        modal.find('.modal-body #product_name').val(section_name);
        console.log('lololoooo')
    })
</script>
<script>
    function chooseProduct(code, name, price, sale_price, product_code, availablequantity, productcode) {
        $('#SearchProduct').modal().hide();


        var Product_Code = code
        var product_name = name
        var product_sale_pice = price
        $('#product_name').val(name);
        $('#thecostProduct').val(price);

        $('#product_no').val(code);
        $('#productcode').val(product_code);

    }
</script>

<script>
    $(document).ready(function() {
        document.getElementById("productcode").focus();
document.addEventListener('keydown', (e) => {
        if (e.key === "+") {
            event.preventDefault()


            event.preventDefault();

            var url = $(this).attr('data-action');
            let table = document.getElementById("example");


            var token_search = $("#token_search").val();

            console.log(" {{ URL::to('create_sendProduct') }}");

            var url = " {{ URL::to('create_sendProduct') }}";
            token_search = $('#token_search').val();
            productNo = $('#product_no').val();
            order_id = $('#showInvoiceNumber').val();
            product_name = $('#product_name').val();
            branchto = $('#branch').val();
            userto = $('#userfrom').val();
            quantity = $('#quentity').val();
            cost_price = $('#thecostProduct').val();

            if (product_name == '') {
                alert("{{ __('home.pleaseChooseProduct') }}")

            }  else if (quantity == '') {
                alert("{{ __('home.quantity') }}")

            } else {
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {

                        _token: token_search,
                        order_id: order_id,
                        product_name: product_name,
                        branch: branchto,
                        userfrom: userto,
                        quentity: quantity,
                        thecostProduct: cost_price,
                        product_no: productNo,
                        reciveInvoiceNumber:0


                    },


                    success: function(data) {
                        $('#showInvoiceNumber').val(data['orderData']['id']);
                        $('#sprint_invoice_number').val(data['orderData']['id']);
                        $('#productcode').val('');
                        $('#thecostProduct').val('');
                        $('#current_location').val('');
                        $('#product_name').val('');
                        $('#quentity').val('');

                        console.log(data)
                        console.log('-=--------------IDS--------------------')
                        console.log(data['orderItems'])
                        console.log(data['orderData']['id'])
                        if (data == []) {
                            alert("{{ __('home.stocknotAvailable') }}");
                        } else {

                            let table = document.getElementById("example");




                            var tableHeaderRowCount = 1;

                            var rowCount = table.rows.length;

                            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                table.deleteRow(tableHeaderRowCount);
                            }
                            count1 = 0;
                            added_value_total = 0;
                            total_sales = 0;

                            data['orderItems'].forEach(async (product) => {
                                product_code = product['product_code'],
                                    count1 = product['count'],
                                    product_name = product['productname']
                                quentity = product['quantity']
                                price = product['cost']
                                total = product['total']
                                console.log(price)
                                console.log(total)
                                console.log(quentity)
                                sales_id = product['details_items_no']
                                $('#order_id').val(product['details_items_no']);

                                product_name_update = product_name.replaceAll(" ", "?")
                                text2 =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                    "  ", "data-return_quentity=", quentity, '  ',
                                    '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                )




                                if (quentity > 0) {


                                    let row = table.insertRow(-1); // We are adding at the end

                                    let c1 = row.insertCell(0);
                                    let c2 = row.insertCell(1);
                                    let c3 = row.insertCell(2);
                                    let c4 = row.insertCell(3);
                                    let c5 = row.insertCell(4);
                                    let c6 = row.insertCell(5);
                                    let c7 = row.insertCell(6);


                                    // Add data to c1 and c2

                                    c1.innerText = count1
                                    c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                    c3.innerText = product_name
                                    c4.innerText = quentity
                                    c5.innerText = price
                                    c6.innerText = total
                                    c7.innerHTML = result2







                                }


                            });


                            //    update3/3/2023












                            $('#productNo').val("__('home.searchbyproductnumber')");

                        }



      $('#product_name').val('');
                            $('#product_price_after_dis').val(0);
                            $('#quantity').val(1);
                            $('#avaliable_quentity').val('');
                            $('#product_location').val('');
                            $('#product_price').val('');
                            $('#purchase_price').val('');
                                  $('#productcode').val('');

                    },
                    error: function(response) {
                        alert("{{ __('home.sorryerror') }}")

                    }

                });
            }



        }})
        $("#button_1").click(function(e) {
                  document.getElementById("button_1").style.display = "none";

            event.preventDefault();

            var url = $(this).attr('data-action');
            let table = document.getElementById("example");


            var token_search = $("#token_search").val();

            console.log(" {{ URL::to('create_sendProduct') }}");

            var url = " {{ URL::to('create_sendProduct') }}";
            token_search = $('#token_search').val();
            productNo = $('#product_no').val();
            order_id = $('#showInvoiceNumber').val();
            product_name = $('#product_name').val();
            branchto = $('#branch').val();
            userto = $('#userfrom').val();
            quantity = $('#quentity').val();
            cost_price = $('#thecostProduct').val();
          $('#productcode').val('');
                        $('#thecostProduct').val('');
                        $('#current_location').val('');
                        $('#product_name').val('');
                        $('#quentity').val('');
            if (product_name == '') {
                alert("{{ __('home.pleaseChooseProduct') }}")

            }  else if (quantity == '') {
                alert("{{ __('home.quantity') }}")

            } else {
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {

                        _token: token_search,
                        order_id: order_id,
                        product_name: product_name,
                        branch: branchto,
                        userfrom: userto,
                        quentity: quantity,
                        thecostProduct: cost_price,
                        product_no: productNo,
                        reciveInvoiceNumber:0

                    },


                    success: function(data) {
                        $('#showInvoiceNumber').val(data['orderData']['id']);
                        $('#sprint_invoice_number').val(data['orderData']['id']);
                        $('#productcode').val('');
                        $('#thecostProduct').val('');
                        $('#current_location').val('');
                        $('#product_name').val('');
                        $('#quentity').val('');

                        console.log(data)
                        console.log('-=--------------IDS--------------------')
                        console.log(data['orderItems'])
                        console.log(data['orderData']['id'])
                        if (data == []) {
                            alert("{{ __('home.stocknotAvailable') }}");
                        } else {

                            let table = document.getElementById("example");




                            var tableHeaderRowCount = 1;

                            var rowCount = table.rows.length;

                            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                table.deleteRow(tableHeaderRowCount);
                            }
                            count1 = 0;
                            added_value_total = 0;
                            total_sales = 0;

                            data['orderItems'].forEach(async (product) => {
                                product_code = product['product_code'],
                                    count1 = product['count'],
                                    product_name = product['productname']
                                quentity = product['quantity']
                                price = product['cost']
                                total = product['total']
                                console.log(price)
                                console.log(total)
                                console.log(quentity)
                                sales_id = product['details_items_no']
                                $('#order_id').val(product['details_items_no']);

                                product_name_update = product_name.replaceAll(" ", "?")
                                text2 =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                    "  ", "data-return_quentity=", quentity, '  ',
                                    '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                )




                                if (quentity > 0) {


                                    let row = table.insertRow(-1); // We are adding at the end

                                    let c1 = row.insertCell(0);
                                    let c2 = row.insertCell(1);
                                    let c3 = row.insertCell(2);
                                    let c4 = row.insertCell(3);
                                    let c5 = row.insertCell(4);
                                    let c6 = row.insertCell(5);
                                    let c7 = row.insertCell(6);


                                    // Add data to c1 and c2

                                    c1.innerText = count1
                                    c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                    c3.innerText = product_name
                                    c4.innerText = quentity
                                    c5.innerText = price
                                    c6.innerText = total
                                    c7.innerHTML = result2







                                }


                            });


                            //    update3/3/2023











                            $('#product_name').val('');
                            $('#product_price_after_dis').val(0);
                            $('#quantity').val(1);
                            $('#avaliable_quentity').val('');
                            $('#product_location').val('');
                            $('#product_price').val('');
                            $('#purchase_price').val('');
                            $('#productNo').val("__('home.searchbyproductnumber')");

                        }

      $('#product_name').val('');
      $('#productcode').val('');
                            $('#product_price_after_dis').val(0);
                            $('#quantity').val(1);
                            $('#avaliable_quentity').val('');
                            $('#product_location').val('');
                            $('#product_price').val('');
                            $('#purchase_price').val('');


                    },
                    error: function(response) {
                        console.log(response)
                        alert("{{ __('home.sorryerror') }}")

                    }
                });
            }

document.getElementById("button_1").style.display = "block";

        });




        $("#deleteproduct").click(function(e) {
            event.preventDefault();
            $('#modaldemo9').modal('hide');
            var url = $(this).attr('data-action');
            let table = document.getElementById("example");
            var token_search = $("#token_search").val();
            id = $('#id_delete').val();

            var url = " {{ URL::to('deleteproduct') }}" + "/" + id;
            token_search = $('#token_search').val();
            console.log('+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
            console.log(id);
            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                success: function(data) {
                    $('#showInvoiceNumber').val(data['orderData']['id']);

                    console.log(data)
                    console.log('-=--------------IDS--------------------')
                    console.log(data['orderItems'])
                    console.log(data['orderData']['id'])
                    if (data == []) {
                        alert("{{ __('home.stocknotAvailable') }}");
                    } else {

                        let table = document.getElementById("example");




                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        count1 = 0;
                        added_value_total = 0;
                        total_sales = 0;

                        data['orderItems'].forEach(async (product) => {
                            product_code = product['product_code'],
                                count1 = product['count'],
                                product_name = product['productname']
                            quentity = product['quantity']
                            price = product['cost']
                            total = product['total']
                            console.log(price)
                            console.log(total)
                            console.log(quentity)
                            sales_id = product['details_items_no']
                            $('#order_id').val(product['details_items_no']);

                            product_name_update = product_name.replaceAll(" ", "?")
                            text2 =
                                ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                            result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                "  ", "data-return_quentity=", quentity, '  ',
                                '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                            )




                            if (quentity > 0) {


                                let row = table.insertRow(-1); // We are adding at the end

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);
                                let c5 = row.insertCell(4);
                                let c6 = row.insertCell(5);
                                let c7 = row.insertCell(6);


                                // Add data to c1 and c2

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = quentity
                                c5.innerText = price
                                c6.innerText = total
                                c7.innerHTML = result2







                            }


                        });


                        //    update3/3/2023











                        $('#product_name').val('');
                        $('#product_price_after_dis').val(0);
                        $('#quantity').val(1);
                        $('#avaliable_quentity').val('');
                        $('#product_location').val('');
                        $('#product_price').val('');
                        $('#purchase_price').val('');
                        $('#productNo').val("__('home.searchbyproductnumber')");

                    }




                },
                error: function(response) {
                    alert("{{ __('home.sorryerror') }}")
                }
            });
        });


        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

    });
</script>






@endsection
