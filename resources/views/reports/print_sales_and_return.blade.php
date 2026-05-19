@extends('layouts.master')
@section('css')
<style>
    @media print {
        -webkit-print-color-adjust: exact; 
   .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 200px;
      font-size: 15px !important;

    }

   @page
        {
           
            size: auto; /* auto is the initial value */
            margin: 2mm 2mm 2mm 2mm; /* this affects the margin in the printer settings */
                font-size:28px;

        }
 .text{
  width: 320px;
  overflow: hidden;
    white-space: pre-wrap;
  text-overflow: ellipsis
        }
        .row-sm{
             dir:"rtl"
        }
         .tx-18{
                            font-size:15px!important;

        }
         .tx-16{
                            font-size:12px!important;

        }
            .double{
            border: 3px solid grey;
            border-radius: 5px;
            width:200px;

        }
    tr {
       bgcolor="grey"
        }

        header
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }
        #print_Button {
            display: none;
        }
  th {
    /*background-color: grey;*/
    font-size:22px;
    }
    }

  
     .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 200px;
      font-size: 15px !important;

    }

    .textend{
        hidden
    }
</style>
@endsection
@section('title')
{{ __('home.sales_and_return') }}
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
               
    <center>    <span class='double'>   {{__('home.sales_and_return')}} </span></center> 



       <div class="table-padding table-responsive ">
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                                    <col style="width:25%">
                                    <col style="width:25%">
                                    <col style="width:25%">
                                    <col style="width:25%">

                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $start }}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ $end }}</label>
                                            </th>




                                    </thead>
                                </table>
                            </div>
                            
                            

    <center>    <span class='double'>   {{__('home.sales')}} </span></center> 



                    <div style="border-radius: 10px" class="card-body mt-5">
                    <div class="table-striped">
                     
                        <?php
                        $userId = 0;
                        ?>
                        <?php
                        $totalprice=0;
                        $userId = 0;
                        $startat = '';
                        $endat = '';
                        $totalpriceallbefordiscount = 0;
                        $totaladdedvalue = 0;
                        $totaldiscount = 0;
                        $totalpriceafterdiscount = 0;
                        $discountreturn=0;
                        ?>



                        </span>
                            <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered" id="example1" data-page-length='50' style=" text-align: center; width:95%">
                          
                                    <thead>
                                        <tr>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.clietName') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.discount') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.totalafterdiscount') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.avt') }}</th>
                                            <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.totalwithTax') }}</th>



                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;
                                        $total = 0;
                                                                                $totaldiscount =0;
?>

                                        @foreach ($data['invoices'] as $product)
                                        <?php

                                        $i = 0;


                                        $endat = $product->created_at;
                                        $avt = App\Models\Avt::find(1);
                                        $saleavt = $avt->AVT;
                                        $totalPriceDay = ($product->cashamount + $product->bankamount + $product->Bank_transfer+ $product->creaditamount);

                                        ?> <?php $i++; ?>

                                        <tr >
                                            <td data-target="id">{{ $product->id }}</td>
                                            <td data-target="numberofpice">{{ $product->created_at }}</td>

                                            <td dir="ltr" data-target="id">
                                                {{ $product->customer->name }}
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
                                        
                                        
                                            <?php
                                        $discountreturn=0;
                                         $discoundoninvoice=0;
                                        if($product->Number_of_Quantity==0){
                                              foreach (App\Models\return_sales::where('invoice_id', $product->id)->get() as $item){
                                                
                                                $discoundoninvoice+=$item->discountoninvoice;

                                               }
                                        }else{
                                        $discoundoninvoice=$product->discount-$product->discountOnProduct;
                                                    
                                        }

                                               $quentity=0;
                                               foreach (App\Models\return_sales::where('invoice_id', $product->id)->get() as $item){
                                                
                                                $discountreturn+= $item->discountvalue;
                                                $quentity+=$item->return_quantity;
                                               }
                                             foreach (App\Models\sales::where('invoice_id', $product->id)->get() as $item){
                                                
                                                $discountreturn+= ($item->Discount_Value);
                                                $quentity+=$item->quantity;

                                               }
                                            $discountreturn+=$discoundoninvoice;

                 $price=$product->cashamount+$product->Bank_transfer+$product->bankamount+$product->creaditamount;
                 $avt = App\Models\Avt::find(1);
                   $totalprice+=$price;
                  $price_befor_tax=$price*100/(100+($avt->AVT*100));
                  $invoicetotal_addedvalue = ($price_befor_tax )* $avt->AVT;
                  $invoicetotal_price = $price_befor_tax;
                  $invoicetotal_discount = $discountreturn;
                  $totaldiscount+=  $invoicetotal_discount ;
                  $totaladdedvalue+=$invoicetotal_addedvalue   ;
                  $totalpriceafterdiscount+=$price_befor_tax   ;

                                            
                                            ?>
<td>{{number_format((float)(round($invoicetotal_price,2)+round($invoicetotal_discount,2)), 2, '.', '')}}</td>
<td>{{number_format(round($invoicetotal_discount,2), 2, '.', '')}}</td>
<td>{{number_format(round($invoicetotal_price,2), 2, '.', '')}}</td>
<td>{{number_format(round($invoicetotal_addedvalue,2), 2, '.', '')}}</td>
<td>{{number_format($price, 2, '.', '')}}</td>
                                   

                                        </tr>
                                     
                                        @endforeach
                                        
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                                                           
                                            <td>-</td>
                                            <td>{{number_format($totaldiscount+$totalpriceafterdiscount, 2, '.', '')}}</td>
                                            <td>{{number_format($totaldiscount, 2, '.', '')}}</td>
                                            <td>{{number_format($totalpriceafterdiscount, 2, '.', '')}}</td>
                                            <td>{{number_format($totaladdedvalue , 2, '.', '')}}</td>
                                            <td>{{number_format(round($totalprice ,2), 2, '.', '') }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    
                        <br>
    <center>    <span class='double'>   {{__('home.salesـreturned')}} </span></center> 
                        <br>
                        <br>


<?php
$Invoices=$data['returnsales'];
?>
 
                @if (isset($Invoices))
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
                    $invoiceIds = [];
                    $totalpricefinal = 0;
                    ?>
                <div >
              
                          <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered" id="example1" data-page-length='50' style=" text-align: center; width:95%">
                          
                                   <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('report.date') }}</th>
                                    <th>{{ __('report.invoiceNo') }}</th>
                                    <th>-</th>
                                    <th>{{ __('home.paymentmethod') }} </th>
                                    <th> {{ __('home.total') }}</th>
                                    <th> {{ __('home.quantity') }}</th>
                                    <th>{{ __('home.operations') }}</th>

                                </tr>
                            </thead>
                            <?php
                            $avt = App\Models\Avt::find(1);
                            $saleavt = $avt->AVT;
                            ?>
                            @foreach ($Invoices as $invoice)
                            <?php

                            if ($count == 0) {
                                $startat = $invoice->created_at;
                            }
                            $endat = $invoice->created_at;
                            $count++;
                            ?>

                     
                            @if (!in_array($invoice->invoice_id, $invoiceIds))
                            <?php
                            $invoiceIds[] = $invoice->invoice_id;
                            ?>
                            @endif
                            @endforeach


                            <?php
                            $returnsales = 0;
                            $totalprice = 0;

                            ?>


                            <tbody>
                                @foreach ($invoiceIds as $invoiceid)

                                <?php
                                                                        $datainvoice=App\Models\invoices::find($invoiceid);

                                $Invoices = App\Models\return_sales::where('invoice_id', $invoiceid)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get();
                                $date = 0;
                                $numberofPice = 0;
                                $total_addedvalue = 0;
                                $eachreturn = 0;
                                foreach ($Invoices as $invoice) {
                                    $eachreturn+= ($invoice->return_Unit_Price * $invoice->return_quantity) - $invoice->discountvalue - $invoice->discountoninvoice;
                                    $Pay = $invoice->Invoice->Pay;
                                    $date = $invoice->created_at;
                                    $numberofPice += $invoice->return_quantity;
                                    $total_addedvalue += ($invoice->return_Unit_Price * $invoice->return_quantity) - $invoice->discountvalue - $invoice->discountoninvoice;
                                    $totalprice += ($invoice->return_Unit_Price * $invoice->return_quantity) - $invoice->discountvalue - $invoice->discountoninvoice;
                                }
                                $i++;
                                $date = explode(' ', $date);
                                $eachreturn += $total_addedvalue * $saleavt;
                                $totalprice += $total_addedvalue * $saleavt;
                                ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $date[0] }}</td>
                                    <td>{{ $invoiceid }}</td>
                                            <td>{{$datainvoice->customer->name}}</td>
                                    <td>


                                        @if ($Pay == 'Cash')

                                        <span style="color:blue !important" class="text-success">{{ __('report.cash') }}</span>
                                        @elseif($Pay == 'Credit')
                                        <span class="text-danger">{{ __('report.credit') }}</span>
                                        @elseif($Pay == 'Bank_transfer')
                                        <span class="text-danger">{{ __('home.Bank_transfer') }}</span>
                                        @else
                                        <span class="text-warning">{{ __('report.shabka') }}</span>
                                        @endif
                                    </td>
                                    <td>{{round($eachreturn,2)}}</td>
                                    <td>{{ $numberofPice }}</td>

                                    <td> <a style="color: black" class="dropdown-item" href="Show_return_Sales_Details/{{ $invoiceid }}"><i class="text-black fas fa-print"></i>&nbsp;&nbsp;
                                            {{ __('home.show') }}
                                        </a></td>
                                </tr>
                                @endforeach

                            </tbody>

                        </table>



                                   <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered" id="example1" data-page-length='50' style=" text-align: center; width:95%">
                          

                                <tbody>

                                    <tr>
                                        <td>{{ __('report.totalallprice') }}</td>

                                        <td>{{ round($totalprice,2) }}</td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        
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