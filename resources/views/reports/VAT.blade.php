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
{{ __('report.VAT') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.VAT') }}

                </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'VAT')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-lg-3" id="start_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-3" id="end_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->

                            </div>
                            <div class="col-lg-3" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control parent-input" name="branch" required>
                                    <option value="-" selected>{{ __('users.allbranchs') }}
                                    </option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                    <option style="font-size: 15px" value="{{ $branch->id }}"> {{ $branch->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                        </div><br>
                        <div class="d-flex justify-content-center mb-3">
                            <button class="btn btn-success print-style p-1">
                                {{ __('home.search') }}
                                <i style=" height: 100;
                                                 
                                                 font-size:15px" class="las la-search"></i>
                            </button>
                        </div>


                    </form>

                </div>
                @if (isset($data))
                <div style="border-radius: 10px" class="card m-3 p-3">
                    <div class="table-responsive">
                        <div class="table-responsive hoverable-table table-padding">
                            <table class="table table-bordered table-striped table-hover" id="example1" data-page-length='50' style=" text-align: center;">
                                <thead>
                                    <tr>
                                        <th>-</th>
                                        <th>{{__('home.Numberofinvoices')}}</th>
                                        <th>{{__('home.addedValue')}}</th>
                                    </tr>

                                </thead>


                                <tbody>
                                    <tr>

                                        <td class="border-bottom-0" > {{ __('report.VATsales') }}</td>
                                        <td class="border-bottom-0"> {{ $data['countsales'] }}</td>
                                        <td class="border-bottom-0" style="color:green"> {{ round($data['totalVatSales'] ,2)}} {{__('home.SAR')}}</td>

                                    </tr>
                                    <tr>

                                        <td class="border-bottom-0"> {{ __('home.returnsalestax') }}</td>
                                        <td class="border-bottom-0"> {{ $data['returncountsales'] }}</td>
                                        <td class="border-bottom-0" style="color:yello"> {{ round($data['salesreturntax'] ,2)}} {{__('home.SAR')}}</td>

                                    </tr>
                                    <tr>

                                        <td class="border-bottom-0"> {{ __('home.saletaxfinal') }}</td>
                                        <td class="border-bottom-0"> -</td>
                                        <td class="border-bottom-0" style="color:red"> {{ round($data['totalVatSales'] ,2)}} {{__('home.SAR')}}</td>

                                    </tr>
                                    <tr>
                                        <td >{{ __('home.expensesVAT') }}</td>
                                        <td>{{ $data['countexpanses'] }}</td>
                                        <td style="color:red">{{ round($data['totalvarExpenses'],2) }} {{__('home.SAR')}}</td>

                                    </tr>
                                    <tr>
                                        <td>{{ __('report.VATparchese') }}</td>
                                        <td>{{ $data['countpurchase'] }}</td>
                                        <td style="color:green">{{ round($data['totalVatPrachese']+$data['purachasereturntax'],2) }} {{__('home.SAR')}}</td>

                                    </tr>
                                    <tr>
                                        <td>{{ __('home.returnpurchasetax') }}</td>
                                        <td>{{ $data['returncountpurchases'] }}</td>
                                        <td>{{ round($data['purachasereturntax'],2) }} {{__('home.SAR')}}</td>

                                    </tr>
                                    <tr>
                                        <td>{{ __('home.purchasetaxfinal') }}</td>
                                        <td>{{ $data['countpurchase'] }}</td>
                                        <td style="color:red">{{ round($data['totalVatPrachese'],2) }} {{__('home.SAR')}}</td>

                                    </tr>
                                    <tr>
                                        <td>{{ __('home.Vatrequest') }}</td>
                                        <td>-</td>
                                        <td style=" color:black;font-size:22px">{{ round($data['totalVatSales']-$data['totalvarExpenses']-round($data['totalVatPrachese'],2),2)  }} {{__('home.SAR')}}</td>

                                    </tr>

                                </tbody>

                            </table>




                            <div class="d-flex justify-content-center my-3">

                                <a style="background-color: #419BB2;font-size:17px" class="btn btn-success p-1" href="{{ url('/' . ($page = 'print_VAT') . '/' . $branch_id . '/' . $data['start_at'] . '/' . $data['end_at']) }}">
                                    {{ __('home.print') }}
                                    <svg style="width: 20px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                        <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                    </svg>
                                </a>
                            </div>
                            <br>
                            @endif
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
<!-- Internal Data tables -->

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
    $(document).ready(function() {

        // $('#invoice_number').hide();

        // $('input[type="radio"]').click(function() {
        //     if ($(this).attr('id') == 'type_div') {
        //         $('#invoice_number').hide();
        //         $('#type').show();
        //         $('#start_at').show();
        //         $('#end_at').show();
        //     } else {
        //         $('#invoice_number').show();
        //         $('#type').hide();
        //         $('#start_at').hide();
        //         $('#end_at').hide();
        //     }
        // });
    });
</script>


@endsection