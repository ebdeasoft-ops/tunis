@extends('layouts.master')
@section('css')

@section('title')
    {{ __('users.usersList') }}
@stop

<!-- Internal Data table css -->

<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('users.usersList') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            <br>
            {{ session('success') }}
        </div>
    @endif

    <!-- row opened -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                @can('Create a vendor')

                    <div class="col-sm-3 col-md-4 mb-3">
                        <a class="btn btn-primary print-style p-1 py-2" href="{{ route('users.create') }}">
                            {{ __('users.adduser') }}
                            <svg style="width: 17px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </a>
                    </div>
                    @endcan
                </div>
                <div class="card-body mt-3">
                    <div class="table-responsive hoverable-table pb-2">
                        <table class="table table-hover table-bordered table-striped text-center mb-5" id="example1" data-page-length='50'
                            style=" text-align: center;">
                            <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">{{ __('users.username') }}</th>
                                    <th class="wd-20p border-bottom-0">{{ __('users.email') }} </th>
                                    <th class="wd-5p border-bottom-0"> {{ __('users.status') }}</th>
                                    <th class="wd-15p border-bottom-0"> {{ __('users.branch') }}</th>

                                    <th class="wd-15p border-bottom-0"> {{ __('users.userType') }}</th>
                                    <th class="wd-10p border-bottom-0">{{ __('users.Operations') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($data as $key => $user)
                                    <?php $i++; ?>
                                    @if (!empty($user->roles_name)&& !in_array("Admin",$user->roles_name))

                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->active == 1)
                                                <span class="label text-success d-flex">
                                                    <div class="dot-label bg-success ml-1"></div>&nbsp;&nbsp;&nbsp;
                                                    <span style="font-size: 14px;margin-top:6px">{{ __('users.active') }}</span>
                                                </span>
                                            @else
                                                <span class="label text-danger d-flex">
                                                    <div class="dot-label bg-danger ml-1"></div>&nbsp;&nbsp;&nbsp;
                                                    <span style="font-size: 14px;margin-top:6px">{{ __('users.notactive') }}</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <h5 class="badge badge-success">{{ $user->branch->name }}</h5>
                                        </td>

                                        <td>
                                            @if (!empty($user->roles_name))
                                                @foreach ($user->roles_name as $v)
                                                    <label class="badge badge-success">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <a style="background-color: #419BB2;" href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-info"
                                                title="تعديل"><i class="las la-pen"></i></a>


                                         
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/div-->

    <!-- Modal effects -->
    <div class="modal" id="modaldemo8">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف المستخدم</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('users.destroy', 'test') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>هل انت متاكد من عملية الحذف ؟</p><br>
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <input class="form-control" name="username" id="username" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
            </div>
        </div>

    </div>
    <!-- /row -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<!-- Internal Modal js-->
<script src="{{ URL::asset('assets/js/modal.js') }}"></script>

<script>
    $('#modaldemo8').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var user_id = button.data('user_id')
        var username = button.data('username')
        var modal = $(this)
        modal.find('.modal-body #user_id').val(user_id);
        modal.find('.modal-body #username').val(username);
    })
</script>


@endsection
