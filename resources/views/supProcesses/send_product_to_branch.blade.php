@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
/* تنسيق الجدول الاحترافي */
.table-professional {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 15px;
}

.table-professional thead th {
    background-color: #f8f9fe;
    color: #495057;
    font-weight: 600;
    padding: 15px;
    border-bottom: 2px solid #eef2f7;
    text-align: center;
}

.table-professional tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
    text-align: center;
    color: #6c757d;
}

.table-professional tbody tr:hover {
    background-color: #fcfcfc;
}

.btn-remove {
    color: #dc3545;
    background: #fff5f5;
    border: 1px solid #ffc9c9;
    border-radius: 6px;
    padding: 5px 10px;
    cursor: pointer;
}

.btn-remove:hover {
    background: #dc3545;
    color: #fff;
}
</style>
@endsection

@section('title') {{__('home.send_product_from_other_branch_other')}} @stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0">{{__('home.send_product_from_other_branch_other')}}</h4>
    </div>
</div>
@endsection

@section('content')
{{-- عرض أخطاء التحقق إن وجدت --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session()->has('productupdatedlocation'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('productupdatedlocation') }}</strong>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <form id="movementForm"
                    action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/create_sendProduct') }}"
                    method="POST">
                    {{ csrf_field() }}

                    {{-- إذا كنا نقوم بتعديل مسودة مسترجعة، نرسل الـ ID الخاص بها --}}
                    @if(isset($draftMovement))
                    <input type="hidden" name="draft_id" value="{{ $draftMovement->id }}">
                    @endif

                    {{-- حقل مخفي لتحديد حالة الإرسال (مسودة draft أو مكتملة completed) --}}
                    <input type="hidden" name="status" id="movement_status" value="completed">

                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <label>{{ __('home.choosebranch_reciver') }}</label>
                            <select name="branch" id="branchs_id" onchange="getEmployeesByBranch()"
                                class="form-control">
                                <option value="">{{ __('home.Select_Receiving_Branch') }}</option>
                                @foreach (App\Models\branchs::get() as $section)
                                @if($section->id != $branchId)
                                <option value="{{ $section->id }}"
                                    {{ isset($draftMovement) && $draftMovement->branch_to == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label for="inputName"
                                class="control-label parent-label">{{ __('home.chooseemployeereciver') }}</label>
                            <select name="employeereciver" id="employeereciver" class="form-control parent-input">
                                <option value="">{{ __('home.choose_employee_placeholder') ?? 'اختر الموظف المستلم' }}
                                </option>
                                {{-- إذا كانت مسودة قادمة، نضع الموظف المخزن كخيار افتراضي لحين تحديثه بالـ Ajax --}}
                                @if(isset($draftMovement))
                                @php $savedUser = \App\Models\User::find($draftMovement->user_to); @endphp
                                @if($savedUser)
                                <option value="{{ $savedUser->id }}" selected>{{ $savedUser->name }}</option>
                                @endif
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row my-4 justify-content-center">
                        <a class="btn btn-warning" data-toggle="modal" href="#SearchProduct">
                            <i class="las la-search"></i> {{ __('home.chooose product') }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table-professional">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('home.product') }}</th>
                                    <th>{{ __('home.quantity') }}</th>
                                    <th>{{ __('home.thecostProduct') }}</th>
                                    <th>{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody id="products_body">
                                {{-- استرجاع وعرض منتجات المسودة تلقائياً إن وجدت --}}
                                @if(isset($draftItems) && count($draftItems) > 0)
                                @foreach($draftItems as $item)
                                <tr>
                                    <td>#</td>
                                    <td>
                                        {{ $item->product->product_name ?? $item->product->name ?? 'منتج غير معروف' }}
                                        <input type="hidden" name="products[]" value="{{ $item->product_id }}">
                                    </td>
                                    <td>
                                        <input type="number" min="0.01" step="any" name="quantities[]"
                                            class="form-control" value="{{ $item->quantity }}"
                                            style="width:100px; margin:auto;">
                                    </td>
                                    <td>
                                        {{ $item->cost_per_each_withoud_tax }}
                                        <input type="hidden" name="prices[]"
                                            value="{{ $item->cost_per_each_withoud_tax }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn-remove remove-row"><i
                                                class="las la-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- قسم أزرار التحكم بالحفظ والحفظ كمسودة --}}
                    {{-- قسم أزرار التحكم بالحفظ والطباعة --}}
                    <div class="d-flex justify-content-center align-items-center mt-4" style="gap: 10px;">

                        {{-- زر الحفظ كمسودة --}}
                        <button type="button" id="draftBtn" class="btn btn-secondary px-4"
                            onclick="submitMovementForm('draft')">
                            <i class="las la-file-alt"></i> {{ __('home.Save_As_Draft') }}
                        </button>

                        {{-- زر الحفظ النهائي --}}
                        <button type="button" id="submitBtn" class="btn btn-primary px-5"
                            onclick="submitMovementForm('completed')">
                            <i class="las la-save"></i> {{ __('home.save_data') }}
                        </button>


                    </div>

            </div>
            </form>

                        <br>

        </div>
               <br>

<center>

          {{-- زر الطباعة (يظهر فقط إذا كان هناك رقم فاتورة $data) --}}
                        @if(session()->has('saved_movement_id') || (isset($data) && !empty($data)))
                        @php
                        // نجلب القيمة من السيشن، وإذا لم تكن موجودة نجلبها من المتغير $data
                        $invoiceId = session()->get('saved_movement_id') ?? $data;
                        @endphp

                        <form
                            action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() .'/print_Transfer_items') }}"
                            method="POST" target="_blank" class="m-0">
                            {{ csrf_field() }}

                            <input type="number" name="sprint_invoice_number" value="{{ $invoiceId }}" hidden>

                            <button type="submit" class="btn btn-success px-4"
                                style="background-color: #419BB2; border-color: #419BB2;">
                                <i class="las la-print"></i> {{ __('home.print') }}
                            </button>
                        </form>
                        @endif
</center>
    </div>
</div>
</div>

<div class="modal fade" id="SearchProduct" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" class="form-control mb-3" placeholder="{{ __('home.searchaboutproduct') }}"
                    id="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                <div id="ajax_responce_serarchDiv"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function getEmployeesByBranch() {
    let branchId = $('#branchs_id').val();
    let employeeSelect = $('#employeereciver');
    let currentSavedUser = "{{ isset($draftMovement) ? $draftMovement->user_to : '' }}";

    // نصوص الترجمة الخاصة بالـ AJAX
    let txtLoading = "{{ __('home.Loading_Employees') }}";
    let txtSelect = "{{ __('home.Select_Receiving_Employee') }}";
    let txtError = "{{ __('home.Error_Loading_Data') }}";
    let txtSelectBranchFirst = "{{ __('home.Select_Branch_First') }}";

    employeeSelect.empty();
    employeeSelect.append('<option value="">' + txtLoading + '</option>');

    if (branchId) {
        $.ajax({
            url: "{{ URL::to('get-branch-employees') }}/" + branchId,
            type: "GET",
            dataType: "json",
            success: function(data) {
                employeeSelect.empty();
                employeeSelect.append('<option value="">' + txtSelect + '</option>');

                $.each(data, function(key, value) {
                    let isSelected = (currentSavedUser == value.id) ? 'selected' : '';
                    employeeSelect.append('<option value="' + value.id + '" ' + isSelected + '>' +
                        value.name + '</option>');
                });
            },
            error: function() {
                employeeSelect.empty();
                employeeSelect.append('<option value="">' + txtError + '</option>');
            }
        });
    } else {
        employeeSelect.empty();
        employeeSelect.append('<option value="">' + txtSelectBranchFirst + '</option>');
    }
}

$(document).ready(function() {
    // جلب الـ draft_id من رابط المتصفح العلوي إن وجد
    let urlParams = new URLSearchParams(window.location.search);
    let draftId = urlParams.get('draft_id');

    // إذا كان المستخدم قادماً من زر "استكمال وتعديل المسودة"
    if (draftId) {
        // التأكد من وضع الـ id في الحقل المخفي الخاص بالفورم لتأمين عملية التحديث
        if ($('input[name="draft_id"]').length === 0) {
            $('#movementForm').prepend('<input type="hidden" name="draft_id" value="' + draftId + '">');
        } else {
            $('input[name="draft_id"]').val(draftId);
        }

        // ✨ السحر هنا: تنظيف شريط عنوان المتصفح وحذف "?draft_id=..." بدون إعادة تحميل الصفحة
        let cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({
            path: cleanUrl
        }, '', cleanUrl);
    }

    // تشغيل جلب الموظفين تلقائياً للفرع المحدد
    if ($('#branchs_id').val()) {
        getEmployeesByBranch();
    }
    if ($('#branchs_id').val()) {
        getEmployeesByBranch();
    }
});

function submitMovementForm(status) {
    // نصوص الترجمة
    let txtSelectProduct = "{{ __('home.Please_Select_At_Least_One_Product') }}";
    let txtSavingDraft = "{{ __('home.Saving_Draft') }}";
    let txtSavingFinal = "{{ __('home.Saving_Final') }}";

    if ($('#products_body tr').length === 0) {
        alert(txtSelectProduct);
        return;
    }

    $('#movement_status').val(status);

    $('#submitBtn').prop('disabled', true);
    $('#draftBtn').prop('disabled', true);

    if (status === 'draft') {
        $('#draftBtn').html('<i class="fa fa-spinner fa-spin"></i> ' + txtSavingDraft);
    } else {
        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> ' + txtSavingFinal);
    }

    $('#movementForm').submit();
}

$('#SearchProduct').on('show.bs.modal', function() {
    $('#searchaboutproduct').val('');
    searchaboutproductfunction();
});

function searchaboutproductfunction() {
    let searchtext = $('#searchaboutproduct').val();
    let current_user_branch = "{{ $branchId }}";
    if (searchtext.trim() == '') {
        searchtext = "1";
    }

    $.ajax({
        url: "{{ URL::to('searchChooseProductpaginatenew') }}/" + searchtext + "/" + current_user_branch,
        type: 'get',
        success: function(data) {
            $("#ajax_responce_serarchDiv").html(data);
        }
    });
}

function chooseProduct(id, name, sale_price) {
    if ($(`input[value="${id}"][name="products[]"]`).length > 0) {
        alert('المنتج مضاف بالفعل في الجدول');
        return;
    }

    let row = `<tr>
            <td>#</td>
            <td>${name}<input type="hidden" name="products[]" value="${id}"></td>
            <td><input type="number" min="0.01" step="any" name="quantities[]" class="form-control" value="1" style="width:100px; margin:auto;"></td>
            <td>${sale_price}<input type="hidden" name="prices[]" value="${sale_price}"></td>
            <td><button type="button" class="btn-remove remove-row"><i class="las la-trash"></i></button></td>
        </tr>`;
    $('#products_body').append(row);
    $('#SearchProduct').modal('hide');
}

$(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
});
</script>
@endsection
