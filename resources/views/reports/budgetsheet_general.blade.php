@extends('layouts.master')

@section('css')
<style>
@media print {
    #print_Button {
        display: none;
    }
}

body {
    font: 13pt Georgia, "Times New Roman", Times, serif;
    line-height: 1.5;
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    margin-bottom: 20px;
}

.billed-from {
    width: 30%;
    text-align: center;
}

.invoice-title {
    text-align: center;
    margin: 20px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: rgba(236, 240, 250, 1);
    color: #419BB2;
    font-weight: bold;
}

.rtl {
    direction: rtl;
}

.ltr {
    direction: ltr;
}

/* تنسيق الشجرة والتوسيع */
.toggle-account {
    cursor: pointer;
    user-select: none;
}

.toggle-account:hover {
    color: #007bff;
}
</style>
@endsection

@section('title')
{{ __('home.general_budget') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
@php
$currentDate = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");
@endphp

<div class="row row-sm">
    <div class="col-md-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">

                    <!-- Header -->
                    <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">

                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>
                            <span style="font-size:25px">{{Nameen}}</span>
                            <br>
                            <p dir=ltr> {{describtionen}} </p>
                            <span dir=ltr>{{STen}} </span>
                            <p dir=ltr> {{Taxen}} </p>

                        </div>
                        <div class="row">
                            <?php
$logo=camplogo;
    ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}"
                                    class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

                        </div>


                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                            <span style="font-size:25px">{{Namear}}</span>
                            <br>
                            <p> {{describtionar}}</p>
                            <p>{{STar}}</p>
                            <p>{{Taxar}}</p>

                        </div><!-- billed-from -->
                    </div><!-- invoice-header -->
                    <!-- Title -->
                    <div class="invoice-title">
                        <h3>تقرير ميزان المراجعة | Trial Balance Report</h3>
                    </div>

                    <!-- Dates -->
                    <table>
                        <tr>
                            <th>{{ __('report.fromdate') }}</th>
                            <td>{{ $start_at }}</td>
                            <th>{{ __('report.todate') }}</th>
                            <td>{{ $end_at }}</td>
                            <th>{{ __('home.exportTime') }}</th>
                            <td>{{ $currentDate }}</td>
                        </tr>
                    </table>

                    <br>

                    <!-- Financial Tables by Type -->
                    @foreach($types as $type)
                    @php
                    $typeRootAccounts = $allAccounts->where('account_type', $type->id)->where(function($q) {
                    return $q->parent_account_number == null || $q->parent_account_number == 0;
                    });
                    @endphp

                    <table>
                        <thead>
                            <tr>
                                <th colspan="4">{{ app()->getLocale() == 'ar' ? $type->name_ar : $type->name_en }}</th>
                            </tr>
                            <tr>
                                <th>الحساب / Account</th>
                                <th>مدين | Debit</th>
                                <th>دائن | Credit</th>
                                <th>الإجمالي | Balance (SAR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typeRootAccounts as $account)
                            @php
                            // جلب الأرصدة مباشرة من المصفوفة المحسوبة مسبقاً في الـ Controller
                            $totals = $accountTotals[$account->id] ?? ['debtor' => 0, 'creditor' => 0, 'balance' => 0];
                            @endphp
                            <tr style="background-color: #e8f4f8; font-weight: bold; color: #2c3e50;">
                                <td style="text-align: right; padding-right: 15px;">
                                    <span class="toggle-account" data-id="{{ $account->account_number }}">
                                        <i class="fa fa-folder text-primary ml-1 fa-icon"></i> {{ $account->name }}
                                    </span>
                                </td>
                                <td>{{ number_format($totals['debtor'], 2) }}</td>
                                <td>{{ number_format($totals['creditor'], 2) }}</td>
                                <td>{{ number_format(abs($totals['balance']), 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    @endforeach

                    <hr>

                    <button class="btn btn-danger float-left mt-3" id="print_Button" onclick="printDiv()">
                        <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }}
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function printDiv() {
    var printContents = document.getElementById('print').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}

// جلب الأبناء عند الضغط عبر الـ AJAX
$(document).on('click', '.toggle-account', function() {
    let accountId = $(this).data('id');
    let parentTr = $(this).closest('tr');
    let nextTr = parentTr.next('.children-row');
    let icon = $(this).find('.fa-icon');

    // إذا كانت الأبناء مفتوحة مسبقاً، قم بإخفائها فقط
    if (nextTr.length > 0) {
        nextTr.toggle();
        icon.toggleClass('fa-folder-open fa-folder');
        return;
    }

    let start_at = '{{ $start_at }}';
    let end_at = '{{ $end_at }}';

    $.ajax({
        url: "{{ route('reports.budget.children') }}",
        type: 'GET',
        data: {
            parent_id: accountId,
            start_at: start_at,
            end_at: end_at
        },
        beforeSend: function() {
            icon.removeClass('fa-folder').addClass('fa-spinner fa-spin');
        },
        success: function(response) {
            let rowHtml =
                `<tr class="children-row"><td colspan="4" style="padding: 0; background-color: #fcfcfc;">${response}</td></tr>`;
            parentTr.after(rowHtml);
            icon.removeClass('fa-spinner fa-spin').addClass('fa-folder-open');
        },
        error: function() {
            alert('حدث خطأ أثناء جلب البيانات، حاول مرة أخرى.');
            icon.removeClass('fa-spinner fa-spin').addClass('fa-folder');
        }
    });
});
</script>
@endsection