@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">

<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

<!-- Internal Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ __('report.salesـprofits') }}
@endsection

@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('report.salesـprofits') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
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

                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'salesـprofits')) }}" method="POST" role="search" id="searchForm" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-lg-3" id="start_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control parent-input fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-3" id="end_at">
                                <label class="parent-label" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control parent-input fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-4 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('report.Enter_employeeـname') }} </p>
                                <select class="form-control parent-input" name="userid" required>
                                    <option value="-"> {{ __('report.Enter_employeeـname') }} </option>
                                    @foreach (App\Models\User::get() as $section)
                                        <option value="{{ $section->id }}"> {{ $section->name }} </option>
                                    @endforeach
                                </select>
                            </div><!-- col-4 -->

                            <div class="col" id="type">
                                <p class="mg-b-10 parent-label"> {{ __('users.branch') }} </p>
                                <select class="form-control parent-input" name="branch" required>
                                    <option value="-" selected>{{ __('users.allbranchs') }}</option>
                                    @foreach (App\Models\branchs::get() as $branch)
                                    <option style="font-size: 15px" value="{{ $branch->id }}"> {{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                <input class="form-control parent-input" name="productNo" hidden=true>
                            </div>

                        </div><br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success p-1 mx-1">
                                {{ __('home.search') }} <i class="las la-search"></i>
                            </button>

                            <button type="button" id="exportExcelBtn" class="btn btn-primary p-1 mx-1">
                                تصدير إكسيل <i class="las la-file-excel"></i>
                            </button>
                        </div>

                        <br>
                    </form>


                    @if (isset($Invoices))
                    <div style="border-radius: 10px" class="card pb-0 px-3">
                        <br>

                        <?php
                        $startat = '';
                        $endat = '';
                        $totalprofit = 0;

                        // نسبة الضريبة (مرة واحدة بدل الاستعلام في كل فاتورة)
                        $saleavt = optional(App\Models\Avt::find(1))->AVT ?? 0;
                        ?>

                        <br>

                        <div class="table-responsive hoverable-table">
                            <table class="table table-hover" id="example1" data-page-length='50' style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.branch') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('report.profit') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                        <th style="color: #FF4F1F;font-size:12px" class="border-bottom-0">{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    $benfitshabka = 0;
                                    $benfitcradit = 0;
                                    $benfitcash = 0;
                                    $benfitBank_transfer = 0;
                                    ?>

                                    @foreach ($Invoices as $product)

                                    <?php
                                    $profit = 0;

                                    if ($i == 0) {
                                        $startat = $product->created_at;
                                    }
                                    $endat = $product->created_at;
                                    $i++;

                                    // ربح الفاتورة اعتماداً على متوسط التكلفة (average_cost)
                                    foreach (App\Models\sales::where('invoice_id', $product->id)->where('quantity', '!=', 0)->get() as $productaa) {
                                        $cost = $productaa->productData->average_cost ?? 0;

                                        $lineProfit = (($productaa->quantity * $productaa->Unit_Price) - $productaa->Discount_Value)
                                                      - ($productaa->quantity * $cost);

                                        $totalprofit += $lineProfit;
                                        $profit += $lineProfit;
                                    }

                                    $totalprofit = $totalprofit - ($product->discount - $product->discountOnProduct);
                                    $invoiceProfit = $profit - ($product->discount - $product->discountOnProduct);

                                    $pay = '';
                                    if ($product->Pay == 'Cash') {
                                        $pay = __('report.cash');
                                    } elseif ($product->Pay == 'Shabka') {
                                        $pay = __('report.shabka');
                                    } elseif ($product->Pay == 'Credit') {
                                        $pay = __('report.credit');
                                    } elseif ($product->Pay == 'Bank_transfer') {
                                        $pay = __('home.Bank_transfer');
                                    } else {
                                        $pay = __('home.Partition of the amount');
                                    }
                                    ?>

                                    <tr>
                                        <td data-target="id">{{ $product->id }}</td>
                                        <td data-target="numberofpice">{{ $product->created_at }}</td>
                                        <td data-target="numberofpice">{{ $product->branch->name }}</td>
                                        <td data-target="numberofpice">
                                            {{ round(($product->Price - $product->discount) + (($product->Price - $product->discount) * $saleavt), 2) }}
                                        </td>
                                        <td>{{ round($invoiceProfit, 2) }}</td>
                                        <td data-target="numberofpice">{{ $pay }}</td>
                                        <td>
                                            <a style="color: #23395D" class="dropdown-item" href="showInvoiceRecent/{{ $product->id }}">
                                                <i style="fill:#072c3c !important" class="fas fa-print"></i>&nbsp;&nbsp;
                                                {{ __('home.show') }}
                                            </a>
                                        </td>
                                    </tr>

                                    <?php
                                    // ربح الفاتورة (للتقسيم حسب طريقة الدفع) بمتوسط التكلفة
                                    $invoiceBenefit = 0;
                                    foreach (App\Models\sales::where('invoice_id', $product->id)->where('save', 1)->get() as $salesbenfit) {
                                        $invoiceBenefit += ($salesbenfit->Unit_Price * $salesbenfit->quantity)
                                                         - (($salesbenfit->productData->average_cost ?? 0) * $salesbenfit->quantity);
                                    }
                                    $invoiceBenefit -= $product->discount;

                                    if ($product->Pay == 'Cash') {
                                        $benfitcash += $invoiceBenefit;
                                    } elseif ($product->Pay == 'Credit') {
                                        $benfitcradit += $invoiceBenefit;
                                    } elseif ($product->Pay == 'Shabka') {
                                        $benfitshabka += $invoiceBenefit;
                                    } elseif ($product->Pay == 'Bank_transfer') {
                                        $benfitBank_transfer += $invoiceBenefit;
                                    } else {
                                        // تقسيم المبلغ: توزيع الربح بنسبة كل طريقة دفع
                                        $paidTotal = $product->cashamount + $product->bankamount + $product->creaditamount + $product->Bank_transfer;
                                        if ($paidTotal > 0) {
                                            $benfitcash          += $invoiceBenefit * ($product->cashamount / $paidTotal);
                                            $benfitshabka        += $invoiceBenefit * ($product->bankamount / $paidTotal);
                                            $benfitcradit        += $invoiceBenefit * ($product->creaditamount / $paidTotal);
                                            $benfitBank_transfer += $invoiceBenefit * ($product->Bank_transfer / $paidTotal);
                                        }
                                    }
                                    ?>
                                    @endforeach
                                </tbody>
                            </table>

                            <br>
                            <br>
                            <br>
                            <br>

                            <div class="table-padding">
                                <table style="border: 2px solid rgba(0,0,0,.3)" class="table table-striped table-bordered text-center my-2">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.benfitcash') }}</th>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ round($benfitcash, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{ __('home.benfitshabka') }}</th>
                                            <th>{{ round($benfitshabka, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.benfitcradit') }}</th>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ round($benfitcradit, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{ __('home.Bank_transfer') }}</th>
                                            <th>{{ round($benfitBank_transfer, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ __('home.total') }}</th>
                                            <th style="background-color: rgba(236, 240, 250, 1);">{{ round($benfitcash + $benfitshabka + $benfitcradit + $benfitBank_transfer, 2) }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center my-3">
                                <a style="background-color: #419BB2;font-size:17px" class="btn btn-success p-1" href="{{ url('/' . ($page = 'printReportProfitSales') . '/' . $branch_id . '/' . $userId . '/' . $startat . '/' . $endat) }}">
                                    {{ __('home.print') }}
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>

                        </div>

                        <br>

                    </div>
                    @endif

                </div>

            </div>

        </div>
        <!-- row closed -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->
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
    $('#exportExcelBtn').on('click', function(e) {
        e.preventDefault();
        var form = $('#searchForm');
        var originalAction = form.attr('action'); // حفظ الرابط الأصلي للبحث

        // تغيير الرابط لرابط الـ Route الخاص بالإكسيل
        var excelRoute = "{{ url(LaravelLocalization::getCurrentLocale() . '/export_sales_excel') }}";

        form.attr('action', excelRoute);
        form.submit(); // إرسال البيانات للـ Route الجديد

        // إعادة الرابط الأصلي بعد الإرسال لكي يعمل زر البحث بشكل طبيعي لاحقاً
        setTimeout(function() {
            form.attr('action', originalAction);
        }, 500);
    });
</script>

<script>
    $(document).ready(function() {
        var timeout = 4000;
        $('.alert').delay(timeout).fadeOut(500);
    });
</script>

@endsection