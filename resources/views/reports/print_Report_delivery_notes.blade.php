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
                            <br>


                            @if (isset($Invoices))
                            <div class="card-body">
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



                                    {{-- <span class="text-danger">{{ __('report.invoiceNo') }} :
                                        {{ $invoice->orderId }}</span>
                                    <br>
                                    <span class="text-danger">{{ __('home.suppliername') }} :
                                        {{ $invoice->supllier->name }}</span>
                                    <br>
                                    <span class="text-danger">{{ __('home.notesClient') }} : {{ $invoice->notes }}</span>
                                    <br>
                                    <span class="text-danger">{{ __('users.branch') }} :
                                        {{ $invoice->branch->name }}</span> --}}



                                        <div class="table-padding my-5">
                                            <table class="table table-striped table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('report.invoiceNo') }}</th>
                                                        <th>{{ $invoice->orderId }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('home.suppliername') }}</th>
                                                        <th>{{ $invoice->supllier->name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('home.notesClient') }}</th>
                                                        <th>{{ $invoice->notes }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('users.branch') }}</th>
                                                        <th>{{ $invoice->branch->name }}</th>
                                                    </tr>    
                                                </thead>    
                                            </table>    
                                        </div>    




                                    <table class="table  table-striped table-bordered text-center">

                                        <thead>
                                          
                                            <tr>
                                                <th class="border-bottom-0">#</th>
                                                <th class="border-bottom-0">{{ __('report.date') }}</th>

                                                <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.saleprice') }}</th>
                                            </tr>

                                        </thead>
                                        <?php
                                        $i = 0;
                                        $profit = 0;
                                        ?>
                                        @foreach (App\Models\orderDetails::where('order_owner', $invoice->orderId)->where('numberofpice', '!=', 0)->get() as $product)
                                            <?php
                                            $i++;
                                            $totalprofit += $product->quantity * $product->Unit_Price - $product->quantity * $product->productData->purchasingـprice;
                                            $profit += $product->quantity * $product->Unit_Price - $product->quantity * $product->productData->purchasingـprice;
                                            $date = explode(' ', $product->created_at);
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $date[0] }}</td>

                                                    <td dir=ltr>{{ $product->productData->Product_Code }}</td>
                                                    <td>{{ $product->productData->product_name }}</td>
                                                    <td>{{ $product->numberofpice }}</td>
                                                    <td>{{ $product->productData->purchasingـprice }}</td>
                                                </tr>

                                            </tbody>
                                        @endforeach
                                    </table>

                                    <div>
                                        <hr style="border-top: 4px solid rgba(0,0,0,.1)">
                                    </div>


                                    <br>
                                @endforeach
                                        <br>


                                    @endif

                                </div>
                                <hr class="mg-b-40">



                                <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                                    {{ __('home.print') }}
                                    <i class="mdi mdi-printer ml-1"></i>
                                </button>
                            
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
