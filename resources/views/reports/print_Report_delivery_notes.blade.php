@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button {
                display: none !important;
            }
            body {
                background: #fff !important;
                -webkit-print-color-adjust: exact;
            }
            .card-invoice {
                border: none !important;
                box-shadow: none !important;
            }
        }

        body {
            font-family: 'Cairo', Georgia, "Times New Roman", Times, serif;
            color: #333;
            background-color: #f8f9fa;
        }

        .invoice-title-header {
            border-bottom: 2px solid #419BB2;
            padding-bottom: 10px;
            margin-bottom: 25px;
            text-align: center;
        }

        .invoice-title-header h2 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .invoice-title-header p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 0;
            letter-spacing: 1px;
        }

        .company-info-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .table-invoice-info th {
            background-color: #f1f5f9 !important;
            color: #333;
            width: 30%;
        }

        .table-products th {
            background-color: #419BB2 !important;
            color: #fff !important;
            border-color: #357a8d !important;
        }
    </style>
@endsection

@section('title')
    {{ __('report.Delivery_notes') }} - {{ __('home.print') }}
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice shadow-sm">
                    <div class="card-body p-4">

                        <!-- عنوان التقرير (عربي وإنجليزي تحت بعض) -->
                        <div class="invoice-title-header">
                            <h2>إذن تسليم بضاعة / ملاحظات التوصيل</h2>
                            <p>Delivery Notes Report</p>
                        </div>

                        <!-- رأس الفاتورة والبيانات التعريفية -->
                        <div class="invoice-header d-flex justify-content-between align-items-center w-100 mb-4">
                            <!-- الطرف الأيسر (الإنجليزي) -->
                            <div class="company-info-box text-left" style="width: 32%;">
                                <span style="font-size: 20px; font-weight: bold; color: #2c3e50;">{{ Nameen ?? 'Company Name' }}</span>
                                <hr class="my-2">
                                <p dir="ltr" class="mb-1 text-muted small">{{ describtionen ?? '' }}</p>
                                <div dir="ltr" class="small"><strong>ST:</strong> {{ STen ?? '' }}</div>
                                <div dir="ltr" class="small"><strong>Tax ID:</strong> {{ Taxen ?? '' }}</div>
                            </div>

                            <!-- الشعار في المنتصف -->
                            <div class="text-center" style="width: 32%;">
                                @php
                                    $logo = camplogo ?? 'default-logo.png';
                                @endphp
                                <a href="https://ebdeasoft.com/" target="_blank">
                                    <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1 img-fluid" alt="logo" style="max-height: 75px; object-fit: contain;">
                                </a>
                            </div>

                            <!-- الطرف الأيمن (العربي) -->
                            <div class="company-info-box text-right" style="width: 32%;">
                                <span style="font-size: 20px; font-weight: bold; color: #2c3e50;">{{ Namear ?? 'اسم الشركة' }}</span>
                                <hr class="my-2">
                                <p class="mb-1 text-muted small">{{ describtionar ?? '' }}</p>
                                <div class="small"><strong>السجل التجاري:</strong> {{ STar ?? '' }}</div>
                                <div class="small"><strong>الرقم الضريبي:</strong> {{ Taxar ?? '' }}</div>
                            </div>
                        </div>

                        @if (isset($Invoices))
                            <div class="card-body px-0">
                                <!-- وقت التصدير -->
                                <div class="mb-4 d-flex align-items-center">
                                    <span style="font-size: 13px; color: #419BB2; font-weight: bold;" class="ml-2">
                                        {{ __('home.exportTime') }} :
                                    </span>
                                    <span style="font-size: 13px; color: #333; font-weight: 600;">
                                        {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                                    </span>
                                </div>

                                @php
                                    $count = 0;
                                    $startat = '';
                                    $endat = '';
                                    $totalprofit = 0;
                                @endphp

                                @foreach ($Invoices as $invoice)
                                    @php
                                        if ($count == 0) {
                                            $startat = $invoice->created_at;
                                        }
                                        $endat = $invoice->created_at;
                                        $count++;
                                    @endphp

                                    <!-- جدول بيانات الفاتورة الأساسية -->
                                    <div class="table-padding mb-4">
                                        <table class="table table-bordered text-center table-invoice-info">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('report.invoiceNo') }}</th>
                                                    <td class="font-weight-bold text-primary">{{ $invoice->orderId }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('home.suppliername') }}</th>
                                                    <td>{{ $invoice->supllier->name ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('home.notesClient') }}</th>
                                                    <td>{{ $invoice->notes ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('users.branch') }}</th>
                                                    <td>{{ $invoice->branch->name ?? '---' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- جدول المنتجات -->
                                    <div class="table-responsive mb-4">
                                        <table class="table table-striped table-bordered text-center table-products">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.product') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.saleprice') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 0; @endphp
                                                @foreach (App\Models\orderDetails::where('order_owner', $invoice->orderId)->where('numberofpice', '!=', 0)->get() as $product)
                                                    @php
                                                        $i++;
                                                        $totalprofit += $product->quantity * $product->Unit_Price - $product->quantity * $product->productData->purchasingـprice;
                                                        $date = explode(' ', $product->created_at);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $date[0] }}</td>
                                                        <td dir="ltr">{{ $product->productData->Product_Code ?? '' }}</td>
                                                        <td>{{ $product->productData->product_name ?? '' }}</td>
                                                        <td>{{ $product->numberofpice }}</td>
                                                        <td>{{ $product->productData->purchasingـprice ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if(!$loop->last)
                                        <hr class="my-4" style="border-top: 2px dashed #cbd5e1;">
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <hr class="mg-b-30">

                        <!-- زر الطباعة -->
                        <div class="clearfix">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2 px-4 py-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                            </button>
                        </div>

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