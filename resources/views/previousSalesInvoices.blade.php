@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<style>
    /* شاشة التحميل (ZATCA) */
    #loading-screen {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        color: white;
    }

    #loading-animation {
        border: 6px solid #f3f3f3;
        border-radius: 50%;
        border-top: 6px solid #FF4F1F;
        width: 60px; height: 60px;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* إطار قسم البحث المخصص */
    .search-box-container {
        border: 2px solid #e1e6f1; /* إطار هادئ يتناسب مع DashForge */
        border-radius: 10px;
        padding: 25px;
        background-color: #f9fbff;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .parent-label {
        font-weight: 600;
        color: #1b2e4b;
        margin-bottom: 8px;
        display: block;
    }

    .our-table thead th {
        background-color: #f1f2f7;
        color: #FF4F1F;
        text-transform: uppercase;
        font-size: 12px;
    }
</style>
@endsection

@section('title')
{{ __('home.previousSalesInvoices') }}
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 my-auto">{{ __('home.previousSalesInvoices') }}</h4>
    </div>
</div>
@endsection

@section('content')

<div id="loading-screen">
    <div id="loading-animation"></div>
    <h4 style="direction: rtl;">جارٍ معالجة الفاتورة مع هيئة الزكاة...</h4>
    <p>Please wait while communicating with ZATCA</p>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                
                <div class="search-box-container">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="parent-label">{{ __('home.enterinvoicenumber') }}</label>
                            <input class="form-control" id="invoiceid_search" placeholder="مثال: 1001" type="text" onkeyup="searchaboutinvoiceByIdfunction()">
                        </div>

                        <div class="col-md-4">
                            <label class="parent-label">{{ __('home.chooseclient') }}</label>
                            <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">
                                <option value="">{{ __('home.all_customers') }}</option>
                                @foreach (App\Models\customers::get() as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->id == 1 ? __('home.Cash Custome') : $customer->name }} - {{ $customer->tax_no }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="parent-label">{{ __('home.searchbydate') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input class="form-control fc-datepicker" id="date_search" placeholder="YYYY-MM-DD" type="text" onchange="searchaboutproductfunction()">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" id="ajax_responce_allinvoicesDiv">
                    <table class="table text-md-nowrap text-center our-table" id="example1">
                        <thead>
                            <tr>
                                <th>{{ __('home.Invoice_no') }}</th>
                                <th>{{ __('home.sallerName') }}</th>
                                <th>{{ __('home.clietName') }}</th>
                                <th>{{ __('home.date') }}</th>
                                <th>{{ __('home.total') }}</th>
                                <th>{{ __('home.operations') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadzatca" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.uploadzatca') }}</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="invoiceid_zatca_input">
                <i class="fas fa-cloud-upload-alt fa-3x text-warning mb-3"></i>
                <h5>{{ __('home.confirmzatcasent') }}</h5>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="btnSentToZatca">{{ __('home.confirm') }}</button>
                <button class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // تهيئة العناصر
        $('.fc-datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
        $('.select2').select2({ width: '100%' });

        // التحميل الأولي للجدول
        loadAllInvoices();
    });

    // دالة جلب البيانات الأساسية
    function loadAllInvoices() {
        $.get("{{ URL::to('getAllinvicesajax') }}", function(data) {
            $("#ajax_responce_allinvoicesDiv").html(data);
        });
    }

    // البحث برقم الفاتورة (keyup للحصول على تجربة بحث فورية)
    function searchaboutinvoiceByIdfunction() {
        let id = $('#invoiceid_search').val();
        let url = id ? "{{ URL::to('searchaboutinvoiceByIdfunction') }}/" + id : "{{ URL::to('getAllinvicesajax') }}";
        $.get(url, function(data) { $("#ajax_responce_allinvoicesDiv").html(data); });
    }

    // البحث بالتاريخ
    function searchaboutproductfunction() {
        let date = $('#date_search').val();
        $.get("{{ URL::to('searchAllInvoicespaginatenew') }}/" + date, function(data) {
            $("#ajax_responce_allinvoicesDiv").html(data);
        });
    }

    // البحث بالعميل
    $('#clientnamesearch').on('change', function() {
        let customerId = $(this).val();
        let url = customerId ? "{{ URL::to('getinvoicesbycustomer') }}/" + customerId : "{{ URL::to('getAllinvicesajax') }}";
        $.get(url, function(data) { $("#ajax_responce_allinvoicesDiv").html(data); });
    });

    // منطق إرسال ZATCA
    $(document).on('click', '[data-target="#uploadzatca"]', function() {
        $('#invoiceid_zatca_input').val($(this).data('id'));
    });

    $("#btnSentToZatca").click(function() {
        let id = $('#invoiceid_zatca_input').val();
        $('#uploadzatca').modal('hide');
        $('#loading-screen').css('display', 'flex');

        $.ajax({
            url: "{{ URL::to('sent_to_zatca') }}/" + id,
            type: 'GET',
            success: function(response) {
                $('#loading-screen').hide();
                if (response == 1) {
                    alert("تم إرسال الفاتورة وتوثيقها بنجاح");
                    loadAllInvoices();
                } else {
                    alert("حدث تنبيه: " + response);
                }
            },
            error: function(xhr) {
                $('#loading-screen').hide();
                alert("خطأ في الاتصال بالخادم");
            }
        });
    });
</script>
@endsection