@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
           .double{
            border: 3px solid grey;
            border-radius: 5px;
            width:200px;

        }
    }
    </style>
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('report.report_returns_sale') }}@stop
@endsection
@section('page-header')
<div class="main-parent">

    <!-- breadcrumb -->
    @endsection
    @section('content')

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- row -->
   <div class="row row-sm">
    <div class="col-md-12 col-xl-12">
           <div class=" main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">
                    <br>
                    <br>
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
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>

                        </div>

  <div class="billed-from" style="width:33%;text-align: center;" >
                            <br>
                             <span style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p dir=ltr> {{describtionen}} </p>
                            <span dir=ltr>{{STen}} </span>
                            <p dir=ltr> {{Taxen}} </p>

                        </div>
                   
                    </div><!-- invoice-header -->
                    <br>
                    
                    <center><span class="double">مرتجع المبيعات - Sales returns</span></center>
                    <br>
                        @if (isset($data))
                 <div class='row' style="justify-content: space-around;">
                        <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                            <thead>
                                <tr class="row12">

                                    <th class="tx-16">CLIENT NAME <br>اسم العميل </th>
                                    <th class="tx-16">{{$data['invoiceData']->customer->name}}</th>



                                </tr>

                                <tr>
                                    <th class="tx-16">TAX NUMBER<br>
                                        الرقم الضريبي </th>

                                    <th class="tx-16">{{$data['invoiceData']->customer->tax_no}}</th>
                                </tr>
                                <tr>

                                    <th class="tx-16"> CLIENT ADDRESS<br>عنوان العميل </th>

                                    <th class="tx-16">{{$data['invoiceData']->customer->address}}</th>

                                </tr>
                            </thead>

                        </table>
    @foreach ($data['salesData'] as $product)
                                    <?php 
                                    $created_at=$product->created_at;
                                  ?>
@endforeach
                        <table style="border:2px solid rgba(0,0,0,.3);width:40%" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                            <thead>
                                <tr>


                                    <th class="tx-16">PAYMENT METHOD<br>طريقة الدفع </th>

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

                                    <th class="tx-16">{{$pay}}</th>





                                </tr>

                                <tr>
                                    <th class="tx-16"> INVOICE DATE<br>تاريخ الفاتورة </th>
                                    <th class="tx-16">{{ $created_at}}</th>
                                </tr>
                                <tr>
                                    <th class="tx-16"> INVOICE NUMBER<br>رقم الفاتورة</th>
                                    <th class="tx-16">{{ $data['invoiceData']->id}}</th>
                                </tr>
                                  <tr>
                                    <th class="tx-16"> NOTICE  NUMBER<br>رقم الاشعار</th>
                                    <th class="tx-16">-</th>
                                </tr>
                            </thead>

                        </table>

                    </div>

                        <div class="table mg-t-30 my-5">
                            <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center my-5">
                                <thead>
                                    <tr>
                                        <th class="wd-center">#</th>
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
                                    $totalprice = 0;
                                    $totalAddedValue = 0;
                                    $avtSaleRate = App\Models\Avt::find(1);
                                    $avtSale = $avtSaleRate->AVT;
                                    $discountoninvoice=0;
                                    $totalss=0;
                                    $totaldiscount=0;
                                    ?>

                                    @foreach ($data['salesData'] as $product)
                                    <?php $i++;
                                    $createdat=$product->created_at;
                                     $totalss+= ($product->return_Unit_Price * $product->return_quantity);
                                    $totaldiscount+=($product->discountvalue+$product->discountoninvoice);
                                    $totalprice += ($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue-$product->discountoninvoice;
                                    ?>

                                    <tr>
                                        <td class="wd-20p">{{$i}}</td>
                                        <td class="wd-center" dir="ltr">{{$product->productData->Product_Code}}</td>
                                        <td class="tx-center">{{ $product->productData->product_name}}</td>
                                        <td class="tx-center">{{ $product->return_Unit_Price}}</td>
                                        <td class="tx-center">{{ $product->return_quantity}}</td>
                                        <td class="tx-center">{{ $product->return_Unit_Price*$product->return_quantity}}</td>
                                        <td class="tx-center">{{ $product->discountvalue}}</td>
                                        <td class="tx-center">{{ ($product->return_Unit_Price*$product->return_quantity)-$product->discountvalue}}</td>

                                    </tr>
                                    @endforeach



                                </tbody>
                            </table>
                            <div class="row">
                            <div class="table-responsive mg-t-20  float-left mt-3 mr-2 table-padding text-center">
                                <table class="table table-invoice table-bordered table-striped">
                                  

                                    <body>
     <tr>

                        <th class="tx-18">الاجمالي - SUB TOTAL </th>
                        <th class="tx-18">{{number_format((float)(round($totalss,2)), 2, '.', '')}}</th>
                     </tr>
                     <tr>
                        <th class="tx-18">الخصم - DISCOUNT </th>
                        <th class="tx-18">{{number_format((float)(round($totaldiscount,2)), 2, '.', '')}}</th>
                     </tr>
                                        <tr>

                        <th class="tx-18">الاجمالي بعد الخصم- SUB TOTAL AFTER DISCOUNT </th>
                                            <td class="tx-">{{ round( $totalprice,2)}}</td>
                                        </tr>
                                        <tr>
                                            <?php
                                                              $avt = App\Models\Avt::find(1);

                                            ?>
                        <th class="tx-18">ضريبة القيمة المضافة({{$avt->AVT*100}}%) -VALUE ADDED TAX ({{$avt->AVT*100}}%)
                                            <td class="tx-">{{round( $totalprice*$avtSale,2)}}</td>
                                        </tr>


                                        <tr>
                        <th class="tx-18">الاجمالي الكلي -NET TOTAL</th>
                                            <td>{{ round( ($totalprice*$avtSale)+ $totalprice,2)}}</td>
                                        </tr>
                                    </body>

                                </table>
                           

 <div >
                                    <?php

                                    $avtSaleRate = App\Models\Avt::find(1);
                                    $avtSaleRate = $avtSaleRate->AVT;

                                    function ConvertToHEX($value)
                                    {
                                        return pack("H*", sprintf("%02X", $value));
                                    }
                                    $sellerName = sallerQrCode;
                                    $varNumber = TaxQrCode;
                                    $time = $createdat;
                                    $total =round( ($totalprice*$avtSale)+ $totalprice,2);
                                    $tax = round( $totalprice*$avtSale,2);
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

                                    $tobase   = $seller . $vat . $time . $total . $VATN;
                                    $dataforQRcode =  base64_encode($tobase);
                                    ?>
                                    {!! QrCode::size(120)->generate( $dataforQRcode) !!}
                                </div>
 </div>

                            @endif
                        </div>
<br>
                    <button class="btn btn-danger float-left mt-3 mr-2 print-style p-1" id="print_Button" onclick="printDiv()"> <i class="mdi mdi-printer ml-1"></i>طباعة</button>

                    </div>
                </div>
            </div>
        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>

<script>
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