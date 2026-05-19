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
                            $banktransfertotal=0;
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
<center> <h3>
تقرير مبيعات السنة الحالية Sales report for the current year</h3> </center>
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
                                       
  <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ date('Y') . '-01' . '-01'}}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ date('Y') . '-12' . '-31' }}</label>
                                            </th>
                                          
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} </label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>
                                            </th>
                                        </tr>


                                    </thead>
                                </table>
                            </div>
                           <?php
                           
                           $totalcount=0;
                           $totalamount=0;
                           
                           
                           ?>
                        <div class="card-body">
                        <br><br><br><br>
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center">
										<thead>
										    
										    		<tr>
                                    <th class="border-bottom-0">    NO   </th>
                                    <th class="border-bottom-0">   الشهر MONTH  </th>
                                    <th>عدد الفواتير  Number of invoices</th>
                             
                                    <th>الاجمالي TOTAL(SAR)</th>
                                    </tr>   
                                    
                                    
                                    
                                    
									<tr>
                                    <th class="border-bottom-0">    1   </th>
                                    <th class="border-bottom-0">   يناير  January</th>
                                    <th>{{$data[0][0]+$data[2][0]}}</th>
                                    <?php
$total =round( $data[1][0]['total_cash'] +$data[1][0]['total_bank'] +$data[1][0]['total_credit'] +$data[1][0]['total_transfer'] +$data[3][0]['total_cash'] +$data[3][0]['total_bank'] +$data[3][0]['total_credit'] +$data[3][0]['total_transfer'] ,2);

$totalamount+=$total;
$totalcount+=$data[0][0]+$data[2][0];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    2   </th>
                                    <th class="border-bottom-0">   فبراير   February  </th>
                                    <th>{{$data[0][1]+$data[2][1]}}</th>
                                    <?php
$total =round( $data[1][1]['total_cash'] +$data[1][1]['total_bank'] +$data[1][1]['total_credit'] +$data[1][1]['total_transfer'] +$data[3][1]['total_cash'] +$data[3][1]['total_bank'] +$data[3][1]['total_credit'] +$data[3][1]['total_transfer'] ,2);

$totalamount+=$total;
$totalcount+=$data[0][1]+$data[2][1];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    3   </th>
                                    <th class="border-bottom-0">     مارس   March</th>
                                    <th>{{$data[0][2]+$data[2][2]}}</th>
                                    <?php
$total =round( $data[1][2]['total_cash'] +$data[1][2]['total_bank'] +$data[1][2]['total_credit'] +$data[1][2]['total_transfer'] +$data[3][2]['total_cash'] +$data[3][2]['total_bank'] +$data[3][2]['total_credit'] +$data[3][2]['total_transfer'] ,2);

$totalamount+=$total;
$totalcount+=$data[0][2]+$data[2][2];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    4   </th>
                                    <th class="border-bottom-0">     أبريل   April</th>
                                    <th>{{$data[0][3]+$data[2][3]}}</th>
                                    <?php
$total =round( $data[1][3]['total_cash'] +$data[1][3]['total_bank'] +$data[1][3]['total_credit'] +$data[1][3]['total_transfer'] +$data[3][3]['total_cash'] +$data[3][3]['total_bank'] +$data[3][3]['total_credit'] +$data[3][3]['total_transfer'] ,2);

$totalamount+=$total;
$totalcount+=$data[0][3]+$data[2][3];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    
                                    
                                    
                  
                                    
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    5   </th>
                                    <th class="border-bottom-0">   مايو   May  </th>
                                    <th>{{$data[0][4]+$data[2][4]}}</th>
                                    <?php
$total =round( $data[1][4]['total_cash'] +$data[1][4]['total_bank'] +$data[1][4]['total_credit'] +$data[1][4]['total_transfer'] +$data[3][4]['total_cash'] +$data[3][4]['total_bank'] +$data[3][4]['total_credit'] +$data[3][4]['total_transfer'] ,2);

$totalamount+=$total;
$totalcount+=$data[0][4]+$data[2][4];


                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    6   </th>
                                    <th class="border-bottom-0">   يونيو   June  </th>
                                    <th>{{$data[0][5]+$data[2][5]}}</th>
                                    <?php
$total =round( $data[1][5]['total_cash'] +$data[1][5]['total_bank'] +$data[1][5]['total_credit'] +$data[1][5]['total_transfer'] +$data[3][5]['total_cash'] +$data[3][5]['total_bank'] +$data[3][5]['total_credit'] +$data[3][5]['total_transfer'] ,2);
$totalamount+=$total;
$totalcount+=$data[0][5]+$data[2][5];

                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    7   </th>
                                    <th class="border-bottom-0">   يوليو July  </th>
                                    <th>{{$data[0][6]+$data[2][6]}}</th>
                                    <?php
$total =round( $data[1][6]['total_cash'] +$data[1][6]['total_bank'] +$data[1][6]['total_credit'] +$data[1][6]['total_transfer'] +$data[3][6]['total_cash'] +$data[3][6]['total_bank'] +$data[3][6]['total_credit'] +$data[3][6]['total_transfer'] ,2);
$totalamount+=$total;
$totalcount+=$data[0][6]+$data[2][6];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    8   </th>
                                    <th class="border-bottom-0">   أغسطس  August  </th>
                                    <th>{{$data[0][7]+$data[2][7]}}</th>
                                    <?php
$total =round( $data[1][7]['total_cash'] +$data[1][7]['total_bank'] +$data[1][7]['total_credit'] +$data[1][7]['total_transfer'] +$data[3][7]['total_cash'] +$data[3][7]['total_bank'] +$data[3][7]['total_credit'] +$data[3][7]['total_transfer'] ,2);

$totalamount+=$total;
$totalcount+=$data[0][7]+$data[2][7];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    9   </th>
                                    <th class="border-bottom-0">     سبتمبر   September </th>
                                    <th>{{$data[0][8]+$data[2][8]}}</th>
                                    <?php
$total =round( $data[1][8]['total_cash'] +$data[1][8]['total_bank'] +$data[1][8]['total_credit'] +$data[1][8]['total_transfer'] +$data[3][8]['total_cash'] +$data[3][8]['total_bank'] +$data[3][8]['total_credit'] +$data[3][8]['total_transfer'] ,2);

$totalamount+=$total;
$totalcount+=$data[0][8]+$data[2][8];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    10   </th>
                                    <th class="border-bottom-0">   أكتوبر   October  </th>
                                    <th>{{$data[0][9]+$data[2][9]}}</th>
                                    <?php
$total =round( $data[1][9]['total_cash'] +$data[1][9]['total_bank'] +$data[1][9]['total_credit'] +$data[1][9]['total_transfer'] +$data[3][9]['total_cash'] +$data[3][9]['total_bank'] +$data[3][9]['total_credit'] +$data[3][9]['total_transfer'] ,2);
$totalamount+=$total;
$totalcount+=$data[0][9]+$data[2][9];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    11   </th>
                                    <th class="border-bottom-0">   نوفمبر  November  </th>
                                    <th>{{$data[0][10]+$data[2][10]}}</th>
                                    <?php
$total =round( $data[1][10]['total_cash'] +$data[1][10]['total_bank'] +$data[1][10]['total_credit'] +$data[1][10]['total_transfer']  +$data[3][10]['total_cash'] +$data[3][10]['total_bank'] +$data[3][10]['total_credit'] +$data[3][10]['total_transfer'],2);
$totalamount+=$total;
$totalcount+=$data[0][10]+$data[2][10];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                    
                                    
                                    
                                    							<tr>
                                    <th class="border-bottom-0">    12   </th>
                                    <th class="border-bottom-0">   ديسمبر  December  </th>
                                    <th>{{$data[0][11]+$data[2][11]}}</th>
                                    <?php
$total =round( $data[1][11]['total_cash'] +$data[1][11]['total_bank'] +$data[1][11]['total_credit'] +$data[1][11]['total_transfer'] +$data[3][11]['total_cash'] +$data[3][11]['total_bank'] +$data[3][11]['total_credit'] +$data[3][11]['total_transfer'] ,2);
$totalamount+=$total;
$totalcount+=$data[0][11]+$data[2][11];
                                    ?>
                                    <th>{{$total}}</th>
                                    </tr>   
                                    
                                
                                    							<tr>
                                    <th class="border-bottom-0">    -   </th>
                                    <th class="border-bottom-0"> الاجمالي TOTAL  </th>
                                    <th>{{$totalcount}}</th>
                                    <th>{{$totalamount }} SAR</th>
                                    </tr>   
                                    
										</thead>

									</table>
                                    <br>
                                    <br>
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
