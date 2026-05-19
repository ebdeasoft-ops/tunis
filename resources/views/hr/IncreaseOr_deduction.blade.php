@extends('layouts.master')
@section('css')
<!-- Internal Nice-select css  -->
<style>
    form {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }

    .radio-label {
        display: flex;
        cursor: pointer;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        margin-bottom: 0.375em;
        /* Accessible outline */
        /* Remove comment to use */
        /*
  	&:focus-within {
  			outline: .125em solid $primary-color;
  	}
  */
    }

    .radio-label input {
        position: absolute;
        left: -9999px;
    }

    .radio-label input:checked+span {
        background-color: #d6d6e5;
    }

    .radio-label input:checked+span:before {
        box-shadow: inset 0 0 0 0.4375em #00005c;
    }

    .radio-label span {
        display: flex;
        align-items: center;
        padding: 0.375em 0.75em 0.375em 0.375em;
        border-radius: 99em;
        transition: 0.25s ease;
    }

    .radio-label span:hover {
        background-color: #d6d6e5;
    }

    .radio-label span:before {
        display: flex;
        flex-shrink: 0;
        content: "";
        background-color: #fff;
        width: 1.5em;
        height: 1.5em;
        border-radius: 50%;
        margin-right: 0.375em;
        transition: 0.25s ease;
        box-shadow: inset 0 0 0 0.125em #00005c;
    }
</style>
<link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title')
{{ __('hr.Increaseـor deductionـforـtheـemployee') }}
@stop


@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('hr.Increaseـor deductionـforـtheـemployee') }}</h4>
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

            @if (session()->has('create_department'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <br>

                <strong>{{ session()->get('create_department') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="card pt-4">
                <div class="card-body pt-3">

                    <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2" action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'Increaseـor_deduction')) }}" method="post">
                        {{ csrf_field() }}
                        {{--
                        <div class="col-lg-3">
                            <label class="rdiobox">
                                <input checked name="rdio" type="radio" value="1" id="type_div">
                                <span>{{ __('hr.bouns') }}
                        </span></label>
                </div>


                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                    <label class="rdiobox"><input name="rdio" value="2" type="radio"><span>
                            {{ __('hr.discount') }}
                        </span></label>
                </div><br><br> --}}

                <div style="border-radius: 10px;width:35% !important" class="row card text-center p-3 mx-2 card-increase">

                    <label style="font-size: 1.1vw;" class="radio-label parent-label mb-3">&nbsp;
                        <input checked name="rdio" type="radio" value="1" id="type_div" />
                        <span class="span-radio">{{ __('hr.bouns') }}</span>
                    </label>
                    <label style="font-size: 1.1vw;" class="radio-label parent-label mb-3">&nbsp;
                        <input name="rdio" value="2" type="radio" />
                        <span class="span-radio">{{ __('hr.discount') }}</span>
                    </label>

                </div>


                <div>
                    <div class="row ">
                        <div class="col-lg-4">
                            <label class="form-label parent-label" id="department">{{ __('hr.searchby_name_Id') }}<span class="tx-danger">*</span> </label>
                            <select name="department" id="department" class="form-control parent-input">

                                @foreach (App\Models\employee::get() as $employee)
                                <option style="font-size: 15px" value="{{ $employee->id }}"> {{ $employee->name_ar }} -
                                    {{ $employee->personal_identification }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4" id="fnWrapper">
                            <label class="parent-label" id="increasValuelabel">{{ __('hr.increasValue') }} </label>
                            <input class="form-control parent-input mg-b-20" name="increasValue" id="increasValue" required type="text" onkeyup="increasValueconvert()" value="0">
                        </div>
                        <div class="col-lg-4" id="lnWrapper">
                            <label class="parent-label" id="decreaseValuelabel">{{ __('hr.decreaseValue') }} </label>
                            <input class="form-control parent-input mg-b-20" data-parsley-class-handler="#lnWrapper" required name="decreaseValue" id="decreaseValue" type="text" onkeyup="decreaseValueconvert()" value="0">
                        </div>

                    </div>

                </div>




                <br>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button class="btn btn-main-primary print-style pd-x-20 p-1" type="submit">
                        {{ __('home.Add') }}
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
    function increasValueconvert() {
        var input = document.getElementById("increasValue");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>
<script>
    function decreaseValueconvert() {
        var input = document.getElementById("decreaseValue");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>
<script>
    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });
        $('#decreaseValuelabel').hide();

        $('#decreaseValue').hide();

        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {
                $('#increasValue').show();
                $('#increasValuelabel').show();
                $('#decreaseValue').hide();
                $('#decreaseValuelabel').hide();
                $('#fnWrapper').show();


            } else {
                $('#decreaseValue').show();
                $('#decreaseValuelabel').show();
                $('#increasValue').hide();
                $('#increasValuelabel').hide();
                $('#fnWrapper').hide();


            }
        });
    });
</script>
@endsection