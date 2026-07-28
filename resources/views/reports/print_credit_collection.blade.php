@extends('layouts.master')

@section('css')
<style>
    @media print {
        #print_Button {
            display: none !important;
        }
        body {
            border: none !important;
            font-size: 12pt;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    body {
        font-family: 'Georgia', "Times New Roman", Times, serif;
        line-height: 1.6;
        color: #333;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-bottom: 2px solid #eee;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .billed-from {
        width: 33%;
        text-align: center;
    }

    .company-logo {
        width: 110px;
        height: 70px;
        object-fit: contain;
    }

    .voucher-title {
        color: #2c3e50;
        font-weight: bold;
        margin: 20px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table-custom th {
        background-color: #ecf0fa !important;
        color: #419BB2 !important;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }
</style>
@endsection

@section('title')
    {{ __('home.voucher') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.voucher') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.index') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice p-4">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center mb-4">
                    <button class="btn btn-danger print-style" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body">
                    <!-- رأس الفاتورة (بيانات الشركة يمين ويسار والشعار بالمنتصف) -->
                    <div class="invoice-header">
                        <!-- بيانات الشركة بالإنجليزية -->
                        <div class="billed-from">
                            <h4 style="font-size: 22px; font-weight: bold;">{{ $Nameen ?? '' }}</h4>
                            <p dir="ltr" class="mb-1">{{ describtionen ?? '' }}</p>
                            <span dir="ltr" class="d-block">{{ STen ?? '' }}</span>
                            <p dir="ltr" class="mb-0">{{ Taxen ?? '' }}</p>
                        </div>

                        <!-- الشعار -->
                        <div class="billed-from text-center">
                                  
                                                    <?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

                        </div>

                        <!-- بيانات الشركة بالعربية -->
                        <div class="billed-from">
                            <h4 style="font-size: 22px; font-weight: bold;">{{ $Namear ?? '' }}</h4>
                            <p class="mb-1">{{ describtionar ?? '' }}</p>
                            <p class="mb-1">{{ STar ?? '' }}</p>
                            <p class="mb-0">{{ Taxar ?? '' }}</p>
                        </div>
                    </div><!-- invoice-header -->

                    <!-- عنوان السند -->
                    <div class="text-center">
                        <h5 class="voucher-title">{{ __('home.voucher') }}</h5>
                    </div>

                    <!-- جدول تواريخ الفلترة ووقت التصدير -->
                    @php
                        \Carbon\Carbon::setLocale('ar');
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                    @endphp
                    <div class="table-responsive my-3">
                        <table class="table table-bordered text-center table-custom">
                            <tr>
                                <th>{{ __('report.fromdate') }}</th>
                                <td>{{ $start_at ?? '-' }}</td>
                                <th>{{ __('report.todate') }}</th>
                                <td>{{ $end_at ?? '-' }}</td>
                                <th>{{ __('home.exportTime') }}</th>
                                <td>{{ $currentdata }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- تفاصيل الفواتير / السندات -->
                    @if (isset($Invoices) && count($Invoices) > 0)
                        @php
                            $totalprice = 0;
                        @endphp

                        @foreach ($Invoices as $invoice)
                            @php
                                $totalprice += $invoice->recive_amount;
                            @endphp

                            <div class="card m-2 p-3 border shadow-sm" style="border-radius: 10px;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center mb-2">
                                        <thead>
                                            <tr>
                                                <th style="color:#419BB2">{{ __('report.invoiceNo') }}</th>
                                                <th style="color:#419BB2">{{ $invoice->sent_abd_count ?? $invoice->id }}</th>
                                                <th style="color:#419BB2">{{ __('report.reciver_name') }}</th>
                                                <th style="color:#419BB2">{{ optional($invoice->user)->name }}</th>
                                                <th style="color:#419BB2">{{ __('home.paymentmethod') }}</th>
                                                <th>-</th>
                                            </tr>
                                            <tr>
                                                <th style="color:#419BB2">{{ __('home.date') }}</th>
                                                <th>{{ __('home.exportTime') }}</th>
                                                <th>{{ __('home.acount_name') }}</th>
                                                <th>{{ __('accountes.cashreceived') }}</th>
                                                <th>{{ __('home.paymentmethod') }}</th>
                                                <th>{{ __('home.notesClient') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th style="color:#419BB2">{{ $invoice->date_export ?? $invoice->created_at->format('Y-m-d') }}</th>
                                                <td>{{ $invoice->created_at }}</td>
                                                <td>{{ optional($invoice->financial_accounts_data)->name }}</td>
                                                <td class="font-weight-bold text-success">{{ $invoice->recive_amount }}</td>
                                                <td>
                                                    @if ($invoice->pay_method == 'Cash')
                                                        <span class="text-success">{{ __('report.cash') }}</span>
                                                    @elseif ($invoice->pay_method == 'Bank_transfer')
                                                        <span class="text-success">{{ __('home.Bank_transfer') }}</span>
                                                    @else
                                                        <span class="text-warning">{{ __('report.shabka') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $invoice->note ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <!-- جدول الإجمالي النهائي -->
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered text-center table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" style="width: 10%">#</th>
                                        <th scope="col" style="width: 45%">{{ __('report.totalprice') }}</th>
                                        <th scope="col" style="width: 45%">{{ __('home.the amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td class="font-weight-bold">{{ __('home.total') }}</td>
                                        <td class="font-weight-bold text-success" style="font-size: 16px;">{{ $totalprice }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- main-content-body-invoice -->
    </div><!-- COL-END -->
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