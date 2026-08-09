@extends('layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    
    <style>
        .search-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            background: #fff;
        }
        .card-header-custom {
            background-color: transparent;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px;
        }
        .form-control-custom {
            height: 45px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 8px 15px;
            font-size: 15px;
            background-color: #fff !important;
        }
        .form-control-custom:focus {
            border-color: #419BB2;
            box-shadow: 0 0 0 0.2rem rgba(65, 155, 178, 0.25);
        }
        .btn-print-custom {
            background-color: #419BB2;
            border-color: #419BB2;
            font-size: 16px;
            font-weight: 600;
            padding: 10px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-print-custom:hover {
            background-color: #357f91;
            border-color: #357f91;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('title')
    {{ __('hr.salarydecoument') }}
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('hr.salarydecoument') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 ml-2">/ تحديد الشهر</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطأ</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- row -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card search-card mg-b-20">
                
                <div class="card-header card-header-custom text-center">
                    <h5 class="font-weight-bold mb-0 text-primary">
                        <i class="fas fa-file-invoice-dollar ml-2"></i> استخراج كشف رواتب الموظفين لشهر محدد
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/print_decument_salary') }}" 
                          method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <input type="hidden" name="end_at" id="end_at_hidden" value="{{ $end_at ?? '' }}" required>

                        <div class="row justify-content-center align-items-center">
                            
                            <!-- اختيار السنة -->
                            <div class="col-md-5 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    السنة <span class="text-danger">*</span>
                                </label>
                                <select id="select_year" class="form-control form-control-custom select2">
                                    <option value="" disabled selected>اختر السنة</option>
                                    @php
                                        $currentYear = date('Y');
                                        $selectedYear = isset($end_at) ? explode('-', $end_at)[0] : $currentYear;
                                    @endphp
                                    @for ($y = $currentYear - 5; $y <= $currentYear + 2; $y++)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- اختيار الشهر -->
                            <div class="col-md-5 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    الشهر <span class="text-danger">*</span>
                                </label>
                                <select id="select_month" class="form-control form-control-custom select2">
                                    <option value="" disabled selected>اختر الشهر</option>
                                    @php
                                        $selectedMonth = isset($end_at) ? explode('-', $end_at)[1] : date('m');
                                        $months = [
                                            '01' => 'يناير (01)',
                                            '02' => 'فبراير (02)',
                                            '03' => 'مارس (03)',
                                            '04' => 'أبريل (04)',
                                            '05' => 'مايو (05)',
                                            '06' => 'يونيو (06)',
                                            '07' => 'يوليو (07)',
                                            '08' => 'أغسطس (08)',
                                            '09' => 'سبتمبر (09)',
                                            '10' => 'أكتوبر (10)',
                                            '11' => 'نوفمبر (11)',
                                            '12' => 'ديسمبر (12)',
                                        ];
                                    @endphp
                                    @foreach ($months as $key => $name)
                                        <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="d-flex justify-content-center mt-3 pt-3">
                            <button type="submit" class="btn btn-success btn-print-custom text-white shadow-sm d-flex align-items-center">
                                <i class="fas fa-print ml-2" style="font-size: 18px;"></i>
                                <span>{{ __('home.print') }} / عرض التقرير</span>
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- row closed -->

@endsection

@section('js')
    <!--Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تفعيل مكتبة Select2 إذا كانت مدعومة في القالب
            $('.select2').select2({
                width: '100%'
            });

            // دمج السنة والشهر تلقائياً في الحقل المخفي قبل الإرسال بالصيغة YYYY-MM
            $('form').on('submit', function(e) {
                var year = $('#select_year').val();
                var month = $('#select_month').val();

                if (!year || !month) {
                    e.preventDefault();
                    alert('يرجى اختيار السنة والشهر أولاً');
                    return false;
                }

                var finalDate = year + '-' + month;
                $('#end_at_hidden').val(finalDate);
            });

            // إخفاء التنبيهات تلقائياً
            var timeout = 4000;
            $('.alert').delay(timeout).fadeOut(500);
        });
    </script>
@endsection