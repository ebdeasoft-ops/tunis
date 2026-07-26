@extends('layouts.master')

@section('css')
<style>
    /* تنسيقات خاصة بالطباعة لتكون عريضة، واضحة وخالية من الشوائب */
    @media print {
        #print_Button, .breadcrumb-header, .main-header, .main-sidebar, .main-footer {
            display: none !important;
        }
        body {
            background-color: #fff !important;
            color: #000 !important;
            direction: rtl !important;
            font-family: 'Cairo', 'Times New Roman', Georgia, serif !important;
            -webkit-print-color-adjust: exact;
        }
        .card-invoice {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #000 !important; /* حدود سوداء وواضحة جداً للطباعة */
            padding: 10px !important;
            text-align: center !important;
            font-weight: bold !important; /* تغميق كل النصوص داخل الجداول */
            font-size: 14pt !important;
        }
        th {
            background-color: #e2e8f0 !important;
        }
    }

    /* تنسيقات الشاشة العادية بخطوط تقيلة وواضحة */
    body {
        font-family: 'Cairo', 'Times New Roman', Georgia, serif;
        background-color: #f8f9fa;
        direction: rtl;
        text-align: right;
        font-weight: 600; /* جعل خط الصفحة بالكامل عريض وثقيل */
    }
    .card-invoice {
        background: #fff;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-top: 20px;
    }
    .invoice-header {
        border-bottom: 3px solid #cbd5e1;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .heavy-text {
        font-weight: 800 !important;
        color: #000 !important;
    }
    .table th {
        background-color: #f1f5f9;
        font-weight: 800;
        color: #000;
        font-size: 15px;
    }
    .table td {
        font-weight: 700;
        color: #111;
        font-size: 15px;
    }
</style>
@endsection

@section('title')
    {{ __('home.recive_product_from_other_branch_other') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
</div>
@endsection

@section('content')
<div class="row row-sm justify-content-center">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">
                    
                    <!-- رأس الفاتورة (البيانات والشعار) -->
                    <div class="invoice-header d-flex justify-content-between align-items-center w-100" style="display: flex; justify-content: space-between; align-items: center;">
                        
                        <!-- الجهة اليمنى (العربية) -->
                        <div class="billed-from text-right" style="width: 33%; text-align: right;">
                            <h3 class="heavy-text mb-2" style="font-size: 22px;">{{ Namear ?? '' }}</h3>
                            <p class="heavy-text mb-1" style="font-size: 14px;">{{ describtionar ?? '' }}</p>
                            <span class="d-block heavy-text mb-1" style="font-size: 14px;">{{ STar ?? '' }}</span>
                            <p class="heavy-text mb-0" style="font-size: 14px;">{{ Taxar ?? '' }}</p>
                        </div>

                        <!-- الشعار في المنتصف -->
                        <div class="invoice-logo text-center" style="width: 33%; text-align: center;">
                            <?php $logo = camplogo ?? ''; ?>
                            <a href="https://ebdeasoft.com/">
                                <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1 img-fluid" alt="logo" style="max-height: 80px; object-fit: contain;">
                            </a>
                        </div>

                        <!-- بيانات الجهة اليسرى (الإنجليزية) -->
                        <div class="billed-from text-left" style="width: 33%; text-align: left;">
                            <h3 class="heavy-text mb-2" style="font-size: 22px;" dir="ltr">{{ Nameen ?? '' }}</h3>
                            <p dir="ltr" class="heavy-text mb-1" style="font-size: 14px;">{{ describtionen ?? '' }}</p>
                            <span dir="ltr" class="d-block heavy-text mb-1" style="font-size: 14px;">{{ STen ?? '' }}</span>
                            <p dir="ltr" class="heavy-text mb-0" style="font-size: 14px;">{{ Taxen ?? '' }}</p>
                        </div>

                    </div>

                    <!-- عنوان المستند -->
                    <div class="text-center my-4">
                        <h2 class="heavy-text text-primary" style="font-size: 26px; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 5px;">
                            {{ __('home.recive_product_from_brance') }}
                        </h2>
                    </div>

                    <!-- جدول تفاصيل الفروع والحركة (خطوط ثقيلة وواضحة) -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-8" style="width: 75%; margin: 0 auto;">
                            <table class="table table-bordered text-center mb-0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <th style="width: 50%;">{{ __('home.branch_sender') }}</th>
                                        <td style="width: 50%;" class="heavy-text">{{ $data['invoice']->branchfrom->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.employeesender') }}</th>
                                        <td class="heavy-text">{{ $data['invoice']->userfrom->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.branch_reciver') }}</th>
                                        <td class="heavy-text">{{ $data['invoice']->branchto->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.employeereciver') }}</th>
                                        <td class="heavy-text">{{ $data['invoice']->userto->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('home.date') }}</th>
                                        <td class="heavy-text" dir="ltr">{{ $data['invoice']->created_at ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- جدول المنتجات -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped text-center" style="width: 100%;">
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
                                    $avtSaleRate = App\Models\Avt::find(2);
                                    $taxRate = $avtSaleRate ? $avtSaleRate->AVT : 0;
                                ?>
                                @foreach ($data['items'] as $product)
                                    <?php 
                                        $i++;
                                        $rowTotal = $product->cost_per_each_withoud_tax * $product->quantity;
                                        $totalprice += $rowTotal;
                                        $totalAddedvalue += $rowTotal * $taxRate;
                                    ?>
                                    <tr>
                                        <td class="heavy-text">{{ $i }}</td>
                                        <td class="heavy-text" dir="ltr">{{ optional($product->product)->Product_Code }}</td>
                                        <td class="heavy-text text-right px-3">{{ optional($product->product)->product_name }}</td>
                                        <td class="heavy-text">{{ $product->quantity }}</td>
                                        <td class="heavy-text">{{ number_format($product->cost_per_each_withoud_tax, 2) }}</td>
                                        <td class="heavy-text">{{ number_format($rowTotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- جدول الإجماليات -->
                    <div class="row justify-content-end mb-4">
                        <div class="col-md-6" style="width: 55%; margin-left: auto;">
                            <table class="table table-bordered text-center mb-0" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>{{ __('home.the amount') }}</th>
                                        <th>{{ __('home.addedValue') }}</th>
                                        <th>{{ __('home.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="heavy-text">{{ number_format($totalprice, 2) }}</td>
                                        <td class="heavy-text">{{ number_format($totalAddedvalue, 2) }}</td>
                                        <td class="heavy-text" style="color: #0d6efd !important;">{{ number_format($totalprice + $totalAddedvalue, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- قسم التوقيع والموظف المستلم -->
                    <div class="signature-section d-flex justify-content-between align-items-center mt-5" style="display: flex; justify-content: space-between; margin-top: 50px;">
                        <div style="text-align: right;">
                            <p class="heavy-text mb-1" style="font-size: 16px;"><strong>{{ __('home.employeereciver') }} :</strong> {{ optional($data['invoice']->userto)->name ?? '' }}</p>
                        </div>
                        <div style="text-align: left;">
                            <p class="heavy-text mb-1" style="font-size: 16px;"><strong>{{ __('home.thesignature') }} :</strong> _________________________</p>
                        </div>
                    </div>

                    <!-- زر الطباعة (يختفي وقت الطباعة) -->
                    <div class="d-flex justify-content-center mt-5">
                        <button class="btn btn-danger px-5 py-3 font-weight-bold shadow-sm" id="print_Button" onclick="window.print()" style="font-size: 16px;">
                            {{ App::getLocale() == 'ar' ? 'طباعة المستند' : 'Print Document' }}
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
@endsection