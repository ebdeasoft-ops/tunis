@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background-color: #fff !important;
            -webkit-print-color-adjust: exact;
        }
        .table thead th {
            background-color: #f1f5f9 !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
        }
    }

    /* تصميم عصري وفخم */
    .report-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        overflow: hidden;
    }

    .invoice-title-box {
        background: linear-gradient(135deg, #419BB2 0%, #296878 100%);
        color: #fff;
        padding: 12px 35px;
        border-radius: 50px;
        display: inline-block;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 5px 15px rgba(65, 155, 178, 0.3);
    }

    .info-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
    }

    .text-ellipsis {
        max-width: 280px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .table thead th {
        background-color: #419BB2;
        color: #ffffff;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f1f5f9 !important;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }
</style>
@endsection

@section('title')
{{ __('home.print') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card report-card p-4">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-info px-4 py-2 shadow-sm" id="print_Button" onclick="printDiv()" style="border-radius: 8px; font-weight: bold;">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body pt-0">
                    <!-- ترويسة الفاتورة / الشركة -->
                    <div class="invoice-header d-flex justify-content-between align-items-center border-bottom pb-4 mb-4" style="width:100%">
                        
                        <div class="billed-from text-left" style="width:33%;">
                            <span style="font-size:18px; font-weight: 800; color: #1e293b;">{{Nameen}}</span>
                            <p class="text-muted mb-1" dir="ltr" style="font-size: 13px;">{{describtionen}}</p>
                            <span class="d-block text-muted" dir="ltr" style="font-size: 13px;">{{STen}}</span>
                            <p class="text-muted mb-0" dir="ltr" style="font-size: 13px;">{{Taxen}}</p>
                        </div>

                        <div class="text-center" style="width:34%;">
                            <?php $logo = camplogo; ?>
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1 img-fluid" alt="logo" style="max-height: 70px; object-fit: contain;">
                            </a>
                        </div>

                        <div class="billed-from text-right" style="width:33%;">
                            <span style="font-size:18px; font-weight: 800; color: #1e293b;">{{Namear}}</span>
                            <p class="text-muted mb-1" style="font-size: 13px;">{{describtionar}}</p>
                            <span class="d-block text-muted" style="font-size: 13px;">{{STar}}</span>
                            <p class="text-muted mb-0" style="font-size: 13px;">{{Taxar}}</p>
                        </div>
                    </div><!-- invoice-header -->

                    <!-- عنوان التقرير -->
                    <div class="text-center my-4">
                        <span class="invoice-title-box">
                            حركة المنتج &nbsp;—&nbsp; Product Transactions
                        </span>
                    </div>

                    <!-- معلومات التقرير ووقت التصدير -->
                    <div class="row align-items-center mb-4 mx-0 info-pill">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="text-secondary font-weight-bold">
                                <i class="far fa-clock text-info ml-1"></i> {{ __('home.exportTime') }} : 
                            </span>
                            <span class="text-dark font-weight-bold">
                                {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                            </span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <span class="text-secondary font-weight-bold mr-3">
                                <i class="far fa-calendar-alt text-info ml-1"></i> {{ __('report.from') }} : 
                                <span class="text-dark">{{ $data['start_at'] }}</span>
                            </span>
                            <span class="text-secondary font-weight-bold">
                                <i class="far fa-calendar-check text-info ml-1"></i> {{ __('report.to') }} : 
                                <span class="text-dark">{{ $data['end_at'] }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- الجدول -->
                    <div class="table-responsive">
                        <table class="table text-md-nowrap mb-0 table-hover text-center align-middle" style="border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0;">
                            <thead>
                                <tr>
                                    <th class="py-3">{{ __('report.invoiceNo') }}</th>
                                    <th class="py-3">{{ __('home.productNo') }}</th>
                                    <th class="py-3">{{ __('home.product') }}</th>
                                    <th class="py-3">{{ __('report.date') }}</th>
                                    <th class="py-3">{{ __('home.oping') }}</th>
                                    <th class="py-3">{{ __('home.quantity') }}</th>
                                    <th class="py-3">{{ __('home.total') }}</th>
                                    <th class="py-3">{{ __('home.operationtype') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($data['products'] as $invoice)
                                    @php 
                                        $i++;
                                        $productid = $invoice['Product_Code'];
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="font-weight-bold text-muted">{{ $invoice['id'] }}</td>
                                        <td dir="ltr" class="font-weight-bold text-dark">{{ $invoice['Product_Code'] }}</td>
                                        <td class="text-ellipsis font-weight-bold text-dark" title="{{ $invoice['product_name'] }}">{{ $invoice['product_name'] }}</td>
                                        <td class="text-muted" style="font-size: 13px;">{{ $invoice['created_at'] }}</td>
                                        
                                        <!-- الرصيد -->
                                        @if($invoice['type'] == 1 || $invoice['type'] == 4 || $invoice['type'] == 6 || $invoice['type'] == 8)
                                            <td class="font-weight-bold text-success">{{ $invoice['current_balance'] + $invoice['quantity'] }}</td>
                                        @elseif($invoice['type'] == 2 || $invoice['type'] == 3 || $invoice['type'] == 5)
                                            <td class="font-weight-bold text-success">{{ $invoice['current_balance'] - $invoice['quantity'] }}</td>
                                        @else
                                            <td class="font-weight-bold text-muted">-</td>
                                        @endif

                                        <td class="font-weight-bold">{{ $invoice['quantity'] }}</td>
                                        <td class="font-weight-bold text-info">{{ number_format(($invoice['quantity'] * $invoice['price']) - $invoice['discount'], 2) }}</td>
                                        
                                        <!-- نوع العملية -->
                                        <td>
                                            @if($invoice['type'] == 2 || $invoice['type'] == 3 || $invoice['type'] == 5)
                                                <span class="badge badge-success badge-status">{{ $invoice['operation'] }}</span>
                                            @else
                                                <span class="badge badge-danger badge-status">{{ $invoice['operation'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- table-responsive -->

                </div><!-- card-body -->
            </div><!-- card -->
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