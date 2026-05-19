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
                    <br>
                    <br><!-- invoice-header -->
                    <?php
                    $Invoices = $data['invoices']
                    ?>


                    @if (isset($Invoices))
                    <br>
                    <br>
                    <br>
                    <div class="row d-flex justify-content-center">


                        <div class="col-lg-3" id="start_at">
                            <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                            <?php
                            $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                            ?>
                            <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                        </div>

                    </div>
                    <br>
                    <?php
                    $userId = 0;
                    $count = 0;
                    ?>
                    <?php
                    $userId = 0;
                    $startat = '';
                    $endat = '';
                    $totalPricefinal = 0;

                    $totalpriceall = 0;
                    $totaladdedvalue = 0;
                    $totaldiscount = 0;
                    $totalpricefinal = 0;
                    ?>


                    <br>




                    <br>

                    </span>

                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered" id="example1" data-page-length='50' style=" text-align: center;">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>

                                    <th class="border-bottom-0">{{ __('report.date') }}</th>

                                    <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                    <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                    <th class="border-bottom-0"> {{ __('home.quantity') }}</th>

                                    <th class="border-bottom-0">{{ __('home.price') }}</th>
                                    <th class="border-bottom-0">{{ __('home.discount') }}</th>
                                    <th class="border-bottom-0">{{ __('home.priceafterDiscount') }}</th>

                                    <th class="border-bottom-0"> {{ __('home.addedValue') }}</th>
                                    <th class="border-bottom-0"> {{ __('home.total') }}</th>
                                </tr>
                            </thead>
                            <?php

                            ?>
                            <tbody>




                                @foreach ($Invoices as $invoice)
                                @if($invoice->Price!=0)

                                <?php
                                $totaldiscount += $invoice->discount;

                                $i = 0;

                                $totalpriceall += $invoice->Price;
                                if ($count == 0) {
                                    $userId = $invoice->user_id;
                                    $startat = $invoice->created_at;
                                }
                                $endat = $invoice->created_at;
                                $count++;
                                $avt = App\Models\Avt::find(1);
                                $saleavt = $avt->AVT;
                                $totalwithoudTax = 0;

                                ?>




                                @foreach (App\Models\sales::where('invoice_id', $invoice->id)->get() as $product)
                                <?php
                                $i++;
                                $date = explode(' ', $product->created_at);
                                $totalwithoudTax += ($product->Unit_Price * ($product->quantity + $product->quantityreturn)) - $product->Discount_Value;
                                ?>
                                <tr class="">
                                    <td>{{ $i }}</td>

                                    <td>{{ $date[0] }}</td>

                                    <td dir='ltr'>{{ $product->productData->Product_Code }}</td>
                                    <td>{{ $product->productData->product_name }}</td>
                                    <td>{{ $product->quantity+$product->quantityreturn }}</td>


                                    <td>{{ $product->Unit_Price }}</td>
                                    <td>{{ $product->Discount_Value }}</td>
                                    <td>{{ ($product->Unit_Price*($product->quantity+$product->quantityreturn)) -$product->Discount_Value }}</td>
                                    <td>{{ (( (($product->quantity+$product->quantityreturn)* $product->Unit_Price )-$product->Discount_Value)*$saleavt) }}</td>
                                    <td>{{ ( (($product->quantity+$product->quantityreturn) * $product->Unit_Price )-$product->Discount_Value) +(( (($product->quantity+$product->quantityreturn) * $product->Unit_Price )-$product->Discount_Value)*$saleavt)}}
                                    </td>



                                </tr>

                                <tr>


                                    @endforeach
                                    <?php
                                    $totalwithoudTax -= $invoice->discount;


                                    ?>
                                <tr style="background-color: #419BB2;">

                                    <td> {{ __('report.invoiceNo') }}</td>
                                    <td> {{ $invoice->id }} </td>
                                    <td> {{ __('users.branch') }} : {{ $invoice->branch->name }}</td>
                                    <td> {{ __('home.clietName') }} : {{ $invoice->customer->name }} </td>
                                    <td>

                                        {{ __('home.paymentmethod') }} :
                                        @if ($invoice->Pay == 'Cash')
                                        <span style="color:white !important" class="text-success">{{ __('report.cash') }}</span>
                                        @elseif($invoice->Pay == 'Credit')
                                        <span class="text-danger">{{ __('report.credit') }}</span>
                                        @elseif($invoice->Pay == 'Bank_transfer')
                                        <span class="text-danger">{{ __('home.Bank_transfer') }}</span>
                                        @else
                                        <span class="text-warning">{{ __('report.shabka') }}</span>
                                        @endif
                                    </td>
                                    <td>{{__('home.the amount')}} : {{$totalwithoudTax }}</td>
                                    <td> {{__('home.addedValue')}} : {{round($totalwithoudTax*$saleavt,2) }} </td>
                                    <td> {{__('home.discount')}} : {{$invoice->discount }} </td>

                                    <td> {{ __('home.total') }}</td>
                                    <td>{{ round($totalwithoudTax*$saleavt+$totalwithoudTax ,2)}}</td>

                                </tr>
                                @endif
                                <?php
                                $totalPricefinal += round(($invoice->cashamount + $invoice->bankamount + $invoice->Bank_transfer+$invoice->creaditamount), 2);
                                $totalwithoudTax = 0;
                                ?>
                                @endforeach

                            </tbody>

                        </table>

                        <table class="table table-bordered table-hover text-center table-striped mt-5">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th>{{ __('report.totalallprice') }}</th>
                                    <th>{{ $totalPricefinal  }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                        <br>



                        <br>



                        @endif
                      
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