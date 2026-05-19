
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
    <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 110px; height: 70px;"></a>

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

                            <div class="row row-sm">
                            <div class="col-lg-3" id="start_at">
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ __('home.exportTime') }} : </label>
                                    <?php
                                    $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                                    ?>
                                    <label style="font-size: 14px;color:#419BB2 ;font-weight:bold;" for="exampleFormControlSelect1"> {{ $currentdata }}</label>

                                </div>
                                <br>
                                <div class="col-xl-12">
                                                <table class="table table-bordered table-striped text-center table-responsive table-hover">
                                                <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.clietName')}}</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.phone')}}</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.Location')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.depit_oping')}} </th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit_oping')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit')}} </th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.debit')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.current balance')}} </th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php $i = 0 ?>
                                @foreach (App\Models\financial_accounts::where('orginal_type',1)->get() as $user)
                                <?php $i++ ;
                                $customer=App\Models\customers::find($user->orginal_id);
                                
                                ?>

                                <tr>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $i}}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $user->name}}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $customer->phone??'' }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $customer->address??'' }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->debtor_opening }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->creditor_opening }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->debtor_current }}</td>
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->creditor_current }}</td>
                                    @if($user->debtor_current-$user->creditor_current ==0)
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                    @elseif($user->debtor_current-$user->creditor_current >0)
                                   <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.credit')}} ( {{$user->debtor_current-$user->creditor_current}} ) {{__('home.SAR')}}</td>
                                    @else
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.debit')}} ( {{($user->debtor_current-$user->creditor_current)*-1}} ) {{__('home.SAR')}}</td>
                                     @endif

                                </tr>


                                @endforeach
                            </tbody>
                                                </table>
                                            <br>
                                        
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
