@extends('layouts.master')
@section('css')
<style>
  @media print {
    .hide-cell {
      display: none !important;
    }
    .double { border-style: double; }

    @page {
      size: auto;
      /* تم تصحيح الخطأ الإملائي هنا من 15m إلى 15mm */
      margin: 2mm 15mm 10mm 15mm;
    }

    /* تصغير حجم الخط العام للطباعة ليتناسب مع الجدول */
    body {
      font-size: 14px;
    }

/* ⬇️ أضف هذا الجزء لحماية جدول الإجماليات السفلي من التمدد ⬇️ */
  table.totals-table {
    table-layout: auto !important;
    width: 40% !important; /* إجبار الجدول على مساحة 40% فقط من الورقة */

    float: left; /* ضمان التموضع الأيسر الصحيح */
  }

  table.totals-table th {
    font-size: 13px !important;
    padding: 6px 10px !important;
    line-height: 1.4;
  }
  table.totals-table tr {
    border-bottom: 1px solid #d3d3d3 !important; /* رسم خط رمادي واضح في الطباعة بين السطور */
    -webkit-print-color-adjust: exact; /* إجبار المتصفح على طباعة الألوان والحدود بدقة */
    print-color-adjust: exact;
  }
    .report-container {
      width: 100%;
    }
    table.report-container {
      page-break-after: always;
    }
    thead.report-header {
      display: table-header-group;
    }
    tfoot.report-footer {
      display: table-footer-group;
    }
    .report-content {
      height: 50%;
    }
    .footer {
      position: fixed;
      bottom: -4px;
    }
    .textend {
      position: fixed;
      text-align: center;
      bottom: -40px;
      width: 100%;
    }

    /* تعديل عرض اسم الصنف داخل الطباعة لمنعه من النزول لأسطر كثيرة */
    .text {
      width: 100% !important;
      max-width: 280px;
      overflow: hidden;
      white-space: normal; /* تم التغيير ليتناسق النص عند النزول */
      text-overflow: ellipsis;
      font-size: 14px !important;
      font-weight: bold;
    }

    .thick {
      font-weight: bold;
    }
    .row-sm {
      dir: "rtl"
    }
    .tx-18 { font-size: 14px !important; }
    .tx-16 { font-size: 13px !important; }
    .tx-12 { font-size: 12px !important; }

    .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 90%;
      font-size: 15px !important;
    }
    tr { color: "grey"; }

    #reciptprinter { display: none; }
    #print_Button { display: none !important; }

    /* تقليل حجم خط الهيدر في الطباعة حتى لا يضخم الجدول */
    th {
      font-size: 14px !important;
      padding: 5px !important;
    }
    td {
      font-size: 13px !important;
      padding: 5px !important;
    }

    /* ⬇️ إجبار الجدول على إعادة توزيع المساحات بعد إخفاء الأعمدة في الطباعة ⬇️ */
    table.invoice-table {
      table-layout: fixed !important;
      width: 100% !important;
    }
    /* توزيع النسب للأعمدة المتبقية فقط بعد إخفاء (المخزون والكمية المتبقية) */
    table.invoice-table th:nth-child(1) { width: 5% !important; }   /* رقم */
    table.invoice-table th:nth-child(2) { width: 35% !important; }  /* اسم الصنف - كبير */
    table.invoice-table th:nth-child(3) { width: 10% !important; }  /* سعر القطعة */
    table.invoice-table th:nth-child(5) { width: 8% !important; }   /* الكمية */
    table.invoice-table th:nth-child(7) { width: 8% !important; }   /* الإجمالي */
    table.invoice-table th:nth-child(8) { width: 8% !important; }   /* الخصم */
    table.invoice-table th:nth-child(9) { width: 10% !important; }  /* الإجمالي بعد الخصم */
    table.invoice-table th:nth-child(10) { width: 8% !important; }  /* الضريبة */
    table.invoice-table th:nth-child(11) { width: 8% !important; }  /* الإجمالي النهائي */
  }

  /* الستايل العادي للشاشة خارج الطباعة */
  .report-container { width: 100%; }
  .textend {
    position: fixed;
    text-align: center;
    bottom: -20px;
    width: 100%;
  }
  .thick { font-weight: bold; }
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

                                       @if($data['invoiceData']->customer->address=='-')
                                          <th class="tx-16">رقم الجوال PHONE  </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->phone}}</th>
                                      @else
                                      <th class="tx-16">المدينة CITY  </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->address}}</th>
                                      @endif
                                      <th class="tx-16">المدينة CITY  </th>
                                      <th class="tx-16">الرياض</th>
                                </tr>
                                   <tr>
                                      <th class="tx-16">المنطقة REGION </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->sub_city}}</th>
                                      <th class="tx-16">المنطقة REGION </th>
                                      <th class="tx-16">حي الخالدية</th>
                                </tr>
                                   <tr>
                                      <th class="tx-16">اسم الشارع  STREET NAME </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->street_name}}</th>
                                      <th class="tx-16">اسم الشارع  STREET NAME </th>
                                      <th class="tx-16"> شارع ضرار بن الأزور</th>
                                </tr>
                                  <tr>
                                      <th class="tx-16"> الرمز البريدي  POSTAL number  </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->postcode}}</th>
                                      <th class="tx-16"> الرمز البريدي  POSTAL number  </th>
                                      <th class="tx-16">12874 </th>
                                </tr>
                                  <tr>
                                      <th class="tx-16"> رقم المبني   BUILDING  NUMBER </th>
                                      <th class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->building_number}}</th>
                                      <th class="tx-16"> رقم المبني   BUILDING  NUMBER </th>
                                      <th class="tx-16">4402 </th>
                                </tr>
                            </thead>

                        </table>



          </div>



     <div class="table-responsive">
                <table style="border:2px solid rgba(0,0,0,.3)" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
           <thead>
      <tr>
         <th style="width: 5%;">NO<br>رقم</th>
         <th style="width: 35%;" class="tx-center">ITEM NAME<br>اسم الصنف </th>
         <th style="width: 10%;" class="tx-center">PRODUCT PRICE<br>سعر القطعة </th>
         <th style="width: 8%;" class="tx-center hide-cell" id="print_Button"> STOCK <br>المخزون </th>
         <th style="width: 7%;" class="tx-center "> QUANTITY <br>الكمية </th>
         <th style="width: 10%;" class="tx-center hide-cell" id="print_Button">Remaining quantity<br>الكمية المتبقية</th>
         <th style="width: 8%;" class="tx-center"> Total<br>الاجمالي </th>
         <th style="width: 8%;" class="tx-center"> DISCOUNT<br>الخصم </th>
         <th style="width: 12%;" class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</th>
         <th style="width: 12%;" class="tx-center tx-18"> VALUE ADDED TAX<br>الضريبة </th>
         <th style="width: 10%;" class="tx-center tx-18"> Total <br>الاجمالي </th>
      </tr>
   </thead>
            <tbody>



              <?php $i = 0;
                              $avt = App\Models\Avt::find(1);

                                   $discountreturn=0;

                                ?>
                     @foreach (App\Models\sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                     @if($product->quantity!=0)
                                          <?php $i++ ?>

                     <tr>
                        <td class="wd-10p">{{$i}}</td>
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
                        @foreach (App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                     <?php $i++;
                    //  $totalreturnprice+=$product->return_Unit_Price*$product->return_quantity;
                    //  $totaladdedvalue+=$product->return_Added_Value*$product->return_quantity;
                     $discountreturn+= $product->discountvalue+ $product->discountoninvoice;
                     ?>
                     <tr>
                        <td class="wd-10p" style="color:red">{{$i}}</td>
                        <td class="tx-center text">{{ $product->productData->product_name}}</td>
                        <td class="tx-center">{{number_format( $product->return_Unit_Price , 2, '.', '')}}</td>
                        <td class="tx-center hide-cell"  id="print_Button">-</td>
                        <td class="tx-center"  >{{ $product->return_quantity}}</td>
                        <td class="tx-center">{{ number_format((float)$product->return_Unit_Price*$product->return_quantity, 2, '.', '')}}</td>
                        <td class="tx-center">{{  number_format((float)$product->discountvalue, 2, '.', '')}}</td>
                        <td class="tx-center">{{  number_format((float)(($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue), 2, '.', '')}}</td>
  <?php
                        $total_row_befor_tax=round(  ($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue ,2);
                        $added_value_row=round($total_row_befor_tax*$avt->AVT  ,2);
                        ?>
                        <td class="tx-center">{{ number_format($added_value_row, 2, '.', '')}}</td>
                        <td class="tx-center">{{ number_format($added_value_row+$total_row_befor_tax, 2, '.', '')}}</td>

                     </tr>
                     @endforeach



                  </tbody>
               </table>
            </div>
          <br>

                                        <center >  <p class="double"> يمكن ارجاع القطع المباعة او استبدالها خلال 7 ايام من تاريخ الشراء وتكون بحالتها المباعة و القطع الكهربائية لا ترد ولا تستبدل</P></center>
            <div class='row' dir=rtl style="justify-content: center">
             <div>
                  <?php
                    function ConvertToHEX($value)
                   {
                       return pack("H*", sprintf("%02X", $value));
                   }
                  $invoice=App\Models\invoices::find( $data['invoiceData']->id);
                  $price=$invoice->cashamount+$invoice->Bank_transfer+$invoice->bankamount+$invoice->creaditamount;
                $avt = App\Models\Avt::find(1);

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

          @if(Auth()->user()->branchs_id==1)


  <center >  <p  class="double">  {{bankname}} <br>Account Number : {{bank_acount_number}}  <br>  IBAN Number : {{bank_acount_iban}}</P></center>


            @endif


        </div>
                <table style="border:2px solid rgba(0,0,0,.3); width: 40%; border-collapse: collapse;" class="table text-md-nowrap mb-0 table-striped totals-table text-center">
                   <thead>
                      <tr style="border-bottom: 1px solid rgba(0,0,0,.15);">
                         <th class="tx-16">الاجمالي - SUB TOTAL </th>
                         <th class="tx-16">{{number_format((float)(round($invoicetotal_price,2)+round($invoicetotal_discount,2)), 2, '.', '')}}</th>
                      </tr>
                      <tr style="border-bottom: 1px solid rgba(0,0,0,.15);">
                         <th class="tx-16">الخصم - DISCOUNT </th>
                         <th class="tx-16">{{number_format(round($invoicetotal_discount,2), 2, '.', '')}}</th>
                      </tr>
                      <tr style="border-bottom: 1px solid rgba(0,0,0,.15);">
                         <th class="tx-16">الاجمالي بعد الخصم<br>SUB TOTAL AFTER DISCOUNT </th>
                         <th class="tx-16">{{number_format(round($invoicetotal_price,2), 2, '.', '')}}</th>
                      </tr>
                      <tr style="border-bottom: 1px solid rgba(0,0,0,.15);">
                         <th class="tx-16">ضريبة القيمة المضافة({{$avt->AVT*100}}%)<br>VALUE ADDED TAX ({{$avt->AVT*100}}%)</th>
                         <th class="tx-16">{{number_format(round($invoicetotal_addedvalue,2), 2, '.', '')}}</th>
                      </tr>
                      <tr>
                         <th class="tx-16">الاجمالي الكلي -NET TOTAL</th>
                         <th class="tx-16">{{number_format(round($invoicetotal_addedvalue+$invoicetotal_price,2), 2, '.', '')}}
                            <br>
                            <p style="color:red; font-size:11px; margin: 5px 0 0 0; font-weight: bold;">
                               <span>{{$data['totatextlriyales'] }}</span>
                               <span>{{$data['totatextlrihalala']}} </span>
                            </p>
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



            <center> <span>
                {{addressar}}
              </span>
            </center>


            <center> <span> {{addressen}}
              </span>
            </center>



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
</script>

@endsection
