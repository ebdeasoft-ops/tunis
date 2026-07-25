@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ __('home.received_movements') ?? 'تقرير المنتجات المستلمة' }}
@stop

@section('page-header')
    <div class="main-parent">
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.received_movements') ?? 'تقرير المنتجات المستلمة' }}</h4>
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
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-0">فلترة تقرير الاستلام</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.received') }}" method="GET" autocomplete="off">
                        <input type="hidden" name="branch_id" value="{{ request('branch_id', auth()->user()->branchs_id) }}">

                        <div class="row">
                            <div class="col-md-4">
                                <label for="from_date">من تاريخ</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label for="to_date">إلى تاريخ</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> بحث</button>
                                <a href="{{ route('reports.received', ['branch_id' => request('branch_id', auth()->user()->branchs_id)]) }}" class="btn btn-secondary btn-block mr-2 mt-0">
                                    <i class="fas fa-undo"></i> إعادة ضبط
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. جدول عرض حركات الاستلام -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap table-hover table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الحركة</th>
                                    <th class="border-bottom-0">من فرع</th>
                                    <th class="border-bottom-0">الموظف المرسِل</th>
                                    <th class="border-bottom-0">الموظف المستلِم</th>
                                    <th class="border-bottom-0">إجمالي التكلفة</th>
                                    <th class="border-bottom-0">عدد البنود</th>
                                    <th class="border-bottom-0">التاريخ</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receivedMovements as $index => $movement)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="badge badge-outline-success">#{{ $movement->id }}</span></td>
                                        <td><strong>{{ $movement->branchFrom->name ?? 'غير محدد' }}</strong></td>
                                        <td>{{ $movement->userFrom->name ?? '-' }}</td>
                                        <td>{{ $movement->userTo->name ?? '-' }}</td>
                                        <td><span class="text-success font-weight-bold">{{ number_format($movement->Totalcost, 2) }}</span></td>
                                        <td><span class="badge badge-info">{{ $movement->items->count() }} منتج</span></td>
                                        <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <!-- زر فتح مودال التفاصيل -->
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#itemsModal{{ $movement->id }}">
                                                <i class="fas fa-eye"></i> عرض المنتجات
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">لا توجد حركات استلام واردة لهذا الفرع بحسب الفلترة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        <h5 class="modal-title">تفاصيل حركة الاستلام رقم #{{ $movement->id }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered text-center mg-b-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>الكمية المستلمة</th>
                                    <th>سعر القطعة</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movement->items as $item)
                                    <tr>
                                        <td class="font-weight-semibold">{{ $item->product->product_name ?? 'منتج غير محدد' }}</td>
                                        <td><span class="badge badge-success px-3 py-1">{{ $item->quantity }}</span></td>
                                        <td>{{ number_format($item->cost_per_each_withoud_tax, 2) }}</td>
                                        <td>{{ number_format($item->quantity * $item->cost_per_each_withoud_tax, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
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

    <script>
        $(document).ready(function() {
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
                "order": [[ 0, "asc" ]]
            });
        });
    </script>
@endsection
