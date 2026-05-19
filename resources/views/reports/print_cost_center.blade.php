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
{{ __('home.Customer_debt_restructuring') }}
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
    
      <h6 style="color: black" class="invoice-title">{{ __('home.Customer_debt_restructuring') }}</h6>

    
</center>

                            <div class="table-padding table-responsive ">
                                
                               
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                               
                                    <col style="width:20%">
                                    <thead>
                                        <tr>
                                          
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
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0"> اسم العميل  <br>  Client Name</th>
                                    <th class="border-bottom-0"> اخر سداد  <br>  Last payment</th>
                                    <th class="border-bottom-0">الرصيد <br> Blance</th>
                                    <th class="border-bottom-0">عمر الدين <br> omer al-dain</th>
                                    <th class="border-bottom-0" class="border-bottom-0">0 : 10</th>
                                    <th class="border-bottom-0" class="border-bottom-0">11  : 30 </th>
                                    <th class="border-bottom-0" class="border-bottom-0">31 : 60</th>
                                    <th class="border-bottom-0" class="border-bottom-0">61 : 90</th>
                                    <th class="border-bottom-0" class="border-bottom-0">91 : 120</th>
                                    <th class="border-bottom-0" class="border-bottom-0">121 : 180</th>
                                    <th class="border-bottom-0" class="border-bottom-0">اكبر من 180 <br>  More then 180</th>
                                    <th class="border-bottom-0" class="border-bottom-0">الاجمالي <br>  Total</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php $i = 0 ?>
                                @foreach ($data as $item)
                                <?php $i++ ;

                                ?>

                                <tr>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$i}}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['name']}}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['lastdate']??'' }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['crrunt_balence']??'' }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['life_creadit'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_0_t_10'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_11_t_30'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_31_t_60'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_61_t_90'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_91_t_120'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_121_t_180'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_181_t_00'] }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$item['f_0_t_10']+$item['f_11_t_30'] +$item['f_31_t_60']+$item['f_61_t_90']+$item['f_91_t_120']+$item['f_121_t_180']+$item['f_91_t_120'] }}</td>
                                  

                                </tr>


                                @endforeach
                            </tbody>
                                                </table>












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