@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.edit') }} - {{ $issue->issue_number }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('material_issues.index') }}">{{ __('home.material_issues') }}</a> / {{ __('home.edit') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.edit') }}: {{ $issue->issue_number }}</h4>
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

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('material_issues.update', $issue->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- رقم إذن الصرف -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.issue_number') }}</label>
                                <input type="text" class="form-control" value="{{ $issue->issue_number }}" readonly>
                            </div>
                        </div>

                        <!-- تاريخ الصرف -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.issue_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', $issue->issue_date) }}" required>
                            </div>
                        </div>

                        <!-- أمر الإنتاج المرتبط -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.manufacturing_order') }}</label>
                                <input type="text" class="form-control" value="{{ $issue->manufacturingOrder->order_number ?? '---' }} - ({{ $issue->manufacturingOrder->finishedProduct->product_name ?? $issue->manufacturingOrder->finishedProduct->name ?? '' }})" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- مخزن صرف الخامات -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.raw_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="raw_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_raw_warehouse') }}</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" {{ old('raw_warehouse_id', $issue->raw_warehouse_id) == $wh->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $wh->name_ar : $wh->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- مخزن التشغيل WIP -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('home.wip_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="wip_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_wip_warehouse') }}</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" {{ old('wip_warehouse_id', $issue->wip_warehouse_id) == $wh->id ? 'selected' : '' }}>
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
                                <input type="text" name="notes" class="form-control" value="{{ old('notes', $issue->notes) }}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">{{ __('home.actual_issued_materials') }}</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-secondary">
                                    <th>{{ __('home.raw_material') }}</th>
                                    <th>{{ __('home.current_issued_qty') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($issue->items as $index => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][raw_material_id]" value="{{ $item->raw_material_id }}">
                                        <strong>{{ $item->rawMaterial->product_name ?? $item->rawMaterial->name ?? '---' }}</strong>
                                    </td>
                                    <td>
                                        <input type="number" step="0.0001" name="items[{{ $index }}][issued_quantity]" class="form-control" value="{{ old('items.'.$index.'.issued_quantity', $item->issued_quantity) }}" required>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('home.edit') }}</button>
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
    });
</script>
@endsection
