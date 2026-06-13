@extends('layouts.master')

@section('title')
{{ __('report.allBranches') }}
@stop

@section('css')
    <!-- DataTables CSS -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <!-- Notify CSS -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <div class="main-parent">
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('report.allBranches') }}</h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success">
            <br>
            {{ session('success') }}
        </div>
    @endif

    <!-- Content Table -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0 p-3 mb-3">
                    @can('add branch')
                        <div class="col-sm-1 col-md-2">
                            <a class="btn btn-primary btn-sm print-style" href="{{ url('/' . ($page = 'addbranch')) }}">
                                {{ __('users.addbranch') }}
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="card-body mt-1">
                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-bordered table-striped" id="example1" data-page-length='50'
                            style="text-align: center;">
                            <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">{{ __('users.branch_name') }}</th>
                                    <th class="wd-20p border-bottom-0">{{ __('users.Location') }}</th>
                                    <th class="wd-20p border-bottom-0">{{ __('home.tybe') }}</th>
                                    <th class="wd-20p border-bottom-0">{{ __('home.main_branch') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (App\Models\branchs::all() as $key => $branch)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ $branch->place }}</td>
                                        <td>
                                            @if($branch->type == 1)
                                                <span class="badge badge-info-transparent px-3 py-2 font-weight-bold">
                                                    {{ __('home.sub_branch') }}
                                                </span>
                                            @else
                                                <span class="badge badge-success-transparent px-3 py-2 font-weight-bold">
                                                    {{ __('home.main_branch') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($branch->branch_id)
                                                @php
                                                    $mainBranch = App\Models\branchs::find($branch->branch_id);
                                                @endphp
                                                <span class="text-primary font-weight-bold">
                                                    {{ $mainBranch ? $mainBranch->name : __('home.not_found') }}
                                                </span>
                                            @else
                                                <span class="text-muted">--</span>
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

    <!-- Delete Modal -->
    <div class="modal" id="modaldemo8">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('users.delete_user') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.destroy', 'test') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}

                    <div class="modal-body">
                        <p>{{ __('users.delete_confirm_msg') }}</p><br>
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <input class="form-control" name="username" id="username" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('users.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('users.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Scripts -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <!-- Custom Script -->
    <script>
        $('#modaldemo8').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var user_id = button.data('user_id');
            var username = button.data('username');
            var modal = $(this);

            modal.find('.modal-body #user_id').val(user_id);
            modal.find('.modal-body #username').val(username);
        });
    </script>
@endsection