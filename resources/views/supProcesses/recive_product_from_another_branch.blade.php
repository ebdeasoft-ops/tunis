@extends('layouts.master')

@section('css')
<style>
.our-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.our-table th,
.our-table td {
    border: 1px solid #000 !important;
    padding: 10px;
    text-align: center;
}

.input-edit {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px;
    width: 100%;
    text-align: center;
    font-weight: bold;
}
</style>
@endsection

@section('title') {{__('home.recive_product_from_other_branch_other')}} @stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0">{{__('home.recive_product_from_other_branch_other')}}</h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <input type="hidden" id="token_search" value="{{ csrf_token() }}">
                <input type="hidden" id="invoiceId" value="-">

                <div class="row">
                    <div class="col-lg-4">
                        <label>{{ __('home.Invoice_no') }}</label>
                        <select name="reciveInvoiceNumber" id="reciveInvoiceNumber" class="form-control select2">
                            <option value="-">{{ __('home.Invoice_no') }}</option>
                            @foreach (App\Models\product_movement_another_branch::where('branch_to',
                            $branchId)->where('reciveInvoiceNumber', 1)->get() as $item)
                            <option value="{{ $item->id }}">{{ $item->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3"><label>{{ __('home.branch_sender') }}</label><input id="branch_show"
                            class="form-control" readonly></div>
                    <div class="col-lg-3"><label>{{ __('home.employeesender') }}</label><input id="userfrom_show"
                            class="form-control" readonly></div>
                </div>

                <table id="example" class="table our-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('home.productNo') }}</th>
                            <th>{{ __('home.product') }}</th>
                            <th>{{ __('home.quantity') }}</th>
                            <th>{{ __('home.thecostProduct') }}</th>
                            <th>{{ __('home.total') }}</th>
                        </tr>
                    </thead>
                    <tbody id="table-body"></tbody>
                </table>

                <div id="confirmDiv" class="d-flex justify-content-center mt-4">
                    <button id="button_1" class="btn btn-info">{{ __('home.confirm') }}</button>
                </div>

                <div id="printSection" class="d-none justify-content-center mt-3">
                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/print_Recive_items') }}"
                        method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="sprint_invoice_number" id="sprint_invoice_number">
                        <button type="submit" class="btn btn-success"><i class="fas fa-print"></i>
                            {{ __('home.print') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modaldemo9">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.alert') }}</h6>
            </div>
            <div class="modal-body"><span style="color:red">{{ __('home.reciveproductnote') }}</span></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                <button id="deleteproduct" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade product-selection" id="SearchProduct" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <label>{{__('home.searchaboutproduct')}}</label>
                            <input type="text" class="form-control" id="searchaboutproduct"
                                onkeyup="searchaboutproductfunction()">
                        </div>
                    </div>
                    <div class="table-responsive" id="ajax_responce_serarchDiv"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<input hidden=true class="form-control" id="branchs_id" name="branchs_id" value="{{Auth()->user()->branchs_id}}">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var currentRowIndex = 0;

// دالة اختيار المنتج
function chooseProduct(code, productcode, name, cost, sale_price, location, availablequantity) {
    let rows = document.querySelectorAll('#table-body tr');
    let targetRow = rows[currentRowIndex];
    if (targetRow) {
        targetRow.querySelector('input[name="product_code[]"]').value = productcode;
    }
    $('#SearchProduct').modal('hide');
}

// دالة البحث
function searchaboutproductfunction() {
    let searchtext = $('#searchaboutproduct').val();
    let branchs_id = $('#branchs_id').val();
    $.ajax({
        url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
        type: 'post',
        data: {
            "_token": $("#token_search").val(),
            "searchtext": searchtext,
            "branchs_id": branchs_id
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        }
    });
}
$(document).on('click', '#ajax_pagination_in_search a', function(e) {
    e.preventDefault();
    var search_by_text = $("#searchaboutproduct").val();
    var url = $(this).attr("href");
    var token_search = $("#token_search").val();
    branchs_id = $('#branchs_id').val();

    jQuery.ajax({
        url: url,
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            "_token": token_search,
            "searchtext": search_by_text,
            "branchs_id": branchs_id,
            "currentrow": window.currentRow,

        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },
        error: function() {

        }
    });
});

// تحميل المودال
$('#SearchProduct').on('show.bs.modal', function() {
    searchaboutproductfunction();
});

// جلب البيانات عند اختيار فاتورة
$('select[name="reciveInvoiceNumber"]').on('change', function() {
    var id = $(this).val();
    $("#sprint_invoice_number").val(id);
    if (id === '-') return;
    $.ajax({
        url: "{{ URL::to('/findinvoiceMovmevt') }}/" + id,
        type: "GET",
        success: function(data) {
            let tbody = $('#table-body');
            tbody.empty();
            data.orderItems.forEach((p, index) => {
                tbody.append(`<tr data-index="${index}">
                        <td>${p.count}</td>
                        <td><input type="hidden" name="product_name[]" value="${p.productname}">
                                           <div class="d-flex gap-2">
                                <input type="hidden" name="product_name[]" value="${p.productname}">
                                <input type="text" name="product_code[]" class="input-edit product-code" value="${p.product_code}" readonly>
                                <button type="button" class="btn btn-sm btn-info p-1" onclick="openProductModal(${index})">
                                    <svg style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                        <path d="M21 21l-6 -6"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td>${p.productname}</td>
                        <td><input type="number" name="quantity[]" class="input-edit" value="${p.quantity}"></td>
                        <td><input type="number" name="cost[]" class="input-edit" value="${p.cost}"></td>
                        <td>${p.total}</td>
                    </tr>`);
            });
        }
    });
});

function openProductModal(index) {
    window.currentRowIndex = index;
    $('#SearchProduct').modal('show');
}

// تأكيد العملية
$("#button_1").click(function() {
    if ($('#reciveInvoiceNumber').val() === '-') {
        alert("يرجى اختيار رقم الفاتورة");
    } else {
        $('#modaldemo9').modal('show');
    }
});

$("#deleteproduct").click(function() {
    let product_codes = $("input[name='product_code[]']").map(function() {
        return $(this).val();
    }).get();
    let product_names = $("input[name='product_name[]']").map(function() {
        return $(this).val();
    }).get();
    let quantities = $("input[name='quantity[]']").map(function() {
        return $(this).val();
    }).get();
    let costs = $("input[name='cost[]']").map(function() {
        return $(this).val();
    }).get();

    $.ajax({
        url: "{{ URL::to('create_reciveProduct') }}",
        type: 'post',
        data: {
            _token: $("#token_search").val(),
            product_code: product_codes,
            product_name: product_names,
            quantity: quantities,
            cost: costs
        },
        success: function(data) {
            console.log(data)
            // التعديل هنا: استخدام data.invoice_number بدلاً من invoice_number

            $('#confirmDiv').fadeOut(200, function() {
                $(this).remove();
            });

            // إظهار زر الطباعة
            $('#printSection').removeClass('d-none').addClass('d-flex').fadeIn(500);
            $('#modaldemo9').modal('hide');
            Swal.fire('تمت العملية بنجاح', '', 'success');
        }
    });
});
</script>
@endsection