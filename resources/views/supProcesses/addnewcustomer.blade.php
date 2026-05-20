@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection

@section('title')
    {{ __('home.addnewcustomer') }}
@stop

@section('page-header')
    <div class="main-parent">
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.addnewcustomer') }}</h4>
                </div>
            </div>
        </div>
    </div> @endsection

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
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/addnewcustomer') }}"
                          method="post" 
                          enctype="multipart/form-data" 
                          autocomplete="off">
                        {{ csrf_field() }}
                        
                        {{-- Row 1: Primary Account Info --}}
                        <div class="row mb-3">
                            <div class="col-lg-4 mb-2">
                                <label Lifor="name" class="control-label parent-label">{{ __('supprocesses.name') }}</label>
                                <input type="text" class="form-control parent-input" id="name" name="name" title="{{ __('supprocesses.name') }}" required>
                            </div>

                            <div class="col-lg-3 mb-2">
                                <label for="phone" class="control-label parent-label">{{ __('supprocesses.phone') }}</label>
                                <input type="text" class="form-control parent-input" id="phone" name="phone" onkeyup="phoneConvert()" title="{{ __('supprocesses.phone') }}">
                            </div>

                            <div class="col-lg-3 mb-2">
                                <label for="email" class="control-label parent-label">{{ __('supprocesses.email') }}</label>
                                <input type="email" class="form-control parent-input" id="email" name="email" title="{{ __('supprocesses.email') }}" placeholder="Example@gmail.com">
                            </div>

                            <div class="col-lg-2 mb-2">
                                <label for="balance" class="control-label parent-label">{{ __('home.current balance') }}</label>
                                <input type="number" step="0.01" class="parent-input form-control" id="balance" name="balance" value="{{ $data['customer']->Balance ?? '0' }}">
                            </div>
                        </div>

                        {{-- Row 2: Regulatory & Financial Limits --}}
                        <div class="row mb-3">
                            <div class="col-lg-3 mb-2">
                                <label for="timeout_periodـinـdays" class="control-label parent-label">{{ __('supprocesses.timeout_periodـinـdays') }}</label>
                                <input type="number" class="form-control parent-input" id="timeout_periodـinـdays" name="timeout_periodـinـdays" value="30" onkeyup="timeoutPeriodConvert()" required>
                            </div>

                            <div class="col-lg-3 mb-2">
                                <label for="TaxـNumber" class="control-label parent-label">{{ __('home.tax_number') }}</label>
                                <input type="text" class="form-control parent-input" id="TaxـNumber" name="TaxـNumber" maxlength="15" minlength="15" onkeyup="TaxNumberConvert()" title="{{ __('supprocesses.TaxـNumber') }}" required>
                            </div>

                            <div class="col-lg-2 col-md-3 mb-2">
                                <label style="font-size: 12px;" for="CRN" class="control-label parent-label">{{ __('home.CRN') }}</label>
                                <input style="height:32px" type="text" class="form-control parent-input" id="CRN" name="CRN" value="0">
                            </div>

                            <div class="col-lg-2 mb-2">
                                <label for="credit_limit" class="control-label parent-label">{{ __('supprocesses.credit_limit') }}</label>
                                <input type="number" class="form-control parent-input" id="credit_limit" name="credit_limit" value="10000" onkeyup="creditLimitConvert()" required>
                            </div>

                            <div class="col-lg-2 mb-2">
                                <label for="city" class="control-label parent-label">{{ __('home.city') }}</label>
                                <input type="text" class="form-control parent-input" id="city" name="city" required>
                            </div>
                        </div>

                        {{-- Row 3: National Address Data Details (ZATCA Compliant Format) --}}
                        <div class="row mb-3">
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
                                <input type="text" class="form-control parent-input" id="postcode" name="postcode" value="11461" required>
                            </div>

                            <div class="col-lg-2 mb-2">
                                <label for="product_notes" class="control-label parent-label">{{ __('supprocesses.product_notes') }}</label>
                                <input type="text" class="form-control parent-input" id="product_notes" name="product_notes">
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
    <script>
        // Universal Localized Number Parser Utility
        function toEnglishNumber(strNum) {
            var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
            var en = '0123456789'.split('');
            return strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        }

        function timeoutPeriodConvert() {
            var input = document.getElementById("timeout_periodـinـdays");
            input.value = toEnglishNumber(input.value);
        }

        function TaxNumberConvert() {
            var input = document.getElementById("TaxـNumber");
            input.value = toEnglishNumber(input.value);
        }

        function creditLimitConvert() {
            var input = document.getElementById("credit_limit");
            input.value = toEnglishNumber(input.value);
        }

        function phoneConvert() {
            var input = document.getElementById("phone");
            input.value = toEnglishNumber(input.value);
        }

        $(document).ready(function() {
            // Toast notification dismiss countdown
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
            
            // Dynamic Cascading Selection Engine
            $('select[name="Section"]').on('change', function() {
                var SectionId = $(this).val();
                if (SectionId) {
                    $.ajax({
                        url: "{{ URL::to('section') }}/" + SectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var $productSelect = $('select[name="product"]').empty();
                            $.each(data, function(key, value) {
                                $productSelect.append('<option value="' + value + '">' + value + '</option>');
                            });
                        },
                        error: function() {
                            console.log('AJAX connection error during dynamic lookup.');
                        }
                    });
                }
            });
        });

        // Math Invoicing Calculations (Retained for conditional form triggers)
        function myFunction() {
            var Amount_Commission = parseFloat(document.getElementById("Amount_Commission")?.value || 0);
            var Discount = parseFloat(document.getElementById("Discount")?.value || 0);
            var Rate_VAT = parseFloat(document.getElementById("Rate_VAT")?.value || 0);

            if (!Amount_Commission) {
                alert('يرجى إدخال مبلغ العمولة');
                return;
            }

            var Amount_Commission2 = Amount_Commission - Discount;
            var intResults = Amount_Commission2 * Rate_VAT / 100;
            var intResults2 = intResults + Amount_Commission2;

            if(document.getElementById("Value_VAT")) {
                document.getElementById("Value_VAT").value = intResults.toFixed(2);
            }
            if(document.getElementById("Total")) {
                document.getElementById("Total").value = intResults2.toFixed(2);
            }
        }
    </script>
@endsection