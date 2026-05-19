@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
        
                  @page
        {
                font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
        border-style: solid;

            size: auto; /* auto is the initial value */
            margin: 2mm 2mm 10mm 2mm; /* this affects the margin in the printer settings */
                font-size:30px!important;

        }
        .tx-18{
                            font-size:15px!important;

        }
         .tx-16{
                            font-size:13px!important;

        }
                .text {
  display: block;
  width: 350px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
        .double{
            border: 3px solid grey;
            border-radius: 5px;
            width:200px;

        }
    }

    body {
    
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
     <span style="font-size:20px">{{Nameen}}</span>
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

   <span style="font-size:17px">{{Namear}}</span>
    <br>
    <p> {{describtionar}}</p>
    <p>{{STar}}</p>
    <p>{{Taxar}}</p>

</div><!-- billed-from -->
</div><!-- invoice-header -->
                    <br>
  <center>  <p class="double"> كشف حساب العميل  <br>
                   Customer account statement
                   </p> </center>

                    <br>
                    <div class="col-lg-3" id="start_at">
                        <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                        <?php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                        ?>
                        <label style="font-size: 12px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label><br>

                        <br>
                    </div>
                    @if (isset($data))
                    <div style="border-radius: 10px" class="card m-3 p-3">
                        <div class="table-padding">

                            <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                <thead>

                                    <tr>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('report.from') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $start_at}}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('report.to') }}</th>
                                        <th style="font-size: 13px;color:#419BB2"> {{ $end_at }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ __('home.clietName') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $customerName}}</th>
                                    </tr>

                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                    <div class="table-responsive">

                        <br>

                        <div>
                            <table class="table table-hover table-bordered table-striped text-center my-3" id="example1" data-page-length='50' style=" text-align: center;">
                                <thead>
                                    <tr>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.sallerName') }} </th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.debit') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.credit') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.current balance') }}</th>
                                        <th id="print_Button" style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                         <tr ">
                                        <td data-target="id">-</td>
                                        <td data-target="id">-</td>

                                        <td data-target="numberofpice">-</td>
                                        <td data-target="numberofpice">-
                                        </td>
                                      
                                        <td data-target="numberofpice">-</td>
                                        <td data-target="numberofpice">-</td>
                                        <td data-target="numberofpice">-</td>
                                        <td data-target="numberofpice">
                                         {{__('home.oping')}}
                                        </td>

                                        <td><span style="color:red;font-size:16px">{{$data[2]}}</span></td>

                                        <td data-target="numberofpice">-</td>

                                    </tr>
                                    <?php
                                    $debitblance=$data[2];
                                    $i=0;
                                    ?>
                                    @foreach ($data[3] as $product)

                                   
                                    <?php $i++; ?>
                                    @if( $product['type']==3)
                                  
                                    <tr>

                                        <th style="color:#419BB2">{{ __('home.decoumentNo') }}</th>
                                        <th class="border-bottom-0"> {{ __('home.clientname') }}</th>
                                        <th class="border-bottom-0"> {{ __('home.date') }}</th>
                                        <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.cashreceived') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.Remainingamount') }}</th>
                                        <th style="color:#419BB2">__</th>
                                        <th style="color:#419BB2">__</th>
                                        <th style="color:#419BB2">__</th>

                                    </tr>



                                    <tr>
                                        <td><span style="color:green;font-size:16px">{{ $product['id'] }}</span></td>
<?php
$invoice=App\Models\credittransactions::find($product['id']);

?>
                                        <td>{{ $invoice->customer->name }}</td>
                                        <td>{{$invoice->created_at }}</td>
                                        <td>
                                            @if ($invoice->pay_method == 'Cash')
                                            <span class="text-success">{{ __('report.cash') }}</span>
                                            @elseif($invoice->pay_method =="Bank_transfer")
                                            <span class="text-success">{{ __('home.Bank_transfer') }}</span>

                                            @else

                                            <span class="text-warning">{{ __('report.shabka') }}</span>
                                            @endif
                                        </td>
                                        <?php

$debitblance-=$invoice->recive_amount ;
?>
                                        <td><span style="color:green;font-size:16px">{{ $invoice->recive_amount  }}</span></td>
                                        <td><span style="color:red;font-size:16px">{{round($debitblance,2)}}</span></td>
                                        <td><span style="color:red;font-size:16px">{{round($debitblance,2) }}</span></td>
<td>--</td>
                                        <td><span style="color:red;font-size:16px">{{round( $debitblance,2) }}</span></td>


                                    </tr>



                                    @endif
                                    @if( $product['type']==2&&$product['typepayment'])


                                    <tr >
                                        <td data-target="id">{{ $product['id'] }}</td>
                                        <td data-target="id">{{ $product['user'] }}</td>
                                        <td data-target="numberofpice">{{ $product['data'] }}</td>
                                        <td data-target="numberofpice">{{ $product['branch'] }}</td>
                                        <td data-target="numberofpice">{{ $product['payment'] }}</td>
                                        <td data-target="numberofpice"><span style="color:red">{{__('home.salesـreturned')}} :
                                        </span>{{round($product['amoint'],2)  }}</td>
                                         @if( $product['typepayment'])
                                    <?php

$debitblance=$debitblance-$product['amoint'];
?>
                                         @endif
                                         @if($product['typepayment'])
                                         
                                                                                  <td>{{round($product['amoint'],2)}}</td>

                                         @else
                                         
                                     <td>--</td>

                                         @endif
                                     <td>--</td>

                                        <td><span style="color:red;font-size:16px">{{ round($debitblance,2)=='-0'?0:round($debitblance,2)}}</span></td>

<td id="print_Button"><a style="color: #23395D" class="dropdown-item" href="showInvoiceRecent/{{ $product['id'] }}"><i style="fill:#072c3c !important" class=" fas fa-print"></i>&nbsp;&nbsp;
                        {{ __('home.show') }}</a></td>
                                    </tr>
   
                                   @endif

                                   @if( $product['type']==1)


                                    <tr >
                                    <td data-target="id">{{ $product['id'] }}</td>
                                        <td data-target="id">{{ $product['user'] }}</td>
                                        <td data-target="numberofpice">{{ $product['data'] }}</td>
                                        <td data-target="numberofpice">{{ $product['branch'] }}</td>
                                        <td data-target="numberofpice">{{ $product['payment'] }}</td>
                                        <td data-target="numberofpice">{{$product['amoint']  }}</td>
                                        
                              @if( $product['typepayment'])
                                    <?php

$debitblance+=$product['amoint'];
?>
@endif
<td>--</td>
                                        <td data-target="numberofpice">{{$product['amoint']  }}</td>

  <td><span style="color:red;font-size:16px">{{ round($debitblance,2)=='-0'?0:round($debitblance,2)}}</span></td>

<td  id="print_Button"><a style="color: #23395D" class="dropdown-item" href="showInvoiceRecent/{{ $product['id'] }}"><i style="fill:#072c3c !important" class=" fas fa-print"></i>&nbsp;&nbsp;
                        {{ __('home.show') }}</a></td>
                                    </tr>
                                   @endif
                                    @endforeach
                                    
                                    <tr>
                                    <th>-</th>
                                    <th>-</th>
                                    <th>-</th>
                                    <th>-</th>
                                    <th>{{ __('home.total') }}</th>
                                    <th>--</th>
                                    <th>--</th>
                                    <th>--</th>
                                    <td><span style="color:red;font-size:16px">{{ round($debitblance,2) }}</span></td>

                                        </tr>
                                </tbody>
                            </table>

                            <br>
                            
                            <br>

                            <?php
                            $customer = App\Models\customers::find($customerId);
                            ?>
                            @endif
                            <br>

                            <div class="table-padding">

                                <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                    <thead>

                                        <tr>
                                            <th>{{ __('home.theamountreciet') }}</th>
                                            <th style="color:red;font-size:16px">{{ round($debitblance,2)}}</th>
                                        </tr>

                                    </thead>
                                </table>
                            </div>
                            <br>
                                     <p>
                            يعتبر كشف الحساب مطابق في حالة عدم وجود اعتراض عليه خلال اسبوع  (٧ ايام ) من تاريخ طباعته
                            </p>
                            <p>The statement of account is considered compliant if there is no objection to it within a week (7 days) from the date of its printing</p>
                     
                        </div>
                        <hr class="mg-b-40">



                   


<br> 

<center><a style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px" href="{{ url('/' . ($page = 'generate_customer_statment_pdf') . '/' .$customerId . '/' . $start_at . '/' . $end_at) }}"
                    class="btn btn-success p-1 px-2 fw-bolder"  id="generate_pdf" target="_blank" >{{ __('home.dwonloadpdf') }}&nbsp;<i class="fa-solid fa-download"></i></i></a>
</center>
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