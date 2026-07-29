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
    <div class="modal" id="paymentmethod">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ __('home.paymentmethod') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mg-b-15">
                            <label style="font-size: 16px;"
                                class="control-label parent-label">&nbsp;&nbsp;{{ __('home.total') }}:&nbsp;</label>
                            <label style="font-size: 20px; font-weight: bold; color: #ff4f1f;" id="totalvalue">0</label>
                            <label style="font-size: 15px;"
                                class="control-label parent-label">&nbsp;{{ __('home.SAR') }}</label>
                        </div>

                        <div class="form-group">
                            <label for="paymodal">{{ __('home.paymentmethod') }}</label>
                            <select class="form-control" name="paymodal" id="paymodal" required>
                                <option value="Cash">{{ __('report.cash') }}</option>
                                <option value="Shabka">{{ __('report.shabka') }}</option>
                                <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                                <option value="Credit">{{ __('report.credit') }}</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="cashamount" class="control-label parent-label">{{ __('report.cash') }}</label>
                                <input type="text" class="form-control parent-input" name="cashamount" id="cashamount"
                                    readonly value="0">
                            </div>
                            <div class="col">
                                <label for="bankamount" class="control-label parent-label">{{ __('report.shabka') }}</label>
                                <input type="text" class="form-control parent-input" name="bankamount" id="bankamount"
                                    readonly value="0">
                            </div>
                            <div class="col">
                                <label for="Bank_transfer"
                                    class="control-label parent-label">{{ __('home.Bank_transfer') }}</label>
                                <input type="text" class="form-control parent-input" name="Bank_transfer" id="Bank_transfer"
                                    readonly value="0">
                            </div>
                            <div class="col">
                                <label for="creaditamount"
                                    class="control-label parent-label">{{ __('report.credit') }}</label>
                                <input type="text" class="form-control parent-input" name="creaditamount" id="creaditamount"
                                    readonly value="0">
                            </div>
                        </div>
                        <!-- حقل خفي لتخزين رقم الفاتورة المراد دفعها -->
                        <input type="hidden" id="invoiceid_payment">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('home.cancel') }}</button>
                        <button type="button" id="confirmpayment" class="btn btn-danger">{{ __('home.confirm') }}</button>
                    </div>
                </div>
            </div>
        </div>

@endsection

    @section('js')
        <!-- Internal Data tables -->

        <!--Internal Datepicker js -->
        <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
        <!--Internal jquery.maskedinput js -->
        <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>

        <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <!-- Internal jquery-simple-datetimepicker js -->
        <script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
        <!--Internal pickerjs js -->
        <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // تهيئة حقل التاريخ الافتراضي
            var date = $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            }).val();

            // ==========================================
            // 1. أحداث فتح المودالات وتجهيز البيانات
            // ==========================================

            // مودال هيئة الزكاة والدخل
            $('#uploadzatca').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $(this).find('.modal-body #invoiceid_zatca').val(id);
            });

            // مودال تحديث التاريخ
            $('#updateDate').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $(this).find('.modal-body #id').val(id);
            });

            // مودال طريقة الدفع وتصفير القيم
            $('#paymentmethod').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var invoice = button.data('totalinvoice');

                $('#invoiceid_payment').val(id);
                document.getElementById('totalvalue').innerHTML = invoice;
                $('#cashamount').val(invoice);
            });

            // ==========================================
            // 2. عمليات الدفع وتحديث الفاتورة (الـ AJAX الرئيسي)
            // ==========================================
            $("#confirmpayment").click(function (e) {
                e.preventDefault();

                // تعبئة الحقول الفارغة بأصفار منعاً لأخطاء الحسابات
                if ($('#cashamount').val() == '') $('#cashamount').val(0);
                if ($('#bankamount').val() == '') $('#bankamount').val(0);
                if ($('#creaditamount').val() == '') $('#creaditamount').val(0);
                if ($('#Bank_transfer').val() == '') $('#Bank_transfer').val(0);

                let text = document.getElementById('totalvalue').innerText;
                let paymentMethodValue = $('#paymodal').val();
                let anotherBankValue = 5;

                let totalEntered = Number($('#cashamount').val()) + Number($('#Bank_transfer').val()) + Number($('#bankamount').val()) + Number($('#creaditamount').val());

                if (Number(text) == totalEntered) {
                    $.ajax({
                        url: "{{URL::to('updatepaymentconfirmpayment')}}/" + $('#invoiceid_payment').val() + '/' +
                            $('#cashamount').val() + '/' + $('#bankamount').val() + '/' +
                            $('#creaditamount').val() + "/" + $('#Bank_transfer').val() + '/' +
                            paymentMethodValue + '/' + anotherBankValue,
                        type: "GET",
                        dataType: "html",
                        success: function (data) {
                            // تحديث الجدول وإغلاق المودال تلقائياً
                            $("#ajax_responce_allinvoicesDiv").html(data);
                            $('#paymentmethod').modal('hide');
                            Swal.fire({
                                title: "تم التحديث بنجاح | Updated Successfully",
                                html: `
            <div style="font-size: 16px; line-height: 1.6;">
                تم تحديث طريقة الدفع وتعديل الحسابات بنجاح.<br>
                <span style="color: #666; font-size: 14px;">The payment method has been updated and accounts adjusted successfully.</span>
            </div>
        `,
                                icon: "success",
                                confirmButtonText: "موافق | OK",
                                confirmButtonColor: "#28a745" // لون أخضر مريح يدل على النجاح
                            });


                        },
                        error: function (response) {
                            alert("{{ __('home.sorryerror') }}");
                        }
                    });
                } else {
                    $('#saveinvice').val(0);
                    alert("{{ __('home.entermonycorrect') }}");
                }
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

            // ==========================================
            // 3. عمليات الإرسال لهيئة الزكاة والدخل (ZATCA)
            // ==========================================
            $("#sent_to_zatca").click(function (e) {
                document.getElementById('loading-screen').style.display = 'block';
                document.getElementById('sendnowzatca').hidden = true;

                var url = "{{ URL::to('sent_to_zatca') }}/" + $('#invoiceid_zatca').val();

                $.ajax({
                    url: url,
                    type: 'GET',
                    cache: false,
                    dataType: "html",
                    success: function (data) {
                        if (data == 1) {
                            var audio = new Audio('/sounds/done.mp3');
                            audio.play();
                            refreshAllInvoicesTable();
                        } else {
                            document.getElementById('sendnowzatca').hidden = false;
                            alert(data);
                        }
                        document.getElementById('loading-screen').style.display = 'none';
                    },
                    error: function (response) {
                        document.getElementById('sendnowzatca').hidden = false;
                        document.getElementById('loading-screen').style.display = 'none';
                        alert(response);
                    }
                });
            });

            // ==========================================
            // 4. تحديث التواريخ وعمليات البحث والفلترة
            // ==========================================
            $("#updateDateInvoice").click(function (e) {
                var url = "{{ URL::to('save_update_DateInvoice') }}/" + $('#id').val() + '/' + $('#date_invoice').val();
                $.ajax({
                    url: url,
                    type: 'GET',
                    cache: false,
                    dataType: "html",
                    success: function (data) {
                        if (data == 1) {
                            refreshAllInvoicesTable();
                        }
                    },
                    error: function (response) {
                        alert(response);
                    }
                });
            });

            function searchaboutproductfunction() {
                var dateVal = $('#date').val();
                $.ajax({
                    url: "{{URL::to('searchAllInvoicespaginatenew')}}/" + dateVal,
                    type: "GET",
                    dataType: "html",
                    success: function (products) {
                        $("#ajax_responce_allinvoicesDiv").html(products);
                    },
                });
            }

            function searchaboutinvoiceByIdfunction() {
                var idVal = $('#invoiceid_payment').val();
                var url = idVal != '' ? "{{URL::to('searchaboutinvoiceByIdfunction')}}/" + idVal : "{{URL::to('getAllinvicesajax')}}";
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "html",
                    success: function (products) {
                        $("#ajax_responce_allinvoicesDiv").html(products);
                    },
                });
            }

            $('select[name="clientnamesearch"]').on('change', function () {
                var selectCustomer = $(this).val();
                var url = selectCustomer != '' ? "{{URL::to('getinvoicesbycustomer')}}/" + selectCustomer : "{{URL::to('getAllinvicesajax')}}";
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "html",
                    success: function (products) {
                        $("#ajax_responce_allinvoicesDiv").html(products);
                    },
                });
            });

            // دالة موحدة لتحديث الجدول عند الحاجة من السيرفر لتقليل التكرار
            function refreshAllInvoicesTable() {
                $.ajax({
                    url: "{{URL::to('getAllinvicesajax')}}",
                    type: "GET",
                    dataType: "html",
                    success: function (products) {
                        $("#ajax_responce_allinvoicesDiv").html(products);
                    },
                });
            }

            // ==========================================
            // 5. أحداث الجلب التلقائي لبيانات العميل والمنتج
            // ==========================================
            $(document).ready(function () {
                $('.select2').select2({
                    width: '100%'
                });
                document.getElementById('loading-screen').style.display = 'none';
                refreshAllInvoicesTable(); // تحميل الجدول فور فتح الصفحة
                $('#invoice_number').hide();

                $('input[type="radio"]').click(function () {
                    if ($(this).attr('id') == 'type_div') {
                        $('#invoice_number').hide();
                        $('#type, #start_at, #end_at').show();
                    } else {
                        $('#invoice_number').show();
                        $('#type, #start_at, #end_at').hide();
                    }
                });

                $('select[name="clientnamesearch"]').on('change', function () {
                    var selectclientid = $(this).val();
                    if (selectclientid) {
                        $.ajax({
                            url: "{{ URL::to('getcustomer') }}/" + selectclientid,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                $('#clientName').val(data['name']);
                                $('#address').val(data['address']);
                                $('#phonenumber').val(data['phone']);
                                $('#notes').val(data['notes']);
                            },
                        });
                    }
                });

                $('select[name="searchproductNo"]').on('change', function () {
                    var selectclientid = $(this).val();
                    if (selectclientid) {
                        $.ajax({
                            url: "{{ URL::to('getproduct') }}/" + selectclientid,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                $('#quentity').val(data['numberofpice']);
                            },
                        });
                    }
                });
            });

            $("#updatecustomer").click(function (e) {
                var url = "{{ URL::to('updatecustomerDataInvoice') }}";
                var token_search = $('#token_search').val();
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,
                    dataType: "html",
                    data: {
                        _token: token_search,
                        id: $('#id').val(),
                        customername: $('#customername').val(),
                        customerId: $('#customerId').val(),
                    },
                    success: function (data) {
                        $("#ajax_responce_allinvoicesDiv").html(data);
                    }
                });
            });

            // دالة الترقيم التلقائي (Pagination) الموحدة (تم حذف التكرار القديم)
            $(document).on('click', '#ajax_pagination_in_search a', function (e) {
                e.preventDefault();
                var search_by_text = $("#date").val();
                var url = $(this).attr("href");
                var token_search = $("#token_search").val();

                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'html',
                    cache: false,
                    data: {
                        search_by_text: search_by_text,
                        "_token": token_search
                    },
                    success: function (data) {
                        $("#ajax_responce_allinvoicesDiv").html(data);
                    }
                });
            });

            //طباعة الفاتورة حرارياً (Receipt)
            $("#reciptprinter").click(function (e) {
                var url = "{{ URL::to('reciptprinter') }}";
                var token_search = $("#token_search").val();
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,
                    dataType: 'html',
                    data: {
                        _token: token_search,
                        show_invoice_number: $('#show_invoice_number').val(),
                    },
                    success: function (data) {
                        const winUrl = URL.createObjectURL(new Blob([data], { type: "text/html" }));
                        window.open(winUrl, "win", `width=800,height=400,screenX=200,screenY=200`);
                    },
                    error: function () {
                        alert("{{ __('home.sorryerror') }}");
                    }
                });
            });
        </script>
    @endsection