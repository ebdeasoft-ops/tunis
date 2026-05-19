@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<style>
    /* إطار البحث العلوي */
    .search-section {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e1e6f1;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }
    
    .parent-label {
        font-weight: 600;
        color: #3b4863;
        margin-bottom: 8px;
        display: block;
        font-size: 13px;
    }

    .our-table thead th {
        background-color: #f9faff;
        color: #ff4f1f !important;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        border-bottom: 2px solid #edf2f9 !important;
    }

    .btn-action-list {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .total-badge {
        font-size: 15px;
        font-weight: bold;
        color: #28a745;
    }
</style>
@endsection

@section('title')
{{ __('home.recentquotation') }}
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.recentquotation') }}</h4>
        </div>
    </div>
</div>
@endsection

@section('content')

@if (count($errors) > 0)
<div class="alert alert-danger">
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>خطأ</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                
                <div class="search-section">
                    <div class="row">
                        <div class="col-lg-4">
                            <label class="parent-label">{{ __('home.enterinvoicenumber') }}</label>
                            <input class="form-control" value="{{ $start_at ?? '' }}" id="invoiceid_search" placeholder="00" type="text" onchange="searchaboutinvoiceByIdfunction()">
                        </div>

                        <div class="col-lg-4">
                            <label class="parent-label">{{ __('home.chooseclient') }}</label>
                            <select class="form-control select2" name="clientnamesearch" id="clientnamesearch">
                                <option label="-- اختر العميل --"></option>
                                @foreach (App\Models\customers::get() as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->id == 1 ? __('home.Cash Custome') : $customer->name }} - {{ $customer->tax_no }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4">
                            <label class="parent-label">{{ __('users.branch') }}</label>
                            <select class="form-control select2" name="branch" id="branch_select">
                                <option value="{{ Auth()->user()->branch->id }}">{{ Auth()->user()->branch->name }}</option>
                                @foreach (App\Models\branchs::get() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" id="previous1uotestable">
                    <table class="table table-hover text-md-nowrap text-center our-table" id="example12">
                        <thead>
                            <tr>
                                <th>{{ __('home.Invoice_no') }}</th>
                                <th>{{ __('home.clietName') }}</th>
                                <th>{{ __('home.date') }}</th>
                                <th>{{ __('home.branch') }}</th>
                                <th>{{ __('home.total') }}</th>
                                <th>{{ __('home.operations') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $avtData = App\Models\Avt::find(1);
                                $avtSaleRate = $avtData ? $avtData->AVT : 1;
                            @endphp
                            
                            @foreach ($data as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td dir="ltr">{{ $product->customer->name }}</td>
                                <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                <td>{{ $product->branch->name }}</td>
                                <td>
                                    @php
                                        $items = App\Models\offer_price_to_customer_items::where('order_id', $product->id)->get();
                                        $subTotal = 0;
                                        $discountTotal = 0;
                                        foreach($items as $item) {
                                            $subTotal += ($item->PriceWithoudTax * $item->quantity);
                                            $discountTotal += $item->discount;
                                        }
                                        $netPrice = round($subTotal, 2) - round($discountTotal, 2);
                                        $totalWithTax = round($netPrice * $avtSaleRate, 2) + $netPrice;
                                    @endphp
                                    <span class="total-badge">{{ number_format($totalWithTax, 2) }}</span>
                                </td>
                                <td>
                                    <div class="btn-action-list">
                              
                                        <a class="btn btn-sm btn-danger modal-effect" data-effect="effect-scale" 
                                           data-id="{{ $product->id }}" data-toggle="modal" href="#delete_quotation">
                                            <i class="las la-trash"></i>
                                        </a>

                                        <a class="btn btn-sm btn-success" href="generate_pdf_qoute/{{ $product->id }}" target="_blank" title="PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>

                                
                                        <form action="{{ url(LaravelLocalization::getCurrentLocale() . '/print_order_perice_to_customer') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="OrderNoprint" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentmethod">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.paymentmethod') }}</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-right">
                <div class="text-center mb-3">
                    <label class="parent-label">{{ __('home.total') }}:</label>
                    <span id="totalvalue" class="h4 text-primary">0</span> <small>{{ __('home.SAR') }}</small>
                </div>
                
                <div class="form-group">
                    <label>{{ __('home.paymentmethod') }}</label>
                    <select class="form-control" name="paymodal" id='paymodal'>
                        <option value="Cash">{{ __('report.cash') }}</option>
                        <option value="Shabka">{{ __('report.shabka') }}</option>
                        <option value="Bank_transfer">{{ __('home.Bank_transfer') }}</option>
                        <option value="Credit">{{ __('report.credit') }}</option>
                        <option value="Partition">{{ __('home.Partition of the amount') }}</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="small">{{ __('report.cash') }}</label>
                        <input type="text" class="form-control" id="cashamount" readonly value="0">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="small">{{ __('report.shabka') }}</label>
                        <input type="text" class="form-control" id="bankamount" readonly value="0">
                    </div>
                    <div class="col-6">
                        <label class="small">{{ __('home.Bank_transfer') }}</label>
                        <input type="text" class="form-control" id="Bank_transfer" readonly value="0">
                    </div>
                    <div class="col-6">
                        <label class="small">{{ __('report.credit') }}</label>
                        <input type="text" class="form-control" id="creaditamount" readonly value="0">
                    </div>
                </div>
                <input hidden type="text" id="invoiceid_hidden">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="confirmpayment" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_quotation">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.alert') }}</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('home.Are_you_sure_delete') }}</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.cancel') }}</button>
                <button id="delete_quotation_function" class="btn btn-danger">{{ __('home.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({ placeholder: 'اختر...', width: '100%' });

        // مودال الدفع - جلب البيانات
        $('#paymentmethod').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var total = button.data('totalinvoice');
            $('#invoiceid_hidden').val(id);
            $('#totalvalue').text(total);
            $('#cashamount').val(total);
        });

        // مودال الحذف - جلب البيانات
        $('#delete_quotation').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            $('#delete_id').val(button.data('id'));
        });

        // تغيير طريقة الدفع
        $('#paymodal').on('change', function() {
            var method = $(this).val();
            var total = $('#totalvalue').text();
            
            // صفر جميع الحقول أولاً
            $('#cashamount, #bankamount, #Bank_transfer, #creaditamount').val(0).prop('readonly', true);

            if(method === 'Cash') $('#cashamount').val(total);
            else if(method === 'Shabka') $('#bankamount').val(total);
            else if(method === 'Bank_transfer') $('#Bank_transfer').val(total);
            else if(method === 'Credit') $('#creaditamount').val(total);
            else {
                // في حالة التقسيط/التجزئة
                $('#cashamount, #bankamount, #Bank_transfer, #creaditamount').prop('readonly', false);
            }
        });

        // تأكيد الدفع
        $("#confirmpayment").click(function() {
            var id = $('#invoiceid_hidden').val();
            var cash = $('#cashamount').val() || 0;
            var bank = $('#bankamount').val() || 0;
            var credit = $('#creaditamount').val() || 0;
            var transfer = $('#Bank_transfer').val() || 0;
            var method = $('#paymodal').val();
            var total = parseFloat($('#totalvalue').text());

            var sum = parseFloat(cash) + parseFloat(bank) + parseFloat(credit) + parseFloat(transfer);

            if (Math.abs(sum - total) < 0.01) {
                $.ajax({
                    url: "{{ URL::to('updatepaymentconfirmpayment_in_quotation') }}/" + id + '/' + cash + '/' + bank + '/' + credit + '/' + transfer + '/' + method,
                    type: "GET",
                    success: function(data) {
                        location.reload();
                    },
                    error: function() { alert("{{ __('home.sorryerror') }}"); }
                });
            } else {
                alert("{{ __('home.entermonycorrect') }}");
            }
        });

        // حذف العرض
        $("#delete_quotation_function").click(function() {
            var id = $('#delete_id').val();
            $.ajax({
                url: "{{ URL::to('delete_offer_price') }}/" + id,
                type: "GET",
                success: function(response) {
                    location.reload();
                }
            });
        });
    });

    // البحث برقم الفاتورة
    function searchaboutinvoiceByIdfunction() {
        var id = $('#invoiceid_search').val();
        if (id != '') {
            $.ajax({
                url: "{{ URL::to('searchpreviousquotes') }}/" + id,
                type: "GET",
                success: function(data) {
                    $("#previous1uotestable").html(data);
                }
            });
        }
    }

    // البحث بالعميل
    $('#clientnamesearch').on('change', function() {
        var id = $(this).val();
        $.ajax({
            url: "{{ URL::to('getquotebycustomer') }}/" + id,
            type: "GET",
            success: function(data) {
                $("#previous1uotestable").html(data);
            }
        });
    });

    // البحث بالفرع
    $('#branch_select').on('change', function() {
        var id = $(this).val();
        $.ajax({
            url: "{{ URL::to('getquotebybranch') }}/" + id,
            type: "GET",
            success: function(data) {
                $("#previous1uotestable").html(data);
            }
        });
    });
</script>
@endsection