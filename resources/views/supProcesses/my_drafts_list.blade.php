@extends('layouts.master')

@section('title') المسودات المخزنة المعلقة @stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0">المسودات السابقة (الحركات غير المعتمدة)</h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mg-b-0 text-md-nowrap text-center">
                        <thead>
                            <tr>
                                <th>رقم المسودة</th>
                                <th>الفرع المستلِم</th>
                                <th>الموظف المستلِم</th>
                                <th>إجمالي التكلفة</th>
                                <th>تاريخ الحفظ</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myDrafts as $draft)
                            <tr>
                                <th scope="row">#{{ $draft->id }}</th>
                                <td>{{ $draft->branchTo->name ?? 'فرع غير معروف' }}</td>
                                <td>{{ $draft->userTo->name ?? 'موظف غير معروف' }}</td>
                                <td><span class="badge badge-info">{{ number_format($draft->Totalcost, 2) }}</span></td>
                                <td>{{ $draft->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/sendProduct?draft_id=' . $draft->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="las la-edit"></i> استكمال وتعديل المسودة
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-muted py-4">لا توجد مسودات محفوظة حالياً.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection