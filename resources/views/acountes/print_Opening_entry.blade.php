@extends('layouts.master')

@section('title')
{{ __('home.Opening_entry') }}
@stop

@section('css')
    <style>
        /* إخفاء زر الطباعة عند ضغط المستخدم على زر طباعة المتصفح */
        @media print {
            #print_Button {
                display: none !important;
            }
            .main-footer {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
                border: none;
            }
        }
        
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            line-height: 1.5;
        }

        .invoice-title {
            color: #419BB2;
            font-weight: bold;
        }
        
        .logo-img {
            width: 110px;
            height: 70px;
            object-fit: contain;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
    </div>
@endsection

@section('content')
    @php
        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
    @endphp

    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body" style="border: 1px solid #ccc; padding: 20px;">
                        
                       <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">
                            <div class="billed-from" style="width:33%;text-align: center;">
                                <br>
                                <span style="font-size:25px">{{Nameen}}</span>
                                <br>
                                <p dir="ltr">{{describtionen}}</p>
                                <span dir="ltr">{{STen}}</span>
                                <p dir="ltr">{{Taxen}}</p>
                            </div>
                            <div class="row">
                                <?php $logo = camplogo; ?>
                                <a href="https://ebdeasoft.com/"><img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>
                            </div>
                            <div class="billed-from" style="width:33%;text-align: center;">
                                <br>
                                <span style="font-size:25px">{{Namear}}</span>
                                <br>
                                <p>{{describtionar}}</p>
                                <p>{{STar}}</p>
                                <p>{{Taxar}}</p>
                            </div>
                        </div>


                        <hr>
                        <center>
                            <h3 class="my-4" style="text-decoration: underline;">{{ __('home.Opening_entry') }}</h3>
                        </center>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center my-2">
                                <thead>
                                    <tr>
                                        <th style="background-color: #f2f2f2; color: #419BB2;">{{ __('home.decoumentNo') }}</th>
                                        <th>{{ $dely_record ?? '-' }}</th>
                                        <th style="background-color: #f2f2f2; color: #419BB2;">{{ __('home.date') }}</th>
                                        <th>{{ $date ?? '-' }}</th>  
                                        <th style="background-color: #f2f2f2; color: #419BB2;">{{ __('home.notesClient') }}</th>
                                        <th>{{ $general_note ?? '-' }}</th>
                                        <th style="background-color: #f2f2f2; color: #419BB2;">{{ __('home.exportTime') }}</th>
                                        <th>{{ $currentdata }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-bordered text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('home.name') }}</th>
                                        <th>{{ __('home.debit') }} (مدين)</th>
                                        <th>{{ __('home.credit') }} (دائن)</th>
                                        <th>{{ __('home.notesClient') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_credit = 0;
                                        $total_debit = 0;
                                    @endphp
                                    @foreach($data as $item)
                                        @php
                                            $total_credit += $item['credit'];
                                            $total_debit += $item['depit'];
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-danger font-weight-bold">{{ number_format($item['depit'], 2) }}</td>
                                            <td class="text-danger font-weight-bold">{{ number_format($item['credit'], 2) }}</td>
                                            <td>{{ $item['note'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td colspan="2">{{ __('home.total') }}</td>
                                        <td class="text-danger">{{ number_format($total_debit, 2) }}</td>
                                        <td class="text-danger">{{ number_format($total_credit, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row mt-5">
                            <div class="col-6">
                                <p><strong>{{ __('home.employeereciver') }}:</strong> {{ Auth::user()->name }}</p>
                            </div>
                            <div class="col-6 text-right">
                                <p><strong>{{ __('home.thesignature') }}:</strong> ............................</p>
                            </div>
                        </div>

                        <hr class="mg-b-40">

                        <button class="btn btn-primary float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                            <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                        </button>
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
            
            // استبدال محتوى الصفحة بمحتوى الطباعة فقط
            document.body.innerHTML = printContents;
            
            // تنفيذ أمر الطباعة
            window.print();
            
            // إعادة المحتوى الأصلي بعد إغلاق نافذة الطباعة
            document.body.innerHTML = originalContents;
            
            // إعادة تحميل الصفحة لضمان عمل السكريبتات الأخرى
            window.location.reload();
        }

        // إخفاء التنبيهات تلقائياً
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
        });
    </script>
@endsection