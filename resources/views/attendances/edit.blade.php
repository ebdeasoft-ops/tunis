@extends('layouts.master')

@section('css')
<style>
    /* حل مشكلة ظهور القائمة المنسدلة فارغة أو شفافة */
    .form-control {
        height: calc(1.5em + .75rem + 2px) !important;
    }
    select.form-control {
        background-color: #fff !important;
        color: #495057 !important;
        opacity: 1 !important;
    }
    option {
        background-color: #fff !important;
        color: #333 !important;
        padding: 10px !important;
    }
</style>
@endsection
@section('title') {{ __('attendances.edit_attendance') }} @stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <h4 class="content-title mb-0">{{ __('attendances.edit_attendance') }}</h4>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('attendances.employee') }}:</label>
                        <select name="employee_id" class="form-control" required>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $attendance->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('attendances.date') }}:</label>
                        <input type="date" name="date" class="form-control" value="{{ $attendance->date }}" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('attendances.check_in') }}:</label>
                        <input type="time" name="check_in" class="form-control" value="{{ $attendance->check_in }}" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('attendances.check_out') }}:</label>
                        <input type="time" name="check_out" class="form-control" value="{{ $attendance->check_out }}">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('attendances.status') }}:</label>
                        <select name="status" class="form-control" required>
                            <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>{{ __('attendances.present') }}</option>
                            <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>{{ __('attendances.late') }}</option>
                            <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>{{ __('attendances.absent') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('attendances.update') }}</button>
                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">{{ __('attendances.cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // جلب الإعدادات من النظام
        const officialCheckIn = "{{ $settings->official_check_in ?? '08:00' }}"; 
        const gracePeriod = parseInt("{{ $settings->grace_period_minutes ?? 15 }}"); 

        const checkInInput = document.querySelector('input[name="check_in"]');
        
        if (checkInInput) {
            checkInInput.addEventListener('input', function() {
                const selectedTime = this.value;
                if (!selectedTime) return;

                const [startH, startM] = selectedTime.split(':').map(Number);
                const [offH, offM] = officialCheckIn.split(':').map(Number);

                const startMinutes = (startH * 60) + startM;
                const officialMinutes = (offH * 60) + offM;

                const diff = startMinutes - officialMinutes;
                const statusSelect = document.querySelector('select[name="status"]');

                if (statusSelect) {
                    if (diff > gracePeriod) {
                        statusSelect.value = 'late';
                    } else {
                        statusSelect.value = 'present';
                    }
                }
            });
        }
    });
</script>
@endsection