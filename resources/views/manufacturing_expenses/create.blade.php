@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.add_new_expense') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('manufacturing_expenses.index') }}">{{ __('home.manufacturing_expenses') }}</a> / {{ __('home.add_new_expense') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.add_new_expense') }}</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('manufacturing_expenses.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Expense Number -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.expense_number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="expense_number" class="form-control" value="{{ old('expense_number', 'EXP-' . date('Ymd') . '-' . rand(100,999)) }}" required>
                            </div>
                        </div>

                        <!-- Expense Date -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.expense_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <!-- Manufacturing Order -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.manufacturing_order') }} <span class="text-danger">*</span></label>
                                <select name="manufacturing_order_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_mo') }}</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}">
                                            {{ $order->order_number }} - ({{ $order->finishedProduct->product_name ?? $order->finishedProduct->name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Expense Type -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.expense_type') }} <span class="text-danger">*</span></label>
                                <select name="expense_type" class="form-control select2" required>
                                    <option value="electricity">{{ __('home.expense_type_electricity') }}</option>
                                    <option value="labor">{{ __('home.expense_type_labor') }}</option>
                                    <option value="maintenance">{{ __('home.expense_type_maintenance') }}</option>
                                    <option value="depreciation">{{ __('home.expense_type_depreciation') }}</option>
                                    <option value="other">{{ __('home.expense_type_other') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.expense_amount') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('home.item_notes') }}</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('home.confirm_expense') }}</button>
                        <a href="{{ route('manufacturing_expenses.index') }}" class="btn btn-secondary">{{ __('home.cancel') }}</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
    });
</script>
@endsection
