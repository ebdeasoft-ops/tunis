@extends('layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    /* Basic styling for loading screen */
    #loading-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
        font-size: 24px;
    }

    #loading-animation {
        border: 4px solid white;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('home.recent_delivers') }}@stop
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto">{{ __('home.recent_delivers') }}</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<center>
    <div id="loading-screen">
        <div id="loading-animation"></div>
        &nbsp;<p>جارٍ إرسال الفاتورة، يرجى الانتظار<br>Invoice is being sent, please wait</p>
    </div>
</center>

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
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                    <br>
                    <div class="row">
                        <div class="col-lg-4" id="start_at">
                            <label for="inputName" class="control-label parent-label">{{ __('home.chooseclient') }}</label>
                            <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">
                                @foreach (App\Models\customers::get() as $customer)
                                <option value="{{ $customer->id }}"> 
                                    {{ $customer->id == 1 ? __('home.Cash Custome') : $customer->name }} - {{ $customer->tax_no }} - {{ $customer->phone }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="col-xl-12">
                        <br>
                        <div class="card mg-b-20">
                            <div>
                                <div class="table-responsive hoverable-table" id="ajax_responce_allinvoicesDiv">
                                    <table class="table text-md-nowrap text-center our-table" id="example1" data-page-length='50' style="text-align: center;">
                                        <thead>
                                            <tr>
                                                <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.clietName') }}</th>
                                                <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                                <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                                <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                                                <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.operations') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <br />
                    </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="paymentmethod">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.paymentmethod') }} </h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label style="font-size:16px" class="control-label parent-label">&nbsp;&nbsp;{{ __('home.total') }} :&nbsp; </label>
                    <label style="font-size:20px" id="totalvalue">0</label>
                    <label style="font-size:15px" class="control-label parent-label">&nbsp{{ __('home.SAR') }} &nbsp;&nbsp;&nbsp;&nbsp; </label>
                    <br>
                </div>
                <div class="col">
                    <label> {{ __('home.paymentmethod') }} </label>
                    <br>
                    <select class="form-control" name="paymodal" id='paymodal' required>
                        <option value="Cash"> {{ __('report.cash') }}</option>
                        <option value="Shabka"> {{ __('report.shabka') }} </option>
                        <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                        <option value="Credit"> {{ __('report.credit') }} </option>
                        <option value="Bank_transfer"> {{ __('home.bank_arbic') }} </option>
                    </select>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label class="control-label parent-label">{{ __('report.cash') }}</label>
                        <input type="text" class="form-control parent-input" name="cashamount" id="cashamount" readonly value=0>
                    </div>
                    <div class="col">
                        <label class="control-label parent-label">{{ __('report.shabka') }}</label>
                        <input type="text" class="form-control parent-input" name="bankamount" id="bankamount" readonly value=0>
                    </div>
                    <div class="col">
                        <label class="control-label parent-label">{{ __('home.Bank_transfer') }}</label>
                        <input type="text" class="form-control parent-input" name="Bank_transfer" id="Bank_transfer" readonly value=0>
                    </div>
                    <div class="col">
                        <label class="control-label parent-label">{{ __('report.credit') }}</label><br>
                        <input type="text" class="form-control parent-input" name="creaditamount" id="creaditamount" readonly value=0>
                    </div>
                    <input hidden type="text" id="invoiceid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="confirmpayment" name="confirmpayment" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="uploadzatca">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.uploadzatca') }} </h6>
            </div>
            <div class="modal-body">
                <input type="hidden" id="invoiceid_zatca">
                <div class="row">
                    <div class="col">
                        <label for="inputName" class="control-label parent-label">{{ __('home.confirmzatcasent') }}</label>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                <button type="submit" id="sent_to_zatca" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        // أخفاء الشاشة عند اكتمال تحميل الصفحة وجلب الداتا مباشرة للمرة الأولى
        document.getElementById('loading-screen').style.display = 'none'; 
        
        $.ajax({
            url: " {{URL::to('getAlldeliversajax')}}",
            type: "GET",
            dataType: "html",
            success: function(products) {
                $("#ajax_responce_allinvoicesDiv").html(products);
            },
        });
    });

    // الرفع لهيئة الزكاة والضريبة والجمارك ZATCA
    $('#uploadzatca').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('.modal-body #invoiceid_zatca').val(id);
    });

    $("#sent_to_zatca").click(function(e) {
        document.getElementById('loading-screen').style.display = 'block'; 
        var url = " {{ URL::to('sent_to_zatca') }}" + '/' + $('#invoiceid_zatca').val();
        if(document.getElementById('sendnowzatca')) {
            document.getElementById('sendnowzatca').hidden = true;
        }

        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            dataType: "html",
            success: function(data) {
                if(data == 1){
                    var audio = new Audio('/sounds/done.mp3');
                    audio.play();

                    $.ajax({
                        url: " {{URL::to('getAlldeliversajax')}}",
                        type: "GET",
                        dataType: "html",
                        success: function(products) {
                            $("#ajax_responce_allinvoicesDiv").html(products);
                        },
                    });
                } else {
                    if(document.getElementById('sendnowzatca')) document.getElementById('sendnowzatca').hidden = false;
                    alert(data);
                }
                document.getElementById('loading-screen').style.display = 'none';
            },
            error: function(response) {
                if(document.getElementById('sendnowzatca')) document.getElementById('sendnowzatca').hidden = false;
                document.getElementById('loading-screen').style.display = 'none';
                console.log(response);
                alert(response);
            }
        });
    });

    // فلترة الفواتير بناءً على العميل المختار
    $('select[name="clientnamesearch"]').on('change', function() {
        var selectCustomer = $(this).val();
        var url = selectCustomer != '' ? " {{URL::to('getAlldeliversajaxbycustomer')}}" + "/" + selectCustomer : " {{URL::to('getAlldeliversajax')}}";
        
        $.ajax({
            url: url,
            type: "GET",
            dataType: "html",
            success: function(products) {
                $("#ajax_responce_allinvoicesDiv").html(products);
            },
        });
    });

    //pagination الأجاكس للبحث والصفحات
    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
        var search_by_text = $("#date").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();

        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search
            },
            success: function(data) {
                $("#ajax_responce_allinvoicesDiv").html(data);
            }
        });
    });

    // التحكم بحقول الدفع داخل المودال بناء على طريقة السداد المختارة
    $('select[name="paymodal"]').on('change', function() {
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

        $('#cashamount').val(0);
        $('#bankamount').val(0);
        $('#creaditamount').val(0);
        $('#Bank_transfer').val(0);

        if (selectCustomer == 'Cash') {
            $('#cashamount').val(value);
        } else if (selectCustomer == 'Shabka') {
            $('#bankamount').val(value);
        } else if (selectCustomer == 'Credit') {
            $('#creaditamount').val(value);
        } else if (selectCustomer == 'Bank_transfer') {
            $('#Bank_transfer').val(value);
        } else {
            $('#cashamount').val(value);
            document.getElementById("bankamount").readOnly = false;
            document.getElementById("cashamount").readOnly = false;
            document.getElementById("Bank_transfer").readOnly = false;
            return;
        }

        document.getElementById("bankamount").readOnly = true;
        document.getElementById("cashamount").readOnly = true;
        document.getElementById("Bank_transfer").readOnly = true;
    });

    // عند فتح مودال تفاصيل الدفع وتمرير الفاتورة والملبغ الإجمالي
    $('#paymentmethod').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var invoice = button.data('totalinvoice');
        $('#invoiceid').val(id);
        document.getElementById('totalvalue').innerHTML = invoice;
        $('#cashamount').val(invoice);
    });

    // تأكيد عملية الدفع والتحقق من صحة المبلغ المدخل بالكامل
    $("#confirmpayment").click(function(e) {
        if ($('#cashamount').val() == '') $('#cashamount').val(0);
        if ($('#bankamount').val() == '') $('#bankamount').val(0);
        if ($('#creaditamount').val() == '') $('#creaditamount').val(0);
        if ($('#Bank_transfer').val() == '') $('#Bank_transfer').val(0);

        var text = document.getElementById('totalvalue').innerText;
        const select = document.getElementById("paymodal");
        const selectedIndex = select.selectedIndex;

        var totalEntered = Number($('#cashamount').val()) + Number($('#Bank_transfer').val()) + Number($('#bankamount').val()) + Number($('#creaditamount').val());

        if (Number(text) == totalEntered) {
            $.ajax({
                url: " {{URL::to('updatepaymentconfirmpayment')}}/" + $('#invoiceid').val() + '/' + $('#cashamount').val() + '/' + $('#bankamount').val() + '/' + $('#creaditamount').val() + "/" + $('#Bank_transfer').val() + '/' + $('#paymodal').val() + '/' + selectedIndex,
                type: "GET",
                dataType: "html",
                success: function(data) {
                    $("#ajax_responce_allinvoicesDiv").html(data);
                },
                error: function(response) {
                    alert("{{ __('home.sorryerror') }}");
                }
            });
        } else {
            if($('#saveinvice').length) $('#saveinvice').val(0);
            alert("{{ __('home.entermonycorrect') }}");
        }
    });
</script>
@endsection