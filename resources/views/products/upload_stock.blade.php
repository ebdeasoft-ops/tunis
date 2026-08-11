@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
<!-- SweetAlert2 css -->
<link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">

<style>
/* تنسيق كارد الخطوات الاحترافي */
.step-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.step-header {
    background-color: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
    transition: background-color 0.3s ease;
}

.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background-color: #4f46e5;
    color: #fff;
    border-radius: 50%;
    font-weight: bold;
    margin-left: 10px;
    transition: all 0.3s ease;
}

/* حالة الإنجاز (تتحول إلى اللون الأخضر وعلامة صح) */
.step-card.completed .step-header {
    background-color: #f0fdf4;
    border-bottom-color: #d1fae5;
}

.step-card.completed .step-number {
    background-color: #10b981;
}

.step-card.completed .step-title {
    color: #065f46;
}
</style>
@endsection

@section('title')
{{ __('home.upload_stock') }}
@endsection

@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.upload_stock') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

@if (session()->has('notfountreturnproduct'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('notfountreturnproduct') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- النموذج الرئيسي مقسم بخطوات -->
<form id="upload-form" autocomplete="off" enctype="multipart/form-data">
    {{ csrf_field() }}

    <!-- الخطوة الأولى: تحديد الفرع والبيانات الأساسية -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card step-card" id="step-1-card">
                <div class="card-header step-header d-flex align-items-center">
                    <span class="step-number" id="step-1-num">1</span>
                    <h5 class="card-title mb-0 font-weight-bold step-title">تحديد الفرع والبيانات الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- الفرع -->
                        <div class="col-lg-4" id="type">
                            <label class="parent-label font-weight-bold"> {{ __('users.branch') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-control parent-input" name="branch" id="branch-select" required>
                                <option value="" disabled selected>اختر الفرع...</option>
                            
                                @foreach (App\Models\branchs::get() as $branch)
                                @if(Auth()->user()->branchs_id == 1 || Auth()->user()->branchs_id == $branch->id)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الخطوة الثانية: تحميل القالب -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card step-card" id="step-2-card">
                <div class="card-header step-header d-flex align-items-center">
                    <span class="step-number" id="step-2-num">2</span>
                    <h5 class="card-title mb-0 font-weight-bold step-title">تحميل قالب الإكسيل المخصص</h5>
                </div>
                <div class="card-body text-center py-4">
                    <p class="text-muted mb-3">قم بتحميل القالب المعتمد، ثم قم بتعبئته بالبيانات المطلوبة بدقة بناءً على
                        الفرع المحدد أعلاه.</p>
                    <!-- زر تحميل القالب مربوط بالـ Route المخصص -->
                    <a href="{{ route('stock.download_template') }}" id="download-template-btn"
                        class="btn btn-outline-success px-4 py-2 font-weight-bold shadow-sm">
                        <i class="fas fa-file-excel mr-2 font-size-16"></i> تحميل قالب الإكسيل (Download Template)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- الخطوة الثالثة: رفع الملف وتأكيد العملية -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card step-card" id="step-3-card">
                <div class="card-header step-header d-flex align-items-center">
                    <span class="step-number" id="step-3-num">3</span>
                    <h5 class="card-title mb-0 font-weight-bold step-title">رفع الملف وتأكيد العملية</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <label class="parent-label font-weight-bold">اختر ملف الإكسيل المعبأ (Excel File)</label>
                            <input type="file" class="form-control-file border p-2 rounded w-100" name="excel_file"
                                id="excel-file-input" accept=".xlsx, .xls" required>
                        </div>
                        <div class="col-md-4 text-md-right text-center">
                            <button type="button" id="confirm-btn"
                                class="btn btn-success px-5 py-2 font-weight-bold shadow-sm">
                                تأكيد وحفظ <i class="las la-check-circle ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

</div>
<!-- Container closed -->
@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!-- SweetAlert2 js -->
<script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>

<script>
$(document).ready(function() {
    // دالة تحديث حالة الخطوات التفاعلية
    function updateStepsStatus() {
        var branchVal = $('#branch-select').val();
        if (branchVal !== null && branchVal !== "") {
            $('#step-1-card').addClass('completed');
            $('#step-1-num').html('<i class="fas fa-check"></i>');
        } else {
            $('#step-1-card').removeClass('completed');
            $('#step-1-num').text('1');
        }

        var fileVal = $('#excel-file-input').val();
        if (fileVal !== "") {
            $('#step-3-card').addClass('completed');
            $('#step-3-num').html('<i class="fas fa-check"></i>');
        } else {
            $('#step-3-card').removeClass('completed');
            $('#step-3-num').text('3');
        }
    }

    // 1. التحقق من اختيار الفرع قبل السماح بتحميل القالب
    $('#download-template-btn').on('click', function(e) {
        var branchVal = $('#branch-select').val();
        if (!branchVal) {
            e.preventDefault(); // منع تحميل الملف
            swal("تنبيه / Warning",
                "يرجى اختيار الفرع أولاً قبل تحميل القالب \n Please select the branch first before downloading the template",
                "warning");
            return false;
        }

        // تفعيل حالة الإنجاز للخطوة الثانية
        $('#step-2-card').addClass('completed');
        $('#step-2-num').html('<i class="fas fa-check"></i>');
    });

    $('#branch-select').on('change', updateStepsStatus);
    $('#excel-file-input').on('change', updateStepsStatus);
    updateStepsStatus();

    // 2. إرسال البيانات عبر AJAX عند الضغط على زر التأكيد مع التحقق من الفرع والملف
    $('#confirm-btn').on('click', function(e) {
        e.preventDefault();

        var branchVal = $('#branch-select').val();
        var fileVal = $('#excel-file-input').val();

        if (!branchVal) {
            swal("تنبيه / Warning", "يرجى اختيار الفرع أولاً \n Please select the branch first",
                "warning");
            return;
        }
        if (!fileVal) {
            swal("تنبيه / Warning",
                "يرجى اختيار ملف الإكسيل أولاً \n Please select the Excel file first", "warning");
            return;
        }

        var formData = new FormData($('#upload-form')[0]);

        // إرسال طلب الـ AJAX
        $.ajax({
            url: "{{ route('stock.import_excel') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#confirm-btn').prop('disabled', true).html(
                    'جاري المعالجة... <i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                $('#confirm-btn').prop('disabled', false).html(
                    'تأكيد وحفظ <i class="las la-check-circle ml-1"></i>');

                if (response.status === 'success') {
                    swal({
                        title: "تم بنجاح! / Success!",
                        text: "تم رفع وتأكيد البيانات بنجاح. عدد المنتجات المرفوعة: " +
                            response.products_count,
                        type: "success",
                        confirmButtonText: "حسناً / OK"
                    }, function() {
                        location.reload();
                    });
                } else {
                    swal("خطأ / Error", response.message || "حدث خطأ ما أثناء العملية",
                        "error");
                }
            },
            error: function(xhr) {
                $('#confirm-btn').prop('disabled', false).html(
                    'تأكيد وحفظ <i class="las la-check-circle ml-1"></i>');
                var errorMessage = "حدث خطأ غير متوقع! / Unexpected error occurred!";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                swal("خطأ / Error", errorMessage, "error");
            }
        });
    });
});
</script>
@endsection