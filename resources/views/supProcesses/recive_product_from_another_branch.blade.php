@extends('layouts.master')
@section('css')
<style>
/* إخفاء زر الطباعة إجبارياً عند تحميل الصفحة */
#printSection {
    display: none !important;
}

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
@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0">{{__('home.recive_product_from_other_branch_other')}}</h4>
    </div>
</div>
@endsection
@section('title') {{__('home.recive_product_from_other_branch_other')}} @stop

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
                            Auth::user()->branchs_id)->where('reciveInvoiceNumber', 1)->get() as $item)
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

                <div id="printSection" class="d-flex justify-content-center mt-3">
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

<div class="modal fade product-selection" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">

            </div>
            <div class="modal-body">


                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                            <label for="inputName" style="font-weight: bold" class="control-label parent-label">
                                {{__('home.searchaboutproduct')}} </label>
                            <input autocomplete="off" dir="ltr" type="text" autofocus class="form-control parent-input"
                                placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct"
                                name="searchaboutproduct" onkeyup="searchaboutproductfunction()" autofocus>
                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label">{{ __('home.groups') }}</label>
                            <select style="width:100%!important" name="product_group" id="product_group"
                                class="form-control select2">
                                <!--placeholder-->
                                @foreach (App\Models\products_group::get() as $section)
                                <option value="{{ $section->id }}"> {{ $section->group_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="ajax_responce_serarchDiv">
                        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%"
                            style="border: 2px solid rgba(0,0,0,.3);">
                            <col style="width:5%">
                            <col style="width:14%">
                            <col style="width:28%">
                            <col style="width:10%">
                            <col style="width:18%">
                            <col style="width:15%">
                            <col style="width:10%">

                            <thead>
                                <tr>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                        {{__('home.product')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                        {{__('home.branch')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">
                                        {{__('home.productlocation')}}
                                    </th>

                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}
                                    </th>
                                    <th style="font-size: 13px" class="border-bottom-0">
                                        {{__('home.purchaseproductwithouttax')}}
                                    </th>
                                    <th style="font-size: 13px" class="border-bottom-0">
                                        {{__('home.sellingproduct without tax')}}
                                    </th>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



                                </tr>
                            </thead>


                            <tbody class="">
                                <?php $i = 0;
    $data = 'm'; ?>

                                <?php $i++ ?>

                                <tr>
                                    <td id="tableData" dir=ltr>-</td>
                                    <td id="tableData" data-target="product_name">-</td>
                                    <td id="tableData" data-target="product_name">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData" data-target="numberofpice">-</td>
                                    <td id="tableData">- </td>
                                </tr>

                            </tbody>
                        </table>
                        <td id="tableData">- </td>
                        </tr>

                        </tbody>
                        </table>
                        <div>

                        </div>



                    </div>

                </div>

                <div class="modal-footer">
                    {{-- <button id="added_product" name="added_product" id="added_product"
                            class="btn btn-primary">{{__('home.confirm')}}</button>
                    --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                </div>

            </div>


        </div>
    </div>

</div>

<input hidden=true class="form-control" id="branchs_id" name="branchs_id" value="{{Auth()->user()->branchs_id}}">
                                                                <input type="hidden" id="token_search" value="{{ csrf_token() }}">

@endsection

@section('js')


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var currentRowIndex = 0; // متغير عالمي لحفظ رقم السطر


function chooseProduct(code, productcode, name, cost, sale_price, location, availablequantity) {
    // جلب السطر بناءً على رقم الـ index المحفوظ
    let rows = document.querySelectorAll('#table-body tr');
    let targetRow = rows[currentRowIndex];

    if (targetRow) {
        // البحث عن الـ input داخل هذا السطر وتحديث قيمته
        let productInput = targetRow.querySelector('input[name="product_code[]"]');
        if (productInput) {
            productInput.value = productcode;
        }
    }

    // إغلاق المودال بعد الاختيار
    $('#productModal').modal('hide');
}



    function searchaboutproductfunction() {
    searchtext = $('#searchaboutproduct').val();
    branchs_id = $('#branchs_id').val();
    var token_search = $("#token_search").val();

    jQuery.ajax({
        url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            "_token": token_search,
            "searchtext": searchtext,
            "branchs_id": branchs_id,
            "currentrow": window.currentRow,
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },

    });

}

$('#SearchProduct').on('show.bs.modal', function(event) {
    searchtext = ' ';
    branchs_id = $('#branchs_id').val();
    var token_search = $("#token_search").val();

    jQuery.ajax({
        url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            "_token": token_search,
            "searchtext": searchtext,
            "branchs_id": branchs_id,
            "currentrow": window.currentRow,
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },

    });

});
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


// 1. جلب البيانات
$('select[name="reciveInvoiceNumber"]').on('change', function() {
    var id = $(this).val();
    if (id === '-') return;

    $.ajax({
        url: "{{ URL::to('/findinvoiceMovmevt') }}/" + id,
        type: "GET",
        success: function(data) {
            // تعريف الـ tbody
            let tbody = $('#table-body'); 
            // مسح البيانات القديمة تماماً
            tbody.empty(); 

            // حلقة التكرار لإنشاء الصفوف
            data.orderItems.forEach((p, index) => { 
                // تم وضع الـ append مرة واحدة فقط وبشكل صحيح
                tbody.append(`
                    <tr data-index="${index}">
                        <td>${p.count}</td>
                        <td>
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
                    </tr>
                `);
            });
        },
        error: function(r) {
            console.log(r);
        }
    });
});


function openProductModal(index) {
window.currentRowIndex = index;
    $('#searchaboutproduct').focus();
   
$('#SearchProduct').modal('show');
}

function searchaboutproductfunction() {
    searchtext = $('#searchaboutproduct').val();
    branchs_id = $('#branchs_id').val();
    var token_search = $("#token_search").val();

    jQuery.ajax({
        url: "{{ URL::to('searchChooseProductpaginatenewSaleBypost')}}",
        type: 'post',
        cache: false,
        dataType: 'html',
        data: {
            "_token": token_search,
            "searchtext": searchtext,
            "branchs_id": branchs_id,
            "currentrow": window.currentRow,
        },
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        },

    });

}


// 2. تأكيد العملية
$("#button_1").click(function() {
    if ($('#reciveInvoiceNumber').val() === '-') {
        alert("يرجى اختيار رقم الفاتورة");
    } else {
        $('#modaldemo9').modal('show');
    }
});
$("#deleteproduct").click(function() {
    // 1. تجميع بيانات الجدول في مصفوفة
    let product_codes = $("input[name='product_code[]']").map(function(){return $(this).val();}).get();
    let product_names = $("input[name='product_name[]']").map(function(){return $(this).val();}).get(); // إضافة جمع الأسماء
    let quantities    = $("input[name='quantity[]']").map(function(){return $(this).val();}).get();
    let costs         = $("input[name='cost[]']").map(function(){return $(this).val();}).get();

    $.ajax({
        url: "{{ URL::to('create_reciveProduct') }}",
        type: 'post',
        data: {
            _token: $("#token_search").val(),
            invoiceId: $('#invoiceId').val(),
            // إرسال البيانات كـ Arrays لتصل للكنترولر
            product_code: product_codes,
            product_name: product_names, // إرسال المصفوفة الجديدة
            quantity: quantities,
            cost: costs
        },
        success: function(data) {
            // 2. تحديث رقم الفاتورة
            $('#sprint_invoice_number').val(data.invoice_number);
            
            // 3. إخفاء زر التأكيد وإظهار زر الطباعة
            $('#confirmDiv').fadeOut(200, function() {
                $(this).remove(); 
            });

            $('#printSection').css({
                'display': 'flex',
                'visibility': 'visible',
                'opacity': '1'
            }).fadeIn(500);
            
            // 4. إغلاق المودال
            $('#modaldemo9').modal('hide');
            
            Swal.fire('تمت العملية بنجاح', '', 'success');
        },
        error: function(r) {
            console.log(r);
            Swal.fire('خطأ!', 'حدثت مشكلة أثناء حفظ البيانات', 'error');
        }
    });
});</script>
@endsection