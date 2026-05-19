@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
    }
</style>
@endsection
@section('title')
{{ __('home.profit-loss-report') }}
@stop
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h5 style="color: white" class="mt-1">
                    معاينة طباعة الفاتورة</h5>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    <!-- row -->
    <div class="row row-sm">
        <div style="padding-left: 0;padding-right:0" class="col-md-12 col-xl-12">

            </h5>
            <div class="col-md-12 col-xl-12">
                <div class=" main-content-body-invoice" id="print">


                    <div class="card card-invoice p-3 pt-4">

                           <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

                        <div class="card-body pt-3">
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
                            <div class="row mg-t-12">
                                <br>
                                <br>
                                <br>

                            </div>





                            <?php
                            $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                            $banktransfertotal=0;
                            ?>

<center>

      <h6 style="color: black" class="invoice-title">{{ __('home.profit-loss-report') }}</h6>


</center>
<br>

{{-- رسالة تنبيه تظهر فقط إذا كانت البيانات أكبر من 50 --}}
    <div class="card-body pt-3">

    {{-- رسالة تنبيه تظهر فقط إذا كانت البيانات أكبر من 50 --}}
 @if(count($data) > 50)
        {{-- حالة البيانات الضخمة: يظهر التنبيه فقط وبداخله زر التحميل --}}
        <div class="alert alert-warning text-center p-5 mb-4" style="border: 2px dashed #ffc107; background-color: #fff3cd;">
            <i class="fas fa-database fa-3x mb-3" style="color: #856404;"></i>
            <h3 class="font-weight-bold" style="color: #856404;">بيانات ضخمة ({{ count($data) }} سجل)</h3>
            <p style="font-size: 18px; color: #856404;">
                للحفاظ على أداء المتصفح، تم إخفاء العرض المباشر. يرجى استخدام زر الإكسيل لتحميل التقرير بالكامل.
            </p>
            {{-- هذا الزر يقوم بتشغيل زر الـ exportBtn المخفي --}}
            <button class="btn btn-success btn-lg shadow" onclick="document.getElementById('exportBtn').click()">
                <i class="fas fa-file-excel ml-1"></i> تحميل ملف Excel الآن
            </button>
        </div>

        {{-- زر الإكسيل الأساسي نجعله مخفياً في حالة البيانات الضخمة لأننا استدعيناه داخل التنبيه --}}
        <div style="display: none;">
            <button id="exportBtn"></button>
        </div>
    @else
        {{-- الحالة العادية: زر الطباعة وزر التصدير يظهران معاً --}}
        <div class="d-flex justify-content-center mb-4 no-print">
            <button class="btn btn-success btn-lg px-4" id="exportBtn">
                {{ __('تصدير التقرير إلى إكسيل') }} <i class="fas fa-file-excel ml-1"></i>
            </button>
        </div>
    @endif

    {{-- الجدول: يختفي من العرض إذا كان > 50 لكنه يظل موجوداً في الكود عشان زر الاكسيل يشوفه --}}
    <div class="table-responsive mg-t-20" id="tableContainer" style="{{ count($data) > 50 ? 'display: none;' : '' }}">
        <table id="profitTable" class="table table-bordered text-center" style="width: 100%; border-collapse: collapse; border: 1px solid #000;" dir="rtl">
    <thead style="background-color: #f8f9fa;">
        <tr data-fill-color="f8f9fa" data-font-weight="bold">
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('رقم المنتج') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('اسم المنتج') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('الكمية المباعة') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('المرتجع') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('صافي الكمية') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('إجمالي المبيعات') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('اجمالي الخصومات') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #ebf5ff;">{{ __('الصافي') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('متوسط التكلفة') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #f8f9fa;">{{ __('إجمالي التكلفة') }}</th>
            <th style="border: 1px solid #000; padding: 10px; background-color: #d4edda;">{{ __('صافي الربح') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px;">{{ $row->Product_Code }}</td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px;">{{ $row->product_name }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->total_sold, 2) }}</td>
            <td style="border: 1px solid #000; color: #ff0000;">{{ number_format($row->total_returned, 2) }}</td>
            <td style="border: 1px solid #000; font-weight: bold;">{{ number_format($row->net_quantity, 2) }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->gross_sales, 2) }}</td>
            <td style="border: 1px solid #000; color: #ff9900;">{{ number_format($row->total_discounts, 2) }}</td>
            <td style="border: 1px solid #000; background-color: #f0f8ff; font-weight: bold;">{{ number_format($row->net_sales, 2) }}</td>
            <td style="border: 1px solid #000; background-color: #f0f8ff; font-weight: bold;">{{ number_format($row->average_cost, 2) }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->total_cost, 2) }}</td>
            <td style="border: 1px solid #000; background-color: #e2fbe5; font-weight: bold; color: #155724;">{{ number_format($row->net_profit, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot style="font-weight: bold;">
        <tr data-fill-color="343a40" data-font-color="ffffff">
            <td colspan="4" style="border: 1px solid #000; background-color: #343a40; color: #ffffff; text-align: center;">{{ __('الإجمــــــــــــالي الكلي') }}</td>
                    <td style="border: 1px solid #000; background-color: #343a40; color: #ffffff;">-</td>
    <td style="border: 1px solid #000; background-color: #343a40; color: #ffffff;">{{ number_format($data->sum('gross_sales'), 2) }}</td>
            <td style="border: 1px solid #000; background-color: #343a40; color: #ff9900;">{{ number_format($data->sum('total_discounts'), 2) }}</td>
            <td style="border: 1px solid #000; background-color: #007bff; color: #ffffff;">{{ number_format($data->sum('net_sales'), 2) }}</td>
            <td style="border: 1px solid #000; background-color: #343a40; color: #ffffff;">-</td>
            <td style="border: 1px solid #000; background-color: #6c757d; color: #ffffff;">{{ number_format($data->sum('total_cost'), 2) }}</td>
            <td style="border: 1px solid #000; background-color: #28a745; color: #ffffff;">{{ number_format($data->sum('net_profit'), 2) }}</td>
        </tr>
    </tfoot>
</table>
    </div>


</div>

        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<script>
    document.getElementById("exportBtn").addEventListener("click", function () {
        let table = document.getElementById("profitTable");

        TableToExcel.convert(table, {
            name: "Profit_Loss_Report.xlsx",
            sheet: { name: "Report" }
        });
    });
</script>
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
