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
                        <br>
                        
                        
                        @if (isset($Invoices))
                        <div style="border-radius: 10px" class="card pb-0 px-3">
                            <br>
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

                                    <br>



                                    {{-- <span class="text-danger">{{ __('report.invoiceNo') }} : {{ $invoice->id }}</span> --}}
                                    <br>

                                    {{-- <span class="text-danger">{{ __('home.suppliername') }} :
                                        {{ $invoice->supllier->name }} --}}
                                        <br>
                                        @if (App\Models\branchs::count() > 1)
                                            {{-- <span class="text-danger">{{ __('users.branch') }} :
                                                {{ $invoice->user->branch->name }} --}}
                                        @endif
                                    </span>

                                    <div class="table-padding">
                                        <table class="table table-bordered table-striped text-center my-3">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('home.suppliername') }}</th>
                                                    <th>{{ $invoice->supllier->name }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('users.branch') }}</th>
                                                    <th>{{ $invoice->user->branch->name }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>


                                    <br>
                                    <span class="text-danger">

                                        <div class="table hoverable-table">
                                                <table class="table text-center table-responsive table-striped table-bordered">
                                                    <br>

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
                                                            <th class="border-bottom-0">{{ __('home.purchase') }}</th>
                                                            <th class="border-bottom-0"> {{ __('home.addedValue') }}</th>
                                                            <th class="border-bottom-0"> {{ __('home.total') }}</th>
                                                        </tr>

                                                    </thead>
                                                    <?php
                                                    $i = 0;
                                                    ?>
                                                    @foreach (App\Models\orderDetails::where('order_owner', $invoice->id)->get() as $product)
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

                                                                <td dir='ltr'>
                                                                    {{ $product->productData->Product_Code }}</td>
                                                                <td>{{ $product->productData->product_name }}</td>
                                                                <td>{{ $product->numberofpice }}</td>
                                                                <td>{{ $product->purchasingـprice }}</td>
                                                                <td>{{ $product->Added_Value }}</td>
                                                                <td>{{ ($product->purchasingـprice + $product->Added_Value) * $product->numberofpice }}
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    @endforeach
                                                </table>

                                                <div class="table-padding mt-5">
                                                    <hr style="border-top: 4px solid rgba(0,0,0,.1)">
                                                </div>

                                @endforeach


                                    <div class="table-padding">
                                        <table class="table table-bordered table-hover text-center table-striped mt-5">
                                            <thead>
                                              <tr>
                                                  <th scope="col">{{ __('report.totalprice') }}
                                                <th scope="col">{{ __('home.the amount') }}</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('report.totalpricewithoudtax') }}</td>
                                                    <td>{{ $totalprice }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('report.totaltax') }}</td>
                                                    <td>{{ $totaladdedvalue }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('report.totalallprice') }}</td>
                                                    <td>{{ $totaladdedvalue + $totalprice }}</td>
                                                </tr>
                                            </tbody>
                                          </table>
                                    </div>




                            @endif

                           
                        </div>

                        <br>

                    </div>
                        <hr class="mg-b-50">



                  
                        <br>


                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- row closed -->
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
