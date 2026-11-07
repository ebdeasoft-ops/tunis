@extends('layouts.master')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ __('home.Historyـofـproductـsales') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Historyـofـproductـsales') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    <!-- عرض الأخطاء -->
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

    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm mg-b-20" style="border-radius: 12px; border: none;">

                <!-- نموذج البحث -->
                <div class="card-header bg-transparent pb-3 pt-4 border-bottom-0">
                    <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/salesReport') }}"
                          method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row align-items-end">
                            <!-- من تاريخ -->
                            <div class="col-lg-2 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('report.fromdate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}"
                                           name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- إلى تاريخ -->
                            <div class="col-lg-2 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('report.todate') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at"
                                           value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                                </div>
                            </div>

                            <!-- البحث باسم العميل -->
                            <div class="col-lg-3 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('home.searchbyclientname') }}</label>
                                <select class="form-control select2" name="UserId" required>
                                    <option value="-">{{ __('home.searchbyclientname') }}</option>
                                    @foreach (App\Models\customers::all() as $customer)
                                        <option value="{{ $customer->id }}" {{ (isset($customer_id) && $customer_id == $customer->id) ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- الفرع -->
                            <div class="col-lg-2 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('users.branch') }}</label>
                                <select class="form-control" name="branch" required>
                                    <option value="{{ Auth()->user()->branch->id }}">{{ Auth()->user()->branch->name }}</option>
                                    @can('System setting')
                                        <option value="-">{{ __('users.allbranchs') }}</option>
                                        @foreach (App\Models\branchs::all() as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    @endcan
                                </select>
                            </div>

                            <!-- طريقة الدفع -->
                            <div class="col-lg-3 mg-t-10">
                                <label class="form-label font-weight-bold text-muted">{{ __('home.paymentmethod') }}</label>
                                <select class="form-control" name="pay" required>
                                    <option value="-">{{ __('home.paymentmethod') }}</option>
                                    <option value="Cash">{{ __('report.cash') }}</option>
                                    <option value="Shabka">{{ __('report.shabka') }}</option>
                                    <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                                    <option value="Credit">{{ __('report.credit') }}</option>
                                    <option value="Partition">{{ __('home.Partition of the amount') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- زر البحث -->
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-success px-4 py-2 shadow-sm" style="border-radius: 8px;">
                                {{ __('home.search') }}
                                <i class="las la-search ml-1" style="font-size: 16px;"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- محتوى الجدول والنتائج -->
                @if (isset($Invoices))
                    <div class="card-body pt-0">
                        @php
                            $userId = 0;
                            $count = 0;
                            $startat = '';
                            $endat = '';
                            $total = 0;
                            $totaldiscount = 0;
                            $avt = App\Models\Avt::find(1);
                            $saleavt = $avt ? $avt->AVT : 0;

                            // تصفير المجاميع قبل بداية اللوب لتفادي أي قيم قديمة
                            $totalCashAmount = 0;
                            $totalNetworkAmount = 0;
                            $totalBankTransferAmount = 0;
                            $totalCreditAmount = 0;
                        @endphp

                        <!-- جدول عرض الفواتير -->
                        <div class="table-responsive hoverable-table mt-3">
                          <table class="table text-md-nowrap table-hover border-top-0" id="example1" data-page-length='50' style="text-align: center;">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.Invoice_no') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.sallerName') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.clietName') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.date') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.branch') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.total') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.paymentmethod') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('report.cash') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('report.shabka') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.bank_transfer') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('report.credit') }}</th>
                                        <th style="color: #FF4F1F; font-weight: bold;">{{ __('home.operations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Invoices as $product)
                                        @php
                                            $totaldiscount += $product->discount;

                                            $cashAmount    = $product->cashamount;
                                            $networkAmount = $product->bankamount;
                                            $Bank_transfer = $product->Bank_transfer;
                                            $creditAmount  = $product->creaditamount;

                                            // شامل التحويل البنكي ضمن إجمالي الفاتورة
                                            $invoiceTotal = $cashAmount + $networkAmount + $Bank_transfer + $creditAmount;
                                            $total += $invoiceTotal;

                                            // مجاميع لعرضها أسفل الجدول
                                            $totalCashAmount         += $cashAmount;
                                            $totalNetworkAmount      += $networkAmount;
                                            $totalBankTransferAmount += $Bank_transfer;
                                            $totalCreditAmount       += $creditAmount;

                                            if ($count == 0) {
                                                $userId = $product->user_id;
                                                $startat = $product->created_at;
                                            }
                                            $endat = $product->created_at;
                                            $count++;

                                            $pays = match($product->Pay) {
                                                'Cash' => __('report.cash'),
                                                'Shabka' => __('report.shabka'),
                                                'Credit' => __('report.credit'),
                                                'Bank_transfer' => __('home.Bank_transfer'),
                                                default => __('home.Partition of the amount'),
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->user->name ?? '-' }}</td>
                                            <td dir="ltr">{{ $product->customer->name ?? '-' }}</td>
                                            <td>{{ $product->created_at }}</td>
                                            <td>{{ $product->branch->name ?? '-' }}</td>
                                            <td class="font-weight-bold text-success">{{ $invoiceTotal }}</td>
                                            <td>
                                                <span class="badge badge-pill badge-light px-2 py-1">{{ $pays }}</span>
                                            </td>
                                            <td>{{ $cashAmount > 0 ? $cashAmount: '-' }}</td>
                                            <td>{{ $networkAmount > 0 ? $networkAmount : '-' }}</td>
                                            <td>{{ $Bank_transfer > 0 ? $Bank_transfer: '-' }}</td>
                                            <td>{{ $creditAmount > 0 ?$creditAmount: '-' }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ url('showInvoiceRecent/' . $product->id) }}">
                                                    <i class="fas fa-print ml-1"></i> {{ __('home.show') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- جدول إجمالي المبالغ حسب طريقة الدفع -->
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center bg-light" style="border-radius: 8px; overflow: hidden;">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>{{ __('report.cash') }}</th>
                                                <th>{{ __('report.shabka') }}</th>
                                                <th>{{ __('home.bank_transfer') }}</th>
                                                <th>{{ __('report.credit') }}</th>
                                                <th>{{ __('report.totalallprice') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">{{ number_format($totalCashAmount, 2) }}</td>
                                                <td class="font-weight-bold">{{ number_format($totalNetworkAmount, 2) }}</td>
                                                <td class="font-weight-bold">{{ number_format($totalBankTransferAmount, 2) }}</td>
                                                <td class="font-weight-bold">{{ number_format($totalCreditAmount, 2) }}</td>
                                                <td class="text-success font-weight-bold">{{ number_format($total, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الطباعة -->
                        @php
                            // معالجة آمنة لتفادي أخطاء الـ Array في روابط الطباعة إذا لم يتم تمريرهم بشكل مصفوفة
                            $printPay = is_array($pay ?? null) ? $pay : [0 => ($pay ?? '-'), 1 => '-'];
                            $printCustomerId = $customer_id ?? ($UserId ?? '-');
                        @endphp

                        <div class="d-flex justify-content-center mt-4 mb-3">
                            <a class="btn btn-info px-4 py-2 shadow-sm text-white d-flex align-items-center ml-2"
                               style="background-color: #419BB2; border-radius: 8px; font-size: 16px;"
                               href="{{ url('printInvoicesReport/' . ($printPay[1] ?? '-') . '/' . ($printPay[0] ?? '-') . '/' . $startat . '/' . $endat . '/' . $printCustomerId) }}">
                                <i class="fas fa-print ml-2" style="font-size: 18px;"></i>
                                {{ __('home.print') }}
                            </a>

                            <a class="btn btn-info px-4 py-2 shadow-sm text-white d-flex align-items-center"
                               style="background-color: #419BB2; border-radius: 8px; font-size: 16px;"
                               href="{{ url('printInvoicesReportdetails/' . ($printPay[1] ?? '-') . '/' . ($printPay[0] ?? '-') . '/' . $startat . '/' . $endat . '/' . $printCustomerId) }}">
                                <i class="fas fa-print ml-2" style="font-size: 18px;"></i>
                                {{ __('home.Print_without_details') }}
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- row closed -->

@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datative/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تهيئة حقول التاريخ
            $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            // تفعيل Select2
            $('.select2').select2({
                width: '100%'
            });

            // إخفاء التنبيهات تلقائياً
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 4000);
        });
    </script>
@endsection