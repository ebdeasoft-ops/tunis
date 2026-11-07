@extends('layouts.master')
@section('css')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --ink: #1c1f26;
        --muted: #6b7280;
        --line: #e3e5e9;
        --accent: #a97c1f;
        --accent-dark: #1c1f26;
        --panel: #f7f8fa;
        --success: #1a7a4c;
    }
    #print {
        font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
        color: var(--ink);
    }
    .card-invoice {
        background: #fff;
        padding: 36px 40px;
        border-radius: 14px;
        box-shadow: 0 6px 28px rgba(20, 20, 30, 0.07);
    }
    /* ---- top bar / actions ---- */
    .invoice-toolbar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 22px;
    }
    .btn-print {
        background: var(--accent-dark);
        color: #fff;
        border: none;
        border-radius: 9px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 14.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(28, 31, 38, 0.18);
    }
    .btn-print:hover {
        background: #33384a;
        color: #fff;
    }
    /* ---- header ---- */
    .invoice-letterhead {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 18px;
        padding-bottom: 20px;
        border-bottom: 3px solid var(--accent-dark);
        margin-bottom: 22px;
    }
    .company-block {
        font-size: 12.5px;
        line-height: 1.55;
        color: var(--muted);
    }
    .company-block .company-name {
        display: block;
        font-size: 22px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 3px;
    }
    .company-block .tax-line {
        font-size: 20px;
        font-weight: 900;
        color: var(--ink);
    }
        .td-tax-line{
        font-size: 18px;
        font-weight: 900;
        color: black;
    }
    .company-block.en {
        text-align: left;
    }
    .company-block.ar {
        text-align: right;
    }
    .letterhead-logo {
        text-align: center;
    }
    .letterhead-logo img {
        width: 92px;
        height: 82px;
        object-fit: contain;
    }
    /* ---- document title badge ---- */
    .doc-title-row {
        text-align: center;
        margin-bottom: 26px;
    }
    .doc-title-badge {
        display: inline-block;
        border: 1.5px solid var(--accent-dark);
        border-radius: 999px;
        padding: 9px 34px;
        font-size: 15px;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: 0.2px;
    }
    .doc-title-badge small {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: var(--muted);
        letter-spacing: 1px;
        margin-top: 2px;
    }
    /* ---- meta info cards (client / quote) ---- */
    .meta-row {
        display: flex;
        gap: 16px;
        margin-bottom: 22px;
    }
    .meta-card {
        flex: 1;
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: 10px;
        overflow: hidden;
    }
    .meta-card .meta-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        font-size: 13px;
    }
    .meta-card .meta-item + .meta-item {
        border-top: 1px solid var(--line);
    }
    .meta-card .meta-label {
        color: var(--muted);
        font-weight: 600;
    }
    .meta-card .meta-value {
        font-weight: 700;
        color: var(--ink);
    }
    .meta-card .meta-value.quote-no {
        color: #b3261e;
    }
    /* ---- items table ---- */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 22px;
        font-size: 13px;
    }
    .items-table th,
    .items-table td {
        padding: 11px 12px;
        border: 1px solid var(--line);
        vertical-align: middle;
        text-align: center;
    }
    .items-table thead th {
        /* خلفية فاتحة + نص غامق بدل خلفية داكنة + نص أبيض: كده العنوان
           فاضل واضح في الطباعة حتى لو خيار "Background graphics" في
           نافذة الطباعة كان متقفل ومحدش شاف الخلفية أصلاً. */
        background: #eef0f4;
        color: var(--ink);
        font-size: 12.5px;
        font-weight: 700;
        border-bottom: 2px solid var(--accent-dark);
    }
    .items-table thead th span {
        display: block;
        font-size: 10px;
        font-weight: 400;
        opacity: 0.75;
        margin-top: 2px;
    }
    .items-table tbody tr:nth-child(even) {
        background: #fafafb;
    }
    .items-table td.item-name {
        text-align: right;
        padding-right: 14px;
        white-space: normal;
        word-break: break-word;
    }
    .items-table td.item-code {
        direction: ltr;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: var(--muted);
    }
    /* ---- totals ---- */
    .totals-wrap {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 22px;
    }
    .totals-card {
        width: 100%;
        max-width: 420px;
        border: 1px solid var(--line);
        border-radius: 10px;
        overflow: hidden;
    }
    .totals-card .totals-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 18px;
        font-size: 13.5px;
    }
    .totals-card .totals-row + .totals-row {
        border-top: 1px solid var(--line);
    }
    .totals-card .totals-row .t-label {
        color: var(--muted);
        font-weight: 600;
    }
    .totals-card .totals-row .t-value {
        font-weight: 700;
    }
    .totals-card .totals-row.discount .t-value {
        color: #b3261e;
    }
    .totals-card .totals-row.grand {
        /* نفس السبب: خلفية فاتحة + نص ملوّن غامق بدل خلفية خضراء مليانة +
           نص أبيض، عشان السطر ده (الإجمالي الكلي المستحق) يفضل واضح في
           الطباعة حتى من غير طباعة الخلفيات. */
        background: #eaf7f0;
        padding: 14px 18px;
        border-top: 2px solid var(--success);
    }
    .totals-card .totals-row.grand .t-label,
    .totals-card .totals-row.grand .t-value {
        color: var(--success);
    }
    .totals-card .totals-row.grand .t-label {
        font-size: 20px;
        font-weight: 800;
    }
    .totals-card .totals-row.grand .t-value {
        font-size: 20px;
        font-weight: 800;
    }
    /* ---- bank + notes ---- */
    .bank-box {
        border: 1.5px dashed #b7bcc4;
        border-radius: 10px;
        background: var(--panel);
        padding: 12px 16px;
        text-align: center;
        font-size: 13px;
        color: var(--ink);
        margin-bottom: 14px;
    }
    .bank-box strong {
        color: var(--muted);
        font-weight: 600;
    }
    .notes-box {
        background: #fff6e0;
        border: 1px solid #f0dca0;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        color: #6b4e00;
    }
    /* ---- footer ---- */
    .invoice-footer {
        text-align: center;
        font-size: 11.5px;
        color: var(--muted);
        border-top: 1px solid var(--line);
        padding-top: 10px;
        margin-top: 8px;
    }
    /* ================= PRINT ================= */
    @media print {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        @page {
            size: A4;
            margin: 5mm 8mm;
        }
        html, body {
            height: auto !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body * {
            visibility: visible;
        }
        #print, #print * {
            visibility: visible;
        }
        #print_Button, #reciptprinter, .invoice-toolbar {
            display: none !important;
        }
        .card, .card-invoice {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        /* مهم: border-collapse: separate (مش collapse) هنا عشان تريك
           تكرار الـ <thead> في كل صفحة مطبوعة يشتغل في المتصفح — لو
           اتحطت collapse، الهيدر بيختفي من الصفحة التانية وما بعدها. */
        .report-container {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            page-break-inside: auto !important;
        }
        tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }
        thead.report-header {
            display: table-header-group !important;
        }
        tfoot.report-footer {
            display: table-footer-group !important;
        }
        .invoice-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    }
</style>
@endsection
@section('title')
    معاينة طباعة عرض التسعيرة
@stop
@section('page-header')
    <div class="main-parent"></div>
@endsection
@section('content')
<div class="row row-sm">
  <div class="col-md-12 col-xl-12">
    <div class="main-content-body-invoice" id="print">
      <div class="card card-invoice">
        <!-- زر الطباعة -->
        <div class="invoice-toolbar">
            <button class="btn-print" id="print_Button" onclick="printDiv()">
              <i class="mdi mdi-printer"></i> {{ __('home.print') }} طباعة الفاتورة
            </button>
        </div>
        <!-- ملاحظة: border-collapse: separate هنا برضو (بدل collapse)
             لنفس سبب تكرار الهيدر في الطباعة أعلاه. -->
        <table class="report-container" style="width:100%; border-collapse: separate; border-spacing: 0;">
            <!-- رأس التقرير (يتكرر آلياً عند الطباعة في كل صفحة) -->
            <thead class="report-header">
              <tr>
                <th style="border: none; background: transparent !important; padding: 0;">
                  <div class="invoice-letterhead" dir="ltr">
                    <!-- بيانات الشركة بالإنجليزية -->
                    <div class="company-block en">
                      <span class="company-name">{{ Nameen ?? '' }}</span>
                      <p class="tax-line" style="margin: 2px 0;">{{ describtionen ?? '' }}</p>
                      <p class="tax-line" style="margin: 2px 0;">{{ STen ?? '' }}</p>
                      <p class="tax-line" style="margin: 2px 0;"> {{ Taxen ?? '' }}</p>
                    </div>
                    <!-- الشعار -->
                    <div class="letterhead-logo">
                      <?php $logo = camplogo ?? ''; ?>
                      <img src="{{ asset('assets/img/brand') . '/' . $logo }}" class="logo-1" alt="logo">
                    </div>
                    <!-- بيانات الشركة بالعربية -->
                    <div class="company-block ar" dir="rtl">
                      <span class="company-name">{{ Namear ?? '' }}</span>
                      <p class="tax-line" style="margin: 2px 0;">{{ describtionar ?? '' }}</p>
                      <p class="tax-line" style="margin: 2px 0;">{{ STar ?? '' }}</p>
                      <p class="tax-line" style="margin: 2px 0;"> {{Taxar ?? '' }}</p>
                    </div>
                  </div>
                  <div class="doc-title-row">
                    <div class="doc-title-badge">
                      عرض تسعيرة للعميل
                      <small>QUOTATION TO CUSTOMER</small>
                    </div>
                  </div>
                  <?php
                  $offer_price_to_customer = [];
                  if(isset($itemsRequest) && $itemsRequest != []) {
                      $offer_price_to_customer = App\Models\offer_price_to_customer::find($id);
                  }
                  ?>
                </th>
              </tr>
            </thead>
            <!-- محتوى التقرير الأساسي -->
            <tbody class="report-content">
              <tr>
                <td style="border: none; padding: 0;">
                  @if($offer_price_to_customer)
                  <!-- تفاصيل العميل والتسعيرة -->
                  <div class="meta-row">
                    <div class="meta-card">
                      <div class="meta-item">
                        <span class="td-tax-line">اسم العميل / Client</span>
                        <span class="td-tax-line">{{ $offer_price_to_customer->customer->name ?? '' }}</span>
                      </div>
                      <div class="meta-item">
                        <span class="td-tax-line">الرقم الضريبي / Tax No</span>
                        <span class="td-tax-line">{{ $offer_price_to_customer->customer->tax_no ?? '' }}</span>
                      </div>
                    </div>
                    <div class="meta-card">
                      <div class="meta-item">
                        <span class="td-tax-line">تاريخ التسعيرة / Date</span>
                        <span class="td-tax-line">{{ $offer_price_to_customer->created_at ?? '' }}</span>
                      </div>
                      <div class="td-tax-line">
                        <span class="td-tax-line">رقم التسعيرة / Quote No</span>
                        <span class="td-tax-line">#{{ $offer_price_to_customer->id ?? '' }}</span>
                      </div>
                    </div>
                  </div>
                  @endif
                  @if (isset($itemsRequest) && count($itemsRequest) > 0)
                  <!-- جدول المنتجات الرئيسي -->
                  <table class="items-table">
                    <thead>
                      <tr>
                        <th>#</th>
                        @if($offer_price_to_customer && $offer_price_to_customer->numbershowstatus)
                          <th td-tax-line>رقم الصنف<br>Item Code</th>
                        @endif
                        <th td-tax-line style="text-align: right; padding-right: 15px;">اسم الصنف<br>Item Name</th>
                        <th td-tax-line>سعر القطعة<br>Price</th>
                        <th td-tax-line>الكمية<br>Qty</th>
                        <th td-tax-line>الإجمالي<br>Total</th>
                        <th td-tax-line>الخصم<br>Discount</th>
                        <th td-tax-line>الإجمالي بعد الخصم<br>Net Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 0;
                      $totalpricePurchases = 0;
                      $totaldiscount = 0;
                      ?>
                      @foreach ($itemsRequest as $product)
                        <?php
                        $i++;
                        $lineTotal = ($product->PriceWithoudTax * $product->quantity);
                        $totalpricePurchases += $lineTotal;
                        $totaldiscount += $product->discount;
                        ?>
                        <tr>
                          <td  class="td-tax-line">{{ $i }}</td>
                          @if($offer_price_to_customer && $offer_price_to_customer->numbershowstatus)
                            <td class="td-tax-line">{{ $product->productData->Product_Code ?? '' }}</td>
                          @endif
                          <td class="td-tax-line">{{ $product->productData->product_name ?? '' }}</td>
                          <td class="td-tax-line">{{ number_format($product->PriceWithoudTax, 2) }}</td>
                          <td class="td-tax-line">{{ $product->quantity }}</td>
                          <td class="td-tax-line">{{ number_format($lineTotal, 2) }}</td>
                          <td class="td-tax-line">{{ number_format($product->discount, 2) }}</td>
                          <td class="td-tax-line" style="font-weight: 700;">{{ number_format(($lineTotal - $product->discount), 2) }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <!-- جدول الإجماليات النهائية -->
                  <?php $avt = App\Models\Avt::find(1);
                        $subTotalAfterDisc = round($totalpricePurchases, 2) - round($totaldiscount + ($offer_price_to_customer->discount ?? 0), 2);
                        $taxValue = round($subTotalAfterDisc * ($avt->AVT ?? 0), 2);
                        $netTotalWithTax = round($subTotalAfterDisc + $taxValue, 2);
                  ?>
                  <div class="totals-wrap">
                    <div class="totals-card">
                      <div class="totals-row">
                        <span class="td-tax-line">الإجمالي قبل الخصم / Sub Total</span>
                        <span class="td-tax-line">{{ number_format($totalpricePurchases, 2) }}</span>
                      </div>
                      <div class="totals-row discount">
                        <span class="td-tax-line">إجمالي الخصم / Total Discount</span>
                        <span class="td-tax-line">{{ number_format($totaldiscount + ($offer_price_to_customer->discount ?? 0), 2) }}</span>
                      </div>
                      <div class="totals-row">
                        <span class="td-tax-line">المبلغ الخاضع للضريبة / Taxable Amount</span>
                        <span class="td-tax-line">{{ number_format($subTotalAfterDisc, 2) }}</span>
                      </div>
                      <div class="totals-row">
                        <span class="td-tax-line">قيمة ضريبة القيمة المضافة ({{ (($avt->AVT ?? 0) * 100) }}%) / VAT</span>
                        <span class="td-tax-line">{{ number_format($taxValue, 2) }}</span>
                      </div>
                      <div class="totals-row">
                        <span class="td-tax-line">الإجمالي الكلي المستحق / Grand Total</span>
                        <span class="td-tax-line">{{ number_format($netTotalWithTax, 2) }} SAR</span>
                      </div>
                    </div>
                  </div>
                  <!-- معلومات الحساب البنكي -->
               
                  <div class="bank-box">
                    <p class="td-tax-line">البنك:</strong> البنك العربي الوطني &nbsp;|&nbsp;
                    <p class="td-tax-line">رقم الحساب:</strong> <span dir="ltr">0108095553810016</span> &nbsp;|&nbsp;
                    <p class="td-tax-line">الآيبان (IBAN):</strong> <span dir="ltr">SA9830400108095553810016</span>
                  </div>
                  @endif
                  <!-- ملاحظات العميل إن وجدت -->
                  @if($offer_price_to_customer && !empty($offer_price_to_customer->notes))
                    <div class="notes-box">
                      <strong>{{ __('home.notesClient') }} :</strong> {{ $offer_price_to_customer->notes }}
                    </div>
                  @endif
                </td>
              </tr>
            </tbody>
            <!-- تذييل الصفحة الثابت (Footer) -->
            <tfoot class="report-footer">
              <tr>
                <td style="border: none;">
                  <div class="invoice-footer">
                    <span>{{ $addressar ?? '' }}</span> &nbsp;|&nbsp; <span>{{ $addressen ?? '' }}</span>
                  </div>
                </td>
              </tr>
            </tfoot>
          </table>
          <input type="hidden" id="token_search" value="{{ csrf_token() }}">
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