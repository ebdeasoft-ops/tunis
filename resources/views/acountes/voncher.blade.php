@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .parent-label { font-weight: 600; color: #495057; margin-bottom: 5px; display: block; }
        .table thead th { background-color: #f8f9fa; vertical-align: middle; font-size: 14px; border: 1px solid #dee2e6; color: #333; }
        .select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid #ced4da !important; display: flex; align-items: center; }
        .select2-container { width: 100% !important; }
        .table-responsive { overflow: visible !important; }
        #grand_total { font-weight: bold; color: #28a745; font-size: 1.5rem; }
        .amount-input { background-color: #fffde7; border: 1px solid #ffd54f !important; font-weight: bold; text-align: center; }
        #success_actions_top { display: none; margin-bottom: 20px; animation: fadeInDown 0.5s ease; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection

@section('title') {{ __('home.voucher') }} @stop

@section('content')
    <div class="container-fluid">
        <br>

        <div id="success_actions_top">
            <div class="alert alert-success border-success shadow-sm d-flex justify-content-between align-items-center p-3">
                <div>
                    <h5 class="mb-0 text-success"><i class="fas fa-check-circle ml-2"></i> {{ __('home.saved_successfully') }} {{ __('home.voucher_number') }}:
                        <span id="new_voucher_no" class="font-weight-bold"></span>
                    </h5>
                    <p class="mb-0 small">{{ __('home.print_or_new_voucher') }}</p>
                </div>
                <div class="btn-group">
                    <button type="button" onclick="printVoucherNow()" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-print ml-1"></i> {{ __('home.print') }}
                    </button>
                    <a id="pdf_link_top" href="#" target="_blank" class="btn btn-danger px-4 shadow-sm">
                        <i class="fas fa-file-pdf ml-1"></i> {{ __('home.dwonloadpdf') }}
                    </a>
                    <button type="button" onclick="location.reload()" class="btn btn-outline-secondary px-3">
                        <i class="fas fa-plus"></i> {{ __('home.new_voucher') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-top-primary">
            <div class="card-header bg-white py-3 border-bottom">
                <h4 class="mb-0 text-primary font-weight-bold" id="form_title">
                    <i class="fas fa-file-invoice-dollar ml-2"></i>{{ __('home.voucher') }}
                </h4>
            </div>

            <form action="{{ route('vocher.store') }}" method="POST" id="voucherForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_create" value="1">
                <input type="hidden" name="receipt_id" id="receipt_id">
                <input type="hidden" name="sent_abd_count" id="sent_abd_count_id">

                <div class="card-body">
                    <div class="row mb-4 p-3 bg-light rounded border mx-0">
                        <div class="col-md-4">
                            <label class="parent-label">{{ __('home.Deposit to account') }}</label>
                            <select name="main_account_id" id="main_account_id" class="form-control select2-main" required>
                                <option value="">{{ __('home.Choose account') }}</option>
                                @foreach(App\Models\financial_accounts::whereIn('parent_account_number', [4, 5])->where('branchs_id',
                                    Auth()->user()->branchs_id)->get() as $acc)
                                    <option value="{{ $acc->id }}"data-parent="{{ $acc->parent_account_number }}">{{ $acc->name }} ({{ $acc->account_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="parent-label">{{ __('home.Payment method') }}</label>
                            <select name="payment_method" class="form-control" id="payment_method">
                                <option value="Cash">{{ __('home.Cash') }}</option>
                                <option value="Shabka"> {{ __('report.shabka') }} </option>
                                <option value="Bank_transfer">{{ __('home.Bank transfer') }}</option>
                            </select>
                            <input type="hidden" name="pay_method_type" id="pay_method_type" value="cash">
                        </div>
                        <div class="col-md-3">
                            <label class="parent-label">{{ __('home.Date') }}</label>
                            <input type="date" name="date" id="date_input" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="receipt_table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 30%;">{{ __('home.Receive from account') }}</th>
                                    <th style="width: 20%;">{{ __('home.Cost Center') }}</th>
                                    <th style="width: 15%;">{{ __('home.Total') }}</th>
                                    <th style="width: 25%;">{{ __('home.Statement') }}</th>
                                    <th style="width: 15%;">{{ __('home.Attachment') }}</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="receipt_tbody">
                                </tbody>
                        </table>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-primary shadow-sm" onclick="addRow()">
                                <i class="fas fa-plus-circle"></i> {{ __('home.add_new_row') }}
                            </button>
                        </div>
                        <div class="col-md-6 text-left">
                            <div class="p-2 border rounded bg-white shadow-sm d-inline-block">
                                <span class="px-2">{{ __('home.Total') }}: </span>
                                <span id="grand_total">0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow" id="submitBtn">
                            <i class="fas fa-save ml-1"></i> {{ __('home.save_voucher_and_process') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <hr class="my-5">

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-history ml-2"></i> {{ __('home.vouchers_history_search') }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="font-weight-bold small">{{ __('home.search_by_voucher_no') }}:</label>
                        <input type="text" class="form-control border-primary" id="search_by_decoumentNo"
                            oninput="search_by_decoumentNo_function()" placeholder="{{ __('home.voucher_number') }}...">
                    </div>
                </div>

                <div id="ajax_responce_allinvoicesDiv" class="table-responsive border rounded p-2">
                    <p class="text-center text-muted py-3">{{ __('home.loading_history') }}...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

                        $('#main_account_id').on('change', function() {
                // جلب الـ data-parent للخيار اللي تم تحديده
                var parentAccount = $(this).find(':selected').data('parent');

                if (parentAccount == 5) {
                    // إذا كان الأب 4 (خزينة) -> يقف على نقدي (Cash)
                    $('#payment_method').val('Cash').trigger('change');
                } else if (parentAccount == 4) {
                    // إذا كان الأب 5 (بنك) -> يقف على حساب بنكي (Bank_transfer)
                    $('#payment_method').val('Bank_transfer').trigger('change');
                }
            });




        let currentVoucherId = null;
        let rowIdx = 0;

        function initSelect2(element) {
            $(element).select2({ width: '100%', dir: "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}", dropdownParent: $(element).closest('td') });
        }

        function addRow() {
            rowIdx++;
            const row = `
                <tr id="row_${rowIdx}">
                    <td class="align-middle">${rowIdx}</td>
                    <td>
                        <select name="items[${rowIdx}][account_id]" class="form-control select2-dynamic" required>
                            <option value=""></option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->account_number }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="items[${rowIdx}][cost_center_id]" class="form-control select2-dynamic">
                            <option value="">{{ __('home.Without') }}</option>
                            @foreach($cost_centers as $cost)
                                <option value="{{ $cost->id }}">{{ $cost->cost_center_ar }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="items[${rowIdx}][amount]" class="form-control amount-input" oninput="updateGrandTotal()" value="0" required>
                    </td>
                    <td>
                        <input type="text" name="items[${rowIdx}][note]" class="form-control" placeholder="{{ __('home.Statement') }}">
                    </td>
                    <td>
                        <input type="file" name="items[${rowIdx}][attachment]" class="form-control-file">
                    </td>
                    <td class="align-middle">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(${rowIdx})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            $('#receipt_tbody').append(row);
            $(`#row_${rowIdx} .select2-dynamic`).each(function () { initSelect2(this); });
        }

        function updateGrandTotal() {
            let total = 0;
            $('.amount-input').each(function () { total += parseFloat($(this).val()) || 0; });
            $('#grand_total').text(total.toFixed(2));
        }

        function removeRow(id) {
            if ($('#receipt_tbody tr').length > 1) { $(`#row_${id}`).remove(); updateGrandTotal(); recalculateIndex(); }
        }

        function recalculateIndex() {
            $('#receipt_tbody tr').each(function (idx) { $(this).find('td:first').text(idx + 1); });
        }

        function search_by_decoumentNo_function() {
            let docNo = $("#search_by_decoumentNo").val();
            let getUrl = "{{ LaravelLocalization::localizeUrl('get_all_send_kabd_jax') }}";
            let searchUrl = "{{ LaravelLocalization::localizeUrl('search_by_decoumentNo_send_abd') }}/" + docNo;
            let url = (docNo == '') ? getUrl : searchUrl;
            if (window.location.protocol === 'https:' && url.startsWith('http:')) {
                url = url.replace('http:', 'https:');
            }
            $.ajax({
                url: url, type: "GET", dataType: "html",
                success: function (data) { $("#ajax_responce_allinvoicesDiv").html(data); }
            });
        }

        function deleteFullVoucher(sent_count) {
            Swal.fire({
                title: "{{ __('home.are_you_sure') }}",
                text: "{{ __('home.delete_voucher_warning') }} (" + sent_count + ")",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "{{ __('home.yes_delete_all') }}",
                cancelButtonText: "{{ __('home.cancel') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: "{{ __('home.deleting') }}...", allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                    let deleteUrl = "{{ LaravelLocalization::localizeUrl('voucher/delete-full') }}/" + sent_count;
                    if (window.location.protocol === 'https:' && deleteUrl.startsWith('http:')) {
                        deleteUrl = deleteUrl.replace('http:', 'https:');
                    }
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: { "_token": "{{ csrf_token() }}" },
                        success: function (response) {
                            Swal.fire({ title: "{{ __('home.success') }}!", text: "{{ __('home.deleted_successfully') }}", icon: 'success', timer: 1500, showConfirmButton: false });
                            search_by_decoumentNo_function();
                        },
                        error: function () { Swal.fire("{{ __('home.error') }}!", "{{ __('home.delete_error') }}", 'error'); }
                    });
                }
            });
        }

        function setDateInputValue(inputSelector, rawDate) {
            if (!rawDate) return;
            let input = $(inputSelector);
            if (!input.length) return;

            let inputType = input.attr('type');
            let d = new Date(rawDate);

            if (!isNaN(d.getTime())) {
                let year = d.getFullYear();
                let month = String(d.getMonth() + 1).padStart(2, '0');
                let day = String(d.getDate()).padStart(2, '0');

                if (inputType === 'datetime-local') {
                    let hours = String(d.getHours()).padStart(2, '0');
                    let minutes = String(d.getMinutes()).padStart(2, '0');
                    input.val(`${year}-${month}-${day}T${hours}:${minutes}`);
                } else {
                    input.val(`${year}-${month}-${day}`);
                }
            } else {
                let str = String(rawDate).trim().replace(' ', 'T');
                let parts = str.split('T');
                let datePart = parts[0];
                let timePart = parts[1] ? parts[1].substring(0, 5) : '00:00';

                if (inputType === 'datetime-local') {
                    input.val(`${datePart}T${timePart}`);
                } else {
                    input.val(datePart);
                }
            }
        }

        // تهريب النص داخل value="..." + إزالة لواحق النظام من البيان
        function escAttr(v) {
            return String(v ?? '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }
        function stripVoucherNote(n) {
            return String(n ?? '')
                .replace(/\s*\|\s*(?:سند صرف رقم|تعديل سند رقم|تعديل سند قبض رقم)\s*:\s*\d+/g, '')
                .replace(/\s*\|\s*(?:ضريبة\s+)?سند صرف\s*\|\s*:\s*\d+/g, '')
                .replace(/\s*\|\s*سند قبض\s*\|\s*رقم\s*:\s*\d+/g, '')
                .trim();
        }
        function fillFormForEdit(data, serf_count, id) {
            $('#receipt_tbody').empty();
            let i = 0;
            let debtorFound = false;
            let pendingPaymentMethod = null;

            // استخراج وتعيين تاريخ السند في خانة التاريخ بدقة حسب نوع الحقل (date أو datetime-local)
            if (Array.isArray(data)) {
                for (let k = 0; k < data.length; k++) {
                    let rawDate = data[k].date_export || data[k].created_at;
                    if (rawDate) {
                        setDateInputValue('#date_input', rawDate);
                        break;
                    }
                }
            }

            data.forEach(function (item) {
                if (parseFloat(item.debtor) > 0) {
                    debtorFound = true;

                    let pm = item.type || item.Pay_Method_Name || item.pay_method;
                    if (pm) {
                        if (pm.toLowerCase().indexOf('cash') !== -1 || pm.indexOf('نقد') !== -1) pendingPaymentMethod = 'Cash';
                        else if (pm.toLowerCase().indexOf('shabka') !== -1 || pm.indexOf('شبك') !== -1) pendingPaymentMethod = 'Shabka';
                        else if (pm.toLowerCase().indexOf('bank') !== -1 || pm.indexOf('تحويل') !== -1) pendingPaymentMethod = 'Bank_transfer';
                        else pendingPaymentMethod = pm;
                    }

                    if (item.customer_id) {
                        let mainSelect = $('#main_account_id');
                        if (mainSelect.find(`option[value="${item.customer_id}"]`).length === 0) {
                            let accName = (item.financial_accounts_data && item.financial_accounts_data.name) ? item.financial_accounts_data.name : ('حساب ' + item.customer_id);
                            mainSelect.append(new Option(accName, item.customer_id, true, true));
                        }
                        mainSelect.val(item.customer_id).trigger('change');
                    }
                }
                else if (parseFloat(item.creditor) > 0) {
                    i++;
                    let rowHtml = `
                        <tr id="row_${i}">
                            <td>${i}</td>
                            <td>
                                <select class="form-control select2 row-acc" name="items[${i}][account_id]" required>
                                    <option value="">{{ __('home.Choose account') }}</option>
                                    @foreach ($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control select2 row-cost" name="items[${i}][cost_center_id]">
                                    <option value="">{{ __('home.Without') }}</option>
                                    @foreach ($cost_centers as $cost)
                                        <option value="{{ $cost->id }}">{{ $cost->cost_center_ar }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[${i}][amount]" value="${item.creditor}" class="form-control amount-input" oninput="updateGrandTotal()" required />
                            </td>
                            <td>
                                <input type="text" name="items[${i}][note]" value="${escAttr(stripVoucherNote(item.note))}" class="form-control" />
                            </td>
                            <td>
                                <input type="file" name="items[${i}][attachment]" class="form-control-file">
                                ${item.attachments ? `<input type="hidden" name="items[${i}][existing_attachment]" value="${escAttr(item.attachments)}">
                                <a href="{{ url('openfile') }}/${encodeURIComponent(item.attachments)}" target="_blank" class="d-inline-block mt-1"><i class="fas fa-paperclip"></i> {{ __('home.show') }}</a>` : ''}
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${i})"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>`;
                    $('#receipt_tbody').append(rowHtml);
                    let currentRow = $(`#row_${i}`);
                    let rowSelect = currentRow.find('.row-acc');
                    if (item.customer_id) {
                        if (rowSelect.find(`option[value="${item.customer_id}"]`).length === 0) {
                            let accName = (item.financial_accounts_data && item.financial_accounts_data.name) ? item.financial_accounts_data.name : ('حساب ' + item.customer_id);
                            rowSelect.append(new Option(accName, item.customer_id, true, true));
                        }
                        rowSelect.val(item.customer_id);
                    }
                    rowSelect.select2({ width: '100%', dir: "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" });
                    currentRow.find('.row-cost').val(item.cost_center || "").select2({ width: '100%', dir: "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" });
                }
            });

            if (!debtorFound && data.length > 0) {
                let first = data[0];
                let pm = first.type || first.Pay_Method_Name || first.pay_method;
                if (pm) {
                    if (pm.toLowerCase().indexOf('cash') !== -1 || pm.indexOf('نقد') !== -1) pendingPaymentMethod = 'Cash';
                    else if (pm.toLowerCase().indexOf('shabka') !== -1 || pm.indexOf('شبك') !== -1) pendingPaymentMethod = 'Shabka';
                    else if (pm.toLowerCase().indexOf('bank') !== -1 || pm.indexOf('تحويل') !== -1) pendingPaymentMethod = 'Bank_transfer';
                    else pendingPaymentMethod = pm;
                }
            }

            // تطبيق طريقة الدفع بعد اكتمال تغيير الحساب الرئيسي لضمان عدم استبدالها
            if (pendingPaymentMethod) {
                $('#payment_method').val(pendingPaymentMethod).trigger('change');
                $('#pay_method_type').val(pendingPaymentMethod);
            }

            // تحديث مؤشر الصفوف
            rowIdx = i;
            if (i === 0) {
                addRow();
            }

            $('#sent_abd_count_id').val(serf_count || 0);
            $('#receipt_id').val(id);
            updateGrandTotal();
            $('#submitBtn').text("{{ __('home.update_selected_voucher') }}").removeClass('btn-success').addClass('btn-info');
            $('#form_title').html('<i class="fas fa-edit ml-2"></i> {{ __('home.edit_voucher_no') }}: ' + (serf_count && serf_count != 0 ? serf_count : id));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function fetchAndFillVoucher(sent_abd_count, id, btn) {
            let baseUrl = "{{ LaravelLocalization::localizeUrl('getDetailsJS_Kabd') }}";
            if (window.location.protocol === 'https:' && baseUrl.startsWith('http:')) {
                baseUrl = baseUrl.replace('http:', 'https:');
            }
            let fetchUrl = (sent_abd_count && sent_abd_count != 0 && sent_abd_count != '0')
                ? (baseUrl + '/' + sent_abd_count)
                : (baseUrl + '/id_' + id);

            let originalIcon = null;
            let buttonEl = btn ? $(btn) : null;
            if (buttonEl && buttonEl.length) {
                originalIcon = buttonEl.html();
                buttonEl.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            }

            $.ajax({
                url: fetchUrl,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (buttonEl && buttonEl.length) buttonEl.html(originalIcon).prop('disabled', false);
                    fillFormForEdit(response, sent_abd_count, id);
                },
                error: function (xhr) {
                    if (buttonEl && buttonEl.length) buttonEl.html(originalIcon).prop('disabled', false);
                    let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "{{ __('home.error') }}";
                    Swal.fire({ title: "{{ __('home.error') }}!", text: msg, icon: 'error' });
                }
            });
        }

        window.editVoucher = function (btn, sent_abd_count, id) {
            if ((!sent_abd_count || sent_abd_count === '') && btn) {
                sent_abd_count = $(btn).attr('data-sent_abd_count') || $(btn).data('sent_abd_count');
            }
            if ((!id || id === '') && btn) {
                id = $(btn).attr('data-id') || $(btn).data('id');
            }
            fetchAndFillVoucher(sent_abd_count, id, btn);
        };

        $(document).on('click', '.edit-btn', function (e) {
            e.preventDefault();
            var sent_abd_count = $(this).attr('data-sent_abd_count') || $(this).data('sent_abd_count');
            var id = $(this).attr('data-id') || $(this).data('id');
            fetchAndFillVoucher(sent_abd_count, id, this);
        });

        function printVoucherNow() {
            if (!currentVoucherId) return;
            let mapForm = document.createElement("form");
            mapForm.target = "_blank"; mapForm.method = "POST";
            mapForm.action = "{{ url('print_reciept_ducoument') }}";
            let tokenInput = document.createElement("input");
            tokenInput.type = "hidden"; tokenInput.name = "_token"; tokenInput.value = "{{ csrf_token() }}";
            mapForm.appendChild(tokenInput);
            let idInput = document.createElement("input");
            idInput.type = "hidden"; idInput.name = "id"; idInput.value = currentVoucherId;
            mapForm.appendChild(idInput);
            document.body.appendChild(mapForm);
            mapForm.submit();
        }

        $(document).ready(function () {
            $('.select2-main').select2({ dir: "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" });
            addRow();
            search_by_decoumentNo_function();

            $('#pay_method_select, #payment_method').on('change', function () { $('#pay_method_type').val($(this).val()); });

            $('#voucherForm').on('submit', function (e) {
                e.preventDefault();
                let submitBtn = $('#submitBtn');
                let receiptId = $('#receipt_id').val();
                let isEdit = (receiptId !== "" && receiptId !== undefined);
                let updateUrl = "{{ LaravelLocalization::localizeUrl('voucher-update-all') }}";
                if (window.location.protocol === 'https:' && updateUrl.startsWith('http:')) {
                    updateUrl = updateUrl.replace('http:', 'https:');
                }
                let actionUrl = isEdit ? updateUrl : $(this).attr('action');

                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> {{ __('home.processing') }}...');

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status === 'success') {
                            if (isEdit) {
                                Swal.fire({ title: "{{ __('home.updated') }}!", text: "{{ __('home.updated_successfully') }}", icon: 'success', timer: 1500, showConfirmButton: false });
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                currentVoucherId = response.id || response.count;
                                $('#new_voucher_no').text(response.count);
                                $('#pdf_link_top').attr('href', "{{ url('download_pdf_send_abd') }}/" + currentVoucherId);
                                $('#success_actions_top').slideDown();
                                Swal.fire({ title: "{{ __('home.saved') }}!", text: "{{ __('home.saved_successfully') }}", icon: 'success', timer: 2000, showConfirmButton: false });
                                $('#voucherForm')[0].reset();
                                $('.select2-main').val(null).trigger('change');
                                $('#receipt_id').val('');
                                $('#receipt_tbody').empty();
                                addRow();
                                $('#grand_total').text('0.00');
                                search_by_decoumentNo_function();
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }
                        } else {
                            Swal.fire("{{ __('home.error') }}!", response.message, 'error');
                        }
                        submitBtn.prop('disabled', false).html('<i class="fas fa-save ml-1"></i> {{ __('home.save_voucher') }}');
                    },
                    error: function (xhr) {
                        submitBtn.prop('disabled', false).html('<i class="fas fa-save ml-1"></i> {{ __('home.save_voucher') }}');
                        let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : "{{ __('home.data_error') }}";
                        Swal.fire("{{ __('home.error') }}!", errorMsg, 'error');
                    }
                });
            });

            $(document).on('click', '#ajax_pagination_in_search a', function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr("href"), type: 'GET', dataType: 'html',
                    success: function (data) { $("#ajax_responce_allinvoicesDiv").html(data); }
                });
            });
        });
    </script>
@endsection