@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        .group-container { border: 1px solid #e1e5ef; border-radius: 8px; margin-bottom: 25px; background: #fff; transition: 0.3s; }
        .group-header { background: #419BB2; color: white; padding: 12px 15px; border-radius: 7px 7px 0 0; display: flex; justify-content: space-between; align-items: center; }
        /* تمييز قسم الحسابات باللون الأخضر المالي */
        .header-accounting { background: #28a745 !important; }
        
        .group-body { padding: 15px; display: flex; flex-wrap: wrap; }
        .permission-item { width: 25%; padding: 8px; border-bottom: 1px solid #f8f9fa; }
        .permission-item:hover { background-color: #f0faff; border-radius: 4px; }
        .search-box { border: 2px solid #419BB2; border-radius: 20px; height: 45px; padding-right: 20px; font-size: 16px; }
        .ckbox span { color: #419BB2; font-weight: 500; cursor: pointer; padding-right: 5px; }
        .btn-select-group { background: rgba(255,255,255,0.2); border: 1px solid white; color: white; font-size: 11px; padding: 2px 8px; border-radius: 4px; }
        .btn-select-group:hover { background: white; color: #419BB2; }
    </style>
@section('title')
    {{ __('roles.add_permisssion') }}
@stop
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto" style="color: white">{{ __('roles.add_permisssion') }}</h4>
        </div>
    </div>
@endsection

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>خطأ!</strong> يرجى مراجعة المدخلات.
        </div>
    @endif

    {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
    
    <div class="card mg-b-20 pt-5 px-3">
        <div class="card-body">
            <div class="row mg-b-30">
                <div class="col-md-4">
                    <label class="font-weight-bold">{{ __('roles.name_permission') }}</label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'مثال: مدير مبيعات فرع']) !!}
                </div>
                <div class="col-md-8 pt-4">
                    <input type="text" id="search-permissions" class="form-control search-box" placeholder="🔍 ابحث عن صلاحية (مثلاً: قيود، بنك، مبيعات)...">
                </div>
            </div>

            @php
                // المصفوفة المحدثة تشمل الحسابات
                $groups = [
                    'المبيعات' => ['مبيعات', 'عملاء', 'عرض سعر', 'تسعيرة', 'invoice'],
                    'المشتريات والموردين' => ['مشتريات', 'مورد', 'vendor'],
                    'الحسابات والمالية' => ['حساب', 'بنك', 'صندوق', 'سند', 'قيد', 'خزينة', 'صرف', 'قبض', 'شيك', 'مالية'],
                    'المنتجات والمخازن' => ['منتج', 'مخزن', 'كمية', 'استلام', 'ارسال', 'product'],
                    'التقارير' => ['تقرير', 'ميزانية', 'ارباح', 'احصائيات'],
                    'الموارد البشرية' => ['موظف', 'راتب', 'حضور', 'بشرية', 'user'],
                    'الإعدادات والربط' => ['صلاحية', 'فرع', 'اعدادات', 'role', 'permission']
                ];

                $used_ids = [];
            @endphp

            @foreach($groups as $groupName => $keywords)
                <div class="group-container section-wrapper">
                    <div class="group-header {{ $groupName == 'الحسابات والمالية' ? 'header-accounting' : '' }}">
                        <span>
                            <i class="fa {{ $groupName == 'الحسابات والمالية' ? 'fa-university' : 'fa-folder' }} ml-2"></i> 
                            {{ $groupName }}
                        </span>
                        <button type="button" class="btn btn-select-group select-all-in-group">تحديد كل القسم</button>
                    </div>
                    <div class="group-body">
                        @foreach ($permission as $value)
                            @php
                                $match = false;
                                foreach($keywords as $word) {
                                    if(str_contains(strtolower($value->name_ar), $word) || str_contains(strtolower($value->name), $word)) $match = true;
                                }
                                if($value->name == 'Create a new branch' || $value->name == 'Create a vendor') $match = false;
                            @endphp

                            @if($match)
                                @php $used_ids[] = $value->id; @endphp
                                <div class="permission-item">
                                    <label class="ckbox">
                                        {{ Form::checkbox('permission[]', $value->id, false, ['class' => 'name p-checkbox']) }}
                                        <span>{{ app()->getLocale() == 'ar' ? $value->name_ar : $value->name }}</span>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="group-container section-wrapper">
                <div class="group-header" style="background: #6c757d;">
                    <span><i class="fa fa-list ml-2"></i> صلاحيات متنوعة</span>
                    <button type="button" class="btn btn-select-group select-all-in-group">تحديد الكل</button>
                </div>
                <div class="group-body">
                    @foreach ($permission as $value)
                        @if(!in_array($value->id, $used_ids))
                            <div class="permission-item">
                                <label class="ckbox">
                                    {{ Form::checkbox('permission[]', $value->id, false, ['class' => 'name p-checkbox']) }}
                                    <span>{{ app()->getLocale() == 'ar' ? $value->name_ar : $value->name }}</span>
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mg-t-30">
                <button type="submit" class="btn btn-main-primary btn-lg px-5 shadow-sm">
                    {{ __('home.Add') }} <i class="fa fa-plus-circle mr-2"></i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('.select-all-in-group').click(function() {
            var container = $(this).closest('.group-container');
            var checkboxes = container.find('.p-checkbox');
            var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
            checkboxes.prop('checked', !allChecked);
            $(this).text(allChecked ? 'تحديد كل القسم' : 'إلغاء تحديد القسم');
        });

        $("#search-permissions").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".permission-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            $(".group-container").each(function() {
                var visibleItems = $(this).find(".permission-item:visible").length;
                $(this).toggle(visibleItems > 0);
            });
        });

        setTimeout(function() { $('.alert').fadeOut(500); }, 4000);
    });
</script>
@endsection