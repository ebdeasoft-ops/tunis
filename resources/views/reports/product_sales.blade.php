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
    {{ __('report.product_sales') }}@stop
@endsection
@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading ">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('report.product_sales') }}

                    </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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

            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'product_sales')) }}"
                        method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">


                       

                            <div class="col-lg-3" id="start_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}"
                                        name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-4" id="end_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" name="end_at"
                                        value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->

                            </div>
                            
                            
                            <div class="col-lg-5 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control parent-input" name="branch" required>
                                    <option value="-" selected>{{ __('users.allbranchs') }}
                                    </option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                        <option value="{{ $branch->id }}"> {{ $branch->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                           
                            

                            <input class="form-control select2 " name="productNo" id="productNo" value='-' hidden>

                        </div>
                        
                        <div class="row my-3">

                            <div class="col-lg-3 mg-t-20 mg-lg-t-0 p-0" style=" height: 80px; width: 210px;">
                                <p class="mg-b-10 parent-label"> . </p>
                                <div class="col-lg-1 mg-t-10 mg-lg-t-0">
                                    <a style="background-color: #FBA10F;width:170px" class="modal-effect btn btn-md btn-info p-0 py-1 button-eng" data-effect="effect-scale"
                                        data-toggle="modal" href="#SearchProduct" title="تحديد">{{ __('home.chooose product') }}<i
                                            style=" height: 100;
                                                 width: 80px;"
                                            class="las la-search"> </i>
                                        </a>
                                </div>
                                
                            </div>
                            <div class="col-lg-3">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.productNo') }} </label>
                                <input dir=ltr type="text" class="form-control parent-input" id="product_code" name="product_code"
                                 readonly    required>

                            </div>
                            <div class="col-lg-6">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.productname') }} </label>
                                <input type="text" class="form-control parent-input" id="productnameshow" name="productnameshow"
                                 readonly   required>

                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center mb-3 mt-3">
                            <button class="btn btn-success mb-3 print-style my-3 p-1">
                                {{ __('home.search') }}
                                <i
                                        style=" height: 100;
                                                 
                                                 font-size:15px"
                                        class="las la-search"></i>
                            </button>
                        </div>


                    </form>

                    @if (isset($products))

                            <div style="border-radius: 10px" class="card p-3">
                                <div class="table-responsive hoverable-table">
                                    <table class="table table-hover table-striped" id="example1" data-page-length='20'
                                        style=" text-align: center;">
    
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">#</th>
                                                <th class="border-bottom-0">{{ __('report.invoiceNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                                <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                                <th class="border-bottom-0">{{ __('home.price') }}</th>
                                                <th class="border-bottom-0">{{ __('home.discount') }}</th>
                                                <th class="border-bottom-0">{{ __('home.priceafterDiscount') }}</th>
                                                
                                                <th class="border-bottom-0"> {{ __('home.addedValue') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.total') }}</th>
    
    
    
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            $totaladdedvalue = 0;
                                            $totalprice = 0;
                                            $productId = 0;
                                            $totaldiscount=0;
                                            $avt=App\Models\Avt::find(1);
                                            $saleavt=$avt->AVT;
                                            $startat = '';
                                            $endat = ''; ?>
                                            @foreach ($products as $invoice)
                                                <?php $i++;
                                                $productId = $invoice->productData->id;
                                                $totaladdedvalue += (( $invoice->Unit_Price*$invoice->quantity)-$invoice->Discount_Value)*$saleavt;
                                                $totalprice +=( $invoice->Unit_Price*$invoice->quantity)-$invoice->Discount_Value ;
                                                $totaldiscount+=$invoice->Discount_Value;
                                                if ($i == 1) {
                                                    $startat = $invoice->created_at;
                                                }
                                                $endat = $invoice->created_at;
 

                                                ?>
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $invoice->invoice_id }} </td>
                                                    <td dir='ltr'>{{ $invoice->productData->Product_Code }} </td>
                                                    <td>{{ $invoice->productData->product_name }}</td>
                                                    <td>{{ $invoice->created_at }}</td>
                                                    <td>{{ $invoice->quantity }}</td>
                                                    <td>{{ ($invoice->Unit_Price*$invoice->quantity) }}</td>
                                                    <td>{{ $invoice->Discount_Value }}</td>
                                                    <td>{{ ($invoice->Unit_Price*$invoice->quantity)-$invoice->Discount_Value }}</td>
                                                    <td>{{ round(((($invoice->Unit_Price*$invoice->quantity)-$invoice->Discount_Value)*$saleavt),2) }}</td>
                                                    <td>{{ round((($invoice->Unit_Price*$invoice->quantity)-$invoice->Discount_Value) + ((($invoice->Unit_Price*$invoice->quantity)-$invoice->Discount_Value)*$saleavt),2) }}
                                                    </td>
    
                                                </tr>
                                            @endforeach
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="table-padding">
                                <table class="table table-bordered table-hover text-center table-striped mt-5">
                                    <thead>
                                      <tr>
                                        <th scope="col">{{ __('home.the amount') }}</th>
                                        <th scope="col">{{ $totalprice }}</th>
                                      </tr>
                                      <tr>
                                        <th scope="col">{{ __('home.discount') }}</th>
                                        <th scope="col">{{ $totaldiscount }}</th>
                                      </tr>

                                      <tr>
                                        <th>
                                            {{ __('home.addedValue') }}
                                        </th>
                                        <th>{{ round($totaladdedvalue ,2)}}</th>
                                      </tr>
                                      <tr>
                                        <th>{{ __('home.total') }}</th>
                                        <th>{{ round($totaladdedvalue + $totalprice,2) }}</th>
                                      </tr>
                                    </thead>
                                    
                                  </table>
                            </div>

                            @if (count($products) > 0)
                            <div class="d-flex justify-content-center">

                                <a style="background-color: #419BB2;font-size:17px" class="btn btn-success p-1"
                                    href="{{ url('/' . ($page = 'printReportProductSales') . '/' . $branch_Id . '/' . $productId . '/' . $startat . '/' . $endat) }}">
                                    {{ __('home.print') }}
                                    <svg style="width: 20px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                        <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                    </svg>
                                </a>
                            </div>
                            <br>
                            @endif
                    @endif

                </div>
            </div>
        
    </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>



    {{-- Update ( 24/4/2023 ) --}}

<div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
<div style="margin: 0 9% !important;" class="modal-dialog modal-xl modal-special" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">


            <div class="card-body">

                <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                    <label for="inputName" style="font-weight: bold" class="control-label parent-label">
                        {{ __('home.searchaboutproduct') }} </label>
                    <input type="text" class="form-control parent-input"
                        placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct"
                        name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                </div>
                <br>
                <div class="table-responsive">
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
                                <th style="font-size: 15px" class="border-bottom-0">#</th>
                                <th style="font-size: 15px" class="border-bottom-0">{{ __('home.productNo') }}
                                </th>
                                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                    {{ __('home.product') }}</th>
                                <th style="font-size: 15px" class="border-bottom-0">{{ __('home.quantity') }}
                                </th>
                                <th style="font-size: 13px" class="border-bottom-0">
                                    {{ __('home.purchaseproductwithouttax') }}</th>
                                <th style="font-size: 13px" class="border-bottom-0">
                                    {{ __('home.sellingproduct without tax') }}</th>
                                <th style="font-size: 15px" class="border-bottom-0">{{ __('home.Add') }}</th>



                            </tr>
                        </thead>
                        <tbody class="">
                            <?php $i = 0;
                            $products2=[];
                            $data = 'm';    
                                 $products2 =App\Models\products::where('branchs_id',Auth()->User()->branchs_id)->paginate(20) ;
                                 ?>
?>
                            @foreach ($products2 as $product)
                                <?php $i++; ?>

                                <tr id="<?php echo $product['id']; ?>">
                                    <td id="tableData"  dir=ltr>{{ $product->id }}</td>
                                    <td id="tableData"  dir=ltr>{{ $product->Product_Code }}</td>
                                    <td id="tableData"  data-target="product_name">
                                        {{ $product->product_name }}</td>
                                    <td id="tableData"  data-target="numberofpice">
                                        {{ $product->numberofpice }}</td>
                                    <td id="tableData"  data-target="numberofpice">
                                        {{ $product->purchasingـprice }}</td>
                                    <td id="tableData" data-target="numberofpice">
                                        {{ $product->sale_price }}</td>
                                    <td id="tableData" >
                                    
                                            <button style="padding: 6px 12px" type="button" id="btn"
                                                name="btn" class="btn btn-success" data-dismiss="modal"
                                                onclick="chooseProduct('{{ $product->id }}','{{ $product->product_name }}','{{ $product->purchasingـprice }}','{{ $product->sale_price }}','{{ $product->Product_Code }}','{{ $product->numberofpice }}')">{{ __('home.Add') }}</button>
                                        

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>

                    </div>

                    <div class="row d-flex justify-content-between pagination-row">

                        <div class="rows-number">
                            <label
                                style="font-size: 12px;color:#419BB2;font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;{{ __('pagination.numberofpages') }}
                                &nbsp;&nbsp; {{ $products2->perPage() }} &nbsp;&nbsp;</label>
                            <label
                                style="font-size: 12px;color:#419BB2;font-weight:bold">{{ __('pagination.numberofproducts') }}
                                &nbsp;&nbsp;{{ $products2->total() }} </label>
                        </div>

                        <div class="buttonss-table row d-flex justify-content-center mb-2">
                            <button id="previousPage" name="previousPage" class="btn btn-primary print-style"><span> << &nbsp;</span>{{__('pagination.previous')}}</button>
                            <div class="col-lg-2 col-md-2 col-sm-2 mg-lg-t-0 prodNumbers"> <input class="form-control " name="currentpage" id="currentpage" readonly value="{{$products2->currentPage()}}" readonly>
                            </div>
                            <input class="form-control " name="previousPagevalue" id="previousPagevalue" readonly value="{{$products2->previousPageUrl()}}" hidden>
                            <input class="form-control " name="nextPageValue" id="nextPageValue" readonly value="{{$products2->nextPageUrl()}}" hidden>
                            <button id="nextPage" name="nextPage" class="btn btn-primary print-style">{{__('pagination.next')}} >></button>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button> --}}
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>

        </div>


    </div>
</div>

</div>


{{-- End Update ( 24/4/2023 ) --}}



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


    {{-- Update ( 24/4/2023 ) --}}

<script>
    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        $.ajax({
            url: " {{ URL::to('searchaboutproduct') }}" + "/" + searchtext,
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




             
                        text =
                            '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                        name = product_name.replaceAll(" ", "<");
                        Product_Code_1= Product_Code.replaceAll(" ", "<");

                        button = text.concat("chooseProduct(", id, ",", "'", name, "'", ",",
                            purchasingـprice, ",", sale_price, ",", "'", Product_Code_1,
                            "'", ",", numberofpice, ")", ">{{ __('home.Add') }}</button>");


                    


                    let row = table.insertRow(-1); // We are adding at the end

                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);

                    c1.innerText = Product_id

                    c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                    c3.innerHTML = product_name
                    c4.innerText = numberofpice
                    c5.innerText = purchasingـprice
                    c6.innerText = sale_price

                    c7.innerHTML = button

                });

            },
        });
    }
</script>

{{-- End Update ( 24/4/2023 ) --}}



{{-- Update ( 24/4/2023 ) --}}

<script>
    function chooseProduct(code, name, price, sale_price, location, availablequantity) {
        $('#SearchProduct').modal().hide();
        $('#searchaboutproduct').val('');
        var Product_Code = code
        name = name.replaceAll("<", " ");
        location= location.replaceAll("<", " ");



            console.log('------')
            console.log(code)
            console.log(name)
            console.log(price)

            var Product_Code = code
            var product_name = name
            var product_sale_pice = price

            $('#productNo').val(code);
            $('#productnameshow').val(product_name);
            $('#product_code').val(location);

    }
</script>

{{--  End Update ( 24/4/2023 ) --}}






    {{-- <script>
        function chooseProduct(code, name, price) {
            $('#SearchProduct').modal().hide();


            console.log('------')
            console.log(code)
            console.log(name)
            console.log(price)

            var Product_Code = code
            var product_name = name
            var product_sale_pice = price

            $('#productNo').val(code);
            $('#productnameshow').val(product_name);


        }
    </script> --}}
    <script>
        $(document).ready(function() {


         

            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
    
      




    $("#nextPage").click(function(e) {
        url = $('#nextPageValue').val().split('page=')[1];
        $.ajax({
            url: " {{ URL::to('goToSaleBypage') }}" + "?page=" + url,
            type: "GET",
            dataType: "json",
            success: function(data) {


                $('#previousPagevalue').val(data['prev_page_url'])
                $('#nextPageValue').val(data['next_page_url'])
                $('#currentpage').val(data['current_page'])
                let table = document.getElementById("SearchProductTable");
                var tableHeaderRowCount = 1;

                // table.classList.add('table-bordered');
                // table.classList.add('table-striped');

           

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




               
                        text =
                            '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';
                        name = product_name.replaceAll(" ", "<");
                        Product_Code_1= Product_Code.replaceAll(" ", "<");
                        button = text.concat("chooseProduct(", id, ",", "'", name, "'",
                            ",", purchasingـprice, ",", sale_price, ",", "'",
                            Product_Code_1, "'", ",", numberofpice, ")",
                            ">{{ __('home.Add') }}</button>");

                    


                    let row = table.insertRow(-1); // We are adding at the end

                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);

                    c1.innerText = Product_id

                    c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                    c3.innerHTML = product_name
                    c4.innerText = numberofpice
                    c5.innerText = purchasingـprice
                    c6.innerText = sale_price

                    c7.innerHTML = button







                });

            },
        });
    });

    $("#previousPage").click(function(e) {

        url = $('#previousPagevalue').val().split('page=')[1];

        if (url != '') {
            $.ajax({
                url: " {{ URL::to('goToSaleBypage') }}" + "?page=" + url,
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




                  
                            text =
                                '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                            name = product_name.replaceAll(" ", "<");
                            Product_Code_1= Product_Code.replaceAll(" ", "<");

                            button = text.concat("chooseProduct(", id, ",", "'", name,
                                "'", ",", purchasingـprice, ",", sale_price, ",",
                                "'", Product_Code_1, "'", ",", numberofpice, ")",
                                ">{{ __('home.Add') }}</button>");

                        


                        let row = table.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);

                        c1.innerText = Product_id

                        c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
                        c3.innerHTML = product_name
                        c4.innerText = numberofpice
                        c5.innerText = purchasingـprice
                        c6.innerText = sale_price

                        c7.innerHTML = button








                    });

                },
            });
        } else {
            alert('url null not fount pervoius')
        }
    });


    // End Update ( 24/4/2023 )

        });
    </script>


@endsection
