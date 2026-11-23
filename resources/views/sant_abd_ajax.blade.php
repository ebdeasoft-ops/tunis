@php
    // مرفقات كل السندات المعروضة في الصفحة (استعلام واحد) حتى يظهر المرفق في كل أسطر السند
    $pageVouchers = (isset($data) && count($data) > 0)
        ? collect(method_exists($data, 'items') ? $data->items() : $data)->pluck('sent_abd_count')->filter()->unique()->values()
        : collect();
    $voucherAttachments = $pageVouchers->isEmpty() ? collect() : App\Models\credittransactions::whereIn('sent_abd_count', $pageVouchers)
        ->whereNotNull('attachments')->where('attachments', '!=', '')
        ->get(['sent_abd_count', 'attachments'])
        ->groupBy('sent_abd_count')
        ->map(fn ($rows) => $rows->pluck('attachments')->unique()->values());
@endphp
<div class="table-responsive">
    <table class="table table-bordered table-striped text-center mb-0">
        <thead>
            <tr>
                <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>
                <th class="border-bottom-0">{{ __('home.exportTime') }}</th>
                <th class="border-bottom-0">{{ __('home.employee') }}</th>
                <th class="border-bottom-0">{{ __('home.acount_name') }}</th>
                <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }}</th>
                <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                <th class="border-bottom-0">{{ __('home.operations') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data) && count($data) > 0)
            @foreach ($data as $index => $invoice)
            <tr>
                <td>{{ $invoice->sent_abd_count }}</td>
                <td>{{ $invoice->date_export ?: ($invoice->created_at ? (is_string($invoice->created_at) ? substr($invoice->created_at, 0, 10) : $invoice->created_at->format('Y-m-d')) : '-') }}</td>
                <td>{{ $invoice->user->name ?? '-' }}</td>
                <td>{{ $invoice->financial_accounts_data->name ?? '-' }}</td>
                <td>{{ $invoice->recive_amount }}</td>
                <td>
                    @if ($invoice->type == 'Cash')
                    {{ __('report.cash') }}
                    @elseif ($invoice->type == 'Bank_transfer')
                    {{ __('home.Bank_transfer') }}
                    @else
                    {{ __('report.shabka') }}
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px; flex-wrap: wrap;">

                        {{-- زر التعديل --}}
                        <button type="button"
                            class="btn btn-info btn-sm d-flex align-items-center justify-content-center edit-btn"
                            style="height: 30px; width: 30px;"
                            onclick="editVoucher(this, '{{ $invoice->sent_abd_count }}', '{{ $invoice->id }}')"
                            data-id="{{ $invoice->id }}"
                            data-sent_abd_count="{{ $invoice->sent_abd_count }}"
                            data-payment_acc="{{ $invoice->branchs_id }}" data-method="{{ $invoice->type }}"
                            data-date="{{ $invoice->date_export ?: ($invoice->created_at ? (is_string($invoice->created_at) ? substr($invoice->created_at, 0, 10) : $invoice->created_at->format('Y-m-d')) : '') }}" data-client_acc="{{ $invoice->customer_id }}"
                            data-amount="{{ $invoice->recive_amount }}" data-cost_center="{{ $invoice->cost_center }}"
                            data-tax_rate="{{ $invoice->vat == 1 ? '0.15' : '0' }}" data-notes="{{ $invoice->note }}"
                            title="{{ __('home.Edit') }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- زر تحميل PDF (أيقونة فقط) --}}
                        {{-- زر تحميل PDF (باستخدام الكلاس المتوافق مع FontAwesome القديم) --}}
                        <a href="{{ url('generate_pdf_reciept_ducoument/' . $invoice->id) }}"
                            class="btn btn-success btn-sm d-flex align-items-center justify-content-center text-white"
                            style="background-color: #419BB2; border-color: #419BB2; height: 30px; width: 30px;"
                            target="_blank" title="{{ __('home.dwonloadpdf') }}">
                            <i class="fas fa-download"></i>
                        </a>
                        {{-- زر الحذف --}}
                        <button type="button"
                            class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                            style="height: 30px; width: 30px;"
                            onclick="deleteFullVoucher('{{ $invoice->sent_abd_count }}')"
                            title="{{ __('home.Delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>

                        {{-- زر الطباعة --}}
                        <form action="{{ url('/print_reciept_ducoument') }}" method="POST" class="d-inline m-0">
                            @csrf
                            <input type="hidden" name="id" value="{{ $invoice->sent_abd_count }}">
                            <button type="submit"
                                class="btn btn-success btn-sm d-flex align-items-center justify-content-center"
                                style="height: 30px; width: 30px;" title="{{ __('home.print') }}">
                                <i class="fas fa-print"></i>
                            </button>
                        </form>

                        {{-- المرفقات (كل مرفقات السند) --}}
                        @php
                            $rowFiles = $invoice->sent_abd_count ? $voucherAttachments->get($invoice->sent_abd_count, collect()) : collect();
                            if ($invoice->attachments && !$rowFiles->contains($invoice->attachments)) {
                                $rowFiles = $rowFiles->concat([$invoice->attachments]);
                            }
                        @endphp
                        @if($rowFiles->isNotEmpty())
                        @foreach ($rowFiles as $file)
                        <a href="{{ url('openfile/' . $file) }}" target="_blank"
                            class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center"
                            style="height: 30px; width: 30px;" title="{{ __('home.show') }}">
                            <i class="fas fa-paperclip"></i>
                        </a>
                        @endforeach
                        @else
                        <span
                            class="badge badge-light text-muted border d-flex align-items-center justify-content-center px-2"
                            style="height: 30px; font-size: 11px;">
                            <i class="fas fa-ban mr-1"></i> {{ __('home.no_attachments') ?? 'لا يوجد' }}
                        </span>
                        @endif

                    </div>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="9" class="text-center text-muted py-3">{{ __('home.no_data_found') }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@if(isset($data) && method_exists($data, 'links'))
<div class="d-flex justify-content-center mt-3" id="ajax_pagination_in_search">
    {{ $data->links() }}
</div>
@endif