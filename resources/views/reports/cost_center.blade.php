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
{{ __('home.cost_center') }}
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
    
      <h6 style="color: black" class="invoice-title">{{ __('home.cost_center') }}</h6>

    
</center>

                            <div class="table-padding table-responsive ">
                                
                               
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $start }}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ $end}}</label>
                                            </th>


                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.cost_center') }} </label>

                                            </th>
                                            <?php
                                            if($cost_center=='-'){
                                                $name_cost='-';
                                            }else{
                                                
                                                   $data_cost=App\Models\Cost_centers::find($cost_center);
                                            
                                           $name_cost=App::getLocale()=='ar'?$data_cost->cost_center_ar:$data_cost->cost_center_en;                            
                                           
                                            }
                                         ?>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $name_cost}}</label>
                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} </label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>
                                            </th>
                                        </tr>


                                    </thead>
                                </table>
                            </div>
                            <br>
                          
     <br>
                        <div class="">
                            <div class="card-body">
                                <div class="">
                                    <div class="">
                                        <table class="table table-hover table-bordered table-striped text-cneter ">

                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.exportTime') }}</th>
                                                    <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                    <th class="border-bottom-0"> {{ __('users.branch') }}</th>
                                                                                                        <th class="border-bottom-0"> {{ __('home.cost_center') }}</th>

                                                    <th class="border-bottom-0"> {{ __('home.expenseresonen') }}</th>
                                                    <th class="border-bottom-0"> {{ __('home.employee') }}</th>
                                                    <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.credit') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.debit') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.notesClient') }}</th>
                                                </tr>

                                            </thead>
                                            <?php
                                            $i = 1;
                                            $total_credit=0;
                                            $total_debit=0;
                                            $end_blance=0;
                                            ?>
                                          
                                            <tbody>
                                          
                                            
                                               
                                                    @foreach ($data as $item)
                                                      <?php
                                                      
                                                      $total_credit+=$total_credit+$item->creditor  ;
                                                      $total_debit=$total_debit+ $item->debtor;
                                                      $end_blance=$end_blance+$item->recive_amount;
                                            $i++;
                                            ?>
                                                <tr>

                                                    <td>{{ $i }}</td>
                                                    <td>{{ $item->sent_serf_count}}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->date_export}}</td>
                                                    <td>{{ $item->branch->name }}</td>
                                                    <td>{{ App::getLocale()=='ar'?$item->cost_center_data->cost_center_ar:$item->cost_center_data->cost_center_en   }}</td>
                                                    <td>{{ $item->financial_accounts_data->name }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>{{ $item->recive_amount}}</td>
                                                    <td>{{ round( $item->debtor,2) }}</td>
                                                    <td>{{ round($item->creditor,2) }}</td>
                                                  <td>{{$item->note }}</td>

                                               
                                                </tr>
                                                  @endforeach
           <tr>

                                                    <td>{{ $i }}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{$end_blance}}</td>
                                                    <td>{{ round($total_debit,2)}}</td>
                                                    <td>{{ round($total_credit,2)}}</td>
                                                     <td>-</td>

                                               
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>


                                    </div>
                                </div>

                                <br>



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