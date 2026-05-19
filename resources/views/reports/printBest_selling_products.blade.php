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
                        <br>

                    </div>


                    @if (isset($bestselling))
                    <div style="border-radius: 10px" class="card p-3 m-3">
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
                        $i = 0;

                        ?>
                        @if ($bestselling != null)
                        <div style="border-radius: 10px" class="card m-3 p-3">
                            <div class="row">
                            &nbsp;
                                &nbsp;
                                &nbsp;
                                <div class="row">
                                    <label style="color: #419BB2;font-size:16px;font-weight:bold" for="exampleFormControlSelect1"> {{ __('report.fromdate') }} :</label>
                                    <label style="color: #419BB2;font-size:16px;font-weight:bold" for="exampleFormControlSelect1"> {{ $date[0] }}</label>
                                </div>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                &nbsp;&nbsp;
                                &nbsp;
                                &nbsp;

                                <div class="row">
                                    <label style="color: #419BB2;font-size:16px;font-weight:bold" for="exampleFormControlSelect1"> {{ __('report.todate') }} :</label>
                                    <label style="color: #419BB2;font-size:16px;font-weight:bold" for="exampleFormControlSelect1"> {{$date[1] }}</label>
                                </div>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                <div class="row" >
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                                    <?php
                                    $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                    ?>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                                </div>
                            </div>
                            @endif
                            <div class="table-responsive hoverable-table">
                                <table class="table table-hover table-striped table-bordered text-center" id="example1" data-page-length='50' style=" text-align: center;">
                                    <col style="width:20%">
                                    <col style="width:30%">
                                    <col style="width:30%">
                                    <col style="width:15%">


                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0"> {{ __('home.productNo') }}</th>

                                            <th class="border-bottom-0"> {{ __('home.productname') }}</th>

                                            <th class="border-bottom-0">{{ __('users.branch') }}</th>
                                            <th class="border-bottom-0"> {{ __('report.Number of pieces sold') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;

                                        $data = 'm';
                                        ?>
                                        @foreach ($bestselling as $product)
                                        <?php
                                        if ($i == 0) {
                                            $startat = $product['start_at'];
                                            $endat = $product['end_at'];
                                        }
                                        ?>
                                        <td dir='ltr'>{{ $product['productcode'] }}</td>
                                        <td>{{ $product['productname'] }}</td>
                                        <td>{{ $product['branch'] }}</td>

                                        <td>{{ $product['numberofsall'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                                <br>

                                <br>
                                @endif
                            </div>

                        </div>
                        <hr class="mg-b-40">


                        <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                            </button>
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