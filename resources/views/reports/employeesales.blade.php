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
{{__('home.year_sales_report')}}
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <?php
        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
        $currentYear = date('Y');
        $lastYear = $currentYear - 1;
    ?>

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
<br>
<center> <h3>تقرير مقارنة مبيعات العامين ({{ $lastYear }} و {{ $currentYear }}) / Sales Comparison Report</h3> </center>
                        <div class="table-padding table-responsive ">
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;"> {{ __('report.fromdate') }}:</label></th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;"> {{ $currentYear . '-01-01'}}</label></th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;"> {{ __('report.todate') }}</label></th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;"> {{ $currentYear . '-12-31' }}</label></th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;"> {{ __('home.exportTime') }} </label></th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;"> {{ $currentdata }}</label></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                           <?php
                            $totalCurrentCount = 0;
                            $totalCurrentAmount = 0;
                            $totalLastCount = 0;
                            $totalLastAmount = 0;
                           ?>
                        <div class="card-body">
                        <br><br>
                                <div class="table-responsive">
                                    <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">NO</th>
                                                <th class="border-bottom-0">الشهر MONTH</th>
                                                <th>عدد فواتير السنة السابقة ({{ $lastYear }})</th>
                                                <th>إجمالي السنة السابقة ({{ $lastYear }}) [SAR]</th>
                                                <th>عدد فواتير السنة الحالية ({{ $currentYear }})</th>
                                                <th>إجمالي السنة الحالية ({{ $currentYear }}) [SAR]</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $monthsNames = [
                                                    1 => ['يناير', 'January'],
                                                    2 => ['فبراير', 'February'],
                                                    3 => ['مارس', 'March'],
                                                    4 => ['أبريل', 'April'],
                                                    5 => ['مايو', 'May'],
                                                    6 => ['يونيو', 'June'],
                                                    7 => ['يوليو', 'July'],
                                                    8 => ['أغسطس', 'August'],
                                                    9 => ['سبتمبر', 'September'],
                                                    10 => ['أكتوبر', 'October'],
                                                    11 => ['نوفمبر', 'November'],
                                                    12 => ['ديسمبر', 'December']
                                                ];
                                            ?>

                                            @for ($i = 0; $i < 12; $i++)
                                                <?php
                                                    $mIndex = $i;
                                                    
                                                    // بيانات السنة السابقة
                                                    $lastYearCount = $data[2][$mIndex] ?? 0;
                                                    $lastYearTotal = round(
                                                        ($data[3][$mIndex]['total_cash'] ?? 0) + 
                                                        ($data[3][$mIndex]['total_bank'] ?? 0) + 
                                                        ($data[3][$mIndex]['total_credit'] ?? 0) + 
                                                        ($data[3][$mIndex]['total_transfer'] ?? 0), 2
                                                    );

                                                    // بيانات السنة الحالية
                                                    $currentYearCount = $data[0][$mIndex] ?? 0;
                                                    $currentYearTotal = round(
                                                        ($data[1][$mIndex]['total_cash'] ?? 0) + 
                                                        ($data[1][$mIndex]['total_bank'] ?? 0) + 
                                                        ($data[1][$mIndex]['total_credit'] ?? 0) + 
                                                        ($data[1][$mIndex]['total_transfer'] ?? 0), 2
                                                    );

                                                    // تجميع المجاميع
                                                    $totalLastCount += $lastYearCount;
                                                    $totalLastAmount += $lastYearTotal;
                                                    
                                                    $totalCurrentCount += $currentYearCount;
                                                    $totalCurrentAmount += $currentYearTotal;
                                                ?>
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $monthsNames[$i+1][0] }} {{ $monthsNames[$i+1][1] }}</td>
                                                    <td>{{ $lastYearCount }}</td>
                                                    <td>{{ $lastYearTotal }}</td>
                                                    <td>{{ $currentYearCount }}</td>
                                                    <td>{{ $currentYearTotal }}</td>
                                                </tr>
                                            @endfor

                                            <!-- صف الإجمالي العام -->
                                            <tr style="font-weight: bold; background-color: #f5f6fb;">
                                                <td colspan="2" class="text-left">الإجمالي العام (TOTAL)</td>
                                                <td>{{ $totalLastCount }}</td>
                                                <td>{{ $totalLastAmount }} SAR</td>
                                                <td>{{ $totalCurrentCount }}</td>
                                                <td>{{ $totalCurrentAmount }} SAR</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br><br>
                                <p>{{__('home.employeereciver')}} : {{Auth()->user()->name}}</p>
                                <br>
                                <p>{{__('home.thesignature')}} : </p>
                                </div>

                        <hr class="mg-b-40">

                        <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i
                                class="mdi mdi-printer ml-1"></i>{{__('home.print')}}</button>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
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