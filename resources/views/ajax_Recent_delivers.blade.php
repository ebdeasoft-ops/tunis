@if (isset($data) && !$data->isEmpty() && count($data) > 0)
    <div class="table-responsive">
        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" style="width: 100%; border: 1px solid #e1e6f1;">
            <colgroup>
                <col style="width: 5%">
                <col style="width: 40%">
                <col style="width: 30%">
                <col style="width: 25%">
            </colgroup>

            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">#</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.clietName') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.total') }}</th>
                    <th style="color: #FF4F1F; font-size: 13px; font-weight: 600;" class="border-bottom-0">{{ __('home.operations') }}</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach ($data as $index => $product)
                    <tr id="{{ $product['id'] ?? $product->id }}">
                        <td dir="ltr" data-target="id" style="vertical-align: middle;">{{ $index + 1 }}</td>
                        <td dir="ltr" style="vertical-align: middle;">{{ $product->supllier->name ?? '' }}</td>
                        <td data-target="numberofpice" style="vertical-align: middle; font-weight: bold;">{{ round($product->blance, 2) }}</td>
                        <td style="vertical-align: middle;">
                            <div class="d-flex justify-content-center align-items-center">
                                @php
                                    $actionUrl = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/print_delivery_to_anoter_supplier';
                                @endphp
                                <form action="{{ url($actionUrl) }}" method="POST" autocomplete="off" class="m-0">
                                    @csrf
                                    <input type="hidden" name="OrderNoprint" id="OrderNoprint" value="{{ $product->to_dlivery_id }}">

                                    <button style="background-color: #419BB2; border: none; font-size: 14px;" type="submit" class="btn btn-primary btn-sm px-3 py-1.5 text-white d-flex align-items-center gap-1">
                                        {{ __('home.show') }}
                                        <svg style="width: 18px; height: 18px; fill: currentColor;" class="ms-1" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z"></path>
                                        </svg>
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