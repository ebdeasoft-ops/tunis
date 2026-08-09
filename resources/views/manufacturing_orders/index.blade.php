@extends('layouts.master')
@section('css')
@section('title') {{ __('home.manufacturing_orders') }} @stop
@endsection

@section('content')
<!-- breadcrumb / عنوان الصفحة -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.manufacturing_orders') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.manufacturing_orders') }}</h4>
                <a href="{{ route('manufacturing_orders.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> {{ __('home.add_new_order') }}
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
                                <th>{{ __('home.order_number') }}</th>
                                <th>{{ __('home.order_date') }}</th>
                                <th>{{ __('home.boms') }}</th>
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
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->bom->code ?? '---' }}</td>
                                <td>{{ $order->finishedProduct->product_name ?? $order->finishedProduct->name ?? '---' }}</td>
                                <td>{{ $order->planned_quantity }}</td>
                                <td>{{ $order->produced_quantity }}</td>
                                <td>
                                    @if($order->status == 'draft')
                                        <span class="badge badge-secondary">{{ __('home.status_draft') }}</span>
                                    @elseif($order->status == 'planned')
                                        <span class="badge badge-info">{{ __('home.status_planned') }}</span>
                                    @elseif($order->status == 'in_progress')
                                        <span class="badge badge-warning">{{ __('home.status_in_progress') }}</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge badge-success">{{ __('home.status_completed') }}</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge badge-danger">{{ __('home.status_cancelled') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- عرض التفاصيل -->
                                    <a href="{{ route('manufacturing_orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="{{ __('عرض التفاصيل') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <!-- التعديل يتاح فقط إذا كان الأمر مسودة أو مخطط -->
                                    @if(in_array($order->status, ['draft', 'planned']))
                                    <a href="{{ route('manufacturing_orders.edit', $order->id) }}" class="btn btn-sm btn-info" title="{{ __('home.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif

                                    <!-- الحذف للمسودة فقط -->
                                    @if($order->status == 'draft')
                                    <form action="{{ route('manufacturing_orders.destroy', $order->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('home.delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
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

@section('js')
@endsection
