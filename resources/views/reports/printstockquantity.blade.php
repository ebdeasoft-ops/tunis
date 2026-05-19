@extends('layouts.master')
@section('css')
<style>
    @media print {
        @page { 
        size: landscape;
    }
    body { 
        writing-mode: tb-rl;
    }   
    .double{
            border: 3px solid grey;
            border-radius: 5px;
            width:200px;

        }
    #print_Button {
            display: none;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
        /*border-style: solid;*/

    }
</style>
@endsection
@section('title')
{{__('home.print')}}
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
                   <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

                <div class="card-body">
            <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">
                     
                        
                        
  <div class="billed-from" style="width:33%;text-align: center;" >
                            <br>
                             <span style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p dir=ltr style="font-size:12px"> {{describtionen}} </p>
                            <span dir=ltr>{{STen}} </span>
                            <p dir=ltr> {{Taxen}} </p>

                        </div>
                   
                        <div class="row">
                        <?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>

                        </div>
        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                           <span style="font-size:17px">{{Namear}}</span>
                            <br>
                            <p> {{describtionar}}</p>
                            <p>{{STar}}</p>
                            <p>{{Taxar}}</p>

                        </div><!-- billed-from -->
                        
                    </div><!-- invoice-header -->
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-danger print-style float-left mt-3 mr-2 p-1" id="print_Button" onclick="printDiv()">
                            {{__('home.print')}}
                            <i class="mdi mdi-printer ml-1"></i>
                        </button>
                    </div>


                    @if(isset($products))
                    <div class="card-body">

                        <br>
                       <center><span class="double">{{__('report.stockquantity')}}</span> </center> 
                        <br>
                        <?php
                        $userId = 0;
                        $count = 0;
                        ?>
                        <?php
                        $userId = 0;
                        $startat = '';
                        $endat = '';
                        $totalprice = 0;
                        $totaladdedvalue = 0;
                        $i = 0;
                        $totalstock=0;
                        $opingstock=0;
                           $Totalsalescount=0;
                                $Totalreturnsalescount=0;
                                $Totalpurchasecount=0;
                                $Totalpurchasereturncount=0;
                                $totalincreasestock=0;
                                $totaldecreasestock=0;
                                $totaldamagestock=0;

                        ?>
                            <div class="table-responsive  ">
                        <table style="border:2px solid rgba(0,0,0,.3);width:100%" class="table  mb-0 table-striped invoice-table ">
                                <thead>
                                    <tr>
                                        <th >#</th>
                                        <th > {{__('home.productNo')}}</th>
                                        <th > {{__('home.productname')}}</th>
                                        <th > {{__('home.oping')}}</th>
                                        <th > {{__('home.purchases')}}</th>
                                        <th > {{__('home.purchase_return')}}</th>
                                        <th > {{__('home.sales')}}</th>
                                        <th > {{__('home.salesـreturned')}}</th>
                                        <th > {{__('home.productdecrease')}}</th>
                                        <th > {{__('home.productincrease')}}</th>
                                        <th > {{__('home.quentitydamagereport')}}</th>
                                        <th > {{__('home.stock')}}</th>
                                        <th > {{__('home.avarge')}}</th>  
                                        <th > {{__('home.total')}}</th>
                                    </tr>
                                </thead>
                                @foreach ($products as $product)
                                <?php
                                $totalprice += $product->purchasingـprice * $product->numberofpice;
                                $totalstock=$totalstock+$product->numberofpice;
                                $opingstock=$opingstock+$product->opening_blance;
                                ?>







                                <?php
                                $i++;
                                $salescount=0;
                                $returnsalescount=0;
                                $purchasecount=0;
                                $purchasereturncount=0;
                                $stockincrease=0;
                                $stockdecrease=0;
                                $damageproduct=0;
                             
                                $sales=App\Models\sales::where('product_id',$product->id)->where('save',1)->get();
                                $stock_update=App\Models\stock_update::where('product_id',$product->id)->whereDate('created_at', '>=',date("Y") ."-1"."-1")->get();
                                $ProductsDamage=App\Models\ProductsDamage::where('product_id',$product->id)->whereDate('created_at', '>=', date("Y") ."-1"."-1")->get();
                                $returnsales=App\Models\return_sales::where('product_id',$product->id)->get();
                                $purchase=App\Models\orderDetails::where('product_id',$product->id)->where('save',1)->get();
                              

                                foreach($stock_update as $productchange){
                                    $stockincrease=$stockincrease+$productchange->productincrease;
                                    $totalincreasestock=$totalincreasestock+$productchange->productincrease;
                                    $stockdecrease=$stockdecrease+$productchange->productdecrease;
                                    $totaldecreasestock=$totaldecreasestock+$productchange->productdecrease;
                                }
                                foreach($ProductsDamage as $productdamge){
                                    $damageproduct=$damageproduct+$productdamge->damage_quantity;
                                    $totaldamagestock=$totaldamagestock+$productdamge->damage_quantity;
                                }

                                foreach($sales as $sale){
                                    $salescount=$salescount+$sale->quantity;
                                    $Totalsalescount=$Totalsalescount+$sale->quantity;
                                }
                             
                                 
                                 foreach($returnsales as $sale){
                                    $returnsalescount=$returnsalescount+$sale->return_quantity;
                                    $Totalreturnsalescount=$Totalreturnsalescount+$sale->return_quantity;
                                }
                                
                                   
                                 foreach($purchase as $sale){
                                    $purchasecount=$purchasecount+$sale->numberofpice;
                                    $purchasereturncount=$purchasereturncount+$sale->returns_purchase;
                                    $Totalpurchasecount=$Totalpurchasecount+$sale->numberofpice;
                                    $Totalpurchasereturncount=$Totalpurchasereturncount+$sale->returns_purchase;
                              
                                    }
                                $date = explode(" ", $product->created_at);

                                ?>


                                <tbody>
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td dir=ltr>{{$product->Product_Code}}</td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$product->opening_blance}}</td>
                                        <td>{{$purchasecount}}</td>
                                        <td>{{$purchasereturncount}}</td>
                                         <td>{{$salescount}}</td>
                                        <td>{{$returnsalescount}}</td>
                                        <td>{{$stockdecrease}} </td>
                                        <td>{{$stockincrease}} </td>
                                        <td>{{$damageproduct}}</td>
                                        <td>{{$product->numberofpice}} </td>
                                        <td>{{$product->purchasingـprice}} </td>
                                        <td>{{$product->numberofpice*$product->purchasingـprice}}</td>
                                    </tr>

                               

                                @endforeach
                                   <tr>
                                        <td>-</td>
                                        <td dir=ltr>-</td>
                                        <td>-</td>
                                        <td>{{$opingstock}}</td>
                                        <td>{{$Totalpurchasecount}}</td>
                                        <td>{{$Totalpurchasereturncount}}</td>
                                         <td>{{$Totalsalescount}}</td>
                                        <td>{{$Totalreturnsalescount}}</td>
                                        <td>-</td>
                                        <td>{{$totaldecreasestock}}</td>
                                        <td>{{ $totalincreasestock}}</td>
                                        <td>{{$totaldamagestock}}</td>
                                        <td>{{$totalstock}}</td>
                                        <td>{{$totalprice}}</td>
                                    </tr>

                                 </tbody>
                            </table>






                            <br>
                            @endif
                        </div>


                        <div class="tabl my-1">
                            <table style=" width:50% " class="table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>{{__('report.totalprice')}}</th>
                                        <th>{{ __('home.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{__('home.valuewithouttax')}}</td>
                                        <td>{{(($totalprice) )}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <hr class="mg-b-40">



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