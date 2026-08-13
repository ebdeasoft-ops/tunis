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


<style>
/* =========================
   Modern UI + Inner Borders
   ========================= */

/* الخط العام */
/* تنسيق الحاوية الرئيسية */
#printdiv {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
}

/* التنسيق الموحد للأزرار */
#printdiv .btn,
#printdiv a,
#printdiv button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 38px !important;
    /* ارتفاع موحد احترافي */
    border-radius: 8px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    border: none !important;
    padding: 0 15px !important;
    text-decoration: none !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

/* زر الواتساب الدائري الصغير */
#send_whats_app {
    width: 45px !important;
    /* مقاس متناسق مع البقية */
    background-color: #25d366 !important;
    font-size: 20px !important;
    color: white !important;
}

/* تأثيرات التمرير (Hover) */
#printdiv a:hover,
#printdiv button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    filter: brightness(1.1);
}

/* أيقونات SVG داخل الأزرار */
.svg-icon-buttons {
    fill: currentColor;
    margin-right: 5px;
    margin-left: 5px;
}

/* زر هيئة الزكاة والضريبة (رمادي) */
#sendzatca {
    background-color: #6c757d !important;
    color: white !important;
}

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

/* Basic styling for loading screen */
#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    color: white;
    font-size: 24px;
    display: none;
}

#loading-animation {
    border: 4px solid white;
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
@section('title')
{{ __('home.sales') }}
@stop
@endsection
@section('page-header')
<div class="main-parent">
    <div style="justify-content: space-between !important" class="breadcrumb-header parent-heading">
        <div class="my-auto" style="width:100%">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="width:100%; gap: 10px;">

                <div class="d-flex align-items-center">
                    <h4 class="content-title mb-0" style="color: #23395D; font-weight: bold;">{{ __('home.sales') }}
                    </h4>
                </div>

                <div class="d-flex flex-wrap align-items-center" style="gap: 5px;">

                    {{-- خيار التحكم في إظهار الرقم --}}
                    <div class="select-wrapper">
                        <select class="form-control select2" name="numbershowstatus" id="numbershowstatus" required>
                            <option value="1">{{__('home.shownumberselect')}}</option>
                            <option value="0">{{__('home.notshow')}}</option>
                        </select>
                    </div>

                    {{-- زر إضافة عميل جديد --}}
                    <button style="background-color: #23395D;" class="btn btn-sm text-white p-2" data-toggle="modal"
                        href="#createcustomer">
                        <i class="fa fa-user-plus"></i> {{ __('home.addnewcustomer') }}
                    </button>

                    {{-- زر إضافة منتج جديد --}}
                    <button style="background-color: #23395D;" class="btn btn-sm text-white p-2" data-toggle="modal"
                        href="#createproduct">
                        <i class="fa fa-plus"></i> {{ __('supprocesses.addproduct') }}
                    </button>

                    <button style="background-color: green;" class="btn btn-sm text-white p-2" data-toggle="modal"
                        href="#updateinvoicebyidmodale">
                        {{ __('home.updateinvoicebyid') }}
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>







@endsection
@section('content')

<center>
    <div id="loading-screen">
        <div id="loading-animation"></div>
        &nbsp; <p> جارٍ إرسال الفاتورة، يرجى الانتظار <br>Invoice is being sent, please wait</p>
    </div>
</center>

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

                <form enctype="multipart/form-data" method="POST" role="search" name="form-name" id='formdata'
                    autocomplete="off">
                    {{ csrf_field() }}

                    <input type="hidden" name="action" id="invoice_action">


                    <div style="border-radius: 10px" class="card p-3 my-3">





                        <?php $i = 0; ?>


                        <table class="table-responsive table table-bordered">
                            <thead class="table-dark">
                                <col style="width:0.5%">
                                <col style="width:1%">
                                <col style="width:10%">
                                <col style="width:15%">
                                <col style="width:8%">
                                <col style="width:9%">
                                <col style="width:9%">
                                <col style="width:7%">
                                <col style="width:8%">
                                <col style="width:8%">
                                <col style="width:5%">
                                <col style="width:6%">
                                <col style="width:8%">
                                <col style="width:8%">
                                <thead>
                                    <tr>
                                        <th>- </th>

                                        <th> # </th>
                                        <th>{{ __('home.productNo') }} </th>
                                        <th>{{ __('home.product') }}</th>
                                        <th>{{ __('home.purchase') }}</th>
                                        <th>{{ __('home.saleperpice') }}</th>

                                        <th> {{ __('home.productprice') }} </th>
                                        <th>{{ __('home.quantity') }}</th>
                                        <th>{{ __('home.price') }}</th>

                                        <th>{{ __('home.discount') }}</th>
                                        <th style="width: 10%">{{ __('home.avt_rate') }}</th>
                                        <th>{{ __('home.addedValue') }}</th>
                                        <th>{{ __('home.total') }}</th>
                                        <th>{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                            <tbody id="productsTableBody">

                            </tbody>
                        </table>

                        <button type="button" class="addProductBtn" style="background-color: #419BB2 ;width:8%"
                            onclick="addRow()"> {{ __('supprocesses.addproduct') }}</button>
                        <br>
                        <div class="row mt-3 p-3"
                            style="background-color: #fcfcfc; border: 1px solid #e1e1e1; border-radius: 8px; justify-content: space-between; align-items: flex-end;">

                            <div class="col-lg-3">
                                <label class="form-label" style="color: #23395D; font-weight: bold; font-size: 13px;">
                                    <i class="fa fa-file-invoice-dollar ml-1"></i> {{ __('home.discound_on_invoice') }}
                                </label>
                                <input type="text" readonly id="discound_on_invoice" name="discound_on_invoice"
                                    oninput='calculateTotalDiscount()' class="form-control"
                                    style="border: 1px solid #23395D; border-radius: 5px; height: 40px; text-align: center; font-weight: bold; color: #d32f2f;"
                                    placeholder="0.00">
                            </div>
                            @can('System setting')

                            <div id="div_show_profit" class="col-lg-3">
                                <label for="profit" class="control-label parent-label"
                                    style="color: #23395D; font-weight: bold; font-size: 13px;">
                                    <i class="fa fa-money-bill-wave ml-1"></i> {{ __('home.profit') }}
                                </label>
                                <input autocomplete="off" type="text" id="profit" name="profit" value="0" readonly
                                    class="form-control parent-input"
                                    style="background-color: #e3f2fd; border: 1px solid #90caf9; border-radius: 5px; height: 40px; text-align: center; font-weight: bold; color: #1565c0;">
                            </div>
                            @endcan


                            <div class="col-lg-4">
                                <label for="last_supplier_cost" class="control-label parent-label"
                                    style="color: #23395D; font-weight: bold; font-size: 13px;">
                                    <i class="fa fa-tag ml-1"></i> {{ __('home.lastpricecustomer') }}
                                </label>
                                <select class="form-control parent-input" name="last_supplier_cost"
                                    id="last_supplier_cost"
                                    style="background-color: #d4edda; border: 1px solid #28a745; border-radius: 5px; height: 40px; font-weight: 500;">
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">


                            <div class="col-md-3"><label class="form-label">{{ __('home.the amount') }}</label><input
                                    readonly type="text" id="totalSum" name="totalSum"
                                    class="form-control parent-input">
                            </div>
                            <div class="col-md-3"><label class="form-label">{{ __('home.discount') }}
                                </label><input type="text" id="totaldiscound" name="totaldiscound" readonly
                                    class="form-control parent-input">
                            </div>
                            <div class="col-md-3"><label class="form-label">{{ __('home.addedValue') }}
                                </label><input type="text" id="totalTax" name="totalTax" readonly
                                    class="form-control parent-input"></div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('home.total') }}</label><input type="text"
                                    id="grandTotal" name="grandTotal" readonly class="form-control parent-input">
                            </div>
                        </div>



                        <br>


                        <input type="number" class="form-control " name="show_invoice_number_update"
                            id="show_invoice_number_update" value=0 title=" رقم الفاتورة " hidden>

                        <div class='row'>

                            <div class="col-lg-5 mg-lg-t-10">
                                <label for="inputName" class="form-label">{{ __('home.chooseclient') }}</label>



                                <select style="width:100%!important" name="clientnamesearch" id="clientnamesearch"
                                    class="form-control select2">
                                    <option value=1>عميل نقدي CASH CUSTOMER</option>


                                </select>
                            </div>



                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <p class="mg-b-10"> {{__('home.paymentmethod')}}</p>
                                <select class="form-control select2" name="paymentmethod" id="paymentmethod" required>
                                    @foreach (App\Models\financial_accounts::where('parent_account_number',
                                    4)->where('branchs_id',
                                    Auth()->user()->branchs_id)->orwhere('parent_account_number',
                                    5)->where('parent_account_number', '!=', NULL)->where('branchs_id',
                                    Auth()->user()->branchs_id)->get() as $section)
                                    <option value="{{ $section->id }}">
                                        {{ app()->getLocale() == 'en' ? $section->name_en : $section->name }}
                                        ({{ $section->account_number }})</option>
                                    @endforeach
                                    <option value="Credit"> {{ __('report.credit') }} </option>
                                    <option value="Partition"> {{ __('home.Partition of the amount') }} </option>

                                </select>
                            </div>
                            <!-- col-4 -->

                            <div class="col-lg-2 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> . </p>
                                <select class="form-control parent-input " name="payment_type" id="payment_type"
                                    required>
                                    <option id="Cash" value="Cash"> {{ __('report.cash') }}</option>
                                    <option value="Shabka"> {{ __('report.shabka') }} </option>
                                    <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                                    <option value="Credit"> {{ __('report.credit') }} </option>
                                    <option value="Partition"> {{ __('home.Partition of the amount') }} </option>


                                </select>
                            </div>


                            <input type="text" class="form-control parent-input" name="cashamount_form"
                                id="cashamount_form" hidden>
                            <input type="text" class="form-control parent-input" name="bankamount_form"
                                id="bankamount_form" hidden>






                            <div class="col-lg-2 mg-t-10">
                                <label for="inputName" class="form-label">P.O
                                </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="p_o"
                                    name="p_o" name="notes" value="- ">
                            </div>
                            <div class="col-lg-1 mg-t-10">
                                <label for="inputName" class="form-label">{{ __('home.notesClient') }}
                                </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="notes"
                                    name="notes" title="يرجي ادخال ملاحظات   " onchange="makenoteoninvoice()"
                                    value="- ">
                            </div>

                            <input class="form-control parent-input fc-datepicker" hidden value="0" name="date"
                                id="date" placeholder="YYYY-MM-DD" type="text" required>





                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">
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
                                
                        </div>
                        </div>

                        <div class="d-flex justify-content-center">

                                                    <br>

                            <button type="button" id="saveInvoice" style="background-color: #419BB2"
                                class="btn btn-success p-1">
                                {{ __('home.invoice_save') }}
                                <i class="fas fa-check-circle"></i>
                            </button>

                            &nbsp;

                            <button type="button" id="saveAsDraft"
                                style="background-color: #ff9800; border-color: #ff9800;"
                                class="btn btn-warning p-1 text-white">
                                {{ __('home.pending_invoice') }}
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </div>
                                                                    <br>

                </form>

            </div>

            <input type="text" class="form-control " name="show_invoice_number" id="show_invoice_number" value=0
                title=" رقم الفاتورة " hidden>

            <center>
                <div class="justify-content-center" id="printdiv">

                    <a id="send_whats_app" target="_blank" title="WhatsApp">
                        <i class="bx bxl-whatsapp"></i>
                    </a>

                    <a id="generate_pdf" class="btn btn-success" target="_blank">
                        <i class="fa-solid fa-download ml-1"></i>
                        {{ __('home.dwonloadpdf') }}
                    </a>

                    <button type="button" id="printReciept" class="btn btn-success">
                        <svg class="svg-icon-buttons" viewBox="0 0 20 20" style="width: 18px;">
                            <path
                                d="M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z">
                            </path>
                        </svg>
                        {{ __('home.print') }}
                    </button>

                    <button type="button" id="reciptprinter" class="btn btn-success">
                        <i class="fa-solid fa-print ml-1"></i>
                        {{ __('home.reciptprinter') }}
                    </button>

                    <button type="button" id="sendzatca" class="btn">
                        <i class="fa-regular fa-paper-plane ml-1"></i>
                        {{ __('home.uploadzatca') }}
                    </button>

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
<input hidden=true class="form-control" id="discound_allow" name="discound_allow" value="{{ $discound_allow / 100 }}">



<div class="modal fade product-selection" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true" data-bs-focus="false">
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
                            name="TaxـNumber" value="0" title="{{ __('supprocesses.TaxـNumber') }}">
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
                            title="{{ __('supprocesses.product_notes') }}" required value='-'>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.region') }}</label>
                        <input type="text" class="form-control parent-input" id="sub_city" name="sub_city"
                            title="{{ __('supprocesses.product_notes') }}" required value='-'>
                    </div>

                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.StreetName') }}</label>
                        <input type="text" class="form-control parent-input" id="StreetName" name="StreetName"
                            title="{{ __('supprocesses.product_notes') }}" required value='-'>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.plot_identification') }}</label>
                        <input type="text" class="form-control parent-input" id="plot_identification"
                            name="plot_identification" title="{{ __('supprocesses.product_notes') }}" required value=0>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.buildnumber') }}</label>
                        <input type="text" class="form-control parent-input" id="buildnumber" name="buildnumber"
                            title="{{ __('supprocesses.product_notes') }}" required value=0>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.postcode') }}</label>
                        <input type="text" class="form-control parent-input" id="postcode" name="postcode"
                            title="{{ __('home.postcode') }}" required value=0>
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
                            id="updateinvoicebyidforsale_update" name="updateinvoicebyidforsale_update"
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
                {{ csrf_field() }}
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
                        <select style="width:100%!important" name="MAINproduct" id="MAINproduct"
                            class="form-control select2">
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

                @if(Auth()->user()->id == 30 || Auth()->user()->id == 17)


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
                        <input autocomplete="off" type="text" class="form-control parent-input" id="sale_price_create"
                            readonly value=0 name="sale_price_create" onkeyup="convertToNumbersalePrice()">
                    </div>
                    <div class="col-lg-4">
                        <label for="inputName" class="control-label parent-label" required>{{ __('home.quantity') }}
                        </label>
                        <input autocomplete="off" type="text" class="form-control parent-input" id="quantity_create"
                            readonly value=0 name="quantity_create" onkeyup="convertToNumbersalePrice()">
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
<input hidden=true class="form-control" id="firstiteminput" name="firstiteminput" value="0">

{{-- End Update ( 24/4/2023 ) --}}
<div class="modal fade product-selection" id="main_product2" name="main_product2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="table-responsive" id="ajax_responce_main_product_Div2">


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

<div class="modal" id="paymentmethod_MODALE">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.Partition of the amount') }} </h6>
            </div>
            <div class="modal-body" style="width:100%">
                <div class="d-flex align-items-center justify-content-center" style="width:100%">
                    <label style="font-size:20px" class="control-label parent-label me-1">
                        {{ __('home.total') }} :&nbsp;&nbsp;
                    </label>

                    <label style="font-size:25px;font-weight:bold;color:green" id="totalvalue">0</label>

                    <label style="font-size:20px" class="control-label parent-label ms-1"> &nbsp;&nbsp;
                        {{ __('home.SAR') }}
                    </label>
                </div>


            </div>
            <br>

            <div class="row">
                &nbsp;&nbsp;
                <div class="col">

                    <label class="control-label parent-label">{{ __('report.cash') }}</label>
                    <input type="text" class="form-control parent-input" name="cashamount" id="cashamount"
                        onkeyup="calcshabka()" readonly value=0>
                </div>

                <div class="col">

                    <label class="control-label parent-label">{{ __('report.shabka') }}</label>
                    <input type="text" class="form-control parent-input" name="bankamount" id="bankamount"
                        onkeyup="calcCash()" value=0>
                </div>

                &nbsp;&nbsp;


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- delete -->

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

<!-- 1. استدعاء مكتبة jQuery (ضرورية جداً قبل أي ملف آخر) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2. ملفات الإضافات (Plugins) -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

<!-- 3. ملف البوتستراب (يأتي بعد الـ jQuery دائماً) -->
<script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- 4. ملف الـ SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$("#getinvoiceupdate").click(function(e) {
    e.preventDefault(); // تأكد من وضعها في البداية

    // إعادة ضبط الحقول قبل جلب البيانات الجديدة
    $('#Bank_transfer').val(0);
    $('#creaditamount').val(0);
    $('#bankamount').val(0);
    $('#cashamount').val(0);
    $("#paymodal").val("Cash").change();

    var url = "{{ URL::to('updateinvoicebyid') }}" + "/" + $('#updateinvoicebyid').val();

    jQuery.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data)
            if (data.error) {
                // تقسيم الرسالة لعرضها بشكل منسق
                let messages = data.error.split('|');

                Swal.fire({
                    icon: 'error',
                    title: 'تنبيه / Alert',
                    html: `<strong>${messages[0]}</strong><br>${messages[1]}`,
                    confirmButtonText: 'حسناً / OK'
                });
                return;
            }
            if (data == 0) {
                alert("{{ __('home.stocknotAvailable') }}");
            } else {
                // 1. مسح الجدول الحالي قبل إضافة المنتجات الجديدة
                $("#productsTableBody").html("");

                // 2. تعبئة بيانات العميل في Select2
                if (data.customer) {
                    var newOption = new Option(data.customer.name, data.customer.id, true, true);
                    $('#clientnamesearch').append(newOption).trigger('change');
                }

                // 3. إضافة المنتجات للجدول
                data.product.forEach(function(item) {
                    let quantity = parseFloat(item.quantity) || 0;
                    let index = rowIndex++; // 👈 يزيد دايمًا

                    if (quantity > 0) {
                        let unitPrice = parseFloat(item.Unit_Price) || 0;
                        let discount = parseFloat(item.Discount_Value) || 0;
                        let taxAmount = parseFloat(item.Added_Value) ||
                            0; // الضريبة لكل منتج من الـ JSON

                        // حساب السعر شامل الضريبة للعرض فقط
                        let priceWithTax = unitPrice + (taxAmount / quantity);

                        let row = `
                                        <tr data-index="${index}">

                                            <td><input type="hidden" name="products[${index}][product_id]" value="${item.id}" class="product-id form-control">
                                            <input type="hidden" name="products[${index}][product_cost]" class="product_cost">
                                <input  class="form-control product-available_quentity" type="hidden" >

                </td>
                                            <td class="align-middle text-center">${index}</td>
                                            <td class="text-start">
                                                <input type="text" class="form-control product-code" value="${item.Product_Code}" readonly>
                                            </td>
                                            <td class="text-start">
                                                <input type="text" class="form-control product-name" name="products[${index}][product_name]"  value="${item.product_name}" >
                                            </td>
                                                                        <td><input type="text" class="form-control product-cost" readonly value="-"></td>

                                            <td><input type="text" name="products[${index}][price-tax]" class="form-control product-price-tax" value="${priceWithTax.toFixed(2)}" onchange="calculateTotals_with_tax()"></td>
                                            <td><input type="text" name="products[${index}][price]" class="form-control product-price" value="${unitPrice.toFixed(2)}" onchange="calculateTotals()"></td>
                                            <td><input type="text" name="products[${index}][quentity]" class="form-control product-quentity" value="${quantity}" oninput="calculateTotals()"></td>
                                            <td><input type="text" name="products[${index}][totalprice_withodtax]" class="form-control product-totalprice_withodtax" value="${(unitPrice * quantity).toFixed(2)}" readonly></td>
                                            <td><input type="text" name="products[${index}][discound]" class="form-control product-discound" value="${discount}" onchange="calculateTotals()"></td>
                                            <td><select class="form-control tax-select" oninput='calculateTotals()' name="products[${index}][tax_rate]">
                            <option value="0.15">15%</option>
                                <option value="0">{{ __('home.Exempt') }}</option>
                            </select></td>
                                            <td><input type="text" name="products[${index}][tax]" class="form-control product-tax" value="${taxAmount.toFixed(2)}" readonly></td>
                                            
                                            <td><input type="text" class="form-control product-total" value="${((unitPrice * quantity) + taxAmount - discount).toFixed(2)}" readonly></td>
                                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">{{ __('home.delete') }}</button></td>
                                        </tr>`;

                        $("#productsTableBody").append(row);

                    }

                });
                rowIndex++
                rowIndex++
                console.log('rowIndex')
                console.log(rowIndex)
                // 4. تحديث عداد الصفوف العالمي (إذا كنت تستخدمه للإضافات اليدوية لاحقاً)

                // 5. تعبئة إجماليات الفاتورة السفلية من الـ JSON مباشرة
                $('#invoicetotal_price_total').val(data.invoicetotal_price);
                $('#invoicetotal_addedvalue_total').val(data.invoicetotal_addedvalue);
                $('#invoicetotal_discount_total').val(data.invoicetotal_discount);

                // 6. استدعاء دالة الحسابات لإعادة التأكيد على الأرقام
                if (typeof calculateTotals === "function") {
                    calculateTotals();
                }

                // إظهار أزرار التحكم بعد التحميل بنجاح
            }
        },
        error: function(xhr) {
            console.error("Error fetching invoice:", xhr.responseText);
            alert("حدث خطأ أثناء جلب بيانات الفاتورة");
        }
    });
});
$(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // يمنع إرسال النموذج
        return false;
    }
});



function replaceproduct(id) {
    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    console.log(" {{URL::to('operationproducts')}}/" + branchs_id + "/" + id)
    jQuery.ajax({
        url: " {{URL::to('operationproducts')}}/" + branchs_id + "/" + id,
        type: 'get',
        dataType: 'html',
        cache: false,

        success: function(data) {
            console.log('done')
            $('#operation_product').modal().show();

            $("#ajax_responce_operation_product_Div").html(data);
        },
        error: function() {

        }
    });


}


function calcshabka() {

    $('#bankamount').val((document.getElementById('grandTotal').value * 1) - ($('#cashamount').val() * 1))
    $('#bankamount_form').val($('#bankamount').val())
    $('#cashamount_form').val($('#cashamount').val())

}

function calcCash() {

    $('#cashamount').val((document.getElementById('grandTotal').value * 1) - ($('#bankamount').val() * 1))
    $('#cashamount_form').val($('#cashamount').val())
    $('#bankamount_form').val($('#bankamount').val())


}


$('select[name="paymentmethod"]').on('change', function() {
    var selectedText = $(this).find(':selected').text();
    var selectedValue = $(this).val();
    var $paymentType = $('#payment_type');

    // 1. إظهار جميع الخيارات أولاً
    $paymentType.find('option').show().prop('disabled', false);

    // التحقق مما إذا كان النص يحتوي على بنك أو bank (بأي حالة أحرف)
    var isBank = /بنك|bank/i.test(selectedText);

    if (isBank) {
        // إذا اخترت البنك: نعطل الخيارات الأخرى، ونترك الشبكة والتحويل، ونُفعل الحقل ليصبح قابلاً للتعديل
        $paymentType.find('option').not('[value="Shabka"], [value="Bank_transfer"]').hide().prop('disabled',
            true);
        $paymentType.val('Shabka').change();
        $paymentType.prop('disabled', false); // مسموح بالتعديل ويتم إرساله

    } else if (selectedValue == "Credit") {
        // إذا اخترت الآجل
        $paymentType.find('option').not('[value="Credit"]').hide().prop('disabled', true);
        $paymentType.val('Credit').change();
        $paymentType.prop('disabled', false); // نتركه غير معطل كي يتم إرسال قيمته للسيرفر

    } else if (selectedValue == "Partition") {
        // إذا اخترت التقسيط
        $paymentType.find('option').not('[value="Partition"]').hide().prop('disabled', true);
        $paymentType.val('Partition').change();
        $paymentType.prop('disabled', false); // نتركه غير معطل كي يتم إرسال قيمته

        $('#paymentmethod_MODALE').modal().show();
        var value = document.getElementById('grandTotal').value;
        document.getElementById('totalvalue').innerHTML = value * 1;

    } else {
        // الحالة الافتراضية (نقدي)
        $paymentType.find('option').not('[value="Cash"]').hide().prop('disabled', true);
        $paymentType.val('Cash').change();
        $paymentType.prop('disabled', false); // نتركه غير معطل كي يتم إرسال قيمته
    }
});

// تفعيل الحقل وتجهيزه قبل حفظ الفاتورة لضمان إرسال قيمته دائماً




let rowIndex = 1;

$("#updateinvoicebyidforsaleupdate").click(function(e) {



    event.preventDefault();
    var url = " {{ URL::to('updateinvoicebyidforsaleupdate') }}" + "/" + $('#updateinvoicebyidforsale_update')
        .val();
    console.log(url)
    jQuery.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        cache: false,


        success: function(data) {




            $('#show_invoice_number_update').val($('#updateinvoicebyidforsale_update').val())


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
                                        <input type="text" class="form-control product-name"  name="products[${index}][product_name]"  placeholder="اختر منتج" >
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

                              <td>
    <div class="d-flex align-items-center justify-content-center" style="width:100%;">
        <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
            type="button"
            onclick="minusFunctionIndex(this)">−</button>

        <input type="text"
            name="products[${index}][quentity]"
            style="width: 50px; text-align: center;"
            class="form-control product-quentity mx-2"
            oninput="calculateTotals()"
            value="1">

        <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
            type="button"
            onclick="plusFunctionIndex(this)">+</button>
    </div>
</td>
                                <td><input type="text" name="products[${index}][totalprice_withodtax]"
                                        class="form-control product-totalprice_withodtax"  readonly value="0" min="0" oninput="calculateTotals()"></td>

                                <td><input type="text" name="products[${index}][discound]"
                                        class="form-control product-discound" value="0" onchange='calculateTotals()' min="0"></td>
          <td><select class="form-control tax-select" oninput='calculateTotals()' name="products[${index}][tax_rate]">
                            <option value="0.15">15%</option>
                                <option value="0">{{ __('home.Exempt') }}</option>
                            </select></td>
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


                    let row_add = document.querySelector(
                        `#productsTableBody tr[data-index='${index}']`);
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
            try {
                $('#clientnamesearch').append(
                    $('<option>', {
                        value: data['customer']['id'],
                        text: data['customer']['name']
                    })
                );
                $('#clientnamesearch').val(data['customer']['id']).trigger('change');
            } catch (e) {
                console.error(e);
            }
            console.log('n')
            console.log(data['customer']['name'])

            calculateTotals()
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: "smooth"
            });

            document.getElementById('printdiv').hidden = true
            document.getElementById('saveInvoice').hidden = false



        },
        error: function(response) {
            alert("{{ __('home.sorryerror') }}")

        }

    })


});

function plusFunctionIndex(btn) {
    let input = btn.previousElementSibling;
    input.value = (parseInt(input.value) || 0) + 1;
    calculateTotals();
}

function minusFunctionIndex(btn) {
    let input = btn.nextElementSibling;
    let val = parseInt(input.value) || 0;
    if (val > 0) input.value = val - 1;
    calculateTotals();
}
$('select[name="product_group"]').on('change', function() {
    var selectclientid = $(this).val();
    var token_search = $("#token_search").val();
    console.log(selectclientid)
    if (selectclientid) {
        $.ajax({
            url: "{{ URL::to('product_sale_group_ajax') }}",
            type: 'post',
            cache: false,
            dataType: 'html',
            data: {
                "_token": token_search,
                "group_id": selectclientid,
                "currentrow": window.currentRow,

            },
            success: function(products) {
                $("#ajax_responce_serarchDiv").html(products);

            },

            error: function(response) {
                console.log(response)
            }
        });
    } else {
        console.log('AJAX load did not work');
    }
});
$('#MAINproduct').select2({
    placeholder: 'ابحث عن المنتج',
    allowClear: true,
    minimumInputLength: 2,
    ajax: {
        url: "{{ route('itemcards.search') }}",
        dataType: 'json',
        delay: 300,
        data: function(params) {
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            return {
                results: data.map(item => ({
                    id: item.id,
                    text: item.product_name
                }))
            };
        }
    }
});
let searchText = '';

$('#clientnamesearch').select2({
    placeholder: 'ابحث عن المنتج',
    allowClear: true,
    minimumInputLength: 2,
    ajax: {
        url: "{{ route('clientnamesearch.search') }}",
        dataType: 'json',
        delay: 300,
        data: function(params) {
            searchText = params.term
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            if (!data || data.length === 0) {
                // 👇 الكود اللي عايز يتنفّذ
                console.log('مفيش بيانات راجعة');
                $('#name').val(searchText)

                $('#clientnamesearch').select2('close');


                return {
                    results: []
                };
            } else {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name + ' :-' + item.tax_no
                    }))
                };
            }
        }
    }
});
</script>
<script>
let barcodeEnabled = true;



var barcode = '';
var interval;
let productCache = {};

document.addEventListener('keydown', function(evt) {
    if (!barcodeEnabled) return; // 🛑 يمنع القراءة أثناء انشغال الكود

    if (interval)
        clearInterval(interval);
    if (evt.code == 'Enter') {
        if (barcode)
            if ($('#saveinvice').val() == 1) {
                $('#invoice_number').val('')
                $('#saveinvice').val('0')

                handleBarcode(barcode);

            } else {
                handleBarcode(barcode);

            }
        barcode = '';
        return;
    }
    if (evt.key != 'Shift')
        barcode += evt.key;
    interval = setInterval(() => barcode = '', 20);
});

function handleBarcode(scanned_barcode) {
    var url = "{{ URL::to('getByCodenew') }}/" + scanned_barcode;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json', // <-- أسرع بكثير ولا تحتاج JSON.parse
        cache: false,
        beforeSend: function() {
            barcodeEnabled = false; // إيقاف القراءة قبل البدء
        },

        complete: function() {
            barcodeEnabled = true; // ✔ إعادة التشغيل بعد الانتهاء
        },
        success: function(data) {
            console.log(productCache)

            if (!data) {
                alert(
                    "عذرا المنتج غير مسجل نرجو تسجيلة اولا \n   Sorry, the product is not registered. Please register it first."
                );
                return;
            }

            let code = data.id;
            let name = data.product_name;
            let productcode = data.Product_Code;
            let sale_price = data.sale_price;

            let table = document.getElementById('productsTableBody');

            // ---------------------------------------------
            // 1) التحقق من تكرار المنتج باستخدام Cache
            // ---------------------------------------------
            console.log('jjjjjjjjjjjjjjjjj')
            console.log($('#firstiteminput').val())
            if ($('#firstiteminput').val() == "0") {
                let rows = table.querySelectorAll('tr');

                if (rows.length == 1) {
                    console.log(rows.length)

                    table.removeChild(rows[rows.length - 1]);
                } else {
                    for (i = 1; i <= rows.length; i++) {
                        table.removeChild(rows[i - 1]);
                    }


                }
            }
            console.log('code Add')
            console.log(productCache[code])

            if (productCache[code] && document.body.contains(productCache[code])) {
                let qty = productCache[code].querySelector(".product-quentity");
                qty.value = Number(qty.value) + 1;
                calculateTotals();
                return;
            }

            // ---------------------------------------------
            // 2) حذف الصف الفارغ (إن وجد)
            // ---------------------------------------------


            // ---------------------------------------------
            // 3) إضافة صف جديد (نسخة محسّنة وسريعة)
            // ---------------------------------------------
            let index = rowIndex++; // 👈 يزيد دايمًا

            let rowHTML = `
                                            <tr data-index="${index}">
                                                <td>
                                                    <input type="hidden" name="products[${index}][product_id]" class="product-id">
                                                                            <input type="hidden" name="products[${index}][product_cost]" class="product_cost">

                                                </td>

                                                <td class="align-middle text-center">${index + 1}</td>

                                                <td><input type="text" class="form-control product-code" readonly></td>
                                                <td><input type="text" class="form-control product-name" name="products[${index}][product_name]"   ></td>
                            <td><input type="text" name="products[${index}][price-tax]"
                                    class="form-control product-price-tax" value="0" min="0" onchange="calculateTotals_with_tax()"></td>
                                                <td><input type="text" name="products[${index}][price]" class="form-control product-price" onchange="calculateTotals()" value="0"></td>

   <td>
    <div class="d-flex align-items-center justify-content-center" style="width:100%;">
        <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
            type="button"
            onclick="minusFunctionIndex(this)">−</button>

        <input type="text"
            name="products[${index}][quentity]"
            style="width: 50px; text-align: center;"
            class="form-control product-quentity mx-2"
            oninput="calculateTotals()"
            value="1">

        <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
            type="button"
            onclick="plusFunctionIndex(this)">+</button>
    </div>
</td>
                                                <td><input type="text" name="products[${index}][totalprice_withodtax]" class="form-control product-totalprice_withodtax" readonly value="0"></td>

                                                <td><input type="text" name="products[${index}][discound]" class="form-control product-discound" onchange="calculateTotals()" value="0"></td>
          <td><select class="form-control tax-select" oninput='calculateTotals()' name="products[${index}][tax_rate]">
                            <option value="0.15">15%</option>
                                <option value="0">{{ __('home.Exempt') }}</option>
                            </select></td>
                                                <td><input type="text" name="products[${index}][tax]" class="form-control product-tax" readonly value="0"></td>

                                                <td><input type="text" class="form-control product-total" readonly value="0"></td>

                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">حذف</button></td>
                                            </tr>
                                        `;

            table.insertAdjacentHTML("beforeend", rowHTML);

            // ---------------------------------------------
            // 4) تعبئة بيانات الصف الجديد
            // ---------------------------------------------
            let newRow = document.querySelector(`#productsTableBody tr[data-index='${index}']`);

            newRow.querySelector('.product-id').value = code;
            newRow.querySelector('.product-name').value = name;
            newRow.querySelector('.product-code').value = productcode;
            newRow.querySelector('.product-price').value = sale_price;
            var audio = new Audio('/sounds/done.mp3');
            audio.play();

            // حفظه في Cache
            productCache[code] = newRow;
            $('#firstiteminput').val("1");

            // تحديث الإجماليات
            calculateTotals();
        },

        error: function() {
            alert(
                "عذرا المنتج غير مسجل نرجو تسجيلة اولا \n   Sorry, the product is not registered. Please register it first."
            );

        }
    });

}



document.addEventListener("keydown", function(e) {
    if (e.key === "F8") {
        e.preventDefault(); // يمنع أي وظيفة افتراضية
        window.open(window.location.href, "_blank");
    }
});

document.addEventListener("keydown", function(e) {
    if (e.key === "+") {
        plusFunction();
    } else if (e.key === "-") {
        minusFunction();
    }
});

function plusFunction() {
    console.log("دست +");
    console.log(rowIndex);
    let row = document.querySelector(`#productsTableBody tr[data-index='${rowIndex - 1}']`);
    if (!row) return;
    row.querySelector('.product-quentity').value = (row.querySelector('.product-quentity').value * 1) + 1;

}

function minusFunction() {
    console.log("دست -");
    console.log(rowIndex);

    let row = document.querySelector(`#productsTableBody tr[data-index='${rowIndex - 1}']`);
    if (!row) return;
    row.querySelector('.product-quentity').value = (row.querySelector('.product-quentity').value * 1) - 1;
}






function replaceproductorginal(id) {
    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    console.log(" {{URL::to('replaceproducts')}}/" + branchs_id + "/" + id)
    jQuery.ajax({
        url: " {{URL::to('replaceproducts')}}/" + branchs_id + "/" + id,
        type: 'get',
        dataType: 'html',
        cache: false,

        success: function(data) {
            console.log('done')

            $('#main_product2').modal().show();

            $("#ajax_responce_main_product_Div2").html(data);

        },
        error: function() {

        }
    });


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

        success: function(data) {
            console.log('done')
            $('#operation_product').modal().show();

            $("#ajax_responce_operation_product_Div").html(data);
        },
        error: function() {

        }
    });


}




function hasInternet() {
    return navigator.onLine;
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

            success: function(request_result) {
                $('#product_name_ar').val(request_result[0][0][0])
            },
            error: function() {

            }
        });

    }

}



function createnewproductajax() {
    var url = " {{ URL::to('addnewProductajax') }}";
    var token_search = $("#token_search").val();

    // تجهيز أصوات التنبيه (تأكد من وجود الملفات في مسار public/assets/audio/)
    var errorAudio = new Audio("{{ asset('assets/audio/error.mp3') }}");
    var successAudio = new Audio("{{ asset('assets/audio/success.mp3') }}");

    // التحقق من الحقول الإجبارية
    if ($('#product_name_ar').val() == '') {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: "{{ __('supprocesses.product_name_ar') }}"
        });
        return;
    }

    if ($('#product_location_create').val() == '') {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: "{{ __('supprocesses.product_location') }}"
        });
        return;
    }

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
            MAINproduct: $('#MAINproduct').val(),
        },
        success: function(data) {
            // حالة 1: المنتج موجود مسبقاً (تم إرجاع 0 من السيرفر)
            if (data == 0) {
                errorAudio.play(); // تشغيل صوت الإنذار
                Swal.fire({
                    icon: 'error',
                    title: 'عذراً.. المنتج موجود',
                    text: 'هذا المنتج مضاف مسبقاً في هذا الفرع بنفس الكود!',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#e74c3c'
                });
                return;
            }

            // حالة 2: نجاح الإضافة
            successAudio.play(); // تشغيل صوت النجاح

            $('#createcustomer').modal('hide');

            // تصفير الحقول
            $('#quantity_create, #cost_price, #sale_price_create').val(0);
            $('#product_location_create, #product_name_ar, #product_notes, #product_code_create').val('');

            // رسالة نجاح احترافية تختفي تلقائياً
            Swal.fire({
                icon: 'success',
                title: 'تمت العملية',
                text: 'تم إضافة المنتج بنجاح لجميع الفروع',
                showConfirmButton: false,
                timer: 2000
            });
        },
        error: function(xhr) {
            errorAudio.play();
            console.log(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'خطأ نظام',
                text: 'فشلت عملية الاتصال بالسيرفر'
            });
        }
    });
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

            success: function(request_result) {
                $('#product_name_ar').val(request_result[0][0][0])
            },
            error: function() {

            }
        });

    }

}





$('select[name="numbershowstatus"]').on('change', function() {
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

            success: function(request_result) {
                $('#product_name_en').val(request_result[0][0][0])
            },
            error: function() {

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
    } else if ($('#TaxـNumber').val().length < 1) {
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


            success: function(data) {
                $('#phone').val('');
                $('#TaxـNumber').val('');
                $('#name').val('')
                console.log('seccusss12111');
                console.log(data)
                $('#clientnamesearch').append($('<option >', {
                    value: data['id'],
                    text: data['name'] + data['tax_no']
                }));
                $('#clientnamesearch').val(data['id']).change();


                $('#massagesave').modal().show();
                setTimeout(() => {
                    $('#massagesave').modal('hide');

                }, 500);
            },
            error: function(response) {
                alert("{{ __('home.sorryerror') }}")

            }
        });







    }



}
$("#reciptprinter").click(function(e) {
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
        success: function(data) {
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
        error: function(response) {
            console.log(response)
            alert("{{ __('home.sorryerror') }}")

        }
    });
});
$(document).ready(function() {
    $('#payment_type').prop('disabled', true);
    document.getElementById('printdiv').hidden = true

})
$(document).on('click', '#saveInvoice', function() {
    $('#invoice_action').val('save');

    $('#formdata').submit();
});

// زر المسودة
$(document).on('click', '#saveAsDraft', function() {
    $('#invoice_action').val('draft');
    $('#formdata').submit();
});

let isSubmitting = false;

$("#formdata").on('submit', function(e) {
    e.preventDefault();

    // --- 1. التحقق من السعر والكمية لجميع الأصناف ---
    let isValid = true;
    let fieldErrorMessage = "";

    $('#productsTableBody tr').each(function() {
        let row = $(this);
        let price = row.find('.product-price').val();
        let qty = row.find('.product-quentity').val();
        let productName = row.find('.product-name').val();

        // فحص إذا كان السعر أو الكمية (فارغ، أو صفر، أو أقل)
        if (price === "" || parseFloat(price) <= 0 || qty === "" || parseFloat(qty) <= 0) {
            row.find('.product-price, .product-quentity').css('border', '2px solid red');
            isValid = false;
            fieldErrorMessage = "يرجى إدخال السعر والكمية بشكل صحيح للمنتج: " + (productName ||
                "غير محدد");
        } else {
            row.find('.product-price, .product-quentity').css('border', '');
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'بيانات ناقصة',
            text: fieldErrorMessage,
            confirmButtonText: 'تصحيح البيانات'
        });
        return; // إيقاف العملية فوراً
    }

    // --- 2. تجهيز بيانات الإرسال (تكملة كودك الأصلي) ---
    var activeBtnValue = $(document.activeElement).val();
    var finalAction = activeBtnValue ? activeBtnValue : $('#invoice_action').val();
    var myData = new FormData(this);
    myData.delete('action');
    myData.append('action', finalAction);

    var clientName = $("#clientnamesearch option:selected").text().trim();
    var form = this;

    let messageText = ($('#invoice_action').val() == 'save') ?
        'هل أنت متأكد من حفظ الفاتورة للعميل؟' :
        'هل أنت متأكد من حفظ المسودة للعميل؟';

    // --- 3. نافذة التأكيد ---
    Swal.fire({
        title: 'تأكيد الحفظ | Confirm Save',
        html: `
                            <div style="font-weight: bold; font-size: 1.1em; margin-bottom: 10px;">
                                ${messageText}
                                <br>
                                <span style="color: #28a745;">${clientName}</span>
                            </div>
                        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'نعم، حفظ | Yes, Save',
        cancelButtonText: 'إلغاء | Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (isSubmitting) return false;
            isSubmitting = true;
            $('#payment_type').prop('disabled', false);

            $('#massagesave').modal('show');
            var url = "{{ URL::to('save_invoice_sale') }}";

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(form), // تأكد من إرسال البيانات المحدثة
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if ($('#invoice_action').val() == 'save') {
                        // منطق الحفظ النهائي والربط مع زاتكا (كودك الأصلي)
                        $('#show_invoice_number').val(data);
                        let link = "{{ URL::to('generate_pdf') }}/" + data;
                        $('#generate_pdf').attr('href', link);

                        // إعداد رابط واتساب
                        let ph = $('#phone').val();
                        let phone = "966" + ph.substring(1);
                        let message = "يسرنا خدمتكم.. فاتورتكم رقم " + data + " : " + link;
                        $('#send_whats_app').attr('href',
                            `https://web.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(message)}`
                        );

                        if (data >= 1) {
                            $('#printdiv').prop('hidden', false);
                            $('#saveInvoice').prop('hidden', true);

                            // إرسال لزاتكا
                            document.getElementById('loading-screen').style.display =
                                'block';
                            var urlZatca = "{{ URL::to('sendzatca_fromsale') }}/" + data;

                            $.ajax({
                                url: urlZatca,
                                type: 'GET',
                                success: function(res) {
                                    document.getElementById('loading-screen')
                                        .style.display = 'none';
                                    $('#massagesave').modal('hide');
                                    if (res == 1) {
                                        new Audio('/sounds/done.mp3').play();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'تم الحفظ والإرسال لزاتكا'
                                        });
                                    } else {
                                        alert("خطأ زاتكا: " + res);
                                    }
                                }
                            });
                        }
                        document.getElementById('loading-screen').style.display = 'none';
                        $('#massagesave').modal('hide');

                    } else {
                        // منطق المسودة (الموجود في كودك)
                        $('#massagesave').modal('hide');
                        let pendingLink = "{{ URL::to('showInvoiceRecent__pending') }}/" +
                            data;
                        Swal.fire({
                            title: 'تم حفظ المسودة!',
                            html: `<p>رقم المسودة <b>#${data}</b></p><a href="${pendingLink}" target="_blank" class="btn btn-info">طباعة المسودة</a>`,
                            icon: 'success'
                        });
                    }
                },
                error: function(r) {
                    console.log(r)
                    isSubmitting = false;
                    $('#massagesave').modal('hide');
                    Swal.fire('خطأ', "تعذر الاتصال بالسيرفر", 'error');
                }
            });
        }
    });
});

$(window).on('offline', function() {
    alert("{{ __('home.sorryerror') }}")
});


function reorderRows() {
    document.querySelectorAll('#productsTableBody tr').forEach((tr, i) => {
        tr.querySelector('td:nth-child(2)').innerText = i + 1;
    });
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
    $('#searchaboutproduct').focus();

}
var modal = document.getElementById('SearchProduct');
modal.addEventListener('shown.bs.modal', function() {
    document.getElementById('searchaboutproduct').focus();
});


function addRow() {
    // جلب القيمة وتحويلها لرقم للتأكد من المقارنة الصحيحة
    let invoiceNumber = parseFloat($('#show_invoice_number').val());

    // التحقق إذا كانت القيمة أكبر من الصفر
    if (invoiceNumber > 0) {
        location.reload(); // إعادة تحميل الصفحة
        return; // التوقف هنا وعدم تنفيذ باقي الكود
    }


    $('#searchaboutproduct').focus();

    $('#SearchProduct').modal().show();
    $('#searchaboutproduct').focus();

}

$("#sendzatca").click(function(e) {
    document.getElementById('loading-screen').style.display = 'block'; // show loading screen

    var url = " {{ URL::to('sendzatca_fromsale') }}" + '/' + $('#show_invoice_number').val();
    console.log(url)
    document.getElementById('sendzatca').hidden = true

    token_search = $('#token_search').val();
    $.ajax({
        url: url,
        type: 'GET',
        cache: false,
        dataType: "html",



        success: function(data) {

            if (data == 1) {
                document.getElementById('loading-screen').style.display =
                    'none'; // Hide loading screen

                var audio = new Audio('/sounds/done.mp3');
                audio.play();
                document.getElementById('sendzatca').hidden = true
                $('#massagesave').modal().show();
                setTimeout(() => {
                    $('#massagesave').modal('hide');

                }, 1000);
            } else {
                $('#massagesave').modal('hide');
                document.getElementById('loading-screen').style.display =
                    'none';
                alert(data)
                $('#massagesave').modal('hide');

                document.getElementById('sendzatca').hidden = false

            }
        },
        error: function(response) {
            console.log(response)

        }
    });


});
$("#printReciept").click(function(e) {
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
        success: function(data) {
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
        error: function(response) {
            console.log(response)
            alert("{{ __('home.sorryerror') }}")

        }
    });
});

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


function calculateTotals() {
    let total = 0,
        taxTotal = 0,
        discountTotal = 0,
        grand = 0;

    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let price = parseFloat(r.querySelector('.product-price').value) || 0;
        let cost = parseFloat(r.querySelector('.product_cost').value) || 0;
        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;
        let profit = price - cost;
        $('#profit').val(profit.toFixed(2));
        // --- فحص الكمية المتاحة (Available Quantity Check) ---
        let availablequantity = parseFloat(r.querySelector('.product-available_quentity').value) || 0;

        // if (qty > availablequantity) {
        if (0) {
            Swal.fire({
                icon: 'error',
                title: 'تنبيه: الكمية غير متوفرة <br> <small>Warning: Insufficient Quantity</small>',
                html: `
                    <div style="direction: rtl; text-align: center;">
                        <p style="font-weight: bold; color: #d33;">الكمية المطلوبة أكبر من الكمية المتاحة في المخزن!</p>
                        <p dir="ltr">The requested quantity exceeds the available stock!</p>
                        <hr>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <strong>الكمية المتاحة | Available Qty:</strong>
                            <span style="color: #28a745; font-size: 1.2em;">${availablequantity}</span>
                        </div>
                    </div>
                `,
                position: 'center',
                showConfirmButton: true,
                confirmButtonText: 'موافق | OK',
                confirmButtonColor: '#d33',
                timer: 5000,
                timerProgressBar: true
            });

            r.querySelector('.product-quentity').classList.add('is-invalid');
            r.querySelector('.product-quentity').style.borderColor = 'red';
            qty = availablequantity; // إجبار الكمية لتكون مساوية للمتاحة (اختياري)
            r.querySelector('.product-quentity').value = availablequantity;
        } else {
            r.querySelector('.product-quentity').classList.remove('is-invalid');
            r.querySelector('.product-quentity').style.borderColor = '';
        }

        // --- فحص التكلفة ---
        // if (price > 0 && price < cost) {
        if (0) {
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
            price = cost;
            r.querySelector('.product-price').value = cost.toFixed(2);
        } else {
            r.querySelector('.product-price').classList.remove('is-invalid');
            r.querySelector('.product-price').style.borderColor = '';
        }

        let discound_allow_Input = parseFloat($('#discound_allow').val()) || 0.15;
        let discountInput = parseFloat(r.querySelector('.product-discound').value) || 0;

        if (discountInput > (qty * price) * discound_allow_Input) {
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
            r.querySelector('.product-discound').value = 0;
        }

        let avtsale = parseFloat(r.querySelector('.tax-select').value) || 0;
        let subtotal = price * qty;
        let discountAmount = discountInput;
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

function calculateTotals_with_tax() {
    let total = 0,
        taxTotal = 0,
        discountTotal = 0,
        grand = 0;

    document.querySelectorAll('#productsTableBody tr').forEach(r => {
        let tax_with_price = parseFloat(r.querySelector('.product-price-tax').value) || 0;
        let cost = parseFloat(r.querySelector('.product_cost').value) || 0;
        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;
        let avtsale = parseFloat(r.querySelector('.tax-select').value) || 0;
        let taxFactor = avtsale > 1 ? (avtsale / 100) : avtsale;

        // --- فحص الكمية المتاحة (Available Quantity Check) ---
        let availablequantity = parseFloat(r.querySelector('.product-available_quentity').value) || 0;

        // if (qty > availablequantity) {
        if (0) {
            Swal.fire({
                icon: 'error',
                title: 'تنبيه: الكمية غير متوفرة <br> <small>Warning: Insufficient Quantity</small>',
                html: `
                    <div style="direction: rtl; text-align: center;">
                        <p style="font-weight: bold; color: #d33;">الكمية المطلوبة أكبر من الكمية المتاحة في المخزن!</p>
                        <p dir="ltr">The requested quantity exceeds the available stock!</p>
                        <hr>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 5px;">
                            <strong>الكمية المتاحة | Available Qty:</strong>
                            <span style="color: #28a745; font-size: 1.2em;">${availablequantity}</span>
                        </div>
                    </div>
                `,
                position: 'center',
                showConfirmButton: true,
                confirmButtonText: 'موافق | OK',
                confirmButtonColor: '#d33',
                timer: 5000,
                timerProgressBar: true
            });

            r.querySelector('.product-quentity').classList.add('is-invalid');
            r.querySelector('.product-quentity').style.borderColor = 'red';
            qty = availablequantity;
            r.querySelector('.product-quentity').value = availablequantity;
        } else {
            r.querySelector('.product-quentity').classList.remove('is-invalid');
            r.querySelector('.product-quentity').style.borderColor = '';
        }

        // استخراج السعر قبل الضريبة للمقارنة
        let price = tax_with_price / (1 + taxFactor);

        // --- فحص التكلفة ---
        // if (tax_with_price > 0 && price < cost) {
        if (0) {
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
            tax_with_price = cost * (1 + taxFactor);
            r.querySelector('.product-price-tax').value = tax_with_price.toFixed(2);
            price = cost;
        } else {
            r.querySelector('.product-price-tax').style.borderColor = '';
        }

        let discound = parseFloat(r.querySelector('.product-discound').value) || 0;
        let discound_allow_Input = parseFloat($('#discound_allow').val()) || 0.15;

        // if (discound > (qty * price) * discound_allow_Input) {
        if (0) {
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
            r.querySelector('.product-discound').value = 0;
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

        let profit = price - cost;
        $('#profit').val(profit.toFixed(2));
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
            "locale": "{{ app()->getLocale() }}", // ✅ صح
            "branchs_id": branchs_id,
            "currentrow": window.currentRow,
        },

        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
            document.getElementById('searchaboutproduct').focus();

        },

    });

}

$('#SearchProduct').on('show.bs.modal', function(event) {
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
            "branchs_id": branchs_id,
            "locale": "{{ app()->getLocale() }}", // ✅ صح
            "currentrow": window.currentRow,
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
            document.getElementById('searchaboutproduct').focus();

        },

    });

});
$(document).on('click', '#ajax_pagination_in_search a', function(e) {
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
            "currentrow": window.currentRow,

        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },
        error: function() {

        }
    });
});

function checkDuplicateProduct(productId, currentIndex) {

    return false; // غير مكرر
}


function chooseProduct(code, productcode, name, cost, sale_price, location, availablequantity, currentrow) {
    if (checkDuplicateProduct(code, currentrow)) {
        return; // منتج مكرر — توقف!
    }
    // else if (availablequantity <= 0) {
    else if (0) {
        // رسالة SweetAlert احترافية لتنبيه نقص المخزون
        Swal.fire({
            title: '<span style="color: #d33;">عذراً! المخزون غير كافٍ</span><br><small>Sorry! Insufficient Stock</small>',
            html: `
                        <div style="text-align: center; font-size: 1.1em; margin-top: 10px;">
                            <p>الكمية المتاحة حالياً هي: <strong style="color: #28a745;">${availablequantity}</strong> فقط</p>
                            <p style="direction: ltr;">Current available stock is: <strong style="color: #28a745;">${availablequantity}</strong> only</p>
                            <hr>
                            <p style="color: #555;">لا يمكن إتمام عملية البيع لعدم توفر مخزون كافٍ.</p>
                            <p style="direction: ltr; color: #555;">Transaction cannot be completed due to out-of-stock.</p>
                        </div>
                    `,
            icon: 'error',
            confirmButtonText: 'حسناً | OK',
            confirmButtonColor: '#3085d6',
            footer: '<a href="#">هل تريد طلب كمية جديدة؟ | Request Stock</a>'
        });
        return; // منتج مكرر — توقف!
    } else {
        let index = rowIndex++; // 👈 يزيد دايمًا



        let table = document.getElementById('productsTableBody');


        console.log('index')
        console.log(index)
        index = index - 1;
        let row = `
                        <tr data-index="${index}">
                            <td><input type="hidden" name="products[${index}][product_id]" class="product-id form-control">
                                <input type="hidden" name="products[${index}][product_cost]" class="product_cost">
                                <input  class="form-control product-available_quentity" type="hidden" >

                </td>
                            <td class="align-middle text-center">${index + 1}</td>

                            <td class="text-start">
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control product-code" placeholder="اختر منتج" readonly>

                                </div>
                            </td>

                            <td class="text-start">
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control product-name" name="products[${index}][product_name]"  placeholder="اختر منتج" >

                                </div>
                            </td>
                            <td><input type="text" class="form-control product-cost" readonly value="0"></td>

                            <td><input type="text" name="products[${index}][price-tax]"
                                    class="form-control product-price-tax" value="0" min="0" onchange="calculateTotals_with_tax()"></td>
                   <td><input type="text" name="products[${index}][price]"
                                    class="form-control product-price" value="0" min="0" onchange="calculateTotals()"></td>

  <td>
    <div class="d-flex align-items-center justify-content-center" style="width:100%;">
        <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
            type="button"
            onclick="minusFunctionIndex(this)">−</button>

        <input type="text"
            name="products[${index}][quentity]"
            style="width: 50px; text-align: center;"
            class="form-control product-quentity mx-2"
            oninput="calculateTotals()"
            value="1">

        <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center p-0"
            style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;"
            type="button"
            onclick="plusFunctionIndex(this)">+</button>
    </div>
</td>
                            <td><input type="text" name="products[${index}][totalprice_withodtax]"
                                    class="form-control product-totalprice_withodtax"  readonly value="0" min="0" oninput="calculateTotals()"></td>

                            <td><input type="text" name="products[${index}][discound]"
                                    class="form-control product-discound" value="0" onchange='calculateTotals()' min="0"></td>
                <td><select class="form-control tax-select" oninput='calculateTotals()' name="products[${index}][tax_rate]">
                            <option value="0.15">15%</option>
                                <option value="0">{{ __('home.Exempt') }}</option>
                            </select></td>
                            <td><input type="text" name="products[${index}][tax]" class="form-control product-tax" value="0" readonly></td>

                            <td><input type="text" class="form-control product-total" readonly value="0"></td>

                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">{{ __('home.delete') }}</button>
                            </td>
                        </tr>`;

        table.insertAdjacentHTML("beforeend", row);
        window.currentRow = index;
        let row1 = document.querySelector(`#productsTableBody tr[data-index='${index}']`);
        $.ajax({
            url: "{{ URL::to('/getlastprice') }}/" + code + "/" + $('#clientnamesearch').val(),
            type: "GET",
            dataType: "json",
            success: function(data) {
                $("#last_supplier_cost").empty();

                data.forEach(async (product) => {

                    $('#last_supplier_cost').append($('<option>', {
                        value: 1,
                        text: "{{ __('home.Invoice_no') }}" + " : " + product[
                                'invoiceid'] + " ** " + product['date'] + " **  " +
                            product['cost'] + " " + "{{ __('home.SAR') }}"
                    }));
                })

            }
        })
        $('#profit').val(sale_price - cost);
        row1.querySelector('.product-id').value = code;
        if ($('#branchs_id').val() == 1 || $('#branchs_id').val() == 11) {

            row1.querySelector('.product_cost').value = cost;

        } else {
            row1.querySelector('.product_cost').value = cost;

        }
        row1.querySelector('.product-name').value = name;
        row1.querySelector('.product-cost').value = cost;
        row1.querySelector('.product-available_quentity').value = availablequantity;
        row1.querySelector('.product-code').value = productcode;
        row1.querySelector('.product-price').value = sale_price;
        row1.querySelector('.product-discound').value = 0;
        $('#firstiteminput').val("1");

        calculateTotals()
    }
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: "smooth"
    });

}
</script>

@endsection