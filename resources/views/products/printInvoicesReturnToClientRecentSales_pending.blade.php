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
                                &nbsp;
                            
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

                                                        @if(strlen($data['invoiceData']->customer->tax_no)==15)
                                    <center >  <p class="double"> Tax Invoice - فاتورة ضريبية</p></center>

            @else
            
            
            <center >  <p class="double">     Simplified tax invoice    فاتورة ضريبية مبسطة </p></center>


             @endif 
         
                  


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





              
               
                  <td class="tx-16">تاريخ الفاتورة INVOICE DATE  </td>
                  <td class="tx-16">{{ $data['invoiceData']->created_at}}</td>
                               </tr> 
                    <tr>
                                                            

                  <td class="tx-16">رقم الفاتورة INVOICE NUMBER </td>
                  <td class="tx-16">{{ $data['invoiceData']->id}}</td>

              
                  <td class="tx-16">اسم الفرع BRANCH NAME </td>
                  <td class="tx-16">{{$data['invoiceData']->branch->name}}</td>

                </tr>
              </tbody>

            </table>
            </div>
          <div class='row' style="justify-content: space-around;" dir=rtl>
   <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);width:100%; border-radius: 5px;" class="table double" >
                            <thead>
                            <tr class="row12"  >

                              <th  class="tx-16"> اسم العميل CLIENT NAME   </th>
                              <th class="tx-16" >{{$data['invoiceData']->customer->name}}</th>
                              <th  class="tx-16">اسم البائع SELLER NAME    </th>
                              <th class="tx-16" >{{Namear}}</th>

                                </tr>
                                
                                 <tr   >
                                    <th class="tx-16"> الرقم الضريبي TAX NUMBER   </th>
                                    <th class="tx-16">{{$data['invoiceData']->customer->tax_no==0?'-':$data['invoiceData']->customer->tax_no}}   <br><span>       سجل التحاري :  {{$data['invoiceData']->customer->CRN==NULL?'-':$data['invoiceData']->customer->CRN}}</span></th>
                                
                                    <th class="tx-16"> الرقم الضريبي TAX NUMBER   </th>
                                    <th class="tx-16">{{Taxen}}</th>
                                                     
                                    </tr>
                              
                                    <tr>
                                    <th class="tx-16">المنطقة REGION </th>
                                    <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->address}}</th>
                                      <th class="tx-16">المنطقة REGION </th>

                                      <th class="tx-16">{{city}}</th>
                                </tr>
                      <tr>
                      <th class="tx-16">المدينة CITY  </th>
                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->sub_city}}</th>
                                      <th class="tx-16">المدينة CITY  </th>
                                      <th class="tx-16"> {{region}} </th>
                                </tr>
                                   <tr>
                                      <th class="tx-16">اسم الشارع  STREET NAME </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->street_name}}</th>
                                      <th class="tx-16">اسم الشارع  STREET NAME </th>
                                      <th class="tx-16"> {{street_name}} </th>
                                </tr>
                                  <tr>
                                      <th class="tx-16"> الرمز البريدي  POSTAL number  </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->postcode}}</th>
                                      <th class="tx-16"> الرمز البريدي  POSTAL number  </th>
                                      <th class="tx-16">{{postal_number}} </th>
                                </tr>
                                  <tr>
                                      <th class="tx-16"> رقم المبني   BUILDING  NUMBER </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->building_number}}</th>
                                      <th class="tx-16"> رقم المبني   BUILDING  NUMBER </th>
                                      <th class="tx-16">{{building_number}} </th>
                                </tr>
                            </thead>
                           
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
                <th class="tx-center "  > QUANTITY <br>الكمية </th>
                <th class="tx-center hide-cell"  id="print_Button">Remaining quantity<br>الكمية المتبقية</th>
                <th class="tx-center"> Total<br>الاجمالي </th>
                <th class="tx-center"> DISCOUNT<br>الخصم </th>
                <th class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</th>

   <th class="tx-center"> VALUE ADDED TAX<br>الضريبة </th>
                <th class="tx-center"> Total <br>الاجمالي  </th>




              </tr>
            </thead>
            <tbody>



              <?php $i = 0;
                              $avt = App\Models\Avt::find(1);

                                   $discountreturn=0;

                                ?>
                     @foreach (App\Models\temp_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                     @if($product->quantity!=0)
                                          <?php $i++ ?>

                     <tr>
                        <td class="wd-10p">{{$i}}</td>
                        <td class="wd-center" dir="rtl">{{$product->productData->Product_Code}}</td>
                        <td class="tx-center text">{{ $product->productData->product_name}}</td>
                        <td class="tx-center">{{ number_format($product->Unit_Price, 2, '.', '')}}</td>
                        <td class="tx-center hide-cell" id="print_Button">{{$product->quantity+$product->reamingQuantity}}</td>
                        <td class="tx-center " >{{$product->quantity}}</td>
                        <td class="tx-center hide-cell" id="print_Button">{{$product->reamingQuantity}}</td>
                        <td class="tx-center">{{ number_format($product->Unit_Price*$product->quantity, 2, '.', '')}}</td>
                        <td class="tx-center">{{ number_format($product->Discount_Value, 2, '.', '')}}</td>
                        <td class="tx-center">{{ number_format(($product->Unit_Price*$product->quantity)-$product->Discount_Value, 2, '.', '')}}</td>
  <?php
                        $total_row_befor_tax=round(  ($product->Unit_Price*$product->quantity)-$product->Discount_Value ,2);
                        $added_value_row=round($total_row_befor_tax*$avt->AVT  ,2);
                        ?>
                        <td class="tx-center">{{ number_format($added_value_row, 2, '.', '')}}</td>
                        <td class="tx-center">{{ number_format($added_value_row+$total_row_befor_tax, 2, '.', '')}}</td>

                     </tr>
                     @endif
                     @endforeach
   


                  </tbody>
               </table>
            </div>
          <br>
 
            <div class='row' dir=rtl style="justify-content: center">
             <div>
                  <?php
                    function ConvertToHEX($value)
                   {
                       return pack("H*", sprintf("%02X", $value));
                   }
                  $invoice=App\Models\temp_invoice::find( $data['invoiceData']->id);
                  $price=$invoice->Price+$invoice->Added_Value;

                  $price_befor_tax=$price*100/(100+($avt->AVT*100));
                  $invoicetotal_addedvalue = ($price_befor_tax )* $avt->AVT;
                  $invoicetotal_price = $price_befor_tax;
                  $invoicetotal_discount = $invoice->discount+$discountreturn;



                  
                   $sellerName = sallerQrCode;
                   $varNumber = TaxQrCode;
                   $time =$invoice->created_at;
$issue_time=substr($time, 11);
                   $issue_date=substr($time, 0, 10);
                   $time = (string)$issue_date . 'T' . (string)$issue_time;

                   $total = number_format((round($invoicetotal_addedvalue + $invoicetotal_price, 2)),2,'.','');
                   $tax = number_format(round($invoicetotal_addedvalue, 2),2,'.','');
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
        <div>
&nbsp;
<center >  <p  class="double">  {{bankname}} <br>Account Number : {{bank_acount_number}}  <br>  IBAN Number : {{bank_acount_iban}}</P></center>

&nbsp;

            
        </div>
               <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">


                  <thead>
                     <tr>

                        <th class="tx-16">الاجمالي - SUB TOTAL </th>
                        <th class="tx-16">{{number_format((float)(round($invoicetotal_price,2)+round($invoicetotal_discount,2)), 2, '.', '')}}</th>
                     </tr>
                     <tr>
                        <th class="tx-16">الخصم - DISCOUNT </th>
                        <th class="tx-16">{{number_format(round($invoicetotal_discount,2), 2, '.', '')}}</th>
                     </tr>
                     <tr>
                        <th class="tx-16">الاجمالي بعد الخصم<br>SUB TOTAL AFTER DISCOUNT </th>
                        <th class="tx-16">{{number_format(round($invoicetotal_price,2), 2, '.', '')}}</th>
                     </tr>
                     <tr>

                        <th class="tx-16">ضريبة القيمة المضافة({{$avt->AVT*100}}%)<br>VALUE ADDED TAX ({{$avt->AVT*100}}%)


                        </th>
                        <th class="tx-16">{{number_format(round($invoicetotal_addedvalue,2), 2, '.', '')}}</th>
                     </tr>



                     <tr>
                        <th class="tx-16">الاجمالي الكلي -NET TOTAL</th>
                        <th class="tx-16">{{number_format(round($invoicetotal_addedvalue+$invoicetotal_price,2), 2, '.', '')}}
                           <br>
                                        <p style="color:red;font-size:10px">     <span style="color:red;font-size:10px">{{$data['totatextlriyales'] }}</span>
                                        <span style="color:red;font-size:10px">{{$data['totatextlrihalala']}} </span></p> 
                        </th>

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
  $("#reciptprinter").click(function (e) {
    reciptprinter
    var url = " {{ URL::to('reciptprinter') }}";
    var token_search = $("#token_search").val();
    $.ajax({
      url: url,
      type: 'post',
      cache: false,
      dataType: 'html',
      data: {
        _token: token_search,
        show_invoice_number: $('#show_invoice_number').val(),
      },
      success: function (data) {
        console.log(data)
        const winUrl = URL.createObjectURL(
          new Blob([data], {
            type: "text/html"
          })
        );
        const win = window.open(
          winUrl,
          "win",
          `width=800,height=400,screenX=200,screenY=200`
        );

      },
      error: function (response) {
        console.log(response)
        alert("{{ __('home.sorryerror') }}")

      }
    });
  });

  function printDiv() {
    var printContents = document.getElementById('print').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
  }
  
  
        $("#reciptprinter").click(function(e) {
        reciptprinter
        var url = " {{ URL::to('reciptprinter') }}";
        var token_search = $("#token_search").val();
        $.ajax({
            url: url,
            type: 'post',
            cache: false,
            dataType: 'html',
            data: {
                _token: token_search,
                show_invoice_number: $('#show_invoice_number').val(),
            },
            success: function(data) {
                console.log(data)
                const winUrl = URL.createObjectURL(
                    new Blob([data], {
                        type: "text/html"
                    })
                );
                const win = window.open(
                    winUrl,
                    "win",
                    `width=800,height=400,screenX=200,screenY=200`
                );

            },
            error: function(response) {
                console.log(response)
                alert("{{ __('home.sorryerror') }}")

            }
        });
    });
</script>

@endsection