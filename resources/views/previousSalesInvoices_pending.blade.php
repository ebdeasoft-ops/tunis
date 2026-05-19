@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    /* شاشة التحميل */
    #loading-screen {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        color: white;
    }

    #loading-animation {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #ff4f1f;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* إطار قسم البحث */
    .search-box-frame {
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 25px;
        background-color: #ffffff;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        position: relative;
    }

    .search-box-frame:hover {
        border-color: #ff4f1f;
        transition: 0.3s;
    }

    .search-box-frame::before {
        content: "-";
        position: absolute;
        top: -12px;
        right: 20px;
        background: #fff;
        padding: 0 10px;
        font-weight: bold;
        color: #ff4f1f;
        font-size: 13px;
    }
    
    .parent-label { font-weight: bold; margin-bottom: 8px; display: block; color: #495057; }
    .card { border-radius: 12px; border: none; }
</style>
@endsection

@section('title')
    {{ __('home.pending_invoice_previes') }}
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 my-auto">{{ __('home.pending_invoice_previes') }}</h4>
    </div>
</div>
@endsection

@section('content')
    <div id="loading-screen">
        <div id="loading-animation"></div>
        <h4>جارٍ تحديث البيانات...</h4>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-body">
                    
                    <div class="search-box-frame">
                        <form action="#" method="POST" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <label class="parent-label">{{ __('home.enterinvoicenumber') }}</label>
                                    <input class="form-control shadow-sm" id="invoiceid_search" placeholder="ID #..." type="text">
                                </div>
                                
                                <div class="col-lg-4">
                                    <label class="parent-label">{{ __('home.chooseclient') }}</label>
                                    <select class="form-control select2 shadow-sm" id="clientnamesearch">
                                        <option value="">{{ __('home.all_customers') }}</option>
                                        @foreach (App\Models\customers::get() as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->id==1 ? __('home.Cash Custome') : $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <label class="parent-label">{{ __('home.searchbydate') }}</label>
                                    <div class="input-group shadow-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input class="form-control fc-datepicker" id="search_date" placeholder="YYYY-MM-DD" type="text">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="ajax_responce_allinvoicesDiv">
                        </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentmethod" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light">
                    <h6 class="modal-title font-weight-bold">{{ __('home.paymentmethod') }}</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4 p-3 rounded" style="background-color: #fff5f2;">
                        <label class="parent-label">{{ __('home.total') }}</label>
                        <div class="h3 font-weight-bold text-danger"><span id="totalvalue">0</span> <small>SAR</small></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('home.paymentmethod') }}</label>
                        <select class="form-control" id='paymodal_select'>
                            <option value="Cash">{{ __('report.cash') }}</option>
                            <option value="Shabka">{{ __('report.shabka') }}</option>
                            <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                            <option value="Credit">{{ __('report.credit') }}</option>
                            <option value="Partition">{{ __('home.Partition of the amount') }}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small">{{ __('report.cash') }}</label>
                            <input type="number" class="form-control" id="cashamount" value="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small">{{ __('report.shabka') }}</label>
                            <input type="number" class="form-control" id="bankamount" value="0" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small">{{ __('home.Bank_transfer') }}</label>
                            <input type="number" class="form-control" id="bank_transfer_amount" value="0" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small">{{ __('report.credit') }}</label>
                            <input type="number" class="form-control" id="creditamount" value="0" readonly>
                        </div>
                    </div>
                    <input type="hidden" id="modal_invoice_id">
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                    <button id="confirmpayment_btn" class="btn btn-danger">{{ __('home.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>

<script>
$(document).ready(function() {
    $('.fc-datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

    function loadData(url, params = {}) {
        $('#loading-screen').css('display', 'flex').fadeIn(100);
        $.ajax({
            url: url,
            type: "GET",
            data: params,
            dataType: "html",
            success: function(html) {
                $("#ajax_responce_allinvoicesDiv").html(html);
            },
            error: function() { alert("خطأ في الاتصال"); },
            complete: function() { $('#loading-screen').fadeOut(100); }
        });
    }

    // التشغيل الأول
    loadData("{{ URL::to('geta_jax_Recent_Invoices_pending') }}");

    // البحث
    $('#invoiceid_search, #search_date, #clientnamesearch').on('keyup change', function() {
        let invId = $('#invoiceid_search').val();
        let date = $('#search_date').val();
        let custId = $('#clientnamesearch').val();
        
        let url = "{{ URL::to('geta_jax_Recent_Invoices_pending') }}";
        if (invId) url = "{{ URL::to('searchaboutinvoice_pendding_ByIdfunction') }}/" + invId;
        else if (date) url = "{{ URL::to('getinvoices_bending_bydate') }}/" + date;
        else if (custId) url = "{{ URL::to('getinvoices_bending_bycustomer') }}/" + custId;

        loadData(url);
    });

    // Pagination
    $(document).on('click', '#ajax_pagination_in_search a', function(e) {
        e.preventDefault();
        loadData($(this).attr("href"));
    });

    // منطق توزيع المبالغ
    $('#paymodal_select').on('change', function() {
        let mode = $(this).val();
        let total = parseFloat($('#totalvalue').text());
        $('#cashamount, #bankamount, #bank_transfer_amount, #creditamount').val(0).prop('readonly', true);

        if (mode === 'Partition') {
            $('#cashamount, #bankamount, #bank_transfer_amount').prop('readonly', false);
        } else {
            if (mode === 'Cash') $('#cashamount').val(total);
            if (mode === 'Shabka') $('#bankamount').val(total);
            if (mode === 'Bank_transfer') $('#bank_transfer_amount').val(total);
            if (mode === 'Credit') $('#creditamount').val(total);
        }
    });

    // تأكيد الدفع
    $(document).on('click', '[data-target="#paymentmethod"]', function() {
        $('#modal_invoice_id').val($(this).data('id'));
        $('#totalvalue').text($(this).data('totalinvoice'));
        $('#paymodal_select').val('Cash').trigger('change');
    });

    $('#confirmpayment_btn').click(function() {
        let id = $('#modal_invoice_id').val();
        let total = parseFloat($('#totalvalue').text());
        let cash = parseFloat($('#cashamount').val()) || 0;
        let bank = parseFloat($('#bankamount').val()) || 0;
        let trans = parseFloat($('#bank_transfer_amount').val()) || 0;
        let cred = parseFloat($('#creditamount').val()) || 0;

        if ((cash + bank + trans + cred).toFixed(2) !== total.toFixed(2)) {
            alert("المجموع غير صحيح!");
            return;
        }

        let url = `{{URL::to('updatepaymentconfirmpayment')}}/${id}/${cash}/${bank}/${cred}/${trans}/${$('#paymodal_select').val()}/1`;
        loadData(url);
        $('#paymentmethod').modal('hide');
    });
});
</script>
@endsection