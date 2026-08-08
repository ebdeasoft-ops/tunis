@extends('layouts.master')

@section('css')
<style>
    @media print {
        #print_Button, .breadcrumb-header, .main-header, .sidebar, footer { display: none !important; }
        body { background: #fff !important; font-family: 'Times New Roman', serif; color: #000; }
        .card { border: none !important; box-shadow: none !important; }
        .table-bordered th, .table-bordered td { border: 1px solid #000 !important; }
    }
    body { font-family: 'Cairo', sans-serif; }
    .report-main-title { font-size: 22px !important; font-weight: bold !important; color: #419BB2; }
    .total-all-box { background-color: #f8f9fa; border: 2px solid #2c3e50; border-radius: 10px; padding: 25px; margin-top: 40px; }
</style>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-body">
                <div class="d-flex justify-content-end p-3"><button class="btn btn-primary" id="print_Button" onclick="printDiv()">طباعة <i class="mdi mdi-printer"></i></button></div>

                <!-- الهيدر -->
                              <!-- رأس الفاتورة والشركة -->
                    <div class="invoice-header d-flex justify-content-between align-items-center w-100 border-bottom pb-4 mb-4">
                        <div class="billed-from text-center" style="width:33%;">
                            <span style="font-size: 26px; font-weight: bold;">{{ Nameen ?? '' }}</span>
                            <p dir="ltr" class="mb-1 text-muted" style="font-size: 15px;">{{ describtionen ?? '' }}</p>
                            <span dir="ltr" class="d-block text-muted" style="font-size: 15px;">{{ STen ?? '' }}</span>
                            <p dir="ltr" class="mb-0 text-muted" style="font-size: 15px;">{{ Taxen ?? '' }}</p>
                        </div>

                        <div class="text-center" style="width:33%;">
                            @php $logo = camplogo ?? 'default.png'; @endphp
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1 img-fluid" alt="logo" style="max-height: 85px;">
                            </a>
                        </div>

                        <div class="billed-from text-center" style="width:33%;">
                            <span style="font-size: 26px; font-weight: bold;">{{ Namear ?? '' }}</span>
                            <p class="mb-1 text-muted" style="font-size: 15px;">{{ describtionar ?? '' }}</p>
                            <p class="mb-1 text-muted" style="font-size: 15px;">{{ STar ?? '' }}</p>
                            <p class="mb-0 text-muted" style="font-size: 15px;">{{ Taxar ?? '' }}</p>
                        </div>
                    </div>
<!-- عرض تاريخ الإصدار وبداية ونهاية الفترة -->
<div class="row mb-4 text-center" style="background-color: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e9ecef; font-size: 15px;">
    <div class="col-md-4">
        <strong>تاريخ الإصدار:</strong> {{ date('Y-m-d H:i') }}
    </div>
    <div class="col-md-4">
        <strong>بداية الفترة:</strong> {{ $start_at ?? 'غير محدد' }}
    </div>
    <div class="col-md-4">
        <strong>نهاية الفترة:</strong> {{ $end_at ?? date('Y-m-d') }}
    </div>
</div>
                @if (isset($Invoices) && count($Invoices) > 0)
                    {{-- متغيرات التجميع الشامل --}}
                    @php 
                        $grandTotalBeforeTax = 0; 
                        $grandTotalTax = 0; 
                        $grandTotalFinal = 0; 
                    @endphp

                    @foreach ($Invoices as $invoice)
                        @php 
                            $totalprice = 0; $vatrat = 0; $discount = $invoice->discount;
                            $products = App\Models\orderDetails::where('order_owner', $invoice->orderId)->where('returns_purchase', '!=', 0)->get();
                        @endphp
                        
                        <div class="border rounded p-3 mb-5 shadow-sm">
                            <table class="table table-bordered text-center mb-3">
                                <thead class="thead-light">
                                    <tr><th>رقم الفاتورة</th><th>{{ $invoice->orderId }}</th><th>المورد</th><th>{{ optional($invoice->supllier)->name }}</th></tr>
                                </thead>
                            </table>
                            <table class="table table-striped table-bordered text-center">
                                <thead><tr><th>المنتج</th><th>الكمية</th><th>السعر</th><th>الضريبة</th><th>الإجمالي</th></tr></thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        @php 
                                            $totalprice += ($product->returns_purchase * $product->purchasingـprice);
                                            $vatrat = ($product->purchasingـprice > 0) ? round($product->Added_Value / $product->purchasingـprice, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ optional($product->productData)->product_name }}</td>
                                            <td>{{ $product->returns_purchase }}</td>
                                            <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                                            <td>{{ number_format($product->Added_Value, 2) }}</td>
                                            <td>{{ number_format(($product->returns_purchase * $product->purchasingـprice) + ($product->returns_purchase * $product->Added_Value), 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @php 
                                $totalTax = round(($totalprice - $discount) * $vatrat, 2);
                                $invoiceGrandTotal = ($totalprice - $discount) + $totalTax;
                                
                                // التجميع في المتغيرات الشاملة
                                $grandTotalBeforeTax += ($totalprice - $discount);
                                $grandTotalTax += $totalTax;
                                $grandTotalFinal += $invoiceGrandTotal;
                            @endphp

                            <table class="table table-bordered w-75 ml-auto mr-auto mt-3 text-center">
                                <tr><th>الإجمالي قبل الضريبة</th><td>{{ number_format($totalprice - $discount, 2) }}</td></tr>
                                <tr><th>إجمالي الضريبة</th><td>{{ number_format($totalTax, 2) }}</td></tr>
                                <tr class="font-weight-bold bg-light"><th>الإجمالي النهائي</th><td>{{ number_format($invoiceGrandTotal, 2) }}</td></tr>
                            </table>
                        </div>
                    @endforeach

                    <!-- الإجمالي الشامل -->
                    <div class="total-all-box text-center">
                        <h4 style="color: #2c3e50; font-weight: bold;">ملخص المرتجعات الشامل (Total Summary)</h4>
                        <table class="table table-bordered w-75 ml-auto mr-auto mt-3 text-center" style="font-size: 18px;">
                            <tr class="bg-primary text-white">
                                <th>الإجمالي قبل الضريبة (All)</th>
                                <th>إجمالي الضريبة (All)</th>
                                <th>الإجمالي النهائي (All)</th>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>{{ number_format($grandTotalBeforeTax, 2) }}</td>
                                <td>{{ number_format($grandTotalTax, 2) }}</td>
                                <td style="font-size: 22px;">{{ number_format($grandTotalFinal, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                @else
                    <div class="alert alert-warning text-center">لا توجد بيانات</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
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