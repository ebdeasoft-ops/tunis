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
              
                        <div class="card-body">

<h3>                                {{__('home.send_product_from_brance')}}
</h3>
                            <div style="padding: 0 0 0 40%" class="table-responsive mg-t-30 mb-3">
                                <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center" id="tableTotalPrice"
                                    name="tableTotalPrice"width="50%">
                                    
                                    
                                    
    
                                    <tbody>
                                    <tr>
                                            <td> {{__('home.Invoice_no')}}</td>
                                            <td>{{$data['invoice']->id??''}}</td>
                                        </tr>
                                    <tr>
                                            <th class="border-bottom-0">{{__('home.branch_sender')}}</th>
                                            <th class="border-bottom-0">{{$data['invoice']->branchfrom->name??''}}</th>
                                        </tr>
                                        <tr>
                                            <td> {{__('home.employeesender')}}</td>
                                            <td>{{$data['invoice']->userfrom->name??''}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('home.branch_reciver')}}</td>
                                            <td>{{$data['invoice']->branchto->name??''}}</td>
                                        </tr>
                                        <tr>
                                            <td>     {{ __('home.employeereciver') }}</td>
     
                                            <td>{{$data['invoice']->userto->name??''}}</td>
                                        </tr>
                                   
                                     
                                        <tr>
                                            <td>{{__('home.date')}}</td>
                                            <td>{{$data['invoice']->created_at??''}}</td>
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
                                    <th> # </th>
                                    <th>{{ __('home.productNo') }} </th>
                                    <th>{{ __('home.product') }}</th>
                                    <th>{{ __('home.quantity') }}</th>
                                    <th>{{ __('home.thecostProduct') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            $totalprice = 0;
                            $totalAddedvalue = 0; ?>
                            @foreach ($data['itemsdetails'] as $product)
                            <?php $i++;
                            $totalprice += $product->cost_per_each_withoud_tax*$product->quantity;
                            $avtSaleRate = App\Models\Avt::find(2);
                            $avtSaleRate = $avtSaleRate->AVT;
                            $totalAddedvalue+=( $product->cost_per_each_withoud_tax*$product->quantity)* $avtSaleRate ;
                             ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td dir=ltr>{{$product->product->Product_Code}}</td>
                                <td>{{$product->product->product_name}}</td>
                                <td>{{$product->quantity}}</td>
                                <td>{{$product->cost_per_each_withoud_tax}}</td>
                                <td>{{$product->cost_per_each_withoud_tax*$product->quantity}}</td>

                            <tr>
                                @endforeach
                        </tbody>
                    </table>
                    <div class="table-responsive mg-t-30 table-padding">
                        <table class="table table-invoice border text-md-nowrap mb-0 table-bordered table-striped text-center" id="tableTotalPrice" name="tableTotalPrice" width="50%">
                            <col style="width:15%">
                            <col style="width:15%">
                            <col style="width:15%">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __('home.the amount') }}</th>
                                    <th class="border-bottom-0">{{ __('home.addedValue') }}</th>
                                    <th class="border-bottom-0">{{ __('home.total') }} </th>

                                </tr>
                            </thead>

                            <body>
                                <tr>
                                    <td> {{$totalprice }}</td>
                                    <td>{{$totalAddedvalue}}</td>
                                    <td>{{$totalAddedvalue+ $totalprice}}</td>
                                </tr>

                            </body>

                        </table>
                        <br>
                        <br>
                        <br>
                        <br>
                        <p>{{__('home.employeereciver')}} : {{$data['invoice']->userto->name??''}}</p>
                      
                        <p>{{__('home.thesignature')}} : </p>                        <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{__('home.print')}}
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
