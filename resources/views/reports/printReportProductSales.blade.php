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
{{__('home.print')}}
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


                    <div class="card-body">
                        @if (isset($products))
                        <br>
                        <br>
                        <br>
                        <div class="col-lg-3" id="start_at">
                            <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                            <?php
                            $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                            ?>
                            <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                        </div>
                        <br>
                        <br>
                        <div style="border-radius: 10px" class="card p-3">
                            <div class="table-responsive hoverable-table">
                                <table class="table table-hover table-striped" id="example1" data-page-length='20' style=" text-align: center;">

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
                                        $totaldiscount = 0;
                                        $avt = App\Models\Avt::find(1);
                                        $saleavt = $avt->AVT;
                                        $startat = '';
                                        $endat = ''; ?>
                                        @foreach ($products as $invoice)
                                        <?php $i++;
                                        $productId = $invoice->productData->id;
                                        $totaladdedvalue += (($invoice->Unit_Price * $invoice->quantity) - $invoice->Discount_Value) * $saleavt;
                                        $totalprice += ($invoice->Unit_Price * $invoice->quantity) - $invoice->Discount_Value;
                                        $totaldiscount += $invoice->Discount_Value;
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
                                            <td>{{round( ((($invoice->Unit_Price*$invoice->quantity)-$invoice->Discount_Value)*$saleavt),2) }}</td>
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

                        <br>
                        @endif
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