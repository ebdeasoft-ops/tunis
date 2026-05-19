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


<style>
    tr:nth-child(even) {
        background-color: #dde2ef;

        color: white
    }
</style>


@section('title')
{{ __('home.Receipt') }}
@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Receipt') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session()->has('nodataprint'))
    <div class="alert alert-warning  alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ __('home.nodataprint') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session()->has('delete'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ session()->get('delete') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session()->has('edit'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ session()->get('edit') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

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

            <div class="card mg-b-20 p-3">



                <div style="border-radius: 10px" class="card pb-0 px-4 pt-4 pb-0">
                    <div class="my-auto">

                        <div class=" d-flex justify-content-between">
                            <div class="choose-product m-1 mt-3">
                                <button style="background-color: #FBA10F;font-size:16px" class="modal-effect btn btn-sm btn-info p-1 button-eng" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد"><i style=" height: 100;
                         width: 70px;
                         font-size:15px" class="las"> {{ __('home.chooose product') }}</i>
                                    <svg style="width: 18 px;height:18px" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                        <path d="M21 21l-6 -6"></path>
                                    </svg>
                                </button>
                            </div>
                            <div style="flex-direction: row;border-radius:5px" class="card p-1 m-1 mt-0 d-flex justify-content-around row m-1">
                                <div class="choose-product">
                                    <button style="background-color: #23395D;" class="modal-effect btn btn-sm btn-info p-2 m-1 button-eng" data-effect="effect-scale" data-toggle="modal" href="#createcustomer" title="تحديد"><i style=" height: 100;
                         width: 65px;
                         font-size:13px" class="las"> {{ __('home.addnewcustomer') }}</i>
                                        <svg style="width: 18px;height:18px" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                            <path d="M16 19h6"></path>
                                            <path d="M19 16v6"></path>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="last-sales">
                                    <a style="color:white;background-color: #23395D;border-radius:5px;font-size:11px;width:165px;padding:9px 6px" class="btn btn-info m-1" href="{{ url('/' . ($page = 'previousSalesInvoices')) }}">{{ __('home.previousSalesInvoices') }}
                                        <svg style="width:16px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.927,5.828h-4.41l-1.929-1.961c-0.078-0.079-0.186-0.125-0.297-0.125H4.159c-0.229,0-0.417,0.188-0.417,0.417v1.669H2.073c-0.229,0-0.417,0.188-0.417,0.417v9.596c0,0.229,0.188,0.417,0.417,0.417h15.854c0.229,0,0.417-0.188,0.417-0.417V6.245C18.344,6.016,18.156,5.828,17.927,5.828 M4.577,4.577h6.539l1.231,1.251h-7.77V4.577z M17.51,15.424H2.491V6.663H17.51V15.424z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'printInvoice')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}



                        <div class="row mb-2">
                        <?php
                            $avtSaleRate = App\Models\Avt::find(1);
                            $avtSaleRate = $avtSaleRate->AVT;
                            ?>
                        <input type="text" class="form-control " id="avtValue" name="avtValue" value="{{$avtSaleRate}}" hidden>

                            <div class="col mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.product') }} </label>
                                <input type="text" class="form-control parent-input" id="product_name" name="product_name" title="  يرجي ادخال رقم المنتج  " value="{{ $data['supllier']->supllier->comp_name ?? '' }}">
                            </div>
                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.productNo') }} </label>
                                <input type="text" class="form-control parent-input" id="product_code" name="product_code" title="  يرجي ادخال رقم المنتج  " readonly>
                            </div>

                            <div class="col-lg-2 ">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.productlocation') }} </label>
                                <input type="text" class="form-control parent-input" id="product_location" name="product_location" value="{{ $data['supllier']->supllier->location ?? '' }}" readonly>
                            </div>

                            <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.availablequantity') }}
                                </label>
                                <input type="text" class="form-control parent-input" id="avaliable_quentity" name="avaliable_quentity" readonly>
                            </div>

                            <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.purchaseproductwithouttax') }}</label>
                                <input type="text" class="form-control parent-input " id="purchase_price" name="purchase_price" readonly required>
                            </div>
                            <?php
                            $avtSaleRate = App\Models\Avt::find(1);
                            $avtSaleRate = $avtSaleRate->AVT;
                            ?>


                        </div>






                        <div class="row mb-2">




                        </div>
                        <div>

                            <div class='row'>
                            <div class="col-lg-2 mg-t-20 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.saleperpice') }}</label>
                                <input type="text" class="form-control parent-input" id="priceWithTax" name="priceWithTax" onchange="changePriceWithTax()" onkeyup="convertToNumberPricewithTaxSale()" required>
                            </div>
                                <div class="col-lg-2">
                                    <label for="inputName" class="control-label ">
                                        {{ __('home.sellingproduct without tax') }}</label>
                                    <input type="text" class="form-control parent-input " id="product_price" name="product_price" onchange="changeAvtValue('{{ $avtSaleRate }}')" onkeyup="convertToNumberPriceSale()" required>
                                </div>

                                <div class="col-lg-2">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.avt') }} </label>
                                    <input type="number" class="form-control parent-input " id="avt" name="avt" title="يرجي ادخال الكمية  "  readonly>
                                </div>
                                <div class="col-lg-2">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }} </label>
                                    <input type="number" class="form-control parent-input " id="quantity" name="quantity" title="يرجي ادخال الكمية  " value=1 required>
                                </div>

                                <div class="col-lg-2">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.discount') }}</label>
                                    <input type="text" class="form-control parent-input " id="product_price_after_dis" name="product_price_after_dis" value=0 onkeyup="convertToNumber()">
                                </div>

                                <div class="col-lg-2" id="type">
                                    <p class="mg-b-10"> {{ __('home.paymentmethod') }} </p>
                                    <select class="form-control select2" name="pay" id='pay' required>

                                        <option value="Cash"> {{ __('report.cash') }}</option>
                                        <option value="Shabka "> {{ __('report.shabka') }} </option>
                                        <option value="Credit "> {{ __('report.credit') }} </option>

                                    </select>

                                </div>
                                

                                <input type="hidden" id="token_search" value="{{ csrf_token() }}">
                                <div class="col mg-t-20 mg-lg-t-0">
                                    <input type="text" hidden=true class="form-control parent-input" id="invoice_number" name="invoice_number">
                                    <input type="text" hidden=true class="form-control parent-input" id="productNo" name="productNo  ">

                                </div>
                                <br>

                            </div>
                            <div class="row">
                                <div class="col mg-t-20 mg-lg-t-0" id="type">
                                    <p class="mg-b-10"> {{ __('home.chooseclient') }} </p>
                                    <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">
                                        <option value="{{ $data['customer']->id ?? 1 }}">
                                            {{ $data['customer']->name ?? __('home.Cash Custome') }}
                                            {{ $data['customer']->phone ?? '' }}
                                        </option>

                                        @foreach (App\Models\customers::get() as $customer)
                                        <option value="{{ $customer->id }}"> {{ $customer->name }}
                                            {{ $customer->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div><!-- col-4 -->
                                <div class="col">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.creditlimit') }}
                                    </label>
                                    <input type="number" class="form-control parent-input " id="creditlimit" name="creditlimit" title="يرجي ادخال الكمية  " value="{{ $data['customer']->Limit_credit ?? '0' }}" readonly required>
                                </div>
                                <div class="col">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.current balance') }}
                                    </label>
                                    <input type="number" class="form-control parent-input " id="balance" name="balance" title="يرجي ادخال الكمية  " value="{{ $data['customer']->Balance ?? '0' }}" readonly required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="inputName" class="control-label parent-label">{{ __('home.notesClient') }}
                                    </label>
                                    <input type="text" class="form-control parent-input" id="notes" name="notes" title="يرجي ادخال ملاحظات  " value="{{ __('home.notesClient') }}">
                                </div>
                            </div>
                            <br>
                            <div class="d-flex justify-content-center">
                                <button id="button_1" name="button_1" class="btn btn-success print-style p-2">
                                    {{ __('home.Add') }}
                                    <svg style="width: 22px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                        <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </form>
                    <br>

                </div>
                <?php $i = 0; ?>


                <div style="padding-left: 0;padding-right:0" class="col">
                    <div style="border-radius: 10px" class="card px-3 pt-4">

                        <div style="padding-bottom: 0" class="card-body">
                            <div class="row">

                                <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                                    <label for="recipient-name" class="form-label parent-label"> {{ __('home.Invoice_no') }}
                                    </label>
                                    <input style="height: 30px;outline :none" for="inputName" class="col-lg-3 mg-t-20 mg-lg-t-0 parent-input" id="showInvoiceNumber" name="showInvoiceNumber" title="{{ __('home.Invoice_no') }}" readonly>

                                </div>

                            </div>
                            <br>

                            <table id="example" class="table our-table border mb-0 table-responsive text-center" style="border: 1px solid black;border-collapse: collapse !important;">
                                <col style="width:2%">
                                <col style="width:15%">
                                <col style="width:23%">
                                <col style="width:10%">
                                <col style="width:7%">
                                <col style="width:7%">
                                <col style="width:7%">
                                <col style="width:7%">
                                <col style="width:22%">
                                <thead>
                                    <tr>
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

                                <body style="color: black">
                                    <tr style="color: black">
                                        <td style="color:black">-</td>
                                        <td style="color:black">-</td>
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

                            <div class="table-responsive mg-t-30">
                                <div class="row">

                                    <div class="col-lg-3 mg-t-10 mg-lg-t-0">
                                        <label for="inputName" class="control-label parent-label">
                                            {{ __('home.discount') }}
                                        </label>
                                        <input type="text" class="form-control parent-input" id="totaldicount" name="totaldicount" type="number" onchange="makeDiscountInvoice()">
                                    </div>

                                    <div class="col-lg-2 mg-t-10 mg-lg-t-0">
                                        <br>
                                        <button style="font-size: 15px; width: 150px;height: 35px;" class="btn btn-danger p-1 " onclick="cancelDiscountInvoice()">
                                            {{ __('home.canceldiscount') }}
                                            <svg class="svg-icon-buttons" style="width: 15 !important;height: 15 !important" viewBox="0 0 20 20">
                                                <path fill="none" d="M12.71,7.291c-0.15-0.15-0.393-0.15-0.542,0L10,9.458L7.833,7.291c-0.15-0.15-0.392-0.15-0.542,0c-0.149,0.149-0.149,0.392,0,0.541L9.458,10l-2.168,2.167c-0.149,0.15-0.149,0.393,0,0.542c0.15,0.149,0.392,0.149,0.542,0L10,10.542l2.168,2.167c0.149,0.149,0.392,0.149,0.542,0c0.148-0.149,0.148-0.392,0-0.542L10.542,10l2.168-2.168C12.858,7.683,12.858,7.44,12.71,7.291z M10,1.188c-4.867,0-8.812,3.946-8.812,8.812c0,4.867,3.945,8.812,8.812,8.812s8.812-3.945,8.812-8.812C18.812,5.133,14.867,1.188,10,1.188z M10,18.046c-4.444,0-8.046-3.603-8.046-8.046c0-4.444,3.603-8.046,8.046-8.046c4.443,0,8.046,3.602,8.046,8.046C18.046,14.443,14.443,18.046,10,18.046z"></path>
                                            </svg>
                                        </button>
                                    </div>

                                </div>

                                <br>
                                <br>
                                <div class="table-padding">
                                    <table class="table border text-md-nowrap mb-0 w-100 text-center our-table mb-2" id="tableTotalPrice" name="tableTotalPrice" width="50%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                                <th class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                                <th class="border-bottom-0">{{ __('home.discount') }} </th>
                                                <th class="border-bottom-0">{{ __('home.total') }} </th>

                                            </tr>
                                        </thead>

                                        <body>

                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                        </body>

                                    </table>
                                </div>

                                <br>
                                <form action="{{ '/' . ($page = 'returnAll') }}" method="POST" role="search" autocomplete="off">
                                    {{ csrf_field() }}


                                    <div class="row d-flex justify-content-end">
                                        <input type="number" class="form-control parent-input " name="invoice_no_delete_All" id="invoice_no_delete_All" title=" رقم الفاتورة " readonly required=true hidden>
                                        <input class="form-control parent-input " name="pagename" id="pagename" readonly value="Receipt" hidden>



                                        <button style="font-size: 15px; width: 150px;" type="submit" class="btn btn-danger p-1">
                                            {{ __('home.returnAll') }}
                                            <svg class="svg-icon-buttons" style="width: 18px !important" viewBox="0 0 20 20">
                                                <path fill="none" d="M12.71,7.291c-0.15-0.15-0.393-0.15-0.542,0L10,9.458L7.833,7.291c-0.15-0.15-0.392-0.15-0.542,0c-0.149,0.149-0.149,0.392,0,0.541L9.458,10l-2.168,2.167c-0.149,0.15-0.149,0.393,0,0.542c0.15,0.149,0.392,0.149,0.542,0L10,10.542l2.168,2.167c0.149,0.149,0.392,0.149,0.542,0c0.148-0.149,0.148-0.392,0-0.542L10.542,10l2.168-2.168C12.858,7.683,12.858,7.44,12.71,7.291z M10,1.188c-4.867,0-8.812,3.946-8.812,8.812c0,4.867,3.945,8.812,8.812,8.812s8.812-3.945,8.812-8.812C18.812,5.133,14.867,1.188,10,1.188z M10,18.046c-4.444,0-8.046-3.603-8.046-8.046c0-4.444,3.603-8.046,8.046-8.046c4.443,0,8.046,3.602,8.046,8.046C18.046,14.443,14.443,18.046,10,18.046z"></path>
                                            </svg>
                                        </button>

                                    </div>






                                </form>
                                <form action="{{ '/' . ($page = 'printReceiptToStorehouse') }}" method="POST" role="search" autocomplete="off">
                                    {{ csrf_field() }}

                                    <br>



                                    <?php $i = 0; ?>
                                    <div class="col-xl-12">
                                        <div class="card-header pb-0">
                                            <div class="d-flex justify-content-between">
                                                <div class='row '>
                                                    <input type="number" class="form-control parent-input " name="show_invoice_number" id="show_invoice_number" title=" رقم الفاتورة " readonly required=true hidden>

                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <button style="background-color: #419BB2;font-size:12px" type="submit" class="btn btn-success p-1">
                                                        {{ __('home.Print the invoice for receipt from the store') }}
                                                        <svg style="width: 16px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- <a class="btn btn-success" href="{{ url('/' . ($page = 'printInvoice')) }}"> {{ __('home.print') }}</a> -->
                                                    <br>

                                                </div>

                                                <br>
                                                <br>
                                            </div>
                                        </div>
                                    </div>

                                    <br />


                            </div>


                            </form>


                        </div>


                    </div>
                </div>

            </div>









            <!-- row closed -->
        </div>
        <!-- Container closed -->
    </div>
    <div class="modal p-3" id="createcustomer">
        <div style="margin: 0 15% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.addnewcustomer') }} </h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label"> {{ __('supprocesses.name') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="name" name="name" title="{{ __('supprocesses.name') }}" required>
                        </div>

                        <div class="col-lg-3 col-md-3 mb-2 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label"> {{ __('supprocesses.phone') }}</label>
                            <input style="height:32px;" type="text" class="form-control parent-input" id="phone" name="phone" onkeyup="phoneConvert()" title="{{ __('supprocesses.phone') }}">
                        </div>

                        <div class="col-lg-3 col-md-3 mb-2 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label"> {{ __('supprocesses.email') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="email" name="email" title="{{ __('supprocesses.email') }}" value='Example@gmail.com'>
                        </div>
                        <!-- <div class="col-lg-3 col-md-3">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.current balance') }} </label>
                                    <input type="number" class="parent-input form-control" id="balance" name="balance"
                                        title="يرجي ادخال الكمية  " value="{{ $data['customer']->Balance ?? '0' }}"
                                        >
                                </div> -->
                    </div>

                    {{-- 2 --}}
                    <div class="row mb-1">
                        <div class="col-lg-3 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.timeout_periodـinـdays') }}</label>
                            <input style="height:32px" type="number" class="form-control parent-input" id="timeout_periodـinـdays" name="timeout_periodـinـdays" title="{{ __('supprocesses.timeout_periodـinـdays') }}" onkeyup="timeout_periodـinـdaysConvert()" value=30 required>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label"> {{ __('home.tax_number') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="TaxـNumber" name="TaxـNumber" onkeyup="TaxـNumberConvert()" title="{{ __('supprocesses.TaxـNumber') }}">
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.credit_limit') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="credit_limit" name="credit_limit" onkeyup="credit_limitConvert()" title="{{ __('supprocesses.credit_limit') }}" value=10000 required>
                        </div>

                        <div class="col-lg-3 col-md-3">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                                {{ __('supprocesses.product_notes') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="product_notes" name="product_notes" title="{{ __('supprocesses.product_notes') }}" value='-'>
                        </div>

                    </div>

                    <br>
                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" onclick="createnewcustomerajax()">
                            {{ __('supprocesses.save_data') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                    </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
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
                            <input type="text" class="form-control parent-input" placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                                <col style="width:5%">
                                <col style="width:14%">
                                <col style="width:28%">
                                <col style="width:10%">
                                <col style="width:18%">
                                <col style="width:15%">
                                <col style="width:10%">

                                <thead>
                                    <tr>
                                        <th style="font-size: 15px" class="border-bottom-0">#</th>
                                        <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                                        <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                                        <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                        <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                                        <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>
                                        <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php $i = 0;
                                    $data = 'm'; ?>

                                    @foreach ($products as $product)
                                    <?php $i++ ?>

                                    <tr id="<?php echo $product['id']; ?>">
                                        <td id="tableData" style="" dir=ltr>{{ $product->id }}</td>
                                        <td id="tableData" style="" dir=ltr>{{ $product->Product_Code }}</td>
                                        <td id="tableData" style="" data-target="product_name">{{ $product->product_name }}</td>
                                        <td id="tableData" style="" data-target="numberofpice">{{ $product->numberofpice }}</td>
                                        <td id="tableData" style="" data-target="numberofpice">{{ $product->purchasingـprice }}</td>
                                        <td id="tableData" style="" data-target="numberofpice">{{ $product->sale_price }}</td>
                                        <td id="tableData" style="">
                                            @if($product->numberofpice==0)

                                            <button style="padding: 6px 15px" type="button" id="btn" name="btn" class="btn btn-warning" data-dismiss="modal">{{__('home.notavailable')}}</button>

                                            @endif
                                            @if($product->numberofpice>0)

                                            <button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick="chooseProduct('{{$product->id}}','{{$product->Product_Code}}','{{$product->product_name}}','{{$product->purchasingـprice}}','{{$product->sale_price}}','{{$product->Product_Location}}','{{$product->numberofpice}}')">{{__('home.Add')}}</button>


                                            @endif

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>

                            </div>

                            <div class="row d-flex justify-content-between pagination-row">

                                <div class="rows-number">
                                    <label style="font-size: 12px;color:#419BB2;font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;{{__('pagination.numberofpages')}} &nbsp;&nbsp; {{$products->perPage()}} &nbsp;&nbsp;</label>
                                    <label style="font-size: 12px;color:#419BB2;font-weight:bold">{{__('pagination.numberofproducts')}} &nbsp;&nbsp;{{$products->total()}} </label>
                                </div>

                                <div class="buttonss-table row d-flex justify-content-center mb-2">
                                    <button id="previousPage" name="previousPage" class="btn btn-primary print-style"><span>
                                            << &nbsp;</span>{{__('pagination.previous')}}</button>
                                    <div class="col-lg-2 col-md-2 col-sm-2 mg-lg-t-0 prodNumbers"> <input class="form-control " name="currentpage" id="currentpage" readonly value="{{$products->currentPage()}}" readonly>
                                    </div>
                                    <input class="form-control " name="previousPagevalue" id="previousPagevalue" readonly value="{{$products->previousPageUrl()}}" hidden>
                                    <input class="form-control " name="nextPageValue" id="nextPageValue" readonly value="{{$products->nextPageUrl()}}" hidden>
                                    <button id="nextPage" name="nextPage" class="btn btn-primary print-style">{{__('pagination.next')}} >></button>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button> --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    </div>

                </div>


            </div>
        </div>

    </div>

    <!-- edit -->

            <!-- edit -->
            <div class="modal fade" id="increaseProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('home.addtoSales') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatePurchase')) }}" method="post" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input type="hidden" name="id_update" id="id_update" value="">
                                    <label for="recipient-name" class="col-form-label"> {{ __('home.product') }}
                                    </label>
                                    <input class="form-control" name="product_name_update" id="product_name_update" readonly>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="inputName" class="control-label parent-label">
                                            {{ __('home.sellingproduct without tax') }}</label>
                                        <input type="text" class="form-control parent-input" id="product_price_update" name="product_price_update" onchange="changeAvtValue('{{ $avtSaleRate }}')" onkeyup="changeAvtValuempdale()" required>
                                    </div>
                                    <div class="col">
                                        <label for="inputName" class="control-label parent-label"> {{ __('home.avt') }} </label>
                                        <input type="text" class="form-control parent-input" id="avt_update" name="avt_update" title="يرجي ادخال الكمية  " value="{{ $avtSaleRate }}" readonly>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col">
                                        <label for="inputName" class="control-label parent-label"> {{ __('home.discount') }}</label>
                                        <input type="text" class="form-control parent-input" id="product_price_after_dis_update" value=0 name="product_price_after_dis_update">
                                    </div>

                                    <div class="col">
                                        <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }} </label>
                                        <input type="text" class="form-control parent-input" id="quantity_update" name="quantity_update" title="يرجي ادخال الكمية  " value=1 required>
                                    </div>
                                </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button id="updateproductalldata" name="updateproductalldata" class="btn btn-danger">{{ __('home.confirm') }}</button>
                        </div>

                        </form>
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
                        <input type="hidden" name="return_quentity_delete" id="return_quentity_delete">
                        <input class="form-control parent-input" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button id="deleteproduct" name="deleteproduct" class="btn btn-danger">{{ __('home.confirm') }}</button>
                    </div>
            </div>
            </form>
        </div>
    </div>





    <!-- main-content closed -->
</div>
@endsection @section('js')
        <!-- Internal Data tables -->

        <!-- Internal Data tables -->
        <!-- Internal Data tables -->
        <!--Internal  Datatable js -->
        <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
        <!--Internal  Datatable js -->
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
            function TaxـNumberConvert() {
                var input = document.getElementById("TaxـNumber");
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
            $('#increaseProduct').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var section_name = button.data('section_name')
                var return_quentity = button.data('return_quentity')
                var price = button.data('section_price')
                var discount = button.data('section_discount')
                var modal = $(this)
                section_name = section_name.replaceAll("?", " ")

                avtsale = $('#avtValue').val();

                modal.find('.modal-body #id_update').val(id);
                modal.find('.modal-body #product_name_update').val(section_name);
                modal.find('.modal-body #product_price_update').val(price);
                modal.find('.modal-body #avt_update').val(Math.round((price * avtsale) * 1000, 2) / 1000);
                modal.find('.modal-body #quantity_update').val(return_quentity);
                modal.find('.modal-body #product_price_after_dis_update').val(discount);
            })
        </script>
      <script>
            function changePriceWithTax() {
                console.log("erweeweeweww - - - - -")
                price = $('#priceWithTax').val();

                avtvalue=(price*100)/115
                rountavt=Math.round((avtvalue * 100),2) / 100;

                $('#avt').val(Math.round(((price-rountavt) * 100),2) / 100);
                $('#product_price').val(rountavt);




            }
        </script>

        <script>
            function credit_limitConvert() {
                var input = document.getElementById("credit_limit");
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
            function phoneConvert() {
                var input = document.getElementById("phone");
                var val = toEnglishNumber(input.value)
                input.value = val;
            }
            function convertToNumberPricewithTaxSale() {
                var input = document.getElementById("priceWithTax");
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
            function convertToNumber() {
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
            function changeAvtValue(avt) {

                avt = $('#avtValue').val();
                price = $('#product_price').val();
                $('#avt').val(Math.round((price * avt) * 1000) / 1000);



            }
        </script>

        <script>
            function changeAvtValuempdale(avt) {
                price = $('#product_price_update').val();
                avt = $('#avtValue').val();

                $('#avt_update').val(Math.round((price * avt) * 1000) / 1000);



            }
        </script>

        {{-- Update ( 24/4/2023 ) --}}

        <script>
            function searchaboutproductfunction() {
                searchtext = $('#searchaboutproduct').val();
                $.ajax({
                    url: " {{URL::to('searchaboutproduct')}}" + "/" + searchtext,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {


                        $('#previousPagevalue').val(data['prev_page_url'])
                        $('#nextPageValue').val(data['next_page_url'])
                        $('#currentpage').val(data['current_page'])
                        let table = document.getElementById("SearchProductTable");
                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        data['data'].forEach(async (product) => {


                            Product_id = product['id'],
                                Product_Code = product['Product_Code'],
                                id = product['id'],
                                product_name = product['product_name'],
                                purchasingـprice = product['purchasingـprice']
                            sale_price = product['sale_price']
                            numberofpice = product['numberofpice']
                            Product_Location = product['Product_Location']
                            button = '';


                            Product_Code = Product_Code.replaceAll("?", "<");
                            Product_Code = Product_Code.replaceAll(" ", "<");


                            if (numberofpice == 0) {

                                button = ' <button style="padding: 6px 15px" type="button" id="btn" name="btn" class="btn btn-warning" data-dismiss="modal">{{__("home.notavailable")}}</button>'


                            } else {
                                text = '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                                name = product_name.replaceAll(" ", "<");
                                loction = Product_Location.replaceAll(" ", "<");
                                Product_Code = Product_Code.replaceAll("?", "<");
                                Product_Code = Product_Code.replaceAll(" ", "<");

                                button = text.concat("chooseProduct(", id, ",", "'", Product_Code, "'", ",", "'", name, "'", ",", purchasingـprice, ",", sale_price, ",", "'", loction, "'", ",", numberofpice, ")", ">{{__('home.Add')}}</button>");


                            }

                            Product_Code = Product_Code.replaceAll("<", " ");

                            let row = table.insertRow(-1); // We are adding at the end

                            let c1 = row.insertCell(0);
                            let c2 = row.insertCell(1);
                            let c3 = row.insertCell(2);
                            let c4 = row.insertCell(3);
                            let c5 = row.insertCell(4);
                            let c6 = row.insertCell(5);
                            let c7 = row.insertCell(6);
                            let c8 = row.insertCell(7);

                            c1.innerText = Product_id

                            c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                            c3.innerHTML = product_name
                            c4.innerText = Product_Location
                            c5.innerText = numberofpice
                            c6.innerText = purchasingـprice
                            c7.innerText = sale_price
                            c8.innerHTML = button


                        });

                    },
                });
            }
        </script>

        {{-- End Update ( 24/4/2023 ) --}}




        {{-- Update ( 24/4/2023 ) --}}

        <script>
            function chooseProduct(code, productcode, name, price, sale_price, location, availablequantity) {
                $('#SearchProduct').modal().hide();
                $('#searchaboutproduct').val('');
                name = name.replaceAll("<", " ");
                name = name.replaceAll("?", " ");
                location = location.replaceAll("<", " ");
                productcode = productcode.replaceAll("<", " ");
                productcode = productcode.replaceAll("?", " ");
                $("#productNo").val(code);
                $('#product_name').val(name);
                $('#product_location').val(location);
                $('#avaliable_quentity').val(availablequantity);
                $('#quantity').val(1);
                $('#product_code').val(productcode);

                $('#product_price').val(sale_price);
                $('#purchase_price').val(price);
                avtsale = $('#avtValue').val();
                $('#avt').val((sale_price * avtsale).toFixed(2));
                $('#priceWithTax').val(((sale_price * avtsale)+(sale_price*1)) );

                console.log(code);
                console.log(name);
                console.log(location);
                console.log(availablequantity);
                console.log(sale_price);
                console.log(price);
                console.log('AJAX load   work');

                console.log('AJAX load   work');
            }
        </script>

        {{-- End Update ( 24/4/2023 ) --}}

        <script>
            $('#modaldemo9').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var return_quentity = button.data('return_quentity')
                var section_name = button.data('section_name')
                section_name = section_name.replaceAll("?", " ")

                console.log('lololoooo')
                console.log(id)
                console.log(return_quentity)
                console.log('lololoooo')
                var modal = $(this)
                modal.find('.modal-body #id_delete').val(id);
                modal.find('.modal-body #return_quentity_delete').val(return_quentity);
                modal.find('.modal-body #product_name').val(section_name);
                console.log('lololoooo')
            })
        </script>

        <script>
            function makeDiscountInvoice() {


                invoiceId = $('#showInvoiceNumber').val()
                totaldicount = $('#totaldicount').val()
                $('#totaldicount').val('')
                $.ajax({
                    url: "{{ URL::to('/makeTotalDiscont') }}/" + invoiceId + "/" + totaldicount,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        if (data && data['discount'] != 0) {

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
                            discount = data['discount']
                            c1.innerText = (Math.round(data['totalprice'] * 100) / 100).toFixed(2),
                                c2.innerText = (Math.round(data['addedvalueafterdiscount'] * 100) / 100).toFixed(2),
                                c3.innerText = (Math.round(data['discount'] * 100) / 100).toFixed(2),
                                totaldiscountvalue = data['totalprice'] + data['addedvalueafterdiscount'];
                            c4.innerText = (Math.round(totaldiscountvalue * 100) / 100).toFixed(2);




                        } else {
                            alert("{{ __('home.sorryerror') }}")
                        }
                    },
                });








            }









            function cancelDiscountInvoice() {


                invoiceId = $('#showInvoiceNumber').val()
                totaldicount = $('#totaldicount').val()
                $('#totaldicount').val('')
                console.log("{{ URL::to('/cancelInvoiceDiscont') }}/" + invoiceId)
                $.ajax({
                    url: "{{ URL::to('/cancelInvoiceDiscont') }}/" + invoiceId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success cancel discount");
                        console.log(data);
                        if (data) {

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
                            discount = data['discount']
                            c1.innerText = (Math.round(data['totalprice'] * 100) / 100).toFixed(2),
                                c2.innerText = (Math.round(data['addedvalueafterdiscount'] * 100) / 100).toFixed(2),
                                c3.innerText = (Math.round(data['discount'] * 100) / 100).toFixed(2),
                                totaldiscountvalue = data['totalprice'] + data['addedvalueafterdiscount'];
                            c4.innerText = (Math.round(totaldiscountvalue * 100) / 100).toFixed(2);




                        } else {
                            alert("{{ __('home.sorryerror') }}")
                        }
                    },
                });








            }






            function convertToNumberPriceSale() {
                var input = document.getElementById("product_price");
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
            $('#exampleModal2').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var section_name = button.data('section_name')
                var return_quentity = button.data('return_quentity')
                var modal = $(this)
                modal.find('.modal-body #id').val(id);
                modal.find('.modal-body #product_name').val(section_name);
                modal.find('.modal-body #return_quentity').val(return_quentity);
            })
        </script>

        <script>
            $('#modaldemo9').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var return_quentity = button.data('return_quentity')
                var section_name = button.data('section_name')
                section_name = section_name.replaceAll("?", " ")

                console.log('lololoooo')
                console.log(id)
                console.log(return_quentity)
                console.log('lololoooo')
                var modal = $(this)
                modal.find('.modal-body #id_delete').val(id);
                modal.find('.modal-body #return_quentity_delete').val(return_quentity);
                modal.find('.modal-body #product_name').val(section_name);
                console.log('lololoooo')
            })
        </script>




        <script>
            function createnewcustomerajax() {
                console.log('+++++++++++++++++++++++++++++++++create customer ++++++++++++++++++++++++++++++++');
                var url = " {{ URL::to('createnewcustomerajax') }}";
                console.log($('#token_search').val())
                console.log($('#product_notes').val())
                console.log($('#email').val())
                console.log($('#phone').val())
                console.log($('#TaxـNumber').val())
                console.log($('#name').val())
                console.log($('#timeout_periodـinـdays').val())
                console.log(url);
                console.log(',,,.,.,.');
                var token_search = $("#token_search").val();
                if ($('#name').val() == '') {
                    alert("{{ __('home.enterclienname') }}")
                } else if ($('#TaxـNumber').val() == '') {
                    alert("{{ __('home.tax_number') }}")
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
                            address: "Client Address",
                            phone: $('#phone').val(),
                            email: $('#email').val(),
                            notes: $('#product_notes').val(),
                            Limit_credit: $('#credit_limit').val(),
                            grace_period_in_days: $('#timeout_periodـinـdays').val()
                        },


                        success: function(data) {
                            $('#phone').val('');
                            $('#TaxـNumber').val('');
                            $('#name').val('')
                            console.log('seccusss12111');
                            console.log(data)
                            $('#clientnamesearch').append($('<option>', {
                                value: data['id'],
                                text: data['name']
                            }));
                        },
                        error: function(response) {
                            alert("{{ __('home.sorryerror') }}")

                        }
                    });







                }


            }
        </script>


        <script>
            function decreaseProduct(id, decreaseCunatity) {
                event.preventDefault();

                var url = $(this).attr('data-action');
                let table = document.getElementById("example");


                var token_search = $("#token_search").val();
                console.log(token_search);

                var url = " {{ URL::to('EditInvoices') }}";
                token_search = $('#token_search').val();
                id = id;
                return_quentity = decreaseCunatity;
                console.log('+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
                console.log(id);
                console.log(return_quentity);


                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        id: id,
                        return_quentity: decreaseCunatity,
                    },


                    success: function(data) {
                        console.log('seccusss12111');

                        console.log(data)
                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        i = 0;
                        added_value_total = 0;
                        total_sales = 0;
                        total_amount = 0;
                        data['product'].forEach(async (product) => {


                            sales_id = product['id'],
                                count1 = product['count'],
                                product_code = product['Product_Code']
                            product_name = product['product_name']
                            quentity = product['quantity']
                            price = product['Unit_Price']
                            discount = product['Discount_Value']
                            addedvalue = product['Added_Value']
                            total = product['Unit_Price'] * product['quantity'] + product[
                                'Added_Value'] * product['quantity']
                            added_value_total = added_value_total + (product['Added_Value'] * product[
                                'quantity'])
                            total_sales = total_sales + (price * product['quantity'])

                            console.log(product_name);

                            text1 =
                                '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                            result = text1.concat("onclick=", "decreaseProduct(", sales_id, ",", "1",
                                ")>",
                                '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px;" class="las la-minus"></i>',
                                "</button> ")
                            product_name_update = product_name.replaceAll(" ", "?")
                            text2 =
                                ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                            result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                "  ", "data-return_quentity=", quentity, '  ',
                                '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                            )

                            update =
                                ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                            update = update.concat(sales_id, "  ", "data-section_name=", product_name_update, "  ", "data-section_price=", price, "  ", "data-section_discount=", discount,
                                "  ", "data-return_quentity=", quentity, '  ',
                                '  data-toggle="modal"   href="#increaseProduct"   title="حذف"><i class="las la-align-justify"></i></a>'
                            )
                            text3 =
                                '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                            result3 = text3.concat("onclick=", "increaseProduct(", sales_id, ",", "1",
                                ")>",
                                '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px" class="las la-plus"></i>',
                                "</button> ")

                            if (quentity > 0) {


                                let table = document.getElementById("example");
                                let row = table.insertRow(-1); // We are adding at the end

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);
                                let c5 = row.insertCell(4);
                                let c6 = row.insertCell(5);
                                let c7 = row.insertCell(6);
                                let c8 = row.insertCell(7);
                                let c9 = row.insertCell(8);

                                // Add data to c1 and c2

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = price
                                c5.innerText = quentity
                                c6.innerText = price * quentity
                                c7.innerText = discount
                                c8.innerText = (price * quentity) - discount
                                c9.innerHTML = result + ' ' + result3 + ' ' + ' ' + update + result2





                            }


                        });


                        //    update3/3/2023

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

                        c1.innerText = (Math.round(data['invoicetotal_price'] * 100) / 100).toFixed(2);
                        c2.innerText = (Math.round(data['invoicetotal_addedvalue'] * 100) / 100).toFixed(2);
                        c3.innerText = (Math.round(data['invoicetotal_discount'] * 100) / 100).toFixed(2);
                        c4.innerText = (Math.round((data['invoicetotal_addedvalue'] + data['invoicetotal_price']) * 100) / 100).toFixed(2);




                        // var rowCount = table.rows.length;

                        // for (var i = 0; i < rowCount; i++) {
                        //     var data = table.rows[i].innerText.innerText;
                        //     console.log('end');

                        // }




                    },
                    error: function(response) {
                        alert("{{ __('home.sorryerror') }}")

                    }
                });
            }
        </script>

        <script>
            //increaseproduct


            function increaseProduct(id, increasequantity) {
                event.preventDefault();

                let table = document.getElementById("example");


                var token_search = $("#token_search").val();
                console.log(token_search);

                var url = " {{ URL::to('increaseProduct') }}";
                token_search = $('#token_search').val();


                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        id: id,
                        increasequantity: increasequantity,
                    },


                    success: function(data) {
                        console.log('seccusss12111');
                        added_value_total = 0;
                        total_sales = 0;
                        console.log(data)
                        console.log(data[0] == "notfount")
                        if (data[0] == "notfount") {
                            alert("{{ __('home.stocknotAvailable') }}");
                        } else {
                            var tableHeaderRowCount = 1;

                            var rowCount = table.rows.length;
                            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                table.deleteRow(tableHeaderRowCount);
                            }

                            data['product'].forEach(async (product) => {


                                sales_id = product['id'],
                                    count1 = product['count'],
                                    product_code = product['Product_Code']
                                product_name = product['product_name']
                                quentity = product['quantity']
                                price = product['Unit_Price']
                                discount = product['Discount_Value']
                                addedvalue = product['Added_Value']
                                total = product['Unit_Price'] * product['quantity'] + product[
                                    'Added_Value'] * product['quantity']
                                added_value_total = added_value_total + (product['Added_Value'] *
                                    product['quantity'])
                                total_sales = total_sales + (price * product['quantity'])
                                console.log(product_name);

                                text1 =
                                    '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                result = text1.concat("onclick=", "decreaseProduct(", sales_id, ",", "1",
                                    ")>",
                                    '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px;" class="las la-minus"></i>',
                                    "</button> ")
                                product_name_update = product_name.replaceAll(" ", "?")
                                text2 =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                    "  ", "data-return_quentity=", quentity, '  ',
                                    '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                )

                                update =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                                update = update.concat(sales_id, "  ", "data-section_name=", product_name_update, "  ", "data-section_price=", price, "  ", "data-section_discount=", discount,
                                    "  ", "data-return_quentity=", quentity, '  ',
                                    '  data-toggle="modal"   href="#increaseProduct"   title="حذف"><i class="las la-align-justify"></i></a>'
                                )
                                text3 =
                                    '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                result3 = text3.concat("onclick=", "increaseProduct(", sales_id, ",", "1",
                                    ")>",
                                    '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px" class="las la-plus"></i>',
                                    "</button> ")

                                if (quentity > 0) {


                                    let table = document.getElementById("example");
                                    let row = table.insertRow(-1); // We are adding at the end

                                    let c1 = row.insertCell(0);
                                    let c2 = row.insertCell(1);
                                    let c3 = row.insertCell(2);
                                    let c4 = row.insertCell(3);
                                    let c5 = row.insertCell(4);
                                    let c6 = row.insertCell(5);
                                    let c7 = row.insertCell(6);
                                    let c8 = row.insertCell(7);
                                    let c9 = row.insertCell(8);

                                    // Add data to c1 and c2

                                    c1.innerText = count1
                                    c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                    c3.innerText = product_name
                                    c4.innerText = price
                                    c5.innerText = quentity
                                    c6.innerText = price * quentity
                                    c7.innerText = discount
                                    c8.innerText = (price * quentity) - discount
                                    c9.innerHTML = result + ' ' + result3 + ' ' + ' ' + update + result2





                                }


                            });


                            //    update3/3/2023


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

                            c1.innerText = (Math.round(data['invoicetotal_price'] * 100) / 100).toFixed(2);
                            c2.innerText = (Math.round(data['invoicetotal_addedvalue'] * 100) / 100).toFixed(2);
                            c3.innerText = (Math.round(data['invoicetotal_discount'] * 100) / 100).toFixed(2);
                            c4.innerText = (Math.round((data['invoicetotal_addedvalue'] + data['invoicetotal_price']) * 100) / 100).toFixed(2);






                            // var rowCount = table.rows.length;

                            // for (var i = 0; i < rowCount; i++) {
                            //     var data = table.rows[i].innerText.innerText;
                            //     console.log('end');

                            // }


                        }

                    },
                    error: function(response) {
                        alert("{{ __('home.sorryerror') }}")

                    }
                });
            }



            //endincrease product
        </script>
        <script>
            $(document).ready(function() {
                $(function() {
                    var timeout = 4000; // in miliseconds (3*1000)
                    $('.alert').delay(timeout).fadeOut(500);
                });
                $('select[name="productNo"]').on('change', function() {




                    console.log('AJAX load   work 0000');

                    var selectProduct = $(this).val();
                    console.log(selectProduct);
                    $.ajax({
                        url: "{{ URL::to('getProductdJsonDecode') }}/" + selectProduct,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                            if (data && data['numberofpice'] > 0) {


                                $('#product_name').val(data['product_name']);
                                $('#product_location').val(data['Product_Location']);
                                $('#avaliable_quentity').val(data['numberofpice']);
                                $('#quantity').val(1);
                                $('#product_price').val(data['sale_price']);
                                $('#purchase_price').val(data['purchasingـprice']);
                                console.log('AJAX load   work');

                            } else {

                                alert("{{ __('home.stocknotAvailable') }}");

                            }
                        },
                    });

                });

                //update today
                //increaseProduct




                //endincreaseProductbutton


                //addProduct



                // Update ( 24/4/2023 )




                $("#nextPage").click(function(e) {
                    url = $('#nextPageValue').val().split('page=')[1];
                    $.ajax({
                        url: " {{URL::to('goToSaleBypage')}}" + "?page=" + url,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {


                            $('#previousPagevalue').val(data['prev_page_url'])
                            $('#nextPageValue').val(data['next_page_url'])
                            $('#currentpage').val(data['current_page'])
                            let table = document.getElementById("SearchProductTable");
                            var tableHeaderRowCount = 1;

                            var rowCount = table.rows.length;

                            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                table.deleteRow(tableHeaderRowCount);
                            }
                            data['data'].forEach(async (product) => {
                                Product_id = product['id'],
                                    Product_Code = product['Product_Code'],
                                    id = product['id'],
                                    product_name = product['product_name'],
                                    purchasingـprice = product['purchasingـprice']
                                sale_price = product['sale_price']
                                numberofpice = product['numberofpice']
                                Product_Location = product['Product_Location']
                                button = '';


                                Product_Code = Product_Code.replaceAll(" ", "<");

                                Product_Code = Product_Code.replaceAll("?", "<");



                                if (numberofpice == 0) {

                                    button = ' <button style="padding: 6px 15px" type="button" id="btn" name="btn" class="btn btn-warning" data-dismiss="modal">{{__("home.notavailable")}}</button>'


                                } else {
                                    text = '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';
                                    name = product_name.replaceAll(" ", "<");
                                    loction = Product_Location.replaceAll(" ", "<");
                                    button = text.concat("chooseProduct(", id, ",", "'", Product_Code, "'", ",", "'", name, "'", ",", purchasingـprice, ",", sale_price, ",", "'", loction, "'", ",", numberofpice, ")", ">{{__('home.Add')}}</button>");
                                }
                                Product_Code = Product_Code.replaceAll("<", " ");

                                let row = table.insertRow(-1); // We are adding at the end

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);
                                let c5 = row.insertCell(4);
                                let c6 = row.insertCell(5);
                                let c7 = row.insertCell(6);
                                let c8 = row.insertCell(7);

                                c1.innerText = Product_id

                                c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                                c3.innerHTML = product_name
                                c4.innerText = Product_Location
                                c5.innerText = numberofpice
                                c6.innerText = purchasingـprice
                                c7.innerText = sale_price
                                c8.innerHTML = button









                            });

                        },
                    });
                });

                $("#previousPage").click(function(e) {

                    url = $('#previousPagevalue').val().split('page=')[1];

                    if (url != '') {
                        $.ajax({
                            url: " {{URL::to('goToSaleBypage')}}" + "?page=" + url,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {

                                $('#previousPagevalue').val(data['prev_page_url'])
                                $('#nextPageValue').val(data['next_page_url'])
                                $('#currentpage').val(data['current_page'])
                                let table = document.getElementById("SearchProductTable");
                                var tableHeaderRowCount = 1;

                                var rowCount = table.rows.length;

                                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                    table.deleteRow(tableHeaderRowCount);
                                }
                                data['data'].forEach(async (product) => {


                                    Product_id = product['id'],
                                        Product_Code = product['Product_Code'],
                                        id = product['id'],
                                        product_name = product['product_name'],
                                        purchasingـprice = product['purchasingـprice']
                                    sale_price = product['sale_price']
                                    numberofpice = product['numberofpice']
                                    Product_Location = product['Product_Location']
                                    button = '';



                                    Product_Code = Product_Code.replaceAll("?", "<");
                                    Product_Code = Product_Code.replaceAll(" ", "<");


                                    if (numberofpice == 0) {

                                        button = ' <button style="padding: 6px 15px" type="button" id="btn" name="btn" class="btn btn-warning" data-dismiss="modal">{{__("home.notavailable")}}</button>'


                                    } else {
                                        text = '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                                        name = product_name.replaceAll(" ", "<");
                                        loction = Product_Location.replaceAll(" ", "<");
                                        button = text.concat("chooseProduct(", id, ",", "'", Product_Code, "'", ",", "'", name, "'", ",", purchasingـprice, ",", sale_price, ",", "'", loction, "'", ",", numberofpice, ")", ">{{__('home.Add')}}</button>");


                                    }

                                    Product_Code = Product_Code.replaceAll("<", " ");

                                    let row = table.insertRow(-1); // We are adding at the end

                                    let c1 = row.insertCell(0);
                                    let c2 = row.insertCell(1);
                                    let c3 = row.insertCell(2);
                                    let c4 = row.insertCell(3);
                                    let c5 = row.insertCell(4);
                                    let c6 = row.insertCell(5);
                                    let c7 = row.insertCell(6);
                                    let c8 = row.insertCell(7);

                                    c1.innerText = Product_id

                                    c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                                    c3.innerHTML = product_name
                                    c4.innerText = Product_Location
                                    c5.innerText = numberofpice
                                    c6.innerText = purchasingـprice
                                    c7.innerText = sale_price
                                    c8.innerHTML = button





                                });

                            },
                        });
                    } else {
                        alert('url null not fount pervoius')
                    }
                });

                // End Update ( 24/4/2023 )

                $("#button_1").click(function(e) {
                    event.preventDefault();

                    var url = $(this).attr('data-action');
                    let table = document.getElementById("example");


                    var token_search = $("#token_search").val();

                    console.log(" {{ URL::to('AddInvoices') }}");

                    var url = " {{ URL::to('AddInvoices') }}";
                    token_search = $('#token_search').val();
                    productNo = $('#productNo').val();
                    invoice_number = $('#invoice_number').val();
                    product_name = $('#product_name').val();
                    product_price_after_dis = $('#product_price_after_dis').val();
                    quantity = $('#quantity').val();
                    avaliablequantity = $('#avaliable_quentity').val();
                    pay = $('#pay').val();
                    clientnamesearch = $('#clientnamesearch').val();
                    creditlimit = $('#creditlimit').val();
                    purchase_price = $('#purchase_price').val();
                    if (product_name == '') {
                        alert("{{ __('home.pleaseChooseProduct') }}")

                    } else {
                        $.ajax({
                            url: url,
                            type: 'post',
                            cache: false,

                            data: {
                                _token: token_search,
                                productNo: $('#productNo').val(),
                                invoice_number: $('#invoice_number').val(),
                                product_name: $('#product_name').val(),
                                product_price_after_dis: $('#product_price_after_dis').val(),
                                quantity: $('#quantity').val(),
                                pay: $('#pay').val(),
                                clientnamesearch: $('#clientnamesearch').val(),
                                creditlimit: $('#creditlimit').val(),
                                product_price: $('#product_price').val(),
                                purchase_price: $('#purchase_price').val(),
                                note: $('#notes').val() ?? '-',


                            },


                            success: function(data) {

                                $('#product_code').val('');

                                // const map =(JSON.parse(response));
                                if (data[0] == "notfount") {
                                    alert("{{ __('home.stocknotAvailable') }}");
                                } else {



                                    $('#show_invoice_number').val(data['invoice_number'])
                                    $('#invoice_number').val(data['invoice_number']);
                                    $('#showInvoiceNumber').val(data['invoice_number']);
                                    $('#invoice_no_delete_All').val(data['invoice_number']);



                                    var tableHeaderRowCount = 1;

                                    var rowCount = table.rows.length;

                                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                        table.deleteRow(tableHeaderRowCount);
                                    }
                                    count1 = 0;
                                    added_value_total = 0;
                                    total_sales = 0;

                                    data['product'].forEach(async (product) => {


                                        sales_id = product['id'],
                                            count1 = product['count'],
                                            product_code = product['Product_Code']
                                        product_name = product['product_name']
                                        quentity = product['quantity']
                                        price = product['Unit_Price']
                                        discount = product['Discount_Value']
                                        addedvalue = product['Added_Value']
                                        total = product['Unit_Price'] * product[
                                                'quantity'] + product['Added_Value'] *
                                            product['quantity']
                                        added_value_total = added_value_total + (
                                            product['Added_Value'] * product[
                                                'quantity'])
                                        total_sales = total_sales + (price * product[
                                            'quantity'])
                                        console.log(product_name);
                                        text1 =
                                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                        result = text1.concat("onclick=", "decreaseProduct(", sales_id, ",", "1",
                                            ")>",
                                            '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px;" class="las la-minus"></i>',
                                            "</button> ")
                                        product_name_update = product_name.replaceAll(" ", "?")
                                        text2 =
                                            ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                        result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                            "  ", "data-return_quentity=", quentity, '  ',
                                            '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                        )

                                        update =
                                            ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                                        update = update.concat(sales_id, "  ", "data-section_name=", product_name_update, "  ", "data-section_price=", price, "  ", "data-section_discount=", discount,
                                            "  ", "data-return_quentity=", quentity, '  ',
                                            '  data-toggle="modal"   href="#increaseProduct"   title="حذف"><i class="las la-align-justify"></i></a>'
                                        )
                                        text3 =
                                            '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                        result3 = text3.concat("onclick=", "increaseProduct(", sales_id, ",", "1",
                                            ")>",
                                            '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px" class="las la-plus"></i>',
                                            "</button> ")

                                        if (quentity > 0) {


                                            let table = document.getElementById("example");
                                            let row = table.insertRow(-1); // We are adding at the end

                                            let c1 = row.insertCell(0);
                                            let c2 = row.insertCell(1);
                                            let c3 = row.insertCell(2);
                                            let c4 = row.insertCell(3);
                                            let c5 = row.insertCell(4);
                                            let c6 = row.insertCell(5);
                                            let c7 = row.insertCell(6);
                                            let c8 = row.insertCell(7);
                                            let c9 = row.insertCell(8);

                                            // Add data to c1 and c2

                                            c1.innerText = count1
                                            c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                            c3.innerText = product_name
                                            c4.innerText = price
                                            c5.innerText = quentity
                                            c6.innerText = price * quentity
                                            c7.innerText = discount
                                            c8.innerText = (price * quentity) - discount
                                            c9.innerHTML = result + ' ' + result3 + ' ' + ' ' + update + result2







                                        }


                                    });


                                    //    update3/3/2023


                                    let tableTotalPrice = document.getElementById(
                                        "tableTotalPrice");
                                    var tableHeaderRowCount = 1;

                                    var rowCount = tableTotalPrice.rows.length;

                                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                        tableTotalPrice.deleteRow(tableHeaderRowCount);
                                    }
                                    let row = tableTotalPrice.insertRow(-
                                        1); // We are adding at the end

                                    let c1 = row.insertCell(0);
                                    let c2 = row.insertCell(1);
                                    let c3 = row.insertCell(2);
                                    let c4 = row.insertCell(3);


                                    // Add data to c1 and c2


                                    c1.innerText = (Math.round(data['invoicetotal_price'] * 100) / 100).toFixed(2);
                                    c2.innerText = (Math.round(data['invoicetotal_addedvalue'] * 100) / 100).toFixed(2);
                                    c3.innerText = (Math.round(data['invoicetotal_discount'] * 100) / 100).toFixed(2);
                                    c4.innerText = (Math.round((data['invoicetotal_addedvalue'] + data['invoicetotal_price']) * 100) / 100).toFixed(2);






                                    var rowCount = table.rows.length;

                                    for (var i = 0; i < rowCount; i++) {
                                        var data = table.rows[i].innerText.innerText;
                                        console.log('end');

                                    }




                                    $('#product_name').val('');
                                    $('#product_price_after_dis').val(0);
                                    $('#quantity').val(1);
                                    $('#avaliable_quentity').val('');
                                    $('#product_location').val('');
                                    $('#product_price').val('');
                                    $('#purchase_price').val('');
                                    $('#priceWithTax').val('');

                                    $('#productNo').val("__('home.searchbyproductnumber')");

                                }




                            },
                            error: function(response) {
                                alert("{{ __('home.sorryerror') }}")

                            }
                        });
                    }
                });
                $('select[name="clientnamesearch"]').on('change', function() {
                    console.log('AJAX load   work 0000');

                    var selectCustomer = $(this).val();
                    console.log(selectCustomer);
                    if ($('#invoice_number').val() != '') {

                        $.ajax({
                            url: "{{ URL::to('/changechustomer') }}/" + $('#invoice_number').val() + "/" + selectCustomer,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                console.log("success");

                                if (data) {


                                    console.log(data);

                                } else {
                                    alert("{{ __('home.sorryerror')}}")
                                }
                            },
                        });
                    }
                    $.ajax({
                        url: "{{ URL::to('/getcustomer') }}/" + selectCustomer,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                            if (data) {


                                $('#creditlimit').val(data['Limit_credit']);
                                $('#balance').val(data['Balance']);

                                console.log('AJAX load   work');

                            } else {
                                alert("{{ __('home.sorryerror')}}")
                            }
                        },
                    });





                });

                $('select[name="pay"]').on('change', function() {
                    console.log('AJAX load   work 0000');

                    var selectCustomer = $(this).val();
                    console.log(selectCustomer);
                    if ($('#invoice_number').val() == '') {
                        console.log('AJAX load  Not work because no invice number PayMent work');

                    } else {
                        $.ajax({
                            url: "{{ URL::to('/changePaymethodIninvoice') }}/" + $('#invoice_number').val() + "/" + selectCustomer,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                console.log("success");
                                if (data) {


                                    console.log('AJAX load  cahnge PayMent work');

                                } else {
                                    alert("{{ __('home.sorryerror')}}")
                                }
                            },
                        });
                    }





                });

                //endAddproduct

                //delete


                //updatealldata


                $("#updateproductalldata").click(function(e) {
                    event.preventDefault();
                    $('#increaseProduct').modal('hide');

                    var url = $(this).attr('data-action');
                    let table = document.getElementById("example");


                    var token_search = $("#token_search").val();
                    console.log(token_search);

                    var url = " {{ URL::to('updateproductallDataInvoices') }}";
                    token_search = $('#token_search').val();
                    id = $('#id_update').val();
                    quentity = $('#quantity_update').val();
                    avt = $('#avt_update').val();
                    discount = $('#product_price_after_dis_update').val();
                    price = $('#product_price_update').val();
                    console.log('-=-=--bcvvcvvc=-=--=')
                    console.log(id)
                    console.log(quentity)
                    console.log(avt)
                    console.log(discount)
                    console.log(price)
                    $.ajax({
                        url: url,
                        type: 'post',
                        cache: false,

                        data: {
                            _token: token_search,
                            id: id,
                            quentity: quentity,
                            avt: avt,
                            discount: discount,
                            price: price
                        },


                        success: function(data) {
                            console.log('seccusss12111');
                            if (data.length ==0) {
                                alert("{{ __('home.stocknotAvailable') }}")

                            } else {
                                console.log(data)
                                var tableHeaderRowCount = 1;
                                added_value_total = 0;
                                total_sales = 0;
                                var rowCount = table.rows.length;
                                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                    table.deleteRow(tableHeaderRowCount);
                                }

                                data['product'].forEach(async (product) => {


                                    sales_id = product['id'],
                                        count1 = product['count'],
                                        product_code = product['Product_Code']
                                    product_name = product['product_name']
                                    quentity = product['quantity']
                                    price = product['Unit_Price']
                                    discount = product['Discount_Value']
                                    addedvalue = product['Added_Value']
                                    total = product['Unit_Price'] * product[
                                            'quantity'] + product['Added_Value'] *
                                        product[
                                            'quantity']
                                    added_value_total = added_value_total + (product[
                                        'Added_Value'] * product['quantity'])
                                    total_sales = total_sales + (price * product[
                                        'quantity'])
                                    console.log(product_name);
                                    text1 =
                                        '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                    result = text1.concat("onclick=", "decreaseProduct(", sales_id, ",", "1",
                                        ")>",
                                        '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px;" class="las la-minus"></i>',
                                        "</button> ")
                                    product_name_update = product_name.replaceAll(" ", "?")
                                    text2 =
                                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                    result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                        "  ", "data-return_quentity=", quentity, '  ',
                                        '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                    )

                                    update =
                                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                                    update = update.concat(sales_id, "  ", "data-section_name=", product_name_update, "  ", "data-section_price=", price, "  ", "data-section_discount=", discount,
                                        "  ", "data-return_quentity=", quentity, '  ',
                                        '  data-toggle="modal"   href="#increaseProduct"   title="حذف"><i class="las la-align-justify"></i></a>'
                                    )
                                    text3 =
                                        '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                    result3 = text3.concat("onclick=", "increaseProduct(", sales_id, ",", "1",
                                        ")>",
                                        '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px" class="las la-plus"></i>',
                                        "</button> ")

                                    if (quentity > 0) {


                                        let table = document.getElementById("example");
                                        let row = table.insertRow(-1); // We are adding at the end

                                        let c1 = row.insertCell(0);
                                        let c2 = row.insertCell(1);
                                        let c3 = row.insertCell(2);
                                        let c4 = row.insertCell(3);
                                        let c5 = row.insertCell(4);
                                        let c6 = row.insertCell(5);
                                        let c7 = row.insertCell(6);
                                        let c8 = row.insertCell(7);
                                        let c9 = row.insertCell(8);

                                        // Add data to c1 and c2

                                        c1.innerText = count1
                                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                        c3.innerText = product_name
                                        c4.innerText = price
                                        c5.innerText = quentity
                                        c6.innerText = price * quentity
                                        c7.innerText = discount
                                        c8.innerText = (price * quentity) - discount
                                        c9.innerHTML = result + ' ' + result3 + ' ' + ' ' + update + result2





                                    }


                                });


                                //    update3/3/2023


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

                                c1.innerText = (Math.round(data['invoicetotal_price'] * 100) / 100).toFixed(2);
                                c2.innerText = (Math.round(data['invoicetotal_addedvalue'] * 100) / 100).toFixed(2);
                                c3.innerText = (Math.round(data['invoicetotal_discount'] * 100) / 100).toFixed(2);
                                c4.innerText = (Math.round((data['invoicetotal_addedvalue'] + data['invoicetotal_price']) * 100) / 100).toFixed(2);




                                // var rowCount = table.rows.length;

                                // for (var i = 0; i < rowCount; i++) {
                                //     var data = table.rows[i].innerText.innerText;
                                //     console.log('end');

                                // }

                            }


                        },
                        error: function(response) {
                            alert("{{ __('home.sorryerror') }}")

                        }
                    });

                });








                //endupdatealldata







                //update
                $("#added_product").click(function(e) {
                    event.preventDefault();
                    $('#modaldemo9').modal('hide');
                    $('#exampleModal2').modal('hide');

                    var url = $(this).attr('data-action');
                    let table = document.getElementById("example");


                    var token_search = $("#token_search").val();
                    console.log(token_search);

                    var url = " {{ URL::to('EditInvoices') }}";
                    token_search = $('#token_search').val();
                    id = $('#id').val();
                    return_quentity = $('#return_quentity').val();
                    original_quantity = $('#original_quantity').val();
                    if (original_quantity >= return_quentity) {
                        $.ajax({
                            url: url,
                            type: 'post',
                            cache: false,

                            data: {
                                _token: token_search,
                                id: $('#id').val(),
                                return_quentity: $('#return_quentity').val(),
                            },


                            success: function(data) {
                                console.log('seccusss12111');

                                console.log(data)
                                var tableHeaderRowCount = 1;
                                added_value_total = 0;
                                total_sales = 0;
                                var rowCount = table.rows.length;
                                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                    table.deleteRow(tableHeaderRowCount);
                                }

                                data['product'].forEach(async (product) => {


                                    sales_id = product['id'],
                                        count1 = product['count'],
                                        product_code = product['Product_Code']
                                    product_name = product['product_name']
                                    quentity = product['quantity']
                                    price = product['Unit_Price']
                                    discount = product['Discount_Value']
                                    addedvalue = product['Added_Value']
                                    total = product['Unit_Price'] * product[
                                            'quantity'] + product['Added_Value'] *
                                        product[
                                            'quantity']
                                    added_value_total = added_value_total + (product[
                                        'Added_Value'] * product['quantity'])
                                    total_sales = total_sales + (price * product[
                                        'quantity'])
                                    console.log(product_name);
                                    text1 =
                                        '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                    result = text1.concat("onclick=", "decreaseProduct(", sales_id, ",", "1",
                                        ")>",
                                        '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px;" class="las la-minus"></i>',
                                        "</button> ")
                                    product_name_update = product_name.replaceAll(" ", "?")
                                    text2 =
                                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                    result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                        "  ", "data-return_quentity=", quentity, '  ',
                                        '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                    )

                                    update =
                                        ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                                    update = update.concat(sales_id, "  ", "data-section_name=", product_name_update, "  ", "data-section_price=", price, "  ", "data-section_discount=", discount,
                                        "  ", "data-return_quentity=", quentity, '  ',
                                        '  data-toggle="modal"   href="#increaseProduct"   title="حذف"><i class="las la-align-justify"></i></a>'
                                    )
                                    text3 =
                                        '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                    result3 = text3.concat("onclick=", "increaseProduct(", sales_id, ",", "1",
                                        ")>",
                                        '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px" class="las la-plus"></i>',
                                        "</button> ")


                                    if (quentity > 0) {


                                        let table = document.getElementById("example");
                                        let row = table.insertRow(-1); // We are adding at the end

                                        let c1 = row.insertCell(0);
                                        let c2 = row.insertCell(1);
                                        let c3 = row.insertCell(2);
                                        let c4 = row.insertCell(3);
                                        let c5 = row.insertCell(4);
                                        let c6 = row.insertCell(5);
                                        let c7 = row.insertCell(6);
                                        let c8 = row.insertCell(7);
                                        let c9 = row.insertCell(8);

                                        // Add data to c1 and c2

                                        c1.innerText = count1
                                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                        c3.innerText = product_name
                                        c4.innerText = price
                                        c5.innerText = quentity
                                        c6.innerText = price * quentity
                                        c7.innerText = discount
                                        c8.innerText = (price * quentity) - discount
                                        c9.innerHTML = result + ' ' + result3 + ' ' + ' ' + update + result2




                                    }


                                });


                                //    update3/3/2023


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

                                c1.innerText = (Math.round(data['invoicetotal_price'] * 100) / 100).toFixed(2);
                                c2.innerText = (Math.round(data['invoicetotal_addedvalue'] * 100) / 100).toFixed(2);
                                c3.innerText = (Math.round(data['invoicetotal_discount'] * 100) / 100).toFixed(2);
                                c4.innerText = (Math.round((data['invoicetotal_addedvalue'] + data['invoicetotal_price']) * 100) / 100).toFixed(2);




                                // var rowCount = table.rows.length;

                                // for (var i = 0; i < rowCount; i++) {
                                //     var data = table.rows[i].innerText.innerText;
                                //     console.log('end');

                                // }




                            },
                            error: function(response) {
                                alert("{{ __('home.sorryerror') }}")

                            }
                        });
                    } else {
                        alert("{{ __('home.returnquantitymorethensale') }}")

                    }
                });




                //end update


                $("#deleteproduct").click(function(e) {
                    event.preventDefault();
                    $('#modaldemo9').modal('hide');
                    var url = $(this).attr('data-action');
                    let table = document.getElementById("example");
                    var token_search = $("#token_search").val();
                    console.log(token_search);
                    var url = " {{ URL::to('EditInvoices') }}";
                    token_search = $('#token_search').val();
                    id = $('#id_delete').val();
                    return_quentity = $('#return_quentity_delete').val();
                    console.log('+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
                    console.log(id);
                    console.log(return_quentity);
                    $.ajax({
                        url: url,
                        type: 'post',
                        cache: false,
                        data: {
                            _token: token_search,
                            id: $('#id_delete').val(),
                            return_quentity: $('#return_quentity_delete').val(),
                        },
                        success: function(data) {
                            console.log('seccusss12111');
                            console.log(data)
                            var tableHeaderRowCount = 1;
                            added_value_total = 0;
                            total_sales = 0;
                            var rowCount = table.rows.length;
                            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                table.deleteRow(tableHeaderRowCount);
                            }
                            i = 0;

                            data['product'].forEach(async (product) => {

                                sales_id = product['id'],
                                    count1 = product['count'],
                                    product_code = product['Product_Code']
                                product_name = product['product_name']
                                quentity = product['quantity']
                                price = product['Unit_Price']
                                discount = product['Discount_Value']
                                addedvalue = product['Added_Value']
                                total = product['Unit_Price'] * product['quantity'] +
                                    product['Added_Value'] * product['quantity']

                                console.log(product_name);
                                text1 =
                                    '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                result = text1.concat("onclick=", "decreaseProduct(", sales_id, ",", "1",
                                    ")>",
                                    '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px;" class="las la-minus"></i>',
                                    "</button> ")
                                product_name_update = product_name.replaceAll(" ", "?")
                                text2 =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-danger mb-1" data-effect="effect-scale" data-id='
                                result2 = text2.concat(sales_id, "  ", "data-section_name=", product_name_update,
                                    "  ", "data-return_quentity=", quentity, '  ',
                                    '  data-toggle="modal"   href="#modaldemo9"   title="حذف"><i class="las la-trash"></i></a>'
                                )

                                update =
                                    ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                                update = update.concat(sales_id, "  ", "data-section_name=", product_name_update, "  ", "data-section_price=", price, "  ", "data-section_discount=", discount,
                                    "  ", "data-return_quentity=", quentity, '  ',
                                    '  data-toggle="modal"   href="#increaseProduct"   title="حذف"><i class="las la-align-justify"></i></a>'
                                )
                                text3 =
                                    '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success mb-1" data-dismiss="modal"'
                                result3 = text3.concat("onclick=", "increaseProduct(", sales_id, ",", "1",
                                    ")>",
                                    '<i style="display:block; margin: 0 auto;left:30%;top:50%;transform:translate(-30%,-50%);margin-left:20px;position:relative;left:7px" class="las la-plus"></i>',
                                    "</button> ")


                                if (quentity > 0) {


                                    let table = document.getElementById("example");
                                    let row = table.insertRow(-1); // We are adding at the end

                                    let c1 = row.insertCell(0);
                                    let c2 = row.insertCell(1);
                                    let c3 = row.insertCell(2);
                                    let c4 = row.insertCell(3);
                                    let c5 = row.insertCell(4);
                                    let c6 = row.insertCell(5);
                                    let c7 = row.insertCell(6);
                                    let c8 = row.insertCell(7);
                                    let c9 = row.insertCell(8);

                                    // Add data to c1 and c2

                                    c1.innerText = count1
                                    c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                    c3.innerText = product_name
                                    c4.innerText = price
                                    c5.innerText = quentity
                                    c6.innerText = price * quentity
                                    c7.innerText = discount
                                    c8.innerText = (price * quentity) - discount
                                    c9.innerHTML = result + ' ' + result3 + ' ' + ' ' + update + result2


                                }
                            });
                            //    update3/3/2023
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

                            c1.innerText = (Math.round(data['invoicetotal_price'] * 100) / 100).toFixed(2);
                            c2.innerText = (Math.round(data['invoicetotal_addedvalue'] * 100) / 100).toFixed(2);
                            c3.innerText = (Math.round(data['invoicetotal_discount'] * 100) / 100).toFixed(2);
                            c4.innerText = (Math.round((data['invoicetotal_addedvalue'] + data['invoicetotal_price']) * 100) / 100).toFixed(2);






                            // var rowCount = table.rows.length;
                            // for (var i = 0; i < rowCount; i++) {
                            //     var data = table.rows[i].innerText.innerText;
                            //     console.log('end');
                            // }
                        },
                        error: function(response) {
                            alert("{{ __('home.sorryerror') }}")
                        }
                    });
                });




                const tbodyEl = document.querySelector("tbody");
                const tableEl = document.querySelector("table");

                function onDeleteRow(e) {
                    if (!e.target.classList.contains("deleteBtn")) {
                        return;
                    }

                    const btn = e.target;
                    console.log('start')

                    console.log(btn.closest("tr").data)
                    console.log('end')
                    alert('delete')
                    btn.closest("tr").remove();
                }

                tableEl.addEventListener("click", onDeleteRow);


                //end added



            });
        </script>


        @endsection