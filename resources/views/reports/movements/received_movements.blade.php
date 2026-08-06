@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ __('home.received_movements_report') ?? 'تقرير الحركات الواردة' }}
@stop

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.received_movements_report') ?? 'تقرير الحركات الواردة' }}
                </h4>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- 1. قسم فلترة البحث والتاريخ -->
<div class="row">
    <div class="col-xl-12">
        <div class="card border-0 shadow-sm mg-b-20 rounded-lg">
            <div class="card-header bg-transparent pb-0 pt-3 border-bottom-0">
                <h5 class="card-title text-success font-weight-bold mb-0">
                    <i class="fas fa-filter ml-2"></i> {{ __('home.filter_received_report') }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.received') }}" method="GET" autocomplete="off">
                    <input type="hidden" name="branch_id"
                        value="{{ request('branch_id', auth()->user()->branchs_id) }}">

                    <div class="row align-items-end">
                        <!-- حقل البحث برقم الحركة -->
                        <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                            <label for="movement_id" class="font-weight-bold text-dark mb-1">{{ __('home.movement_number') }}</label>
                            <input type="number" name="movement_id" value="{{ request('movement_id') }}"
                                class="form-control rounded-pill px-3">
                        </div>

                        <!-- من تاريخ -->
                        <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                            <label for="from_date" class="font-weight-bold text-dark mb-1">{{ __('home.from_date') }}</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                class="form-control rounded-pill px-3">
                        </div>

                        <!-- إلى تاريخ -->
                        <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                            <label for="to_date" class="font-weight-bold text-dark mb-1">{{ __('home.to_date') }}</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                class="form-control rounded-pill px-3">
                        </div>

                        <!-- الأزرار (تم توسيع العمود لـ col-lg-6 لمنع انكسار الأزرار) -->
                        <div class="col-md-3 col-lg-6">
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <button type="submit"
                                    class="btn btn-success flex-fill rounded-pill shadow-sm mb-2 mb-xl-0 ml-1 text-white px-2 text-nowrap">
                                    <i class="fas fa-search ml-1"></i> {{ __('home.search') }}
                                </button>

                                <!-- زر إكسيل -->
                                <a href="{{ route('reports.recive.excel', request()->all()) }}"
                                    class="btn btn-success flex-fill rounded-pill shadow-sm mb-2 mb-xl-0 ml-1 text-white px-2 text-nowrap">
                                    <i class="fas fa-file-excel ml-1"></i> {{ __('home.excel') }}
                                </a>

                                <!-- زر PDF -->
                                <a href="{{ route('reports.recive.pdf', request()->all()) }}"
                                    class="btn btn-danger flex-fill rounded-pill shadow-sm mb-2 mb-xl-0 ml-1 text-white px-2 text-nowrap">
                                    <i class="fas fa-file-pdf ml-1"></i> {{ __('home.pdf') }}
                                </a>

                                <!-- زر إعادة الضبط -->
                                <a href="{{ route('reports.received', ['branch_id' => request('branch_id', auth()->user()->branchs_id)]) }}"
                                    class="btn btn-secondary flex-fill rounded-pill shadow-sm mb-2 mb-xl-0 px-2 text-nowrap">
                                    <i class="fas fa-undo ml-1"></i> {{ __('home.reset') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 2. جدول عرض حركات الاستلام الواردة عبر الـ AJAX -->
<div class="row">
    <div class="col-xl-12">
        <div class="card border-0 shadow-sm mb-4 rounded-lg">
            <div class="card-body px-0 pb-0">
                <div class="px-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-success font-weight-bold mb-0">
                        <i class="fas fa-list-alt ml-2"></i> {{ __('Received Movements Report') }}
                        {{ __('home.received_movements_report') }}
                    </h5>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card border-0 shadow-sm mb-4 rounded-lg">
                            <div class="card-body px-0 pb-0" id="table-container">
                                @include('reports.movements.partials.received_movements_table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. فصل المودالات خارج الجدول لمنع تشوه التصميم -->
@foreach($receivedMovements as $movement)
<div class="modal fade" id="itemsModal{{ $movement->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">{{ __('home.receive_movement_details_title', ['id' => $movement->id]) }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle text-center mb-0"
                        style="vertical-align: middle;">
                        <thead class="bg-light text-dark border-bottom">
                            <tr>
                                <th class="py-3 font-weight-bold text-dark">
                                    {{ __('home.product_name') }}
                                </th>
                                <th class="py-3 font-weight-bold text-dark">
                                    {{ __('home.quantity_received') }}
                                </th>
                                <th class="py-3 font-weight-bold text-dark">
                                    {{ __('home.unit_price') }}
                                </th>
                                <th class="py-3 font-weight-bold text-dark">
                                    {{ __('home.total') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movement->items as $item)
                            <tr class="transition-hover">
                                <td class="font-weight-bold text-dark text-right pr-3" style="font-size: 0.95rem;">
                                    <i class="fas fa-box text-success ml-1"></i>
                                    {{ $item->product->product_name ?? __('home.unspecified_product') }}
                                </td>
                                <td>
                                    <span class="badge badge-light border px-3 py-2 text-success font-weight-bold"
                                        style="font-size: 0.9rem;">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark font-weight-bold" style="font-size: 0.95rem;">
                                        {{ number_format($item->cost_per_each_withoud_tax, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success font-weight-bold" style="font-size: 1rem;">
                                        {{ number_format($item->quantity * $item->cost_per_each_withoud_tax, 2) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-2 text-black-50"></i>
                                    <p class="mb-0 font-weight-bold">
                                        {{ __('home.no_products_found') }}
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.close') }}</button>
            </div>
        </div>
    </div>
</div>
@endforeach

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
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
$(document).on('click', '.ajax-pagination a, .pagination a', function(e) {
    e.preventDefault();
    let url = $(this).attr('href');

    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
            $('#table-container').css('opacity', '0.5');
        },
        success: function(data) {
            $('#table-container').html(data);
            $('#table-container').css('opacity', '1');

            let container = $('#table-container');
            if (container.length) {
                $('html, body').animate({
                    scrollTop: container.offset().top - 100
                }, 300);
            }
        },
        error: function() {
            $('#table-container').css('opacity', '1');
            alert('حدث خطأ أثناء تحميل البيانات.');
        }
    });
});

$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'اختر الفرع',
        width: '100%'
    });

    $('#example1').DataTable({
        "language": {
            "sProcessing": "جاري التحميل...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يُعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sSearch": "بحث سريـــع:",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        },
        "order": [
            [0, "asc"]
        ]
    });
});
</script>
@endsection
