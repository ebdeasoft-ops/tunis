@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<style>
    /* إطار مخصص لمنطقة البحث */
    .search-filter-section {
        border: 2px solid #e1e6f1;
        border-radius: 8px;
        padding: 20px;
        background-color: #fcfdfe;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .parent-label {
        font-weight: bold;
        color: #1b2e4b;
        margin-bottom: 8px;
        display: block;
    }

    /* تحسين شكل الجدول ليتناسب مع التصميم */
    .our-table thead th {
        background-color: #f1f2f7;
        color: #FF4F1F;
        text-transform: uppercase;
        font-size: 11px;
        border-bottom-width: 2px;
    }
</style>
@endsection

@section('title')
{{ __('home.previousPurchasesInvoices') }}
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 my-auto">{{ __('home.previousPurchasesInvoices') }}</h4>
    </div>
</div>
@endsection

@section('content')

@if (count($errors) > 0)
<div class="alert alert-danger">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>خطأ</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                
                <div class="search-filter-section">
                    <form action="" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="parent-label">{{ __('home.enterinvoicenumber') }}</label>
                                <input class="form-control" id="invoiceid" placeholder="1********" type="text" onkeyup="searchaboutinvoiceByIdfunction()">
                            </div>

                            <div class="col-lg-6">
                                <label class="parent-label">{{ __('home.shearchbysuppliername') }}</label>
                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">
                                    <option value="">{{ __('home.all_suppliers') }}</option>
                                    @foreach (App\Models\supllier::get() as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive hoverable-table" id="ajax_responce_allinvoicesDiv">
                    <table class="table text-md-nowrap text-center our-table" id="example1" data-page-length='50'>
                        <thead>
                            <tr>
                                <th>{{ __('home.Invoice_no') }}</th>
                                <th>{{ __('home.buyer name') }}</th>
                                <th>{{ __('home.suppliername') }}</th>
                                <th>{{ __('home.date') }}</th>
                                <th>{{ __('home.branch') }}</th>
                                <th>{{ __('home.total') }}</th>
                                <th>{{ __('home.paymentmethod') }}</th>
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

<div class="modal fade" id="delete_quotation" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.alert') }}</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="delete_id">
                <h5>{{ __('home.Are_you_sure_delete') }}</h5>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="delete_quotation_function" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentmethod" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.paymentmethod') }}</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center align-items-baseline mb-3">
                    <label class="parent-label">{{ __('home.total') }} : </label>
                    <span id="totalvalue" class="h3 mx-2 text-primary">0</span>
                    <label class="parent-label">{{ __('home.SAR') }}</label>
                </div>
                <div class="form-group">
                    <label>{{ __('home.paymentmethod') }}</label>
                    <select class="form-control" name="paymodal" id="paymodal">
                        <option value="Cash">{{ __('report.cash') }}</option>
                        <option value="Shabka">{{ __('report.shabka') }}</option>
                        <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                        <option value="Credit">{{ __('report.credit') }}</option>
                    </select>
                </div>
                <div class="row text-center">
                    <div class="col-3"><small>{{ __('report.cash') }}</small><input class="form-control text-center" id="cashamount" readonly></div>
                    <div class="col-3"><small>{{ __('report.shabka') }}</small><input class="form-control text-center" id="bankamount" readonly></div>
                    <div class="col-3"><small>تحويل</small><input class="form-control text-center" id="Bank_transfer" readonly></div>
                    <div class="col-3"><small>{{ __('report.credit') }}</small><input class="form-control text-center" id="creaditamount" readonly></div>
                </div>
                <input type="hidden" id="invoiceid_hidden">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="confirmpayment" class="btn btn-success">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });

        // تحميل البيانات عند فتح الصفحة
        loadInvoices();

        // البحث الفوري برقم الفاتورة
        window.searchaboutinvoiceByIdfunction = function() {
            let id = $('#invoiceid').val();
            let url = id ? "{{ URL::to('searchaboutinvoiceByIdfunctionpurchases') }}/" + id : "{{ URL::to('getAllinvicesapurchasesjax') }}";
            $.get(url, function(data) { $("#ajax_responce_allinvoicesDiv").html(data); });
        };

        // البحث عند تغيير المورد
        $('#clientnamesearch').on('change', function() {
            let supplierId = $(this).val();
            let url = supplierId ? "{{ URL::to('getinvoicesbyspplluer') }}/" + supplierId : "{{ URL::to('getAllinvicesapurchasesjax') }}";
            $.get(url, function(data) { $("#ajax_responce_allinvoicesDiv").html(data); });
        });

        function loadInvoices() {
            $.get("{{ URL::to('getAllinvicesapurchasesjax') }}", function(data) {
                $("#ajax_responce_allinvoicesDiv").html(data);
            });
        }

        // إعداد موديول الحذف
        $('#delete_quotation').on('show.bs.modal', function(event) {
            let id = $(event.relatedTarget).data('id');
            $('#delete_id').val(id);
        });

        $("#delete_quotation_function").click(function() {
            let id = $('#delete_id').val();
            $.get("{{URL::to('delete_purchase_invoice')}}/" + id, function(data) {
                $("#ajax_responce_allinvoicesDiv").html(data);
                $('#delete_quotation').modal('hide');
            });
        });

        // إعداد موديول الدفع
        $('#paymentmethod').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let id = button.data('id');
            let total = button.data('totalinvoice');
            $('#invoiceid_hidden').val(id);
            $('#totalvalue').text(total);
            $('#cashamount').val(total);
            $('#bankamount, #Bank_transfer, #creaditamount').val(0);
        });

        $("#confirmpayment").click(function() {
            let id = $('#invoiceid_hidden').val();
            let url = `{{URL::to('updatepaymentconfirmpaymentpurchases')}}/${id}/${$('#cashamount').val()}/${$('#bankamount').val()}/${$('#creaditamount').val()}/${$('#Bank_transfer').val()}/${$('#paymodal').val()}`;
            $.get(url, function(data) {
                $("#ajax_responce_allinvoicesDiv").html(data);
                $('#paymentmethod').modal('hide');
            });
        });
    });

            // مراقبة حقل السيلكت في المودال لتوزيع المبالغ تلقائياً وحماية الحقول (ReadOnly)
            $('select[name="paymodal"]').on('change', function () {
                var selectCustomer = $(this).val();
                var value = 0;

                if ($('#cashamount').val() != 0) {
                    value = $('#cashamount').val();
                } else if ($('#bankamount').val() != 0) {
                    value = $('#bankamount').val();
                } else if ($('#Bank_transfer').val() != 0) {
                    value = $('#Bank_transfer').val();
                } else {
                    value = $('#creaditamount').val();
                }

                if (selectCustomer == 'Cash') {
                    $('#cashamount').val(value);
                    $('#bankamount').val(0); $('#creaditamount').val(0); $('#Bank_transfer').val(0);
                    $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
                } else if (selectCustomer == 'Shabka') {
                    $('#cashamount').val(0); $('#bankamount').val(value); $('#creaditamount').val(0); $('#Bank_transfer').val(0);
                    $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
                } else if (selectCustomer == 'Credit') {
                    $('#cashamount').val(0); $('#bankamount').val(0); $('#Bank_transfer').val(0); $('#creaditamount').val(value);
                    $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
                } else if (selectCustomer == 'Bank_transfer') {
                    $('#cashamount').val(0); $('#bankamount').val(0); $('#creaditamount').val(0); $('#Bank_transfer').val(value);
                    $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', true);
                } else {
                    // في حال اختيار دفع متعدد (Partition)
                    $('#cashamount').val(value);
                    $('#bankamount').val(0); $('#creaditamount').val(0); $('#Bank_transfer').val(0);
                    $("#bankamount, #cashamount, #Bank_transfer").prop('readOnly', false);
                }
            });

</script>
@endsection