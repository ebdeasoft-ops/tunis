@extends('layouts.master')

@section('title') {{ __('hr.eos_calculator') }} @stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="card-title m-0 font-weight-bold text-primary">{{ __('hr.eos_calculator') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('eos.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>{{ __('hr.name') }}</label>
                                <select name="employee_id" class="form-control" required>
                                    <option value="">{{ __('hr.select_employee') }}</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name_ar ?? $employee->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label>{{ __('hr.join_date') }}</label>
                                <input type="date" name="join_date" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>{{ __('hr.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>{{ __('hr.basic_salary') }}</label>
                                <input type="number" step="0.01" name="basic_salary" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>{{ __('hr.reason') }}</label>
                                <select name="reason" class="form-control" required>
                                    <option value="resignation">{{ __('hr.resignation') }}</option>
                                    <option value="termination">{{ __('hr.termination') }}</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('hr.calculate_and_save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection