@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
 <style>
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
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

 
  </style>
<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('home.salesـreturned') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->



    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.salesـreturned') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
   <center>
    <div id="loading-screen">
    <div id="loading-animation"></div>
    &nbsp;  <p>  جارٍ إرسال الفاتورة، يرجى الانتظار  <br>Invoice  is being sent, please wait</p>
  </div>
  </center>
    @if (session()->has('foundinvoice'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>
        <strong> {{ session()->get('foundinvoice') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>
        <strong> {{ session()->get('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if (session()->has('createnewproduct'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <br>
        <strong> {{ session()->get('createnewproduct') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session()->has('notfountreturnproduct'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ session()->get('notfountreturnproduct') }}</strong>
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'return_sale')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="col-lg-5 mg-t-20 mg-lg-t-0">


                            <label for="inputName" class="control-label parent-label">{{ __('home.enterinvoicenumber') }}</label>
                            <input type="text" class="form-control parent-input" id="invoice_no" name="invoice_no" title="  يرجي ادخال رقم الفاتورة  " onkeyup="convertToNumberPriceSale()" required>

                        </div>





                        <div>

                            <br>

                            <div class="d-flex justify-content-center">
                                <button style="background-color: #419BB2" type="submit" class="btn btn-success p-1">
                                    {{ __('home.search') }}
                                    <i style=" height: 100;
                                                 
                                                 font-size:15px" class="las la-search"></i>
                                </button>
                            </div>
                        </div>

                        <br>

                    </form>
                </div>

            </div>
        </div>



        <br>



        @if (isset($data['product']))
        <?php $i = 0; ?>
        <div class="col-xl-12">
            <div style="border-radius: 10px" class="card mg-b-20 p-3">
                <div class="card-header pb-0 p-5">
                    <div class="d-flex justify-content-between">
       <div class="col-lg-2 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label" style="color:red;  font-weight: bold;font-size:22px"> {{ __('home.paymentmethod') }} </p>
                                <select style="color:green;  font-weight: bold;font-size:16px" class="form-control parent-input " name="pay_return_sale" id="pay_return_sale">


                                    @if($data['payment']=="Cash")
                                    <option selected style="color:green;  font-weight: bold;font-size:14px" value="Cash"> {{ __('report.cash') }}</option>
                                    @else
                                    <option style="color:green;  font-weight: bold;font-size:14px" value="Cash"> {{ __('report.cash') }}</option>
                                    @endif

                                    @if($data['payment']=="Shabka"||$data['payment']=="Bank_transfer")
                                    <option selected style="color:green;  font-weight: bold;font-size:14px" value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                                    @else
                                    <option style="color:green;  font-weight: bold;font-size:14px" value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                                    @endif
                                    @if($data['payment']=="Credit")
                                    <option selected style="color:green;  font-weight: bold;font-size:14px" value="Credit"> {{ __('report.credit') }} </option>
                                    @else
                                    <option  style="color:green;  font-weight: bold;font-size:14px" value="Credit"> {{ __('report.credit') }} </option>
                                    @endif


                                </select>

                            </div>                     
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-invoice border text-md-nowrap mb-0 text-center" name="example" id="example" width="100%">
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
                                <th class="border-bottom-0"># </th>
                                <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                <th class="border-bottom-0">{{ __('home.product') }}</th>
                                <th class="tx-center"> {{ __('home.productprice') }} </th>

                                <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                                <th class="border-bottom-0">{{ __('home.price') }}</th>
                                <th class="border-bottom-0">{{ __('home.discount') }}</th>

                                <th class="border-bottom-0">{{ __('home.total') }} </th>
                                <th class="border-bottom-0">{{ __('home.operations') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            $invoiceId = 0;
                            ?>
                            @foreach ($data['product'] as $product)
                            <?php $i++;
                            $invoiceId = $product->invoice_id; ?>
                            @if ($product->quantity > 0)
                            <tr>
                                <td>{{ $i }}</td>
                                <td dir=ltr>{{ $product->productData->Product_Code }}</td>
                                <td>{{ $product->productData->product_name }}</td>
                                <td class="tx-center">{{ $product->Unit_Price }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->Unit_Price * $product->quantity }}</td>
                                <td>{{ $product->Discount_Value }}</td>
                                <td>{{ $product->Unit_Price * $product->quantity - $product->Discount_Value }}
                                </td>

                                <td>
                                    <a style="background-color: #419BB2;" class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" data-id="{{ $product->id }}" data-section_name="{{ $product->productData->product_name }}" data-description="{{ $product->quantity }}" data-ordernumber="{{ $product->invoice_id }}" data-toggle="modal" href="#exampleModal2" title="تعديل"><i class="las la-pen"></i></a>


                                </td>
                                @endif
                            <tr>
                                @endforeach
                        </tbody>
                    </table>
                    <input class="form-control " name="recentretrn" id="recentretrn" hidden value=0 hidden>

                    <div style="padding: 0 20% 0 20%" class="table-responsive mg-t-20  float-left mt-3 mr-2">
                        <table class="table table-invoice border text-md-nowrap mb-0 text-center" name="tableTotalPrice" id="tableTotalPrice" width="100%">

                            <body>
                                <tr>
                                    <td class="tx-">{{__('home.the amount')}}</td>
                                    <td class="tx-">{{ __('home.discount') }}</td>
                                    <td class="tx-">{{ __('home.avt') }}</td>
                                    <td class="tx-">{{ __('home.total') }}</td>

                                </tr>
                                <tr>
                                    <td class="tx-">{{( $data['invoicetotal_price']+$data['invoicetotal_discount'] )?? 0 }}</td>

                                    <td class="tx-">{{ $data['invoicetotal_discount'] ?? 0 }}</td>
                                    <td class="tx-">{{ round( $data['invoicetotal_addedvalue'] ?? 0,2) }}</td>
                                    <td class="tx-">
                                        {{ round($data['invoicetotal_price'] + $data['invoicetotal_addedvalue'],2 )}}
                                    </td>
                                </tr>

                            </body>

                        </table>

                        <br>
                        <br>
                        <div class="d-flex justify-content-center">

                            <a style="background-color: #419BB2;height:30px" class="btn btn-success p-1" href="{{ url('/' . ($page = 'printreturnInvoice') . '/' . $data['invoice_id']) }}">
                                {{__('home.print')}}
                                <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                </svg>
                            </a>
                            
&nbsp;
  <a style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px" href="{{ url('/' . ($page = 'generate_return_sale_pdf') . '/' . $data['invoice_id']) }}"
                    class="btn btn-success p-1 px-2 fw-bolder"  id="generate_pdf" target="_blank" >{{ __('home.dwonloadpdf') }}&nbsp;<i class="fa-solid fa-download"></i></i></a>
&nbsp;
<input hidden id="invoice_id" value="{{$data['invoice_id']}}">
   <button  id="reciptprinter" class="btn btn-success p-1 px-2 fw-bolder">
                                        {{ __('home.reciptprinter') }}
                                        <svg style="width: 15px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                        </svg>
                                    </button>

                        </div>

                        <br>
                        </form>

                        <br>
                    </div>






                    <div class="row d-flex justify-content-end p-0 mb-2">

                        <button style="background-color:#FF4F1F" id="showmodal" href="#paymentmethod" class="btn btn-danger p-1">
                            {{ __('home.returninvoiceItem') }} </button>
                            
                            &nbsp;                            
&nbsp;                            
                                   
                                    <button style="background-color: grey;" class="modal-effect btn btn-sm btn-info" id="sendzatca" >
                                        {{ __('home.uploadzatca') }}&nbsp;<i class="fa-regular fa-paper-plane"></i>
                                    </button>

                    </div>









                </div>

                <br />


            </div>




            <div class="row">







                @endif

                </table>

            </div>
        </div>


    </div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>

   
        
 <div class="modal fade product-selection" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" id="loading" name="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
            <div class="modal-dialog modal-xl" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" role="document">
                <div class="modal-content">
                  
                    <div class="modal-body" style="justify-content: center;">


 <center><img style="width:250px;height:250px;" class="custom_img" src="{{ asset('assets/admin/uploads/loading.png') }}" >
                        
</center>



                          
                        </div>

                     
                    </div>


                </div>
            </div>

        </div>
        
        

<!-- edit -->
@if (isset($data['product']))

<div class="modal" id="paymentmethod">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.alert') }} </h6>
            </div>
            <form action="{{ '/' . ($page = 'returnAll') }}" method="POST" role="search" autocomplete="off">
                {{ csrf_field() }}


                <div class="modal-body">

                    <input type="number" class="form-control " name="invoice_no_delete_All" id="invoice_no_delete_All" title=" رقم الفاتورة " value="{{ $invoiceId }}" required=true hidden>
                    <input class="form-control " name="pagename" id="pagename" readonly value="products.salesreturned" hidden>





                    <div class="col">
                        <span style="color:red">{{__('home.Are_you_sure')}}</span>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    <button id="returnAll" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('home.RETURNSPURCHASEpart')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'update_return_sale')) }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                    <div class="form-group">

                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="currentquantity" id="currentquantity">
                        <input type="hidden" name="ordernumber" id="ordernumber" value="">
                        <label for="recipient-name" class="col-form-label"> {{__('home.product')}} </label>

                        <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label"> {{__('home.numberofpicereturens')}} :</label>
                        <input class="form-control" type="number" id="return_quentity" name="return_quentity" required>
                    </div>
            </div>
            </form>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal" name="updateproductalldata" id="updateproductalldata">{{ __('home.confirm') }}</button>
                <button class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
            </div>
        </div>
    </div>
</div>




<!-- main-content closed -->
</div>
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
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
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>
<script>

$("#sendzatca").click(function(e) {
                  document.getElementById('loading-screen').style.display = 'block'; // show loading screen

        var url = " {{ URL::to('sent_to_zatca_return_items') }}"+'/'+ $('#invoice_id').val();
        console.log(url)
        document.getElementById('sendzatca').hidden = true

        token_search = $('#token_search').val();
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            dataType: "html",
           


            success: function(data) {
               console.log(data)
                if(data==1){
                          document.getElementById('loading-screen').style.display = 'none'; // Hide loading screen

                var audio = new Audio('/sounds/done.mp3');
                audio.play();
                   $('#SearchProduct').modal().show();
 setTimeout(() => {
         $('#SearchProduct').modal('hide');

        }, 1000);
                document.getElementById('dwonloadxml').hidden = false
                document.getElementById('sendzatca').hidden = true
                }
                else{
                    alert(data)
                    document.getElementById('sendzatca').hidden = false

                }
            },
              error: function(response) {
                console.log(response)

}
        });
    })


        $("#reciptprinter").click(function(e) {
        var url = " {{ URL::to('returnsalesprinter') }}"+'/'+$('#invoice_id').val();
        console.log(url);
        var token_search = $("#token_search").val();
        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            dataType: 'html',
           
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

    function convertToNumberPriceSale() {
        var input = document.getElementById("invoice_no");
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
        var ordernumber = button.data('ordernumber')
        var section_name = button.data('section_name')
        var description = button.data('description')
        var modal = $(this)
        console.log('-----=====-----')
        console.log(id)
        console.log(ordernumber)
        console.log(description)
        console.log(section_name)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #ordernumber').val(ordernumber);
        modal.find('.modal-body #product_name').val(section_name);
        modal.find('.modal-body #return_quentity').val(description);
        modal.find('.modal-body #currentquantity').val(description);
    })
</script>

<script>
    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var ordernumber = button.data('ordernumber')

        var description = button.data('description')

        var section_name = button.data('section_name')
        var modal = $(this)

        modal.find('.modal-body #ordernumber').val(ordernumber);
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #description').val(description);
        modal.find('.modal-body #product_name').val(section_name);
    })
</script>


<script>
    $("#showmodal").click(function(e) {
        if($('#recentretrn').val()==0){
        $('#paymentmethod').modal('show');

       }


    });
    $(document).ready(function() {


      document.getElementById('loading-screen').style.display = 'none'; // Hide loading screen

        $("#returnAll").click(function(e) {
            document.getElementById('returnAll').style.visibility = 'hidden';
            event.preventDefault();
            let table = document.getElementById("example");
            $('#loading').modal().show();
            var token_search = $("#token_search").val();
            var url = " {{ URL::to('returnAll') }}";
            token_search = $('#token_search').val();
            console.log($('#pagename').val())
            console.log($('#invoice_no_delete_All').val())
            var pay_return_sale = $("#pay_return_sale").val();

            if($('#recentretrn').val()==0){
                $('#recentretrn').val(1);

            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    pagename: $('#pagename').val(),
                    invoice_no_delete_All: $('#invoice_no_delete_All').val(),
                    pay_return_sale:pay_return_sale

                },


                success: function(data) {
                    // const map =(JSON.parse(response));
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




                    c1.innerText = 0
                    c2.innerText = 0
                    c3.innerText = 0
                    c4.innerText = 0

                    console.log('++++++')
                    console.log(data)
                    var tableHeaderRowCount = 1;

                    var rowCount = table.rows.length;

                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                        table.deleteRow(tableHeaderRowCount);
                    }
                    i = 0;

setTimeout(() => {
         $('#loading').modal('hide');

        }, 500); 
                    alert(data['message'])


                },
                error: function(response) {
                    console.log(response)
                    alert("{{ __('home.sorryerror') }}")
                    $('#recentretrn').val(0);

                }
            })
        }
        })











        $("#updateproductalldata").click(function(e) {
            document.getElementById('updateproductalldata').style.visibility = 'hidden';
            $('#loading').modal().show();
            event.preventDefault();
            let table = document.getElementById("example");
            var token_search = $("#token_search").val();
            var pay_return_sale = $("#pay_return_sale").val();
            var url = " {{ URL::to('update_return_sale') }}";
            token_search = $('#token_search').val();
       
            if ($('#currentquantity').val()*1 >= $('#return_quentity').val()*1) {
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        id: $('#id').val(),
                        return_quentity: $('#return_quentity').val(),
                        ordernumber: $('#ordernumber').val(),
                        product_name: $('#product_name').val(),
                        pay_return_sale:pay_return_sale
                    },


                    success: function(data) {
                        // const map =(JSON.parse(response));
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
                        added_value_total = data['invoicetotal_addedvalue'];
                        total_reaming = data['invoicetotal_price'];
                        totaldiscount = data['invoicetotal_discount']
                        total = data['total']

                        c1.innerText =Number(total_reaming)+Number(totaldiscount)
                        c2.innerText = totaldiscount
                        c3.innerText = added_value_total
                        c4.innerText = total

                        console.log('++++++')
                        console.log(data)
                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        i = 0;
                        data['product'].forEach(async (product) => {
                            i++;


                            text4 =
                                ' <a  style="width:40px;height:20px;background-color: #419BB2;" class="modal-effect btn btn-sm btn-warning" class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id='
                            result4 = text4.concat(product['id'], "  ",
                                "data-section_name=", product['product_name'], "  ",
                                "data-description=", product['quantity'], "  ",
                                "data-ordernumber=", data['invoice_id'], '  ',
                                'data-toggle="modal"   href="#exampleModal2"   title="حذف"><i class="las la-align-justify"></i></a>'
                            )



                            if (product['quantity'] > 0) {

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
                                count1 = product['count'],
                                    product_code = product['Product_Code']
                                product_name = product['product_name']
                                quentity = product['quantity']
                                Unit_Price = product['Unit_Price']
                                totalsale = product['Unit_Price'] * product['quantity']
                                discount = product['Discount_Value']
                                totalsaleafterdiscount = (product['Unit_Price'] * product['quantity']) - product['Discount_Value']

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = Unit_Price
                                c5.innerText = quentity
                                c6.innerText = totalsale
                                c7.innerText = discount
                                c8.innerText = totalsaleafterdiscount
                                c9.innerHTML = result4


                            }


                        });


                        //    update3/3/2023










setTimeout(() => {
         $('#loading').modal('hide');

        }, 500); 
            




                        alert('تم عملية الاسترجاع بنجاح \n   The recovery process was completed successfully')


                    },
                    error: function(response) {
                        console.log(response)

                        alert("{{ __('home.sorryerror') }}")

                    }
                })
            } else {
                alert("{{ __('home.returnquantitymorethensale') }}")
            }
            
                        document.getElementById('updateproductalldata').style.visibility =  'visible';

        })
    })
















    $(function() {
        var timeout = 20000; // in miliseconds (3*1000)
        $('.alert').delay(timeout).fadeOut(500);
    });
</script>






@endsection