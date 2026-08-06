@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
    
    <style>
        .search-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }
        .parent-label { font-weight: 600; color: #495057; margin-bottom: 8px; display: block; font-size: 13px; }
        
        /* ضبط اتجاه حقول التاريخ لكتابة الأرقام بشكل صحيح ومريح */
        .fc-datepicker {
            direction: ltr !important;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }} !important;
        }

        .dataTables_wrapper .dataTables_filter { float: left !important; text-align: left; }
        .dataTables_wrapper .dataTables_length { float: right !important; text-align: right; }
        table.dataTable thead th { text-align: center !important; background-color: #23395D !important; color: #fff !important; }
        .form-control, .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 8px) !important;
            padding: 6px 12px;
            border-radius: 8px !important;
            border: 1px solid #ced4da !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
@endsection

@section('title')
    {{ __('report.customerـpurchases') }}
@endsection

@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('report.customerـpurchases') }}</h4>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->
    </div>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{ __('home.error') ?? 'Error' }}</strong>
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
            
            <!-- قسم البحث الفاخر -->
            <div class="card search-card mg-b-20 p-4">
                <div class="card-header pb-3 bg-transparent border-0 px-0 pt-0">
                    <h5 class="text-primary font-weight-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-filter mr-2"></i> {{ __('home.advanced_search_filters') ?? 'Advanced Search & Filters' }}
                    </h5>
                    <hr class="mt-2 mb-0">
                </div>
                <div class="card-body px-0 pb-0 pt-2">
                    <form action="{{ route('customerـpurchases', app()->getLocale()) }}" method="POST" role="search" autocomplete="off">
                        @csrf
                        <div class="row">
                                        <!-- From Date -->
                            <div class="col-lg-3 mg-b-15" id="start_at">
                                <label class="parent-label"><i class="fas fa-calendar-alt text-muted mr-1"></i> {{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    @if(app()->getLocale() == 'ar')
                                        <input class="form-control parent-input fc-datepicker border-left-0" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required style="border-radius: 8px 0 0 8px !important;">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-light border-left-0" style="border-radius: 0 8px 8px 0;">
                                                <i class="fas fa-calendar text-primary"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                                                <i class="fas fa-calendar text-primary"></i>
                                            </div>
                                        </div>
                                        <input class="form-control parent-input fc-datepicker border-left-0" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required style="border-radius: 0 8px 8px 0 !important;">
                                    @endif
                                </div>
                            </div>

                            <!-- To Date -->
                            <div class="col-lg-3 mg-b-15" id="end_at">
                                <label class="parent-label"><i class="fas fa-calendar-alt text-muted mr-1"></i> {{ __('report.todate') }}</label>
                                <div class="input-group">
                                    @if(app()->getLocale() == 'ar')
                                        <input class="form-control parent-input fc-datepicker border-left-0" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required style="border-radius: 8px 0 0 8px !important;">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-light border-left-0" style="border-radius: 0 8px 8px 0;">
                                                <i class="fas fa-calendar text-primary"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                                                <i class="fas fa-calendar text-primary"></i>
                                            </div>
                                        </div>
                                        <input class="form-control parent-input fc-datepicker border-left-0" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required style="border-radius: 0 8px 8px 0 !important;">
                                    @endif
                                </div>
                            </div>
                            <!-- Customer Name -->
                            <div class="col-lg-4 mg-b-15">
                                <label class="parent-label"><i class="fas fa-user text-muted mr-1"></i> {{ __('home.searchbyclientname') }}</label>
                                <select class="form-control parent-input" name="UserId" id="clientnamesearch" required>
                                    <option value="-">{{ __('home.searchbyclientname') }}</option>
                                </select>
                            </div>

                            <!-- Branch -->
                            <div class="col-lg-2 mg-b-15">
                                <label class="parent-label"><i class="fas fa-code-branch text-muted mr-1"></i> {{ __('users.branch') }}</label>
                                <select class="form-control parent-input select2" name="branch" required>
                                    <option value="-" {{ (isset($branch_id) && $branch_id == '-') ? 'selected' : '' }}>{{ __('users.allbranchs') }}</option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                        <option value="{{ $branch->id }}" {{ (isset($branch_id) && $branch_id == $branch->id) ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                
                        </div>

                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <button type="submit" class="btn btn-success print-style px-5 py-2 shadow-sm font-weight-bold" style="border-radius: 8px; font-size: 15px;">
                                <i class="las la-search fs-16 ml-1"></i> {{ __('home.search') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if (isset($Invoices))
                <div style="border-radius: 12px;" class="card m-0 p-3 shadow-sm border bg-white">
                    
                    <!-- Header with Title & Export Buttons properly spaced -->
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <h5 class="text-dark mb-0 font-weight-bold"><i class="fas fa-file-invoice text-primary mr-2"></i> {{ __('home.invoices_results') ?? 'Invoices Results' }}</h5>
                        </div>
                        <div class="col-md-6 text-md-right mt-2 mt-md-0">
                            <div id="export_buttons_container" class="d-inline-flex gap-2"></div>
                        </div>
                    </div>

                    <div class="table-responsive hoverable-table bg-white p-2 rounded">
                        <table class="table table-hover table-striped table-bordered text-center align-middle" id="example1" data-page-length='10' style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ __('home.Invoice_no') }}</th>
                                    <th>{{ __('home.sallerName') }}</th>
                                    <th>{{ __('home.clietName') }}</th>
                                    <th>{{ __('home.date') }}</th>
                                    <th>{{ __('home.branch') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                    <th>{{ __('home.paymentmethod') }}</th>
                                    <th>{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $startat = $Invoices->first()->created_at ?? '';
                                    $endat = $Invoices->last()->created_at ?? '';
                                    $saleavt = $avt->AVT ?? 0;
                                @endphp

                                @foreach ($Invoices as $product)
                                    @php
                                        $pay = match($product->Pay) {
                                            'Cash' => __('report.cash'),
                                            'Shabka' => __('report.shabka'),
                                            'Credit' => __('report.credit'),
                                            'Bank_transfer' => __('home.Bank_transfer'),
                                            default => __('home.Partition of the amount'),
                                        };

                                        $totalPrice = ($product->Price - $product->discount) + (($product->Price - $product->discount) * $saleavt);
                                    @endphp
                                    <tr>
                                        <td class="font-weight-bold text-primary">{{ $product->id }}</td>
                                        <td>{{ $product->user->name ?? '' }}</td>
                                        <td dir="ltr" class="font-weight-bold">{{ $product->customer->name ?? '' }}</td>
                                        <td>{{ $product->created_at }}</td>
                                        <td>{{ $product->branch->name ?? '' }}</td>
                                        <td class="text-success font-weight-bold">{{ round($totalPrice, 2) }}</td>
                                        <td><span class="badge badge-info p-2">{{ $pay }}</span></td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary px-3 py-1 d-inline-flex align-items-center justify-content-center" href="{{ url('showInvoiceRecent/' . $product->id) }}">
                                                <i class="fas fa-print mr-1"></i>
                                                {{ __('home.show') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="my-3">
                        <hr style="border-top: 2px dashed rgba(0,0,0,.1)">
                    </div>

                    @if ($Invoices->count() >= 1 && isset($userid))
                        <div class="d-flex justify-content-center">
                            <a style="background-color: #419BB2; font-size:16px;" class="btn btn-success px-5 py-2 text-white shadow-sm d-flex align-items-center rounded-pill"
                                href="{{ url('/print_customerـpurchases/' . $userid[1] . '/' . $userid[0] . '/' . $startat . '/' . $endat) }}">
                                <i class="fas fa-print mr-2"></i>
                                {{ __('home.print') }} {{ __('home.full_customer_report') ?? 'Full Customer Report' }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <!-- Internal Data tables & Export Buttons Scripts -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>

    <!-- Internal Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            var table = $('#example1').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "{{ __('home.all') ?? 'All' }}"]],
                language: {
                    search: "{{ __('home.quick_search') ?? 'Quick Search:' }}",
                    lengthMenu: "{{ __('home.show_menu_records') ?? 'Show _MENU_ records' }}",
                    info: "{{ __('home.table_info') ?? 'Showing _START_ to _END_ of _TOTAL_ entries' }}",
                    paginate: {
                        first: "{{ __('home.first') ?? 'First' }}",
                        last: "{{ __('home.last') ?? 'Last' }}",
                        next: "{{ __('home.next') ?? 'Next' }}",
                        previous: "{{ __('home.previous') ?? 'Previous' }}"
                    }
                },
                buttons: [
                    { extend: 'excel', text: '<i class="fas fa-file-excel mr-1"></i> Excel', className: 'btn btn-success btn-sm shadow-sm' },
                    { extend: 'csv', text: '<i class="fas fa-file-csv mr-1"></i> CSV', className: 'btn btn-info btn-sm shadow-sm' },
                    { extend: 'print', text: '<i class="fas fa-print mr-1"></i> {{ __("home.print") }}', className: 'btn btn-secondary btn-sm shadow-sm' }
                ]
            });

            table.buttons().container().appendTo('#export_buttons_container');

            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });

            $('.select2:not(#clientnamesearch)').select2({
                width: '100%'
            });

            let searchedTerm = '';

            $('#clientnamesearch').select2({
                width: '100%',
                placeholder: "{{ __('home.searchbyclientname') }}",
                allowClear: true,
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('clientnamesearch.search') }}",
                    dataType: 'json',
                    delay: 350,
                    data: function(params) {
                        searchedTerm = params.term;
                        return { q: params.term };
                    },
                    processResults: function(data) {
                        if (!data || data.length === 0) {
                            $('#name').val(searchedTerm);
                            $('#clientnamesearch').select2('close');

                            if ($('#createcustomer').length) {
                                $('#createcustomer').modal('show');
                            }
                            return { results: [] };
                        } else {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: item.name + ' :- ' + (item.tax_no ?? '')
                                }))
                            };
                        }
                    }
                }
            });

            setTimeout(function() {
                $('.alert').fadeTo(500, 0, function() {
                    $(this).remove();
                });
            }, 4000);
        });
    </script>
@endsection