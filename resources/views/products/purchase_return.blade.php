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
@endsection

@section('title')
    {{ __('home.purchase_return') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.purchase_return') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ البحث وإدارة المرتجعات</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    {{-- عرض الأخطاء والرسائل --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطأ!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('notfountreturnpuracheseproduct'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('notfountreturnpuracheseproduct') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('editpurchase'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('editpurchase') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- row البحث -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20 border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <input type="hidden" id="token_search" value="{{ csrf_token() }}">

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/Purchase_returns_Data') }}"
                          method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row align-items-end">
                            <div class="col-lg-6">
                                <label for="clientName" class="control-label font-weight-bold text-dark mb-2">
                                    <i class="fas fa-file-invoice ml-1 text-primary"></i> {{ __('home.enterinvoicenumber') }}
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="clientName" name="clientName" placeholder="أدخل رقم الفواتير..." required style="border-radius: 8px 0 0 8px; height: 42px;">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success px-4" style="border-radius: 0 8px 8px 0; font-weight: 600;">
                                            {{ __('home.search') }} 
                                            <i class="fas fa-search mr-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (isset($data['product']))
        <?php $orderId = $data['resource_purchases']->orderId ?? 0; ?>
        
        <!-- تفاصيل المورد والفاتورة -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card mg-b-20 border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-transparent pb-0">
                        <h5 class="card-title text-primary font-weight-bold mb-0">
                            <i class="fas fa-info-circle ml-1"></i> بيانات الفاتورة والمورد
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 form-group">
                                <label class="control-label text-muted font-weight-bold">{{ __('home.suppliername') }}</label>
                                <input type="text" class="form-control bg-light" value="{{ $data['supllier']->supllier->comp_name ?? '' }}" readonly>
                            </div>
                            <div class="col-lg-3 form-group">
                                <label class="control-label text-muted font-weight-bold">{{ __('home.phone') }}</label>
                                <input type="text" class="form-control bg-light" value="{{ $data['supllier']->supllier->phone ?? '' }}" readonly>
                            </div>
                            <div class="col-lg-3 form-group">
                                <label class="control-label text-muted font-weight-bold">{{ __('home.Location') }}</label>
                                <input type="text" class="form-control bg-light" value="{{ $data['supllier']->supllier->location ?? '' }}" readonly>
                            </div>
                            <div class="col-lg-3 form-group">
                                <label class="control-label text-muted font-weight-bold">{{ __('home.purchasedate') }}</label>
                                <input type="text" class="form-control bg-light" value="{{ $data['supllier']->created_at ?? '' }}" readonly>
                            </div>
                            
                            <input name="returnAllpurchase_id" id="returnAllpurchase_id" value="{{ $orderId }}" hidden>

                            <?php
                                $pay = '';
                                if($data['supllier']->Limit_credit == "Cash") {
                                    $pay = __('report.cash');
                                } elseif($data['supllier']->Limit_credit == "Shabka") {
                                    $pay = __('report.shabka');
                                } elseif($data['supllier']->Limit_credit == "Bank_transfer") {
                                    $pay = __('home.Bank_transfer');
                                } else {
                                    $pay = __('report.credit');
                                }
                            ?>
                            <div class="col-lg-3 form-group">
                                <label class="control-label text-muted font-weight-bold">{{ __('home.paymentmethod') }}</label>
                                <input type="text" class="form-control bg-light" value="{{ $pay ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول المنتجات -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card mg-b-20 border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-transparent pb-0">
                        <h4 class="card-title mg-b-0 font-weight-bold text-dark">{{ __('home.purchases') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-md-nowrap mb-0 table-striped table-bordered text-center align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('home.productNo') }}</th>
                                        <th>{{ __('home.product') }}</th>
                                        <th>{{ __('users.branch') }}</th>
                                        <th>{{ __('home.quantity') }}</th>
                                        <th>{{ __('home.purchase') }}</th>
                                        <th>{{ __('home.addedValue') }}</th>
                                        <th>{{ __('home.total') }}</th>
                                        <th>{{ __('home.RETURNSPURCHAE') }}</th>
                                        <th>{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 0;
                                        $totalprice = 0;
                                        $totalAddedvalue = 0; 
                                    ?>
                                    @foreach ($data['product'] as $product)
                                        @if($product->numberofpice != 0)
                                            <?php 
                                                $i++;
                                                $totalprice += $product->purchasingـprice * $product->numberofpice;
                                                $totalAddedvalue += $product->Added_Value * $product->numberofpice; 
                                            ?>
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td dir="ltr" class="font-weight-bold">{{ $product->productData->Product_Code ?? '' }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $data['branch'] }}</td>
                                                <td><span class="badge badge-light px-2 py-1 font-weight-bold">{{ $product->numberofpice }}</span></td>
                                                <td>{{ number_format($product->purchasingـprice, 2) }}</td>
                                                <td>{{ number_format($product->Added_Value, 2) }}</td>
                                                <td class="font-weight-bold">{{ number_format(($product->Added_Value + $product->purchasingـprice) * $product->numberofpice, 2) }}</td>
                                                <td><span class="badge badge-warning px-2 py-1">{{ $product->returns_purchase }}</span></td>
                                                <td>
                                                    <a class="btn btn-sm btn-info mb-1" 
                                                       data-effect="effect-scale"
                                                       data-id="{{ $product->productData->id ?? '' }}"
                                                       data-section_name="{{ $product->product_name }}"
                                                       data-ordernumber="{{ $data['supllier']->id }}"
                                                       data-description="{{ $product->numberofpice }}" 
                                                       data-toggle="modal"
                                                       href="#exampleModal2" title="تعديل المرتجع">
                                                        <i class="las la-pen"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- جدول إجمالي الأسعار -->
                        <div class="row mt-4 justify-content-end">
                            <div class="col-lg-6">
                                <div class="table-responsive">
                                    <table class="table border text-md-nowrap mb-0 table-bordered table-striped text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ __('home.the amount') }}</th>
                                                <th>{{ __('home.discount') }}</th>
                                                <th>{{ __('home.addedValue') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ number_format($totalprice, 2) }}</td>
                                                <td>{{ number_format($data['resource_purchases']->discount ?? 0, 2) }}</td>
                                                <?php
                                                    $totalAddedvalue = ($data['resource_purchases']->In_debt ?? 0) - ($totalprice - ($data['resource_purchases']->discount ?? 0));
                                                ?>
                                                <td>{{ number_format($totalAddedvalue, 2) }}</td>
                                                <td class="font-weight-bold text-success">{{ number_format($data['resource_purchases']->In_debt ?? 0, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- الأزرار التحتية (طباعة + إرجاع الكل) -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a class="btn btn-success px-4 py-2 shadow-sm" href="{{ url('/' . 'printReturnpurchases' . '/' . $orderId) }}" style="font-weight: 600; border-radius: 8px;">
                                <i class="fas fa-print ml-1"></i> {{ __('home.print') }}
                            </a>

                            <a class="btn btn-danger px-4 py-2 shadow-sm" data-effect="effect-scale" data-toggle="modal" href="#paymentmethod111" style="font-weight: 600; border-radius: 8px;">
                                <i class="las la-undo ml-1"></i> {{ __('home.returninvoiceItem') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal إرجاع كل الفاتورة -->
    <div class="modal fade" id="paymentmethod111" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle ml-1"></i> {{ __('home.alert') }}</h6>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <input type="number" class="form-control" name="recentretrn" id="recentretrn" value="0" hidden>
                    <h5 class="text-danger font-weight-bold mb-0">{{ __('home.Are_you_sure') }}</h5>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">{{ __('home.cancel') }}</button>
                    <button id="returnAll" data-dismiss="modal" class="btn btn-danger px-4 font-weight-bold">{{ __('home.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تعديل جزء من المرتجع -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel">{{ __('home.RETURNSPURCHASEpart') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="ordernumber" id="ordernumber" value="">
                        
                        <div class="form-group">
                            <label for="product_name" class="col-form-label font-weight-bold">{{ __('home.product') }}</label>
                            <input class="form-control bg-light" name="product_name" id="product_name" type="text" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="return_quentity" class="col-form-label font-weight-bold">{{ __('home.numberofpicereturens') }}</label>
                            <input class="form-control" id="return_quentity" name="return_quentity" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">{{ __('home.cancel') }}</button>
                        <button class="btn btn-primary px-4 font-weight-bold" id="button_1" data-dismiss="modal">{{ __('home.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        $('select[name="productNo"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');
                $.ajax({
                    url: "{{ URL::to('getproduct') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success123");
                        console.log(data);
                        console.log("{{ URL::to('getsupllier') }}/" + selectclientid);
                        $('#productnameshow').val(data['product_name']);

                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
        $('select[name="productname"]').on('change', function() {
            console.log('AJAX load   work 0000');

            var selectclientid = $(this).val();
            if (selectclientid) {
                console.log('AJAX load   work');
                $.ajax({
                    url: "{{ URL::to('getproduct') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log("success123");
                        console.log(data);
                        console.log("{{ URL::to('getsupllier') }}/" + selectclientid);
                        $('#productnameshow').val(data['product_name']);

                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    </script>




    <script>
    
       // 1. حدث تعديل كمية المنتج الفردي
$("#button_1").click(function(e) {
    e.preventDefault(); // إصلاح الخطأ باستخدام المتغير ممرر المعامل e

    var $btn = $(this);
    var url = "{{ URL::to('purchaseproduct_update') }}";
    var token_search = $("#token_search").val();

    // تعطيل الزر مؤقتاً لحماية قاعدة البيانات من النقرات المتكررة
    $btn.prop('disabled', true);

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'html',
        cache: false,
        data: {
            _token: token_search,
            return_quentity: $('#return_quentity').val(),
            ordernumber: $('#ordernumber').val(),
            id: $('#id').val()
        },
        success: function(data) {
            $("#response_div").html(data);

            // تنبيه نجاح احترافي بـ SweetAlert
            Swal.fire({
                title: 'تم التعديل بنجاح',
                text: 'Has been modified successfully',
                icon: 'success',
                confirmButtonText: 'موافق',
                confirmButtonColor: '#3085d6'
            });
        },
        error: function(response) {
            console.log(response);
            
            Swal.fire({
                title: 'خطأ / Error',
                text: "{{ __('home.sorryerror') }}",
                icon: 'error',
                confirmButtonText: 'إغلاق',
                confirmButtonColor: '#d33'
            });
        },
        complete: function() {
            // إعادة تفعيل الزر بعد انتهاء الطلب
            $btn.prop('disabled', false);
        }
    });
});




// 2. حدث إرجاع الفاتورة بالكامل
$("#returnAll").click(function(e) {
    e.preventDefault(); // إصلاح منع تحديث الصفحة المباشر هنا أيضاً

    var $btnAll = $(this);
    var url = "{{ URL::to('returnAllpurchase') }}";
    var token_search = $('#token_search').val();

    // التحقق من شرطك المخصص لمنع التكرار المالي
    if ($('#recentretrn').val() == 0) {
        $('#recentretrn').val(1);
        
        // إخفاء الزر لتجنب نقره مجدداً أثناء المعالجة
        $btnAll.css('visibility', 'hidden');

        $.ajax({
            url: url,
            type: 'post',
            cache: false,
            data: {
                _token: token_search,
                ordernumber: $('#returnAllpurchase_id').val()
            },
            success: function(data) {
                $("#response_div").html(data);

                Swal.fire({
                    title: 'تم إرجاع الكل بنجاح',
                    text: 'All items have been returned successfully',
                    icon: 'success',
                    confirmButtonText: 'موافق',
                    confirmButtonColor: '#28a745'
                });
            },
            error: function(response) {
                console.log(response);
                // إعادة الزر للظهور وتصفير الشرط في حال الفشل لإعطاء المستخدم فرصة أخرى
                $btnAll.css('visibility', 'visible');
                $('#recentretrn').val(0);

                Swal.fire({
                    title: 'خطأ / Error',
                    text: "{{ __('home.sorryerror') }}",
                    icon: 'error',
                    confirmButtonText: 'إغلاق',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }
});

    </script>


@endsection
