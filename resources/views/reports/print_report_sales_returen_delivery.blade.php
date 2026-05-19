@extends('layouts.master')

@section('css')
<style>
    /* تحسينات العرض العام */
    .card-invoice {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 10px;
    }

    .invoice-title {
        font-size: 22px;
        font-weight: bold;
        color: #419BB2;
        border: 2px solid #419BB2;
        display: inline-block;
        padding: 5px 25px;
        border-radius: 5px;
        background-color: #f9f9f9;
        margin-bottom: 20px;
    }

    /* تنسيق الجداول */
    .table thead th {
        background-color: #f2f7f9 !important;
        color: #419BB2 !important;
        border-bottom: 2px solid #419BB2 !important;
        vertical-align: middle !important;
    }

    .table-totals th {
        background-color: #f8f9fa;
        width: 30%;
    }

    /* إعدادات الطباعة */
    @media print {
        #print_Button, .breadcrumb-header, .main-footer {
            display: none !important;
        }

        body {
            background: #fff !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .main-content-body-invoice {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        @page {
            size: A4;
            margin: 1cm;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important;
        }
        
        -webkit-print-color-adjust: exact;
    }

    .billed-from p {
        margin-bottom: 2px;
        font-size: 14px;
        color: #555;
    }
</style>
@endsection

@section('title')
{{ __('home.print') }}
@stop

@section('page-header')
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print" dir="rtl">
            <div class="card card-invoice">
                <div class="d-flex justify-content-center no-print">
                    <button class="btn btn-danger mt-3 mb-3" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body">
                    <div class="invoice-header d-flex align-items-center justify-content-between" style="width:100%">
                        <div class="billed-from text-center" style="width:33%;">
                            <span style="font-size:20px; font-weight:bold;">{{Nameen}}</span>
                            <p dir="ltr">{{describtionen}}</p>
                            <p dir="ltr"> {{STen}}</p>
                            <p dir="ltr">{{Taxen}}</p>
                        </div>

                        <div class="text-center" style="width:33%;">
                            <?php $logo = camplogo; ?>
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" alt="logo" style="width: 120px; height: auto;">
                            </a>
                        </div>

                        <div class="billed-from text-center" style="width:33%;">
                            <span style="font-size:20px; font-weight:bold;">{{Namear}}</span>
                            <p>{{describtionar}}</p>
                            <p> {{STar}}</p>
                            <p> {{Taxar}}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <div class="invoice-title">
                            {{ __('home.salesreturned_delivery') }}
                        </div>
                        <p class="mt-2">
                            <strong>{{ __('home.exportTime') }}:</strong> 
                            {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                        </p>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped text-center" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('report.date')}}</th>
                                    <th>{{__('report.invoiceNo')}}</th>
                                    <th>{{__('home.quantity')}}</th>
                                    <th>{{__('home.price')}}</th>
                                    <th>{{__('home.discount')}}</th>
                                    <th>{{__('home.total')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $invoiceIds = [];
                                    foreach ($Invoices as $inv) {
                                        if(!in_array($inv->invoice_id, $invoiceIds)) $invoiceIds[] = $inv->invoice_id;
                                    }
                                    
                                    $totalprice = 0; $totalpricefinal = 0; $totaldiscount = 0; $i = 0;
                                ?>

                                @foreach($invoiceIds as $invoiceid)
                                    <?php
                                        $subInvoices = App\Models\return_sales_deliverys::where('invoice_id', $invoiceid)->get();
                                        $numberofPice = 0; $temp_total = 0; $temp_before_discount = 0; $temp_discount = 0;
                                        $date = "";

                                        foreach ($subInvoices as $item) {
                                            $date = explode(" ", $item->created_at)[0];
                                            $qty = $item->return_quantity;
                                            $u_price = $item->return_Unit_Price;
                                            $disc = $item->discountvalue + $item->discountoninvoice;

                                            $temp_before_discount += ($qty * $u_price);
                                            $temp_discount += $disc;
                                            $temp_total += ($qty * $u_price) - $disc;
                                            $numberofPice += $qty;
                                        }

                                        $totalprice += $temp_before_discount;
                                        $totaldiscount += $temp_discount;
                                        $totalpricefinal += $temp_total;
                                        $i++;
                                    ?>
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$date}}</td>
                                        <td><strong>{{$invoiceid}}</strong></td>
                                        <td>{{$numberofPice}}</td>
                                        <td>{{number_format($temp_before_discount, 2)}}</td>
                                        <td>{{number_format($temp_discount, 2)}}</td>
                                        <td class="font-weight-bold">{{number_format($temp_total, 2)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end mt-4">
                        <div class="col-md-5">
                            <table class="table table-bordered table-totals">
                                <tbody>
                                    <tr>
                                        <th>{{ __('home.the amount') }}</th>
                                        <td class="text-center">{{ number_format($totalprice, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.discount') }}</th>
                                        <td class="text-center text-danger">{{ number_format($totaldiscount, 2) }}</td>
                                    </tr>
                                    <tr style="font-size: 18px; font-weight: bold; background-color: #f2f7f9;">
                                        <th>{{ __('home.total') }}</th>
                                        <td class="text-center text-primary">{{ number_format($totalpricefinal, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> </div>
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