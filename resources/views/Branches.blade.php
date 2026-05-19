@extends('layouts.master')
@section('css')

@section('title')
    {{ __('home.branches') }}
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
                <h4 class="content-title mb-0 my-auto">{{ __('home.branches') }}</h4>
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
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">

                <div class="card-body px-3 pb-3 pt-5">
                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered table-striped" id="example1" data-page-length='50'
                            style=" text-align: center;">
                            <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">{{ __('users.branch') }}</th>
                                    <th class="wd-20p border-bottom-0">{{ __('home.Location') }} </th>


                                    <th class="wd-10p border-bottom-0">{{ __('users.Operations') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($branches as $branch)
                                    <?php $i++; ?>

                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ $branch->place }}</td>


                                        <td>
                                            <a class="modal-effect btn btn-sm print-style btn-info" data-effect="effect-scale"
                                                data-branchid="{{ $branch->id }}"
                                                data-branchname="{{ $branch->name }}"
                                                data-branchplace="{{ $branch->place }}" data-toggle="modal"
                                                href="#modaldemo8" title="{{ __('roles.update') }}"><i
                                                    class="las la-pen"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    <h6 class="modal-title"> {{ __('home.update_branch_data') }}</h6><button aria-label="Close"
                        class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form
                    action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'updatebranch')) }}"
                    method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="col">

                            <div class="col">
                                <input type="text" class="form-control" id="id" name="id" hidden>

                                <label for="inputName" class="control-label">{{ __('users.branch_name') }}</label>
                                <input type="text" class="form-control" id="breanchName" name="breanchName"
                                    title="  يرجي ادخال رقم الفاتورة  " required>





                            </div>
                            <div class="col">


                                <label for="inputName" class="control-label">{{ __('users.branch_place') }}</label>
                                <input type="text" class="form-control" id="branchLoction" name="branchLoction"
                                    title="  يرجي ادخال رقم الفاتورة  " required>





                            </div>

                        </div><!-- col-4 -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__( 'home.cancel' )}}</button>
                        <button type="submit" class="btn btn-danger">{{__( 'home.confirm' )}}</button>
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
        var branchplace = button.data('branchplace')
        var branchname = button.data('branchname')
        var branchid = button.data('branchid')
        var modal = $(this)
        modal.find('.modal-body #breanchName').val(branchname);
        modal.find('.modal-body #branchLoction').val(branchplace);
        modal.find('.modal-body #id').val(branchid);
    })
</script>


@endsection
