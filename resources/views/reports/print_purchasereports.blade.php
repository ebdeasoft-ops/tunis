@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button {
                display: none !important;
            }
            body {
                background: #fff !important;
                -webkit-print-color-adjust: exact;
                direction: rtl;
            }
            .invoice-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .table-custom th {
                background-color: #343a40 !important;
                color: #fff !important;
            }
        }

        body {
            font-family: 'Cairo', 'Tahoma', Georgia, sans-serif;
            color: #2c3e50;
            background-color: #f4f6f9;
        }

        .invoice-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 35px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .invoice-header-custom {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }

        .company-info h4 {
            font-weight: 700;
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 6px;
        }

        .company-info p, .company-info span {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .report-title {
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 12px;
            border-radius: 8px;
            font-weight: 700;
            color: #2c3e50;
            font-size: 17px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
        }

        .table-custom th {
            background-color: #2c3e50 !important;
            color: #fff !important;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
            padding: 12px 8px;
            border: 1px solid #34495e;
        }

        .table-custom td {
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
            padding: 10px 8px;
            color: #2c3e50;
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-custom tfoot tr {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
@endsection

@section('title')
    {{ __('home.print') }} - {{ __('home.purchasereports') }}
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="container">
                    
                    <!-- زر الطباعة -->
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-danger px-4 shadow-sm font-weight-bold" id="print_Button" onclick="printDiv()">
                            <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                        </button>
                    </div>

                    <div class="invoice-card">
                        <!-- ترويسة الفاتورة / الشركة (عربي / شعار / إنجليزي) -->
                        <div class="row invoice-header-custom align-items-center">
                            
                            <!-- الجهة اليمنى (العربية) -->
                            <div class="col-4 text-center company-info">
                                <h4>{{ Namear ?? '' }}</h4>
                                <p>{{ describtionar ?? '' }}</p>
                                <p>{{ STar ?? '' }}</p>
                                <p>{{ Taxar ?? '' }}</p>
                            </div>

                            <!-- الشعار في المنتصف -->
                            <div class="col-4 text-center">
                                <?php $logo = camplogo ?? ''; ?>
                                <a href="https://ebdeasoft.com/">
                                    <img src="{{ asset('assets/img/brand') . '/' . $logo }}" class="logo-1 img-fluid" alt="logo" style="max-height: 70px; object-fit: contain;">
                                </a>
                            </div>

                            <!-- الجهة اليسرى (الإنجليزية) -->
                            <div class="col-4 text-center company-info">
                                <h4>{{ Nameen ?? '' }}</h4>
                                <p dir="ltr">{{ describtionen ?? '' }}</p>
                                <span dir="ltr">{{ STen ?? '' }}</span>
                                <p dir="ltr">{{ Taxen ?? '' }}</p>
                            </div>

                        </div>

                        <!-- عنوان التقرير -->
                        <div class="report-title">
                            <i class="fas fa-file-invoice-dollar ml-2"></i> {{ __('home.purchasereports') }}
                        </div>

                        <!-- جدول البيانات -->
                        @if (isset($products) && count($products) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-custom">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('report.invoiceNo') }}</th>
                                            <th>{{ __('report.date') }}</th>
                                            <th>{{ __('home.suppliername') }}</th>
                                            <th dir="ltr">{{ __('home.productNo') }}</th>
                                            <th>{{ __('home.product') }}</th>
                                            <th>{{ __('home.quantity') }}</th>
                                            <th>{{ __('home.purchase') }}</th>
                                            <th>{{ __('home.addedValue') }}</th>
                                            <th>{{ __('home.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 0;
                                        $grandTotal = 0;
                                        ?>
                                        @foreach ($products as $invoice)
                                            <?php 
                                            $i++;
                                            $rowTotal = ($invoice->purchasingـprice * $invoice->numberofpice) + ($invoice->Added_Value * $invoice->numberofpice);
                                            $grandTotal += $rowTotal;
                                            
                                            $supplierName = App\Models\orderTosupllier::find($invoice->order_owner);
                                            ?>
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $invoice->order_owner }}</td>
                                                <td>{{ $invoice->created_at }}</td>
                                                <td>{{ optional(optional($supplierName)->supllier)->name ?? '---' }}</td>
                                                <td dir="ltr">{{ optional($invoice->productData)->Product_Code }}</td>
                                                <td>{{ optional($invoice->productData)->product_name }}</td>
                                                <td>{{ $invoice->numberofpice }}</td>
                                                <td>{{ number_format($invoice->purchasingـprice, 2) }}</td>
                                                <td>{{ number_format($invoice->Added_Value, 2) }}</td>
                                                <td class="font-weight-bold text-success">{{ number_format($rowTotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="9" class="text-left px-4 font-weight-bold" style="font-size: 14px;">الإجمالي العام (Grand Total):</td>
                                            <td class="text-center text-primary font-weight-bold" style="font-size: 15px;">{{ number_format($grandTotal, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning text-center p-4">
                                لا توجد بيانات لعرضها في هذا التقرير حالياً.
                            </div>
                        @endif

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