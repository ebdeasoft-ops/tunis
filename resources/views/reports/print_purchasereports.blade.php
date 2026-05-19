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
                        <div class="row mg-t-12">
                        <br>
                        <br>
                        <span>{{__('home.purchases')}}</span>
                        <br>
                           
                        </div>
                           
                        </div>
                      

                        @if (isset($products))
                    <div style="border-radius: 10px" class="card m-3 p-3">
                            <div class="table-responsive hoverable-table">
                                <table class=" table text-md-nowrap table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{ __('report.invoiceNo') }}</th>
                                            <th class="border-bottom-0">{{ __('report.date') }}</th>

                                            <th class="border-bottom-0">{{ __('home.suppliername') }}</th>

                                            <th class="border-bottom-0" dir='ltr'> {{ __('home.productNo') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                            <th class="border-bottom-0">{{ __('home.purchase') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.addedValue') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.total') }}</th>



                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;
                                        $totaladdedvalue = 0;
                                        $totalprice = 0;
                                        $startat = '';
                                        $endat = ''; ?>
                                        @foreach ($products as $invoice)
                                            <?php $i++;
                                            $totaladdedvalue += $invoice->Added_Value * $invoice->quantity;
                                            $totalprice += $invoice->Unit_Price * $invoice->quantity;
                                            if ($i == 1) {
                                                $startat = $invoice->created_at;
                                            }
                                            $endat = $invoice->created_at;
                                            ?>
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $invoice->order_owner }} </td>
                                                <td>{{ $invoice->created_at }}</td>

                                                <?php
                                                $supplierName = App\Models\orderTosupllier::find($invoice->order_owner);
                                                ?>
                                                <td>{{ $supplierName->supllier->name }} </td>
                                                <td dir=ltr>{{ $invoice->productData->Product_Code }} </td>
                                                <td>{{ $invoice->productData->product_name }}</td>
                                                <td>{{ $invoice->numberofpice }}</td>
                                                <td>{{ $invoice->purchasingـprice }}</td>
                                                <td>{{ $invoice->Added_Value }}</td>
                                                <td>{{ $invoice->purchasingـprice * $invoice->numberofpice + $invoice->Added_Value * $invoice->numberofpice }}
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                
                        @endif
                </div>
                <hr class="mg-b-40">



            
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
