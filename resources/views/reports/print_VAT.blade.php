@extends('layouts.master')
@section('css')
<style>
    /* تعديل تنسيقات الطباعة لتسمح بتعدد الصفحات تلقائياً وضمان ظهور جميع الجداول */
    @media print {
        body, html {
            height: auto !important;
            overflow: visible !important;
            background-color: #fff !important;
        }

        body * {
            visibility: hidden !important;
        }

        #print, #print * {
            visibility: visible !important;
        }

        #print {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 10px !important;
            background-color: #fff !important;
        }

        .no-print {
            display: none !important;
        }

        .table-responsive {
            overflow: visible !important;
        }
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        color: #334155;
    }

    .report-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        padding: 30px;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .report-header {
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .company-info h5 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .company-info p {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 3px;
    }

    .report-title-badge {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 20px;
        display: inline-block;
        text-align: center;
    }

    .report-title-badge h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .info-bar {
        background: #f8fafc;
        border-right: 4px solid #3b82f6;
        padding: 12px 18px;
        border-radius: 6px;
        margin-bottom: 25px;
        font-size: 14px;
        color: #334155;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-top: 25px;
        margin-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 6px;
    }

    .table-official {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        margin-bottom: 20px;
    }

    .table-official thead th {
        background-color: #1e293b;
        color: #ffffff;
        border: none;
        padding: 12px 10px;
        font-weight: 600;
        text-align: center;
        font-size: 13.5px;
    }

    .table-official tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        color: #334155;
        border-top: 1px solid #e2e8f0;
        text-align: center;
        font-size: 13px;
        background-color: #ffffff;
    }

    .table-official tbody tr:hover {
        background-color: #f8fafc;
    }

    .text-success-custom { color: #059669 !important; font-weight: 600; }
    .text-warning-custom { color: #d97706 !important; font-weight: 600; }
    .text-danger-custom { color: #dc2626 !important; font-weight: 600; }
</style>
@endsection

@section('title')
{{ __('home.print') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between no-print">
</div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="report-container">
                
                <!-- ترويسة التقرير (الهيدر والشعار) -->
                <div class="report-header d-flex justify-content-between align-items-center flex-wrap" dir="rtl">
                    <div class="company-info" style="width: 33%; text-align: right;">
                        <h5>{{ Namear }}</h5>
                        <p>{{ describtionar }}</p>
                        <p>{{ STar }}</p>
                        <p>{{ Taxar }}</p>
                    </div>

                    <div class="text-center my-2 my-md-0" style="width: 33%;">
                        <?php $logo = camplogo; ?>
                        <a href="https://ebdeasoft.com/">
                            <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 100px; height: 90px; object-fit: contain;">
                        </a>
                        <div class="mt-2">
                            <div class="report-title-badge">
                                <h4>تقرير ضريبة القيمة المضافة والمصاريف</h4>
                            </div>
                        </div>
                    </div>

                    <div class="company-info" style="width: 33%; text-align: left;" dir="ltr">
                        <h5>{{ Nameen }}</h5>
                        <p>{{ describtionen }}</p>
                        <p>{{ STen }}</p>
                        <p>{{ Taxen }}</p>
                    </div>
                </div>

                <!-- شريط الفترة الزمنية ووقت الإصدار -->
                <div class="d-flex justify-content-between align-items-center flex-wrap info-bar" dir="rtl">
                    <div>
                        <span class="font-weight-bold ml-2">{{ __('report.from') }} :</span> <span class="text-primary font-weight-bold">{{ $data['start_at'] }}</span>
                        <span class="mx-3">|</span>
                        <span class="font-weight-bold ml-2">{{ __('report.to') }} :</span> <span class="text-primary font-weight-bold">{{ $data['end_at'] }}</span>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <?php $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s"); ?>
                        <span class="font-weight-bold ml-1 text-muted">{{ __('home.exportTime') }} :</span> 
                        <span class="font-weight-bold text-dark">{{ $currentdata }}</span>
                    </div>
                </div>

                <!-- 1. قسم المبيعات ومرتجعاتها -->
                <div class="section-title text-right">أولاً: قسم المبيعات ومرتجعاتها</div>
                <div class="table-responsive">
                    <table class="table table-official">
                        <thead>
                            <tr>
                                <th>البيان</th>
                                <th>{{ __('home.Numberofinvoices') }}</th>
                                <th>{{ __('home.withoudtax') }}</th>
                                <th>{{ __('home.addedValue') }}</th>
                                <th>{{ __('home.totalwithTax') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">{{ __('report.VATsales') }}</td>
                                <td>{{ $data['countsales'] }}</td>
                                <td>{{ number_format($data['total_sale'], 2) }}</td>
                                <td class="text-success-custom">{{ number_format($data['totalVatSales'], 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-success-custom">{{ number_format($data['totalVatSales'] + $data['total_sale'], 2) }} {{ __('home.SAR') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">{{ __('home.returnsalestax') }}</td>
                                <td>{{ $data['returncountsales'] }}</td>
                                <td>{{ number_format($data['salesreturn_withodtaxtax'], 2) }}</td>
                                <td class="text-warning-custom">{{ number_format($data['salesreturntax'], 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-warning-custom">{{ number_format($data['salesreturntax'] + $data['salesreturn_withodtaxtax'], 2) }} {{ __('home.SAR') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">{{ __('home.depitsales') }}</td>
                                <td>0</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr style="background-color: #f8fafc;">
                                <td class="font-weight-bold text-dark">{{ __('home.saletaxfinal') }} (صافي المبيعات)</td>
                                <td>-</td>
                                <td>{{ number_format($data['total_sale'] - $data['salesreturn_withodtaxtax'], 2) }}</td>
                                <td class="text-danger-custom">{{ number_format($data['totalVatSales'] - $data['salesreturntax'], 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-danger-custom">{{ number_format(($data['totalVatSales'] - $data['salesreturntax']) + ($data['total_sale'] - $data['salesreturn_withodtaxtax']), 2) }} {{ __('home.SAR') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- 2. قسم المشتريات ومرتجعاتها -->
                <div class="section-title text-right">ثانياً: قسم المشتريات ومرتجعاتها</div>
                <div class="table-responsive">
                    <table class="table table-official">
                        <thead>
                            <tr>
                                <th>البيان</th>
                                <th>{{ __('home.Numberofinvoices') }}</th>
                                <th>{{ __('home.withoudtax') }}</th>
                                <th>{{ __('home.addedValue') }}</th>
                                <th>{{ __('home.totalwithTax') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">{{ __('report.VATparchese') }}</td>
                                <td>{{ $data['countpurchase'] }}</td>
                                <td>{{ number_format($data['totalpurchase'], 2) }}</td>
                                <td class="text-success-custom">{{ number_format($data['totalVatPrachese_tax'], 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-success-custom">{{ number_format($data['totalVatPrachese_tax'] + $data['totalpurchase'], 2) }} {{ __('home.SAR') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">{{ __('home.returnpurchasetax') }}</td>
                                <td>{{ $data['returncountpurchases'] }}</td>
                                <td>{{ number_format($data['totalreturnpurchase'], 2) }}</td>
                                <td>{{ number_format($data['purachasereturntax'], 2) }} {{ __('home.SAR') }}</td>
                                <td>{{ number_format($data['purachasereturntax'] + $data['totalreturnpurchase'], 2) }} {{ __('home.SAR') }}</td>
                            </tr>
                            <?php
                                $final_purshase = $data['totalVatPrachese_tax'] + $data['totalpurchase'] - ($data['purachasereturntax'] + $data['totalreturnpurchase']);
                            ?>
                            <tr style="background-color: #f8fafc;">
                                <td class="font-weight-bold text-dark">{{ __('home.purchasetaxfinal') }} (صافي المشتريات)</td>
                                <td>{{ $data['countpurchase'] }}</td>
                                <td>{{ number_format($data['totalpurchase'] - $data['totalreturnpurchase'], 2) }}</td>
                                <td class="text-danger-custom">{{ number_format($data['totalVatPrachese_tax'] - $data['purachasereturntax'], 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-danger-custom">{{ number_format($final_purshase, 2) }} {{ __('home.SAR') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- 3. قسم المصروفات -->
                <div class="section-title text-right">ثالثاً: قسم المصروفات</div>
                <div class="table-responsive">
                    <table class="table table-official">
                        <thead>
                            <tr>
                                <th>البيان</th>
                                <th>العدد / العمليات</th>
                                <th>المبلغ (بدون ضريبة)</th>
                                <th>قيمة الضريبة</th>
                                <th>الإجمالي (شامل الضريبة)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">{{ __('home.expensesVAT') }} (المصروفات)</td>
                                <td>{{ $data['countexpanses'] }}</td>
                                <td class="text-danger-custom">{{ number_format($data['totalvarExpenses'] * 100 / 15, 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-danger-custom">{{ number_format($data['totalvarExpenses'], 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-danger-custom">{{ number_format($data['totalvarExpenses'] + ($data['totalvarExpenses'] * 100 / 15), 2) }} {{ __('home.SAR') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- 4. الملخص المالي وتوضيح المعادلة -->
                <div class="section-title text-right">رابعاً: الخلاصة والملخص الضريبي (صافي المبيعات - [صافي المشتريات + المصروفات])</div>
                <div class="table-responsive">
                    <table class="table table-official">
                        <thead>
                            <tr>
                                <th>البيان التوضيحي</th>
                                <th>المبلغ بدون ضريبة</th>
                                <th>قيمة الضريبة</th>
                                <th>الإجمالي شامل الضريبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">صافي المبيعات</td>
                                <td>{{ number_format($data['total_sale'] - $data['salesreturn_withodtaxtax'], 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-success-custom">{{ number_format($data['totalVatSales'] - $data['salesreturntax'], 2) }} {{ __('home.SAR') }}</td>
                                <td>{{ number_format(($data['totalVatSales'] - $data['salesreturntax']) + ($data['total_sale'] - $data['salesreturn_withodtaxtax']), 2) }} {{ __('home.SAR') }}</td>
                            </tr>

                            <?php
                                $exp_without_tax = $data['totalvarExpenses'] * 100 / 15;
                                $exp_tax = $data['totalvarExpenses'];
                                $exp_total = $exp_tax + $exp_without_tax;

                                $net_purch_without_tax = $data['totalpurchase'] - $data['totalreturnpurchase'];
                                $net_purch_tax = $data['totalVatPrachese_tax'] - $data['purachasereturntax'];

                                $sum_purch_exp_without = $net_purch_without_tax + $exp_without_tax;
                                $sum_purch_exp_tax = $net_purch_tax + $exp_tax;
                                $sum_purch_exp_total = $final_purshase + $exp_total;
                            ?>
                            <tr>
                                <td class="font-weight-bold">إجمالي (صافي المشتريات + المصروفات)</td>
                                <td>{{ number_format($sum_purch_exp_without, 2) }} {{ __('home.SAR') }}</td>
                                <td class="text-warning-custom">{{ number_format($sum_purch_exp_tax, 2) }} {{ __('home.SAR') }}</td>
                                <td>{{ number_format($sum_purch_exp_total, 2) }} {{ __('home.SAR') }}</td>
                            </tr>

                            <tr style="background-color: #f1f5f9; border-top: 2px solid #cbd5e1;">
                                <td class="font-weight-bold text-dark" style="font-size: 14px;">{{ __('home.Vatrequest') }} (صافي ضريبة المبيعات - [صافي المشتريات + المصروفات])</td>
                                <td>-</td>
                                <td class="text-danger-custom" style="font-size: 18px;">
                                    {{ number_format((round($data['totalVatSales'] - $data['salesreturntax'], 2) - round($data['totalvarExpenses'], 2) - ($data['totalVatPrachese_tax'] - $data['purachasereturntax'])), 2) }} {{ __('home.SAR') }}
                                </td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center no-print mt-4">
                    <button class="btn btn-danger px-5 py-2 font-weight-bold shadow-sm" id="print_Button" onclick="window.print()" style="border-radius: 6px;">
                        <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
@endsection