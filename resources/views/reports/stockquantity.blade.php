@extends('layouts.master')

@section('css')
<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ __('report.stockquantity') }}
@endsection

@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.stockquantity') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
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

<!-- row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            
            <div class="card-header pb-0">
                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . 'stockquantity') }}" method="POST" role="search" autocomplete="off" id="reportForm">
                    {{ csrf_field() }}

                    <?php
                    $branchdata = $branchdata ?? null;
                    $display = '>=';
                    $quantity = 1;
                    $loction = '-';
                    
                    if ($branchdata != null && $branchdata != '-') {
                        $data = explode("/", $branchdata);
                        $branchdata = $data[0] ?? '-';
                        $display = $data[1] ?? '>=';
                        $quantity = $data[2] ?? 1;
                        $loction = $data[3] ?? '-';
                    }

                    $branchlast = null;
                    if ($branchdata != null && $branchdata != '-') {
                        $branchlast = App\Models\branchs::find($branchdata);
                    }
                    ?>

                    <div class="row">
                        <!-- الفرع -->
                        <div class="col-lg-2" id="type">
                            <p class="mg-b-10 parent-label">{{ __('users.branch') }}</p>
                            <select class="form-control select2" name="branch" id="branch" required>
                                <option value="{{ $branchdata == '-' ? '-' : $branchdata }}" selected>
                                    {{ $branchdata == '-' ? __('users.allbranchs') : optional($branchlast)->name }}
                                </option>
                                <option value="-">{{ __('users.allbranchs') }}</option>
                                @foreach (App\Models\branchs::get() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            
                            <?php $avtSaleRate = App\Models\Avt::find(2); ?>
                            <input type="text" class="form-control" id="avtValue" name="avtValue" value="{{ optional($avtSaleRate)->AVT }}" hidden>
                        </div>

                        <!-- اختيار الكمية للعرض -->
                        <div class="col-lg-3" id="type">
                            <p class="mg-b-10 parent-label">{{ __('home.choosequantitytodisplay') }}</p>
                            <select class="form-control" name="choosequantitytodisplay" id="choosequantitytodisplay" required>
                                <option value="{{ $display }}" selected>
                                    @if($display == '==') {{ __('home.morethen0') }}
                                    @elseif($display == '>=') {{ __('home.morethen1') }}
                                    @else {{ __('home.lessthen') }}
                                    @endif
                                </option>
                                <option value='=='>{{ __('home.morethen0') }}</option>
                                <option value='<='>{{ __('home.lessthen') }}</option>
                                <option value='>='>{{ __('home.morethen1') }}</option>
                            </select>
                        </div>

                        <!-- إلى تاريخ -->
                        <div class="col-lg-2" id="end_at">
                            <label class="parent-label" for="exampleFormControlSelect1">{{ __('report.todate') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <input class="form-control parent-input fc-datepicker" name="end_at" value="{{ $end_at ?? date('Y-m-d') }}" placeholder="YYYY-MM-DD" type="text" required>
                            </div>
                        </div>

                        <!-- الكمية -->
                        <div class="col-lg-2 mg-t-10 mg-lg-t-0">
                            <label for="quantity" class="control-label parent-label">{{ __('home.quantity') }}</label>
                            <input type="number" class="form-control parent-input" id="quantity" name="quantity" title="يرجي ادخال الكمية" value="{{ $quantity }}" required>
                        </div>

                        <!-- الموقع -->
                        <div class="col-lg-3 mg-t-10 mg-lg-t-0">
                            <label for="Location" class="control-label parent-label">{{ __('home.Location') }}</label>
                            <input class="form-control parent-input" id="Location" name="Location" value="{{ $loction }}">
                        </div>
                    </div>

                    <!-- أزرار البحث وعرض التقرير أو التصدير -->
                    <div class="row">
                        <div class="col">
                  <div class="row">
    <div class="col">
        <div class="d-flex justify-content-center align-items-center my-4" style="gap: 15px;">
            <!-- زر العرض / البحث -->
            <button type="submit" name="action_type" value="search" class="btn btn-success px-4 py-2">
                {{ __('home.search') }} <i class="las la-search" style="font-size:16px;"></i>
            </button>

            <!-- زر تصدير Excel (يرسل قيمة export_excel لكي تتعرف عليه الفانكشن) -->
            <button type="submit" name="export_excel" value="1" class="btn btn-primary px-4 py-2">
                تصدير Excel <i class="las la-file-excel" style="font-size:16px;"></i>
            </button>
        </div>
    </div>
</div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<!-- row closed -->
@endsection

@section('js')
<!-- Internal Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
@endsection