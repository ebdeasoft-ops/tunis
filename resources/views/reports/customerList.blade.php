@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

<style>
    /* تحسين شكل الجدول والبحث */
    .dataTables_filter input {
        border: 2px solid #5b73e8 !important;
        border-radius: 5px;
        padding: 5px 15px !important;
        margin-bottom: 10px;
        width: 300px !important;
    }
    .invoice-table thead th {
        background-color: #f8f9fa;
        color: #333;
        vertical-align: middle !important;
    }
    .total-row {
        background-color: #ebeffd !important;
        font-weight: bold;
        font-size: 16px;
    }
</style>
@endsection

@section('title')
{{__('home.customer_supplier_account')}}
@stop

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{__('home.customer_supplier_account')}}</h4>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">كشف حسابات العملاء (من {{ date('Y') }}-01-01 إلى اليوم)</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="customerTable" class="table text-md-nowrap mb-0 table-striped invoice-table text-center" style="border:1px solid #ddd;">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{__('home.clietName')}}</th>
                                <th class="border-bottom-0">{{__('home.tax_number')}}</th>
                                <th class="border-bottom-0 text-success">{{__('home.debit')}} (مدين)</th>
                                <th class="border-bottom-0 text-danger">{{__('home.credit')}} (دائن)</th>
                                <th class="border-bottom-0">{{__('home.current balance')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $totalAllCredit = 0; 
                                $totalAllDebit = 0; 
                                $i = 1;
                            @endphp

                            @foreach($customers as $customer)
                                @php
                                    $credit = $customer->total_credit ?? 0;
                                    $debit = $customer->total_debit ?? 0;
                                    $balance = $debit - $credit;
                                @endphp

                                {{-- عرض فقط من لديهم حركات مالية --}}
                                @if($debit-$credit != 0)
                                    @php 
                                        $totalAllCredit += $credit; 
                                        $totalAllDebit += $debit; 
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td class="font-weight-bold">{{ $customer->name }}</td>
                                        <td>{{ $customer->tax_no ?? '-' }}</td>
                                        <td class="text-success">{{ number_format($debit, 2) }}</td>
                                        <td class="text-danger">{{ number_format($credit, 2) }}</td>
                                        <td>
                                            @if($balance > 0)
                                                <span class="text-success font-weight-bold">{{__('home.debit')}} ({{ number_format($balance, 2) }})</span>
                                            @elseif($balance < 0)
                                                <span class="text-danger font-weight-bold">{{__('home.credit')}} ({{ number_format(abs($balance), 2) }})</span>
                                            @else
                                                <span class="badge badge-light">{{__('home.Balanced')}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" class="text-center">الإجمالي العام</td>
                                <td class="text-success">{{ number_format($totalAllDebit, 2) }}</td>
                                <td class="text-danger">{{ number_format($totalAllCredit, 2) }}</td>
                                <td>
                                    @php $final = $totalAllDebit - $totalAllCredit; @endphp
                                    @if($final > 0)
                                        <span class="text-success">{{ number_format($final, 2) }} (مدين)</span>
                                    @else
                                        <span class="text-danger">{{ number_format(abs($final), 2) }} (دائن)</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // تفعيل الـ DataTable مع ميزة البحث الفوري بالاسم
        $('#customerTable').DataTable({
            language: {
                searchPlaceholder: 'ابحث باسم العميل...',
                sSearch: '',
                lengthMenu: '_MENU_',
                info: "عرض _START_ إلى _END_ من أصل _TOTAL_ عميل",
                paginate: {
                    first: "الأول",
                    last: "الأخير",
                    next: "التالي",
                    previous: "السابق"
                }
            },
            dom: 'Bfrtip', // لإظهار أزرار الطباعة والبحث
            buttons: [
                { extend: 'excel', className: 'btn btn-success-light', text: 'تصدير Excel' },
                { extend: 'print', className: 'btn btn-primary-light', text: 'طباعة' }
            ],
            pageLength: 50, // عدد الصفوف في الصفحة
            order: [[1, 'asc']] // ترتيب أبجدي حسب الاسم
        });

        // إخفاء رسالة النجاح بعد 4 ثواني
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 4000);
    });
</script>
@endsection