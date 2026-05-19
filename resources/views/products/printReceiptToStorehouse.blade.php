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
معاينه طباعة الفاتورة
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
                    <div class="row mg-t-12">
                        <br>
                        <br><!-- billed-from -->
                    </div><!-- invoice-header -->
                    <div class="row mg-t-12">


                    </div>
                    <div class="table-responsive mg-t-40">
                        <table class="table text-md-nowrap mb-0 invoice-table table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="wd-20p">#</th>
                                    <th class="wd-40p">اسم العميل </th>
                                    <th class="tx-center"> رقم العميل </th>
                                    <th class="tx-center"> تاريخ </th>
                                    <th class="tx-center"> رقم الفاتورة </th>




                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>1</td>
                                    <td class="tx-12">{{$data['invoiceData']->customer->name}}</td>
                                    <td class="tx-12">{{$data['invoiceData']->customer->id}}</td>
                                    <td class="tx-center">{{ $data['invoiceData']->created_at}}</td>
                                    <td class="tx-center">{{ $data['invoiceData']->id}}</td>

                                </tr>



                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mg-t-40">
                        <table class="table text-md-nowrap mb-0 invoice-table table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">NO </th>
                                    <th class="border-bottom-0">{{__('home.productNo')}} </th>
                                    <th class="border-bottom-0">{{__('home.product')}}</th>
                                    <th class="tx-center"> {{__('home.productprice')}} </th>

                                    <th class="border-bottom-0">{{__('home.quantity')}}</th>
                                    <th class="border-bottom-0">{{__('home.price')}}</th>
                                    <th class="border-bottom-0">{{__('home.discount')}}</th>
                                    <th class="border-bottom-0">{{__('home.total')}}</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                ?>

                                @foreach ($data['salesData'] as $product)
                                <?php $i++ ?>

                                <tr>
                                    <td>{{ $i }}</td>
                                    <td dir=ltr>{{$product->productData->Product_Code}}</td>
                                    <td>{{$product->productData->product_name}}</td>
                                    <td>{{$product->Unit_Price}}</td>

                                    <td>{{$product->quantity}}</td>
                                    <td>{{$product->Unit_Price*$product->quantity}}</td>
                                    <td>{{$product->Discount_Value}}</td>
                                    <td>{{($product->Unit_Price*$product->quantity)-$product->Discount_Value}}</td>

                                </tr>
                                @endforeach

                                <tr>

                            </tbody>
                        </table>
                    </div>
                    <br>

                    <div class="table-responsive mg-t-40 table-padding">

                        <table class="table invoice-table table-striped text-center">

                            <body>
                                <tr>
                                    <td class="tx-">المجموع</td>
                                    <td class="tx-">{{$data['invoicetotal_price']}}</td>
                                </tr>
                                <tr>
                                    <td class="tx-">الضريبة</td>
                                    <td class="tx-">{{$data['invoicetotal_addedvalue']}}</td>
                                </tr>
                                <tr>
                                    <td class="tx-">الخصم</td>
                                    <td class="tx-">{{$data['invoicetotal_discount']}}</td>
                                </tr>

                                <tr>
                                    <td class="tx-">المبلغ</td>
                                    <td class="tx-">{{$data['invoicetotal_addedvalue']+$data['invoicetotal_price']}}</td>
                                </tr>
                            </body>

                        </table>

                    </div>

                    <hr class="mg-b-40">



                    <button class="btn btn-danger  float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i class="mdi mdi-printer ml-1"></i>طباعة لاستلام من المخزن</button>
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