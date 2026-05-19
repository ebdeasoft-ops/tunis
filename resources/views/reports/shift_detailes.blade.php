
@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
{{__('report.Rosaryـdetails')}}@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{__('report.Rosaryـdetails')}}

</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
                </span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- row -->
<div class="row">

    <div class="col-xl-12">
        <div class="card mg-b-20">


            <div class="card-header pb-0">

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/'. ($page = 'shift_detailes')) }}" method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}

                    <div class="row">

                        <div class="col-lg-3" id="start_at">
                            <label for="exampleFormControlSelect1"> {{__('report.fromdate')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}"
                                    name="start_at" placeholder="YYYY-MM-DD" type="text" required>
                            </div><!-- input-group -->
                        </div>

                        <div class="col-lg-3" id="end_at">
                            <label for="exampleFormControlSelect1"> {{__('report.todate')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" name="end_at"
                                    value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text" required>
                            </div><!-- input-group -->
                            
                        </div>
                        <div class="col" id="type">
    <p class="mg-b-10">   {{__('users.branch')}} </p>
    <select class="form-control select2 " name="branch"
        required>
        <option value="-" selected>{{__('users.allbranchs')}}
        </option>
        @foreach(App\Models\branchs::get() as $branch)
		<option value="{{$branch->id}}" > {{$branch->name}}</option>

      @endforeach
    </select>
	
       </div class='row'>
                        <div class="col" id="type">
    <p class="mg-b-10">   {{__('home.paymentmethod')}} </p>
    <select class="form-control select2 " name="pay"
        required>
        <option value="-" selected>{{__('home.paymentmethod')}}
        </option>
		<option value="Cash" > {{__('report.cash')}}</option>
		<option value="Shabka "> {{__('report.shabka')}} </option>
		<option value="Credit "> {{__('report.credit')}} </option>

    </select>
	
</div class='row'>
                    </div><br>
					<div class="d-flex justify-content-center"> 
                            <button class="btn btn-success ">{{__('home.search')}}</button>
                        </div>

                  
                </form>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                @if(isset($Invoices))
<?php
$userId=0;
$count=0;
?>
<?php
$userId=0;
$startat='';
$endat='';
$totalprice=0;
$totaladdedvalue=0;
?>
@foreach ($Invoices as $invoice)
<?php 
                                    $totaladdedvalue+=$invoice->Added_Value;
                                    $totalprice+=$invoice->Price;
                                    if($count==0){
                                        $userId=$invoice->user_id;
                                        $startat=$invoice->created_at;
                                    }
                                    $endat=$invoice->created_at;
                                    $count++;

                                     ?>
                                     
<br>



            <span class="text-danger">{{__('report.invoiceNo')}}   :  {{$invoice->id}}       </span>  
            <br> 
            <span class="text-danger">{{__('users.branch')}}   :  {{$invoice->branch->name}}       </span>  
            <br>         
           <span class="text-danger" >{{__('home.paymentmethod')}}   :   

    @if ($invoice->Pay == 'Cash')
        <span class="text-success">{{__('report.cash') }}</span>
    @elseif($invoice->Pay == 'Credit')
        <span class="text-danger">{{__('report.credit') }}</span>
    @else
        <span class="text-warning">{{__('report.shabka') }}</span>
    @endif
    <br>         

</span>
   
    <table class="table table-sm">
  <thead>
        <tr>
        <th class="border-bottom-0">#</th>
        <th class="border-bottom-0">{{__('report.date')}}</th>

                                <th class="border-bottom-0"> {{__('home.productNo')}}</th>
                                <th class="border-bottom-0"> {{__('home.product')}}</th>
                                    <th class="border-bottom-0"> {{__('home.quantity')}}</th>

                                    <th class="border-bottom-0">{{__('home.price')}}</th>
                                    <th class="border-bottom-0"> {{__('home.addedValue')}}</th>
                                    <th class="border-bottom-0"> {{__('home.total')}}</th>
        </tr>
    </thead>
    <?php
    $i=0;
?>
    @foreach(App\Models\sales::where('invoice_id',$invoice->id)->get() as $product)
    <?php
    $i++;
    $date= explode(" ",$product->created_at);
?>
    <tbody>
        <tr>
        <td>{{$i}}</td>
        <td>{{$date[0]}}</td>

        <td dir='ltr'>{{$product->productData->Product_Code}}</td>
            <td>{{$product->productData->product_name}}</td>
            <td>{{$product->quantity}}</td>
      

            <td>{{$product->Unit_Price}}</td>
            <td>{{$product->Added_Value}}</td>
            <td>{{($product->quantity*$product->Added_Value)+($product->quantity*$product->Unit_Price)}}</td>
        </tr>
       
    </tbody>
    
@endforeach
</table>

          <span class="text-warning  float-left mt-3 mr-2" id="print_Button" >{{__('home.total')}} : {{($invoice->Added_Value+$invoice->Price)}}</span>
            
          <br>
          <br>
@endforeach
<br>
-----------------------------------------------------  {{__('report.totalprice')}} ---------------------------
<br>

          <span class="text-success">{{__('report.totalpricewithoudtax')}} :  {{ $totalprice}}  <br> <br> {{__('report.totaltax')}} : {{$totaladdedvalue}}  <br><br> </span>
          <span class="text-warning" >{{__('report.totalallprice')}} : {{($totaladdedvalue+ $totalprice)}}</span>
            
          <br>
          ----------------------------------------------------------------------------------------------------



                        <br>

                        <div class="d-flex justify-content-center">

<a class="btn btn-success" href="{{ url('/' . ($page = 'print_shift_detailes').'/'.$pay[1].'/'.$pay[0].'/'.$startat.'/'.$endat) }}"> {{__('home.print')}}</a>
</div>
<br>
                    @endif
                </div>
     
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();

</script>

<script>
        $(document).ready(function() {

            $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
    
        });
    </script>
@endsection












