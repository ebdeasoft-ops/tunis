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
{{ __('home.voucher') }}
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

        </h5>
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
                    
                    
                   
                                      
     <center>      
       <h6 style="color: black" class="invoice-title">{{ __('home.voucher') }}</h6>

     </center>                   

                            <div class="table-padding table-responsive ">
                                
                                 <?php
                                $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                ?>
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $start_at }}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ $end_at }}</label>
                                            </th>


                  
                                           
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} </label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>
                                            </th>
                                         
                                        </tr>


                                    </thead>
                                </table>
                            </div>
                            <br>

                        @if (isset($Invoices))
                 
                        </div>
                        <br>
                        <div style="border-radius: 10px" class="card m-3 p-3">
                            <div class="table-responsive">
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
                                $totalprice += $invoice->recive_amount;
                                if ($count == 0) {
                                    $userId = $invoice->user_id;
                                    $startat = $invoice->created_at;
                                }
                                $endat = $invoice->created_at;
                                $count++;

                                ?>
                                {{--
                                    <br>



                                    <span class="text-danger">{{ __('report.invoiceNo') }} : {{ $invoice->id }} </span>
                                <br>
                                <span class="text-danger">{{ __('report.reciver_name') }} : {{ $invoice->user->name }}
                                </span>


                                <br>
                                <span class="text-danger">{{ __('home.paymentmethod') }} :

                                    @if ($invoice->pay_method == 'Cash')
                                    <span class="text-success">{{ __('report.cash') }}</span>
                                    @elseif ($invoice->pay_method == 'Bank_transfer')
                                    <span class="text-success">{{ __('home.Bank_transfer') }}</span>
                                    @else
                                    <span class="text-warning">{{ __('report.shabka') }}</span>
                                    @endif
                                    <br>

                                </span> --}}

                                <table class="table table-striped table-bordered text-center">

                                    <thead>
                                        <tr>
                                            <th style="color:#419BB2">{{ __('report.invoiceNo') }}</th>
                                            <th style="color:#419BB2">{{ $invoice->sent_abd_count }}</th>
                                            <th style="color:#419BB2">{{ __('report.reciver_name') }}</th>
                                            <th style="color:#419BB2">{{ $invoice->user->name }}</th>
                                            <th style="color:#419BB2">{{ __('home.paymentmethod') }}</th>
                                                  <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> -</label>
                                            </th>
                                        </tr>
                                        <tr>
                                                                                        <th style="color:#419BB2">{{ __('home.date') }}</th>

                                            <th class="border-bottom-0"> {{ __('home.exportTime') }}</th>
                                            <th class="border-bottom-0"> {{ __('home.acount_name') }}</th>
                                            <th class="border-bottom-0">{{ __('accountes.cashreceived') }}</th>
                                            <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                            <th class="border-bottom-0">{{ __('home.notesClient') }}</th>

                                        </tr>
                                    </thead>
                                    <?php
                                    $i = 0;
                                    ?>
                                 
                                    <tbody>
                                        <tr>
                                                                                        <th style="color:#419BB2">{{ $invoice->date_export }}</th>

                                            <td>{{ $invoice->created_at}}</td>
                                            <td>{{ $invoice->financial_accounts_data->name }}</td>
                                            <td>{{ $invoice->recive_amount }}</td>

                                            <td>
                                                @if ($invoice->pay_method == 'Cash')
                                                <span class="text-success">{{ __('report.cash') }}</span>
                                                @elseif($invoice->pay_method =="Bank_transfer")
                                                <span class="text-success">{{ __('home.Bank_transfer') }}</span>

                                                @else

                                                <span class="text-warning">{{ __('report.shabka') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->note }}</td>

                                        </tr>

                                    </tbody>

                                </table>

                                <div class="my-5">
                                    <hr style="border-top: 4px solid rgba(0,0,0,.3)">
                                </div>


                                <br>
                                <br>
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
                                                <td>{{ __('home.total') }}</td>
                                                <td>{{ $totalprice }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

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