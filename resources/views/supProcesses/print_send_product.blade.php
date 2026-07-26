@extends('layouts.master')

@section('css')
<style>
    /* تنسيقات خاصة بالطباعة الرسمية */
    @media print {
        #print_Button, .breadcrumb-header, .main-header, .main-sidebar, .main-footer {
            display: none !important;
        }
        body {
            background-color: #fff !important;
            color: #000 !important;
            direction: rtl !important;
            font-family: 'Cairo', 'Times New Roman', serif !important;
            -webkit-print-color-adjust: exact;
        }
        .card-invoice {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .table th, .table td {
            border: 1px solid #333 !important;
            padding: 8px !important;
            font-size: 13pt !important;
        }
    }

    /* تنسيقات الشاشة بتصميم عصري واحترافي */
    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f4f6f9;
        direction: rtl;
        text-align: right;
    }
    .card-invoice {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-top: 20px;
    }
    .invoice-header {
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 25px;
        margin-bottom: 25px;
    }
    .company-title {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
    }
    .invoice-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a202c;
        background: #f7fafc;
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }
    .table th {
        background-color: #f8fafc !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 14px;
    }
    .table td {
        color: #334155 !important;
        font-weight: 600 !important;
        font-size: 14px;
        vertical-align: middle !important;
    }
    .signature-box {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px dashed #cbd5e1;
    }
</style>
@endsection

@section('title')
معاينة طباعة المنتجات
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
<div class="row row-sm justify-content-center">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">
                    
                    <!-- رأس الفاتورة (تم ضبط الاتجاهات بدقة: عربي يمين، شعار وسط، إنجليزي يسار) -->
                    <div class="invoice-header d-flex justify-content-between align-items-center w-100" style="display: flex; justify-content: space-between; align-items: center;">
                        
                        <!-- 1. الجهة العربية (يمين) -->
                        <div style="width: 33%; text-align: right;">
                            <span class="company-title">{{ Namear ?? '' }}</span>
                            <p class="mb-1 text-muted">{{ describtionar ?? '' }}</p>
                            <p class="mb-1 text-muted">{{ STar ?? '' }}</p>
                            <p class="mb-0 text-muted">{{ Taxar ?? '' }}</p>
                        </div>

                        <!-- 2. الشعار في المنتصف -->
                        <div style="width: 33%; text-align: center;">
                            <?php $logo = camplogo ?? ''; ?>
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand') . '/' . $logo }}" class="logo-1" alt="logo" style="max-height: 75px; object-fit: contain;">
                            </a>
                        </div>

                        <!-- 3. الجهة الإنجليزية (يسار) -->
                        <div style="width: 33%; text-align: left;" dir="ltr">
                            <span class="company-title">{{ Nameen ?? '' }}</span>
                            <p class="mb-1 text-muted">{{ describtionen ?? '' }}</p>
                            <span class="d-block text-muted">{{ STen ?? '' }}</span>
                            <p class="mb-0 text-muted">{{ Taxen ?? '' }}</p>
                        </div>

                    </div>

                    <!-- عنوان المستند -->
                    <div class="text-center my-4">
                        <h3 class="invoice-title">
                            {{ __('home.send_product_from_brance') }}
                        </h3>
                    </div>

                    <!-- جدول تفاصيل الفاتورة والفروع -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-7" style="width: 60%; margin: 0 auto;">
                            <table class="table table-bordered text-center mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%;">{{ __('home.Invoice_no') }}</th>
                                        <td style="width: 60%;">{{ $data['invoice']->id ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.branch_sender') }}</th>
                                        <td>{{ $data['invoice']->branchfrom->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.employeesender') }}</th>
                                        <td>{{ $data['invoice']->userfrom->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.branch_reciver') }}</th>
                                        <td>{{ $data['invoice']->branchto->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.employeereciver') }}</th>
                                        <td>{{ $data['invoice']->userto->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.date') }}</th>
                                        <td dir="ltr">{{ $data['invoice']->created_at ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- جدول المنتجات الرئيسي -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">{{ __('home.productNo') }}</th>
                                    <th style="width: 40%;">{{ __('home.product') }}</th>
                                    <th style="width: 10%;">{{ __('home.quantity') }}</th>
                                    <th style="width: 15%;">{{ __('home.thecostProduct') }}</th>
                                    <th style="width: 15%;">{{ __('home.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0;
                                    $totalprice = 0;
                                    $totalAddedvalue = 0; 
                                    $avtSaleRateModel = App\Models\Avt::find(2);
                                    $avtSaleRate = $avtSaleRateModel ? $avtSaleRateModel->AVT : 0;
                                ?>
                                @foreach ($data['items'] as $product)
                                    <?php 
                                        $i++;
                                        $itemTotal = $product->cost_per_each_withoud_tax * $product->quantity;
                                        $totalprice += $itemTotal;
                                        $totalAddedvalue += $itemTotal * $avtSaleRate;
                                    ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td dir="ltr">{{ $product->product->Product_Code ?? '' }}</td>
                                        <td class="text-right px-3">{{ $product->product->product_name ?? '' }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{ number_format($product->cost_per_each_withoud_tax, 2) }}</td>
                                        <td>{{ number_format($itemTotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- جدول الإجماليات -->
                    <div class="row justify-content-end mb-4">
                        <div class="col-md-5" style="width: 50%; margin-left: auto;">
                            <table class="table table-bordered text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('home.the amount') }}</th>
                                        <th>{{ __('home.addedValue') }}</th>
                                        <th>{{ __('home.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($totalprice, 2) }}</td>
                                        <td>{{ number_format($totalAddedvalue, 2) }}</td>
                                        <td class="font-weight-bold text-primary">{{ number_format($totalAddedvalue + $totalprice, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- قسم التواقيع والمستلم -->
                    <div class="signature-box d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 font-weight-bold"><strong>{{ __('home.employeereciver') }} :</strong> {{ $data['invoice']->userto->name ?? '' }}</p>
                        </div>
                        <div>
                            <p class="mb-1 font-weight-bold"><strong>{{ __('home.thesignature') }} :</strong> ____________________</p>
                        </div>
                    </div>

                    <!-- زر الطباعة -->
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn btn-danger px-5 py-2 font-weight-bold shadow-sm" id="print_Button" onclick="printDiv()" style="font-size: 15px;">
                            {{ __('home.print') }}
                            <i class="mdi mdi-printer ml-1"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
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