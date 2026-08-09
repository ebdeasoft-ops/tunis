@extends('layouts.master')

@section('title')
    {{ __('hr.add_new_contract') }}
@stop

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mg-b-0">{{ __('hr.add_new_contract') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('contracts.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- اختيار الموظف -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('hr.name') }} <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                <option value="">{{ __('hr.select_employee') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ (app()->getLocale() == 'ar') ? $employee->name_ar : $employee->name_en }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- نوع العقد -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('hr.contract_type') }} <span class="text-danger">*</span></label>
                            <input type="text" name="contract_type" class="form-control @error('contract_type') is-invalid @enderror" value="{{ old('contract_type') }}" required>
                            @error('contract_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- تاريخ البداية -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('hr.start_date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- تاريخ النهاية -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('hr.end_date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- الراتب الأساسي -->
                        <div class="col-md-4 form-group">
                            <label>{{ __('hr.basic_salary') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary') }}" required>
                            @error('basic_salary')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- تاريخ انتهاء الإقامة -->
                        <div class="col-md-4 form-group">
                            <label>{{ __('hr.iqama_expiry') }}</label>
                            <input type="date" name="iqama_expiry_date" class="form-control @error('iqama_expiry_date') is-invalid @enderror" value="{{ old('iqama_expiry_date') }}">
                            @error('iqama_expiry_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- تاريخ انتهاء رخصة العمل -->
                        <div class="col-md-4 form-group">
                            <label>{{ __('hr.work_permit_expiry') }}</label>
                            <input type="date" name="work_permit_expiry_date" class="form-control @error('work_permit_expiry_date') is-invalid @enderror" value="{{ old('work_permit_expiry_date') }}">
                            @error('work_permit_expiry_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-success">{{ __('hr.save') }}</button>
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">{{ __('hr.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection