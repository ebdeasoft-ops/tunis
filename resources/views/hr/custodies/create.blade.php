@extends('layouts.master')

@section('title') {{ __('hr.new_custody') }} @stop

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h4 class="card-title m-0 font-weight-bold text-primary">{{ __('hr.new_custody') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('custodies.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>{{ __('hr.name') }}</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">{{ __('hr.select_employee') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name_ar ?? $emp->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>{{ __('hr.item_name') }}</label>
                        <input type="text" name="item_name" class="form-control" placeholder="مثلاً: لابتوب ديل..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>{{ __('hr.serial_number') }}</label>
                        <input type="text" name="serial_number" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>{{ __('hr.delivery_date') }}</label>
                        <input type="date" name="delivery_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('hr.save') ?? 'حفظ' }}</button>
                <a href="{{ route('custodies.index') }}" class="btn btn-secondary">رجوع</a>
            </form>
        </div>
    </div>
</div>
@endsection