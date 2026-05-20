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
{{ __('home.dlivery') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.dlivery') }}</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
        <div style="width:20px">

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
            <div style="border-end-end-radius: 10px;border-end-start-radius:10px;" class="card mg-b-20 pt-3 p-3">


                <div style="border-radius:10px" class="card pb-0 p-3">

                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'AddproductPriceToCustomer')) }}"
                        method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}






                        <div class="row mb-2">

                            <div class="col-lg-4 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label">{{ __('home.clietName') }} </p>
                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch"
                                    required>
                                    <option value="-" selected>
                                        {{ $supplierdata->clientnamesearch ?? __('home.searchbyclientname') }}
                                    </option>

                                    @foreach (App\Models\customers::orderBy('id', 'desc')->get() as $section)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                            <div class="col-lg-8 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.notesClient') }}
                                </label>
                                <input type="text" class="form-control parent-input" id="notes" name="notes"
                                    title="  يرجي ادخال رقم المنتج  ">
                            </div>
                            <input class="form-control parent-input" name="productNo" id="productNo" hidden>

                            <input hidden type="text" class="form-control parent-input" id="product_location"
                                name="product_location" value="{{ $data['supllier']->supllier->location ?? '' }}"
                                readonly>





                            <?php
                            $avtSaleRate = App\Models\Avt::find(1);
                            $avtSaleRate = $avtSaleRate->AVT;
                            ?>

                        </div>
                        <div style="flex-direction: row;border-radius:5px"
                            class="card p-1 m-1 mt-0 d-flex justify-content-around row m-1">


                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <a style="background-color: #FBA10F;font-size:14px;width:130px"
                                    class="modal-effect btn btn-sm btn-info py-1 px-1" data-effect="effect-scale"
                                    data-toggle="modal" href="#SearchProduct"
                                    title="تحديد">{{__('home.chooose product') }}
                                    <svg style="width: 16px;height:16px" xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                        <path d="M21 21l-6 -6"></path>
                                    </svg>
                                </a>
                            </div>






                        </div>



                        <div class="row mb-2">
                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.product') }}
                                </label>
                                <input type="text" class="form-control parent-input" id="product_name"
                                    name="product_name" title="  يرجي ادخال رقم المنتج  " readonly>
                            </div>

                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.purchaseproductwithouttax') }}</label>
                                <input type="text" class="form-control parent-input " id="purchase_price"
                                    name="purchase_price" readonly required>
                            </div>
                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.saleperpice') }}</label>
                                <input autocomplete="off" type="text" class="form-control parent-input"
                                    id="priceWithTax" name="priceWithTax" onchange="changePriceWithTax()"
                                    onkeyup="convertToNumberPricewithTaxSale()" required>
                            </div>
                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.sellingproduct without tax') }}</label>
                                <input type="text" class="form-control parent-input " id="product_price"
                                    name="product_price" onchange="changeAvtValue('{{ $avtSaleRate }}')"
                                    onkeyup="convertToNumberPriceSale()" required>
                                <input type="text" class="form-control parent-input " id="avtValue" name="avtValue"
                                    value="{{ $avtSaleRate }}" hidden>
                            </div>
                            <div class="col-lg-1 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.lastpricecustomer') }}</label>
                                <input autocomplete="off" type="text" readonly value=0 class="form-control parent-input"
                                    id="lastpricecustomer" name="lastpricecustomer" onchange="changePriceWithTax()"
                                    onkeyup="convertToNumberPricewithTaxSale()" required>
                            </div>

                            <div class="col-lg-1 ">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.avt') }} </label>
                                <input type="text" class="form-control parent-input " id="avt" name="avt"
                                    title="يرجي ادخال الكمية  " value="{{ $avtSaleRate }}" readonly>
                            </div>
                            <div class="col-lg-1 mg-t-10 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }}
                                </label>
                                <input type="text" class="form-control parent-input " id="quantity" name="quantity"
                                    title="يرجي ادخال الكمية  " value=1 required onkeyup="quantityconvertToNumber()">
                            </div>

                            <div class="col-lg-1 mg-t-10 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.discount') }}</label>
                                <input type="text" class="form-control parent-input " id="product_price_after_dis"
                                    value=0 name="product_price_after_dis" onkeyup="discountconvertToNumber()">
                            </div>

                        </div>

                        <br>
                        <div class="d-flex justify-content-center">
                            <button id="button_1" name="button_1" class="btn btn-success p-1 px-2 print-style">
                                {{ __('home.Add') }}
                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none"
                                        d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <input hidden=true class="form-control" id="branchs_id" name="branchs_id"
                            value="{{Auth()->user()->branchs_id}}">

                        <div class="row">

                            <div class="col">

                                <input type="number" hidden class="form-control parent-input" id="orderNo"
                                    name="orderNo">
                            </div>


                        </div>
                </div>


                {{-- 3 --}}
                </form>

                <br>




                <div>
                    <div class="card-body pb-0">
                        <div class="table-responsive hoverable-table">
                            <table class="table text-center table-hover our-table" id="example" data-page-length='50'>

                                <thead>
                                    <tr>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0"># </th>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.productNo') }} </th>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.product') }}</th>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.productlocation') }}</th>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.price') }}</th>
                                        <th style="font-size: 13px;padding:8px" class="border-bottom-0">
                                            {{ __('home.quantity') }}</th>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.total') }}</th>

                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.discount') }}</th>

                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.totalafterdiscount') }}</th>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.addedValue') }}</th>

                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.total') }}</th>
                                        <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                            {{ __('home.operations') }}</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>


                            <div class="table-responsive mg-t-30">



                                <div class="table-responsive mg-t-30 table-padding">
                                    <table class="table our-table text-center text-md-nowrap mb-0" id="tableTotalPrice"
                                        name="tableTotalPrice" width="50%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                                    {{ __('home.the amount') }}</th>
                                                <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                                    {{ __('home.discount') }}</th>

                                                <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                                    {{ __('home.addedValue') }}</th>

                                                <th style="font-size: 15px;padding:8px" class="border-bottom-0">
                                                    {{ __('home.total') }} </th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>*</td>
                                                <td>*</td>
                                                <td>*</td>
                                                <td>*</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <form
                                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'print_delivery_to_anoter_supplier')) }}"
                                        method="POST" role="search" autocomplete="off">
                                        {{ csrf_field() }}


                                        <div class="d-flex justify-content-center">
                                            <input type="number" class="form-control parent-input " name="OrderNoprint"
                                                id="OrderNoprint" title=" رقم الفاتورة " readonly required=true hidden>

                                            <button style="background-color: #419BB2;font-size:17px" type="submit"
                                                class="btn btn-success mt-3 p-1 px-2">
                                                {{ __('home.print') }}
                                                <svg style="width: 22px !important" class="svg-icon-buttons"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                        <br>
                                </div>

                            </div>

                            <br>
                            <br />
                            </form>

                        </div>
















                    </div>
                </div>
                <!-- row closed -->
            </div>
            <!-- Container closed -->
        </div>
        <!-- edit -->
        <div class="modal fade" id="increaseProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div style="margin: 5% !important;" class="modal-dialog modal-special" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('home.addtoSales') }}</h5>
                        <button type="button" class="close choose-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form
                            action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatePurchase')) }}"
                            method="post" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="hidden" name="id_update" id="id_update" value="">
                                <label for="recipient-name" class="col-form-label"> {{ __('home.product') }}
                                </label>
                                <input class="form-control" name="product_name_update" id="product_name_update"
                                    readonly>
                            </div>
                            <div class="row">

                                <div class="col">
                                    <label for="inputName" class="control-label parent-label">
                                        {{ __('home.sellingproduct without tax') }}</label>
                                    <input type="text" class="form-control parent-input" id="product_price_update"
                                        name="product_price_update"
                                        onchange="changeAvtValueupdatewithodtaxupdate('{{ $avtSaleRate }}')"
                                        onkeyup="changeAvtValuempdale()" required>
                                </div>
                                <div class="col">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.avt') }}
                                    </label>
                                    <input type="text" class="form-control parent-input" id="avt_update"
                                        name="avt_update" title="يرجي ادخال الكمية  " value="{{ $avtSaleRate }}"
                                        readonly>
                                </div>


                            </div>
                            <?php
                            $avtSaleRate = App\Models\Avt::find(1);
                            $avtSaleRate = $avtSaleRate->AVT;
                            ?>
                            <input type="text" hidden class="form-control parent-input" id="avt_value" name="avt_value"
                                title="يرجي ادخال الكمية  " value="{{ $avtSaleRate }}" readonly>

                            <div class="row">

                                <div class="col">
                                    <label for="inputName" class="control-label parent-label">
                                        {{ __('home.discount') }}</label>
                                    <input type="text" class="form-control parent-input"
                                        id="product_price_after_dis_update" value=0
                                        name="product_price_after_dis_update">
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }}
                                    </label>
                                    <input type="text" class="form-control parent-input" id="quantity_update"
                                        name="quantity_update" title="يرجي ادخال الكمية  " value=1 required>
                                </div>
                            </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{__('home.cancel')}}</button>
                        <button id="updateproductalldata" name="updateproductalldata"
                            class="btn btn-danger">{{ __('home.confirm') }}</button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="modaldemo9">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.Retrieveall') }} </h6><button aria-label="Close"
                            class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'EditInvoices')) }}"
                        method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <p>{{ __('home.Are_you_sure') }}</p><br>
                            <input type="hidden" name="id_delete" id="id_delete">
                            <input type="hidden" name="return_quentity_delete" id="return_quentity_delete">
                            <input class="form-control parent-input" name="product_name" id="product_name" type="text"
                                readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ __('home.cancel') }}</button>
                            <button id="deleteproduct" name="deleteproduct"
                                class="btn btn-danger">{{ __('home.confirm') }}</button>
                        </div>
                </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
            <div class="modal-dialog modal-xl product-selection" role="document">
                <div class="modal-content">

                    <div class="modal-body">


                        <div class="card-body">

                            <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                                <label for="inputName" style="font-weight: bold" class="control-label parent-label">
                                    {{__('home.searchaboutproduct')}} </label>
                                <input type="text" class="form-control parent-input"
                                    placeholder="{{ __('home.Search By Name or Product Number') }}"
                                    id="searchaboutproduct" name="searchaboutproduct"
                                    onkeyup="searchaboutproductfunction()">
                            </div>
                            <br>
                            <div class="table-responsive" id="ajax_responce_serarchDiv">
                                <table class="table text-md-nowrap text-center our-table" id="SearchProductTable"
                                    width="100%" style="border: 2px solid rgba(0,0,0,.3);">
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
                                            <th style="font-size: 15px" class="border-bottom-0"
                                                style="text-align:center">{{__('home.product')}}</th>
                                            <th style="font-size: 15px" class="border-bottom-0"
                                                style="text-align:center">{{__('home.branch')}}</th>
                                            <th style="font-size: 15px" class="border-bottom-0"
                                                style="text-align:center">{{__('home.productlocation')}}</th>

                                            <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}
                                            </th>
                                            <th style="font-size: 13px" class="border-bottom-0">
                                                {{__('home.purchaseproductwithouttax')}}</th>
                                            <th style="font-size: 13px" class="border-bottom-0">
                                                {{__('home.sellingproduct without tax')}}</th>
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
                                <div>

                                </div>


                            </div>

                        </div>

                        <div class="modal-footer">
                            {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button>
                            --}}
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{__('home.cancel')}}</button>
                        </div>

                    </div>


                </div>
            </div>

        </div>


        <!-- main-content closed -->
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
                            <input style="height:32px" type="text" class="form-control parent-input"
                                id="updateinvoicebyid" name="name" title="{{ __('supprocesses.name') }}" required>
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


@endsection
@section('js')
<script>
// دالة موحدة لبناء وتنظيف جداول الفواتير تلقائيًا ومنع تكرار الكود
function renderInvoiceTable(data) {
    let table = document.getElementById("example");
    var tableHeaderRowCount = 1;
    var rowCount = table.rows.length;

    // تفريغ الجدول القديم
    for (var i = tableHeaderRowCount; i < rowCount; i++) {
        table.deleteRow(tableHeaderRowCount);
    }

    var total_purchases = 0;
    var totaldiscount = 0;
    var temp = 0;
    var total_purchases_AddedValue = 0;

    data.forEach(async (product) => {
        if (product['count'] == 1) {
            $('#orderNo').val(product['order_id']);
            $('#OrderNoprint').val(product['order_id']);
        }

        temp = product['discount'];
        total_purchases = total_purchases + (product['sale_price'] * product['quantity']);
        totaldiscount = totaldiscount + product['discount'];
        total_purchases_AddedValue = total_purchases_AddedValue + ((product['sale_price'] * product[
            'quantity']) - product['discount']) * $('#avt_value').val();

        var count1 = product['count'];
        var product_code = product['productCode'];
        var product_name = product['productName'];
        var quentity = product['quantity'];
        var price = product['sale_price'];
        var discount = product['discount'];
        var id = product['id'];

        if (quentity > 0) {
            // تصحيح الأقواس الزائدة وإضافة علامات تنصيص لبيانات الـ data-attributes
            var update =
                ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id="' +
                id + '" data-section_name="' + product_name + '" data-section_price="' + price +
                '" data-section_discount="' + discount + '" data-return_quentity="' + quentity +
                '" data-toggle="modal" href="#increaseProduct" title="تعديل"><i class="las la-align-justify"></i></a>';
            var result3 =
                ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id="' +
                id + '" data-section_name="' + product_name + '" data-return_quentity="' + quentity +
                '" data-toggle="modal" href="#modaldemo9" title="حذف"><i class="las la-trash"></i></a>';

            let row = table.insertRow(-1);

            let c1 = row.insertCell(0);
            let c2 = row.insertCell(1);
            let c3 = row.insertCell(2);
            let c4 = row.insertCell(3);
            let c5 = row.insertCell(4);
            let c6 = row.insertCell(5);
            let c7 = row.insertCell(6);
            let c8 = row.insertCell(7);
            let c9 = row.insertCell(8);
            let c10 = row.insertCell(9);
            let c11 = row.insertCell(10);
            let c12 = row.insertCell(11);

            c1.innerText = count1;
            c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>';
            c3.innerText = product_name;
            c4.innerText = product['Product_Location'];
            c5.innerText = price;
            c6.innerText = quentity;
            c7.innerText = quentity * price;
            c8.innerText = discount;
            c9.innerText = (quentity * price) - discount;

            var tax = Math.round(((price * quentity) - discount) * 0.15 * 100) / 100;
            c10.innerText = tax;
            c11.innerText = Math.round(((price * quentity) + tax - discount) * 100) / 100;
            c12.innerHTML = result3 + '  ' + update;
        }
    });

    totaldiscount = totaldiscount + temp;

    // تحديث جدول الإجماليات السفلي
    let tableTotalPrice = document.getElementById("tableTotalPrice");
    var rowCountTotal = tableTotalPrice.rows.length;

    for (var i = tableHeaderRowCount; i < rowCountTotal; i++) {
        tableTotalPrice.deleteRow(tableHeaderRowCount);
    }

    let rowTotal = tableTotalPrice.insertRow(-1);
    let tc1 = rowTotal.insertCell(0);
    let tc2 = rowTotal.insertCell(1);
    let tc3 = rowTotal.insertCell(2);
    let tc4 = rowTotal.insertCell(3);

    tc1.innerText = ((Math.round(total_purchases * 100) / 100).toFixed(2));
    tc2.innerText = ((Math.round(totaldiscount * 100) / 100).toFixed(2));
    tc3.innerText = ((Math.round(total_purchases_AddedValue * 100) / 100).toFixed(2));
    tc4.innerText = ((Math.round((total_purchases - totaldiscount + total_purchases_AddedValue) * 100)) / 100).toFixed(
        2);
}

// الـ Listeners الخاصة بـ الاختصارات والأحداث
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.keyCode == '38') {
        $('#SearchProduct').modal().show();
    }
});

document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.code === 'ArrowDown') {
        e.preventDefault(); // تصحيح الخطأ هنا من event إلى e

        let table = document.getElementById("example");
        var _token = $("#token_search").val();
        var url = " {{ URL::to('Addproduct_to_dlivery_supplier') }}";

        clientnamesearch = $('#clientnamesearch').val();
        token_search = $('#token_search').val();
        productNo = $('#productNo').val();
        orderNo = $('#orderNo').val();
        discount = $('#product_price_after_dis').val() * $('#quantity').val();
        quantity = $('#quantity').val();
        product_price = $('#product_price').val();
        notes = $('#notes').val();

        if (clientnamesearch == '-') {
            alert("{{ __('home.pleasechooseClient') }}");
        } else if (productNo == '') {
            alert("{{ __('home.pleaseSelectProduct') }}");
        } else if (quantity == '') {
            alert("{{ __('home.pleaseenterquantity') }}");
        } else {
            $('#product_price_after_dis').val(0);
            $('#quantity').val(1);
            $('#avaliable_quentity').val('');
            $('#product_location').val('');
            $('#product_price').val('');
            $('#purchase_price').val('');
            $('#product_name').val('');

            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                data: {
                    "_token": _token,
                    "clientnamesearch": clientnamesearch,
                    "productNo": productNo,
                    "quentity": quantity,
                    "saleprice": product_price,
                    "orderNo": orderNo,
                    'discount': discount,
                    "notes": notes
                },
                success: function(data) {
                    $('#quentity').val('');
                    $('#productnameshow').val('');
                    renderInvoiceTable(data); // استدعاء الدالة الموحدة
                },
                error: function(response) {
                    console.log(response);
                    alert("{{ __('home.sorryerror') }}");
                }
            });
        }
    }
});

$('#increaseProduct').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var section_name = button.data('section_name');
    var return_quentity = button.data('return_quentity');
    var price = button.data('section_price');
    var discount = button.data('section_discount');
    var modal = $(this);

 

    avtsale = $('#avtValue').val();

    modal.find('.modal-body #id_update').val(id);
    modal.find('.modal-body #product_name_update').val(section_name);
    modal.find('.modal-body #product_price_update').val(price);
    modal.find('.modal-body #avt_update').val(Math.round((price * avtsale) * 1000, 2) / 1000);
    modal.find('.modal-body #quantity_update').val(return_quentity);
    modal.find('.modal-body #product_price_after_dis_update').val(discount / return_quentity);
    modal.find('.modal-body #priceWithTax_update').val((price * 1) + Math.round((price * avtsale) * 1000, 2) /
        1000);
});
</script>

<script>
function makeDiscountInvoice() {
    totaldicount = $('#totaldicount').val();
    $('#totaldicount').val('');
    orderNo = $('#orderNo').val();

    $.ajax({
        url: "{{ URL::to('/makeTotalDiscontOferprice') }}/" + orderNo + "/" + totaldicount,
        type: "GET",
        dataType: "json",
        success: function(data) {
            console.log("success");
            if (data) {
                let tableTotalPrice = document.getElementById("tableTotalPrice");
                var tableHeaderRowCount = 1;
                var rowCount = tableTotalPrice.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    tableTotalPrice.deleteRow(tableHeaderRowCount);
                }
                let row = tableTotalPrice.insertRow(-1);

                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);
                let c4 = row.insertCell(3);

                total_purchases = data['total_purchases'];
                totaldiscount = data['totaldiscount'];
                total_purchases_AddedValue = data['total_purchases_AddedValue'];

                c1.innerText = total_purchases;
                c2.innerText = totaldiscount;
                c3.innerText = total_purchases_AddedValue;
                c4.innerText = (total_purchases - totaldiscount) + (total_purchases_AddedValue * 1);
            } else {
                alert("{{ __('home.sorryerror') }}");
            }
        },
    });
}

function changePriceWithTax() {
    price = $('#priceWithTax').val();
    avtvalue = (price * 100) / 115;
    rountavt = Math.round((avtvalue * 100), 2) / 100;

    $('#avt').val(Math.round(((price - rountavt) * 100), 2) / 100);
    $('#product_price').val(rountavt.toFixed(2));
}

document.addEventListener('keydown', (e) => {
    if (e.key === "Enter") {
        searchtext = $('#product_code').val();
        $('#searchaboutproduct').val(searchtext);
        $('#SearchProduct').modal().show();
    }
});

$('#SearchProduct').on('shown.bs.modal', function() {
    $('#searchaboutproduct').focus();
});

$("#updateproductalldata").click(function(e) {
    e.preventDefault(); // تم استخدام e بدل event هنا أيضاً للأمان
    $('#increaseProduct').modal('hide');

    var token_search = $("#token_search").val();
    var url = " {{ URL::to('updateproductallDatadelivery') }}";

    id = $('#id_update').val();
    quentity = $('#quantity_update').val();
    discount = $('#product_price_after_dis_update').val() * $('#quantity_update').val();
    price = $('#product_price_update').val();

    $.ajax({
        url: url,
        type: 'post',
        cache: false,
        data: {
            _token: token_search,
            id: id,
            quentity: quentity,
            discount: discount,
            price: price
        },
        success: function(data) {
            $('#quentity').val('');
            $('#productnameshow').val('');
            renderInvoiceTable(data); // استدعاء الدالة الموحدة لمنع التكرار والأخطاء
        }
    });
});

function changeAvtValuempdale(avt) {
    price = $('#product_price_update').val();
    avt = $('#avtValue').val();
    $('#avt_update').val(Math.round((price * avt) * 1000) / 1000);
}
</script>








<script>
$("#deleteproduct").click(function(e) {
    event.preventDefault();
    $('#modaldemo9').modal('hide');
    id = $('#id_delete').val();
    console.log("{{ URL::to('/deleteitemdelivery') }}/" + id)
    $.ajax({
        url: "{{ URL::to('/deleteitemdelivery') }}/" + id,
        type: "GET",
        dataType: "json",
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

            total_purchases = 0;
            totaldiscount = 0;
            temp = 0;
            total_purchases_AddedValue = 0;
            data.forEach(async (product) => {
                if (product['count'] == 1) {
                    $('#orderNo').val(product['order_id']);
                    $('#OrderNoprint').val(product['order_id']);

                }

                temp = product['discount'];
                total_purchases = total_purchases + (product['sale_price'] * product[
                    'quantity']);
                totaldiscount = totaldiscount + product['discount'];
                total_purchases_AddedValue = total_purchases_AddedValue + ((product[
                    'sale_price'] * product['quantity']) - product['discount']) * $(
                    '#avt_value').val();

                count1 = product['count'],
                    product_code = product['productCode']
                product_name = product['productName']
                quentity = product['quantity']
                price = product['sale_price']
                added_value = product['added_value']
                discount = product['discount']
                id = product['id']

                if (quentity > 0) {
                    update =
                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                    update = update.concat(id, "  ", "data-section_name=", product_name,
                        "  ", "data-section_price=", price, "  ",
                        "data-section_discount=", discount,
                        "  ", "data-return_quentity=", quentity, '  ',
                        '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                    )
                    text2 =
                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                    result3 = text2.concat(id, "  ", "data-section_name=", product_name,
                        "  ", "data-return_quentity=", quentity, '  ',
                        '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                    )


                    console.log('+++AFTER 5 TABLE+++')

                    let row = table.insertRow(-
                        1); // We are adding at the end
                    console.log('+++AFTER 5 TABLE+++')

                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);
                    let c8 = row.insertCell(7);
                    let c9 = row.insertCell(8);
                    let c10 = row.insertCell(9);
                    let c11 = row.insertCell(10);
                    let c12 = row.insertCell(11);

                    console.log('+++AFTER 6 TABLE+++')
                    console.log(price)

                    // Add data to c1 and c2


                    c1.innerText = count1
                    c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                    c3.innerText = product_name
                    c4.innerText = product['Product_Location']
                    c5.innerText = price
                    c6.innerText = quentity
                    c7.innerText = quentity * price
                    c8.innerText = discount
                    c9.innerText = (quentity * price) - discount
                    tax = Math.round(((price * quentity) - discount) * 0.15 * 100) / 100
                    c10.innerText = tax
                    c11.innerText = Math.round(((price * quentity) + tax - discount) *
                        100) / 100
                    c12.innerHTML = result3 + '  ' + update

                }


            });


            totaldiscount = totaldiscount + temp;
            let tableTotalPrice = document.getElementById("tableTotalPrice");
            var tableHeaderRowCount = 1;

            var rowCount = tableTotalPrice.rows.length;

            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                tableTotalPrice.deleteRow(tableHeaderRowCount);
            }
            let row = tableTotalPrice.insertRow(-1); // We are adding at the end

            let c1 = row.insertCell(0);
            let c2 = row.insertCell(1);
            let c3 = row.insertCell(2);
            let c4 = row.insertCell(3);


            // Add data to c1 and c2

            c1.innerText = ((Math.round(total_purchases * 100) / 100).toFixed(2))
            c2.innerText = ((Math.round(totaldiscount * 100) / 100).toFixed(2))
            c3.innerText = ((Math.round(total_purchases_AddedValue * 100) / 100).toFixed(2))
            c4.innerText = ((Math.round((total_purchases - totaldiscount +
                total_purchases_AddedValue) * 100)) / 100).toFixed(2)






        }
    });
})
document.addEventListener('keydown', (e) => {
    if (e.key === "F9") {
        $('#SearchProduct').modal().show();

    }
})
</script>
<script>
function quantityconvertToNumber() {
    var input = document.getElementById("quantity");
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
function convertToNumberPriceSale() {
    var input = document.getElementById("product_price ");
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
function discountconvertToNumber() {
    var input = document.getElementById("product_price_after_dis");
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
function searchaboutproductfunction() {
    searchtext = $('#searchaboutproduct').val();
    branchs_id = $('#branchs_id').val();


    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    jQuery.ajax({
        url: " {{URL::to('searchChooseProductpaginatenewSale')}}/" + searchtext + "/" + branchs_id,
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

$('#SearchProduct').on('show.bs.modal', function(event) {
    branchs_id = $('#branchs_id').val();
    console.log(branchs_id)
    jQuery.ajax({
        url: " {{URL::to('ChooseProductpaginatenewSale')}}/" + branchs_id,
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
</script>


<script>
$("#getinvoiceupdate").click(function(e) {
    event.preventDefault();
    var url = " {{ URL::to('updatequtation') }}" + "/" + $('#updateinvoicebyid').val();;
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


            let table = document.getElementById("example");
            console.log('+++AFTER TABLE+++')



            var tableHeaderRowCount = 1;

            var rowCount = table.rows.length;
            console.log('+++AFTER 3 TABLE+++')

            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                table.deleteRow(tableHeaderRowCount);
            }
            console.log('+++AFTER 4 TABLE+++')

            total_purchases = 0;
            totaldiscount = 0;
            total_purchases_AddedValue = 0;
            temp = 0;

            data.forEach(async (product) => {
                temp = product['totaldiscount'];

                total_purchases = total_purchases + (product['sale_price'] * product[
                    'quantity']);
                totaldiscount = totaldiscount + product['discount'];
                total_purchases_AddedValue = total_purchases_AddedValue + ((product[
                    'sale_price'] * product['quantity']) - product['discount']) * $(
                    '#avt_value').val();

                if (product['count'] == 1) {
                    $('#orderNo').val(product['order_id']);
                    $('#OrderNoprint').val(product['order_id']);

                }

                count1 = product['count'],
                    product_code = product['productCode']
                product_name = product['productName']
                quentity = product['quantity']
                price = product['sale_price']
                added_value = product['added_value']
                discount = product['discount']
                id = product['id']

                if (quentity > 0) {
                    update =
                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                    update = update.concat(id, "  ", "data-section_name=", product_name,
                        "  ", "data-section_price=", price, "  ",
                        "data-section_discount=", discount,
                        "  ", "data-return_quentity=", quentity, '  ',
                        '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                    )
                    text2 =
                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                    result3 = text2.concat(id, "  ", "data-section_name=", product_name,
                        "  ", "data-return_quentity=", quentity, '  ',
                        '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                    )


                    console.log('+++AFTER 5 TABLE+++')

                    let row = table.insertRow(-
                        1); // We are adding at the end
                    console.log('+++AFTER 5 TABLE+++')

                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);
                    let c8 = row.insertCell(7);
                    let c9 = row.insertCell(8);
                    let c10 = row.insertCell(9);
                    let c11 = row.insertCell(10);
                    let c12 = row.insertCell(11);

                    console.log('+++AFTER 6 TABLE+++')
                    console.log(price)

                    // Add data to c1 and c2


                    c1.innerText = count1
                    c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                    c3.innerText = product_name
                    c4.innerText = product['Product_Location']
                    c5.innerText = price
                    c6.innerText = quentity
                    c7.innerText = quentity * price
                    c8.innerText = discount
                    c9.innerText = (quentity * price) - discount
                    tax = Math.round(((price * quentity) - discount) * 0.15 * 100) / 100
                    c10.innerText = tax
                    c11.innerText = Math.round(((price * quentity) + tax - discount) *
                        100) / 100
                    c12.innerHTML = result3 + '  ' + update

                }


            });


            totaldiscount = totaldiscount + temp;
            let tableTotalPrice = document.getElementById("tableTotalPrice");
            var tableHeaderRowCount = 1;

            var rowCount = tableTotalPrice.rows.length;

            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                tableTotalPrice.deleteRow(tableHeaderRowCount);
            }
            let row = tableTotalPrice.insertRow(-1); // We are adding at the end

            let c1 = row.insertCell(0);
            let c2 = row.insertCell(1);
            let c3 = row.insertCell(2);
            let c4 = row.insertCell(3);


            // Add data to c1 and c2


            c1.innerText = ((Math.round(total_purchases * 100) / 100).toFixed(2))
            c2.innerText = ((Math.round(totaldiscount * 100) / 100).toFixed(2))
            c3.innerText = ((Math.round(total_purchases_AddedValue * 100) / 100).toFixed(2))
            c4.innerText = ((Math.round((total_purchases - totaldiscount +
                total_purchases_AddedValue) * 100)) / 100).toFixed(2)





        },
        error: function(response) {
            alert("{{ __('home.sorryerror') }}")

        }
    })
})

function chooseProduct(code, name, price, sale_price, location, availablequantity, w) {
    $('#SearchProduct').modal().hide();
    console.log(location)
    console.log(sale_price)

    var Product_Code = code
    var product_name = name
    var product_sale_pice = price
    name = name.replaceAll("<", " ");
    location = location.replaceAll("<", " ");
    $("#productNo").val(Product_Code);
    $('#product_name').val(price);
    $('#product_price').val(location);
    $('#purchase_price').val(sale_price);
    $('#product_location').val(availablequantity);
    $('#avaliable_quentity').val(w);
    avtsale = $('#avtValue').val();
    $('#avt').val(location * avtsale);
    $('#priceWithTax').val((((location * 1) + (location * avtsale)) * 1000) / 1000);

    document.getElementById("product_price").focus();
    console.log("{{ URL::to('/getlastprice') }}/" + $("#productNo").val() + "/" + $('#clientnamesearch').val())

    $.ajax({
        url: "{{ URL::to('/getlastprice') }}/" + $("#productNo").val() + "/" + $('#clientnamesearch').val(),
        type: "GET",
        dataType: "json",
        success: function(data) {
            console.log(data)
            if (!data || data.length === 0) {
                // If data is empty, null, or 0
                $('#lastpricecustomer').val('Not Found');
            } else {
                // If data exists, we can grab the most recent price (assuming last item)
                // or adjust this if 'data' is meant to be a single number in some API responses
                if (typeof data !== 'object') {
                    $('#lastpricecustomer').val(data);
                } else {
                    // If it's an array, take the cost from the first or last product
                    $('#lastpricecustomer').val(data[0]['cost']);
                }

                // Clear the dropdown first so you don't keep appending duplicates on every call
                $('#last_supplier_cost').empty();

                // Loop through the products to build your dropdown options
                data.forEach((product) => {
                    $('#last_supplier_cost').append($('<option>', {
                        value: product[
                        'invoiceid'], // Using the invoice ID as a dynamic value makes more sense than a static 1
                        text: "{{ __('home.Invoice_no') }}" + " : " + product[
                                'invoiceid'] + " ** " + product['date'] + " **  " +
                            product['cost'] + " " + "{{ __('home.SAR') }}"
                    }));
                });
            }

        }
    })

}

function changeAvtValue(avt) {
    price = $('#product_price').val();
    avtvalue = (price * avt)
    $('#avt').val(price * avt);
    $('#priceWithTax').val(((price * 1) + Math.round((price * avt) * 1000) / 1000).toFixed(2));




}
</script>

<script>
$(document).ready(function() {
    $(function() {
        var timeout = 4000; // in miliseconds (3*1000)
        $('.alert').delay(timeout).fadeOut(500);
    });

    // End Update ( 24/4/2023 )
    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var return_quentity = button.data('return_quentity')
        var section_name = button.data('section_name')
        var modal = $(this)
        modal.find('.modal-body #id_delete').val(id);
        modal.find('.modal-body #return_quentity_delete').val(return_quentity);
        modal.find('.modal-body #product_name').val(section_name);
        console.log('lololoooo')
    })
    document.addEventListener('keydown', (e) => {
        if (e.key === "+") {
            event.preventDefault();

            let table = document.getElementById("example");


            var _token = $("#token_search").val();

            var url = " {{ URL::to('AddproductPriceToCustomer') }}";


            clientnamesearch = $('#clientnamesearch').val();
            token_search = $('#token_search').val();
            productNo = $('#productNo').val();
            orderNo = $('#orderNo').val();
            discount = $('#product_price_after_dis').val();
            quantity = $('#quantity').val();
            product_price = $('#product_price').val()
            console.log('++++++++++MOHAMED+++++++++')

            console.log(clientnamesearch)
            console.log(productNo)
            console.log(orderNo)
            console.log(quantity)
            console.log(discount)
            console.log(product_price)
            console.log(_token);

            console.log('+++++++++++++++++++')



            if (clientnamesearch == '-') {
                alert("{{ __('home.pleasechooseClient') }}")

            } else if (productNo == '') {
                alert("{{ __('home.pleaseSelectProduct') }}")

            } else if (quantity == '') {
                alert("{{ __('home.pleaseenterquantity') }}")

            } else {
                $('#product_price_after_dis').val(0);
                $('#quantity').val(1);
                $('#avaliable_quentity').val('');
                $('#product_location').val('');
                $('#product_price').val('');
                $('#purchase_price').val('');
                $('#product_name').val('');

                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        "_token": _token,
                        "clientnamesearch": clientnamesearch,
                        "productNo": productNo,
                        "quentity": quantity,
                        "saleprice": product_price,
                        "orderNo": orderNo,
                        'discount': discount
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

                        total_purchases = 0;
                        totaldiscount = 0;
                        temp = 0;
                        total_purchases_AddedValue = 0;
                        data.forEach(async (product) => {
                            if (product['count'] == 1) {
                                $('#orderNo').val(product['order_id']);
                                $('#OrderNoprint').val(product['order_id']);

                            }

                            temp = product['discount'];
                            total_purchases = total_purchases + (product[
                                'sale_price'] * product['quantity']);
                            totaldiscount = totaldiscount + product['discount'];
                            total_purchases_AddedValue =
                                total_purchases_AddedValue + ((product[
                                        'sale_price'] * product['quantity']) -
                                    product['discount']) * $('#avt_value')
                            .val();

                            count1 = product['count'],
                                product_code = product['productCode']
                            product_name = product['productName']
                            quentity = product['quantity']
                            price = product['sale_price']
                            added_value = product['added_value']
                            discount = product['discount']
                            id = product['id']

                            if (quentity > 0) {
                                update =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                                update = update.concat(id, "  ",
                                    "data-section_name=", product_name,
                                    "  ", "data-section_price=", price,
                                    "  ", "data-section_discount=",
                                    discount,
                                    "  ", "data-return_quentity=", quentity,
                                    '  ',
                                    '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                                )
                                text2 =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                result3 = text2.concat(id, "  ",
                                    "data-section_name=", product_name,
                                    "  ", "data-return_quentity=", quentity,
                                    '  ',
                                    '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                )


                                console.log('+++AFTER 5 TABLE+++')

                                let row = table.insertRow(-
                                    1); // We are adding at the end
                                console.log('+++AFTER 5 TABLE+++')

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);
                                let c5 = row.insertCell(4);
                                let c6 = row.insertCell(5);
                                let c7 = row.insertCell(6);
                                let c8 = row.insertCell(7);
                                let c9 = row.insertCell(8);
                                let c10 = row.insertCell(9);
                                let c11 = row.insertCell(10);
                                let c12 = row.insertCell(11);

                                console.log('+++AFTER 6 TABLE+++')
                                console.log(price)

                                // Add data to c1 and c2


                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' +
                                    product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = product['Product_Location']
                                c5.innerText = price
                                c6.innerText = quentity
                                c7.innerText = quentity * price
                                c8.innerText = discount
                                c9.innerText = (quentity * price) - discount
                                tax = Math.round(((price * quentity) -
                                    discount) * 0.15 * 100) / 100
                                c10.innerText = tax
                                c11.innerText = Math.round(((price * quentity) +
                                    tax - discount) * 100) / 100
                                c12.innerHTML = result3 + '  ' + update
                            }


                        });


                        totaldiscount = totaldiscount + temp;
                        let tableTotalPrice = document.getElementById("tableTotalPrice");
                        var tableHeaderRowCount = 1;

                        var rowCount = tableTotalPrice.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            tableTotalPrice.deleteRow(tableHeaderRowCount);
                        }
                        let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);


                        // Add data to c1 and c2

                        c1.innerText = ((Math.round(total_purchases * 100) / 100).toFixed(
                            2))
                        c2.innerText = ((Math.round(totaldiscount * 100) / 100).toFixed(2))
                        c3.innerText = ((Math.round(total_purchases_AddedValue * 100) / 100)
                            .toFixed(2))
                        c4.innerText = ((Math.round((total_purchases - totaldiscount +
                            total_purchases_AddedValue) * 100)) / 100).toFixed(2)






                    },
                    error: function(response) {
                        consol.log(response)
                        alert("{{ __('home.sorryerror') }}")

                    }
                });
            }
        }
    })

    $("#button_1").click(function(e) {
        event.preventDefault();

        let table = document.getElementById("example");


        var _token = $("#token_search").val();

        var url = " {{ URL::to('Addproduct_to_dlivery_supplier') }}";


        clientnamesearch = $('#clientnamesearch').val();
        token_search = $('#token_search').val();
        productNo = $('#productNo').val();
        orderNo = $('#orderNo').val();
        discount = $('#product_price_after_dis').val() * $('#quantity').val();
        quantity = $('#quantity').val();
        product_price = $('#product_price').val()
        notes = $('#notes').val()

        console.log('++++++++++MOHAMED+++++++++')

        console.log(clientnamesearch)
        console.log(productNo)
        console.log(orderNo)
        console.log(quantity)
        console.log(discount)
        console.log(product_price)
        console.log(_token);

        console.log('+++++++++++++++++++')



        if (clientnamesearch == '-') {
            alert("{{ __('home.pleasechooseClient') }}")

        } else if (productNo == '') {
            alert("{{ __('home.pleaseSelectProduct') }}")

        } else if (quantity == '') {
            alert("{{ __('home.pleaseenterquantity') }}")

        } else {
            $('#product_price_after_dis').val(0);
            $('#quantity').val(1);
            $('#avaliable_quentity').val('');
            $('#product_location').val('');
            $('#product_price').val('');
            $('#purchase_price').val('');
            $('#product_name').val('');

            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    "_token": _token,
                    "clientnamesearch": clientnamesearch,
                    "productNo": productNo,
                    "quentity": quantity,
                    "saleprice": product_price,
                    "orderNo": orderNo,
                    'discount': discount,
                    "notes": notes

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

                    total_purchases = 0;
                    totaldiscount = 0;
                    temp = 0;
                    total_purchases_AddedValue = 0;
                    data.forEach(async (product) => {
                        if (product['count'] == 1) {
                            $('#orderNo').val(product['order_id']);
                            $('#OrderNoprint').val(product['order_id']);

                        }

                        temp = product['discount'];
                        total_purchases = total_purchases + (product[
                            'sale_price'] * product['quantity']);
                        totaldiscount = totaldiscount + product['discount'];
                        total_purchases_AddedValue =
                            total_purchases_AddedValue + ((product[
                                    'sale_price'] * product['quantity']) -
                                product['discount']) * $('#avt_value').val();

                        count1 = product['count'],
                            product_code = product['productCode']
                        product_name = product['productName']
                        quentity = product['quantity']
                        price = product['sale_price']
                        added_value = product['added_value']
                        discount = product['discount']
                        id = product['id']

                        if (quentity > 0) {
                            update =
                                ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                            update = update.concat(id, "  ",
                                "data-section_name=", product_name, "  ",
                                "data-section_price=", price, "  ",
                                "data-section_discount=", discount,
                                "  ", "data-return_quentity=", quentity,
                                '  ',
                                '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                            )
                            text2 =
                                ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                            result3 = text2.concat(id, "  ",
                                "data-section_name=", product_name,
                                "  ", "data-return_quentity=", quentity,
                                '  ',
                                '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                            )


                            console.log('+++AFTER 5 TABLE+++')

                            let row = table.insertRow(-
                                1); // We are adding at the end
                            console.log('+++AFTER 5 TABLE+++')

                            let c1 = row.insertCell(0);
                            let c2 = row.insertCell(1);
                            let c3 = row.insertCell(2);
                            let c4 = row.insertCell(3);
                            let c5 = row.insertCell(4);
                            let c6 = row.insertCell(5);
                            let c7 = row.insertCell(6);
                            let c8 = row.insertCell(7);
                            let c9 = row.insertCell(8);
                            let c10 = row.insertCell(9);
                            let c11 = row.insertCell(10);
                            let c12 = row.insertCell(11);

                            console.log('+++AFTER 6 TABLE+++')
                            console.log(price)

                            // Add data to c1 and c2


                            c1.innerText = count1
                            c2.innerHTML = ' <span dir=ltr>' + product_code +
                                '</span>'
                            c3.innerText = product_name
                            c4.innerText = product['Product_Location']
                            c5.innerText = price
                            c6.innerText = quentity
                            c7.innerText = quentity * price
                            c8.innerText = discount
                            c9.innerText = (quentity * price) - discount
                            tax = Math.round(((price * quentity) - discount) *
                                0.15 * 100) / 100
                            c10.innerText = tax
                            c11.innerText = Math.round(((price * quentity) +
                                tax - discount) * 100) / 100
                            c12.innerHTML = result3 + '  ' + update
                        }


                    });


                    totaldiscount = totaldiscount + temp;
                    let tableTotalPrice = document.getElementById("tableTotalPrice");
                    var tableHeaderRowCount = 1;

                    var rowCount = tableTotalPrice.rows.length;

                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                        tableTotalPrice.deleteRow(tableHeaderRowCount);
                    }
                    let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);


                    // Add data to c1 and c2

                    c1.innerText = ((Math.round(total_purchases * 100) / 100).toFixed(2))
                    c2.innerText = ((Math.round(totaldiscount * 100) / 100).toFixed(2))
                    c3.innerText = ((Math.round(total_purchases_AddedValue * 100) / 100)
                        .toFixed(2))
                    c4.innerText = ((Math.round((total_purchases - totaldiscount +
                        total_purchases_AddedValue) * 100)) / 100).toFixed(2)






                },
                error: function(response) {
                    console.log(response)
                    alert("{{ __('home.sorryerror') }}")

                }
            });
        }
    });





    $('select[name="clientNosearch"]').on('change', function() {
        console.log('AJAX load  333333333333333333333333333 work 0000');

        var selectclientid = $(this).val();
        if (selectclientid) {
            console.log('AJAX load   work');

            $.ajax({
                url: "{{ URL::to('getcustomer') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success");
                    console.log(data['name']);
                    $('#clientName').val(data['name']);
                    $('#address').val(data['address']);
                    $('#phonenumber').val(data['phone']);
                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
});

$('select[name="clientnamesearch"]').on('change', function() {
    console.log('AJAX load 1223232222222222  work 0000');

    var selectclientid = $(this).val();
    if (selectclientid) {
        console.log('AJAX load   work');

        $.ajax({
            url: "{{ URL::to('getcustomerproductsdelivery') }}/" + selectclientid,
            type: "GET",
            dataType: "json",

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

                total_purchases = 0;
                totaldiscount = 0;
                temp = 0;
                total_purchases_AddedValue = 0;
                data.forEach(async (product) => {
                    if (product['count'] == 1) {
                        $('#orderNo').val(product['order_id']);
                        $('#OrderNoprint').val(product['order_id']);

                    }

                    temp = product['discount'];
                    total_purchases = total_purchases + (product['sale_price'] *
                        product['quantity']);
                    totaldiscount = totaldiscount + product['discount'];
                    total_purchases_AddedValue = total_purchases_AddedValue + ((product[
                        'sale_price'] * product['quantity']) - product[
                        'discount']) * $('#avt_value').val();

                    count1 = product['count'],
                        product_code = product['productCode']
                    product_name = product['productName']
                    quentity = product['quantity']
                    price = product['sale_price']
                    added_value = product['added_value']
                    discount = product['discount']
                    id = product['id']

                    if (quentity > 0) {
                        update =
                            ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                        update = update.concat(id, "  ", "data-section_name=",
                            product_name, "  ", "data-section_price=", price, "  ",
                            "data-section_discount=", discount,
                            "  ", "data-return_quentity=", quentity, '  ',
                            '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )
                        text2 =
                            ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                        result3 = text2.concat(id, "  ", "data-section_name=",
                            product_name,
                            "  ", "data-return_quentity=", quentity, '  ',
                            '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                        )


                        console.log('+++AFTER 5 TABLE+++')

                        let row = table.insertRow(-
                            1); // We are adding at the end
                        console.log('+++AFTER 5 TABLE+++')

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        let c8 = row.insertCell(7);
                        let c9 = row.insertCell(8);
                        let c10 = row.insertCell(9);
                        let c11 = row.insertCell(10);
                        let c12 = row.insertCell(11);

                        console.log('+++AFTER 6 TABLE+++')
                        console.log(price)

                        // Add data to c1 and c2


                        c1.innerText = count1
                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                        c3.innerText = product_name
                        c4.innerText = product['Product_Location']
                        c5.innerText = price
                        c6.innerText = quentity
                        c7.innerText = quentity * price
                        c8.innerText = discount
                        c9.innerText = (quentity * price) - discount
                        tax = Math.round(((price * quentity) - discount) * 0.15 * 100) /
                            100
                        c10.innerText = tax
                        c11.innerText = Math.round(((price * quentity) + tax -
                            discount) * 100) / 100
                        c12.innerHTML = result3 + '  ' + update

                    }


                });


                totaldiscount = totaldiscount + temp;
                let tableTotalPrice = document.getElementById("tableTotalPrice");
                var tableHeaderRowCount = 1;

                var rowCount = tableTotalPrice.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    tableTotalPrice.deleteRow(tableHeaderRowCount);
                }
                let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);
                let c4 = row.insertCell(3);


                // Add data to c1 and c2

                c1.innerText = ((Math.round(total_purchases * 100) / 100).toFixed(2))
                c2.innerText = ((Math.round(totaldiscount * 100) / 100).toFixed(2))
                c3.innerText = ((Math.round(total_purchases_AddedValue * 100) / 100).toFixed(2))
                c4.innerText = ((Math.round((total_purchases - totaldiscount +
                    total_purchases_AddedValue) * 100)) / 100).toFixed(2)






            },
        });
    } else {
        console.log('AJAX load did not work');
    }
});


$('select[name="searchproductNo"]').on('change', function() {
    console.log('AJAX load   work 0000');

    var selectclientid = $(this).val();
    if (selectclientid) {
        console.log('AJAX load   work');

        $.ajax({
            url: "{{ URL::to('getproduct') }}/" + selectclientid,
            type: "GET",
            dataType: "json",
            success: function(data) {
                console.log("success");
                console.log(data['name']);
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

    $('#invoice_number').hide();

    $('input[type="radio"]').click(function() {
        if ($(this).attr('id') == 'type_div') {
            $('#invoice_number').hide();
            $('#type').show();
            $('#start_at').show();
            $('#end_at').show();
        } else {
            $('#invoice_number').show();
            $('#type').hide();
            $('#start_at').hide();
            $('#end_at').hide();
        }
    });
});
</script>


@endsection