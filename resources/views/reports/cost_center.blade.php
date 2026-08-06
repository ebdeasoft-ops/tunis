@extends('layouts.master')

@section('css')
<style>
    /* تنسيقات عامة لصفحة معاينة الفاتورة/التقرير */
    .invoice-card {
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        background-color: #fff;
    }
    
    .invoice-header-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .company-info h4 {
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }

    .report-filter-table th {
        background-color: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        vertical-align: middle;
    }

    .main-table th {
        background-color: #419BB2 !important;
        color: #fff !important;
        text-align: center;
        vertical-align: middle;
        font-size: 13px;
    }

    .main-table td {
        vertical-align: middle;
        text-align: center;
        font-size: 13px;
    }

    /* تنسيق خاص للطباعة */
    @media print {
        #print_Button, .breadcrumb-header, .main-parent, footer, header {
            display: none !important;
        }
        .card-invoice {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        body {
            background-color: #fff !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection

@section('title')
    {{ __('home.cost_center') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between parent-heading">
    <div class="my-auto">
        <div class="d-flex">
            <h5 class="content-title mb-0 my-auto text-white">معاينة طباعة التقرير</h5>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card invoice-card p-4">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary px-4 py-2 shadow-sm" id="print_Button" onclick="printDiv()">
                        <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                    </button>
                </div>

                <div class="card-body pt-2">
                    
                    <!-- رأس الفاتورة / الشركة -->
                    <div class="invoice-header-box">
                        <!-- الجهة اليسرى (الإنجليزية مثلاً) -->
                        <div class="company-info text-left" style="width: 33%;">
                            <h4>{{ Nameen ?? '' }}</h4>
                            <p class="text-muted mb-1" dir="ltr">{{ describtionen ?? '' }}</p>
                            <span class="d-block text-muted" dir="ltr">{{ STen ?? '' }}</span>
                            <small class="text-muted" dir="ltr">{{ Taxen ?? '' }}</small>
                        </div>

                        <!-- الشعار في المنتصف -->
                        <div class="text-center" style="width: 33%;">
                            @php
                                $logo = camplogo ?? 'default.png';
                            @endphp
                            <a href="https://ebdeasoft.com/" target="_blank">
                                <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1 img-fluid" alt="logo" style="max-height: 70px;">
                            </a>
                        </div>

                        <!-- الجهة اليمنى (العربية) -->
                        <div class="company-info text-right" style="width: 33%;">
                            <h4>{{ Namear ?? '' }}</h4>
                            <p class="text-muted mb-1">{{ describtionar ?? '' }}</p>
                            <span class="d-block text-muted">{{ STar ?? '' }}</span>
                            <small class="text-muted">{{ Taxar ?? '' }}</small>
                        </div>
                    </div>

                    <!-- عنوان التقرير -->
                    <div class="text-center my-4">
                        <h4 class="font-weight-bold text-dark">{{ __('home.cost_center') }}</h4>
                    </div>

                    @php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                        
                        if($cost_center == '-'){
                            $name_cost = '-';
                        } else {
                            $data_cost = App\Models\Cost_centers::find($cost_center);
                            $name_cost = App::getLocale() == 'ar' ? ($data_cost->cost_center_ar ?? '-') : ($data_cost->cost_center_en ?? '-');
                        }
                    @endphp

                    <!-- جدول الفلاتر والمعلومات العليا -->
                    <div class="table-responsive mb-4">
                        <table class="table report-filter-table text-center rounded">
                            <tr>
                                <th><span class="text-primary font-weight-bold">{{ __('report.fromdate') }}:</span></th>
                                <td>{{ $start }}</td>
                                <th><span class="text-primary font-weight-bold">{{ __('report.todate') }}:</span></th>
                                <td>{{ $end }}</td>
                                <th><span class="text-primary font-weight-bold">{{ __('home.cost_center') }}:</span></th>
                                <td>{{ $name_cost }}</td>
                                <th><span class="text-primary font-weight-bold">{{ __('home.exportTime') }}:</span></th>
                                <td>{{ $currentdata }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- جدول البيانات الأساسي -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped main-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('home.decoumentNo') }}</th>
                                    <th>{{ __('home.exportTime') }}</th>
                                    <th>{{ __('report.date') }}</th>
                                    <th>{{ __('users.branch') }}</th>
                                    <th>{{ __('home.cost_center') }}</th>
                                    <th>{{ __('home.expenseresonen') }}</th>
                                    <th>{{ __('home.employee') }}</th>
                                    <th>{{ __('accountes.Theamountpaid') }}</th>
                                    <th>{{ __('home.credit') }}</th>
                                    <th>{{ __('home.debit') }}</th>
                                    <th>{{ __('home.notesClient') }}</th>
                                </tr>
                            </thead>
                            
                            @php
                                $i = 1;
                                $total_credit = 0;
                                $total_debit = 0;
                                $end_blance = 0;
                            @endphp

                            <tbody>
                                @foreach ($data as $item)
                                    @php
                                        $total_credit += $item->creditor;
                                        $total_debit += $item->debtor;
                                        $end_blance += $item->recive_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $item->sent_serf_count }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->date_export }}</td>
                                        <td>{{ $item->branch->name ?? '-' }}</td>
                                        <td>{{ App::getLocale() == 'ar' ? ($item->cost_center_data->cost_center_ar ?? '-') : ($item->cost_center_data->cost_center_en ?? '-') }}</td>
                                        <td>{{ $item->financial_accounts_data->name ?? '-' }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>{{ $item->recive_amount }}</td>
                                        <td>{{ round($item->debtor, 2) }}</td>
                                        <td>{{ round($item->creditor, 2) }}</td>
                                        <td>{{ $item->note ?? '-' }}</td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            </tbody>

                            <!-- صف الإجماليات -->
                            <tfoot>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="8" class="text-right">الإجمالي (Total):</td>
                                    <td>{{ $end_blance }}</td>
                                    <td>{{ round($total_debit, 2) }}</td>
                                    <td>{{ round($total_credit, 2) }}</td>
                                    <td>-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

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