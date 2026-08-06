@extends('layouts.master')

@section('css')
<style>
    @media print {
        #print_Button, #excel_Button {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background: #fff !important;
        }
    }

    body {
        font-family: 'Cairo', 'Times New Roman', Times, serif;
        color: #333;
    }

    .print-container {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
    }

    .invoice-header-box {
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .print-table th {
        background-color: #1e293b !important;
        color: #fff !important;
        text-align: center;
        padding: 14px !important;
        font-size: 15px;
    }

    .print-table td {
        text-align: center;
        padding: 14px !important;
        vertical-align: middle !important;
        font-size: 14.5px;
    }

    .info-badge {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: bold;
        color: #419BB2;
    }
</style>
@endsection

@section('title')
{{ __('home.print') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('report.Reports') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.print') }}</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="card shadow-sm border-0" id="print">
            <div class="card-body print-container">
                
                <!-- أزرار التحكم (طباعة وتصدير إكسيل) العلويّة -->
                <div class="d-flex justify-content-end mb-4" style="gap: 10px;">
                    <button type="button" class="btn btn-success px-4 py-2" id="excel_Button" onclick="exportTableToExcel('bestSellingTable', 'Best_Selling_Products_Report')" style="border-radius: 10px; font-weight: 600;">
                        {{ __('home.excel') ?? 'تصدير Excel' }} <i class="fas fa-file-excel ml-1"></i>
                    </button>
                    <button type="button" class="btn btn-danger px-4 py-2" id="print_Button" onclick="printDiv()" style="border-radius: 10px; font-weight: 600;">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <!-- الهيدر والبيانات واللوجو -->
                <div class="invoice-header-box d-flex justify-content-between align-items-center flex-wrap">
                    <div class="billed-from text-left" style="width:33%;">
                        <span style="font-size:22px; font-weight:bold;">{{ Nameen ?? '' }}</span>
                        <p class="mb-1 text-muted" dir="ltr">{{ describtionen ?? '' }}</p>
                        <span class="d-block text-muted" dir="ltr">{{ STen ?? '' }}</span>
                        <p class="mb-0 text-muted" dir="ltr">{{ Taxen ?? '' }}</p>
                    </div>

                    <div class="text-center my-2" style="width:33%;">
                        @php
                            $logo = camplogo ?? 'default.png';
                        @endphp
                        <a href="https://ebdeasoft.com/">
                            <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1" alt="logo" style="max-width: 120px; height: auto;">
                        </a>
                    </div>

                    <div class="billed-from text-right" style="width:33%;">
                        <span style="font-size:22px; font-weight:bold;">{{ Namear ?? '' }}</span>
                        <p class="mb-1 text-muted">{{ describtionar ?? '' }}</p>
                        <p class="mb-1 text-muted">{{ STar ?? '' }}</p>
                        <p class="mb-0 text-muted">{{ Taxar ?? '' }}</p>
                    </div>
                </div>

                <!-- عنوان التقرير (المنتجات الأكثر مبيعاً) بخط كبير بالعربي والإنجليزي -->
                <div class="text-center my-4">
                    <h3 class="font-weight-bold text-dark" style="font-size: 26px;">{{ __('report.Best selling products') }}</h3>
                    <p class="text-muted font-weight-bold" style="font-size: 16px;">Best Selling Products Report</p>
                </div>

                <!-- تفاصيل التقرير (من تاريخ / إلى تاريخ / وقت التصدير) -->
                @if (isset($bestselling))
                    <div class="d-flex justify-content-around align-items-center flex-wrap mb-4 p-3 info-badge">
                        <div>
                            <span>{{ __('report.fromdate') }} : </span>
                            <span class="text-dark">{{ $date[0] ?? '' }}</span>
                        </div>
                        <div>
                            <span>{{ __('report.todate') }} : </span>
                            <span class="text-dark">{{ $date[1] ?? '' }}</span>
                        </div>
                        <div>
                            <span>{{ __('home.exportTime') }} : </span>
                            <span class="text-dark">
                                {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                            </span>
                        </div>
                    </div>

                    <!-- جدول المنتجات الأكثر مبيعاً (مع إضافة id لتصدير الإكسيل) -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered print-table mb-0" id="bestSellingTable" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                    <th class="border-bottom-0">{{ __('home.productname') }}</th>
                                    <th class="border-bottom-0">{{ __('users.branch') }}</th>
                                    <th class="border-bottom-0">{{ __('report.Number of pieces sold') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bestselling as $product)
                                    <tr>
                                        <td dir="ltr" class="font-weight-bold text-muted" style="font-size: 15px;">{{ $product['productcode'] }}</td>
                                        <td class="font-weight-bold text-dark" style="font-size: 16px;">{{ $product['productname'] }}</td>
                                        <td style="font-size: 15px;">{{ $product['branch'] }}</td>
                                        <td><span class="badge badge-success px-3 py-2" style="font-size: 14px;">{{ $product['numberofsall'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- أزرار التحكم السفليّة -->
                <div class="d-flex justify-content-center mt-4 pt-3 border-top" style="gap: 15px;">
                    <button type="button" class="btn btn-success px-5 py-2" id="excel_Button" onclick="exportTableToExcel('bestSellingTable', 'Best_Selling_Products_Report')" style="border-radius: 10px; font-weight: 600;">
                        {{ __('home.excel') ?? 'تصدير Excel' }} <i class="fas fa-file-excel ml-1"></i>
                    </button>
                    <button type="button" class="btn btn-danger px-5 py-2" id="print_Button" onclick="printDiv()" style="border-radius: 10px; font-weight: 600;">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
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

    // دالة تصدير الجدول إلى ملف Excel مباشرة من المتصفح
    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel;charset=utf-8;';
        var tableSelect = document.getElementById(tableID);
        
        // التأكد من دعم ترميز اللغة العربية بشكل صحيح
        var tableHTML = "\uFEFF" + tableSelect.outerHTML;
        
        filename = filename ?filename+'.xls' : 'excel_data.xls';
        
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(tableHTML);
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>
@endsection