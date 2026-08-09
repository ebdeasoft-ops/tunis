@extends('layouts.master')
@section('css')
@section('title') {{ __('home.finished_goods_receipts') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.finished_goods_receipts') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.finished_goods_receipts_title') }}</h4>
                <a href="{{ route('finished_goods_receipts.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> {{ __('home.add_new_receipt') }}
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
                                <th>{{ __('home.receipt_number') }}</th>
                                <th>{{ __('home.receipt_date') }}</th>
                                <th>{{ __('home.order_number') }}</th>
                                <th>{{ __('home.finished_product') }}</th>
                                <th>{{ __('home.received_qty') }}</th>
                                <th>{{ __('home.fg_warehouse') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipts as $key => $receipt)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $receipt->receipt_number }}</strong></td>
                                <td>{{ $receipt->receipt_date }}</td>
                                <td>{{ $receipt->manufacturingOrder->order_number ?? '---' }}</td>
                                <td>{{ $receipt->manufacturingOrder->finishedProduct->product_name ?? $receipt->manufacturingOrder->finishedProduct->name ?? '---' }}</td>
                                <td><span class="badge badge-success tx-14">{{ $receipt->received_quantity }}</span></td>
                                <td>{{ app()->getLocale() == 'ar' ? ($receipt->finishedGoodsWarehouse->name_ar ?? '---') : ($receipt->finishedGoodsWarehouse->name_en ?? '---') }}</td>
                                <td>
                                    <a href="{{ route('finished_goods_receipts.show', $receipt->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('finished_goods_receipts.edit', $receipt->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finished_goods_receipts.destroy', $receipt->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $receipts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
