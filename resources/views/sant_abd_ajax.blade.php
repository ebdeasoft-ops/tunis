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
                            <form action="{{ url('/print_reciept_ducoument') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $invoice->sent_abd_count }}">
                                <button type="submit" class="btn btn-sm btn-success">
                                    {{ __('home.print') }}
                                </button>
                            </form>

                            @if($invoice->attachments != null)
                                <a target="_blank" href="{{ url('/openfile/' . $invoice->attachments) }}" class="btn btn-sm btn-info">
                                    {{ __('home.show') }}
                                </a>
                            @else
                                -
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