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
        .invoice-header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        
        /* التحكم بمظهر الصفحة أثناء الطباعة */
        @media print {
            #print_Button, .print-style, .main-header, .sidebar-nav, .footer {
                display: none !important;
            }
            body {
                background-color: #fff;
                color: #000;
                direction: rtl;
                padding: 0;
                margin: 0;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #000 !important;
            }
            .main-content-body-invoice {
                padding: 0 !important;
                margin: 0 !important;
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

                        <div class="invoice-header-box" dir="rtl">
                            <div class="billed-from" style="width:33%; text-align: center;">
                                <span class="thick" style="font-size:18px">{{ $data['company_data']->Namear ?? '' }}</span>
                                <p class="tx-14 thick mb-1">{{ $data['company_data']->describtionar ?? '' }}</p>
                                <p class="tx-14 thick mb-1">{{ $data['company_data']->STar ?? '' }}</p>
                                <p class="tx-14 thick mb-0">{{ $data['company_data']->Taxar ?? '' }}</p>
                            </div>

                            <div class="text-center" style="width: 34%;">
                                @php $logo = defined('camplogo') ? camplogo : 'logo.png'; @endphp
                                <img src="{{ asset('assets/img/brand/'.$logo) }}" style="max-width: 130px; height: auto; margin-bottom: 10px;" alt="logo">
                                <div class="invoice-title-box">
                                    <h4 class="mb-0 font-weight-bold" style="color: #419BB2;">
                                        فاتورة مشتريات
                                        <div style="font-size: 12px; font-weight: normal; letter-spacing: 1px; color: #555;">PURCHASE INVOICE</div>
                                    </h4>
                                </div>
                            </div>

                            <div class="billed-from" style="width:33%; text-align: center;" dir="ltr">
                                <span class="thick" style="font-size:18px">{{ $data['company_data']->Nameen ?? '' }}</span>
                                <p class="tx-14 thick mb-1">{{ $data['company_data']->describtionen ?? '' }}</p>
                                <p class="tx-14 thick mb-1">{{ $data['company_data']->STen ?? '' }}</p>
                                <p class="tx-14 thick mb-0">{{ $data['company_data']->Taxen ?? '' }}</p>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="text-center my-3">
                            <span class="invoice-title px-4">{{ __('home.Purchase_order_of_resources') }}</span>
                        </div>

                        <div class="row mg-t-20">
                            <div class="col-md-6 col-lg-5">
                                <div class="table-responsive table-padding">
                                    <table class="table table-sm table-bordered table-striped text-center mb-0" id="tableSupplierInfo">
                                        <tbody>
                                            <tr>
                                                <td class="bg-light thick" style="width: 40%;">{{ __('home.Invoice_no') }}</td>
                                                <td class="thick text-danger">
                                                    {{ $data['productsdata'][0]->order_owner ?? ($data['productsdata'][0]->supplier_id ?? '-') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('home.date') }}</td>
                                                <td dir="ltr">{{ date("Y-m-d H:i") }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('home.suppliername') }}</td>
                                                <td class="thick">{{ $data['supllierdata']->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('supprocesses.Location') }}</td>
                                                <td>{{ $data['supllierdata']->location ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light thick">{{ __('supprocesses.phone') }}</td>
                                                <td dir="ltr">{{ $data['supllierdata']->phone ?? '' }}</td>
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
                                        @if(isset($product->numberofpice) && $product->numberofpice != 0)
                                            @php 
                                                $i++;
                                                $purchasingPrice = $product->purchasingـprice ?? 0;
                                                $addedValue = $product->Added_Value ?? 0;
                                                
                                                $rowPrice = $purchasingPrice * $product->numberofpice;
                                                $rowTax = $addedValue * $product->numberofpice;
                                                
                                                $totalprice += $rowPrice;
                                                $totalAddedvalue += $rowTax;
                                            @endphp
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td dir="ltr" class="text-monospace">{{ $product->productData->Product_Code ?? '' }}</td>
                                                <td class="text-right px-3">{{ $product->productData->product_name ?? '' }}</td>
                                                <td>{{ $product->numberofpice }}</td>
                                                <td>{{ number_format($purchasingPrice, 2) }}</td>
                                                <td>{{ number_format($addedValue, 2) }}</td>
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
            
            // إعادة تحميل الصفحة للحفاظ على سلامة الـ DOM واللغة بعد الخروج من نافذة الطباعة
            location.reload();
        }
    </script>
@endsection