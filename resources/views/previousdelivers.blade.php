@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    /* Basic styling for loading screen */
    #loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      color: white;
      font-size: 24px;
    }

    #loading-animation {
      border: 4px solid white;
      border-radius: 50%;
      border-top: 4px solid #3498db;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

 
  </style>
<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{ __('home.recent_delivers') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto">{{ __('home.recent_delivers') }}</h4>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    <center>
    <div id="loading-screen">
    <div id="loading-animation"></div>
    &nbsp;  <p>  جارٍ إرسال الفاتورة، يرجى الانتظار  <br>Invoice  is being sent, please wait</p>
  </div>
  </center>
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'printavaliableproduct')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}







                        {{-- 3 --}}


                        <br>


               <div class="row">

                                                   <div class="col-lg-4" id="start_at">
                                <label for="inputName" class="control-label parent-label">{{ __('home.chooseclient') }}</label>

                                <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">


                                    @foreach (App\Models\customers::get() as $customer)
                                    <option value="{{ $customer->id }}"> {{ $customer->id==1?__('home.Cash Custome'):$customer->name }}- {{ $customer->tax_no}} - {{ $customer->phone}}

                                    </option>
                                    @endforeach
                                </select>
                       
                                         <!-- input-group -->
                             
                               </div><br>
                               
                        <?php $i = 0; ?>
                        <div class="col-xl-12">
                              <br>
                               
                             
                            <div class="card mg-b-20">
                        
                              
                                <div >
                                    <div class="table-responsive hoverable-table" id="ajax_responce_allinvoicesDiv">
                                        <table class="table text-md-nowrap text-center our-table" id="example1" data-page-length='50' style=" text-align: center;">
                                            <thead>
                                                <tr>
                                                    <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.clietName') }}</th>
                                                    <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                                    <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                                    <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                                                    <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.operations') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                             
                                            </tbody>
                                        </table>
                                        <div>



                                        </div>
                                        <br>

                                    </div>
                                </div>
                            </div>

                            <br />
                    </form>

                </div>



            </div>
        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->
</div>


<!-- enter payments -->
<div class="modal" id="paymentmethod">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.paymentmethod') }} </h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label style="font-size:16px" class="control-label parent-label">&nbsp;&nbsp;{{ __('home.total') }} :&nbsp; </label>
                    <label style="font-size:20px" id="totalvalue">0</label>
                    <label style="font-size:15px" class="control-label parent-label">&nbsp;{{ __('home.SAR') }} &nbsp;&nbsp;&nbsp;&nbsp; </label>
                    <br>


                </div>
                <div class=" col">
                    <label> {{ __('home.paymentmethod') }} </label>
                    <br>

                    <select class="form-control" name="paymodal" id='paymodal' required>

                        <option value="Cash"> {{ __('report.cash') }}</option>
                        <option value="Shabka"> {{ __('report.shabka') }} </option>
                        <option value="Bank_transfer"> {{ __('home.Bank_transfer') }} </option>
                        <option value="Credit"> {{ __('report.credit') }} </option>
                        <option value="Bank_transfer"> {{ __('home.bank_arbic') }} </option>

                    </select>


                </div>
                <br>
                <div class="row">

                    <div class="col">

                        <label class="control-label parent-label">{{ __('report.cash') }}</label>
                        <input type="text" class="form-control parent-input" name="cashamount" id="cashamount" readonly value=0>
                    </div>

                    <div class="col">

                        <label class="control-label parent-label">{{ __('report.shabka') }}</label>
                        <input type="text" class="form-control parent-input" name="bankamount" id="bankamount" readonly value=0>
                    </div>

                    <div class="col">

                        <label class="control-label parent-label">{{ __('home.Bank_transfer') }}</label>
                        <input type="text" class="form-control parent-input" name="Bank_transfer" id="Bank_transfer" readonly value=0>
                    </div>
                    <div class="col">

                        <label class="control-label parent-label">{{ __('report.credit') }}</label><br>
                        <input type="text" class="form-control parent-input" name="creaditamount" id="creaditamount" readonly value=0>
                    </div>
                    <input hidden type="text" id="invoiceid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="confirmpayment" name="confirmpayment" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="uploadzatca">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.uploadzatca') }} </h6>
            </div>
            {{ csrf_field() }}


            <div class="modal-body">




            <input type="hidden" id="invoiceid_zatca" >


                <div class="row">

                    <div class="col">


                        <label for="inputName" class="control-label parent-label">{{ __('home.confirmzatcasent') }}</label>

                    </div>
                   

               





                <br>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.cancel')}}</button>
                <button type="submit" id="sent_to_zatca" data-dismiss="modal" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>

        </div>
    </div>
</div>


@endsection
@section('js')
<!-- Internal Data tables -->

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
</script>



<script>

    $('#uploadzatca').on('show.bs.modal', function(event) {
       
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        modal.find('.modal-body #invoiceid_zatca').val(id);
    })
  $("#sent_to_zatca").click(function(e) {
              document.getElementById('loading-screen').style.display = 'block'; // show loading screen

        var url = " {{ URL::to('sent_to_zatca') }}"+'/'+ $('#invoiceid_zatca').val();
        document.getElementById('sendnowzatca').hidden = true

        token_search = $('#token_search').val();
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            dataType: "html",
           


            success: function(data) {
            if(data==1){
                var audio = new Audio('/sounds/done.mp3');
                audio.play();


                $.ajax({
                url: " {{URL::to('getAlldeliversajax')}}",
                type: "GET",
                dataType: "html",
                success: function(products) {
                    $("#ajax_responce_allinvoicesDiv").html(products);
                },
         });
             
      document.getElementById('loading-screen').style.display = 'none'; // Hide loading screen

            }
            else{
                document.getElementById('sendnowzatca').hidden = false
      document.getElementById('loading-screen').style.display = 'none'; // Hide loading screen

                alert(data)
            }

            },
              error: function(response) {
                document.getElementById('sendnowzatca').hidden = false
             document.getElementById('loading-screen').style.display = 'none'; // Hide loading screen

                console.log(response)
                                alert(response)


}
        });


    })

    function searchaboutproductfunction() {
        date = $('#date').val();
        console.log(" {{URL::to('searchAllInvoicespaginatenew')}}" + "/" + date)
        $.ajax({
            url: " {{URL::to('searchAllInvoicespaginatenew')}}" + "/" + date,
            type: "GET",
            dataType: "html",
            success: function(products) {
                console.log(products)
                $("#ajax_responce_allinvoicesDiv").html(products);


            },
        });
    }
        $('select[name="clientnamesearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectCustomer = $(this).val();
        if (selectCustomer != '') {
            $.ajax({
                url: " {{URL::to('getAlldeliversajaxbycustomer')}}" + "/" + selectCustomer,
                type: "GET",
                dataType: "html",
                success: function(products) {
                    console.log(products)
                    $("#ajax_responce_allinvoicesDiv").html(products);


                },
            });
        } else {
            $.ajax({
                url: " {{URL::to('getAlldeliversajax')}}",
                type: "GET",
                dataType: "html",
                success: function(products) {
                    $("#ajax_responce_allinvoicesDiv").html(products);


                },
            });
        }

        });
        
        $(document).ready(function () {
                  document.getElementById('loading-screen').style.display = 'none'; // Hide loading screen
console.log(" {{URL::to('getAlldeliversajax')}}")
  $.ajax({
                url: " {{URL::to('getAlldeliversajax')}}",
                type: "GET",
                dataType: "html",
                success: function(products) {
                    $("#ajax_responce_allinvoicesDiv").html(products);


                },
            });});
    function searchaboutinvoiceByIdfunction() {
        date = $('#invoiceid').val();
        if (date != '') {

            $.ajax({
                url: " {{URL::to('searchaboutinvoiceByIdfunction')}}" + "/" + date,
                type: "GET",
                dataType: "html",
                success: function(products) {
                    console.log(products)
                    $("#ajax_responce_allinvoicesDiv").html(products);


                },
            });
        } else {
            $.ajax({
                url: " {{URL::to('getAlldeliversajax')}}",
                type: "GET",
                dataType: "html",
                success: function(products) {
                    $("#ajax_responce_allinvoicesDiv").html(products);


                },
            });
        }
    }

    $("#updatecustomer").click(function(e) {
        var url = " {{ URL::to('updatecustomerDataInvoice') }}";
        token_search = $('#token_search').val();
        $.ajax({
            url: url,
            type: 'post',
            cache: false,
            dataType: "html",
            data: {
                _token: token_search,
                id: $('#id').val(),
                customername: $('#customername').val(),
                customerId: $('#customerId').val(),

            },


            success: function(data) {
                $("#ajax_responce_allinvoicesDiv").html(data);

            }
        });
    })

    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
        var search_by_text = $("#date").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();

        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search
            },
            success: function(data) {
                console.log(data)
                $("#ajax_responce_allinvoicesDiv").html(data);
            },
            error: function() {

            }
        });
    });

    $(document).on('click', '#ajax_pagination_in_search a ', function(e) {
        e.preventDefault();
        var search_by_text = $("#date").val();
        var url = $(this).attr("href");
        var token_search = $("#token_search").val();

        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            cache: false,
            data: {
                search_by_text: search_by_text,
                "_token": token_search
            },
            success: function(data) {
                console.log(data)
                $("#ajax_responce_allinvoicesDiv").html(data);
            },
            error: function() {

            }
        });
    });
</script>





















<script>
    $('select[name="paymodal"]').on('change', function() {

        var selectCustomer = $(this).val();
        if ($('#cashamount').val() != 0) {
            value = $('#cashamount').val()

        } else if ($('#bankamount').val() != 0) {
            value = $('#bankamount').val();

        } else if ($('#Bank_transfer').val() != 0) {
            value = $('#Bank_transfer').val();

        } else {
            value = $('#creaditamount').val();
        }


        if (selectCustomer == 'Cash') {
            $('#cashamount').val(value)
            $('#bankamount').val(0)
            $('#creaditamount').val(0)
            $('#Bank_transfer').val(0)
            document.getElementById("bankamount").readOnly = true;
            document.getElementById("cashamount").readOnly = true;
            document.getElementById("Bank_transfer").readOnly = true;


        } else if (selectCustomer == 'Shabka') {
            $('#cashamount').val(0)
            $('#bankamount').val(value)
            $('#creaditamount').val(0)
            $('#Bank_transfer').val(0)

            document.getElementById("bankamount").readOnly = true;
            document.getElementById("cashamount").readOnly = true;
            document.getElementById("Bank_transfer").readOnly = true;

        } else if (selectCustomer == 'Credit') {
            $('#cashamount').val(0)
            $('#bankamount').val(0)
            $('#Bank_transfer').val(0)
            $('#creaditamount').val(value)
            document.getElementById("bankamount").readOnly = true;
            document.getElementById("cashamount").readOnly = true;
            document.getElementById("Bank_transfer").readOnly = true;

        } else if (selectCustomer == 'Bank_transfer') {


            $('#cashamount').val(0)
            $('#bankamount').val(0)
            $('#creaditamount').val(0)
            $('#Bank_transfer').val(value)
            document.getElementById("bankamount").readOnly = true;
            document.getElementById("cashamount").readOnly = true;
            document.getElementById("Bank_transfer").readOnly = true;

        } else {
            $('#cashamount').val(value)
            $('#bankamount').val(0)
            $('#creaditamount').val(0)
            $('#Bank_transfer').val(0)
            document.getElementById("bankamount").readOnly = false;
            document.getElementById("cashamount").readOnly = false;
            document.getElementById("Bank_transfer").readOnly = false;


        }





    });
    $('#updateCustomer').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        $('#cashamount').val(0)
        $('#Bank_transfer').val(0)
        $('#creaditamount').val(0)
        $('#bankamount').val(0)

        var customername = button.data('customername')


        var modal = $(this)

        modal.find('.modal-body #customername').val(customername);
        modal.find('.modal-body #id').val(id);

    })
</script>

<script>
    $('#paymentmethod').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var invoice = button.data('totalinvoice')
        $('#invoiceid').val(id)
        document.getElementById('totalvalue').innerHTML = invoice;
        $('#cashamount').val(invoice)

    });

    $("#confirmpayment").click(function(e) {


        if ($('#cashamount').val() == '') {
            $('#cashamount').val(0)
        }
        if ($('#bankamount').val() == '') {
            $('#bankamount').val(0)
        }
        if ($('#creaditamount').val() == '') {
            $('#creaditamount').val(0)
        }
        if ($('#Bank_transfer').val() == '') {
            $('#Bank_transfer').val(0)
        } else {

        }


        text = document.getElementById('totalvalue').innerText
  const select = document.getElementById("paymodal");
  const selectedIndex = select.selectedIndex;

        console.log(" {{URL::to('updatepaymentconfirmpayment')}}/" + $('#invoiceid').val() + '/' + $('#cashamount').val() + '/' + $('#bankamount').val() + '/' + $('#creaditamount').val() + "/" + $('#Bank_transfer').val() + '/' + $('#paymodal').val()+'/'+selectedIndex)
        if (Number(text) == (Number($('#cashamount').val()) + Number($('#Bank_transfer').val()) + Number($('#bankamount').val()) + Number($('#creaditamount').val()))) {
            $.ajax({
                url: " {{URL::to('updatepaymentconfirmpayment')}}/" + $('#invoiceid').val() + '/' + $('#cashamount').val() + '/' + $('#bankamount').val() + '/' + $('#creaditamount').val() + "/" + $('#Bank_transfer').val() + '/' + $('#paymodal').val()+'/'+selectedIndex,
                type: "GET",
                dataType: "html",
                success: function(data) {
                    $("#ajax_responce_allinvoicesDiv").html(data);



                },
                error: function(response) {

                    alert("{{ __('home.sorryerror') }}")

                }

            })
        } else {
            $('#saveinvice').val(0);

            alert("{{ __('home.entermonycorrect') }}")

        }


    })
    $(document).ready(function() {


        $('select[name="clientnamesearch"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');

                $.ajax({
                    url: "{{ URL::to('getcustomer') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success");
                        console.log(data['name']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['address']);
                        $('#phonenumber').val(data['phone']);
                        $('#notes').val(data['notes']);
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

    $('select[name="searchproductNo"]').on('change', function() {
        console.log('AJAX load   work 0000');

        var selectclientid = $(this).val();
        if (selectclientid) {
            console.log('AJAX load   work');

            $.ajax({
                url: "{{ URL::to('getproduct') }}/" + selectclientid,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("success");
                    console.log(data['name']);
                    $('#quentity').val(data['numberofpice']);

                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });
</script>




<script>
    $(document).ready(function() {
        $.ajax({
            url: " {{URL::to('getAlldeliversajax')}}",
            type: "GET",
            dataType: "html",
            success: function(products) {
                $("#ajax_responce_allinvoicesDiv").html(products);


            },
        });
        $('#invoice_number').hide();

        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {
                $('#invoice_number').hide();
                $('#type').show();
                $('#start_at').show();
                $('#end_at').show();
            } else {
                $('#invoice_number').show();
                $('#type').hide();
                $('#start_at').hide();
                $('#end_at').hide();
            }
        });
    });
    
    
        $("#reciptprinter").click(function(e) {
        reciptprinter
        var url = " {{ URL::to('reciptprinter') }}";
        var token_search = $("#token_search").val();
        $.ajax({
            url: url,
            type: 'post',
            cache: false,
            dataType: 'html',
            data: {
                _token: token_search,
                show_invoice_number: $('#show_invoice_number').val(),
            },
            success: function(data) {
                console.log(data)
                const winUrl = URL.createObjectURL(
                    new Blob([data], {
                        type: "text/html"
                    })
                );
                const win = window.open(
                    winUrl,
                    "win",
                    `width=800,height=400,screenX=200,screenY=200`
                );

            },
            error: function(response) {
                console.log(response)
                alert("{{ __('home.sorryerror') }}")

            }
        });
    });
</script>


@endsection