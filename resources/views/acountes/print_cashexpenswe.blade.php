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
{{__('home.Receipt document')}}@stop
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
                    <div class="row ">
                    <center>{{__('home.other_expenses')}}</center>
                        
                    </div>


                    <div class="card-body">
                        <br><br>
                        <div class="table-responsive">
                            <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>

                                        <th class="border-bottom-0"> {{ __('accountes.user') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.Reasonforspendingmoney') }}</th>
                                        <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }}</th>
                                        <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th class="border-bottom-0">{{ __('home.notesClient') }}</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td>{{$data['id']}}</td>
                                    <td>{{$data['user']}}</td>
                                    <td>{{$data['Reasonforspendingmoney']}}</td>
                                    <td>{{$data['Theـamountـpaid']}}</td>
                                    <td>{{$data['Pay_Method_Name']}}</td>
                                    <td>{{$data['notes']}}</td>

                                </tbody>
                            </table>
                            <br>
                            <br>
                            <br>
                            <br>
                            <div class="billed-from">
                                <br>
                                <p>{{__('home.employeereciver')}} : </p>
                                <br>
                                <p>{{__('home.thesignature')}} : </p>
                            </div>

                            <hr class="mg-b-40">



                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i class="mdi mdi-printer ml-1"></i>{{__('home.print')}}</button>
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
<script>
    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

    });
    $(document).ready(function() {

    });
</script>
@endsection