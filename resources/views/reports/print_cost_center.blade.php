@extends('layouts.master')

@section('css')
<style>
    @media print {
        #print_Button {
            display: none !important;
        }
        body {
            border: none !important;
            font-size: 12pt;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

    body {
        font-family: 'Georgia', "Times New Roman", Times, serif;
        line-height: 1.6;
        color: #333;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-bottom: 2px solid #eee;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .billed-from {
        width: 33%;
        text-align: center;
    }

    .voucher-title {
        color: #2c3e50;
        font-weight: bold;
        margin: 20px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table-custom th {
        background-color: #ecf0fa !important;
        color: #419BB2 !important;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }
</style>
@endsection

@section('title')
{{ __('home.Customer_debt_restructuring') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Customer_debt_restructuring') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.index') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice p-4">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center mb-4">
                    <button class="btn btn-danger print-style" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <div class="card-body">
                    <!-- رأس الفاتورة -->
                    <div class="invoice-header">
                        <!-- بيانات الشركة بالإنجليزية -->
                        <div class="billed-from">
                            <h4 style="font-size: 22px; font-weight: bold;">{{ $Nameen ?? '' }}</h4>
                            <p dir="ltr" class="mb-1">{{ $describtionen ?? '' }}</p>
                            <span dir="ltr" class="d-block">{{ $STen ?? '' }}</span>
                            <p dir="ltr" class="mb-0">{{ $Taxen ?? '' }}</p>
                        </div>

                        <!-- الشعار -->
                        <div class="billed-from text-center">
                            <?php
                            $logo = camplogo;
                            ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>
                        </div>

                        <!-- بيانات الشركة بالعربية -->
                        <div class="billed-from">
                            <h4 style="font-size: 22px; font-weight: bold;">{{ $Namear ?? '' }}</h4>
                            <p class="mb-1">{{ $describtionar ?? '' }}</p>
                            <p class="mb-1">{{ $STar ?? '' }}</p>
                            <p class="mb-0">{{ $Taxar ?? '' }}</p>
                        </div>
                    </div><!-- invoice-header -->

                    <!-- عنوان التقرير -->
                    <div class="text-center">
                        <h5 class="voucher-title">{{ __('home.Customer_debt_restructuring') }}</h5>
                    </div>

                    <!-- وقت التصدير -->
                    @php
                        \Carbon\Carbon::setLocale('ar');
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                    @endphp
                    <div class="table-responsive my-3">
                        <table class="table table-bordered text-center table-custom" style="max-width: 400px; margin: 0 auto;">
                            <tr>
                                <th>{{ __('home.exportTime') }}</th>
                                <td>{{ $currentdata }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- جدول بيانات أعمار الديون للعملاء -->
                    <div class="table-responsive mt-4">
                        <table class="table table-hover table-bordered table-striped text-center budgetSheet-table" style="border: 1px solid black; border-collapse: collapse !important;">
                            <thead>
                                <tr class="table-custom">
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">اسم العميل <br> Client Name</th>
                                    <th class="border-bottom-0">اخر سداد <br> Last payment</th>
                                    <th class="border-bottom-0">الرصيد <br> Balance</th>
                                    <th class="border-bottom-0">عمر الدين <br> omer al-dain</th>
                                    <th class="border-bottom-0">0 : 10</th>
                                    <th class="border-bottom-0">11 : 30</th>
                                    <th class="border-bottom-0">31 : 60</th>
                                    <th class="border-bottom-0">61 : 90</th>
                                    <th class="border-bottom-0">91 : 120</th>
                                    <th class="border-bottom-0">121 : 180</th>
                                    <th class="border-bottom-0">اكبر من 180 <br> More then 180</th>
                                    <th class="border-bottom-0">الاجمالي <br> Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($data) && count($data) > 0)
                                    @php $i = 0; @endphp
                                    @foreach ($data as $item)
                                        @php $i++; @endphp
                                        <tr>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $i }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['name'] ?? '' }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['lastdate'] ?? '-' }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['crrunt_balence'] ?? '' }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['life_creadit'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['f_0_t_10'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['f_11_t_30'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['f_31_t_60'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['f_61_t_90'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['f_91_t_120'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['f_121_t_180'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;">{{ $item['f_181_t_00'] ?? 0 }}</td>
                                            <td style="font-size: 15px; font-weight: bold;" class="text-success">
                                                {{ 
                                                    ($item['f_0_t_10'] ?? 0) + 
                                                    ($item['f_11_t_30'] ?? 0) + 
                                                    ($item['f_31_t_60'] ?? 0) + 
                                                    ($item['f_61_t_90'] ?? 0) + 
                                                    ($item['f_91_t_120'] ?? 0) + 
                                                    ($item['f_121_t_180'] ?? 0) + 
                                                    ($item['f_181_t_00'] ?? 0) 
                                                }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="13" class="text-center text-muted py-3">لا توجد بيانات متاحة</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div><!-- card-body -->
            </div><!-- card -->
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