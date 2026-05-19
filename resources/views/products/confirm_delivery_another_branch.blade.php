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
{{ __('home.confirm_delivery') }}@stop
@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->



    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.confirm_delivery') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    @endsection
    @section('content')

    @if (session()->has('foundinvoice'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <br>
        <strong> {{ session()->get('foundinvoice') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session()->has('notfountreturnproduct'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <br>
        <strong>{{ session()->get('notfountreturnproduct') }}</strong>
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'search_confirm_delievery')) }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="col-lg-5 mg-t-20 mg-lg-t-0">


                            <label for="inputName" class="control-label parent-label">{{ __('home.enterinvoicenumber') }}</label>
                            <input type="text" class="form-control parent-input" id="invoice_no" name="invoice_no" title="  يرجي ادخال رقم الفاتورة  " onkeyup="convertToNumberPriceSale()" required>

                        </div>



                </div>

                <div>

                    <br>

                    <div class="d-flex justify-content-center">
                        <button style="background-color: #419BB2" type="submit" class="btn btn-success p-1">
                            {{ __('home.search') }}
                            <i style=" height: 100;
                                                 
                                                 font-size:15px" class="las la-search"></i>
                        </button>
                    </div>
                </div>

                <br>

                </form>

            </div>
        </div>



        <br>



        @if (isset($data['products']))
        <?php $i = 0; ?>
        <div class="col-xl-12">
            <div style="border-radius: 10px" class="card mg-b-20 p-3">
                <div class="card-header pb-0 p-5">
                    <div class="d-flex justify-content-between">
                        <h4 style="background-color: #23395D;border-radius:5px;color:white" class="card-title mg-b-0 p-2">
                            {{ __('home.Notacceptedyet') }}
                        </h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>
                <div class="card-body">
                    <table style="border:1px solid black" id="example" class="table key-buttons text-md-nowrap table-bordered  text-center " name='prodyctsavaliable'>
                    <col style="width:5%">
                                    <col style="width:25%">
                                    <col style="width:35%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                        <thead>
                            <tr>
                                <th class="border-bottom-0"># </th>
                                <th class="border-bottom-0">{{ __('home.productNo') }}</th>
                                <th class="border-bottom-0">{{ __('home.product') }}</th>
                                <th class="border-bottom-0">{{ __('home.quantity') }}</th>
                                <th class="border-bottom-0">{{ __('home.Location') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            $invoiceId = 0;
                            ?>
                            @foreach ($data['products'] as $product)
                            <?php $i++;
                            $invoiceId = $product->invoice_id; ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td dir=ltr>{{ $product->productData->Product_Code }}</td>
                                <td>{{ $product->productData->product_name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->productData->Product_Location }}</td>

                               
                             
                             
                            <tr>
                                @endforeach
                        </tbody>
                    </table>
          


                      

                        <br>

                        <br>
                    </div>


                    <form action="{{ '/' . ($page = 'confirm_delivery_all') }}" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}


                        <div class="row d-flex justify-content-end p-0 mb-2">
                            <input type="number" class="form-control " name="invoice_no" id="invoice_no_delete_All" title=" رقم الفاتورة " value="{{ $data['datamain']['invoice_id'] }}" required=true hidden>

                            <button style="background-color:#FF4F1F" type="submit" class="btn btn-danger p-1">
                                {{ __('home.confirm_delivery') }} </button>

                        </div>






                    </form>
                </div>
            </div>

            <br />


        </div>




        <div class="row">







            @endif

            </table>

        </div>
    </div>


</div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- edit -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">استرجاع جزء من البضاعة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'update_return_sale')) }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="form-group">

                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="ordernumber" id="ordernumber" value="">
                        <label for="recipient-name" class="col-form-label">اسم المنتج : </label>

                        <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">عدد القطع التي سوف تسترجع :</label>
                        <textarea class="form-control" id="return_quentity" name="return_quentity" required></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">تاكيد</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            </div>
            </form>
        </div>
    </div>
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
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
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
</script>
<script>
    function convertToNumberPriceSale() {
        var input = document.getElementById("invoice_no");
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
    $('#exampleModal2').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)

        var id = button.data('id')
        var ordernumber = button.data('ordernumber')
        var section_name = button.data('section_name')
        var description = button.data('description')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #ordernumber').val(ordernumber);
        modal.find('.modal-body #product_name').val(section_name);
        modal.find('.modal-body #return_quentity').val(description);
    })
</script>

<script>
    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var ordernumber = button.data('ordernumber')

        var description = button.data('description')

        var section_name = button.data('section_name')
        var modal = $(this)
        modal.find('.modal-body #ordernumber').val(ordernumber);
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #description').val(description);
        modal.find('.modal-body #product_name').val(section_name);
    })
</script>


<script>
    $(document).ready(function() {
        $(function() {
            var timeout = 4000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(500);
        });
    })
</script>






@endsection