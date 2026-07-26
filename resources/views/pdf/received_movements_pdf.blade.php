<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الحركات الواردة</title>
    <style>
        body {
            font-family: 'Cairo', 'Tahoma', sans-serif;
            font-size: 13px;
            color: #333;
            direction: rtl;
            text-align: right;
            margin: 20px;
            background: #fff;
        }

        h2 {
            text-align: center;
            color: #1F4E78;
            margin-bottom: 20px;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            direction: rtl;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: right;
        }

        th {
            background-color: #1F4E78;
            color: #ffffff;
            font-size: 13px;
            text-align: center;
        }

        .movement-row {
            background-color: #D9E1F2;
            font-weight: bold;
        }

        .item-row {
            background-color: #F9FBFD;
            color: #555;
        }

        .text-center {
            text-align: center;
        }

        /* تنسيق الطباعة لضبط الصفحة أفقياً تلقائياً */
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()"> <!-- فتح نافذة الطباعة / الحفظ تلقائياً عند فتح الصفحة -->

    <h2>تقرير الحركات الواردة وتفاصيل المنتجات</h2>

    <table>
        <thead>
            <tr>
                <th>رقم الحركة</th>
                <th>الفرع المحول منه (المصدر)</th>
                <th>الفرع المحول إليه (المستقبل)</th>
                <th>المستخدم المسؤول</th>
                <th>الحالة</th>
                <th>تاريخ الإنشاء</th>
                <th>بيان المنتج / الحركة</th>
                <th>الكمية</th>
                <th>سعر القطعة</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
                <!-- صف الحركة الرئيسي -->
                <tr class="movement-row">
                    <td class="text-center">{{ $movement->id }}</td>
                    <td>{{ optional($movement->branchfrom)->name ?? '---' }}</td>
                    <td>{{ optional($movement->branchto)->name ?? '---' }}</td>
                    <td>{{ optional($movement->userfrom)->name ?? '---' }}</td>
                    <td class="text-center">{{ $movement->status }}</td>
                    <td class="text-center">{{ $movement->created_at ? $movement->created_at->format('Y-m-d H:i') : '---' }}</td>
                    <td>إجمالي تكلفة الحركة الكلية:</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td>
                        {{ number_format($movement->Totalcost ?? $movement->items->sum(fn($i) => $i->quantity * $i->cost_per_each_withoud_tax), 2) }}
                    </td>
                </tr>

                <!-- صفوف المنتجات التابعة -->
                @if($movement->items && $movement->items->count() > 0)
                    @foreach($movement->items as $item)
                        <tr class="item-row">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="padding-right: 20px;">&nbsp;&nbsp;↳ {{ optional($item->product)->product_name ?? 'منتج غير محدد' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">{{ number_format($item->cost_per_each_withoud_tax, 2) }}</td>
                            <td>{{ number_format($item->quantity * $item->cost_per_each_withoud_tax, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>

</body>
</html>