@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@endsection

@section('title')
{{ __('report.enpenses_reason') }}
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.enpenses_reason') }}</h4>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            
            @if (session()->has('notcreate'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('notcreate') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if (session()->has('Cost_center_created_successfully'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                <strong>خطأ</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div style="border-radius: 10px;" class="card">
                <div class="card-body p-5">
                    
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'expenses_reason')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="breanchName" class="control-label parent-label">{{ __('report.costcenter_ar') }}</label>
                                    <input type="text" class="form-control parent-input" id="breanchName" name="breanchName" value="{{ $data['supllier']->supllier->comp_name ?? '' }}" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="expenses_reason_en" class="control-label parent-label">{{ __('report.costcenter_en') }}</label>
                                    <input type="text" class="form-control parent-input" id="expenses_reason_en" name="expenses_reason_en" value="{{ $data['supllier']->supllier->comp_name ?? '' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" style="background-color: #419BB2" class="btn btn-success px-5">
                                    {{ __('home.Add') }}
                                    <svg style="width: 20px;" class="svg-icon-buttons mr-2" viewBox="0 0 20 20">
                                        <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
    @endsection

@section('js')
<script>
    $(document).ready(function() {
        // إخفاء التنبيهات تلقائياً بعد 4 ثوانٍ
        setTimeout(function() {
            $('.alert').fadeOut(500);
        }, 4000);
    });
</script>
@endsection