@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('title')
{{ __('roles.Viewـpermissions') }}
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('roles.permissions') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

{{-- التنبيهات الاحترافية باستخدام SweetAlert2 للغات المشتركة --}}
@if (session()->has('Add'))
<script>
window.onload = function() {
    Swal.fire({
        title: "تم الإضافة بنجاح | Added Successfully",
        text: "تم إضافة الصلاحية بنجاح إلى النظام.",
        icon: "success",
        confirmButtonText: "موافق | OK",
        confirmButtonColor: "#419BB2"
    });
}
</script>
@endif

@if (session()->has('edit'))
<script>
window.onload = function() {
    Swal.fire({
        title: "تم التحديث بنجاح | Updated Successfully",
        text: "تم تحديث بيانات الصلاحية بنجاح.",
        icon: "success",
        confirmButtonText: "موافق | OK",
        confirmButtonColor: "#419BB2"
    });
}
</script>
@endif

@if (session()->has('delete'))
<script>
window.onload = function() {
    Swal.fire({
        title: "تم الحذف بنجاح | Deleted Successfully",
        text: "تم حذف الصلاحية المحددة نهائياً.",
        icon: "error",
        confirmButtonText: "موافق | OK",
        confirmButtonColor: "#FF4F1F"
    });
}
</script>
@endif

<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right mb-5">
                            <a class="btn btn-primary btn-md print-style p-1" href="{{ route('roles.create') }}">
                                {{ __('home.Add') }}
                                <svg style="width: 18px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                    <path fill="none"
                                        d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mg-b-0 text-md-nowrap table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('roles.name_permission') }}</th>
                                <th>{{ __('home.operations') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key => $role)
                            {{-- الفحص الصحيح لحظر ظهور حساب الـ Admin منعاً للعبث بالصلاحيات الرئيسية --}}
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a style="background-color: #419BB2; font-size:15px; border:none;"
                                        class="btn btn-success btn-sm text-white"
                                        href="{{ route('roles.show', $role->id) }}">
                                        {{ __('roles.display') }}
                                    </a>

                                    <a style="background-color: #FF4F1F; font-size:15px; border:none;"
                                        class="btn btn-primary btn-sm text-white"
                                        href="{{ route('roles.edit', $role->id) }}">
                                        {{ __('roles.update') }}
                                    </a>

                                    @if ($role->name !== 'owner')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline', 'id' => 'delete-form-'.$role->id]) !!}
                                    <button type="button" class="btn btn-danger btn-sm" style="font-size:15px" onclick="confirmDelete({{ $role->id }})">
                                        {{ __('roles.delete') }}
                                    </button>
                                    {!! Form::close() !!}
                                    @endif
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
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(roleId) {
    Swal.fire({
        title: "هل أنت متأكد؟ | Are you sure?",
        text: "لن يمكنك التراجع عن حذف هذه الصلاحية!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF4F1F",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "نعم، احذفها | Yes, delete it!",
        cancelButtonText: "إلغاء | Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + roleId).submit();
        }
    });
}
</script>
@endsection