@if (@isset($products) && !@empty($products) && count($products) > 0)
    <div class="table-responsive">
        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
            <thead>
                <tr>
                    <th class="border-bottom-0">#</th>
                    <th class="border-bottom-0">{{ __('report.invoiceNo') }}</th>
                    <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                    <th class="border-bottom-0">{{ __('home.product') }}</th>
                    <th class="border-bottom-0">{{ __('report.date') }}</th>
                    <th class="border-bottom-0">{{ __('home.operationtype') }}</th>
                    <th class="border-bottom-0">-</th>
                    <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                    <th class="border-bottom-0">{{ __('home.price') }}</th>
                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.operations') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $invoice)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $invoice['id'] }}</td>
                    <td dir='ltr'>{{ $invoice['Product_Code'] }}</td>
                    <td>{{ $invoice['product_name'] }}</td>
                    <td>{{ $invoice['created_at'] }}</td>
                    <td style="color:red">{{ $invoice['operation'] }}</td>
                    <td>{{ $invoice['man'] }}</td>
                    <td>{{ $invoice['quantity'] }}</td>
                    <td>{{ $invoice['price'] }}</td>

                    <td>
                        {{-- فحص الصلاحية العام --}}
                        @can('System setting')
                            @if($invoice['type'] == 40)
                                <span style="color:green">-</span>
                            @elseif($invoice['type'] == 4)
                                <a style="background-color: #419BB2;height:30px" class="btn btn-success p-1"
                                   href="{{ url('/purchasesShow/' . $invoice['id']) }}">
                                    {{__('home.print')}}
                                    <i class="fas fa-print"></i>
                                </a>
                            @elseif($invoice['type'] == 1)
                                <a style="color: #23395D" class="dropdown-item" href="{{ url('showInvoiceRecent/' . $invoice['id']) }}">
                                    <i class="fas fa-print"></i> {{ __('home.show') }}
                                </a>
                            @elseif($invoice['type'] == 2)
                                <a style="background-color: #419BB2;height:30px" class="btn btn-success p-1"
                                   href="{{ url('/printreturnInvoice/' . $invoice['id']) }}">
                                    {{__('home.print')}}
                                    <i class="fas fa-print"></i>
                                </a>
                            @elseif($invoice['type'] == 3)
                                <a style="color: #23395D" class="dropdown-item" href="{{ url('print_Transfer_products/' . $invoice['id']) }}">
                                    <i class="fas fa-print"></i> {{ __('home.show') }}
                                </a>
                            @endif
                        @else
                            {{-- ما يظهر في حال عدم وجود صلاحية System setting --}}
                            <span class="text-muted">لا تملك صلاحية</span>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-danger">
        {{__('home.notfounddata')}}
    </div>
@endif
