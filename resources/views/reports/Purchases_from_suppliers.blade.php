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
{{ __('home.purchases') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.purchases') }}

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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'Purchasesـfromـsuppliers')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-lg-2" id="start_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-2" id="end_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->

                            </div>
                            <div class="col-lg-2" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control parent-input" name="branch" required>
                                    <option value="-" selected>{{ __('users.allbranchs') }}
                                    </option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                    <option style="font-size: 15px" value="{{ $branch->id }}"> {{ $branch->name }}</option>
                                    @endforeach
                                </select>

                            </div >

                            <div class="col-lg-3 mg-t-20 mg-lg-t-0 mb-2" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.shearchbysuppliername') }}</p>
                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch" required>

                                    <option style="font-size: 15px" value="-">{{ __('home.suppliername') }}
                                    </option>
                                    @foreach (App\Models\supllier::get() as $section)
                                    <option style="font-size: 15px" value="{{ $section->id }}"> {{ $section->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div><!-- col-4 -->


                            <div class="col" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.paymentmethod') }} </p>
                                <select class="form-control parent-input" name="pay" required>
                                    <option value="-" style="font-size: 15px" selected>{{ __('home.paymentmethod') }}
                                    </option>
                                    <option style="font-size: 15px" value="Cash"> {{ __('report.cash') }}</option>
                                    <option style="font-size: 15px" value="Shabka "> {{ __('report.shabka') }} </option>
                                    <option style="font-size: 15px" value="Credit "> {{ __('report.credit') }} </option>
                                    'cash'=>'Cash',

                                </select>

                            </div class='row'>
                        </div><br>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-success print-style my-2 p-1">
                                {{ __('home.search') }}
                                <i style=" height: 100;
                                                 
                                                 font-size:15px" class="las la-search"></i>
                            </button>
                        </div>




                </div>
                </form>
                @if (isset($Invoices))
                <div style="border-radius: 10px" class="card px-3 m-3">
                    <?php
                    $userId = 0;
                    $count = 0;
                    ?>
                    <?php
                    $userId = 0;
                    $startat = '';
                    $endat = '';
                    $totalprice = 0;
                    $totalshipping = 0;
                    $totaladdedvalue = 0;
                    $totaldiscount = 0;
                    ?>


                    <div class="table-responsive hoverable-table">

                        <table class="table table-hover" id="example1" data-page-length='50' style=" text-align: center;">
                            <thead>
                                <tr>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.buyer name') }} </th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.suppliername') }}</th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.date') }}</th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.total') }}</th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.operations') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>

                                @foreach ($Invoices as $product)
                                <?php
                                $totalshipping += $product['shipping fee'] + $product['Other expenses'];
                                $totaldiscount += $product->discount;
                                if ($count == 0) {
                                    $userId = $product->user_id;
                                    $startat = $product->created_at;
                                }
                                $endat = $product->created_at;
                                $count++;

                                ?>
                                <?php $i++; ?>
                                <?php
                                $totalpricetable=0;
                                $addedvaluetable=0;
                                foreach (App\Models\orderDetails::where('order_owner', $product->orderId)->get() as $productaa) {

                                    $addedvaluetable+=$productaa->numberofpice * $productaa->purchasingـprice;
                                    $addedvaluetable+= $productaa->numberofpice * $productaa->Added_Value;
                                    $i++;
                                    $totalprice += $productaa->numberofpice * $productaa->purchasingـprice;
                                    $totaladdedvalue += $productaa->numberofpice * $productaa->Added_Value;
                                    $date = explode(' ', $product->created_at);
                                }
                                ?>
                                <tr id="<?php echo $product['id']; ?>">
                                    <td data-target="id">{{ $product->orderId }}</td>
                                    <?php
                                    $buyer = App\Models\orderTosupllier::find($product->orderId);
                                    ?>
                                    <td data-target="id">{{ $buyer->user->name }}</td>
                                    <td dir="ltr" data-target="id">
                                        {{ $product->supllier->name }}
                                    </td>
                                    <td data-target="numberofpice">{{ $product->created_at }}
                                    </td>
                                    <td data-target="numberofpice">{{ $product->branch->name }}
                                    </td>
                                    <td data-target="numberofpice">{{ $product->In_debt+$product['shipping fee'] }}</td>

                                    <?php
                                    $pays = '';
                                    if ($product->Pay_Method_Name == 'Cash') {
                                        $pays = __('report.cash');
                                    } elseif ($product->Pay_Method_Name == 'Shabka') {
                                        $pays = __('report.shabka');
                                    } else {
                                        $pays = __('report.credit');
                                    }

                                    ?>
                                    <td data-target="numberofpice">{{ $pays  }}</td>
                                    <td> <a class="dropdown-item" href="purchasesShow/{{ $product->orderId }}"><i style="fill:#072c3c !important" class="fas fa-print"></i>&nbsp;&nbsp;
                                            {{ __('home.show') }}
                                        </a>
                                        <br>
                                          <a class="modal-effect btn btn-sm btn-succees"   target="_blank"
href="{{ url('/' . ($page = 'openfile') .'/'. $product->attachments ) }}"    title="تعديل طريقة الدفع">{{ __('home.dwonloadpdf')}}<i class="fa-solid fa-download"></i></a></td>

                                </tr>
                                @endforeach

                                <div class="my-4">
                                    <hr style="border-top: 4px solid rgba(0,0,0,.3)">
                                </div>






                                <div class="table-padding">
                                    <table class="table table-bordered table-hover text-center table-striped mt-5">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('report.totalprice') }}</th>
                                                <th scope="col">{{ __('home.the amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ __('report.totalpricewithoudtax') }}</td>
                                                <td>{{ $totalprice }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('report.totaltax') }}</td>
                                                <td>{{ $totaladdedvalue }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('report.Shipping and unloading cost') }}</td>
                                                <td>{{ $totalshipping }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('home.discount') }}</td>
                                                <td>{{ $totaldiscount }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('report.totalallprice') }}</td>
                                                <td>{{ $totaladdedvalue + $totalprice + $totalshipping-$totaldiscount  }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>



                                <br>

                                <div class="d-flex justify-content-center">

                                    <a style="background-color: #419BB2;font-size:14px" class="btn btn-success" href="{{ url('/' . ($page = 'print_Purchasesـfromـsuppliers') . '/' . $pay[1] . '/' . $pay[0] .'/' . $pay[2] . '/' . $startat . '/' . $endat) }}">
                                        {{ __('home.print') }}
                                        <svg style="width: 17px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                        </svg>
                                    </a>
                                </div>
                                <br>
                                @endif
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
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
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

        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

    });
</script>

@endsection