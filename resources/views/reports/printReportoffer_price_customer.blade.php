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
{{ __('home.print') }}
@stop
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class=" main-content-body-invoice" id="print">
            <div class="card card-invoice">
                   <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

                <div class="card-body">
                  <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">

<div class="billed-from" style="width:33%;text-align: center;" >
    <br>
     <span style="font-size:25px">{{Nameen}}</span>
    <br>
    <p dir=ltr> {{describtionen}} </p>
    <span dir=ltr>{{STen}} </span>
    <p dir=ltr> {{Taxen}} </p>

</div>
<div class="row">
<?php
$logo=camplogo;
?>
<a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

</div>


<div class="billed-from" style="width:33%;text-align: center;">
    <br>

   <span style="font-size:25px">{{Namear}}</span>
    <br>
    <p> {{describtionar}}</p>
    <p>{{STar}}</p>
    <p>{{Taxar}}</p>

</div><!-- billed-from -->
</div><!-- invoice-header -->
                    <div class=" mg-t-12">

                        <br>
                        <br>
                        <div class="col-lg-3" id="start_at">
                            <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                            <?php
                            $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                            ?>
                            <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                        </div>
                        @if (isset($Invoices))
                        <div class="card-body">

                            <div style="border-radius: 10px" class="card pb-0 px-3">
                                <div class="table-responsive">
                                    <br>

                                    <?php
                                    $count = 0;
                                    $startat = '';
                                    $endat = '';
                                    $totalprofit = 0;
                                    ?>
                                    @foreach ($Invoices as $invoice)
                                    <?php
                                    if ($count == 0) {
                                        $startat = $invoice->created_at;
                                    }
                                    $endat = $invoice->created_at;
                                    $count++;
                                    ?>

                                    <br>



                                    {{-- <span class="text-danger">{{ __('report.orderNo') }} : {{ $invoice->id }}</span>
                                    <br>
                                    <span class="text-danger">{{ __('users.branch') }} :
                                        {{ $invoice->branch->name }}</span>
                                    <br>
                                    <span class="text-danger">{{ __('home.clietName') }} :
                                        {{ $invoice->customer->name }}</span> --}}


                                    <div class="table-padding">
                                        <table class="table table-striped table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('report.orderNo') }}</th>
                                                    <th>{{ $invoice->id }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('users.branch') }}</th>
                                                    <th>{{ $invoice->branch->name }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('home.clietName') }}</th>
                                                    <th>{{ $invoice->customer->name }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>


                                    <table class="table table-striped table-bordered text-center table-responsive">


                                        <thead>

                                         

                                            <tr>
                                                <th class="border-bottom-0">#</th>
                                                <th class="border-bottom-0">{{ __('report.date') }}</th>

                                                <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.price') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.addedValueperpice') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.discount') }}</th>

                                                <th class="border-bottom-0"> {{ __('home.total') }}</th>


                                            </tr>

                                        </thead>
                                        <?php
                                        $avt = App\Models\Avt::find(1);
                                        $saleavt = $avt->AVT;
                                        $i = 0;
                                        $profit = 0;
                                        ?>
                                        @foreach (App\Models\offer_price_to_customer_items::where('order_id', $invoice->id)->where('quantity', '!=', 0)->get() as $product)
                                        <?php
                                        $i++;
                                        $profit += (($product->quantity* $product->PriceWithoudTax)-$product->discount)+ ((($product->quantity* $product->PriceWithoudTax)-$product->discount)*$saleavt);
                                        $date = explode(' ', $product->created_at);
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $date[0] }}</td>

                                                <td dir='ltr'>{{ $product->productData->Product_Code }}</td>
                                                <td>{{ $product->productData->product_name }}</td>
                                                <td>{{ $product->PriceWithoudTax  }}</td>
                                                <td>{{$product->quantity}}</td>
                                                <td>{{( $saleavt* $product->PriceWithoudTax)}}</td>
                                                <td>{{$product->discount}}</td>

                                                <td>{{( ($product->quantity* $product->PriceWithoudTax)-$product->discount)+ ((($product->quantity* $product->PriceWithoudTax)-$product->discount)*$saleavt)}}</td>

                                            </tr>

                                        </tbody>
                                        @endforeach
                                    </table>


                                    <span class="text-warning  float-left mt-3 mr-2" id="print_Button">{{ __('home.total') }} : {{ $profit }}</span>
                                  <br>  @endforeach


                                    <div>
                                        <hr style="border-top: 4px solid rgba(0,0,0,.3)">
                                    </div>



                                    --------------------------------------------


                                    <br>

                                    <br>

                                    <br>



                                </div>
                                <br>


                                @endif

                            </div>
                       
                        </div>
                    </div>
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