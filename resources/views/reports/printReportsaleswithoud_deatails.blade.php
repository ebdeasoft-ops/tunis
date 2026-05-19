@extends('layouts.master')
@section('css')
<style>
    @media print {
        -webkit-print-color-adjust: exact; 
   .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 200px;
      font-size: 15px !important;

    }

   @page
        {
           
            size: auto; /* auto is the initial value */
            margin: 2mm 2mm 2mm 2mm; /* this affects the margin in the printer settings */
                font-size:28px;

        }
 .text{
  width: 320px;
  overflow: hidden;
    white-space: pre-wrap;
  text-overflow: ellipsis
        }
        .row-sm{
             dir:"rtl"
        }
         .tx-18{
                            font-size:15px!important;

        }
         .tx-16{
                            font-size:12px!important;

        }
            .double{
            border: 3px solid grey;
            border-radius: 5px;
            width:200px;

        }
    tr {
       bgcolor="grey"
        }

        header
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }
        #print_Button {
            display: none;
        }
  th {
    /*background-color: grey;*/
    font-size:22px;
    }
    }

  
     .double {
      border: 3px solid grey;
      border-radius: 5px;
      width: 200px;
      font-size: 15px !important;

    }

    .textend{
        hidden
    }
</style>
@endsection
@section('title')
{{ __('home.print') }}
@stop
@section('page-header')
<!-- breadcrumb -->

<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row row-sm"  >
   <div class="col-md-12 col-xl-12">
      <div class=" main-content-body-invoice" id="print"  dir=rtl>
         <div class="card card-invoice">
                <div class="d-flex justify-content-center">
                            <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                                {{ __('home.print') }}
                                <i class="mdi mdi-printer ml-1"></i>
                        </div>
                        <br>

            <div class="card-body">

               <div class="invoice-header" style="display: flex;justify-content:space-between;width:100%" dir="rtl">
            
                   <div class="billed-from" style="width:33%;text-align: center;" >
                     <br>

                     <span style="font-size:16px">{{Namear}}</span>
                     <br>
                     <p> {{describtionar}}</p>
                     <p>{{STar}}</p>
                     <p>{{Taxar}}</p>

                  </div><!-- billed-from -->
                 
                  <div class="row">
                     <?php
                     $logo = camplogo;
                     ?>
                     <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>

                  </div>
      <div class="billed-from" style="width:33%;text-align: center;">
                     <br>
                     <span style="font-size:19px">{{Nameen}}</span>
                     <br>
                     <p dir=rtl> {{describtionen}} </p>
                     <span dir=rtl>{{STen}} </span>
                     <p dir=rtl> {{Taxen}} </p>

                  </div>
                  

               </div><!-- invoice-header -->
               
    <center>    <span class='double'>المبيعات   Sales </span></center> 


       <div class="table-padding table-responsive ">
                                <table style="border: 2px solid rgba(0,0,0,0)" class="table table-striped table-bordered text-center my-2">
                                    <col style="width:25%">
                                    <col style="width:25%">
                                    <col style="width:25%">
                                    <col style="width:25%">

                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.fromdate') }}:</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $start[0] }}</label>
                                            </th>

                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('report.todate') }}</label>

                                            </th>
                                            <th style="background-color: rgba(236, 240, 250, 1);"> <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1">{{ $end[0] }}</label>
                                            </th>




                                    </thead>
                                </table>
                            </div>
                 <div style="border-radius: 10px" class="card-body mt-5">
    <div class="table-striped">
        @php
            $totalPriceAll = 0;
            $totalAddedValueAll = 0;
            $totalDiscountAll = 0;
            $totalPriceAfterDiscountAll = 0;
            
            // جلب قيمة الضريبة مرة واحدة خارج الحلقة لتوفير الاستعلامات
            $avt = App\Models\Avt::find(1);
            $taxRate = $avt ? $avt->AVT : 0.15; // افتراضي 15% إذا لم يوجد سجل
        @endphp

        <div class="table-responsive hoverable-table">
            <table class="table table-hover table-bordered" id="example1" data-page-length='50' style="text-align: center; width:95%">
                <thead>
                    <tr>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.Invoice_no') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.date') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.clietName') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.total') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.discount') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.totalafterdiscount') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.avt') }}</th>
                        <th style="color: #FF4F1F; font-size:12px" class="border-bottom-0">{{ __('home.totalwithTax') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Invoices as $product)
                        @php
                            // 1. حساب إجمالي المبلغ المدفوع (شامل الضريبة)
                            $currentInvoicePrice = ($product->cashamount + $product->bankamount + $product->Bank_transfer + $product->creaditamount);

                            // 2. حساب الخصم
                            $invoiceDiscount = 0;
                        

                            // إضافة خصومات المنتجات (المرتجعة والمباعة)
                           $discound_return_sales=App\Models\return_sales::where('invoice_id', $product->id)->sum('discountvalue');
                           $discount_sales=App\Models\sales::where('invoice_id', $product->id)->sum('Discount_Value');
                            $invoiceDiscount += $discound_return_sales;
                            $invoiceDiscount +=  $discount_sales;
                            $invoiceDiscount +=$product->discount==0?$product->discountOnInvoice:($product->discount- $discount_sales-$discound_return_sales);

                            // 3. حساب القيم قبل الضريبة
                            // السعر قبل الضريبة = السعر الشامل / (1 + نسبة الضريبة)
                            $priceBeforeTax = $currentInvoicePrice / (1 + $taxRate);
                            $taxAmount = $currentInvoicePrice - $priceBeforeTax;

                            // 4. تحديث الإجماليات النهائية للجدول
                            $totalPriceAll += $currentInvoicePrice;
                            $totalDiscountAll += $invoiceDiscount;
                            $totalAddedValueAll += $taxAmount;
                            $totalPriceAfterDiscountAll += $priceBeforeTax;

                            // تحديد طريقة الدفع
                            $paymentMethod = match($product->Pay) {
                                'Cash' => __('report.cash'),
                                'Shabka' => __('report.shabka'),
                                'Credit' => __('report.credit'),
                                'Bank_transfer' => __('home.Bank_transfer'),
                                default => __('home.Partition of the amount'),
                            };
                        @endphp

                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->created_at }}</td>
                            <td>{{ $product->customer->name ?? 'N/A' }}</td>
                            <td>{{ $paymentMethod }}</td>
                            {{-- المجموع قبل الخصم (صافي + خصم) --}}
                            <td>{{ number_format($priceBeforeTax + $invoiceDiscount, 2) }}</td>
                            {{-- الخصم --}}
                            <td>{{ number_format($invoiceDiscount, 2) }}</td>
                            {{-- الإجمالي بعد الخصم (قبل الضريبة) --}}
                            <td>{{ number_format($priceBeforeTax, 2) }}</td>
                            {{-- قيمة الضريبة --}}
                            <td>{{ number_format($taxAmount, 2) }}</td>
                            {{-- الإجمالي النهائي شامل الضريبة --}}
                            <td>{{ number_format($currentInvoicePrice, 2) }}</td>
                        </tr>
                    @endforeach

                    <!-- سطر الإجماليات النهائية -->
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td colspan="4">{{ __('home.total') }}</td>
                        <td>{{ number_format($totalPriceAfterDiscountAll + $totalDiscountAll, 2) }}</td>
                        <td>{{ number_format($totalDiscountAll, 2) }}</td>
                        <td>{{ number_format($totalPriceAfterDiscountAll, 2) }}</td>
                        <td>{{ number_format($totalAddedValueAll, 2) }}</td>
                        <td>{{ number_format($totalPriceAll, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
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