<!DOCTYPE html>
<html dir="rtl">

<head>
  <title> Invoice </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    * {
      font-family: DejaVu Sans !important;
    }

    body {
      font-size: 12px;
      font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
      padding: 2px;
      margin: 10px;

      color: black;
    }

.tx-center{
    text-align: center;
}
    body {
      color: black;
      text-align: right;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    .justify_content {
      /* justify-content:space-between; */
    }

    th,
    td {
      /* text-align: left; */
      padding: 1px;
                    text-align: center;

    }
    .border{
                border: 1px solid black;

    }

    tr:nth-child(even) {
      background-color: #D6EEEE;
    }

    @page {
      size: a4;
      margin: 4px;
      padding: 0;
    }




    .row {
      display: block;
      padding-left: 24;
      padding-right: 24;
      page-break-before: avoid;
      page-break-after: avoid;
    }

    .column {
      display: block;
      page-break-before: avoid;
      page-break-after: avoid;
    }
  </style>
</head>

<body>
  <div class="justify_content" style="display: flex;width:100%" dir=rtl>




    
    
      <?php
      $logo = camplogo;
      ?>



   
    <table>


      <tr class="heading">
      <td  style="width:35%">
          <center><span class="thick" style="font-size:16px">{{Nameen}}</span> </center>
          <center><span class="tx-16 thick"> {{describtionen}} </span> </center>
          <center><span class="tx-16 thick">{{STen}} </span> </center>
          <center><span class="tx-16 thick"> {{  $logo}} </span> </center>

        </td>
      <td style="width:35%"><center>          
        <img src="{{ public_path('assets/img/brand').'/'.$logo }}"
      style="width: 150px; height: 100px;">
</center></td>  

        <td style="width:30%">
        <center><span style="font-size:18px">{{Namear}}</span> </center>
        <center><span class="tx-16 thick"> {{describtionar}}</span> </center>
        <center><span class="tx-16 thick">{{STar}}</span> </center>
        <center><span class="tx-16 thick">{{Taxar}}</span> </center>

        </td>

     
      </tr>

    
    </table>
  </div><!-- invoice-header -->
 
                <p><center>  Sales returns   مرتجع المبيعات</center></p>
           
                <p><center>Notice creditor  اشعار دائن     </center></p>

  <br>
  <br>

<table style="width:100%; border: 1px solid black;">

    <tr class="heading">
      <td>
        <table class="border" style="border:2px solid rgba(0,0,0,.3)" dir="rtl">
          <tbody style=" border: 1px solid black;">
            <tr class="row12" dir="rtl">

              <td style=" border: 1px solid black;" class="tx-16">{{$data['invoiceData']->customer->name}}</td>
              <td style=" border: 1px solid black;" class="tx-16">CLIENT NAME -اسم العميل </td>



            </tr>

            <tr>
             

              <td style=" border: 1px solid black;"  class="tx-16">{{$data['invoiceData']->customer->tax_no==0?'-':$data['invoiceData']->customer->tax_no}}</td>
              <td style=" border: 1px solid black;" class="tx-16">TAX NUMBER-
                الرقم الضريبي </td>
            </tr>
            <tr>


              <td style=" border: 1px solid black;" class="tx-16">{{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->address}}</td>
              <td style=" border: 1px solid black;" class="tx-16"> CLIENT ADDRESS-عنوان العميل </td>

            </tr>

        
  </tbody>

        </table>
      </td>

      <td>
        <table dir="rtl" style=" border: 1px solid black;">
          <tbody style=" border: 1px solid black;">
       
                <?php 
                                    $created_at='-';
                                  ?>
  @foreach ($data['salesData'] as $product)
                                    <?php 
                                    $created_at=$product->created_at;
                                  ?>
@endforeach
          
            <tr>
              <td style=" border: 1px solid black;" class="tx-16">{{ $data['invoiceData']->id}}</td>
              <td style=" border: 1px solid black;" class="tx-16"> INVOICE NUMBER-رقم الفاتورة</td>

            </tr>
              <tr>
              <td style=" border: 1px solid black;" class="tx-16">{{ $created_at}}</td>
              <td style=" border: 1px solid black;" class="tx-16"> INVOICE DATE    - تاريخ الفاتورة </td>

            </tr>
            
                    <tr>
              <td style=" border: 1px solid black;" class="tx-16">{{ $data['invoiceData']->NOTICE_Number}}</td>
              <td style=" border: 1px solid black;" class="tx-16"> NOTICE  NUMBER رقم الاشعار  </td>

            </tr>
                     
          </tbody>

        </table>
      </td>
    </tr>


    </table>
 



 
  <br>
  <br>
  <div dir="rtl">
    <table class="border" style="        border: 1px solid black;
">
    <tbody>
        <tr>
          <td style=" border: 1px solid black;" class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</td>
          <td style=" border: 1px solid black;" class="tx-center"> DISCOUNT<br>الخصم </td>
          <td style=" border: 1px solid black;" class="tx-center"> Total<br>الاجمالي </td>
          <td style=" border: 1px solid black;" class="tx-center " id="print_Button"> QUANTITY <br>الكمية </td>
          <td style=" border: 1px solid black;" class="tx-center">PRODUCT PRICE<br>سعر القطعة </td>
          <td style=" border: 1px solid black;" class="tx-center">ITEM NAME<br>اسم الصنف </td>
          <td style=" border: 1px solid black;" class="wd-center">Item NO <br> رقم منتج </td>
          <td style=" border: 1px solid black;" class="wd-center">NO<br>رقم</td>


        </tr>
      <tbody style=" border: 1px solid black;">



           <?php $i = 0;
                                $totalprice = 0;
                                $totalAddedValue = 0;
                                $avtSaleRate = App\Models\Avt::find(1);
                                $avtSale = $avtSaleRate->AVT;
                                $discount_total=0;
                                ?>

        @foreach ($data['salesData'] as $product)
        <?php $i++;
                                $discount_total+=( $product->discountvalue + $product->discountoninvoice);
                                $totalprice += ($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue - $product->discountoninvoice;
                                ?>

        <tr>
        <td style=" border: 1px solid black;"  class="tx-center">{{ number_format(($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue, 2, '.', '')}}</td>
        <td style=" border: 1px solid black;" class="tx-center">{{ number_format($product->discountvalue, 2, '.', '')}}</td>
        <td style=" border: 1px solid black;" class="tx-center">{{ number_format($product->return_Unit_Price*$product->quantity, 2, '.', '')}}</td>
        <td style=" border: 1px solid black;" class="tx-center hide-cell" id="print_Button">{{$product->return_quantity}}</td>
        <td style=" border: 1px solid black;" class="tx-center">{{ number_format($product->return_Unit_Price, 2, '.', '')}}</td>
        <td style=" border: 1px solid black;" class="tx-center text">{{ $product->productData->product_name}}</td>
        <td style=" border: 1px solid black;" class="wd-center" dir="rtl">{{$product->productData->Product_Code}}</td>

          <td style=" border: 1px solid black;" class="wd-10p">{{$i}}</td>
         
        </tr>
        @endforeach
     



      </tbody>
    </table>
  </div>
  <br>
  <br>
  <div class='row' dir=rtl>
    <div>
      <?php
      function ConvertToHEX($value)
      {
        return pack("H*", sprintf("%02X", $value));
      }
      $price = round(($totalprice*$avtSale),2)+ $totalprice;
      $avt = App\Models\Avt::find(1);

   


      $sellerName = sallerQrCode;
      $varNumber = TaxQrCode;
      $time = $created_at;
      $issue_time = substr($time, 11);
      $issue_date = substr($time, 0, 10);
      $time = (string)$issue_date . 'T' . (string)$issue_time;

      $total = number_format(round(($totalprice*$avtSale),2)+ $totalprice, 2, '.', '');
      $tax = number_format(round(($totalprice*$avtSale),2), 2, '.', '');
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
      $empty = '';
      $Hexempty = ConvertToHEX(6) . ConvertToHEX(strlen($empty));
      $empty6 = $Hexempty . $empty;
      $Hexempty = ConvertToHEX(7) . ConvertToHEX(strlen($empty));
      $empty7 = $Hexempty . $empty;
      $Hexempty = ConvertToHEX(8) . ConvertToHEX(strlen($empty));
      $empty8 = $Hexempty . $empty;
      $Hexempty = ConvertToHEX(9) . ConvertToHEX(strlen($empty));
      $empty9 = $Hexempty . $empty;
      $tobase   = $seller . $vat . $time . $total . $VATN . $empty6 . $empty7 . $empty8 . $empty9;

      $dataforQRcode =  base64_encode($tobase);
      ?>

    </div>
    <center>
      <table dir="rtl" style=" border: 1px solid black;width:100%">


        <tbody style=" border: 1px solid black;">
          <tr>

            <td style=" border: 1px solid black;" class="tx-18"> <center> {{round($discount_total,2)+round($totalprice,2)}}   </center> </td>
            <td style=" border: 1px solid black;" class="tx-18">  <center> الاجمالي  SUB TOTAL </center> </td>

          </tr>
          <tr>
            <td style=" border: 1px solid black;" class="tx-18"> <center> {{round($discount_total,2)}} </center></td>
            <td style=" border: 1px solid black;" class="tx-18" >  <center>  الخصم  DISCOUNT </center> </td>

          </tr>
          <tr>
            <td style=" border: 1px solid black;"  class="tx-18">  <center>{{ $totalprice}}</center></td>
            <td style=" border: 1px solid black;" class="tx-18">  <center> الاجمالي بعد الخصم SUB TOTAL AFTER DISCOUNT  </center></td>

          </tr>
          <tr>


            <td style=" border: 1px solid black;" class="tx-18"><center> {{round( $totalprice*$avtSale,2)}} </center></td>
            <td style=" border: 1px solid black;" class="tx-18" >  <center> ضريبة القيمة المضافة({{$avt->AVT*100}}%) VALUE ADDED TAX 
            </center>  </td>
          </tr>


          <?php
          $total = round(($totalprice*$avtSale),2)+ $totalprice;
          list($whole, $decimal) = explode('.', str_replace(",", "", number_format((float)$total, 2, '.', '')));

          $check = str_split($decimal);
          if ($check[0] == "0") {
            $decimal = (int)$check[1];
          } else {
            $decimal = $decimal;
          }
          if ($decimal != '00') {

            $num = (int)$decimal;
            $num = str_replace(array(',', ''), '', trim($num));
            if (!$num) {
              return false;
            }
            $num = (int) $num;
            $words = array();
            $list1 = array(
              '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
              'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
            );
            $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
            $list3 = array(
              '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
              'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
              'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
            );
            $num_length = strlen($num);
            $levels = (int) (($num_length + 2) / 3);
            $max_length = $levels * 3;
            $num = substr('00' . $num, -$max_length);
            $num_levels = str_split($num, 3);
            for ($i = 0; $i < count($num_levels); $i++) {
              $levels--;
              $hundreds = (int) ($num_levels[$i] / 100);
              $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
              $tens = (int) ($num_levels[$i] % 100);
              $singles = '';
              if ($tens < 20) {
                $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '');
              } elseif ($tens >= 20) {
                $tens = (int)($tens / 10);
                $tens = ' and ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
              }
              $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
            } //end for loop
            $commas = count($words);
            if ($commas > 1) {
              $commas = $commas - 1;
            }
            $words = implode(' ',  $words);
            $words = preg_replace('/^\s\b(and)/', '', $words);
            $words = trim($words);
            $words = ucfirst($words);
            $words = $words;
            $decimal = $words;
          } else {
            $decimal != 'zero';
          }
          $num = round((int)$whole, 2);
          $num = str_replace(array(',', ''), '', trim($num));
          if (!$num) {
            return false;
          }
          $num = (int) $num;
          $words = array();
          $list1 = array(
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
          );
          $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
          $list3 = array(
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
          );
          $num_length = strlen($num);
          $levels = (int) (($num_length + 2) / 3);
          $max_length = $levels * 3;
          $num = substr('00' . $num, -$max_length);
          $num_levels = str_split($num, 3);
          for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
              $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '');
            } elseif ($tens >= 20) {
              $tens = (int)($tens / 10);
              $tens = ' and ' . $list2[$tens] . ' ';
              $singles = (int) ($num_levels[$i] % 10);
              $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
          } //end for loop
          $commas = count($words);
          if ($commas > 1) {
            $commas = $commas - 1;
          }
          $words = implode(' ',  $words);
          $words = preg_replace('/^\s\b(and)/', '', $words);
          $words = trim($words);
          $words = ucfirst($words);
          $words = $words;
          $result = $words;



          ?>
          <tr>
            <td style=" border: 1px solid black;" class="tx-18"> <center> {{round(($totalprice*$avtSale),2)+ $totalprice}}
            
            <br>
              <p style="color:red;font-size:11px"> {{$result }} <span style="color:red;font-size:11px">Riyals</span> and {{$decimal}} <span style="color:red;font-size:11px">Halala</span></p>
        </center>    </td>
            <td style=" border: 1px solid black;"  class="tx-18">  <center> الاجمالي الكلي NET TOTAL </center></td>

          </tr>
        </tbody>


      </table>
    </center>
  </div>
  <br>

 <center>

   </center>

  </div>
  <br>
<span style="color:black"> 
<center>

<img src="data:image/png;base64,{!! base64_encode(QrCode::size(110)->generate( $dataforQRcode) )!!}">
</center>
</span>
  <br>

  <br>

  <div>

  </div>
  </div>
  <div style="  position: fixed;     
       text-align: center;    
       bottom: 0px; 
       width: 100%;">

    @if(Auth()->user()->branchs_id==1)

    <center> <span dir=rtl>
        {{addressar}}
      </span>
    </center>


    <center> <span> {{addressen}}
      </span>
    </center>

    @endif
  </div>
</body>

</html>