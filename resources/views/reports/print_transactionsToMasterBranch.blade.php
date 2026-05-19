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
{{ __('home.transactionsToMasterBranch') }}
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
                                <span>{{ __('home.transactionsToMasterBranch') }}
</span>
                                <br>

                            </div>

                            <div class="row d-flex justify-content-center">

                                <div class="col-lg-3" id="start_at">
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $data['start_at'] }}</label>


                                </div>
                                <div class="col-lg-3" id="start_at">
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}:</label>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $data['end_at'] }}</label>

                                </div>
                                <div class="col-lg-3" id="start_at">
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                                    <?php
                                    $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                    ?>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                @if (isset($data))
                                <?php $i = 0; ?>
                                <div class="col-xl-12">
                                    <div class="card mg-b-20">
                                        <div class="card-header pb-0">
                                        </div>
                                        <div class="card-body">
                                            <div style="border-radius: 5px !important" class="table-responsive py-2">


                                                <div class="card-header pb-0">

                                                    <div class="table-responsive">
                                                        <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center">
                                                            <thead>
                                                                <tr>
                                                                    <th class="border-bottom-0"> {{__('home.date')}}</th>
                                                                    <th class="border-bottom-0">{{__('accountes.Theamountpaid')}} {{__('report.cash')}}</th>
                                                                    <th class="border-bottom-0">{{__('accountes.Theamountpaid')}} {{__('report.shabka')}}</th>
                                                                    <th class="border-bottom-0">{{ __('home.Bank_transfer') }}</th>
                                                                    <th class="border-bottom-0">{{ __('home.total') }}</th>

                                                                    <th class="border-bottom-0">{{__('home.branch_sender')}}</th>
                                                                    <th class="border-bottom-0">{{__('home.branch_reciver')}}</th>

                                                                    <th class="border-bottom-0">{{__('home.stautes')}}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $totalconfermed = 0;
                                                                $totalrejected = 0;
                                                                $totalpaddind = 0;
                                                                ?>
                                                                @foreach($data['transactions'] as $tansaction)
                                                                <tr>
                                                                    <?php
                                                                    if ($tansaction['status'] == 1) {
                                                                        $totalconfermed += $tansaction['bank_transfer'] + $tansaction['Pay_Method_Name'] + $tansaction['amount'];
                                                                    } elseif ($tansaction['status'] == 0) {
                                                                        $totalpaddind += $tansaction['bank_transfer'] + $tansaction['Pay_Method_Name'] + $tansaction['amount'];
                                                                    } else {
                                                                        $totalrejected += $tansaction['bank_transfer'] + $tansaction['Pay_Method_Name'] + $tansaction['amount'];
                                                                    }
                                                                    ?>
                                                                    <td>{{$tansaction['created_at']}}</td>
                                                                    <td>{{$tansaction['amount']}}</td>
                                                                    <td>{{$tansaction['Pay_Method_Name']}}</td>
                                                                    <td>{{$tansaction['bank_transfer']}}</td>

                                                                    <td>{{$tansaction['bank_transfer']+$tansaction['Pay_Method_Name']+$tansaction['amount']}}</td>
                                                                    <td>{{$tansaction->branch->name}}</td>
                                                                    <td>{{$tansaction->user->branch->name}}</td>
                                                                    <?php
                                                                    $id = $tansaction->id;


                                                                    ?>

                                                                    <td>
                                                                        @if($tansaction['status']==0)
                                                                        {{__('home.Notacceptedyet')}}
                                                                        @elseif($tansaction['status']==2)
                                                                        <span style="color:red">{{__('home.reject')}}</span>
                                                                        @else

                                                                        {{__('home.accept')}}
                                                                        @endif
                                                                    </td>


                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>








                                                </div>




















                                                <div class="table-padding">
                                                    <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.Total transactions accepted') }}
                                                                </th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ $totalconfermed }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.Total Transactions Not Confirmed') }}
                                                                </th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ $totalpaddind }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color: rgba(236, 240, 250, 1);"> {{ __('home.Total rejected transactions') }}
                                                                </th>
                                                                <th style="background-color: rgba(236, 240, 250, 1);">{{ $totalrejected }}</th>
                                                            </tr>

                                                        </thead>
                                                    </table>
                                                </div>
                                                @endif
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