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
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
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
                    <div class="card-body">
                    <?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

                        <div class="row mg-t-12">
                        <br>
                        <br>
                        <br><!-- billed-from -->
                        </div><!-- invoice-header -->
                        <div class="row mg-t-12">
                            <div class="col-md">
                            <div class="col-md">
                                <h5 class="tx-gray-600">معلومات الفاتورة</h5>
                                <p class="invoice-info-row"><span>رقم الفاتورة</span>
                                    <span>67</span></p>
                                <p class="invoice-info-row"><span>تاريخ الاصدار</span>
                                    <span>{{$data['date']}}</span></p>
                                <p class="invoice-info-row"><span>اسم العميل  </span>
                                    <span>{{$data['clientname']}}</span></p>
                                    <p class="invoice-info-row"><span>العنوان</span>
                                    <span>{{$data['clientaddress']}}</span></p> 
                                     <p class="invoice-info-row"><span>رقم الجوال </span>
                                    <span>{{$data['clientphone']}}</span></p>
                                  
                                    <p class="invoice-info-row"><span> الملاحظات </span>
                                    <span>  {{$data['clientnote']}}</span></p>
                            </div>
                            </div>
                           
                        </div>
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th class="wd-20p">#</th>
                                        <th class="wd-40p">{{__('home.product')}}</th>
                                        @if($data['print']=='printprice')
                                        <th class="tx-center"> {{ $product->Unit_Price}} </th>
                                       @endif
                                       @if($data['print']=='print quentity')
                                        <th class="tx-center"> الكمية المتاحة</th>
                                       @endif
                                       

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($data['product'] as $product)

                                    <tr>
                                        <td >{{$product->id}}</td>
                                        <td class="tx-12">{{$product->product_name}}</td>
                                        @if($data['print']=='printprice')
                                        <td class="tx-center">{{ $product->sale_price}}</td>
                                       @endif
                                       @if($data['print']=='print quentity')
                                       <td class="tx-center">{{ $product->numberofpice}}</td>
                                       @endif
                                       
                                    </tr>
                                    @endforeach

                              
                                   
                                </tbody>
                            </table>
                        </div>
                        <hr class="mg-b-40">



                        <button class="btn btn-danger  float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i
                                class="mdi mdi-printer ml-1"></i>طباعة</button>
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
