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
{{__('home.print')}}
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
  <span class="text-muted mt-1 tx-13 mr-2 mb-0">
                    معاينة طباعة الفاتورة</span>
            </div>
        </div>

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
     <span style="font-size:25px">{{Nameen}}</span>
    <br>
    <p dir=ltr> {{describtionen}} </p>
    <span dir=ltr>{{STen}} </span>
    <p dir=ltr> {{Taxen}} </p>

</div>
<div class="row">
<?php
$logo=camplogo;
?>
<a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

</div>


<div class="billed-from" style="width:33%;text-align: center;">
    <br>

   <span style="font-size:25px">{{Namear}}</span>
    <br>
    <p> {{describtionar}}</p>
    <p>{{STar}}</p>
    <p>{{Taxar}}</p>

</div><!-- billed-from -->
</div><!-- invoice-header -->
                        <div class="row mg-t-12">
                        <br>

                        <br><!-- invoice-header -->
                        <div class="row mg-t-12">
                         
                           <span>{{__('report.Rosaryـdetails')}}</</span>
                        </div>
                      

                        <div class="card-body">
                <div class="table-responsive">
                @if(isset($Invoices))
<?php
$userId=0;
$count=0;
?>
<?php
$userId=0;
$startat='';
$endat='';
$totalprice=0;
$totaladdedvalue=0;
?>
@foreach ($Invoices as $invoice)
<?php 
                                    $totaladdedvalue+=$invoice->Added_Value;
                                    $totalprice+=$invoice->Price;
                                    if($count==0){
                                        $userId=$invoice->user_id;
                                        $startat=$invoice->created_at;
                                    }
                                    $endat=$invoice->created_at;
                                    $count++;

                                     ?>
                                     
<br>



            <span class="text-danger">{{__('report.invoiceNo')}}   :  {{$invoice->id}}       </span>  
            <br> 
            <span class="text-danger">{{__('users.branch')}}   :  {{$invoice->branch->name}}       </span>  
            <br>         
           <span class="text-danger" >{{__('home.paymentmethod')}}   :   

    @if ($invoice->Pay == 'Cash')
        <span class="text-success">{{__('report.cash') }}</span>
    @elseif($invoice->Pay == 'Credit')
        <span class="text-danger">{{__('report.credit') }}</span>
    @else
        <span class="text-warning">{{__('report.shabka') }}</span>
    @endif
    <br>         

</span>
   
    <table class="table table-sm">
  <thead>
        <tr>
        <th class="border-bottom-0">#</th>
        <th class="border-bottom-0">{{__('report.date')}}</th>

                                <th class="border-bottom-0"> {{__('home.productNo')}}</th>
                                <th class="border-bottom-0"> {{__('home.product')}}</th>
                                    <th class="border-bottom-0"> {{__('home.quantity')}}</th>

                                    <th class="border-bottom-0">{{__('home.price')}}</th>
                                    <th class="border-bottom-0"> {{__('home.addedValue')}}</th>
                                    <th class="border-bottom-0"> {{__('home.total')}}</th>
        </tr>
    </thead>
    <?php
    $i=0;
?>
    @foreach(App\Models\sales::where('invoice_id',$invoice->id)->get() as $product)
    <?php
    $i++;
    $date= explode(" ",$product->created_at);
?>
    <tbody>
        <tr>
        <td>{{$i}}</td>
        <td>{{$date[0]}}</td>

        <td dir='ltr'>{{$product->productData->Product_Code}}</td>
            <td>{{$product->productData->product_name}}</td>
            <td>{{$product->quantity}}</td>
      

            <td>{{$product->Unit_Price}}</td>
            <td>{{$product->Added_Value}}</td>
            <td>{{($product->quantity*$product->Added_Value)+($product->quantity*$product->Unit_Price)}}</td>
        </tr>
       
    </tbody>
    
@endforeach
</table>

          <span class="text-warning  float-left mt-3 mr-2" id="print_Button" >{{__('home.total')}} : {{($invoice->Added_Value+$invoice->Price)}}</span>
            
          <br>
          <br>
@endforeach
<br>
-----------------------------------------------------  {{__('report.totalprice')}} ---------------------------
<br>

          <span class="text-success">{{__('report.totalpricewithoudtax')}} :  {{ $totalprice}}  <br> <br> {{__('report.totaltax')}} : {{$totaladdedvalue}}  <br><br> </span>
          <span class="text-warning" >{{__('report.totalallprice')}} : {{($totaladdedvalue+ $totalprice)}}</span>
            
          <br>
          ----------------------------------------------------------------------------------------------------



                        <br>

<br>
                    @endif
                </div>
                        <hr class="mg-b-40">



                                </div>
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
