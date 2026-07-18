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

                    <div class="card-body">
                        <br>
                        <br>
                        <center>
                            
                       <span style="font-size: 24px;color:black;  font-weight:bold">{{ __('home.account_statement') }}</span>

                            
                        </center>
                        <br>
                   
                                <?php
                                $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                ?>
                           
                        
                              <div style="border-radius: 10px" class="card m-3 p-3">
                        <div class="table-padding">

                            <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                <thead>

                                    <tr>
                                         <th style="font-size: 13px;color:#419BB2">{{ __('home.account_statement') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $account_name}}<br>{{$branch_name}}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('report.from') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $start_at}}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('report.to') }}</th>
                                        <th style="font-size: 13px;color:#419BB2"> {{ $end_at }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('home.exportTime') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $currentdata}}</th>
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
                                                    <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.debit') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.credit') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.current balance') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.notesClient') }}</th>
                                                </tr>

                                            </thead>
                                            <?php
                                            $i = 1;
                                            $total_credit=$credit;
                                            $total_debit=$debit;
                                            $end_blance=0;
                                            ?>
                                          
                                            <tbody>
                                          
                                            
                                                 <tr>

                                                    <td>{{ $i }}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                             
                                                    <td>{{ round($debit,2)}}</td>
                                                    <td>{{ round($credit,2)}}</td>
                                              @if($total_debit-$total_credit ==0)
                                                <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                                @elseif($total_debit-$total_credit >0)
                                                <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.debit')}} ( {{round($total_debit-$total_credit,2)}} ) {{__('home.SAR')}}</td>
                                                @else
                                                <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.credit')}} ( {{round(($total_debit-$total_credit)*-1,2)}} ) {{__('home.SAR')}}</td>
                                                @endif
                                                    <td>{{ __('home.oping') }}</td>

                                               
                                                </tr>
                                                    @foreach ($data as $invoice)
                                                      <?php
                                                      
                                                      $total_credit+=$invoice['credit']  ;
                                                      $total_debit+=$invoice['depit']  ;
                                                      $end_blance= round($invoice['current_blance'],2);
                                            $i++;
                                            ?>
                                                <tr>

                                                    <td>{{ $i }}</td>
                                                    <td>{{ $invoice['id']}}</td>
                                                    <td>{{ $invoice['date'] }}</td>
                                                    <td>{{ $invoice['date_export'] }}</td>
                                                    <td>{{ $invoice['user'] }}</td>
                                                    <td>{{ $invoice['recive_amount'] }}</td>
                                                    <td>{{ round($invoice['depit'],2) }}</td>
                                                    <td>{{ round($invoice['credit'],2) }}</td>
                                            @if($total_debit-$total_credit ==0)
                                                <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                                @elseif($total_debit-$total_credit >0)
                                                <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.debit')}} ( {{round($total_debit-$total_credit,2)}} ) {{__('home.SAR')}}</td>
                                                @else
                                                <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.credit')}} ( {{round(($total_debit-$total_credit)*-1,2)}} ) {{__('home.SAR')}}</td>
                                                @endif              <td>{{ $invoice['note'] }}</td>

                                               
                                                </tr>
                                                  @endforeach
           <tr>

                                                    <td>*</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td style="color:green">{{ round($total_debit,2)}}</td>
                                                    <td style="color:green">{{ round($total_credit,2)}}</td>
                                                    <td style="color:green">{{__('home.current balance')}}</td>
                                                @if($total_debit-$total_credit ==0)
                                                <td style="font-size: 15px;font-weight: bold;color:green" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                                @elseif($total_debit-$total_credit >0)
                                                <td style="font-size: 15px;font-weight: bold;color:green" data-target="numberofpice">{{__('home.debit')}} ( {{round($total_debit-$total_credit,2)}} ) {{__('home.SAR')}}</td>
                                                @else
                                                <td style="font-size: 17px;font-weight: bold;color:green" data-target="numberofpice">{{__('home.credit')}} ( {{round(($total_debit-$total_credit)*-1,2)}} ) {{__('home.SAR')}}</td>
                                                @endif                                                      

                                               
                                                </tr>
                                                
                                                 <tr>

                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                     <td style="color:red">{{ round($total_debit-$debit,2)}}</td>
                                                    <td style="color:red">{{ round($total_credit-$credit,2)}}</td>

                                                    <td  style="color:red">{{__('home.balance_period')}}</td>
                                          
                                                @if(($total_debit-$total_credit)-($debit-$credit)  ==0)
                                                <td style="font-size: 15px;font-weight: bold;color:red" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                                @elseif(($total_debit-$total_credit)-($debit-$credit) >0)
                                                <td style="font-size: 15px;font-weight: bold;color:red" data-target="numberofpice">{{__('home.debit')}} ( {{round(($total_debit-$total_credit)-($debit-$credit) ,2)}} ) {{__('home.SAR')}}</td>
                                                @else
                                                <td style="font-size: 15px;font-weight: bold;color:red" data-target="numberofpice">{{__('home.credit')}} ( {{round((($total_debit-$total_credit)-($debit-$credit) )*-1,2)}} ) {{__('home.SAR')}}</td>
                                                @endif

                                               
                                                </tr>
                                            </tbody>
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