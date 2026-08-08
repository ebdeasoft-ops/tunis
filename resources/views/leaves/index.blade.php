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
    <h4 class="content-title mb-0">{{ __('leaves.title') }}</h4>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
@endsection

@section('title') {{ __('leaves.title') }} @stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header-custom">
                <h5 class="mb-0 text-primary"><i class="fas fa-calendar-alt mr-2"></i> {{ __('leaves.title') }}</h5>
                <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-custom shadow-sm">
                    <i class="fas fa-plus"></i> {{ __('leaves.add_new') }}
                </a>
            </div>

            <div class="card-body">
                <!-- فلاتر البحث عبر Backend -->
                <form method="GET" action="{{ route('leaves.index') }}" class="row mb-4">
                    <div class="col-md-4">
                        <label class="font-weight-bold">{{ __('leaves.employee') }}:</label>
                        <select name="employee_id" class="form-control">
                            <option value="">{{ __('leaves.employee') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="font-weight-bold">{{ __('leaves.status') }}:</label>
                        <select name="status" class="form-control">
                            <option value="">{{ __('leaves.status') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('leaves.pending') }}</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('leaves.approved') }}</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('leaves.rejected') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">بحث</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover text-center" id="leavesTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('leaves.employee') }}</th>
                                <th>{{ __('leaves.type') }}</th>
                                <th>{{ __('leaves.start_date') }}</th>
                                <th>{{ __('leaves.end_date') }}</th>
                                <th>{{ __('leaves.days_count') }}</th>
                                <th>{{ __('leaves.deduction') }}</th>
                                <th>{{ __('leaves.status') }}</th>
                                <th>{{ __('leaves.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->id }}</td>
                                <td>{{ $leave->employee->name ?? '---' }}</td>
                                <td>{{ __('leaves.' . $leave->leave_type) }}</td>
                                <td>{{ $leave->start_date }}</td>
                                <td>{{ $leave->end_date }}</td>
                                <td>{{ $leave->days_count }}</td>
                                <td>{{ $leave->deduction_amount }}</td>
                                <td>
                                    <span class="badge {{ $leave->status == 'approved' ? 'bg-success text-white' : ($leave->status == 'rejected' ? 'bg-danger text-white' : 'bg-warning text-dark') }}">
                                        {{ __('leaves.' . $leave->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-sm btn-info btn-custom">
                                        <i class="fas fa-edit"></i> تعديل
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
        $(document).ready(function() {
            $('#leavesTable').DataTable({
                dom: 'Brtip',
                buttons: ['excelHtml5', 'print'],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
                }
            });
        });
    </script>
@endsection