@extends('layouts.master')

@section('title')
    {{ __('hr.contracts_management') }}
@stop

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">{{ __('hr.contracts_list') }}</h4>
                        <div>
                            <a href="{{ route('documents.alerts') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-bell"></i> {{ __('hr.document_alerts') }}
                            </a>
                            <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('hr.add_new_contract') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>{{ __('hr.name') }}</th>
                                    <th>{{ __('hr.contract_type') }}</th>
                                    <th>{{ __('hr.start_date') }}</th>
                                    <th>{{ __('hr.end_date') }}</th>
                                    <th>{{ __('hr.iqama_expiry') }}</th>
                                    <th>{{ __('hr.work_permit_expiry') }}</th>
                                    <th>{{ __('home.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contracts as $contract)
                                    <tr>
                                        <td>{{ (app()->getLocale() == 'ar') ? $contract->employee->name_ar : $contract->employee->name_en }}</td>
                                        <td>{{ $contract->contract_type }}</td>
                                        <td>{{ $contract->start_date }}</td>
                                        <td>
                                            <span class="badge {{ Carbon\Carbon::parse($contract->end_date)->isPast() ? 'badge-danger' : 'badge-success' }}">
                                                {{ $contract->end_date }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($contract->iqama_expiry_date)
                                                <span class="badge {{ Carbon\Carbon::parse($contract->iqama_expiry_date)->isPast() ? 'badge-danger' : 'badge-info' }}">
                                                    {{ $contract->iqama_expiry_date }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($contract->work_permit_expiry_date)
                                                <span class="badge {{ Carbon\Carbon::parse($contract->work_permit_expiry_date)->isPast() ? 'badge-danger' : 'badge-info' }}">
                                                    {{ $contract->work_permit_expiry_date }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-info"><i class="las la-pen"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection