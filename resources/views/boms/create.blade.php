@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.add_new_bom') }} @stop
@endsection

@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('boms.index') }}">{{ __('home.boms') }}</a> / {{ __('home.add_new_bom') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.add_new_bom') }}</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('boms.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.bom_code') }} <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" value="{{ old('code', 'BOM-' . rand(1000,9999)) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.bom_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.finished_product') }} <span class="text-danger">*</span></label>
                                <select name="finished_product_id" class="form-control select2" required>
                                    <option value="">{{ __('home.select_finished_product') }}</option>
                                    @foreach($finishedProducts as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.output_qty') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="output_quantity" class="form-control" value="{{ old('output_quantity', 1) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('home.item_notes') }}</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mt-4">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="is_active" value="1" checked>
                                    <span class="custom-control-label">{{ __('home.activate_bom') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">{{ __('home.raw_materials_required') }}</h4>

                    <div class="table-responsive">
                  <table class="table table-bordered" id="bom_items_table">
                    <thead>
                        <tr class="table-secondary">
                            <th>{{ __('home.material_category') }}</th>
                            <th>{{ __('home.raw_material') }}</th>
                            <th>{{ __('home.units') }}</th>
                            <th>{{ __('home.required_qty') }}</th>
                            <th>{{ __('home.scrap_percent') }}</th>
                            <th>{{ __('home.item_notes') }}</th>
                            <th><button type="button" id="add_row" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- اختر الفئة -->
                            <td>
                                <select class="form-control select2 group-select">
                                    <option value="">{{ __('home.all_categories') }}</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ app()->getLocale() == 'ar' ? $group->group_ar : $group->group_en }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <!-- المادة الخام (تم تعديل اسم الحقل المربوط بالفئة إلى product_group) -->
                            <td>
                                <select name="items[0][raw_material_id]" class="form-control select2 raw-material-select" required>
                                    <option value="">{{ __('home.select_raw_material') }}</option>
                                    @foreach($rawMaterials as $material)
                                        <option value="{{ $material->id }}" data-group="{{ $material->product_group }}">
                                            {{ $material->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <!-- الوحدة -->
                            <td>
                                <select name="items[0][unit_id]" class="form-control select2" required>
                                    <option value="">{{ __('home.select_unit') }}</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ app()->getLocale() == 'ar' ? $unit->name_ar : $unit->name_en }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" step="0.0001" name="items[0][quantity]" class="form-control" placeholder="0.00" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[0][scrap_percentage]" class="form-control" value="0.00">
                            </td>
                            <td>
                                <input type="text" name="items[0][notes]" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('home.save_bom') }}</button>
                        <a href="{{ route('boms.index') }}" class="btn btn-secondary">{{ __('home.cancel') }}</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ width: '100%' });

    let rowIndex = 0;

    // فلترة المادة الخام بناءً على الفئة المختارة
    $(document).on('change', '.group-select', function() {
        let groupId = String($(this).val() || '').trim();
        let row = $(this).closest('tr');
        let materialSelect = row.find('.raw-material-select');

        // إعادة ضبط القائمة وتدمير تهيئة select2 للخلية فقط
        materialSelect.val('');

        materialSelect.find('option').each(function() {
            let productGroup = String($(this).data('group') || '').trim();
            let optionVal = $(this).val();

            if (optionVal === "") {
                $(this).prop('disabled', false);
                return;
            }

            if (groupId === "" || productGroup === groupId) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });

        // تحديث مظهر Select2 بعد إتاحة وحظر الخيارات
        materialSelect.select2({ width: '100%' });
    });

    // إضافة صف جديد
    $('#add_row').click(function() {
        rowIndex++;
        let newRow = `
            <tr>
                <td>
                    <select class="form-control select2-dynamic group-select">
                        <option value="">{{ __('home.all_categories') }}</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ app()->getLocale() == 'ar' ? $group->group_ar : $group->group_en }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="items[${rowIndex}][raw_material_id]" class="form-control select2-dynamic raw-material-select" required>
                        <option value="">{{ __('home.select_raw_material') }}</option>
                        @foreach($rawMaterials as $material)
                            <option value="{{ $material->id }}" data-group="{{ $material->product_group }}">
                                {{ $material->product_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="items[${rowIndex}][unit_id]" class="form-control select2-dynamic" required>
                        <option value="">{{ __('home.select_unit') }}</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ app()->getLocale() == 'ar' ? $unit->name_ar : $unit->name_en }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="0.0001" name="items[${rowIndex}][quantity]" class="form-control" placeholder="0.00" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="items[${rowIndex}][scrap_percentage]" class="form-control" value="0.00">
                </td>
                <td>
                    <input type="text" name="items[${rowIndex}][notes]" class="form-control">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;
        $('#bom_items_table tbody').append(newRow);
        $('.select2-dynamic').select2({ width: '100%' });
        $('.select2-dynamic').removeClass('select2-dynamic');
    });

    // حذف الصف
    $(document).on('click', '.remove-row', function() {
        if($('#bom_items_table tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('{{ __('home.min_material_warning') }}');
        }
    });
});
</script>
@endsection
