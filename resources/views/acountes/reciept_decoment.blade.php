@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet">
    <style>
        .parent-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            display: block;
        }

        .table-thead-color {
            background-color: #f8f9fa;
        }

        .tax-value {
            background-color: #f1f1f1 !important;
            font-weight: bold;
            color: #d33;
        }

        #total_sum {
            color: #28a745;
            font-size: 1.2rem;
            font-weight: bold;
        }

        #total_tax {
            color: #dc3545;
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
@endsection

@section('title')
{{ __('home.Receipt document') }}
@stop

@section('content')
    <br>
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="content-title mb-0 my-auto" id="form_title">{{ __('home.Receipt document') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form id="receipt_form" action="{{ route('receipt.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="receipt_id" id="receipt_id">

                        <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="parent-label">{{ __('home.Withdraw from account') }}</label>
                            <select class="form-control select2" name="payment_account_id" id="payment_account_id" required>
                                <option value="">{{ __('home.Choose account') }}</option>
                                @foreach (App\Models\financial_accounts::whereIn('parent_account_number', [4, 5])->where('branchs_id',
                                    Auth()->user()->branchs_id)->get() as $acc)
                                    <option value="{{ $acc->id }}" data-parent="{{ $acc->parent_account_number }}">
                                        {{ $acc->name }} ({{ $acc->account_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="parent-label">{{ __('home.Payment method') }}</label>
                            <select class="form-control" name="pay_method_type" id="pay_method_type">
                                <option value="Cash">{{ __('home.Cash') }}</option>
                                <option value="Shabka"> {{ __('report.shabka') }} </option>
                                <option value="Bank_transfer">{{ __('home.Bank transfer') }}</option>
                            </select>
                        </div>
                            <div class="col-md-4">
                                <label class="parent-label">{{ __('home.Date') }}</label>
                                <input class="form-control" name="date" id="date_input" type="date"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="dynamic_field">
                                <thead class="table-thead-color">
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 20%">{{ __('home.Disburse to account') }}</th>
                                        <th style="width: 15%">{{ __('home.Cost Center') }}</th>
                                        <th style="width: 10%">{{ __('home.Amount (Inclusive)') }}</th>
                                        <th style="width: 10%">{{ __('home.avt_rate') }}</th>
                                        <th style="width: 10%">{{ __('home.Tax Value') }}</th>
                                        <th style="width: 15%">{{ __('home.Statement') }}</th>
                                        <th style="width: 10%">{{ __('home.Attachment') }}</th>
                                        <th>{{ __('home.Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <select class="form-control select2" name="items[0][client_account_id]"
                                                id="item_account_0" required>
                                                <option value="">{{ __('home.Choose account') }}</option>
                                                @foreach (App\Models\financial_accounts::where('active', 1)->where('is_parent', 0)->get() as $acc)
                                                    <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="items[0][cost_center]"
                                                id="item_cost_0">
                                                <option value="">{{ __('home.Without') }}</option>
                                                @foreach (App\Models\Cost_centers::all() as $cost)
                                                    <option value="{{ $cost->id }}">{{ $cost->cost_center_ar }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="items[0][amount]" id="item_amount_0" step="0.01"
                                                class="form-control amount-input" required></td>
                                        <td>
                                            <select class="form-control tax-select" name="items[0][tax_rate]"
                                                id="item_tax_0">
                                                <option value="0">{{ __('home.Tax') }} (0%)</option>
                                                <option value="0.05">5%</option>
                                                <option value="0.15">15%</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="items[0][tax_value]" class="form-control tax-value"
                                                readonly value="0.00"></td>
                                        <td><input type="text" name="items[0][notes]" id="item_notes_0"
                                                class="form-control"></td>
                                        <td><input type="file" name="items[0][attachment]" class="form-control-file"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="9" class="text-right">
                                            <button type="button" class="btn btn-primary btn-sm" id="add_row">+
                                                {{ __('home.Add Row') }}</button>
                                        </td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="3">{{ __('home.Total') }}: <span id="total_sum">0.00</span></td>
                                        <td colspan="6">{{ __('home.Total Tax') }}: <span id="total_tax">0.00</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="submit" id="submit_button"
                            class="btn btn-success btn-block mt-3">{{ __('home.Save and process') }}</button>
                        <button type="button" id="cancel_edit" class="btn btn-secondary btn-block mt-2"
                            style="display:none;">{{ __('home.cancel') }}</button>
                    </form>
                </div>
            </div>

            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="row">
                        <div id="AVT_Div2" class="col-lg-3">
                            <label for="search_by_decoumentNo"
                                class="control-label parent-label">{{ __('home.decoumentNo') }}</label>
                            <input type="number" class="form-control parent-input" id="search_by_decoumentNo"
                                onkeyup="search_by_decoumentNo_function()"
                                placeholder="{{ __('home.Search by doc number') }}">
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="ajax_responce_allinvoicesDiv">
                        <div class="text-center p-3">{{ __('home.Loading') }}...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
                $('#payment_account_id').on('change', function() {
                // جلب الـ data-parent للخيار اللي تم تحديده
                var parentAccount = $(this).find(':selected').data('parent');

                if (parentAccount == 5) {
                    // إذا كان الأب 4 (خزينة) -> يقف على نقدي (Cash)
                    $('#pay_method_type').val('Cash').trigger('change');
                } else if (parentAccount == 4) {
                    // إذا كان الأب 5 (بنك) -> يقف على حساب بنكي (Bank_transfer)
                    $('#pay_method_type').val('Bank_transfer').trigger('change');
                }
            });

            // تفعيل السيلكت 2 وتحميل البيانات
            $('.select2').select2({ width: '100%' });
            loadAllData();

            var i = 1;
            $('#add_row').click(function () {
                var html = `<tr id="row${i}">
                <td>${i + 1}</td>
                <td><select class="form-control select2" name="items[${i}][client_account_id]" required>
                    <option value="">{{ __('home.Choose account') }}</option>
                    @foreach (App\Models\financial_accounts::where('active', 1)->where('is_parent', 0)->get() as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select></td>
                <td><select class="form-control select2" name="items[${i}][cost_center]">
                    <option value="">{{ __('home.Without') }}</option>
                    @foreach (App\Models\Cost_centers::all() as $cost)
                        <option value="{{ $cost->id }}">{{ $cost->cost_center_ar }}</option>
                    @endforeach
                </select></td>
                <td><input type="number" name="items[${i}][amount]" step="0.01" class="form-control amount-input" required></td>
                <td><select class="form-control tax-select" name="items[${i}][tax_rate]">
                    <option value="0">{{ __('home.Exempt') }}</option><option value="0.05">5%</option><option value="0.15">15%</option>
                </select></td>
                <td><input type="text" name="items[${i}][tax_value]" class="form-control tax-value" readonly value="0.00"></td>
                <td><input type="text" name="items[${i}][notes]" class="form-control"></td>
                <td><input type="file" name="items[${i}][attachment]" class="form-control-file"></td>
                <td><button type="button" class="btn btn-danger btn-sm btn_remove" id="${i}">X</button></td>
            </tr>`;
                $('#dynamic_field tbody').append(html);
                $(`#row${i} .select2`).select2({ width: '100%' });
                i++;
            });

            $(document).on('click', '.btn_remove', function () {
                $(this).parents('tr').remove();
                calculate();
            });

            $(document).on('keyup change', '.amount-input, .tax-select', calculate);

            function calculate() {
                var total = 0, tax = 0;
                $('#dynamic_field tbody tr').each(function () {
                    var amt = parseFloat($(this).find('.amount-input').val()) || 0;
                    var rate = parseFloat($(this).find('.tax-select').val()) || 0;
                    var tVal = rate > 0 ? (amt - (amt / (1 + rate))) : 0;
                    $(this).find('.tax-value').val(tVal.toFixed(2));
                    total += amt; tax += tVal;
                });
                $('#total_sum').text(total.toFixed(2));
                $('#total_tax').text(tax.toFixed(2));
            }

            // إرسال النموذج (إضافة أو تحديث)
            $('#receipt_form').on('submit', function (e) {
                e.preventDefault();
                var actionUrl = $(this).attr('action');

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: new FormData(this),
                    processData: false, contentType: false,
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('home.saved_successfully') }}",
                            text: res.message
                        });
                        resetForm();
                        loadAllData();
                    },
                    error: function (r) {
                        console.log(r)
                    }
                });
            });

            $(document).on('click', '#ajax_pagination_in_search a ', function (e) {
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
                        "_token": token_search
                    },
                    success: function (data) {
                        $("#ajax_responce_allinvoicesDiv").html(data);
                    },
                    error: function () {

                    }
                });
            });

            // دالة لتصفير النموذج بعد النجاح أو الإلغاء
            function resetForm() {
                $('#receipt_form')[0].reset();
                $('#receipt_id').val('');
                $('#receipt_form').attr('action', "{{ route('receipt.store') }}");
                $('#form_title').text("{{ __('home.Receipt document') }}");
                $('#submit_button').text("{{ __('home.Save and process') }}").removeClass('btn-info').addClass('btn-success');
                $('#cancel_edit').hide();
                $('#add_row').show();
                $('#dynamic_field tbody tr:not(:first)').remove();
                $('.select2').val('').trigger('change');
                calculate();
            }

            $('#cancel_edit').click(resetForm);

            // حدث التعديل (Edit) لجلب كافة الصفوف
            $(document).on('click', '.edit-btn', function () {
                // نعتمد هنا على رقم السند (serf_count) وليس فقط الـ ID الفريد للسطر
                var serf_count = $(this).data('serf_count');
                var id = $(this).data('id');

                // 1. تغيير شكل النموذج لوضع التعديل
                $('#receipt_id').val(id);
                $('#receipt_form').attr('action', "{{ url('receipt-update') }}/" + id);
                $('#form_title').text("{{ __('home.Edit Receipt') }} #" + serf_count);
                $('#submit_button').text("{{ __('home.Update') }}").removeClass('btn-success').addClass('btn-info');
                $('#cancel_edit').show();
                $('#add_row').show(); // نجعله متاحاً لإضافة أسطر جديدة أثناء التعديل

                // 2. جلب كافة التفاصيل من السيرفر
                $.ajax({
                    url: "{{ url('get-receipt-details') }}/" + serf_count,
                    type: "GET",
                    success: function (response) {
                        $('#dynamic_field tbody').empty();
                        i = 0;

                        // 1. البحث عن قيد الخزينة/البنك (الذي يحتوي على قيمة في creditor)

                       var paymentEntry = response.find(item => parseFloat(item.creditor) > 0);

                       if (paymentEntry) {
                            // تعيين حساب الخزينة في القائمة العلوية
                            $('#payment_account_id').val(paymentEntry.customer_id).trigger('change');

                            // --- الكود الجديد والمضمون لجلب طريقة الدفع من أي سطر في المصفوفة ---
                            var validMethodEntry = response.find(item => item.Pay_Method_Name && item.Pay_Method_Name.trim() !== '');

                            if (validMethodEntry) {
                                let method = validMethodEntry.Pay_Method_Name.toLowerCase().trim();

                                if (method === 'cash') {
                                    $('#pay_method_type').val('Cash').trigger('change');
                                } else if (method === 'bank_transfer' || method === 'bank transfer') {
                                    $('#pay_method_type').val('Bank_transfer').trigger('change');
                                } else {
                                    $('#pay_method_type').val(validMethodEntry.pay_method).trigger('change');
                                }
                            }
                            // ------------------------------------------------------------------

                            $('#date_input').val(paymentEntry.date_export);
                        }

                        // 2. تصفية القيود لعرض الحسابات المدينة فقط في الجدول (استثناء الخزينة والضريبة)
                        $.each(response, function (index, item) {
                            // نتحقق أن القيد مدين (debtor > 0)
                            // ونستبعد حساب الضريبة إذا كان رقمه ثابتاً (مثلاً 102)
                            if (parseFloat(item.debtor) > 0 && item.customer_id != 102) {
                                addDynamicRowWithData(item);
                            }
                        });

                        $('#form_title').text("{{ __('home.Edit Receipt') }} #" + paymentEntry.sent_serf_count);
                        $('#submit_button').text("{{ __('home.Update') }}").addClass('btn-info').removeClass('btn-success');
                        $('#cancel_edit').show();
                        window.scrollTo(0, 0);
                    }, error: function (r) {
                        console.log(r)
                    }
                });
            });
            // دالة بناء السطر بالبيانات عند التعديل
            function addDynamicRowWithData(item) {
                var html = `<tr id="row${i}">
            <td>${i + 1}</td>
            <td>
                <select class="form-control select2 row-acc" name="items[${i}][client_account_id]" required>
                    <option value="">{{ __('home.Choose account') }}</option>
                    @foreach (App\Models\financial_accounts::all() as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-control select2 row-cost" name="items[${i}][cost_center]">
                    <option value="">{{ __('home.Without') }}</option>
                    @foreach (App\Models\Cost_centers::all() as $cost)
                        <option value="{{ $cost->id }}">{{ $cost->cost_center_ar }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${i}][amount]" value="${item.recive_amount}" step="0.01" class="form-control amount-input" required></td>
            <td>
                <select class="form-control tax-select row-tax" name="items[${i}][tax_rate]">
                    <option value="0" ${item.vat == 0 ? 'selected' : ''}>0%</option>
                    <option value="0.05" ${item.vat_rate == 0.05 ? 'selected' : ''}>5%</option>
                    <option value="0.15" ${item.vat_rate == 0.15 ? 'selected' : ''}>15%</option>
                </select>
            </td>
            <td><input type="text" name="items[${i}][tax_value]" class="form-control tax-value" readonly value="0.00"></td>
            <td><input type="text" name="items[${i}][notes]" value="${item.note || ''}" class="form-control"></td>
            <td><input type="file" name="items[${i}][attachment]" class="form-control-file"></td>
            <td><button type="button" class="btn btn-danger btn-sm btn_remove">X</button></td>
        </tr>`;

                // إضافة السطر للجدول
                $('#dynamic_field tbody').append(html);

                // تفعيل التنسيقات والقيم للسطر الجديد
                var currentRow = $(`#row${i}`);
                currentRow.find('.row-acc').val(item.customer_id).select2({ width: '100%' });
                currentRow.find('.row-cost').val(item.cost_center).select2({ width: '100%' });

                i++; // زيادة العداد للسطر التالي
                calculate(); // إعادة حساب الإجماليات
            }
            // دالة مساعدة لبناء السطر أثناء التعديل
            function addDynamicRowForEdit(item) {
                var rowHtml = `<tr id="row${i}">
            <td>${i + 1}</td>
            <td>
                <select class="form-control select2 row-acc" name="items[${i}][client_account_id]" required>
                    <option value="">{{ __('home.Choose account') }}</option>
                    @foreach (App\Models\financial_accounts::all() as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-control select2 row-cost" name="items[${i}][cost_center]">
                    <option value="">{{ __('home.Without') }}</option>
                    @foreach (App\Models\Cost_centers::all() as $cost)
                        <option value="{{ $cost->id }}">{{ $cost->cost_center_ar }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${i}][amount]" value="${item.recive_amount}" step="0.01" class="form-control amount-input" required></td>
            <td>
                <select class="form-control tax-select row-tax" name="items[${i}][tax_rate]">
                    <option value="0" ${item.vat == 0 ? 'selected' : ''}>0%</option>
                    <option value="0.05" ${item.vat_rate == 0.05 ? 'selected' : ''}>5%</option>
                    <option value="0.15" ${item.vat_rate == 0.15 ? 'selected' : ''}>15%</option>
                </select>
            </td>
            <td><input type="text" name="items[${i}][tax_value]" class="form-control tax-value" readonly value="0.00"></td>
            <td><input type="text" name="items[${i}][notes]" value="${item.note || ''}" class="form-control"></td>
            <td><input type="file" name="items[${i}][attachment]" class="form-control-file"></td>
            <td>${i === 0 ? '' : '<button type="button" class="btn btn-danger btn-sm btn_remove">X</button>'}</td>
        </tr>`;

                $('#dynamic_field tbody').append(rowHtml);

                // تفعيل Select2 للسطر الجديد وتحديد القيم
                var currentRow = $('#row' + i);
                currentRow.find('.select2').select2({ width: '100%' });
                currentRow.find('.row-acc').val(item.customer_id).trigger('change');
                currentRow.find('.row-cost').val(item.cost_center).trigger('change');

                i++; // زيادة العداد للسطر التالي
            }

            // حدث الحذف (Delete)
            $(document).on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: "{{ __('home.Are you sure?') }}",
                    text: "{{ __('home.delete_warning') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: "{{ __('home.Yes, delete it!') }}",
                    cancelButtonText: "{{ __('home.Cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('receipt-delete') }}/" + id,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (res) {
                                Swal.fire("{{ __('home.Deleted!') }}", res.message, "success");
                                loadAllData();
                            }
                        });
                    }
                });
            });
        });

        function loadAllData() {
            $.ajax({
                url: "{{URL::to('get_all_send_serf_jax')}}",
                type: "GET",
                dataType: "html",
                success: function (products) {
                    $("#ajax_responce_allinvoicesDiv").html(products);
                }
            });
        }

        function search_by_decoumentNo_function() {
            var val = $("#search_by_decoumentNo").val();
            if (val == '') {
                loadAllData();
            } else {
                $.ajax({
                    url: "{{URL::to('search_by_decoumentNo_send_serf')}}/" + val,
                    type: "GET",
                    dataType: "html",
                    success: function (products) {
                        $("#ajax_responce_allinvoicesDiv").html(products);
                    }
                });
            }
        }
    </script>
@endsection
