@extends('layouts.master')

@section('css')
<style>
    /* --- تحسينات المظهر العام للشاشة --- */
    .double {
        border: 2px solid #419BB2;
        border-radius: 8px;
        padding: 5px 30px;
        display: inline-block;
        font-size: 18px !important;
        font-weight: bold;
        color: #419BB2;
        background-color: #f8f9fa;
        margin-bottom: 20px;
        text-align: center;
    }

    .table-responsive {
        border: none !important;
    }

    .card-invoice {
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-radius: 10px;
        padding: 20px;
    }

    /* --- تحسينات الطباعة --- */
    @media print {
        body {
            background-color: white !important;
            direction: rtl !important;
        }

        #print_Button, .main-footer, .breadcrumb-header {
            display: none !important;
        }

        /* إخفاء عمود "العمليات" في الطباعة فقط ليبقى التقرير احترافياً */
        #example1 th:last-child, 
        #example1 td:last-child {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .main-content-body-invoice {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        @page {
            size: A4;
            margin: 1.5cm;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #333 !important; /* حدود واضحة للحبر */
        }

        -webkit-print-color-adjust: exact;
    }

    /* تنسيقات إضافية للجداول */
    .table thead th {
        background-color: #f2f7f9 !important;
        color: #419BB2 !important;
        font-weight: bold;
    }

    .invoice-header p {
        margin-bottom: 5px;
        line-height: 1.4;
    }
</style>
@endsection
@section('title')
{{ __('home.print') }}
@stop
@section('page-header')
<!-- breadcrumb -->

<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row row-sm"  >
   <div class="col-md-12 col-xl-12">
      <div class=" main-content-body-invoice" id="print"  dir=rtl>
         <div class="card card-invoice">
                <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

            <div class="card-body">

               <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%" dir="rtl">
            
                   <div class="billed-from" style="width:33%;text-align: center;" >
                     <br>

                     <span style="font-size:16px">{{Namear}}</span>
                     <br>
                     <p> {{describtionar}}</p>
                     <p>{{STar}}</p>
                     <p>{{Taxar}}</p>

                  </div><!-- billed-from -->
                 
                  <div class="row">
                     <?php
                     $logo = camplogo;
                     ?>
                     <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>

                  </div>
      <div class="billed-from" style="width:33%;text-align: center;">
                     <br>
                     <span style="font-size:19px">{{Nameen}}</span>
                     <br>
                     <p dir=rtl> {{describtionen}} </p>
                     <span dir=rtl>{{STen}} </span>
                     <p dir=rtl> {{Taxen}} </p>

                  </div>
                  

               </div><!-- invoice-header -->
               
    <center>    <span class='double'>التسليمات     </span></center> 


       <div class="table-padding table-responsive ">
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                                    <col style="width:25%">
                                    <col style="width:25%">
                                    <col style="width:25%">
                                    <col style="width:25%">

                                    <thead>
                                        <tr>
                                        <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.branch') }}:</label>

</th>                                   
         <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $branch}}</label>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $start}}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ $end }}</label>
                                            </th>




                                    </thead>
                                </table>
                            </div>
                            <div class="table-striped">
                        @if (isset($Invoices))
                        <?php
                        $userId = 0;
                        $count = 0;
                        ?>
                        <?php
                        $userId = 0;
                        $startat = '';
                        $endat = '';
                        $totalpriceall = 0;
                        $totaladdedvalue = 0;
                        $totaldiscount = 0;
                        $totalpricefinal = 0;
                        ?>


                        <br>




                        <br>

                        </span>
                            <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered" id="example1" data-page-length='50' style=" text-align: center; width:95%">
                          
                                    <thead>
                                        <tr>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.sallerName') }} </th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.clietName') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.operations') }}</th>



                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;
                                        $total = 0; ?>

                                        @foreach ($Invoices as $product)
                                        <?php
                                        $totaldiscount += $product->discount;

                                        $i = 0;

                                        $totalpriceall += $product->cashamount + $product->bankamount + $product->Bank_transfer;
                                        if ($count == 0) {
                                            $userId = $product->user_id;
                                            $startat = $product->created_at;
                                        }
                                        $endat = $product->created_at;
                                        $count++;
                                      
                                        $totalPriceDay = ($product->cashamount + $product->bankamount + $product->Bank_transfer+ $product->creaditamount);

                                        ?> <?php $i++; ?>

                                        <tr id="<?php echo $product['id']; ?>">
                                            <td data-target="id">{{ $product->id }}</td>
                                            <td data-target="id">{{ $product->user->name }}</td>
                                            <td dir="ltr" data-target="id">
                                                {{ $product->customer->name }}
                                            </td>
                                            <td data-target="numberofpice">{{ $product->created_at }}</td>
                                            <td data-target="numberofpice">{{ $product->branch->name }}
                                            </td>
                                            <td data-target="numberofpice">
                                                <?php
                                                $avt = App\Models\Avt::find(1);
                                                $saleavt = $avt->AVT;
                                                ?>
                                                {{ round ($product->cashamount + $product->bankamount+$product->Bank_transfer+$product->creaditamount,2) }}
                                            </td>

                                            <?php
                                            $pays = '';
                                            if ($product->Pay == 'Cash') {
                                                $pays = __('report.cash');
                                            } elseif ($product->Pay == 'Shabka') {
                                                $pays = __('report.shabka');
                                            } elseif ($product->Pay == "Credit") {
                                                $pays = __('report.credit');
                                            } elseif ($product->Pay == "Bank_transfer") {
                                                $pays = __('home.Bank_transfer');
                                            } else {
                                                $pays = __('home.Partition of the amount');
                                            }

                                            ?>
                                            <td data-target="numberofpice">{{ $pays }}</td>
                                            <td> 
                                            <a style="color: #23395D" class="dropdown-item" href="showInvoiceRecentdelivery/{{ $product->id }}"><i style="fill:#072c3c !important" class=" fas fa-print"></i>&nbsp;&nbsp;
                        {{ __('home.show') }}
                    </a> 
                                            
                                            
                                          </td>

                                        </tr>
                                        <?php
                                        $total += ($product->cashamount + $product->bankamount + $product->Bank_transfer + $product->creaditamount);
                                        ?>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="table-padding">
                            <table class="table table-bordered table-hover text-center table-striped mt-5">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">{{ __('report.totalprice') }}</th>
                                        <th>{{ __('home.the amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>{{ __('report.totalallprice') }}</td>
                                        <td>{{round($total ,2) }}</td>
                                    </tr>
                               

                                </tbody>
                            </table>
                        </div>

                        <br>
@endif

                  

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