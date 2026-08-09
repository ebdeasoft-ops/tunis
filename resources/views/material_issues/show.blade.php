@extends('layouts.master')
@section('css')
@section('title') {{ __('home.material_issues') }} - {{ $issue->issue_number }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('material_issues.index') }}">{{ __('home.material_issues') }}</a> / {{ $issue->issue_number }}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route('material_issues.index') }}" class="btn btn-secondary btn-sm ml-2">
            <i class="fa fa-arrow-right"></i> {{ __('home.cancel') }}
        </a>
    </div>
</div>
<!-- breadcrumb -->

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.issue_number') }}: {{ $issue->issue_number }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>{{ __('home.issue_number') }}:</strong> {{ $issue->issue_number }}</p>
                        <p><strong>{{ __('home.issue_date') }}:</strong> {{ $issue->issue_date }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.order_number') }}:</strong> {{ $issue->manufacturingOrder->order_number ?? '---' }}</p>
                        <p><strong>{{ __('home.finished_product') }}:</strong> {{ $issue->manufacturingOrder->finishedProduct->product_name ?? $issue->manufacturingOrder->finishedProduct->name ?? '---' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.item_notes') }}:</strong> {{ $issue->notes ?? '---' }}</p>
                    </div>
                </div>

                <hr>

                <!-- تفاصيل المخازن -->
                <h5 class="mb-3 text-primary"><i class="fa fa-warehouse"></i> {{ __('home.warehouses') }}</h5>
                <div class="row bg-light p-3 rounded mb-4">
                    <div class="col-md-6">
                        <strong>{{ __('home.raw_warehouse') }}:</strong>
                        <span class="text-dark">{{ app()->getLocale() == 'ar' ? ($issue->rawWarehouse->name_ar ?? '---') : ($issue->rawWarehouse->name_en ?? '---') }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('home.wip_warehouse') }}:</strong>
                        <span class="text-dark">{{ app()->getLocale() == 'ar' ? ($issue->wipWarehouse->name_ar ?? '---') : ($issue->wipWarehouse->name_en ?? '---') }}</span>
                    </div>
                </div>

                <!-- جدول البنود المصروفة -->
                <h5 class="mb-3"><i class="fa fa-boxes"></i> {{ __('home.actual_issued_materials') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="table-secondary">
                                <th>#</th>
                                <th>{{ __('home.raw_material') }}</th>
                                <th>{{ __('home.current_issued_qty') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issue->items as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $item->rawMaterial->product_name ?? $item->rawMaterial->name ?? '---' }}</strong></td>
                                <td><span class="badge badge-success tx-14">{{ $item->issued_quantity }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
