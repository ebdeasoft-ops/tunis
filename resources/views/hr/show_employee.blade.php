@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title')
    {{ __('hr.show_employees') }}
@stop


@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('hr.show_employees') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">


        <div class="col-lg-12 col-md-12">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>خطا</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            <div class="card">


                <div class="card-body py-5">
                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover table-hover table-bordered table-striped" id="example1" data-page-length='50'
                            style=" text-align: center;">

                            <thead>

                                <tr>
                                    <th># </th>
                                    <th>{{ __('home.date') }} </th>
                                    <th>{{ __('hr.name') }}</th>
                                    <th>{{ __('hr.Id') }}</th>
                                    <th>{{ __('hr.email') }}</th>
                                    <th>{{ __('hr.phone') }}</th>
                                    <th>{{ __('hr.department') }}</th>
                                    <th>{{ __('hr.salary') }}</th>
                                    <th>{{ __('hr.age') }}</th>
                                    <th>{{ __('hr.sex') }}</th>
                                    <th>{{ __('home.operations') }}</th>



                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>

                                @foreach (App\Models\employee::get() as $employee)
                                    <?php $i++; ?>

                                    <td>{{ $i }}</td>
                                    <td>{{ $employee->created_at }}</td>
                                    <td>{{ __('hr.phone') == 'رقم الجوال' ? $employee->name_ar : $employee->name_en }}</td>
                                    <td>{{ $employee->personal_identification }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ __('hr.phone') == 'رقم الجوال' ? $employee->departments->name_ar : $employee->departments->name_en }}
                                    </td>
                                    <td>{{ $employee->salary }}</td>
                                    <td>{{ $employee->old }}</td>
                                    <td>{{ $employee->sex == 'male' ? __('hr.male') : __('hr.female') }}
                                    </td>
                                    <td> <a style="background-color: #419BB2;font-size:15px" class="btn btn-sm btn-info" href="{{ 'updateEmployee/' . $employee->id }}"><i
                                                class="las la-pen"></i> </a>
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
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')


<!-- Internal Nice-select js-->
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js') }}"></script>

<!--Internal  Parsley.min js -->
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<!-- Internal Form-validation js -->
<script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>
<script>
    $(document).ready(function() {
        $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
       
    });
</script>
@endsection
