@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('report.stockquantity') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.stockquantity') }}

                </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'stockquantity')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            <?php
                            if ($branchdata != null) {
                                $data = explode("/", $branchdata);
                                $branchdata = $data[0];
                                $display = $data[1] ?? 1;
                                $quantity = $data[2];
                                $loction = $data[3]??'-';
                            }

                            ?>

                            @if ($branchdata != '-')
                            <?php
                            $branchlast = App\Models\branchs::find($branchdata);
                            ?>
                            @endif
                            <div class="col-lg-3" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control " name="branch" id="branch" required>
                                    <option style="font-size: 15px" value="{{ $branchdata == '-' ? '-' : $branchdata }}" selected>
                                        {{ $branchdata == '-' ? __('users.allbranchs') : $branchlast->name }}
                                    </option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                    <option style="font-size: 15px" value="{{ $branch->id }}"> {{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <?php
                                $avtSaleRate = App\Models\Avt::find(2);
                                ?>
                                <input type="text" class="form-control " id="avtValue" name="avtValue" value="{{ $avtSaleRate->AVT }}" hidden>


                            </div>
                            <div class="col-lg-3" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('home.choosequantitytodisplay') }} </p>
                                <select class="form-control " name="choosequantitytodisplay" id="choosequantitytodisplay" required>
                                    <?php
                                    $value='>=';
                                    $text='';
                                    if ($data[1] == '==') {
                                        $value='==';
                                    $text=__('home.morethen0');
                                    } elseif ($data[1] == '>=') {
                                        $value='>=';
                                        $text=__('home.morethen1');
                                    } else {
                                        $value='<=';
                                        $text=__('home.lessthen');
                                    }                                 ?>
                                    <option style="font-size: 15px" value="{{ $value}}" selected> {{ $text}}</option>
                                    <option style="font-size: 15px" value='=='> {{__('home.morethen0')}}</option>
                                    </option>
                                    <option style="font-size: 15px" value='<='> {{ __('home.lessthen') }}</option>

                                    <option style="font-size: 15px" value='>='> {{ __('home.morethen1') }}</option>


                                </select>

                            </div>
                            <div class="col-lg-3 mg-t-10 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.quantity') }} </label>
                                <input type="number" class="form-control parent-input" id="quantity" name="quantity" title="يرجي ادخال الكمية  " value="{{$quantity??1}}" required>
                            </div>
                            <div class="col-lg-3 mg-t-10 mg-lg-t-0">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.Location') }} </label>
                                <input  class="form-control parent-input" id="Location" name="Location" title="يرجي ادخال الكمية  " value="-" >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-center my-5">
                                    <button class="btn btn-success print-style p-1">
                                        {{ __('home.search') }}
                                        <i style=" height: 100;
                                                 
                                                 font-size:15px" class="las la-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                    </form>

                </div>
                @if (isset($products))
                <div class="card-body">
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

                    ?>
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">

                            <div class="modal-body">


                                <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                                    <label for="inputName" class="control-label parent-label">
                                        {{ __('home.searchaboutproduct') }} </label>
                                    <input dir=ltr type="text" class="form-control" id="searchaboutproduct" name="searchaboutproduct" placeholder="{{ __('home.Search By Name or Product Number') }}" onkeyup="searchaboutproductfunction()">
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <table class="table text-center our-table text-md-nowrap" id="SearchProductTable" width="100%">
                                        <col style="width:5%">
                                        <col style="width:15%">
                                        <col style="width:30%">
                                        <col style="width:10%">
                                        <col style="width:15%">
                                        <col style="width:10%">
                                        <col style="width:10%">

                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">#</th>
                                                <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.productname') }}</th>
                                                <th class="border-bottom-0"> {{__('home.productlocation')}}</th>

                                                <th class="border-bottom-0"> {{ __('home.saleprice') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.stock') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.totalwithTax') }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            $totalStockValue = 0;
                                            $data = 'm'; ?>

                                            @foreach ($products as $product)
                                            <?php
                                            $i++;
                                            $avtSaleRate = App\Models\Avt::find(2);
                                            $date = explode(' ', $product->created_at);

                                            ?>
                                            <tr id="<?php echo $product['id']; ?>">
                                                <td>{{ $i }}</td>
                                                <td dir='ltr'>{{ $product->Product_Code }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td class="border-bottom-0">{{$product->Product_Location}}</td>

                                                <td>{{ $product->purchasingـprice }}
                                                </td>
                                                <td>{{ $product->numberofpice }}</td>
                                                <td>{{ $product->purchasingـprice * $product->numberofpice }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div>

                                    </div>

                                    <div class="row d-flex justify-content-between pagination-row">

                                        <div class="rows-number">
                                            <label style="font-size: 12px;color:#419BB2;font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;{{ __('pagination.numberofpages') }}
                                                &nbsp;&nbsp; {{ $products->perPage() }} &nbsp;&nbsp;</label>
                                            <label style="font-size: 12px;color:#419BB2;font-weight:bold">{{ __('pagination.numberofproducts') }}
                                                &nbsp;&nbsp;{{ $products->total() }} </label>
                                        </div>

                                        <div class="buttonss-table row d-flex justify-content-center mb-2">
                                            <button id="previousPage" name="previousPage" class="btn btn-primary print-style"><span>
                                                    << &nbsp;</span>{{ __('pagination.previous') }}</button>
                                            <div class="col-lg-2 col-md-2 col-sm-2 mg-lg-t-0 prodNumbers"> <input class="form-control " name="currentpage" id="currentpage" readonly value="{{ $products->currentPage() }}" readonly>
                                            </div>
                                            <input class="form-control " name="previousPagevalue" id="previousPagevalue" readonly value="{{ $products->previousPageUrl() }}" hidden>
                                            <input class="form-control " name="nextPageValue" id="nextPageValue" readonly value="{{ $products->nextPageUrl() }}" hidden>
                                            <button id="nextPage" name="nextPage" class="btn btn-primary print-style">{{ __('pagination.next') }}
                                                >></button>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>
                    </div>
                    <br>
                    <br>


                    <div class="d-flex justify-content-center">

                        <a class="btn btn-success print-style p-1" href="{{ url('/' . ($page = 'printstockquantity') . '/' . $branchdata.'/'.$display.'/'.$quantity.'/'.$loction) }}">
                            {{ __('home.print') }}
                            <i class="mdi mdi-printer ml-1"></i>
                        </a>
                    </div>
                    <br>
                    <br>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')
<!-- Internal Data tables -->

<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>
<script>
    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        avt_purchases = $('#avtValue').val()


        $.ajax({
            url: " {{ URL::to('search_stockquantityPagination') }}" + "/" + searchtext + "/" + $('#branch')
                .val(),
            type: "GET",
            dataType: "json",
            success: function(data) {

                $('#previousPagevalue').val(data['prev_page_url'])
                $('#nextPageValue').val(data['next_page_url'])
                $('#currentpage').val(data['current_page'])
                let table = document.getElementById("SearchProductTable");
                var tableHeaderRowCount = 1;

                var rowCount = table.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    table.deleteRow(tableHeaderRowCount);
                }
                data['data'].forEach(async (product) => {
                    Product_id = product['id'],
                        Product_Code = product['Product_Code'],
                        id = product['id'],
                        product_name = product['product_name'],
                        purchasingـprice = product['purchasingـprice']
                    sale_price = product['sale_price']
                    numberofpice = product['numberofpice']
                    Product_Location = product['Product_Location']
                    button = '';





                    text =
                        '<button type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                    name = product_name.replaceAll(" ", "<");
                    button = text.concat("chooseProduct(", id, ",", "'", name, "'", ",",
                        purchasingـprice, ",", sale_price, ",", "'", Product_Location,
                        "'", ",", numberofpice, ")", ">{{ __('home.Add') }}</button>");




                    let row = table.insertRow(-1); // We are adding at the end

                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);

                    c1.innerText = Product_id

c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
c3.innerHTML = product_name
c4.innerText = Product_Location
c5.innerText =purchasingـprice 
c6.innerText = numberofpice
c7.innerText = (( purchasingـprice * numberofpice)).toFixed(2)







                });

            },
        });
    }
</script>
<script>
    $(document).ready(function() {



        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });


        $("#nextPage").click(function(e) {
            display = $('#choosequantitytodisplay').val();

            url = $('#nextPageValue').val().split('page=')[1];
            avt_purchases = $('#avtValue').val()
            console.log(avt_purchases)
            $.ajax({
                url: " {{ URL::to('stockquantityPagination') }}" + "/" + $('#branch').val() + "/" + display +"/"+$('#quantity').val()+ "?page=" + url,
                type: "GET",
                dataType: "json",
                success: function(data) {


                    $('#previousPagevalue').val(data['prev_page_url'])
                    $('#nextPageValue').val(data['next_page_url'])
                    $('#currentpage').val(data['current_page'])
                    let table = document.getElementById("SearchProductTable");
                    var tableHeaderRowCount = 1;

                    var rowCount = table.rows.length;

                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                        table.deleteRow(tableHeaderRowCount);
                    }
                    data['data'].forEach(async (product) => {
                        Product_id = product['id'],
                            Product_Code = product['Product_Code'],
                            id = product['id'],
                            product_name = product['product_name'],
                            purchasingـprice = product['purchasingـprice']
                        sale_price = product['sale_price']
                        numberofpice = product['numberofpice']
                        Product_Location = product['Product_Location']
                        button = '';







                        let row = table.insertRow(-
                            1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);

                        c1.innerText = Product_id

c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
c3.innerHTML = product_name
c4.innerText = Product_Location
c5.innerText =purchasingـprice 
c6.innerText = numberofpice
c7.innerText = (( purchasingـprice * numberofpice)).toFixed(2)







                    });

                },
            });
        });

        $("#previousPage").click(function(e) {
            display = $('#choosequantitytodisplay').val();

            url = $('#previousPagevalue').val().split('page=')[1];
            avt_purchases = $('#avtValue').val()

            if (url != '') {
                $.ajax({
                    url: " {{ URL::to('stockquantityPagination') }}" + "/" + $('#branch').val() + "/" + display +"/"+$('#quantity').val()+ "?page=" + url,

                    type: "GET",
                    dataType: "json",
                    success: function(data) {

                        $('#previousPagevalue').val(data['prev_page_url'])
                        $('#nextPageValue').val(data['next_page_url'])
                        $('#currentpage').val(data['current_page'])
                        let table = document.getElementById("SearchProductTable");
                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        data['data'].forEach(async (product) => {


                            Product_id = product['id'],
                                Product_Code = product['Product_Code'],
                                id = product['id'],
                                product_name = product['product_name'],
                                purchasingـprice = product['purchasingـprice']
                            sale_price = product['sale_price']
                            numberofpice = product['numberofpice']
                            Product_Location = product['Product_Location']
                            button = '';






                            let row = table.insertRow(-
                                1); // We are adding at the end
                            let c1 = row.insertCell(0);
                            let c2 = row.insertCell(1);
                            let c3 = row.insertCell(2);
                            let c4 = row.insertCell(3);
                            let c5 = row.insertCell(4);
                            let c6 = row.insertCell(5);
                            let c7 = row.insertCell(6);

                            c1.innerText = Product_id

c2.innerHTML = '<span dir=ltr>' + Product_Code + '</span>'
c3.innerHTML = product_name
c4.innerText = Product_Location
c5.innerText =purchasingـprice 
c6.innerText = numberofpice
c7.innerText = (( purchasingـprice * numberofpice)).toFixed(2)









                        });

                    },
                });
            } else {
                alert('url null not fount pervoius')
            }
        });



    });
</script>


@endsection