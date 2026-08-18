{{-- partials/branch-transfers.blade.php --}}
@forelse($transfers as $transfer)
    <tr style="border-bottom: 1px solid #f1f5f9;">
        <td class="py-3 px-3 text-start">
            <span class="fw-bold px-2 py-1 rounded" style="background-color: #f1f5f9; color: #334155; font-family: monospace; font-size: 13px;">
                #{{ $transfer->id }}
            </span>
        </td>
        <td class="py-3 px-3">
            <i class="fas fa-warehouse text-muted me-1 fs-12"></i>
            {{ $transfer->branchfrom->name ?? '—' }}
        </td>
        <td class="py-3 px-3">
            <i class="fas fa-long-arrow-alt-left text-primary"></i>
        </td>
        <td class="py-3 px-3">
            <i class="fas fa-warehouse text-muted me-1 fs-12"></i>
            {{ $transfer->branchto->name ?? '—' }}
        </td>
        <td class="py-3 px-3 fw-bold text-success">
            {{ number_format($transfer->Totalcost, 2) }} {{ __('home.SAR') }}
        </td>
        <td class="py-3 px-3">
            @if($transfer->status === 'completed')
                <span class="badge bg-success-transparent text-success px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                    <i class="fas fa-check-circle me-1"></i> {{ __('home.transfer_completed') }}
                </span>
            @else
                <span class="badge bg-warning-transparent text-warning px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                    <i class="fas fa-clock me-1"></i> {{ __('home.transfer_pending') }}
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <div class="mb-2">
                <i class="fas fa-exchange-alt text-light fs-1"></i>
            </div>
            <p class="mb-0 fw-semibold">{{ __('home.no_transfers_today') }}</p>
        </td>
    </tr>
@endforelse