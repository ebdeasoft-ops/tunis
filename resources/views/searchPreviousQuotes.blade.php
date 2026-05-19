<table class="table text-md-nowrap text-center our-table" id="example12" data-page-length='50'>
    <thead>
        <tr>
            <th class="border-bottom-0" style="color: #FF4F1F; font-size:12px">{{ __('home.Invoice_no') }}</th>
            <th class="border-bottom-0" style="color: #FF4F1F; font-size:12px">{{ __('home.clietName') }}</th>
            <th class="border-bottom-0" style="color: #FF4F1F; font-size:12px">{{ __('home.date') }}</th>
            <th class="border-bottom-0" style="color: #FF4F1F; font-size:12px">{{ __('home.branch') }}</th>
            <th class="border-bottom-0" style="color: #FF4F1F; font-size:12px">{{ __('home.total') }}</th>
            <th class="border-bottom-0" style="color: #FF4F1F; font-size:12px">{{ __('home.operations') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $avtSetting = App\Models\Avt::find(1);
            $avtRate = $avtSetting ? $avtSetting->AVT : 0.15; // الافتراضي 15% إذا لم يوجد
        @endphp

        @foreach ($data as $product)
            <tr id="{{ $product->id }}">
                <td>{{ $product->id }}</td>
                <td dir="ltr">{{ $product->customer->name }}</td>
                <td>{{ $product->created_at->format('Y-m-d') }}</td>
                <td>{{ $product->branch->name }}</td>
                
                @php
                    // حساب الإجماليات والخصومات
                    $items = App\Models\offer_price_to_customer_items::where('order_id', $product->id)->get();
                    $subTotal = $items->sum(fn($i) => $i->PriceWithoudTax * $i->quantity);
                    $itemsDiscount = $items->sum('discount');
                    
                    // خصم إضافي على مستوى الفاتورة
                    $invoice = App\Models\offer_price_to_customer::find($product->id);
                    $extraDiscount = $invoice ? $invoice->discount : 0;

                    $totalNet = round($subTotal, 2) - round($itemsDiscount + $extraDiscount, 2);
                    $totalWithTax = round($totalNet * $avtRate, 2) + $totalNet;
                @endphp

                <td class="font-weight-bold text-dark">
                    {{ number_format($totalWithTax, 2) }}
                </td>

                <td>
                    <div class="btn-icon-list d-flex justify-content-center" style="gap: 5px;">
               
                        <a class="btn btn-sm btn-danger modal-effect" data-effect="effect-scale" 
                           data-id="{{ $product->id }}" data-toggle="modal" href="#delete_quotation" title="{{ __('home.delete') }}">
                            <i class="las la-trash"></i>
                        </a>

                        <a class="btn btn-sm btn-success" href="generate_pdf_qoute/{{ $product->id }}" target="_blank" title="{{ __('home.dwonloadpdf') }}">
                            <i class="fas fa-file-pdf"></i>
                        </a>

                  

                        <form action="{{ url(LaravelLocalization::getCurrentLocale() . '/print_order_perice_to_customer') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="OrderNoprint" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-sm btn-primary" style="background-color: #419BB2;" title="{{ __('home.show') }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4" id="ajax_pagination_in_search">
    {{ $data->links() }}
</div>