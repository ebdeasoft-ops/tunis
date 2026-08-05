@extends('layouts.master')

@section('css')
<style>
    /* جعل كافة النصوص والجدول بخط أثقل وأوضح */
    body, .report-card, .report-table td, .report-table th {
        font-weight: 600 !important;
        color: #1a202c;
    }

    .report-table th {
        font-weight: 700 !important;
    }

    .total-row {
        font-weight: 900 !important;
    }

    @media print {
        /* إخفاء الهيدر، القائمة الجانبية، وكل عناصر النظام */
        .main-header, .main-sidebar, .sidebar, header, nav, footer, .app-header, .app-sidebar {
            display: none !important;
        }
        
        /* إخفاء زر الطباعة وكل الأزرار أثناء الطباعة */
        button, .btn, .no-print {
            display: none !important;
        }
        
        /* ضبط خلفية الصفحة للطباعة الاحترافية */
        body, html {
            background-color: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #print-report {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        @page { size: A4; margin: 10mm; }
    }

    .report-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
        padding: 35px;
        margin: 30px auto;
        max-width: 950px;
        border-top: 5px solid #2b6cb0;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        border: 1px solid #cbd5e0;
    }
    .report-table th {
        background-color: #2b6cb0;
        color: #ffffff;
        padding: 12px 15px;
        text-align: right;
    }
    .report-table th:last-child { text-align: left; }
    .report-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #cbd5e0;
        text-align: right;
    }
    .report-table td:last-child {
        text-align: left;
        font-family: 'Courier New', Courier, monospace;
    }
    .section-header {
        background-color: #edf2f7;
        color: #2b6cb0;
    }
    .total-row {
        background-color: #e2e8f0;
    }
</style>
@endsection

@section('title')
قائمة التدفقات النقدية
@stop

@section('content')
<div class="container-fluid" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="report-card" id="print-report">
                
                <!-- زر الطباعة وتاريخ التصدير (يختفي عند الطباعة) -->
                <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                    <span style="font-size: 13px; color: #718096;">تاريخ التصدير: {{ date('Y-m-d H:i:s') }}</span>
                    <button class="btn btn-danger px-4 py-2 font-weight-bold" onclick="window.print()" style="background-color: #e53e3e; border: none;">
                        طباعة التقرير <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <!-- ترويسة التقرير -->
                <div class="text-center mb-4">
                    <h3 style="font-weight: 800;">قائمة التدفقات النقدية</h3>
                    <span>Statement of Cash Flows</span>
                    <div class="mt-2">
                        <span class="badge" style="background: #ebf8ff; color: #2b6cb0; padding: 6px 16px; border-radius: 20px; font-weight: bold;">
                            للفترة من: {{$fromDate}} إلى: {{$toDate}}
                        </span>
                    </div>
                </div>

                <!-- جدول التدفقات النقدية -->
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>البيان (Description)</th>
                            <th>المبلغ (Amount)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- رصيد البداية -->
                        <tr>
                            <td><strong>رصيد النقدية وما في حكمها في بداية الفترة</strong><br><small>Beginning Cash Balance</small></td>
                            <td>{{ number_format($beginningCash, 2) }}</td>
                        </tr>

                        <!-- الأنشطة التشغيلية -->
                        <tr class="section-header">
                            <td colspan="2">1. التدفقات النقدية من الأنشطة التشغيلية (Operating Activities)</td>
                        </tr>
                        <tr>
                            <td>صافي النقد من الأنشطة التشغيلية</td>
                            <td style="color: {{ $operatingCashFlow >= 0 ? '#276749' : '#9b2c2c' }};">
                                {{ number_format($operatingCashFlow, 2) }}
                            </td>
                        </tr>

                        <!-- الأنشطة الاستثمارية -->
                        <tr class="section-header">
                            <td colspan="2">2. التدفقات النقدية من الأنشطة الاستثمارية (Investing Activities)</td>
                        </tr>
                        <tr>
                            <td>صافي النقد من الأنشطة الاستثمارية (الأصول الثابتة)</td>
                            <td style="color: {{ $investingCashFlow >= 0 ? '#276749' : '#9b2c2c' }};">
                                {{ number_format($investingCashFlow, 2) }}
                            </td>
                        </tr>

                        <!-- الأنشطة التمويلية -->
                        <tr class="section-header">
                            <td colspan="2">3. التدفقات النقدية من الأنشطة التمويلية (Financing Activities)</td>
                        </tr>
                        <tr>
                            <td>صافي النقد من الأنشطة التمويلية (حقوق الملكية)</td>
                            <td style="color: {{ $financingCashFlow >= 0 ? '#276749' : '#9b2c2c' }};">
                                {{ number_format($financingCashFlow, 2) }}
                            </td>
                        </tr>

                        <!-- صافي التغير والنهاية -->
                        <tr class="total-row">
                            <td>صافي الزيادة / (النقص) في النقدية خلال الفترة<br><small>Net Increase in Cash</small></td>
                            <td>{{ number_format($netCashChange, 2) }}</td>
                        </tr>
                        <tr class="total-row" style="background-color: #cbd5e0;">
                            <td>رصيد النقدية وما في حكمها في نهاية الفترة<br><small>Ending Cash Balance</small></td>
                            <td style="color: #2b6cb0;">{{ number_format($endingCash, 2) }} ر.س</td>
                        </tr>
                    </tbody>
                </table>

                <!-- التوقيعات -->
                <div style="display: flex; justify-content: space-between; margin-top: 40px; padding-top: 20px; border-top: 1px solid #cbd5e0; font-size: 13px; color: #4a5568; font-weight: bold;">
                    <div>المحاسب: ........................</div>
                    <div>المدير المالي: ........................</div>
                    <div>اعتماد الإدارة: ........................</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection