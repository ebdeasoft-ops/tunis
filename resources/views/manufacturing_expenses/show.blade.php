@extends('layouts.master')
@section('css')
@section('title') {{ __('home.manufacturing_expenses') }} - {{ $expense->expense_number }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('manufacturing_expenses.index') }}">{{ __('home.manufacturing_expenses') }}</a> / {{ $expense->expense_number }}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route('manufacturing_expenses.index') }}" class="btn btn-secondary btn-sm ml-2">
            <i class="fa fa-arrow-right"></i> {{ __('home.cancel') }}
        </a>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.expense_number') }}: {{ $expense->expense_number }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>{{ __('home.expense_number') }}:</strong> {{ $expense->expense_number }}</p>
                        <p><strong>{{ __('home.expense_date') }}:</strong> {{ $expense->expense_date }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.order_number') }}:</strong> {{ $expense->manufacturingOrder->order_number ?? '---' }}</p>
                        <p><strong>{{ __('home.expense_type') }}:</strong>
                            @if($expense->expense_type == 'electricity')
                                {{ __('home.expense_type_electricity') }}
                            @elseif($expense->expense_type == 'labor')
                                {{ __('home.expense_type_labor') }}
                            @elseif($expense->expense_type == 'maintenance')
                                {{ __('home.expense_type_maintenance') }}
                            @elseif($expense->expense_type == 'depreciation')
                                {{ __('home.expense_type_depreciation') }}
                            @else
                                {{ __('home.expense_type_other') }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.expense_amount') }}:</strong> <span class="badge badge-danger tx-16">{{ number_format($expense->amount, 2) }}</span></p>
                        <p><strong>{{ __('home.item_notes') }}:</strong> {{ $expense->notes ?? '---' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
