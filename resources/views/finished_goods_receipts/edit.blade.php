@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.edit') }} - {{ $receipt->receipt_number }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('finished_goods_receipts.index') }}">{{ __('home.finished_goods_receipts') }}</a> / {{ __('home.edit') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.edit') }}: {{ $receipt->receipt_number }}</h4>
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

                <form action="{{ route('finished_goods_receipts.update', $receipt->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Receipt Number -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.receipt_number') }}</label>
                                <input type="text" class="form-control" value="{{ $receipt->receipt_number }}" readonly>
                            </div>
                        </div>

                        <!-- Receipt Date -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.receipt_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date', $receipt->receipt_date) }}" required>
                            </div>
                        </div>

                        <!-- MO Info -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.manufacturing_order') }}</label>
                                <input type="text" class="form-control" value="{{ $receipt->manufacturingOrder->order_number ?? '---' }} - ({{ $receipt->manufacturingOrder->finishedProduct->product_name ?? $receipt->manufacturingOrder->finishedProduct->name ?? '' }})" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Received Quantity -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.received_qty') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="received_quantity" class="form-control" value="{{ old('received_quantity', $receipt->received_quantity) }}" required>
                            </div>
                        </div>

                        <!-- WIP Warehouse -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.wip_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="wip_warehouse_id" class="form-control select2" required>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" {{ old('wip_warehouse_id', $receipt->wip_warehouse_id) == $wh->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $wh->name_ar : $wh->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- FG Warehouse -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.fg_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="finished_goods_warehouse_id" class="form-control select2" required>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" {{ old('finished_goods_warehouse_id', $receipt->finished_goods_warehouse_id) == $wh->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $wh->name_ar : $wh->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('home.item_notes') }}</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes', $receipt->notes) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('home.edit') }}</button>
                        <a href="{{ route('finished_goods_receipts.index') }}" class="btn btn-secondary">{{ __('home.cancel') }}</a>
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
