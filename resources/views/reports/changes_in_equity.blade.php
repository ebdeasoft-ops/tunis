@extends('layouts.master')
@section('css')
<style>
    /* تنسيقات الطباعة: نخفي كل شيء ما عدا التقرير، وما تخفيش أي عنصر أب للتقرير */
    @media print {
        .main-header,
        .main-sidebar,
        .main-footer,
        .breadcrumb-header,
        .no-print,
        nav,
        aside,
        header,
        footer {
            display: none !important;
        }

        body, html {
            background-color: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* إخفاء كل حاجة غير مقروءة، وإظهار التقرير وحاوياته الأب فقط
           (متعمدين ماحطيناش display:none على app-content / main-content
           عشان دول أب للتقرير، وإخفاء الأب بيخفي التقرير معاه) */
        body * {
            visibility: hidden !important;
        }
        #print-report, #print-report * {
            visibility: visible !important;
        }

        #print-report {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 10px !important;
            box-shadow: none !important;
            border-top: none !important;
            background-color: #fff !important;
        }

        @page {
            size: A4;
            margin: 10mm;
        }
    }

    body {
        font-family: 'Cairo', 'Tajawal', sans-serif, Arial;
        color: #2d3748;
        background-color: #f4f6f9;
    }

    .report-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
        padding: 40px 45px;
        margin: 30px auto;
        max-width: 950px;
        border-top: 5px solid #2b6cb0;
    }

    /* هيدر الشركة الثلاثي الاحترافي */
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 22px;
        margin-bottom: 4px;
        position: relative;
    }

    /* خط رفيع إضافي أسفل الفاصل الرئيسي - توثيق يحاكي القوائم المالية الرسمية */
    .invoice-header::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: -6px;
        height: 1px;
        background: #e2e8f0;
    }

    .company-info {
        width: 33%;
        text-align: center;
    }

    .company-info h5 {
        font-size: 19px;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 6px;
    }

    .company-info p {
        font-size: 13.5px;
        color: #718096;
        margin: 3px 0;
    }

    .company-logo img {
        width: 100px;
        height: 90px;
        object-fit: contain;
    }

    /* ترويسة التقرير المالي */
    .report-title-box {
        text-align: center;
        margin: 30px 0 26px;
    }

    .report-title-box .eyebrow {
        display: block;
        font-size: 12.5px;
        letter-spacing: 2px;
        color: #2b6cb0;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 9px;
    }

    .report-title-box h3 {
        font-size: 25px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 4px;
    }

    .report-title-box .subtitle-en {
        font-size: 14.5px;
        color: #718096;
        font-style: italic;
        letter-spacing: 0.5px;
    }

    .report-period {
        display: inline-block;
        background: #ebf8ff;
        color: #2b6cb0;
        padding: 7px 20px;
        border-radius: 20px;
        font-size: 14.5px;
        font-weight: 600;
        margin-top: 14px;
        border: 1px solid #bee3f8;
    }

    /* جدول التقرير المالي الاحترافي */
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .report-table th {
        background-color: #2b6cb0;
        color: #ffffff;
        font-size: 14.5px;
        font-weight: 600;
        padding: 15px 18px;
        text-align: right;
    }

    .report-table th:last-child {
        text-align: left;
    }

    .report-table td {
        padding: 16px 18px;
        font-size: 15px;
        border-bottom: 1px solid #edf2f7;
        color: #2d3748;
        text-align: right;
    }

    .report-table td strong {
        font-weight: 600;
    }

    .report-table td small {
        font-size: 12px;
    }

    .report-table td:last-child {
        text-align: left;
        font-weight: 600;
        font-family: 'Courier New', Courier, monospace;
        font-size: 15.5px;
        direction: ltr;
    }

    .report-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    /* صف الإجمالي بشكل يحاكي القوائم المالية المعتمدة: خط علوي + خط سفلي مزدوج */
    .report-table tbody tr.total-row {
        background-color: #edf2f7;
        font-weight: 700;
        color: #1a202c;
        border-top: 2px solid #cbd5e0;
    }

    .report-table tbody tr.total-row td {
        border-bottom: 3px double #2b6cb0;
        font-size: 17px;
        color: #2b6cb0;
        padding-top: 18px;
        padding-bottom: 18px;
    }

    /* التوقيعات السفلية */
    .sign-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-top: 55px;
        font-size: 14px;
        font-weight: 500;
        color: #4a5568;
        text-align: center;
    }
    .sign-row .sign-box {
        flex: 1;
        border-top: 1px solid #cbd5e0;
        padding-top: 10px;
    }

    .report-footnote {
        margin-top: 26px;
        padding-top: 12px;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #a0aec0;
    }
</style>
@endsection

@section('title')
قائمة التغير في حقوق الملكية
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between no-print"></div>
@endsection

@section('content')
<div class="container-fluid" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-11">

            <!-- بطاقة التقرير مع معرف الطباعة -->
            <div class="report-card" id="print-report">

                <!-- زر الطباعة ووقت التصدير (يختفيان تماماً عند الطباعة بفضل no-print) -->
                <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                    <span style="font-size: 13px; color: #718096;">
                        <i class="fas fa-info-circle ml-1"></i> تاريخ التصدير: {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                    </span>
                    <button class="btn btn-danger px-4 py-2 font-weight-bold shadow-sm" onclick="printReport()" style="border-radius: 6px; background-color: #e53e3e; border: none;">
                        طباعة التقرير <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <!-- ترويسة الشركة الثلاثية -->
                <div class="invoice-header">
                    <div class="company-info">
                        <h5>{{ Namear ?? '' }}</h5>
                        <p>{{ describtionar ?? '' }}</p>
                        <p>{{ STar ?? '' }}</p>
                        <p>{{ Taxar ?? '' }}</p>
                    </div>

                    <div class="company-logo">
                        <?php $logo = camplogo ?? 'default.png'; ?>
                        <a href="https://ebdeasoft.com/"><img src="{{ asset('assets/img/brand').'/'.$logo }}" alt="logo"></a>
                    </div>

                    <div class="company-info">
                        <h5>{{ Nameen ?? '' }}</h5>
                        <p>{{ describtionen ?? '' }}</p>
                        <p>{{ STen ?? '' }}</p>
                        <p>{{ Taxen ?? '' }}</p>
                    </div>
                </div>

                <!-- عنوان التقرير والفترة -->
                <div class="report-title-box">
                    <span class="eyebrow">قائمة مالية معتمدة</span>
                    <h3>قائمة التغير في حقوق الملكية</h3>
                    <span class="subtitle-en">Statement of Changes in Equity</span>
                    <div>
                        <span class="report-period">
                            للفترة من {{ $fromDate }} إلى {{ $toDate }}
                        </span>
                    </div>
                </div>

                <!-- جدول عرض البيانات المالية الاحترافي -->
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>البيان (Description)</th>
                            <th>المبلغ (Amount)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>رصيد حقوق الملكية في بداية الفترة</strong>
                                <br><small style="color: #718096; font-weight: normal;">Beginning Balance</small>
                            </td>
                            <td>{{ number_format($beginningCapital, 2) }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>إضافات رأس المال خلال الفترة</strong>
                                <br><small style="color: #718096; font-weight: normal;">Capital Additions</small>
                            </td>
                            <td style="color: #38a169;">+ {{ number_format($capitalAdditions, 2) }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>المسحوبات الشخصية خلال الفترة</strong>
                                <br><small style="color: #718096; font-weight: normal;">Drawings</small>
                            </td>
                            <td style="color: #e53e3e;">( {{ number_format($drawings, 2) }} )</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>صافي الدخل / الربح للفترة</strong>
                                <br><small style="color: #718096; font-weight: normal;">Net Income for the Period</small>
                            </td>
                            <td style="color: {{ $netIncome >= 0 ? '#38a169' : '#e53e3e' }};">
                                {{ $netIncome >= 0 ? '+ ' . number_format($netIncome, 2) : number_format($netIncome, 2) }}
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td>
                                رصيد حقوق الملكية في نهاية الفترة
                                <br><small style="color: #4a5568; font-weight: normal; font-size: 11px;">Ending Balance</small>
                            </td>
                            <td>{{ number_format($endingCapital, 2) }} ر.س</td>
                        </tr>
                    </tbody>
                </table>

                <!-- التوقيعات السفلية -->
                <div class="sign-row">
                    <div class="sign-box">المحاسب</div>
                    <div class="sign-box">المدير المالي</div>
                    <div class="sign-box">اعتماد الإدارة</div>
                </div>

                <div class="report-footnote">
                    <span>{{ Namear ?? '' }}</span>
                    <span>مستند مُصدر آليًا من النظام</span>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    /*
     * بنعتمد هنا على نسخ محتوى التقرير فعليًا لصفحة مستقلة وقت الطباعة،
     * بدل الاعتماد الكامل على CSS، عشان نضمن ظهور التقرير بغض النظر
     * عن شكل حاويات القالب الرئيسي (position / overflow / height... إلخ)
     * واللي ممكن تسبب ظهور صفحة بيضاء عند الطباعة المباشرة.
     */
    function printReport() {
        var original = document.getElementById('print-report');
        if (!original) { window.print(); return; }

        // ننسخ التقرير وبعدين نشيل أي عنصر بكلاس no-print فعليًا من النسخة
        // (مش بس نخفيه بالـ CSS) عشان نضمن اختفاءه حتى لو فيه تعارض
        // مع أنماط القالب الأساسي (theme) اللي بتتنسخ مع باقي الـ styles.
        var clone = original.cloneNode(true);
        clone.querySelectorAll('.no-print').forEach(function (el) {
            el.remove();
        });

        var printWindow = window.open('', '_blank');
        if (!printWindow) {
            alert('من فضلك اسمح بفتح النوافذ المنبثقة (Popups) لهذا الموقع عشان تقدر تطبع التقرير.');
            return;
        }

        var styles = '';
        document.querySelectorAll('style, link[rel="stylesheet"]').forEach(function (el) {
            styles += el.outerHTML;
        });

        printWindow.document.open();
        printWindow.document.write(
            '<!DOCTYPE html><html lang="ar" dir="rtl"><head><meta charset="utf-8">' +
            styles +
            '<style>body{background:#fff;margin:0;padding:20px;}</style>' +
            '</head><body>' + clone.outerHTML + '</body></html>'
        );
        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.onafterprint = function () {
                printWindow.close();
            };
        };
    }
</script>
@endsection