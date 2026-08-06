@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <style>
        .search-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        .parent-label { 
            font-weight: 600; 
            color: #1e293b; 
            margin-bottom: 8px; 
            display: block; 
            font-size: 13.5px; 
        }
        .form-control, .select2-container--default .select2-selection--single {
            height: 48px !important;
            padding: 10px 16px;
            border-radius: 12px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .input-group-text {
            border-radius: 0 12px 12px 0 !important;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: none;
            color: #3b82f6;
        }
        .fc-datepicker {
            border-radius: 12px 0 0 12px !important;
            border-right: none !important;
        }
        .btn-custom-search {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            padding: 12px 35px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-custom-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
            color: #fff;
        }
        .btn-custom-print {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            padding: 12px 30px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.25);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-custom-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35);
            color: #fff;
        }
        table.invoice-table thead th { 
            background-color: #1e293b !important; 
            color: #fff !important; 
            border: none !important;
            padding: 14px !important;
            font-size: 14px;
        }
        .table-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }
    </style>
@endsection

@section('title')
    {{ __('report.Best selling products') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.Reports') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('report.Best selling products') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong><i class="fas fa-exclamation-triangle ml-1"></i> خطأ</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            
            <!-- كارد الفلترة والبحث المتقدم -->
            <div class="card search-card mg-b-20 p-4">
                <div class="card-header pb-3 bg-transparent border-0 px-0 pt-0">
                    <h5 class="text-dark font-weight-bold mb-0 d-flex align-items-center">
                        <i class="fas fa-filter text-success mr-2"></i> {{ __('home.advanced_search_filters') ?? 'فلترة بيانات التقرير' }}
                    </h5>
                    <hr class="mt-3 mb-3 border-light">
                </div>
                
                <div class="card-body px-0 pb-0 pt-0">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/Best_selling_products') }}" method="POST" role="search" autocomplete="off">
                        @csrf

                        <div class="row">
                            <!-- الفرع -->
                            <div class="col-lg-4 mg-b-15" id="type">
                                <label class="parent-label">{{ __('users.branch') }}</label>
                                <select class="form-control select2" name="branch" required>
                                    <option value="-" selected>{{ __('users.allbranchs') }}</option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- إلى التاريخ -->
                            <div class="col-lg-4 mg-b-15" id="end_at">
                                <label class="parent-label">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <input class="form-control fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- من التاريخ -->
                            <div class="col-lg-4 mg-b-15" id="start_at">
                                <label class="parent-label">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- زر البحث -->
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-custom-search">
                                <i class="las la-search fs-18 ml-1"></i> {{ __('home.search') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- عرض النتائج -->
            @if (isset($bestselling))
                <div class="card table-card p-4 mg-b-20">
                    @php
                
                        $i = 0;
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-hover table-striped text-center invoice-table mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ __('home.productNo') }}</th>
                                    <th>{{ __('home.productname') }}</th>
                                    <th>{{ __('users.branch') }}</th>
                                    <th>{{ __('report.Number of pieces sold') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bestselling as $product)
                                    @php
                                        if ($i == 0) {
                                          
                                            $i++;
                                        }
                                    @endphp
                                    <tr>
                                        <td dir="ltr" class="font-weight-bold text-muted">{{ $product['productcode'] }}</td>
                                        <td class="font-weight-bold text-dark">{{ $product['productname'] }}</td>
                                        <td><span class="badge badge-light px-3 py-1 font-weight-bold" style="font-size: 12.5px;">{{ $product['branch'] }}</span></td>
                                        <td><span class="badge badge-success px-3 py-2" style="font-size: 13.5px;">{{ $product['numberofsall'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- زر الطباعة -->
                    <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                        <a class="btn btn-custom-print" href="{{ url('/' . ($page = 'printBest_selling_products') . '/' . ($branch_id ?? '-') . '/' . ($start_at ?: 'all') . '/' . ($end_at ?: 'all')) }}">
                            <i class="fas fa-print ml-2"></i> {{ __('home.print') }}
                        </a>
                    </div>
                </div>
            @endif

        </div>
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
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!-- Internal Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('.select2').select2({ width: '100%' });

            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            var timeout = 4000;
            $('.alert').delay(timeout).fadeOut(500);
        });
    </script>
@endsection