@extends('layouts.master')
@section('css')
@section('title') {{ __('home.units') }} @stop
@endsection

@section('content')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.units') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.units') }}</h4>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUnitModal">
                    <i class="fa fa-plus"></i> {{ __('إضافة وحدة جديدة') }}
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
                                <th>{{ __('home.unit_ar') }}</th>
                                <th>{{ __('home.unit_en') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($units as $key => $unit)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $unit->name_ar }}</td>
                                <td>{{ $unit->name_en }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editUnitModal{{ $unit->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editUnitModal{{ $unit->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-header-title">{{ __('تعديل وحدة') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <form action="{{ route('units.update', $unit->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>{{ __('home.unit_ar') }}</label>
                                                    <input type="text" name="name_ar" class="form-control" value="{{ $unit->name_ar }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ __('home.unit_en') }}</label>
                                                    <input type="text" name="name_en" class="form-control" value="{{ $unit->name_en }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">{{ __('حفظ') }}</button>
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

<!-- Add Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-header-title">{{ __('إضافة وحدة جديدة') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('units.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('home.unit_ar') }}</label>
                        <input type="text" name="name_ar" class="form-control" placeholder="مثال: كيلو جرام" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('home.unit_en') }}</label>
                        <input type="text" name="name_en" class="form-control" placeholder="مثال: KG" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('حفظ') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
