@extends('layouts.master')
@section('css')
<style>
    @media print {
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact;
        
        @page {
            size: A4 landscape; /* تحديد حجم ورقة الطباعة أفقي ليتسع الجدول */
            margin: 10mm;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            font-size: 12px !important;
            color: #000;
        }

        #print_Button, .excel-btn-group {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        th {
            background-color: #f2f2f2 !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
        }

        .print-total-row {
            background-color: #e8f4f8 !important;
            -webkit-print-color-adjust: exact;
        }
    }

    /* تنسيقات عامة احترافية للشاشة */
    .double {
        border: 2px solid #419BB2;
        border-radius: 8px;
        padding: 6px 20px;
        font-weight: bold;
        color: #419BB2;
        background-color: #f8f9fa;
        font-size: 16px !important;
    }

    .invoice-header {
        border-bottom: 2px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .table th {
        vertical-align: middle !important;
        background-color: #f8f9fa;
    }

    /* تنسيق مميز لصف الإجماليات للشاشة */
    .print-total-row {
        background-color: #e8f4f8;
        font-weight: bold;
        font-size: 15px;
        color: #2c3e50;
        border-top: 2px solid #419BB2;
        border-bottom: 2px solid #419BB2;
    }
</style>
@endsection

@section('title')
{{ __('home.print') }} - {{ __('reports.sales') }}
@stop

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body main-content-body-invoice" id="print" dir="rtl">
            <div class="card card-invoice p-4 shadow-sm">
                
                <!-- أزرار التحكم (طباعة وتصدير إكسيل) -->
                <div class="d-flex justify-content-between align-items-center mb-4 excel-btn-group">
                    <div>
                        <button class="btn btn-danger print-style" id="print_Button" onclick="printDiv()">
                            {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                        </button>
                        <button class="btn btn-success" id="export_Excel" onclick="exportTableToExcel('salesTable', 'Sales_Report')">
                            تصدير Excel <i class="mdi mdi-file-excel ml-1"></i>
                        </button>
                    </div>
                </div>

                <!-- رأس الفاتورة / التقرير -->
                <div class="invoice-header d-flex justify-content-between align-items-center w-100">
                    <div class="billed-from text-right" style="width:33%;">
                        <span style="font-size:16px; font-weight:bold;">{{ Namear ?? '' }}</span>
                        <p class="mb-1 text-muted">{{ describtionar ?? '' }}</p>
                        <p class="mb-1 text-muted">{{ STar ?? '' }}</p>
                        <p class="mb-0 text-muted">{{ Taxar ?? '' }}</p>
                    </div>

                    <div class="text-center" style="width:33%;">
                        @php $logo = camplogo ?? ''; @endphp
                        <a href="https://ebdeasoft.com/">
                            <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="max-height: 80px; object-fit: contain;">
                        </a>
                    </div>

                    <div class="billed-from text-left" style="width:33%;">
                        <span style="font-size:16px; font-weight:bold;">{{ Nameen ?? '' }}</span>
                        <p class="mb-1 text-muted">{{ describtionen ?? '' }}</p>
                        <p class="mb-1 text-muted">{{ STen ?? '' }}</p>
                        <p class="mb-0 text-muted">{{ Taxen ?? '' }}</p>
                    </div>
                </div>

                <!-- عنوان التقرير -->
                <div class="text-center my-3">
                    <span class="double">المبيعات &nbsp; Sales</span>
                </div>

                <!-- جدول تاريخ التقرير -->
                <div class="table-responsive my-3">
                    <table class="table table-bordered text-center" style="background: #fdfdfd;">
                        <tr>
                            <th style="width: 25%; color: #419BB2;">{{ __('report.fromdate') }}:</th>
                            <td style="width: 25%; font-weight: bold;">{{ $start?? '' }}</td>
                            <th style="width: 25%; color: #419BB2;">{{ __('report.todate') }}:</th>
                            <td style="width: 25%; font-weight: bold;">{{ $end ?? '' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- جدول البيانات الرئيسي -->
                <div class="card-body p-0 mt-3">
                    @php
                        $totalPriceAll = 0;
                        $totalAddedValueAll = 0;
                        $totalDiscountAll = 0;
                        $totalPriceAfterDiscountAll = 0;
                        
                        $avt = App\Models\Avt::find(1);
                        $taxRate = $avt ? $avt->AVT : 0.15;
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="salesTable" style="width:100%">
                            <thead class="thead-light">
                                <tr style="background-color: #419BB2; color: #fff;">
                                    <th class="border-bottom-0 text-white">{{ __('home.Invoice_no') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.date') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.clietName') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.paymentmethod') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.total') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.discount') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.totalafterdiscount') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.avt') }}</th>
                                    <th class="border-bottom-0 text-white">{{ __('home.totalwithTax') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Invoices as $product)
                                    @php
                                        $currentInvoicePrice = ($product->cashamount + $product->bankamount + $product->Bank_transfer + $product->creaditamount);
                                        
                                        $invoiceDiscount = 0;
                                        $discound_return_sales = App\Models\return_sales::where('invoice_id', $product->id)->sum('discountvalue');
                                        $discount_sales = App\Models\sales::where('invoice_id', $product->id)->sum('Discount_Value');
                                        
                                        $invoiceDiscount += $discound_return_sales;
                                        $invoiceDiscount += $discount_sales;
                                        $invoiceDiscount += $product->discount == 0 ? $product->discountOnInvoice : ($product->discount - $discount_sales - $discound_return_sales);

                                        $priceBeforeTax = $currentInvoicePrice / (1 + $taxRate);
                                        $taxAmount = $currentInvoicePrice - $priceBeforeTax;

                                        $totalPriceAll += $currentInvoicePrice;
                                        $totalDiscountAll += $invoiceDiscount;
                                        $totalAddedValueAll += $taxAmount;
                                        $totalPriceAfterDiscountAll += $priceBeforeTax;

                                        $paymentMethod = match($product->Pay) {
                                            'Cash' => __('report.cash'),
                                            'Shabka' => __('report.shabka'),
                                            'Credit' => __('report.credit'),
                                            'Bank_transfer' => __('home.Bank_transfer'),
                                            default => __('home.Partition of the amount'),
                                        };
                                    @endphp

                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->created_at }}</td>
                                        <td>{{ $product->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $paymentMethod }}</td>
                                        <td>{{ number_format($priceBeforeTax + $invoiceDiscount, 2) }}</td>
                                        <td>{{ number_format($invoiceDiscount, 2) }}</td>
                                        <td>{{ number_format($priceBeforeTax, 2) }}</td>
                                        <td>{{ number_format($taxAmount, 2) }}</td>
                                        <td>{{ number_format($currentInvoicePrice, 2) }}</td>
                                    </tr>
                                @endforeach

                                <!-- صف الإجماليات الملون والبارز -->
                                <tr class="print-total-row">
                                    <td colspan="4" class="text-right font-weight-bold">{{ __('home.total') }}</td>
                                    <td>{{ number_format($totalPriceAfterDiscountAll + $totalDiscountAll, 2) }}</td>
                                    <td>{{ number_format($totalDiscountAll, 2) }}</td>
                                    <td>{{ number_format($totalPriceAfterDiscountAll, 2) }}</td>
                                    <td>{{ number_format($totalAddedValueAll, 2) }}</td>
                                    <td>{{ number_format($totalPriceAll, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
   function printDiv() {
      var printContents = document.getElementById('print').innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
      location.reload();
   }

   // دالة تصدير Excel مع تلوين الهيدر وصف الإجماليات بشكل مباشر
   function exportTableToExcel(tableID, filename = '') {
      var downloadLink;
      var dataType = 'application/vnd.ms-excel;charset=utf-8;';
      var tableSelect = document.getElementById(tableID);
      
      // استنساخ الجدول وتعديل الألوان لضمان ظهورها داخل ملف الإكسيل
      var clonedTable = tableSelect.cloneNode(true);
      
      // تلوين الهيدر في النسخة المصدرة
      var headers = clonedTable.querySelectorAll('thead th');
      headers.forEach(function(th) {
         th.style.backgroundColor = "#419BB2";
         th.style.color = "#FFFFFF";
         th.style.textAlign = "center";
         th.style.fontWeight = "bold";
      });

      // تلوين صف الإجماليات في النسخة المصدرة
      var rows = clonedTable.querySelectorAll('tbody tr');
      if (rows.length > 0) {
         var lastRow = rows[rows.length - 1];
         lastRow.style.backgroundColor = "#E8F4F8";
         lastRow.style.fontWeight = "bold";
         var cells = lastRow.querySelectorAll('td');
         cells.forEach(function(td) {
            td.style.backgroundColor = "#E8F4F8";
            td.style.color = "#000000";
            td.style.fontWeight = "bold";
         });
      }

      var tableHTML = '<html dir="rtl"><head><meta charset="utf-8"><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; text-align: center; font-family: Cairo, sans-serif; }</style></head><body>' + clonedTable.outerHTML + '</body></html>';
      
      var blob = new Blob(['\ufeff' + tableHTML], { type: dataType });
      
      if (navigator.msSaveOrOpenBlob) {
          navigator.msSaveOrOpenBlob(blob, filename + '.xls');
      } else {
          downloadLink = document.createElement("a");
          downloadLink.href = URL.createObjectURL(blob);
          downloadLink.download = filename + '.xls';
          document.body.appendChild(downloadLink);
          downloadLink.click();
          document.body.removeChild(downloadLink);
      }
   }
</script>
@endsection