@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
                  @page
        {
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
                                                                                    <p><center>{{ __('home.bankDecument') }}</center></p>

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
                                        <th style="font-size: 13px;color:#419BB2">{{ __('home.branch') }}</th>
                                        <th style="font-size: 13px;color:#419BB2">{{ $branch}}</th>
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
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">#</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.employee') }} </th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.operationtype') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.action') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.the amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 0;
                                    $totaldiscount = 0;
                                    $totalPriceDay = 0;

                                    $listId = [];
                                    $debitblance = 0;

                                    ?>


                                    <?php
                                    $i = 0;
                                    $withdrow=0;
                                    $indepit=0;

                                    ?>
                                    @foreach($data as $invoice)


                                    <?php $i++; 
                                     $withdrow+=0;
                                     $indepit+=0;
                                    ?>

                                    <td>{{$i}}</td>
                                    <td>{{$invoice['date']}}</td>
                                    <td>{{$invoice['user']}}</td>
                                    <td>{{$invoice['branch']}}</td>
                                    <td>{{$invoice['type']}}</td>
                                    <td>{{$invoice['payment']}}</td>
                                    @if($invoice['in']==0)
                                    <?php
                                    $withdrow+=$invoice['amount'];
                                    ?>
                                    <td><span style="color:red">{{__('home.withdraw')}}</td>
                                    @else
                                    <td><span style="color:green">{{__('home.deposit')}}</td>
                                    <?php
                                    $indepit+=$invoice['amount'];
                                    ?>
                                    @endif
                                    <td>{{round($invoice['amount'],2)}}</td>
                                  

                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>

                            <br>

                            <br>


                            @endif
                            <br>


                            <br>
                        </div>
                        <hr class="mg-b-40">


                        <div class="table-padding">
                                                    <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                                        <thead>

                                                            <tr>
                                                                <th>{{ __('home.withdraw') }}</th>
                                                                <th style="color: red">{{ round( $withdrow,2) }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th >{{ __('home.deposit') }}</th>
                                                                <th style="color:green;">{{ round($indepit ,2)   }}</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
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