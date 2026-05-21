@extends('layouts.master')

@section('title', __('home.journal_title'))

@section('css')
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    /* تنسيقات الحالات والجماليات */
    .balanced { color: #28a745; font-weight: bold; background-color: #e6f4ea; padding: 6px 15px; border-radius: 20px; border: 1px solid #28a745; display: inline-block; }
    .unbalanced { color: #dc3545; font-weight: bold; background-color: #fce8e6; padding: 6px 15px; border-radius: 20px; border: 1px solid #dc3545; display: inline-block; }
    
    /* أزرار التحكم الرئيسية */
    .btn-action { min-width: 150px; height: 45px; font-weight: bold; margin: 5px; border-radius: 8px; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    
    /* تكبير أزرار الجدول السفلي (تحديث وحذف وطباعة) */
    .btn-sm-custom {
        font-size: 1.15rem !important; /* تكبير الخط جداً */
        font-weight: 800 !important;
        padding: 10px 25px !important; /* تكبير الزر */
        border-radius: 10px;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin: 2px;
    }
    .btn-sm-custom:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

    /* تمييز قسم البحث */
    .search-section { background: #fdfdfd; border: 1px solid #e2e8f0; padding: 20px; border-radius: 15px; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.journal_title') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card shadow-sm">
            <div class="card-header search-section">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <input type="number" id="search_record_no" class="form-control" placeholder="{{ __('home.journal_search_placeholder') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary px-4" type="button" onclick="fetchRecord()">
                                    <i class="fa fa-search ml-1"></i> {{ __('home.journal_search_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 text-left">
                        <button class="btn btn-outline-dark" onclick="resetForm()">
                            <i class="fa fa-plus-circle ml-1"></i> {{ __('home.journal_new_entry') }}
                        </button>
                    </div>
                </div>
            </div>

            <form action="{{ route('journal.store') }}" method="POST" id="journalForm">
                @csrf
                <input type="hidden" name="record_id" id="record_id" value="0">
                
                <div class="card-body bg-light border-bottom">
                    <div class="row">
                        <div class="col-lg-3">
                            <label class="font-weight-bold">{{ __('home.journal_entry_date') }}</label>
                            <input class="form-control fc-datepicker" name="date" id="main_date" value="{{ date('Y-m-d') }}" type="text" required>
                        </div>
                        <div class="col-lg-9">
                            <label class="font-weight-bold">{{ __('home.journal_general_statement') }}</label>
                            <input class="form-control" name="general_notes" id="general_notes" type="text" placeholder="اكتب وصف القيد هنا...">
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th width="30%">{{ __('home.journal_account_name') }}</th>
                                    <th width="20%">{{ __('home.journal_cost_center') }}</th>
                                    <th width="12%">{{ __('home.journal_debit') }}</th>
                                    <th width="12%">{{ __('home.journal_credit') }}</th>
                                    <th width="20%">{{ __('home.journal_analysis_statement') }}</th>
                                    <th width="5%">#</th>
                                </tr>
                            </thead>
                            <tbody id="journal-body"></tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2" class="tx-18">{{ __('home.journal_total') }}</td>
                                    <td id="total_debit" class="text-primary tx-20">0.00</td>
                                    <td id="total_credit" class="text-primary tx-20">0.00</td>
                                    <td colspan="2"><span id="balance_label" class="unbalanced">{{ __('home.journal_unbalanced') }}</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-info btn-rounded mt-2" onclick="addRow()">
                        <i class="fa fa-plus ml-1"></i> {{ __('home.journal_add_row') }}
                    </button>

                    <div class="d-flex justify-content-center flex-wrap mt-4 pb-3 border-top pt-4">
                        <button type="submit" class="btn btn-primary btn-action" id="save_btn" disabled>
                            <i class="fa fa-save"></i> {{ __('home.journal_save') }}
                        </button>
                        <button type="button" class="btn btn-danger btn-action" id="delete_btn" onclick="deleteEntry()" style="display:none;">
                            <i class="fa fa-trash"></i> {{ __('home.journal_delete') }}
                        </button>
                        <button type="button" class="btn btn-dark btn-action" id="print_btn" onclick="printRecord()" style="background-color: #419BB2; border:none; display:none;">
                            <i class="fa fa-print"></i> {{ __('home.journal_print') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mt-4 mb-5">
    <div class="col-xl-12">
        <div class="card shadow-sm border-top-primary">
            <div class="card-body">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <h4 class="text-primary font-weight-bold mb-0">
                            <i class="fa fa-list-alt ml-2"></i> {{ __('home.latest_entries') }}
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group shadow-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" id="bottom_search" class="form-control form-control-lg" placeholder="ابحث الآن برقم القيد أو البيان...">
                        </div>
                    </div>
                </div>

                <div id="latest_records_container">
                    <div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x text-primary"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const accounts = @json(\App\Models\financial_accounts::where('active', 1)->where('is_parent', 0)->get());
    const centers = @json(App\Models\Cost_centers::all());
    let count = 0;

    $(document).ready(() => { 
        resetForm(); 
        loadLatestRecords(); 
    });

    // تحميل الجدول السفلي
    function loadLatestRecords(page = 1) {
        let searchQuery = $('#bottom_search').val();
        $.get("{{ url('get_latest_journals') }}", { page: page, search: searchQuery }, function(data) {
            $('#latest_records_container').html(data);
        });
    }

    // البحث السفلي
    $(document).on('keyup', '#bottom_search', function() {
        loadLatestRecords(1);
    });

    // التعديل (تسميع البيانات فوق)
    function editFromTable(id) {
        $('#search_record_no').val(id);
        fetchRecord();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function fetchRecord() {
        let id = $('#search_record_no').val();
        if(!id) return;
        $.get("{{ url('get_journal_details') }}/" + id, function(res) {
            if(res.status == 1) {
                $('#journal-body').empty(); count = 0;
                $('#record_id').val(res.data.id);
                $('#main_date').val(res.data.date);
                $('#general_notes').val(res.data.general_notes);
                
                // إظهار أزرار الحذف والطباعة بمجرد جلب قيد موجود
                $('#delete_btn, #print_btn').fadeIn();
                $('#save_btn').html('<i class="fa fa-edit"></i> ' + "{{ __('home.journal_update') }}").prop('disabled', false);
                
                res.details.forEach(item => addRow(item.customer_id, item.cost_center, item.debtor, item.creditor, item.note));
                setTimeout(calc, 200);
            }
        });
    }

    function addRow(accId = '', ccId = '', deb = 0, cre = 0, note = '') {
        count++;
        let accOptions = accounts.map(a => `<option value="${a.id}" ${a.id == accId ? 'selected' : ''}>${a.account_number} - ${a.name}</option>`).join('');
        let ccOptions = centers.map(c => `<option value="${c.id}" ${c.id == ccId ? 'selected' : ''}>${c.cost_center_ar}</option>`).join('');
        let row = `<tr id="r_${count}">
            <td><select name="accounts[]" class="form-control s2-dyn" required>${accOptions}</select></td>
            <td><select name="cost_centers[]" class="form-control s2-dyn"><option value="">بدون..</option>${ccOptions}</select></td>
            <td><input type="number" step="0.01" name="debit[]" class="form-control d-val text-center" value="${deb}" onkeyup="calc()"></td>
            <td><input type="number" step="0.01" name="credit[]" class="form-control c-val text-center" value="${cre}" onkeyup="calc()"></td>
            <td><input type="text" name="notes[]" class="form-control" value="${note}"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="$('#r_${count}').remove();calc();"><i class="fa fa-times"></i></button></td>
        </tr>`;
        $('#journal-body').append(row);
        if ($.fn.select2) { $(`.s2-dyn`).select2({ width: '100%' }); }
    }

    function calc() {
        let td = 0, tc = 0;
        $('.d-val').each(function(){ td += parseFloat($(this).val()) || 0; });
        $('.c-val').each(function(){ tc += parseFloat($(this).val()) || 0; });
        $('#total_debit').text(td.toFixed(2)); $('#total_credit').text(tc.toFixed(2));
        let isBalanced = Math.abs(td - tc) < 0.01 && td > 0;
        $('#balance_label').text(isBalanced ? "{{ __('home.journal_balanced') }}" : "{{ __('home.journal_unbalanced') }}").attr('class', isBalanced ? 'balanced' : 'unbalanced');
        $('#save_btn').prop('disabled', !isBalanced);
    }

    function resetForm() {
        $('#journalForm')[0].reset(); 
        $('#journal-body').empty(); 
        $('#record_id').val(0);
        $('#delete_btn, #print_btn').hide(); 
        $('#save_btn').html('<i class="fa fa-save"></i> ' + "{{ __('home.journal_save') }}");
        addRow(); addRow(); calc();
    }

    // الحفظ AJAX وتحديث الجدول السفلي فوراً
    $('#journalForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: function(res) {
                if(res[0] == 1) { 
// نافذة تنبيه SweetAlert باللغتين عربي وإنجليزي
        swal.fire({
            title: '<span style="direction: rtl; display: block; margin-bottom: 5px;">تمت العملية بنجاح!</span>' +
                   '<span style="font-size: 16px; color: #666; display: block;">Operation Completed Successfully!</span>',
            html: '<div style="direction: rtl; font-size: 16px; margin-bottom: 8px;">' + res[1] + '</div>' +
                  '<div style="font-size: 14px; color: #888; font-weight: 500;">The record has been updated.</div>',
            icon: 'success',
            confirmButtonText: 'موافق / OK',
            confirmButtonColor: '#003366' // متناسق مع لون الهوية الأزرق الخاص بك
        });
        
        loadLatestRecords(); 
                    if($('#record_id').val() == 0) resetForm(); 
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
    });

    function deleteEntry() {
        if(confirm("هل أنت متأكد من حذف هذا القيد نهائياً؟")) {
            $.get("{{ url('journal_delete') }}/" + $('#record_id').val(), function(res) {
                if (res[0] == 1) { 
                    alert(res[1]); 
                    resetForm(); 
                    loadLatestRecords(); 
                }
            });
        }
    }

    function printRecord() {
        let form = $(`<form action="{{ url('print_daily_record') }}" method="POST" target="_blank">@csrf<input type="hidden" name="record_id_print" value="${$('#record_id').val()}"></form>`);
        $('body').append(form); form.submit(); form.remove();
    }
</script>
@endsection