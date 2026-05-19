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
{{ __('home.stock') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->

    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">&nbsp;&nbsp;{{ __('home.stock') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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
    <input type="hidden" id="token_search" value="{{ csrf_token() }}">

    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">


                    {{ csrf_field() }}



                    <?php $i = 0; ?>
                    <div class="col-xl-12">
                        <div style="border-radius: 10px" class="card mg-b-20">
                            <div class="card-body p-5">

   <div class="main-parent-filter p-2 mb-3" style="border: 1px dashed #23395D; border-radius: 8px; background-color: #f8f9fa;">
    <div class="row align-items-end mx-0">
        
        <div class="col-lg-2 col-md-6 mb-2">
            <label for="searchaboutproduct" style="font-weight: bold; font-size: 12px; color: #23395D;" class="control-label parent-label mb-1"> 
                {{__('home.searchaboutproduct')}} 
            </label>
            <input dir="ltr" type="text" class="form-control form-control-sm parent-input" 
                   placeholder="{{ __('home.Search By Name or Product Number') }}" 
                   id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
        </div>

        <div class="col-lg-2 col-md-6 mb-2">
            <label for="product_group" class="control-label parent-label mb-1" style="font-size: 12px;">{{ __('home.groups') }}</label>
            <select name="product_group" id="product_group" class="form-control form-control-sm select2">
                <option value="">{{ __('home.all_groups') }}</option>
                @foreach (App\Models\products_group::get() as $section)
                    <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                @endforeach
            </select>
        </div>
 



    </div>
</div>
                            
                      
                                <br>
                                <div id="ajax_responce_serarchDiv" class="table our-table border mb-0 table-responsive text-center" >
                                    <table class="table text-md-nowrap text-center our-table" id="example2" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                                        <col style="width:5%">
                                        <col style="width:15%">
                                        <col style="width:20%">
                                        <col style="width:10%">
                                        <col style="width:10%">
                                        <col style="width:10%">
                                        <col style="width:15%">
                                        <col style="width:15%">


                                        <thead>
                                            <tr>
                                                <th style="font-size: 15px" class="border-bottom-0">#</th>
                                                <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                                                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                                                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
                                                <th style="font-size: 15px" class="border-bottom-0">{{__('home.productlocation')}}</th>
                                                <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                                <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                                                <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>



                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <?php $i = 0;
                                            ?>

                                            <?php $i++ ?>

                                            <tr>
                                                <td id="tableData"  dir=ltr>-</td>
                                                <td id="tableData"  dir=ltr>-</td>
                                                <td id="tableData"  data-target="product_name">-</td>
                                                <td id="tableData"  data-target="product_name">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                                <td id="tableData"  data-target="numberofpice">-</td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                    
                                    <div>

                                    </div>



                                </div>

                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->
</div>

    <div class="modal p-3" id="delete_quotation">
        <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.alert') }} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label"> {{ __('home.Are_you_sure_delete') }}</label>
                        </div>


                    </div>

                        <input type="text" hidden class="form-control parent-input" name="delete_id" id="delete_id" >

                    <br>
                     <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="delete_quotation_function" name="delete_quotation_function" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade product-selection" id="operation_product" name="main_product" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="table-responsive" id="ajax_responce_operation_product_Div">


                </div>
            </div>

            <div class="modal-footer">
                {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button>
                --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
            </div>

        </div>


    </div>
</div>

</div>
@endsection
@section('js')
<script>
    // 1. تعريف الـ Base URL من الإعدادات ليكون متاحاً في السكريبت
    const API_BASE_URL = "{{ config('app.api_url') }}";

    function searchaboutproductfunction() {
        let searchtext = $('#searchaboutproduct').val();
        let token_search = $("#token_search").val();
        let selectclientid = $("#branchs_id").val();

        jQuery.ajax({
            // الاعتماد على المتغير الأساسي
            url: API_BASE_URL + "/products/search-all-paginate",
            type: 'post',
            cache: false,
            dataType: 'html',
            data: {
                "_token": token_search,
                "searchtext": searchtext,
                "branchs_id": selectclientid,
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
        });
    }

    $(document).on('click', '#ajax_pagination_in_search a', function(e) {
        e.preventDefault();
        let url = $(this).attr("href"); // هنا الرابط يأتي كاملاً من الباجينيشن
        let search_by_text = $("#searchaboutproduct").val();
        let token_search = $("#token_search").val();
        let selectclientid = $("#branchs_id").val();

        jQuery.ajax({
            url: url,
            type: 'post',
            cache: false,
            dataType: 'html',
            data: {
                "_token": token_search,
                "searchtext": search_by_text,
                "branchs_id": selectclientid,
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            }
        });
    });

    $(document).ready(function() {
        // التحميل الأول للبيانات عند فتح الصفحة
        let token_search = $("#token_search").val();
        let selectclientid = $("#branchs_id").val();

        jQuery.ajax({
            url: API_BASE_URL + "/products/search-all-paginate",
            type: 'post',
            cache: false,
            dataType: 'html',
            data: {
                "_token": token_search,
                "searchtext": '0',
                "branchs_id": selectclientid,
            },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
        });

        $('select[name="product_group"]').on('change', function() {
            let group_id = $(this).val();
            let token_search = $("#token_search").val();
            let search_by_text = $("#searchaboutproduct").val(); // تم تصحيح الـ ID هنا ليتطابق مع حقل البحث

            if (group_id) {
                $.ajax({
                    url: API_BASE_URL + "/products/group-ajax",
                    type: 'post',
                    cache: false,
                    dataType: 'html',
                    data: {
                        "_token": token_search,
                        "group_id": group_id,
                        "searchtext": search_by_text,
                    },
                    success: function(products) {
                        $("#ajax_responce_serarchDiv").html(products);
                    },
                });
            }
        });
    });
</script>
@endsection