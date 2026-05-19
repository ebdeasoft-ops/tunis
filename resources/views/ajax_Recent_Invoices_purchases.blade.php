@if (isset($data) && !empty($data) && count($data) > 0)
    <div class="table-responsive">
        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.1);">
            <thead>
                <tr>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.Invoice_no') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.buyer name') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.supplierinvoicenumber') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.suppliername') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.date') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.branch') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.total') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.paymentmethod') }}</th>
                    <th class="border-bottom-0" style="color: #FF4F1F; font-size:11px">{{ __('home.operations') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)
                    <tr id="row-{{ $product->id }}">
                        <td>{{ $product->orderId }}</td>
                        
                        {{-- ملاحظة: يفضل تحميل 'user' من خلال العلاقة في Controller --}}
                        @php
                            $orderInfo = App\Models\orderTosupllier::find($product->orderId);
                        @endphp
                        <td>{{ $orderInfo->user->name ?? "-" }}</td>
                        
                        <td>{{ $product->Purchase_invoice_number }}</td> {{-- تم إغلاق الوسم هنا --}}
                        
                        <td dir="ltr"><strong>{{ $product->supllier->name }}</strong></td>
                        
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                        
                        <td>{{ $product->branch->name }}</td>
                        
                        <td>
                            <span class="font-weight-bold" style="color:red">
                                @if($product->recoveredـpieces != 0)
                                    {{ __('home.return') }}
                                @else
                                    {{ number_format($product->In_debt + $product['shipping fee'], 2) }} {{ __('home.SAR') }}
                                @endif
                            </span>
                        </td>

                        <td>
                            @php
                                $payMethods = [
                                    'Cash' => __('report.cash'),
                                    'Shabka' => __('report.shabka'),
                                    'Bank_transfer' => __('home.Bank_transfer'),
                                    'Credit' => __('report.credit')
                                ];
                                $pay = $payMethods[$product->Pay_Method_Name] ?? __('report.credit');
                            @endphp
                            <span class="badge badge-light p-2">{{ $pay }}</span>
                        </td>

                        <td>
                            <div class="btn-icon-list justify-content-center">
                                {{-- زر الطباعة/العرض --}}
                                <a class="btn btn-sm btn-primary" href="{{ url('purchasesShow/'.$product->orderId) }}" title="{{ __('home.show') }}">
                                    <i class="fas fa-print"></i>
                                </a>

                                {{-- زر تعديل الدفع --}}
                                <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" 
                                   data-id="{{ $product->orderId }}" 
                                   data-totalinvoice="{{ round(($product->In_debt) - ($product->discount), 2) == 0 ? __('home.return') : round(($product->In_debt) - ($product->discount), 2) }}" 
                                   data-toggle="modal" href="#paymentmethod" title="{{ __('home.updatepayment') }}">
                                    <i class="las la-wallet"></i>
                                </a>

                                {{-- الملفات المرفقة --}}
                                @if($product->attachments == null)
                                    <a class="modal-effect btn btn-sm btn-warning" data-id="{{ $product->orderId }}" data-toggle="modal" href="#uplaodmodal" title="{{ __('home.uplaodpdf') }}">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                @else
                                    <a class="btn btn-sm btn-success" target="_blank" href="{{ url('openfile/'.$product->attachments) }}" title="{{ __('home.dwonloadpdf') }}">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif

                                {{-- زر الحذف --}}
                                <a class="modal-effect btn btn-sm btn-danger" data-id="{{ $product->orderId }}" data-toggle="modal" href="#delete_quotation" title="{{ __('home.delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center" id="ajax_pagination_in_search">
        {{ $data->links() }}
    </div>

@else
    <div class="alert alert-custom alert-indicator-top alert-danger" role="alert">
        <div class="alert-content">
            <span class="alert-title">{{ __('home.alert') }}!</span>
            <span class="alert-text">{{ __('home.notfounddata') }}</span>
        </div>
    </div>
@endif