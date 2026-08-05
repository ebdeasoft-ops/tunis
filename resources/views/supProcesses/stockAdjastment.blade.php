@extends('layouts.master')
@section('css')
<style>
    form {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }

    .radio-label {
        display: flex;
        cursor: pointer;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        margin-bottom: 0.375em;
        /* Accessible outline */
        /* Remove comment to use */
        /*
  	&:focus-within {
  			outline: .125em solid $primary-color;
  	}
  */
    }

    .radio-label input {
        position: absolute;
        left: -9999px;
    }

    .radio-label input:checked+span {
        background-color: #d6d6e5;
    }

    .radio-label input:checked+span:before {
        box-shadow: inset 0 0 0 0.4375em #00005c;
    }

    .radio-label span {
        display: flex;
        align-items: center;
        padding: 0.375em 0.75em 0.375em 0.375em;
        border-radius: 99em;
        transition: 0.25s ease;
    }

    .radio-label span:hover {
        background-color: #d6d6e5;
    }

    .radio-label span:before {
        display: flex;
        flex-shrink: 0;
        content: "";
        background-color: #fff;
        width: 1.5em;
        height: 1.5em;
        border-radius: 50%;
        margin-right: 0.375em;
        transition: 0.25s ease;
        box-shadow: inset 0 0 0 0.125em #00005c;
    }
</style>
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
{{ __('supprocesses.stockadjustment') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('supprocesses.stockadjustment') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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
    @if (session()->has('productupdated'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>

        <strong>{{ session()->get('productupdated') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">



            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">

            <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="row align-items-end">

            <!-- اختيار الفرع -->
            <div class="col-md-3">
                <label class="form-label font-weight-bold">{{ __('users.branch') }}</label>
                <select class="form-control select2" name="branchs_id" id="branchs_id">
                    <option value="{{ Auth()->user()->branch->id }}">{{ Auth()->user()->branch->name }}</option>
                        @foreach (App\Models\branchs::where('id', '!=', Auth()->user()->branch->id)->get() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                </select>
            </div>

            <!-- زر اختيار المنتج -->
            <div class="col-md-3">
                <button type="button" class="btn btn-warning w-100 shadow-sm" data-toggle="modal" data-target="#SearchProduct">
                    <i class="las la-search"></i> {{ __('home.chooose product') }}
                </button>
            </div>

            <!-- أزرار الإكسل -->
            <div class="col-md-6 text-md-right mt-3 mt-md-0 gap-5">
                <div class="d-flex justify-content-md-end">
                    <a href="{{ route('stock.download_template') }}" class="btn btn-outline-success shadow-sm">
                        <i class="fas fa-file-excel"></i> {{ __('home.download_template') }}
                    </a>

                    <div class="upload-btn-wrapper">
                        <input type="file" id="excel_file" style="display:none;" accept=".xlsx, .xls">
                        <button type="button" class="btn btn-primary shadow-sm" onclick="document.getElementById('excel_file').click();">
                            <i class="fas fa-upload"></i> {{ __('home.upload_excel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



                <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                    action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/stockAdjastment') }}"
                    method="post">
                     {{ csrf_field() }}

                        <div class="card pt-4">
                            <div class="card-body pt-3">
                                <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                                <div class="table-responsive mg-t-20">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr class="table-info">
                                                <th>{{ __('home.productNo') }}</th>
                                                <th>{{ __('home.productname') }}</th>
                                                <th>{{ __('home.adjustment_type') }}</th>
                                                <th>{{ __('supprocesses.current_quantity') }}</th>
                                                <th>{{ __('home.adjusted_quantity') }}</th>
                                                <th>{{ __('home.notesClient') }}</th>
                                                <th>{{ __('home.delete') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="adjustment-table-body">
                                            </tbody>
                                    </table>
                                </div>

                                <br>

                                <div class="d-flex justify-content-center">
                                    <button style="background-color: #419BB2; font-size:17px" type="submit" class="btn btn-success">
                                        {{ __('roles.update') }}
                                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path fill="white" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>

</div>
        </div>
    </div>
</div>

@if (isset($itemsRequest))
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">{{ __('home.movement_details') }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive hoverable-table">
                  <table class="table table-hover" id="example1" data-page-length='50' style="text-align: center;">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                <th class="border-bottom-0">{{ __('home.product') }}</th>
                                <th class="border-bottom-0">{{ __('home.adjustment_type') }}</th>
                                <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                            </tr>
                        </thead>
                       <tbody id="adjustment-table-body">
                                @foreach ($itemsRequest as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->productData->Product_Code }}</td>
                                    <td>{{ $product->productData->product_name }}</td>
                                    <td>
                                        <select class="form-control type-selector">
                                            <option value="inc">{{ __('home.increasequantity') }} (+)</option>
                                            <option value="dec">{{ __('home.decreasequentity') }} (-)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="productNo[]" value="{{ $product->product_id }}">

                                        <input type="number" name="productincrease[]" class="form-control inc-input" value="0">
                                        <input type="number" name="productdecrease[]" class="form-control dec-input" value="0" style="display:none;">
                                    </td>
                                    <td>
                                        <input type="text" name="note[]" class="form-control">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <a class="btn btn-success" href="{{ url('/printOrderPriceFromSupplier/' . $itemsRequest[0]->order_id) }}">
                            {{ __('home.print') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<br>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!--search  -->

<div class="modal fade" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
    <div class="modal-dialog modal-xl product-selection" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="card-body">

                    <div class="col-lg-4 mg-t-20 mg-lg-t-0">
                        <label for="inputName" style="font-weight: bold" class="control-label parent-label"> {{__('home.searchaboutproduct')}} </label>
                        <input dir="ltr" type="text" class="form-control parent-input" placeholder="{{ __('home.Search By Name or Product Number') }}" id="searchaboutproduct" name="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                    </div>
                    <br>
                    <div class="table-responsive" id="ajax_responce_serarchDiv">
                        <table class="table text-md-nowrap text-center our-table" id="SearchProductTable" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                            <col style="width:5%">
                            <col style="width:14%">
                            <col style="width:28%">
                            <col style="width:10%">
                            <col style="width:10%">
                            <col style="width:13%">
                            <col style="width:10%">
                            <col style="width:10%">

                            <thead>
                                <tr>
                                    <th style="font-size: 15px" class="border-bottom-0">#</th>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.productNo')}} </th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.product')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.branch')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0" style="text-align:center">{{__('home.productlocation')}}</th>

                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.quantity')}}</th>
                                    <th style="font-size: 13px" class="border-bottom-0">{{__('home.purchaseproductwithouttax')}}</th>
                                    <th style="font-size: 13px" class="border-bottom-0">{{__('home.sellingproduct without tax')}}</th>
                                    <th style="font-size: 15px" class="border-bottom-0">{{__('home.Add')}}</th>



                                </tr>
                            </thead>
                            <tbody class="">
                                <?php $i = 0;
                                $data = 'm'; ?>

                                <?php $i++ ?>

                                <tr>
                                    <td id="tableData" dir=ltr>-</td>
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
                        <div>

                        </div>
                        <div class="row d-flex justify-content-between pagination-row">



                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    </div>

                </div>


            </div>
        </div>

    </div>

    <!-- main-content closed -->
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

// تجهيز مصفوفة الترجمات لاستخدامها داخل كود JS
const translations = {
    alreadyAdded: "{{ __('home.already_added') ?? 'هذا المنتج مضاف بالفعل في الجدول' }}",
    increase: "{{ __('home.increasequantity') ?? 'زيادة' }} (+)",
    decrease: "{{ __('home.decreasequentity') ?? 'نقصان' }} (-)",
    quantityPlaceholder: "{{ __('home.quantity') ?? 'الكمية' }}",
    notePlaceholder: "{{ __('home.note_placeholder') ?? 'ملاحظة' }}",
    delete: "{{ __('home.delete') ?? 'حذف' }}"
};
$('#excel_file').on('change', function(e) {
    let selectedBranch = $('#branchs_id').val();
    let file = this.files[0];

    if (!selectedBranch) {
        Swal.fire('تنبيه', 'يرجى اختيار الفرع أولاً', 'warning');
        $(this).val('');
        return;
    }

    // قراءة الملف للتحقق من عدد الصفوف قبل الإرسال
    let reader = new FileReader();
    reader.onload = function(e) {
        let data = new Uint8Array(e.target.result);
        let workbook = XLSX.read(data, {type: 'array'});
        let jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]]);

        // التحقق: هل يتجاوز 100 صف؟
        if (jsonData.length > 100) {
            Swal.fire('خطأ', 'عذراً، الملف يحتوي على ' + jsonData.length + ' صف. الحد الأقصى هو 100 صف فقط.', 'error');
            $('#excel_file').val('');
            return;
        }

        // إذا كان صحيحاً، نبدأ عملية الرفع عبر AJAX
        let formData = new FormData();
        formData.append('excel_file', file);
        formData.append('branch_id', selectedBranch);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ route('stock.import_ajax') }}",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                Swal.showLoading();
            },
            success: function(response) {
                if (response.success) {
                    renderExcelProducts(response.data);
                    Swal.fire('تم!', 'تم رفع المنتجات وتحديث الجدول بنجاح', 'success');
                } else {
                    Swal.fire('خطأ!', response.message || 'حدث خطأ ما', 'error');
                }
            },
            error: function() {
                Swal.fire('خطأ!', 'حدثت مشكلة أثناء معالجة ملف الإكسيل', 'error');
            }
        });
    };
    reader.readAsArrayBuffer(file);
});



function renderExcelProducts(productsList) {
    let table = document.getElementById('adjustment-table-body');

    productsList.forEach((product) => {
        if ($("#row_" + product['product_id']).length > 0) return;

        let available = parseFloat(product['available_quantity']);
        let inventory = parseFloat(product['inventory_quantity']);

        // تحديد النوع بناءً على المقارنة
        // إذا كان الجرد أكبر من المخزن = زيادة
        // إذا كان الجرد أقل من المخزن = نقصان
        let type = (inventory >= available) ? 'inc' : 'dec';

        // حساب الفرق للكمية التي سيتم إدخالها في حقل الكمية (القيمة المطلقة للفرق)
        let diffAmount = Math.abs(inventory - available);

        let newRow = `
            <tr id="row_${product['product_id']}">
                <td>
                    ${product['Product_Code']}
                    <input type="hidden" name="productNo[]" value="${product['product_id']}">
                </td>
                <td>${product['product_name']}</td>
                <td>
                  <select class="form-control row-type-selector" style="width: 110px;">
                    <option value="inc" ${type === 'inc' ? 'selected' : ''}>${translations.increase}</option>
                    <option value="dec" ${type === 'dec' ? 'selected' : ''}>${translations.decrease}</option>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control text-center" value="${available}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control amount-input" value="${diffAmount}" required min="0">
                    <input type="hidden" name="productincrease[]" class="inc-val" value="${type === 'inc' ? diffAmount : 0}">
                    <input type="hidden" name="productdecrease[]" class="dec-val" value="${type === 'dec' ? diffAmount : 0}">
                </td>
                <td>
                    <input type="text" name="note[]" value="تعديل  جرد  update stock" class="form-control" placeholder="ملاحظة">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove()">
                        <i class="fa fa-trash"></i> ${translations.delete}
                    </button>
                </td>
            </tr>
        `;

        table.insertAdjacentHTML("beforeend", newRow);
    });
}
// دالة مساعدة لتحديث القيم المخفية (زيادة أو نقصان)
function updateHiddenValues(row) {
    let type = row.find('.row-type-selector').val();
    let amount = row.find('.amount-input').val();

    if (type === 'inc') {
        row.find('.inc-val').val(amount);
        row.find('.dec-val').val(0);
    } else {
        row.find('.inc-val').val(0);
        row.find('.dec-val').val(amount);
    }
}

// تفعيل التحديث عند تغيير النوع أو الكمية يدوياً
$(document).on('change', '.row-type-selector, .amount-input', function() {
    updateHiddenValues($(this).closest('tr'));
});





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
    function convertToNewQuantity() {
        var input = document.getElementById("newquentity");
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
    document.addEventListener('keydown', (e) => {
        if (e.key === "F9") {
            $('#SearchProduct').modal().show();

        }
    })
    document.addEventListener('keydown', (e) => {
        searchtext = $('#product_code').val();

        if (e.key === "Enter") {
            searchtext = $('#product_code').val();
            $('#searchaboutproduct').val(searchtext);
            // document.getElementById("searchaboutproduct").focus();
            $('#SearchProduct').modal().show();



        }
    })
    $('#SearchProduct').on('shown.bs.modal', function() {
        $('#searchaboutproduct').focus();
    })
</script>
<script>
    function searchaboutproductfunction() {
        searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();


        console.log(branchs_id)
        jQuery.ajax({
            url:  "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "locale" : "{{ app()->getLocale() }}", // ✅ صح
                    "branchs_id": branchs_id,
                },
                  success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });

    }
    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
           searchtext = $('#searchaboutproduct').val();
        branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");

        jQuery.ajax({
            url: url,
                  type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                    "branchs_id": branchs_id,
                },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function() {

            }
        });
    });
    $('#SearchProduct').on('show.bs.modal', function(event) {
           searchtext = '1';
        branchs_id = $('#branchs_id').val();
        var token_search = $("#token_search").val();
        var url = $(this).attr("href");

        jQuery.ajax({
            url:  "{{ URL::to('ChooseProductpaginatenewupdate')}}",
                  type: 'post',
                cache: false,
                dataType: 'html',
                data: {
                    "_token": token_search,
                    "searchtext": searchtext,
                     "locale" : "{{ app()->getLocale() }}", // ✅ صح
                    "branchs_id": branchs_id,
                },
            success: function(data) {
                $("#ajax_responce_serarchDiv").html(data);
            },
            error: function(r) {
            }
        });
    })
</script>

<script>



    // لاحظ هنا زودنا عدد المتغيرات عشان تطابق الـ 11 اللي مبعوثين من الـ PHP بالظبط
       function chooseProduct(code, name, price, sale_price, productcode, availablequantity, productcode2) {




        // 1. إغلاق المودال
        $('#SearchProduct').modal('hide');

        // 2. منع التكرار باستخدام الـ id (المتغير الأول)
        if ($("#row_" + code).length > 0) {
            alert("هذا المنتج مضاف بالفعل في الجدول");
            return;
        }

        // 3. بناء السطر بالبيانات الصحيحة
        // رقم المنتج = product_code (المتغير رقم 9)
        // الكمية الحالية = numberofpice (المتغير رقم 8)
        var newRow = `
            <tr id="row_${code}">
                <td>
                    ${productcode}
                    <input type="hidden" name="productNo[]" value="${code}">
                </td>
                <td>${name}</td>
                <td>
                  <select class="form-control row-type-selector" style="width: 110px;">
                    <option value="inc">${translations.increase}</option>
                    <option value="dec">${translations.decrease}</option>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control text-center" value="${availablequantity}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control amount-input" placeholder="${translations.quantityPlaceholder}" required min="1">

                    <input type="hidden" name="productincrease[]" class="inc-val" value="0">
                    <input type="hidden" name="productdecrease[]" class="dec-val" value="0">
                </td>
                <td>
                    <input type="text" name="note[]" class="form-control" placeholder="${translations.notePlaceholder}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove()">
                        <i class="fa fa-trash"></i> ${translations.delete}
                    </button>
                </td>
            </tr>
        `;

        $('#adjustment-table-body').append(newRow);
    }

    // السكريبت اللي بيوزع الأرقام للكنترولر (مهم جداً يفضل موجود)
    $(document).on('change keyup', '.row-type-selector, .amount-input', function() {
        let row = $(this).closest('tr');
        let type = row.find('.row-type-selector').val();
        let amount = row.find('.amount-input').val() || 0;

        if (type === 'inc') {
            row.find('.inc-val').val(amount);
            row.find('.dec-val').val(0);
        } else {
            row.find('.inc-val').val(0);
            row.find('.dec-val').val(amount);
        }
    });
</script>



<script>


    $('select[name="productNo"]').on('change', function() {
        console.log('AJAX load   work 0000');

        var selectclientid = $(this).val();
        if (selectclientid) {
            console.log('AJAX load   work');
            $.ajax({
                url: "{{ URL::to('getproduct') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success123");
                    console.log(data);
                    console.log("{{ URL::to('getsupllier') }}/" + selectclientid);
                    $('#productnameshow').val(data['product_name']);
                    $('#quentity').val(data['numberofpice']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
    $('select[name="productname"]').on('change', function() {
        console.log('AJAX load   work 0000');

        var selectclientid = $(this).val();
        if (selectclientid) {
            console.log('AJAX load   work');
            $.ajax({
                url: "{{ URL::to('getproduct') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success123");
                    console.log(data);
                    console.log("{{ URL::to('getsupllier') }}/" + selectclientid);
                    $('#productnameshow').val(data['product_name']);
                    $('#quentity').val(data['numberofpice']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
</script>




<script>
    $(document).ready(function() {

        $('#productdecrease').val(0);
        $('#productincrease').val(0);

            $(function() {
                var timeout = 4000; // in miliseconds (3*1000)
                $('.alert').delay(timeout).fadeOut(500);
            });


        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });
        $('#productdecrease').hide();

        $('#productdecreaselabel').hide();

        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {

                $('#productincreaselabel').show();
                $('#productincrease').show();
                $('#productdecreaselabel').hide();
                $('#productdecrease').hide();
                $('#productdecrease').val(0);
                $('#lnWrapper').show();

            } else {

                $('#productdecreaselabel').show();
                $('#productdecrease').show();
                $('#productincrease').hide();
                $('#productincrease').val(0);
                $('#productincreaselabel').hide();
                $('#lnWrapper').hide();

            }
        });
    });
</script>



@endsection
