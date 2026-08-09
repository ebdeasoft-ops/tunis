@extends('layouts.master')
@section('css')
@section('title') {{ __('home.warehouses') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.warehouses') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.warehouses') }}</h4>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addWarehouseModal">
                    <i class="fa fa-plus"></i> {{ __('home.add_new_warehouse') }}
                </button>
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
                                <th>{{ __('home.warehouse_code') }}</th>
                                <th>{{ __('home.warehouse_name_ar') }}</th>
                                <th>{{ __('home.warehouse_name_en') }}</th>
                                <th>{{ __('home.warehouse_address') }}</th>
                                <th>{{ __('home.active_status') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouses as $key => $warehouse)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $warehouse->code }}</strong></td>
                                <td>{{ $warehouse->name_ar }}</td>
                                <td>{{ $warehouse->name_en ?? '---' }}</td>
                                <td>{{ $warehouse->address ?? '---' }}</td>
                                <td>
                                    @if($warehouse->is_active)
                                        <span class="badge badge-success">{{ __('home.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('home.disabled') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editWarehouseModal{{ $warehouse->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal التعديل -->
                            <div class="modal fade" id="editWarehouseModal{{ $warehouse->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('home.edit_warehouse') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>{{ __('home.warehouse_code') }}</label>
                                                    <input type="text" name="code" class="form-control" value="{{ $warehouse->code }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ __('home.warehouse_name_ar') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="name_ar" class="form-control" value="{{ $warehouse->name_ar }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ __('home.warehouse_name_en') }}</label>
                                                    <input type="text" name="name_en" class="form-control" value="{{ $warehouse->name_en }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ __('home.warehouse_address') }}</label>
                                                    <input type="text" name="address" class="form-control" value="{{ $warehouse->address }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="is_active" value="1" {{ $warehouse->is_active ? 'checked' : '' }}>
                                                        <span class="custom-control-label">{{ __('home.active') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">{{ __('home.save_order') }}</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal الإضافة -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('home.add_new_warehouse') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('warehouses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('home.warehouse_code') }}</label>
                        <input type="text" name="code" class="form-control" value="WH-{{ rand(100,999) }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('home.warehouse_name_ar') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('home.warehouse_name_en') }}</label>
                        <input type="text" name="name_en" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('home.warehouse_address') }}</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="is_active" value="1" checked>
                            <span class="custom-control-label">{{ __('home.active') }}</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('home.save_order') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
