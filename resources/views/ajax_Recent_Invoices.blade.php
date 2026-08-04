@if (isset($data) && !empty($data) && count($data) > 0)
    <div class="table-responsive">
        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 1px solid #e1e6f1;">
            <thead>
                <tr>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.Invoice_no') }}</th>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.sallerName') }}</th>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.clietName') }}</th>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.date') }}</th>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.branch') }}</th>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.total') }}</th>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.paymentmethod') }}</th>
                    <th style="color: #FF4F1F; font-size:12px">الربط مع زكاة (Zatca)</th>
                    <th style="color: #FF4F1F; font-size:12px">{{ __('home.operations') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $avtSetting = App\Models\Avt::find(1);
                    $saleavt = $avtSetting ? $avtSetting->AVT : 0.15;
                @endphp

                @foreach ($data as $product)
                    @php
                        // حساب الإجمالي مع الضريبة
                        $netPrice =  $product->cashamount+$product->Bank_transfer+$product->bankamount+$product->creaditamount;
                        $totalWithTax = round($netPrice, 2);
                        
                        // تحديد نص طريقة الدفع
                        $payText = match($product->Pay) {
                            'Cash' => __('report.cash'),
                            'Shabka' => __('report.shabka'),
                            'Credit' => __('report.credit'),
                            'Bank_transfer' => __('home.Bank_transfer'),
                            default => __('home.Partition of the amount'),
                        };

                        // تجهيز رابط الواتساب
                        $cleanPhone = "966" . ltrim($product->customer->phone ?? '', '0');
                        $pdfLink = "https://demoo.ebdeaclients.online/ar/generate_pdf/" . $product->id;
                        $waMessage = "يسرنا خدمتك. فاتورتك رقم {$product->id} جاهزة للتحميل:\n" . $pdfLink;
                        $waFullUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode($waMessage);
                    @endphp

                    <tr id="{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->user->name ?? '---' }}</td>
                        <td dir="ltr">{{ $product->customer->name ?? '---' }}</td>
                        <td>{{ $product->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $product->branch->name }}</td>
                        <td class="font-weight-bold">
                            @if($totalWithTax == 0)
                                <span class="text-danger">{{ __('home.return') }}</span>
                            @else
                                {{ number_format($totalWithTax, 2) }}
                            @endif
                        </td>
                        <td>
                            <small>{{ $payText }}</small>
                            @if($product->Pay == "Partition")
                                <div class="text-muted" style="font-size: 10px;">
                                    {{ __('report.shabka') }}: {{ $product->bankamount }} | 
                                    {{ __('home.Bank_transfer') }}: {{ $product->Bank_transfer }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($product->sent_to_zatca == 1)
                                <span class="badge badge-success-light">
                                    <i class="fa fa-check-circle text-success"></i> مرسلة
                                </span>
                            @else
                                <span class="badge badge-danger-light">
                                    <i class="fa fa-times-circle text-danger"></i> مسودة
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-icon-list d-flex justify-content-center align-items-center" style="gap: 4px;">
                                <!-- زر الطباعة / العرض -->
                                <a href="showInvoiceRecent/{{ $product->id }}" class="btn btn-sm btn-primary" title="{{ __('home.show') }}">
                                    <i class="fas fa-print"></i>
                                </a>

                                <!-- زر الواتساب -->
                                <a href="{{ $waFullUrl }}" target="_blank" class="btn btn-sm btn-success" style="background-color: #25d366; border: none;" title="واتساب">
                                    <i class="fab fa-whatsapp"></i>
                                </a>

                                <!-- زر التحميل PDF -->
                                <a href="generate_pdf/{{ $product->id }}" target="_blank" class="btn btn-sm btn-secondary" title="{{ __('home.dwonloadpdf') }}">
                                    <i class="fas fa-file-pdf"></i>
                                </a>

                                <!-- زر تعديل طريقة الدفع -->
                                <a class="btn btn-sm btn-info modal-effect" 
                                   data-effect="effect-scale" 
                                   data-id="{{ $product->id }}" 
                                   data-totalinvoice="{{ $totalWithTax }}" 
                                   data-toggle="modal" href="#paymentmethod" title="{{ __('home.updatepayment') }}">
                                    <i class="las la-pen"></i>
                                </a>

                                <!-- نموذج ورار مرتجع المبيعات -->
                                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/return_sale') }}" method="POST" role="search" autocomplete="off" style="display: inline-block; margin: 0;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="invoice_no" value="{{ $product->id }}">
                                    <button style="background-color: #419BB2; border: none;" type="submit" class="btn btn-sm btn-success" title="{{ __('home.salesـreturned') }}">
                                        <i class="las la-search" style="font-size:15px"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3" id="ajax_pagination_in_search">
        {{ $data->links() }}
    </div>

@else
    <div class="alert alert-custom alert-indicator-top alert-danger fade show" role="alert">
        <div class="alert-content">
            <span class="alert-title">تنبيه!</span>
            <span class="alert-text">{{ __('home.notfounddata') }}</span>
        </div>
    </div>
@endif