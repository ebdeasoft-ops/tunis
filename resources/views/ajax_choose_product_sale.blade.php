@php 
        // تعيين اللغة بناءً على لغة النظام الحالية لضمان استمرارها في طلبات الـ AJAX
    app()->setLocale($getLocale); 
@endphp

@if (isset($data) && !empty($data) && count($data) > 0)
    @php $i = 1; @endphp
    <div class="table-responsive">
        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%"
            style="border: 2px solid rgba(0,0,0,.3);">
            <thead>
                <tr>
                    <th style="font-size: 15px">#</th>
                    <th style="font-size: 15px">{{ __('home.productNo') }}</th>
                    <th style="font-size: 15px">{{ __('home.product') }}</th>
                    <th style="font-size: 15px">{{ __('home.productlocation') }}</th>
                    <th style="font-size: 15px">{{ __('home.quantity') }}</th>
                                         @if(Auth()->user()->id==17|| Auth()->user()->id==28|| Auth()->user()->id==11)
                <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th> 
                <th style="font-size: 13px" class="border-bottom-0">{{__('home.average_cost')}}</th>
                @endif
                    <th style="font-size: 13px">{{ __('home.sellingproduct without tax') }}</th>
                    <th style="font-size: 15px">{{ __('home.refnumber') }}</th>
                    <th style="font-size: 13px">{{ __('home.notesClient') }}</th>
                    <th style="font-size: 15px">{{ __('home.Add') }} </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)
                    <tr id="{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td style="color:{{ $product->products_mix != 0 ? 'red' : 'green' }}; font-size:18px;">
                            {{ $product->Product_Code }}
                        </td>
                        {{-- ملاحظة: اسم المنتج وموقعه سيظلان بالعربية إذا كانا مخزنين بالعربية في الداتابيز --}}
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->Product_Location }}</td>
                        <td>
                            @if($product->numberofpice <= 0)
                                <span style="color:red; font-size:19px;">{{ $product->numberofpice }} /
                                    {{ __('home.notavailable') }}</span>
                            @else
                                <span style="color:green; font-size:18px;">{{ $product->numberofpice }}</span>
                            @endif
                        </td>
                         @if(Auth()->user()->id==17|| Auth()->user()->id==28|| Auth()->user()->id==11)

                <td id="tableData"  data-target="numberofpice">{{ $product->purchasingـprice }}</td>
                <td id="tableData"  data-target="numberofpice">{{ $product->average_cost }}</td>
           
                                @endif
                        <td>{{ $product->sale_price }}</td>
                        <td>{{ $product->refnumber == null ? __('home.notdata') : str_replace("+", " - ", $product->refnumber) }}
                        </td>
                        <td>{{ $product->notes }}</td>
                        <td>
                            <button
                                style="padding: 6px 12px; background:{{ $product->numberofpice <= 0 ? 'grey' : 'green' }}; border-color:white; color:white;"
                                type="button" data-dismiss="modal"
                                onclick="chooseProduct('{{$product->id}}','{{$product->Product_Code}}','{{$product->product_name}}','{{$product->purchasingـprice}}','{{$product->sale_price}}','{{$product->Product_Location}}','{{$product->numberofpice}}','{{$currentrow}}')">
                                {{ __('home.Add') }}
                            </button>
                            <br>
                            <button style="padding: 6px 12px; margin: 2px;" class="btn btn-warning" data-dismiss="modal"
                                onclick="replaceproduct('{{$product->id}}')">
                                {{ __('home.transactions') }}
                            </button>

                            @php
                                $count = App\Models\products::where('main_product', $product->main_product)->where('main_product', '!=', 0)->count();
                            @endphp

                            @if($count > 1)
                                <br>
                                <button style="padding: 6px 12px" class="btn btn-info" data-dismiss="modal"
                                    onclick="replaceproductorginal('{{$product->main_product}}')">
                                    {{ __('home.replace') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="justify-content-start" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>
    </div>
@else
    <div class="alert alert-danger">
        {{ __('home.notfounddata') }}
    </div>
@endif