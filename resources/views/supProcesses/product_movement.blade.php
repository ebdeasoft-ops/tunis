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
{{__('supprocesses.product_movement')}}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{__('supprocesses.product_movement')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/'. ($page = 'product_movement')) }}" method="POST" enctype="multipart/form-data"
 role="search" autocomplete="off">
                        {{ csrf_field() }}

<div class="card-body box-shadow-0">
    <div class="row row-sm mb-4 bg-light p-3 br-5">
        <div class="col-lg-6">
            <p class="radio-label parent-label mb-2" style="font-size: 1.1rem; font-weight: bold;">
                <i class="fas fa-store-alt"></i> {{ __('users.branch') }}
            </p>
            <select class="form-control select2" style="width:100%" name="branchs_id" id="branchs_id">
                <option value="{{ Auth()->user()->branch->id }}"> {{ Auth()->user()->branch->name }} </option>
                @foreach (App\Models\branchs::get() as $section)
                    @if(Auth()->user()->branch->id != $section->id)
                        <option value="{{ $section->id }}"> {{ $section->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-lg-6 d-flex align-items-end mt-3 mt-lg-0">
            <a style="background-color: #FF4F1F; border: none;" class="modal-effect btn btn-info btn-block py-2" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct">
                <i class="las la-search"></i> {{ __('home.chooose product') }}
            </a>
        </div>
    </div>

    <hr>

    <h6 class="main-content-label mb-3 mt-4 text-primary"><i class="fas fa-barcode"></i> {{ __('home.basic_product_data') }}</h6>
    <div class="row row-sm mb-3">
        <div class="col-lg-4" id="type">
            <label class="parent-label"> {{ __('home.productNo') }} </label>
            <input type="text" class="form-control parent-input" id="productcode" name="productcode" dir="ltr">
            <input hidden name="productname" id="productname">
        </div>
        <div class="col-lg-4">
            <label class="parent-label"> {{__('home.productname')}} </label>
            <input type="text" class="form-control" id="productnameshow" name="productnameshow" required>
        </div>
        <div class="col-lg-4">
            <label class="parent-label"> {{ __('home.refnumber') }} </label>
            <input type="text" class="form-control parent-input" id="refnumber" name="refnumber">
        </div>
    </div>
                @can('System setting')

    <h6 class="main-content-label mb-3 mt-4 text-primary"><i class="fas fa-money-bill-wave"></i> {{ __('home.prices_and_category') }}</h6>
    <div class="row row-sm mb-3">
        <div class="col-lg-4">
            <label class="parent-label"> {{ __('home.purachesepice') }} </label>
            <input type="text" class="form-control parent-input" id="purachesepice" name="purachesepice" required>
        </div>
        <div class="col-lg-4">
            <label class="parent-label"> {{ __('home.Wholesale_price') }} </label>
            <input type="text" class="form-control parent-input" id="Wholesale_price" name="Wholesale_price" required>
        </div>
        <div class="col-lg-4">
            <label class="parent-label"> {{ __('home.sellingproduct without tax') }} </label>
            <input type="text" class="form-control parent-input" id="product_price" name="product_price" required>
        </div>
    </div>
                @endcan

    <div class="row row-sm mb-3">
        <div class="col-lg-6">
            <label class="parent-label">{{ __('home.groups') }}</label>
            <select name="product_group" id="product_group" class="form-control select2">
                @foreach (App\Models\products_group::get() as $section)
                    <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label class="parent-label">{{ __('home.MAINproduct') }}</label>
            <select name="MAINproduct" id="MAINproduct" class="form-control select2">
                <option value="no"> - </option>
            </select>
        </div>
    </div>

    <hr>

    <h6 class="main-content-label mb-3 mt-4 text-primary"><i class="fas fa-map-marker-alt"></i> {{ __('home.storage_and_notes') }}</h6>
    <div class="row row-sm mb-3">
        <div class="col-lg-4">
            <label class="parent-label"> {{__('supprocesses.current_location')}} </label>
            <input type="text" class="form-control bg-light" id="current_location" name="current_location" readonly>
        </div>
        <div class="col-lg-4">
            <label class="parent-label"> {{__('supprocesses.new_location')}} </label>
            <input type="text" class="form-control parent-input" id="new_location" name="new_location" required>
        </div>
        <div class="col-lg-4">
            <label class="parent-label"> {{ __('supprocesses.product_notes') }} </label>
            <input type="text" class="form-control parent-input" id="product_notes" name="product_notes" required value='-'>
        </div>
    </div>

    <div class="row row-sm mt-4">
        <div class="col-md-12">
            <div class="form-group p-3 br-5" style="border: dashed 2px #ccc; background-color: #f9f9f9;">
                <label class="parent-label"><i class="fas fa-image"></i> {{__('home.photo')}}</label>
                <input autocomplete="off" onchange="readURL(this)" type="file" id="Item_img" name="Item_img" class="form-control-file">
                @error('active')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <input type="number" id="product_no" name="product_no" hidden>
    <input hidden type="number" id="quentity" name="quentity">
    <input hidden id="user_id" name="user_id" value="{{Auth()->user()->discount_allow_limit}}">
</div>
               </div>
                        </div>                        <br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success print-style"> {{__('roles.update')}} </button>
                        </div>


                        <br>



                </div>
            </div>



            <br>





            </table>

        </div>
    </div>


</div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
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

<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>


<script>

 document.addEventListener('keydown', (e) => {
    if (e.key === "F9") {
        $('#SearchProduct').modal().show();

    }})
     document.addEventListener('keydown', (e) => {
    searchtext = $('#product_code').val();

if (e.ctrlKey && e.keyCode == '38') {
                searchtext = $('#product_code').val();
$('#searchaboutproduct').val(searchtext);
// document.getElementById("searchaboutproduct").focus();
$('#SearchProduct').modal().show();



    }})
    $('#SearchProduct').on('shown.bs.modal', function () {
    $('#searchaboutproduct').focus();
})

    function getproduct() {}



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
</script>

<script>
    function chooseProduct(code, name, price, sale_price, product_location, availablequantity, productcode,MAINproductname,maincode) {
        $('#SearchProduct').modal().hide();
 console.log('=======----=========')
        console.log(code)
        console.log(name)
        console.log(sale_price)
        console.log(price)
        console.log(product_location)
        console.log(availablequantity)
        console.log(productcode)
        console.log(MAINproductname)
        console.log(maincode)
    $('#MAINproduct')
      .append($('<option selected>', { value :productcode  })
      .text(MAINproductname));
         searchtext =product_location;
         console.log(" {{URL::to('getproduct')}}/" + code)
    jQuery.ajax({
            url: " {{URL::to('getproduct')}}/" + code ,
            type: 'get',
            cache: false,
            dataType: "json",
            success: function(data) {
                    $('#product_group').val(data['product_group']).change();
        $('#uploadedimg').attr('src','https://demoo.ebdeaclients.online/assets/admin/uploads'+'/'+data['photo'])

                    refnumber=data['refnumber']
                    numberofpice=data['numberofpice']

           $('#refnumber').val(refnumber);
           $('#quentity').val(numberofpice);
        $('#Wholesale_price').val(data['Wholesale_price']);
        $('#product_notes').val(data['notes']);




        }

        }
        )
        var Product_Code = code
        var product_name = name
        var product_sale_pice = price
        $('#productnameshow').val(name);
        $('#current_location').val(availablequantity);
        $('#new_location').val(availablequantity);
        $('#product_price').val(sale_price);

        $('#product_no').val(code);
        $('#productcode').val(product_location);
        $('#purachesepice').val(price);


    }
</script>

<script>
     document.addEventListener('keydown', (e) => {
    searchtext = $('#productcode').val();

    if (e.key === "Enter") {
$('#searchaboutproduct').val(searchtext);
// document.getElementById("searchaboutproduct").focus();
$('#SearchProduct').modal().show();




    }})
        $('#SearchProduct').on('shown.bs.modal', function () {
  $('#searchaboutproduct').focus();
    $('#searchaboutproduct').val($('#productcode').val());
           $('#searchaboutproduct').keyup()
})
    $(document).ready(function() {
    $('#productcode').focus();
         user_id=$('#user_id').val();
if(user_id==1)
{

}
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

    });
</script>






@endsection
