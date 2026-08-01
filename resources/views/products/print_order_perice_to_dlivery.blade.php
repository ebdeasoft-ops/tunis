@extends('layouts.master')

@section('css')
<style>
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

    #print_Button, #reciptprinter {
      display: none !important;
    }

    .card, .card-invoice {
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

  /* التصميم العام والشكل الفاخر على الشاشة */
  .card-invoice {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  }

  .invoice-header-box {
    border-bottom: 2px solid #2b2b2b;
    padding-bottom: 15px;
    margin-bottom: 20px;
  }

  .badge-delivery {
    border: 2px solid #2b2b2b;
    border-radius: 8px;
    width: 300px;
    font-size: 15px !important;
    padding: 10px;
    margin: 15px auto;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: bold;
    color: #333;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  }

  .table-custom {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    border-collapse: collapse;
  }

  .table-custom th, .table-custom td {
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
</style>
@endsection

@section('title')
معاينه طباعة المنتجات
@stop

@section('page-header')
<div class="main-parent">
  <!-- breadcrumb -->
</div>
@endsection

@section('content')
<!-- row -->
<div class="row row-sm">
  <div class="col-md-12 col-xl-12">
    <div class="main-content-body-invoice" id="print">
      <div class="card card-invoice">
        
        <!-- زر الطباعة -->
        <div class="row mb-4" style="display: flex; justify-content: flex-end; width: 100%;">
          <button class="btn btn-dark px-4 py-2 shadow-sm" id="print_Button" onclick="printDiv()">
            <i class="mdi mdi-printer ml-1"></i>{{__('home.print')}}
          </button>
        </div>

        <div class="card-body p-0">
          <table class="report-container">

            <thead class="report-header">
              <tr>
                <th class="report-header-cell" style="border: none; background: transparent !important;">
                  
                  <div class="invoice-header-box" style="display: flex; justify-content: space-between; width: 100%; align-items: center;" dir="ltr">
                    
                    <!-- بيانات الشركة إنجليزي -->
                    <div style="width: 33%; text-align: left; font-size: 12px; line-height: 1.4;">
                      <span style="font-size: 15px; font-weight: bold; color: #111;">{{Nameen ?? ''}}</span>
                      <p style="margin: 2px 0;" dir="ltr">{{ describtionen ?? '' }}</p>
                      <p style="margin: 2px 0;" dir="ltr">{{ STen ?? '' }}</p>
                      <p style="margin: 2px 0; font-weight: bold;" dir="ltr">{{ Taxen ?? '' }}</p>
                    </div>

                    <!-- الشعار -->
                    <div style="width: 33%; text-align: center;">
                      <?php $logo = camplogo ?? ''; ?>
                      <a href="https://ebdeasoft.com/">
                        <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 95px; height: 85px; object-fit: contain;">
                      </a>
                    </div>

                    <!-- بيانات الشركة عربي -->
                    <div style="width: 33%; text-align: right; font-size: 12px; line-height: 1.4;" dir="rtl">
                      <span style="font-size: 15px; font-weight: bold; color: #111;">{{Namear ?? ''}}</span>
                      <p style="margin: 2px 0;">{{ describtionar ?? '' }}</p>
                      <p style="margin: 2px 0;">{{ STar ?? '' }}</p>
                      <p style="margin: 2px 0; font-weight: bold;">{{ Taxar ?? '' }}</p>
                    </div>

                  </div>

                  <div class="text-center">
                    <div class="badge-delivery">
                      تسليم منتجات <br>
                      <span style="font-size: 12px; color: #666; font-weight: normal;">Delivery of Products</span>
                    </div>
                  </div>

                  <br>
                </th>
              </tr>
            </thead>

            <tbody class="report-content">
              <tr>
                <td class="report-content-cell" style="border: none; padding-top: 10px;">

                  @if (isset($data) && isset($data['supplier']))
                  <!-- بيانات العميل/المورد -->
                  <div style="display: flex; justify-content: center; width: 100%; margin-bottom: 25px;">
                    <table class="table-custom table-light-custom text-center" style="width: 50%;">
                      <thead>
                        <tr>
                          <th style="width: 45%;">CLIENT NAME <br> اسم العميل</th>
                          <td style="font-weight: bold;" class="tx-16">{{$data['supplier']->supllier->name ?? ''}}</td>
                        </tr>
                        <tr>
                          <th>TAX NUMBER <br> الرقم الضريبي</th>
                          <td class="tx-16">{{$data['supplier']->supllier->tax_no ?? ''}}</td>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  @endif

                  @if (isset($data) && isset($data['items']) && count($data['items']) > 0)
                  <!-- جدول تفاصيل المنتجات -->
                  <div style="margin-bottom: 25px;">
                    <table class="table-custom text-center">
                      <thead>
                        <tr>
                          <th>NO<br><span style="font-size:10px;">رقم</span></th>
                          <th>Item NO<br><span style="font-size:10px;">رقم منتج</span></th>
                          <th style="text-align: right; padding-right: 15px;">ITEM NAME<br><span style="font-size:10px;">اسم الصنف</span></th>
                          <th>PRODUCT PRICE<br><span style="font-size:10px;">سعر القطعة</span></th>
                          <th>QUANTITY<br><span style="font-size:10px;">الكمية</span></th>
                          <th>TOTAL AMOUNT<br><span style="font-size:10px;">الاجمالي</span></th>
                          <th>DISCOUNT<br><span style="font-size:10px;">الخصم</span></th>
                          <th>Total AFTER DISCOUNT<br><span style="font-size:10px;">الاجمالي بعد الخصم</span></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $i = 0;
                        $totalpricePurchases = 0;
                        $totaldiscount = 0;
                        ?>
                        @foreach ($data['items'] as $product)
                          <?php 
                          $i++;
                          $lineTotal = ($product->cost * $product->quantity);
                          $totalpricePurchases += $lineTotal;
                          $totaldiscount += $product->discount;
                          ?>
                          <tr>
                            <td>{{ $i }}</td>
                            <td dir="ltr" style="font-family: monospace;">{{ $product->productData->Product_Code ?? '' }}</td>
                            <td style="text-align: right; padding-right: 15px;" class="text-truncate-custom">{{ $product->productData->product_name ?? '' }}</td>
                            <td>{{ number_format($product->cost, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ number_format($lineTotal, 2) }}</td>
                            <td>{{ number_format($product->discount, 2) }}</td>
                            <td style="font-weight: bold;">{{ number_format(($lineTotal - $product->discount), 2) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <!-- جدول الإجماليات النهائية والضرائب -->
                  <div style="margin-bottom: 20px;">
                    <?php 
                    $avt = App\Models\Avt::find(1); 
                    $subTotal = round($totalpricePurchases, 2);
                    $totalDisc = round($totaldiscount, 2);
                    $subTotalAfterDisc = $subTotal - $totalDisc;
                    $taxAmount = round($subTotalAfterDisc * ($avt->AVT ?? 0), 2);
                    $grandTotal = round($taxAmount + $subTotalAfterDisc, 2);
                    ?>
                    
                    <table class="table-custom text-center">
                      <thead>
                        <tr>
                          <th>SUB TOTAL<br><span style="font-size:10px;">الاجمالي</span></th>
                          <th>DISCOUNT<br><span style="font-size:10px;">الخصم</span></th>
                          <th>SUB TOTAL AFTER DISCOUNT<br><span style="font-size:10px;">الاجمالي بعد الخصم</span></th>
                          <th>VALUE ADDED TAX ({{($avt->AVT ?? 0)*100}}%)<br><span style="font-size:10px;">ضريبة القيمة المضافة</span></th>
                          <th style="background-color: #212529 !important; color: #fff !important;">NET TOTAL<br><span style="font-size:10px;">الاجمالي الكلي</span></th>
                        </tr>
                        <tr>
                          <td style="font-weight: bold;">{{ number_format($subTotal, 2) }}</td>
                          <td style="font-weight: bold; color: #d9534f;">{{ number_format($totalDisc, 2) }}</td>
                          <td style="font-weight: bold;">{{ number_format($subTotalAfterDisc, 2) }}</td>
                          <td style="font-weight: bold;">{{ number_format($taxAmount, 2) }}</td>
                          <td style="font-size: 16px; font-weight: bold; color: #28a745; background: #e8f5e9;">{{ number_format($grandTotal, 2) }}</td>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  @endif

                </td>
              </tr>
            </tbody>

            <!-- تذييل الصفحة -->
            <tfoot class="report-footer">
              <tr>
                <td style="border: none;">
                  <div class="footer">
                    @if(Auth()->user()->branchs_id == 1)
                      <span>{{ addressar ?? '' }}</span> | <span>{{ addressen ?? '' }}</span>
                    @endif
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
<!-- row closed -->
@endsection

@section('js')
<!-- Internal Chart.bundle js -->
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