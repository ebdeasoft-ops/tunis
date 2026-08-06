@extends('layouts.master')

@section('title') {{ __('home.pending_stored_drafts') }} @stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0">{{ __('home.previous_drafts_unapproved') }}</h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mg-b-0 text-md-nowrap text-center">
                        <thead>
                            <tr>
                                <th>{{ __('home.draft_number') }}</th>
                                <th>{{ __('home.receiving_branch') }}</th>
                                <th>{{ __('home.receiving_employee') }}</th>
                                <th>{{ __('home.total_cost') }}</th>
                                <th>{{ __('home.save_date') }}</th>
                                <th>{{ __('home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myDrafts as $draft)
                            <tr>
                                <th scope="row">#{{ $draft->id }}</th>
                                <td>{{ $draft->branchTo->name ?? __('home.unknown_branch') }}</td>
                                <td>{{ $draft->userTo->name ?? __('home.unknown_employee') }}</td>
                                <td><span class="badge badge-info">{{ number_format($draft->Totalcost, 2) }}</span></td>
                                <td>{{ $draft->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/sendProduct?draft_id=' . $draft->id . '&branch_id=' . $branchId) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="las la-edit"></i> {{ __('home.complete_and_edit_draft') }}
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-muted py-4">{{ __('home.no_saved_drafts') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
