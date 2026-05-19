@extends('layouts.master')
@section('title', __('home.Opening_entry'))

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* التنسيقات الأصلية */
        .entry-header-table { background: #ffffff; border: 1px solid #e1e1e1; margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .header-label { background-color: #f8f9fa; font-weight: bold; color: #333; width: 15%; border: 1px solid #e1e1e1 !important; text-align: center; vertical-align: middle !important; }
        .header-value { width: 35%; background: #fff; border: 1px solid #e1e1e1 !important; padding: 5px 15px; vertical-align: middle !important; }
        .entry-table thead th { background-color: #419BB2 !important; color: white; text-align: center; padding: 12px; }
        .remove-tr { color: #dc3545; cursor: pointer; font-size: 18px; transition: 0.3s; }
        .status-badge { padding: 5px 12px; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; }
        .badge-balanced { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-unbalanced { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .search-box { background: #f0f7ff; border: 1px solid #cfe2ff; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .btn-print-custom { background-color: #419BB2 !important; color: white !important; border: none; height: 38px; display: flex; align-items: center; }
        
        /* تنسيقات قسم الجدول السفلي */
        .table-latest thead th { background-color: #f4f6f9 !important; color: #333 !important; }
        .pagination { justify-content: center !important; margin-top: 15px; }
    </style>
@endsection

@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Opening_entry') }}</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="search-box">
                <div class="row align-items-center justify-content-end">
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-primary" onclick="fetchOldEntry()">
                                    <i class="fa fa-search"></i> {{ __('home.fetch_data') }}
                                </button>
                            </div>
                            <input type="number" id="search_id_input" class="form-control text-center" placeholder="{{ __('home.search_entry') }}">
                        </div>
                    </div>
                </div>
            </div>

            <form id="mainEntryForm">
                @csrf
                <input type="hidden" name="record_id" id="record_id" value="0">
                <div class="table-responsive">
                    <table class="table entry-header-table">
                        <tbody>
                            <tr>
                                <td class="header-label">{{ __('home.record_number') }}</td>
                                <td class="header-value text-center text-primary font-weight-bold"><span id="display_id">NEW</span></td>
                                <td class="header-label">{{ __('home.entry_date') }}</td>
                                <td class="header-value"><input type="date" name="entry_date" id="entry_date" class="form-control border-0" value="{{ date('Y-m-d') }}"></td>
                            </tr>
                            <tr>
                                <td class="header-label">{{ __('home.general_note') }}</td>
                                <td colspan="3" class="header-value"><input type="text" name="general_note" id="general_note" class="form-control border-0" placeholder="{{ __('home.general_note_placeholder') }}"></td>
                            </tr>
                            <tr>
                                <td class="header-label">{{ __('home.stautes') }}</td>
                                <td class="header-value text-center"><span id="header_status" class="status-badge badge-unbalanced">{{ __('home.unbalanced') }}</span></td>
                                <td class="header-label">{{ __('home.total') }}</td>
                                <td class="header-value text-center"><span id="header_total" class="font-weight-bold text-danger h5">0.00</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered entry-table text-center">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 30%">{{ __('home.account_financial') }}</th>
                                <th style="width: 12%">{{ __('home.debit') }}</th>
                                <th style="width: 12%">{{ __('home.credit') }}</th>
                                <th style="width: 36%">{{ __('home.analytical_note') }}</th>
                                <th style="width: 5%"><i class="fa fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                        <tfoot>
                            <tr class="bg-light font-weight-bold">
                                <td colspan="2" class="text-center">{{ __('home.total') }}</td>
                                <td id="totalDebit">0.00</td>
                                <td id="totalCredit">0.00</td>
                                <td colspan="2"><span id="footer_status_text" class="text-danger">{{ __('home.unbalanced') }}</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="button" class="btn btn-info btn-sm" onclick="addNewRow()"><i class="fa fa-plus"></i> {{ __('home.add_new_row') }}</button>
                <div class="d-flex align-items-center">
                    <form action="{{ url('print_Opening_entry') }}" method="POST" target="_blank" id="print_form" class="m-0 mr-2" style="display: none;">
                        @csrf
                        <input type="hidden" name="record_id_print" id="record_id_print" value="">
                        <button type="submit" class="btn btn-print-custom px-3">{{ __('home.print') }} &nbsp; <i class="fa fa-print"></i></button>
                    </form>
                    <button type="button" onclick="saveEntryForm()" id="saveBtn" class="btn btn-success px-4 mr-2" disabled>{{ __('home.save_changes') }}</button>
                    <button type="button" class="btn btn-danger px-4" onclick="location.reload()">{{ __('home.delete_full_entry') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <h5 class="card-title mb-0"> {{ __('home.latest_entries') }}  </h5>
            <div class="w-25">
                <input type="text" id="tableSearchInput" class="form-control form-control-sm" placeholder="بحث سريع في النتائج الحالية...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-center table-latest border" id="latestEntriesTable">
                    <thead>
                        <tr>
                <th>{{ __('home.record_number') }}</th>
                <th>{{ __('home.journal_entry_date') }}</th>
                <th>{{ __('home.journal_general_statement') }}</th>
                            <th>{{ __('home.total') }}</th>
                <th>{{ __('home.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="latestEntriesBody">
                        @foreach($latest_entries as $entry)
                        <tr>
                            <td class="font-weight-bold text-primary">{{ $entry->entry_number }}</td>
                            <td>{{ $entry->entry_date }}</td>
                            <td class="text-right">{{ \Str::limit($entry->general_note, 50) }}</td>
                            <td class="text-success font-weight-bold">{{ number_format($entry->total_amount, 2) }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="loadEntryToEdit({{ $entry->entry_number }})">
                                    <i class="fa fa-eye"></i> عرض وتعديل
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $latest_entries->links() }}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const TRANS = {
            balanced: "{{ __('home.balanced') }}",
            unbalanced: "{{ __('home.unbalanced') }}",
            selectAccount: "{{ __('home.select_account') }}",
            successSave: "{{ __('home.success_save') }}",
            entryNotFound: "{{ __('home.entry_not_found') }}"
        };

        let dir = "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}";

        // إضافة سطر
        function addNewRow(data = null) {
            let count = $('#tableBody tr').length + 1;
            let dVal = data ? (data.debit || data.depit || 0) : 0;
            let cVal = data ? (data.credit || 0) : 0;

            let row = `<tr>
                <td class="row-num">${count}</td>
                <td>
                    <select name="account_id[]" class="form-control select2">
                        <option value="">${TRANS.selectAccount}</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" ${data && (data.account_id == "{{ $acc->id }}") ? 'selected' : ''}>{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" step="0.01" name="debit[]" class="form-control debit-val text-center" value="${dVal}"></td>
                <td><input type="number" step="0.01" name="credit[]" class="form-control credit-val text-center" value="${cVal}"></td>
                <td><input type="text" name="line_note[]" class="form-control" value="${data ? (data.analytical_note || data.note || '') : ''}"></td>
                <td><i class="fas fa-trash-alt remove-tr" onclick="removeRow(this)"></i></td>
            </tr>`;

            $('#tableBody').append(row);
            $('.select2').select2({ width: '100%', dir: dir });
            calculateTotals();
        }

        function removeRow(btn) {
            if ($('#tableBody tr').length > 1) {
                $(btn).closest('tr').remove();
                calculateTotals();
            }
        }

        function calculateTotals() {
            let d = 0, c = 0;
            $('.debit-val').each(function () { d += parseFloat($(this).val()) || 0; });
            $('.credit-val').each(function () { c += parseFloat($(this).val()) || 0; });
            $('#totalDebit').text(d.toFixed(2));
            $('#totalCredit').text(c.toFixed(2));
            $('#header_total').text(d.toFixed(2));
            let balanced = (d.toFixed(2) === c.toFixed(2) && d > 0);
            if (balanced) {
                $('#header_status').html(TRANS.balanced).attr('class', 'status-badge badge-balanced');
                $('#footer_status_text').html(TRANS.balanced).attr('class', 'text-success');
                $('#saveBtn').prop('disabled', false);
            } else {
                $('#header_status').html(TRANS.unbalanced).attr('class', 'status-badge badge-unbalanced');
                $('#footer_status_text').html(TRANS.unbalanced).attr('class', 'text-danger');
                $('#saveBtn').prop('disabled', true);
            }
        }

        // جلب بيانات قيد قديم
        function fetchOldEntry() {
            let id = $('#search_id_input').val();
            if (!id) return;
            $.ajax({
                url: "/get-entry-details/" + id,
                type: "GET",
                success: function (res) {
                    if(res.success === true) {
                        $('#tableBody').empty();
                        let master = res.entry;
                        $('#record_id').val(master.id);
                        $('#record_id_print').val(master.id);
                        $('#display_id').text(master.id);
                        $('#entry_date').val(master.date || master.entry_date);
                        $('#general_note').val(master.note || master.general_note);
                        if (res.items && res.items.length > 0) {
                            res.items.forEach(item => addNewRow(item));
                        }
                        calculateTotals();
                        $('#print_form').fadeIn();
                        $('html, body').animate({ scrollTop: 0 }, 'fast');
                    } else {
                        $('#print_form').hide();
                        Swal.fire('خطأ', TRANS.entryNotFound, 'error');
                    }
                }
            });
        }

        // عرض القيد من الجدول السفلي
        function loadEntryToEdit(id) {
            $('#search_id_input').val(id);
            fetchOldEntry();
        }

        // بحث سريع في الجدول السفلي
        $("#tableSearchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#latestEntriesBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // دالة الحفظ
        function saveEntryForm() {
            let saveBtn = $('#saveBtn');
            $.ajax({
                url: "{{ route('opening_entry.store') }}",
                type: "POST",
                data: $('#mainEntryForm').serialize(),
                beforeSend: function () { saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...'); },
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire('نجاح', TRANS.successSave, 'success').then(() => {
                            location.reload(); // إعادة التحميل لتحديث الجدول السفلي
                        });
                    }
                },
                    error: function(response) {

            console.log(response);
            
            Swal.fire({
                title: 'خطأ / Error',
                text: "{{ __('home.sorryerror') }}",
                icon: 'error',
                confirmButtonText: 'إغلاق',
                confirmButtonColor: '#d33'
            });
        
}
            });
        }

        $(document).on('input', '.debit-val, .credit-val', function () {
            let row = $(this).closest('tr');
            if ($(this).hasClass('debit-val') && $(this).val() > 0) row.find('.credit-val').val(0);
            if ($(this).hasClass('credit-val') && $(this).val() > 0) row.find('.debit-val').val(0);
            calculateTotals();
        });

        $(document).ready(() => { if ($('#tableBody tr').length === 0) addNewRow(); });
    </script>
@endsection