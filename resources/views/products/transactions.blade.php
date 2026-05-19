
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
{{__('home.Showـtheـavailableـquantityـtoـtheـcustomer')}}@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{__('home.Showـtheـavailableـquantityـtoـtheـcustomer')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/'. ($page = 'printavaliableproduct')) }}" method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}


    

                    <div class="row">

                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                            <p class="mg-b-10"> {{__('home.searchbyclientname')}}</p>
                            <select class="form-control select2" name="clientnamesearch"
                                required>
                                <option value="{{ $type ?? 'حدد نوع الفواتير' }}" selected>
                                    {{ $type ?? __('home.enterclienname') }}
                                </option>

                                @foreach ($data['allcustomers'] as $section)
                                        <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                            </select>
                        </div><!-- col-4 -->
						<div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                            <p class="mg-b-10">    {{__('home.searchbyclientNo')}} </p>
                            <select class="form-control select2 " name="clientnamesearch"
                                required>
                                <option value="{{ $type ?? 'ادخل رقم العميل' }}" selected>
                                    {{ $type ?? __('home.enterclientnumber')  }}
                                </option>

                                @foreach ($data['allcustomers'] as $section)
                                        <option value="{{ $section->id }}"> {{ $section->id }}</option>
                                    @endforeach

                            </select>
                        </div><!-- col-4 -->



               
						<div class="col">
                                <label for="inputName" class="control-label"> {{__('home.clientname')}} </label>
                                <input type="text" class="form-control" id="clientName" name="clientName"
                                  >
                            </div>
							
                        
                            </div>
							<div class="row">
						<div class="col">
                                <label for="inputName" class="control-label"> {{__('home.phone')}} </label>
                                <input type="text" class="form-control " id="phonenumber" name="phonenumber"
                                    title="يرجي ادخال رقم الجوال "
                                     required>
                            </div>
                            <div class="col">
                                <label for="inputName" class="control-label"> {{__('home.addressClient')}} </label>
                                <input type="text" class="form-control" id="address" name="address"
                                  >
                            </div>
                            <div class="col">
                                <label for="inputName" class="control-label"> {{__('home.notesClient')}}  </label>
                                <input type="text" class="form-control" id="notes"
                                    name="notes" title="يرجي ادخال ملاحظات  "
                                    >
                            </div>


                    </div>


					<div class="row">



    
					</div>
                        </div> 


                        {{-- 3 --}}

                      
					<br>

                 

                        @if (isset($data['allproduct']))
                        <?php $i = 0; ?>
                <div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<i class="mdi mdi-dots-horizontal text-gray"></i>
                                    <h4 class="card-title mg-b-0">{{__('home.searchaboutproduct')}}     </h4>

								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap" name='prodyctsavaliable'>
										<thead>
											<tr>
                                            <th class="border-bottom-0"># </th>
                                            <th class="border-bottom-0">{{__('home.productNo')}} </th>
                                            <th class="border-bottom-0">{{__('home.product')}}</th>

                                            <th class="border-bottom-0">{{__('home.quantity')}}</th>
                                            <th class="border-bottom-0">{{__('home.price')}}</th>
                                   
                                 

											</tr>
										</thead>
										<tbody>
                                        <?php $i=0?>

                                        @foreach ($data['allproduct'] as $product)
                                        <?php $i++ ?>

                                    <tr id="<?php echo $product['id'];?>">
                                    <td data-target="id">{{ $i}}</td>
                                           <td data-target="id">{{ $product->Product_Code }}</td>                                         <td data-target="product_name">{{ $product->product_name }}</td>
                                        <td data-target="numberofpice">{{ $product->numberofpice }}</td>
                                        <td data-target="numberofpice">{{ $product->sale_price }}</td>

                                    </tr>
                                @endforeach
										</tbody>
									</table>

								</div>
							</div>
						</div>
					</div>
                    <div class="d-flex justify-content-center">

                            <button type="submit" class="btn btn-success"> {{__('home.printavaliablequantity')}} </button>
                        </div>
						<br/>
                </form>

            </div>
          
              
             
<!-- 
               <div class="row">
               <div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mg-b-0">ابحث عن المنتج  </h4>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap">
										<thead>
											<tr>
                                    <th class="border-bottom-0">كود المنتج</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">الكمية</th>
                                    <th class="border-bottom-0">السعر</th>

											</tr>
										</thead>
										<tbody>
                                        @foreach ($data['allproduct'] as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->numberofpice }}</td>
                                        <td>{{ $product->sale_price }}</td>

                                    </tr>
                                @endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>





                  
                            @endif

                        </table>

                </div>
               </div>
         
       
    </div> -->
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




 $('select[name="clientnamesearch"]').on('change', function() {
                console.log('AJAX load   work 0000');

                var selectclientid = $(this).val();
                if (selectclientid) {
                    console.log('AJAX load   work');

                    $.ajax({
                        url: "{{ URL::to('getcustomer') }}/" + selectclientid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                    console.log(data['name']); 
                    $('#clientName').val(data['name']);
                    $('#address').val(data['address']);
                    $('#phonenumber').val(data['phone']);
                    $('#notes').val(data['notes']);
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });

        $('select[name="searchproductNo"]').on('change', function() {
                console.log('AJAX load   work 0000');

                var selectclientid = $(this).val();
                if (selectclientid) {
                    console.log('AJAX load   work');

                    $.ajax({
                        url: "{{ URL::to('getproduct') }}/" + selectclientid,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log("success");
                    console.log(data['name']); 
                    $('#quentity').val(data['numberofpice']);
                
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
    });
        

        
    </script>




<script>
    $(document).ready(function() {

        $('#invoice_number').hide();

        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {
                $('#invoice_number').hide();
                $('#type').show();
                $('#start_at').show();
                $('#end_at').show();
            } else {
                $('#invoice_number').show();
                $('#type').hide();
                $('#start_at').hide();
                $('#end_at').hide();
            }
        });
    });

</script>


@endsection
