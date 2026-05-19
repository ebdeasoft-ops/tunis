@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;
@endphp
<div class="table-responsive">
    <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">


        <thead>
            <tr>
                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.buyer name') }} </th>
                                                                            <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.supplierinvoicenumber') }}</th>

                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.suppliername') }}</th>
                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.date') }}</th>
                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.branch') }}</th>
                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.total') }}</th>
                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.operations') }}</th>

            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>

            @foreach ($data as $product)
            <?php $i++; ?>

            <tr id="<?php echo $product['id']; ?>">
                <td data-target="id">{{ $product->orderId }}</td>
                <?php
                $buyer = App\Models\orderTosupllier::find($product->orderId);
                ?>
                <td data-target="id">{{ $buyer->user->name ??"-"}}</td>
                                                                                <td data-target="numberofpice">{{ $product->Purchase_invoice_number }}

                <td dir="ltr" data-target="id">
                    {{ $product->supllier->name }}
                </td>
                <td data-target="numberofpice">{{ $product->created_at }}
                </td>
                <td data-target="numberofpice">{{ $product->branch->name }}
                </td>
                <td><span style="color:red">{{ $product->recoveredـpieces!=0?__('home.return'):$product->In_debt-$product->discount }}</span></td>

                <?php
                $pay = '';
                if ($product->Pay_Method_Name == 'Cash') {
                    $pay = __('report.cash');
                } elseif ($product->Pay_Method_Name == 'Shabka') {
                    $pay = __('report.shabka');
                } elseif ($product->Pay_Method_Name == 'Bank_transfer') {
                    $pay = __('home.Bank_transfer');
                } else {
                    $pay = __('report.credit');
                }

                ?>
                <td data-target="numberofpice">{{ $pay }}</td>
                <td> <a class="dropdown-item" href="purchasesShow/{{ $product->orderId }}"><i style="fill:#072c3c !important" class="fas fa-print"></i>&nbsp;&nbsp;
                        {{ __('home.show') }}
                    </a>
                    <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" data-id="{{ $product->orderId }}" data-totalinvoice="{{  round(($product->In_debt) - ($product->discount),2)==0?__('home.return'):round(($product->In_debt) - ($product->discount),2)}}" data-toggle="modal" href="#paymentmethod" title="تعديل طريقة الدفع">{{ __('home.updatepayment') }}<i class="las la-pen"></i></a>

       
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <br>
        <div class="justify-content-start" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>



        @else
        <div class="alert alert-danger">
            {{__('home.notfounddata')}}
        </div>
        @endif