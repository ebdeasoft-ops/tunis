@if (isset($data) && $data->count() > 0)
    <div class="table-responsive">
        <table class="table text-center our-table shadow-sm" id="SearchProductTable" style="border: 1px solid #e1e1e1;">
            <thead class="bg-light">
                <tr>
                    <th class="text-primary" style="width: 5%">{{ __('home.Invoice_no') }}</th>
                    <th class="text-primary">{{ __('home.sallerName') }}</th>
                    <th class="text-primary">{{ __('home.clietName') }}</th>
                    <th class="text-primary">{{ __('home.date') }}</th>
                    <th class="text-primary">{{ __('home.branch') }}</th>
                    <th class="text-primary">{{ __('home.total') }}</th>
                    <th class="text-primary" style="width: 25%">{{ __('home.operations') }}</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    // جلب قيمة الضريبة مرة واحدة خارج الحلقة لتحسين الأداء
                    $saleavt = App\Models\Avt::find(1)->AVT ?? 0.15; 
                @endphp

                @foreach ($data as $product)
                    <tr id="row_{{ $product->id }}">
                        <td class="font-weight-bold">{{ $product->id }}</td>
                        <td><span class="badge badge-light">{{ $product->user->name ?? 'N/A' }}</span></td>
                        <td>{{ $product->customer->name ?? __('home.Cash Custome') }}</td>
                        <td>{{ $product->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $product->branch->name ?? '' }}</td>
                        <td class="font-weight-bold text-dark">
                            @php
                                $netPrice = $product->Price - $product->discount;
                                $totalWithTax = round($netPrice + ($netPrice * $saleavt), 2);
                            @endphp
                            
                            @if($totalWithTax == 0)
                                <span class="text-danger">{{ __('home.return') }}</span>
                            @else
                                {{ number_format($totalWithTax, 2) }} {{ __('home.SAR') }}
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="update_pending_invoice/{{ $product->id }}" 
                                   class="btn btn-sm btn-success-light" 
                                   title="{{ __('home.updateinvoice') }}">
                                    <i class="fas fa-edit"></i> {{ __('home.updateinvoice') }}
                                </a>

                                <a href="showInvoiceRecent__pending/{{ $product->id }}" 
                                   class="btn btn-sm btn-info-light" 
                                   title="{{ __('home.show') }}">
                                    <i class="fas fa-print"></i> {{ __('home.show') }}
                                </a>

                       
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>
    </div>
@else
    <div class="alert alert-custom alert-indicator-top indicator-danger" role="alert">
        <div class="alert-content">
            <span class="alert-title text-danger">نأسف!</span>
            <p>{{ __('home.notfounddata') }}</p>
        </div>
    </div>
@endif