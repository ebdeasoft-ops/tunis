@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection

@section('title')
    {{ __('home.updatecustome') }}
@stop

@section('page-header')
    <div class="main-parent">
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.updatecustome') }}</h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    @if (session()->has('newcustomer'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <br>
            <strong>{{ session()->get('newcustomer') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif   
    
    @if (session()->has('updateseccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <br>
            <strong>{{ session()->get('updateseccess') }}</strong>
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

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div style="border-end-end-radius: 10px; border-end-start-radius: 10px;" class="card pt-5">
                <div class="card-body pb-0">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/updatecustomer') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}

                        {{-- الصف الأول: تحديد العميل والبيانات الأساسية --}}
                        <div class="row mb-3">
                            <div class="col-lg-4 mb-2">
                                <label for="clientnamesearch" class="control-label parent-label">{{ __('home.chooseclient') }}</label>
                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">
                                    <option value="" selected disabled>--- اختر العميل ---</option>
                                    @foreach (App\Models\customers::get() as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->id == 1 ? __('home.Cash Custome') : $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-2">
                                <label for="nameclient" class="control-label parent-label">{{ __('home.clietName') }}</label>
                                <input type="text" class="parent-input form-control" id="nameclient" name="nameclient" required>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="phone" class="control-label parent-label">{{ __('supprocesses.phone') }}</label>
                                <input type="text" class="form-control parent-input" id="phone" name="phone" onkeyup="phoneConvert()">
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="email" class="control-label parent-label">{{ __('supprocesses.email') }}</label>
                                <input type="email" class="form-control parent-input" id="email" name="email">
                            </div>
                        </div>

                        {{-- الصف الثاني: المعطيات المالية والرقابية --}}
                        <div class="row mb-3">
                            <div class="col-lg-2 mb-2">
                                <label for="timeout_periodـinـdays" class="control-label parent-label">{{ __('supprocesses.timeout_periodـinـdays') }}</label>
                                <input type="text" class="form-control parent-input" id="timeout_periodـinـdays" name="grace_period_in_days" onkeyup="timeoutConvert()" required>
                            </div>
                            <div class="col-lg-2 col-md-3 mb-2">
                                <label style="font-size: 12px;" for="CRN" class="control-label parent-label">{{ __('home.CRN') }}</label>
                                <input style="height:32px" type="text" class="form-control parent-input" id="CRN" name="CRN" onkeyup="TaxNumberConvert()">
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="TaxـNumber" class="control-label parent-label">{{ __('home.tax_number') }}</label>
                                <input type="text" class="form-control parent-input" id="TaxـNumber" name="TaxـNumber" onkeyup="TaxNumberConvert()">
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="credit_limit" class="control-label parent-label">{{ __('supprocesses.credit_limit') }}</label>
                                <input type="text" class="form-control parent-input" id="credit_limit" name="credit_limit" onkeyup="creditLimitConvert()" required>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="product_notes" class="control-label parent-label">{{ __('supprocesses.product_notes') }}</label>
                                <input type="text" class="form-control parent-input" id="product_notes" name="product_notes">
                            </div>
                        </div>

                        {{-- الصف الثالث: العنوان الوطني المتوافق مع متطلبات هيئة الزكاة والضريبة والجمارك --}}
                        <div class="row mb-3">
                            <div class="col-lg-2 mb-2">
                                <label for="city" class="control-label parent-label">{{ __('home.city') }}</label>
                                <input type="text" class="form-control parent-input" id="city" name="city" required>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="sub_city" class="control-label parent-label">{{ __('home.region') }}</label>
                                <input type="text" class="form-control parent-input" id="sub_city" name="sub_city" required>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="StreetName" class="control-label parent-label">{{ __('home.StreetName') }}</label>
                                <input type="text" class="form-control parent-input" id="StreetName" name="StreetName" required>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="plot_identification" class="control-label parent-label">{{ __('home.plot_identification') }}</label>
                                <input type="text" class="form-control parent-input" id="plot_identification" name="plot_identification" required>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="buildnumber" class="control-label parent-label">{{ __('home.buildnumber') }}</label>
                                <input type="text" class="form-control parent-input" id="buildnumber" name="buildnumber" required>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="postcode" class="control-label parent-label">{{ __('home.postcode') }}</label>
                                <input type="text" class="form-control parent-input" id="postcode" name="postcode" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-4">
                            <button style="background-color: #419BB2" type="submit" class="btn btn-primary">
                                {{ __('supprocesses.save_data') }}
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
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>

    <script>
        // دالة موحدة لتحويل الأرقام الهندية إلى إنجليزية منعاً للتكرار العشوائي بالملف القديم
        function toEnglishNumber(strNum) {
            if (!strNum) return '';
            var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
            var en = '0123456789'.split('');
            return strNum.toString().replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        }

        function timeoutConvert() {
            var input = document.getElementById("timeout_periodـinـdays");
            input.value = toEnglishNumber(input.value);
        }

        function TaxNumberConvert() {
            var input = document.getElementById("TaxـNumber");
            input.value = toEnglishNumber(input.value);
            
            var crnInput = document.getElementById("CRN");
            if(crnInput) crnInput.value = toEnglishNumber(crnInput.value);
        }

        function creditLimitConvert() {
            var input = document.getElementById("credit_limit");
            input.value = toEnglishNumber(input.value);
        }

        function phoneConvert() {
            var input = document.getElementById("phone");
            input.value = toEnglishNumber(input.value);
        }
    </script>

    <script>
        $(document).ready(function() {
            // تنفيذ سحب بيانات العميل ديناميكياً عند التغيير
            $('select[name="clientnamesearch"]').on('change', function() {
                var selectCustomer = $(this).val();
                if (selectCustomer) {
                    $.ajax({
                        url: "{{ URL::to('/getcustomer') }}/" + selectCustomer,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $("#nameclient").val(data['name'] || '');
                            $("#product_notes").val(data['notes'] || '');
                            $("#credit_limit").val(data['Limit_credit'] || '0');
                            $("#TaxـNumber").val(data['tax_no'] || '');
                            $("#timeout_periodـinـdays").val(data['grace_period_in_days'] || '0');
                            $("#email").val(data['email'] || '');
                            $("#phone").val(data['phone'] || '');
                            $("#buildnumber").val(data['building_number'] || '');
                            $("#plot_identification").val(data['plot_identification'] || '');
                            $("#StreetName").val(data['street_name'] || '');
                            $("#city").val(data['address'] || '');
                            $("#sub_city").val(data['sub_city'] || '');
                            $("#postcode").val(data['postcode'] || '');
                            $("#CRN").val(data['CRN'] || '0');
                        },
                        error: function() {
                            console.error("خطأ أثناء جلب بيانات العميل المختار عبر الـ AJAX.");
                        }
                    });
                }
            });

            // إخفاء رسائل النجاح أو التنبيهات تلقائياً بعد 4 ثوانٍ
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
        });
    </script>
@endsection