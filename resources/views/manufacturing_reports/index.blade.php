@extends('layouts.master')
@section('css')
@section('title') {{ __('home.manufacturing_reports') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.manufacturing_reports') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.manufacturing_reports') }}</h4>
            </div>
            <div class="card-body">
                <!-- نموذج الفلترة -->
                <form action="{{ route('manufacturing_reports.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>{{ __('home.order_status') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ __('الكل') }}</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('home.status_draft') }}</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('home.status_in_progress') }}</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('home.status_completed') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('من تاريخ') }}</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('إلى تاريخ') }}</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> {{ __('عرض التقرير') }}</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table text-md-nowrap">
                        <thead>
                            <tr class="table-secondary">
                                <th>#</th>
                                <th>{{ __('home.order_number') }}</th>
                                <th>{{ __('home.finished_product') }}</th>
                                <th>{{ __('home.planned_qty') }}</th>
                                <th>{{ __('home.produced_qty') }}</th>
                                <th>{{ __('home.order_status') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $key => $order)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->finishedProduct->product_name ?? $order->finishedProduct->name ?? '---' }}</td>
                                <td>{{ $order->planned_quantity }}</td>
                                <td><span class="badge badge-success">{{ $order->produced_quantity ?? 0 }}</span></td>
                                <td>
                                    @if($order->status == 'completed')
                                        <span class="badge badge-success">{{ __('home.status_completed') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('home.status_in_progress') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('manufacturing_reports.order_cost', $order->id) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fa fa-file-invoice-dollar"></i> {{ __('home.order_cost_report') }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
