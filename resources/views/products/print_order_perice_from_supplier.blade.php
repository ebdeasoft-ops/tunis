@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
        border-style: solid;

    }
</style>
@endsection
@section('title')
معاينه طباعة المنتجات
@stop
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    {{-- <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">الفواتير</h4>
                </div>
            </div>

        </div> --}}
    <!-- breadcrumb -->
    @endsection
    @section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice pt-5">
                    <div class="card-body">
                    <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%" dir=rtl>




<div class="billed-from" style="width:33%;text-align: center;">
    <br>

    <span  class="tx-18 thick">{{Namear}}</span>
    <br>
    <p class="tx-16 thick"> {{describtionar}}</p>
    <p class="tx-16 thick">{{STar}}</p>
    <p class="tx-16 thick">{{Taxar}}</p>

</div><!-- billed-from -->
<div class="row">
    <?php
    $logo = camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>

</div>

<div class="billed-from" style="width:33%;text-align: center;">
    <br>
    <span class="tx-18 thick">{{Nameen}}</span>
    <br>
    <p class="tx-16 thick" > {{describtionen}} </p>
    <span class="tx-16 thick">{{STen}} </span>
    <p class="tx-16 thick"> {{Taxen}} </p>

</div>

</div><!-- invoice-header -->
<br>
<br>
            <center> <p  >  {{__('home.Requestـpricesـofـproducts')}}</p></center>

            <br>
            <br>
            
            
                                <div class="table-responsive mg-t-30 table-padding">
                                    <table class="table text-center table-invoice border text-md-nowrap mb-0 table-bordered table-striped" id="tableTotalPrice"
                                        name="tableTotalPrice"width="50%">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0"><span>{{__('home.Invoice_no')}}  </span></th>
                                                <th class="border-bottom-0"><span>{{$id}}</span></th>
                                            </tr>
                                        </thead>
        
                                        <body>
                                      <?php
                                    $order_price_from_supplier= App\Models\order_price_from_supplier::find($id);
                                      
                                      ?>

                                            <tr>
                                                <td><span> {{ __('home.date') }}</span></td>
                                                <td><span><?php echo date("Y-m-d h:i") ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><span>{{ __('home.suppliername') }}   </span></td>
                                                <td><span>{{$order_price_from_supplier->supllier->name}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span>{{ __('supprocesses.Location') }}</span></td>
                                                <td><span>{{$order_price_from_supplier->supllier->location}}</span></td>
                                            </tr>

                                            <tr>
                                                <td><span>{{ __('supprocesses.phone') }}  </span></td>
                                                <td><span>{{$order_price_from_supplier->supllier->phone}}</span></td>
                                            </tr>
                                          
        
                                        </body>
        
                                    </table>

                                </div> 
                                
                                

                        @if (isset($itemsRequest))
                        <?php $i = 0; ?>
                        <div class="col-xl-12">
                            <div class="mg-b-20 mb-0 pb-0">

                                <div class="card-body pb-0">
                                                       <div class="table-responsive mg-t-40">
                            <table class="table table-bordered table-striped text-center table-invoice border text-md-nowrap mb-0">
                                  <thead>
                                                <tr>
                                                    <th class="border-bottom-0"># </th>
                                                    <th class="border-bottom-0" >{{ __('home.productNo') }} </th>
                                                    <th class="border-bottom-0" style="width: 60%;">{{ __('home.product') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.saleprice') }}</th>



                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0; ?>
                                                @foreach ($itemsRequest as $product)
                                                <?php $i++; ?>
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td dir=ltr>{{ $product->productData->Product_Code }}</td>
                                                    <td>{{ $product->productData->product_name }}</td>
                                                    <td>{{ $product->quantity }}</td>
                                                    <td>..........</td>

                                                <tr>
                                                    @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <br>
<p>{{__('home.signtyre_purchase_manger')}}</p>
                        <p>{{__('home.thesignature')}} : </p>
                        <br>
                        <br>
                                </div>
                            </div>
                            <br>
                     
                        </div>


                        </form>

                        @endif

                      



                        <button class="btn btn-danger float-left mt-3 mr-2 print-style" id="print_Button" onclick="printDiv()">
                            {{__('home.print')}}
                            <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>


<script type="text/javascript">
    function printDiv() {
        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>

@endsection