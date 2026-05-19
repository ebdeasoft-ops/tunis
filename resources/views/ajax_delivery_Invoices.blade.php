@if (isset($data) && !$data->isEmpty() && count($data) > 0)
    <div class="table-responsive">
        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" style="width: 100%; border: 1px solid #e1e6f1;">
            <colgroup>
                <col style="width: 8%">
                <col style="width: 15%">
                <col style="width: 20%">
                <col style="width: 12%">
                <col style="width: 10%">
                <col style="width: 12%">
                <col style="width: 10%">
                <col style="width: 13%">
            </colgroup>

            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.sallerName') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.clietName') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.date') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.branch') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.total') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.operations') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $product)
                    <tr id="{{ $product->id }}">
                        <td data-target="id" style="vertical-align: middle;">{{ $product->id }}</td>
                        <td data-target="id" style="vertical-align: middle;">{{ $product->user->name ?? '' }}</td>
                        <td dir="ltr" data-target="id" style="vertical-align: middle;">{{ $product->customer->name ?? '' }}</td>
                        <td data-target="numberofpice" style="vertical-align: middle;">{{ $product->created_at }}</td>
                        <td data-target="numberofpice" style="vertical-align: middle;">{{ $product->branch->name ?? '' }}</td>
                        <td style="vertical-align: middle;">
                            @if ($product->Pay == 'Cash')
                                {{ __('report.cash') }}
                            @elseif ($product->Pay == 'Shabka')
                                {{ __('report.shabka') }}
                            @elseif ($product->Pay == 'Credit')
                                {{ __('report.credit') }}
                            @elseif ($product->Pay == 'Bank_transfer')
                                {{ __('home.Bank_transfer') }}
                            @else
                                {{ __('home.Partition of the amount') }}
                            @endif
                        </td>
                        <td data-target="numberofpice" style="vertical-align: middle; font-weight: bold;">
                            {{ round(($product->Price - $product->discount), 2) }}
                        </td>
                        <td style="vertical-align: middle;">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <a style="color: #23395D; font-size: 14px;" class="btn btn-sm btn-light w-100 d-flex align-items-center justify-content-center" href="showInvoiceRecentdelivery/{{ $product->id }}">
                                    <i style="color: #072c3c;" class="fas fa-print me-1"></i>&nbsp;{{ __('home.show') }}
                                </a>

                                @php
                                    $actionUrl = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/return_sale_delivery';
                                @endphp
                                <form action="{{ url($actionUrl) }}" method="POST" autocomplete="off" class="w-100 m-0">
                                    @csrf
                                    <input type="hidden" id="invoice_no" name="invoice_no" value="{{ $product->id }}">
                                    
                                    <button style="background-color: #419BB2; border: none; font-size: 13px;" type="submit" class="btn btn-success btn-sm w-100 text-white py-1 px-2 d-flex align-items-center justify-content-center">
                                        <i class="las la-search me-1" style="font-size: 14px;"></i>{{ __('home.delivery_return') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-start mt-3" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>
    </div>
@else
    <div class="alert alert-danger mt-3">
        {{ __('home.notfounddata') }}
    </div>
@endif