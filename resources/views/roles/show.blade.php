@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        .group-container { border: 1px solid #e1e5ef; border-radius: 8px; margin-bottom: 20px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .group-header { background: #23395D; color: white; padding: 10px 15px; border-radius: 7px 7px 0 0; font-weight: bold; }
        /* تمييز قسم الحسابات باللون الأخضر */
        .header-accounting { background: #28a745 !important; } 
        
        .group-body { padding: 15px; display: flex; flex-wrap: wrap; }
        .permission-badge { 
            background: #f0faff; 
            color: #419BB2; 
            padding: 8px 12px; 
            margin: 5px; 
            border-radius: 20px; 
            border: 1px solid #d1e9f0;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }
        .permission-badge i { margin-left: 6px; font-size: 10px; }
        .role-title-card { background: #419BB2; color: white; padding: 15px; border-radius: 8px; margin-bottom: 25px; display: flex; align-items: center; }
    </style>
@section('title')
    {{ __('roles.Viewـpermissions') }}
@stop
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto" style="color: white">{{ __('roles.Viewـpermissions') }}</h4>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mg-b-20 py-4 px-4">
            <div class="card-body">
                
                <div class="mg-b-20 text-left">
                    <a class="btn btn-primary btn-sm shadow-sm" style="background-color: #23395D;" href="{{ route('roles.index') }}">
                        <i class="fa fa-arrow-right ml-1"></i> {{ __('users.back') }}
                    </a>
                </div>

                <div class="role-title-card">
                    <i class="fa fa-user-tag fa-2x ml-3"></i>
                    <div>
                        <small class="d-block text-white-50">الدور الوظيفي:</small>
                        <h4 class="mb-0">{{ $role->name }}</h4>
                    </div>
                </div>

                @php
                    // المصفوفة المحدثة لتشمل الحسابات والمالية
                    $groups = [
                        'المبيعات' => ['مبيعات', 'عملاء', 'عرض سعر', 'تسعيرة', 'invoice'],
                        'المشتريات والموردين' => ['مشتريات', 'مورد', 'vendor'],
                        'الحسابات والمالية' => ['حساب', 'بنك', 'صندوق', 'سند', 'قيد', 'خزينة', 'صرف', 'قبض', 'شيك'],
                        'المنتجات والمخازن' => ['منتج', 'مخزن', 'كمية', 'استلام', 'ارسال', 'product'],
                        'التقارير' => ['تقرير', 'ميزانية', 'ارباح'],
                        'الموارد البشرية' => ['موظف', 'راتب', 'حضور', 'بشرية', 'user'],
                        'الإعدادات والربط' => ['صلاحية', 'فرع', 'اعدادات', 'role', 'permission', 'Theta']
                    ];
                    $shown_ids = [];
                @endphp

                @if(!empty($rolePermissions))
                    @foreach($groups as $groupName => $keywords)
                        @php
                            $filteredPermissions = $rolePermissions->filter(function($p) use ($keywords) {
                                foreach($keywords as $word) {
                                    if(str_contains(strtolower($p->name_ar), $word) || str_contains(strtolower($p->name), $word)) return true;
                                }
                                return false;
                            });
                        @endphp

                        @if($filteredPermissions->count() > 0)
                            <div class="group-container">
                                <div class="group-header {{ $groupName == 'الحسابات والمالية' ? 'header-accounting' : '' }}">
                                    <i class="fa {{ $groupName == 'الحسابات والمالية' ? 'fa-university' : 'fa-folder-open' }} ml-2"></i> 
                                    {{ $groupName }}
                                </div>
                                <div class="group-body">
                                    @foreach($filteredPermissions as $v)
                                        @php $shown_ids[] = $v->id; @endphp
                                        <div class="permission-badge">
                                            <i class="fa fa-check-circle"></i>
                                            {{ app()->getLocale() == 'ar' ? $v->name_ar : $v->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @php
                        $others = $rolePermissions->whereNotIn('id', $shown_ids);
                    @endphp
                    @if($others->count() > 0)
                        <div class="group-container">
                            <div class="group-header" style="background: #6c757d;">
                                <i class="fa fa-th-large ml-2"></i> صلاحيات متنوعة
                            </div>
                            <div class="group-body">
                                @foreach($others as $v)
                                    <div class="permission-badge">
                                        <i class="fa fa-check-circle"></i>
                                        {{ app()->getLocale() == 'ar' ? $v->name_ar : $v->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning text-center">لا توجد صلاحيات مسندة لهذا الدور بعد.</div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection