@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.add_new_material_issue') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('material_issues.index') }}">{{ __('home.material_issues') }}</a> / {{ __('home.add_new_material_issue') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.material_issues_title') }}</h4>
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

                <form action="{{ route('material_issues.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Issue Number -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.issue_number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="issue_number" class="form-control" value="{{ old('issue_number', 'MI-' . date('Ymd') . '-' . rand(100,999)) }}" required>
                            </div>
                        </div>

                        <!-- Issue Date -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.issue_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', date('Y-m-d')) }}" required>
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
                        <!-- Raw Warehouse -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.raw_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="raw_warehouse_id" id="raw_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_raw_warehouse') }}</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ app()->getLocale() == 'ar' ? $wh->name_ar : $wh->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('home.item_notes') }}</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">{{ __('home.actual_issued_materials') }}</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="issue_items_table">
                            <thead>
                                <tr class="table-secondary">
                                    <th>{{ __('home.raw_material') }}</th>
                                    <th>{{ __('home.units') }}</th>
                                    <th>{{ __('home.planned_qty') }}</th>
                                    <th>{{ __('home.already_issued') }}</th>
                                    <th>{{ __('home.current_issued_qty') }}</th>
                                    <th>{{ __('تكلفة الوحدة') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="empty_row">
                                    <td colspan="6" class="text-center text-muted">{{ __('home.mo_selection_notice') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('home.confirm_material_issue') }}</button>
                        <a href="{{ route('material_issues.index') }}" class="btn btn-secondary">{{ __('home.cancel') }}</a>
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
                $('#issue_items_table tbody').html('<tr id="empty_row"><td colspan="5" class="text-center text-muted">{{ __('home.mo_selection_notice') }}</td></tr>');
                return;
            }

            $.ajax({
                url: "{{ url('/get-mo-materials') }}/" + orderId,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.raw_warehouse_id) {
                        $('#raw_warehouse_id').val(response.raw_warehouse_id).trigger('change');
                    }
                    if (response.wip_warehouse_id) {
                        $('#wip_warehouse_id').val(response.wip_warehouse_id).trigger('change');
                    }

                    let tbody = $('#issue_items_table tbody');
                    tbody.empty();

                    $.each(response.items, function(index, item) {
                        let row = `
                            <tr>
                                <td>
                                    <input type="hidden" name="items[${index}][raw_material_id]" value="${item.raw_material_id}">
                                    <strong>${item.raw_material_name}</strong>
                                </td>
                                <td>${item.unit_name}</td>
                                <td>${item.planned_quantity}</td>
                                <td><span class="badge badge-info">${item.already_issued}</span></td>
                                <td>
                                    <input type="number" step="0.0001" name="items[${index}][issued_quantity]" class="form-control" value="${item.remaining_qty}" max="${item.remaining_qty}" required>
                                </td>
                               <td>
                                    <input type="number" step="0.01" name="items[${index}][unit_cost]" class="form-control bg-light" value="${item.unit_cost}" readonly>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                }
            });
        });
    });
</script>
@endsection
