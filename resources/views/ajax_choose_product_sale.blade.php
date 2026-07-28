{{ app()->setLocale($getLocale) }}
@if (isset($data) && $data->isNotEmpty())
    <div class="table-responsive p-3">
        <table class="table text-center our-table" id="SearchProductTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('home.productNo') }}</th>
                    <th>{{ __('home.product') }}</th>
                    <th>{{ __('home.branch') }}</th>
                    <th>{{ __('home.productlocation') }}</th>
                    <th>{{ __('home.quantity') }}</th>
                    @can('System setting')
                        <th>{{ __('home.purchaseproductwithouttax') }}</th>
                        <th>{{ __('home.average_cost') }}</th>
                    @endcan
                    <th>{{ __('home.sellingproduct without tax') }}</th>
                    <th>{{ __('home.refnumber') }}</th>
                    <th>{{ __('home.notesClient') }}</th>
                    <th>{{ __('home.Add') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)
                    <tr id="{{ $product->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge {{ $product->products_mix != 0 ? 'bg-danger' : 'bg-success' }} text-white">
                                {{ $product->Product_Code }}
                            </span>
                        </td>
                        <td class="font-weight-bold">{{ $product->product_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $product->branch->name ?? '**' }}</span></td>
                        <td>{{ $product->Product_Location }}</td>
                        <td>
                            @if($product->numberofpice <= 0)
                                <span class="text-danger fw-bold">{{ __('home.notavailable') }}</span>
                            @else
                                <span class="text-success fw-bold" style="font-size: 1.1rem;">{{ $product->numberofpice }}</span>
                            @endif
                        </td>
                        @can('System setting')
                            <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                            <td>{{ number_format($product->average_cost, 2) }}</td>
                        @endcan
                        <td class="fw-bold text-primary">{{ number_format($product->sale_price, 2) }}</td>
                        <td class="text-muted small">
                            {{ $product->refnumber == null ? __('home.notdata') : str_replace("+", " - ", $product->refnumber) }}
                        </td>
                        <td class="text-muted small">{{ $product->notes ?? '-' }}</td>
                        <td>
                            <div class="d-flex flex-column align-items-center">
                                <button class="btn btn-action btn-add" data-dismiss="modal"
                                    onclick="chooseProduct('{{$product->id}}','{{$product->Product_Code}}','{{$product->product_name}}','{{$product->purchasingـprice}}','{{$product->sale_price}}','{{$product->Product_Location}}','{{$product->numberofpice}}','{{$currentrow}}')">
                                    <i class="fa fa-plus-circle me-1"></i> {{ __('home.Add') }}
                                </button>

                                <div class="btn-group mt-1">
                                    <button class="btn btn-action btn-warning" data-dismiss="modal" onclick="replaceproduct('{{$product->id}}')">
                                        {{ __('home.transactions') }}
                                    </button>
                                    @php $count = App\Models\products::where('main_product', $product->main_product)->where('main_product', '!=', 0)->count(); @endphp
                                    @if($count > 1)
                                        <button class="btn btn-action btn-info" data-dismiss="modal" onclick="replaceproductorginal('{{$product->main_product}}')">
                                            {{ __('home.replace') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>
    </div>
@else
    <div class="alert alert-danger text-center">
        <i class="fa fa-info-circle"></i> {{ __('home.notfounddata') }}
    </div>
@endif
