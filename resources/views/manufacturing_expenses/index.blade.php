@extends('layouts.master')
@section('css')
@section('title') {{ __('home.manufacturing_expenses') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.manufacturing_expenses') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.manufacturing_expenses_title') }}</h4>
                <a href="{{ route('manufacturing_expenses.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> {{ __('home.add_new_expense') }}
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table text-md-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('home.expense_number') }}</th>
                                <th>{{ __('home.expense_date') }}</th>
                                <th>{{ __('home.order_number') }}</th>
                                <th>{{ __('home.expense_type') }}</th>
                                <th>{{ __('home.expense_amount') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $key => $expense)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $expense->expense_number }}</strong></td>
                                <td>{{ $expense->expense_date }}</td>
                                <td>{{ $expense->manufacturingOrder->order_number ?? '---' }}</td>
                                <td>
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
                                </td>
                                <td><strong class="text-danger">{{ number_format($expense->amount, 2) }}</strong></td>
                                <td>
                                    <a href="{{ route('manufacturing_expenses.show', $expense->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('manufacturing_expenses.edit', $expense->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manufacturing_expenses.destroy', $expense->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
