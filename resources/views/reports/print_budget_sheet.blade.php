@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
    }
</style>
@endsection
@section('title')
{{ __('report.budgetsheet') }}
@stop
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h5 style="color: white" class="mt-1">
                    معاينة طباعة الفاتورة</h5>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    <!-- row -->
    <div class="row row-sm">
        <div style="padding-left: 0;padding-right:0" class="col-md-12 col-xl-12">

            </h5>
            <div class="col-md-12 col-xl-12">
                <div class=" main-content-body-invoice" id="print">
                   
                                  
                    <div class="card card-invoice p-3 pt-4">
                        
                           <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

                        <div class="card-body pt-3">
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
                                <br>

                            </div>





                            <?php
                            $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                            $banktransfertotal=0;
                            ?>

<center>
    
      <h6 style="color: black" class="invoice-title">{{ __('report.budgetsheet') }}</h6>

    
</center>

                            <div class="table-padding table-responsive ">
                                
                               
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $data['start_at'] }}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ $data['end_at'] }}</label>
                                            </th>


                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.branch') }} </label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $data['branch'] }}</label>
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
                            <div class="table-responsive">
                                <?php

                                $totalrecivefrombranchcash = 0;
                                $totalrecivefrombranchshabka = 0;

                                $i = 0;
                                ?>
                                <div class="col-xl-12">
                                    <div class="card mg-b-20">
                                        <div class="card-header pb-0">
                                        </div>
                                        <div class="card-body">
                                            <div style="border-radius: 5px !important" class="table-responsive py-2">


                                                <table class="table text-center table-bordered budgetSheet-table" style="border: 1px solid black;border-collapse: collapse !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>__</th>
                                                            <th colspan="3" style="background-color: #ecf0fa;font-size: 12px">{{ __('home.sales') }}</th>
                                                            <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.purchases') }}</th>
                                                            <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.The amount paid') }}</th>
                                                            <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.newexpense') }}</th>
                                                            <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.receive money') }}</th>
                                                            <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.cach_from_bank') }}</th>
                                                            <th style="background-color: #ecf0fa;font-size: 12px">{{ __('home.convertboxtobank') }}</th>

                                                      
                                                        </tr>
                                                    <tbody>
                                                        <tr>
                                                            <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('report.cash') }}</td>
                                                            <td style="vertical-align: middle" colspan="3">{{ __('home.sales') }} : {{ $data['salescash'] }} </td>
                                                            <td style="vertical-align: middle">{{__('home.purchases')}} : {{ $data['purchesecash'] }}</td>
                                                            <td>{{ round($data['transactiontosuplliers_cash'] )}}</td>
                                                            <td>{{ round($data['expense_cash'] )}}</td>
                                                            <td>{{ round($data['credittransaction_cash']) }}</td>
                                                            <td>{{__('home.cach_bank')}} : {{$data['bank_cash']}} </td>
                                                                                 <td>__</td>



                                                        <tr>
                                                            <?php
                                                            $returnsalescach = $data['returnsalescash'] + $data['returnSalespartial']  

                                                            ?>
                                                            <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;{{__('home.return')}} : {{ $returnsalescach }}</td>
                                                            <td colspan="">{{__('home.return')}} : {{ $data['returnpurchasecash'] }}</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                     
                                                         
                                                        </tr>
                                                        <?php
                                                        $totalsalescash = $data['salescash'] - $returnsalescach;
                                                        ?>
                                                        <tr class="budget-line">
                                                            <td colspan="3">{{__('home.total')}} : {{$totalsalescash }} </td>
                                                            <td>{{__('home.total') }}:{{$data['purchesecash']-$data['returnpurchasecash'] }}</td>
                                                            <td>{{__('home.total') }}: {{ $data['transactiontosuplliers_cash']}}</td>
                                                            <td>{{__('home.total') }}: {{ $data['expense_cash']}}</td>
                                                            <td> {{__('home.total')}} :{{ $data['credittransaction_cash']}} </td>
                                                            <td>{{__('home.cach_bank')}} : {{$data['bank_cash']}} </td>
                                                            <td>__</td>

                                                     
                                                        </tr>
                                                        </tr>






                                                        <tr>
                                                            <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('report.shabka') }}</td>
                                                            <td colspan="3">{{__('home.sales')}}
                                                                : {{$data['salesshabka']}}
                                                            </td>
                                                            <td>
                                                                {{__('home.purchases')}} : {{ $data['purcheseshabka'] }}
                                                            </td>
                                                            <td>
                                                                {{ $data['transactiontosuplliers_shabka'] }}
                                                            </td>  
                                                            <td>
                                                                {{ $data['expense_shabka'] }}
                                                            </td>
                                                            <td>
                                                                {{ $data['credittransaction_shabka'] }}
                                                            </td>
                                                                                                                      <td>__</td>
                                                            <td>__</td>

                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">{{ __('home.return') }} : {{ $data['returnsalesshabka']}}
                                                            </td>
                                                            <td>{{ __('home.return') }} : {{ $data['returnpurchaseshabka'] }}</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>



                                                        </tr>
                                                        <tr class="budget-line">
                                                            <?php
                                                            $totalsaleshabka = $data['salesshabka'];
                                                            ?>
                                                            <td colspan="3">{{ __('home.total') }} : {{ $totalsaleshabka- $data['returnsalesshabka'] }}</td>
                                                            <td>{{ __('home.total') }} : {{$data['purcheseshabka']- $data['returnpurchaseshabka'] }}</td>
                                                            <td>{{ __('home.total')}} :{{ $data['transactiontosuplliers_shabka']}}</td>
                                                                <td>
                                                                {{ $data['expense_shabka'] }}
                                                            </td>
                                                            <td>{{ __('home.total') }}:{{ $data['credittransaction_shabka'] }}</td>
                                                                                                                       <td>__</td>
                                                            <td>__</td>

                                                        </tr>














                                                        <tr>
                                                            <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('home.Bank_transfer') }}</td>
                                                            <td colspan="3">{{__('home.sales')}} : {{$data['salesBankTransfer']}}</td>
                                                            <td>{{__('home.purchases')}} : {{ $data['purchasebankTransfer'] }}</td>
                                                            <td>{{$data['transactiontosuplliers_banktransfer']}}</td>
                                                            <td>{{$data['expense_banktransfer']}}</td>
                                                            <td>{{$data['credittransaction_banktransfer']}}</td>
                                                            <td>{{__('home.shabka_bank')}} : {{$data['bank_shabka'] }}</td>
                                                            <td> {{$data['convertcashboxToBankitemamount'] }}</td>

                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">{{ __('home.return') }} : {{ $data['returnSalesBankTransfer']}}</td>
                                                            <td>{{ __('home.return') }} : {{$data['returnpurchasebanktransfer'] }} </td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>


                                                        </tr>
                                                        <tr class="budget-line">
                                                            <td colspan="3">

                                                                <?php
                                                                $totalsalepartial = $data['salesBankTransfer'];

                                                                ?>
                                                                {{ __('home.total') }} : {{ $totalsalepartial- $data['returnSalesBankTransfer']}}
                                                            </td>
                                                            <td> {{ __('home.total') }} : {{ $data['purchasebankTransfer']-$data['returnpurchasebanktransfer']}}</td>
                                                            <td>{{ __('home.total') }} : {{$data['transactiontosuplliers_banktransfer']}}</td>
                                                                                                                        <td>{{$data['expense_banktransfer']}}</td>

                                                            <td>{{ __('home.total') }} : {{$data['credittransaction_banktransfer']}}</td>
                                                           <td>{{__('home.shabka_bank')}} : {{$data['bank_shabka'] }}</td>
                                                            <td> {{$data['convertcashboxToBankitemamount'] }}</td>

                                                        </tr>
                                                        </tr>














                                                        <tr>
                                                            <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('report.credit') }}</td>
                                                            <td style="vertical-align: middle" colspan="3">{{ __('home.sales') }} : {{$data['salescredit']}}
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                {{__('home.purchases')}} : {{ $data['purchesecredit'] }}
                                                            </td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>

                                                        <tr>
                                                            <td colspan="3">{{__('home.return')}} : {{ $data['returnsalescredit'] }}</td>
                                                            <td>{{__('home.return')}} : {{$data['returnpurchasecredit']}}</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>

                                                        </tr>
                                                        <?php
                                                        $totalsalecrdit = $data['salescredit'] - $data['returnsalescredit'];
                                                        ?>
                                                        <tr class="budget-line">
                                                            <td colspan="3">{{ __('home.total') }} : {{ $totalsalecrdit }}</td>
                                                            <td>{{ __('home.total') }} : {{ $data['purchesecredit']-$data['returnpurchasecredit']}}</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>

                                                        </tr>
                                                        </tr>

























                                                        <tr>
                                                            <td rowspan="3" style="background-color:#ecf0fa;vertical-align:middle">{{ __('home.total') }}</td>
                                                            <td colspan="3">{{__('home.sales')}} : {{ $data['salescredit'] + $data['salesshabka'] + $data['salescash']+$data['salesBankTransfer'] }}</td>
                                                            <td>{{__('home.purchases')}} : {{ $data['purchesecredit'] +$data['purchasebankTransfer']+ $data['purcheseshabka'] + $data['purchesecash']  }}</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td> {{$data['bank_shabka']+$data['bank_cash'] }}</td>
                                                            <td> {{$data['convertcashboxToBankitemamount'] }}</td>

                                                        <tr>
                                                            <td colspan="3">{{__('home.return')}} : {{ $data['returnsalesshabka'] + $data['returnsalescash'] + $data['returnsalescredit'] +$data['returnSalespartial']+$data['returnSalesBankTransfer']}}</td>
                                                            <td>{{__('home.return')}} : {{ $data['returnpurchaseshabka'] + $data['returnpurchasecash'] + $data['returnpurchasecredit']  +$data['returnpurchasebanktransfer']}}</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>
                                                            <td>__</td>

                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">{{__('home.total')}} : {{  $data['salescredit'] + $data['salesshabka'] + $data['salescash']+$data['salesBankTransfer']-( $data['returnsalesshabka'] + $data['returnsalescash'] + $data['returnsalescredit'] +$data['returnSalespartial']+$data['returnSalesBankTransfer'])}} </td>
                                                            <td>{{__('home.total')}} : {{round( ( $data['purchesecash'] + $data['purchesecredit'] + $data['purcheseshabka'] + $data['purchasebankTransfer']-( $data['returnpurchasecash'] + $data['returnpurchasecredit']+$data['returnpurchasebanktransfer']+$data['returnpurchaseshabka'])) ,2) }}</td>
                                                            <td rowspan="3">{{ __('home.total') }} : {{ round($data['transactiontosuplliers_shabka'] + $data['transactiontosuplliers_cash']+$data['transactiontosuplliers_banktransfer'],2) }}</td>
                                                            <td rowspan="3">{{ __('home.total') }} : {{ round($data['expense_cash'] + $data['expense_banktransfer']+$data['expense_shabka'],2) }}</td>
                                                            <td rowspan="3">{{ __('home.total') }} : {{ round($data['credittransaction_shabka'] + $data['credittransaction_cash']+$data['credittransaction_banktransfer'],2) }}</td>
                                                            <td> {{$data['bank_shabka']+$data['bank_cash'] }}</td>
                                                            <td> {{$data['convertcashboxToBankitemamount'] }}</td>

                                                        </tr>
                                                        </tr>

                                                    </tbody>
                                                    </thead>
                                                </table>








                                                <div class="table-padding">
                                                    <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-1">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.paymentmethod') }} </th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.receive money') }} </th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.The amount paid') }}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.Remainingamount') }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ __('report.cash') }}</th>
                                                                <th>{{round(( $data['bank_cash']+$data['totaltransferlastdayCash'] +$data['credittransaction_cash']+($data['salescash']-$returnsalescach)+ $totalrecivefrombranchcash),2) }}</th>
                                                                <th>{{ $data['expense_cash'] + $data['transactiontosuplliers_cash']+$data['purchesecash']- $data['returnpurchasecash'] +round(  $data['convertcashboxToBankitemamount'] ,2) }}</th>
                                                                <th>{{round((( $data['bank_cash']+$data['totaltransferlastdayCash'] +$data['credittransaction_cash']+($data['salescash']-$returnsalescach)+ $totalrecivefrombranchcash)-round(  $data['convertcashboxToBankitemamount'] ,2))-($data['expense_cash'] + $data['transactiontosuplliers_cash']+$data['purchesecash']- $data['returnpurchasecash']),2) }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.bank') }}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{$data['credittransaction_shabka']+round( $data['totaltransferlastdaybank'] ,2) +$data['bank_shabka']+$data['salesBankTransfer']+$data['salesshabka']+$banktransfertotal+ $totalrecivefrombranchshabka+$data['credittransaction_banktransfer'] +round( $data['convertcashboxToBankitemamount'] ,2)}}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ $data['transactiontosuplliers_shabka']+$data['transactiontosuplliers_banktransfer']+$data['expense_banktransfer']+$data['expense_shabka']+$data['purcheseshabka']+$data['purchasebankTransfer']-( $data['returnpurchasebanktransfer']+$data['returnpurchaseshabka'])}}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{round( ($data['credittransaction_shabka']+round( $data['totaltransferlastdaybank'] ,2) +$data['credittransaction_banktransfer']+$data['bank_shabka']+$banktransfertotal+$data['salesshabka']+$data['salesBankTransfer']+ $totalrecivefrombranchshabka +round( $data['convertcashboxToBankitemamount'] ,2)   )-( $data['transactiontosuplliers_shabka']+$data['transactiontosuplliers_banktransfer']+$data['expense_banktransfer']+$data['expense_shabka']+$data['purcheseshabka']+$data['purchasebankTransfer']-( $data['returnpurchasebanktransfer']+$data['returnpurchaseshabka'])),2)}}</th>

                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>









                         

                                                @can('Sales profit')

                                                <div class="table-padding">
                                                    <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.benfitcash') }}
                                                                </th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ round( $data['benfitcash'] ,2) }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ __('home.benfitshabka') }}</th>
                                                                <th>{{ round( $data['benfitshabka'],2) }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.benfitcradit') }}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ round($data['benfitcradit'] ,2)   }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.benfitbankTransferSales') }}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ round($data['benfitBank_transfer'] ,2)   }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.total') }}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ round($data['benfitcradit']+round($data['benfitBank_transfer'] ,2)+round( $data['benfitcash'] ,2) ,2)+round( $data['benfitshabka'],2)   }}</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                @endcan




                                                <div class="table-padding">
                                                    <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                                        <thead>

                                                            <tr>
                                                                <th>{{ __('home.credit_supplier_amount') }}</th>
                                                                <th>{{ round( $data['credit_supplier_amount'],2) }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.creadit_customer_amount') }}</th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ round($data['creadit_customer_amount'] ,2)   }}</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>





                                            </div>

                                        </div>
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
</div>
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