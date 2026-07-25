@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.form-control-lg {
    border-radius: 10px;
    font-size: 1.1rem;
}

.card {
    border-radius: 15px;
}

.action-btns i {
    cursor: pointer;
    margin: 0 5px;
    font-size: 1.1rem;
}
</style>
@section('title') {{ __('home.confirm_delivery') }} @stop
@endsection

@section('content')
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div id="ajax-alert-container"></div>

            <div class="card shadow-sm border-0 mb-5">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0" id="form-title"><i class="fas fa-plus-circle me-2"></i>
                        {{ __('home.add_new_delivery_note') }}</h5>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between p-2" style="gap: 5px;">

                    <!-- العنصر الأول (سيظهر في اليمين نظراً لأن الاتجاه العام RTL) -->


                    <!-- العنصر الثاني (سيظهر في أقصى اليسار) -->
                    <div>
                        <div>
                            <button style="background-color: green;" class="btn btn-sm text-white p-2"
                                data-toggle="modal" href="#updateinvoicebyidmodale">
                                {{ __('home.updateinvoicebyid') }}
                            </button>
                        </div>
                    </div>

                </div>
                <div class="card-body p-4">
                    <form id="delivery-note-form">
                        @csrf
                        <input type="hidden" name="note_id" id="note_id">

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('home.delivery_number') }}</label>
                                <input type="text" name="code" id="code" value="{{ $nextCode }}"
                                    class="form-control form-control-lg bg-light" readonly>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold">{{ __('home.customer') }}</label>
                                <select name="customer_id" id="customer_search" class="form-control form-control-lg"
                                    required></select>
                            </div>
                        </div>

                        <div class="card border-primary mb-4">
                            <div class="card-body bg-light">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-5">
                                        <label
                                            class="form-label fw-bold small text-muted">{{ __('home.product_search') }}</label>
                                        <select id="temp_product_search" class="form-control form-control-lg"></select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold small text-muted">{{ __('home.code') }}
                                            (ID)</label>
                                        <input type="text" id="temp_product_id"
                                            class="form-control form-control-lg bg-white" readonly placeholder="---">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold small text-muted">{{ __('home.qty') }}</label>
                                        <input type="number" id="temp_quantity" class="form-control form-control-lg"
                                            min="1">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="add-item-to-list"
                                            class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-plus-circle me-1"></i> {{ __('home.add_item') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped" id="items-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="15%">{{ __('home.code') }}</th>
                                        <th width="50%">{{ __('home.product') }}</th>
                                        <th width="20%">{{ __('home.qty') }}</th>
                                        <th width="15%">{{ __('home.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" id="save-btn" class="btn btn-success btn-lg px-5 shadow-sm">
                                <i class="fas fa-save me-2"></i> {{ __('home.save_data') }}
                            </button>
                            <button type="button" id="cancel-edit" class="btn btn-secondary btn-lg px-4 d-none">
                                {{ __('home.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">{{ __('home.all_notes') }}</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center shadow-sm border" id="notes-table">
                            <thead class="table">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('home.code') }}</th> {{-- كود الإذن --}}
                                    <th>{{ __('home.product_number') }}</th> {{-- كود المنتج التجاري --}}
                                    <th>{{ __('home.product') }}</th> {{-- اسم المنتج --}}
                                    <th>{{ __('home.customer') }}</th>
                                    <th>{{ __('home.qty') }}</th>
                                    <th>{{ __('home.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notes as $note)
                                <tr id="row-{{ $note->id }}" class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $note->id }}</td>

                                    {{-- كود المنتج --}}
                                    <td>
                                        @foreach($note->items as $item)
                                        <div class="fw-bold text-primary small">
                                            {{ $item->product->Product_Code ?? 'N/A' }}</div>
                                        @endforeach
                                    </td>

                                    {{-- اسم المنتج --}}
                                    <td class="text-start">
                                        @foreach($note->items as $item)
                                        <div class="border-bottom mb-1 pb-1 small">
                                            {{ $item->product->product_name ?? '---' }}
                                        </div>
                                        @endforeach
                                    </td>

                                    <td>{{ $note->customer->name ?? '---' }}</td>

                                    {{-- الكمية --}}
                                    <td>
                                        @foreach($note->items as $item)
                                        <div class="mb-1 pb-1">{{ $item->quantity + 0 }}</div>
                                        @endforeach
                                    </td>

                                    <td class="action-btns">
                                        <a href="{{ route('delivery-notes.print', $note->id) }}" target="_blank"
                                            class="btn btn-sm btn-secondary">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <button class="btn btn-sm btn-info edit-btn" data-id="{{ $note->id }}"
                                            data-code="{{ $note->code }}" data-customer-id="{{ $note->customer_id }}"
                                            data-customer-name="{{ $note->customer->name ?? '' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $note->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal p-3" id="updateinvoicebyidmodale">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.updateinvoicebyid') }} </h6><button aria-label="Close"
                        class="close close-special" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                        <label style="font-size: 12px;" for="inputName" class="control-label parent-label">
                            {{ __('home.enterinvoicenumber') }}</label>
                        <input style="height:32px" type="text" class="form-control parent-input" id="updateinvoicebyid"
                            name="name" title="{{ __('supprocesses.name') }}" required>
                    </div>


                </div>


                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal"
                        id="getinvoiceupdate">
                        {{ __('home.search') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none"
                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                            </path>
                        </svg>
                    </button>
                </div>
        </div>

    </div>
</div>
</div>


@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$("#getinvoiceupdate").click(function(e) {
    e.preventDefault(); // تأكد من وضعها في البداية


    var url = "{{ URL::to('updateinvoicebyid') }}" + "/" + $('#updateinvoicebyid').val();
    console.log(url)

    jQuery.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.error) {
                // تقسيم الرسالة لعرضها بشكل منسق
                let messages = data.error.split('|');

                Swal.fire({
                    icon: 'error',
                    title: 'تنبيه / Alert',
                    html: `<strong>${messages[0]}</strong><br>${messages[1]}`,
                    confirmButtonText: 'حسناً / OK'
                });
                return;
            }
            if (data == 0) {
                alert("{{ __('home.stocknotAvailable') }}");
            } else {
                if (data.customer) {
                    var newOption = new Option(data.customer.name, data.customer.id, true, true);
                    $('#customer_search').append(newOption).trigger('change');
                }
                // 3. جلب الأصناف المرتبطة بالإذن عبر Ajax
                $('#items-body').html(
                    '<tr><td colspan="4"><i class="fas fa-spinner fa-spin"></i> جاري تحميل الأصناف...</td></tr>'
                );
                $('#items-body').empty();

                data.product.forEach(function(item) {
                    // جلب اسم المنتج وكوده من العلاقة
                    let pName = item.product_name ? item.product_name : '---';
                    let pCode = item.Product_Code ? item.Product_Code :
                    '---'; // جلب كود المنتج
                    let pId = item.id; // الـ ID الحقيقي للاستخدام في الـ name والـ data-id

                    let row = `
                        <tr data-id="${pId}">
                            <td class="fw-bold">${pCode}</td> <td>
                                ${pName}
                                <input type="hidden" name="items[${pId}][product_id]" value="${pId}">
                            </td>
                            <td>
                                <input type="number" name="items[${pId}][quantity]"
                                       value="${Math.round(item.quantity)}"
                                       class="form-control text-center">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    $('#items-body').append(row);
                });

            }
        },
        error: function(xhr) {
            console.error("Error fetching invoice:", xhr.responseText);
            alert("حدث خطأ أثناء جلب بيانات الفاتورة");
        }
    });
});




$(document).ready(function() {
    // 1. تهيئة Select2 للعملاء (Ajax)
    $('#customer_search').select2({
        placeholder: "{{ __('home.type_to_search') }}",
        allowClear: true,
        dir: "rtl",
        ajax: {
            url: "{{ route('customers.ajax') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    // 2. تهيئة Select2 للمنتجات (الخانة المؤقتة للإضافة)
    $('#temp_product_search').select2({
        placeholder: "{{ __('home.product_search') }}",
        allowClear: true,
        dir: "rtl",
        ajax: {
            url: "{{ route('products.search') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // 3. إدارة إضافة الأصناف للجدول المؤقت
    $('#temp_product_search').on('select2:select', function(e) {
        let data = e.params.data;

        // وضع الكود في الخانة المخصصة للعرض فقط
        $('#temp_product_id').val(data.product_code);

        // تخزين الـ ID الحقيقي في "data attribute" لاستخدامه عند الإضافة للجدول
        $('#temp_product_id').attr('data-real-id', data.id);
    });

    $('#add-item-to-list').click(function() {
        let pId = $('#temp_product_id').attr('data-real-id'); // الـ ID الحقيقي
        let pCode = $('#temp_product_id').val(); // الـ Product_Code

        // 🔥 التعديل هنا: جلب نص المنتج المحدد حالياً فقط مع تنظيف المسافات الزائدة
        let pName = $('#temp_product_search').find(':selected').text().trim();

        let qty = $('#temp_quantity').val();

        // التحقق من البيانات
        if (!pId || !qty || qty <= 0) {
            alert("{{ __('home.please_select_product_and_qty') }}");
            return;
        }

        // منع تكرار نفس المنتج في الجدول
        if ($(`#items-body tr[data-id="${pId}"]`).length > 0) {
            alert("{{ __('home.product_already_added') }}");
            return;
        }

        let row = `
            <tr data-id="${pId}">
                <td class="fw-bold">${pCode}</td>
                <td>
                    ${pName}
                    <input type="hidden" name="items[${pId}][product_id]" value="${pId}">
                </td>
                <td>
                    <input type="number" name="items[${pId}][quantity]" value="${Math.round(qty)}" class="form-control text-center">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;

        $('#items-body').append(row);

        // تصفير خانات الإضافة
        $('#temp_product_search').val(null).trigger('change');
        $('#temp_product_id').val('').removeAttr('data-real-id');
        $('#temp_quantity').val('');
    });

    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
    });

    // 4. الحفظ النهائي للفاتورة (Ajax)
    $('#delivery-note-form').on('submit', function(e) {
        e.preventDefault();

        if ($('#items-body tr').length === 0) {
            alert("برجاء إضافة صنف واحد على الأقل");
            return;
        }

        let btn = $('#save-btn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ route('delivery-notes.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message || "حدث خطأ ما");
                btn.prop('disabled', false).html(
                    '<i class="fas fa-save me-2"></i> {{ __("home.save_data") }}');
            }
        });
    });

    // 5. التعديل (سحب البيانات)
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let customerId = $(this).data('customer-id');
        let customerName = $(this).data('customer-name');

        // 1. تعبئة البيانات الأساسية
        $('#note_id').val(id);
        $('#code').val(code);

        // 2. ضبط العميل في Select2
        if (customerId) {
            let option = new Option(customerName, customerId, true, true);
            $('#customer_search').empty().append(option).trigger('change');
        }

        // 3. جلب الأصناف المرتبطة بالإذن عبر Ajax
        $('#items-body').html(
            '<tr><td colspan="4"><i class="fas fa-spinner fa-spin"></i> جاري تحميل الأصناف...</td></tr>'
        );

        $.ajax({
            url: "{{ url('delivery-notes') }}/" + id + "/items",
            method: "GET",
            success: function(items) {
                $('#items-body').empty();

                items.forEach(function(item) {
                    // جلب اسم المنتج وكوده من العلاقة
                    let pName = item.product ? item.product.product_name : '---';
                    let pCode = item.product ? item.product.Product_Code :
                        '---'; // جلب كود المنتج
                    let pId = item
                        .product_id; // الـ ID الحقيقي للاستخدام في الـ name والـ data-id

                    let row = `
                        <tr data-id="${pId}">
                            <td class="fw-bold">${pCode}</td> <td>
                                ${pName}
                                <input type="hidden" name="items[${pId}][product_id]" value="${pId}">
                            </td>
                            <td>
                                <input type="number" name="items[${pId}][quantity]"
                                       value="${Math.round(item.quantity)}"
                                       class="form-control text-center">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    $('#items-body').append(row);
                });
            },
            error: function() {
                $('#items-body').html(
                    '<tr><td colspan="4" class="text-danger">خطأ في تحميل الأصناف</td></tr>'
                );
            }
        });

        // 4. تحديث شكل الفورم
        $('#form-title').html('<i class="fas fa-edit me-2"></i> {{ __("home.edit_note") }}');
        $('#cancel-edit').removeClass('d-none');

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // 6. الحذف النهائي من الجدول الرئيسي
    $(document).on('click', '.delete-btn', function() {
        if (confirm("{{ __('home.confirm_delete_msg') }}")) {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ url('delivery-notes/delete') }}/" + id,
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    $('#row-' + id).remove();
                }
            });
        }
    });

    // 7. إلغاء التعديل
    $('#cancel-edit').on('click', function() {
        $('#delivery-note-form')[0].reset();
        $('#note_id').val('');
        $('#customer_search').val(null).trigger('change');
        $('#items-body').empty();
        $(this).addClass('d-none');
        $('#form-title').html(
            '<i class="fas fa-plus-circle me-2"></i> {{ __("home.add_new_delivery_note") }}');
    });
});
</script>
@endsection