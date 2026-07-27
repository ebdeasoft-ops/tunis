@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button {
                display: none !important;
            }
            .main-sidebar, .main-header, .breadcrumb-header {
                display: none !important;
            }
            body {
                background: #fff !important;
                color: #000 !important;
            }
            .card-invoice {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }

        body {
            font-family: 'Cairo', 'Tajawal', Georgia, serif;
            color: #333;
            background-color: #f8f9fa;
        }

        .card-invoice {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 20px;
            border-top: 4px solid #dc3545;
        }

        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .company-info h4 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .company-info p, .company-info span {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .document-title {
            background: #fff5f5;
            color: #9b1c1c;
            padding: 8px 20px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 6px;
            display: inline-block;
            border: 1px solid #f8b4b4;
            text-align: center;
        }

        /* ضبط الجدول ليكون باتجاه صحيح */
        .table {
            direction: rtl;
        }

        .table thead th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
            border: none;
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
            font-size: 13px;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-weight: 600;
        }
    </style>
@endsection

@section('title')
    {{ __('home.Payment voucher') }} - سند صرف
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Payment voucher') }} / سند صرف</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    
                    <!-- رأس سند الصرف -->
                    <div class="invoice-header d-flex justify-content-between align-items-center w-100">
                        <!-- بيانات الشركة عربي (اليمين) -->
                        <div class="company-info text-right" style="width: 33%;">
                            <h4 style="font-size: 18px;">{{ Namear }}</h4>
                            <p>{{ describtionar }}</p>
                            <p>{{ STar }}</p>
                            <p>{{ Taxar }}</p>
                        </div>

                        <!-- الشعار والعنوان بالمنتصف -->
                        <div class="text-center" style="width: 33%;">
                            @php
                                $logo = camplogo;
                            @endphp
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="max-height: 70px; object-fit: contain;">
                            </a>
                            <div class="mt-3">
                                <span class="document-title">
                                    سند صرف<br>
                                    <span style="font-size: 12px; color: #666;">Payment Voucher</span>
                                </span>
                            </div>
                        </div>

                        <!-- بيانات الشركة إنجليزي (اليسار) -->
                        <div class="company-info text-left" style="width: 33%;">
                            <h4 style="font-size: 18px;">{{ Nameen }}</h4>
                            <p dir="ltr">{{ describtionen }}</p>
                            <span dir="ltr">{{ STen }}</span>
                            <p dir="ltr">{{ Taxen }}</p>
                        </div>
                    </div>

                    <!-- جدول البيانات مرتب بالترتيب الصحيح (من اليمين لليسار) -->
                    <div class="card-body p-0">
                        <div class="table-responsive mt-4">
                            <table class="table text-md-nowrap table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">{{ __('home.decoumentNo') }}<br><small>Doc No.</small></th>
                                        <th class="border-bottom-0">{{ __('home.exportTime') }}<br><small>Export Time</small></th>
                                        <th class="border-bottom-0">{{ __('report.date') }}<br><small>Date</small></th>
                                        <th class="border-bottom-0">{{ __('home.name') }}<br><small>Name</small></th>
                                        <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }}<br><small>Paid Amount</small></th>
                                        <th class="border-bottom-0">{{ __('home.paymentmethod') }}<br><small>Payment Method</small></th>
                                        <th class="border-bottom-0">{{ __('home.date') }}<br><small>Voucher Date</small></th>
                                        <th class="border-bottom-0">{{ __('home.notesClient') }}<br><small>Notes</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['transaction'] as $item)
                                        @php
                                            $pay = match ($item['method_pay'] ?? '') {
                                                'Cash'          => __('report.cash') . ' / Cash',
                                                'Shabka'        => __('report.shabka') . ' / Network',
                                                'Credit'        => __('report.credit') . ' / Credit',
                                                'Bank_transfer' => __('home.Bank_transfer') . ' / Bank Transfer',
                                                default         => __('home.Partition of the amount') . ' / Multiple',
                                            };
                                        @endphp
                                        <tr>
                                            <td><span class="badge badge-light px-2 py-1">{{ $item['sent_serf_count'] }}</span></td>
                                            <td>{{ $item['created_at'] }}</td>
                                            <td>{{ $item['date_export'] }}</td>
                                            <td class="font-weight-bold text-dark">{{ $item['name'] }}</td>
                                            <td class="font-weight-bold text-danger">{{ number_format((float)$item['paid_amount'], 2) }}</td>
                                            <td><span class="badge badge-warning">{{ $pay }}</span></td>
                                            <td>{{ $item['date'] }}</td>
                                            <td>{{ $item['note'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- التوقيع ومسؤول الصرف -->
                        <div class="signature-section">
                            <div>
                                <p class="mb-1">المسؤول / Receiver : <span class="text-dark font-weight-bold">{{ Auth()->user()->name }}</span></p>
                            </div>
                            <div class="text-left" style="min-width: 200px;">
                                <p class="mb-4">التوقيع / Signature : ....................................</p>
                            </div>
                        </div>

                        <!-- زر الطباعة -->
                        <div class="text-left mt-4">
                            <button class="btn btn-danger print-style px-4 py-2" id="print_Button" onclick="printDiv()">
                                <i class="mdi mdi-printer ml-1"></i> طباعة / Print
                            </button>
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
    <script>
        $(document).ready(function() {
            var timeout = 4000;
            $('.alert').delay(timeout).fadeOut(500);
        });
    </script>
@endsection