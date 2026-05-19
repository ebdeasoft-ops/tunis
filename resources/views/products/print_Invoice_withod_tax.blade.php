@extends('layouts.master')
@section('css')
<style>
  @media print {
.hide-cell{
          display: none;

}
.double {border-style: double;}
    @page {

      size: auto;
      /* auto is the initial value */
            margin: 2mm 15m 10mm 15mm/* this affects the margin in the printer settings */
      font-size: 28px;

    }
    .report-container{
        width:100%;
    }
table.report-container {
    page-break-after:always;
}
thead.report-header {
    display:table-header-group;
}
tfoot.report-footer {
    display:table-footer-group;
} 
.report-content{
    height:50%;
}
    .footer {
      position: fixed;
      bottom:-4px;
    }

    .textend {
      position: fixed;
      text-align: center;
      bottom: -40px;
      width: 100%;
    }

    .text {
      width: 320px;
      overflow: hidden;
      white-space: pre-wrap;
      text-overflow: ellipsis
    }
           .thick {
  font-weight: bold;
}
    .row-sm {
      dir: "rtl"
    }

        .tx-18{
                            font-size:19px!important;

        }
         .tx-16{
                            font-size:13px!important;

        }
        .tx-12{
                            font-size:12px!important;

        }

    .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 90%;
      font-size: 15px !important;

    }

    tr {
      color:"grey"
    }

  
#reciptprinter
{
      display: none;
    }
    #print_Button {
      display: none;
    }
    th {
      /*background-color: grey;*/
      font-size: 22px;
    }
  }

  .report-container{
        width:100%;
    }

  .textend {
    position: fixed;
    text-align: center;
    bottom: -20px;
    width: 100%;
  }
             .thick {
  font-weight: bold;
}
    .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 90%;
      font-size: 15px !important;

    }

</style>
@endsection
@section('title')
معاينه طباعة الفاتورة
@stop
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">

</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row row-sm table-responsive " dir="ltr">
  <div class="col-md-12 col-xl-12">
    <div class=" main-content-body-invoice" id="print">
      <div class="card card-invoice">
              <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
        <div class="card-body ">
            <table class="report-container" >

              <thead class="report-header">
     <tr>
        <th class="report-header-cell">
          <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%" dir=rtl>




                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                            <span class="thick" style="font-size:18px">{{Namear}}</span>
                            <br>
                            <p class="tx-16 thick"> {{describtionar}}</p>
                            <p class="tx-16 thick">{{STar}}</p>
                            <p class="tx-16 thick">{{Taxar}}</p>

                        </div><!-- billed-from -->
                        <div >
                            <?php
                            $logo = camplogo;
                            ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 150px; height: 150px;"></a>

                        </div>

                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>
                            <span class="thick" style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p class="tx-16 thick" > {{describtionen}} </p>
                            <span class="tx-16 thick">{{STen}} </span>
                            <p class="tx-16 thick"> {{Taxen}} </p>

                        </div>

                    </div><!-- invoice-header -->

                                    <center >  <p class="double">تسليم منتجات</p></center>

        
                                    <div class='row' style="justify-content: space-around;" dir=rtl>
   <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);width:100%; border-radius: 5px;" class="table double" >
                            <thead>
                            <tr class="row12"  >

                              <th  class="tx-16"> اسم العميل CLIENT NAME   </th>
                              <th class="tx-16" >{{$data['invoiceData']->customer->name}}</th>
                              <th  class="tx-16">اسم البائع SELLER NAME    </th>
                              <th class="tx-16" >{{Namear}}</th>

                                </tr>
                               
                            </thead>
                           
                        </table>

           

          </div>
                  


          <input type="number" class="form-control " name="show_invoice_number" id="show_invoice_number"
            title="      " value="{{$data['invoiceData']->id}}" hidden>

          
            </th>
      </tr>
    </thead>
    
       <tbody class="report-content">
      <tr>
         <td class="report-content-cell">
                       <div class='row' style="justify-content: space-around;" dir=rtl>

   <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);width:100%; border-radius: 5px;" class="table double" >
              <tbody>
            



                  <?php

                                    $pay = '';
                                    if ($data['invoiceData']->Pay == "Cash") {
                                        $pay = __('report.cash');
                                    } elseif ($data['invoiceData']->Pay == "Shabka") {
                                        $pay = __('report.shabka');
                                    } elseif ($data['invoiceData']->Pay == "Credit") {
                                        $pay = __('report.credit');
                                    } elseif ($data['invoiceData']->Pay == "Bank_transfer") {
                                        $pay = __('home.Bank_transfer');
                                    } else {
                                        $pay = __('home.Partition of the amount');
                                    }
                                    ?>
                                    
                                    
                                        <tr>
                                        
                  <td class="tx-16">طريقة الدفع PAYMENT METHOD   </td>

                  <td class="tx-16">{{$pay}}</td>





              
               
                  <td class="tx-16">تاريخ التسليمINVOICE DATE  </td>
                  <td class="tx-16">{{ $data['invoiceData']->created_at}}</td>
                               </tr> 
                    <tr>
                                                            

                  <td class="tx-16">رقم التسليمINVOICE NUMBER </td>
                  <td class="tx-16">{{ $data['invoiceData']->id}}</td>

              
                  <td class="tx-16">اسم الفرع BRANCH NAME </td>
                  <td class="tx-16">{{$data['invoiceData']->branch->name}}</td>

                </tr>
              </tbody>

            </table>
            </div>
      
       
     <div class="table-responsive">
                        <table style="border:2px solid rgba(0,0,0,.3)" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                          <thead>
                
              <tr>
                <th class="wd-center">NO<br>رقم</th>
                <th class="wd-center">Item NO <br> رقم منتج </th>
                <th class="tx-center">ITEM NAME<br>اسم الصنف </th>
                <th class="tx-center">PRODUCT PRICE<br>سعر القطعة </th>
                <th class="tx-center hide-cell" id="print_Button"     > STOCK <br>المخزون </th>
                <th class="tx-center "   > QUANTITY <br>الكمية </th>
                <th class="tx-center "   > UNIT <br>وحدة </th>
                <th class="tx-center hide-cell"  id="print_Button">Remaining quantity<br>الكمية المتبقية</th>
                <th class="tx-center"> Total<br>الاجمالي </th>
                <th class="tx-center"> DISCOUNT<br>الخصم </th>
                <th class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</th>



              </tr>
            </thead>
            <tbody>



              <?php $i = 0;
                                   $discountreturn=0;

                                ?>
                     @foreach (App\Models\sales_withoud_taxes::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                     @if($product->quantity!=0)
                                          <?php $i++ ?>

                     <tr>
                        <td class="wd-10p">{{$i}}</td>
                        <td class="wd-center" dir="rtl">{{$product->productData->Product_Code}}</td>
                        <td class="tx-center text">{{ $product->productData->product_name}}</td>
                        <td class="tx-center">{{ number_format($product->Unit_Price, 2, '.', '')}}</td>
                        <td class="tx-center hide-cell" id="print_Button">{{$product->quantity+$product->reamingQuantity}}</td>
                        <td class="tx-center ">{{$product->quantity}}</td>
                        <td class="tx-center ">{{$product->unit}}</td>
                        <td class="tx-center hide-cell" id="print_Button">{{$product->reamingQuantity}}</td>
                        <td class="tx-center">{{ number_format($product->Unit_Price*$product->quantity, 2, '.', '')}}</td>
                        <td class="tx-center">{{ number_format($product->Discount_Value, 2, '.', '')}}</td>
                        <td class="tx-center">{{ number_format(($product->Unit_Price*$product->quantity)-$product->Discount_Value, 2, '.', '')}}</td>

                     </tr>
                     @endif
                     @endforeach
                     <span>{{count(App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get())}}</span>
                        @foreach (App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                     <?php $i++;
                    //  $totalreturnprice+=$product->return_Unit_Price*$product->return_quantity;
                    //  $totaladdedvalue+=$product->return_Added_Value*$product->return_quantity;
                     $discountreturn+= $product->discountvalue+ $product->discountoninvoice;
                     ?>
                     <tr>
                        <td class="wd-10p" style="color:red">{{$i}}</td>
                        <td class="wd-center" dir="rtl">{{$product->productData->Product_Code}}</td>
                        <td class="tx-center text">{{ $product->productData->product_name}}</td>
                        <td class="tx-center">{{number_format( $product->return_Unit_Price , 2, '.', '')}}</td>
                        <td class="tx-center hide-cell"  id="print_Button">-</td>
                        <td class="tx-center "  >{{ $product->return_quantity}}</td>
                        <td class="tx-center hide-cell"  id="print_Button">-</td>
                        <td class="tx-center">{{ number_format((float)$product->return_Unit_Price*$product->return_quantity, 2, '.', '')}}</td>
                        <td class="tx-center">{{  number_format((float)$product->discountvalue, 2, '.', '')}}</td>
                        <td class="tx-center">{{  number_format((float)(($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue), 2, '.', '')}}</td>

                     </tr>
                     @endforeach



                  </tbody>
               </table>
            </div>
          <br>
 
            <div class='row' dir=rtl style="justify-content: center">
           
        <div>


          <?php
                 $invoice=App\Models\delivery_to_customer_withoud_tax_invoices::find( $data['invoiceData']->id);
                 $price=$invoice->cashamount+$invoice->Bank_transfer+$invoice->bankamount+$invoice->creaditamount;

                 $price_befor_tax=$price;
                 $invoicetotal_discount = $invoice->discount+$discountreturn;


          ?>  
        </div>
               <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">


                  <thead>
                     <tr>

                        <th class="tx-16">الاجمالي - SUB TOTAL </th>
                        <th class="tx-16">{{number_format((float)(round( $price_befor_tax,2)+round($invoicetotal_discount,2)), 2, '.', '')}}</th>
                     </tr>
                     <tr>
                        <th class="tx-16">الخصم - DISCOUNT </th>
                        <th class="tx-16">{{number_format(round($invoicetotal_discount,2), 2, '.', '')}}</th>
                     </tr>
                     <tr>
                        <th class="tx-16">الاجمالي بعد الخصم<br>SUB TOTAL AFTER DISCOUNT </th>
                        <th class="tx-16">{{number_format(round($price_befor_tax,2), 2, '.', '')}}</th>
                     </tr>
                  



                    
                  </thead>


               </table>
 </div>
</div>
          </div>
<br>

<span class="tx-16 ">{{__('home.notesClient')}} : {{$data['invoiceData']->note}}</span>
          <br>
          

   </td>
       </tr>
     </tbody>
   <tfoot class="report-footer">
       <tr>
           <td>
               <br>
           </td>
       </tr>
       <tr>
           <td>
               <br>
           </td>
       </tr>
       <tr>
           <td>
               <br>
           </td>
       </tr>
      <tr>
         <td class="report-footer-cell">

          <div class="footer" style="     
       text-align: center;    "
       
          @if(Auth()->user()->branchs_id==1)

            <center> <span>
                {{addressar}}
              </span>
            </center>


            <center> <span> {{addressen}}
              </span>
            </center>

            @endif
  
        @if(Auth()->user()->branchs_id==11)

            <center> <span>
المملكة العربية السعودية  - الدمام - دلة شارع عمر بن الخطاب بعد ليان  رقم الجوال  0507461524
</span>
            </center>


            <center> <span> Kingdom of Saudi Arabia - Dammam - Dala Omar Bin Al Khattab Street after Lian Mobile number 0507461524
              </span>
            </center>

            @endif
            @if(Auth()->user()->branchs_id==10)

            <center> <span>
                المملكة العربية السعودية  - جدة - الجوهرة - مجمع البسامي  رقم الجوال  0535589521 

              </span>
            </center>


            <center> <span>
                Kingdom of Saudi Arabia - Jeddah - Al Jawhara - Al Bassami Complex Mobile number 0535589521
</span>
            </center>

            @endif
            
                @if(Auth()->user()->branchs_id==9)

            <center> <span>
المملكة العربية السعودية  - خميس مشيط - طريق وادي بن هشبل   رقم الجوال  0556690148 

              </span>
            </center>


            <center> <span>
Kingdom of Saudi Arabia - Khamis Mushayt - Wadi Bin Hisbal Road Mobile number 0556690148</span>
            </center>

            @endif
  
  
          
          </div>
 </td>
      </tr>
    </tfoot>
    
    </table>

          <input type="hidden" id="token_search" value="{{ csrf_token() }}">
        

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


    $(document).ready(function() {

        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        setTimeout(() => {
            window.print();
        }, 500);
        setTimeout(() => {
            window.close();
        }, 10000);

    })
</script>
</script>

@endsection