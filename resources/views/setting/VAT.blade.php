@extends('layouts.master')
@section('css')
    <!--Internal  Font Awesome -->
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!--Internal  treeview -->
    <link href="{{ URL::asset('assets/plugins/treeview/treeview-rtl.css') }}" rel="stylesheet" type="text/css" />



@section('title')
    {{ __('home.AVTSHOW') }}@stop


@endsection
@section('page-header')
    <div class="main-parent">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between parent-heading">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">{{ __('home.AVTSHOW') }}
                </div>
            </div>
        </div>
        <!-- breadcrumb -->


        @if (session()->has('detete_vat'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <br>

                <strong>{{ session()->get('detete_vat') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('create_vat'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <br>

                <strong>{{ session()->get('create_vat') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif


        @if (session()->has('edit_vat'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <br>

                <strong>{{ session()->get('edit_vat') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif




        <!-- row -->
        <div class="row row-sm">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-right">

                                    <a style="background-color: #419BB2;font-size:17px" class="modal-effect btn btn-primary p-1" data-effect="effect-scale" data-toggle="modal"
                                        href="#create" title="{{ __('home.Add') }}">{{ __('home.create_avt') }} <i
                                            class="las la-pen"></i></a>
                                </div>
                            </div>
                            <br>
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mg-b-0 text-md-nowrap table-hover table-bordered table-striped">
                                <thead>
                                    <tr>


                                        <th>#</th>
                                        <th>{{ __('home.name_avt') }}</th>
                                        <th>{{ __('home.avt_rate') }}</th>
                                        <th>{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    ?>
                                    @foreach (App\Models\Avt::where('id','!=',3)->get() as $role)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ __('home.name_avt') == 'اسم الضريبة' ? $role->name_ar : $role->name_en }}
                                            </td>
                                            <td>{{ $role->AVT * 100 }} %</td>
                                            <td>

                                                <a style="background-color: #FF4F1F" class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                    data-id="{{ $role->id }}"
                                                    data-section_name="{{ __('home.name_avt') == 'اسم الضريبة' ? $role->name_ar : $role->name_en }}"
                                                    data-rate="{{ $role->AVT }}" data-toggle="modal"
                                                    href="#exampleModal2" title="{{ __('roles.update') }}"><i
                                                        class="las la-pen"></i></a>



                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->

    </div>
    <!-- create -->
    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('home.create_avt') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'New_avt')) }}"
                        method="post" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="form-group">

                            <label for="recipient-name" class="col-form-label"> {{ __('home.name_avt_ar') }} </label>

                            <input class="form-control" name="name_avt_ar" id="name_avt_ar" type="text">
                        </div>
                        <div class="form-group">

                            <label for="recipient-name" class="col-form-label"> {{ __('home.name_avt_en') }} </label>

                            <input class="form-control" name="name_avt_en" id="name_avt_en" type="text">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">{{ __('home.avt_rate') }}</label>
                            <input type="number" class="form-control" id="vat_rate" name="vat_rate" required></input>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('home.confirm') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('home.cancel') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>





    <!-- update -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('home.update_vat') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form
                        action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'update_vat')) }}"
                        method="post" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="form-group">

                            <input type="hidden" name="id" id="id" value="">
                            <label for="recipient-name" class="col-form-label"> {{ __('home.name_avt') }} </label>

                            <input class="form-control" name="vat_name" id="vat_name" type="text" readonly>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">{{ __('home.avt_rate') }}</label>
                            <input type="number" class="form-control" id="vat_rate" name="vat_rate" required></input>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('home.confirm') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('home.cancel') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- delete -->
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.delete_vat') }} </h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form
                    action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'destory_avt')) }}"
                    method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{ __('home.confirm_delete_vat') }}</p><br>

                        <input type="hidden" name="id" id="id" value="">

                        <input class="form-control" name="vat_name" id="vat_name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">{{ __('home.confirm') }}</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    <!-- main-content closed -->
    </div>
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)

            var id = button.data('id')
            var section_name = button.data('section_name')
            var rate = button.data('rate') * 100
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #vat_name').val(section_name);
            modal.find('.modal-body #vat_rate').val(rate);
        })
    </script>

    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var section_name = button.data('section_name')
            var rate = button.data('rate')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #vat_name').val(section_name);
            modal.find('.modal-body #vat_rate').val(rate);
        })
    </script>

@endsection
