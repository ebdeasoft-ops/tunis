@extends('layouts.master')

@section('css')
<style>
    .form-control, .form-select { border-radius: 8px; padding: 10px 15px; }
    .card-custom { border: none; border-radius: 12px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); }
    .card-header-custom { background: #fff; border-bottom: 1px solid #e3e6f0; padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    .btn-custom { border-radius: 8px; font-weight: 600; padding: 10px 20px; }
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <h4 class="content-title mb-0">تعديل طلب الإجازة</h4>
</div>
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

@section('title') تعديل طلب الإجازة @stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0 text-primary"><i class="fas fa-edit mr-2"></i> تعديل طلب الإجازة</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-row">
                        <!-- الموظف -->
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">{{ __('leaves.employee') }} <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-control" required>
                                <option value="">{{ __('leaves.employee') }}...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $leave->employee_id == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- نوع الإجازة -->
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">{{ __('leaves.type') }} <span class="text-danger">*</span></label>
                            <select name="leave_type" class="form-control" required>
                                <option value="annual" {{ $leave->leave_type == 'annual' ? 'selected' : '' }}>{{ __('leaves.annual') }}</option>
                                <option value="casual" {{ $leave->leave_type == 'casual' ? 'selected' : '' }}>{{ __('leaves.casual') }}</option>
                                <option value="sick" {{ $leave->leave_type == 'sick' ? 'selected' : '' }}>{{ __('leaves.sick') }}</option>
                                <option value="unpaid" {{ $leave->leave_type == 'unpaid' ? 'selected' : '' }}>{{ __('leaves.unpaid') }}</option>
                                <option value="unauthorized" {{ $leave->leave_type == 'unauthorized' ? 'selected' : '' }}>{{ __('leaves.unauthorized') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- تاريخ البداية -->
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">{{ __('leaves.start_date') }} <span class="text-danger">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ $leave->start_date }}" class="form-control" required>
                        </div>

                        <!-- تاريخ النهاية -->
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">{{ __('leaves.end_date') }} <span class="text-danger">*</span></label>
                            <input type="date" id="end_date" name="end_date" value="{{ $leave->end_date }}" class="form-control" required>
                        </div>

                        <!-- عدد الأيام -->
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">{{ __('leaves.days_count') }} <span class="text-danger">*</span></label>
                            <input type="number" id="days_count" name="days_count" value="{{ $leave->days_count }}" class="form-control" min="1" readonly required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- الحالة -->
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">{{ __('leaves.status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $leave->status == 'pending' ? 'selected' : '' }}>{{ __('leaves.pending') }}</option>
                                <option value="approved" {{ $leave->status == 'approved' ? 'selected' : '' }}>{{ __('leaves.approved') }}</option>
                                <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>{{ __('leaves.rejected') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- السبب أو الملاحظات -->
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('leaves.reason') }}</label>
                        <textarea name="reason" class="form-control" rows="3">{{ $leave->reason }}</textarea>
                    </div>

                    <!-- الأزرار -->
                    <div class="form-group text-right mt-4">
                        <button type="submit" class="btn btn-success btn-custom px-4">
                            <i class="fas fa-save mr-1"></i> تعديل وحفظ
                        </button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-custom px-4">
                            <i class="fas fa-times mr-1"></i> {{ __('leaves.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const daysCountInput = document.getElementById('days_count');

        function calculateDays() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDateInput.value && endDateInput.value) {
                if (endDate >= startDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    daysCountInput.value = diffDays;
                } else {
                    alert('تاريخ النهاية يجب أن يكون بعد تاريخ البداية أو يساويه');
                    endDateInput.value = '';
                    daysCountInput.value = '';
                }
            }
        }

        startDateInput.addEventListener('change', calculateDays);
        endDateInput.addEventListener('change', calculateDays);
    });
</script>
@endsection