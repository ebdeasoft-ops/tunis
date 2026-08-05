@extends('layouts.master')
@section('css')
<style>
    /* تنسيقات الطباعة الصارمة لإخفاء القوائم والهيدر وعرض التقرير لوحده */
    @media print {
        body, html {
            background-color: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
        }

        /* إخفاء الهيدر، السايدبار، الفوتر، وكل محتويات القالب الرئيسي */
        .main-header, 
        .main-sidebar, 
        .main-footer, 
        .breadcrumb-header, 
        .no-print, 
        nav, 
        aside, 
        header, 
        footer,
        .app-content,
        .main-content,
        #print_Button {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            max-height: 0 !important;
            overflow: hidden !important;
        }

        /* إخفاء كافة عناصر الصفحة افتراضياً */
        body * {
            visibility: hidden !important;
        }

        /* إظهار قسم التقرير المحدد فقط ومحتوياته بالكامل */
        #print, 
        #print * {
            visibility: visible !important;
        }

        /* ضبط مقاسات قسم التقرير ليحتل صفحة الطباعة بوضوح */
        #print {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 10px !important;
            box-shadow: none !important;
            border: none !important;
            background-color: #fff !important;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
    }
</style>
@endsection

@section('title')
{{ __('home.print') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between no-print">
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice" style="border: none; box-shadow: none;">
                <div class="card-body">
                    
                    <!-- الهيدر والبيانات والجدول -->
                    <div class="invoice-header" style="display: flex; justify-content: space-between; width: 100%" dir="rtl">
                        <div class="billed-from" style="width:33%; text-align: center;">
                            <br>
                            <span class="tx-18 thick">{{ Namear }}</span>
                            <br>
                            <p class="tx-16 thick">{{ describtionar }}</p>
                            <p class="tx-16 thick">{{ STar }}</p>
                            <p class="tx-16 thick">{{ Taxar }}</p>
                        </div><!-- billed-from -->

                        <div class="row">
                            <?php
                            $logo = camplogo;
                            ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>
                        </div>

                        <div class="billed-from" style="width:33%; text-align: center;">
                            <br>
                            <span class="tx-18 thick">{{ Nameen }}</span>
                            <br>
                            <p class="tx-16 thick">{{ describtionen }}</p>
                            <span class="tx-16 thick">{{ STen }}</span>
                            <p class="tx-16 thick">{{ Taxen }}</p>
                        </div>
                    </div><!-- invoice-header -->
                    
                    <br>
                    <center>
                        <span>{{ __('report.VAT') }}</span>
                    </center>
                    <br>

                    <div class="col-lg-3" id="start_at">
                        <label style="font-size: 14px; color:#419BB2; font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                        <?php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                        ?>
                        <label style="font-size: 14px; color:#419BB2; font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>
                    </div>

                    <div style="border-radius: 10px" class="card m-3 p-3">
                        <div class="table-responsive">
                            <span style="font-size: 17px; color:#419BB2" class="text-">{{ __('report.from') }} : {{ $data['start_at'] }} </span>
                            &nbsp;&nbsp;
                            <span style="font-size: 17px; color:#419BB2" class="text">{{ __('report.to') }} : {{ $data['end_at'] }} </span>

                            <br>

                            <div class="table-responsive hoverable-table my-3">
                                <div style="border-radius: 10px" class="card m-3 p-3">
                                    <div class="table-responsive">
                                        <div class="table-responsive hoverable-table table-padding">
                                            <table class="table table-bordered table-striped table-hover" id="example1" data-page-length='50' style="text-align: center;">
                                                <thead>
                                                    <tr>
                                                        <th>-</th>
                                                        <th>{{ __('home.Numberofinvoices') }}</th>
                                                        <th>{{ __('home.withoudtax') }}</th>
                                                        <th>{{ __('home.addedValue') }}</th>
                                                        <th>{{ __('home.totalwithTax') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="border-bottom-0">{{ __('report.VATsales') }}</td>
                                                        <td class="border-bottom-0">{{ $data['countsales'] }}</td>
                                                        <td class="border-bottom-0">{{ round($data['total_sale'], 2) }}</td>
                                                        <td class="border-bottom-0" style="color:green">{{ round($data['totalVatSales'], 2) }} {{ __('home.SAR') }}</td>
                                                        <td class="border-bottom-0" style="color:green">{{ round($data['totalVatSales'] + $data['total_sale'], 2) }} {{ __('home.SAR') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-bottom-0">{{ __('home.returnsalestax') }}</td>
                                                        <td class="border-bottom-0">{{ $data['returncountsales'] }}</td>
                                                        <td class="border-bottom-0">{{ round(($data['salesreturn_withodtaxtax']), 2) }}</td>
                                                        <td class="border-bottom-0" style="color:yellow">{{ round($data['salesreturntax'], 2) }} {{ __('home.SAR') }}</td>
                                                        <td class="border-bottom-0" style="color:yellow">{{ round($data['salesreturntax'] + $data['salesreturn_withodtaxtax'], 2) }} {{ __('home.SAR') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-bottom-0">{{ __('home.depitsales') }}</td>
                                                        <td class="border-bottom-0">0</td>
                                                        <td class="border-bottom-0">0</td>
                                                        <td class="border-bottom-0">0</td>
                                                        <td class="border-bottom-0">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-bottom-0">{{ __('home.saletaxfinal') }}</td>
                                                        <td class="border-bottom-0">-</td>
                                                        <td class="border-bottom-0">{{ round(($data['total_sale'] - $data['salesreturn_withodtaxtax']), 2) }}</td>
                                                        <td class="border-bottom-0" style="color:red">{{ round($data['totalVatSales'] - $data['salesreturntax'], 2) }} {{ __('home.SAR') }}</td>
                                                        <td class="border-bottom-0" style="color:red">{{ round(($data['totalVatSales'] - $data['salesreturntax']) + ($data['total_sale'] - $data['salesreturn_withodtaxtax']), 2) }} {{ __('home.SAR') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('home.expensesVAT') }}</td>
                                                        <td>{{ $data['countexpanses'] }}</td>
                                                        <td style="color:red">{{ round($data['totalvarExpenses'] * 100 / 15, 2) }} {{ __('home.SAR') }}</td>
                                                        <td style="color:red">{{ round($data['totalvarExpenses'], 2) }} {{ __('home.SAR') }}</td>
                                                        <td style="color:red">{{ round($data['totalvarExpenses'] + round($data['totalvarExpenses'] * 100 / 15, 2), 2) }} {{ __('home.SAR') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('report.VATparchese') }}</td>
                                                        <td>{{ $data['countpurchase'] }}</td>
                                                        <td>{{ $data['totalpurchase'] }}</td>
                                                        <td style="color:green">{{ $data['totalVatPrachese_tax'] }} {{ __('home.SAR') }}</td>
                                                        <td style="color:green">{{ $data['totalVatPrachese_tax'] + ($data['totalpurchase']) }} {{ __('home.SAR') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('home.returnpurchasetax') }}</td>
                                                        <td>{{ $data['returncountpurchases'] }}</td>
                                                        <td>{{ $data['totalreturnpurchase'] }}</td>
                                                        <td>{{ $data['purachasereturntax'] }} {{ __('home.SAR') }}</td>
                                                        <td>{{ $data['purachasereturntax'] + $data['totalreturnpurchase'] }} {{ __('home.SAR') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('home.purchasetaxfinal') }}</td>
                                                        <td>{{ $data['countpurchase'] }}</td>
                                                        <td>{{ $data['totalpurchase'] - $data['totalreturnpurchase'] }}</td>
                                                        <td style="color:red">{{ $data['totalVatPrachese_tax'] - $data['purachasereturntax'] }}{{ __('home.SAR') }}</td>
                                                        <?php
                                                        $final_purshase = $data['totalVatPrachese_tax'] + $data['totalpurchase'] - ($data['purachasereturntax'] + $data['totalreturnpurchase']);
                                                        ?>
                                                        <td style="color:red">{{ $final_purshase }}{{ __('home.SAR') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('home.Vatrequest') }}</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td style="color:black; font-size:22px">{{ round($data['totalVatSales'] - $data['salesreturntax'], 2) - round($data['totalvarExpenses'], 2) - ($data['totalVatPrachese_tax'] - $data['purachasereturntax']) }}</td>
                                                        <td>-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr class="mg-b-40">

                                        <!-- زر الطباعة -->
                                        <div class="d-flex justify-content-center no-print">
                                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="window.print()">
                                                {{ __('home.print') }}
                                                <i class="mdi mdi-printer ml-1"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- COL-END -->
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection

@section('js')
<!--Internal Chart.bundle js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
@endsection