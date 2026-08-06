@extends('layouts.master')
@section('css')
    <style>
        @media print {
            #print_Button {
                display: none !important;
            }
            body {
                background: #fff !important;
                border: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }

        body {
            font-family: 'Cairo', 'Times New Roman', Times, serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .invoice-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 20px;
        }

        .company-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .table-custom th {
            background-color: #f4f6f8 !important;
            color: #495057;
            font-weight: 600;
            text-align: center;
        }
        
        .table-custom td {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('title')
    تقرير مبيعات الموظف - Employee Sales Report
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto text-muted">{{ __('home.print') }}</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card invoice-card">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-end mb-4">
                    <button class="btn btn-danger px-4 py-2 shadow-sm" id="print_Button" onclick="printDiv()">
                        <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                    </button>
                </div>

                <!-- رأس الفاتورة / الشركة -->
                <div class="company-header d-flex justify-content-between align-items-center w-100 flex-wrap">
                    <div class="billed-from text-left" style="width:33%;">
                        <span style="font-size:22px; font-weight:bold; color: #2c3e50;">{{ Nameen ?? '' }}</span>
                        <p class="text-muted mb-1" dir="ltr">{{ describtionen ?? '' }}</p>
                        <span class="d-block text-muted" dir="ltr">{{ STen ?? '' }}</span>
                        <p class="text-muted mb-0" dir="ltr">{{ Taxen ?? '' }}</p>
                    </div>

                    <div class="text-center my-2" style="width:33%;">
                        @php $logo = camplogo ?? 'default.png'; @endphp
                        <a href="https://ebdeasoft.com/">
                            <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1" alt="logo" style="max-height: 70px; object-fit: contain;">
                        </a>
                    </div>

                    <div class="billed-from text-right" style="width:33%;">
                        <span style="font-size:22px; font-weight:bold; color: #2c3e50;">{{ Namear ?? '' }}</span>
                        <p class="text-muted mb-1">{{ describtionar ?? '' }}</p>
                        <span class="d-block text-muted">{{ STar ?? '' }}</span>
                        <p class="text-muted mb-0">{{ Taxar ?? '' }}</p>
                    </div>
                </div>

                <!-- عنوان التقرير الرئيسي في المنتصف بالعربية والإنجليزية -->
                <div class="text-center my-4">
                    <h2 style="font-weight: bold; color: #2c3e50; font-family: 'Cairo', sans-serif; margin-bottom: 5px;">
                        تقرير مبيعات الموظف
                    </h2>
                    <h4 style="font-weight: 600; color: #7f8c8d; font-family: 'Times New Roman', Times, serif;">
                        Employee Sales Report
                    </h4>
                    <hr style="width: 150px; border-top: 2px solid #419BB2; margin: 15px auto;">
                </div>

                @if (isset($Invoices))
                    @php
                        $totaldiscount = 0;
                        $totalpriceall = 0;
                        $totaladdedvalue = 0;
                        $total = 0;
                        
                        $avt = App\Models\Avt::find(1);
                        $saleavt = $avt ? $avt->AVT : 0;
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                    <th class="border-bottom-0">{{ __('home.sallerName') }}</th>
                                    <th class="border-bottom-0">{{ __('home.clietName') }}</th>
                                    <th class="border-bottom-0">{{ __('home.date') }}</th>
                                    <th class="border-bottom-0">{{ __('home.branch') }}</th>
                                    <th class="border-bottom-0">{{ __('report.totalpricewithoudtax') }}</th>
                                    <th class="border-bottom-0">{{ __('report.totaltax') }}</th>
                                    <th class="border-bottom-0">{{ __('home.total') }}</th>
                                    <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Invoices as $product)
                                    @php
                                        $totaldiscount += $product->discount;
                                        
                                        // المبلغ الإجمالي للفاتورة شامل الضريبة
                                        $invoiceTotalWithTax = ($product->cashamount + $product->bankamount + $product->Bank_transfer + $product->creaditamount) - $product->discount;
                                        
                                        // استخراج المبلغ بدون ضريبة والضريبة من المبلغ الشامل
                                        if ($saleavt > 0) {
                                            $invoiceTotalWithoutTax = $invoiceTotalWithTax / (1 + $saleavt);
                                            $invoiceTax = $invoiceTotalWithTax - $invoiceTotalWithoutTax;
                                        } else {
                                            $invoiceTotalWithoutTax = $invoiceTotalWithTax;
                                            $invoiceTax = 0;
                                        }

                                        $totalpriceall += $invoiceTotalWithoutTax;
                                        $totaladdedvalue += $invoiceTax;
                                        $total += $invoiceTotalWithTax;

                                        $pays = match($product->Pay) {
                                            'Cash' => __('report.cash'),
                                            'Shabka' => __('report.shabka'),
                                            'Credit' => __('report.credit'),
                                            'Bank_transfer' => __('home.Bank_transfer'),
                                            default => __('home.Partition of the amount')
                                        };
                                    @endphp
                                    <tr>
                                        <td class="font-weight-bold text-dark">#{{ $product->id }}</td>
                                        <td>{{ optional($product->user)->name }}</td>
                                        <td dir="ltr" class="font-weight-semibold">{{ optional($product->customer)->name }}</td>
                                        <td class="text-muted small">{{ $product->created_at }}</td>
                                        <td>{{ optional($product->branch)->name }}</td>
                                        <td>{{ number_format($invoiceTotalWithoutTax, 2) }}</td>
                                        <td class="text-info font-weight-bold">{{ number_format($invoiceTax, 2) }}</td>
                                        <td class="font-weight-bold text-success">{{ number_format($invoiceTotalWithTax, 2) }}</td>
                                        <td>{{ $pays }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- جدول الملخص النهائي -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-5">
                            <table class="table table-bordered text-center table-striped">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold text-right">{{ __('home.totaldiscount') }}</td>
                                        <td class="text-danger font-weight-bold">{{ number_format($totaldiscount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-right">{{ __('report.totalpricewithoudtax') }}</td>
                                        <td>{{ number_format($totalpriceall, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-right">{{ __('report.totaltax') }}</td>
                                        <td class="text-info font-weight-bold">{{ number_format($totaladdedvalue, 2) }}</td>
                                    </tr>
                                    <tr class="bg-success text-white">
                                        <td class="font-weight-bold text-right"><strong>{{ __('report.totalallprice') }}</strong></td>
                                        <td><strong>{{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

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
    </script>
@endsection