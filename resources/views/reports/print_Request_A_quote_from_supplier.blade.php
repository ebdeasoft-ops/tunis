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
<div class="row ">
    <div class="col">

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
                    <span>{{ __('report.Requestـaـquoteـfromـtheـsupplier') }}</span>
                    <br>
                    <!-- invoice-header -->



                    @if (isset($Invoices))
                    <?php
                    $userId = 0;
                    $count = 0;
                    ?>
                    <?php
                    $userId = 0;
                    $startat = '';
                    $endat = '';
                    $totalprice = 0;
                    $totaladdedvalue = 0;
                    ?>
                    @foreach ($Invoices as $invoice)
                    <?php

                    $totalEachInvoce = 0;

                    if ($count == 0) {
                        $userId = $invoice->user_id;
                        $startat = $invoice->created_at;
                    }
                    $endat = $invoice->created_at;
                    $count++;

                    ?>


                    {{--

                                <span class="text-danger">{{ __('report.invoiceNo') }} : {{ $invoice->id }}</span>
                    <br>
                    <span class="text-danger">{{ __('home.suppliername') }} : {{ $invoice->supllier->name }}

                    </span>
                    <br>
                    <span class="text-danger"> --}}

                        <div>
                            <hr style="border-top: 4px solid rgba(0,0,0,.1)">
                        </div>

                        <div class="table-padding pt-5">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>{{ __('report.invoiceNo') }}</th>
                                        <th>{{ $invoice->id }}</th>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.suppliername') }}</th>
                                        <th>{{ $invoice->supllier->name }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>



                        <br>
                        <span class="text-danger">

                            <div class="table-responsive hoverable-table table-striped px-1">
                                <table class="table table-sm table-hover table-bordered">

                                    <thead>

                                        <tr>
                                            <th>{{__('home.Invoice_no')}}</th>
                                            <th>{{ $invoice->id }}</th>
                                        </tr>

                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{ __('report.date') }}</th>

                                            <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                        </tr>

                                    </thead>
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach (App\Models\order_price_from_supplier_items::where('order_id', $invoice->id)->get() as $product)
                                    <?php
                                    $i++;
                                    $totaladdedvalue += $product->Added_Value * $product->numberofpice;
                                    $totalprice += $product->purchasingـprice * $product->numberofpice;
                                    $totalEachInvoce += ($product->purchasingـprice + $product->Added_Value) * $product->numberofpice;

                                    $date = explode(' ', $product->created_at);
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $date[0] }}</td>

                                            <td dir=ltr>{{ $product->productData->Product_Code }}</td>
                                            <td>{{ $product->productData->product_name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                        </tr>

                                    </tbody>
                                    @endforeach

                                </table>


                                <div class="my-4">
                                    <hr style="border-top: 4px solid rgba(0,0,0,.1)">
                                </div>
                                @endforeach



                                @endif


                            </div>
                            <hr class="mg-b-50">



                         
                            <br>


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