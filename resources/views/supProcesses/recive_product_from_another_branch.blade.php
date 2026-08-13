@extends('layouts.master')

@section('css')
<style>
    .card-custom {
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 8px;
    }
    .our-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 15px;
    }
    .our-table th {
        background-color: #1F4E78 !important;
        color: #ffffff !important;
        border: none !important;
        padding: 12px;
        text-align: center;
        font-weight: 600;
    }
    .our-table td {
        border-bottom: 1px solid #e9ecef !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        padding: 12px;
        text-align: center;
        vertical-align: middle;
    }
    .our-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .input-edit {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 6px 10px;
        width: 100%;
        text-align: center;
        font-weight: bold;
        transition: border-color 0.15s ease-in-out;
    }
    .input-edit:focus {
        border-color: #1F4E78;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(31, 78, 120, 0.25);
    }
</style>
@endsection

@section('title') {{__('home.recive_product_from_other_branch_other')}} @stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 text-primary font-weight-bold">
            <i class="fas fa-boxes text-primary {{ App::getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i> 
            {{__('home.recive_product_from_other_branch_other')}}
        </h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card card-custom mg-b-20">
            <div class="card-body">
                <input type="hidden" id="token_search" value="{{ csrf_token() }}">
                <input type="hidden" id="invoiceId" value="-">

                <!-- قسم اختيار الفاتورة والبيانات الأساسية -->
                <div class="div-header bg-light p-3 rounded mb-4">
                    <div class="row align-items-end">
                        <div class="col-lg-4 mb-3 mb-lg-0">
                            <label class="font-weight-bold text-dark">{{ __('home.Invoice_no') }}</label>
                            <select name="reciveInvoiceNumber" id="reciveInvoiceNumber" class="form-control select2">
                                <option value="-">-- {{ __('home.Invoice_no') }} --</option>
                                @foreach (App\Models\product_movement_another_branch::where('branch_to', $branchId)->where('user_to', Auth()->user()->id)->where('reciveInvoiceNumber', 1)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3 mb-lg-0">
                            <label class="font-weight-bold text-dark">{{ __('home.branch_sender') }}</label>
                            <input id="branch_show" class="form-control bg-white" readonly placeholder="---">
                        </div>
                        <div class="col-lg-4">
                            <label class="font-weight-bold text-dark">{{ __('home.employeesender') }}</label>
                            <input id="userfrom_show" class="form-control bg-white" readonly placeholder="---">
                        </div>
                    </div>
                </div>

                <!-- جدول المنتجات -->
                <div class="table-responsive">
                    <table id="example" class="table our-table">
                        <thead>
                            <tr>
                                <th style="{{ App::getLocale() == 'ar' ? 'border-top-right-radius: 6px;' : 'border-top-left-radius: 6px;' }}">#</th>
                                <th>{{ __('home.productNo') }}</th>
                                <th>{{ __('home.product') }}</th>
                                <th>{{ __('home.quantity') }}</th>
                                <th>{{ __('home.thecostProduct') }}</th>
                                <th style="{{ App::getLocale() == 'ar' ? 'border-top-left-radius: 6px;' : 'border-top-right-radius: 6px;' }}">{{ __('home.total') }}</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <tr>
                                <td colspan="6" class="text-muted py-4 font-italic">
                                    {{ App::getLocale() == 'ar' ? 'يرجى اختيار رقم الفاتورة لعرض المنتجات...' : 'Please select an invoice number to view products...' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- زر التأكيد -->
                <div id="confirmDiv" class="d-flex justify-content-center mt-4">
                    <button id="button_1" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm">
                        <i class="fas fa-check-circle {{ App::getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('home.confirm') }}
                    </button>
                </div>

                <!-- زر الطباعة -->
                <div id="printSection" class="d-none justify-content-center mt-3">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/print_Recive_items') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="sprint_invoice_number" id="sprint_invoice_number">
                        <button type="submit" class="btn btn-success px-5 py-2 font-weight-bold shadow-sm">
                            <i class="fas fa-print {{ App::getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('home.print') }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal التحذير والتأكيد -->
<div class="modal fade" id="modaldemo9" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content card-custom">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title font-weight-bold">
                    <i class="fas fa-exclamation-triangle {{ App::getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('home.alert') }}
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="text-danger font-weight-bold mb-0" style="font-size: 1.1rem;">{{ __('home.reciveproductnote') }}</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    {{ App::getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                </button>
                <button type="button" id="deleteproduct" class="btn btn-danger px-4 font-weight-bold">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal البحث عن المنتجات -->
<div class="modal fade product-selection" id="SearchProduct" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content card-custom">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold text-primary">{{ __('home.searchaboutproduct') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="font-weight-bold">{{__('home.searchaboutproduct')}}</label>
                        <input type="text" class="form-control" id="searchaboutproduct" placeholder="{{ App::getLocale() == 'ar' ? 'ابحث برقم أو اسم المنتج...' : 'Search by product code or name...' }}" onkeyup="searchaboutproductfunction()">
                    </div>
                </div>
                <div class="table-responsive" id="ajax_responce_serarchDiv"></div>
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
var currentLocale = "{{ App::getLocale() }}";
var isSubmitting = false; // متغير لمنع تكرار الضغط

function chooseProduct(code, productcode, name, cost, sale_price, location, availablequantity) {
    let rows = document.querySelectorAll('#table-body tr');
    let targetRow = rows[currentRowIndex];
    if (targetRow) {
        targetRow.querySelector('input[name="product_code[]"]').value = productcode;
    }
    $('#SearchProduct').modal('hide');
}

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
    var branchs_id = $('#branchs_id').val();

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
        }
    });
});

$('#SearchProduct').on('show.bs.modal', function() {
    searchaboutproductfunction();
});

$('select[name="reciveInvoiceNumber"]').on('change', function() {
    var id = $(this).val();
    $("#sprint_invoice_number").val(id);
    let defaultMsg = currentLocale === 'ar' ? 'يرجى اختيار رقم الفاتورة لعرض المنتجات...' : 'Please select an invoice number to view products...';
    
    if (id === '-') {
        $('#table-body').html(`<tr><td colspan="6" class="text-muted py-4 font-italic">${defaultMsg}</td></tr>`);
        $('#branch_show').val('');
        $('#userfrom_show').val('');
        return;
    }
    
    $.ajax({
        url: "{{ URL::to('/findinvoiceMovmevt') }}/" + id,
        type: "GET",
        success: function(data) {
            if(data.senderdata.barnch_name) $('#branch_show').val(data.senderdata.barnch_name);
            if(data.senderdata.user_name) $('#userfrom_show').val(data.senderdata.user_name);

            let tbody = $('#table-body');
            tbody.empty();
            let noDataMsg = currentLocale === 'ar' ? 'لا توجد منتجات لهذه الفاتورة' : 'No products found for this invoice';
            
            if(!data.orderItems || data.orderItems.length === 0) {
                tbody.html(`<tr><td colspan="6" class="text-danger py-3">${noDataMsg}</td></tr>`);
                return;
            }

            data.orderItems.forEach((p, index) => {
                let marginClass = currentLocale === 'ar' ? 'mr-2' : 'ml-2';
                tbody.append(`<tr data-index="${index}">
                    <td class="font-weight-bold text-muted">${p.count || (index + 1)}</td>
                    <td>
                        <div class="d-flex align-items-center justify-content-center">
                            <input type="hidden" name="product_name[]" value="${p.productname}">
                            <input type="text" name="product_code[]" class="input-edit product-code ${marginClass}" value="${p.product_code}" readonly>
                            <button type="button" class="btn btn-sm btn-outline-primary p-2" onclick="openProductModal(${index})" title="Search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </td>
                    <td class="font-weight-bold text-dark">${p.productname}</td>
                    <td><input type="number" name="quantity[]" class="input-edit" value="${p.quantity}"></td>
                    <td><input type="number" name="cost[]" class="input-edit" value="${p.cost}"></td>
                    <td class="font-weight-bold text-success">${p.total}</td>
                </tr>`);
            });
        }
    });
});

function openProductModal(index) {
    window.currentRowIndex = index;
    $('#SearchProduct').modal('show');
}

$("#button_1").click(function() {
    if ($('#reciveInvoiceNumber').val() === '-') {
        let warnTitle = currentLocale === 'ar' ? 'تنبيه' : 'Warning';
        let warnMsg = currentLocale === 'ar' ? 'يرجى اختيار رقم الفاتورة أولاً' : 'Please select an invoice number first';
        Swal.fire(warnTitle, warnMsg, 'warning');
    } else {
        $('#modaldemo9').modal('show');
    }
});

$("#deleteproduct").click(function() {
    if (isSubmitting) return; // منع الضغط المتكرر
    isSubmitting = true;

    let $btn = $(this);
    $btn.prop('disabled', true);

    let reciveInvoiceNumber = $("#reciveInvoiceNumber").val(); 
    let product_codes = $("input[name='product_code[]']").map(function() { return $(this).val(); }).get();
    let product_names = $("input[name='product_name[]']").map(function() { return $(this).val(); }).get();
    let quantities = $("input[name='quantity[]']").map(function() { return $(this).val(); }).get();
    let costs = $("input[name='cost[]']").map(function() { return $(this).val(); }).get();

    $.ajax({
        url: "{{ URL::to('create_reciveProduct') }}",
        type: 'post',
        data: {
            _token: $("#token_search").val(),
            reciveInvoiceNumber: reciveInvoiceNumber, 
            product_code: product_codes,
            product_name: product_names,
            quantity: quantities,
            cost: costs,
            branchId: {{$branchId}}
        },
        success: function(data) {
            $('#confirmDiv').fadeOut(200, function() {
                $(this).remove();
            });

            $('#printSection').removeClass('d-none').addClass('d-flex').fadeIn(500);
            $('#modaldemo9').modal('hide');
            let successMsg = currentLocale === 'ar' ? 'تمت العملية بنجاح' : 'Operation completed successfully';
            Swal.fire(successMsg, '', 'success');
        },
        error: function(xhr) {
            isSubmitting = false;
            $btn.prop('disabled', false);
            let errorMsg = currentLocale === 'ar' ? 'حدث خطأ ما، يرجى المحاولة لاحقاً' : 'An error occurred, please try again later';
            Swal.fire('Error', errorMsg, 'error');
        }
    });
});
</script>
@endsection