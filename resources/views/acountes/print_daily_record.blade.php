@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button { display: none !important; }
            body { border: none !important; -webkit-print-color-adjust: exact; }
        }
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        .table-bordered th, .table-bordered td { border: 1px solid #444 !important; vertical-align: middle; }
        .record-info-box {
            border: 1px solid #419BB2;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fcfcfc;
        }
        .info-label { font-weight: bold; color: #419BB2; }
    </style>
@endsection

@section('title')
    {{__('home.Daily_record')}}
@stop

@section('content')
    <?php
        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
    ?>

    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                        
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

                        <hr style="border-top: 2px solid #419BB2;">

                        <center>
                            <h3 class="mb-4" style="text-decoration: underline;">{{__('home.Daily_record')}} (سند قيد يومي)</h3>
                        </center>

                        <div class="record-info-box">
                            <div class="row text-right">
                                <div class="col-4">
                                    <span class="info-label">{{ __('home.decoumentNo') }}: </span> 
                                    <span style="font-size: 18px; font-weight: bold;">#{{ $dely_record }}</span>
                                </div>
                                <div class="col-4 text-center">
                                    <span class="info-label">{{ __('home.date') }}: </span> 
                                    <span>{{ $date }}</span>
                                </div>
                                <div class="col-4 text-left">
                                    <span class="info-label">{{ __('home.exportTime') }}: </span> 
                                    <span style="font-size: 11px;">{{ $currentdata }}</span>
                                </div>
                            </div>
                            <div class="row mt-3 text-right">
                                <div class="col-12">
                                    <span class="info-label">{{ __('home.notesClient') ?? 'البيان العام' }}: </span>
                                    <span style="border-bottom: 1px dotted #000; display: inline-block; width: 85%;">
                                        {{ $main_note ?? '---' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-striped">
                                <thead style="background-color: #ecf0fa;">
                                    <tr>
                                        <th style="width: 10%">رقم الحساب</th>
                                        <th style="width: 35%">{{__('home.name')}} (اسم الحساب)</th>
                                        <th style="width: 15%">{{__('home.debit')}} (مدين)</th>
                                        <th style="width: 15%">{{__('home.credit')}} (دائن)</th>
                                        <th style="width: 25%">ملاحظات السطر</th>
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
                                            <td>{{ $item['id'] }}</td>
                                            <td class="text-right">{{ $item['name'] }}</td>
                                            <td style="font-weight: bold;">{{ number_format($item['depit'], 2) }}</td>
                                            <td style="font-weight: bold;">{{ number_format($item['credit'], 2) }}</td>
                                            <td style="font-size: 11px;">{{ $item['note'] ?? '-' }}</td>
                                        </tr>   
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color: #eee; font-weight: bold;">
                                    <tr>  
                                        <td colspan="2">{{ __('home.total') }}</td>
                                        <td style="color:red">{{ number_format($total_debit, 2) }}</td>
                                        <td style="color:green">{{ number_format($total_credit, 2) }}</td>
                                        <td>
                                            @if($total_debit == $total_credit)
                                                <span class="badge badge-success">القيد متزن</span>
                                            @else
                                                <span class="badge badge-danger">غير متزن (الفرق: {{ number_format(abs($total_debit - $total_credit), 2) }})</span>
                                            @endif
                                        </td>
                                    </tr> 
                                </tfoot>
                            </table>
                        </div>

                        <div class="row mt-5 text-center">
                            <div class="col-4">
                                <p style="border-top: 1px solid #000; padding-top: 5px;">المحاسب</p>
                                <p>{{ Auth()->user()->name }}</p>
                            </div>
                            <div class="col-4">
                                <p style="border-top: 1px solid #000; padding-top: 5px;">المراجعة</p>
                            </div>
                            <div class="col-4">
                                <p style="border-top: 1px solid #000; padding-top: 5px;">المدير المالي / الاعتماد</p>
                            </div>
                        </div>

                        <div class="no-print mt-4">
                            <hr>
                            <button class="btn btn-danger float-left" id="print_Button" onclick="printDiv()"> 
                                <i class="mdi mdi-printer ml-1"></i>{{__('home.print')}}
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