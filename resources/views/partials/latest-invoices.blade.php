{{-- partials/latest-invoices.blade.php --}}
@forelse($latestInvoices as $invoice)
    @php
        $totalAmount = ($invoice->cashamount ?? 0) + ($invoice->bankamount ?? 0) + ($invoice->creaditamount ?? 0) + ($invoice->Bank_transfer ?? 0);
        if ($totalAmount == 0 && isset($invoice->Price)) {
            $totalAmount = $invoice->Price;
        }
        $isReturned = $invoice->returnSales && $invoice->returnSales->isNotEmpty();
        if ($isReturned) {
            $paymentMethod = __('home.sales_return');
            $badgeStyle = 'background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;';
        } else {
            $payValue = strtolower(trim($invoice->Pay ?? ''));
            switch ($payValue) {
                case 'cash':
                    $paymentMethod = __('home.cash');
                    $badgeStyle = 'background-color: #dcfce7; color: #166534; border: 1px solid #86efac;';
                    break;
                case 'shabka': case 'card': case 'bank': case 'network': case 'span':
                    $paymentMethod = __('home.card_span');
                    $badgeStyle = 'background-color: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc;';
                    break;
                case 'bank_transfer': case 'transfer':
                    $paymentMethod = __('home.bank_transfer');
                    $badgeStyle = 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde047;';
                    break;
                case 'partition': case 'more': case 'multi':
                    $paymentMethod = __('home.split_payment');
                    $badgeStyle = 'background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;';
                    break;
                case 'credit': case 'creadit': case 'dept':
                    $paymentMethod = __('home.credit');
                    $badgeStyle = 'background-color: #ffe4e6; color: #be123c; border: 1px solid #fda4af;';
                    break;
                default:
                    if ($invoice->morepayment_way == 1) {
                        $paymentMethod = __('home.split_payment');
                        $badgeStyle = 'background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;';
                    } elseif (($invoice->bankamount ?? 0) > 0) {
                        $paymentMethod = __('home.card_span');
                        $badgeStyle = 'background-color: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc;';
                    } elseif (($invoice->Bank_transfer ?? 0) > 0) {
                        $paymentMethod = __('home.bank_transfer');
                        $badgeStyle = 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde047;';
                    } elseif (($invoice->creaditamount ?? 0) > 0) {
                        $paymentMethod = __('home.credit');
                        $badgeStyle = 'background-color: #ffe4e6; color: #be123c; border: 1px solid #fda4af;';
                    } else {
                        $paymentMethod = $invoice->Pay ?? __('home.cash');
                        $badgeStyle = 'background-color: #dcfce7; color: #166534; border: 1px solid #86efac;';
                    }
                    break;
            }
        }
    @endphp
    <tr style="border-bottom: 1px solid #f1f5f9;">
        <td class="py-3 px-3 text-start">
            <span class="fw-bold px-2 py-1 rounded" style="background-color: #f1f5f9; color: #334155; font-family: monospace; font-size: 13px;">
                #{{ $invoice->invoice_number ?? $invoice->id }}
            </span>
        </td>
        <td class="py-3 px-3 text-start fw-semibold text-dark fs-14">
            <i class="far fa-user text-muted me-1 fs-12"></i>
            {{ $invoice->customer->name ?? $invoice->customer->customer_name ?? __('home.cash_customer') }}
        </td>
        <td class="py-3 px-3">
            <span class="fw-extrabold fs-15 {{ $isReturned ? 'text-danger' : 'text-success' }}" style="letter-spacing: 0.3px;">
                {{ $isReturned ? '-' : '' }}{{ number_format($totalAmount, 2) }}
                <small class="fs-11 text-muted fw-normal me-1">{{ __('home.SAR') }}</small>
            </span>
        </td>
        <td class="py-3 px-3">
            <span class="px-3 py-1 rounded-pill fw-bold fs-12 d-inline-block" style="{{ $badgeStyle }}">
                {{ $paymentMethod }}
            </span>
        </td>
        <td class="py-3 px-3">
            @if($isReturned)
                <span class="badge bg-danger-transparent text-danger px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                    <i class="fas fa-undo me-1"></i> {{ __('home.returned') }}
                </span>
            @elseif($invoice->status == 1 || $invoice->save == 1)
                <span class="badge bg-success-transparent text-success px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                    <i class="fas fa-check-circle me-1"></i> {{ __('home.completed') }}
                </span>
            @else
                <span class="badge bg-warning-transparent text-warning px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                    <i class="fas fa-clock me-1"></i> {{ __('home.pending') }}
                </span>
            @endif
        </td>
        <td class="py-3 px-3 text-end text-muted fs-12 fw-medium">
            <i class="far fa-clock me-1 text-light-gray"></i>
            {{ $invoice->created_at ? $invoice->created_at->diffForHumans() : '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <div class="mb-2">
                <i class="fas fa-inbox text-light fs-1"></i>
            </div>
            <p class="mb-0 fw-semibold">{{ __('home.no_transactions_today') }}</p>
        </td>
    </tr>
@endforelse