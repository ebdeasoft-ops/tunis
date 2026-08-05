@extends('layouts.master')

@section('css')
<style>
    @media print {
        .text {
            width: 320px;
            overflow: hidden;
            white-space: pre-wrap;
            text-overflow: ellipsis;
        }
        @page {
            size: auto;
            margin: 5mm 2mm 18mm 2mm;
        }
        header, tfoot {
            display: table-header-group;
        }
        #print_Button, #export_Button {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
        background-color: #f8f9fa;
    }

    .invoice-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .invoice-header-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-bottom: 2px solid #eee;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .table-custom th, .table-custom td {
        vertical-align: middle !important;
    }
</style>
@endsection

@section('title')
{{ __('home.print') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        
        <!-- أزرة التحكم (طباعة وتصدير) -->
        <div class="d-flex justify-content-center mb-3">
            <button class="btn btn-danger float-left mt-3 mr-2 shadow-sm" id="print_Button" onclick="printDiv()">
                {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
            </button>
            <button class="btn btn-success float-left mt-3 shadow-sm" id="export_Button" onclick="exportExcel()">
                تصدير اكسيل <i class="mdi mdi-file-excel ml-1"></i>
            </button>
        </div>

        <div class="main-content-body-invoice" id="print">
            <div class="card invoice-card">
                <div class="card-body">
                    
                    <?php
                    $Invoices = $data['invoices'];
                    function ConvertToHEX($value) {
                        return pack("H*", sprintf("%02X", $value));
                    }
                    $coun = 0;
                    $y = count($Invoices);
                    ?>

                    @if (isset($Invoices))
                        @foreach ($Invoices as $invoice)
                            <?php $coun++; ?>

                            <!-- رأس الفاتورة -->
                            <div class="invoice-header-box">
                                <div style="width:33%; text-align: center;">
                                    <span style="font-size:16px; font-weight: bold;">{{Namear}}</span>
                                    <p class="mb-1 text-muted">{{describtionar}}</p>
                                    <p class="mb-1">{{STar}}</p>
                                    <p class="mb-0">{{Taxar}}</p>
                                </div>
                                
                                <div style="width:33%; text-align: center;">
                                    <?php $logo = camplogo; ?>
                                    <a href="https://ebdeasoft.com/">
                                        <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 100px; height: 90px; object-fit: contain;">
                                    </a>
                                </div>

                                <div style="width:33%; text-align: center;">
                                    <span style="font-size:16px; font-weight: bold;">{{Nameen}}</span>
                                    <p class="mb-1 text-muted" dir="ltr">{{describtionen}}</p>
                                    <span dir="ltr">{{STen}}</span>
                                    <p class="mb-0" dir="ltr">{{Taxen}}</p>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <h5 class="font-weight-bold text-primary">{{ $invoice->branch->name }}</h5>
                                <h4 class="font-weight-bold text-dark">فاتورة ضريبية - Tax Invoice</h4>
                            </div>

                            @if($invoice->note)
                                <div class="alert alert-light border mb-3">
                                    <strong>{{__('home.notesClient')}} :</strong> {{$invoice->note}}
                                </div>
                            @endif

                            <!-- بيانات العميل ومعلومات الفاتورة -->
                            <div class="row mb-4" style="justify-content: space-between;">
                                <table style="border:1px solid #dee2e6; width:48%;" class="table table-sm table-bordered mb-0 text-center table-custom">
                                    <thead>
                                        <tr>
                                            <th class="bg-light w-50">اسم العميل <br> CLIENT NAME</th>
                                            <td class="font-weight-bold">{{$invoice->customer->name}}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">الرقم الضريبي <br> TAX NUMBER</th>
                                            <td>{{$invoice->customer->tax_no}}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">عنوان العميل <br> CLIENT ADDRESS</th>
                                            <td>{{$invoice->customer->address}}</td>
                                        </tr>
                                    </thead>
                                </table>

                                <table style="border:1px solid #dee2e6; width:48%;" class="table table-sm table-bordered mb-0 text-center table-custom">
                                    <thead>
                                        <tr>
                                            <th class="bg-light w-50">طريقة الدفع <br> PAYMENT METHOD</th>
                                            <?php
                                            $pay = '';
                                            if ($invoice->Pay == "Cash") { $pay = __('report.cash'); }
                                            elseif ($invoice->Pay == "Shabka") { $pay = __('report.shabka'); }
                                            elseif ($invoice->Pay == "Credit") { $pay = __('report.credit'); }
                                            elseif ($invoice->Pay == "Bank_transfer") { $pay = __('home.Bank_transfer'); }
                                            else { $pay = __('home.Partition of the amount'); }
                                            ?>
                                            <td class="font-weight-bold">{{$pay}}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">تاريخ الفاتورة <br> INVOICE DATE</th>
                                            <td>{{$invoice->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">رقم الفاتورة <br> INVOICE NUMBER</th>
                                            <td>{{$invoice->id}}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <!-- جدول المنتجات -->
                            <div class="table-responsive mg-t-20 mb-4">
                                <table style="border:1px solid #dee2e6;" class="table table-bordered table-striped text-center table-custom">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>رقم <br> NO</th>
                                            <th>رقم منتج <br> Item NO</th>
                                            <th>اسم الصنف <br> ITEM NAME</th>
                                            <th>سعر القطعة <br> PRICE</th>
                                            <th>الكمية <br> QTY</th>
                                            <th>الاجمالي <br> TOTAL</th>
                                            <th>الخصم <br> DISCOUNT</th>
                                            <th>الإجمالي بعد الخصم <br> NET TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 0;
                                        $discountreturn = 0;
                                        ?>

                                        @foreach (App\Models\sales::where('invoice_id', $invoice->id)->get() as $product)
                                            <?php $i++; ?>
                                            @if($product->quantity != 0)
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td dir="ltr">{{$product->productData->Product_Code}}</td>
                                                <td class="text text-right">{{ $product->productData->product_name}}</td>
                                                <td>{{ number_format($product->Unit_Price, 2) }}</td>
                                                <td>{{ $product->quantity}}</td>
                                                <td>{{ number_format($product->Unit_Price * $product->quantity, 2) }}</td>
                                                <td>{{ number_format($product->Discount_Value, 2) }}</td>
                                                <td>{{ number_format(($product->Unit_Price * $product->quantity) - $product->Discount_Value, 2) }}</td>
                                            </tr>
                                            @endif
                                        @endforeach

                                        @foreach (App\Models\return_sales::where('invoice_id', $invoice->id)->get() as $product)
                                            <?php 
                                            $i++;
                                            $discountreturn += $product->discountvalue + $product->discountoninvoice;
                                            ?>
                                            @if($product->return_quantity != 0)
                                            <tr class="text-danger">
                                                <td>{{$i}}</td>
                                                <td dir="ltr">{{$product->productData->Product_Code}}</td>
                                                <td class="text text-right">{{ $product->productData->product_name}} (مرتجع)</td>
                                                <td>{{ number_format($product->return_Unit_Price, 2) }}</td>
                                                <td>{{ $product->return_quantity}}</td>
                                                <td>{{ number_format($product->return_Unit_Price * $product->return_quantity, 2) }}</td>
                                                <td>{{ number_format($product->discountvalue, 2) }}</td>
                                                <td>{{ number_format(($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue, 2) }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- QR Code وملخص الحسابات -->
                            <div class="row align-items-center justify-content-between mt-4">
                                <div class="col-md-4 text-center mb-3">
                                    <?php
                                    $price = $invoice->cashamount + $invoice->Bank_transfer + $invoice->bankamount + $invoice->creaditamount;
                                    $avt = App\Models\Avt::find(1);

                                    $price_befor_tax = $price * 100 / (100 + ($avt->AVT * 100));
                                    $invoicetotal_addedvalue = ($price_befor_tax) * $avt->AVT;
                                    $invoicetotal_price = $price_befor_tax;
                                    $invoicetotal_discount = $invoice->discount + $discountreturn;

                                    $sellerName = sallerQrCode;
                                    $varNumber = TaxQrCode;
                                    $time = $invoice->created_at;
                                    $issue_time = substr($time, 11);
                                    $issue_date = substr($time, 0, 10);
                                    $time = (string)$issue_date . 'T' . (string)$issue_time;

                                    $total = (round($invoicetotal_addedvalue + $invoicetotal_price, 2));
                                    $tax = round($invoicetotal_addedvalue, 2);
                                    
                                    $HexSeller = ConvertToHEX(1) . ConvertToHEX(strlen($sellerName));
                                    $seller = $HexSeller . $sellerName;
                                    $HexVAT = ConvertToHEX(2) . ConvertToHEX(strlen($varNumber));
                                    $vat = $HexVAT . $varNumber;
                                    $HexTime = ConvertToHEX(3) . ConvertToHEX(strlen($time));
                                    $time = $HexTime . $time;
                                    $HexTotal = ConvertToHEX(4) . ConvertToHEX(strlen($total));
                                    $total = $HexTotal . $total;
                                    $HexVATN = ConvertToHEX(5) . ConvertToHEX(strlen($tax));
                                    $VATN = $HexVATN . $tax;

                                    $empty = '';
                                    $Hexempty = ConvertToHEX(6) . ConvertToHEX(strlen($empty));
                                    $empty6 = $Hexempty . $empty;
                                    $Hexempty = ConvertToHEX(7) . ConvertToHEX(strlen($empty));
                                    $empty7 = $Hexempty . $empty;
                                    $Hexempty = ConvertToHEX(8) . ConvertToHEX(strlen($empty));
                                    $empty8 = $Hexempty . $empty;
                                    $Hexempty = ConvertToHEX(9) . ConvertToHEX(strlen($empty));
                                    $empty9 = $Hexempty . $empty;
                                    $tobase = $seller . $vat . $time . $total . $VATN . $empty6 . $empty7 . $empty8 . $empty9;
                                    $dataforQRcode = base64_encode($tobase);
                                    ?>
                                    {!! QrCode::size(110)->generate($dataforQRcode) !!}
                                </div>

                                <div class="col-md-7">
                                    <table style="border:1px solid #dee2e6;" class="table table-sm table-bordered mb-0 table-custom">
                                        <tr>
                                            <th class="bg-light">الاجمالي - SUB TOTAL</th>
                                            <td class="text-center font-weight-bold">{{ number_format(round($invoicetotal_price, 2) + round($invoicetotal_discount, 2), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">الخصم - DISCOUNT</th>
                                            <td class="text-center font-weight-bold text-danger">{{ number_format(round($invoicetotal_discount, 2), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">الاجمالي بعد الخصم - SUB TOTAL AFTER DISCOUNT</th>
                                            <td class="text-center font-weight-bold">{{ number_format(round($invoicetotal_price, 2), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">ضريبة القيمة المضافة ({{$avt->AVT*100}}%) - VAT</th>
                                            <td class="text-center font-weight-bold">{{ number_format(round($invoicetotal_addedvalue, 2), 2) }}</td>
                                        </tr>
                                        <tr class="table-active">
                                            <th class="font-weight-bold">الاجمالي الكلي - NET TOTAL</th>
                                            <td class="text-center font-weight-bold text-success" style="font-size: 1.1rem;">
                                                {{ number_format(round($invoicetotal_addedvalue + $invoicetotal_price, 2), 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- تذييل الصفحة / العنوان -->
                            @if(Auth()->user()->branchs_id == 1)
                                <div class="text-center mt-5 pt-3 border-top text-muted" style="font-size: 11pt;">
                                    <p class="mb-1">{{addressar}}</p>
                                    <p class="mb-0">{{addressen}}</p>
                                </div>
                            @endif

                            @if($coun != $y)
                                <p style="page-break-after: always;"></p>
                            @endif

                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
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

   function exportExcel() {
      var printContents = document.getElementById('print').innerHTML;
      var tempDiv = document.createElement('div');
      tempDiv.innerHTML = printContents;
      
      var printBtn = tempDiv.querySelector('#print_Button');
      var exportBtn = tempDiv.querySelector('#export_Button');
      if (printBtn) printBtn.remove();
      if (exportBtn) exportBtn.remove();

      var dataType = 'application/vnd.ms-excel;charset=utf-8';
      var tableHTML = '<html dir="rtl"><head><meta charset="utf-8"><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #000; padding: 5px; text-align: center; }</style></head><body>' + tempDiv.innerHTML + '</body></html>';
      
      var filename = 'invoice_report.xls';
      var downloadLink = document.createElement("a");
      
      document.body.appendChild(downloadLink);
      
      if (navigator.msSaveOrOpenBlob) {
          var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
          navigator.msSaveOrOpenBlob(blob, filename);
      } else {
          downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(tableHTML);
          downloadLink.download = filename;
          downloadLink.click();
          document.body.removeChild(downloadLink);
      }
   }
</script>
@endsection