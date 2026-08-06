@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button, .export-btn {
                display: none !important;
            }
            body {
                border: none !important;
            }
        }
        body {
            font: 13pt "Times New Roman", Times, serif;
            line-height: 1.5;
        }
        .table-styled {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .invoice-title {
            color: #419BB2;
            font-weight: bold;
        }
    </style>
@endsection

@section('title')
    {{ __('home.print') }}
@stop

@section('content')
    <div class="row row-sm">
        <div class="col-md-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    
                    {{-- أزرار التحكم --}}
                    <div class="d-flex justify-content-center mt-4 no-print">
                        <a style="background-color: #419BB2; border:none" class="btn btn-success p-2 export-btn" 
                           href="{{ url('/Invoices_purchases_export/' . $branch . '/' . $pay .'/' . $suplier_id . '/' . $startat . '/' . $endat) }}">
                            EXPORT EXCEL <i class="fa fa-file-excel ml-1"></i>
                        </a>
                        <button class="btn btn-danger ml-2" id="print_Button" onclick="printDiv()">
                            {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        {{-- الهيدر الخاص بالشركة --}}
                        <div class="invoice-header d-flex justify-content-between">
                            <div class="billed-from text-center" style="width:33%">
                                <h2 class="invoice-title">{{ Nameen }}</h2>
                                <p dir="ltr">{{ describtionen }}<br>{{ STen }}<br>{{ Taxen }}</p>
                            </div>
                            
                            <div class="text-center">
                                @php $logo = camplogo; @endphp
                                <img src="{{ asset('assets/img/brand/'.$logo) }}" style="width: 120px; height: auto;">
                            </div>

                            <div class="billed-from text-center" style="width:33%">
                                <h2 class="invoice-title">{{ Namear }}</h2>
                                <p>{{ describtionar }}<br>{{ STar }}<br>{{ Taxar }}</p>
                            </div>
                        </div>

                    @if (isset($Invoices) && $Invoices->count() > 0)
<!-- عرض وقت التصدير وفترة التقرير -->
                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded mb-4">
                    <span style="font-size: 14px; color: #419BB2; font-weight: bold;">
                        <i class="fas fa-clock ml-1"></i> {{ __('home.exportTime') }} :
                        {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                    </span>
                    <span style="font-size: 14px; color: #2c3e50; font-weight: bold;">
                        <i class="fas fa-calendar-alt ml-1"></i> {{ __('report.fromdate') ?? 'الفترة' }} : 
                        <span class="text-danger" dir="ltr">{{ $startat ?? '' }}</span> &nbsp; إلى &nbsp; <span class="text-danger" dir="ltr">{{ $endat ?? '' }}</span>
                    </span>
                </div>

    @php
        // تعريف متغيرات الإجماليات العامة للتقرير
        $totalAllBeforeTax = 0;
        $totalAllTax = 0;
        $totalAllDiscount = 0;
        $totalAllShipping = 0;
        
        // المتغيرات الجديدة للتصنيف
        $totalNetNonTaxable = 0; // إجمالي صافي الفواتير الصفرية
        $totalNetTaxable = 0;    // إجمالي صافي الفواتير الضريبية
    @endphp

 @foreach ($Invoices as $invoice)
    @php
        $invoiceSubtotalBeforeTax = 0;
        $invoiceVAT = 0;
        $shipping = ($invoice['shipping fee'] ?? 0) + ($invoice['Other expenses'] ?? 0);
        
        // جلب تفاصيل الأصناف وحساب ضريبة كل صنف بدقة
        $details = App\Models\orderDetails::where('order_owner', $invoice->orderId)->get();
        
        foreach($details as $p) {
            $qty = $p->numberofpice + $p->returns_purchase;
            
            // حساب سعر الشراء الإجمالي للسطر
            $lineAmount = $qty * $p->purchasingـprice;
            $invoiceSubtotalBeforeTax += $lineAmount;

            // المنطق الجديد: إذا كانت القيمة المضافة أكبر من الصفر نحسب 15%، وإلا فالضريبة صفر
            $taxRate = ($p->Added_Value > 0) ? 0.15 : 0;
            $invoiceVAT += ($lineAmount * $taxRate);
        }

        // معالجة ضريبة الخصم: تخصم الضريبة فقط إذا كانت الفاتورة ضريبية أصلاً
        $discount_net_vat = 0;
        if ($invoiceVAT > 0 && $invoice->discount > 0) {
            // حساب قيمة الضريبة الموجودة داخل مبلغ الخصم (بفرض أن الخصم شامل الضريبة)
            $discount_net_vat = $invoice->discount - ($invoice->discount / 1.15);
            $invoiceVAT -= $discount_net_vat;
        }

        // الصافي النهائي لهذه الفاتورة
        $invoiceGrandTotal = ($invoiceSubtotalBeforeTax - $invoice->discount) + $invoiceVAT + $shipping;

        // تصنيف الفاتورة
        if ($invoiceVAT <= 0) {
            $totalNetNonTaxable += $invoiceGrandTotal;
            $rowClass = 'border-danger'; 
            $headerClass = 'bg-danger-transparent';
        } else {
            $totalNetTaxable += $invoiceGrandTotal;
            $rowClass = 'border-success'; 
            $headerClass = 'bg-success-transparent';
        }

        // تحديث الإجماليات العامة
        $totalAllBeforeTax += $invoiceSubtotalBeforeTax;
        $totalAllTax += $invoiceVAT;
        $totalAllDiscount += $invoice->discount;
        $totalAllShipping += $shipping;
    @endphp

    <div class="table-responsive mt-4">
        <table class="table table-bordered text-center {{ $rowClass }}" style="border-width: 2px;">
            <thead class="{{ $headerClass }}">
                <tr>
                    <th colspan="1" class="text-right">رقم الفاتورة: {{ $invoice->orderId }}</th>
                    <th colspan="1" class="text-right">تاريخ : {{ $invoice->created_at }}</th>
                    <th colspan="1" class="text-right">اسم المورد : {{ $invoice->supllier->name }}</th>
                    <th colspan="2" class="text-left">الحالة: {{ $invoiceVAT <= 0 ? 'معفاة / صفرية' : 'خاضعة للضريبة' }}</th>
                </tr>
                <tr>
                    <th>رقم المنتج</th>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر (قبل الضريبة)</th>
                    <th>الضريبة ({{ $invoiceVAT > 0 ? '15%' : '0%' }})</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $product)
                    @php
                        $lineQty = $product->numberofpice + $product->returns_purchase;
                        $currentTaxRate = ($product->Added_Value > 0) ? 0.15 : 0;
                        $lineTax = ($lineQty * $product->purchasingـprice) * $currentTaxRate;
                        $lineTotal = ($lineQty * $product->purchasingـprice) + $lineTax;
                    @endphp
                    <tr>
                        <td>{{ $product->productData->Product_Code ?? 'N/A' }}</td>
                        <td>{{ $product->productData->product_name ?? 'N/A' }}</td>
                        <td>{{ $lineQty }}</td>
                        <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                        <td class="{{ $lineTax == 0 ? 'text-danger' : 'text-success' }} font-weight-bold">
                            {{ number_format($lineTax, 2) }}
                        </td>
                        <td>{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <tr>
                    <td colspan="2">الخصم: {{ number_format($invoice->discount, 2) }}</td>
                    <td colspan="2">الشحن: {{ number_format($shipping, 2) }}</td>
                    <td class="font-weight-bold">صافي الفاتورة: {{ number_format($invoiceGrandTotal, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endforeach
    {{-- جدول الخلاصة النهائية للتقرير بالكامل --}}
    <div class="row mt-5">
        <div class="col-md-6 offset-md-6">
            <table class="table table-bordered text-center border-dark shadow-sm">
                <thead class="bg-dark text-white">
                    <tr>
                        <th colspan="2" style="font-size: 18px">ملخص إجماليات التقرير</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-danger">
                        <td>إجمالي صافي الفواتير (غير الخاضعة للضريبة)</td>
                        <td class="font-weight-bold">{{ number_format($totalNetNonTaxable, 2) }}</td>
                    </tr>
                    <tr class="table-success">
                        <td>إجمالي صافي الفواتير (الخاضعة للضريبة)</td>
                        <td class="font-weight-bold">{{ number_format($totalNetTaxable, 2) }}</td>
                    </tr>
                    <tr>
                        <td>إجمالي القيمة (قبل الضريبة) لكل الفواتير</td>
                        <td>{{ number_format($totalAllBeforeTax, 2) }}</td>
                    </tr>
                    <tr>
                        <td>إجمالي الخصومات</td>
                        <td>{{ number_format($totalAllDiscount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>إجمالي ضريبة القيمة المضافة</td>
                        <td class="{{ $totalAllTax == 0 ? 'text-danger' : 'text-success' }} font-weight-bold">
                            {{ number_format($totalAllTax, 2) }}
                        </td>
                    </tr>
                    <tr class="bg-light">
                        <td class="font-weight-bold text-uppercase">الإجمالي النهائي (صافي المشتريات)</td>
                        <td class="font-weight-bold text-primary" style="font-size: 22px">
                            {{ number_format(($totalAllBeforeTax - $totalAllDiscount) + $totalAllTax + $totalAllShipping, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert alert-warning text-center mt-5">لا توجد نتائج لهذا البحث</div>
@endif
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