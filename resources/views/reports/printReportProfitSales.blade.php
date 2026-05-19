@extends('layouts.master')

@section('css')
<style>
    @media print {
        #print_Button { display: none !important; }
        body { margin: 0; padding: 0; }
        .card { border: none !important; box-shadow: none !important; }
    }

    .double {
        border: 3px double #419BB2;
        padding: 5px 25px;
        border-radius: 5px;
        display: inline-block;
        font-weight: bold;
        background-color: #f8f9fa;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .table th {
        background-color: #ecf0fa !important;
        color: #419BB2 !important;
        vertical-align: middle !important;
        font-size: 12px;
    }

    .table td { font-size: 12px; vertical-align: middle !important; }
    
    .header-info p { margin-bottom: 2px; font-size: 13px; }
</style>
@endsection

@section('title', 'تقرير الأرباح | Profit Report')

@section('content')
<div class="row row-sm">
    <div class="col-md-12">
        <div class="d-flex justify-content-center mb-3">
            <button class="btn btn-danger" id="print_Button" onclick="printDiv()">
                طباعة / Print <i class="mdi mdi-printer ml-1"></i>
            </button>
        </div>

        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice p-4">
                <div class="card-body">
                    <!-- الهيدر العربي والإنجليزي -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="header-info text-center" style="width:35%;">
                            <h5 class="font-weight-bold">{{ Namear }}</h5>
                            <p>{{ describtionar }}</p>
                            <p>س.ت: {{ STen }}</p>
                            <p>الرقم الضريبي {{Taxen}}</p>
                        </div>

                        <div class="text-center" style="width:30%;">
                            <img src="{{ asset('assets/img/brand/'.camplogo) }}" alt="logo" style="width: 120px;">
                        </div>

                        <div class="header-info text-center" style="width:35%;" dir="ltr">
                            <h5 class="font-weight-bold">{{ Nameen }}</h5>
                            <p>{{ describtionen }}</p>
                            <p>C.R: {{ STen }}</p>
                            <p>VAT: {{ Taxen }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center my-3">
                        <div class='double'>
                            تقرير أرباح المبيعات والمرتجع <br>
                            Sales & Returns Profit Report
                        </div>
                    </div>

                    <!-- معلومات التقرير -->
                    <table class="table table-bordered text-center mb-4">
                        <thead>
                            <tr>
                                <th>من تاريخ / From</th>
                                <td>{{ $start }}</td>
                                <th>إلى تاريخ / To</th>
                                <td>{{ $end }}</td>
                                <th>وقت الاستخراج / Time</th>
                                <td>{{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i") }}</td>
                            </tr>
                        </thead>
                    </table>

<!-- جدول المبيعات -->
<h6 class="font-weight-bold text-primary">المبيعات / Sales</h6>
<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>التاريخ / Date</th>
            <th>رقم الفاتورة / Invoice No</th>
            <th>المنتج / Product</th>
            <th>الكمية / Qty</th>
            <th> تكلفة القطعة </th>
            <th>التكلفة / Cost</th>
            <th>البيع / Price</th>
            <th>الخصم / Disc.</th>
            <th>الربح / Profit</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $tP = 0; $tC = 0; $tS = 0; $tD = 0; $tQ = 0;
        @endphp
        @foreach ($data['sales'] as $index => $item)
            @php
                $invoice = $item->Invoice;
                $totalItemsDiscount = \App\Models\sales::where('invoice_id', $item->invoice_id)->sum('Discount_Value');
                
                $discount_on_invoice =$invoice->discount==0 ?$invoice->discountOnInvoice: ($invoice->discount?? 0) - $totalItemsDiscount ;

                $totalGrossAmount = \App\Models\sales::where('invoice_id', $item->invoice_id)
                    ->selectRaw('SUM((quantity + quantityreturn) * Unit_Price) as total')
                    ->first()
                    ->total;

                $invTotalBeforeGeneralDiscount = ($totalGrossAmount ?? 0);
                $qtyTotal = ($item->quantity + $item->quantityreturn);
                $itemGrossSales = $item->Unit_Price * $qtyTotal;

                $itemWeight = ($invTotalBeforeGeneralDiscount > 0) ? ($itemGrossSales / $invTotalBeforeGeneralDiscount) : 0;
                $itemShareOfGeneralDiscount = $discount_on_invoice * $itemWeight;

                $cost = ($item->productData->purchasingـprice ?? 0) * $qtyTotal;
                $sales = $itemGrossSales;
                $totalItemDiscount = $item->Discount_Value + $itemShareOfGeneralDiscount;
                $profit = $sales - $cost - $totalItemDiscount;

                // تجميع الإجماليات
                $tP += $profit; $tC += $cost; $tS += $sales; $tD += $totalItemDiscount; $tQ += $qtyTotal;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('Y-m-d h:i:s') }}</td>
                <td>{{ $item->invoice_id }}</td>
                <td>{{ $item->productData->product_name ?? '-' }}</td> 
                <td>{{ number_format($qtyTotal, 2) }}</td>
                <td>{{ number_format($item->productData->purchasingـpric, 2) }}</td>
                <td>{{ number_format($cost, 2) }}</td>
                <td>{{ number_format($sales, 2) }}</td>
                <td>{{ number_format($totalItemDiscount, 2) }}</td>
                <td class="text-success font-weight-bold">{{ number_format($profit, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <!-- إجماليات المبيعات -->
    <tfoot class="bg-light font-weight-bold">
        <tr>
            <td colspan="4">الإجمالي / Total</td>
            <td>{{ number_format($tQ, 2) }}</td>
            <td>{{ number_format($tC, 2) }}</td>
            <td>{{ number_format($tS, 2) }}</td>
            <td>{{ number_format($tD, 2) }}</td>
            <td class="text-success">{{ number_format($tP, 2) }}</td>
        </tr>
    </tfoot>
</table>

<!-- جدول المرتجعات -->
<h6 class="font-weight-bold text-danger mt-4">المرتجعات / Returns</h6>
<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>التاريخ / Date</th>  
            <th>رقم الفاتورة / Invoice No</th>
            <th>المنتج / Product</th>
            <th>الكمية / Qty</th>
            <th>التكلفة / Cost</th>
            <th>الخصم / Disc.</th>
            <th>المسترجع / Return</th>
            <th>الربح المفقود / Loss</th>
        </tr>
    </thead>
    <tbody>
        @php $tRP = 0; $tRC = 0; $tRS = 0; $tRD = 0; $tRQ = 0; @endphp
        @foreach ($data['returns'] as $index => $item)
            @php
                $totalItemDiscount = ($item->discountoninvoice ?? 0) + ($item->discountvalue ?? 0);
                $costR = ($item->productData->purchasingـprice ?? 0) * $item->return_quantity;
                $salesR = ($item->return_Unit_Price * $item->return_quantity);
                $profitR = $salesR - $costR - $totalItemDiscount;

                // تجميع الإجماليات
                $tRP += $profitR; $tRC += $costR; $tRS += ($salesR - $totalItemDiscount); $tRD += $totalItemDiscount; $tRQ += $item->return_quantity;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('Y-m-d h:i:s') }}</td>
                <td>{{ $item->invoice_id }}</td>
                <td>{{ $item->productData->product_name ?? '-' }}</td>
                <td>{{ number_format($item->return_quantity, 2) }}</td>
                <td>{{ number_format($costR, 2) }}</td>
                <td>{{ number_format($totalItemDiscount, 2) }}</td>
                <td>{{ number_format($salesR - $totalItemDiscount, 2) }}</td>
                <td class="text-danger font-weight-bold">{{ number_format($profitR, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <!-- إجماليات المرتجعات -->
    <tfoot class="bg-light font-weight-bold">
        <tr>
            <td colspan="4">إجمالي المرتجعات / Total Returns</td>
            <td>{{ number_format($tRQ, 2) }}</td>
            <td>{{ number_format($tRC, 2) }}</td>
            <td>{{ number_format($tRD, 2) }}</td>
            <td>{{ number_format($tRS, 2) }}</td>
            <td class="text-danger">{{ number_format($tRP, 2) }}</td>
        </tr>
    </tfoot>
</table>
<!-- الخلاصة النهائية / Net Summary -->
<div class="row mt-4">
    <div class="col-md-6">
        <h6 class="font-weight-bold text-dark">ملخص الأرباح / Profit Summary</h6>
        <table class="table table-bordered shadow-sm">
            <tr class="bg-primary text-white">
                <th>إجمالي ربح المبيعات / Total Sales Profit</th>
                <td class="font-weight-bold">{{ number_format($tP, 2) }}</td>
            </tr>
            <tr class="bg-danger text-white">
                <th>إجمالي خسارة المرتجعات / Total Return Loss</th>
                <td class="font-weight-bold">{{ number_format($tRP, 2) }}</td>
            </tr>
            <tr class="bg-light" style="border-top: 2px solid #000;">
                <th style="font-size: 1.1rem;">صافي الربح / Net Profit</th>
                <th class="text-primary" style="font-size: 1.3rem;">
                    {{ number_format($tP - $tRP, 2) }} 
                    <small style="font-size: 12px;">SAR</small>
                </th>
            </tr>
        </table>
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