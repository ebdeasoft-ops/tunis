@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_Button {
            display: none;
        }
         .text{
  width: 320px;
  overflow: hidden;
    white-space: pre-wrap;
  text-overflow: ellipsis
        }
        .double{
            border: 3px solid grey;
            border-radius: 5px;
            width:200px;

        }
    }

 .text{
  width: 320px;
  overflow: hidden;
    white-space: pre-wrap;
  text-overflow: ellipsis
        }
        .double{
            border: 3px solid grey;
            border-radius: 5px;
            width:200px;

        }
 
</style>
@endsection
@section('title')
{{ __('home.print') }}
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
                             <span style="font-size:17px">{{Nameen}}</span>
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

                           <span style="font-size:17px">{{Namear}}</span>
                            <br>
                            <p> {{describtionar}}</p>
                            <p>{{STar}}</p>
                            <p>{{Taxar}}</p>

                        </div><!-- billed-from -->
                    </div><!-- invoice-header -->
                    <br>
                    <center><span class="double">حركة المنتج
                -
                    Product Transactions
                    </span></center>
                    <br>
                    <div class="col-lg-3" id="start_at">
                        <center>
                        <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                        <?php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                        ?>
                        <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>
</center>
                    </div>
                    <div style="border-radius: 10px" class="card m-3 p-3">
                        <div class="table-responsive">
                            <span style="font-size: 17px;color:#419BB2" >{{ __('report.from') }} :
                                {{ $data['start_at'] }} </span>
                            &nbsp;&nbsp;
                            <span style="font-size: 17px;color:#419BB2" >{{ __('report.to') }} :
                                {{ $data['end_at'] }} </span>


                            <br>

                      <div class=" mg-t-40" >
                    <table style="border:2px solid rgba(0,0,0,.3);" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
                                 
    
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">{{ __('report.invoiceNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                                <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                <th class="border-bottom-0">{{ __('home.oping') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.quantity') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.total') }}</th>
                                                <th class="border-bottom-0">{{ __('home.operationtype') }}</th>

                 
    
    
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                           ?>
                                            @foreach ($data['products'] as $invoice)
                                                <?php $i++;
                                                $productid=$invoice['Product_Code'];
               

                                                ?>
                                                <tr>
                                                    <td>{{ $invoice['id'] }} </td>
                                                    <td dir='ltr'>{{ $invoice['Product_Code'] }} </td>
                                                    <td class="text">{{ $invoice['product_name'] }}</td>
                                                    <td>{{ $invoice['created_at']}}</td>
                                                    @if($invoice['type']==1  )
                                                    <td style="color:green">{{ $invoice['current_balance']+$invoice['quantity']  }}</td>
                                                    @endif
                                                     @if($invoice['type']==2 )
                                                    <td style="color:green">{{ $invoice['current_balance']-$invoice['quantity']  }}</td>
                                                    @endif
                                                     @if($invoice['type']==3 )
                                                    <td style="color:green">{{ $invoice['current_balance']-$invoice['quantity']  }}</td>
                                                    @endif
                                                     @if($invoice['type']== 4 )
                                                    <td style="color:green">{{ $invoice['current_balance']+$invoice['quantity']  }}</td>
                                                    @endif
                                                        @if($invoice['type']== 5 )
                                                    <td style="color:green">{{ $invoice['current_balance']-$invoice['quantity']  }}</td>
                                                    @endif
                                                        @if($invoice['type']== 6 )
                                                    <td style="color:green">{{ $invoice['current_balance']+$invoice['quantity']  }}</td>
                                                    @endif
                                                                                                            @if($invoice['type']== 8 )
                                                    <td style="color:green">{{ $invoice['current_balance']+$invoice['quantity']  }}</td>
                                                    @endif
                                                                                                            @if($invoice['type']== 9 )
                                                    <td style="color:green">-</td>
                                                    @endif
                                                    <td>{{ $invoice['quantity'] }}</td>
                                                    <td>{{ ($invoice['quantity']*$invoice['price'])-$invoice['discount'] }}</td>
                                                    @if($invoice['type']==2 ||$invoice['type']==3 ||$invoice['type']==5 )
                                                    <td style="color:green">{{ $invoice['operation'] }}</td>
                                                    @else
                                                    <td style="color:red">{{ $invoice['operation'] }}</td>
                                                    @endif

                                                </tr>
                                            @endforeach
    
                                        </tbody>
                                    </table>
                                </div>
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