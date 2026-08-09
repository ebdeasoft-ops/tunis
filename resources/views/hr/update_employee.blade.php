@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@stop
@section('title')
    {{ __('hr.updateeploye') }}
@endsection

@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
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

                        <!-- تمرير معرف الموظف المخفي للتعديل -->
                        <input type="hidden" name="id" value="{{ $employee->id }}">

                        <div>
                            <div class="row">
                                <div class="col-lg-4" id="fnWrapper">
                                    <label class="parent-label">{{ __('hr.employee_name_ar') }} <span class="tx-danger">*</span></label>
                                    <input class="form-control form-control-sm mg-b-20" name="employee_name_ar" required
                                        type="text" value="{{ $employee->name_ar }}">
                                </div>
                                <div class="col-lg-4" id="lnWrapper">
                                    <label class="parent-label">{{ __('hr.employee_name_en') }} <span class="tx-danger">*</span></label>
                                    <input class="form-control form-control-sm mg-b-20"
                                        data-parsley-class-handler="#lnWrapper" required name="employee_name_en"
                                        value="{{ $employee->name_en ?? $employee->name_ar }}">
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
                                    data-parsley-class-handler="#lnWrapper" name="personal_identification"
                                    id="personal_identification" required type="text"
                                    value="{{ $employee->personal_identification }}" onkeyup="personal_identificationconvert()">
                            </div>
                            <div class="col-lg-3" id="lnWrapper">
                                <label class="parent-label"> {{ __('hr.phone') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="phone" id="phone" required
                                    type="text" value="{{ $employee->phone }}" onkeyup="phoneconvert()">
                            </div>

                            <div class="col-lg-3" id="lnWrapper">
                                <label class="parent-label"> {{ __('hr.salary') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="salary" id="salary" required
                                    type="text" value="{{ $employee->salary }}" onkeyup="salaryconvert()">
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">{{ __('hr.department') }} </label>
                                <select name="department" id="department"
                                    class="form-control parent-input">
                                    @foreach (App\Models\departments::get() as $section)
                                        <option value="{{ $section->id }}" {{ $employee->department_id == $section->id ? 'selected' : '' }}>
                                            {{ $section->name_ar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- خانات البدلات الثابتة الجديدة مع جلب القيمة القديمة ودعم الترجمة -->
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="parent-label">{{ __('hr.housing_allowance') }}</label>
                                <input class="form-control parent-input form-control-sm mg-b-20" name="housing_allowance" id="housing_allowance" type="text" value="{{ $employee->housing_allowance ?? 0 }}" onkeyup="housingconvert()">
                            </div>
                            <div class="col-lg-4">
                                <label class="parent-label">{{ __('hr.transportation_allowance') }}</label>
                                <input class="form-control parent-input form-control-sm mg-b-20" name="transportation_allowance" id="transportation_allowance" type="text" value="{{ $employee->transportation_allowance ?? 0 }}" onkeyup="transportationconvert()">
                            </div>
                            <div class="col-lg-4">
                                <label class="parent-label">{{ __('hr.other_allowances') }}</label>
                                <input class="form-control parent-input form-control-sm mg-b-20" name="other_allowances" id="other_allowances" type="text" value="{{ $employee->other_allowances ?? 0 }}" onkeyup="otherallowancesconvert()">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <label class="form-label"> {{ __('hr.age') }}</label>
                                <input name="age" id="age" value="{{ $employee->old }}"
                                    class="form-control parent-input" onkeyup="ageconvert()">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label"> {{ __('hr.nationality') }}</label>
                                <input name="nationality" value="{{ $employee->nationality }}" id="nationality"
                                    class="form-control parent-input">
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label">{{ __('hr.sex') }} </label>
                                <select name="sex" id="sex"
                                    class="form-control parent-input">
                                    <option value="male" {{ $employee->sex == 'male' ? 'selected' : '' }}> {{ __('hr.male') }}</option>
                                    <option value="female" {{ $employee->sex == 'female' ? 'selected' : '' }}> {{ __('hr.female') }}</option>
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

<!--Internal Parsley.min js -->
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<!-- Internal Form-validation js -->
<script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>

<script>
    function toEnglishNumber(strNum) {
        if (!strNum) return '';
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = String(strNum).replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        return strNum;
    }

    function personal_identificationconvert() {
        var input = document.getElementById("personal_identification");
        input.value = toEnglishNumber(input.value);
    }
    function ageconvert() {
        var input = document.getElementById("age");
        input.value = toEnglishNumber(input.value);
    }
    function phoneconvert() {
        var input = document.getElementById("phone");
        input.value = toEnglishNumber(input.value);
    }
    function salaryconvert() {
        var input = document.getElementById("salary");
        input.value = toEnglishNumber(input.value);
    }
    function housingconvert() {
        var input = document.getElementById("housing_allowance");
        input.value = toEnglishNumber(input.value);
    }
    function transportationconvert() {
        var input = document.getElementById("transportation_allowance");
        input.value = toEnglishNumber(input.value);
    }
    function otherallowancesconvert() {
        var input = document.getElementById("other_allowances");
        input.value = toEnglishNumber(input.value);
    }
</script>

<script>
    $(document).ready(function() {
        var timeout = 4000;
        $('.alert').delay(timeout).fadeOut(500);
    });
</script>
@endsection