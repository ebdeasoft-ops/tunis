@extends('layouts.master')

@section('css')
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <h4 class="content-title mb-0">{{ __('hr.hr_settings') }}</h4>
</div>
@endsection
@section('title') {{ __('attendances.hr_settings') }} @stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-cogs mr-2"></i> {{ __('hr.hr_settings') }}</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <form action="{{ route('hr-settings.update', $setting->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('hr.official_check_in') }}:</label>
                        <input type="time" name="official_check_in" class="form-control"
                            value="{{ $setting->official_check_in }}" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('hr.official_check_out') }}:</label>
                        <input type="time" name="official_check_out" class="form-control"
                            value="{{ $setting->official_check_out }}" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('hr.grace_period') }}:</label>
                        <input type="number" name="grace_period_minutes" class="form-control"
                            value="{{ $setting->grace_period_minutes }}" required>
                        <small class="text-muted">{{ __('hr.grace_hint') }}</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('hr.weekend_days') }}:</label>
                        <select name="weekend_days" class="form-control" required>
                            <option value="friday" {{ $setting->weekend_days == 'friday' ? 'selected' : '' }}>
                                {{ __('hr.friday_only') }}</option>
                            <option value="friday_saturday"
                                {{ $setting->weekend_days == 'friday_saturday' ? 'selected' : '' }}>
                                {{ __('hr.friday_saturday') }}</option>
                        </select>
                        <small class="text-muted">{{ __('hr.weekend_hint') }}</small>
                    </div>

                    <!-- حقل قيمة الساعة الإضافية الجديد -->
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('hr.overtime_hour_rate') }}:</label>
                        <input type="number" step="0.01" name="overtime_hour_rate" class="form-control"
                            value="{{ $setting->overtime_hour_rate }}" placeholder="{{ __('hr.overtime_hour_rate') }}"
                            required>
                        <small class="text-muted">{{ __('hr.overtime_hour_rate_hint') }}</small>
                    </div>
                    <button type="submit"
                        class="btn btn-success btn-block font-weight-bold mt-4">{{ __('hr.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection