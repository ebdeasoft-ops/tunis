@extends('layouts.master')
@section('css')
<!--- Internal Select2 css-->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('title')
{{ __('supprocesses.addproduct') }}@stop

@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('supprocesses.addproduct') }}</h4>
            </div>
        </div>
        
                        <div class="choose-product">
                         
                        <button style="background-color: #23395D;" class="modal-effect btn btn-sm btn-info p-2 m-1 button-eng" data-effect="effect-scale" data-toggle="modal" href="#creategroup" title="تحديد"><i style=" height: 50;font-weight:400 !important;
                                                 width: 65px;
                                                 font-size:13px" class="las"> {{ __('home.create_group') }}</i>

                        </button>
                                
                   
                        
                            </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')

    @if (session()->has('addProduct'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>

        <strong>{{ session()->get('addProduct') }}</strong>
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
                <div class="card-body">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'addnewProduct')) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}
                        {{-- 1 --}}


                        <div class="row mb-2">
                            <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_name_ar') }}</label>
                                <input type="text" class="form-control parent-input" id="product_name_ar" name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}" onkeyup="translateNameToEnglish()"  required>
                            </div>
   <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_name_en') }}</label>
                                <input type="text" class="form-control parent-input" id="product_name_en" name="product_name_en"  onkeyup="translateNameToArbic()"  required>
                            </div>


                            <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('supprocesses.product_code') }}</label>
                                <input type="text" class="form-control parent-input" id="product_code" name="product_code" type="text" dir="ltr" onkeyup="convertToNumber()" title="{{ __('supprocesses.product_code') }}" >
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row mb-2">
                            <div class="col-lg-2 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('supprocesses.product_branch') }}</label>
                                <select name="Section" class="form-control parent-input" onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                    <!--placeholder-->
                            @foreach (App\Models\branchs::where('id', Auth()->user()->branchs_id)->get() as $section)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="col-lg-2 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.groups') }}</label>
                                <select name="product_group" id="product_group" class="form-control select2" onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                    <!--placeholder-->
                                   @foreach (App\Models\products_group::get() as $section)
                                    <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.MAINproduct') }}</label>
                                <select name="MAINproduct" id="MAINproduct" class="form-control select2" onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                    <!--placeholder-->
                                    <option value=0> {{ __('home.noreplace') }}</option>

                                  
                                </select>
                            </div>
                         <select hidden name="unit" class="form-control parent-input">
                                    <!--placeholder-->
                                    <div class="row">

                                        <option value="piece"> {{ __('home.unitـpiece') }}</option>
                                        <option value="box">{{ __('home.unit_box') }}</option>
                                </select>


                                <div class="col-lg-5 mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.refnumber') }}</label>
                                <input type="text" class="form-control parent-input" id="refnumber" name="refnumber"  >
                            </div>



                        </div>


                        {{-- 3 --}}





                        {{-- 5 --}}
                        <div class="row mb-2">
                            <div class="col-lg-3 mb-2" style="direction: ltr !important;">

                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_location') }}</label>
                                <input dir="ltr" style="direction:LTR !important ;text-align:start!important;" type="text" class="form-control parent-input" id="product_location" name="product_location" title="{{ __('supprocesses.product_location') }}" required>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.minmum_quantity_stock_alart') }}</label>
                                <input type="text" class="form-control parent-input" id="minmum_quantity_stock_alart" name="minmum_quantity_stock_alart" onkeyup="minmum_quantity_stock_alartConvert()" title="{{ __('supprocesses.minmum_quantity_stock_alart') }}" value=2 required>
                            </div>

                           


                                <div class="col-lg-3 mb-2">

                                                 <label for="inputName" class="control-label parent-label">
 {{__('home.photo')}}</label>
                  <input autocomplete="off" onchange="readURL(this)" type="file" id="Item_img" name="Item_img" class="form-control">
                  @error('active')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
             
            </div>



                            <div class="col-lg-3 mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('supprocesses.product_notes') }}</label>
                                <input type="text" class="form-control parent-input" id="product_notes" name="product_notes" title="{{ __('supprocesses.product_notes') }}">
                            </div>

                        

                        </div><br>



                        <div class="d-flex justify-content-center">
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

</div>

<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>



<div class="modal p-3" id="creategroup">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.create_group') }} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-2">
                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.groub_ar') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input" id="groub_ar" name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}" onkeyup="groub_translateNameToEnglish()"  required>
                    </div>
                    
                    
                     <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.group_en') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input" id="groub_en" name="product_name_en" title="{{ __('supprocesses.product_name_en') }}" onkeyup="groub_translateNameToArbic()"  required>
                            </div>



                </div>

                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" onclick="createnewgroupajax()">
                        {{ __('supprocesses.save_data') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg>
                    </button>
                </div>
        </div>

    </div>
</div>

                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">



 <div class="modal fade product-selection" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" id="massagesave" name="massagesave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
            <div class="modal-dialog modal-xl" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" role="document">
                <div class="modal-content">
                  
                    <div class="modal-body" style="justify-content: center;">


 <center><img style="width:250px;height:250px;" class="custom_img" src="{{ asset('assets/admin/uploads/done.png') }}" >
                        
</center>



                          
                        </div>

                     
                    </div>


                </div>
            </div>

        </div>
        
        
        
        

@endsection
@section('js')
<!-- Internal Select2 js-->
{{-- jQuery (مرة واحدة فقط – غالبًا موجود في layout، لو موجود هناك احذف ده) --}}

{{-- Select2 --}}
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

{{-- ملفات المشروع --}}


<script>
    $('#MAINproduct').select2({
        placeholder: 'ابحث عن المنتج',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: "{{ route('itemcards.search') }}",
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.product_name
                    }))
                };
            }
        }
    });

function groub_translateNameToArbic(){
   var wordEnglish = $('#groub_en').val();
    
         jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=en&tl=ar&q=" + wordEnglish,
            type: 'get',
            cache: false,

            success: function(request_result) {
                $('#groub_ar').val(request_result[0][0][0])
            },
            error: function() {

            }
        });
    
    
}

function groub_translateNameToEnglish(){
   var wordarbic = $('#groub_ar').val();
    
         jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=ar&tl=en&q=" + wordarbic,
            type: 'get',
            cache: false,

            success: function(request_result) {
                $('#groub_en').val(request_result[0][0][0])
            },
            error: function() {

            }
        });
    
    
}
function translateNameToArbic(){}


 function createnewgroupajax() {
        console.log('+++++++++++++++++++++++++++++++++create_products_group ++++++++++++++++++++++++++++++++');
        var url = " {{ URL::to('create_products_group') }}";
   
        var token_search = $("#token_search").val();
        if ($('#groub_ar').val() == '') {
            alert("{{ __('home.groub_ar') }}")
        } else if ($('#groub_en').val() == '') {
            alert("{{ __('home.groub_en') }}")
        } else {
console.log($('#groub_ar').val())
console.log($('#groub_en').val())
console.log(token_search)

            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                data: {
                    _token: token_search,
                    groub_ar: $('#groub_ar').val(),
                    groub_en: $('#groub_en').val(),
                },


                    success: function(data) {
                        console.log(data)
                        $('#creategroup').modal('hide');
                        $('#groub_ar').val('');
                        $('#groub_en').val('');
                
          $('#massagesave').modal().show();
 setTimeout(() => {
         $('#massagesave').modal('hide');

        }, 1000); 

                    },
                      error: function(response) {
console.log(response)
                }
                
            });







        }


    }




function translateNameToEnglish(){}

</script>
<script>
    function minmum_quantity_stock_alartConvert() {
        var input = document.getElementById("minmum_quantity_stock_alart");
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
                      error: function(response) {
                console.log(response)

                }
                });

            } else {
                console.log('AJAX load did not work');
            }
        });

    });
</script>



<script>
    function convertToNumber() {
        var input = document.getElementById("product_code");
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