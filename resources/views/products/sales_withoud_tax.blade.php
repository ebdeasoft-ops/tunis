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
    .summary-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }

    .parent-input {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 15px;
        background-color: #fff !important; /* لجعلها تبدو نظيفة حتى وهي readonly */
        transition: all 0.3s ease;
        font-size: 1.1rem;
        font-weight: bold;
        color: #212529;
    }

    /* تمييز حقل الإجمالي النهائي بلون مختلف */
    #grandTotal {
        background-color: #e7f3ff !important;
        border-color: #0d6efd;
        color: #0d6efd;
    }

    /* تأثير بسيط عند تمرير الماوس */
    .parent-input:hover {
        border-color: #ced4da;
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
{{ __('home.sel_product_withoud_tax') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="main-parent">
    <div style="justify-content: space-between !important" class="breadcrumb-header parent-heading ">
        <div class="my-auto"style="width:100%">
<div class="d-flex align-items-center justify-content-between flex-wrap" style="width:100%; gap: 10px;">

    <div class="d-flex align-items-center">
        <h4 class="content-title mb-0 my-auto">{{ __('home.sel_product_withoud_tax') }}</h4>
    </div>

    <div class="d-flex flex-wrap align-items-center" style="gap: 5px;">

        <div class="select-wrapper">
            <select class="form-control select2" name="numbershowstatus" id="numbershowstatus" required>
                <option value="1">{{__('home.shownumberselect')}}</option>
                <option value="0">{{__('home.notshow')}}</option>
            </select>
        </div>

        <button style="background-color: #23395D;" class="btn btn-sm text-white p-2" data-toggle="modal" href="#createcustomer">
            <i class="fa fa-user-plus"></i> {{ __('home.addnewcustomer') }}
        </button>



        <button style="background-color: #23395D;" class="btn btn-sm text-white p-2" data-toggle="modal" href="#createproduct">
            <i class="fa fa-plus"></i> {{ __('supprocesses.addproduct') }}
        </button>

        @can('sales return')
        <button style="background-color: green;" class="btn btn-sm text-white p-2" data-toggle="modal" href="#updateinvoicebyidmodale">
            {{ __('home.updateinvoicebyid') }}
        </button>
        @endcan
    </div>
</div>
            </div><!-- col-4 -->
        </div><!-- col-4 -->
    </div><!-- col-4 -->
</div><!-- col-4 -->








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
          <input hidden=true class="form-control" id="discound_allow" name="discound_allow" value="{{ $discound_allow/100 }}">

                <form enctype="multipart/form-data" method="POST" role="search" name="form-name" id='formdata'
                    autocomplete="off">
                    {{ csrf_field() }}



                    <div style="border-radius: 10px" class="card p-3 my-3">





                        <?php $i = 0; ?>


                        <table class="table-responsive table table-bordered">
                            <thead class="table-dark">
                                <col style="width:0.5%">
                                <col style="width:1%">
                                <col style="width:14%">
                                <col style="width:22%">
                                <col style="width:9%">
                                <col style="width:9%">
                                <col style="width:9%">
                                <col style="width:9%">
                                <col style="width:12%">
                                <col style="width:8%">
                                <thead>
                                    <tr>
                                        <th>- </th>

                                        <th> # </th>
                                        <th>{{ __('home.productNo') }} </th>
                                        <th>{{ __('home.product') }}</th>

                                        <th> {{ __('home.productprice') }} </th>
                                        <th>{{ __('home.quantity') }}</th>
                                        <th>{{ __('home.price') }}</th>
                                        <th>{{ __('home.discount') }}</th>
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
      <div class="row mt-3 p-3" style="background-color: #fcfcfc; border: 1px solid #e1e1e1; border-radius: 8px; justify-content: space-between; align-items: flex-end;">

<div class="col-lg-3">
    <label class="form-label" style="color: #23395D; font-weight: bold; font-size: 13px;">
        <i class="fa fa-file-invoice-dollar ml-1"></i> {{ __('home.discound_on_invoice') }}
    </label>
    <input type="text" id="discound_on_invoice" name="discound_on_invoice"  readonly
           oninput='calculateTotalDiscount()'
           class="form-control"
           style="border: 1px solid #23395D; border-radius: 5px; height: 40px; text-align: center; font-weight: bold; color: #d32f2f;"
           placeholder="0.00">
</div>
                @can('System setting')

    <div id="div_show_profit" class="col-lg-3">
        <label for="profit" class="control-label parent-label" style="color: #23395D; font-weight: bold; font-size: 13px;">
            <i class="fa fa-money-bill-wave ml-1"></i> {{ __('home.profit') }}
        </label>
        <input autocomplete="off" type="text" id="profit" name="profit" value="0" readonly
               class="form-control parent-input"
               style="background-color: #e3f2fd; border: 1px solid #90caf9; border-radius: 5px; height: 40px; text-align: center; font-weight: bold; color: #1565c0;">
    </div>
                    @endcan


    <div class="col-lg-4">
        <label for="last_supplier_cost" class="control-label parent-label" style="color: #23395D; font-weight: bold; font-size: 13px;">
            <i class="fa fa-tag ml-1"></i> {{ __('home.lastpricecustomer') }}
        </label>
        <select class="form-control parent-input" name="last_supplier_cost" id="last_supplier_cost"
                style="background-color: #d4edda; border: 1px solid #28a745; border-radius: 5px; height: 40px; font-weight: 500;">
            </select>
    </div>
</div>
         <div class="row mt-3 summary-section">
    <div class="col-md-4">
        <label class="form-label">
            <i class="bi bi-cash-stack me-1"></i> {{ __('home.the amount') }}
        </label>
        <input readonly type="text" id="totalSum" name="totalSum" class="form-control parent-input text-center">
    </div>

    <div class="col-md-4">
        <label class="form-label">
            <i class="bi bi-tag me-1"></i> {{ __('home.discount') }}
        </label>
        <input type="text" id="totaldiscound" name="totaldiscound" readonly class="form-control parent-input text-center text-danger">
    </div>

    <div class="col-md-4">
        <label class="form-label text-primary">
            <i class="bi bi-calculator me-1"></i> {{ __('home.total') }}
        </label>
        <input type="text" id="grandTotal" name="grandTotal" readonly class="form-control parent-input text-center">
    </div>
</div>


                        <br>


            <input type="number" class="form-control " name="show_invoice_number_update" id="show_invoice_number_update" value=0
                title=" رقم التسليم" hidden>

                        <div class='row'>

                            <div class="col-lg-5 mg-lg-t-10">
                                <label for="inputName" class="form-label">{{ __('home.chooseclient') }}</label>



                                           <select style="width:100%!important" name="clientnamesearch" id="clientnamesearch" class="form-control select2">
                                    <option  value=1 >عميل نقدي CASH CUSTOMER</option>


                            </select>
                            </div><!-- col-4 -->



                      <div class="col-lg-2 mg-t-20 mg-lg-t-0">
    <p class="mg-b-10"> {{ __('home.paymentmethod') }}</p>
    <select class="form-control select2" name="paymentmethod" id="paymentmethod" required>
        @foreach (App\Models\financial_accounts::where('parent_account_number', 4)
            ->where('branchs_id', Auth()->user()->branchs_id)
            ->orWhere('parent_account_number', 5)
            ->where('parent_account_number', '!=', NULL)
            ->where('branchs_id', Auth()->user()->branchs_id)
            ->get() as $section)

            <option value="{{ $section->id }}">
                {{ app()->getLocale() == 'en' ? $section->name_en : $section->name }}
                ({{ $section->account_number }})
            </option>

        @endforeach

        <option value="Partition"> {{ __('home.Partition of the amount') }} </option>
    </select>
</div>
                            <!-- col-4 -->

                            <div class="col-lg-2 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> . </p>
                                <select class="form-control parent-input " name="payment_type" id="payment_type" required>
                                    <option id="Cash" value="Cash"> {{ __('report.cash') }}</option>
                                    <option value="Shabka"> {{ __('report.shabka') }} </option>
                                    <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                                    <option value="Partition"> {{ __('home.Partition of the amount') }} </option>


                                </select>
                            </div>


                                <input type="text" class="form-control parent-input" name="cashamount_form" id="cashamount_form" hidden>
                                <input type="text" class="form-control parent-input" name="bankamount_form" id="bankamount_form" hidden>






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

                                    <input class="form-control parent-input fc-datepicker" hidden value="0" name="date" id="date" placeholder="YYYY-MM-DD" type="text" required>





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
                                <input hidden=true class="form-control" id="rate_system" name="rate_system" value="{{$rate_system}}">
                                    <input hidden=true class="form-control" id="shownumberproduct" name="shownumberproduct" value="1">
                            </div>
                            <br>
                            <br>
                            <div class="d-flex justify-content-center">
                                <button type='submit' style="background-color: #419BB2" id="saveInvoice"
                                    name="saveInvoice" class="btn btn-success p-1">
                                    {{ __('home.invoice_save') }}
                                    <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                        <path fill="none"
                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                        </path>
                                    </svg>
                                </button>
                                &nbsp;

                                <!-- <a style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px"
                                        class="btn btn-success p-1 px-2 fw-bolder"
                                        id="pending_invoice">{{ __('home.pending_invoice') }}&nbsp; <svg style="width: 20px"
                                            class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path fill="none"
                                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                            </path>
                                        </svg></i></a> -->

                            </div>
                </form>

            </div>

            <input type="text" class="form-control " name="show_invoice_number" id="show_invoice_number"   value=0
                title=" رقم التسليم" hidden>

            <center>
                <div class=" justify-content-center" id="printdiv">


                    <button type="button"
                        style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px"
                        id="printReciept" class="btn btn-success p-1 px-2 fw-bolder">
                        {{ __('home.print') }}
                        <svg style="width: 15px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path
                                d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555">
                            </path>
                        </svg>
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
                        <input style="height:32px" type="text" class="form-control parent-input" id="CRN"
                            name="CRN" onkeyup="TaxـNumberConvert() "value=0  title="{{ __('supprocesses.TaxـNumber') }}">
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
                            title="{{ __('supprocesses.product_notes') }}" required   value='-'>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.region') }}</label>
                        <input type="text" class="form-control parent-input" id="sub_city" name="sub_city"
                            title="{{ __('supprocesses.product_notes') }}" required   value='-'>
                    </div>

                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.StreetName') }}</label>
                        <input type="text" class="form-control parent-input" id="StreetName" name="StreetName"
                            title="{{ __('supprocesses.product_notes') }}" required  value='-'>
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
                            title="{{ __('supprocesses.product_notes') }}" required   value=0>
                    </div>
                    <div class="col-lg-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.postcode') }}</label>
                        <input type="text" class="form-control parent-input" id="postcode" name="postcode"
                            title="{{ __('home.postcode') }}"  required  value=0>
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
            <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special"
                role="document">
                <div class="modal-content modal-content-demo p-3">
                    <form>
                        <div class="modal-header">
                            <h6 class="modal-title"> {{ __('supprocesses.addproduct') }} </h6><button aria-label="Close"
                                class="close close-special" data-dismiss="modal" type="button"><span
                                    aria-hidden="true">&times;</span></button>
                        </div>
                        {{ csrf_field() }}
                        <br>
<label style="font-size:18px; color:red;font-weight:bold;">&nbsp;&nbsp;<input style="font-size:16px; color:yellow;" type="checkbox"  value=0 id="translate_status">&nbsp;&nbsp;{{__('home.active_translate')}}</label>
                        <br>


                        <div class="row mb-2">
                            <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_name_ar') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input"
                                    id="product_name_ar" name="product_name_ar"
                                    title="{{ __('supprocesses.product_name_ar') }}" onkeyup="translateNameToEnglish()"
                                    required>
                            </div>


                            <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_name_en') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input"
                                    id="product_name_en" name="product_name_en"
                                    title="{{ __('supprocesses.product_name_en') }}" onkeyup="translateNameToArbic()"
                                    required>
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
                                    @if(Auth()->user()->branch->id!=$section->id)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="inputName"
                                    class="control-label parent-label">{{ __('home.groups') }}</label>
                                <select style="width:100%!important" name="product_group" id="product_group"
                                    class="form-control select2">
                                    <!--placeholder-->
                                    @foreach (App\Models\products_group::get() as $section)
                                    <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="inputName"
                                    class="control-label parent-label">{{ __('home.MAINproduct') }}</label>
                                <br>
                                <select style="width:100%!important" name="MAINproduct" id="MAINproduct" class="form-control select2">
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

<div class="row">
        <div class="col-lg-4">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.purachesepice') }}
                                </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="cost_price" name="cost_price" value=0 onkeyup="convertToNumberpurchasersPrice()">
                            </div>
                            <div class="col-lg-4">
                                <label for="inputName" class="control-label parent-label" required>{{ __('home.salepice') }} </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="sale_price_create" value=0  name="sale_price_create" onkeyup="convertToNumbersalePrice()">
                            </div>
                            <div class="col-lg-4">
                                <label for="inputName" class="control-label parent-label" required>{{ __('home.quantity') }} </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="quantity_create" value=0   name="quantity_create" onkeyup="convertToNumbersalePrice()">
                            </div>

</div>



                        {{-- 5 --}}
                        <div class="row mb-2">
                            <div class="col-lg-4 mb-2" style="direction: ltr !important;">

                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_location') }}</label>
                                <input dir="ltr" style="direction:LTR !important ;text-align:start!important;"
                                    type="text" class="form-control parent-input" id="product_location_create"
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
                                <input type="text" class="form-control parent-input" id="product_notes"
                                    name="product_notes" title="{{ __('supprocesses.product_notes') }}">
                            </div>

                            <div class="col-lg-4 mb-2">
                                <input type="text" class="form-control parent-input" id="product_name_en"
                                    name="product_name_en" title=" {{ __('supprocesses.product_name_en') }}" hidden>
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
                {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button>
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
                {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button>
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
                        <h6 class="modal-title">  {{ __('home.Partition of the amount') }} </h6>
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
                                    onkeyup="calcCash()"  value=0>
                            </div>

                  &nbsp;&nbsp;


                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('home.cancel') }}</button>
                        <button  data-dismiss="modal"
                            class="btn btn-danger">{{ __('home.confirm') }}</button>
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
<!-- Internal Data tables -->

<!-- Internal Data tables -->
<!-- Internal Data tables -->
<!--Internal  Datatable js -->
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Select2 --}}
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script>

    $("#getinvoiceupdate").click(function (e) {
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
                success: function (data) {
                    console.log(data)
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
                        let rowIndex = 0;
                        data.product.forEach(function (item) {
                            let quantity = parseFloat(item.quantity) || 0;

                            if (quantity > 0) {
                                let unitPrice = parseFloat(item.Unit_Price) || 0;
                                let discount = parseFloat(item.Discount_Value) || 0;
                                let taxAmount = 0; // الضريبة لكل منتج من الـ JSON

                                // حساب السعر شامل الضريبة للعرض فقط
                                let priceWithTax = unitPrice + (taxAmount / quantity);
                                let row = `
                            <tr data-index="${rowIndex}">
                                alert(item.id)
                                <td><input type="hidden" name="products[${rowIndex}][product_id]" value="${item.id}" class="product-id form-control">
                                    <input type="hidden" name="products[${rowIndex}][product_cost]" class="product_cost">
                                </td>
                                <td class="align-middle text-center">${rowIndex + 1}</td>
                                <td class="text-start">
                                    <input type="text" class="form-control product-code" value="${item.Product_Code}" readonly>
                                </td>
                                <td class="text-start">
                                    <input type="text" class="form-control product-name" value="${item.product_name}" name="products[${rowIndex}][product-name]">
                                </td>
                                <td><input type="text" name="products[${rowIndex}][price]" class="form-control product-price" value="${unitPrice.toFixed(2)}" onchange="calculateTotals()"></td>
                                <td><input type="text" name="products[${rowIndex}][quentity]" class="form-control product-quentity" value="${quantity}" oninput="calculateTotals()"></td>
                                <td><input type="text" name="products[${rowIndex}][totalprice_withodtax]" class="form-control product-totalprice_withodtax" value="${(unitPrice * quantity).toFixed(2)}" readonly></td>
                                <td><input type="text" name="products[${rowIndex}][discound]" class="form-control product-discound" value="${discount}" onchange="calculateTotals()"></td>
                                <td><input type="text" class="form-control product-total" value="${((unitPrice * quantity) + taxAmount - discount).toFixed(2)}" readonly></td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">{{ __('home.delete') }}</button></td>
                            </tr>`;

                                $("#productsTableBody").append(row);
                                rowIndex++;
                            }
                        });

                        // 4. تحديث عداد الصفوف العالمي (إذا كنت تستخدمه للإضافات اليدوية لاحقاً)
                        window.rowIndex = rowIndex;

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
                error: function (xhr) {
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






$('select[name="paymentmethod"]').on('change', function () {

    var selectclientid = $(this).val();
    var selectedOption = $(this).find(':selected');
    var parentAccount = selectedOption.data('parent'); // قراءة رقم الحساب الأب
    var text_currnt = selectedOption.text();

    // 💡 التحقق إما عن طريق رقم الحساب الأب (5 للبنك) أو احتواء الاسم على البنك/Bank
    if (parentAccount == 5 || text_currnt.includes('البنك') || text_currnt.toLowerCase().includes('bank')) {

        $('#payment_type').prop("disabled", false);
        $('#Cash').prop("disabled", true);
        document.getElementById('payment_type').style.visibility = 'visible';
        $('#payment_type').val('Shabka').change();
        $('#Credit').prop("disabled", true);

    } else if ($('#paymentmethod').val() == "Credit") {

        $('#payupdate').val(selectclientid).change();
        $('#payment_type').val('Credit').change();

    } else if ($('#paymentmethod').val() == "Partition") {

        $('#payupdate').val(selectclientid).change();
        $('#payment_type').val('Partition').change();
        $('#paymentmethod_MODALE').modal().show();

        var value = document.getElementById('grandTotal').value;
        document.getElementById('totalvalue').innerHTML = value * 1;

        $('#cashamount').val(value);
        $('#bankamount').val(0);
        document.getElementById("bankamount").readOnly = false;
        document.getElementById("cashamount").readOnly = false;
    } else {

        $('#payment_type').val('Cash').change();
        $('#Cash').prop("disabled", false);
    }

    $('#payupdate').val(selectclientid).change();
});


let rowIndex = 1;

    $("#updateinvoicebyidforsaleupdate").click(function(e) {



            event.preventDefault();
            var url = " {{ URL::to('updateinvoicebyidforsaleupdate') }}" + "/" + $('#updateinvoicebyidforsale_update').val();
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

 let index = rowIndex-1

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

                <td><input type="text" name="products[${index}][price]"
                        class="form-control product-price" value="0" min="0" onchange="calculateTotals()"></td>

                <td><input type="text" name="products[${index}][quentity]"
                        class="form-control product-quentity" oninput='calculateTotals()' value=1></td>

                <td><input type="text" name="products[${index}][totalprice_withodtax]"
                        class="form-control product-totalprice_withodtax"  readonly value="0" min="0" oninput="calculateTotals()"></td>

                <td><input type="text" name="products[${index}][discound]"
                        class="form-control product-discound" value="0" onchange='calculateTotals()' min="0"></td>


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
                   try {
    $('#clientnamesearch').append(
        $('<option>', { value: data['customer']['id'], text:data['customer']['name'] })
    );
    $('#clientnamesearch').val(data['customer']['id']).trigger('change');
} catch (e) {
    console.error(e);
}
console.log('n')
console.log(data['customer']['name'])

              calculateTotals()
            window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });

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
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
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
            data: function (params) {
                searchText=params.term
                return { q: params.term };
            },
            processResults: function (data) {
                    if (!data || data.length === 0) {
        // 👇 الكود اللي عايز يتنفّذ
        console.log('مفيش بيانات راجعة');
        $('#name').val( searchText)

                $('#clientnamesearch').select2('close');


        $('#createcustomer').modal().show();
        return { results: [] };
    }
else{
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

        document.addEventListener('keydown', function (evt) {
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
                beforeSend: function () {
                    barcodeEnabled = false; // إيقاف القراءة قبل البدء
                },

                complete: function () {
                    barcodeEnabled = true; // ✔ إعادة التشغيل بعد الانتهاء
                },
                success: function (data) {
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
                             <td><input type="hidden" name="products[${index}][product_id]" class="product-id form-control">
                        <input type="hidden" name="products[${index}][product_cost]" class="product_cost">

            </td>

                                <td class="align-middle text-center">${index + 1}</td>

                                <td><input type="text" class="form-control product-code" readonly></td>
                                <td><input type="text" class="form-control product-name" readonly></td>
            <td><input type="text" name="products[${index}][price-tax]"
                    class="form-control product-price-tax" value="0" min="0" onchange="calculateTotals_with_tax()"></td>
                                <td><input type="text" name="products[${index}][price]" class="form-control product-price" onchange="calculateTotals()" value="0"></td>

                 <td>  <div class="input-group input-group-sm" style="width:100%;">
        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;" type="button"
            onclick="minusFunctionIndex(this)">−</button>
            &nbsp;

                                <input type="text" name="products[${index}][quentity]"  style="width:50px; text-align:center;" class="form-control product-quentity" oninput="calculateTotals()" value="1">
&nbsp;

        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;" type="button"
            onclick="plusFunctionIndex(this)">+</button>
    </div>

    </td>
                                <td><input type="text" name="products[${index}][totalprice_withodtax]" class="form-control product-totalprice_withodtax" readonly value="0"></td>

                                <td><input type="text" name="products[${index}][discound]" class="form-control product-discound" onchange="calculateTotals()" value="0"></td>


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

                error: function () {
                    alert(
                        "عذرا المنتج غير مسجل نرجو تسجيلة اولا \n   Sorry, the product is not registered. Please register it first."
                    );

                }
            });

        }



document.addEventListener("keydown", function (e) {
  if (e.key === "F8") {
    e.preventDefault(); // يمنع أي وظيفة افتراضية
    window.open(window.location.href, "_blank");
  }
});

document.addEventListener("keydown", function (e) {
  if (e.key === "+") {
    plusFunction();
  }
  else if (e.key === "-") {
    minusFunction();
  }
});

function plusFunction() {
  console.log("دست +");
  console.log(rowIndex);
     let row = document.querySelector(`#productsTableBody tr[data-index='${rowIndex-1}']`);
            if (!row) return;
            row.querySelector('.product-quentity').value = (row.querySelector('.product-quentity').value*1)+1;

}

function minusFunction() {
  console.log("دست -");
    console.log(rowIndex);

     let row = document.querySelector(`#productsTableBody tr[data-index='${rowIndex-1}']`);
            if (!row) return;
            row.querySelector('.product-quentity').value = (row.querySelector('.product-quentity').value*1)-1;
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
                    url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=en&tl=ar&q=" + wordEnglish,
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



function createnewproductajax() {
    var url = " {{ URL::to('addnewProductajax') }}";
    var token_search = $("#token_search").val();

    // تجهيز أصوات التنبيه (تأكد من وجود الملفات في مسار public/assets/audio/)
    var errorAudio = new Audio("{{ asset('assets/audio/error.mp3') }}");
    var successAudio = new Audio("{{ asset('assets/audio/success.mp3') }}");

    // التحقق من الحقول الإجبارية
    if ($('#product_name_ar').val() == '') {
        Swal.fire({ icon: 'warning', title: 'تنبيه', text: "{{ __('supprocesses.product_name_ar') }}" });
        return;
    }

    if ($('#product_location_create').val() == '') {
        Swal.fire({ icon: 'warning', title: 'تنبيه', text: "{{ __('supprocesses.product_location') }}" });
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
            Swal.fire({ icon: 'error', title: 'خطأ نظام', text: 'فشلت عملية الاتصال بالسيرفر' });
        }
    });
}

function translateNameToArbic() {
  const checkbox = document.getElementById('translate_status');

  if (checkbox.checked) {


      var wordEnglish = $('#product_name_en').val();

    jQuery.ajax({
        url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=en&tl=ar&q=" + wordEnglish,
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
        url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=ar&tl=en&q=" + wordarbic,
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
    } else if ($('#TaxـNumber').val().length <1) {
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
    document.getElementById('printdiv').hidden = true

})

let isSubmitting = false;

$("#formdata").on('submit', function (e) {
    e.preventDefault();

    // 1. جلب اسم العميل من السلكت
    var clientName = $("#clientnamesearch option:selected").text().trim();
    var form = this;

    // 2. إظهار نافذة التأكيد (سند تسليم)
    Swal.fire({
        title: 'تأكيد حفظ سند التسليم | Confirm Delivery',
        html: `
            <div style="font-weight: bold; font-size: 1.1em; margin-bottom: 10px;">
                هل أنت متأكد من حفظ سند التسليم للعميل؟
                <br>
                <span style="color: #fd7e14;">${clientName}</span>
            </div>
            <hr>
            <div style="font-size: 0.9em; color: #666;">
                Are you sure you want to save the delivery note for:
                <br>
                <strong>${clientName}</strong>?
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fd7e14', // لون برتقالي مميز لسندات التسليم
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم، حفظ | Yes, Save',
        cancelButtonText: 'إلغاء | Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            // التحقق من حالة الإرسال لمنع التكرار
            if (isSubmitting) {
                return false;
            }

            isSubmitting = true;

            // إظهار مودال التحميل
            $('#massagesave').modal('show');

            var url = "{{ URL::to('save_delivery_sale') }}";

            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                data: new FormData(form),

                success: function (data) {
                    $('#show_invoice_number').val(data);

                    if (data >= 1) {
                        $('#printdiv').prop('hidden', false);
                        $('#saveInvoice').prop('hidden', true);
                        $('#firstiteminput').val("0");

                        setTimeout(() => {
                            $('#massagesave').modal('hide');

                            // تنبيه نجاح نهائي
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحفظ | Saved',
                                text: 'تم حفظ سند التسليم بنجاح | Delivery note saved successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }, 1000);
                    } else {
                        Swal.fire('Error | خطأ', "{{ __('home.sorryerror') }}", 'error');
                        isSubmitting = false;
                        $('#massagesave').modal('hide');
                    }
                },

                error: function (r) {
                    console.log(r);
                    Swal.fire('Error | خطأ', "{{ __('home.sorryerror') }}", 'error');
                    $('#massagesave').modal('hide');
                    isSubmitting = false;
                }
            });
        }
    });
});


$(window).on('offline', function () {
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
modal.addEventListener('shown.bs.modal', function () {
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

$("#printReciept").click(function(e) {
    var url = " {{ URL::to('print_Invoice_withod_tax') }}";
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

    let amountBeforeTax =  $('#discound_on_invoice').val()*1;
        // 3. تقريب الرقم لخانة أو خانتين عشريتين ثم وضعه في الحقل مرة أخرى
      amountBeforeTax=amountBeforeTax.toFixed(2);


    avtsale = $('#avtValue').val();
    totalbefore_discount = document.getElementById('totalSum').value;
    let discountTotal = 0;


    document.querySelectorAll('#productsTableBody tr').forEach(r => {

        let discound = parseFloat(r.querySelector('.product-discound').value) || 0;
        discountTotal += discound;
    });
    discountTotal += amountBeforeTax * 1;

    let grand = (totalbefore_discount - discountTotal) ;
    document.getElementById('totaldiscound').value = discountTotal.toFixed(2);
    document.getElementById('grandTotal').value = grand.toFixed(2);

}
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
        r.querySelector('.product-total').value = totalRow.toFixed(2);

        total += subtotal;
        taxTotal += tax;
        grand += totalRow;
        discountTotal += discountAmount;
    });

    document.getElementById('totalSum').value = total.toFixed(2);
    document.getElementById('totaldiscound').value = discountTotal.toFixed(2);
    document.getElementById('grandTotal').value = grand.toFixed(2);
}


function calculateTotals_with_tax() {
    let total = 0,
        taxTotal = 0,
        discountTotal = 0,
        grand = 0;
                saleprice=0;

    avtsale = $('#avtValue').val();
    document.querySelectorAll('#productsTableBody tr').forEach(r => {
                let tax_with_price = parseFloat(r.querySelector('.product-price-tax').value) || 0;

{

        let qty = parseFloat(r.querySelector('.product-quentity').value) || 0;
        let price =( tax_with_price*100)/((100*avtsale)+100);
        console.log(avtsale)
        console.log(tax_with_price)
        console.log(price)
        let discound = parseFloat(r.querySelector('.product-discound').value) || 0;
        let subtotal = price * qty;
        let tax = ((price * qty) - discound) * avtsale;
        let totalRow = subtotal - discound + tax;
        r.querySelector('.product-price').value = price.toFixed(2);
        r.querySelector('.product-totalprice_withodtax').value = subtotal.toFixed(2);
        r.querySelector('.product-tax').value = tax.toFixed(2);
        r.querySelector('.product-total').value = totalRow.toFixed(2);
                saleprice=price

        total += subtotal;
        taxTotal += tax;
        grand += totalRow;
        discountTotal += discound;



        }
    });
    cost= $('#profit').val( );
var profit = saleprice - cost; $('#profit').val(profit.toFixed(2));
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
}else if (availablequantity<=0) {
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
}else{
        let index = rowIndex++; // 👈 يزيد دايمًا



    let table = document.getElementById('productsTableBody');


console.log('index')
console.log(index)
index=index-1;
    let row = `
        <tr data-index="${index}">
            <td><input type="hidden" name="products[${index}][product_id]" class="product-id form-control">
                        <input type="hidden" name="products[${index}][product_cost]" class="product_cost">

            </td>
            <td class="align-middle text-center">${index + 1}</td>

            <td class="text-start">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control product-code" placeholder="اختر منتج" readonly>

                </div>
            </td>

            <td class="text-start">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control product-name" placeholder="اختر منتج" readonly>

                </div>
            </td>


   <td><input type="text" name="products[${index}][price]"
                    class="form-control product-price" value="0" min="0" onchange="calculateTotals()"></td>

                        <td>  <div class="input-group input-group-sm" style="width:100%;">
        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;" type="button"
            onclick="minusFunctionIndex(this)">−</button>
            &nbsp;

                                <input type="text" name="products[${index}][quentity]"  style="width:50px; text-align:center;" class="form-control product-quentity" oninput="calculateTotals()" value="1">
&nbsp;

        <button    class="btn btn-secondary rounded-circle"
            style="width:32px; height:32px;" type="button"
            onclick="plusFunctionIndex(this)">+</button>
    </div>

    </td>
            <td><input type="text" name="products[${index}][totalprice_withodtax]"
                    class="form-control product-totalprice_withodtax"  readonly value="0" min="0" oninput="calculateTotals()"></td>

            <td><input type="text" name="products[${index}][discound]"
                    class="form-control product-discound" value="0" onchange='calculateTotals()' min="0"></td>


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
    $('#profit').val(sale_price -cost );
    row1.querySelector('.product-id').value = code;
        if($('#branchs_id').val()==1){

    row1.querySelector('.product_cost').value = cost;

    }else{
    row1.querySelector('.product_cost').value = cost;

    }
    row1.querySelector('.product-name').value = name;
    row1.querySelector('.product-code').value = productcode;
    row1.querySelector('.product-price').value = sale_price;
    row1.querySelector('.product-discound').value = 0;
    $('#firstiteminput').val("1");

    calculateTotals()
    }
    window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });

}
</script>

@endsection
