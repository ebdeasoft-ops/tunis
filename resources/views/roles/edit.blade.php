@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        .group-container { border: 1px solid #e1e5ef; border-radius: 8px; margin-bottom: 25px; background: #fff; transition: 0.3s; }
        .group-header { background: #419BB2; color: white; padding: 12px 15px; border-radius: 7px 7px 0 0; display: flex; justify-content: space-between; align-items: center; font-weight: bold; }
        /* تمييز قسم الحسابات باللون الأخضر المالي */
        .header-accounting { background: #28a745 !important; }
        
        .group-body { padding: 15px; display: flex; flex-wrap: wrap; }
        .permission-item { width: 25%; padding: 8px; border-bottom: 1px solid #f8f9fa; }
        .permission-item:hover { background-color: #f0faff; border-radius: 4px; }
        .search-box { border: 2px solid #419BB2; border-radius: 20px; height: 45px; padding-right: 20px; font-size: 16px; margin-bottom: 25px; }
        .ckbox span { color: #419BB2; font-weight: 500; cursor: pointer; padding-right: 5px; }
        .btn-select-group { background: rgba(255,255,255,0.2); border: 1px solid white; color: white; font-size: 11px; padding: 2px 8px; border-radius: 4px; cursor: pointer; }
        .btn-select-group:hover { background: white; color: #419BB2; }
    </style>
@endsection

@section('content')
    {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $role->id]]) !!}
    <div class="card mg-b-20 px-3 pt-4">
        <div class="card-body">
            <div class="row mg-b-20">
                <div class="col-md-4">
                    <label class="font-weight-bold">{{ __('roles.name_permission') }}</label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'اسم الدور']) !!}
                </div>
                <div class="col-md-8 pt-4">
                    <input type="text" id="search-permissions" class="form-control search-box" placeholder="🔍 ابحث عن صلاحية أو قسم مالية، مبيعات...">
                </div>
            </div>

            @php
                $groups = [
                    'المبيعات' => ['مبيعات', 'عملاء', 'عرض سعر', 'تسعيرة', 'invoice'],
                    'المشتريات والموردين' => ['مشتريات', 'مورد', 'vendor'],
                    'الحسابات والمالية' => ['حساب', 'بنك', 'صندوق', 'سند', 'قيد', 'خزينة', 'صرف', 'قبض', 'شيك'],
                    'المنتجات والمخازن' => ['منتج', 'مخزن', 'كمية', 'استلام', 'ارسال', 'product'],
                    'التقارير' => ['تقرير', 'ميزانية', 'ارباح'],
                    'الموارد البشرية' => ['موظف', 'راتب', 'حضور', 'بشرية', 'user'],
                    'الإعدادات والصلاحيات' => ['صلاحية', 'مستخدم', 'فرع', 'اعدادات', 'role', 'permission']
                ];
                $used_ids = [];
            @endphp

            @foreach($groups as $groupName => $keywords)
                <div class="group-container section-to-filter">
                    <div class="group-header {{ $groupName == 'الحسابات والمالية' ? 'header-accounting' : '' }}">
                        <span>
                            <i class="fa {{ $groupName == 'الحسابات والمالية' ? 'fa-university' : 'fa-folder-open' }} ml-2"></i> 
                            {{ $groupName }}
                        </span>
                        <button type="button" class="btn btn-select-group select-all-in-group">تحديد القسم</button>
                    </div>
                    <div class="group-body">
                        @foreach($permission as $value)
                            @php
                                $match = false;
                                foreach($keywords as $word) {
                                    if(str_contains(strtolower($value->name_ar), $word) || str_contains(strtolower($value->name), $word)) $match = true;
                                }
                            @endphp

                            @if($match)
                                @php $used_ids[] = $value->id; @endphp
                                <div class="permission-item">
                                    <label class="ckbox">
                                        {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'p-checkbox']) }}
                                        <span>{{ app()->getLocale() == 'ar' ? $value->name_ar : $value->name }}</span>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="group-container section-to-filter">
                <div class="group-header" style="background: #6c757d;">
                    <span><i class="fa fa-list ml-2"></i> صلاحيات متنوعة</span>
                    <button type="button" class="btn btn-select-group select-all-in-group">تحديد الكل</button>
                </div>
                <div class="group-body">
                    @foreach($permission as $value)
                        @if(!in_array($value->id, $used_ids))
                            <div class="permission-item">
                                <label class="ckbox">
                                    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'p-checkbox']) }}
                                    <span>{{ app()->getLocale() == 'ar' ? $value->name_ar : $value->name }}</span>
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="text-center mg-t-30">
                <button type="submit" class="btn btn-main-primary btn-lg px-5 shadow-sm">{{ __('roles.update') }}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // 1. تحديد/إلغاء تحديد كل القسم
        $('.select-all-in-group').click(function() {
            var container = $(this).closest('.group-container');
            var checkboxes = container.find('.p-checkbox');
            var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
            checkboxes.prop('checked', !allChecked);
            $(this).text(allChecked ? 'تحديد القسم' : 'إلغاء التحديد');
        });

        // 2. البحث الذكي
        $("#search-permissions").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".permission-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            $(".group-container").each(function() {
                var hasVisible = $(this).find(".permission-item:visible").length > 0;
                $(this).toggle(hasVisible);
            });
        });
    });
</script>
@endsection