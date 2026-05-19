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
{{ __('home.shabka_bank') }}
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
                        <div class="card-body pt-3">
     <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>



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
                                                  <span> <center>{{ __('home.cach_from_bank') }}</center></span>

                            <div class="row mg-t-12">
                                <br>
                                <br>

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
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.branch') }} : </label>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $data['branch'] }}</label>

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
                            @if (count($data)>0)
                            <div class="">
                                <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0"> {{ __('home.date') }}</th>
                                            <th class="border-bottom-0">{{ __('home.employee') }}</th>
                                            <th class="border-bottom-0">{{ __('home.branch') }}</th>
                                            <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                            <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                             <th class="border-bottom-0">{{ __('home.notesClient') }}</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($data['transactions'] as $operation)
                                        <?php
                                        $branchId = $operation->branch->id;
                                        if ($operation->payment_method == 'Cash') {
                                            $pay = __('report.cash');
                                        } elseif ($operation->payment_method == 'Bank_transfer') {
                                            $pay = __('home.Bank_transfer');
                                        } else {
                                            $pay = __('report.shabka');
                                        }
                                        $date = explode(" ", $operation->created_at)
                                        ?>
                                        <tr>
                                            <td>{{ $date[0] }}</td>

                                            <td>{{ $operation->user->name }}</td>
                                            <td>{{ $operation->branch->name }}</td>

                                            <td>{{ $operation->the_amount }}</td>
                                            <td>{{ $pay }}</td>
                                                                                <td>{{$operation->notes }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>




                                @endif
                            </div>

                        </div>
                   
                    </div>
                    <hr class="mg-b-40">




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