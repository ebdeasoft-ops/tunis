@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.add_new_receipt') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('finished_goods_receipts.index') }}">{{ __('home.finished_goods_receipts') }}</a> / {{ __('home.add_new_receipt') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.add_new_receipt') }}</h4>
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

                <form action="{{ route('finished_goods_receipts.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Receipt Number -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.receipt_number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="receipt_number" class="form-control" value="{{ old('receipt_number', 'FGR-' . date('Ymd') . '-' . rand(100,999)) }}" required>
                            </div>
                        </div>

                        <!-- Receipt Date -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.receipt_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <!-- Manufacturing Order Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.manufacturing_order') }} <span class="text-danger">*</span></label>
                                <select name="manufacturing_order_id" id="manufacturing_order_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_mo') }}</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}">
                                            {{ $order->order_number }} - ({{ $order->finishedProduct->product_name ?? $order->finishedProduct->name ?? '' }}) - {{ __('home.planned_qty') }}: {{ $order->planned_quantity }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Finished Product Name (Auto) -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.finished_product') }}</label>
                                <input type="text" id="finished_product_name" class="form-control" readonly placeholder="{{ __('home.auto_filled') }}">
                            </div>
                        </div>

                        <!-- Planned Qty -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.planned_qty') }}</label>
                                <input type="text" id="planned_quantity" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Already Produced Qty -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.already_produced') }}</label>
                                <input type="text" id="already_produced" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Received Quantity Now -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.received_qty') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="received_quantity" id="received_quantity" class="form-control" value="{{ old('received_quantity') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- WIP Warehouse -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.wip_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="wip_warehouse_id" id="wip_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_wip_warehouse') }}</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ app()->getLocale() == 'ar' ? $wh->name_ar : $wh->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- FG Warehouse -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.fg_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="finished_goods_warehouse_id" id="finished_goods_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_fg_warehouse') }}</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ app()->getLocale() == 'ar' ? $wh->name_ar : $wh->name_en }}</option>
                                    @endforeach
                                </select>
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
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('home.confirm_receipt') }}</button>
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

        $('#manufacturing_order_id').change(function() {
            let orderId = $(this).val();

            if (!orderId) {
                $('#finished_product_name, #planned_quantity, #already_produced, #received_quantity').val('');
                return;
            }

            $.ajax({
                url: "{{ url('/get-mo-receipt-details') }}/" + orderId,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#finished_product_name').val(response.finished_product_name);
                    $('#planned_quantity').val(response.planned_quantity);
                    $('#already_produced').val(response.already_produced);
                    $('#received_quantity').val(response.remaining_quantity);

                    if (response.wip_warehouse_id) {
                        $('#wip_warehouse_id').val(response.wip_warehouse_id).trigger('change');
                    }
                    if (response.finished_goods_warehouse_id) {
                        $('#finished_goods_warehouse_id').val(response.finished_goods_warehouse_id).trigger('change');
                    }
                }
            });
        });
    });
</script>
@endsection
