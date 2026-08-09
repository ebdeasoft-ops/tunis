@extends('layouts.master')

@section('title') {{ __('hr.eos_title') }} @stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0 font-weight-bold text-primary">{{ __('hr.eos_title') }}</h4>
                    <a href="{{ route('eos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('hr.new_eos') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('hr.name') }}</th>
                                    <th>{{ __('hr.join_date') }}</th>
                                    <th>{{ __('hr.end_date') }}</th>
                                    <th>{{ __('hr.service_years') }}</th>
                                    <th>{{ __('hr.basic_salary') }}</th>
                                    <th>{{ __('hr.reason') }}</th>
                                    <th>{{ __('hr.reward_amount') }}</th>
                                    <th>{{ __('hr.actions') ?? 'العمليات' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $record->employee->name_ar ?? $record->employee->name_en }}</strong></td>
                                    <td>{{ $record->join_date }}</td>
                                    <td>{{ $record->end_date }}</td>
                                    <td><span class="badge badge-info">{{ $record->service_years }} سنة</span></td>
                                    <td>{{ number_format($record->basic_salary, 2) }}</td>
                                    <td>
                                        @if($record->reason == 'termination')
                                            <span class="badge badge-danger">{{ __('hr.termination') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('hr.resignation') }}</span>
                                        @endif
                                    </td>
                                    <td><strong class="text-success">{{ number_format($record->reward_amount, 2) }}</strong></td>
                                    <td>
                                        <a href="#" onclick="window.print(); return false;" class="btn btn-sm btn-info" title="طباعة">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">{{ __('hr.no_eos_records') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- روابط التصفح (Pagination) -->
                    <div class="mt-3">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- تنسيق خاص لإخفاء القوائم والأزرار عند الطباعة --}}
@push('css')
<style>
@media print {
    .main-sidebar, .main-header, .card-header, .btn, footer, .breadcrumb {
        display: none !important;
    }
    .card, .card-body, .container-fluid {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
@endsection