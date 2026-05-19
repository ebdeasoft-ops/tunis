@extends('layouts.master')
@section('css')
   <style>
  @media print {
    -webkit-print-color-adjust: exact;

    @page {

      size: auto;
      /* auto is the initial value */
      margin: 11mm 17mm 22mm 17mm;
      font-size: 28px;

    }
        .text {
      width: 220px;
      overflow: hidden;
      white-space: pre-wrap;
      text-overflow: ellipsis
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
      bottom:-2px;
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

    .row-sm {
      dir: "rtl"
    }

    .tx-18 {
      font-size: 15px !important;

    }

    .tx-16 {
      font-size: 12px !important;

    }

    .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 220px;
      font-size: 15px !important;

    }
    .text {
      width: 320px;
      overflow: hidden;
      white-space: pre-wrap;
      text-overflow: ellipsis
    }
    tr {
      bgcolor="grey"
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
</style>
@endsection
@section('title')
    معاينه طباعة المنتجات
@stop
@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        
        <!-- breadcrumb -->
    @endsection
    @section('content')
        <!-- row -->
    <div class="row row-sm" >
  <div class="col-md-12 col-xl-12">
    <div class=" main-content-body-invoice" id="print">
      <div class="card card-invoice">
              <div class="row" style="display: flex;justify-content:center;width:100%">


            <button class="btn btn-danger float-left mt-3 mr-2 print-style p-1" id="print_Button" onclick="printDiv()">
              <i class="mdi mdi-printer ml-1"></i>{{__('home.print')}}</button>
        
          </div>
          <br>
          <br>
                    <br>
          <br>

        <div class="card-body">
            <table class="report-container" >

              <thead class="report-header" >
     <tr>
        <th class="report-header-cell">
          <div class="invoice-header margintop" style="display: flex;justify-content:space-between;width:100%" dir="ltr">


            <div class="billed-from" style="width:33%;text-align: center;">
              <br>
              <span style="font-size:17px">{{Nameen}}</span>
              <br>
              <p dir=ltr > {{describtionen}} </p>
              <p dir=ltr>{{STen}} </p>
              <p dir=ltr> {{Taxen}} </p>

            </div>



            <div class="row">
              <?php
$logo=camplogo;
    ?>
              <a href=""><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1"
                  alt="logo" style="width: 110px; height: 100px;"></a>

            </div>

            <div class="billed-from" style="width:33%;text-align: center;">
              <br>

              <span style="font-size:16px">{{Namear}}</span>
              <br>
              <p> {{describtionar}}</p>
              <p>{{STar}}</p>
              <p>{{Taxar}}</p>

            </div><!-- billed-from -->

          </div><!-- invoice-header -->

     <?php
     $offer_price_to_customer=[];
     if($itemsRequest!=[]){
     
$offer_price_to_customer=App\Models\offer_price_to_customer::find($id);
}

?>


       
                   <center> <p  class="double">فاتورة اولية    <br> PROFORMA INVOICE</p></center>



        
          <center>
                        <h5>{{$offer_price_to_customer->branch->name}}</h5>

          </center>
            </th>
      </tr>
    </thead>
    
       <tbody class="report-content">
      <tr>
         <td class="report-content-cell">
  
          <div class='row' style="justify-content: space-around;">
                        <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                            <thead>
                                <tr class="row12"  >

<th >CLIENT NAME <br>اسم العميل  </th>
                                     <th class="tx-16" >{{$offer_price_to_customer->customer->name}}</th>



                                </tr>
                                
                                 <tr   >
                                    <th class="tx-16">TAX NUMBER<br> 
                                    الرقم الضريبي </th>

                                    <th class="tx-16">{{$offer_price_to_customer->customer->tax_no}}</th>
                                                                    </tr>
                           
                            </thead>
                           
                        </table>
                                          
                                              <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                            <thead>
         
  
               @if($offer_price_to_customer->type_of_decument==1)

                                   <tr>
                                         <th class="tx-16"> Quote DATE<br>تاريخ التسعيرة </th>
                                           <th class="tx-16">{{ $offer_price_to_customer->created_at}}</th>
                                    </tr>
                                  <tr>
                                        <th class="tx-16"> Quote NUMBER<br>رقم التسعيرة</th>
                                    <th class="tx-16">{{ $offer_price_to_customer->id}}</th>
                                </tr>
                                
                                         @else

            <tr>
                                         <th class="tx-16">  DATE<br>تاريخ  </th>
                                           <th class="tx-16">{{ $offer_price_to_customer->created_at}}</th>
                                    </tr>
                                  <tr>
                                        <th class="tx-16">  NUMBER<br>رقم </th>
                                    <th class="tx-16">{{ $offer_price_to_customer->id}}</th>
                                </tr>




         @endif

                            </thead>
                          
                        </table>
                
                                </div>
                      

          
   <br>
                                <br>
                                <br>

                            @if (isset($itemsRequest))
                                <?php $i = 0; ?>
                                <div class="col-xl-12">
                                    <div class="mg-b-20">

                                  <div class=" mg-t-20 mt-3 mr-2 d-flex ">
                        <table style="border:2px solid rgba(0,0,0,.3);width:100%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                                                    <thead>
                                                        <tr>
                                     <th >NO<br>رقم</th>
                                     @if($offer_price_to_customer->numbershowstatus)
                                     
                                                                         <th >Item NO <br> رقم منتج </th>

                                     @endif
                                    <th >ITEM NAME<br>اسم الصنف </th>
                                    <th >PRODUCT PRICE<br>سعر القطعة </th>
                                    <th > QUANTITY <br>الكمية </th>                                       
                                     <th >TOTAL AMOUNT<br>الاجمالي</th>
                                    <th > DISCOUNT<br>الخصم </th>
                                    <th > Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</th>



                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 0;
                                                        $totalpricePurchases = 0;
                                                        $totalAddedValue = 0;
                                                        $totaldiscount = 0;
                                                        ?>
                                                        @foreach ($itemsRequest as $product)
                                                            <?php $i++;
                                                            $avt = App\Models\Avt::find(1);
                                                            $totalpricePurchases += ($product->PriceWithoudTax * $product->quantity);
                                                            // $totalAddedValue += $product->PriceWithoudTax * $avt->AVT * $product->quantity;
                                                            $totaldiscount += $product->discount;
                                                            
                                                            ?>
                                                            <tr>
                                                                <td>{{ $i }}</td>
                                                                     @if($offer_price_to_customer->numbershowstatus)
                                                                                                     <td dir="ltr">{{ $product->productData->Product_Code }}</td>

                                     
                                     @endif
                                                                <td class="text">{{ $product->productData->product_name }}</td>
                                                                <td>{{ $product->PriceWithoudTax }}</td>
                                                                <td>{{ $product->quantity }}</td>
                                                                <td>{{ $product->quantity*$product->PriceWithoudTax }}</td>
                                                                <td>{{ $product->discount }}</td> 
                                                                <td>{{ round(($product->quantity * ($product->PriceWithoudTax )) - $product->discount,2) }}
                                                                </td>

                                                            <tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                <br>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                    
                                    
                                    
                                    
                              <div class="row">
                              <div>
                                                               
                                
          @if(Auth()->user()->branchs_id==1)

       
           <center >  <p  class="double">   مصرف الراجحي <br>Account Number : 215000010006086015217  <br>  IBAN Number : SA9880000215608016015217</P></center>


            @endif
  
        @if(Auth()->user()->branchs_id==11)

       
           <center >  <p  class="double">   مصرف الراجحي <br>Account Number : 215000010006086195142  <br>  IBAN Number : SA7780000215608016195142</P></center>



            @endif
            @if(Auth()->user()->branchs_id==10)

          
           <center >  <p  class="double">   مصرف الراجحي <br>Account Number : 215000010006086195159  <br>  IBAN Number : SA0680000215608016195159</P></center>

            @endif
            
                @if(Auth()->user()->branchs_id==9)

     
           <center >  <p  class="double">   مصرف الراجحي <br>Account Number : 215000010006086015217  <br>  IBAN Number : SA9880000215608016015217</P></center>


            @endif
                              </div>
                              
                              &nbsp;
                              &nbsp;
                              &nbsp;

               <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                                     
                                        <thead>
                                            <tr>
  <th class="tx-17">SUB TOTAL  <br>الاجمالي قبل الضريبة</th>
  <th>{{ round($totalpricePurchases,2)}}</th>
</tr>
<tr>
         <th class="tx-17">DISCOUNT <br>الخصم </th>
        <th>{{ round($offer_price_to_customer->discount,2) }}</th>
</tr>
<tr>
        <th class="tx-17">SUB TOTAL AFTER DISCOUNT <br>  الاجمالي بعد الخصم</th>
        <th>{{ round($totalpricePurchases,2)- round($offer_price_to_customer->discount,2) }}</th>

</tr>
<tr>
    
   <?php
                                $avt = App\Models\Avt::find(1);?>
                                            <th class="tx-17">VALUE ADDED TAX   ({{$avt->AVT*100}}%)
                                            <br>
                                            
                                            ضريبة القيمة المضافة({{$avt->AVT*100}}%)</th>   
                                                <th>{{  round((round($totalpricePurchases,2)- round($offer_price_to_customer->discount,2))* $avt->AVT,2)}}</th>

</tr>
         <tr>
                                              <th class="tx-17">NET TOTAL<br>الاجمالي الكلي </th>
                                                <th>{{( round((round($totalpricePurchases,2)- round($offer_price_to_customer->discount,2))* $avt->AVT,2)+(round($totalpricePurchases,2)- round($offer_price_to_customer->discount,2)) )}}</th>

                                            </tr>
                                        </thead>

                                    

                                    </table>
                                    
     
                                      
                                          
                                         </div>
                                         </div>





                                @endif
                                <br /> 
                                                                &nbsp;
                             
      
                                </form>

<br>
<p>{{__('home.notesClient')}} : {{$offer_price_to_customer->notes}}</p>




          </div>


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
