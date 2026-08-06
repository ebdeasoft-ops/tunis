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
            font: 13pt Georgia, "Times New Roman", Times, serif;
            -webkit-print-color-adjust: exact;
        }
        .table thead th {
            background-color: #419BB2 !important;
            color: #fff !important;
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

    .report-title-box {
        background: linear-gradient(135deg, #419BB2 0%, #296878 100%);
        color: #fff;
        padding: 10px 30px;
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
        max-width: 250px;
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
                    <!-- ترويسة الشركة / الفاتورة -->
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
                    </div>

                    <!-- عنوان التقرير -->
                    <div class="text-center my-4">
                        <span class="report-title-box">
                            {{ __('home.updatestockquentity') }}
                        </span>
                    </div>

                    <!-- معلومات وقت التصدير -->
                    <div class="row align-items-center mb-4 mx-0 info-pill">
                        <div class="col-md-12 text-center">
                            <span class="text-secondary font-weight-bold">
                                <i class="far fa-clock text-info ml-1"></i> {{ __('home.exportTime') }} : 
                            </span>
                            <span class="text-dark font-weight-bold">
                                {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                            </span>
                        </div>
                    </div>

                    <!-- الجدول -->
                    <div class="table-responsive">
                        <table class="table text-md-nowrap mb-0 table-hover text-center align-middle" style="border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0;">
                            <thead>
                                <tr>
                                    <th class="py-3">#</th>
                                    <th class="py-3">{{ __('report.date') }}</th>
                                    <th class="py-3">{{ __('home.employee') }}</th>
                                    <th class="py-3">{{ __('home.productNo') }}</th>
                                    <th class="py-3">{{ __('home.productname') }}</th>
                                    <th class="py-3">{{ __('home.notesClient') }}</th>
                                    <th class="py-3">{{ __('home.productdecrease') }}</th>
                                    <th class="py-3">{{ __('home.productincrease') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($stock_update as $operation)
                                    <?php $i++; ?>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="font-weight-bold text-muted">{{ $i }}</td>
                                        <td class="text-muted" style="font-size: 13px;">{{ $operation->created_at }}</td>
                                        <td>
                                            <span class="badge px-3 py-2 text-white" style="background-color: #419BB2; border-radius: 6px; font-size: 12px;">
                                                {{ $operation->user->name }}
                                            </span>
                                        </td>
                                        <td dir="ltr" class="font-weight-bold text-dark">{{ $operation->productData->Product_Code }}</td>
                                        <td class="font-weight-bold text-dark">{{ $operation->productData->product_name }}</td>
                                        <td class="text-muted">{{ $operation->note }}</td>
                                        <td class="font-weight-bold text-danger">
                                            {{ $operation->productdecrease == 0 ? '-' : $operation->productdecrease }}
                                        </td>
                                        <td class="font-weight-bold text-success">
                                            {{ $operation->productincrease == 0 ? '-' : $operation->productincrease }}
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