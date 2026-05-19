<!DOCTYPE html>
<html dir="rtl">

<head>
  <title>Arabic Invoice </title>
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
          <center><span class="thick" style="font-size:13px">{{Nameen}}</span> </center>
          <center><span class="tx-16 thick"> {{describtionen}} </span> </center>
          <center><span class="tx-16 thick">{{STen}} </span> </center>
          <center><span class="tx-16 thick"> {{Taxen}} </span> </center>

        </td>
      <td style="width:35%"><center>          
        <img src="{{ public_path('assets/img/brand/logo.png') }}"
      style="width: 150px; height: 100px;">
</center></td>  

        <td style="width:30%">
        <center><span style="font-size:14px">{{Namear}}</span> </center>
        <center><span class="tx-16 thick"> {{describtionar}}</span> </center>
        <center><span class="tx-16 thick">{{STar}}</span> </center>
        <center><span class="tx-16 thick">{{Taxar}}</span> </center>

        </td>

     
      </tr>

    
    </table>
  </div><!-- invoice-header -->

  <center> <p  class="double"> عرض تسعيرة للعميل <br> Quote to the customer</p></center>
     <?php
$offer_price_to_customer=App\Models\offer_price_to_customer::find($itemsRequest[0]->order_id);

?>
  <br>
  <br>

<table style="width:100%">

    <tr class="heading">
    
      <td>
        <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);">
        <thead>
         
  
      
         <tr>
         <td style=" border: 1px solid black;" class="tx-16">{{ $offer_price_to_customer->created_at}}</td>

               <td style=" border: 1px solid black;" class="tx-16"> Quote DATE<br>تاريخ التسعيرة </td>
          </tr>
        <tr>
        <td style=" border: 1px solid black;" class="tx-16">{{ $offer_price_to_customer->id}}</td>

              <td style=" border: 1px solid black;" class="tx-16"> Quote NUMBER<br>رقم التسعيرة</td>
      </tr>
  </thead>

</table>
      </td>

      <td>
        <table style=" border: 1px solid black;" dir="rtl">
                            <thead>
                                <tr  class="row12"  >
                                <td style=" border: 1px solid black;" class="tx-16" >{{$offer_price_to_customer->customer->name}}</td>

<td  style=" border: 1px solid black;">CLIENT NAME <br>اسم العميل  </td>



                                </tr>
                                
                                 <tr   >
                                 <td style=" border: 1px solid black;" class="tx-16">{{$offer_price_to_customer->customer->tax_no}}</td>

                                    <td style=" border: 1px solid black;" class="tx-16">TAX NUMBER<br> 
                                    الرقم الضريبي </td>

                                                                    </tr>
                           
                            </thead>
                           
                        </table>
      </td>

    </tr>


    </table>
 



 
  <br>
  <br>
  <div dir="rtl">
    <table style=" border: 1px solid black;">
    <tbody>
        <tr style=" border: 1px solid black;">
          <td style=" border: 1px solid black;" class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم</td>
          <td style=" border: 1px solid black;" class="tx-center"> DISCOUNT<br>الخصم </td>
          <td style=" border: 1px solid black;" class="tx-center"> Total<br>الاجمالي </td>
          <td style=" border: 1px solid black;" class="tx-center " id="print_Button"> QUANTITY <br>الكمية </td>
          <td style=" border: 1px solid black;" class="tx-center">PRODUCT PRICE<br>سعر القطعة </td>
          <td style=" border: 1px solid black;" class="tx-center">ITEM NAME<br>اسم الصنف </td>
          <td style=" border: 1px solid black;" class="wd-center">Item NO <br> رقم منتج </td>
          <td style=" border: 1px solid black;" class="wd-center">NO<br>رقم</td>


        </tr>
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
                                                            <tr style=" border: 1px solid black;">
                                                                <td style=" border: 1px solid black;"><center>{{ round(($product->quantity * ($product->PriceWithoudTax )) - $product->discount,2) }}</center></td>
                                                                <td style=" border: 1px solid black;"><center>{{ $product->discount }}</center></td> 
                                                                <td style=" border: 1px solid black;"><center>{{ $product->quantity*$product->PriceWithoudTax }}</center></td>
                                                                <td style=" border: 1px solid black;"><center>{{ $product->quantity }}</center></td>
                                                                <td style=" border: 1px solid black;"><center>{{ $product->PriceWithoudTax }}</center></td>
                                                                <td style=" border: 1px solid black;"><center>{{ $product->productData->product_name }}</center></td>
                                                                <td style=" border: 1px solid black;" dir="ltr"><center>{{ $product->productData->Product_Code }}</center></td>
                                                                <td style=" border: 1px solid black;"><center>{{ $i }} </center></td>

                                                            </tr>
                                                        @endforeach



      



      </tbody>
    </table>
  </div>
  <br>
  <br>
  <div class='row' dir=rtl>
  
    <center>
      <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);width:100%">


        <tbody>
          <tr>

            <td style=" border: 1px solid black;" class="tx-18"> <center>{{ round($totalpricePurchases,2)}}  </center> </td>
            <td style=" border: 1px solid black;" class="tx-18">  <center> الاجمالي  SUB TOTAL </center> </td>

          </tr>
          <tr>
            <td style=" border: 1px solid black;" class="tx-18"> <center> {{ round($totaldiscount+$offer_price_to_customer->discount,2) }}</center></td>
            <td style=" border: 1px solid black;" class="tx-18" >  <center>  الخصم  DISCOUNT </center> </td>

          </tr>
          <tr>
            <td style=" border: 1px solid black;" class="tx-18">  <center> {{ round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2) }} </center></td>
            <td style=" border: 1px solid black;" class="tx-18">  <center> الاجمالي بعد الخصم SUB TOTAL AFTER DISCOUNT  </center></td>

          </tr>
          <tr>


            <td style=" border: 1px solid black;" class="tx-18"> <center> {{  round((round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2))* $avt->AVT,2)}} </center></td>
            <td style=" border: 1px solid black;" class="tx-18" >  <center> ضريبة القيمة المضافة({{$avt->AVT*100}}%) VALUE ADDED TAX 
            </center>  </td>
          </tr>


          <?php
          $total = round((round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2))* $avt->AVT,2)+(round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2)) ;
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
            <td style=" border: 1px solid black;" class="tx-18"> <center> {{number_format(round($total,2), 2, '.', '')}}
            
            <br>
              <p style="color:red;font-size:11px"> {{$result }} <span style="color:red;font-size:11px">Riyals</span>  &nbsp; and {{$decimal}} <span style="color:red;font-size:11px">Halala</span></p>
        </center>    </td>
            <td style=" border: 1px solid black;" class="tx-18">  <center> الاجمالي الكلي NET TOTAL </center></td>

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

</span>
  <br>

</span>
  <br>

  <div>

  </div>
  </div>
  <div style="  position: fixed;     
       text-align: center;    
       bottom: 0px; 
       width: 100%;">

    @if(Auth()->user()->branchs_id==1)

    <center> <span>
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