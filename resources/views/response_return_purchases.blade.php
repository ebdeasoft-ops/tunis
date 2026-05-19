      <div class="table-responsive mg-t-40 " style="width:95%,margin:20px">
                    <table style="border:2px solid rgba(0,0,0,.3)" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                          <thead>
                                <tr>
                                    <th style="vertical-align: middle" class="border-bottom-0"># </th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.productNo') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.product') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('users.branch') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.quantity') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.purchase') }} </th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.total') }} </th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.RETURNSPURCHAE') }}</th>
                                    <th style="vertical-align: middle" class="border-bottom-0">{{ __('home.operations') }}</th>



                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $totalprice = 0;
                                $totalAddedvalue = 0;
                                $orderId=0; ?>
                                @foreach ($data['product'] as $product)
                                    <?php $i++;
                                     if($i==1){
                                        $orderId=$product->order_owner;
                                    }
                                    $totalprice += $product->purchasingـprice * $product->numberofpice;
                                    $totalAddedvalue += $product->Added_Value * $product->numberofpice; ?>
                                    <tr>
                                        @if($product->numberofpice!=0)
                                        <td style="vertical-align: middle">{{ $i }}</td>
                                        <td style="vertical-align: middle" dir=ltr>{{ $product->productData->Product_Code }}</td>
                                        <td style="vertical-align: middle">{{ $product->product_name }}</td>
                                        <td style="vertical-align: middle">{{ $data['branch'] }}</td>
                                        <td style="vertical-align: middle">{{ $product->numberofpice }}</td>
                                        <td style="vertical-align: middle">{{ $product->purchasingـprice }}</td>
                                        <td style="vertical-align: middle">{{ $product->Added_Value }}</td>
                                        <td style="vertical-align: middle">{{ ($product->Added_Value + $product->purchasingـprice) * $product->numberofpice }}
                                        </td>
                                        <td style="vertical-align: middle">{{ $product->returns_purchase }}</td>

                                        <td style="vertical-align: middle">
                                            <a style="background-color: #419BB2" class="modal-effect btn btn-sm btn-info mb-1" data-effect="effect-scale"
                                                data-id="{{ $product->productData->id }}"
                                                data-section_name="{{ $product->product_name }}"
                                                data-ordernumber="{{ $data['supllier']->id }}"
                                                data-description="{{ $product->numberofpice }}" data-toggle="modal"
                                                href="#exampleModal2" title="تعديل"><i class="las la-pen"></i></a>

                                        </td>
                                        @endif
                                    <tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="table-responsive mg-t-30 table-padding">
                            <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped" id="tableTotalPrice"
                                name="tableTotalPrice"width="50%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <col style="width:20%">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                        <th class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                        <th class="border-bottom-0">{{ __('home.discount') }}</th>
                                        <th class="border-bottom-0">{{ __('home.total') }} </th>

                                    </tr>
                                </thead>

                                <body>
                                    <tr>
                                        <td> {{ $totalprice }}</td>
                                        <td>{{ $totalAddedvalue }}</td>
                                        <td>{{$totalprice==0?0:$data['resource_purchases']->discount}}</td>
                                        <td>{{ $totalprice==0?0:$totalAddedvalue + $totalprice-$data['resource_purchases']->discount }}</td>
                                    </tr>

                                </body>

                            </table>
                            
                            </div>
                            
                            <br>
                            
                              <div class="d-flex justify-content-center mt-3">

                                    <a class="btn btn-success print-style p-1" href="{{ url('/' . ($page = 'printReturnpurchases').'/'.$orderId) }}">
                                        {{__('home.print')}}
                                        <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                        </svg>
                                    </a> 

                            </div>
                            <br>