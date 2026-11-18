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
  <!-- enter payments -->
    <div class="modal fade" id="paymentmethod" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold">{{ __('home.paymentmethod') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <!-- عرض إجمالي الفاتورة -->
                    <div class="alert alert-light text-center py-2 mb-3" style="border: 1px solid #e1e6f1; border-radius: 8px;">
                        <span style="font-size: 15px; color: #555;">{{ __('home.total') }}:</span>
                        <span style="font-size: 22px; font-weight: bold; color: #ff4f1f; margin: 0 6px;" id="totalvalue">0.00</span>
                        <span style="font-size: 14px; color: #555;">{{ __('home.SAR') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="paymodal" class="font-weight-bold">{{ __('home.paymentmethod') }}</label>
                        <select class="form-control" name="paymodal" id="paymodal" required>
                            <option value="Cash">{{ __('report.cash') }}</option>
                            <option value="Shabka">{{ __('report.shabka') }}</option>
                            <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                            <option value="Credit">{{ __('report.credit') }}</option>
                            <option value="Partition">{{ __('home.Partition of the amount') }}</option>
                        </select>
                    </div>

                    <!-- حقول توزيع وتجزئة المبالغ -->
                    <div class="card p-3 mb-2" style="background-color: #f8fafc; border: 1px dashed #d0d7de; border-radius: 8px;">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="cashamount" class="control-label parent-label mb-1" style="font-size: 13px;">
                                    <i class="fas fa-money-bill-wave text-success"></i> {{ __('report.cash') }}
                                </label>
                                <input type="number" step="any" min="0" class="form-control parent-input payment-amount-input" name="cashamount" id="cashamount"
                                    readonly value="0">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="bankamount" class="control-label parent-label mb-1" style="font-size: 13px;">
                                    <i class="fas fa-credit-card text-primary"></i> {{ __('report.shabka') }}
                                </label>
                                <input type="number" step="any" min="0" class="form-control parent-input payment-amount-input" name="bankamount" id="bankamount"
                                    readonly value="0">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="Bank_transfer" class="control-label parent-label mb-1" style="font-size: 13px;">
                                    <i class="fas fa-university text-info"></i> {{ __('home.Bank_transfer') }}
                                </label>
                                <input type="number" step="any" min="0" class="form-control parent-input payment-amount-input" name="Bank_transfer" id="Bank_transfer"
                                    readonly value="0">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="creaditamount" class="control-label parent-label mb-1" style="font-size: 13px;">
                                    <i class="fas fa-user-clock text-warning"></i> {{ __('report.credit') }}
                                </label>
                                <input type="number" step="any" min="0" class="form-control parent-input payment-amount-input" name="creaditamount" id="creaditamount"
                                    readonly value="0">
                            </div>
                        </div>

                        <!-- شريط مراقبة المبالغ والمتبقي لحظياً -->
                        <div id="partition_info_box" class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center" style="font-size: 13px;">
                            <div>
                                <span class="text-muted">مجموع المدخل: </span>
                                <strong id="entered_sum_display" class="text-primary font-weight-bold">0.00</strong>
                            </div>
                            <div>
                                <span class="text-muted">المتبقي: </span>
                                <strong id="remaining_sum_display" class="font-weight-bold text-success">0.00</strong>
                            </div>
                        </div>
                    </div>

                    <!-- حقل خفي لتخزين رقم الفاتورة المراد دفعها -->
                    <input type="hidden" id="invoiceid_payment">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('home.cancel') }}</button>
                    <button type="button" id="confirmpayment" class="btn btn-danger font-weight-bold">{{ __('home.confirm') }}</button>
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

            // دالة حساب المجاميع والمتبقي في المودال لحظياً
            function updatePaymentCalculations() {
                let totalInvoice = parseFloat($('#totalvalue').text()) || 0;
                let cash = parseFloat($('#cashamount').val()) || 0;
                let bank = parseFloat($('#bankamount').val()) || 0;
                let transfer = parseFloat($('#Bank_transfer').val()) || 0;
                let credit = parseFloat($('#creaditamount').val()) || 0;

                let enteredTotal = cash + bank + transfer + credit;
                let remaining = totalInvoice - enteredTotal;

                $('#entered_sum_display').text(enteredTotal.toFixed(2));
                $('#remaining_sum_display').text(remaining.toFixed(2));

                if (Math.abs(remaining) < 0.01) {
                    $('#remaining_sum_display').removeClass('text-danger text-warning').addClass('text-success');
                } else if (remaining < 0) {
                    $('#remaining_sum_display').removeClass('text-success text-warning').addClass('text-danger');
                } else {
                    $('#remaining_sum_display').removeClass('text-success text-danger').addClass('text-warning');
                }
            }

            // مراقبة كتابة المبالغ لحظياً في أي حقل
            $(document).on('input keyup change', '.payment-amount-input', function () {
                updatePaymentCalculations();
            });

            // فتح مودال طريقة الدفع وتعبئة بيانات الفاتورة
            $('#paymentmethod').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var invoice = parseFloat(button.data('totalinvoice')) || 0;
                var payMethod = button.data('pay') || 'Cash';
                var cash = parseFloat(button.data('cash')) || 0;
                var shabka = parseFloat(button.data('shabka')) || 0;
                var transfer = parseFloat(button.data('transfer')) || 0;
                var credit = parseFloat(button.data('credit')) || 0;

                $('#invoiceid_payment').val(id);
                $('#totalvalue').text(invoice.toFixed(2));

                // تعيين طريقة الدفع الحالية
                $('#paymodal').val(payMethod);

                if (payMethod === 'Partition') {
                    $('#cashamount').val(cash);
                    $('#bankamount').val(shabka);
                    $('#Bank_transfer').val(transfer);
                    $('#creaditamount').val(credit);
                    $('#cashamount, #bankamount, #Bank_transfer, #creaditamount').prop('readOnly', false);
                } else {
                    $('#cashamount, #bankamount, #Bank_transfer, #creaditamount').val(0).prop('readOnly', true);
                    if (payMethod === 'Cash') $('#cashamount').val(invoice);
                    else if (payMethod === 'Shabka') $('#bankamount').val(invoice);
                    else if (payMethod === 'Bank_transfer') $('#Bank_transfer').val(invoice);
                    else if (payMethod === 'Credit') $('#creaditamount').val(invoice);
                    else {
                        $('#paymodal').val('Cash');
                        $('#cashamount').val(invoice);
                    }
                }

                updatePaymentCalculations();
            });

            // تغيير نوع الدفع من القائمة المنسدلة
            $('select[name="paymodal"]').on('change', function () {
                var method = $(this).val();
                var total = parseFloat($('#totalvalue').text()) || 0;

                if (method === 'Partition') {
                    // تمكين التعديل على جميع حقول التجزئة (نقدي، شبكة، تحويل بنكي، آجل)
                    $('#cashamount, #bankamount, #Bank_transfer, #creaditamount').prop('readOnly', false);

                    // إذا كانت الحقول فارغة أو صفر، نضع قيمة افتراضية في الكاش لتسهيل البدء
                    let currentEntered = (parseFloat($('#cashamount').val()) || 0) +
                                         (parseFloat($('#bankamount').val()) || 0) +
                                         (parseFloat($('#Bank_transfer').val()) || 0) +
                                         (parseFloat($('#creaditamount').val()) || 0);
                    if (currentEntered === 0) {
                        $('#cashamount').val(total);
                    }
                } else {
                    // حماية الحقول وتعيين القيمة الإجمالية لطريقة الدفع المختارة فقط
                    $('#cashamount, #bankamount, #Bank_transfer, #creaditamount').val(0).prop('readOnly', true);
                    if (method === 'Cash') $('#cashamount').val(total);
                    else if (method === 'Shabka') $('#bankamount').val(total);
                    else if (method === 'Bank_transfer') $('#Bank_transfer').val(total);
                    else if (method === 'Credit') $('#creaditamount').val(total);
                }

                updatePaymentCalculations();
            });

            // ==========================================
            // 2. عمليات الدفع وتحديث الفاتورة (الـ AJAX الرئيسي)
            // ==========================================
            $("#confirmpayment").click(function (e) {
                e.preventDefault();

                let cash = parseFloat($('#cashamount').val()) || 0;
                let bank = parseFloat($('#bankamount').val()) || 0;
                let transfer = parseFloat($('#Bank_transfer').val()) || 0;
                let credit = parseFloat($('#creaditamount').val()) || 0;
                let totalInvoice = parseFloat($('#totalvalue').text()) || 0;
                let paymentMethodValue = $('#paymodal').val();
                let anotherBankValue = 5;

                let totalEntered = cash + bank + transfer + credit;

                // التحقق من أن مجموع المبالغ يتطابق مع إجمالي الفاتورة
                if (Math.abs(totalEntered - totalInvoice) > 0.05) {
                    Swal.fire({
                        icon: 'error',
                        title: 'المبلغ غير متطابق',
                        html: `
                            <div style="font-size: 15px; text-align: center;">
                                مجموع المبالغ المدخلة: <strong>${totalEntered.toFixed(2)}</strong> ر.س<br>
                                إجمالي الفاتورة المطلوب: <strong>${totalInvoice.toFixed(2)}</strong> ر.س<br>
                                <span class="text-danger font-weight-bold">يرجى ضبط المبالغ ليتطابق المجموع تماماً مع إجمالي الفاتورة.</span>
                            </div>
                        `,
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#ff4f1f'
                    });
                    return;
                }

                let invoiceId = $('#invoiceid_payment').val();
                let url = "{{URL::to('updatepaymentconfirmpayment')}}/" + invoiceId + '/' +
                          cash + '/' + bank + '/' + credit + '/' + transfer + '/' +
                          paymentMethodValue + '/' + anotherBankValue;

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
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
                            confirmButtonColor: "#28a745"
                        });
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: "{{ __('home.sorryerror') }}",
                            confirmButtonText: 'حسناً'
                        });
                    }
                });
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
                var dateVal = $('#date_search').val();
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
                var idVal = $('#invoiceid_search').val();
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
