@extends('layouts.master')

@section('css')
    <style>
        /* تحسينات عامة للتنسيق والخطوط المطبوعة */
        .thick {
            font-weight: bold;
        }
        .table-padding {
            margin-top: 20px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            color: #333;
            border-bottom: 2px solid #28a745;
            padding-bottom: 5px;
            display: inline-block;
            width: auto;
        }
        .signature-section {
            margin-top: 50px;
            padding-right: 20px;
        }
        
        /* التحكم بمظهر الصفحة أثناء الطباعة */
        @media print {
            #print_Button, .print-style {
                display: none !important;
            }
            body {
                background-color: #fff;
                color: #000;
                direction: rtl;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #000 !important;
            }
        }
    </style>
@endsection

@section('title')
    معاينة طباعة أمر الشراء
@endsection

@section('page-header')
    @endsection

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12 mt-5">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice shadow-sm">
                    <div class="card-body p-4">
                        
                        <div class="clearfix">
                            <button class="btn btn-danger float-left mb-3 print-style" id="print_Button" onclick="printDiv()"> 
                                <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') ?? 'طباعة' }}
                            </button>
                        </div>

                        <div class="invoice-header" style="display: flex; justify-content: space-between; align-items: center; width: 100%; border-bottom: 1px solid #e1e6f1; padding-bottom: 15px;" dir="rtl">
                            
                            <div class="billed-from" style="width: 35%; text-align: right;">
                                <h5 class="tx-18 thick mb-1">{{ Namear ?? Namear }}</h5>
                                <p class="tx-14 text-muted mb-1">{{ describtionar ?? describtionar }}</p>
                                <p class="tx-13 mb-1"><b>الرقم الضريبي:</b> {{ Taxar ?? Taxar }}</p>
                                <p class="tx-13 mb-0">{{ STar ?? STar }}</p>
                            </div>

                            <div class="text-center" style="width: 30%;">
                                @php $logo = defined('camplogo') ? camplogo : 'logo.png'; @endphp
                                <a href="https://ebdeasoft.com/">
                                    <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1" alt="logo" style="max-width: 120px; height: auto;">
                                </a>
                            </div>

                            <div class="billed-from" style="width: 35%; text-align: left;" dir="ltr">
                                <h5 class="tx-18 thick mb-1">{{ Nameen ?? Nameen }}</h5>
                                <p class="tx-14 text-muted mb-1">{{ describtionen ?? describtionen }}</p>
                                <p class="tx-13 mb-1"><b>VAT No:</b> {{ Taxen ?? Taxen }}</p>
                                <p class="tx-13 mb-0">{{ STen ?? STen }}</p>
                            </div>
                        </div>

                        <div class="text-center my-4">
                            <span class="invoice-title px-4">{{ __('home.Purchase_order_of_resources') }}</span>
                        </div>

                        <div class="row mg-t-20">
                            <div class="col-md-6 col-lg-5">
                                <div class="table-responsive table-padding">
                                    <table class="table table-sm table-bordered table-striped text-center mb-0" id="tableSupplierInfo">
                                        <tbody>
                                            <tr>
                                                <td class="bg-light thick" style="width: 40%;">{{ __('home.Invoice_no') }}</td>
                                                <td class="thick text-danger">{{ $data['productsdata'][0]->order_owner ?? $data['productsdata'][0]->supllier->id }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('home.date') }}</td>
                                                <td>{{ date("Y-m-d H:i") }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('home.suppliername') }}</td>
                                                <td class="thick">{{ $data['supllierdata']->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('supprocesses.Location') }}</td>
                                                <td>{{ $data['supllierdata']->location }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('supprocesses.phone') }}</td>
                                                <td>{{ $data['supllierdata']->phone }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>    
                            </div>
                        </div>

                        <div class="table-responsive mg-t-40">
                            <table class="table table-bordered table-striped text-center border text-md-nowrap mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">{{ __('home.productNo') }}</th>
                                        <th style="width: 35%;">{{ __('home.product') }}</th>
                                        <th style="width: 10%;">{{ __('home.quantity') }}</th>
                                        <th style="width: 11%;">{{ __('home.price') }}</th>
                                        <th style="width: 11%;">{{ __('home.addedValue') }}</th>
                                        <th style="width: 13%;">{{ __('home.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $i = 0;
                                        $totalprice = 0;
                                        $totalAddedvalue = 0; 
                                    @endphp

                                    @foreach ($data['productsdata'] as $product)
                                        @if($product->numberofpice != 0)
                                            @php 
                                                $i++;
                                                $rowPrice = $product->purchasingـprice * $product->numberofpice;
                                                $rowTax = $product->Added_Value * $product->numberofpice;
                                                $totalprice += $rowPrice;
                                                $totalAddedvalue += $rowTax;
                                            @endphp
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td dir="ltr" class="text-monospace">{{ $product->productData->Product_Code }}</td>
                                                <td class="text-right px-3">{{ $product->productData->product_name }}</td>
                                                <td>{{ $product->numberofpice }}</td>
                                                <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                                                <td>{{ number_format($product->Added_Value, 2) }}</td>
                                                <td class="thick">{{ number_format($rowPrice + $rowTax, 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-start mg-t-30">
                            <div class="col-md-6 col-lg-5">
                                <div class="table-responsive table-padding">
                                    <table class="table table-bordered text-center mb-0">
                                        <thead class="bg-gray-200 text-dark font-weight-bold">
                                            <tr>
                                                <th>{{ __('home.the amount') }}</th>
                                                <th>{{ __('home.addedValue') }}</th>
                                                <th class="bg-success text-white">{{ __('home.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="thick">
                                                <td>{{ number_format($totalprice, 2) }}</td>
                                                <td>{{ number_format($totalAddedvalue, 2) }}</td>
                                                <td class="text-success tx-16">{{ number_format($totalprice + $totalAddedvalue, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="signature-section text-right">
                            <p class="mb-1 thick">{{ __('home.signtyre_purchase_manger') }}</p>
                            <p class="text-muted">{{ __('home.thesignature') }} : ............................................</p>
                        </div>

                        <hr class="mg-b-40 my-4">

                    </div>
                </div>
            </div>
        </div></div>
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
            
            // إعادة تحميل الصفحة لضمان استقرار لغة الواجهة وأكواد الجافا سكريبت بعد الطباعة
            location.reload();
        }
    </script>
@endsection