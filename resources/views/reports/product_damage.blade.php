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
    {{ __('home.product damage') }}@stop
@endsection
@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.product damage') }}

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
        @if (session()->has('notfountreturnproduct'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <br>
                <strong>{{ session()->get('notfountreturnproduct') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- row -->

            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'ProductsDamageReport')) }}"
                        method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-lg-4" id="start_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}"
                                        name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-4" id="end_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" name="end_at"
                                        value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-lg-4 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control parent-input" name="branch" required>
                                    <option value="-" selected>{{ __('users.allbranchs') }}
                                    </option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                        <option value="{{ $branch->id }}"> {{ $branch->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            

                            
                            

                            <input class="form-control select2 " name="productNo" id="productNo" value='-' hidden>

                        </div>
                        
                        <div class="row my-3">
                        <div class="col-lg-2 mg-t-20 mg-lg-t-0 p-2" style=" height: 80px; width: 300px;">
                                <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                                    <a style="background-color: #FBA10F" class="modal-effect btn btn-md btn-info p-0 py-1 button-eng" data-effect="effect-scale"
                                        data-toggle="modal" href="#SearchProduct" title="تحديد">
                                        {{ __('home.chooose product') }}
                                        <i
                                            style=" height: 100;
                                                 width: 130px;"
                                            class="las la-search"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.productname') }} </label>
                                <input type="text" class="form-control parent-input" id="productnameshow" name="productnameshow" readonly
                                    >

                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center mb-3 mt-3">
                            <button class="btn btn-success mb-3 print-style my-3">
                                {{ __('home.search') }}
                                <i
                                        style=" height: 100;
                                                 
                                                 font-size:15px"
                                        class="las la-search"></i>
                            </button>
                        </div>


                    </form>

                    @if (isset($products))

                            <div style="border-radius: 10px" class="card p-3">
                                <div class="table-responsive hoverable-table">
                                    <table class="table table-hover table-striped" id="example1" data-page-length='20'
                                        style=" text-align: center;">
    
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">#</th>
                                                <th class="border-bottom-0"> {{ __('home.productNo') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.product') }}</th>
                                                <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.employee') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.branch') }}</th>
                                                <th class="border-bottom-0"> {{ __('home.quentitydamage') }}</th>
                                               
    
    
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;?>
                                         
                                            @foreach ($products as $invoice)
                                                <?php 
                                                $i++;
                                                ?>
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td dir='ltr'>{{ $invoice->productData->Product_Code }} </td>
                                                    <td>{{ $invoice->productData->product_name }}</td>
                                                    <td>{{ $invoice->created_at }}</td>
                                                    <td>{{ $invoice->user->name }}</td>
                                                    <td>{{ $invoice->branch->name}}</td>
                                                    <td>{{ $invoice->damage_quantity }}</td>

                                                    
    
                                                </tr>
                                            @endforeach
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>


               
                  
                    @endif

                </div>
            </div>
        
    </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>



    {{-- Update ( 24/4/2023 ) --}}

<div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
<div style="margin: 4% 9% !important;" class="modal-dialog modal-xl modal-special" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">


            <div class="card-body">

                <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                    <label for="inputName" style="font-weight: bold" class="control-label parent-label">
                        {{ __('home.searchaboutproduct') }} </label>
                    <input type="text" class="form-control parent-input"
                        placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct"
                        name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table text-md-nowrap text-center our-table" id="SearchProductTable"
                        width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                        <col style="width:5%">
                        <col style="width:14%">
                        <col style="width:28%">
                        <col style="width:10%">
                        <col style="width:18%">
                        <col style="width:15%">
                        <col style="width:10%">

                        <thead>
                            <tr>
                                <th style="font-size: 15px" class="border-bottom-0">#</th>
                                <th style="font-size: 15px" class="border-bottom-0">{{ __('home.productNo') }}
                                </th>
                                <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                    {{ __('home.product') }}</th>
                                <th style="font-size: 15px" class="border-bottom-0">{{ __('home.quantity') }}
                                </th>
                                <th style="font-size: 13px" class="border-bottom-0">
                                    {{ __('home.purchaseproductwithouttax') }}</th>
                                <th style="font-size: 13px" class="border-bottom-0">
                                    {{ __('home.sellingproduct without tax') }}</th>
                                <th style="font-size: 15px" class="border-bottom-0">{{ __('home.Add') }}</th>



                            </tr>
                        </thead>
                        <tbody class="">
                            <?php $i = 0;
                            $products2=[];
                            $data = 'm';    
                                 $products2 =App\Models\products::paginate(20) ;
                                 ?>
?>
                            @foreach ($products2 as $product)
                                <?php $i++; ?>

                                <tr id="<?php echo $product['id']; ?>">
                                    <td id="tableData"  dir=ltr>{{ $product->id }}</td>
                                    <td id="tableData"  dir=ltr>{{ $product->Product_Code }}</td>
                                    <td id="tableData"  data-target="product_name">
                                        {{ $product->product_name }}</td>
                                    <td id="tableData"  data-target="numberofpice">
                                        {{ $product->numberofpice }}</td>
                                    <td id="tableData"  data-target="numberofpice">
                                        {{ $product->purchasingـprice }}</td>
                                    <td id="tableData"  data-target="numberofpice">
                                        {{ $product->sale_price }}</td>
                                    <td id="tableData" >
                                    
                                            <button style="padding: 6px 12px" type="button" id="btn"
                                                name="btn" class="btn btn-success" data-dismiss="modal"
                                                onclick="chooseProduct('{{ $product->id }}','{{ $product->product_name }}','{{ $product->purchasingـprice }}','{{ $product->sale_price }}','{{ $product->Product_Code }}','{{ $product->numberofpice }}')">{{ __('home.Add') }}</button>
                                        

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>

                    </div>

                    <div class="row d-flex justify-content-between pagination-row">

                        <div class="rows-number">
                            <label
                                style="font-size: 12px;color:#419BB2;font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;{{ __('pagination.numberofpages') }}
                                &nbsp;&nbsp; {{ $products2->perPage() }} &nbsp;&nbsp;</label>
                            <label
                                style="font-size: 12px;color:#419BB2;font-weight:bold">{{ __('pagination.numberofproducts') }}
                                &nbsp;&nbsp;{{ $products2->total() }} </label>
                        </div>

                        <div class="buttonss-table row d-flex justify-content-center mb-2">
                            <button id="previousPage" name="previousPage" class="btn btn-primary print-style"><span> << &nbsp;</span>{{__('pagination.previous')}}</button>
                            <div class="col-lg-2 col-md-2 col-sm-2 mg-lg-t-0 prodNumbers"> <input class="form-control " name="currentpage" id="currentpage" readonly value="{{$products2->currentPage()}}" readonly>
                            </div>
                            <input class="form-control " name="previousPagevalue" id="previousPagevalue" readonly value="{{$products2->previousPageUrl()}}" hidden>
                            <input class="form-control " name="nextPageValue" id="nextPageValue" readonly value="{{$products2->nextPageUrl()}}" hidden>
                            <button id="nextPage" name="nextPage" class="btn btn-primary print-style">{{__('pagination.next')}} >></button>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                {{-- <button id="added_product" name="added_product" id="added_product" class="btn btn-primary">{{__('home.confirm')}}</button> --}}
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>

        </div>


    </div>
</div>

</div>


{{-- End Update ( 24/4/2023 ) --}}



@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
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


    {{-- Update ( 24/4/2023 ) --}}

<script>
    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        $.ajax({
            url: " {{ URL::to('searchaboutproduct') }}" + "/" + searchtext,
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
                            '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                        name = product_name.replaceAll(" ", "<");
                        Product_Code_1= Product_Code.replaceAll(" ", "<");

                        button = text.concat("chooseProduct(", id, ",", "'", name, "'", ",",
                            purchasingـprice, ",", sale_price, ",", "'", Product_Code_1,
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
                    c4.innerText = numberofpice
                    c5.innerText = purchasingـprice
                    c6.innerText = sale_price

                    c7.innerHTML = button

                });

            },
        });
    }
</script>

{{-- End Update ( 24/4/2023 ) --}}



{{-- Update ( 24/4/2023 ) --}}

<script>
    function chooseProduct(code, name, price, sale_price, location, availablequantity) {
        $('#SearchProduct').modal().hide();
        $('#searchaboutproduct').val('');
        var Product_Code = code
        name = name.replaceAll("<", " ");
        location= location.replaceAll("<", " ");



            console.log('------')
            console.log(code)
            console.log(name)
            console.log(price)

            var Product_Code = code
            var product_name = name
            var product_sale_pice = price

            $('#productNo').val(code);
            $('#productnameshow').val(product_name);

    }
</script>

{{--  End Update ( 24/4/2023 ) --}}






    {{-- <script>
        function chooseProduct(code, name, price) {
            $('#SearchProduct').modal().hide();


            console.log('------')
            console.log(code)
            console.log(name)
            console.log(price)

            var Product_Code = code
            var product_name = name
            var product_sale_pice = price

            $('#productNo').val(code);
            $('#productnameshow').val(product_name);


        }
    </script> --}}
    <script>
        $(document).ready(function() {


           

            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
    
       


    $("#nextPage").click(function(e) {
        url = $('#nextPageValue').val().split('page=')[1];
        $.ajax({
            url: " {{ URL::to('goToSaleBypage') }}" + "?page=" + url,
            type: "GET",
            dataType: "json",
            success: function(data) {


                $('#previousPagevalue').val(data['prev_page_url'])
                $('#nextPageValue').val(data['next_page_url'])
                $('#currentpage').val(data['current_page'])
                let table = document.getElementById("SearchProductTable");
                var tableHeaderRowCount = 1;

                // table.classList.add('table-bordered');
                // table.classList.add('table-striped');

           

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
                            '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';
                        name = product_name.replaceAll(" ", "<");
                        Product_Code_1= Product_Code.replaceAll(" ", "<");
                        button = text.concat("chooseProduct(", id, ",", "'", name, "'",
                            ",", purchasingـprice, ",", sale_price, ",", "'",
                            Product_Code_1, "'", ",", numberofpice, ")",
                            ">{{ __('home.Add') }}</button>");

                    


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
                    c4.innerText = numberofpice
                    c5.innerText = purchasingـprice
                    c6.innerText = sale_price

                    c7.innerHTML = button







                });

            },
        });
    });

    $("#previousPage").click(function(e) {

        url = $('#previousPagevalue').val().split('page=')[1];

        if (url != '') {
            $.ajax({
                url: " {{ URL::to('goToSaleBypage') }}" + "?page=" + url,
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
                                '<button style="padding: 6px 12px" type="button" id="btn" name="btn" class="btn btn-success" data-dismiss="modal" onclick=';

                            name = product_name.replaceAll(" ", "<");
                            Product_Code_1= Product_Code.replaceAll(" ", "<");

                            button = text.concat("chooseProduct(", id, ",", "'", name,
                                "'", ",", purchasingـprice, ",", sale_price, ",",
                                "'", Product_Code_1, "'", ",", numberofpice, ")",
                                ">{{ __('home.Add') }}</button>");

                        


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
                        c4.innerText = numberofpice
                        c5.innerText = purchasingـprice
                        c6.innerText = sale_price

                        c7.innerHTML = button








                    });

                },
            });
        } else {
            alert('url null not fount pervoius')
        }
    });


    // End Update ( 24/4/2023 )

        });
    </script>


@endsection
