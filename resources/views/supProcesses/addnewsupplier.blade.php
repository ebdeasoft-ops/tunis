@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection

@section('title')
    {{ __('home.addnewsupplier') }}
@stop

@section('page-header')
    <div class="main-parent">
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.addnewsupplier') }}</h4>
                </div>
            </div>
        </div>
    </div> @endsection

@section('content')

    @if (session()->has('addnewsupplier'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <br>
            <strong>{{ session()->get('addnewsupplier') }}</strong>
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
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/addnewsupplier') }}"
                          method="post" 
                          enctype="multipart/form-data" 
                          autocomplete="off">
                        {{ csrf_field() }}
                        
                        {{-- Row 1 --}}
                        <div class="row mb-2">
                            <div class="col-lg-4 mb-2">
                                <label for="name" class="control-label parent-label">{{ __('home.entersuppliername') }}</label>
                                <input type="text" class="form-control parent-input" id="name" name="name" required>
                            </div>

                            <div class="col-lg-4 mb-2">
                                <label for="phone" class="control-label parent-label">{{ __('supprocesses.phone') }}</label>
                                <input type="text" class="form-control parent-input" id="phone" name="phone"
                                       onkeyup="phoneConvert()" title="{{ __('supprocesses.phone') }}" required>
                            </div>

                            <div class="col-lg-4 mb-2">
                                <label for="email" class="control-label parent-label">{{ __('supprocesses.email') }}</label>
                                <input type="text" class="form-control parent-input" id="email" name="email"
                                       title="{{ __('supprocesses.email') }}" value="Example@gmail.com">
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="row mb-2">
                            <div class="col-lg-4 mb-2">
                                <label for="location" class="control-label parent-label">{{ __('supprocesses.Location') }}</label>
                                <input type="text" class="form-control parent-input" id="location" name="location"
                                       title="{{ __('supprocesses.Location') }}" required>
                            </div>

                            <div class="col-lg-4 mb-2">
                                <label for="TaxـNumber" class="control-label parent-label">{{ __('supprocesses.TaxـNumber') }}</label>
                                <input type="number" class="form-control parent-input" id="TaxـNumber" name="TaxـNumber"
                                       onkeyup="TaxNumberConvert()" title="{{ __('supprocesses.TaxـNumber') }}" required>
                            </div>

                            <div class="col-lg-4 mb-2">
                                <label for="notes" class="control-label parent-label">{{ __('supprocesses.product_notes') }}</label>
                                <input type="text" class="form-control parent-input" id="notes" name="notes"
                                       title="{{ __('supprocesses.product_notes') }}">
                            </div>
                        </div>

                        <input type="text" id="comp_name" name="comp_name" value="camp" hidden>

                        <div class="d-flex justify-content-center class= mt-4 mb-4">
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
        // Global utility to map Eastern Arabic numerals to standard Western digits
        function toEnglishNumber(strNum) {
            const ar = '٠١٢٣٤٥٦٧٨٩'.split('');
            const en = '0123456789'.split('');
            return strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        }

        function TaxNumberConvert() {
            var input = document.getElementById("TaxـNumber");
            input.value = toEnglishNumber(input.value);
        }

        function phoneConvert() {
            var input = document.getElementById("phone");
            input.value = toEnglishNumber(input.value);
        }

        $(document).ready(function() {
            // Auto fade out flash alerts
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
            
            // Dynamic section/product handling
            $('select[name="Section"]').on('change', function() {
                var SectionId = $(this).val();
                if (SectionId) {
                    $.ajax({
                        url: "{{ URL::to('section') }}/" + SectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var $productSelect = $('select[name="product"]');
                            $productSelect.empty();
                            $.each(data, function(key, value) {
                                $productSelect.append('<option value="' + value + '">' + value + '</option>');
                            });
                        },
                    });
                }
            });
        });

        // Calculations Engine
        function myFunction() {
            var Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value) || 0;
            var Discount = parseFloat(document.getElementById("Discount").value) || 0;
            var Rate_VAT = parseFloat(document.getElementById("Rate_VAT").value) || 0;

            if (!Amount_Commission) {
                alert('يرجى إدخال مبلغ العمولة');
                return;
            }

            var Amount_Commission2 = Amount_Commission - Discount;
            var intResults = Amount_Commission2 * Rate_VAT / 100;
            var intResults2 = intResults + Amount_Commission2;

            document.getElementById("Value_VAT").value = intResults.toFixed(2);
            document.getElementById("Total").value = intResults2.toFixed(2);
        }
    </script>
@endsection