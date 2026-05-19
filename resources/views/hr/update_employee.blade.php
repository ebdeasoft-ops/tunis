@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title')
    {{ __('hr.updateeploye') }}
@stop


@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading class="parent-label"">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('hr.updateeploye') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">


        <div class="col-lg-12 col-md-12">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>خطا</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session()->has('updated_employee'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <br>

                    <strong>{{ session()->get('updated_employee') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body px-3 pt-5">

                    <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updateEmployee')) }}"
                        method="post">
                        {{ csrf_field() }}



                        <div>
                            <div class="row ">

                                <div class="col-lg-4" id="fnWrapper">
                                    <label class="parent-label">{{ __('hr.employee_name_ar') }} <span class="tx-danger">*</span></label>
                                    <input class="form-control form-control-sm mg-b-20" name="employee_name_ar" required
                                        type="text" value="{{ $employee->name_ar }}">
                                </div>
                                <div class="col-lg-4" id="lnWrapper">
                                    <label class="parent-label">{{ __('hr.employee_name_en') }} <span class="tx-danger">*</span></label>
                                    <input class="form-control form-control-sm mg-b-20"
                                        data-parsley-class-handler="#lnWrapper" required name="employee_name_en"
                                        value="{{ $employee->name_ar }}">
                                </div>
                                <div class="col-lg-4" id="lnWrapper">
                                    <label class="parent-label">{{ __('hr.email') }} <span class="tx-danger">*</span></label>
                                    <input class="form-control form-control-sm mg-b-20"
                                        data-parsley-class-handler="#lnWrapper" name="email" required type="email"
                                        value="{{ $employee->email }}">
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-3" id="lnWrapper">
                                <label class="parent-label"> {{ __('hr.Id') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="personal_identification" required
                                    type="number" value="{{ $employee->personal_identification }}">
                            </div>
                            <div class="col-lg-3" id="lnWrapper">
                                <label class="parent-label"> {{ __('hr.phone') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="phone" required type="number"
                                    value="{{ $employee->phone }}">
                            </div>

                            <div class="col-lg-3" id="lnWrapper">
                                <label class="parent-label"> {{ __('hr.salary') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="salary" required type="number"
                                    value="{{ $employee->salary }}">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">{{ __('hr.department') }} </label>
                                <select name="department" id="department"
                                    class="form-control  parent-input">
                                    @foreach (App\Models\departments::get() as $section)
                                        <option value="{{ $section->id }}"> {{ $section->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-lg-4">
                                <label class="form-label"> {{ __('hr.age') }}</label>
                                <input name="age" id="age" value="{{ $employee->old }}"
                                    class="form-control  parent-input nice-select">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label"> {{ __('hr.nationality') }}</label>
                                <input name="nationality" value="{{ $employee->nationality }}" id="nationality"
                                    class="form-control  parent-input nice-select">
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label">{{ __('hr.sex') }} </label>
                                <select name="sex" id="sex"
                                    class="form-control  parent-input">
                                    <option value="male"> {{ __('hr.male') }}</option>
                                    <option value="female"> {{ __('hr.female') }}</option>
                                </select>
                            </div>

                        </div>

                        <br>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button class="btn btn-main-primary print-style pd-x-20"
                                type="submit">
                                {{ __('roles.update') }}
                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
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


<!-- Internal Nice-select js-->
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js') }}"></script>

<!--Internal  Parsley.min js -->
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<!-- Internal Form-validation js -->
<script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>
<script>
    $(document).ready(function() {
        $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
       
    });
</script>
@endsection
