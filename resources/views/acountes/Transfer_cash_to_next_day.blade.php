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
{{ __('home.Transfer cash to the next day') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Transfer cash to the next day') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'ExpensesOwner')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}


                        <input type="hidden" id="token_search" value="{{ csrf_token() }}">




                        <div class='row'>


                            <div class="col-lg-3 mb-2">
                                <label for="inputName" class="control-label parent-label"> {{ __('home.The amount of the transferred amount') }}
                                </label>
                                <input type="number" class="form-control parent-input" id="The_amount_transferred_amount" name="The_amount_transferred_amount" title="يرجي ادخال الكمية  "  value=0 required onkeyup="moneyconvertToNumber()">
                            </div>
                           
                            <div class="col-lg-2">
                                <label for="inputName" class="control-label parent-label">{{ __('home.bank balance amount') }} </label>
                                <input type="number" class="parent-input form-control" id="bankblance" name="bankblance" title="يرجي ادخال ملاحظات  " >
                            </div>
                            <div class="col-lg-3 parent-label" id="start_at">
                                <label for="exampleFormControlSelect1"> {{ __('home.day_transfer') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div><input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="date" id="date" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="inputName" class="control-label parent-label">{{ __('home.notesClient') }} </label>
                                <input type="text" class="parent-input form-control" id="notes" name="notes" title="يرجي ادخال ملاحظات  " value="-">
                            </div>
                           
                            <br>

                        </div>
                        <br>
                    </form>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success print-style p-1" id="button_1">
                            {{ __('home.Add') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                        <br>

                    </div>
                    <br>
                    <br>
                    <br>

                </div>








            </div>
        </div>
    </div>

</div>
<div class="col-xl-12">
    <div style="border-radius: 10px" class="card mg-b-20 pt-3">
        <div class="card-body">
            <div class="table-responsive ">
                <table id="example" class="table text-md-nowrap text-center our-table" width="100%" style="border: 2px solid rgba(0,0,0,.3);">
                    <thead>
                        <tr>
                        <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>
                        <th class="border-bottom-0">{{ __('home.day_transfer') }}</th>
                            <th class="border-bottom-0"> {{ __('accountes.user') }}</th>
                            <th class="border-bottom-0"> {{ __('users.branch') }}</th>
                            <th class="border-bottom-0">{{ __('home.The amount of the transferred amount') }}</th>
                            <th class="border-bottom-0">{{ __('home.bank balance amount') }}</th>
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
                        <td>-</td>


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



                    <div class="col-lg-3 mb-2">
                        <label for="inputName" class="control-label parent-label"> {{ __('home.The amount of the transferred amount') }}
                        </label>
                        <input type="number" class="form-control parent-input" id="The_amount_transferred_amountupdate" name="The_amount_transferred_amountupdate" title="يرجي ادخال الكمية  " required onkeyup="moneyconvertToNumber()">
                    </div>
                    <div class="col-lg-3 mb-2">
                        <label for="inputName" class="control-label parent-label"> {{ __('home.bank balance amount') }}
                        </label>
                        <input type="number" class="form-control parent-input" id="bankblance_update" name="bankblance_update" title="يرجي ادخال رصيد المرحل  " required >
                    </div>

                    <div class="col-lg-6">
                        <label for="inputName" class="control-label parent-label">{{ __('home.notesClient') }} </label>
                        <input type="text" class="parent-input form-control" id="notesupdate" name="notesupdate" title="يرجي ادخال ملاحظات  " value="-">
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
</script>

<script>
       $('#increaseProduct').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)

        var id = button.data('id')
        var amount = button.data('amount')
        var bankblance=button.data('bankblance')
        var modal = $(this)



        modal.find('.modal-body #transactionId').val(id);
        modal.find('.modal-body #The_amount_transferred_amountupdate').val(amount);
        modal.find('.modal-body #bankblance_update').val(bankblance);

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
    $("#updatedecoument").click(function(e) {
        var url = " {{ URL::to('updatedecoumentcashNextDay') }}";
        var token_search = $("#token_search").val();

        console.log($('#notesupdate').val())
        console.log($('#The_amount_transferred_amountupdate').val())
        if ($('#The_amount_transferred_amountupdate').val() == 0) {
            alert("{{__('home.should')}}")

        } else {
            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    transactionId: $('#transactionId').val(),
                    notes: $('#notesupdate').val(),
                    The_amount_transferred_amount: $('#The_amount_transferred_amountupdate').val(),

                    bank_balance_amount: $('#bankblance_update').val()??0,


                },


                success: function(data) {

                    let table = document.getElementById("example");
                    var tableHeaderRowCount = 1;
                    console.log(data)
                    var rowCount = table.rows.length;
                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                        table.deleteRow(tableHeaderRowCount);
                    }
                    let row = table.insertRow(-1); // We are adding at the end
                    update = ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                    update = update.concat(data['id'], '  ', ' data-amount=', data['the_amount'], '  ',
                        '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                    )
                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);
                    c1.innerText = data['id']
                    c2.innerText = data['date']
                    c3.innerText = data['user']
                    c4.innerHTML = ' <span dir=ltr style="color:red">' + data['branch'] + '</span>'
                    c5.innerText = data['the_amount']
                    c6.innerText = data['currentamount']
                    c7.innerHTML = update

                    $('#cashreceived').val(0)
                },
                error: function(response) {
                    alert("{{ __('home.sorryerror') }}")

                }
            })




        }



    })

    $("#button_1").click(function(e) {
        var url = " {{ URL::to('Transfercashto_the_next_day') }}";
        var token_search = $("#token_search").val();

        console.log($('#The_amount_transferred_amount').val())
        console.log($('#The_amount_transferred_amount').val())
        console.log($('#notes').val())
        if ($('#The_amount_transferred_amount').val() == 0) {
            alert("{{__('home.should')}}")

        }else if ($('#date').val() == '') {
            alert("{{__('home.day_transfer')}}")

        } else {
            $.ajax({
                url: url,
                type: 'post',
                cache: false,

                data: {
                    _token: token_search,
                    notes: $('#notes').val(),
                    date: $('#date').val(),
                    The_amount_transferred_amount: $('#The_amount_transferred_amount').val(),
                    bank_balance_amount: $('#bankblance').val()??0,

                },


                success: function(data) {
                    $('#bankblance').val(0)
                    $('#The_amount_transferred_amount').val(0)
                    let table = document.getElementById("example");
                    var tableHeaderRowCount = 1;

                    var rowCount = table.rows.length;
                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                        table.deleteRow(tableHeaderRowCount);
                    }
                    let row = table.insertRow(-1); // We are adding at the end
                    update = ' <a style="width:40px;height:20px" class="modal-effect btn btn-sm btn-warning mb-1" data-effect="effect-scale" data-id='
                    update = update.concat(data['id'], '  ', ' data-amount=', data['the_amount'], '  ', ' data-bankblance=', data['currentamount'],' ',
                        '  data-toggle="modal"   href="#increaseProduct"   title="تعديل"><i class="las la-align-justify"></i></a>'
                    )
                    let c1 = row.insertCell(0);
                    let c2 = row.insertCell(1);
                    let c3 = row.insertCell(2);
                    let c4 = row.insertCell(3);
                    let c5 = row.insertCell(4);
                    let c6 = row.insertCell(5);
                    let c7 = row.insertCell(6);
                    c1.innerText = data['id']
                    c2.innerText = data['date']
                    c3.innerText = data['user']
                    c4.innerHTML = ' <span dir=ltr style="color:red">' + data['branch'] + '</span>'
                    c5.innerText = data['the_amount']
                    c6.innerText = data['currentamount']
                    c7.innerHTML = update

                    $('#cashreceived').val(0)
                },
                error: function(response) {
                    alert("{{ __('home.sorryerror') }}")

                }
            })




        }



    })




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

<script>
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




@endsection