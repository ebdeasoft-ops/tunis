@extends('layouts.master')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css" rel="stylesheet">
<style>
    .table thead th { background-color: #f8f9fe; border-bottom: 2px solid #e1e5ef; color: #444; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .table tbody tr:hover { background-color: #f1f4f9; transition: 0.3s; }
    .badge { padding: 8px 12px; border-radius: 50px; font-size: 12px; }
    .btn-custom { border-radius: 8px; font-weight: 600; padding: 6px 15px; }
    .card-header-custom { background: #fff; border-bottom: 1px solid #eee; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <h4 class="content-title mb-0">{{ __('attendances.title') }}</h4>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
@endsection
@section('title') {{ __('attendances.title') }} @stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <!-- Header -->
            <div class="card-header-custom">
                <h5 class="mb-0 text-primary"><i class="fas fa-clock mr-2"></i> {{ __('attendances.title') }}</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-custom shadow-sm ml-2">
                        <i class="fas fa-plus"></i> {{ __('attendances.add_new') }}
                    </a>
                    
                    <a href="{{ route('attendances.template') }}" class="btn btn-info btn-custom shadow-sm text-white ml-2">
                        <i class="fas fa-download"></i> {{ __('attendances.download_template') }}
                    </a>

                    <button class="btn btn-success btn-custom shadow-sm" data-toggle="modal" data-target="#excelImportModal">
                        <i class="fas fa-file-excel"></i> {{ __('attendances.import_excel') }}
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- فلاتر البحث -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="font-weight-bold">{{ __('attendances.employee') }}:</label>
                        <select id="employee_filter" class="form-control">
                            <option value="">{{ __('attendances.employee') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">{{ __('attendances.status') }}:</label>
                        <select id="status_filter" class="form-control">
                            <option value="">{{ __('attendances.status') }}</option>
                            <option value="present">{{ __('attendances.present') }}</option>
                            <option value="late">{{ __('attendances.late') }}</option>
                            <option value="absent">{{ __('attendances.absent') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">{{ __('attendances.from_date') }}</label>
                        <input type="date" id="min_date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">{{ __('attendances.to_date') }}</label>
                        <input type="date" id="max_date" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="filter_btn" class="btn btn-primary btn-block">{{ __('attendances.search') }}</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover text-center" id="attendanceTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('attendances.employee') }}</th>
                                <th>{{ __('attendances.date') }}</th>
                                <th>{{ __('attendances.check_in') }}</th>
                                <th>{{ __('attendances.check_out') }}</th>
                                <th>{{ __('attendances.attendance_type') ?? 'نوع الحضور' }}</th>
                                <th>{{ __('attendances.status') }}</th>
                                <th>{{ __('attendances.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- البيانات تُجلب عبر الـ Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal الاستيراد -->
<div class="modal fade" id="excelImportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-excel mr-2"></i> {{ __('attendances.import_excel') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('attendances.import') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoader()">
                @csrf
                <div class="modal-body p-4">
                    <div class="custom-file mb-3">
                        <input type="file" name="file" class="custom-file-input" id="customFile" required>
                        <label class="custom-file-label" for="customFile">Choose Excel file...</label>
                    </div>
                    <small class="text-muted">Make sure the file contains columns (employee_id, date, check_in, check_out, status).</small>
                    
                    <div id="uploadLoader" style="display:none; text-align:center; margin-top:20px;">
                        <i class="fas fa-spinner fa-spin fa-2x text-success"></i>
                        <p class="text-success mt-2 font-weight-bold">Processing and uploading file, please wait...</p>
                    </div>
                </div>
                <div class="modal-footer" id="modalFooter">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Start Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
    <!-- DataTables JS & Buttons -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        function showLoader() {
            document.getElementById('uploadLoader').style.display = 'block';
            document.getElementById('submitBtn').disabled = true;
        }

        $(document).ready(function() {
            var table = $('#attendanceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('attendances.index') }}",
                    data: function (d) {
                        d.employee_id = $('#employee_filter').val();
                        d.status = $('#status_filter').val();
                        d.min_date = $('#min_date').val();
                        d.max_date = $('#max_date').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'employee_name', orderable: false, searchable: false },
                    { data: 'date', name: 'date' },
                    { data: 'check_in', orderable: false, searchable: false },
                    { data: 'check_out', orderable: false, searchable: false },
                    { data: 'attendance_type', orderable: false, searchable: false },
                    { data: 'status_formatted', orderable: false, searchable: false },
                    { data: 'action', orderable: false, searchable: false }
                ],
                dom: 'Brtip',
                buttons: ['excelHtml5', 'print'],
                language: {
                    url: "{{ app()->getLocale() == 'ar' ? '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json' : '//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json' }}"
                }
            });

            $('#filter_btn').click(function() {
                table.ajax.reload();
            });
        });
    </script>
@endsection