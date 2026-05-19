@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button {
                display: none !important;
            }
            body {
                border: none !important;
            }
        }

        body {
            font: 13pt Georgia, "Times New Roman", Times, serif;
            line-height: 1.5;
        }
        
        /* تنسيق الجدول ليظهر بشكل احترافي في الطباعة */
        .table-bordered th, .table-bordered td {
            border: 1px solid #333 !important;
        }
    </style>
@endsection

@section('title')
    {{ __('home.print') }}
@stop

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-danger" id="print_Button" onclick="printDiv()">
                            {{ __('home.print') }}
                            <i class="mdi mdi-printer ml-1"></i>
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
<?php
$logo=camplogo;
?>
<a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

</div>


<div class="billed-from" style="width:33%;text-align: center;">
    <br>

   <span style="font-size:25px">{{Namear}}</span>
    <br>
    <p> {{describtionar}}</p>
    <p>{{STar}}</p>
    <p>{{Taxar}}</p>

</div><!-- billed-from -->
</div><!-- invoice-header -->

                        <hr>

                        <div class="text-center mg-t-12">
                            <h4 style="color: #419BB2; font-weight: bold;">{{ __('report.Expenses') }}</h4>
                            <div class="mt-2">
                                <label style="font-size: 14px; color:#419BB2; font-weight:bold;">
                                    {{ __('home.exportTime') }} : 
                                    @php echo \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s"); @endphp
                                </label>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            @if (isset($Invoices) && count($Invoices) > 0)
                                <table class="table table-hover table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('report.date') }}</th>
                                            <th>{{ __('users.branch') }}</th>
                                            <th>{{ __('accountes.user') }}</th>
                                            <th>يخضع للضريبة </th>
                                            <th>{{ __('accountes.Theamountpaid') }}</th>
                                            <th>{{ __('accountes.Reasonforspendingmoney') }}</th>
                                            <th>{{ __('home.paymentmethod') }}</th>
                                            <th>{{ __('home.notesClient') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            @php 
    $totalprice = 0; 
    $totalVatActive = 0;    // المجموع الخاضع
    $totalVatInactive = 0;  // المجموع غير الخاضع
@endphp

@foreach ($Invoices as $invoice)
    @php 
        $totalprice += $invoice->recive_amount; 
        
        // شرط الجمع المنفصل
        if($invoice->vat == 1) {
            $totalVatActive += $invoice->recive_amount;
        } else {
            $totalVatInactive += $invoice->recive_amount;
        }
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $invoice->created_at }}</td>
        <td>{{ $invoice->branch->name }}</td>
        <td>{{ $invoice->user->name }}</td>
        <td>
            @if($invoice->vat == 1)
                <span class="badge badge-success" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fa fa-check"></i> يخضع للضريبة
                </span>
            @else
                <span class="badge badge-danger" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fa fa-times"></i> غير خاضع للضريبة
                </span>
            @endif
        </td>
        <td class="font-weight-bold">{{ number_format($invoice->recive_amount, 2) }}</td>
        <td>{{ $invoice->financial_accounts_data->name??''}}</td>
        <td>{{ $invoice->Pay_Method_Name }}</td>
        <td>{{ $invoice->note ?? '-' }}</td>
    </tr>
@endforeach
                                    </tbody>
                                </table>

            <div class="row mt-4">
    <div class="col-md-5 {{ app()->getLocale() == 'ar' ? 'mr-auto' : 'ml-auto' }}">
        <table class="table table-bordered text-center shadow-sm">
            <thead class="bg-light">
                <tr>
                    <th>البيان</th>
                    <th>المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-success font-weight-bold">مجموع الخاضع للضريبة</td>
                    <td class="tx-16 font-weight-bold">{{ number_format($totalVatActive, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-danger font-weight-bold">مجموع غير خاضع للضريبة</td>
                    <td class="tx-16 font-weight-bold">{{ number_format($totalVatInactive, 2) }}</td>
                </tr>
                <tr class="bg-dark text-white">
                    <td class="font-weight-bold">{{ __('home.total') }}</td>
                    <td class="tx-18 font-weight-bold">{{ number_format($totalprice, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
                            @else
                                <div class="alert alert-light text-center border mt-4">
                                    {{ __('home.no_data') }}
                                </div>
                            @endif
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