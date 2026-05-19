@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
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
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.convertboxtobank') }}

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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'ConvertBoxtobankReport')) }}" method="POST" role="search" autocomplete="off">
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
                            <div class="col-lg-4 mb-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.choosebranch_reciver') }}</label>
                                <select name="branch" id="branch" class="form-control parent-input">
                                    <!--placeholder-->
                                    @foreach (App\Models\branchs::get() as $section)
                                    <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                <input class="form-control parent-input" name="productNo" hidden=true>
                            </div>
                        </div><br>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-success print-style my-2">
                                {{ __('home.search') }}
                                <i style=" height: 100;
                                                 
                                                 font-size:15px" class="las la-search"></i>
                            </button>
                        </div>

                    </form>




                    @if (count($data)>0)
                    <div style="border-radius: 10px" class="card m-3 p-2 pb-0">
                        <?php
                        $userId = 0;
                        $count = 0;
                        ?>
                        <?php
                        $userId = 0;
                        $startat = '';
                        $endat = '';
                        $totalprice = 0;
                        $totaladdedvalue = 0;
                        $branchId = 0;
                        ?>


                       <div class="table-responsive mg-t-20 mt-3 mr-2 d-flex ">
                        <table style="border:2px solid rgba(0,0,0,.3);" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
 <thead>
                                    <tr>
                                        <th class="border-bottom-0"> {{ __('home.date') }}</th>
                                        <th class="border-bottom-0">{{ __('home.employee') }}</th>
                                        <th class="border-bottom-0">{{ __('home.branch') }}</th>
                                        <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                        <th class="border-bottom-0">{{ __('home.notesClient') }}</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($data as $operation)
                                    <?php
                                    if ($count == 0) {
                                        $startat = $operation->created_at;
                                    }
                                    $count++;
                                    $endat = $operation->created_at;

                                    $branchId = $operation->branch->id;
                                    if ($operation->payment_method == 'Cash') {
                                        $pay = __('report.cash');
                                    } else {
                                        $pay = __('report.shabka');
                                    }
                                    $date = explode(" ", $operation->created_at)
                                    ?>
                                    <tr>
                                        <td>{{ $date[0] }}</td>

                                        <td>{{ $operation->user->name }}</td>
                                        <td>{{ $operation->branch->name }}</td>

                                        <td>{{ $operation->amount }}</td>
                                        <td>{{ $operation->note }}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br />


                        @endif
                    </div>

                    <br>

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

        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

    });
</script>
@endsection