@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
    
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@stop

@section('title')
    {{ __('hr.show_employees') }}
@endsection

@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-chart-line text-primary ml-2"></i> لوحة تحليل بيانات الموظفين والرواتب والجنسيات</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">

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

            <!-- قسم الرسوم البيانية المتقدمة (Charts) -->
            <div class="row row-sm mb-4">
                <!-- مخطط توزيع الموظفين حسب الأقسام -->
                <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-transparent pb-0">
                            <h6 class="card-title mb-0 font-weight-bold text-dark"><i class="fas fa-chart-pie text-info ml-1"></i> توزيع الموظفين حسب الأقسام</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 260px; position: relative;">
                                <canvas id="departmentsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- مخطط توزيع الموظفين حسب الجنسيات -->
                <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-transparent pb-0">
                            <h6 class="card-title mb-0 font-weight-bold text-dark"><i class="fas fa-globe text-warning ml-1"></i> توزيع الموظفين حسب الجنسيات</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 260px; position: relative;">
                                <canvas id="nationalitiesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- مخطط إجمالي الرواتب والبدلات الثابتة -->
                <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-transparent pb-0">
                            <h6 class="card-title mb-0 font-weight-bold text-dark"><i class="fas fa-chart-bar text-success ml-1"></i> إجمالي الرواتب والبدلات الثابتة</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 260px; position: relative;">
                                <canvas id="allowancesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول عرض البيانات -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap pb-3">
                    <h5 class="card-title mb-0 font-weight-bold text-dark"><i class="fas fa-table text-primary ml-1"></i> تفاصيل سجلات الموظفين</h5>
                    <!-- مكان مستقل لزر تصدير الإكسيل ليكون شكله مرتباً -->
                    <div id="exportButtonContainer"></div>
                </div>
                <div class="card-body px-4 py-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered text-center align-middle" id="example1" data-page-length='50' style="width:100%">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('home.date') }}</th>
                                    <th>{{ __('hr.name') }}</th>
                                    <th>{{ __('hr.Id') }}</th>
                                    <th>{{ __('hr.nationality') ?? 'الجنسية' }}</th>
                                    <th>{{ __('hr.email') }}</th>
                                    <th>{{ __('hr.phone') }}</th>
                                    <th>{{ __('hr.department') }}</th>
                                    <th>{{ __('hr.salary') }}</th>
                                    <th>{{ __('hr.housing_allowance') ?? 'بدل السكن' }}</th>
                                    <th>{{ __('hr.transportation_allowance') ?? 'بدل الانتقال' }}</th>
                                    <th>{{ __('hr.other_allowances') ?? 'بدلات أخرى' }}</th>
                                    <th>{{ __('hr.age') }}</th>
                                    <th>{{ __('hr.sex') }}</th>
                                    <th>{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach (App\Models\employee::get() as $employee)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $employee->created_at ? $employee->created_at->format('Y-m-d') : '-' }}</td>
                                        <td class="font-weight-bold">{{ __('hr.phone') == 'رقم الجوال' ? $employee->name_ar : ($employee->name_en ?? $employee->name_ar) }}</td>
                                        <td>{{ $employee->personal_identification }}</td>
                                        <td><span class="badge badge-warning px-2 py-1">{{ $employee->nationality ?? 'غير محدد' }}</span></td>
                                        <td>{{ $employee->email }}</td>
                                        <td dir="ltr">{{ $employee->phone }}</td>
                                        <td>
                                            @if($employee->departments)
                                                <span class="badge badge-info px-2 py-1">
                                                    {{ __('hr.phone') == 'رقم الجوال' ? $employee->departments->name_ar : $employee->departments->name_en }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-success font-weight-bold">{{ number_format($employee->salary, 2) }}</td>
                                        <td>{{ number_format($employee->housing_allowance ?? 0, 2) }}</td>
                                        <td>{{ number_format($employee->transportation_allowance ?? 0, 2) }}</td>
                                        <td>{{ number_format($employee->other_allowances ?? 0, 2) }}</td>
                                        <td>{{ $employee->old }}</td>
                                        <td>{{ $employee->sex == 'male' ? __('hr.male') : __('hr.female') }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-info px-3" href="{{ url('updateEmployee/' . $employee->id) }}" style="background-color: #419BB2; border-color: #419BB2;">
                                                <i class="las la-pen"></i> تعديل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>

<!-- Chart.js لجلب الرسوم البيانية المتطورة -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Internal Nice-select js-->
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>

<!--Internal Parsley.min js -->
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<!-- Internal Form-validation js -->
<script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>

<script>
    @php
        // 1. تجهيز بيانات الأقسام
        $deptData = App\Models\employee::with('departments')->get()->groupBy(function($item) {
            return $item->departments ? $item->departments->name_ar : 'بدون قسم';
        });
        $deptLabels = $deptData->keys();
        $deptCounts = $deptData->map->count();

        // 2. تجهيز بيانات الجنسيات
        $natData = App\Models\employee::get()->groupBy(function($item) {
            return $item->nationality ?? 'غير محدد';
        });
        $natLabels = $natData->keys();
        $natCounts = $natData->map->count();

        // 3. تجهيز إجماليات الرواتب والبدلات
        $totalSalary = App\Models\employee::sum('salary');
        $totalHousing = App\Models\employee::sum('housing_allowance');
        $totalTransportation = App\Models\employee::sum('transportation_allowance');
        $totalOthers = App\Models\employee::sum('other_allowances');
    @endphp

    // رسم بياني الأقسام (Doughnut Chart)
    var ctxDept = document.getElementById('departmentsChart').getContext('2d');
    new Chart(ctxDept, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deptLabels) !!},
            datasets: [{
                data: {!! json_encode($deptCounts->values()) !!},
                backgroundColor: ['#419BB2', '#6259ca', '#53caed', '#ffc107', '#28a745', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // رسم بياني الجنسيات (Pie Chart)
    var ctxNat = document.getElementById('nationalitiesChart').getContext('2d');
    new Chart(ctxNat, {
        type: 'pie',
        data: {
            labels: {!! json_encode($natLabels) !!},
            datasets: [{
                data: {!! json_encode($natCounts->values()) !!},
                backgroundColor: ['#ffc107', '#28a745', '#17a2b8', '#6259ca', '#dc3545', '#343a40'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // رسم بياني الرواتب والبدلات (Bar Chart)
    var ctxAllowances = document.getElementById('allowancesChart').getContext('2d');
    new Chart(ctxAllowances, {
        type: 'bar',
        data: {
            labels: ['الرواتب', 'السكن', 'الانتقال', 'أخرى'],
            datasets: [{
                label: 'الإجمالي',
                data: [{{ $totalSalary }}, {{ $totalHousing }}, {{ $totalTransportation }}, {{ $totalOthers }}],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#6c757d'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: width = true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });

    $(document).ready(function() {
        var table = $('#example1').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                searchPlaceholder: 'بحث في السجلات...',
                sSearch: '',
                lengthMenu: '_MENU_ عنصر لكل صفحة',
                info: 'عرض _START_ إلى _END_ من إجمالي _TOTAL_ سجل',
                paginate: {
                    sFirst: 'الأول',
                    sLast: 'الأخير',
                    sNext: 'التالي',
                    sPrevious: 'السابق'
                }
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel ml-1"></i> تصدير إلى Excel',
                    className: 'btn btn-success btn-sm shadow-sm font-weight-bold',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });

        // نقل زر الإكسيل إلى الهيدر العلوي بجانب عنوان الجدول ليكون شكله احترافي ومنظم بعيداً عن حقل البحث
        table.buttons().container().appendTo('#exportButtonContainer');

        var timeout = 4000;
        $('.alert').delay(timeout).fadeOut(500);
    });
</script>
@endsection