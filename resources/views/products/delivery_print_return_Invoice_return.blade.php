@extends('layouts.master')

@section('css')
<style>
    /* التنسيق العام للشاشة */
    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f4f7f6;
    }

    .main-content-body-invoice {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    /* عناوين الفاتورة */
    .invoice-title {
        font-size: 20px; /* زيادة حجم العنوان */
        font-weight: 900; /* جعل الخط عريض جداً */
        color: #000;
        border: 4px double #000; /* تقوية الإطار */
        padding: 10px 40px;
        display: inline-block;
        background: #f9f9f9;
        border-radius: 5px;
    }

    /* جداول البيانات */
    .table-invoice thead th {
        background-color: #000 !important; /* لون أسود صريح للطباعة */
        color: white !important;
        text-align: center;
        vertical-align: middle;
        font-size: 16px; /* تكبير الخط */
        font-weight: bold;
        border: 1px solid #000 !important;
    }

    .table-invoice tbody td {
        vertical-align: middle;
        text-align: center;
        border: 2px solid #000 !important; /* تقوية حدود الخلايا */
        font-weight: bold; /* جعل محتوى الجدول عريض */
        font-size: 16px;
        color: #000 !important;
    }

    /* ملخص الحسابات */
    .summary-table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary-table th {
        background-color: #f8f9fa;
        text-align: right;
        padding: 10px;
        border: 2px solid #000;
        font-weight: 800;
        font-size: 18px;
        color: #000;
    }

    .summary-table td {
        text-align: center;
        padding: 10px;
        border: 2px solid #000;
        font-weight: bold;
        font-size: 18px;
        color: #000;
    }

    .total-row {
        background-color: #000 !important;
        color: white !important;
    }

    /* تنسيق الطباعة لضمان الوضوح التام */
    @media print {
        #print_Button { display: none !important; }
        body { 
            background-color: #fff !important; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact !important; /* إجبار المتصفح على إظهار الألوان */
            print-color-adjust: exact !important;
        }
        .main-content-body-invoice { box-shadow: none; border: none; padding: 0; }
        .card { border: none !important; }
        
        /* تكبير النصوص في الطباعة */
        p, span, td, th {
            color: #000 !important;
            font-weight: bold !important;
        }

        .print-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12pt; /* تكبير خط التذييل */
            border-top: 2px solid #000;
            padding: 10px 0;
            font-weight: bold;
        }

        @page {
            margin: 10mm;
            size: A4;
        }
    }
</style>
@endsection

@section('title')
معاينه طباعة مرتجع تسليمات
@stop

@section('content')
<div class="row row-sm mt-4">
    <div class="col-md-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button class="btn btn-danger" id="print_Button" onclick="printDiv()">
                            <i class="mdi mdi-printer ml-1"></i> طباعة التقرير
                        </button>
                    </div>

                    <div class="invoice-header d-flex justify-content-between align-items-start" dir="rtl">
                        <div class="billed-from text-center" style="width:35%">
                            <h4 class="font-weight-bold" style="font-size: 19px;">{{Namear}}</h4>
                            <p class="mb-1" style="font-size: 16px;">{{describtionar}}</p>
                            <p class="mb-1" style="font-size: 16px;">{{STar}}</p>
                            <p class="font-weight-bold text-primary" style="font-size: 18px;">{{Taxar}}</p>
                        </div>

                        <div class="text-center" style="width:25%">
                            @php $logo = camplogo; @endphp
                            <img src="{{ asset('assets/img/brand/'.$logo) }}" class="img-fluid mb-3" style="max-width: 150px;">
                            <br>
                            <span class="invoice-title">مرتجع تسليمات</span>
                        </div>

                        <div class="billed-from text-center" style="width:35%" dir="ltr">
                            <h4 class="font-weight-bold" style="font-size: 22px;">{{Nameen}}</h4>
                            <p class="mb-1" style="font-size: 16px;">{{describtionen}}</p>
                            <p class="mb-1" style="font-size: 16px;">{{STen}}</p>
                            <p class="font-weight-bold text-primary" style="font-size: 18px;">{{Taxen}}</p>
                        </div>
                    </div>

                    <hr style="border-top: 2px solid #000;">

                    <div class="row mt-4" dir="rtl">
                        <div class="col-md-6">
                            <table class="table table-bordered shadow-sm" style="border: 2px solid #000;">
                                <tr>
                                    <th class="bg-light w-40" style="font-size: 18px;">اسم العميل</th>
                                    <td class="font-weight-bold" style="font-size: 18px;">{{$data['invoiceData']->customer->name}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered shadow-sm" style="border: 2px solid #000;">
                                <tr>
                                    <th class="bg-light" style="font-size: 18px;">رقم التسليم</th>
                                    <td class="font-weight-bold text-danger" style="font-size: 14px;">{{ $data['invoiceData']->id }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light" style="font-size: 18px;">طريقة الدفع</th>
                                    <td style="font-size: 16px; font-weight: bold;">
                                        @php
                                            $pay = match($data['invoiceData']->Pay) {
                                                'Cash' => __('report.cash'),
                                                'Shabka' => __('report.shabka'),
                                                'Credit' => __('report.credit'),
                                                'Bank_transfer' => __('home.Bank_transfer'),
                                                default => __('home.Partition of the amount'),
                                            };
                                        @endphp
                                        {{ $pay }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-invoice table-bordered table-striped" style="border: 2px solid #000;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #000 !important;">رقم المنتج</th>
                                    <th style="border: 1px solid #000 !important;">اسم الصنف</th>
                                    <th style="border: 1px solid #000 !important;">سعر القطعة</th>
                                    <th style="border: 1px solid #000 !important;">الكمية</th>
                                    <th style="border: 1px solid #000 !important;">الإجمالي</th>
                                    <th style="border: 1px solid #000 !important;">الخصم</th>
                                    <th style="border: 1px solid #000 !important;">الصافي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $discount_total = 0; 
                                    $totalprice = 0; 
                                @endphp
                                @foreach ($data['salesData'] as $product)
                                @php
                                    $discount_total += ($product->discountvalue + $product->discountoninvoice);
                                    $sub_total = $product->return_Unit_Price * $product->return_quantity;
                                    $net_item = $sub_total - $product->discountvalue;
                                    $totalprice += ($net_item - $product->discountoninvoice);
                                @endphp
                                <tr>
                                    <td>{{$product->productData->Product_Code}}</td>
                                    <td class="text-right"><strong style="font-size: 17px;">{{ $product->productData->product_name}}</strong></td>
                                    <td>{{ number_format($product->return_Unit_Price, 2) }}</td>
                                    <td>{{ $product->return_quantity }}</td>
                                    <td>{{ number_format($sub_total, 2) }}</td>
                                    <td class="text-danger">{{ number_format($product->discountvalue, 2) }}</td>
                                    <td class="font-weight-bold" style="background-color: #eee !important;">{{ number_format($net_item, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4" dir="rtl">
                        <div class="col-md-7">
                            <div class="p-3 border rounded bg-light" style="border: 2px solid #000 !important;">
                                <h6 class="font-weight-bold border-bottom pb-2" style="font-size: 18px;">ملاحظات:</h6>
                                <p style="font-size: 16px; font-weight: bold;">{{ $data['invoiceData']->note ?: 'لا توجد ملاحظات إضافية' }}</p>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <table class="summary-table">
                                <tr>
                                    <th>الإجمالي قبل الخصم</th>
                                    <td style="font-size: 20px;">{{ number_format($totalprice + $discount_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-danger">إجمالي الخصم (-)</th>
                                    <td class="text-danger" style="font-size: 20px;">{{ number_format($discount_total, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th style="color: white !important; font-size: 22px;">الصافي النهائي</th>
                                    <td style="font-size: 24px; color: white !important;">{{ number_format($totalprice, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="print-footer mt-5">
                    
                        <p class="text-muted" style="font-size: 12px; font-weight: bold;">تم استخراج هذه الفاتورة عبر نظام إبداع سوفت</p>
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
</script>
@endsection