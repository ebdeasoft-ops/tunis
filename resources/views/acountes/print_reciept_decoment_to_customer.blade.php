@extends('layouts.master')
@section('css')
    <style>
        /* تحسينات الطباعة */
        @media print {
            #print_Button { display: none; }
            .breadcrumb-header { display: none; }
            body { border: none !important; margin: 0; padding: 0; background: #fff; }
            .main-content { margin-right: 0 !important; }
            .card-invoice { box-shadow: none !important; border: none !important; }
            
            /* ضمان طباعة الألوان الشفافة للأختام */
            .stamp-container svg {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f6f9;
        }

        .invoice-box {
            background: #fff;
            padding: 30px;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        #example {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #example thead {
            background: #343a40 !important;
            color: #fff !important;
        }

        #example th, #example td {
            padding: 12px;
            border: 1px solid #dee2e6 !important;
            text-align: center;
        }

        .total-text-box {
            background-color: #f8f9fa;
            border: 2px dashed #28a745;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        /* تنسيق منطقة التوقيع والأختام الجديدة */
        .footernew {
            display: flex;
            justify-content: space-around;
            margin-top: 80px;
            padding: 20px;
            position: relative;
        }

        .signature-section {
            width: 40%;
            text-align: center;
            position: relative;
            min-height: 120px;
        }

        .signature-line {
            margin-top: 10px;
            border-top: 1px solid #333;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .header-logo {
            width: 120px;
            height: auto;
        }

        /* تنسيق حاويات الأختام الـ SVG */
        .stamp-container {
            position: absolute;
            opacity: 0.85;
            z-index: 10;
        }

        .stamp-gulf {
            top: 80px;
            left: 10%;
            transform: rotate(-10deg);
        }

        .stamp-tunisia {
            top: 50px;
            right: 10%;
            transform: rotate(15deg);
        }

        .signature-container {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
            opacity: 0.9;
        }

        /* تنسيق النصوص المزدوجة (عربي وتحته إنجليزي) */
        .dual-lang {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .dual-lang .en-text {
            font-size: 0.85em;
            color: #6c757d;
            font-weight: normal;
        }
    </style>
@endsection

@section('title') {{ __('home.voucher') }} @stop

@section('content')
    <div class="row row-sm">
        <div class="col-md-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice shadow-sm">
                    <div class="card-body invoice-box">
                        <div class="invoice-header d-flex justify-content-between align-items-start">
                            <!-- الجهة اليسرى -->
                            <div class="billed-from text-center" style="width:30%">
                                <h4 class="text-uppercase">{{ Nameen }}</h4>
                                <p class="text-muted small">{{ describtionen }}<br>{{ STen }}<br>{{ Taxen }}</p>
                            </div>
                            
                            <!-- الشعار وعنوان سند القبض -->
                            <div class="text-center">
                                @php $logo = camplogo; @endphp
                                <img src="{{ asset('assets/img/brand/'.$logo) }}" class="header-logo" alt="logo">
                                <h3 class="mt-3 text-dark border-bottom pb-2">
                                    <div class="dual-lang">
                                        <span>سند قبض</span>
                                        <span class="en-text">Cash Receipt Voucher</span>
                                    </div>
                                </h3>
                            </div>

                            <!-- الجهة اليمنى -->
                            <div class="billed-from text-center" style="width:30%">
                                <h4>{{ Namear }}</h4>
                                <p class="text-muted small">{{ describtionar }}<br>{{ STar }}<br>{{ Taxar }}</p>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="dual-lang">
                                                <span>رقم السند</span>
                                                <span class="en-text">Document No</span>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dual-lang">
                                                <span>التاريخ</span>
                                                <span class="en-text">Date</span>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dual-lang">
                                                <span>الاسم</span>
                                                <span class="en-text">Name</span>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dual-lang">
                                                <span>المبلغ المدفوع</span>
                                                <span class="en-text">Amount Paid</span>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dual-lang">
                                                <span>طريقة الدفع</span>
                                                <span class="en-text">Payment Method</span>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dual-lang">
                                                <span>ملاحظات</span>
                                                <span class="en-text">Notes</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['transaction'] as $item)
                                    <tr>
                                        <td class="font-weight-bold text-primary">{{ $item['sent_abd_count'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['date_export'])->format('Y-m-d') }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td class="font-weight-bold">{{ number_format($item['paid_amount'], 2) }}</td>
                                        <td>
                                            @php 
                                                $method = strtolower($item['method_pay']); 
                                            @endphp

                                            @if($method == 'cash')
                                                <span class="badge badge-success p-2" style="min-width: 90px; font-size: 14px;">
                                                    <i class="fas fa-money-bill-wave ml-1"></i> 
                                                    <div class="dual-lang" style="display:inline-flex; flex-direction:column; vertical-align:middle;">
                                                        <span>نقدي</span>
                                                        <span style="font-size:10px; color:#fff;">Cash</span>
                                                    </div>
                                                </span>
                                            @elseif($method == 'bank')
                                                <span class="badge badge-primary p-2" style="min-width: 90px; font-size: 14px;">
                                                    <i class="fas fa-university ml-1"></i> 
                                                    <div class="dual-lang" style="display:inline-flex; flex-direction:column; vertical-align:middle;">
                                                        <span>البنك</span>
                                                        <span style="font-size:10px; color:#fff;">Bank</span>
                                                    </div>
                                                </span>
                                            @else
                                                <span class="badge badge-light p-2">{{ $item['method_pay'] }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $item['note'] ?? '---' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
<div class="total-text-box text-center">
    <h5 class="mb-1 text-success">
        <div class="dual-lang">
            <span>المبلغ المدفوع كتابةً:</span>
            <span class="en-text" style="color: #28a745;">Amount Paid in Words:</span>
        </div>
    </h5>
    <p class="mb-0" style="font-size: 1.1rem; font-weight: bold;">
        <div class="dual-lang">
            <div>
                <span>{{ $data['totatextlriyales'] }}</span> 
                <span class="mx-1">و</span>
                <span>{{ $data['totatextlrihalala'] }}</span>
            </div>
            <div class="en-text" style="font-size: 1rem; color: #333;">
                <span>{{ $data['totatext_en_riyales'] }}</span> 
                <span class="mx-1">and</span>
                <span>{{ $data['totatext_en_halala'] }}</span>
            </div>
        </div>
    </p>
</div>
                        <div class="footernew mt-5">
                            <div class="signature-section">
                            

                                <div class="signature-container">
                                    <svg width="100" height="60" viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M 10,40 C 20,10 40,20 50,30 S 70,50 90,20 M 30,35 Q 35,45 40,35 T 50,35 M 15,15 L 25,55" stroke="#111" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                                    </svg>
                                </div>

                                <p class="mb-0 font-weight-bold mt-2">
                                    <div class="dual-lang">
                                        <span>أمين الصندوق / البائع</span>
                                        <span class="en-text">Cashier / Salesman</span>
                                    </div>
                                </p>
                                <div class="signature-line"></div>
                            </div>

                            <div class="signature-section">
                                <div class="stamp-container stamp-tunisia"></div>

                                <p class="mb-0 font-weight-bold mt-2">
                                    <div class="dual-lang">
                                        <span>المستلم / العميل</span>
                                        <span class="en-text">Receiver / Customer</span>
                                    </div>
                                </p>
                                <div class="signature-line"></div>
                            </div>
                        </div>

                        <div class="text-center mt-4 no-print">
                            <hr>
                            <button class="btn btn-primary btn-lg px-5 shadow-sm" id="print_Button" onclick="printDiv()"> 
                                <i class="fas fa-print ml-2"></i> 
                                <div class="dual-lang" style="display:inline-flex; flex-direction:column; vertical-align:middle;">
                                    <span>طباعة</span>
                                    <span style="font-size:10px; color:#fff;">Print</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
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