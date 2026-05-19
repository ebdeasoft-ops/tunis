@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title')
    {{ __('users.add_user') }}
@stop


@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('users.add_user') }}</h4>
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

            <div class="card">
                <div class="card-body p-5">
                    {{-- <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm"
                                href="{{ route('users.index') }}">{{ __('users.back') }}</a>
                        </div>
                    </div> --}}
                    <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                        action="{{ route('users.store', 'test') }}" method="post">
                        {{ csrf_field() }}

                        <div class="">

                            <div class="row mg-b-20">

                                <div class="parsley-input col-md-6" id="fnWrapper">
                                    <label class="parent-label">{{ __('users.username') }} <span class="tx-danger">*</span></label>
                                    <input class="form-control form-control-sm" name="name" required=""
                                        type="text">
                                </div>

                                <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                    <label class="parent-label">{{ __('users.email') }} <span class="tx-danger">*</span></label>
                                    <input class="form-control form-control-sm"
                                        data-parsley-class-handler="#lnWrapper" name="email" required type="email">
                                </div>
                            </div>

                        </div>

                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label class="parent-label"> {{ __('users.password') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm"
                                    data-parsley-class-handler="#lnWrapper" name="password" required type="password">
                            </div>

                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label class="parent-label"> {{ __('users.confirmـpassword') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm"
                                    data-parsley-class-handler="#lnWrapper" name="confirm-password" required=""
                                    type="password">
                            </div>

                        </div>

                        <div class="row row-sm mg-b-20">
                            <div class="col-lg-6">
                                <label class="form-label"> {{ __('users.Userـstatus') }}</label>
                                <select style="direction: rtl" name="Status" id="select-beast"
                                    class="form-control  parent-input">
                                    <option style="font-size: 15px" value=1> {{ __('users.active') }}</option>
                                    <option style="font-size: 15px" value=0> {{ __('users.disactive') }}</option>
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">{{ __('users.branch') }} </label>
                                <select style="direction: rtl" name="branchs_id" id="branchs_id"
                                    class="form-control parent-input ">
                                    @foreach (App\Models\branchs::get() as $section)
                                        <option style="direction: rtl;font-size:15px" value="{{ $section->id }}"><span style="direction: rtl !important">{{ $section->name }}</span></option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row mg-b-20">
                            <div class="col-xs-12 col-md-12">
                                <div style="" class="form-group">
                                    <label class="form-label"> {{ __('users.User_roles') }}</label>
                                    {!! Form::select('roles_name[]', $roles, [], ['class' => 'form-control', 'multiple','user_role']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button class="btn btn-main-primary pd-x-20 print-style" type="submit">
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
        $(document).ready(function() {

            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
    
        });
    </script>
@endsection
