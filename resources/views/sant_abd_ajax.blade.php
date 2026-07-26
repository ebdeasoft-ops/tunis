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
                <td>{{ $invoice->created_at }}</td>
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
                    <div class="btn-icon-list d-flex justify-content-center" style="gap: 5px;">

                        {{-- زر التعديل (تم ربط data attributes بالموديل بدقة) --}}
                        <button type="button" class="btn btn-info btn-sm edit-btn" data-id="{{ $invoice->id }}"
                            data-sent_abd_count="{{ $invoice->sent_abd_count }}" {{-- السطر الأهم لجلب كل الصفوف --}}
                            data-payment_acc="{{ $invoice->branchs_id }}" data-method="{{ $invoice->type }}"
                            data-date="{{ $invoice->date_export }}" data-client_acc="{{ $invoice->customer_id }}"
                            data-amount="{{ $invoice->recive_amount }}" data-cost_center="{{ $invoice->cost_center }}"
                            data-tax_rate="{{ $invoice->vat == 1 ? '0.15' : '0' }}" data-notes="{{ $invoice->note }}"
                            title="{{ __('home.Edit') }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- زر الحذف --}}
                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                            onclick="deleteFullVoucher('{{ $invoice->sent_abd_count }}')"
                            title="{{ __('home.Delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form action="{{ url('/print_reciept_ducoument') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $invoice->sent_abd_count }}">
                            <button type="submit" class="btn btn-sm btn-success">
                                {{ __('home.print') }}
                            </button>
                        </form>

                        @if($invoice->attachments)
                        <a href="{{ url('openfile/' . $invoice->attachments) }}" target="_blank"
                            class="btn btn-secondary btn-sm" title="{{ __('home.show') }}">
                            <i class="fas fa-paperclip"></i>
                        </a>
                        @else
                        <span class="badge badge-light text-muted border px-2 py-1" style="font-size: 12px;">
                            <i class="fas fa-ban mr-1"></i> {{ __('home.no_attachments') ?? 'لا يوجد' }}
                        </span>
                        @endif
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
<div class="d-flex justify-content-center mt-3" id="ajax_pagination_in_search">
    {{ $data->links() }}
</div>