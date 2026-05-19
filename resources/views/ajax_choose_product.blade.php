@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;

@endphp
<div  class="table-responsive">
    <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
        <col style="width:5%">
        <col style="width:14%">
        <col style="width:28%">
        <col style="width:10%">
        <col style="width:10%">
        <col style="width:13%">
        <col style="width:10%">
        <col style="width:10%">
        <col style="width:10%">

        <thead>
            <tr>
                <th style="font-size: 15px" class="border-bottom-0">#</th>
                <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.photo')}}</th>

                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.productlocation')}}</th>
                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.suppliername')}}</th>

                <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                                    @can('System setting')

                <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                                                    @endcan

                <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>
                  <th style="font-size: 15px" class="border-bottom-0">{{__('home.refnumber')}}</th>
                <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



            </tr>
        </thead>
        <tbody class="">
            <?php $i = 0;
           ?>

            @foreach ($data as $product)
            <?php $i++ ?>
  <?php
                $orderproduct = App\Models\orderDetails::orderby('created_at', 'desc')->where('product_id', $product->id)->first();
                if($orderproduct!=null){
                    $supplierDat = App\Models\orderTosupllier::find($orderproduct->order_owner);

                }



                ?>
            <tr id="<?php echo $product['id']; ?>">
                <td id="tableData" dir=ltr>{{ $product->id }}</td>
                <td id="tableData" dir=ltr>{{ $product->Product_Code }}</td>
                <td id="tableData" data-target="product_name">{{ $product->product_name }}</td>
                            <td> <img style="width:80px;height:80px;" class="custom_img" src="{{ asset('assets/admin/uploads').'/'.$product->photo }}" alt="لوجو الشركة">

                <td id="tableData" data-target="product_name">{{ $product->branch->name }}</td>
                <td id="tableData" data-target="numberofpice">{{ $product->Product_Location }}</td>
                                <td id="tableData" data-target="numberofpice">{{ $orderproduct!=null?$supplierDat->supllier->name:'-' }}</td>

                <td id="tableData" data-target="numberofpice">{{ $product->numberofpice }}</td>
                                                    @can('System setting')

                <td id="tableData" data-target="numberofpice">{{ $product->purchasingـprice }}</td>
                                                    @endcan

                <td id="tableData" data-target="numberofpice">{{ $product->sale_price }}</td>
                     <td id="tableData"  data-target="numberofpice">{{ $product->refnumber==null?__('home.notdata'):str_replace("+"," - ",$product->refnumber) }}</td>
                <td id="tableData">

                    <button style="padding: 6px 12px;background:green;border-color:white" type="button" id="btn" name="btn"  data-dismiss="modal" onclick="chooseProduct('{{ $product->id }}','{{ $product->product_name }}','{{ $product->purchasingـprice }}','{{ $product->sale_price }}','{{ $product->Product_Code }}','{{ $product->Product_Location }}')">{{ __('home.Add') }}</button>

                  </td>                                                      
                                                                        


            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <br>
             <br>
  <div class="justify-content-start" id="ajax_pagination_in_search">
            {{ $data->links() }}
        </div>

         


        @else
        <div class="alert alert-danger">
        {{__('home.notfounddata')}}          </div>
        @endif