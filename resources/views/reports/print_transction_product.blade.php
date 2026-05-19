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
border-style: solid;

}
/* تحسينات للشاشة والطباعة */
    .invoice-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        background: #fff;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .main-title {
        color: #419BB2;
        border-bottom: 2px solid #419BB2;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .table thead th {
        background-color: #f8f9fa !important;
        color: #333;
        font-weight: bold;
        vertical-align: middle;
    }
    .total-box {
        background-color: #f0f9ff;
        border: 1px solid #cfe2ff;
        border-radius: 8px;
        padding: 15px;
    }
    @media print {
        .no-print { display: none; }
        body { background: white; }
        .invoice-card { box-shadow: none; border: 1px solid #ddd; }
    }
    </style>
@endsection
@section('title')
    معاينه طباعة المنتجات
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">
                       <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

                    <div class="card-body">
                                  <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%">

<div class="billed-from" style="width:33%;text-align: center;" >
    <br>
     <span style="font-size:18px">{{Nameen}}</span>
    <br>
    <p dir=ltr> {{describtionen}} </p>
    <span dir=ltr>{{STen}} </span>
    <p dir=ltr> {{Taxen}} </p>

</div>
<div class="row">
<?php
$logo=camplogo;
?>
<a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

</div>


<div class="billed-from" style="width:33%;text-align: center;">
    <br>

   <span style="font-size:18px">{{Namear}}</span>
    <br>
    <p> {{describtionar}}</p>
    <p>{{STar}}</p>
    <p>{{Taxar}}</p>

</div><!-- billed-from -->
</div><!-- invoice-header -->
              
                    

        <br />



<div class="card-body invoice-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="main-title">تقرير حركة المنتجات الصادر</h3>
        <div class="text-left" dir="ltr">
            <span class="badge badge-outline-info" style="font-size: 14px; border: 1px solid #419BB2; padding: 5px 10px;">
                {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
            </span>
            <span class="text-muted ml-2">: {{ __('home.exportTime') }}</span>
        </div>
    </div>

    <?php 
        $total_all_invoices_price = 0; 
        $total_all_added_value = 0; 
        $grand_system_cost = 0; 
    ?>

    @foreach( $data['transctions'] as $invoice)
        <div class="invoice-card">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5 class="text-primary" style="font-weight: bold;"># فاتورة رقم: {{$invoice->id}}</h5>
                    <p class="mb-1"><strong>التاريخ:</strong> {{$invoice->created_at}}</p>
                    <p class="mb-0"><strong>الحالة:</strong> 
                        {!! $invoice->reciveInvoiceNumber == 0 
                            ? '<span class="badge badge-danger">بانتظار الاستلام</span>' 
                            : '<span class="badge badge-success">تم الاستلام بنجاح</span>' !!}
                    </p>
                </div>
                <div class="col-md-6 text-left border-right">
                    <p class="mb-1"><strong>من:</strong> {{$invoice->branchfrom->name ?? ''}}</p>
                    <p class="mb-1"><strong>المسؤول:</strong> {{$invoice->userfrom->name ?? ''}}</p>
                    <p class="mb-0 text-muted"><strong>إلى:</strong> {{$invoice->branchto->name ?? ''}}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover text-center border">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>كود المنتج</th>
                            <th>اسم المنتج</th>
                            <th>الكمية</th>
                            <th>سعر الوحدة</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 0; 
                            $invoice_subtotal = 0;
                            $avtRate = App\Models\Avt::find(2)->AVT ?? 0.15;
                        ?>
                        @foreach (App\Models\product_movement_another_branch_items::where('order_id', $invoice->id)->get() as $product)
                            <?php 
                                $i++;
                                $line_total = $product->cost_per_each_withoud_tax * $product->quantity;
                                $invoice_subtotal += $line_total;
                                
                                $total_all_invoices_price += $line_total;
                                $total_all_added_value += ($line_total * $avtRate);
                            ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td class="font-weight-bold text-secondary">{{$product->product->Product_Code ?? ""}}</td>
                                <td>{{$product->product->product_name ?? ""}}</td>
                                <td><span class="badge badge-pill badge-light border">{{$product->quantity}}</span></td>
                                <td>{{number_format($product->cost_per_each_withoud_tax, 2)}}</td>
                                <td class="font-weight-bold">{{number_format($line_total, 2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="sub-total-box">
                    <strong>إجمالي الفاتورة الصافي: </strong>
                    <span class="h5 mb-0" style="color:#333; font-weight:bold;">{{number_format($invoice_subtotal, 2)}} ريال</span>
                </div>
                
                @can('System setting')
                <div class="text-muted">
                    <i class="fas fa-database mr-1"></i> تكلفة النظام: 
                    <strong style="color:#419BB2;">{{number_format($invoice->cost_withod_tax, 2)}} ريال</strong>
                </div>
                <?php $grand_system_cost += $invoice->cost_withod_tax; ?>
                @endcan
            </div>
        </div>
    @endforeach

    <div class="grand-total-section">
        <div class="row text-center align-items-center">
            
            <div class="col-md-3 border-left">
                <span class="total-label">المبلغ (بدون ضريبة)</span>
                <span class="total-value">{{ number_format($total_all_invoices_price, 2) }}</span>
            </div>

            <div class="col-md-3 border-left">
                <span class="total-label">الضريبة المضافة ({{ $avtRate * 100 }}%)</span>
                <span class="total-value" style="color: #ffbcbc;">{{ number_format($total_all_added_value, 2) }}</span>
            </div>

            <div class="col-md-3 border-left">
                <span class="total-label" style="font-weight: bold; font-size: 18px;">الإجمالي الشامل</span>
                <div class="main-total-value">
                    {{ number_format($total_all_invoices_price + $total_all_added_value, 2) }}
                    <span class="currency">ريال</span>
                </div>
            </div>

            @can('System setting')
            <div class="col-md-3">
                <span class="total-label">إجمالي تكلفة النظام</span>
                <span class="total-value" style="color: #419BB2;">{{ number_format($grand_system_cost, 2) }}</span>
            </div>
            @endcan

        </div>
    </div>
</div>
                   
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>


    <script type="text/javascript">
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
