@extends('layouts.master')
@section('css')
<!-- Internal Nice-select css  -->
<link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title')
{{ __('report.enpenses_reason') }}@stop


@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.enpenses_reason') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    <!-- row -->
    <div class="row">


        <div class="col-lg-12 col-md-12">
            @if (session()->has('notcreate'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <br>
                <strong>{{ session()->get('notcreate') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if (session()->has('Cost_center_created_successfully'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <br>

                <strong>{{ session()->get('Cost_center_created_successfully') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
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

            <div style="border-end-end-radius: 10px;border-end-start-radius:10px" class="card pt-5">
                <div class="card-body">

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'expenses_reason')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-lg-4">


                                <label for="inputName" class="control-label parent-label">{{ __('report.costcenter_ar') }}</label>
                                <input type="text" class="form-control parent-input" id="breanchName" name="breanchName" title="  يرجي ادخال رقم الفاتورة  " value="{{ $data['supllier']->supllier->comp_name ?? '' }}" required>

                            </div>
                            <div class="col-lg-4">


                                <label for="inputName" class="control-label parent-label">{{ __('report.costcenter_en') }}</label>
                                <input type="text" class="form-control parent-input" id="expenses_reason_en" name="expenses_reason_en" title="  يرجي ادخال رقم الفاتورة  " value="{{ $data['supllier']->supllier->comp_name ?? '' }}" required >

                            </div>
                 

                        </div><!-- col-4 -->


                </div><br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-success ">
                        {{ __('home.Add') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg>
                    </button>
                </div>

                <br>

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