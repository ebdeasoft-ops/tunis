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
{{__('home.recive_product_from_other_branch_other')}}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{__('home.recive_product_from_other_branch_other')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')
    @if (session()->has('nodataprint'))
    <div class="alert alert-warning  alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ __('home.nodataprint') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session()->has('confirmed'))
    <div class="alert alert-success  alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ session()->get('confirmed') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    @endif
    @if (session()->has('createnewproduct'))
    <div class="alert alert-warning  alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ session()->get('createnewproduct') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
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
    @if (session()->has('productupdatedlocation'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>

        <strong>{{ session()->get('productupdatedlocation') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">



            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">


                    <input type="hidden" id="token_search" value="{{ csrf_token() }}">


                    <input type="hidden" id="token_search" value="{{ csrf_token() }}" hidden>
                    <input type="hidden" id="order_id" name="order_id" hidden>




                    <div class="row">
                        <div class="col-lg-4 mb-2">
                            <label for="inputName" class="control-label parent-label">{{ __('home.Invoice_no') }}</label>
                            <select name="reciveInvoiceNumber" id="reciveInvoiceNumber" class="form-control select2">
                                <option value="-"> {{ __('home.Invoice_no') }}</option>
                                @foreach (App\Models\product_movement_another_branch::where('branch_to',Auth()->user()->branchs_id)->where('reciveInvoiceNumber',0)->get() as $section)

                                <option value="{{ $section->id }}"> {{ $section->id }}</option>
                                @endforeach
                            </select>
                        </div>






                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label">{{ __('home.branch_sender') }}</label>
                            <input name="branch_show" id="branch_show" class="form-control" readonly>
                            <input name="branch" id="branch" class="form-control" hidden>

                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="inputName" class="control-label parent-label">{{ __('home.employeesender') }}</label>
                            <input name="userfrom_show" id="userfrom_show" class="form-control" readonly>
                            <input name="userfrom" id="userfrom" class="form-control" hidden>
                        </div>

                        <div class="col-lg-2 mb-2">
                                <label for="exampleFormControlSelect1"> {{ __('home.exportTime') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control parent-input fc-datepicker" readonly name="date" id="date" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>
                    </div>
                    <br>
                    <br>

                    <table id="example" class="table our-table border mb-0 table-responsive text-center" style="border: 1px solid black;border-collapse: collapse !important;">
                        <col style="width:2%">
                        <col style="width:15%">
                        <col style="width:30%">
                        <col style="width:15%">
                        <col style="width:20%">
                        <col style="width:15%">

                        <thead>
                            <tr>
                                <th> # </th>
                                <th>{{ __('home.productNo') }} </th>
                                <th>{{ __('home.product') }}</th>
                                <th>{{ __('home.quantity') }}</th>
                                <th>{{ __('home.thecostProduct') }}</th>
                                <th>{{ __('home.total') }}</th>
                            </tr>
                        </thead>

                        <body style="color: black">
                            <tr style="color: black">
                                <td style="color:black">-</td>
                                <td style="color:black">-</td>
                                <td style="color:black">-</td>
                                <td style="color:black">-</td>
                                <td style="color:black">-</td>
                                <td style="color:black">-</td>
                            </tr>

                        </body>
                    </table>
                    <br>
                    <br>

                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" name="button_1" id="button_1" href="#modaldemo9" class="modal-effect btn btn-sm btn-info  button-eng">
                            {{ __('home.confirm') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                    </div>


                    <input hidden=true class="form-control" id="branchs_id" name="branchs_id" value="{{Auth()->user()->branchs_id}}">

                    <br>
                    <div id="proDatediv">
                        <form action="{{  url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() .'/' . ($page = 'print_Recive_items')) }}" method="POST" role="search" autocomplete="off">
                            {{ csrf_field() }}



                            <div class="row  d-flex justify-content-end mt-3">
                                <input type="number" class="form-control " name="sprint_invoice_number" id="sprint_invoice_number" value="{{$data}}" title=" رقم الفاتورة " readonly required=true hidden>


                                <button style="background-color: #419BB2;font-size:17px" type="submit" class="btn btn-success p-1 px-2 fw-bolder">
                                    {{ __('home.print') }}
                                    <svg style="width: 22px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                        <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                    </svg>
                                </button>
                                <br>





                            </div>


                        </form>
                    </div>
                </div>
                <br>
            </div>



            <br>





            <?php $i = 0; ?>



            <!-- row closed -->
        </div>
        <!-- Container closed -->
    </div>
    <!-- main-content closed -->
</div>
<!-- delete -->
<div class="modal" id="modaldemo9">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.alert') }} </h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <span style="color:red">{{ __('home.reciveproductnote') }}</span><br>
                <input type="hidden" name="invoiceId" id="invoiceId" value='-'>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                <button id="deleteproduct" name="deleteproduct" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>

    </div>
</div>

@endsection
@section('js')

<<script>


    // 1. جلب بيانات الفاتورة عند اختيار الرقم من القائمة
    $('select[name="reciveInvoiceNumber"]').on('change', function() {
        var selectCustomer = $(this).val();
        if (!selectCustomer || selectCustomer === '-') return;

        // إظهار علامة التحميل لمنع التلاعب أثناء جلب البيانات
        Swal.fire({
            title: 'جاري جلب البيانات...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $(this).prop('disabled', true); // تعطيل السيلكت مؤقتاً
        $('#invoiceId').val(selectCustomer);

        $.ajax({
            url: "{{ URL::to('/findinvoiceMovmevt') }}/" + selectCustomer,
            type: "GET",
            dataType: "json",
            success: function(data) {
                Swal.close(); // إغلاق التحميل
                $('select[name="reciveInvoiceNumber"]').prop('disabled', false);

                if (data) {
                    $('#userfrom_show').val(data['senderdata']['user_name']);
                    $('#date').val(data['senderdata']['created_at']);
                    $('#userfrom').val(data['senderdata']['user_id']);
                    $('#branch_show').val(data['senderdata']['barnch_name']);
                    $('#branch').val(data['senderdata']['barnch_id']);

                    let table = document.getElementById("example");
                    var tableHeaderRowCount = 1;
                    var rowCount = table.rows.length;

                    // تفريغ الجدول القديم
                    for (var i = tableHeaderRowCount; i < rowCount; i++) {
                        table.deleteRow(tableHeaderRowCount);
                    }

                    data['orderItems'].forEach((product) => {
                        let quentity = product['quantity'];
                        if (quentity > 0) {
                            let row = table.insertRow(-1);
                            row.insertCell(0).innerText = product['count'];
                            row.insertCell(1).innerHTML = '<span dir=ltr>' + product['product_code'] + '</span>';
                            row.insertCell(2).innerText = product['productname'];
                            row.insertCell(3).innerText = quentity;
                            row.insertCell(4).innerText = product['cost'];
                            row.insertCell(5).innerText = product['total'];
                            $('#order_id').val(product['details_items_no']);
                        }
                    });
                }
            },
            error: function() {
                Swal.close();
                $('select[name="reciveInvoiceNumber"]').prop('disabled', false);
                Swal.fire('خطأ', 'فشل في جلب بيانات الفاتورة', 'error');
            }
        });
    });

    // 2. معالجة زر التأكيد الرئيسي لفتح المودال
    $("#button_1").click(function(e) {
        e.preventDefault();
        if ($('#reciveInvoiceNumber').val() == '-') {
            Swal.fire('تنبيه', "{{__('home.enterinvoicenumber')}}", 'warning');
        } else {
            $('#modaldemo9').modal('show');
        }
    });

    // 3. معالجة عملية الحفظ النهائية (داخل المودال)
    $("#deleteproduct").click(function(e) {
        var url = " {{ URL::to('create_reciveProduct') }}";
        
        // منع التكرار: إظهار تحميل وتعطيل الأزرار فوراً
        Swal.fire({
            title: 'جاري المعالجة...',
            text: 'يتم الآن استلام المنتجات وتحديث المخزون',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $(this).prop('disabled', true);
        $("#button_1").prop('disabled', true);
        $('#modaldemo9').modal('hide');

        var token_search = $("#token_search").val();

        $.ajax({
            url: url,
            type: 'post',
            data: {
                _token: token_search,
                invoiceId: $('#invoiceId').val(),
            },
            success: function(data) {
                // رسالة النجاح
                Swal.fire({
                    icon: 'success',
                    title: 'تمت العملية',
                    text: data['mesage'],
                    confirmButtonColor: '#419BB2'
                });

                // تحديث القائمة المنسدلة
                var x = document.getElementById("reciveInvoiceNumber");
                for(i=x.options.length-1; i>=0; i--) { x.remove(i); }
                
                $('#sprint_invoice_number').val(data['invoice_number']);
                data['invoices_Reciepts'].forEach((decoument_id) => {
                    $('#reciveInvoiceNumber').append(`<option selected value="${decoument_id['id']}">${decoument_id['id']}</option>`);
                });

                // تصفير الخانات وإظهار زر الطباعة
                $('#branch_show').val('');
                $('#userfrom_show').val('');
                document.getElementById('proDatediv').hidden = false;
                document.getElementById('button_1').style.visibility = 'hidden';
            },
            error: function(response) {
                Swal.fire('خطأ', 'حدث خلل في النظام، لم يتم تكرار الاستلام', 'error');
                $("#deleteproduct").prop('disabled', false);
                $("#button_1").prop('disabled', false);
            }
        });
    });

    // إخفاء قسم الطباعة عند بداية التحميل
    $(document).ready(function() {
        document.getElementById('proDatediv').hidden = true;
    });
</script>



@endsection