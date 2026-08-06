@extends('layouts.master')

@section('css')
<style>
    /* تنسيقات الطباعة الفاخرة */
    @media print {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;

        @page {
            size: auto;
            margin: 5mm;
        }

        body {
            font-family: 'Cairo', sans-serif !important;
            background-color: #fff !important;
        }

        #print_Button, .print-style {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    /* تنسيق الإطارات والعناوين */
    .double {
        border: 2px solid #6c757d;
        border-radius: 6px;
        padding: 6px 20px;
        font-size: 16px !important;
        font-weight: bold;
        background-color: #f8f9fa;
        display: inline-block;
        color: #333;
    }

    .invoice-header img {
        max-width: 100px;
        height: auto;
    }

    .table th {
        vertical-align: middle !important;
    }
</style>
@endsection

@section('title')
{{ __('home.sales_and_return') }}
@stop

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print" dir="rtl">
            <div class="card card-invoice p-3">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center mb-3">
                    <button class="btn btn-danger print-style mt-3" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body">
                    <!-- ترويسة الفاتورة (البيانات واللوجو) -->
                    <div class="invoice-header d-flex justify-content-between align-items-center w-100 mb-4" dir="rtl">
                        <div class="billed-from text-center" style="width:33%;">
                            <span class="font-weight-bold" style="font-size:16px">{{ Namear ?? '' }}</span>
                            <p class="mb-1 text-muted">{{ describtionar ?? '' }}</p>
                            <p class="mb-1">{{ STar ?? '' }}</p>
                            <p class="mb-0">{{ Taxar ?? '' }}</p>
                        </div>

                        <div class="text-center" style="width:33%;">
                            @php $logo = camplogo ?? 'default.png'; @endphp
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1" alt="logo">
                            </a>
                        </div>

                        <div class="billed-from text-center" style="width:33%;">
                            <span class="font-weight-bold" style="font-size:16px">{{ Nameen ?? '' }}</span>
                            <p class="mb-1 text-muted">{{ describtionen ?? '' }}</p>
                            <p class="mb-1">{{ STen ?? '' }}</p>
                            <p class="mb-0">{{ Taxen ?? '' }}</p>
                        </div>
                    </div>

                    <!-- عنوان التقرير -->
                    <div class="text-center my-3">
                        <span class="double">{{ __('home.sales_and_return') }}</span>
                    </div>

                    <!-- جدول فترة التقرير -->
                    <div class="table-responsive my-3">
                        <table class="table table-bordered text-center">
                            <tr>
                                <th style="background-color: #ecf0fa; color: #419BB2; width:25%;">{{ __('report.fromdate') }}</th>
                                <th style="background-color: #ecf0fa; color: #419BB2; width:25%;">{{ $start }}</th>
                                <th style="background-color: #ecf0fa; color: #419BB2; width:25%;">{{ __('report.todate') }}</th>
                                <th style="background-color: #ecf0fa; color: #419BB2; width:25%;">{{ $end }}</th>
                            </tr>
                        </table>
                    </div>

                    <!-- قسم المبيعات -->
                    <div class="text-center my-4">
                        <span class="double">{{ __('home.sales') }}</span>
                    </div>

                    @php
                        $totalprice = 0;
                        $totaldiscount = 0;
                        $totaladdedvalue = 0;
                        $totalpriceafterdiscount = 0;
                        $avtModel = App\Models\Avt::find(1);
                        $saleavt = $avtModel ? $avtModel->AVT : 0;
                    @endphp

                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered text-center" style="width:100%">
                            <thead>
                                <tr style="color: #FF4F1F; font-size:13px;">
                                    <th>{{ __('home.Invoice_no') }}</th>
                                    <th>{{ __('home.date') }}</th>
                                    <th>{{ __('home.clietName') }}</th>
                                    <th>{{ __('home.paymentmethod') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                    <th>{{ __('home.discount') }}</th>
                                    <th>{{ __('home.totalafterdiscount') }}</th>
                                    <th>{{ __('home.avt') }}</th>
                                    <th>{{ __('home.totalwithTax') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['invoices'] as $product)
                                    @php
                                        $price = ($product->cashamount + $product->bankamount + $product->Bank_transfer + $product->creaditamount);
                                        
                                        // حساب الخصم والمرتجعات المرتبطة بالفاتورة بشكل آمن
                                        $discoundoninvoice = 0;
                                        $discountreturn = 0;
                                        
                                        if ($product->Number_of_Quantity == 0) {
                                            foreach (App\Models\return_sales::where('invoice_id', $product->id)->get() as $item) {
                                                $discoundoninvoice += $item->discountoninvoice;
                                            }
                                        } else {
                                            $discoundoninvoice = $product->discount - $product->discountOnProduct;
                                        }

                                        foreach (App\Models\return_sales::where('invoice_id', $product->id)->get() as $item) {
                                            $discountreturn += $item->discountvalue;
                                        }
                                        foreach (App\Models\sales::where('invoice_id', $product->id)->get() as $item) {
                                            $discountreturn += $item->Discount_Value;
                                        }
                                        $discountreturn += $discoundoninvoice;

                                        $price_befor_tax = $saleavt > 0 ? ($price * 100) / (100 + ($saleavt * 100)) : $price;
                                        $invoicetotal_addedvalue = $price_befor_tax * $saleavt;
                                        
                                        $totalprice += $price;
                                        $totaldiscount += $discountreturn;
                                        $totaladdedvalue += $invoicetotal_addedvalue;
                                        $totalpriceafterdiscount += $price_befor_tax;

                                        // ترجمة طريقة الدفع
                                        $pays = match($product->Pay) {
                                            'Cash' => __('report.cash'),
                                            'Shabka' => __('report.shabka'),
                                            'Credit' => __('report.credit'),
                                            'Bank_transfer' => __('home.Bank_transfer'),
                                            default => __('home.Partition of the amount')
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->created_at }}</td>
                                        <td>{{ optional($product->customer)->name }}</td>
                                        <td>{{ $pays }}</td>
                                        <td>{{ number_format($price_befor_tax + $discountreturn, 2) }}</td>
                                        <td>{{ number_format($discountreturn, 2) }}</td>
                                        <td>{{ number_format($price_befor_tax, 2) }}</td>
                                        <td>{{ number_format($invoicetotal_addedvalue, 2) }}</td>
                                        <td>{{ number_format($price, 2) }}</td>
                                    </tr>
                                @endforeach

                                <!-- صف المجاميع للمبيعات -->
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="4">المجموع الكلي</td>
                                    <td>{{ number_format($totaldiscount + $totalpriceafterdiscount, 2) }}</td>
                                    <td>{{ number_format($totaldiscount, 2) }}</td>
                                    <td>{{ number_format($totalpriceafterdiscount, 2) }}</td>
                                    <td>{{ number_format($totaladdedvalue, 2) }}</td>
                                    <td>{{ number_format($totalprice, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- قسم المرتجعات -->
                    @if (isset($data['returnsales']) && count($data['returnsales']) > 0)
                        <div class="text-center my-4">
                            <span class="double">{{ __('home.salesـreturned') }}</span>
                        </div>

                        @php
                            $invoiceIds = collect($data['returnsales'])->pluck('invoice_id')->unique()->toArray();
                            $totalReturnsPrice = 0;
                        @endphp

                        <div class="table-responsive hoverable-table">
                            <table class="table table-hover table-bordered text-center" style="width:100%">
                               <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('report.date') }}</th>
                                        <th>{{ __('report.invoiceNo') }}</th>
                                        <th>{{ __('home.clietName') }}</th>
                                        <th>{{ __('home.paymentmethod') }}</th>
                                        <th>{{ __('home.total') }}</th>
                                        <th>{{ __('home.quantity') }}</th>
                                        <th>{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoiceIds as $index => $invoiceid)
                                        @php
                                            $datainvoice = App\Models\invoices::find($invoiceid);
                                            $returnItems = App\Models\return_sales::where('invoice_id', $invoiceid)
                                                ->whereDate('created_at', '>=', $start)
                                                ->whereDate('created_at', '<=', $end)
                                                ->get();
                                            
                                            $eachreturn = 0;
                                            $numberofPice = 0;
                                            $date = '';

                                            foreach ($returnItems as $item) {
                                                $subTotal = ($item->return_Unit_Price * $item->return_quantity) - $item->discountvalue - $item->discountoninvoice;
                                                $eachreturn += $subTotal;
                                                $numberofPice += $item->return_quantity;
                                                $date = $item->created_at;
                                            }
                                            $eachreturn += ($eachreturn * $saleavt);
                                            $totalReturnsPrice += $eachreturn;
                                            $payType = optional(optional($returnItems->first())->Invoice)->Pay;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $date ? explode(' ', $date)[0] : '' }}</td>
                                            <td>{{ $invoiceid }}</td>
                                            <td>{{ optional(optional($datainvoice)->customer)->name }}</td>
                                            <td>
                                                @if ($payType == 'Cash')
                                                    <span class="text-success">{{ __('report.cash') }}</span>
                                                @elseif($payType == 'Credit')
                                                    <span class="text-danger">{{ __('report.credit') }}</span>
                                                @elseif($payType == 'Bank_transfer')
                                                    <span class="text-danger">{{ __('home.Bank_transfer') }}</span>
                                                @else
                                                    <span class="text-warning">{{ __('report.shabka') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($eachreturn, 2) }}</td>
                                            <td>{{ $numberofPice }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-light" href="Show_return_Sales_Details/{{ $invoiceid }}">
                                                    <i class="fas fa-print"></i> {{ __('home.show') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- إجمالي المرتجعات -->
                            <table class="table table-bordered text-center mt-2" style="width: 30% float: left;">
                                <tr>
                                    <td class="font-weight-bold bg-light">{{ __('report.totalallprice') }}</td>
                                    <td class="font-weight-bold text-danger">{{ number_format($totalReturnsPrice, 2) }}</td>
                                </tr>
                            </table>
                        </div>
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
</script>
@endsection