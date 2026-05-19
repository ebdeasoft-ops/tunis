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
                        <div class="row mg-t-12">
                            <br>
                            <br>

                        </div>
                        @if (isset($Invoices))
                        <div style="border-radius: 10px" class="card pb-0 px-3 mt-3 mb-3">
                        <div class="col-lg-3" id="start_at">
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                                    <?php
                                    $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                    ?>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                                </div>
                                <?php
                                $userId = 0;
                                $count = 0;
                                ?>
                                <?php
                                $userId = 0;
                                $startat = '';
                                $endat = '';
                                $totalprice = 0;
                                $avt=App\Models\Avt::find(1);
                                $saleavt=$avt->AVT;
                                $totaladdedvalue = 0;
                                $totaldiscount=0;
                                ?>
                                @foreach ($Invoices as $invoice)
                                    <?php
                                    $totaladdedvalue += ( $invoice->Price- $invoice->discount)*$saleavt;
                                    $totalprice +=( $invoice->Price- $invoice->discount);
                                    $totaldiscount+=$invoice->discount;
                                    if ($count == 0) {
                                        $userId = $invoice->user_id;
                                        $startat = $invoice->created_at;
                                    }
                                    $endat = $invoice->created_at;
                                    $count++;
                                  
                                    ?>

                                    <br>





                                    <table class="table table-striped table-bordered">

                                        <thead>
                                            <tr>
                                            <th>
                                                {{ __('report.invoiceNo') }} 
                                                </th>
                                                <th>
                                                    {{ $invoice->id }}
                                                </th>  <th>
                                                {{ __('home.totaldiscount') }} 
                                                </th>
                                                <th>
                                                    {{$invoice->discount}}
                                                </th> 
                                                 <th>
                                                {{ __('report.totalpricewithoudtax') }} 
                                                </th>
                                                <th>
                                                    {{( $invoice->Price- $invoice->discount) }}
                                                </th>
                                                  <th>
                                                  {{ __('report.totaltax') }}                                                </th>
                                                <th>
                                                    {{($invoice->Price- $invoice->discount)*$saleavt}}
                                                </th>
                                                  <th>
                                                {{ __('home.total') }} 
                                                </th>
                                                <th>
                                                    {{(($invoice->Price- $invoice->discount))+(($invoice->Price- $invoice->discount)*$saleavt) }}
                                                </th>
                                            </tr>
                                            
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
                                        $i = 0;
                                        ?>
                                        @foreach (App\Models\sales::where('invoice_id', $invoice->id)->get() as $product)
                                            <?php
                                            $i++;
                                            $date = explode(' ', $product->created_at);
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $date[0] }}</td>

                                                    <td dir='ltr'>{{ $product->productData->Product_Code }}</td>
                                                    <td>{{ $product->productData->product_name }}</td>
                                                    <td>{{ $product->quantity }}</td>
                                                  

                                                <td>{{ ($product->Unit_Price*$product->quantity) }}</td>
                                                    <td>{{ $product->Discount_Value }}</td>
                                                    <td>{{ ($product->Unit_Price*$product->quantity)-$product->Discount_Value }}</td>
                                                    <td>{{ ((($product->Unit_Price*$product->quantity)-$product->Discount_Value)*$saleavt) }}</td>
                                                    <td>{{ (($product->Unit_Price*$product->quantity)-$product->Discount_Value) + ((($product->Unit_Price*$product->quantity)-$product->Discount_Value)*$saleavt) }}

                                                </tr>

                                            </tbody>
                                        @endforeach
                                    </table>

                                    {{-- <span class="text-warning  float-left mt-3 mr-2"
                                        id="print_Button">{{ __('home.total') }} :
                                        {{ $invoice->Added_Value + $invoice->Price }}</span> --}}

                                @endforeach
                            

                                    <div class="table-padding">
                                        <table class="table table-bordered table-hover text-center table-striped mt-5">
                                            <thead>
                                              <tr>
                                                <th scope="col"></th>
                                                <th scope="col">{{ __('report.totalprice') }}</th>
                                                <th>{{ __('home.the amount') }}</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>{{ __('home.totaldiscount') }}</td>
                                                <td>{{ $totaldiscount }}</td>
                                              </tr>
                                              <tr>
                                                <th scope="row">1</th>
                                                <td>{{ __('report.totalpricewithoudtax') }}</td>
                                                <td>{{ $totalprice }}</td>
                                              </tr>
                                            
                                              <tr>
                                                <th scope="row">2</th>
                                                <td>{{ __('report.totaltax') }}</td>
                                                <td> {{round( $totaladdedvalue,2) }}</td>
                                              </tr>
                                              <tr>
                                                <th scope="row">3</th>
                                                <td>{{ __('report.totalallprice') }}</td>
                                                <td>{{round( $totaladdedvalue + $totalprice,2) }}</td>
                                              </tr>
                                            </tbody>
                                          </table>
                                    </div>


                            @endif

                    </div>



                
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
