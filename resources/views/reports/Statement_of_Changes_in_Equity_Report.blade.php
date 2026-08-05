@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
        /* إخفاء القوائم والهيدر والسايدبار عند الطباعة */
        .main-header, .main-sidebar, .main-footer, .breadcrumb-header, nav, aside, header, footer {
            display: none !important;
        }
        body * {
            visibility: hidden !important;
        }
        #print, #print * {
            visibility: visible !important;
        }
        #print {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
    }
</style>
@endsection

@section('title')
{{ __('home.Statement_of_Changes_in_Equity_Report') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- نموذج البحث (يختفي عند الطباعة) -->
<div class="card mg-b-20 no-print">
    <div class="card-header pb-0">
        <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ('changesInEquity')) }}" method="POST" role="search" autocomplete="off">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-lg-3" id="start_at">
                    <label class="parent-label" for="start_at"> {{ __('report.fromdate') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <input class="form-control parent-input fc-datepicker" value="{{ $fromDate ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                    </div>
                </div>

                <div class="col-lg-3" id="end_at">
                    <label class="parent-label" for="end_at"> {{ __('report.todate') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                        <input class="form-control parent-input fc-datepicker" name="end_at" value="{{ $toDate ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                    </div>
                </div>

                <div class="col-lg-3" id="type">
                    <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                    <select class="form-control parent-input" name="branch" required>
                        <option value="-" {{ (isset($branch) && $branch == '-') ? 'selected' : '' }}>{{ __('users.allbranchs') }}</option>
                        @foreach (App\Models\branchs::get() as $b)
                        <option value="{{ $b->id }}" {{ (isset($branch) && $branch == $b->id) ? 'selected' : '' }}> {{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div><br>
            <div class="d-flex justify-content-center mb-3">
                <button class="btn btn-success print-style p-1">
                    {{ __('home.search') }} <i class="las la-search" style="font-size:15px"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@if (isset($beginningCapital))
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">
                    
                    <!-- الهيدر الثلاثي (نفس تصميم الفاتورة) -->
                    <div class="invoice-header" style="display: flex; justify-content: space-between; width: 100%" dir="rtl">
                        <div class="billed-from" style="width:33%; text-align: center;">
                            <br>
                            <span class="tx-18 thick">{{ Namear }}</span>
                            <br>
                            <p class="tx-16 thick">{{ describtionar }}</p>
                            <p class="tx-16 thick">{{ STar }}</p>
                            <p class="tx-16 thick">{{ Taxar }}</p>
                        </div><!-- billed-from -->

                        <div class="row">
                            <?php
                            $logo = camplogo;
                            ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>
                        </div>

                        <div class="billed-from" style="width:33%; text-align: center;">
                            <br>
                            <span class="tx-18 thick">{{ Nameen }}</span>
                            <br>
                            <p class="tx-16 thick">{{ describtionen }}</p>
                            <span class="tx-16 thick">{{ STen }}</span>
                            <p class="tx-16 thick">{{ Taxen }}</p>
                        </div>
                    </div><!-- invoice-header -->
                    
                    <br>
                    <center>
                        <span>قائمة التغير في حقوق الملكية (Statement of Changes in Equity)</span>
                    </center>
                    <br>

                    <div class="col-lg-3">
                        <label style="font-size: 14px; color:#419BB2; font-weight:bold;"> {{ __('home.exportTime') }} : </label>
                        <?php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
                        ?>
                        <label style="font-size: 14px; color:#419BB2; font-weight:bold;"> {{ $currentdata }}</label>
                    </div>

                    <div style="border-radius: 10px" class="card m-3 p-3">
                        <div class="table-responsive">
                            <span style="font-size: 17px; color:#419BB2">{{ __('report.from') }} : {{ $fromDate }} </span>
                            &nbsp;&nbsp;
                            <span style="font-size: 17px; color:#419BB2">{{ __('report.to') }} : {{ $toDate }} </span>

                            <br>

                            <div class="table-responsive hoverable-table my-3">
                                <div style="border-radius: 10px" class="card m-3 p-3">
                                    <div class="table-responsive">
                                        <div class="table-responsive hoverable-table table-padding">
                                            <table class="table table-bordered table-striped table-hover" style="text-align: center;">
                                                <thead>
                                                    <tr>
                                                        <th>البيان (Description)</th>
                                                        <th>المبلغ (Amount)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="font-weight: bold;">رصيد حقوق الملكية في بداية الفترة</td>
                                                        <td>{{ number_format($beginningCapital, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;">إضافات رأس المال خلال الفترة</td>
                                                        <td style="color: green;">+ {{ number_format($capitalAdditions, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;">المسحوبات الشخصية خلال الفترة</td>
                                                        <td style="color: red;">({{ number_format($drawings, 2) }})</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;">صافي الدخل / الربح للفترة</td>
                                                        <td style="color: {{ $netIncome >= 0 ? 'green' : 'red' }};">
                                                            {{ number_format($netIncome, 2) }}
                                                        </td>
                                                    </tr>
                                                    <tr style="background-color: #f8f9fa; font-weight: bold; font-size: 15px;">
                                                        <td>رصيد حقوق الملكية في نهاية الفترة</td>
                                                        <td style="color: #419BB2;">{{ number_format($endingCapital, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr class="mg-b-40">

                                        <!-- زر الطباعة -->
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                                {{ __('home.print') }}
                                                <i class="mdi mdi-printer ml-1"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- COL-END -->
</div>
<!-- row closed -->
@endif
@endsection

@section('js')
<!--Internal Chart.bundle js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<script>
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

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