@extends('layouts.master')

@section('css')
<style>
    /* التنسيق العام لضمان بياض الصفحة ووضوح الحدود */
    .card-invoice {
        border-radius: 15px;
        box-shadow: none;
        border: none;
        background-color: #fff !important;
    }

    /* حل مشكلة المربع الغامق: جعل الرأس أبيض بحدود سوداء */
    .invoice-table th {
        background-color: #fff !important;
        color: #000 !important;
        font-weight: bold;
        border: 1px solid #000 !important;
        vertical-align: middle;
        text-align: center;
        padding: 8px;
    }

    .invoice-table td {
        border: 1px solid #000 !important;
        vertical-align: middle;
        background-color: #fff !important;
        color: #000 !important;
    }

    .double-border-box {
        border: 2px solid #000 !important;
        border-radius: 10px;
        padding: 10px;
        background: #fff !important;
    }
    .invoice-type-title {
        font-size: 19px !important;
        font-weight: 900;
        background: #fff !important;
        margin: 15px 0;
        display: inline-block;
        padding: 10px 50px;
        border: 3px double #000;
        color: #000;
    }{
        font-size: 24px !important;
        font-weight: 900;
        background: #fff !important;
        margin: 15px 0;
        display: inline-block;
        padding: 10px 50px;
        border: 3px double #000;
        color: #000;
    }
    p.invoice-type-title {
        font-size: 24px !important;
        font-weight: 900;
        background: #fff !important;
        margin: 15px 0;
        display: inline-block;
        padding: 10px 50px;
        border: 3px double #000;
        color: #000;
    }

    /* تنسيق الطباعة المحسن */
    @media print {
        .hide-on-print, #print_Button { display: none !important; }
        body { background: #fff !important; margin: 0; padding: 0; }
        .card { border: none !important; }

        /* إلغاء أي تظليل تلقائي من المتصفح */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: transparent !important;
        }

        @page {
            margin: 10mm;
            size: A4;
        }

        .footer-fixed {
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #000;
            padding: 5px 0;
            font-size: 11px;
            text-align: center;
            background-color: white !important;
        }

        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>
@endsection

@section('title')
معاينة طباعة تسليم منتجات
@stop

@section('content')
<div class="row row-sm mt-4" dir="ltr">
    <div class="col-md-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="d-flex justify-content-center hide-on-print">
                    <button class="btn btn-danger mt-3" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>
                                    <div class="invoice-header d-flex justify-content-between w-100" dir="rtl">
                                        <div style="width:33%; text-align: center;">
                                            <h4 class="font-weight-bold">{{Namear}}</h4>
                                            <p>{{describtionar}}</p>
                                            <p>{{STar}}</p>
                                            <p class="text-primary">{{Taxar}}</p>
                                        </div>
                                        <div class="text-center">
                                            @php $logo = camplogo; @endphp
                                            <img src="{{ asset('assets/img/brand/'.$logo) }}" style="width: 120px; height: 120px; object-fit: contain;">
                                        </div>
                                        <div style="width:33%; text-align: center;" dir="ltr">
                                            <h4 class="font-weight-bold">{{Nameen}}</h4>
                                            <p>{{describtionen}}</p>
                                            <p>{{STen}}</p>
                                            <p class="text-primary">{{Taxen}}</p>
                                        </div>
                                    </div>
                                    <center><p class="invoice-type-title">تسليم منتجات</p></center>

                                    <div class="double-border-box mb-3" dir="rtl">
                                        <table class="table table-borderless m-0 text-right">
                                            <tr>
                                                <td class="font-weight-bold">اسم العميل: {{ $data['invoiceData']->customer->name }}</td>
                                                <td class="font-weight-bold text-left">اسم البائع: {{ Namear }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <div class="double-border-box mb-3" dir="rtl">
                                        <table class="table table-borderless m-0 text-right">
                                            @php
                                                $pay = match($data['invoiceData']->Pay) {
                                                    'Cash' => __('report.cash'),
                                                    'Shabka' => __('report.shabka'),
                                                    'Credit' => __('report.credit'),
                                                    'Bank_transfer' => __('home.Bank_transfer'),
                                                    default => __('home.Partition of the amount'),
                                                };
                                            @endphp
                                            <tr>
                                                <td>طريقة الدفع: <strong>{{ $pay }}</strong></td>
                                                <td class="text-left">تاريخ التسليم : <strong>{{ $data['invoiceData']->created_at }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>رقم التسليم : <span class="text-danger font-weight-bold">{{ $data['invoiceData']->id }}</span></td>
                                                <td class="text-left">اسم الفرع: <strong>{{ $data['invoiceData']->branch->name }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table invoice-table text-center">
                                            <thead>
                                                <tr>
                                                    <th>م</th>
                                                    <th>كود المنتج</th>
                                                    <th>اسم الصنف</th>
                                                    <th>سعر الوحدة</th>
                                                    <th class="hide-on-print">المخزون</th>
                                                    <th>الكمية</th>
                                                    <th>الوحدة</th>
                                                    <th>الإجمالي</th>
                                                    <th>الخصم</th>
                                                    <th>الصافي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 0; $discountreturn = 0; @endphp
                                                @foreach (App\Models\sales_withoud_taxes::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                                                    @if($product->quantity != 0)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $product->productData->Product_Code ?? "" }}</td>
                                                        <td class="text-right font-weight-bold">{{ $product->productData->product_name ?? "" }}</td>
                                                        <td>{{ number_format($product->Unit_Price, 2) }}</td>
                                                        <td class="hide-on-print">{{ $product->quantity + $product->reamingQuantity }}</td>
                                                        <td>{{ $product->quantity }}</td>
                                                        <td>{{ $product->unit }}</td>
                                                        <td>{{ number_format($product->Unit_Price * $product->quantity, 2) }}</td>
                                                        <td class="text-danger">{{ number_format($product->Discount_Value, 2) }}</td>
                                                        <td class="font-weight-bold">{{ number_format(($product->Unit_Price * $product->quantity) - $product->Discount_Value, 2) }}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                {{-- المرتجعات --}}
                                                @foreach (App\Models\return_sales::where('invoice_id', $data['invoiceData']->id)->get() as $product)
                                                    @php $discountreturn += $product->discountvalue + $product->discountoninvoice; @endphp
                                                    <tr style="color:red">
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $product->productData->Product_Code }}</td>
                                                        <td class="text-right">{{ $product->productData->product_name }} (مرتجع)</td>
                                                        <td>{{ number_format($product->return_Unit_Price, 2) }}</td>
                                                        <td class="hide-on-print">-</td>
                                                        <td>{{ $product->return_quantity }}</td>
                                                        <td>-</td>
                                                        <td>{{ number_format($product->return_Unit_Price * $product->return_quantity, 2) }}</td>
                                                        <td>{{ number_format($product->discountvalue, 2) }}</td>
                                                        <td>{{ number_format(($product->return_Unit_Price * $product->return_quantity) - $product->discountvalue, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-3" dir="rtl" style="display: flex; width: 100%;">
                                        <div style="width: 60%; padding-left: 10px;">
                                            <div style="border: 1px solid #000; padding: 10px; min-height: 95px;">
                                                <strong>ملاحظات:</strong>
                                                <p>{{ $data['invoiceData']->note }}</p>
                                            </div>
                                        </div>
                                        <div style="width: 40%;">
                                            @php
                                                $invoice = App\Models\delivery_to_customer_withoud_tax_invoices::find($data['invoiceData']->id);
                                                $price = $invoice->cashamount + $invoice->Bank_transfer + $invoice->bankamount + $invoice->creaditamount;
                                                $total_discount = $invoice->discount + $discountreturn;
                                                $sub_total = $price + $total_discount;
                                            @endphp
                                            <table class="table table-bordered text-right m-0">
                                                <tr>
                                                    <th style="background-color: #fff !important; border: 1px solid #000 !important;">الإجمالي</th>
                                                    <td style="border: 1px solid #000 !important;">{{ number_format($sub_total, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="background-color: #fff !important; border: 1px solid #000 !important;" class="text-danger">الخصم (-)</th>
                                                    <td style="border: 1px solid #000 !important;" class="text-danger">{{ number_format($total_discount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="background-color: #fff !important; border: 1px solid #000 !important;">الصافي النهائي</th>
                                                    <td style="background-color: #fff !important; border: 1px solid #000 !important;">{{ number_format($price, 2) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td>
                                    <div class="footer-fixed">
                                        @if(Auth()->user()->branchs_id == 1)
                                            <p>{{addressar}} | {{addressen}}</p>
                                        @elseif(Auth()->user()->branchs_id == 11)
                                            <p>المملكة العربية السعودية - الدمام - دلة | 0507461524</p>
                                        @elseif(Auth()->user()->branchs_id == 10)
                                            <p>المملكة العربية السعودية - جدة - الجوهرة | 0535589521</p>
                                        @elseif(Auth()->user()->branchs_id == 9)
                                            <p>المملكة العربية السعودية - خميس مشيط | 0556690148</p>
                                        @endif
                                        <p class="small text-muted">تم الاستخراج بواسطة نظام إبداع سوفت</p>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
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
