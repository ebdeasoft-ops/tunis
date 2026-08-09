@extends('layouts.master')
@section('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('title') {{ __('home.add_new_order') }} @stop
@endsection





@section('content')
<!-- breadcrumb / عنوان الصفحة -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.manufacturing') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('manufacturing_orders.index') }}">{{ __('home.manufacturing_orders') }}</a> / {{ __('home.add_new_order') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <h4 class="card-title mg-b-0">{{ __('home.add_new_order') }}</h4>
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

                @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
                <form action="{{ route('manufacturing_orders.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- رقم أمر الإنتاج -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.order_number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="order_number" class="form-control" value="{{ old('order_number', 'MO-' . date('Ymd') . '-' . rand(100,999)) }}" required>
                            </div>
                        </div>

                        <!-- تاريخ أمر الإنتاج -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.order_date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <!-- اختيار شجرة المكونات BOM -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.boms') }} <span class="text-danger">*</span></label>
                                <select name="bom_id" id="bom_id" class="form-control select2" required>
                                    <option value="">{{ __('اختر شجرة المكونات') }}</option>
                                    @foreach($boms as $bom)
                                        <option value="{{ $bom->id }}">{{ $bom->code }} - {{ $bom->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- المنتج النهائي (يتم قراءته تلقائياً من الـ BOM) -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.finished_product') }}</label>
                                <input type="text" id="finished_product_name" class="form-control" readonly placeholder="{{ __('سيظهر تلقائياً') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- الكمية المخطط إنتاجها -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.planned_qty') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="planned_quantity" id="planned_quantity" class="form-control" value="{{ old('planned_quantity', 1) }}" required>
                            </div>
                        </div>

                        <!-- مخزن صرف الخامات -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.raw_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="raw_material_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('اختر مخزن الخامات') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ app()->getLocale() == 'ar' ? $warehouse->name_ar : $warehouse->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- مخزن الإنتاج تحت التشغيل WIP -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.wip_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="wip_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('اختر مخزن التشغيل') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ app()->getLocale() == 'ar' ? $warehouse->name_ar : $warehouse->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- مخزن المنتج التام -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('home.fg_warehouse') }} <span class="text-danger">*</span></label>
                                <select name="finished_goods_warehouse_id" class="form-control select2" required>
                                    <option value="">{{ __('اختر مخزن المنتج التام') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ app()->getLocale() == 'ar' ? $warehouse->name_ar : $warehouse->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('home.item_notes') }}</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">{{ __('home.planned_materials') }}</h4>

                    <!-- جدول الخامات المخطط استهلاكها بناءً على الـ BOM والكمية المخططة -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="mo_items_table">
                            <thead>
                                <tr class="table-secondary">
                                    <th>{{ __('home.raw_material') }}</th>
                                    <th>{{ __('home.units') }}</th>
                                    <th>{{ __('الكمية لشجرة واحدة') }}</th>
                                    <th>{{ __('إجمالي الكمية المخططة') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="empty_row">
                                    <td colspan="4" class="text-center text-muted">{{ __('يرجى اختيار شجرة المكونات (BOM) أولاً لعرض الخامات المطلوب صرفها') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('حفظ أمر الإنتاج') }}</button>
                        <a href="{{ route('manufacturing_orders.index') }}" class="btn btn-secondary">{{ __('home.cancel') }}</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });

        let bomItemsData = []; // تخزين خامات الـ BOM مؤقتاً في الصفحة
        let baseOutputQty = 1;

        // عند تغير اختيار شجرة المكونات (BOM)
        $('#bom_id').change(function() {
            let bomId = $(this).val();

            if (!bomId) {
                $('#finished_product_name').val('');
                $('#mo_items_table tbody').html('<tr id="empty_row"><td colspan="4" class="text-center text-muted">{{ __('يرجى اختيار شجرة المكونات (BOM) أولاً لعرض الخامات المطلوب صرفها') }}</td></tr>');
                bomItemsData = [];
                return;
            }

            // طلب AJAX لجلب تفاصيل الـ BOM والخامات
            $.ajax({
                url: "{{ url('/get-bom-details') }}/" + bomId,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#finished_product_name').val(response.finished_product_name);
                    baseOutputQty = parseFloat(response.output_quantity) || 1;
                    bomItemsData = response.items;

                    // إعاده إعادة حساب الجدول
                    renderMoItems();
                },
                error: function(xhr) {
                    alert('{{ __('حدث خطأ أثناء جلب بيانات شجرة المكونات') }}');
                }
            });
        });

        // عند تغيير الكمية المخطط إنتاجها، نعيد حساب إجمالي الكمية المخططة لكل خام فوراً
        $('#planned_quantity').on('input change', function() {
            renderMoItems();
        });

        // دالة رسم وحساب كميات الخامات المخططة
        function renderMoItems() {
            if (bomItemsData.length === 0) return;

            let plannedQty = parseFloat($('#planned_quantity').val()) || 0;
            let tbody = $('#mo_items_table tbody');
            tbody.empty();

            $.each(bomItemsData, function(index, item) {
                // المعادلة: (الكمية المطلوب إنتاجها / كمية الـ BOM الأساسية) * كمية المادة الخام في الـ BOM
                let totalMaterialNeeded = (plannedQty / baseOutputQty) * parseFloat(item.quantity);

                let row = `
                    <tr>
                        <td>
                            <input type="hidden" name="items[${index}][raw_material_id]" value="${item.raw_material_id}">
                            <strong>${item.raw_material_name}</strong>
                        </td>
                        <td>${item.unit_name}</td>
                        <td>${item.quantity}</td>
                        <td>
                            <input type="number" step="0.0001" name="items[${index}][planned_quantity]" class="form-control" value="${totalMaterialNeeded.toFixed(4)}" required readonly>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }
    });
</script>
@endsection
