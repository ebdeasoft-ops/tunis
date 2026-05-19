@extends('layouts.master')

@section('css')
<style>
    /* طباعة */
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

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: rgba(236, 240, 250, 1);
        color: #419BB2;
        font-weight: bold;
    }

    .total-row th, .total-row td {
        font-weight: bold;
        background-color: #f0f8ff;
    }

    .rtl {
        direction: rtl;
    }

    .ltr {
        direction: ltr;
    }

</style>
@endsection

@section('title')
General Budget
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
@php
    $currentDate = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

    // مجموعات الحسابات
    $fixedAssets = App\Models\financial_accounts::where('parent_account_number', 74)->get();
    $currentAssets = App\Models\financial_accounts::where('parent_account_number',75)->get();
    $liabilities = App\Models\financial_accounts::where('account_type', 2)->where('parent_account_number',NULL)->get();
    $equity = App\Models\financial_accounts::where('account_type', 5)->where('parent_account_number',NULL)->get();

    function calculateTotals($accounts) {
        $debit = $accounts->sum('debtor_current');
        $credit = $accounts->sum('creditor_current');
        return ['debit' => $debit, 'credit' => $credit, 'balance' => $debit - $credit];
    }

   

@endphp

<div class="row row-sm">
    <div class="col-md-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                <div class="card-body">

                    <!-- Header -->
                    <div class="invoice-header">
                        <div class="billed-from ltr">
                            <span style="font-size:25px">{{ Nameen }}</span>
                            <p>{{ describtionen }}</p>
                            <p>{{ STen }}</p>
                            <p>{{ Taxen }}</p>
                        </div>

                        <div class="text-center">
                                                  <?php
$logo=camplogo;
    ?>
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

         
                        </div>

                        <div class="billed-from rtl">
                            <span style="font-size:25px">{{ Namear }}</span>
                            <p>{{ describtionar }}</p>
                            <p>{{ STar }}</p>
                            <p>{{ Taxar }}</p>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="invoice-title">
                        <h3>تقرير الميزانية العامة للسنة الحالية | General Budget Report</h3>
                    </div>

                    <!-- Dates -->
                    <table>
                        <tr>
                            <th>{{ __('report.fromdate') }}</th>
                            <td> 2025-01-01</td>
                            <th>{{ __('report.todate') }}</th>
                            <td> 2025-12-31</td>
                            <th>{{ __('home.exportTime') }}</th>
                            <td>{{ $currentDate }}</td>
                        </tr>
                    </table>

                    <br>

                    <!-- Financial Table -->
                    @php
                        $categories = [
                            'الأصول الثابتة' => $fixedAssets,
                            'الأصول المتداولة' => $currentAssets,
                            'الخصوم' => $liabilities,
                            'حقوق الملكية' => $equity,
                        ];
                    @endphp

                    @foreach($categories as $title => $accounts)
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4">{{ $title }}</th>
                                </tr>
                                <tr>
                                    <th>الحساب / Account</th>
                                    <th>مدين | Debit</th>
                                    <th>دائن | Credit</th>
                                    <th>الإجمالي | Balance (SAR)</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php
                                $total_depit=0;
                                $total_creaite=0;
                                
                                ?>
                                @foreach($accounts as $account)
                                @if(App\Models\financial_accounts::where('parent_account_number',$account->id)->count()==0)
                                @if($account->debtor_current - $account->creditor_current!=0)
                                    <tr>
                                        <td>{{ $account->name }}</td>
                                        <td>{{ $account->debtor_current }}</td>
                                        <td>{{ $account->creditor_current }}</td>
                                        <td>{{ round($account->debtor_current - $account->creditor_current, 2) }}</td>
                                    </tr>
                                    
                                         <?php
                                $total_depit+=$account->debtor_current;
                                $total_creaite+=$account->creditor_current;
                                
                                ?>
                                
                                
                                    @endif
                                    @else
                                    
                                                     <tr>
                                    <td colspan="4">{{ $account->name }}</td>
                                </tr>
                          
                                @foreach(App\Models\financial_accounts::where('parent_account_number',$account->id)->get() as $account_2)
                                   
                              @if(App\Models\financial_accounts::where('parent_account_number',$account_2->id)->count()==0)
                                @if($account_2->debtor_current - $account_2->creditor_current!=0)                           
                            
                                    <tr>
                                        <td>{{ $account_2->name }}</td>
                                        <td>{{ $account_2->debtor_current }}</td>
                                        <td>{{ $account_2->creditor_current }}</td>
                                        <td>{{ round($account_2->debtor_current - $account_2->creditor_current, 2) }}</td>
                                    </tr>
                                                <?php
                                $total_depit+=$account->debtor_current;
                                $total_creaite+=$account->creditor_current;
                                
                                ?>
                                   @endif
                                    @else
                                   @foreach(App\Models\financial_accounts::where('parent_account_number',$account_2->id)->get() as $account_3)
                                     @if($account_3->debtor_current - $account_3->creditor_current!=0)                           
                            
                                    <tr>
                                        <td>{{ $account_3->name }}</td>
                                        <td>{{ $account_3->debtor_current }}</td>
                                        <td>{{ $account_3->creditor_current }}</td>
                                        <td>{{ round($account_3->debtor_current - $account_3->creditor_current, 2) }}</td>
                                    </tr>
                                                <?php
                                $total_depit+=$account->debtor_current;
                                $total_creaite+=$account->creditor_current;
                                
                                ?>
  @endif
                                    @endforeach
               
                                    
                                    @endif
                                    @endforeach
                                    @endif
                                @endforeach
       @php $tot = calculateTotals($accounts); @endphp
                                <tr class="total-row">
                                    <td>المجموع | Total</td>
                                    <td>{{ $total_depit }}</td>
                                    <td>{{$total_creaite }}</td>
                                    <td>{{ round($total_depit-$total_creaite, 2) }}</td>
                                </tr>
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
</script>
@endsection
