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
{{ __('home.profit_and_lost') }}
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
<div class="row justify-content-center">    

     <div class="d-flex ">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button"
                                onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                              
                             
                        </div>
                        
                  

</div>
                   
                        <br>

                        <div class="card-body pt-3">
                            <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">

                                <div class="billed-from" style="width:33%;text-align: center;">
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
                                    <a href="https://ebdeasoft.com/"><img
                                            src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo"
                                            style="width: 110px; height: 70px;"></a>

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

                                <h6 style="color: black" class="invoice-title">{{ __('home.profit_and_lost') }}</h6>


                            </center>

                            <div class="table-padding table-responsive ">


                                <table style="border: 2px solid rgba(0,0,0,0)"
                                    class="table table-striped table-bordered text-center my-2">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1">
                                                    {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> {{ $start }}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>
                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1">{{ $end }}</label></th>


                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> {{ __('home.branch') }} </label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> {{ $branch }}</label>
                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> {{ __('home.exportTime') }}
                                                </label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> {{ $currentdata }}</label>
                                            </th>
                                        </tr>


                                    </thead>
                                </table>
                            </div>

                            <hr class="mg-b-40">

                            <div class="table-padding table-responsive ">


                                <table style="border: 2px solid rgba(0,0,0,0)"
                                    class="table table-striped table-bordered text-center my-2">
                                    <col style="width:30%">
                                    <col style="width:20%">
                                    <col style="width:30%">
                                    <col style="width:20%">

                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1">التكاليف والمصاريف Costs and
                                                    expenses</label>

                                            </th>
                                            <th>-</th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> الايردات Revenue </label>
                                            </th>


                                        </tr>


                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>المصاريف - expenses</td>
                                            <td>{{$data['expense']}}</td>
                                            <td>ايردات المبيعات  Sales revenue  </td>
                                            <td>{{$data['sales']}}</td>
                                        </tr>

                                        <tr>
                                            <td> تكاليف المبيعات SALES costs</td>
                                            <td>{{$data['purchase']}}</td>
                                            <td> ايردات مرتجع المشتريات Purchase returns</td>
                                            <td>{{$data['purchase_return']}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td>تكاليف مرتجع المبيعات Sales return costs</td>
                                            <td style="color:red">(-{{$data['sales_return_cost']}})</td>
                                            <td>  ايردات بيع الاصول Selling assets</td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td> مرتجع المبيعات Sales return </td>
                                            <td style="color:red">(-{{$data['sales_return']}})</td>
                                        </tr>
                                           <tr>
                                            <td>تكاليف اخري Other costs </td>
                                            <td>0</td>
                                            <td>  ايردات اخري  Other revenues </td>
                                            <td>0</td>
                                        </tr>
                                        <?php
                                        $total_expense=$data['purchase']+$data['expense']-$data['sales_return_cost'];
                                        $total_incoming=$data['purchase_return']+$data['sales']-$data['sales_return'];
                                        $profit_final_value=$total_incoming-$total_expense;
                                        $string_value=$profit_final_value>0?'صافي الربح  Net profit':'صافي الخسارة Net loss';
                                        ?>
                                        <tr>
                                            <td>الاجمالي  TOTAL</td>

                                            <td>  {{   $total_expense}} </td>
                                            <td>الاجمالي  TOTAL</td>

                                            <td>  {{    $total_incoming}} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-padding table-responsive ">


                                <table style="border: 2px solid rgba(0,0,0,0)"
                                    class="table table-striped table-bordered text-center my-2">
                                    <col style="width:15%">
                                    <col style="width:15%">

                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1"> {{ $string_value}}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label
                                                    style="font-size: 14px;color:#419BB2 ;font-weight:bold;"
                                                    for="exampleFormControlSelect1">
                                                    {{$profit_final_value>0? $profit_final_value:$profit_final_value*-1}}</label>
                                            </th>


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