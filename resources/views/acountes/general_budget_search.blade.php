@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ __('home.general_budget') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.general_budget') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
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
    <div class="row">
        <div class="col-xl-12">
            <div class="card box-shadow-0 mg-b-20">
                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="card-title mb-0 text-primary"><i class="fas fa-filter ml-2"></i> {{ __('home.search_filters') ?? 'فلترة البيانات' }}</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . 'general_budget_search') }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            <!-- التاريخ -->
                            <div class="col-lg-6 mg-b-20" id="date_section">
                                <label class="form-label font-weight-bold">{{ __('report.date') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-primary text-white">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <!-- تم تغيير type إلى date لضمان ظهور التقويم بجميع المتصفحات بدون مشاكل -->
                                    <input class="form-control" id="date_input" value="{{ $date ?? '' }}" name="date" type="date" required style="line-height: 25px;">
                                </div>
                            </div>

                            <!-- الفروع -->
                            <div class="col-lg-6 mg-b-20" id="type">
                                <label class="form-label font-weight-bold">{{ __('users.branch') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="branch" required>
                                    @if(Auth()->user()->branchs_id == 1)
                                        <option value="-" {{ isset($branch) && $branch == '-' ? 'selected' : '' }}>{{ __('users.allbranchs') }}</option>
                                    @endif
                                    @foreach (App\Models\branchs::get() as $b)
                                        @if(Auth()->user()->branchs_id == 1 || Auth()->user()->branchs_id == $b->id)
                                            <option value="{{ $b->id }}" {{ isset($branch) && $branch == $b->id ? 'selected' : '' }}>
                                                {{ $b->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary pd-x-30 mg-r-5 search-btn">
                                <i class="las la-search ml-1" style="font-size:16px;"></i> {{ __('home.search') }}
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
    <!--Internal Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // تفعيل الـ Select2 للقائمة المنسدلة للفروع
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
@endsection