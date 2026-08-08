@extends('layouts.master')
@section('css')
<style>
    @media print {
        @page { 
            size: A4 landscape;
            margin: 10mm;
        }
        
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* إخفاء الأزرار والعناصر غير المرغوبة عند الطباعة */
        #print_Button, .no-print {
            display: none !important;
            visibility: hidden !important;
        }

        /* جعل محتوى الطباعة يملأ الشاشة بالعرض بدون ظلال أو حدود خارجية مزعجة */
        #print, .card-invoice {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: #ffffff !important;
        }

        /* تنسيق الجداول لتناسب الورقة العرضية بوضوح وتجنب تقطيع الخطوط */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 11px !important;
        }

        th, td {
            border: 1px solid #94a3b8 !important;
            padding: 5px !important;
            text-align: center !important;
        }

        thead th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }

        tfoot td {
            background-color: #e2e8f0 !important;
            font-weight: bold !important;
        }

        /* منع انقسام صفوف الجدول بطريقة سيئة بين الصفحات */
        tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }

    /* التنسيقات العامة للشاشة العادية */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        color: #333;
    }

    .card-invoice {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        padding: 25px;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .billed-from {
        width: 33%;
    }

    .logo-1 {
        width: 110px;
        height: 70px;
        object-fit: contain;
    }

    .table-custom th {
        background-color: #f8fafc !important;
        color: #1e293b !important;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        font-size: 12.5px;
    }

    .table-custom td {
        text-align: center;
        vertical-align: middle;
        font-size: 12px;
    }
</style>
@endsection

@section('title')
{{__('home.print')}}
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
            <div class="card card-invoice">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center mb-4 no-print">
                    <button class="btn btn-danger print-style px-4 py-2 font-weight-bold" id="print_Button" onclick="printDiv()" style="border-radius: 6px;">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body p-0">
                    <!-- رأس الفاتورة (بيانات الشركة بالإنجليزية يسار، الشعار بالمنتصف، وبيانات الشركة بالعربية يمين) -->
                    <div class="invoice-header">
                        <!-- بيانات الشركة بالإنجليزية (يسار) -->
                        <div class="billed-from text-left" dir="ltr">
                            <h4 style="font-size: 18px; font-weight: bold; color: #1e293b;">{{ Nameen ?? '' }}</h4>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ describtionen ?? '' }}</p>
                            <span class="d-block text-muted" style="font-size: 12px;">{{ STen ?? '' }}</span>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ Taxen ?? '' }}</p>
                        </div>

                        <!-- الشعار (منتصف) -->
                        <div class="billed-from text-center">
                            @php $logo = camplogo ?? ''; @endphp
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo">
                            </a>
                        </div>

                        <!-- بيانات الشركة بالعربية (يمين) -->
                        <div class="billed-from text-right" dir="rtl">
                            <h4 style="font-size: 18px; font-weight: bold; color: #1e293b;">{{ Namear ?? '' }}</h4>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ describtionar ?? '' }}</p>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ STar ?? '' }}</p>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ Taxar ?? '' }}</p>
                        </div>
                    </div><!-- invoice-header -->
<!-- عرض تاريخ الإصدار وبداية ونهاية الفترة -->
<!-- عرض تاريخ الإصدار وبداية ونهاية الفترة -->
<div class="row mb-4 text-center" style="background-color: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e9ecef; font-size: 15px;">
    <div class="col-md-4">
        <strong>تاريخ الإصدار:</strong> {{ date('Y-m-d H:i') }}
    </div>
    <div class="col-md-4">
        {{-- هنا نستخدم المتغير القادم من الـ Controller، وإذا كان فارغاً نعرض بداية السنة الحالية --}}
        <strong>بداية الفترة:</strong> {{ $start_at ?? date('Y-01-01') }}
    </div>
    <div class="col-md-4">
        <strong>نهاية الفترة:</strong> {{ $endAt  }}
    </div>
</div>
                    @if(isset($products))
                    <div class="card-body px-0">
                        <!-- عنوان التقرير -->
                        <div class="text-center mb-3">
                            <h4 class="font-weight-bold text-dark" style="font-size: 16px; letter-spacing: 0.5px;">
                                {{ __('report.stockquantity') }}
                            </h4>
                            <hr class="w-25 mx-auto" style="border-top: 2px solid #3b82f6; margin-top: 5px;">
                        </div>

                        @php
                            $totalprice = 0;
                            $i = 0;
                            $totalstock = 0;
                            $opingstock = 0;
                            $Totalsalescount = 0;
                            $Totalreturnsalescount = 0;
                            $Totalpurchasecount = 0;
                            $Totalpurchasereturncount = 0;
                            $totalincreasestock = 0;
                            $totaldecreasestock = 0;
                            $totaldamagestock = 0;
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0 align-middle table-custom">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th scope="col" style="width: 3%;">#</th>
                                        <th scope="col" style="width: 8%;">{{ __('home.productNo') }}</th>
                                        <th scope="col" style="width: 14%;">{{ __('home.productname') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.oping') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.purchases') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.purchase_return') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.sales') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.salesـreturned') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.productdecrease') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.productincrease') }}</th>
                                        <th scope="col" style="width: 6%;">{{ __('home.quentitydamagereport') }}</th>
                                        <th scope="col" style="width: 7%;">{{ __('home.stock') }}</th>
                                        <th scope="col" style="width: 8%;">{{ __('home.avarge') }}</th>
                                        <th scope="col" style="width: 12%;">{{ __('home.total') }}</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                   @foreach ($products as $product)
    @php
        $i++;
        $salescount = 0;
        $returnsalescount = 0;
        $purchasecount = 0;
        $purchasereturncount = 0;
        $stockincrease = 0;
        $stockdecrease = 0;
        $damageproduct = 0;
$startAt =  date('Y-01-01'); // افتراضياً بداية السنة الحالية
        // بناء استعلامات العلاقات مع تطبيق شرط التاريخ ($endAt) إذا تم تحديده
        $salesQuery = App\Models\sales::where('product_id', $product->id)->where('save', 1)->whereDate('created_at', '>=', $startAt);
        $stockUpdateQuery = App\Models\stock_update::where('product_id', $product->id)->whereDate('created_at', '>=', $startAt);
        $productsDamageQuery = App\Models\ProductsDamage::where('product_id', $product->id)->whereDate('created_at', '>=', $startAt);
        $returnSalesQuery = App\Models\return_sales::where('product_id', $product->id)->whereDate('created_at', '>=', $startAt);
        $purchaseQuery = App\Models\orderDetails::where('product_id', $product->id)->where('save', 1)->whereDate('created_at', '>=', $startAt);

        // إذا أدخل المستخدم تاريخ نهاية، نقوم بالفلترة حتى ذلك التاريخ
        if (!empty($endAt)) {
            $salesQuery->whereDate('created_at', '<=', $endAt);
            $stockUpdateQuery->whereDate('created_at', '<=', $endAt);
            $productsDamageQuery->whereDate('created_at', '<=', $endAt);
            $returnSalesQuery->whereDate('created_at', '<=', $endAt);
            $purchaseQuery->whereDate('created_at', '<=', $endAt);
        }

        $sales = $salesQuery->get();
        $stock_update = $stockUpdateQuery->get();
        $ProductsDamage = $productsDamageQuery->get();
        $returnsales = $returnSalesQuery->get();
        $purchase = $purchaseQuery->get();

        // تجميع الحركات
        foreach($stock_update as $productchange){
            $stockincrease += $productchange->productincrease;
            $totalincreasestock += $productchange->productincrease;
            $stockdecrease += $productchange->productdecrease;
            $totaldecreasestock += $productchange->productdecrease;
        }
        foreach($ProductsDamage as $productdamge){
            $damageproduct += $productdamge->damage_quantity;
            $totaldamagestock += $productdamge->damage_quantity;
        }
        foreach($sales as $sale){
            $salescount += $sale->quantity;
            $Totalsalescount += $sale->quantity;
        }
        foreach($returnsales as $sale){
            $returnsalescount += $sale->return_quantity;
            $Totalreturnsalescount += $sale->return_quantity;
        }
        foreach($purchase as $sale){
            $purchasecount += $sale->numberofpice;
            $purchasereturncount += $sale->returns_purchase;
            $Totalpurchasecount += $sale->numberofpice;
            $Totalpurchasereturncount += $sale->returns_purchase;
        }

        $totalprice += $product->purchasingـprice * $product->numberofpice;
        $totalstock += $product->numberofpice;
        $opingstock += $product->opening_blance;
    @endphp

    <!-- صف العرض في الجدول -->
    <tr class="text-center">
        <td class="font-weight-bold text-muted">{{ $i }}</td>
        <td dir="ltr" class="font-weight-bold text-primary">{{ $product->Product_Code }}</td>
        <td class="text-right font-weight-bold text-dark px-2">{{ $product->product_name }}</td>
        <td>{{ $product->opening_blance }}</td>
        <td class="text-info font-weight-bold">{{ $purchasecount }}</td>
        <td>{{ $purchasereturncount }}</td>
        <td class="text-success font-weight-bold">{{ $salescount }}</td>
        <td>{{ $returnsalescount }}</td>
        <td class="text-danger">{{ $stockdecrease }}</td>
        <td class="text-success">{{ $stockincrease }}</td>
        <td class="text-danger font-weight-bold">{{ $damageproduct }}</td>
        <td class="font-weight-bold">{{ $product->numberofpice }}</td>
        <td>{{ number_format($product->purchasingـprice, 2) }}</td>
        <td class="font-weight-bold text-dark">{{ number_format($product->numberofpice * $product->purchasingـprice, 2) }}</td>
    </tr>
@endforeach
                                </tbody>

                                <!-- صف الإجماليات النهائية -->
                                <tfoot class="table-secondary font-weight-bold text-center">
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold text-dark py-2">{{ __('home.total') }}</td>
                                        <td>{{ $opingstock }}</td>
                                        <td>{{ $Totalpurchasecount }}</td>
                                        <td>{{ $Totalpurchasereturncount }}</td>
                                        <td>{{ $Totalsalescount }}</td>
                                        <td>{{ $Totalreturnsalescount }}</td>
                                        <td>{{ $totaldecreasestock }}</td>
                                        <td>{{ $totalincreasestock }}</td>
                                        <td>{{ $totaldamagestock }}</td>
                                        <td>{{ $totalstock }}</td>
                                        <td>-</td>
                                        <td class="text-success font-weight-bold">{{ number_format($totalprice, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- جدول ملخص قيمة المخزون -->
                    <div class="table-responsive my-3" style="width: 40%;">
                        <table class="table table-bordered table-striped text-center mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('report.totalprice') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __('home.valuewithouttax') }}</td>
                                    <td class="font-weight-bold text-success">{{ number_format($totalprice, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr class="mg-b-30">

                </div><!-- card-body -->
            </div><!-- card card-invoice -->
        </div><!-- main-content-body-invoice -->
    </div><!-- COL-END -->
</div>
<!-- row closed -->
@endsection

@section('js')
<!-- Internal Chart.bundle js -->
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