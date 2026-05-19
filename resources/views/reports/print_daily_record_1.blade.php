@extends('layouts.master')

@section('title')
    {{ __('home.print') }}
@stop

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
        .header-title { color: #419BB2; font-weight: bold; margin-top: 15px; }
        .daily-record-head { background-color: #f8f9fa !important; font-weight: bold; }
    </style>
@endsection

@section('content')
    <div class="row row-sm" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-danger" id="print_Button" onclick="printDiv()">
                            {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">
                            <div class="billed-from" style="width:33%;text-align: center;" >
                                <br>
                                <span style="font-size:25px">{{Nameen}}</span>
                                <br>
                                <p dir=ltr> {{describtionen}} </p>
                                <span dir=ltr>{{STen}} </span>
                                <p dir=ltr> {{Taxen}} </p>
                            </div>
                            <div class="row">
                                <?php $logo=camplogo; ?>
                                <a href="https://ebdeasoft.com/"><img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>
                            </div>
                            <div class="billed-from" style="width:33%;text-align: center;">
                                <br>
                                <span style="font-size:25px">{{Namear}}</span>
                                <br>
                                <p> {{describtionar}}</p>
                                <p>{{STar}}</p>
                                <p>{{Taxar}}</p>
                            </div>
                        </div>

                        <hr style="border-top: 2px solid #419BB2;">

                        <div class="text-center">
                            <h4 class="header-title">{{ __('home.journal_title') ?? 'تقرير القيود اليومية' }}</h4>
                            <div class="d-flex justify-content-center" style="gap: 20px;">
                                <span><strong>{{ __('report.fromdate') ?? 'من' }}:</strong> {{ $start_at }}</span>
                                <span><strong>{{ __('report.todate') ?? 'إلى' }}:</strong> {{ $end_at }}</span>
                            </div>
                            <p class="mt-2" style="font-size: 11px; color: #666;">
                                {{ __('home.exportTime') }}: {{ \Carbon\Carbon::now()->addHours(3)->format('Y-m-d H:i:s') }}
                            </p>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered text-center table-striped">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('report.date') }}</th>
                                        <th>{{ __('home.journal_account_name') ?? 'الحساب المالي' }}</th>
                                        <th>{{ __('home.journal_debit') ?? 'مدين' }}</th>
                                        <th>{{ __('home.journal_credit') ?? 'دائن' }}</th>
                                        <th>{{ __('home.paymentmethod') }}</th>
                                        <th>{{ __('home.notesClient') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $total_debtor = 0; 
                                        $total_creditor = 0;
                                        $current_record_id = null; // الحل: تعريف المتغير هنا قبل الحلقة
                                    @endphp
                                    @foreach($List_dely_record as $index => $item)
                                        @php 
                                            $total_debtor += $item['debtor'];
                                            $total_creditor += $item['creditor'];
                                        @endphp

                                        {{-- فحص تغير رقم القيد لعرض صف رأس القيد --}}
                                        @if($current_record_id !== $item['dely_record'])
                                            <tr class="daily-record-head">
                                                {{-- تم تعديل colspan إلى 7 ليغطي كامل عرض الجدول --}}
                                                <td colspan="7" style="text-align: right; padding: 10px; border-top: 2px solid #444 !important; background-color: #f0f0f0 !important;">
                                                    <strong>{{ __('home.record_number') ?? 'رقم القيد' }}:</strong> #{{ $item['dely_record'] }}
                                                    <span style="margin: 0 15px; color: #ccc;">|</span>
                                                    <strong>{{ __('home.notesClient') ?? 'البيان العام' }}:</strong> 
                                                    <span>{{ $item['main_note'] ?? '---' }}</span>
                                                </td>
                                            </tr>
                                            @php $current_record_id = $item['dely_record']; @endphp
                                        @endif

                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item['date'])->format('Y-m-d') }}</td>
                                            <td class="text-right">{{ $item['name'] }}</td>
                                            <td class="font-weight-bold">{{ number_format($item['debtor'], 2) }}</td>
                                            <td class="font-weight-bold">{{ number_format($item['creditor'], 2) }}</td>
                                            <td>{{ $item['method_pay'] }}</td>
                                            <td style="font-size: 11px;">{{ $item['note'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color: #eee; font-weight: bold;">
                                    <tr>
                                        <td colspan="3">{{ __('home.total') }}</td>
                                        <td class="text-primary">{{ number_format($total_debtor, 2) }}</td>
                                        <td class="text-danger">{{ number_format($total_creditor, 2) }}</td>
                                        <td colspan="2">
                                            {{ __('difference') ?? 'الفرق' }}: 
                                            {{ number_format(abs($total_debtor - $total_creditor), 2) }}
                                        </td>
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
    <script>
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