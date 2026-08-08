<style>
    .custom-report-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Cairo', 'Tajawal', sans-serif;
        background-color: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .custom-report-table thead tr:first-child {
        background: linear-gradient(135deg, #198754 0%, #157347 100%);
        color: #ffffff;
    }

    .custom-report-table thead tr:first-child th {
        padding: 16px;
        font-size: 18px;
        letter-spacing: 0.5px;
    }

    .custom-report-table thead tr:nth-child(2) {
        background-color: #f8f9fa;
        color: #333333;
        border-bottom: 2px solid #dee2e6;
    }

    .custom-report-table th, 
    .custom-report-table td {
        padding: 12px 10px;
        text-align: center;
        font-size: 14px;
        border-bottom: 1px solid #f1f1f1;
    }

    .custom-report-table thead th {
        font-weight: 700;
        text-transform: capitalize;
    }

    .custom-report-table tbody tr {
        transition: all 0.2s ease-in-out;
    }

    .custom-report-table tbody tr:hover {
        background-color: #f4fbf7;
        transform: scale(1.001);
    }

    .custom-report-table tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }

    .custom-report-table tfoot tr {
        background: linear-gradient(135deg, #212529 0%, #343a40 100%);
        color: #ffffff;
        font-weight: bold;
        font-size: 15px;
    }

    .custom-report-table tfoot td {
        padding: 14px 10px;
        border-top: 2px solid #495057;
    }

    /* تنسيق أرقام الكود والكميات لتبدو أكثر احترافية */
    .text-code {
        font-family: monospace;
        font-weight: 600;
        color: #0d6efd;
    }

    .text-stock {
        font-weight: bold;
        color: #198754;
    }
</style>

<div class="table-responsive">
<table dir="rtl">
    <thead>
        <!-- السطر الأول: العنوان الرئيسي -->
        <tr>
            <th colspan="14" style="background-color: #157347; color: #ffffff; text-align: center; font-weight: bold; font-size: 16px; height: 40px; vertical-align: middle;">
                {{ __('report.stockquantity') }}
            </th>
        </tr>
        <!-- السطر الثاني: عناوين الأعمدة -->
        <tr style="background-color: #198754; color: #ffffff;">
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 5px;">#</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">{{ __('home.productNo') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 25px;">{{ __('home.productname') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.oping') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.purchases') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.purchase_return') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.sales') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.salesـreturned') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.productdecrease') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.productincrease') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.quentitydamagereport') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.stock') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.avarge') }}</th>
            <th style="background-color: #157347; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ __('home.total') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $index => $product)
        <tr>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $index + 1 }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3; mso-number-format:'\@';">{{ $product->Product_Code }}</td>
            <td style="text-align: right; border: 1px solid #d3d3d3; font-weight: bold;">{{ $product->product_name }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $product->opening_blance }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $product->purchasecount }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $product->purchasereturncount }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $product->salescount }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $product->returnsalescount }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3; color: #dc3545; font-weight: bold;">{{ $product->stockdecrease }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3; color: #198754; font-weight: bold;">{{ $product->stockincrease }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $product->damageproduct }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3; font-weight: bold; background-color: #e8f5e9;">{{ $product->numberofpice }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3;">{{ $product->purchasingـprice }}</td>
            <td style="text-align: center; border: 1px solid #d3d3d3; font-weight: bold;">{{ $product->numberofpice * $product->purchasingـprice }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="background-color: #212529; color: #ffffff; text-align: right; font-weight: bold; border: 1px solid #000000; height: 30px; vertical-align: middle;">{{ __('home.total') }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['opingstock'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['purchasecount'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['purchasereturncount'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['salescount'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['returnsalescount'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['stockdecrease'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['stockincrease'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['damageproduct'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['totalstock'] }}</td>
            <td style="background-color: #212529; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000;">-</td>
            <td style="background-color: #212529; color: #20c997; text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $totals['totalprice'] }}</td>
        </tr>
    </tfoot>
</table>
</div>