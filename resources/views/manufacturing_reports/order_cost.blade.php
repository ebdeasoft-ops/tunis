@extends('layouts.master')
@section('css')
<style>
    @media print {
        .btn-print { display: none; }
    }
</style>
@section('title') {{ __('home.order_cost_report') }} - {{ $order->order_number }} @stop
@endsection

@section('content')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 my-auto">{{ __('home.order_cost_report') }}</h4>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-primary btn-print"><i class="fa fa-print"></i> {{ __('طباعة التقرير') }}</button>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card p-4">
            <!-- الهيدر الخاص بالأمر -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><strong>{{ __('home.order_number') }}:</strong> {{ $order->order_number }}</h5>
                    <p><strong>{{ __('home.finished_product') }}:</strong> {{ $order->finishedProduct->product_name ?? $order->finishedProduct->name ?? '---' }}</p>
                    <p><strong>{{ __('home.order_date') }}:</strong> {{ $order->order_date }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>{{ __('home.planned_qty') }}:</strong> {{ $order->planned_quantity }}</p>
                    <p><strong>{{ __('home.produced_qty') }}:</strong> {{ $order->produced_quantity ?? 0 }}</p>
                </div>
            </div>

            <hr>

            <!-- 1. تفاصيل الخامات المباشرة -->
            <h5 class="text-primary mb-3">{{ __('home.total_materials_cost') }}</h5>
            <table class="table table-bordered mb-4">
                <thead>
                    <tr class="table-secondary">
                        <th>{{ __('home.raw_material') }}</th>
                        <th>{{ __('home.current_issued_qty') }}</th>
                        <th>{{ __('home.unit_cost') }}</th>
                        <th>{{ __('home.total_cost') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materialExpenses as $item)
                    @php
                        // جلب تكلفة الوحدة ديناميكياً من purchasing_price للمادة الخام إذا لم توجد في البند
                        $unitCost = $item->unit_cost > 0
                            ? $item->unit_cost
                            : ($item->rawMaterial->purchasing_price ?? $item->rawMaterial->cost ?? 0);
                        $itemTotalCost = $item->issued_quantity * $unitCost;
                    @endphp
                    <tr>
                        <td>{{ $item->rawMaterial->product_name ?? $item->rawMaterial->name ?? '---' }}</td>
                        <td>{{ $item->issued_quantity }}</td>
                        <td>{{ number_format($unitCost, 2) }}</td>
                        <td>{{ number_format($itemTotalCost, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">{{ __('home.no_materials_issued') }}</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('home.total_materials_cost_sum') }}:</th>
                        <th>{{ number_format($totalMaterialsCost, 2) }}</th>
                    </tr>
                </tfoot>
            </table>

            <!-- 2. المصاريف غير المباشرة -->
            <h5 class="text-primary mb-3">{{ __('home.total_overhead_cost') }}</h5>
            <table class="table table-bordered mb-4">
                <thead>
                    <tr class="table-secondary">
                        <th>{{ __('home.expense_type') }}</th>
                        <th>{{ __('home.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overheadExpenses as $expense)
                    <tr>
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
                        <td>{{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center text-muted">{{ __('home.no_overhead_expenses') }}</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right">{{ __('home.total_overhead_cost_sum') }}:</th>
                        <th>{{ number_format($totalOverheadCost, 2) }}</th>
                    </tr>
                </tfoot>
            </table>

            <!-- 3. ملخص التكلفة النهائية -->
            <div class="row bg-light p-3 rounded mt-3">
                <div class="col-md-4">
                    <h4>{{ __('home.total_actual_cost') }}:</h4>
                    <h3 class="text-danger"><strong>{{ number_format($grandTotalCost, 2) }}</strong></h3>
                </div>
                <div class="col-md-4">
                    <h4>{{ __('home.produced_qty') }}:</h4>
                    <h3 class="text-dark"><strong>{{ $order->produced_quantity ?? 0 }}</strong></h3>
                </div>
                <div class="col-md-4">
                    <h4>{{ __('home.unit_final_cost') }}:</h4>
                    <h3 class="text-success"><strong>{{ number_format($costPerUnit, 2) }}</strong></h3>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
