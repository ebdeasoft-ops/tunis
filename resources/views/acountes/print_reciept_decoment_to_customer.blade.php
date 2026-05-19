@extends('layouts.master')
@section('css')
    <style>
        /* تحسينات الطباعة */
        @media print {
            #print_Button { display: none; }
            .breadcrumb-header { display: none; }
            body { border: none !important; margin: 0; padding: 0; }
            .main-content { margin-right: 0 !important; }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .invoice-box {
            background: #fff;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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

        .footernew {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding: 20px;
            border-top: 1px solid #eee;
        }

        .header-logo {
            width: 120px;
            height: auto;
        }
    </style>
@endsection

@section('title') {{__('home.voucher')}} @stop

@section('content')
    <div class="row row-sm">
        <div class="col-md-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice shadow-sm">
                    <div class="card-body">
                        <div class="invoice-header d-flex justify-content-between align-items-start">
                            <div class="billed-from text-center" style="width:30%">
                                <h4 class="text-uppercase">{{ Nameen }}</h4>
                                <p class="text-muted small">{{ describtionen }}<br>{{ STen }}<br>{{ Taxen }}</p>
                            </div>
                            
                            <div class="text-center">
                                @php $logo = camplogo; @endphp
                                <img src="{{ asset('assets/img/brand/'.$logo) }}" class="header-logo" alt="logo">
                                <h3 class="mt-3 text-dark border-bottom pb-2">{{ __('home.voucher') }}</h3>
                            </div>

                            <div class="billed-from text-center" style="width:30%">
                                <h4>{{ Namear }}</h4>
                                <p class="text-muted small">{{ describtionar }}<br>{{ STar }}<br>{{ Taxar }}</p>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('home.decoumentNo') }}</th>
                                        <th>{{ __('report.date') }}</th>
                                        <th>{{ __('home.name') }}</th>
                                        <th>{{ __('accountes.Theamountpaid') }}</th>
                                        <th>{{ __('home.paymentmethod') }}</th>
                                        <th>{{ __('home.notesClient') }}</th>
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
                                            <span class="badge badge-light p-2">{{ $item['method_pay'] }}</span>
                                        </td>
                                        <td class="text-right">{{ $item['note'] ?? '---' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="total-text-box text-center">
                            <h5 class="mb-1 text-success">{{ __('accountes.Theamountpaid') }} كتابةً:</h5>
                            <p class="mb-0" style="font-size: 1.2rem; font-weight: bold;">
                                <span>{{ $data['totatextlriyales'] }}</span> 
                                <span class="mx-1">و</span>
                                <span>{{ $data['totatextlrihalala'] }}</span>
                            </p>
                        </div>

                        <div class="footernew mt-5">
                            <div class="text-center">
                                <p class="mb-0 font-weight-bold">أمين الصندوق / البائع</p>
                                <p class="small text-muted">Salesman Signature</p>
                                <p class="mt-4">_______________________</p>
                            </div>
                            <div class="text-center">
                                <p class="mb-0 font-weight-bold">المستلم / العميل</p>
                                <p class="small text-muted">Customer Signature</p>
                                <p class="mt-4">_______________________</p>
                            </div>
                        </div>

                        <div class="text-center mt-4 no-print">
                            <hr>
                            <button class="btn btn-primary btn-lg px-5 shadow-sm" id="print_Button" onclick="printDiv()"> 
                                <i class="fas fa-print ml-2"></i> {{ __('home.print') }}
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
            location.reload(); // إعادة التحميل لإعادة ربط أحداث الـ JavaScript
        }
    </script>
@endsection