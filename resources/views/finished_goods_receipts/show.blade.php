@extends('layouts.master')
@section('css')
@section('title') {{ __('home.finished_goods_receipts') }} - {{ $receipt->receipt_number }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('finished_goods_receipts.index') }}">{{ __('home.finished_goods_receipts') }}</a> / {{ $receipt->receipt_number }}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route('finished_goods_receipts.index') }}" class="btn btn-secondary btn-sm ml-2">
            <i class="fa fa-arrow-right"></i> {{ __('home.cancel') }}
        </a>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.receipt_number') }}: {{ $receipt->receipt_number }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>{{ __('home.receipt_number') }}:</strong> {{ $receipt->receipt_number }}</p>
                        <p><strong>{{ __('home.receipt_date') }}:</strong> {{ $receipt->receipt_date }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.order_number') }}:</strong> {{ $receipt->manufacturingOrder->order_number ?? '---' }}</p>
                        <p><strong>{{ __('home.finished_product') }}:</strong> {{ $receipt->manufacturingOrder->finishedProduct->product_name ?? $receipt->manufacturingOrder->finishedProduct->name ?? '---' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ __('home.received_qty') }}:</strong> <span class="badge badge-success tx-16">{{ $receipt->received_quantity }}</span></p>
                        <p><strong>{{ __('home.item_notes') }}:</strong> {{ $receipt->notes ?? '---' }}</p>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3 text-primary"><i class="fa fa-warehouse"></i> {{ __('home.warehouses') }}</h5>
                <div class="row bg-light p-3 rounded">
                    <div class="col-md-6">
                        <strong>{{ __('home.wip_warehouse') }}:</strong>
                        <span class="text-dark">{{ app()->getLocale() == 'ar' ? ($receipt->wipWarehouse->name_ar ?? '---') : ($receipt->wipWarehouse->name_en ?? '---') }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('home.fg_warehouse') }}:</strong>
                        <span class="text-dark">{{ app()->getLocale() == 'ar' ? ($receipt->finishedGoodsWarehouse->name_ar ?? '---') : ($receipt->finishedGoodsWarehouse->name_en ?? '---') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
