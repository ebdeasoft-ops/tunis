@extends('layouts.master')
@section('css')
    <style>
        @media print {
            @page { 
                size: landscape;
            }
            #print_Button {
                display: none !important;
            }
            body {
                background: #fff !important;
                border: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }

        body {
            font-family: 'Cairo', 'Times New Roman', Times, serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .invoice-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 20px;
        }

        .company-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .table-custom th {
            background-color: #f4f6f8 !important;
            color: #495057;
            font-weight: 600;
            text-align: center;
        }
        
        .table-custom td {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('title')
    {{ __('home.print') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto text-muted">{{ __('home.print') }}</h4>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row row-sm">
    <div class="col-md-12 col-xl-12">
        <div class="main-content-body-invoice" id="print">
            <div class="card invoice-card">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-end mb-4">
                    <button class="btn btn-danger px-4 py-2 shadow-sm" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>

                <!-- ترويسة الشركة (يمين ويسار الشعار) -->
                <div class="company-header d-flex justify-content-between align-items-center w-100 flex-wrap">
                    <div class="billed-from text-left" style="width:33%;">
                        <span style="font-size:20px; font-weight:bold; color: #2c3e50;">{{ Nameen ?? '' }}</span>
                        <p class="text-muted mb-1" dir="ltr" style="font-size:12px;">{{ describtionen ?? '' }}</p>
                        <span class="d-block text-muted" dir="ltr" style="font-size:12px;">{{ STen ?? '' }}</span>
                        <p class="text-muted mb-0" dir="ltr" style="font-size:12px;">{{ Taxen ?? '' }}</p>
                    </div>

                    <div class="text-center my-2" style="width:33%;">
                        @php $logo = camplogo ?? 'default.png'; @endphp
                        <a href="https://ebdeasoft.com/">
                            <img src="{{ asset('assets/img/brand/' . $logo) }}" class="logo-1" alt="logo" style="max-height: 80px; object-fit: contain;">
                        </a>
                    </div>

                    <div class="billed-from text-right" style="width:33%;">
                        <span style="font-size:20px; font-weight:bold; color: #2c3e50;">{{ Namear ?? '' }}</span>
                        <p class="text-muted mb-1" style="font-size:13px;">{{ describtionar ?? '' }}</p>
                        <span class="d-block text-muted" style="font-size:12px;">{{ STar ?? '' }}</span>
                        <p class="text-muted mb-0" style="font-size:12px;">{{ Taxar ?? '' }}</p>
                    </div>
                </div>

                <!-- عنوان التقرير الرئيسي في المنتصف بالعربية والإنجليزية (شكل فاخر) -->
                <div class="text-center my-4">
                    <h2 style="font-weight: bold; color: #2c3e50; font-family: 'Cairo', sans-serif; margin-bottom: 5px;">
                        {{ __('home.purchase_product_by_date') }}
                    </h2>
                    <h4 style="font-weight: 600; color: #7f8c8d; font-family: 'Times New Roman', Times, serif; font-size: 18px;">
                        Purchased Products By Date Report
                    </h4>
                    <hr style="width: 180px; border-top: 2px solid #419BB2; margin: 15px auto;">
                </div>

                @if(isset($products))
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-bordered align-middle" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-bottom-0" style="width: 8%;">#</th>
                                        <th class="border-bottom-0" style="width: 25%;">كود المنتج</th>
                                        <th class="border-bottom-0" style="width: 35%;">اسم المنتج</th>
                                        <th class="border-bottom-0" style="width: 16%;">الكمية المباعة</th>
                                        <th class="border-bottom-0" style="width: 16%;">التكلفة / الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="font-weight-bold text-dark">{{ $loop->iteration }}</td>
                                            <td dir="ltr" class="font-weight-semibold text-muted">{{ $product->productData->Product_Code ?? '---' }}</td>
                                            <td class="text-right font-weight-bold" style="padding-right: 20px;">{{ $product->productData->product_name ?? '---' }}</td>
                                            <td class="font-weight-bold text-info">{{ $product->total_quantity }}</td>
                                            <td class="font-weight-bold text-success">{{ number_format($product->total_sales_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <hr class="mg-b-40">

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