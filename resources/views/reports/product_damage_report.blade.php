@extends('layouts.master')
@section('css')
<style>
    @media print {
        @page { 
            size: landscape;
        }
        body { 
            writing-mode: tb-rl;
        }   
        .double {
            border: 3px solid grey;
            border-radius: 5px;
            width: 200px;
        }
        #print_Button {
            display: none;
        }
    }

    body {
        font: 13pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.5;
    }
</style>
@endsection

@section('title')
{{ __('home.print') }}
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
        <div class="main-content-body-invoice" id="print">
            <div class="card card-invoice">
                
                <!-- زر الطباعة -->
                <div class="d-flex justify-content-center">
                    <button class="btn btn-danger print-style float-left mt-3 mr-2" id="print_Button" onclick="printDiv()">
                        {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
                    </button>
                </div>
                <br>

                <div class="card-body">
                    <!-- هيدر الفاتورة (البيانات واللوجو) -->
                    <div class="invoice-header" style="display: flex; justify-content: space-between; width: 100%;">
                        
                        <div class="billed-from" style="width: 33%; text-align: center;">
                            <br>
                            <span style="font-size: 19px">{{ Nameen ?? '' }}</span>
                            <br>
                            <p dir="ltr" style="font-size: 12px"> {{ describtionen ?? '' }} </p>
                            <span dir="ltr">{{ STen ?? '' }}</span>
                            <p dir="ltr"> {{ Taxen ?? '' }} </p>
                        </div>
                     
                        <div class="row">
                            <?php $logo = camplogo ?? 'default.png'; ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets/img/brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 100px;"></a>
                        </div>

                        <div class="billed-from" style="width: 33%; text-align: center;">
                            <br>
                            <span style="font-size: 17px">{{ Namear ?? '' }}</span>
                            <br>
                            <p>{{ describtionar ?? '' }}</p>
                            <p>{{ STar ?? '' }}</p>
                            <p>{{ Taxar ?? '' }}</p>
                        </div><!-- billed-from -->
                        
                    </div><!-- invoice-header -->

                    <!-- جدول عرض تالف المنتجات -->
                    @if(isset($products))
                    <div class="card-body">
                        <br>
                        <center><span class="double">{{ __('home.product damage') }}</span></center> 
                        <br>
                       
                        <div class="table-responsive">
                            <table style="border: 2px solid rgba(0,0,0,.3); width: 100%; text-align: center;" class="table mb-0 table-striped invoice-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('home.productNo') }}</th>
                                        <th>{{ __('home.product') }}</th>
                                        <th>{{ __('report.date') }}</th>
                                        <th>{{ __('home.employee') }}</th>
                                        <th>{{ __('home.branch') }}</th>
                                        <th>{{ __('home.quentitydamage') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td dir="ltr">{{ $invoice->productData->Product_Code ?? '' }}</td>
                                        <td>{{ $invoice->productData->product_name ?? '' }}</td>
                                        <td>{{ $invoice->created_at }}</td>
                                        <td>{{ $invoice->user->name ?? '' }}</td>
                                        <td>{{ $invoice->branch->name ?? '' }}</td>
                                        <td>
                                            <span style="font-weight: bold; color: #dc3546;">
                                                {{ $invoice->damage_quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                    </div>
                    @endif

                    <hr class="mg-b-40">

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
<!-- Internal Chart.bundle js -->
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