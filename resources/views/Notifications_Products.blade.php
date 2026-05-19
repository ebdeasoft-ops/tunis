
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
{{__('home.alarmproductQuantity')}}@stop
@endsection
@section('page-header')
<!-- breadcrumb -->

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
        <div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">&nbsp;&nbsp;{{__('home.alarmproductQuantity')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
    </span>
        </div>
    </div>
</div>
<br>
<br>

            <div class="card-header pb-0">

                <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/'. ($page = 'printavaliableproduct')) }}" method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}


    

               


                        {{-- 3 --}}

                      
                        <br>
                        <br>

                 

                        <?php $i = 0; ?>
                <div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<i class="mdi mdi-dots-horizontal text-gray"></i>
                                    <h4 class="card-title mg-b-0">{{__('home.searchaboutproduct')}}     </h4>

								</div>
							</div>
							<div class="card-body" >
                            <table class="table table-sm">
    <div class="table-responsive hoverable-table">
                    <table class="table table-hover" id="example1" data-page-length='50' style=" text-align: center;">
   	<thead>
											<tr>
                                            <th class="border-bottom-0"># </th>
                                            <th class="border-bottom-0">{{__('home.productNo')}} </th>
                                            <th class="border-bottom-0">{{__('home.product')}}</th>

                                            <th class="border-bottom-0">{{__('home.quantity')}}</th>
                                   
                                 

											</tr>
										</thead>
										<tbody>
                                        <?php $i=0?>
                                        <?php
                                $products=App\Models\products::where('branchs_id', auth()->user()->branchs_id)->get();
                                $products_low_quantity=[];
                                foreach( $products as  $product){
                                    if($product->numberofpice<=$product->minmum_quantity_stock_alart){
                                        $products_low_quantity[]= $product;

                                    }

                                }
                                ?>
                                        @foreach ( $products_low_quantity as $product)
                                        <?php $i++ ?>

                                    <tr id="<?php echo $product['id'];?>">
                                    <td data-target="id">{{ $i}}</td>
                                           <td dir="ltr" data-target="id">{{ $product->Product_Code }}</td>                                        
                                           <td data-target="product_name">{{ $product->product_name }}</td>
                                        <td data-target="numberofpice">{{ $product->numberofpice }}</td>

                                    </tr>
                                @endforeach
										</tbody>
									</table>

								</div>
							</div>
						</div>
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
							
								</div>
							</div>
						</div>
					</div>





                  

                    

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

// $(document).on('click', 'a[data-role=update]',function() {
// // var id=$(this).data('id');
// // var productname=$('#',id).children('td[data-darget=product_name]').text();
// // var numberofpice=$('#',id).children('td[data-darget=numberofpice]').text();
// // var print=$('#',id).children('td[data-darget=print]').text();
// var clientName=$('#clientName').val();
// console.log('clientName');
// console.log(clientName);
// var address=$('#address').val();
// console.log('address');
// console.log(address);
// var phone=$('#phonenumber').val();
// console.log('phone');
// console.log(phone);
// var notes=$('#notes').val();
// console.log('notes');
// console.log(notes);


// data.forEach(function (item) {
//     console.log(item);

// });
// console.log("{{ URL::to('printavaliableproduct') }}");
// $.ajaxSetup({
//       headers: {
//         'X-CSSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//       }
//     });
// alert("{{ csrf_token()}}");
// $.ajax({
//         type: 'POST',
//         url:"{{ URL::to('printavaliableproduct') }}",
//         dataType: "JSON",
//         data: {
//             "_token":"{{ csrf_token()}}",
//             'products':data, 'clentdata':clientName},
//         complete: function(responseText){
//             console.log('data');
//             alert(data)

//         }
//         ,error:function(responseText){
//             console.log('ERRROR');
//             alert('error');

//             console.log(responseText);

//         }

//      });
//      console.log("{{ URL::to('printproduct') }}");



//  });




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
