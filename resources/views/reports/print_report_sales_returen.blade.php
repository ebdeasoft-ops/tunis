@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.5;
    }
    
    .table-padding {
        margin-top: 20px;
        width: 35%;
        float: left;
    }
</style>
@endsection

@section('title')
{{__('home.print')}}
@stop

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }}
                        <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>
                <br>

                <div class="card-body">
                    <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">
                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>
                            <span style="font-size:25px">{{ $Nameen ?? '' }}</span>
                            <p dir="ltr">{{ $describtionen ?? '' }}</p>
                            <p dir="ltr">{{ $STen ?? '' }}</p>
                            <p dir="ltr">{{ $Taxen ?? '' }}</p>
                        </div>
                        <div class="row align-items-center">
                            @php $logo = $camplogo ?? 'logo.png'; @endphp
                            <a href="#"><img src="{{ asset('assets/img/brand/'.$logo) }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>
                        </div>
                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>
                            <span style="font-size:25px">{{ $Namear ?? '' }}</span>
                            <p>{{ $describtionar ?? '' }}</p>
                            <p>{{ $STar ?? '' }}</p>
                            <p>{{ $Taxar ?? '' }}</p>
                        </div>
                    </div>

                    @php
                        // جلب نسبة الضريبة
                        $avt_data = App\Models\Avt::find(1);
                        $saleavt = $avt_data->AVT ?? 0.15;
                        
                        $grandTotalBeforeTax = 0;
                        $grandTotalTax = 0;
                        $i = 0;
                    @endphp

                    <h4 class="text-center mt-4 mb-2">تقرير مرتجعات المبيعات</h4>

                    <table class="table table-bordered table-striped text-center" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('report.date')}}</th>
                                <th>{{__('report.invoiceNo')}}</th>
                                <th>{{__('home.quantity')}}</th>
                                <th>{{__('home.price')}} (صافي)</th>
                                <th>{{__('home.addedValue')}}</th>
                                <th>{{__('home.discount')}}</th>
                                <th>{{__('home.total')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Invoices as $invoiceId => $items)
                                @php
                                    $i++;
                                    // حساب إجمالي الكميات لهذه الفاتورة
                                    $invoiceQty = $items->sum('return_quantity');
                                    
                                    // حساب الخصم (خصم الصنف + خصم الفاتورة)
                                    $invoiceDiscount = $items->sum('discountvalue') + $items->sum('discountoninvoice');
                                    
                                    // تطبيق المعادلة: (السعر * الكمية) - الخصم = الصافي قبل الضريبة
                                    $subTotalBeforeTax = $items->sum(function($item) {
                                        return ($item->return_Unit_Price * $item->return_quantity) - ($item->discountvalue + $item->discountoninvoice);
                                    });

                                    $taxAmount = $subTotalBeforeTax * $saleavt;
                                    $totalWithTax = $subTotalBeforeTax + $taxAmount;

                                    // تجميع الإجماليات النهائية للتقرير
                                    $grandTotalBeforeTax += $subTotalBeforeTax;
                                    $grandTotalTax += $taxAmount;
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $items->first()->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $invoiceId }}</td>
                                    <td>{{ number_format($invoiceQty, 0) }}</td>
                                    <td>{{ number_format($subTotalBeforeTax, 2) }}</td>
                                    <td>{{ number_format($taxAmount, 2) }}</td>
                                    <td>{{ number_format($invoiceDiscount, 2) }}</td>
                                    <td>{{ number_format($totalWithTax, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="table-padding">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-light">{{__('report.totalpricewithoudtax')}}</th>
                                <td class="font-weight-bold">{{ number_format($grandTotalBeforeTax, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">{{__('report.totaltax')}}</th>
                                <td class="font-weight-bold">{{ number_format($grandTotalTax, 2) }}</td>
                            </tr>
                            <tr class="table-info">
                                <th>{{__('report.totalallprice')}}</th>
                                <td class="font-weight-bold">{{ number_format($grandTotalBeforeTax + $grandTotalTax, 2) }}</td>
                            </tr>
                        </table>
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