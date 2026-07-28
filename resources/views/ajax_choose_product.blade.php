@if (isset($data) && !empty($data) && count($data) > 0)
<div class="table-responsive">
    <table class="table table-hover table-bordered text-md-nowrap text-center our-table align-middle" id="SearchProductTable" width="100%" style="border: 1px solid rgba(0,0,0,.15); box-shadow: 0 2px 4px rgba(0,0,0,0.04);">

        <thead class="bg-light text-dark fw-bold">
            <tr>
                <th style="font-size: 14px; vertical-align: middle;">#</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.productNo') }}</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.product') }}</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.branch') }}</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.productlocation') }}</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.suppliername') }}</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.quantity') }}</th>
                @can('System setting')
                    <th style="font-size: 13px; vertical-align: middle;">{{ __('home.purchaseproductwithouttax') }}</th>
                @endcan
                <th style="font-size: 13px; vertical-align: middle;">{{ __('home.sellingproduct without tax') }}</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.refnumber') }}</th>
                <th style="font-size: 14px; vertical-align: middle;">{{ __('home.Add') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $product)
                @php
                    $orderproduct = App\Models\orderDetails::orderBy('created_at', 'desc')->where('product_id', $product->id)->first();
                    $supplierName = '-';
                    if ($orderproduct && $orderproduct->orderTosupllier && $orderproduct->orderTosupllier->supllier) {
                        $supplierName = $orderproduct->orderTosupllier->supllier->name;
                    }
                @endphp

                <tr id="product_row_{{{ $product->id }}}">
                    <td class="text-muted small" dir="ltr">{{ $product->id }}</td>
                    <td class="fw-bold text-secondary" dir="ltr">{{ $product->Product_Code }}</td>
                    <td class="text-start ps-3 fw-bold">{{ $product->product_name }}</td>
                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ $product->branch->name ?? '**' }}</span></td>
                    <td class="text-muted">{{ $product->Product_Location ?? '-' }}</td>
                    <td class="text-truncate" style="max-width: 150px;">{{ $supplierName }}</td>
                    <td>
                        <span class="fw-bold {{ $product->numberofpice > 5 ? 'text-success' : 'text-danger' }}">
                            {{ $product->numberofpice }}
                        </span>
                    </td>

                    @can('System setting')
                        <td class="table-light fw-bold text-dark">{{ $product->purchasingـprice }}</td>
                    @endcan

                    <td class="fw-bold text-primary">{{ $product->sale_price }}</td>
                    <td dir="ltr" class="small text-muted">
                        {{ $product->refnumber == null ? __('home.notdata') : str_replace("+", " - ", $product->refnumber) }}
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm px-3 shadow-sm d-inline-flex align-items-center"
                                type="button"
                                data-dismiss="modal"
                                onclick="chooseProduct('{{ $product->id }}','{{ addslashes($product->product_name) }}','{{ $product->purchasingـprice }}','{{ $product->sale_price }}','{{ $product->Product_Code }}','{{ $product->numberofpice }}')">
                            <i class="fe fe-plus me-1"></i> {{ __('home.Add') }}
                        </button>
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
    <div class="alert alert-custom alert-danger text-center my-3 p-3 shadow-sm" role="alert">
        <i class="fe fe-info me-2"></i> {{ __('home.notfounddata') }}
    </div>
@endif
