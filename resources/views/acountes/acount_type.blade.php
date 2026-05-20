@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ __('home.account_type') }}
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">&nbsp;&nbsp;{{ __('home.account_type') }}</h4>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('content')

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

    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20" style="border-radius: 10px;">
                <div class="card-header pb-0">
                    {{ csrf_field() }}
                </div>
                
                <div class="card-body p-5">
                    <div id="ajax_responce_serarchDiv" class="table-responsive text-center">
                        <table class="table table-bordered text-md-nowrap text-center our-table" id="example2" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                            <colgroup>
                                <col style="width: 5%;">
                                <col style="width: 40%;">
                                <col style="width: 20%;">
                                <col style="width: 35%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th style="font-size: 18px; font-weight: bold;" class="border-bottom-0">#</th>
                                    <th style="font-size: 18px; font-weight: bold;" class="border-bottom-0">{{ __('home.acount_name') }}</th>
                                    <th style="font-size: 18px; font-weight: bold;" class="border-bottom-0">{{ __('home.status_active') }}</th>
                                    <th style="font-size: 18px; font-weight: bold;" class="border-bottom-0">{{ __('home.relatediternalaccounts') }}</th>
                                </tr>
                            </head>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach(App\Models\acounts_type::get() as $account)
                                    @php $i++; @endphp
                                    <tr>
                                        <td style="font-size: 15px; font-weight: bold;" dir="ltr">{{ $i }}</td>
                                        <td style="font-size: 15px; font-weight: bold;" data-target="product_name">
                                            {{ App::getLocale() == 'ar' ? $account->name_ar : $account->name_en }}
                                        </td>
                                        <td style="font-size: 15px; font-weight: bold;">
                                            {{ $account->active == 0 ? __('users.notactive') : __('users.active') }}
                                        </td>
                                        <td style="font-size: 15px; font-weight: bold;">
                                            {{ $account->relatediternalaccounts == 0 ? __('home.no') : __('home.yes') }}
                                        </td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        if ($('#example2').length) {
            $('#example2').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "بحث...",
                    // يمكنك تخصيص باقي نصوص الترجمة هنا إذا رغبت
                }
            });
        }
    });
</script>
@endsection