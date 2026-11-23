@if (isset($data) && !empty($data) && count($data) > 0)
@php
    // مرفقات كل السندات المعروضة في الصفحة (استعلام واحد) حتى يظهر المرفق في كل أسطر السند
    $pageVouchers = collect(method_exists($data, 'items') ? $data->items() : $data)
        ->pluck('sent_serf_count')->filter()->unique()->values();
    $voucherAttachments = App\Models\credittransactions::whereIn('sent_serf_count', $pageVouchers)
        ->whereNotNull('attachments')->where('attachments', '!=', '')
        ->get(['sent_serf_count', 'attachments'])
        ->groupBy('sent_serf_count')
        ->map(fn ($rows) => $rows->pluck('attachments')->unique()->values());
@endphp
<div class="table-responsive w-100" id="ajax_responce_allinvoicesDiv">
    <table class="table text-md-nowrap text-center our-table w-100" id="example2"
        style="border: 2px solid rgba(0,0,0,.3); width: 100% !important;">
            <thead>
                <tr class="bg-light">
                    <th>{{ __('home.decoumentNo') }}</th>
                    <th>{{ __('home.exportTime') }}</th>
                    <th>{{ __('report.date') }}</th>
                    <th>{{ __('home.employee') }}</th>
                    <th>{{ __('home.acount_name') }}</th>
                    <th>{{ __('accountes.Theamountpaid') }}</th>
                    <th>{{ __('home.paymentmethod') }}</th>
                    <th>{{ __('home.Attachment') }}</th>
                    <th>{{ __('home.operations') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $invoice)
                    <tr>
                        {{-- رقم المستند --}}
                        <td><span class="badge badge-secondary">{{ $invoice->sent_serf_count }}</span></td>

                        {{-- وقت الإنشاء وتاريخ التصدير --}}
                        <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $invoice->date_export }}</td>

                        {{-- الموظف والحساب --}}
                        <td>{{ $invoice->user->name ?? '---' }}</td>
                        <td>{{ $invoice->financial_accounts_data->name ?? '---' }}</td>

                        {{-- المبلغ --}}
                        <td><strong class="text-success">{{ number_format($invoice->recive_amount, 2) }}</strong></td>

                        {{-- طريقة الدفع --}}
                        <td>
                            @if ($invoice->Pay_Method_Name == 'Cash')
                                <span class="badge badge-pill badge-primary">{{ __('report.cash') }}</span>
                            @elseif ($invoice->Pay_Method_Name == 'Bank_transfer')
                                <span class="badge badge-pill badge-info">{{ __('home.Bank_transfer') }}</span>
                            @else
                                <span class="badge badge-pill badge-warning">{{ __('report.shabka') }}</span>
                            @endif
                        </td>

                        {{-- المرفقات --}}
                        <td>
                            @php
                                $rowFiles = $invoice->sent_serf_count ? $voucherAttachments->get($invoice->sent_serf_count, collect()) : collect();
                                if ($invoice->attachments && !$rowFiles->contains($invoice->attachments)) {
                                    $rowFiles = $rowFiles->concat([$invoice->attachments]);
                                }
                            @endphp
                            @forelse ($rowFiles as $file)
                                <a href="{{ url('openfile/' . $file) }}" target="_blank"
                                    class="btn btn-secondary btn-sm mb-1" title="{{ __('home.show') }}">
                                    <i class="fas fa-paperclip"></i>
                                </a>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </td>

                        {{-- العمليات --}}
                        <td>
                            <div class="btn-icon-list d-flex justify-content-center" style="gap: 5px;">

                                {{-- زر التعديل (تم ربط data attributes بالموديل بدقة) --}}
                                <button type="button" class="btn btn-info btn-sm edit-btn" data-id="{{ $invoice->id }}"
                                    data-serf_count="{{ $invoice->sent_serf_count }}" {{-- السطر الأهم لجلب كل الصفوف --}}
                                    data-payment_acc="{{ $invoice->branchs_id }}" data-method="{{ $invoice->type }}"
                                    data-date="{{ $invoice->date_export }}" data-client_acc="{{ $invoice->customer_id }}"
                                    data-amount="{{ $invoice->recive_amount }}" data-cost_center="{{ $invoice->cost_center }}"
                                    data-tax_rate="{{ $invoice->vat == 1 ? '0.15' : '0' }}" data-notes="{{ $invoice->note }}"
                                    title="{{ __('home.Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- زر الحذف --}}
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $invoice->id }}"
                                    title="{{ __('home.Delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>

                                {{-- زر الطباعة --}}
                                <form action="{{ url('print_reciept') }}" method="POST" target="_blank" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $invoice->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm" title="{{ __('home.print') }}">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- الترقيم --}}
    <div class="d-flex justify-content-center mt-3" id="ajax_pagination_in_search">
        {{ $data->links() }}
    </div>

@else
    <div class="alert alert-danger text-center shadow-sm">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ __('home.notfounddata') }}
    </div>
@endif