@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    {{ __('home.systemSetting') }}@stop

@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.systemSetting') }}</h4>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->
    @endsection
    @section('content')
    <br>

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
                <strong>خطا</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- row -->
        <div class="row">

            <div class="col-lg-12 col-md-12">
                <div style="border-end-end-radius: 10px;border-end-start-radius:10px" class="card pt-5">
                    <div class="card-body pb-0">
                        <form
                            action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updateCamData')) }}"
                            method="post" enctype="multipart/form-data" autocomplete="off">
                            {{ csrf_field() }}
                            {{-- 1 --}}

                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.camName_ar') }}</label>
                                    <input type="text" class="form-control parent-input" id="camName_ar" name="camName_ar" value="{{$data->name_ar}}"
                                        title="{{ __('supprocesses.name') }}" required>
                                </div>

                                <div class="col-lg-5 ">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.camName_en') }}</label>
                                    <input type="text" class="form-control parent-input" id="camName_en" name="camName_en" value="{{$data->name_en}}"
                                        onkeyup="phoneConvert()" title="{{ __('home.camName_en') }}">
                                </div>

                                <div class="col-lg-2 ">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.SR') }}</label>
                                    <input type="text" class="form-control parent-input" id="SR" name="SR" value="{{$data->SR}}"
                                        title="{{ __('home.SR') }}" placeholder='****'>
                                </div>
                               
                            </div>
                            <div class="row ">

<div class="col-lg-3">
        <label for="inputName" class="control-label parent-label"> {{ __('home.Tax') }} </label>
        <input type="number" class="parent-input form-control" id="Tax" name="Tax"
            title="يرجي ادخال الكمية  "  placeholder='3*****************3' value="{{$data->Tax}}"
            >
            </div>

    <div class="col-lg-3">
        <label for="inputName" class="control-label parent-label">
            {{ __('home.descriptionarbic') }}</label>
        <input type="text" class="form-control parent-input" id="descriptionarbic" value="{{$data->descriptionarbic}}"
            name="descriptionarbic"
            title=" {{ __('home.addres_ar') }}"
            onkeyup="timeout_periodـinـdaysConvert()" required>
    </div>
    <div class="col-lg-3">
        <label for="inputName" class="control-label parent-label">
            {{ __('home.descriptionenglish') }}</label>
        <input type="text" class="form-control parent-input" id="descriptionenglish" value="{{$data->descriptionenglish}}"
            name="descriptionenglish"
            title=" {{ __('home.addres_ar') }}"
            onkeyup="timeout_periodـinـdaysConvert()" required>
    </div>
      <div class="col-lg-3">
        <label for="inputName" class="control-label parent-label"> {{ __('home.discount_rate_invoice_sale') }} </label>
        <input type="number" class="parent-input form-control" id="discount_on_invoice" name="discount_on_invoice"
         value="{{$data->discount_on_invoice}}">
         </div>
         </div>



<?php
 $setting=App\Models\settings::find(1)

?>
                            <div class="row mb-3">
                          
                                <div class="col-lg-2 ">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.postcode') }}</label>
                                    <input type="text" class="form-control parent-input" id="postcode" name="postcode"  placeholder="12356"
                                        onkeyup="phoneConvert()" title="{{ __('home.camName_en') }}" value="{{$setting->postal_number}}" required>
                                </div>
                                <div class="col-lg-2">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.scandery_number') }}</label>
                                    <input type="text" class="form-control parent-input" id="scander_number" name="scander_number" value="{{$setting->plot_identification}}"  placeholder="1265"
                                        onkeyup="phoneConvert()" title="{{ __('home.camName_en') }}" required>
                                </div> 
                                    <div class="col-lg-2">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.buildnumber') }}</label>
                                    <input type="text" class="form-control parent-input" id="buildnumber" name="buildnumber"  placeholder="1245"
                                        onkeyup="phoneConvert()" title="{{ __('home.camName_en') }}"  value="{{$setting->building_number}}" required>
                                </div>
                                 <div class="col-lg-2 ">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.StreetName') }}</label>
                                    <input type="text" class="form-control parent-input" id="StreetName" name="StreetName"  placeholder="طريق الملك"
                                        onkeyup="phoneConvert()" title="{{ __('home.camName_en') }}"  value="{{$setting->street_name}}"  required>
                                </div>
                                <div class="col-lg-2 ">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.city') }}</label>

                                    <input type="text" class="form-control parent-input" id="region" name="region" }" placeholder="الخرج"
                                        onkeyup="phoneConvert()" title="{{ __('home.camName_en') }}"  value="{{$setting->region}}"  required>
                                </div>
                                 
                             <div class="col-lg-2 ">
                             <label for="inputName" class="control-label parent-label"> {{ __('home.region') }}</label>

                                    <input type="text" class="form-control parent-input" id="city" name="city"  value="{{$setting->city}}"  placeholder="الرياض"
                                        onkeyup="phoneConvert()" title="{{ __('home.camName_en') }}" required>
                                        </div>                           
                                        </div>                           
                             

                                    <div class="row">

                                    <div class="col-lg-4 ">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.country') }}</label>
                                    <input type="text" class="form-control parent-input" id="country" name="country" value="SA" readonly>
                                    </div>
                     
                            
                                  <div class="col-lg-4 ">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.TEL') }}</label>
                                    <input type="number" class="form-control parent-input" id="TEL" name="TEL" value="{{$setting->mobile}}" 
                                    required >
                    

                            </div>
                                         <div class="col-lg-4 ">
                                    <label for="inputName" class="control-label parent-label"> {{ __('home.email') }}</label>
                                    <input type="email" class="form-control parent-input" id="email" name="email" value="{{$setting->email_address}}"  required >
                            </div>   
                             
                            </div>
                               
                            <div class="row">

<div class="col-lg-4 ">
<label for="inputName" class="control-label parent-label"> {{ __('home.bankname') }}</label>
<input type="text" class="form-control parent-input" id="bankname" name="bankname" value="{{$data->bankname}}" required >
</div>


<div class="col-lg-4 ">
<label for="inputName" class="control-label parent-label"> {{ __('home.bank_acount_number') }}</label>
<input type="number" class="form-control parent-input" id="bank_acount_number" name="bank_acount_number" value="{{$data->bank_acount_number}}" 
required >


</div>
     <div class="col-lg-4 ">
<label for="inputName" class="control-label parent-label"> {{ __('home.bank_acount_iban') }}</label>
<input type="text" class="form-control parent-input" id="bank_acount_iban" name="bank_acount_iban" value="{{$data->bank_acount_iban}}"  required >
</div>   

</div>

                                     <br>


                            <div class="col">
                                    <label for="inputName" class="control-label parent-label">
                                        {{ __('home.addres_ar') }}</label>
                                    <input type="text" class="form-control parent-input" id="address_ar" value="{{$data->address_ar}}"
                                        name="address_ar"
                                        title=" {{ __('home.addres_ar') }}"
                                        onkeyup="timeout_periodـinـdaysConvert()" required>
                                </div> 
                                <br>
                                 <div class="col">
                                    <label for="inputName" class="control-label parent-label">
                                        {{ __('home.addres_en') }}</label>
                                    <input type="text" class="form-control parent-input" id="address_en" value="{{$data->address_en}}"
                                        name="address_en"
                                        title=" {{ __('home.addres_ar') }}"
                                        onkeyup="timeout_periodـinـdaysConvert()" required>
                                        </div>
                                        </div>
                                        <div class="col-md-6" style="border:solid 3px #000 ; margin:10px;">
               <div class="form-group">
                  <label> {{__('home.logo')}}</label>
                  <img id="uploadedimg" src="{{ asset('assets\img\brand').'/'.$data['logo'] }}" style="width: 150px; width: 100px;">
                  <input autocomplete="off" onchange="readURL(this)" type="file" id="logo" name="logo" class="form-control">
                  @error('active')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               </div>

                            <div class="d-flex justify-content-center">
                                <button style="background-color: #419BB2" type="submit" class="btn btn-primary">
                                    {{ __('supprocesses.save_data') }}
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

    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
    </div>
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
    <script>
        function timeout_periodـinـdaysConvert() {
            var input = document.getElementById("timeout_periodـinـdays");
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
        function TaxـNumberConvert() {
            var input = document.getElementById("TaxـNumber");
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
        function credit_limitConvert() {
            var input = document.getElementById("credit_limit");
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
        function phoneConvert() {
            var input = document.getElementById("phone");
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
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();
    </script>

    <script>
        $(document).ready(function() {
            

            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
    
     
            $('select[name="Section"]').on('change', function() {
                var SectionId = $(this).val();
                if (SectionId) {
                    $.ajax({
                        url: "{{ URL::to('section') }}/" + SectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="product"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="product"]').append('<option value="' +
                                    value + '">' + value + '</option>');
                            });
                        },
                    });

                } else {
                    console.log('AJAX load did not work');
                }
            });

        });
    </script>


    <script>
        function myFunction() {

            var Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value);
            var Discount = parseFloat(document.getElementById("Discount").value);
            var Rate_VAT = parseFloat(document.getElementById("Rate_VAT").value);
            var Value_VAT = parseFloat(document.getElementById("Value_VAT").value);

            var Amount_Commission2 = Amount_Commission - Discount;


            if (typeof Amount_Commission === 'undefined' || !Amount_Commission) {

                alert('يرجي ادخال مبلغ العمولة ');

            } else {
                var intResults = Amount_Commission2 * Rate_VAT / 100;

                var intResults2 = parseFloat(intResults + Amount_Commission2);

                sumq = parseFloat(intResults).toFixed(2);

                sumt = parseFloat(intResults2).toFixed(2);

                document.getElementById("Value_VAT").value = sumq;

                document.getElementById("Total").value = sumt;

            }

        }
    </script>


@endsection
