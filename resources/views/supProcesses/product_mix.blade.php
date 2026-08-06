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


<style>
    /* إجبار كل النصوص والأرقام والعناصر داخل جداول العرض على الظهور باللون الأبيض الناصع */
    #example, #example *,
    #tableTotalPrice, #tableTotalPrice * {
        color: #ffffff !important;
    }

    /* إجبار الأزرار أو الروابط داخل الجدول إن وجدت على أن تكون واضحة */
    #example a, #tableTotalPrice a {
        color: #ffffff !important;
    }
</style>
@endsection

@section('title')
    {{ __('home.product_mix') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between align-items-center my-3">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 font-weight-bold text-primary">{{ __('home.product_mix') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
            </div>
        </div>
        <div class="choose-product">
            <button style="background-color: #23395D;" class="modal-effect btn btn-sm btn-info px-3 py-2 shadow-sm font-weight-bold" data-effect="effect-scale" data-toggle="modal" href="#updateinvoicefromsale" title="تحديد">
                <i class="las la-edit ml-1"></i> {{ __('home.update_product_mix') }}
                <svg style="width: 18px; height: 18px; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus mr-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                    <path d="M16 19h6"></path>
                    <path d="M19 16v6"></path>
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                </svg>
            </button>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطأ</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('nodataprint'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <strong>{{ __('home.nodataprint') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Row Form Section -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body">

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/'. ($page = 'AddproducttoSupllier')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="productcode_mix" class="control-label font-weight-bold text-muted">{{ __('home.productcode_mix') }}</label>
                                <input readonly type="text" class="form-control bg-light" id="productcode_mix" name="productcode_mix">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="mixproductname" class="control-label font-weight-bold text-muted">{{ __('home.mixproductname') }}</label>
                                <input type="text" class="form-control" id="mixproductname" name="mixproductname">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="mixproduct_cost" class="control-label font-weight-bold text-muted">{{ __('home.cost') }}</label>
                                <input type="text" class="form-control" id="mixproduct_cost" name="mixproduct_cost" value="0">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="text-secondary font-weight-bold mb-3">{{ __('home.component') }}</h5>

                        <div class="row align-items-end">
                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">
                            <input type="hidden" id="productNo">

                            <div class="col-lg-3 mb-3">
                                <a style="background-color: #FBA10F;" class="modal-effect btn btn-sm btn-warning text-white px-3 py-2 font-weight-bold shadow-sm w-100" data-effect="effect-scale" data-toggle="modal" href="#SearchProduct" title="تحديد">
                                    {{ __('home.chooose product') }}
                                    <svg style="width: 16px; height: 16px; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search mr-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                        <path d="M21 21l-6 -6"></path>
                                    </svg>
                                </a>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="productnameshow" class="control-label font-weight-bold text-muted">{{ __('home.productname') }}</label>
                                <input type="text" class="form-control bg-light" id="productnameshow" name="productnameshow" readonly>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-3">
                                <label for="quentity" class="control-label font-weight-bold text-muted">{{ __('home.quantity') }}</label>
                                <input type="text" class="form-control" id="quentity" name="quentity">
                            </div>

                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="cost_item" class="control-label font-weight-bold text-muted">{{ __('home.cost') }}</label>
                                <input type="number" class="form-control bg-light" id="cost_item" name="cost_item" readonly>
                            </div>
                        </div>

                        <input type="number" class="form-control" id="orderNo" name="orderNo" hidden>

                        <div class="d-flex justify-content-center mt-4">
                            <button style="background-color: #419BB2;" type="submit" id="button_1" class="btn text-white px-4 py-2 font-weight-bold shadow-sm">
                                {{ __('home.Add') }}
                                <svg style="width: 20px; height: 20px; vertical-align: middle;" class="svg-icon-buttons mr-1" viewBox="0 0 20 20">
                                    <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Tables Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="table-responsive hoverable-table">
                        <table id="example" class="table table-bordered table-striped text-md-nowrap text-center" name="prodyctsavaliable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('home.productNo') }}</th>
                                    <th>{{ __('home.product') }}</th>
                                    <th>{{ __('home.quantity') }}</th>
                                    <th>{{ __('home.price') }}</th>
                                    <th>{{ __('home.addedValue') }}</th>
                                    <th>{{ __('home.total') }}</th>
                                    <th>{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Price Table -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered text-md-nowrap mb-0 table-striped text-center" id="tableTotalPrice" name="tableTotalPrice">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('home.the amount') }}</th>
                                            <th>{{ __('home.addedValue') }}</th>
                                            <th>{{ __('home.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>*</td>
                                            <td>*</td>
                                            <td>*</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Print Form -->
                    <div class="d-flex justify-content-center mt-3">
                        <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() .'/'. ($page = 'printProductToSupllierOrder')) }}" method="POST" role="search" autocomplete="off">
                            {{ csrf_field() }}
                            <input type="number" class="form-control" name="show_invoice_number" id="show_invoice_number" readonly required hidden>
                            <input value="{{ Auth()->user()->branch->id }}" class="form-control" name="branchs_id" id="branchs_id" readonly required hidden>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- row closed -->

    <!-- Modal: ExampleModal2 -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel">{{ __('home.RETURNSPURCHASEpart') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatePurchaseOrder')) }}" method="post" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="hidden" name="original_quantity" id="original_quantity" value="">
                            <input type="hidden" name="id" id="id" value="">
                            <input type="hidden" name="ordernumber" id="ordernumber" value="">
                            <label for="product_name" class="col-form-label font-weight-bold">{{ __('home.product') }}</label>
                            <input class="form-control bg-light" name="product_name" id="product_name" type="text" readonly>
                        </div>
                        <div class="form-group">
                            <label for="return_quentity" class="col-form-label font-weight-bold">{{ __('home.numberofpicereturens') }}</label>
                            <input class="form-control" name="return_quentity" id="return_quentity" type="text">
                        </div>
                        <div class="modal-footer">
                            <button id="added_product" name="added_product" class="btn btn-primary px-4">{{ __('home.confirm') }}</button>
                            <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">{{ __('home.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: SearchProduct -->
    <div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir="rtl" aria-hidden="true">
        <div class="modal-dialog modal-xl product-selection" role="document">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="col-lg-4 mb-3 px-0">
                            <label for="searchaboutproduct" class="control-label font-weight-bold text-muted">{{ __('home.searchaboutproduct') }}</label>
                            <input autocomplete="off" dir="ltr" type="text" class="form-control" placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                        </div>

                        <div class="table-responsive" id="ajax_responce_serarchDiv">
                            <table class="table table-bordered table-striped text-md-nowrap text-center" id="SearchProductTable" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('home.productNo') }}</th>
                                        <th>{{ __('home.product') }}</th>
                                        <th>{{ __('home.branch') }}</th>
                                        <th>{{ __('home.productlocation') }}</th>
                                        <th>{{ __('home.quantity') }}</th>
                                        <th>{{ __('home.purchaseproductwithouttax') }}</th>
                                        <th>{{ __('home.sellingproduct without tax') }}</th>
                                        <th>{{ __('home.Add') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer px-0 pb-0 mt-3">
                            <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">{{ __('home.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Update Invoice From Sale -->
    <div class="modal fade" id="updateinvoicefromsale" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content shadow">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold">{{ __('home.productcode_mix') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label for="updateinvoicebyidforsale" class="control-label font-weight-bold text-muted">{{ __('home.enterinvoicenumber') }}</label>
                            <input autocomplete="off" type="text" class="form-control" id="updateinvoicebyidforsale" name="updateinvoicebyidforsale" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button style="background-color: #419BB2;" class="btn text-white px-4" data-dismiss="modal" id="updateinvoicebyidforsaleupdate">
                            {{ __('home.search') }}
                            <svg style="width: 18px; vertical-align: middle;" class="svg-icon-buttons mr-1" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')

<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>


<script>
     $('#SearchProduct').on('shown.bs.modal', function () {

$('#searchaboutproduct').focus();
$('#searchaboutproduct').val($('#productcode').val());
       $('#searchaboutproduct').keyup()



})
$(document).on('click', '#ajax_pagination_in_search a', function(e) {
        e.preventDefault();
        console.log(url)
        var searchtext = $("#searchaboutproduct").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();
        branchs_id = $('#branchs_id').val();
        console.log(url)

        jQuery.ajax({
            url: url,
            type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "branchs_id": branchs_id,
                    "locale" : "{{ app()->getLocale() }}"
                },
            success: function(data) {
                console.log(data)
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });

function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();

        jQuery.ajax({
                url:  "{{ URL::to('searchChooseProductpaginatenewpurchaseBypost')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "branchs_id": branchs_id,
                    "locale" : "{{ app()->getLocale() }}"
                },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },

        });

    }
function doc_keyUp(e) {

// this would test for whichever key is 40 (down arrow) and the ctrl key at the same time
if (e.ctrlKey && e.keyCode == '38') {
    // call your function to do the thing

    $('#SearchProduct').modal().show();

}
}


document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.code === 'ArrowDown') {

        event.preventDefault();

let table = document.getElementById("example");

var url = " {{URL::to('Addmixproduct')}}";

var _token = $("#token_search").val();




if ($('#mixproductname').val() == '') {
    alert("{{ __('home.mixproductname')}}")

} else if ($('#productname').val() == '') {
    alert("{{ __('home.pleaseChooseProduct')}}")

} else if ($('#quentity').val() == '') {
    alert("{{ __('home.pleaseCompleteEmpty') }}")

} else {

    $.ajax({
        url: url,
        type: 'post',
        cache: false,

        data: {
            "_token": _token,
            "mixproductname": $('#mixproductname').val(),
            "mixproduct_cost": $('#mixproduct_cost').val(),
            "productnameshow": $('#productnameshow').val(),
            "quentity": $('#quentity').val(),
            "productNo": $('#productNo').val(),
            "quentityprice": $('#cost_item').val(),
            "orderNo": $('#orderNo').val()
        },


        success: function(data) {


            // const map =(JSON.parse(response));

            console.log('+++HI mOHAMED+++')


            var tableHeaderRowCount = 1;

            var rowCount = table.rows.length;

            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                table.deleteRow(tableHeaderRowCount);
            }
            count1 = 0;
            added_value_total = 0;
            total_purchases = 0;
            total_amount = 0;
            data.forEach(async (product) => {
                $('#show_invoice_number').val(product['orderNo'])
                $('#productcode_mix').val(product['productcode_mix'])

                $('#orderNo').val(product['orderNo'])

                count1 = product['count'],
                product_code = product['productCode']
                product_name = product['product_name']
                quentity = product['quantity']
                purchasingـprice = product['purchasingـprice']
                addedvalue = product['Added_Value']
                total = (product['total'])
                added_value_total = product['totalAdded_Value']
                total_purchases = (product['totalPrice'])
                total_amount = added_value_total + total_purchases,

                text1 = '<button style="width:40px;height:20px"  class="btn btn-danger mt-2" data-dismiss="modal"'
                result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i  class="las la-trash trash-table"></i>', "</button> ")

                text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")

                if (quentity > 0) {
                    console.log(result2)

                    let table = document.getElementById("example");
                    let row = table.insertRow(-1); // We are adding at the end

                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);
                    let c8 = row.insertCell(7);

                    // Add data to c1 and c2

                    c1.innerText = count1
                    c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                    c3.innerText = product_name
                    c4.innerText = quentity
                    c5.innerText = purchasingـprice
                    c6.innerText = addedvalue
                    c7.innerText = Math.round(total * 100, 2) / 100;
                    c8.innerHTML = result + ' ' + result2 + '  ' + result1




                }


            });
            $("#productname").val('');
            $('#productnameshow').val('');
            $('#quentityprice').val('price');
            $('#productNo').val('');
            let tableTotalPrice = document.getElementById("tableTotalPrice");
            var tableHeaderRowCount = 1;

            var rowCount = tableTotalPrice.rows.length;

            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                tableTotalPrice.deleteRow(tableHeaderRowCount);
            }
            let row = tableTotalPrice.insertRow(-1); // We are adding at the end

            let c1 = row.insertCell(0);
            let c2 = row.insertCell(1);
            let c3 = row.insertCell(2);


            // Add data to c1 and c2

            c1.innerText = Math.round(total_purchases * 100, 2) / 100
            c2.innerText = Math.round(added_value_total * 100, 2) / 100
            c3.innerText = Math.round(total_amount * 100, 2) / 100












            $('#productname').val('');
            $('#productnameshow').val('');
            $('#quentity').val('');
            $('#quentityprice').val('');
            $('#sale_price').val('');






        },
        error: function(response) {
            console.log(response)
            alert("{{ __('home.sorryerror')}}")

        }
    });
}

    }


})
// register the handler
document.addEventListener('keyup', doc_keyUp, false);

    $('#exampleModal2').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        console.log('jjhjhjjjj ----- jjhhhhhh')
        console.log(button.data('id'))
        console.log(button.data('ordernumber'))
        console.log(button.data('section_name'))
        console.log(button.data('description'))
        var id = button.data('id')
        var ordernumber = button.data('ordernumber')
        var section_name = button.data('section_name')
        var description = button.data('description')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #ordernumber').val(ordernumber);
        modal.find('.modal-body #product_name').val(section_name);
        modal.find('.modal-body #return_quentity').val(description);
        modal.find('.modal-body #original_quantity').val(description);

    })
</script>
<script>
    function convertToNumbersalePrice() {
        var input = document.getElementById("sale_price");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>
<script>
    function convertToNumberpurchasersPrice() {
        var input = document.getElementById("quentityprice");
        var val = toEnglishNumber(input.value)
        input.value = val;
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
        //  strNum = strNum.replace(/[^\d]/g, '');
        return strNum;
    }
</script>


{{-- Update ( 24/4/2023 ) --}}

<script>
   $(document).on('click', '#ajax_pagination_in_search a', function(e) {
        e.preventDefault();
        console.log(url)
        var search_by_text = $("#search_by_text").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();
        branchs_id = $('#branchs_id').val();
        console.log(url)

        jQuery.ajax({
            url: url,
            type: 'get'+'/'+ branchs_id,
            dataType: 'html',
            cache: false,

            success: function(data) {
                console.log(data)
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });
    $('#SearchProduct').on('show.bs.modal', function(event) {
        branchs_id = $('#branchs_id').val();
        console.log(branchs_id)
        jQuery.ajax({
            url: " {{URL::to('ChooseProductpaginatenew')}}/" + branchs_id,
            type: 'get',
            dataType: 'html',
            cache: false,

            success: function(data) {
                console.log('done')
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });

    })
</script>

{{-- End Update ( 24/4/2023 ) --}}



{{-- Update ( 24/4/2023 ) --}}

<script>
    function chooseProduct(code, name, price, sale_price, location, availablequantity) {
        $('#SearchProduct').modal().hide();

        $('#searchaboutproduct').val('');

        document.getElementById("quentity").focus();
        console.log('------')
        console.log(code)
        console.log(name)
        console.log(price)
        console.log(sale_price)
        console.log(location)
        console.log(availablequantity)
        console.log('---*---')


        $('#productnameshow').val(name);
        $('#cost_item').val(price);
        $('#productNo').val(code);

    }
</script>

{{-- End Update ( 24/4/2023 ) --}}



<script>
    function increaseProduct(id_increase, ordernumber, increasequentity) {

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);
        console.log('+++incr---->>>---ease+++')

        console.log(id_increase)
        console.log(ordernumber)
        console.log(increasequentity)

        console.log('+++increase+++')
        var url = " {{URL::to('updateproduct_mix_Increase')}}";
        token_search = $('#token_search').val();




        $.ajax({
            url: url,
            type: 'post',
            cache: false,

            data: {
                _token: token_search,
                id: id_increase,
                ordernumber: ordernumber,
                increasequentity: 1,
            },


            success: function(data) {

                // const map =(JSON.parse(response));



                console.log('+++increase+++')
                console.log(data)
                var tableHeaderRowCount = 1;

                var rowCount = table.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    table.deleteRow(tableHeaderRowCount);
                }
                count1 = 0;
                added_value_total = 0;
                total_purchases = 0;
                total_amount = 0;
                data.forEach(async (product) => {

                    $('#orderNo').val(product['orderNo'])
                    $('#productcode_mix').val(product['productcode_mix'])

                    count1 = product['count'],
                        product_code = product['productCode']
                    product_name = product['product_name']
                    quentity = product['quantity']
                    purchasingـprice = product['purchasingـprice']
                    saleperpice = product['saleperpice']
                    addedvalue = product['Added_Value']
                    total = product['total']

                    added_value_total = added_value_total + (product['Added_Value'] * product['quantity'])
                    total_purchases = total_purchases + (product['purchasingـprice'] * product['quantity'])
                    total_amount = total_amount + ((product['purchasingـprice'] * product['quantity']) + (product['Added_Value'] * product['quantity']))



                    text1 = '<button style="width:40px;height:20px" type="button"  class="btn btn-danger mt-2" data-dismiss="modal"'
                    result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i class="las la-trash trash-table"></i>', "</button> ")

                    text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                    text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")



                    if (quentity > 0) {


                        let table = document.getElementById("example");
                        let row = table.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        let c8 = row.insertCell(7);

                        // Add data to c1 and c2

                        c1.innerText = count1
                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                        c3.innerText = product_name
                        c4.innerText = quentity
                        c5.innerText = purchasingـprice
                        c6.innerText = addedvalue
                        c7.innerText = Math.round(total * 100, 2) / 100
                        c8.innerHTML = result + ' ' + result2 + '  ' + result1




                    }


                });
                let tableTotalPrice = document.getElementById("tableTotalPrice");
                var tableHeaderRowCount = 1;

                var rowCount = tableTotalPrice.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    tableTotalPrice.deleteRow(tableHeaderRowCount);
                }
                let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);


                // Add data to c1 and c2

                c1.innerText = Math.round(total_purchases * 100, 2) / 100
                c2.innerText = Math.round(added_value_total * 100, 2) / 100
                c3.innerText = Math.round(total_amount * 100, 2) / 100

                //    update3/3/2023





                var rowCount = table.rows.length;

                for (var i = 0; i < rowCount; i++) {
                    var data = table.rows[i].innerText.innerText;
                    console.log('end');

                }










            },
            error: function(response) {
                console.log(response)
                alert("{{ __('home.sorryerror')}}")

            }
        });


    }
</script>
<script>


$("#updateinvoicebyidforsaleupdate").click(function(e) {
     console.log(" {{ URL::to('getmixproduct') }}" + "/" + $('#updateinvoicebyidforsale').val())
            event.preventDefault();
            var url = " {{ URL::to('getmixproduct') }}" + "/" + $('#updateinvoicebyidforsale').val();
            console.log(url)
            jQuery.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                cache: false,

                success: function(data) {

                    let table = document.getElementById("example");

                // const map =(JSON.parse(response));



                console.log('++++++')
                var tableHeaderRowCount = 1;

                var rowCount = table.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    table.deleteRow(tableHeaderRowCount);
                }
                count1 = 0;
                added_value_total = 0;
                total_purchases = 0;
                total_amount = 0;
                data.forEach(async (product) => {

                    $('#orderNo').val(product['orderNo'])
                    $('#productcode_mix').val(product['productcode_mix'])
                    $('#mixproductname').val(product['product_name_mix'])

                    count1 = product['count'],
                        product_code = product['productCode']
                    product_name = product['product_name']
                    quentity = product['quantity']
                    purchasingـprice = product['purchasingـprice']
                    saleperpice = product['saleperpice']
                    total = product['total']
                    addedvalue = product['Added_Value']
                    total = product['total']

                    added_value_total = added_value_total + (product['Added_Value'] * product['quantity'])
                    total_purchases = total_purchases + (product['purchasingـprice'] * product['quantity'])
                    total_amount = total_amount + ((product['purchasingـprice'] * product['quantity']) + (product['Added_Value'] * product['quantity']))



                    text1 = '<button style="width:40px;height:20px"  class="btn btn-danger mt-2" data-dismiss="modal"'
                    result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i class="las la-trash trash-table"></i>', "</button> ")

                    text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                    text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")


                    if (quentity > 0) {


                        let table = document.getElementById("example");
                        let row = table.insertRow(-1); // We are adding at the end
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        let c8 = row.insertCell(7);

                        // Add data to c1 and c2

                        c1.innerText = count1
                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                        c3.innerText = product_name
                        c4.innerText = quentity
                        c5.innerText = purchasingـprice
                        c6.innerText = addedvalue
                        c7.innerText = Math.round(total * 100, 2) / 100
                        c8.innerHTML = result + ' ' + result2 + '  ' + result1



                    }


                })
                let tableTotalPrice = document.getElementById("tableTotalPrice");
                var tableHeaderRowCount = 1;

                var rowCount = tableTotalPrice.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    tableTotalPrice.deleteRow(tableHeaderRowCount);
                }
                let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);


                // Add data to c1 and c2

                c1.innerText = Math.round(total_purchases * 100, 2) / 100
                c2.innerText = Math.round(added_value_total * 100, 2) / 100
                c3.innerText = Math.round(total_amount * 100, 2) / 100

                //    update3/3/2023





                var rowCount = table.rows.length;

                for (var i = 0; i < rowCount; i++) {
                    var data = table.rows[i].innerText.innerText;
                    console.log('end');

                }






            }

            })
        })


    function decreaseProdect(id_decrease, ordernumber, decreasequentity) {
        event.preventDefault();
        $('#exampleModal2').modal('hide');

        let table = document.getElementById("example");


        var token_search = $("#token_search").val();
        console.log(token_search);

        var url = " {{URL::to('updateproduct_mix_decrease')}}";
        token_search = $('#token_search').val();

        console.log('+++Decrease+++')
        console.log('+++id+++')

        console.log(id_decrease)
        console.log(ordernumber)
        console.log(decreasequentity)

        console.log('+++Decr--->ease+++')



        $.ajax({
            url: url,
            type: 'post',
            cache: false,

            data: {
                _token: token_search,
                id: id_decrease,
                ordernumber: ordernumber,
                return_quentity: decreasequentity,
            },


            success: function(data) {

                // const map =(JSON.parse(response));



                console.log('++++++')
                console.log(data)
                var tableHeaderRowCount = 1;

                var rowCount = table.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    table.deleteRow(tableHeaderRowCount);
                }
                count1 = 0;
                added_value_total = 0;
                total_purchases = 0;
                total_amount = 0;
                data.forEach(async (product) => {

                    $('#orderNo').val(product['orderNo'])
                    $('#productcode_mix').val(product['productcode_mix'])

                    count1 = product['count'],
                        product_code = product['productCode']
                    product_name = product['product_name']
                    quentity = product['quantity']
                    purchasingـprice = product['purchasingـprice']
                    saleperpice = product['saleperpice']
                    total = product['total']
                    addedvalue = product['Added_Value']
                    total = product['total']

                    added_value_total = added_value_total + (product['Added_Value'] * product['quantity'])
                    total_purchases = total_purchases + (product['purchasingـprice'] * product['quantity'])
                    total_amount = total_amount + ((product['purchasingـprice'] * product['quantity']) + (product['Added_Value'] * product['quantity']))



                    text1 = '<button style="width:40px;height:20px"  class="btn btn-danger mt-2" data-dismiss="modal"'
                    result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i class="las la-trash trash-table"></i>', "</button> ")

                    text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                    text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                    result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")


                    if (quentity > 0) {


                        let table = document.getElementById("example");
                        let row = table.insertRow(-1); // We are adding at the end
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        let c7 = row.insertCell(6);
                        let c8 = row.insertCell(7);

                        // Add data to c1 and c2

                        c1.innerText = count1
                        c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                        c3.innerText = product_name
                        c4.innerText = quentity
                        c5.innerText = purchasingـprice
                        c6.innerText = addedvalue
                        c7.innerText = Math.round(total * 100, 2) / 100
                        c8.innerHTML = result + ' ' + result2 + '  ' + result1




                    }


                });
                let tableTotalPrice = document.getElementById("tableTotalPrice");
                var tableHeaderRowCount = 1;

                var rowCount = tableTotalPrice.rows.length;

                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                    tableTotalPrice.deleteRow(tableHeaderRowCount);
                }
                let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);


                // Add data to c1 and c2

                c1.innerText = Math.round(total_purchases * 100, 2) / 100
                c2.innerText = Math.round(added_value_total * 100, 2) / 100
                c3.innerText = Math.round(total_amount * 100, 2) / 100

                //    update3/3/2023





                var rowCount = table.rows.length;

                for (var i = 0; i < rowCount; i++) {
                    var data = table.rows[i].innerText.innerText;
                    console.log('end');

                }










            },
            error: function(response) {
                alert("{{ __('home.sorryerror')}}")

            }
        });

    }
</script>





<script>
    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

        function selectProduct(val) {
            alert(val);
        }

        $('select[name="clientNosearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#clientName').val(data['location']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone']);
                    },
                });
            } else {
                alert("{{ __('home.sorryerror')}}")
            }
        });




        // Update ( 24/4/2023 )




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
                        Product_Code_1 = Product_Code.replaceAll(" ", "<");
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
                            Product_Code_1 = Product_Code.replaceAll(" ", "<");

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




        $("#button_1").click(function(e) {
            event.preventDefault();

            let table = document.getElementById("example");

            var url = " {{URL::to('Addmixproduct')}}";

            var _token = $("#token_search").val();




            if ($('#mixproductname').val() == '') {
                alert("{{ __('home.mixproductname')}}")

            } else if ($('#productname').val() == '') {
                alert("{{ __('home.pleaseChooseProduct')}}")

            } else if ($('#quentity').val() == '') {
                alert("{{ __('home.pleaseCompleteEmpty') }}")

            } else {

                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        "_token": _token,
                        "mixproductname": $('#mixproductname').val(),
                        "mixproduct_cost": $('#mixproduct_cost').val(),
                        "productnameshow": $('#productnameshow').val(),
                        "quentity": $('#quentity').val(),
                        "productNo": $('#productNo').val(),
                        "quentityprice": $('#cost_item').val(),
                        "orderNo": $('#orderNo').val()
                    },


                    success: function(data) {


                        // const map =(JSON.parse(response));

                        console.log('+++HI mOHAMED+++')


                        var tableHeaderRowCount = 1;

                        var rowCount = table.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        count1 = 0;
                        added_value_total = 0;
                        total_purchases = 0;
                        total_amount = 0;
                        data.forEach(async (product) => {
                            $('#show_invoice_number').val(product['orderNo'])
                            $('#productcode_mix').val(product['productcode_mix'])

                            $('#orderNo').val(product['orderNo'])

                            count1 = product['count'],
                            product_code = product['productCode']
                            product_name = product['product_name']
                            quentity = product['quantity']
                            purchasingـprice = product['purchasingـprice']
                            addedvalue = product['Added_Value']
                            total = (product['total'])
                            added_value_total = product['totalAdded_Value']
                            total_purchases = (product['totalPrice'])
                            total_amount = added_value_total + total_purchases,

                            text1 = '<button style="width:40px;height:20px"  class="btn btn-danger mt-2" data-dismiss="modal"'
                            result1 = text1.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", quentity, ")>", '<i  class="las la-trash trash-table"></i>', "</button> ")

                            text = '<button style="height:20px;width:20px;background-color: #419BB2"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                            result = text.concat("onclick=", "decreaseProdect(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-minus"></i>', "</button> ")


                            text2 = '<button style="height:20px;width:20px;background-color: #419BB2" type="button"  class="btn btn-success minus-plus-buttons mt-2" data-dismiss="modal"'
                            result2 = text2.concat("onclick=", "increaseProduct(", product['product_id'], ",", product['orderNo'], ",", "1", ")>", '<i class="las la-plus"></i>', "</button> ")

                            if (quentity > 0) {
                                console.log(result2)

                                let table = document.getElementById("example");
                                let row = table.insertRow(-1); // We are adding at the end

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);
                                let c5 = row.insertCell(4);
                                let c6 = row.insertCell(5);
                                let c7 = row.insertCell(6);
                                let c8 = row.insertCell(7);

                                // Add data to c1 and c2

                                c1.innerText = count1
                                c2.innerHTML = ' <span dir=ltr>' + product_code + '</span>'
                                c3.innerText = product_name
                                c4.innerText = quentity
                                c5.innerText = purchasingـprice
                                c6.innerText = addedvalue
                                c7.innerText = Math.round(total * 100, 2) / 100;
                                c8.innerHTML = result + ' ' + result2 + '  ' + result1




                            }


                        });
                        $("#productname").val('');
                        $('#productnameshow').val('');
                        $('#quentityprice').val('price');
                        $('#productNo').val('');
                        let tableTotalPrice = document.getElementById("tableTotalPrice");
                        var tableHeaderRowCount = 1;

                        var rowCount = tableTotalPrice.rows.length;

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            tableTotalPrice.deleteRow(tableHeaderRowCount);
                        }
                        let row = tableTotalPrice.insertRow(-1); // We are adding at the end

                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);


                        // Add data to c1 and c2

                        c1.innerText = Math.round(total_purchases * 100, 2) / 100
                        c2.innerText = Math.round(added_value_total * 100, 2) / 100
                        c3.innerText = Math.round(total_amount * 100, 2) / 100












                        $('#productname').val('');
                        $('#productnameshow').val('');
                        $('#quentity').val('');
                        $('#quentityprice').val('');
                        $('#sale_price').val('');






                    },
                    error: function(response) {
                        console.log(response)
                        alert("{{ __('home.sorryerror')}}")

                    }
                });
            }
        });







        $('select[name="clientNosearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);

                        $('#phonenumber').val(data['phone'] == null ? '05---------' : data['phone']);
                        $('#notes').val(data['comp_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });

        $('select[name="clientnamesearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");

                        console.log(data['name']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone'] == null ? '05---------' : data['phone']);
                        $('#notes').val(data['comp_name']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });
</script>


@endsection
