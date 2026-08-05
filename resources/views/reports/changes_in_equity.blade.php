@extends('layouts.master')
@section('css')
<style>
    @media print {
        .hide-print { display: none !important; }
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            background-color: #ffffff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    body {
        font-family: 'Cairo', 'Tajawal', sans-serif, Arial;
        color: #2d3748;
        background-color: #f7fafc;
    }

    .report-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin: 20px auto;
        max-width: 900px;
    }

    /* Report Header */
    .report-header {
        text-align: center;
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .report-header h4 {
        font-size: 18px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 5px;
    }

    .report-header p {
        font-size: 13px;
        color: #718096;
        margin: 0;
    }

    /* Filter Form */
    .filter-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
    }

    /* Report Table */
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .report-table th {
        background-color: #2d3748;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        padding: 12px 15px;
        text-align: right;
    }

    .report-table th:last-child {
        text-align: left;
    }

    .report-table td {
        padding: 12px 15px;
        font-size: 13px;
        border-bottom: 1px solid #e2e8f0;
        color: #2d3748;
        text-align: right;
    }

    .report-table td:last-child {
        text-align: left;
        font-weight: 600;
    }

    .report-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .report-table tbody tr.total-row {
        background-color: #edf2f7;
        font-weight: bold;
        color: #1a202c;
    }

    .report-table tbody tr.total-row td {
        border-bottom: none;
    }
</style>
@endsection

@section('title')
قائمة التغير في حقوق الملكية
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
<div class="container-fluid" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="report-card">
                

                <!-- زر الطباعة المباشر -->
                <div class="d-flex justify-content-end mb-3 hide-print">
                    <button class="btn btn-danger px-4 py-2 font-weight-bold shadow-sm" onclick="printDiv()" style="border-radius: 6px;">
                        طباعة القائمة <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <!-- ترويسة التقرير -->
                <div class="report-header">
                    <h4>قائمة التغير في حقوق الملكية</h4>
                    <p>Statement of Changes in Equity</p>
                    <p class="mt-2" style="font-size: 12px; font-weight: bold; color: #4a5568;">
                        للفترة من: {{$fromDate}} إلى: {{$toDate}}
                    </p>
                </div>

                <!-- جدول عرض البيانات المالية -->
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>البيان (Description)</th>
                            <th>المبلغ (Amount)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>رصيد حقوق الملكية في بداية الفترة <br><small style="color: #718096; font-weight: normal;">Beginning Balance</small></td>
                            <td>{{ number_format($beginningCapital, 2) }}</td>
                        </tr>
                        <tr>
                            <td>إضافات رأس المال خلال الفترة <br><small style="color: #718096; font-weight: normal;">Capital Additions</small></td>
                            <td>{{ number_format($capitalAdditions, 2) }}</td>
                        </tr>
                        <tr>
                            <td>المسحوبات الشخصية خلال الفترة <br><small style="color: #718096; font-weight: normal;">Drawings</small></td>
                            <td style="color: #e53e3e;">( {{ number_format($drawings, 2) }} )</td>
                        </tr>
                        <tr>
                            <td>صافي الدخل / الربح للفترة <br><small style="color: #718096; font-weight: normal;">Net Income for the Period</small></td>
                            <td style="color: #2b6cb0;">{{ number_format($netIncome, 2) }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>رصيد حقوق الملكية في نهاية الفترة <br><small style="color: #4a5568; font-weight: normal;">Ending Balance</small></td>
                            <td style="font-size: 14px; color: #1a202c;">{{ number_format($endingCapital, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function printDiv() {
        window.print();
    }
</script>
@endsection