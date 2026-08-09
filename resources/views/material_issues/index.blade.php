@extends('layouts.master')
@section('css')
@section('title') {{ __('home.material_issues') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.material_issues') }}</span>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mg-b-0">{{ __('home.material_issues_title') }}</h4>
                <a href="{{ route('material_issues.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> {{ __('home.add_new_material_issue') }}
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
                                <th>{{ __('home.issue_number') }}</th>
                                <th>{{ __('home.issue_date') }}</th>
                                <th>{{ __('home.order_number') }}</th>
                                <th>{{ __('home.raw_warehouse') }}</th>
                                <th>{{ __('home.wip_warehouse') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issues as $key => $issue)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>{{ $issue->issue_number }}</strong></td>
                                <td>{{ $issue->issue_date }}</td>
                                <td>{{ $issue->manufacturingOrder->order_number ?? '---' }}</td>
                                <td>{{ app()->getLocale() == 'ar' ? ($issue->rawWarehouse->name_ar ?? '---') : ($issue->rawWarehouse->name_en ?? '---') }}</td>
                                <td>{{ app()->getLocale() == 'ar' ? ($issue->wipWarehouse->name_ar ?? '---') : ($issue->wipWarehouse->name_en ?? '---') }}</td>
                                <td>
                                    <a href="{{ route('material_issues.show', $issue->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    @if($issue->manufacturingOrder && $issue->manufacturingOrder->status != 'completed')
                                    <a href="{{ route('material_issues.edit', $issue->id) }}" class="btn btn-sm btn-info" title="{{ __('home.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $issues->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
