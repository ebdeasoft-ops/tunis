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
    {{ __('home.Report_offer_to_customer') }}@stop
@endsection
@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.Report_offer_to_customer') }}

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
        @if (session()->has('notfountreturnproduct'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <br>
                <strong>{{ session()->get('notfountreturnproduct') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- row -->
        <div class="row">

            <div class="col-xl-12">
                <div class="card mg-b-20">


                    <div class="card-header pb-0">

                        <form
                            action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'show_offer_price_customer')) }}"
                            method="POST" role="search" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="row">

                                <div class="col-lg-3" id="start_at">
                                    <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div><input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}"
                                            name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                    </div><!-- input-group -->
                                </div>

                                <div class="col-lg-3" id="end_at">
                                    <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div><input class="form-control parent-input fc-datepicker" name="end_at"
                                            value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                    </div><!-- input-group -->
                                </div>

                                <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                    <p class="mg-b-10 parent-label"> {{ __('home.searchbyclientname') }} </p>
                                    <select class="form-control parent-input select2" name="UserId" required>

                                        <option value="-"> {{ __('home.searchbyclientname') }}</option>

                                        @foreach (App\Models\customers::get() as $section)
                                            <option style="font-size: 15px" value="{{ $section->id }}"> {{ $section->name }} -
                                                {{ $section->id }}</option>
                                        @endforeach
                                    </select>
                                </div><!-- col-4 -->

                                <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                    <input class="form-control parent-input" name="productNo" hidden=true>
                                </div>
                            </div><br>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-success print-style p-1">
                                    {{ __('home.search') }}
                                    <i
                                        style=" height: 100;
                                                 
                                                 font-size:15px"
                                        class="las la-search"></i>
                                </button>
                                <br>
                            </div>

                            <br>
                        </form>




                        @if (isset($Invoices))
                        <div style="border-radius: 10px" class="card pb-0 px-3">
                            <br>

                                <?php
                                $count = 0;
                                $startat = '';
                                $endat = '';
                                $totalprofit = 0;
                                ?>
                                @foreach ($Invoices as $invoice)
                                    <?php
                                    if ($count == 0) {
                                        $startat = $invoice->created_at;
                                    }
                                    $endat = $invoice->created_at;
                                    $count++;
                                    ?>

                                    <br>



                                    {{-- <span class="text-danger">{{ __('report.orderNo') }} : {{ $invoice->id }}</span>
                                    <br>
                                    <span class="text-danger">{{ __('users.branch') }} :
                                        {{ $invoice->branch->name }}</span>
                                    <br>
                                    <span class="text-danger">{{ __('home.clietName') }} :
                                        {{ $invoice->customer->name }}</span> --}}


                                    <div class="table-padding">
                                        <table class="table table-striped table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('report.orderNo') }}</th>
                                                    <th>{{ $invoice->id }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('users.branch') }}</th>
                                                    <th>{{ $invoice->branch->name }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('home.clietName') }}</th>
                                                    <th>{{ $invoice->customer->name }}</th>
                                                </tr>    
                                            </thead>    
                                        </table>    
                                    </div>


                                    <table class="table table-striped table-bordered text-center table-responsive">


                                        <thead>

                                          

                                            <tr>
                                                <th class="border-bottom-0">#</th>
                                                <th class="border-bottom-0">{{ __('report.date') }}</th>

                                                <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.price') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.addedValueperpice') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.discount') }}</th>

                                                <th class="border-bottom-0"> {{ __('home.total') }}</th>


                                            </tr>

                                        </thead>
                                        <?php
                                        $avt = App\Models\Avt::find(1);
                                        $saleavt = $avt->AVT;
                                        $i = 0;
                                        $profit = 0;
                                        ?>
                                        @foreach (App\Models\offer_price_to_customer_items::where('order_id', $invoice->id)->where('quantity', '!=', 0)->get() as $product)
                                            <?php
                                            $i++;
                                            $profit += (($product->quantity* $product->PriceWithoudTax)-$product->discount)+ ((($product->quantity* $product->PriceWithoudTax)-$product->discount)*$saleavt);
                                            $date = explode(' ', $product->created_at);
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $date[0] }}</td>

                                                    <td dir='ltr'>{{ $product->productData->Product_Code }}</td>
                                                    <td>{{ $product->productData->product_name }}</td>
                                                    <td>{{ $product->PriceWithoudTax  }}</td>
                                                    <td>{{$product->quantity}}</td>
                                                    <td>{{( $saleavt* $product->PriceWithoudTax)}}</td>
                                                    <td>{{$product->discount}}</td>

                                                    <td>{{(($product->quantity* $product->PriceWithoudTax)-$product->discount)+ ((($product->quantity* $product->PriceWithoudTax)-$product->discount)*$saleavt) }}</td>

                                                </tr>

                                            </tbody>
                                        @endforeach
                                    </table>


                                    <span class="text-warning  float-left mt-3 mr-2"
                                        id="print_Button">{{ __('home.total') }} : {{ $profit }}</span>
                                @endforeach


                                    <div>
                                        <hr style="border-top: 4px solid rgba(0,0,0,.3)">
                                    </div>

                                    
                                <br>

                                <br>
                                <div class="d-flex justify-content-center mb-2">
                                    <a style="background-color: #419BB2;font-size:17px" class="btn btn-success p-1"
                                        href="{{ url('/' . ($page = 'printReportoffer_price_customer') . '/' . $userId . '/' . $startat . '/' . $endat) }}">
                                        {{ __('home.print') }}
                                        <svg style="width: 20px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                        </svg>
                                    </a>
                                </div>
                                <br>
                            @endif


                        </div>

                        <br>

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

            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
    
        });
    </script>

@endsection
