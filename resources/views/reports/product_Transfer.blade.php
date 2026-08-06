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
@section('title')
{{ __('home.Transfer of goods') }} @stop
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Transfer of goods') }}</h4>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطأ</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session()->has('notfountreturnproduct'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('notfountreturnproduct') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20 shadow-sm" style="border-radius: 10px;">
            <div class="card-body">
                
                <!-- Search Form -->
                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ('search_Transfer_products')) }}" method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-lg-3 mb-3" id="start_at">
                            <label class="form-label font-weight-bold"> {{ __('report.fromdate') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3" id="end_at">
                            <label class="form-label font-weight-bold"> {{ __('report.todate') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input class="form-control fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="branch_from" class="form-label font-weight-bold">{{ __('home.choosebranch_sender') }}</label>
                            <select name="branch_from" id="branch_from" class="form-control select2">
                                @foreach (App\Models\branchs::get() as $section)
                                    <option value="{{ $section->id }}" {{ (isset($branch_from) && $branch_from == $section->id) ? 'selected' : '' }}> {{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="branch_to" class="form-label font-weight-bold">{{ __('home.choosebranch_reciver') }}</label>
                            <select name="branch_to" id="branch_to" class="form-control select2">
                                @foreach (App\Models\branchs::get() as $section)
                                    <option value="{{ $section->id }}" {{ (isset($branch_to) && $branch_to == $section->id) ? 'selected' : '' }}> {{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary px-5 py-2" style="border-radius: 6px;">
                            <i class="las la-search fs-16 ml-1"></i> {{ __('home.search') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>

        @if (isset($data['transctions']) && count($data['transctions']) > 0)
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-body">
                    
                    <?php
                    $startat = $data['start_at'] ?? '';
                    $endat = $data['end_at'] ?? '';
                    $branch_to = $data['branch_to'] ?? '';
                    $branch_from = $data['branch_from'] ?? '';
                    ?>

                    <div class="table-responsive">
                        <table class="table text-md-nowrap table-hover text-center" data-page-length='50'>
                            <thead>
                                <tr>
                                    <th class="border-bottom-0 text-danger">{{ __('home.Invoice_no') }}</th>
                                    <th class="border-bottom-0 text-danger">{{ __('home.date') }}</th>
                                    <th class="border-bottom-0 text-danger">{{ __('home.branch_sender') }}</th>
                                    <th class="border-bottom-0 text-danger">{{ __('home.employeesender') }}</th>
                                    <th class="border-bottom-0 text-danger">{{ __('home.branch_reciver') }}</th>
                                    <th class="border-bottom-0 text-danger">{{ __('home.stautes') }}</th>
                                    @can('System setting')
                                        <th class="border-bottom-0 text-danger">التكلفة بدون ضريبة</th>
                                    @endcan
                                    <th class="border-bottom-0 text-danger">{{ __('home.the amount') }}</th>
                                    <th class="border-bottom-0 text-danger">{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['transctions'] as $transction)
                                    <tr>
                                        <td class="font-weight-bold">{{ $transction->id }}</td>
                                        <td>{{ $transction->created_at }}</td>
                                        <td>{{ $transction->branchfrom->name ?? '' }}</td>
                                        <td>{{ $transction->userfrom->name ?? '' }}</td>
                                        <td>{{ $transction->branchto->name ?? '' }}</td>
                                        <td>
                                            @if($transction->reciveInvoiceNumber == 0)
                                                <span class="badge badge-danger p-2" style="font-size: 11px; border-radius: 4px;">{{ __('home.sendproduct') }}</span>
                                            @else
                                                <span class="badge badge-success p-2" style="font-size: 11px; border-radius: 4px;">{{ __('home.reciveproduct') }}</span>
                                            @endif
                                        </td>
                                        @can('System setting')
                                            <td>{{ $transction->cost_withod_tax }}</td>
                                        @endcan
                                        <td>
                                            <?php
                                            $avt = App\Models\Avt::find(2);
                                            $saleavt = $avt ? $avt->AVT : 0;
                                            ?>
                                            {{ ($transction->Totalcost) + ($transction->Totalcost * $saleavt) }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-info" href="print_Transfer_products/{{ $transction->id }}">
                                                <i class="fas fa-print"></i> {{ __('home.show') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <a class="btn btn-info px-4 py-2" style="border-radius: 6px; background-color: #419BB2;" href="{{ url('/' . ('print_products_Transfer') . '/' . $branch_from . '/' . $branch_to . '/' . $startat . '/' . $endat) }}">
                            <i class="fas fa-print ml-1"></i> {{ __('home.print') }}
                        </a>
                    </div>

                </div>
            </div>
        @endif

    </div>
</div>
<!-- row closed -->
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
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

<script>
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        var timeout = 4000;
        $('.alert').delay(timeout).fadeOut(500);
    });
</script>
@endsection