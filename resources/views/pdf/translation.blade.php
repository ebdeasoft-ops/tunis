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

    .tx-center {
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



    th,
    td {
        /* text-align: left; */
        padding: 1px;
        text-align: center;

    }

    .border {
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

    .double {
        border: 3px solid grey;
        border-radius: 5px;
        width: 90%;
        font-size: 10px !important;

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
    <div style="display: flex;width:100%" dir=rtl>





        <?php
      $logo = camplogo;
      ?>




        <table>


            <tr class="heading">
                <td style="width:35%">
                    <center><span class="thick" style="font-size:12px">{{Nameen}}</span> </center>
                    <center><span class="tx-14 thick"> {{describtionen}} </span> </center>
                    <center><span class="tx-14 thick">{{STen}} </span> </center>
                    <center><span class="tx-14 thick"> {{Taxen}} </span> </center>

                </td>
                <td style="width:35%">
                    <center>
                        <img src="{{ public_path('assets/img/brand').'/'.$logo }}" style="width: 150px; height: 100px;">
                    </center>
                </td>

                <td style="width:30%">
                    <center><span style="font-size:12px">{{Namear}}</span> </center>
                    <center><span class="tx-14 thick"> {{describtionar}}</span> </center>
                    <center><span class="tx-14 thick">{{STar}}</span> </center>
                    <center><span class="tx-14 thick">{{Taxar}}</span> </center>

                </td>


            </tr>


        </table>
    </div><!-- invoice-header -->


    @if($data['invoiceData']->customer->id==1)
    <center>
        <p class="double"> Simplified tax invoice فاتورة ضريبية مبسطة </p>
    </center>

    @else
    <center>
        <p class="double"> فاتورة ضريية Tax Invoice </p>
    </center>

    @endif

    <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);width:100%; border-radius: 5px;" class="table double">
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


                <td class="tx-16">{{ $data['invoiceData']->id}}</td>
                <td class="tx-16"> INVOICE NUMBER<br>رقم الفاتورة</td>



                <td class="tx-16">{{$data['invoiceData']->branch->name}}</td>
                <td class="tx-16"> BRANCH NAME<br>اسم الفرع </td>


            </tr>
            <tr>


                <td class="tx-16">{{$pay}}</td>
                <td class="tx-16">PAYMENT METHOD <br>طريقة الدفع </td>







                <td class="tx-16">{{ $data['invoiceData']->created_at}}</td>
                <td class="tx-16"> INVOICE DATE<br>تاريخ الفاتورة </td>

            </tr>

        </tbody>

    </table>
    </div>
    <br>
    <table style="width:100%; border: 1px solid black;">

        <tr class="heading">

            <br>
            <td>

                </div>
                <div dir=rtl>
                    <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);width:100%; border-radius: 5px;">
                        <thead>
                            <tr class="row12">

                                <td class="tx-16">{{Namear}}</td>
                                <td class="tx-16">SELLER NAME اسم البائع </td>

                                <td class="tx-16">{{$data['invoiceData']->customer->name}}</td>

                                <td class="tx-16">CLIENT NAME اسم العميل </td>


                            </tr>

                            <tr>
                                <td class="tx-16">{{Taxen}}</td>
                                <td class="tx-16">TAX NUMBER الرقم الضريبي </td>

                                <td class="tx-16">
                                    {{$data['invoiceData']->customer->tax_no==0?'-':$data['invoiceData']->customer->tax_no}}
                                </td>

                                <td class="tx-16">TAX NUMBER الرقم الضريبي </td>


                            </tr>

                            <tr>
                                <td class="tx-16">{{ defined('city') ? city : '-' }}</td>
                                <td class="tx-16">المدينة CITY </td>


                                @if($data['invoiceData']->customer->address=='-')
                                <td class="tx-16">
                                    {{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->phone}}
                                </td>
                                <td class="tx-16">رقم الجوال PHONE </td>

                                @else





                                <td class="tx-16">
                                    {{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->address}}
                                </td>

                                <td class="tx-16">المدينة CITY </td>

                                @endif

                            </tr>
                            <tr>
                                <td class="tx-16">{{ defined('region') ? region : '-' }} </td>
                                <td class="tx-16">المنطقة REGION </td>

                                <td class="tx-16">
                                    {{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->sub_city}}
                                </td>

                                <td class="tx-16">المنطقة REGION </td>

                            </tr>
                            <tr>
                                <td class="tx-16"> {{ defined('street_name') ? street_name : '-' }}</td>
                                <td class="tx-16">اسم الشارع STREET NAME </td>

                                <td class="tx-16">
                                    {{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->street_name}}
                                </td>

                                <td class="tx-16">اسم الشارع STREET NAME </td>

                            </tr>
                            <tr>
                                <td class="tx-16">{{ defined('postal_number') ? postal_number : '-' }} </td>
                                <td class="tx-16"> الرمز البريدي POSTAL number </td>

                                <td class="tx-16">{{$data['invoiceData']->customer->postcode}}</td>

                                <td class="tx-16"> الرمز البريدي POSTAL number </td>

                            </tr>
                            <tr>
                                <td class="tx-16">{{ defined('building_number') ? building_number : '-' }} </td>


                                <td class="tx-16"> رقم المبني BUILDING NUMBER </td>

                                <td class="tx-16">
                                    {{$data['invoiceData']->customer->id==1?'-':$data['invoiceData']->customer->building_number}}
                                </td>

                                <td class="tx-16"> رقم المبني BUILDING NUMBER </td>

                            </tr>
                        </thead>

                    </table>


            </td>
        </tr>


    </table>






    <div dir="ltr">
        <table class="border" style="border: 1px solid black;
">
            <tbody>
                <tr>
                    <td style=" border: 1px solid black;" class="tx-center"> Total AFTER DISCOUNT<br>الاجمالي بعد الخصم
                    </td>
                    <td style=" border: 1px solid black;" class="tx-center"> DISCOUNT<br>الخصم </td>
                    <td style=" border: 1px solid black;" class="tx-center"> Total<br>الاجمالي </td>
                    <td style=" border: 1px solid black;" class="tx-center">PRODUCT PRICE<br>سعر القطعة </td>
                    <td style=" border: 1px solid black;" class="tx-center "> QUANTITY <br>الكمية </td>

                    <td style=" border: 1px solid black;" class="tx-center">ITEM NAME<br>اسم الصنف </td>
                    <td style=" border: 1px solid black;" class="wd-center">Item NO <br> رقم منتج </td>
                    <td style=" border: 1px solid black;" class="wd-center">NO<br>رقم</td>


                </tr>
            <tbody style=" border: 1px solid black;">



                <?php $i = 0;
        $discountreturn = 0;

        ?>
                @foreach (App\Models\sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                @if($product->quantity!=0)
                <?php $i++ ?>

                <tr>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{ number_format(($product->Unit_Price*$product->quantity)-$product->Discount_Value, 2, '.', '')}}
                    </td>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{ number_format($product->Discount_Value, 2, '.', '')}}</td>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{ number_format($product->Unit_Price*$product->quantity, 2, '.', '')}}</td>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{ number_format($product->Unit_Price, 2, '.', '')}}</td>
                    <td style=" border: 1px solid black;" class="tx-center hide-cell" id="print_Button">
                        {{$product->quantity}}</td>

                    <td style=" border: 1px solid black;" class="tx-center text">
                        {{ $product->productData->product_name}}</td>
                    <td style=" border: 1px solid black;" class="wd-center" dir="rtl">
                        {{$product->productData->Product_Code}}</td>

                    <td style=" border: 1px solid black;" class="wd-10p">{{$i}}</td>

                </tr>
                @endif
                @endforeach
                @foreach (App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                <?php $i++;
        //  $totalreturnprice+=$product->return_Unit_Price*$product->return_quantity;
        //  $totaladdedvalue+=$product->return_Added_Value*$product->return_quantity;
        $discountreturn += $product->discountvalue + $product->discountoninvoice;
        ?>
                @if($product->return_quantity!=0)
                <tr>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{ number_format((float)(($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue), 2, '.', '')}}
                    </td>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{ number_format((float)$product->discountvalue, 2, '.', '')}}</td>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{ number_format((float)$product->return_Unit_Price*$product->return_quantity, 2, '.', '')}}
                    </td>
                    <td style=" border: 1px solid black;" class="tx-center hide-cell" id="print_Button">
                        {{ $product->return_quantity}}</td>
                    <td style=" border: 1px solid black;" class="tx-center">
                        {{number_format( $product->return_Unit_Price , 2, '.', '')}}</td>
                    <td style=" border: 1px solid black;" class="tx-center text">
                        {{ $product->productData->product_name}}</td>
                    <td style=" border: 1px solid black;" class="wd-center" dir="rtl">
                        {{$product->productData->Product_Code}}</td>

                    <td style=" border: 1px solid black;" class="wd-10p" style="color:red">{{$i}}</td>

                </tr>
                @endif
                @endforeach



            </tbody>
        </table>
    </div>
    <br>

    <div class='row' dir=ltr>
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
        <center>
            <p class="double"> يمكن ارجاع القطع المباعة او استبدالها خلال 7 ايام من تاريخ الشراء وتكون بحالتها المباعة و
                <br>القطع الكهربائية لا ترد ولا تستبدل
            </P>
        </center>
        <div class="row" dir=rtl>


            <table style="width:110%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                <tr>



                    <td>


                        <table style="border:2px solid rgba(0,0,0,.3);width:100%"
                            class="table text-md-nowrap mb-0 table-striped invoice-table text-center">


                            <thead>
                                <tr>

                                    <td class="tx-16">
                                        {{number_format((float)(round($invoicetotal_price,2)+round($invoicetotal_discount,2)), 2, '.', '')}}
                                    </td>
                                    <td class="tx-16">الاجمالي - SUB TOTAL </td>

                                </tr>
                                <tr>
                                    <td class="tx-16">{{number_format(round($invoicetotal_discount,2), 2, '.', '')}}
                                    </td>
                                    <td class="tx-16">الخصم - DISCOUNT </td>

                                </tr>
                                <tr>
                                    <td class="tx-16">{{number_format(round($invoicetotal_price,2), 2, '.', '')}}</td>
                                    <td class="tx-16">الاجمالي بعد الخصم<br>SUB TOTAL AFTER DISCOUNT </td>

                                </tr>
                                <tr>


                                    <td class="tx-16">{{number_format(round($invoicetotal_addedvalue,2), 2, '.', '')}}
                                    </td>
                                    <td class="tx-16">ضريبة القيمة المضافة({{$avt->AVT*100}}%)<br>VALUE ADDED TAX
                                        ({{$avt->AVT*100}}%)
                                    </td>
                                </tr>



                                <tr>
                                    <td class="tx-16">
                                        {{number_format(round($invoicetotal_addedvalue+$invoicetotal_price,2), 2, '.', '')}}

                                    </td>
                                    <td class="tx-16">الاجمالي الكلي -NET TOTAL</td>

                                </tr>
                            </thead>


                        </table>
                    </td>
                    <td>

                        @if(Auth()->user()->branchs_id==1)

                        <center>
                            <p class="double"> {{bankname}} <br>Account Number : {{bank_acount_number}} <br> IBAN Number
                                : {{bank_acount_iban}}</P>
                        </center>

                        @endif

                    </td>

                    <td>


                        <img
                            src="data:image/png;base64,{!! base64_encode(QrCode::size(110)->generate( $dataforQRcode) )!!}">


                    </td>

                </tr>
            </table>
            <div>


            </div>

            <div>


            </div>
        </div>

    </div>
    <br>

    <center>

    </center>

    </div>
    <br>
    <span style="color:black">
        <center>

        </center>
    </span>
    <br>

    <span class="tx-16 ">{{__('home.notesClient')}} : {{$data['invoiceData']->note}}</span>
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