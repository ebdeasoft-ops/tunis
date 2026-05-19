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
    {{ __('hr.salarydecoument') }}
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
                        <div class="card-body">
                                   <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">

                        <div class="billed-from" style="width:40%;text-align: center;" >
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
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 200px; height: 130px;"></a>

                        </div>


                        <div class="billed-from" style="width:40%;text-align: center;">
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

                            
<center><h2> {{ __('hr.salarydecoument') }}</h2>
<br>
<h3>
      {{$month}}
</h3></center> 



                            <div class="">

                                <div class="card">


                                    <div class="card-body">
                                        <div class="hoverable-table px-5">
                                            <table class="table table-hover table-striped table-bordered " id="example1" data-page-length='50'
                                                style=" text-align: center;">

                                                <thead>

                                                    <tr>
                                                        <th># </th>
                                                        <th>{{ __('hr.name') }}</th>
                                                        <th>{{ __('hr.Id') }}</th>
                                                        {{-- <th>{{ __('hr.email') }}</th>
                                                        <th>{{ __('hr.phone') }}</th> --}}
                                                        <th>{{ __('hr.department') }}</th>
                                                        <th>{{ __('hr.salary') }}</th>
                                                        <th>{{ __('hr.increastotal') }}</th>
                                                        <th>{{ __('hr.decreasetotal') }}</th>
                                                        <th>{{ __('home.amountLoans') }}</th>
                                                        <th>{{ __('home.total') }}</th>



                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 0; ?>

                                                    @foreach ($list_salary_data as $employee)
                                                        <?php $i++; ?>

                                                        <td>{{ $i }}</td>
                                                        <td>{{ __('hr.phone') == 'رقم الجوال' ? $employee['employeeData']->name_ar : $employee['employeeData']->name_en }}
                                                        </td>
                                                        <td>{{ $employee['employeeData']->personal_identification }}</td>
                                                        {{-- <td>{{ $employee['employeeData']->email }}</td> --}}
                                                        {{-- <td>{{ $employee['employeeData']->phone }}</td> --}}
                                                        <td>{{ __('hr.phone') == 'رقم الجوال' ? $employee['employeeData']->departments->name_ar : $employee['employeeData']->departments->name_en }}
                                                        </td>
                                                        <td>{{ $employee['employeeData']->salary }}</td>
                                                        <td>{{ $employee['bounes'] }}</td>
                                                        <td>{{ $employee['discount'] }}</td>
                                                        <td>{{ $employee['Loans'] }}</td>
                                                        <td>{{ $employee['employeeData']->salary + $employee['bounes'] - $employee['discount']-$employee['Loans'] }}
                                                        </td>

                                                        </td>



                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                        <hr class="mg-b-40">



                                        <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button"
                                            onclick="printDiv()">
                                            {{ __('home.print') }}
                                            <i class="mdi mdi-printer ml-1"></i>
                                    </div>
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
<script>
    $(document).ready(function() {
        $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
       
    });
</script>
@endsection
