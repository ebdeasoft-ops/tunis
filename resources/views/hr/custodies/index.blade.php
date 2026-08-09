@extends('layouts.master')

@section('title') {{ __('hr.custody_and_assets') }} @stop

@section('content')
<br>
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="card-title m-0 font-weight-bold text-primary">{{ __('hr.custody_and_assets') }}</h4>
            <a href="{{ route('custodies.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('hr.new_custody') }}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('hr.name') }}</th>
                            <th>{{ __('hr.item_name') }}</th>
                            <th>{{ __('hr.serial_number') }}</th>
                            <th>{{ __('hr.delivery_date') }}</th>
                            <th>{{ __('hr.status') }}</th>
                            <th>{{ __('home.actions') ?? 'العمليات' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($custodies as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->employee->name_ar ?? $item->employee->name_en }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->serial_number ?? '---' }}</td>
                            <td>{{ $item->delivery_date }}</td>
                            <td>
                                @if($item->status == 'delivered')
                                    <span class="badge badge-warning">{{ __('hr.delivered') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('hr.returned') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 'delivered')
                                    <form action="{{ route('custodies.return', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('hr.return_action') ?? 'إرجاع العهدة' }}" onclick="return confirm('هل أنت متأكد من استلام هذه العهدة؟')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="#" onclick="window.print(); return false;" class="btn btn-sm btn-info" title="طباعة">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">لا توجد عهد مسجلة حالياً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $custodies->links() }}</div>
        </div>
    </div>
</div>
@endsection