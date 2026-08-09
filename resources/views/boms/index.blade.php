@extends('layouts.master')
@section('css')
@section('title') {{ __('home.boms') }} @stop
@endsection

@section('content')
<!-- breadcrumb / عنوان الصفحة -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.boms') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.boms') }}</h4>
                <a href="{{ route('boms.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> {{ __('home.add_new_bom') }}
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
                                <th>{{ __('home.bom_code') }}</th>
                                <th>{{ __('home.bom_name') }}</th>
                                <th>{{ __('home.finished_product') }}</th>
                                <th>{{ __('home.output_qty') }}</th>
                                <th>{{ __('home.active_status') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($boms as $key => $bom)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $bom->code }}</td>
                                <td>{{ $bom->name }}</td>
                                <td>{{ $bom->finishedProduct->product_name ?? '---' }}</td>
                                <td>{{ $bom->output_quantity }}</td>
                                <td>
                                    @if($bom->is_active)
                                        <span class="badge badge-success">{{ __('home.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('home.disabled') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('boms.edit', $bom->id) }}" class="btn btn-sm btn-info" title="{{ __('home.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('boms.destroy', $bom->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('home.delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $boms->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
