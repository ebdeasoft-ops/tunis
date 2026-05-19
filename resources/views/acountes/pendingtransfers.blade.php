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
{{ __('home.pendingtransfers') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.pendingtransfers') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    @if (session()->has('transferupdated'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>

        <strong>{{ session()->get('transferupdated') }}</strong>
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

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0"> {{__('home.date')}}</th>
                                    <th class="border-bottom-0">{{__('accountes.Theamountpaid')}} {{__('report.cash')}}</th>
                                    <th class="border-bottom-0">{{__('accountes.Theamountpaid')}} {{__('report.shabka')}}</th>
                                    <th class="border-bottom-0">{{ __('home.Bank_transfer') }}</th>
                                    <th class="border-bottom-0">{{ __('home.total') }}</th>

                                    <th class="border-bottom-0">{{__('home.branch')}}</th>
                                    <th class="border-bottom-0">{{__('home.stautes')}}</th>
                                    <th class="border-bottom-0">{{__('home.operations')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\transferMoney_to_mainbranch::where('status','!=',1)->where('from_user_id',Auth()->user()->id)->get() as $tansaction)
                                <tr>

                                    <td>{{$tansaction['created_at']}}</td>
                                    <td>{{$tansaction['amount']}}</td>
                                    <td>{{$tansaction['Pay_Method_Name']}}</td>
                                    <td>{{$tansaction['bank_transfer']}}</td>
                                    <td>{{$tansaction['bank_transfer']+$tansaction['Pay_Method_Name']+$tansaction['amount']}}</td>
                                    <td>{{$tansaction->branch->name}}</td>
                                    <?php
                                    $id = $tansaction->id;
                                    ?>

                                    @if($tansaction->status==0)
                                    <td>{{__('home.Notacceptedyet')}}</td>
                                    @else

                                    <td><span style="color:red">{{__('home.reject')}}</span></td>

                                    @endif

                                    <td>
                                        @if($tansaction->status==0)

                                        <div class="d-flex justify-content-center">


                                            <a style="height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id="{{$tansaction['id']}}" data-cash="{{ $tansaction['amount']}}" data-shabka="{{$tansaction['Pay_Method_Name'] }}" data-banktransfer="{{$tansaction['bank_transfer']}}" data-toggle="modal" href="#increaseProduct" title="تعديل">
                                                {{ __('home.updatedecoument') }}</a>

                                        </div>
                                        @endif
                                        <div class="row  d-flex justify-content-end mt-3">
                                            <form action="{{ '/' . ($page = 'print_Transfer_Main_Branch') }}" method="POST" role="search" autocomplete="off">
                                                {{ csrf_field() }}



                                                <div class='col ' id="printdiv">
                                                    <input type="number" class="form-control " name="id" id="id" title=" رقم الفاتورة " value="{{$tansaction['id']}}" hidden>


                                                    <button style="background-color: #419BB2;font-size:12px;width:80px;height:20px" type="submit" class="btn btn-success p-1 px-2 fw-bolder">
                                                        {{ __('home.print') }}
                                                        <svg style="width: 12px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                                        </svg>
                                                    </button>





                                                </div>


                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

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
<div class="modal fade" id="increaseProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div style="margin: 5% !important;" class="modal-dialog modal-special" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('home.updatedecoument') }}</h5>
                <button type="button" class="close choose-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updateTransfertomainbranchnotconfirm')) }}" method="POST" role="search" name="form-name" autocomplete="off">
                {{ csrf_field() }}

                <div class="modal-body">

                    <div class="form-group">
                        <input type="hidden" name="transactionId" id="transactionId" value="">

                    </div>



                    <div class="row">


                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label">{{ __('home.chooseemployeereciver') }}</label>
                            <select name="usertoupdate" id="usertoupdate" class="form-control parent-input">
                                <!--placeholder-->
                                @foreach (App\Models\user::get() as $section)
                                <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mb-2" id="type">
                            <p class="mg-b-10 parent-label"> {{ __('report.cash') }} </p>
                            <input class="form-control parent-input" name="cashreceivedupdate" title="يرجي ادخال مبلغ  النقدي  " id="cashreceivedupdate" value=0>

                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label"> {{ __('report.shabka') }}
                            </label>
                            <input type="number" value=0 class="form-control parent-input" id="payupdate" name="payupdate" title="يرجي ادخال مبلغ  الشبكة  ">
                        </div>

                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label"> {{ __('home.Bank_transfer') }}
                            </label>
                            <input type="number" value=0 class="form-control parent-input" id="Bank_transferupdate" name="Bank_transferupdate" title="يرجي ادخال مبلغ  الشبكة  ">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    <button type="submit" class="btn btn-danger">{{ __('home.confirm') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<!-- Internal Data tables -->

<!-- <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script> -->
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!-- <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script> -->
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->

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
    $(function() {
        var timeout = 4000; // in miliseconds (3*1000)
        $('.alert').delay(timeout).fadeOut(500);
    });

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

    $('#increaseProduct').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)

        var id = button.data('id')
        var bank_transfer = button.data('banktransfer')
        var shabka = button.data('shabka')
        var cash = button.data('cash')
        var modal = $(this)


        modal.find('.modal-body #transactionId').val(id);
        modal.find('.modal-body #cashreceivedupdate').val(cash);
        modal.find('.modal-body #payupdate').val(shabka);
        modal.find('.modal-body #Bank_transferupdate').val(bank_transfer);

    })
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




@endsection