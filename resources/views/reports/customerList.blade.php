@extends('layouts.master')
@section('css')

@section('title')
{{__('home.customerList')}}
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
                <h4 class="content-title mb-0 my-auto">{{__('home.customerList')}}</h4>
            </div>
                   
                          

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
            <div class="card py-2">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                    </div>
                </div>
                <div class="card-body">
   <div class="table-responsive  ">
                        <table style="border:2px solid rgba(0,0,0,.3);" class="table text-md-nowrap mb-0 table-striped invoice-table text-center">
<thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.clietName')}}</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.phone')}}</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.Location')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.depit_oping')}} </th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit_oping')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit')}} </th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.debit')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.current balance')}} </th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php $i = 0 ;
                                $totalcredit=0;
                                $totaldebit=0;
                                ?>
                                @foreach (App\Models\financial_accounts::where('orginal_type',1)->orderBy('debtor_current', 'desc')->get() as $user)
                                <?php $i++ ;
                                $customer=App\Models\customers::find($user->orginal_id);
                                
                                ?>

                                <tr>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $i}}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $user->name}}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $customer->phone??'' }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $customer->address??'' }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->debtor_opening }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->creditor_opening }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->debtor_current }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->creditor_current }}</td>
                                    @if($user->debtor_current-$user->creditor_current ==0)
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                    @elseif($user->debtor_current-$user->creditor_current >0)
                                   <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.credit')}} ( {{round($user->debtor_current-$user->creditor_current,2)}} ) {{__('home.SAR')}}</td>
                                    @else
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.debit')}} ( {{round(($user->debtor_current-$user->creditor_current)*-1,2)}} ) {{__('home.SAR')}}</td>
                                     @endif

<?php
  $totalcredit+=$user->creditor_current;
                                $totaldebit+=$user->debtor_current;

?>


                                </tr>

                                @endforeach
                                
                                
          <tr>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">-</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">-</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">-</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">-</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">-</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">-</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$totaldebit }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$totalcredit}}</td>
                                    @if($totaldebit-$totalcredit ==0)
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                    @elseif($totaldebit-$totalcredit >0)
                                   <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.credit')}} ( {{round($totaldebit-$totalcredit,2)}} ) {{__('home.SAR')}}</td>
                                    @else
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.debit')}} ( {{round(($totaldebit-$totalcredit)*-1,2)}} ) {{__('home.SAR')}}</td>
                                     @endif




                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>

                </div>
            </div>
        </div>
    </div>
    <!--/div-->

    <!-- Modal effects -->

<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<!-- <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script> -->
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!-- <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script> -->
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
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
<script>
    $(document).ready(function() {

        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });

    });
</script>

@endsection