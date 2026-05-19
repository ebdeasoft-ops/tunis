@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('home.convertboxtobank') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading parent-label">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.convertboxtobank') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')

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

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'Add_blance_from_bank')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}






                        <div class='row'>
                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control parent-input" name="branchs_id" id="branchs_id">

                                    <option value="{{ Auth()->user()->branch->id }}"> {{ Auth()->user()->branch->name }}
                                    </option>

                                    @foreach (App\Models\branchs::get() as $section)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div><!-- col-4 -->
                            <div class="col-lg-3 mb-2" id="type">
                                <label class="parent-label"> {{ __('home.paymentmethod') }} </label>
                                <div class="input-group">
                                    <select class="form-control parent-input" name="pay" required>
                                        <option value="Cash"> {{ __('report.cash') }}</option>
                                        <option value="Shabka "> {{ __('report.shabka') }} </option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label for="inputName" class="parent-label"> {{ __('home.the amount') }}
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control parent-input" id="cashreceived" name="cashreceived" title="يرجي ادخال الكمية  " required onkeyup="moneyconvertToNumber()">
                                </div>
                            </div>




                            <div class="col-lg-3 parent-label" id="start_at">
                                <label for="exampleFormControlSelect1"> {{ __('report.date') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <br>

                        </div>
                        <br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success print-style">
                                {{ __('home.Add') }}
                                <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                            </button>
                            <br>

                        </div>
                        <br>
                        <br>


                </div>

            </div>

        </div>
        @if ($data != null)
        <div class="col-xl-12">
            <div style="border-radius: 10px" class="card mg-b-20 p-2">
                <div class="card-body pb-0">
                    <div class="table-responsive pt-4 pb-2 mt-4">
                        <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0"> {{ __('home.date') }}</th>
                                    <th class="border-bottom-0">{{ __('home.employee') }}</th>
                                    <th class="border-bottom-0">{{ __('home.branch') }}</th>
                                    <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                    <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($data['payment_method'] == 'Cash') {
                                    $pay = __('report.cash');
                                } else {
                                    $pay = __('report.shabka');
                                }
                                $date =    explode(" ", $data['created_at'])
                                ?>

                                <td>{{ $date[0] }}</td>

                                <td>{{ Auth()->user()->name }}</td>
                                <td>{{ $data->branch->name }}</td>

                                <td>{{ $data['the_amount'] }}</td>
                                <td>{{ $pay }}</td>

                            </tbody>
                        </table>
                    </div>
                    <br />
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')
<!-- Internal Data tables -->

<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>

<script>
    function moneyconvertToNumber() {
        var input = document.getElementById("cashreceived");
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
        $('select[name="clientNosearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#debtamount').val(data['In_debt']);

                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone']);
                        $('#notes').val(data['comp_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });

        $('select[name="clientnamesearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);

                        $('#debtamount').val(data['In_debt']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone']);
                        $('#notes').val(data['comp_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });
</script>






@endsection