@extends('layouts.master')

@section('css')
<style>
    /* تنسيقات الطباعة الصارمة لإخفاء القوائم والهيدر وعرض التقرير لوحده */
    @media print {
        body, html {
            background-color: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        /* إخفاء الهيدر، السايدبار، والفوتر الخاص بالقالب الرئيسي بالكامل */
        .main-header, .main-sidebar, .main-footer, .breadcrumb-header, .no-print, nav, aside, header, footer {
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
            box-shadow: none !important;
            border: none !important;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
        background-color: #f8f9fa;
    }

    .card-custom {
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        border: none;
    }
</style>
@endsection

@section('title')
{{ __('home.Statement_of_Changes_in_Equity_Report') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between no-print">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Statement_of_Changes_in_Equity_Report') }}</h4>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<!-- نموذج البحث (يختفي عند الطباعة) -->
<div class="card card-custom mg-b-20 no-print">
    <div class="card-body">
        <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ('changesInEquity')) }}" method="POST" role="search" autocomplete="off">
            {{ csrf_field() }}
            <div class="row justify-content-center">
                <div class="col-lg-4" id="start_at">
                    <label class="parent-label font-weight-bold" for="start_at"> {{ __('report.fromdate') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <input class="form-control parent-input fc-datepicker" value="{{ $fromDate ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                    </div>
                </div>

                <div class="col-lg-4" id="end_at">
                    <label class="parent-label font-weight-bold" for="end_at"> {{ __('report.todate') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <input class="form-control parent-input fc-datepicker" name="end_at" value="{{ $toDate ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-success px-4 py-2 shadow-sm">
                    {{ __('home.search') }} <i class="las la-search ml-1" style="font-size:15px"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- منطقة التقرير القابلة للطباعة -->
<div id="print">
    <div class="card card-custom">
        <div class="card-body">
            <div class="text-center py-4">
                <h4 class="font-weight-bold">{{ __('home.Statement_of_Changes_in_Equity_Report') }}</h4>
                @if(isset($fromDate) && isset($toDate))
                    <p class="text-muted">الفترة من: {{ $fromDate }} إلى: {{ $toDate }}</p>
                @endif
            </div>
            <!-- يمكنك إضافة جدول أو محتوى التقرير هنا -->
        </div>
    </div>
</div>
@endsection

@section('js')
<!--Internal Chart.bundle js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<script>
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
</script>
@endsection