@extends('layouts.master')
@section('css')

<!-- Internal Data table css -->

<!--Internal  Datatable js -->
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<style>
/* زر الحفظ الأساسي */
    .btn-main-action {
        background-color: #419BB2 !important;
        color: white !important;
        border: none;
        border-radius: 50px; /* شكل بيضاوي عصري */
        transition: all 0.3s ease;
    }

    .btn-main-action:hover {
        background-color: #358094 !important;
        transform: scale(1.05); /* تكبير بسيط عند الحفظ */
        box-shadow: 0 6px 15px rgba(65, 155, 178, 0.3) !important;
    }

    /* زر التعليق */
    .btn-outline-secondary {
        border: 2px solid #ced4da;
        background: white;
        color: #6c757d;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #6c757d;
        color: #343a40;
    }

    .svg-white {
        fill: white;
    }

    .tx-18 {
        font-size: 18px;
    }

    .shadow-md {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }

    .gap-3 {
        gap: 1rem;
    }
    /* ألوان مخصصة لتناسب هويتك */
    .btn-primary-custom {
        background-color: #419BB2 !important;
        border: none;
        color: white;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-primary-custom:hover {
        background-color: #358094 !important;
        transform: translateY(-2px);
    }

    .btn-secondary-custom {
        background-color: #2c3e50 !important; /* لون داكن للـ PDF لتمييزه */
        border: none;
        color: white;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-secondary-custom:hover {
        background-color: #1a252f !important;
        transform: translateY(-2px);
    }

    .gap-3 { gap: 1rem !important; }

    /* تنسيق الحاوية */
    .bg-light-custom {
        background-color: #f8f9fa;
        border-radius: 12px;
        border: 1px dashed #419BB2; /* إطار خفيف لتمييز منطقة الأكشن */
    }
.addProductBtn:hover {
        background-color: #358094 !important; /* لون أغمق قليلاً عند التمرير */
        transform: translateY(-2px); /* حركة بسيطة للأعلى */
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
    }
    .addProductBtn:active {
        transform: translateY(0);
    }
        /* تحسين شكل الـ select2 ليتناسب مع الـ input group */
    .select2-container--default .select2-selection--single {
        border-radius: 5px !important;
        height: 38px !important;
        border: 1px solid #e1e5ef !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 35px !important;
        padding-right: 15px !important;
    }
    /* =========================
   Modern UI + Inner Borders
   ========================= */

    /* الخط العام */
    body,
    table,
    td,
    th,
    input,
    button,
    .form-control {
        font-family: "Tajawal", sans-serif;
        font-weight: 600 !important;
        color: #000 !important;
    }

    /* جدول بحدود داخلية */
    table {
        border-collapse: collapse !important;
        width: 100%;
    }

    table thead th {
        background: #f1f3f9;
        padding: 12px;
        font-weight: 700 !important;
        color: #000 !important;
        text-align: center;
        border: 1px solid #d6d6d6 !important;
        /* حدود داخلية */
    }

    /* الصفوف */
    #productsTableBody tr {
        background: #ffffff;
        transition: background 0.2s ease;
    }

    #productsTableBody tr:hover {
        background: #f7f9ff;
    }

    /* حدود داخلية للـ <td> */
    #productsTableBody td {
        border: 1px solid #e0e0e0 !important;
        padding: 10px !important;
        vertical-align: middle;
    }

    /* الحقول */
    .form-control {
        border-radius: 6px !important;
        border: 1px solid #c8ccd4 !important;
        transition: .2s;
    }

    .form-control:focus {
        border-color: #478bff !important;
        box-shadow: 0 0 0 2px rgba(71, 139, 255, 0.25);
    }

    /* أزرار اختيار المنتج */
    .btn-info,
    .btn-primary {
        background: linear-gradient(135deg, #478bff, #357bff) !important;
        border: none !important;
        color: #fff !important;
        border-radius: 6px !important;
        font-weight: 700 !important;
    }

    .btn-info:hover,
    .btn-primary:hover {
        background: linear-gradient(135deg, #3f79ff, #296bff) !important;
        transform: translateY(-1px);
    }

    /* زر الحذف */
    .btn-danger {
        background: linear-gradient(135deg, #ff5b5b, #ff3b3b) !important;
        border: none !important;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #ff4343, #ff2020) !important;
        transform: scale(1.05);
    }

    /* زر إضافة صف */
    #addProductBtn,
    .btn-add-product {
        background: linear-gradient(135deg, #1a73e8, #0d5bd8) !important;
        padding: 8px 20px;
        border-radius: 8px !important;
        color: #fff !important;
        font-weight: 700 !important;
        border: none;
    }

    #addProductBtn:hover {
        transform: translateY(-2px);
    }

    /* المودال */
    .modal-content {
        border-radius: 12px !important;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: #f1f3f9;
        border-bottom: 1px solid #ddd !important;
    }

    .modal-title {
        color: #000 !important;
        font-weight: 700 !important;
    }

    .modal-body {
        background: #fff;
    }

    /* جدول البحث داخل المودال */
    #productsTable th,
    #productsTable td {
        border: 1px solid #dcdcdc !important;
    }

    #productsTable tr:hover {
        background: #eef3ff !important;
    }

    /* تحسين الـ div */
    td .d-flex {
        align-items: center;
    }
</style>
@section('title')
    {{ __('home.Purchase_order_of_resources') }}
    @stop
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="main-parent">
        <div class="breadcrumb-header justify-content-between"
            style="display: flex; align-items: center; background-color: #f8f9fa; padding: 10px 20px; border-bottom: 2px solid #23395D; margin-bottom: 20px; width: 100%; border-radius: 4px 4px 0 0;">

            <div class="my-auto">
                <h4 class="content-title mb-0" style="color: #23395D; font-weight: bold; font-size: 18px; margin: 0;">
                    {{ __('home.Purchase_order_of_resources') }}
                </h4>
            </div>

            <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">

                <div style="min-width: 150px;">
                    <select class="form-control select2" name="numbershowstatus" id="numbershowstatus" required>
                        <option value="1">{{__('home.shownumberselect')}}</option>
                        <option value="0">{{__('home.notshow')}}</option>
                    </select>
                </div>


                <button class="modal-effect btn btn-sm text-white"
                    style="background-color: #23395D; border: 1px solid #1a2a44; border-radius: 3px; padding: 6px 14px; font-size: 11px; white-space: nowrap;"
                    data-toggle="modal" href="#createproduct">
                    <i class="fa fa-plus" style="margin-left: 5px;"></i> {{ __('supprocesses.addproduct') }}
                </button>


            </div>
        </div>
    </div>







@endsection
@section('content')


    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">


                    <?php
    $avtSaleRate = App\Models\Avt::find(1);
    $avtSaleRate = $avtSaleRate->AVT;
                $system_settings = App\Models\system_setting::find(1);

        $discound_allow = $system_settings->discount_on_invoice;
                                                                            ?>
<input hidden=true class="form-control" id="discound_allow" name="discound_allow" value="{{ $discound_allow/100 }}">

                    <form enctype="multipart/form-data" method="POST" role="search" name="form-name" id='formdata'
                        autocomplete="off">
                        {{ csrf_field() }}



                        <div style="border-radius: 10px" class="card p-3 my-3">





                            <?php $i = 0; ?>


                            <table class="table-responsive table table-bordered">
                                <thead class="table-dark">
                                    <col style="width:0.5%">
                                    <col style="width:2%">
                                    <col style="width:16%">
                                    <col style="width:20%">
                                    <col style="width:9%">
                                    <col style="width:9%">
                                    <col style="width:9%">
                                    <col style="width:9%">
                                    <col style="width:9%">
                                    <col style="width:9%">
                                    <col style="width:10%">
                                    <thead>
                                        <tr>
                                            <th>- </th>

                                            <th> # </th>
                                            <th>{{ __('home.productNo') }} </th>
                                            <th>{{ __('home.product') }}</th>
                                            <th>{{ __('home.saleperpice') }}</th>
                                            <th> {{ __('home.productprice') }} </th>
                                            <th>{{ __('home.quantity') }}</th>
                                            <th>{{ __('home.price') }}</th>
                                            <th>{{ __('home.discount') }}</th>
                                            <th>{{ __('home.addedValue') }}</th>
                                            <th>{{ __('home.total') }}</th>
                                            <th>{{ __('home.operations') }}</th>
                                        </tr>
                                    </thead>
                                <tbody id="productsTableBody">

                                </tbody>
                            </table>

                         <button type="button" class="btn addProductBtn shadow-sm text-white"
        style="background-color: #419BB2; border-radius: 8px; padding: 8px 20px; border: none; transition: all 0.3s ease;"
        onclick="addRow()">
    <i class="fas fa-plus-circle me-1"></i> {{ __('supprocesses.addproduct') }}
</button>
                            <br>
                            <div class="row mt-3 p-3"
                                style="background-color: #fcfcfc; border: 1px solid #e1e1e1; border-radius: 8px; justify-content: space-between; align-items: flex-end;">

                                <div class="col-md-2"><label class="form-label">{{ __('home.discound_on_invoice') }}
                                    </label><input type="text" id="discound_on_invoice" name="discound_on_invoice"
                                   readonly     oninput='calculateTotalDiscount()' class="form-control"></div>

                                <div class="col-lg-4">
                                    <label for="last_supplier_cost" class="control-label parent-label"
                                        style="color: #23395D; font-weight: bold; font-size: 13px;">
                                        <i class="fa fa-tag ml-1"></i> {{ __('home.last_quotation_price') }}
                                    </label>
                                    <select class="form-control parent-input" name="last_supplier_cost"
                                        id="last_supplier_cost"
                                        style="background-color: #d4edda; border: 1px solid #28a745; border-radius: 5px; height: 40px; font-weight: 500;">
                                    </select>
                                </div>
                            </div>
  <div class="row mt-4">
    <div class="col-md-3">
        <div class="card bd-0 bg-light p-3 text-center shadow-sm">
            <label class="form-label fw-bold text-muted mb-1">{{ __('home.the amount') }}</label>
            <input readonly type="text" id="totalSum" name="totalSum"
                   class="form-control text-center border-0 bg-transparent fw-bold tx-20"
                   style="color: #444;" value="0.00">
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bd-0 bg-danger-light p-3 text-center shadow-sm">
            <label class="form-label fw-bold text-danger mb-1">{{ __('home.discount') }}</label>
            <input type="text" id="totaldiscound" name="totaldiscound" readonly
                   class="form-control text-center border-0 bg-transparent fw-bold text-danger tx-20"
                   value="0.00">
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bd-0 bg-primary-light p-3 text-center shadow-sm">
            <label class="form-label fw-bold text-primary mb-1">{{ __('home.addedValue') }}</label>
            <input type="text" id="totalTax" name="totalTax" readonly
                   class="form-control text-center border-0 bg-transparent fw-bold text-primary tx-20"
                   value="0.00">
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bd-0 bg-success p-3 text-center shadow-sm border-2">
            <label class="form-label fw-bold text-white mb-1" style="font-size: 1.1rem;">{{ __('home.total') }}</label>
            <input type="text" id="grandTotal" name="grandTotal" readonly
                   class="form-control text-center border-0 bg-transparent fw-bold text-white tx-24"
                   value="0.00">
        </div>
    </div>
</div>



                            <br>

                            <input type="text" class="form-control " name="show_invoice_number_update"
                                id="show_invoice_number_update" value=0 title=" رقم الفاتورة " hidden>



             <div class="row row-sm align-items-end">
    <div class="col-lg-4 mg-t-10">
        <label for="clientnamesearch" class="form-label fw-bold text-muted">
            <i class="fas fa-user-tag me-1 text-primary"></i> {{ __('home.chooseclient') }}
        </label>
        <select class="form-control select2 shadow-sm" name="clientnamesearch" id="clientnamesearch">
            @foreach (App\Models\supllier::get() as $customer)
                <option value="{{ $customer->id }}">
                    {{-- عرض الاسم والرقم الضريبي بشكل منسق --}}
                        {{ $customer->name }}

                </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-8 mg-t-10">
        <label for="notes" class="form-label fw-bold text-muted">
            <i class="fas fa-sticky-note me-1 text-warning"></i> {{ __('home.notesClient') }}
        </label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-pen-nib text-muted tx-12"></i>
            </span>
            <input autocomplete="off" type="text"
                   class="form-control parent-input border-start-0 ps-0"
                   id="notes" name="notes"
                   onchange="makenoteoninvoice()"
                   value="-"
                   placeholder="{{ __('home.enter_notes_here') }}">
        </div>
    </div>
</div>



                                <input type="hidden" id="token_search" value="{{ csrf_token() }}">
                                <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                                    <input type="text" hidden=true class="form-control" id="invoice_number"
                                        name="invoice_number" value="{{ $data['invoice_id'] ?? '' }}">

                                    <input type="text" hidden=true class="form-control" id="saveinvice" name="saveinvice"
                                        value=0>

                                    <input hidden=true class="form-control" id="branchs_id" name="branchs_id"
                                        value="{{Auth()->user()->branchs_id}}">
                                    <input hidden=true class="form-control" id="user_id" name="user_id"
                                        value="{{Auth()->user()->discount_allow_limit}}">
                                    <?php

    $rate_discount = App\Models\system_setting::find(1);
    $rate_system = $rate_discount->discount_on_invoice;
                                                                                ?>
                                    <input hidden=true class="form-control" id="rate_system" name="rate_system"
                                        value="{{$rate_system}}">
                                    <input hidden=true class="form-control" id="shownumberproduct" name="shownumberproduct"
                                        value="1">
                                <br>
                                <br>
    <div class="d-flex justify-content-center">

    <button type="submit" id="saveInvoice" name="saveInvoice"
            class="btn btn-main-action shadow-md px-5 py-2 d-flex align-items-center">
        <span class="fw-bold tx-18">                            {{ __('supprocesses.save_data') }}
</span>
        <svg class="ms-2 svg-white" viewBox="0 0 24 24" style="width: 22px; height: 22px;">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
        </svg>
    </button>



</div>
         </form>

                </div>

                <input type="text" class="form-control " name="show_invoice_number" id="show_invoice_number"
                    title=" رقم الفاتورة " hidden>

                <center>
                    <div class=" justify-content-center" id="printdiv">
<form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . 'printProductToSupllierOrder') }}"
      method="POST" role="search" autocomplete="off">
    @csrf

    <div class="card bd-0 shadow-none bg-light-custom p-3">
        <div class="row row-sm justify-content-center text-center">

            <input type="hidden" name="OrderNoprint" id="OrderNoprint">

            <div class="col-md-12 d-flex flex-wrap justify-content-center gap-3">

                <button type="submit" id="print_qoute" class="btn btn-primary-custom shadow-sm d-flex align-items-center px-4 py-2">
                    <span class="fw-bold">{{ __('home.print') }}</span>
                    <svg class="ms-2" style="width: 20px; height: 20px; fill: white;" viewBox="0 0 24 24">
                        <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                    </svg>
                </button>


            </div>
        </div>
    </div>
</form>


        </div>



        </center>

    </div>

    </div>

    </div>









    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    </div>
    <!--search  -->


    <div class="modal fade product-selection"
        style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" id="massagesave"
        name="massagesave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
        <div class="modal-dialog modal-xl"
            style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" role="document">
            <div class="modal-content">

                <div class="modal-body" style="justify-content: center;">


                    <center><img style="width:250px;height:250px;" class="custom_img"
                            src="{{ asset('assets/admin/uploads/done.png') }}">

                    </center>




                </div>


            </div>


        </div>
    </div>

    </div>
    <input hidden=true class="form-control" id="phone" name="phone">



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

    <?php
    $avtSaleRate = App\Models\Avt::find(1);
    $avtSaleRate = $avtSaleRate->AVT;
                                            ?>
    <input type="text" class="form-control " id="avtValue" name="avtValue" value="{{$avtSaleRate}}" hidden>
    {{-- End Update ( 24/4/2023 ) --}}



    <div class="modal p-3" id="createcustomer">
        <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.addnewcustomer') }} </h6><button aria-label="Close"
                            class="close close-special" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}
                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-6 col-md-4 mb-2">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.name') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="name" name="name"
                                title="{{ __('supprocesses.name') }}" required>
                        </div>

                        <div class="col-lg-4 col-md-3 mb-2 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.phone') }}</label>
                            <input style="height:32px;" type="text" class="form-control parent-input" id="phone"
                                name="phone" onkeyup="phoneConvert()" title="{{ __('supprocesses.phone') }}">
                        </div>

                        <div class="col-lg-4 col-md-3 mb-2 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.email') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="email" name="email"
                                title="{{ __('supprocesses.email') }}" value='Example@gmail.com'>
                        </div>
                        <!-- <div class="col-lg-3 col-md-3">
                                            <label for="inputName" class="control-label parent-label"> {{ __('home.current balance') }} </label>
                                            <input type="text" class="parent-input form-control" id="balance" name="balance"
                                                title="يرجي ادخال الكمية  " value="{{ $data['customer']->Balance ?? '0' }}"
                                                >
                                        </div> -->
                    </div>

                    {{-- 2 --}}
                    <div class="row mb-1">
                        <div class="col-lg-3 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.timeout_periodـinـdays') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input"
                                id="timeout_periodـinـdays" name="timeout_periodـinـdays"
                                title="{{ __('supprocesses.timeout_periodـinـdays') }}"
                                onkeyup="timeout_periodـinـdaysConvert()" value=30 required>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('home.tax_number') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="TaxـNumber"
                                name="TaxـNumber" onkeyup="TaxـNumberConvert()" title="{{ __('supprocesses.TaxـNumber') }}">
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('home.CRN') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="CRN" name="CRN"
                                onkeyup="TaxـNumberConvert() " value=0 title="{{ __('supprocesses.TaxـNumber') }}">
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.credit_limit') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="credit_limit"
                                name="credit_limit" onkeyup="credit_limitConvert()"
                                title="{{ __('supprocesses.credit_limit') }}" value=10000 required>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.product_notes') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="product_notes"
                                name="product_notes" title="{{ __('supprocesses.product_notes') }}" value='-'>
                        </div>

                    </div>
                    <div class="row mb-3">

                        <div class="col-lg-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('home.city') }}</label>
                            <input type="text" class="form-control parent-input" id="city" name="city"
                                title="{{ __('supprocesses.product_notes') }}" required>
                        </div>
                        <div class="col-lg-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('home.region') }}</label>
                            <input type="text" class="form-control parent-input" id="sub_city" name="sub_city"
                                title="{{ __('supprocesses.product_notes') }}" required>
                        </div>

                        <div class="col-lg-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('home.StreetName') }}</label>
                            <input type="text" class="form-control parent-input" id="StreetName" name="StreetName"
                                title="{{ __('supprocesses.product_notes') }}" required>
                        </div>
                        <div class="col-lg-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('home.plot_identification') }}</label>
                            <input type="text" class="form-control parent-input" id="plot_identification"
                                name="plot_identification" title="{{ __('supprocesses.product_notes') }}" required>
                        </div>
                        <div class="col-lg-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('home.buildnumber') }}</label>
                            <input type="text" class="form-control parent-input" id="buildnumber" name="buildnumber"
                                title="{{ __('supprocesses.product_notes') }}" required>
                        </div>
                        <div class="col-lg-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('home.postcode') }}</label>
                            <input type="text" class="form-control parent-input" id="postcode" name="postcode"
                                title="{{ __('home.postcode') }}" value='' required>
                        </div>


                    </div>
                    <br>
                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                            onclick="createnewcustomerajax()">
                            {{ __('supprocesses.save_data') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none"
                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                </path>
                            </svg>
                        </button>
                    </div>
            </div>

        </div>
    </div>
    </div>

    <div class="modal p-3" id="updateinvoicefromsale">
        <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.updateinvoice') }} </h6><button aria-label="Close"
                            class="close close-special" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('home.enterinvoicenumber') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input"
                                id="updateinvoicebyidforsale" name="updateinvoicebyidforsale"
                                title="{{ __('supprocesses.name') }}" required>
                        </div>


                    </div>


                    <br>
                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                            id="updateinvoicebyidforsaleupdate">
                            {{ __('home.search') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none"
                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                </path>
                            </svg>
                        </button>
                    </div>
            </div>

        </div>
    </div>
    </div>


    <div class="modal p-3" id="updateinvoicebyidmodale">
        <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.updateinvoicebyid') }} </h6><button aria-label="Close"
                            class="close close-special" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}calculateTotals
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('home.enterinvoicenumber') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="updateinvoicebyid"
                                name="name" title="{{ __('supprocesses.name') }}" required>
                        </div>


                    </div>


                    <br>
                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                            id="getinvoiceupdate">
                            {{ __('home.search') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none"
                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                </path>
                            </svg>
                        </button>
                    </div>
            </div>

        </div>
    </div>
    </div>



    <div class="modal p-3" id="createproduct">
        <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('supprocesses.addproduct') }} </h6><button aria-label="Close"
                            class="close close-special" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}
                    <br>
                    <label style="font-size:18px; color:red;font-weight:bold;">&nbsp;&nbsp;<input
                            style="font-size:16px; color:yellow;" type="checkbox" value=0
                            id="translate_status">&nbsp;&nbsp;{{__('home.active_translate')}}</label>
                    <br>


                    <div class="row mb-2">
                        <div class="col mb-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.product_name_ar') }}</label>
                            <input autocomplete=off type="text" class="form-control parent-input" id="product_name_ar"
                                name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}"
                                onkeyup="translateNameToEnglish()" required>
                        </div>


                        <div class="col mb-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.product_name_en') }}</label>
                            <input autocomplete=off type="text" class="form-control parent-input" id="product_name_en"
                                name="product_name_en" title="{{ __('supprocesses.product_name_en') }}"
                                onkeyup="translateNameToArbic()" required>
                        </div>


                        <div class="col mb-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.product_code') }}</label>
                            <input type="text" class="form-control parent-input" id="product_code_create"
                                name="product_code_create" type="text" dir="ltr" onkeyup="convertToNumber()"
                                title="{{ __('supprocesses.product_code') }}">
                        </div>

                    </div>

                    {{-- 2 --}}
                    <div class="row mb-2">
                        <div class="col-lg-3 mb-2">
                            <label for="inputName"
                                class="control-label parent-label">{{ __('supprocesses.product_branch') }}</label>
                            <select name="Section" id="Section" class="form-control parent-input"
                                onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                <!--placeholder-->
                                <option value="{{ Auth()->user()->branch->id }}"> {{ Auth()->user()->branch->name }}
                                </option>

                                @foreach (App\Models\branchs::get() as $section)
                                    @if(Auth()->user()->branch->id != $section->id)
                                        <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endif
                                @endforeach
                            </select>
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
                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label">{{ __('home.MAINproduct') }}</label>
                            <br>
                            <select style="width:100%!important" name="MAINproduct" class="form-control select2">
                                <!--placeholder-->
                                <option value=0> {{ __('home.noreplace') }}</option>


                            </select>
                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('home.refnumber') }}</label>
                            <input type="text" class="form-control parent-input" id="refnumber" name="refnumber">
                        </div>
                        <select hidden name="unit" id="unit" class="form-control parent-input">
                            <!--placeholder-->
                            <div class="row">

                                <option value="piece"> {{ __('home.unitـpiece') }}</option>
                                <option value="box">{{ __('home.unit_box') }}</option>
                        </select>




                    </div>


                    {{-- 3 --}}

                                                  @if(Auth()->user()->id==30||Auth()->user()->id==17)


                    <div class="row">
                        <div class="col-lg-4">
                            <label for="inputName" class="control-label parent-label"> {{ __('home.purachesepice') }}
                            </label>
                            <input autocomplete="off" type="text" class="form-control parent-input" id="cost_price"
                                name="cost_price" value=0 onkeyup="convertToNumberpurchasersPrice()">
                        </div>
                        <div class="col-lg-4">
                            <label for="inputName" class="control-label parent-label" required>{{ __('home.salepice') }}
                            </label>
                            <input autocomplete="off" type="text" class="form-control parent-input" id="sale_price_create"
                                value=0 name="sale_price_create" onkeyup="convertToNumbersalePrice()">
                        </div>
                        <div class="col-lg-4">
                            <label for="inputName" class="control-label parent-label" required>{{ __('home.quantity') }}
                            </label>
                            <input autocomplete="off" type="text" class="form-control parent-input" id="quantity_create"
                                value=0 name="quantity_create" onkeyup="convertToNumbersalePrice()">
                        </div>

                    </div>


                    @else

                                <div class="row">
                        <div class="col-lg-4">
                            <label for="inputName" class="control-label parent-label"> {{ __('home.purachesepice') }}
                            </label>
                            <input autocomplete="off" type="text" class="form-control parent-input" id="cost_price" readonly
                                name="cost_price" value=0 onkeyup="convertToNumberpurchasersPrice()">
                        </div>
                        <div class="col-lg-4">
                            <label for="inputName" class="control-label parent-label" required>{{ __('home.salepice') }}
                            </label>
                            <input autocomplete="off" type="text" class="form-control parent-input" id="sale_price_create" readonly
                                value=0 name="sale_price_create" onkeyup="convertToNumbersalePrice()">
                        </div>
                        <div class="col-lg-4">
                            <label for="inputName" class="control-label parent-label" required>{{ __('home.quantity') }}
                            </label>
                            <input autocomplete="off" type="text" class="form-control parent-input" id="quantity_create" readonly
                                value=0 name="quantity_create" onkeyup="convertToNumbersalePrice()">
                        </div>

                    </div>



                                       @endif

                    {{-- 5 --}}
                    <div class="row mb-2">
                        <div class="col-lg-4 mb-2" style="direction: ltr !important;">

                            <label for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.product_location') }}</label>
                            <input dir="ltr" style="direction:LTR !important ;text-align:start!important;" type="text"
                                class="form-control parent-input" id="product_location_create"
                                name="product_location_create" value='-' title="{{ __('supprocesses.product_location') }}"
                                required>
                        </div>









                        {{-- 3 --}}





                        {{-- 5 --}}


                        <div class="col-lg-4 mb-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.minmum_quantity_stock_alart') }}</label>
                            <input type="text" class="form-control parent-input" id="minmum_quantity_stock_alart"
                                name="minmum_quantity_stock_alart" onkeyup="minmum_quantity_stock_alartConvert()"
                                title="{{ __('supprocesses.minmum_quantity_stock_alart') }}" value=2 required>
                        </div>







                        <div class="col-lg-4 mb-2">
                            <label for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.product_notes') }}</label>
                            <input type="text" class="form-control parent-input" id="product_notes" name="product_notes"
                                title="{{ __('supprocesses.product_notes') }}">
                        </div>

                        <div class="col-lg-4 mb-2">
                            <input type="text" class="form-control parent-input" id="product_name_en" name="product_name_en"
                                title=" {{ __('supprocesses.product_name_en') }}" hidden>
                        </div>

                    </div><br>

                    <br>
                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                            onclick="createnewproductajax()">
                            {{ __('supprocesses.save_data') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none"
                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                </path>
                            </svg>
                        </button>
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
                    {{-- <button id="added_product" name="added_product" id="added_product"
                        class="btn btn-primary">{{__('home.confirm')}}</button>
                    --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                </div>

            </div>


        </div>
    </div>

    </div>




@endsection
@section('js')

    <!-- Ionicons js -->
    <script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
    <!--Internal  pickerjs js -->
    <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
    <!-- Internal form-elements js -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>


function calculateTotals_with_tax() {
    let total = 0, taxTotal = 0, discountTotal = 0, grand = 0;
    let avtsale = parseFloat($('#avtValue').val()) || 0;
    let taxFactor = avtsale > 1 ? (avtsale / 100) : avtsale;

    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let tax_with_price = parseFloat(r.querySelector('.product-price-tax').value) || 0;
        let cost = parseFloat(r.querySelector('.product_cost').value) || 0;
        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;

        // استخراج السعر قبل الضريبة للمقارنة
        let price = tax_with_price / (1 + taxFactor);

        // --- فحص التكلفة ---
        if (tax_with_price > 0 && price < cost) {
Swal.fire({
    icon: 'error',
    title: 'تنبيه: خطأ في سعر البيع <br> <small>Warning: Sales Price Error</small>',
    html: `
        <div style="direction: rtl; text-align: center;">
            <p style="font-weight: bold; color: #d33;">السعر المدخل أقل من سعر البيع المعتمد في النظام!</p>
            <p dir="ltr">The entered price is lower than the registered selling price!</p>
            <hr>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 5px;">
                <strong>سعر النظام | System Price:</strong>
                <span style="color: #28a745; font-size: 1.2em;">${cost}</span>
            </div>
        </div>
    `,
    position: 'center',
    showConfirmButton: true,
    confirmButtonText: 'موافق | OK',
    confirmButtonColor: '#3085d6',
    timer: 6000,
    timerProgressBar: true
});
           r.querySelector('.product-price-tax').style.borderColor = 'red';
            // إجبار السعر ليكون التكلفة + الضريبة
            tax_with_price = cost * (1 + taxFactor);
            r.querySelector('.product-price-tax').value = tax_with_price.toFixed(2);
            price = cost;
        } else {
            r.querySelector('.product-price-tax').style.borderColor = '';
        }
        let discound = parseFloat(r.querySelector('.product-discound').value) || 0;
        let discound_allow_Input = parseFloat($('#discound_allow').val()) || 0.15;

if (discound > (qty*price) * discound_allow_Input) {
    Swal.fire({
        icon: 'error',
        title: 'تنبيه: تجاوز حد الخصم <br> <small>Warning: Discount Limit Exceeded</small>',
        html: `
            <div style="direction: rtl; text-align: center;">
                <p>لقد تخطيت الخصم المسموح به لهذا المنتج!</p>
                <p dir="ltr">You have exceeded the allowed discount for this product!</p>
                <hr>
                <strong>الحد المسموح | Allowed Limit:</strong> ${cost * discound_allow_Input}
            </div>
        `,
        position: 'center',
        showConfirmButton: true,
        confirmButtonText: 'موافق | OK',
        confirmButtonColor: '#d33',
        timer: 6000,
        timerProgressBar: true
    });
}
        let subtotal = price * qty;
        let tax = (subtotal - discound) * taxFactor;
        let totalRow = subtotal - discound + tax;

        r.querySelector('.product-price').value = price.toFixed(2);
        r.querySelector('.product-totalprice_withodtax').value = subtotal.toFixed(2);
        r.querySelector('.product-tax').value = tax.toFixed(2);
        r.querySelector('.product-total').value = totalRow.toFixed(2);

        total += subtotal;
        taxTotal += tax;
        grand += totalRow;
        discountTotal += discound;

        // حساب الربح للسطر الحالي
        let profit = price - cost;
        $('#profit').val(profit.toFixed(2));
    });

    document.getElementById('totalSum').value = total.toFixed(2);
    document.getElementById('totaldiscound').value = discountTotal.toFixed(2);
    document.getElementById('totalTax').value = taxTotal.toFixed(2);
    document.getElementById('grandTotal').value = grand.toFixed(2);
}





        function replaceproduct(id) {
            branchs_id = $('#branchs_id').val();
            console.log(branchs_id)
            console.log(" {{URL::to('operationproducts')}}/" + branchs_id + "/" + id)
            jQuery.ajax({
                url: " {{URL::to('operationproducts')}}/" + branchs_id + "/" + id,
                type: 'get',
                dataType: 'html',
                cache: false,

                success: function (data) {
                    console.log('done')
                    $('#operation_product').modal().show();

                    $("#ajax_responce_operation_product_Div").html(data);
                },
                error: function () {

                }
            });


        }



        let rowIndex = 1;
        function calculateTotalDiscount() {
    // 1. جلب القيم وتحويلها لأرقام بدقة عالية
    let discountOnInvoice = parseFloat($('#discound_on_invoice').val() || 0); // القيمة 5
    let totalSum = parseFloat(document.getElementById('totalSum').value || 0); // القيمة 35
    let avtsale = parseFloat($('#avtValue').val() || 0.15); // نسبة الضريبة

    // 2. حساب الخصم الصافي (قبل الضريبة) بدون تقريب وسيط
    // 5 / 1.15 = 4.347826086956522...
    let amountBeforeTax = discountOnInvoice / (1 + avtsale);

    // 3. حساب إجمالي الخصومات من الجدول (إن وجدت)
    let tableDiscountTotal = 0;
    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let discound = parseFloat(r.querySelector('.product-discound').value) || 0;
        tableDiscountTotal += discound;
    });

    // 4. إجمالي الخصم الكلي (خام)
    let finalDiscountTotal = tableDiscountTotal + amountBeforeTax;

    // 5. الحسابات النهائية:
    // الصافي = 35 - 4.347826... = 30.652173...
    let netTotal = totalSum - finalDiscountTotal;

    // الضريبة = 30.652173... * 0.15 = 4.597826...
    let taxTotal = netTotal * avtsale;

    // الإجمالي النهائي = 30.652173... + 4.597826... = 35.25 (تقريباً)
    // لكن بما أن الخصم الإجمالي شامل الضريبة هو 5، فالصافي المطلوب هو 30 تماماً
    let grandTotal = netTotal + taxTotal;

    // 6. العرض في الحقول مع التقريب لخانة واحدة فقط إذا لزم الأمر أو خانتين
    // لجعلها 30.00 بالضبط:
    document.getElementById('totaldiscound').value = finalDiscountTotal.toFixed(2);
    document.getElementById('totalTax').value = taxTotal.toFixed(2);
    document.getElementById('grandTotal').value = Math.round(grandTotal * 100) / 100; // حل مشكلة 29.99

    // تأكيد إضافي إذا كان الفرق ضئيل جداً نتيجة تقريب المتصفح
    if (Math.abs(grandTotal - 30) < 0.01) {
        document.getElementById('grandTotal').value = "30.00";
    }
}

        $("#updateinvoicebyidforsaleupdate").click(function (e) {



            event.preventDefault();
            var url = " {{ URL::to('updateofficebyidforupdate') }}" + "/" + $('#updateinvoicebyidforsale').val();
            console.log(url)
            jQuery.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                cache: false,


                success: function (data) {




                    $('#show_invoice_number_update').val($('#updateinvoicebyidforsale').val())


                    console.log('++++++')
                    console.log(data)

                    document.getElementById("productsTableBody").innerHTML = "";

                    data['product'].forEach(async (product) => {

                        quentity = product['quantity']

                        let index = rowIndex - 1

                        if (quentity > 0) {
                            let table = document.getElementById('productsTableBody');


                            let row = `
                <tr data-index="${index}">
                    <td><input type="hidden" name="products[${index}][product_id]" class="product-id form-control">
                                            <input type="hidden" name="products[${index}][product_cost]" class="product_cost">

                    </td>
                    <td class="align-middle text-center">${index + 1}</td>

                    <td class="text-start">
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control product-code" placeholder="اختر منتج" readonly>
                            <button type="button" class="btn btn-sm btn-info p-1"
                                    style="background-color: #FBA10F;font-size:13px;width:40px"
                                    onclick="openProductModal(${index})">   <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                <path d="M21 21l-6 -6"></path>
                                                            </svg></button>
                        </div>
                    </td>

                    <td class="text-start">
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control product-name" placeholder="اختر منتج" readonly>
                            <button type="button" class="btn btn-sm btn-info p-1"
                                    style="background-color: #FBA10F;font-size:13px;width:40px"
                                    onclick="openProductModal(${index})">   <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                <path d="M21 21l-6 -6"></path>
                                                            </svg></button>
                        </div>
                    </td>
        <td><input type="text" name="products[${index}][price-tax]"
                    class="form-control product-price-tax" value="0" min="0" onchange="calculateTotals_with_tax()"></td>
                    <td><input type="text" name="products[${index}][price]"
                            class="form-control product-price" value="0" min="0" onchange="calculateTotals()"></td>

                    <td><input type="text" name="products[${index}][quentity]"
                            class="form-control product-quentity" oninput='calculateTotals()' value=1></td>

                    <td><input type="text" name="products[${index}][totalprice_withodtax]"
                            class="form-control product-totalprice_withodtax"  readonly value="0" min="0" oninput="calculateTotals()"></td>

                    <td><input type="text" name="products[${index}][discound]"
                            class="form-control product-discound" value="0" onchange='calculateTotals()' min="0"></td>

                    <td><input type="text" name="products[${index}][tax]" class="form-control product-tax" value="0" readonly></td>

                    <td><input type="text" class="form-control product-total" readonly value="0"></td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">{{ __('home.delete') }}</button>
                    </td>
                </tr>`;

                            table.insertAdjacentHTML("beforeend", row);




                            product_code = product['Product_Code']
                            product_name = product['product_name']
                            purchasingـprice = product['Unit_Price']
                            quantity = product['quantity']


                            let row_add = document.querySelector(`#productsTableBody tr[data-index='${index}']`);
                            row_add.querySelector('.product-id').value = product['id'];
                            row_add.querySelector('.product-name').value = product_name;
                            row_add.querySelector('.product-code').value = product_code;
                            row_add.querySelector('.product-price').value = purchasingـprice;
                            row_add.querySelector('.product-discound').value = 0;
                            row_add.querySelector('.product-quentity').value = quentity;

                            index = rowIndex++; // 👈 يزيد دايمًا

                        }

                        window.currentRow = index;

                    });
                                        calculateTotals()

           $('#discound_on_invoice').val(data['discound']).trigger('input');
                    try {
                        $('#clientnamesearch').append(
                            $('<option>', { value: data['customer']['id'], text: data['customer']['name'] })
                        );
                        $('#clientnamesearch').val(data['customer']['id']).trigger('change');
                    } catch (e) {
                        console.error(e);
                    }
                    console.log('n')
                    console.log(data['customer']['name'])

                    window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });

                    document.getElementById('printdiv').hidden = true
                    document.getElementById('saveInvoice').hidden = false



                },
                error: function (response) {
                    alert("{{ __('home.sorryerror') }}")

                }

            })


        });




        function createnewproductajax() {
            console.log('+++++++++++++++++++++++++++++++++create customer ++++++++++++++++++++++++++++++++');
            var url = " {{ URL::to('addnewProductajax') }}";
            console.log($('#product_notes').val())
            console.log($('#minmum_quantity_stock_alart').val())
            console.log($('#product_name_ar').val())
            console.log($('#product_code').val())
            console.log($('#Section').val())
            console.log($('#unit').val())
            console.log($('#product_location').val())
            var token_search = $("#token_search").val();
            if ($('#product_name_ar').val() == '') {
                alert("{{ __('supprocesses.product_name_ar') }}")
            } else if ($('#product_location_create').val() == '') {
                alert("{{ __('supprocesses.product_location') }}")
            } else {


                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        product_notes: $('#product_notes').val() ?? '-',
                        minmum_quantity_stock_alart: $('#minmum_quantity_stock_alart').val(),
                        product_name_ar: $('#product_name_ar').val(),
                        product_name_en: $('#product_name_en').val(),
                        product_code: $('#product_code_create').val(),
                        Section: $('#Section').val(),
                        unit: $('#unit').val(),
                        product_location: $('#product_location_create').val(),
                        refnumber: $('#refnumber').val(),
                        product_group: $('#product_group').val(),
                        numberofpice: $('#quantity_create').val(),
                        cost_price: $('#cost_price').val(),
                        sale_price_create: $('#sale_price_create').val(),


                        success: function (data) {
                            $('#createcustomer').modal('hide');
                            $('#quantity_create').val(0)
                            $('#cost_price').val(0)
                            $('#sale_price_create').val(0)
                            $('#product_location').val('');
                            $('#product_name_ar').val('');
                            $('#product_notes').val('')
                            $('#product_code').val('')
                            $('#massagesave').modal().show();
                            setTimeout(() => {
                                $('#massagesave').modal('hide');

                            }, 1000);

                        },
                    }
                });







            }


        }

        function translateNameToArbic() {
            const checkbox = document.getElementById('translate_status');

            if (checkbox.checked) {


                var wordEnglish = $('#product_name_en').val();

                jQuery.ajax({
                    url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=en&tl=ar&q=" +
                        wordEnglish,
                    type: 'get',
                    cache: false,

                    success: function (request_result) {
                        $('#product_name_ar').val(request_result[0][0][0])
                    },
                    error: function () {

                    }
                });

            }

        }





        $('select[name="numbershowstatus"]').on('change', function () {
            console.log('AJAX load   work 0000');

            var selectCustomer = $(this).val();
            $('#shownumberproduct').val(selectCustomer)


        })

        function translateNameToEnglish() {
            const checkbox = document.getElementById('translate_status');

            if (checkbox.checked) {


                var wordarbic = $('#product_name_ar').val();

                jQuery.ajax({
                    url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=ar&tl=en&q=" +
                        wordarbic,
                    type: 'get',
                    cache: false,

                    success: function (request_result) {
                        $('#product_name_en').val(request_result[0][0][0])
                    },
                    error: function () {

                    }
                });

            }

        }


        function createnewcustomerajax() {


            console.log('+++++++++++++++++++++++++++++++++create customer ++++++++++++++++++++++++++++++++');
            var url = " {{ URL::to('createnewcustomerajax') }}";

            var token_search = $("#token_search").val();
            if ($('#name').val() == '') {
                alert("{{ __('home.enterclienname') }}")
            } else if ($('#buildnumber').val() == '') {
                alert("{{ __('home.buildnumber') }}")
            } else if ($('#plot_identification').val() == '') {
                alert("{{ __('home.plot_identification') }}")
            } else if ($('#postcode').val() == '') {
                alert("{{ __('home.postcode') }}")
            } else if ($('#StreetName').val() == '') {
                alert("{{ __('home.StreetName') }}")
            } else if ($('#city').val() == '') {
                alert("{{ __('home.city') }}")
            } else if ($('#sub_city').val() == '') {
                alert("{{ __('home.sub_city') }}")
            } else if ($('#TaxـNumber').val().length != 15) {
                alert('يجب ان يكون رقم الضريبي مكون من 15 رقم     \n    The tax number must consist of 15 digits')
            } else {

                $('#createcustomer').modal().hide();

                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        name: $('#name').val(),
                        tax_no: $('#TaxـNumber').val(),
                        Balance: 0,
                        city: $('#city').val() ?? "client address",
                        phone: $('#phone').val(),
                        email: $('#email').val(),
                        notes: $('#product_notes').val(),
                        Limit_credit: $('#credit_limit').val(),
                        grace_period_in_days: $('#timeout_periodـinـdays').val(),
                        buildnumber: $('#buildnumber').val(),
                        plot_identification: $('#plot_identification').val(),
                        StreetName: $('#StreetName').val(),
                        sub_city: $('#sub_city').val(),
                        postcode: $('#postcode').val(),
                        CRN: $('#CRN').val(),
                    },


                    success: function (data) {
                        $('#phone').val('');
                        $('#TaxـNumber').val('');
                        $('#name').val('')
                        console.log('seccusss12111');
                        console.log(data)
                        $('#clientnamesearch').append($('<option >', {
                            value: data['id'],
                            text: data['name'] + data['tax_no']
                        }));


                        $('#massagesave').modal().show();
                        setTimeout(() => {
                            $('#massagesave').modal('hide');

                        }, 500);
                    },
                    error: function (response) {
                        alert("{{ __('home.sorryerror') }}")

                    }
                });







            }



        }
        $("#reciptprinter").click(function (e) {
            var url = " {{ URL::to('reciptprinter') }}";
            var token_search = $("#token_search").val();
            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    _token: token_search,
                    show_invoice_number: $('#show_invoice_number').val(),
                },
                success: function (data) {
                    console.log(data)
                    const winUrl = URL.createObjectURL(
                        new Blob([data], {
                            type: "text/html"
                        })
                    );
                    const win = window.open(
                        winUrl,
                        "win",
                        `width=800,height=400,screenX=200,screenY=200`
                    );

                },
                error: function (response) {
                    console.log(response)
                    alert("{{ __('home.sorryerror') }}")

                }
            });
        });
        $(document).ready(function () {
            document.getElementById('printdiv').hidden = true

        })

$("#formdata").on('submit', function (e) {
    e.preventDefault();

    // 1. التحقق من صحة المدخلات (المنتج، السعر، والكمية)
    let isValid = true;
    let errorMessage = "";

    $('#productsTableBody tr').each(function() {
        let row = $(this);
        let productId = row.find('.product-id').val(); // فحص ID المنتج
        let price = row.find('.product-price').val();
        let qty = row.find('.product-quentity').val();
        let productName = row.find('.product-name').val();

        // أ. التأكد من اختيار صنف (ليس فارغاً)
        if (!productId || productId === "" || productId === "0") {
            row.css('background-color', '#fff3cd');
            isValid = false;
            errorMessage = "يرجى اختيار صنف صحيح في جميع الصفوف";
            return false;
        }

        // ب. التأكد من السعر والكمية
        if (price === "" || parseFloat(price) <= 0 || qty === "" || parseFloat(qty) <= 0) {
            row.find('.product-price, .product-quentity').css('border', '2px solid red');
            isValid = false;
            errorMessage = "يوجد نقص في سعر أو كمية المنتج: " + (productName || "غير محدد");
            return false;
        } else {
            row.find('.product-price, .product-quentity').css('border', '');
            row.css('background-color', '');
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'بيانات غير مكتملة',
            text: errorMessage,
            confirmButtonText: 'موافق'
        });
        return;
    }

    // 2. إذا كانت البيانات سليمة، ننتقل لنافذة التأكيد الخاصة بالمورد
    var supplierName = $("#clientnamesearch option:selected").text().trim(); // اسم المورد من السيرش
    var form = this;

    Swal.fire({
        title: 'تأكيد حفظ أمر الشراء | Confirm Purchase Order',
        html: `<b>هل أنت متأكد من حفظ أمر الشراء من المورد؟</b><br><span style="color: #28a745;">${supplierName}</span>`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'نعم، حفظ | Yes, Save',
        cancelButtonText: 'إلغاء | Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('#massagesave').modal('show');

            // تعديل الرابط ليتوجه إلى دالة حفظ المشتريات الجديدة بدلاً من القديمة
            var url = "{{ URL::to('save_purchase_order') }}";

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(form),
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    // إذا عاد السيرفر بمعرف الفاتورة (ID أكبر من أو يساوي 1)
                    if (data >= 1) {
                        $('#show_invoice_number').val(data);
                        $('#OrderNoprint').val(data);
                        document.getElementById('printdiv').hidden = false;
                        $('#saveInvoice').addClass('d-none');

                        // رابط الـ PDF المخصص لطباعة أمر الشراء
                        let link = "{{ URL::to('generate_pdf_purchase_order') }}/" + data;
                        $('#generate_pdf').attr('href', link);

                        setTimeout(() => {
                            $('#massagesave').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'تم حفظ أمر الشراء بنجاح',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }, 1000);
                    } else {
                        $('#massagesave').modal('hide');
                        Swal.fire('خطأ', 'حدثت مشكلة أثناء حفظ أمر الشراء', 'error');
                    }
                },
                error: function(R) {
                    console.log(R)
                    $('#massagesave').modal('hide');
                    Swal.fire('خطأ', 'فشل الاتصال بالسيرفر', 'error');
                }
            });
        }
    });
});

function reorderRows() {
    // جلب كل الصفوف داخل جسم الجدول
    const rows = document.querySelectorAll('#productsTableBody tr');

    rows.forEach((tr, i) => {
        // 1. تحديث رقم التسلسل الظاهري في العمود الثاني
        const rowNumberCell = tr.querySelector('td:nth-child(2)');
        if (rowNumberCell) {
            rowNumberCell.innerText = i + 1;
        }

        // 2. تحديث خاصية data-index للصف
        tr.setAttribute('data-index', i);

        // 3. تحديث الـ onclick في أزرار البحث لترسل الـ index الجديد
        const searchButtons = tr.querySelectorAll('button[onclick^="openProductModal"]');
        searchButtons.forEach(btn => {
            btn.setAttribute('onclick', `openProductModal(${i})`);
        });

        // 4. أهم خطوة: تحديث الـ name لكل الـ inputs لضمان ترتيب المصفوفة المرسلة للسيرفر
        // نبحث عن أي input يحتوي اسمه على كلمة products[رقم]
        const inputs = tr.querySelectorAll('input[name*="products"]');
        inputs.forEach(input => {
            let name = input.getAttribute('name');
            // تغيير الرقم بين الأقواس المربعة ليكون i الحالي
            // products[0][price] -> products[1][price] وهكذا
            let newName = name.replace(/products\[\d+\]/, `products[${i}]`);
            input.setAttribute('name', newName);
        });
    });

    // تحديث عداد الصفوف العالمي إذا كنت تستخدمه
    if (typeof rowCounter !== 'undefined') {
        rowCounter = rows.length;
    }
}

function removeRow(btn) {
    btn.closest('tr').remove();
                calculateTotals()
                    reorderRows();


}

        let rowCounter = 0;

        function openProductModal(index) {
            window.currentRow = index;
            $('#SearchProduct').modal().show();
            // $('#searchaboutproduct').focus();

        }

        function addRow() {
                                reorderRows();

            let table = document.getElementById('productsTableBody');
            let index = table.querySelectorAll('tr').length;

              let row = `
                <tr data-index="${index}">
                    <td><input type="hidden" name="products[${index}][product_id]" class="product-id form-control">
                    <input type="hidden" name="products[${index}][product_cost]" class="product_cost">

                    </td>
                    <td class="align-middle text-center">${index + 1}</td>

                    <td class="text-start">
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control product-code" placeholder="اختر منتج" readonly>
                            <button type="button" class="btn btn-sm btn-info p-1"
                                    style="background-color: #FBA10F;font-size:13px;width:40px"
                                    onclick="openProductModal(${index})">   <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                <path d="M21 21l-6 -6"></path>
                                                            </svg></button>
                        </div>
                    </td>

                    <td class="text-start">
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control product-name" placeholder="اختر منتج" readonly>
                            <button type="button" class="btn btn-sm btn-info p-1"
                                    style="background-color: #FBA10F;font-size:13px;width:40px"
                                    onclick="openProductModal(${index})">   <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                <path d="M21 21l-6 -6"></path>
                                                            </svg></button>
                        </div>
                    </td>
        <td><input type="text" name="products[${index}][price-tax]"
                    class="form-control product-price-tax" value="0" min="0" onchange="calculateTotals_with_tax()"></td>
                    <td><input type="text" name="products[${index}][price]"
                            class="form-control product-price" value="0" min="0" onchange="calculateTotals()"></td>

                    <td><input type="text" name="products[${index}][quentity]"
                            class="form-control product-quentity" oninput='calculateTotals()' value=1></td>

                    <td><input type="text" name="products[${index}][totalprice_withodtax]"
                            class="form-control product-totalprice_withodtax"  readonly value="0" min="0" oninput="calculateTotals()"></td>

                    <td><input type="text" name="products[${index}][discound]"
                            class="form-control product-discound" value="0" onchange='calculateTotals()' min="0"></td>

                    <td><input type="text" name="products[${index}][tax]" class="form-control product-tax" value="0" readonly></td>

                    <td><input type="text" class="form-control product-total" readonly value="0"></td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">{{ __('home.delete') }}</button>
                    </td>
                </tr>`;


            table.insertAdjacentHTML("beforeend", row);
            window.currentRow = index;

            $('#SearchProduct').modal().show();
            // $('#searchaboutproduct').focus();

        }

        $("#printReciept").click(function (e) {
            var url = " {{ URL::to('printInvoice') }}";
            var token_search = $("#token_search").val();
            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    _token: token_search,
                    show_invoice_number: $('#show_invoice_number').val(),
                },
                success: function (data) {
                    const winUrl = URL.createObjectURL(
                        new Blob([data], {
                            type: "text/html"
                        })
                    );
                    const win = window.open(
                        winUrl,
                        "win",
                        `width=800,height=400,screenX=200,screenY=200`
                    );

                },
                error: function (response) {
                    console.log(response)
                    alert("{{ __('home.sorryerror') }}")

                }
            });
        });

function calculateTotals() {
    let total = 0, taxTotal = 0, discountTotal = 0, grand = 0;
    let taxInput = parseFloat($('#avtValue').val()) || 0;
    let avtsale = taxInput > 1 ? taxInput / 100 : taxInput;

    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let price = parseFloat(r.querySelector('.product-price').value) || 0;
        let cost = parseFloat(r.querySelector('.product_cost').value) || 0; // جلب التكلفة من الحقل المخفي
        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;

        // --- فحص التكلفة ---
        if (price > 0 && price < cost) {
Swal.fire({
    icon: 'error',
    title: 'تنبيه: خطأ في سعر البيع <br> <small>Warning: Sales Price Error</small>',
    html: `
        <div style="direction: rtl; text-align: center;">
            <p style="font-weight: bold; color: #d33;">السعر المدخل أقل من سعر البيع المعتمد في النظام!</p>
            <p dir="ltr">The entered price is lower than the registered selling price!</p>
            <hr>
            <div style="background: #f8f9fa; padding: 10px; border-radius: 5px;">
                <strong>سعر النظام | System Price:</strong>
                <span style="color: #28a745; font-size: 1.2em;">${cost}</span>
            </div>
        </div>
    `,
    position: 'center',
    showConfirmButton: true,
    confirmButtonText: 'موافق | OK',
    confirmButtonColor: '#3085d6',
    timer: 6000,
    timerProgressBar: true
});

            r.querySelector('.product-price').classList.add('is-invalid');
            r.querySelector('.product-price').style.borderColor = 'red';
            price = cost; // إجبار السعر ليكون مساوياً للتكلفة (اختياري)
            r.querySelector('.product-price').value = cost.toFixed(2);
        } else {
            r.querySelector('.product-price').classList.remove('is-invalid');
            r.querySelector('.product-price').style.borderColor = '';
        }



    let discound_allow_Input = parseFloat($('#discound_allow').val()) || 0.15;
    let discountInput = parseFloat(r.querySelector('.product-discound').value) || 0;

if (discountInput > (qty*price) * discound_allow_Input) {
    Swal.fire({
        icon: 'error',
        title: 'تجاوز حد الخصم | Discount Limit Exceeded',
        text: 'لقد تخطيت الخصم المسموح به | You have exceeded the allowed discount',
        position: 'center',
        showConfirmButton: true,
        confirmButtonText: 'موافق | OK',
        confirmButtonColor: '#d33',
        timer: 5000,
        timerProgressBar: true
    });
r.querySelector('.product-discound').value=0
}


        let subtotal = price * qty;
        let discountAmount= discountInput;
        let taxableAmount = subtotal - discountAmount;
        let tax = taxableAmount * avtsale;
        let totalRow = taxableAmount + tax;

        r.querySelector('.product-totalprice_withodtax').value = subtotal.toFixed(2);
        r.querySelector('.product-tax').value = tax.toFixed(2);
        r.querySelector('.product-total').value = totalRow.toFixed(2);
        r.querySelector('.product-price-tax').value = (price * (1 + avtsale)).toFixed(2);

        total += subtotal;
        taxTotal += tax;
        grand += totalRow;
        discountTotal += discountAmount;
    });

    document.getElementById('totalSum').value = total.toFixed(2);
    document.getElementById('totaldiscound').value = discountTotal.toFixed(2);
    document.getElementById('totalTax').value = taxTotal.toFixed(2);
    document.getElementById('grandTotal').value = grand.toFixed(2);
}
        function searchaboutproductfunction() {
            searchtext = $('#searchaboutproduct').val();
            branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search").val();

            jQuery.ajax({
                url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "locale" : "{{ app()->getLocale() }}",
                    "branchs_id": branchs_id,
                    "currentrow": window.currentRow,
                },
                success: function (data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },

            });

        }

        $('#SearchProduct').on('show.bs.modal', function (event) {
            searchtext = '';
            branchs_id = $('#branchs_id').val();
            var token_search = $("#token_search").val();

            jQuery.ajax({
                url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "locale" : "{{ app()->getLocale() }}",
                    "branchs_id": branchs_id,
                    "currentrow": window.currentRow,
                },
                success: function (data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },

            });

        });
        $(document).on('click', '#ajax_pagination_in_search a', function (e) {
            e.preventDefault();
            var search_by_text = $("#searchaboutproduct").val();
            var url = $(this).attr("href");
            var token_search = $("#token_search").val();
            branchs_id = $('#branchs_id').val();

            jQuery.ajax({
                url: url,
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": search_by_text,
                    "branchs_id": branchs_id,
                },
                success: function (data) {
                    $("#ajax_responce_serarchDiv").html(data);
                },
                error: function () {

                }
            });
        });
        // const input = document.getElementById('searchaboutproduct');

        // input.addEventListener('blur', function () {
        //     setTimeout(() => input.focus(), 0);
        // });
        function chooseProduct(code, productcode, name, cost, sale_price, location, availablequantity, currentrow) {
            console.log(window.currentRow);

            let row = document.querySelector(`#productsTableBody tr[data-index='${currentrow}']`);
            row.querySelector('.product-id').value = code;
            row.querySelector('.product-name').value = name;
            row.querySelector('.product-code').value = productcode;
            row.querySelector('.product-price').value = sale_price;
            row.querySelector('.product-discound').value = 0;
    if($('#branchs_id').val()==1){

    row.querySelector('.product_cost').value = 0;

    }else{
    row.querySelector('.product_cost').value = 0;

    }
            calculateTotals()
            window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
            console.log("{{ URL::to('/getlastprice_offer_price') }}/" + code+ "/" + $('#clientnamesearch').val());

            $.ajax({
                url: "{{ URL::to('/getlastprice_offer_price') }}/" + code + "/" + $('#clientnamesearch').val(),
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $("#last_supplier_cost").empty();

                    data.forEach(async (product) => {

                        $('#last_supplier_cost').append($('<option>', {
                            value: 1,
                            text: "{{ __('home.Invoice_no') }}" + " : " + product['invoiceid'] + " ** " + product['date'] + " **  " + product['cost'] + " " + "{{ __('home.SAR') }}"
                        }));
                    })

                }
            })
        }
    </script>

@endsection
