@extends('layouts.master')
@section('css')
<style>
     @media print {
         .text{
  width: 320px;
  overflow: hidden;
    white-space: pre-wrap;
  text-overflow: ellipsis
        }
      
        
          @page
        {
            size: auto; /* auto is the initial value */
            margin: 5mm 2mm 18mm 2mm; /* this affects the margin in the printer settings */
                font-size:28px;

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
    }

   body {
      font: 13pt Georgia, "Times New Roman", Times, serif;
      line-height: 1.5;
      /*border-style: solid;*/

   }
</style>
@endsection
@section('title')
{{ __('home.print') }}
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
      <div class=" main-content-body-invoice" id="print">
         <div class="card card-invoice">
                <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

            <div class="card-body">

               <br>
               <div class="d-flex justify-content-center">
                  <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                     {{ __('home.print') }}
                     <i class="mdi mdi-printer ml-1"></i>
                  </button>
               </div>
               <br><!-- invoice-header -->
               <?php
               $Invoices = $data['invoices'];
                function ConvertToHEX($value)
                   {
                       return pack("H*", sprintf("%02X", $value));
                   }
                   
                   $coun=0;
                   $y=count($Invoices);
               ?>


               @if (isset($Invoices))
               <br>
               <br>






               @foreach ($Invoices as $invoice)


               <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">
                  <div class="billed-from" style="width:33%;text-align: center;">
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
                     $coun++;
                     ?>
                     <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>

                  </div>

                  <div class="billed-from" style="width:33%;text-align: center;">
                     <br>
                     <span style="font-size:19px">{{Nameen}}</span>
                     <br>
                     <p dir=ltr> {{describtionen}} </p>
                     <span dir=ltr>{{STen}} </span>
                     <p dir=ltr> {{Taxen}} </p>

                  </div>

               </div><!-- invoice-header -->
                         <center>
                  <p class="double"> {{ $invoice->branch->name}}</p>
               </center>


               <p>
                  <center> Tax Invoice - فاتورة ضريبية</center>
               </p>


               <span>{{__('home.notesClient')}} : {{$invoice->note}}</span>
               <br>
             
               <br>
               <div class='row' style="justify-content: space-around;">
                  <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                     <thead>
                        <tr class="row12">

                           <th class="tx-16">CLIENT NAME <br>اسم العميل </th>
                           <th class="tx-16">{{$invoice->customer->name}}</th>



                        </tr>

                        <tr>
                           <th class="tx-16">TAX NUMBER<br>
                              الرقم الضريبي </th>

                           <th class="tx-16">{{$invoice->customer->tax_no}}</th>
                        </tr>
                        <tr>

                           <th class="tx-16"> CLIENT ADDRESS<br>عنوان العميل </th>

                           <th class="tx-16">{{$invoice->customer->address}}</th>

                        </tr>
                     </thead>

                  </table>

                  <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                     <thead>
                        <tr>


                           <th class="tx-16">PAYMENT METHOD<br>طريقة الدفع </th>

                           <?php

                           $pay = '';
                           if ($invoice->Pay == "Cash") {
                              $pay = __('report.cash');
                           } elseif ($invoice->Pay == "Shabka") {
                              $pay = __('report.shabka');
                           } elseif ($invoice->Pay == "Credit") {
                              $pay = __('report.credit');
                           } elseif ($invoice->Pay == "Bank_transfer") {
                              $pay = __('home.Bank_transfer');
                           } else {
                              $pay = __('home.Partition of the amount');
                           }
                           ?>

                           <th class="tx-16">{{$pay}}</th>





                        </tr>

                        <tr>
                           <th class="tx-16"> INVOICE DATE<br>تاريخ الفاتورة </th>
                           <th class="tx-16">{{ $invoice->created_at}}</th>
                        </tr>
                        <tr>
                           <th class="tx-16"> INVOICE NUMBER<br>رقم الفاتورة</th>
                           <th class="tx-16">{{ $invoice->id}}</th>
                        </tr>
                     </thead>

                  </table>

               </div>

               <br>


           
            <div class="table-responsive mg-t-40">
               <table style="border:2px solid rgba(0,0,0,.3)" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                  <thead>
                     <tr>
                        <th class="wd-center">NO<br>رقم</th>
                        <th class="wd-center">Item NO <br> رقم منتج </th>
                        <th class="tx-center">ITEM NAME<br>اسم الصنف </th>
                        <th class="tx-center">PRODUCT PRICE<br>سعر القطعة </th>
                        <th class="tx-center"> QUANTITY <br>الكمية </th>
                        <th class="tx-center">TOTAL AMOUNT<br>الاجمالي</th>
                        <th class="tx-center"> DISCOUNT<br>الخصم </th>
                        <th class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</th>



                     </tr>
                  </thead>
                  <tbody>



                     <?php $i = 0;
                     $discountreturn=0;
                     $totaladdedvalue=0;
                     $totalreturnprice=0;
                     ?>

                     @foreach (App\Models\sales::where('invoice_id', $invoice->id)->get() as $product)
                     <?php $i++ ?>
                     @if($product->quantity!=0)
                     <tr>
                        <td class="wd-10p">{{$i}}</td>
                        <td class="wd-center" dir="ltr">{{$product->productData->Product_Code}}</td>
                        <td class="tx-center text">{{ $product->productData->product_name}}</td>
                        <td class="tx-center">{{ $product->Unit_Price}}</td>
                        <td class="tx-center">{{ $product->quantity}}</td>
                        <td class="tx-center">{{ $product->Unit_Price*$product->quantity}}</td>
                        <td class="tx-center">{{ $product->Discount_Value}}</td>
                        <td class="tx-center">{{ ($product->Unit_Price*$product->quantity)-$product->Discount_Value}}</td>

                     </tr>
                     @endif
                     @endforeach
                        @foreach (App\Models\return_sales::where('invoice_id', $invoice->id)->get() as $product)
                     <?php $i++;
                    //  $totalreturnprice+=$product->return_Unit_Price*$product->return_quantity;
                    //  $totaladdedvalue+=$product->return_Added_Value*$product->return_quantity;
                     $discountreturn+= $product->discountvalue+ $product->discountoninvoice;
                     ?>
                     @if($product->return_quantity!=0)
                     <tr>
                        <td class="wd-10p" style="color:red">{{$i}}</td>
                        <td class="wd-center" dir="ltr">{{$product->productData->Product_Code}}</td>
                        <td class="tx-center text">{{ $product->productData->product_name}}</td>
                        <td class="tx-center">{{ $product->return_Unit_Price}}</td>
                        <td class="tx-center">{{ $product->return_quantity}}</td>
                        <td class="tx-center">{{ $product->return_Unit_Price*$product->return_quantity}}</td>
                        <td class="tx-center">{{ $product->discountvalue}}</td>
                        <td class="tx-center">{{ ($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue}}</td>

                     </tr>
                     @endif
                     @endforeach



                  </tbody>
               </table>
            </div>
            <br>
            <br>
            <div class='row' style="justify-content: space-around;">
             <div>
                  <?php
                  $price=$invoice->cashamount+$invoice->Bank_transfer+$invoice->bankamount+$invoice->creaditamount;
                                    $avt = App\Models\Avt::find(1);

                  $price_befor_tax=$price*100/(100+($avt->AVT*100));
                  $invoicetotal_addedvalue = ($price_befor_tax )* $avt->AVT;
                  $invoicetotal_price = $price_befor_tax;
                  $invoicetotal_discount = $invoice->discount+$discountreturn;



                  
                   $sellerName = sallerQrCode;
                   $varNumber = TaxQrCode;
                   $time = $invoice->created_at;
$issue_time=substr($time, 11);
                   $issue_date=substr($time, 0, 10);
                   $time = (string)$issue_date . 'T' . (string)$issue_time;

                   $total = (round($invoicetotal_addedvalue + $invoicetotal_price, 2));
                   $tax = round($invoicetotal_addedvalue, 2);
                   $HexSeller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName));
                   $seller  =  $HexSeller . $sellerName;
                   $HexVAT  = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber));
                   $vat  = $HexVAT . $varNumber;
                   $HexTime = ConvertToHEX(3) . ConvertToHEX(strlen($time));
                   $time  = $HexTime . $time;
                   $HexTotal = ConvertToHEX(4) . ConvertToHEX(strlen($total));
                   $total  = $HexTotal . $total;
                   $HexVATN = ConvertToHEX(5) . ConvertToHEX(strlen($tax));
                   $VATN  = $HexVATN . $tax;

 $empty='';
                 $Hexempty = ConvertToHEX(6) . ConvertToHEX(strlen($empty));
                 $empty6 = $Hexempty . $empty;
                 $Hexempty = ConvertToHEX(7) . ConvertToHEX(strlen($empty));
                 $empty7 = $Hexempty . $empty;
                 $Hexempty = ConvertToHEX(8) . ConvertToHEX(strlen($empty));
                 $empty8 = $Hexempty . $empty;
                 $Hexempty = ConvertToHEX(9) . ConvertToHEX(strlen($empty));
                 $empty9 = $Hexempty . $empty;
                 $tobase   = $seller . $vat . $time . $total . $VATN. $empty6. $empty7. $empty8. $empty9; 
                                             $dataforQRcode =  base64_encode($tobase);
                  ?>
                                                      {!! QrCode::size(110)->generate( $dataforQRcode) !!}

           </div>

               <table style="border:2px solid rgba(0,0,0,.3);width:80%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">


                  <thead>
                     <tr>

                        <th class="tx-18">الاجمالي - SUB TOTAL </th>
                        <th class="tx-18">{{round($invoicetotal_price,2)+round($invoicetotal_discount,2)}}</th>
                     </tr>
                     <tr>
                        <th class="tx-18">الخصم - DISCOUNT </th>
                        <th class="tx-18">{{round($invoicetotal_discount,2)}}</th>
                     </tr>
                     <tr>
                        <th class="tx-18">الاجمالي بعد الخصم- SUB TOTAL AFTER DISCOUNT </th>
                        <th class="tx-18">{{round($invoicetotal_price,2)}}</th>
                     </tr>
                     <tr>

                        <th class="tx-18">ضريبة القيمة المضافة({{$avt->AVT*100}}%) -VALUE ADDED TAX ({{$avt->AVT*100}}%)


                        </th>
                        <th class="tx-18">{{round($invoicetotal_addedvalue,2)}}</th>
                     </tr>



                     <tr>
                        <th class="tx-18">الاجمالي الكلي -NET TOTAL</th>
                        <th class="tx-18">{{round($invoicetotal_addedvalue+$invoicetotal_price,2)}}
                           <br>
                        </th>

                     </tr>
                  </thead>


               </table>
 </div>










               <div style="  position: fixed;     
       text-align: center;    
       bottom: 0px; 
       width: 100%;">

                  @if(Auth()->user()->branchs_id==1)
                  <center> <span>
                        -
                     </span>
                  </center>
                  <center> <span>
                        {{addressar}}
                     </span>
                  </center>


                  <center> <span> {{addressen}}
                     </span>
                  </center>
  </div>
                  @endif
             


               <!-- row closed -->
@if($coun!=$y)
               <p style="page-break-after: always;">&nbsp;</p>

@endif
  
      
               @endforeach


 

               <br>



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