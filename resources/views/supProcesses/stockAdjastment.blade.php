@extends('layouts.master')
@section('css')
<style>
    form {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }

    .radio-label {
        display: flex;
        cursor: pointer;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        margin-bottom: 0.375em;
        /* Accessible outline */
        /* Remove comment to use */
        /*
  	&:focus-within {
  			outline: .125em solid $primary-color;
  	}
  */
    }

    .radio-label input {
        position: absolute;
        left: -9999px;
    }

    .radio-label input:checked+span {
        background-color: #d6d6e5;
    }

    .radio-label input:checked+span:before {
        box-shadow: inset 0 0 0 0.4375em #00005c;
    }

    .radio-label span {
        display: flex;
        align-items: center;
        padding: 0.375em 0.75em 0.375em 0.375em;
        border-radius: 99em;
        transition: 0.25s ease;
    }

    .radio-label span:hover {
        background-color: #d6d6e5;
    }

    .radio-label span:before {
        display: flex;
        flex-shrink: 0;
        content: "";
        background-color: #fff;
        width: 1.5em;
        height: 1.5em;
        border-radius: 50%;
        margin-right: 0.375em;
        transition: 0.25s ease;
        box-shadow: inset 0 0 0 0.125em #00005c;
    }
</style>
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
{{ __('supprocesses.stockadjustment') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('supprocesses.stockadjustment') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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
    @if (session()->has('productupdated'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>

        <strong>{{ session()->get('productupdated') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">



            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                                     <div class="col-lg-2 mg-t-20 mg-lg-t-0" id="type">
                                <p style="font-size: 1.1vw;" class="radio-label parent-label mb-3"> {{ __('users.branch') }} </p>
                                <br>
                                <select class="form-control select2" style="width:100%" name="branchs_id" id="branchs_id">

                                    <option value="{{ Auth()->user()->branch->id }}"> {{ Auth()->user()->branch->name }}
                                    </option>
@if(Auth()->user()->id!=22&&Auth()->user()->id!=23&&Auth()->user()->id!=26)
                                    @foreach (App\Models\branchs::get() as $section)
                                    @if(Auth()->user()->branch->id!=$section->id)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </div><!-- col-4 -->
                            <br>
                <div class="row">
                     
                    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                        <a style="background-color: #FBA10F" class="modal-effect btn btn-md px-0 btn-info" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">
                            <i style="width: 200px;" class="las la-search"> {{ __('home.chooose product') }}</i>
                        </a>
                    </div>
                </div>

                <br>
                <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                    action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/stockAdjastment') }}"
                    method="post">
                     {{ csrf_field() }}

                        <div class="card pt-4">
                            <div class="card-body pt-3">
                                <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                                <div class="table-responsive mg-t-20">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr class="table-info">
                                                <th>{{ __('home.productNo') }}</th>
                                                <th>{{ __('home.productname') }}</th>
                                                <th>نوع التعديل</th> <th>{{ __('supprocesses.current_quantity') }}</th>
                                                <th>الكمية المعدلة</th>
                                                <th>{{ __('home.notesClient') }}</th>
                                                <th>حذف</th>
                                            </tr>
                                        </thead>
                                        <tbody id="adjustment-table-body">
                                            </tbody>
                                    </table>
                                </div>

                                <br>

                                <div class="d-flex justify-content-center">
                                    <button style="background-color: #419BB2; font-size:17px" type="submit" class="btn btn-success">
                                        {{ __('roles.update') }}
                                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path fill="white" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>

</div>
        </div>
    </div>
</div>

@if (isset($itemsRequest))
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">تفاصيل الحركة</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive hoverable-table">
                  <table class="table table-hover" id="example1" data-page-length='50' style="text-align: center;">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                <th class="border-bottom-0">{{ __('home.product') }}</th>
                                <th class="border-bottom-0">نوع التعديل</th> <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                            </tr>
                        </thead>
                       <tbody id="adjustment-table-body">
                                @foreach ($itemsRequest as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->productData->Product_Code }}</td>
                                    <td>{{ $product->productData->product_name }}</td>
                                    <td>
                                        <select class="form-control type-selector">
                                            <option value="inc">{{ __('home.increasequantity') }} (+)</option>
                                            <option value="dec">{{ __('home.decreasequentity') }} (-)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="productNo[]" value="{{ $product->product_id }}">

                                        <input type="number" name="productincrease[]" class="form-control inc-input" value="0">
                                        <input type="number" name="productdecrease[]" class="form-control dec-input" value="0" style="display:none;">
                                    </td>
                                    <td>
                                        <input type="text" name="note[]" class="form-control">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <a class="btn btn-success" href="{{ url('/printOrderPriceFromSupplier/' . $itemsRequest[0]->order_id) }}">
                            {{ __('home.print') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<br>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!--search  -->

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

    <!-- main-content closed -->
</div>
@endsection
@section('js')

<script>
    function convertToNumbersalePrice() {
        var input = document.getElementById("sale_price");
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
    function convertToNewQuantity() {
        var input = document.getElementById("newquentity");
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
    document.addEventListener('keydown', (e) => {
        if (e.key === "F9") {
            $('#SearchProduct').modal().show();

        }
    })
    document.addEventListener('keydown', (e) => {
        searchtext = $('#product_code').val();

        if (e.key === "Enter") {
            searchtext = $('#product_code').val();
            $('#searchaboutproduct').val(searchtext);
            // document.getElementById("searchaboutproduct").focus();
            $('#SearchProduct').modal().show();



        }
    })
    $('#SearchProduct').on('shown.bs.modal', function() {
        $('#searchaboutproduct').focus();
    })
</script>
<script>
    function searchaboutproductfunction() {
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
                    "searchtext": searchtext,
                    "branchs_id": branchs_id,
                },
                  success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
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
           searchtext = '1';
        branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");

        jQuery.ajax({
            url:  "{{ URL::to('ChooseProductpaginatenewupdate')}}",
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
            error: function(r) {
            }
        });
    })
</script>

<script>
     


    // لاحظ هنا زودنا عدد المتغيرات عشان تطابق الـ 11 اللي مبعوثين من الـ PHP بالظبط
       function chooseProduct(code, name, price, sale_price, productcode, availablequantity, productcode2) {
   



        // 1. إغلاق المودال
        $('#SearchProduct').modal('hide');

        // 2. منع التكرار باستخدام الـ id (المتغير الأول)
        if ($("#row_" + code).length > 0) {
            alert("هذا المنتج مضاف بالفعل في الجدول");
            return;
        }

        // 3. بناء السطر بالبيانات الصحيحة
        // رقم المنتج = product_code (المتغير رقم 9)
        // الكمية الحالية = numberofpice (المتغير رقم 8)
        var newRow = `
            <tr id="row_${code}">
                <td>
                    ${productcode}
                    <input type="hidden" name="productNo[]" value="${code}">
                </td>
                <td>${name}</td>
                <td>
                    <select class="form-control row-type-selector" style="width: 110px;">
                        <option value="inc">زيادة (+)</option>
                        <option value="dec">نقصان (-)</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control text-center" value="${availablequantity}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control amount-input" placeholder="الكمية" required min="1">

                    <input type="hidden" name="productincrease[]" class="inc-val" value="0">
                    <input type="hidden" name="productdecrease[]" class="dec-val" value="0">
                </td>
                <td>
                    <input type="text" name="note[]" class="form-control" placeholder="ملاحظة">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove()">
                        <i class="fa fa-trash"></i> حذف
                    </button>
                </td>
            </tr>
        `;

        $('#adjustment-table-body').append(newRow);
    }

    // السكريبت اللي بيوزع الأرقام للكنترولر (مهم جداً يفضل موجود)
    $(document).on('change keyup', '.row-type-selector, .amount-input', function() {
        let row = $(this).closest('tr');
        let type = row.find('.row-type-selector').val();
        let amount = row.find('.amount-input').val() || 0;

        if (type === 'inc') {
            row.find('.inc-val').val(amount);
            row.find('.dec-val').val(0);
        } else {
            row.find('.inc-val').val(0);
            row.find('.dec-val').val(amount);
        }
    });
</script>



<script>


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
                    $('#quentity').val(data['numberofpice']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
    $('select[name="productname"]').on('change', function() {
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
                    $('#quentity').val(data['numberofpice']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
</script>




<script>
    $(document).ready(function() {

        $('#productdecrease').val(0);
        $('#productincrease').val(0);

            $(function() {
                var timeout = 4000; // in miliseconds (3*1000)
                $('.alert').delay(timeout).fadeOut(500);
            });


        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });
        $('#productdecrease').hide();

        $('#productdecreaselabel').hide();

        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {

                $('#productincreaselabel').show();
                $('#productincrease').show();
                $('#productdecreaselabel').hide();
                $('#productdecrease').hide();
                $('#productdecrease').val(0);
                $('#lnWrapper').show();

            } else {

                $('#productdecreaselabel').show();
                $('#productdecrease').show();
                $('#productincrease').hide();
                $('#productincrease').val(0);
                $('#productincreaselabel').hide();
                $('#lnWrapper').hide();

            }
        });
    });
</script>



@endsection
