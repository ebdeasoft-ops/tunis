@extends('layouts.master')

@section('title')
    {{ __('hr.document_alerts') }}
@stop

@section('css')
<style>
    /* تأثيرات الأنيميشن والحركة */
    .fade-in { animation: fadeIn 0.8s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .card-alert { border: none; border-radius: 15px; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .card-alert:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
    .card-header { border-radius: 15px 15px 0 0 !important; font-weight: bold; }
</style>
@stop

@section('content')
<br>
<div class="container-fluid fade-in">
    
    <!-- القسم الأول: الوثائق التي توشك على الانتهاء (3 كروت) -->
    <h4 class="mb-3 text-warning font-weight-bold"><i class="fas fa-bell"></i> وثائق توشك على الانتهاء قريباً</h4>
    <div class="row mb-5">
        <!-- تنبيهات الإقامات -->
        <div class="col-md-4">
            <div class="card card-alert border-warning">
                <div class="card-header bg-warning text-white">إقامات توشك على الانتهاء</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse($expiringIqamas as $item)
                            <li class="mb-2 border-bottom pb-2">
                                <strong>{{ (app()->getLocale() == 'ar') ? $item->employee->name_ar : $item->employee->name_en }}</strong><br>
                                <small class="text-danger font-weight-bold">{{ __('hr.expires_on') }}: {{ $item->iqama_expiry_date }}</small>
                            </li>
                        @empty
                            <li class="text-muted">لا توجد إقامات تنتهي قريباً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- تنبيهات رخص العمل -->
        <div class="col-md-4">
            <div class="card card-alert border-info">
                <div class="card-header bg-info text-white">رخص عمل توشك على الانتهاء</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse($expiringWorkPermits as $item)
                            <li class="mb-2 border-bottom pb-2">
                                <strong>{{ (app()->getLocale() == 'ar') ? $item->employee->name_ar : $item->employee->name_en }}</strong><br>
                                <small class="text-danger font-weight-bold">{{ __('hr.expires_on') }}: {{ $item->work_permit_expiry_date }}</small>
                            </li>
                        @empty
                            <li class="text-muted">لا توجد رخص عمل تنتهي قريباً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- تنبيهات عقود العمل -->
        <div class="col-md-4">
            <div class="card card-alert border-primary">
                <div class="card-header bg-primary text-white">عقود توشك على الانتهاء</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse($expiringContracts as $item)
                            <li class="mb-2 border-bottom pb-2">
                                <strong>{{ (app()->getLocale() == 'ar') ? $item->employee->name_ar : $item->employee->name_en }}</strong><br>
                                <small class="text-danger font-weight-bold">{{ __('hr.expires_on') }}: {{ $item->end_date }}</small>
                            </li>
                        @empty
                            <li class="text-muted">لا توجد عقود تنتهي قريباً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- القسم الثاني: الوثائق المنتهية بالفعل (3 كروت كبيرة وواضحة) -->
    <h4 class="mb-3 text-danger font-weight-bold"><i class="fas fa-exclamation-triangle"></i> وثائق منتهية بالفعل (تحتاج لاتخاذ إجراء)</h4>
    <div class="row">
        <!-- إقامات منتهية -->
        <div class="col-md-4">
            <div class="card card-alert border-danger shadow">
                <div class="card-header bg-danger text-white text-center h5 py-3">إقامات منتهية</div>
                <div class="card-body" style="min-height: 150px;">
                    <ul class="list-unstyled mb-0">
                        @forelse($expiredIqamas as $item)
                            <li class="mb-3 border-bottom pb-2">
                                <strong style="font-size: 16px;">{{ (app()->getLocale() == 'ar') ? $item->employee->name_ar : $item->employee->name_en }}</strong><br>
                                <span class="badge badge-danger p-2 mt-1" style="font-size: 13px;">انتهت في: {{ $item->iqama_expiry_date }}</span>
                            </li>
                        @empty
                            <li class="text-success font-weight-bold text-center py-3">✅ ممتاز، لا توجد إقامات منتهية.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- رخص عمل منتهية -->
        <div class="col-md-4">
            <div class="card card-alert border-danger shadow">
                <div class="card-header bg-danger text-white text-center h5 py-3">رخص عمل منتهية</div>
                <div class="card-body" style="min-height: 150px;">
                    <ul class="list-unstyled mb-0">
                        @forelse($expiredWorkPermits as $item)
                            <li class="mb-3 border-bottom pb-2">
                                <strong style="font-size: 16px;">{{ (app()->getLocale() == 'ar') ? $item->employee->name_ar : $item->employee->name_en }}</strong><br>
                                <span class="badge badge-danger p-2 mt-1" style="font-size: 13px;">انتهت في: {{ $item->work_permit_expiry_date }}</span>
                            </li>
                        @empty
                            <li class="text-success font-weight-bold text-center py-3">✅ ممتاز، لا توجد رخص عمل منتهية.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- عقود منتهية -->
        <div class="col-md-4">
            <div class="card card-alert border-danger shadow">
                <div class="card-header bg-danger text-white text-center h5 py-3">عقود عمل منتهية</div>
                <div class="card-body" style="min-height: 150px;">
                    <ul class="list-unstyled mb-0">
                        @forelse($expiredContracts as $item)
                            <li class="mb-3 border-bottom pb-2">
                                <strong style="font-size: 16px;">{{ (app()->getLocale() == 'ar') ? $item->employee->name_ar : $item->employee->name_en }}</strong><br>
                                <span class="badge badge-danger p-2 mt-1" style="font-size: 13px;">انتهى في: {{ $item->end_date }}</span>
                            </li>
                        @empty
                            <li class="text-success font-weight-bold text-center py-3">✅ ممتاز، لا توجد عقود منتهية.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection