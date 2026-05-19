<table>
    <thead>
        <tr>
            <th colspan="11" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #DCE6F1;">
                تقرير أرباح المبيعات ومرتجع المبيعات
            </th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #F2F2F2;">من تاريخ:</th>
            <th colspan="2">{{ $start }}</th>
            <th colspan="2" style="background-color: #F2F2F2;">إلى تاريخ:</th>
            <th colspan="2">{{ $end }}</th>
            <th colspan="2" style="background-color: #F2F2F2;">وقت التصدير:</th>
            <th>{{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i") }}</th>
        </tr>
    </thead>
</table>

{{-- 1. جدول المبيعات --}}
<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>التاريخ / Date</th>
            <th>رقم الفاتورة / Invoice No</th>
            <th>رقم المنتج / PRODUCT CODE</th>
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
                <td>{{ $item->productData->Product_Code ?? '-' }}</td> 
                <td>{{ $item->productData->product_name ?? '-' }}</td> 
                <td>{{ number_format($qtyTotal, 2) }}</td>
                <td>{{ number_format($item->productData->purchasingـprice, 2) }}</td>
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

<br>

{{-- 2. جدول المرتجعات --}}
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

<br>

