@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Cairo', sans-serif !important;
        }
        .custom-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.05);
            background: #ffffff;
            transition: all 0.3s ease;
        }
        .page-header-title {
            font-weight: 800;
            color: #2c3e50;
            letter-spacing: 0.5px;
        }
        .form-control.parent-input, .select2-container--default .select2-selection--single {
            height: 48px !important;
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 10px 15px;
            font-size: 14px;
            background-color: #f8fafc;
            transition: all 0.2s;
        }
        .form-control.parent-input:focus {
            border-color: #419BB2 !important;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(65, 155, 178, 0.15);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .parent-label {
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            font-size: 13.5px;
        }
        .input-group-text {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 10px 0 0 10px !important;
            color: #64748b;
        }
        .parent-input {
            border-radius: 0 10px 10px 0 !important;
        }
        .btn-search-luxury {
            background: linear-gradient(135deg, #419BB2 0%, #2b6cb0 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 35px;
            font-weight: 700;
            color: #fff;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(65, 155, 178, 0.3);
            transition: all 0.3s ease;
        }
        .btn-search-luxury:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(65, 155, 178, 0.4);
            color: #fff;
        }
    </style>

@section('title')
    {{ __('home.product damage') }}
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between my-4">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <div class="card-icon bg-info-transparent text-info rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(65, 155, 178, 0.1);">
                    <i class="fas fa-exclamation-triangle fa-lg" style="color: #419BB2;"></i>
                </div>
                <div>
                    <h4 class="content-title mb-1 page-header-title">{{ __('home.product damage') }}</h4>
                    <span class="text-muted tx-13">لوحة تحكم تقارير الأضرار والتوالف</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="server" style="border-radius: 12px;">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong class="font-weight-bold">خطأ في الإدخال:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('notfountreturnproduct'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
            <strong class="font-weight-bold">{{ session()->get('notfountreturnproduct') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Row Search Card -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card mg-b-20">
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ('ProductsDamageReport')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            
                            <!-- تاريخ البداية -->
                            <div class="col-lg-4 mb-4" id="start_at">
                                <label class="parent-label" for="start_at_input">
                                    <i class="far fa-calendar-alt text-info mr-1"></i> {{ __('report.fromdate') }}
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input id="start_at_input" class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- تاريخ النهاية -->
                            <div class="col-lg-4 mb-4" id="end_at">
                                <label class="parent-label" for="end_at_input">
                                    <i class="far fa-calendar-alt text-info mr-1"></i> {{ __('report.todate') }}
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input id="end_at_input" class="form-control parent-input fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- الفروع -->
                            <div class="col-lg-4 mb-4" id="type">
                                <label class="parent-label" for="branch_select">
                                    <i class="fas fa-code-branch text-info mr-1"></i> {{ __('users.branch') }}
                                </label>
                                <select id="branch_select" class="form-control parent-input select2" name="branch" required>
                                    <option value="-" selected>{{ __('users.allbranchs') }}</option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <!-- زر البحث الفاخر -->
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-search-luxury">
                                <i class="las la-search fs-16 ml-2" style="font-size: 18px; vertical-align: middle;"></i> 
                                {{ __('home.search') }}
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
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!--Internal Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Ion.rangeSlider.min js -->
    <script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!--Internal jquery-simple-datetimepicker js -->
    <script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
    <!--Internal pickerjs js -->
    <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // تفعيل Datepicker
            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            // تفعيل Select2 بجميع الخصائص
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
@endsection