@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.edit') }} - {{ $expense->expense_number }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('manufacturing_expenses.index') }}">{{ __('home.manufacturing_expenses') }}</a> / {{ __('home.edit') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.edit') }}: {{ $expense->expense_number }}</h4>
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

                <form action="{{ route('manufacturing_expenses.update', $expense->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Expense Number -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.expense_number') }}</label>
                                <input type="text" class="form-control" value="{{ $expense->expense_number }}" readonly>
                            </div>
                        </div>

                        <!-- Expense Date -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.expense_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date) }}" required>
                            </div>
                        </div>

                        <!-- MO Info -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.manufacturing_order') }}</label>
                                <input type="text" class="form-control" value="{{ $expense->manufacturingOrder->order_number ?? '---' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Expense Type -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.expense_type') }} <span class="text-danger">*</span></label>
                                <select name="expense_type" class="form-control select2" required>
                                    <option value="electricity" {{ $expense->expense_type == 'electricity' ? 'selected' : '' }}>{{ __('home.expense_type_electricity') }}</option>
                                    <option value="labor" {{ $expense->expense_type == 'labor' ? 'selected' : '' }}>{{ __('home.expense_type_labor') }}</option>
                                    <option value="maintenance" {{ $expense->expense_type == 'maintenance' ? 'selected' : '' }}>{{ __('home.expense_type_maintenance') }}</option>
                                    <option value="depreciation" {{ $expense->expense_type == 'depreciation' ? 'selected' : '' }}>{{ __('home.expense_type_depreciation') }}</option>
                                    <option value="other" {{ $expense->expense_type == 'other' ? 'selected' : '' }}>{{ __('home.expense_type_other') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.expense_amount') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('home.item_notes') }}</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes', $expense->notes) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('home.edit') }}</button>
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
