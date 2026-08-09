@extends('layouts.master')
@section('css')
@section('title') {{ __('home.manufacturing_order') }} - {{ $order->order_number }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('manufacturing_orders.index') }}">{{ __('home.manufacturing_orders') }}</a> / {{ $order->order_number }}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route('manufacturing_orders.index') }}" class="btn btn-secondary btn-sm ml-2">
            <i class="fa fa-arrow-right"></i> {{ __('home.cancel') }}
        </a>
    </div>
</div>
<!-- breadcrumb -->

<div class="row mt-3">
    <div class="col-xl-12">
        <!-- كارت تفاصيل أمر الإنتاج -->
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.manufacturing_order') }}: {{ $order->order_number }}</h4>
                <div>
                    @if($order->status == 'draft')
                        <span class="badge badge-secondary tx-14">{{ __('home.status_draft') }}</span>
                    @elseif($order->status == 'planned')
                        <span class="badge badge-info tx-14">{{ __('home.status_planned') }}</span>
                    @elseif($order->status == 'in_progress')
                        <span class="badge badge-warning tx-14">{{ __('home.status_in_progress') }}</span>
                    @elseif($order->status == 'completed')
                        <span class="badge badge-success tx-14">{{ __('home.status_completed') }}</span>
                    @elseif($order->status == 'cancelled')
                        <span class="badge badge-danger tx-14">{{ __('home.status_cancelled') }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>{{ __('home.order_number') }}:</strong> {{ $order->order_number }}</p>
                        <p><strong>{{ __('home.order_date') }}:</strong> {{ $order->order_date }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>{{ __('home.boms') }}:</strong> {{ $order->bom->code ?? '---' }} - {{ $order->bom->name ?? '' }}</p>
                        <p><strong>{{ __('home.finished_product') }}:</strong> {{ $order->finishedProduct->product_name ?? $order->finishedProduct->name ?? '---' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>{{ __('home.planned_qty') }}:</strong> <span class="text-primary font-weight-bold">{{ $order->planned_quantity }}</span></p>
                        <p><strong>{{ __('home.produced_qty') }}:</strong> <span class="text-success font-weight-bold">{{ $order->produced_quantity ?? 0 }}</span></p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>{{ __('home.item_notes') }}:</strong> {{ $order->notes ?? '---' }}</p>
                    </div>
                </div>

                <hr>

                <!-- تفاصيل المخازن المربوطة -->
                <h5 class="mb-3 text-primary"><i class="fa fa-warehouse"></i> {{ __('المخازن المحددة للأمر') }}</h5>
                <div class="row bg-light p-3 rounded mb-4">
                    <div class="col-md-4">
                        <strong>{{ __('home.raw_warehouse') }}:</strong>
                        <span class="text-dark">{{ app()->getLocale() == 'ar' ? ($order->rawMaterialWarehouse->name_ar ?? '---') : ($order->rawMaterialWarehouse->name_en ?? '---') }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('home.wip_warehouse') }}:</strong>
                        <span class="text-dark">{{ app()->getLocale() == 'ar' ? ($order->wipWarehouse->name_ar ?? '---') : ($order->wipWarehouse->name_en ?? '---') }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>{{ __('home.fg_warehouse') }}:</strong>
                        <span class="text-dark">{{ app()->getLocale() == 'ar' ? ($order->finishedGoodsWarehouse->name_ar ?? '---') : ($order->finishedGoodsWarehouse->name_en ?? '---') }}</span>
                    </div>
                </div>

                <!-- جدول الخامات المخططة -->
                <h5 class="mb-3"><i class="fa fa-boxes"></i> {{ __('home.planned_materials') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="table-secondary">
                                <th>#</th>
                                <th>{{ __('home.raw_material') }}</th>
                                <th>{{ __('home.total_planned_qty') }}</th>
                                <th>{{ __('الكمية المصروفة فعلياً') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->materialIssues as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $item->rawMaterial->product_name ?? $item->rawMaterial->name ?? '---' }}</strong></td>
                                <td>{{ $item->planned_quantity }}</td>
                                <td>{{ $item->issued_quantity ?? 0 }}</td>
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
