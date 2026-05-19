@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('home.newexpense') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading parent-label">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.newexpense') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
              
        </div>
          <button  style=" height: 40px;font-weight:400 !important;
                                                 width: 100px;
                                                 font-size:13px" class="modal-effect btn btn-sm btn-info  button-eng" data-effect="effect-scale" data-toggle="modal" href="#updateinvoicebyidmodale" title="تحديد"><i
                                    class="las"> {{ __('home.update_decument') }}</i>
                                        <svg style="width:16px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.927,5.828h-4.41l-1.929-1.961c-0.078-0.079-0.186-0.125-0.297-0.125H4.159c-0.229,0-0.417,0.188-0.417,0.417v1.669H2.073c-0.229,0-0.417,0.188-0.417,0.417v9.596c0,0.229,0.188,0.417,0.417,0.417h15.854c0.229,0,0.417-0.188,0.417-0.417V6.245C18.344,6.016,18.156,5.828,17.927,5.828 M4.577,4.577h6.539l1.231,1.251h-7.77V4.577z M17.51,15.424H2.491V6.663H17.51V15.424z"></path>
                                        </svg>
                                    </button>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')

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

    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <form enctype="multipart/form-data" method="POST" role="search" name="form-name" id='formdata' autocomplete="off">
                        {{ csrf_field() }}

                        <input type="hidden" id="token_search" value="{{ csrf_token() }}">





                        <div class='row'>

                            <div class="col-lg-2 mb-2" id="type">
                                <label class="parent-label"> {{ __('home.paymentmethod') }} </label>
                                <div class="input-group">
                                    <select class="form-control parent-input" name="pay" id="pay" required>
                                        <option value="Cash"> {{ __('report.cash') }}</option>
                                        <option value="Shabka"> {{ __('report.shabka') }} </option>
                                        <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="inputName" class="parent-label"> {{ __('accountes.Theamountpaid') }}
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control parent-input" id="cashreceived" name="cashreceived" value=0 title="يرجي ادخال الكمية  " required onkeyup="moneyconvertToNumber()">
                                </div>
                            </div>

                            <div class="col-lg-2 mb-2" id="type">
                                <label class="parent-label"> {{ __('report.enpenses_reason') }} </label>
                                <div class="input-group">
                                    <select class="form-control parent-input" name="reasone" id="reasone" required>

                                        @foreach (App\Models\Expenses_reasons::get() as $Expenses_reason)
                                        <option value="{{ $Expenses_reason->id }}">
                                            {{ App::getLocale()=='ar'?$Expenses_reason->expenses_reason:$Expenses_reason->expenses_reason_en }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                              <div class="col-lg-2 parent-label">
                  <label> {{__('home.attachments')}}</label>
                  <input autocomplete="off" onchange="readURL(this)" type="file" id="attachments" name="attachments" class="form-control">
                
               </div>
                            <div class="col-lg-2 parent-label" id="start_at">
                                <label for="exampleFormControlSelect1"> {{ __('report.date') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="date" id="date" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                              <div class="col-lg-2 parent-label">
                                <label for="inputName" class="control-label parent-label">{{ __('home.notesClient') }}
                                </label>
                                <input autocomplete="off" type="text" class="form-control parent-input" id="notes" name="notes" title="يرجي ادخال ملاحظات   "  
                                  value ="- " >         
                            </div>

                            <br>

                        </div>
                        <br>
                    <div class="d-flex justify-content-center">
                        <button type='submit' class="btn btn-success print-style p-1" id="button_1">
                            {{ __('home.savedecoument') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                        <br>

                    </div>
                                        </form>

                    <br>
                    <br>


                </div>

            </div>

        </div>
        <div class="col-xl-12">
            <div style="border-radius: 10px" class="card mg-b-20 p-2">
                <div class="card-body pb-0">
                    <div class="table-responsive pt-4 pb-2 mt-4">
                        <table id="example" class="table text-md-nowrap text-center our-table" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>

                                    <th class="border-bottom-0"> {{ __('accountes.user') }}</th>
                                    <th class="border-bottom-0">{{ __('accountes.Reasonforspendingmoney') }}</th>
                                    <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }}</th>
                                    <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                    <th class="border-bottom-0">{{ __('home.operations') }}</th>
                                </tr>
                            </thead>

                            <tbody>


                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>

                            </tbody>
                        </table>
                    </div>
                    <br />
                    <div class="d-flex justify-content-center">


                        <div class="row  d-flex justify-content-end mt-3">
                            <form action="{{ '/' . ($page = 'print_expansedecoument') }}" method="POST" role="search" autocomplete="off">
                                {{ csrf_field() }}



                                <div class='col ' id="printdiv">
                                    <input type="number" class="form-control " name="id" id="id" title=" رقم الفاتورة " readonly required hidden>


                                    <button style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px" type="submit" class="btn btn-success p-1 px-2 fw-bolder">
                                        {{ __('home.print') }}
                                        <svg style="width: 15px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                            <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                        </svg>
                                    </button>





                                </div>


                            </form>
                        </div>
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

    <div class="modal p-3" id="updateinvoicebyidmodale">
        <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
            <div class="modal-content modal-content-demo p-3">
                <form>
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ __('home.update_decument') }} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    {{ csrf_field() }}
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-6 col-md-4 mb-2">
                            <label style="font-size: 12px;" for="inputName" class="control-label parent-label"> {{ __('home.decoumentNo') }}</label>
                            <input style="height:32px" type="text" class="form-control parent-input" id="updateinvoicebyid" name="name" title="{{ __('supprocesses.name') }}" required>
                        </div>


                    </div>


                    <br>
                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" id="getinvoiceupdate">
                            {{ __('home.search') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                    </div>
            </div>

        </div>
    </div>
</div>


  <div class="modal fade product-selection" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" id="SearchProduct" name="SearchProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
            <div class="modal-dialog modal-xl" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" role="document">
                <div class="modal-content">
                  
                    <div class="modal-body" style="justify-content: center;">


 <center><img style="width:250px;height:250px;" class="custom_img" src="{{ asset('assets/admin/uploads/done.png') }}" >
                        
</center>



                          
                        </div>

                     
                    </div>


                </div>
            </div>

        </div>


<!-- edit -->
<div class="modal fade" id="increaseProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div style="margin: 5% !important;" class="modal-dialog modal-special" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('home.updatedecoument') }}</h5>
                <button type="button" class="close choose-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{ csrf_field() }}
                <div class="form-group">
                    <input type="hidden" name="transactionId" id="transactionId" value="">

                </div>



                <div class="row">


                    <div class="col" id="type">
                        <p class="mg-b-10 parent-label"> {{ __('home.paymentmethod') }} </p>
                        <select class="form-control parent-input " name="payupdate" id="payupdate" required>
                            <option value="Cash"> {{ __('report.cash') }}</option>
                            <option value="Shabka "> {{ __('report.shabka') }} </option>
                            <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>


                        </select>
                    </div>
                    <div class="col" id="type">
                        <label class="parent-label"> {{ __('report.enpenses_reason') }} </label>
                            <select class="form-control parent-input" name="reasoneupdate" id="reasoneupdate" required>

                                @foreach (App\Models\Expenses_reasons::get() as $Expenses_reason)
                                <option value="{{ $Expenses_reason->id }}">
                                    {{ $Expenses_reason->expenses_reason }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="inputName" class="control-label parent-label"> {{ __('accountes.Theamountpaid') }}
                            </label>
                            <input type="text" class="form-control parent-input" id="cashreceivedupdate" name="cashreceivedupdate" value=0 title="يرجي ادخال الكمية  " required onkeyup="moneyconvertToNumber()">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                    <button id="updatedecoument" name="updatedecoument" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
                </div>

                </form>
            </div>
        </div>
    </div>
    @endsection
    @section('js')
    <!-- Internal Data tables -->

    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!--Internal  Datepicker js -->
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
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();



  $("#getinvoiceupdate").click(function(e) {
            event.preventDefault();
            var url = " {{ URL::to('getAndUpdateExpenses') }}" + "/" + $('#updateinvoicebyid').val();
            console.log(url)
            jQuery.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                cache: false,

             
                success: function(data) {
         let table = document.getElementById("example");
                        var tableHeaderRowCount = 1;
                        console.log(data['id'])
                        var rowCount = table.rows.length;
                        $('#cashreceived').val(0)
                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        let row = table.insertRow(-1); // We are adding at the end
                        update = ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                        update = update.concat(data['id'], '  ', ' data-amount=', data['Theـamountـpaid'], '  ',
                            '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);

                        c1.innerText = data['id']
                        c2.innerText = data['user']
                        c3.innerHTML = ' <span dir=ltr style="color:red">' + data['expense'] + '</span>'
                        c4.innerText = data['Theـamountـpaid']
                        c5.innerText = data['Pay_Method_Name']
                        c6.innerHTML = update

                        $('#id').val(data['id'])



                },
                error: function(response) {
                    alert("{{ __('home.sorryerror') }}")

                }
                
            })
            
       })











        $('#increaseProduct').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)

            var id = button.data('id')
            var amount = button.data('amount')
            var modal = $(this)



            modal.find('.modal-body #transactionId').val(id);
            modal.find('.modal-body #cashreceivedupdate').val(amount);

        })
    </script>

    <script>
        $("#updatedecoument").click(function(e) {
            var url = " {{ URL::to('updateExpenses') }}";
            var token_search = $("#token_search").val();
            console.log($('#cashreceivedupdate').val())
            console.log('--------------reson id---------')
            console.log( $('#reasoneupdate').val())
            console.log('--------------reson id---------')

            console.log( $('#payupdate').val())
            console.log( $('#transactionId').val())

            if ($('#cashreceivedupdate').val() == '') {
                alert("{{__('home.should')}}")

            } else {
                cashreceived= $('#cashreceivedupdate').val()
                $('#cashreceivedupdate').val(0)

                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,

                    data: {
                        _token: token_search,
                        cashreceivedupdate: cashreceived,
                        reasoneupdate: $('#reasoneupdate').val(),
                        payupdate: $('#payupdate').val(),
                        transactionId: $('#transactionId').val(),
                    },


                    success: function(data) {
                        let table = document.getElementById("example");
                        var tableHeaderRowCount = 1;
                        console.log(data['id'])
                        var rowCount = table.rows.length;
                        $('#cashreceived').val(0)
                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        let row = table.insertRow(-1); // We are adding at the end
                        update = ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                        update = update.concat(data['id'], '  ', ' data-amount=', data['Theـamountـpaid'], '  ',
                            '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        c1.innerText = data['id']
                        c2.innerText = data['user']
                        c3.innerHTML = ' <span dir=ltr style="color:red">' + data['expense'] + '</span>'
                        c4.innerText = data['Theـamountـpaid']
                        c5.innerText = data['Pay_Method_Name']
                        c6.innerHTML = update
                        $('#id').val(data['id'])


                    },
                    error: function(response) {
                        console.log(response)
                        alert("{{ __('home.sorryerror') }}")

                    }
                })




            }



        })



      $("#formdata").on('submit',function(e) {
            document.getElementById('button_1').style.visibility = 'hidden';

        e.preventDefault();
            var url = " {{ URL::to('Expenses') }}";
            var token_search = $("#token_search").val();


            if ($('#cashreceived').val() == 0) {
                alert("{{__('home.should')}}")

            } else if ($('#date').val() == '') {
                alert("{{__('home.enter date')}}")

            } else {
                cashreceived= $('#cashreceived').val()
                $.ajax({
                    url: url,
                    type: 'post',
                    cache: false,
contentType:false,
processData:false,
                data:new FormData(this),


                    success: function(data) {

                        let table = document.getElementById("example");
                        var tableHeaderRowCount = 1;
                        console.log(data['id'])
                        var rowCount = table.rows.length;
                        $('#cashreceived').val(0)
                        document.getElementById('button_1').style.visibility = 'visible';

                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        let row = table.insertRow(-1); // We are adding at the end
                        update = ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                        update = update.concat(data['id'], '  ', ' data-amount=', data['Theـamountـpaid'], '  ',
                            '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                        )
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);

                        c1.innerText = data['id']
                        c2.innerText = data['user']
                        c3.innerHTML = ' <span dir=ltr style="color:red">' + data['expense'] + '</span>'
                        c4.innerText = data['Theـamountـpaid']
                        c5.innerText = data['Pay_Method_Name']
                        c6.innerHTML = update

                        $('#id').val(data['id'])
                        $('#SearchProduct').modal().show();
 setTimeout(() => {
         $('#SearchProduct').modal('hide');

        }, 1000);


                    },
                    error: function(response) {
                        console.log(response)
                        alert("{{ __('home.sorryerror') }}")

                    }
                })




            }



        })

        function moneyconvertToNumber() {
            var input = document.getElementById("cashreceived");
            var val = toEnglishNumber(input.value)
            input.value = val;
        }

        function toEnglishNumber(strNum) {
            var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
            var en = '0123456789'.split('');
            strNum = strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
            //  strNum = strNum.replace(/[^\d]/g, '');
            return strNum;
        }
    </script>

    <script>
        $(document).ready(function() {
            $(function() {
                var timeout = 4000; // in miliseconds (3*1000)
                $('.alert').delay(timeout).fadeOut(500);
            });
            $('select[name="clientNosearch"]').on('change', function() {
                console.log('AJAX load   work 0000');

                var selectclientid = $(this).val();
                if (selectclientid) {
                    console.log('AJAX load   work');

                    $.ajax({
                        url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                            console.log(data['name']);
                            $('#debtamount').val(data['In_debt']);

                            $('#clientName').val(data['name']);
                            $('#address').val(data['location']);
                            $('#phonenumber').val(data['phone']);
                            $('#notes').val(data['comp_name']);
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });

            $('select[name="clientnamesearch"]').on('change', function() {
                console.log('AJAX load   work 0000');

                var selectclientid = $(this).val();
                if (selectclientid) {
                    console.log('AJAX load   work');

                    $.ajax({
                        url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                            console.log(data['name']);

                            $('#debtamount').val(data['In_debt']);
                            $('#clientName').val(data['name']);
                            $('#address').val(data['location']);
                            $('#phonenumber').val(data['phone']);
                            $('#notes').val(data['comp_name']);
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>






    @endsection