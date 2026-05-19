@extends('layouts.master')
@section('css')
<style>
    /* تحسينات الطباعة العامة */
    @media print {
        #print_Button, .main-footer, .header-icon, .breadcrumb-header, .main-header-message { display: none !important; }
        body { background-color: #fff !important; margin: 0; padding: 0; }
        .card { border: none !important; box-shadow: none !important; margin: 0; }
        .main-content { margin-top: 0 !important; padding-top: 0 !important; }
        /* لضمان ظهور الألوان في الطباعة */
        .info-table td:first-child { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; }
        .table-main thead { background-color: #419BB2 !important; color: white !important; -webkit-print-color-adjust: exact; }
        .total-highlight { background-color: #15803d !important; color: white !important; -webkit-print-color-adjust: exact; }
    }

    /* تنسيق الهوية البصرية */
    .invoice-header-wrapper {
        border-bottom: 2px solid #419BB2;
        padding-bottom: 25px;
        margin-bottom: 30px;
    }
    .company-name { font-size: 20px; font-weight: bold; color: #333; margin-bottom: 5px; }
    .company-info { font-size: 13px; color: #666; line-height: 1.5; }

    /* تنسيق جدول البيانات (المورد والمعلومات) */
    .info-table { width: 100%; border-collapse: separate; border-spacing: 0 5px; }
    .info-table td { padding: 8px 12px; border: 1px solid #eef0f7; font-size: 14px; }
    .info-table td:first-child { 
        font-weight: bold; color: #419BB2; width: 40%; 
        background-color: #f8f9fa; border-radius: 5px 0 0 5px; 
    }
    .info-table td:last-child { border-radius: 0 5px 5px 0; text-align: center; font-weight: 500; }

    /* تنسيق جدول المنتجات الرئيسي */
    .table-main { border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6 !important; }
    .table-main thead th { border: none !important; font-weight: 600 !important; font-size: 13px; }
    
    /* تصميم عنوان الفاتورة المركزي */
    .invoice-title-box {
        border: 2px solid #419BB2;
        padding: 8px 20px;
        border-radius: 50px;
        display: inline-block;
        background-color: #f0f9ff;
        min-width: 220px;
    }
</style>
@endsection

@section('title') معاينة فاتورة مشتريات @stop

@section('content')
<div class="row row-sm mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm" id="print">
            <div class="card-body">
                
                <button class="btn btn-info float-left mb-4 shadow-sm" id="print_Button" onclick="window.print()">
                    <i class="fas fa-print ml-1"></i> طباعة الفاتورة
                </button>

                <div class="clearfix"></div>

            <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%" dir=rtl>




                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                            <span class="thick" style="font-size:18px">{{Namear}}</span>
                            <br>
                            <p class="tx-16 thick"> {{describtionar}}</p>
                            <p class="tx-16 thick">{{STar}}</p>
                            <p class="tx-16 thick">{{Taxar}}</p>

                        </div>
                <div class="text-center" style="width: 40%;">
                        @php $logo = defined('camplogo') ? camplogo : 'logo.png'; @endphp
                        <img src="{{ asset('assets/img/brand/'.$logo) }}" style="max-width: 130px; height: auto; margin-bottom: 10px;" alt="logo">
                        <br>
                        <div class="invoice-title-box">
                            <h4 class="mb-0 font-weight-bold" style="color: #419BB2;">
                                فاتورة مشتريات
                                <div style="font-size: 12px; font-weight: normal; letter-spacing: 1px;">PURCHASE INVOICE</div>
                            </h4>
                        </div>
                    </div>

                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                            <span class="thick" style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p class="tx-16 thick" > {{describtionen}} </p>
                            <span class="tx-16 thick">{{STen}} </span>
                            <p class="tx-16 thick"> {{Taxen}} </p>

                        </div>

                    </div><!-- invoice-header -->

                <div class="row mb-4">
                    <div class="col-md-5">
                        <table class="info-table">
                            <tr>
                                <td>{{__('home.Invoice_no')}}</td>
                                <td>#{{$data['resource_purchases']->orderId}}</td>
                            </tr>
                            <tr>
                                <td>{{ __('home.date') }}</td>
                                <td>{{ date('Y-m-d H:i', strtotime($data['resource_purchases']->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td>{{__('home.paymentmethod')}}</td>
                                <td>
                                    @php
                                        $methods = [
                                            'Cash' => __('report.cash'),
                                            'Shabka' => __('report.shabka'),
                                            'Bank_transfer' => __('home.Bank_transfer')
                                        ];
                                        echo $methods[$data['pay']] ?? __('report.credit');
                                    @endphp
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <table class="info-table">
                            <tr>
                                <td>{{ __('home.entersuppliername') }}</td>
                                <td>{{$data['supllierdata']->name}}</td>
                            </tr>
                            <tr>
                                <td>{{ __('home.tax_number') }}</td>
                                <td>{{$data['supllierdata']->TaxـNumber}}</td>
                            </tr>
                            <tr>
                                <td>حالة الفاتورة</td>
                                <td><span class="text-success font-weight-bold">مكتملة</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-main table-striped text-center">
                        <thead class="text-white" style="background-color: #419BB2;">
                            <tr>
                                <th>#</th>
                                <th>{{ __('home.productNo') }}</th>
                                <th>{{ __('home.product') }}</th>
                                <th>{{__('home.quantity')}}</th>
                                <th>{{__('home.price')}}</th>
                                <th>{{__('home.addedValue')}}</th>
                                <th>{{__('home.total')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $i = 0; $totalprice = 0; $totalAddedvalue = 0; 
                            @endphp
                            @foreach ($data['productsdata'] as $product)
                                @if($product->numberofpice != 0)
                                    @php
                                        $i++;
                                        $lineTotal = ($product->purchasingـprice * $product->numberofpice);
                                        $lineTax = ($product->Added_Value * $product->numberofpice);
                                        $totalprice += $lineTotal;
                                        $totalAddedvalue += $lineTax;
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td dir="ltr" class="text-muted small">{{$product->productData->Product_Code}}</td>
                                        <td class="font-weight-bold">{{$product->product_name}}</td>
                                        <td>{{ number_format($product->numberofpice, 2) }}</td>
                                        <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                                        <td>{{ number_format($product->Added_Value, 2) }}</td>
                                        <td class="font-weight-bold">{{ number_format($lineTotal + $lineTax, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mt-4">
                    <div class="col-md-7">
                        <table class="table table-bordered text-center shadow-sm">
                            <thead class="bg-light">
                                <tr style="font-size: 13px;">
                                    <th>{{ __('home.the amount') }}</th>
                                    <th>{{ __('home.discount') }}</th>
                                    <th>{{ __('home.addedValue') }}</th>
                                    <th>{{ __('home.shipping fee') }}</th>
                                    <th >{{ __('home.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="font-size: 16px; font-weight: bold;">
                                    <td>{{ number_format($totalprice, 2) }}</td>
                                    <td class="text-danger">{{ number_format($data['resource_purchases']->discount, 2) }}</td>
                                    <td>{{ number_format($data['resource_purchases']->In_debt - ($totalprice - $data['resource_purchases']->discount), 2) }}</td>
                                    <td>{{ number_format($data['resource_purchases']['shipping fee'], 2) }}</td>
                                    <td >
                                        {{ number_format($data['resource_purchases']->In_debt + $data['resource_purchases']['shipping fee'], 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5 text-center text-muted small">
                    <hr>
                    <p class="mb-0">صدرت هذه الفاتورة آلياً من نظام إبداع سوفت المحاسبي - EbdeaSoft</p>
                    <p>نشكركم لثقتكم بنا</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // لا حاجة لأكواد JS معقدة، window.print() مع CSS Media Query هو الحل الأكثر استقراراً.
</script>
@endsection