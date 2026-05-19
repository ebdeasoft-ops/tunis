@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;
@endphp
<div class="table-responsive">
    <table class="table text-md-nowrap  text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
        <col style="width:2%">
        <col style="width:10%">
        <col style="width:31%">
        <col style="width:10%">
        <col style="width:7%">
        <col style="width:9%">
        <col style="width:6%">
        <col style="width:20%">

        <thead>
            <tr>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.sallerName') }} </th>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.clietName') }}</th>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.operations') }}</th>


            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>

            @foreach ($data as $product)
            <?php $i++; ?>

            <tr id="<?php echo $product['id']; ?>">
                <td data-target="id">{{ $product->id }}</td>
                <td data-target="id">{{ $product->user->name??'' }}</td>
                <td dir="ltr" data-target="id">
                    {{ $product->customer->name??'' }}
                </td>
                <td data-target="numberofpice">{{ $product->created_at }}</td>
                <td data-target="numberofpice">{{ $product->branch->name }}
                </td>
                <td data-target="numberofpice">
                    <?php
                    $avt = App\Models\Avt::find(1);
                    $saleavt = $avt->AVT;

                    ?>
                    {{ round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)==0?__('home.return'):round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)  }}
                </td>

                <?php
                $pay = '';
                if ($product->Pay == 'Cash') {
                    $pay = __('report.cash');
                } elseif ($product->Pay == 'Shabka') {
                    $pay = __('report.shabka');
                } elseif ($product->Pay == "Credit") {
                    $pay = __('report.credit');
                } elseif ($product->Pay == "Bank_transfer") {
                    $pay = __('home.Bank_transfer');
                } else {
                    $pay = __('home.Partition of the amount');
                }

                ?>
                <td>{{$pay}}

                    @if($product->Pay =="Partition")
                    <br>
                    {{__('report.cash')}} : {{round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)==0?__('home.return'):round(($product->Price-$product->discount) + (($product->Price-$product->discount)*$saleavt),2)-$product->bankamount-$product->Bank_transfer }}
                    {{__('report.shabka')}} : {{$product->bankamount}}
                    {{__('home.Bank_transfer')}} : {{$product->Bank_transfer}}
                    @endif

                </td>
                
                <td>
                     <a style="color: #23395D" class="dropdown-item" href="showInvoiceRecent/{{ $product->id }}"><i style="fill:#072c3c !important" class=" fas fa-print"></i>&nbsp;&nbsp;
                        {{ __('home.show') }}
                    </a>
               

 
     @if($product->sent_to_zatca_status=='PASS')
                        <a style="background-color: grey;" class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"   href="dwonloadxml/{{ $product->id }}" target="_blank" >{{ __('home.zatcapass') }}&nbsp;<i class="fa-solid fa-download"></i></i></a>

                        @else
                        <a style="background-color: green;" id='sendnowzatca' class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" data-id="{{ $product->id }}" data-toggle="modal" href="#uploadzatca" title="تعديل بيانات العميل ">{{ __('home.uploadzatca') }}&nbsp;<i class="fa-regular fa-paper-plane"></i></a>


                        @endif

                </td>

            </tr>
            @endforeach
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