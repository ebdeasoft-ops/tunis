@extends('layouts.master')
@section('css')
<style>
@media print {
.hide-cell {
    display: none !important;
  }
    @page {

        size: auto;
        /* auto is the initial value */
        margin: 11mm 17mm 22mm 17mm;
        font-size: 28px;

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
        bottom: -2px;
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
        width: 250px;
        font-size: 15px !important;

    }

    tr {
        bgcolor="grey"
    }


    #reciptprinter {
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

.report-container {
    width: 100%;
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
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">

                    <br>
                    <br>
                    <div class="card-body">
                        <table class="report-container">

                            <thead class="report-header">
                                <tr>
                                    <th class="report-header-cell">
                                        <div class="invoice-header margintop"
                                            style="display: flex;justify-content:space-between;width:100%" dir="ltr">


                                            <div class="billed-from" style="width:33%;text-align: center;">
                                                <br>
                                                <span style="font-size:17px">{{Nameen}}</span>
                                                <br>
                                                <p dir=ltr> {{describtionen}} </p>
                                                <p dir=ltr>{{STen}} </p>
                                                <p dir=ltr> {{Taxen}} </p>

                                            </div>



                                            <div class="row">
                                                <?php
$logo=camplogo;
    ?>
                                                <a href=""><img src="{{ asset('assets\img\brand').'/'.$logo }}"
                                                        class="logo-1" alt="logo"
                                                        style="width: 110px; height: 100px;"></a>

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

                                        <center>
                                            <p class="double"> عرض تسعيرة <br>
                                                QUTATION</p>
                                        </center>
                                        <?php
     $offer_price_to_customer=[];
     if($itemsRequest!=[]){
     
$offer_price_to_customer=App\Models\offer_price_to_customer::find($id);
}

?>







                                        <br>

                                    </th>
                                </tr>
                            </thead>

                            <tbody class="report-content">
                                <tr>
                                    <td class="report-content-cell">

                                        <div class='row' style="justify-content: space-around;">
                                            <table style="border:2px solid rgba(0,0,0,.3);width:40%"
                                                class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                                                <thead>
                                                    <tr class="row12">

                                                        <th>CLIENT NAME <br>اسم العميل </th>
                                                        <th class="tx-16">{{$offer_price_to_customer->customer->name}}
                                                        </th>



                                                    </tr>

                                                    <tr>
                                                        <th class="tx-16">TAX NUMBER<br>
                                                            الرقم الضريبي </th>

                                                        <th class="tx-16">{{$offer_price_to_customer->customer->tax_no}}
                                                        </th>
                                                    </tr>

                                                </thead>

                                            </table>

                                            <table style="border:2px solid rgba(0,0,0,.3);width:40%"
                                                class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                                                <thead>



                                                    <tr>
                                                        <th class="tx-16"> Quote DATE<br>تاريخ التسعيرة </th>
                                                        <th class="tx-16">{{ $offer_price_to_customer->created_at}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="tx-16"> Quote NUMBER<br>رقم التسعيرة</th>
                                                        <th class="tx-16">{{ $offer_price_to_customer->id}}</th>
                                                    </tr>
                                                </thead>

                                            </table>

                                        </div>




                                        @if (isset($itemsRequest))
                                        <?php $i = 0; ?>
                                        <div class="col-xl-12">
                                            <div class="mg-b-20">

                                                <div class=" mg-t-20 mt-3 mr-2 d-flex ">
                                                    <table style="border:2px solid rgba(0,0,0,.3);width:100%"
                                                        class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>NO<br>رقم</th>

                                                                <th>Item NO <br> رقم منتج </th>

                                                                <th>ITEM NAME<br>اسم المنتج </th>
                                                                <th>PRODUCT PRICE<br>سعر المنتج</th>
                                                                <th> QUANTITY <br>الكمية </th>
                                                                 <th class="hide-cell">STOCK<br>مخزون</th>
                                                                <th class="tx-center "> UNIT <br>وحدة </th>

                                                                <th>TOTAL AMOUNT<br>الاجمالي</th>
                                                                <th> DISCOUNT<br>الخصم </th>
                                                                <th> TAX<br>الضريبة </th>
                                                                <th> Total <br>الاجمالي </th>



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
                                                                <td dir="ltr">{{ $product->productData->Product_Code }}
                                                                </td>
                                                                <td>{{ $product->productData->product_name }}</td>
                                                                <td>{{ $product->PriceWithoudTax }}</td>
                                                                <td>{{ $product->quantity }}</td>
                                                                <td class="hide-cell">{{ $product->productData->numberofpice }}</td>

                                                                <td>{{ $product->unit }}</td>
                                                                <td>{{ $product->quantity*$product->PriceWithoudTax }}
                                                                </td>
                                                                <td>{{ $product->discount }}</td>
                                                                <?php
                                                                $TOTAL=round(($product->quantity * ($product->PriceWithoudTax )) - $product->discount,2);
                                                                $added_tax=round($TOTAL*0.15,2) ;
                                                                ?>
                                                                <td>{{$added_tax }}</td>
                                                                <td>{{ $TOTAL+$added_tax }}</td>
                                                            <tr>
                                                                @endforeach
                                                        </tbody>
                                                    </table>

                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">







                                            <div class='row'>
                                                &nbsp;
                                                &nbsp;
                                                &nbsp; &nbsp;
                                                &nbsp;
                                                <table style="border:2px solid rgba(0,0,0,.3);width:50%"
                                                    class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                                                    <col style="width:20%">
                                                    <col style="width:15%">
                                                    <col style="width:20%">
                                                    <col style="width:20%">
                                                    <col style="width:25%">
                                                    <thead>
                                                        <tr>
                                                            <th class="tx-17">SUB TOTAL <br>الاجمالي</th>
                                                            <th class="tx-17">DISCOUNT <br>الخصم </th>
                                                            <th class="tx-17">SUB TOTAL AFTER DISCOUNT <br> الاجمالي بعد
                                                                الخصم</th>
                                                            <?php
                                $avt = App\Models\Avt::find(1);?>
                                                            <th class="tx-17">VALUE ADDED TAX ({{$avt->AVT*100}}%)
                                                                <br>

                                                                ضريبة القيمة المضافة({{$avt->AVT*100}}%)
                                                            </th>
                                                            <th class="tx-17">NET TOTAL<br>الاجمالي الكلي </th </tr>
                                                    </thead>

                                                    <body>
                                                        <td>{{ round($totalpricePurchases,2)}}</td>

                                                        <td>{{ round($totaldiscount+$offer_price_to_customer->discount,2) }}
                                                        </td>
                                                        <td>{{ round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2) }}
                                                        </td>
                                                        <td>{{  round((round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2))* $avt->AVT,2)}}
                                                        </td>
                                                        <td>{{( round((round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2))* $avt->AVT,2)+(round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2)) )}}
                                                        </td>
                                                        <?php

$total_value_quote=round((round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2))* $avt->AVT,2)+(round($totalpricePurchases,2)- round($totaldiscount+$offer_price_to_customer->discount,2));

?>
                                                    </body>

                                                </table>




                                            </div>




                                            &nbsp;
                                            &nbsp;
                                            &nbsp;
                                            &nbsp;
                                            <center>
                                                <p class="double"> {{bankname}} <br>Account Number :
                                                    {{bank_acount_number}} <br> IBAN Number : {{bank_acount_iban}}</P>
                                            </center>

                                        </div>
                                        @endif
                                        <br />
                                        </form>

                                        <br>
                                        <p>{{__('home.notesClient')}} : {{$offer_price_to_customer->notes}}</p>


                                        <br>
                                        <div class="row" style="display: flex;justify-content:center;width:100%">


                                            <button class="btn btn-danger float-left mt-3 mr-2 print-style p-1"
                                                id="print_Button" onclick="printDiv()">
                                                <i class="mdi mdi-printer ml-1"></i>{{__('home.print')}}</button>
                                            &nbsp;
                                            <a class="btn btn-danger float-left mt-3 mr-2 print-style p-1"
                                                id="print_Button" data-effect="effect-scale" data-id="{{$id}}"
                                                data-totalinvoice="{{$total_value_quote}}" data-toggle="modal"
                                                href="#paymentmethod"
                                                title="تعديل طريقة الدفع">{{ __('home.updateinvoicebyid') }}<i
                                                    class="las la-pen"></i></a>

                                        </div>

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
       text-align: center;    " @if(Auth()->user()->branchs_id==1)



                                    <center> <span> {{addressen}}
                                        </span>
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
<div class="modal" id="paymentmethod">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.paymentmethod') }} </h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label style="font-size:16px" class="control-label parent-label">&nbsp;&nbsp;{{ __('home.total') }}
                        :&nbsp; </label>
                    <label style="font-size:20px" id="totalvalue">0</label>
                    <label style="font-size:15px" class="control-label parent-label">&nbsp;{{ __('home.SAR') }}
                        &nbsp;&nbsp;&nbsp;&nbsp; </label>
                    <br>


                </div>
                <div class=" col">
                    <label> {{ __('home.paymentmethod') }} </label>
                    <br>

                    <select class="form-control" name="paymodal" id='paymodal' required>

                        <option value="Cash"> {{ __('report.cash') }}</option>
                        <option value="Shabka"> {{ __('report.shabka') }} </option>
                        <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                        <option value="Credit"> {{ __('report.credit') }} </option>
                        <option value="Partition"> {{ __('home.Partition of the amount') }} </option>

                    </select>


                </div>
                <br>
                <div class="row">

                    <div class="col">

                        <label class="control-label parent-label">{{ __('report.cash') }}</label>
                        <input type="text" class="form-control parent-input" name="cashamount" id="cashamount" readonly
                            value=0>
                    </div>

                    <div class="col">

                        <label class="control-label parent-label">{{ __('report.shabka') }}</label>
                        <input type="text" class="form-control parent-input" name="bankamount" id="bankamount" readonly
                            value=0>
                    </div>

                    <div class="col">

                        <label class="control-label parent-label">{{ __('home.Bank_transfer') }}</label>
                        <input type="text" class="form-control parent-input" name="Bank_transfer" id="Bank_transfer"
                            readonly value=0>
                    </div>
                    <div class="col">

                        <label class="control-label parent-label">{{ __('report.credit') }}</label><br>
                        <input type="text" class="form-control parent-input" name="creaditamount" id="creaditamount"
                            readonly value=0>
                    </div>
                    <input hidden type="text" id="invoiceid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="confirmpayment" name="confirmpayment" data-dismiss="modal"
                    class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
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

$('#paymentmethod').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var invoice = button.data('totalinvoice')
    $('#invoiceid').val(id)
    document.getElementById('totalvalue').innerHTML = invoice;
    $('#cashamount').val(invoice)

});

$("#confirmpayment").click(function(e) {


    if ($('#cashamount').val() == '') {
        $('#cashamount').val(0)
    }
    if ($('#bankamount').val() == '') {
        $('#bankamount').val(0)
    }
    if ($('#creaditamount').val() == '') {
        $('#creaditamount').val(0)
    }
    if ($('#Bank_transfer').val() == '') {
        $('#Bank_transfer').val(0)
    } else {

    }


    text = document.getElementById('totalvalue').innerText

    console.log(" {{URL::to('updatepaymentconfirmpayment_in_quotation')}}/" + $('#invoiceid').val() + '/' + $(
        '#cashamount').val() + '/' + $('#bankamount').val() + '/' + $('#creaditamount').val() + "/" + $(
        '#Bank_transfer').val() + '/' + $('#paymodal').val())
    if (Number(text) == (Number($('#cashamount').val()) + Number($('#Bank_transfer').val()) + Number($(
            '#bankamount').val()) + Number($('#creaditamount').val()))) {
        $.ajax({
            url: " {{URL::to('updatepaymentconfirmpayment_in_quotation')}}/" + $('#invoiceid').val() +
                '/' + $('#cashamount').val() + '/' + $('#bankamount').val() + '/' + $('#creaditamount')
                .val() + "/" + $('#Bank_transfer').val() + '/' + $('#paymodal').val(),
            type: "GET",
            dataType: "html",
            success: function(data) {

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

                alert("{{ __('home.sorryerror') }}")

            }

        })
    } else {
        $('#saveinvice').val(0);

        alert("{{ __('home.entermonycorrect') }}")

    }


})
</script>



@endsection