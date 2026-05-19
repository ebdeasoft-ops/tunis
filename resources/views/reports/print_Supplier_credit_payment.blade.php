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
{{ __('report.Supplier credit payment') }}
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
                      
                     
                        
                        
                        
     <center>      
       <h6 style="color: black" class="invoice-title">{{ __('report.Supplier credit payment') }}</h6>

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
                        
                        
                       

                        </div>
                        <br>
                        <div class="">
                            <div class="card-body">
                                <div class="">
                                    <div class="">
                                        <table class="table table-hover table-bordered table-striped text-cneter ">

                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.exportTime') }}</th>
                                                    <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                    <th class="border-bottom-0"> {{ __('home.employee') }}</th>
                                                    <th class="border-bottom-0"> {{ __('home.decoumentNo') }}
                                                    <th class="border-bottom-0"> {{ __('home.acount_name') }}</th>
                                                    <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }} </th>
                                                    <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                                </tr>

                                            </thead>
                                            @foreach ($Invoices as $invoice)
                                            <?php
                                            $i = 0;
                                            ?>
                                            <?php
                                            $i++;

                                            $date =  $invoice->created_at;
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                                                                        <td>{{ $invoice->sent_serf_count}}</td>

                                                    <td>{{ $date }}</td>
                                                    <td>{{  $invoice->date_export }}</td>
                                                    <td>{{ $invoice->user->name }}</td>
                                                    <td>{{ $invoice->id }}</td>
                                                    <td>{{ $invoice->financial_accounts_data->name }}</td>
                                                    <td>{{ $invoice->recive_amount }}</td>
                                                    <td>
                                                        @if ($invoice->type == 'Cash')
                                                        {{ __('report.cash') }}
                                                        @elseif ($invoice->type == 'Bank_transfer')
                                                        {{ __('home.Bank_transfer') }}
                                                        @else
                                                        {{ __('report.shabka') }}
                                                        @endif
                                                    </td>

                                                </tr>

                                            </tbody>
                                            @endforeach
                                        </table>
                                        <br>


                                    </div>
                                </div>

                                <br>


                                <br>

                                <hr class="mg-b-40">


                            


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