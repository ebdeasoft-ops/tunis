@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
        border-style: solid;

    }
</style>
@endsection
@section('title')
معاينه طباعة المنتجات
@stop
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class=" main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">
 <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">

                      
                        
                        
                             <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                           <span style="font-size:16px">{{Namear}}</span>
                            <br>
                            <p> {{describtionar}}</p>
                            <p>{{STar}}</p>
                            <p>{{Taxar}}</p>

                        </div><!-- billed-from -->
                        <div class="row">
                        <?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>

                        </div>

  <div class="billed-from" style="width:33%;text-align: center;" >
                            <br>
                             <span style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p dir=ltr> {{describtionen}} </p>
                            <span dir=ltr>{{STen}} </span>
                            <p dir=ltr> {{Taxen}} </p>

                        </div>
                   
                    </div><!-- invoice-header -->
                    <div class="card-body">

                    <a style="font-size: 10px" class="invoice-title p-2 mb-5">
                            {{__('home.purchase_return')}}
                        </a><br>
                        <div style="padding: 0 0 0 40%" class="table-responsive mg-t-30 mb-3">
                            <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center" id="tableTotalPrice" name="tableTotalPrice" width="50%">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">{{__('home.suppliername')}}</th>
                                        <th class="border-bottom-0">{{$data['supllier']->supllier->comp_name??''}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td> {{__('home.phone')}}</td>
                                        <td>{{$data['supllier']->supllier->phone??''}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('home.Location')}}</td>
                                        <td>{{$data['supllier']->supllier->location??''}}</td>
                                    </tr>
                                    <tr>
                                        <td> {{ __('home.paymentmethod') }}</td>
                                        <?php

                                        $pay = '';
                                        if ($data['supllier']->Limit_credit == "Cash") {
                                            $pay = __('report.cash');
                                        } elseif ($data['supllier']->Limit_credit == "Shabka") {
                                            $pay = __('report.shabka');
                                        } elseif ($data['supllier']->Limit_credit == "Bank_transfer") {
                                            $pay = __('home.Bank_transfer');
                                        } else {
                                            $pay = __('report.credit');
                                        }
                                        ?>
                                        <td>{{$pay}}</td>
                                    </tr>

                                    <tr>
                                        <td>{{__('home.purchasedate')}}</td>
                                        <td>{{$data['supllier']->created_at??''}}</td>
                                    </tr>
                                </tbody>

                            </table>

                            </form>
                            <br>
                        </div>




                        <div class="table-responsive">
                            <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center" name='prodyctsavaliable'>
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0"># </th>
                                        <th class="border-bottom-0">{{__('home.productNo')}}</th>
                                        <th class="border-bottom-0">{{__('home.product')}}</th>
                                        <th class="border-bottom-0">{{__('users.branch')}}</th>

                                        <th class="border-bottom-0">{{__('home.purchase')}} </th>
                                        <th class="border-bottom-0">{{__('home.addedValue')}}</th>
                                        <th class="border-bottom-0">{{__('home.RETURNSPURCHAE')}} </th>

                                        <th class="border-bottom-0">{{__('home.total')}} </th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $totalprice = 0;
                                    $totalAddedvalue = 0;
                                    $orderId = 0; ?>
                                    @foreach ($data['product'] as $product)
                                    <?php $i++;
                                    $orderId = $product->order_owner;
                                    $totalprice += $product->purchasingـprice * $product->returns_purchase;
                                    $totalAddedvalue += $product->Added_Value * $product->returns_purchase ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td dir=ltr>{{$product->productData->Product_Code}}</td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$data['branch']}}</td>
                                        <td>{{$product->purchasingـprice}}</td>
                                        <td>{{$product->Added_Value}}</td>
                                        <td>{{$product->returns_purchase}}</td>
                                        <td>{{($product->returns_purchase*$product->Added_Value)+($product->returns_purchase*$product->purchasingـprice)}}</td>

                                    <tr>
                                        @endforeach
                                </tbody>
                            </table>
                            <div class="table-responsive mg-t-30 table-padding">
                                <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center" id="tableTotalPrice" name="tableTotalPrice" width="50%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <?php
                                    $x = App\Models\orderDetails::where('order_owner', $orderId)->where('numberofpice', '!=', 0)->get();
                                    $count = count($x);
                                    ?>
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                             @if(!$count)
                                            <th class="border-bottom-0">{{ __('home.discount') }}</th>
                                            @endif
                                            <th class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                           
                                            <th class="border-bottom-0">{{ __('home.total') }} </th>

                                        </tr>
                                    </thead>

                                    <body>
                                        <tr>
                                            <td> {{$totalprice }}</td>
                                                <?php
                                            $total = 0;
                                            $total = $totalAddedvalue + $totalprice;
                                            $avtSaleRate = App\Models\Avt::find(2);
$avtSaleRate = $avtSaleRate->AVT;
                                            ?>
                                                 @if(!$count)
                                            <?php
                                            $totalAddedvalue=($totalprice - $data['resource_purchases']->discount)*$avtSaleRate;
                                            $total = $totalAddedvalue + $totalprice - $data['resource_purchases']->discount;
                                            ?>
                                            <td>{{$data['resource_purchases']->discount}}</td>
                                            @endif
                                            <td>{{$totalAddedvalue}}</td>
                                        
                                       
                                            <td>{{$total}}</td>
                                        </tr>

                                    </body>

                                </table>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                        طباعة
                                        <i class="mdi mdi-printer ml-1"></i>
                                    </button>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <!-- <button type="submit" class="btn btn-danger"> استرجاع  </button> -->
                                </div>
                                </form>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>

                <br />


            </div>



        </div>
    </div>
</div>
</div><!-- COL-END -->
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>


<script type="text/javascript">
    function printDiv() {
        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>

@endsection