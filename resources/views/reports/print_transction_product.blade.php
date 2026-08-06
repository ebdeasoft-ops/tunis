@extends('layouts.master')
@section('css')
    <style>
        @media print {
            #print_Button, .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
            }
            .invoice-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }
        }
        
        body {
            font-family: 'Cairo', Tahoma, sans-serif !important;
            color: #333;
            background-color: #f8f9fa;
        }

        .invoice-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .main-title {
            color: #2b6cb0;
            font-weight: 800;
            font-size: 20px;
            position: relative;
            padding-bottom: 8px;
        }

        /* تحسين الجدول ليكون واضحاً وغير صغير */
        .table {
            width: 100% !important;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table thead th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            border-bottom: 2px solid #cbd5e1;
            padding: 12px !important;
        }

        .table td {
            padding: 12px !important;
            vertical-align: middle;
            color: #334155 !important;
            font-size: 14px !important; /* تكبير خط محتوى الجدول ليكون واضحاً */
        }

        /* صندوق الإجماليات الشامل */
        .grand-total-container {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            border-radius: 12px;
            padding: 22px;
            margin-top: 25px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .grand-total-container .total-label {
            font-size: 14px;
            color: #94a3b8;
            display: block;
            margin-bottom: 5px;
        }

        .grand-total-container .total-value {
            font-size: 18px;
            font-weight: 700;
            color: #f8fafc;
        }

        .grand-total-container .main-highlight {
            font-size: 24px;
            color: #38bdf8;
            font-weight: 800;
        }

        .sub-total-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 14px;
        }

        .company-header-info p {
            margin-bottom: 4px;
            color: #475569;
            font-size: 14px;
        }
    </style>
@endsection

@section('title')
    معاينة طباعة المنتجات
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between"></div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice" id="print">
                <div class="card card-invoice shadow-lg" style="border-radius: 15px; border: none;">
                    
                    <!-- زر الطباعة العلوي -->
                    <div class="d-flex justify-content-end p-4 pb-0 no-print">
                        <button class="btn btn-info px-4 py-2 shadow-sm" id="print_Button" onclick="printDiv()" style="border-radius: 8px; font-weight: 600; background-color: #419BB2; border: none;">
                            <i class="mdi mdi-printer ml-1" style="font-size: 18px;"></i> {{ __('home.print') }}
                        </button>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        
                        <!-- رأس الفاتورة والشعار -->
                        <div class="invoice-header d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom" style="width:100%">
                            
                            <div class="billed-from company-header-info" style="width:33%; text-align: right;" dir="ltr">
                                <span style="font-size:16px; font-weight:700; color: #1e293b;">{{Nameen}}</span>
                                <p>{{describtionen}}</p>
                                <p>{{STen}}</p>
                                <p>{{Taxen}}</p>
                            </div>

                            <div class="text-center" style="width:34%;">
                                <?php $logo = camplogo; ?>
                                <a href="https://ebdeasoft.com/">
                                    <img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="max-height: 75px; object-fit: contain;">
                                </a>
                            </div>

                            <div class="billed-from company-header-info" style="width:33%; text-align: left;" dir="rtl">
                                <span style="font-size:16px; font-weight:700; color: #1e293b;">{{Namear}}</span>
                                <p>{{describtionar}}</p>
                                <p>{{STar}}</p>
                                <p>{{Taxar}}</p>
                            </div>
                        </div>

                        <!-- عنوان التقرير -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="main-title">تقرير حركة المنتجات الصادر</h3>
                            <div class="text-left" dir="ltr">
                                <span class="badge badge-light shadow-sm" style="font-size: 13px; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 6px; color: #475569;">
                                    <i class="far fa-clock mr-1 text-info"></i> {{ \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s") }}
                                </span>
                                <span class="text-muted ml-2 font-weight-bold">: {{ __('home.exportTime') }}</span>
                            </div>
                        </div>

                        <?php 
                            $total_all_invoices_price = 0; 
                            $total_all_added_value = 0; 
                            $grand_system_cost = 0; 
                            $avtRate = App\Models\Avt::find(2)->AVT ?? 0.15;
                        ?>

                        <!-- تكرار الفواتير -->
                        @foreach($data['transctions'] as $invoice)
                            <div class="invoice-card">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-6">
                                        <h5 class="text-info mb-2" style="font-weight: 700; font-size: 17px;">
                                            <i class="fas fa-file-invoice ml-1"></i> فاتورة رقم: #{{$invoice->id}}
                                        </h5>
                                        <p class="mb-1 text-muted" style="font-size: 14px;"><strong>التاريخ:</strong> {{$invoice->created_at}}</p>
                                        <p class="mb-0" style="font-size: 14px;"><strong>الحالة:</strong> 
                                            {!! $invoice->reciveInvoiceNumber == 0 
                                                ? '<span class="badge badge-danger px-2 py-1" style="font-size:12px;">بانتظار الاستلام</span>' 
                                                : '<span class="badge badge-success px-2 py-1" style="font-size:12px;">تم الاستلام بنجاح</span>' !!}
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-md-left border-right-md mt-2 mt-md-0" style="font-size: 14px;">
                                        <p class="mb-1"><strong>من فرع:</strong> <span class="text-dark font-weight-bold">{{$invoice->branchfrom->name ?? ''}}</span></p>
                                        <p class="mb-1"><strong>المسؤول:</strong> {{$invoice->userfrom->name ?? ''}}</p>
                                        <p class="mb-0"><strong>إلى فرع:</strong> <span class="text-dark font-weight-bold">{{$invoice->branchto->name ?? ''}}</span></p>
                                    </div>
                                </div>

                                <!-- جدول عناصر الفاتورة (بحجم واضح ومقروء) -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 6%;">#</th>
                                                <th style="width: 20%;">كود المنتج</th>
                                                <th style="width: 34%;">اسم المنتج</th>
                                                <th style="width: 10%;">الكمية</th>
                                                <th style="width: 15%;">سعر الوحدة</th>
                                                <th style="width: 15%;">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $i = 0; 
                                                $invoice_subtotal = 0;
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
                                                    <td class="font-weight-bold text-secondary">{{ $product->product->Product_Code ?? "" }}</td>
                                                    <td class="text-right font-weight-bold text-dark">{{ $product->product->product_name ?? "" }}</td>
                                                    <td><span class="badge badge-light border px-2 py-1 font-weight-bold" style="font-size: 13px;">{{ $product->quantity }}</span></td>
                                                    <td>{{ number_format($product->cost_per_each_withoud_tax, 2) }}</td>
                                                    <td class="font-weight-bold text-dark">{{ number_format($line_total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- إجمالي الفاتورة الفرعي -->
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                    <div class="sub-total-box">
                                        <span class="text-muted">إجمالي الفاتورة الصافي:</span>
                                        <span class="h6 mb-0 text-dark font-weight-bold ml-1">{{ number_format($invoice_subtotal, 2) }} ريال</span>
                                    </div>
                                    
                                    @can('System setting')
                                    <div class="text-muted" style="font-size: 14px;">
                                        <i class="fas fa-database text-info mr-1"></i> تكلفة النظام: 
                                        <strong style="color:#2b6cb0;">{{ number_format($invoice->cost_withod_tax, 2) }} ريال</strong>
                                    </div>
                                    <?php $grand_system_cost += $invoice->cost_withod_tax; ?>
                                    @endcan
                                </div>
                            </div>
                        @endforeach

                        <!-- صندوق الإجماليات الشامل -->
                        <div class="grand-total-container">
                            <div class="row text-center align-items-center">
                                
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <span class="total-label">المبلغ (بدون ضريبة)</span>
                                    <span class="total-value">{{ number_format($total_all_invoices_price, 2) }} <small>ريال</small></span>
                                </div>

                                <div class="col-md-3 mb-3 mb-md-0">
                                    <span class="total-label">الضريبة المضافة ({{ $avtRate * 100 }}%)</span>
                                    <span class="total-value text-warning">{{ number_format($total_all_added_value, 2) }} <small>ريال</small></span>
                                </div>

                                <div class="col-md-3 mb-3 mb-md-0">
                                    <span class="total-label" style="color: #38bdf8;">الإجمالي الشامل النهائي</span>
                                    <div class="main-highlight">
                                        {{ number_format($total_all_invoices_price + $total_all_added_value, 2) }}
                                        <span style="font-size: 14px; font-weight: normal; color: #94a3b8;">ريال</span>
                                    </div>
                                </div>

                                @can('System setting')
                                <div class="col-md-3">
                                    <span class="total-label">إجمالي تكلفة النظام</span>
                                    <span class="total-value" style="color: #38bdf8;">{{ number_format($grand_system_cost, 2) }} <small>ريال</small></span>
                                </div>
                                @endcan

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
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