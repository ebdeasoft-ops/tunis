@extends('layouts.master')

@section('css')
  <style>
    /* تنسيقات الطباعة الاحترافية وإلغاء الصفحات الفارغة */
    @media print {
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;

      @page {
        size: A4;
        margin: 5mm 8mm;
      }

      html,
      body {
        height: auto !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      body * {
        visibility: visible;
      }

      #print,
      #print * {
        visibility: visible;
      }

      #print_Button,
      #reciptprinter {
        display: none !important;
      }

      .card,
      .card-invoice {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      .report-container {
        width: 100% !important;
        border-collapse: collapse;
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

      .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        font-size: 11px;
        color: #555;
        border-top: 1px solid #ddd;
        padding-top: 5px;
      }
    }

    /* التصميم العام على الشاشة */
    .card-invoice {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .invoice-header-box {
      border-bottom: 2px solid #2b2b2b;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }

    .badge-quote {
      border: 2px solid #2b2b2b;
      border-radius: 8px;
      width: 320px;
      font-size: 15px !important;
      padding: 10px;
      margin: 15px auto;
      text-align: center;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      font-weight: bold;
      color: #333;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .table-custom {
      width: 100%;
      margin-bottom: 1rem;
      color: #212529;
      border-collapse: collapse;
    }

    .table-custom th,
    .table-custom td {
      padding: 10px 12px;
      vertical-align: middle;
      border: 1px solid #dee2e6;
    }

    .table-custom thead th {
      background-color: #343a40 !important;
      color: #fff !important;
      font-size: 13px;
      text-align: center;
      border-color: #343a40;
    }

    .table-light-custom th {
      background-color: #f1f3f5 !important;
      color: #333 !important;
      font-size: 13px;
      border: 1px solid #ced4da;
    }

    .text-truncate-custom {
      max-width: 250px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .bank-box {
      border: 2px dashed #6c757d;
      border-radius: 8px;
      background: #fafbfc;
      padding: 12px;
      text-align: center;
      font-size: 14px;
      color: #495057;
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
          <div class="row mb-4" style="display: flex; justify-content: flex-end; width: 100%;">
            <button class="btn btn-dark px-4 py-2 shadow-sm" id="print_Button" onclick="printDiv()">
              <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }} طباعة الفاتورة
            </button>
          </div>

          <div class="card-body p-0">
            <table class="report-container">

              <!-- رأس التقرير (يتكرر آلياً عند الطباعة في كل صفحة) -->
              <thead class="report-header">
                <tr>
                  <th style="border: none; background: transparent !important;">
                    <div class="invoice-header-box"
                      style="display: flex; justify-content: space-between; width: 100%; align-items: center;" dir="ltr">

                      <!-- بيانات الشركة بالإنجليزية -->
                      <div style="width: 33%; text-align: left; font-size: 12px; line-height: 1.4;">
                        <span style="font-size: 15px; font-weight: bold; color: #111;">{{ Nameen ?? '' }}</span>
                        <p style="margin: 2px 0;">{{ describtionen ?? '' }}</p>
                        <p style="margin: 2px 0;">{{ STen ?? '' }}</p>
                        <p style="margin: 2px 0; font-weight: bold;">Tax No: {{ Taxen ?? '' }}</p>
                      </div>

                      <!-- الشعار -->
                      <div style="width: 33%; text-align: center;">
                        <?php $logo = camplogo ?? ''; ?>
                        <img src="{{ asset('assets/img/brand') . '/' . $logo }}" class="logo-1" alt="logo"
                          style="width: 95px; height: 85px; object-fit: contain;">
                      </div>

                      <!-- بيانات الشركة بالعربية -->
                      <div style="width: 33%; text-align: right; font-size: 12px; line-height: 1.4;" dir="rtl">
                        <span style="font-size: 15px; font-weight: bold; color: #111;">{{ Namear ?? '' }}</span>
                        <p style="margin: 2px 0;">{{ describtionar ?? '' }}</p>
                        <p style="margin: 2px 0;">{{ STar ?? '' }}</p>
                        <p style="margin: 2px 0; font-weight: bold;">الرقم الضريبي: {{ Taxar ?? '' }}</p>
                      </div>

                    </div>

                    <div class="text-center">
                      <div class="badge-quote">
                        عرض تسعيرة للعميل <br>
                        <span style="font-size: 12px; color: #666; font-weight: normal;">QUOTATION TO CUSTOMER</span>
                      </div>
                    </div>

                    <?php
  $offer_price_to_customer = [];
  if (isset($itemsRequest) && $itemsRequest != []) {
    $offer_price_to_customer = App\Models\offer_price_to_customer::find($id);
  }
                    ?>
                  </th>
                </tr>
              </thead>

              <!-- محتوى التقرير الأساسي -->
              <tbody class="report-content">
                <tr>
                  <td style="border: none; padding-top: 10px;">

                    @if($offer_price_to_customer)
                      <!-- تفاصيل العميل والتسعيرة -->
                      <div style="display: flex; justify-content: space-between; gap: 15px; margin-bottom: 20px;">

                        <!-- جدول بيانات العميل -->
                        <table class="table-custom table-light-custom text-center" style="width: 49%;">
                          <thead>
                            <tr>
                              <th style="width: 40%;">اسم العميل / Client</th>
                              <td style="font-weight: bold;">{{ $offer_price_to_customer->customer->name ?? '' }}</td>
                            </tr>
                            <tr>
                              <th>الرقم الضريبي / Tax No</th>
                              <td>{{ $offer_price_to_customer->customer->tax_no ?? '' }}</td>
                            </tr>
                          </thead>
                        </table>

                        <!-- جدول بيانات التسعيرة -->
                        <table class="table-custom table-light-custom text-center" style="width: 49%;">
                          <thead>
                            <tr>
                              <th style="width: 40%;">تاريخ التسعيرة / Date</th>
                              <td>{{ $offer_price_to_customer->created_at ?? '' }}</td>
                            </tr>
                            <tr>
                              <th>رقم التسعيرة / Quote No</th>
                              <td style="font-weight: bold; color: #d9534f;">#{{ $offer_price_to_customer->id ?? '' }}</td>
                            </tr>
                          </thead>
                        </table>

                      </div>
                    @endif

                    @if (isset($itemsRequest) && count($itemsRequest) > 0)
                                      <!-- جدول المنتجات الرئيسي -->
                                      <div style="margin-bottom: 20px;">
                                        <table class="table-custom text-center">
                                          <thead>
                                            <tr>
                                              <th>#</th>
                                              @if($offer_price_to_customer && $offer_price_to_customer->numbershowstatus)
                                                <th>رقم الصنف<br><span style="font-size:10px;">Item Code</span></th>
                                              @endif
                                              <th style="text-align: right; padding-right: 15px;">اسم الصنف<br><span
                                                  style="font-size:10px;">Item Name</span></th>
                                              <th>سعر القطعة<br><span style="font-size:10px;">Price</span></th>
                                              <th>الكمية<br><span style="font-size:10px;">Qty</span></th>
                                              <th>الإجمالي<br><span style="font-size:10px;">Total</span></th>
                                              <th>الخصم<br><span style="font-size:10px;">Discount</span></th>
                                              <th>الإجمالي بعد الخصم<br><span style="font-size:10px;">Net Total</span></th>
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
                                                                    <td>{{ $i }}</td>
                                                                    @if($offer_price_to_customer && $offer_price_to_customer->numbershowstatus)
                                                                      <td dir="ltr" style="font-family: monospace;">{{ $product->productData->Product_Code ?? '' }}
                                                                      </td>
                                                                    @endif
                                                                    <td
                                                                      style="text-align: right; padding-right: 15px; white-space: normal; word-break: break-word;">
                                                                      {{ $product->productData->product_name ?? '' }}
                                                                    </td>
                                                                    <td>{{ number_format($product->PriceWithoudTax, 2) }}</td>
                                                                    <td>{{ $product->quantity }}</td>
                                                                    <td>{{ number_format($lineTotal, 2) }}</td>
                                                                    <td>{{ number_format($product->discount, 2) }}</td>
                                                                    <td style="font-weight: bold;">{{ number_format(($lineTotal - $product->discount), 2) }}</td>
                                                                  </tr>
                                            @endforeach
                                          </tbody>
                                        </table>
                                      </div>

                                      <!-- جدول الإجماليات النهائية -->
                                      <div style="margin-bottom: 20px;">
                                        <?php  $avt = App\Models\Avt::find(1);
                      $subTotalAfterDisc = round($totalpricePurchases, 2) - round($totaldiscount + ($offer_price_to_customer->discount ?? 0), 2);
                      $taxValue = round($subTotalAfterDisc * ($avt->AVT ?? 0), 2);
                      $netTotalWithTax = round($subTotalAfterDisc + $taxValue, 2);
                                        ?>
                                        <table class="table-custom text-center" style="background: #fdfdfe;">
                                          <thead>
                                            <tr>
                                              <th>الإجمالي قبل الخصم<br><span style="font-size:10px;">Sub Total</span></th>
                                              <th>إجمالي الخصم<br><span style="font-size:10px;">Total Discount</span></th>
                                            </tr>
                                            <tr>
                                              <td style="font-size: 15px; font-weight: bold;">{{ number_format($totalpricePurchases, 2) }}
                                              </td>
                                              <td style="font-size: 15px; font-weight: bold; color: #d9534f;">
                                                {{ number_format($totaldiscount + ($offer_price_to_customer->discount ?? 0), 2) }}</td>
                                            </tr>
                                          </thead>
                                        </table>

                                        <table class="table-custom text-center" style="margin-top: -10px;">
                                          <thead>
                                            <tr>
                                              <th>المبلغ الخاضع للضريبة<br><span style="font-size:10px;">Taxable Amount</span></th>
                                              <th>قيمة ضريبة القيمة المضافة ({{ (($avt->AVT ?? 0) * 100) }}%)<br><span
                                                  style="font-size:10px;">VAT Amount</span></th>
                                              <th style="background-color: #212529 !important; color: #fff !important;">الإجمالي الكلي
                                                المستحق<br><span style="font-size:10px;">Grand Total</span></th>
                                            </tr>
                                            <tr>
                                              <td style="font-size: 15px; font-weight: bold;">{{ number_format($subTotalAfterDisc, 2) }}
                                              </td>
                                              <td style="font-size: 15px; font-weight: bold;">{{ number_format($taxValue, 2) }}</td>
                                              <td style="font-size: 17px; font-weight: bold; color: #28a745; background: #e8f5e9;">
                                                {{ number_format($netTotalWithTax, 2) }} SAR</td>
                                            </tr>
                                          </thead>
                                        </table>
                                      </div>

                                      <!-- معلومات الحساب البنكي -->
                                      <div class="bank-box mb-3">
                                        <strong>البنك:</strong> {{ bankname ?? 'غير متوفر' }} &nbsp;|&nbsp;
                                        <strong>رقم الحساب:</strong> <span dir="ltr">{{ bank_acount_number ?? '---' }}</span> &nbsp;|&nbsp;
                                        <strong>الآيبان (IBAN):</strong> <span dir="ltr">{{ bank_acount_iban ?? '---' }}</span>
                                      </div>
                    @endif

                    <!-- ملاحظات العميل إن وجدت -->
                    @if($offer_price_to_customer && !empty($offer_price_to_customer->notes))
                      <div
                        style="background: #fff3cd; border: 1px solid #ffeeba; padding: 10px 15px; border-radius: 6px; font-size: 13px; color: #856404; margin-top: 15px;">
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
                    <div class="footer">
                      <span>{{ addressar ?? '' }}</span> | <span>{{ addressen ?? '' }}</span>
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