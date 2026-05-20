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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


@section('title')
{{ __('home.add_new_acount_finance') }}
@stop
@endsection
@section('page-header')
<div class="main-parent">
   <!-- breadcrumb -->

   <div class="breadcrumb-header justify-content-between parent-heading">
      <div class="my-auto">
         <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">&nbsp;&nbsp;{{ __('home.add_new_acount_finance') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">
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
   @if (session()->has('error_mastik'))
            <div class="alert alert-danger">
                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{ session()->get('error_mastik') }}</strong>
               
            </div>
        @endif
   @if (session()->has('create_acount'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <br>

                <strong>{{ session()->get('create_acount') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
   <div class="card">
      <div class="card-header">
      </div>
      <!-- /.card-header -->
      <div class="card-body">
      <form
                            action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() . '/' . ($page = 'add_new_acount_finance')) }}"
                            method="post" enctype="multipart/form-data" autocomplete="off">
                            {{ csrf_field() }}    
                            
                            <div class="row">
               @csrf
               <div class="col-md-6">
                  <div class="form-group">
                     <label>  {{__('home.acount_name')}}</label>
                     <input name="name" id="name" class="form-control" required>
                     @error('name')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>  {{__('home.tybe')}} </label>
                     <select style="width:100%" class="form-control select2" name="account_type" id="account_type" class="form-control ">
                        @foreach (App\Models\acounts_type::get() as $info )
                        <option value="{{$info->id}}"> {{ $info->name_ar }} </option>
                        @endforeach
                     </select>
                     @error('account_type')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label> {{__('home.Master')}} </label>
                     <select name="is_parent" id="is_parent" class="form-control">
                        <option value="1"> {{__('home.yes')	}}</option>
                        <option value="0"> {{__('home.no')}}</option>
                     </select>
                     @error('is_parent')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
               <div class="col-md-6" id="parentDiv"  style="display: none;" >
                  <div class="form-group">
                     <label>  {{__('home.Master_account')}}</label>
                     <select style="width:100%" class="form-control select2" name="parent_account_number" id="parent_account_number" class="form-control select2">
                     <option value=0> -</option>

                 
                     </select>
                     @error('parent_account_number')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>    {{__('home.account_status_first_time')}} </label>
                     <select style="width:100%" class="form-control select2" name="start_balance_status" id="start_balance_status" class="form-control">
                        <option   value="3"> {{__('home.Balanced')}}</option>
                        <option   value="1"> {{__('home.debit')}} </option>
                        <option   value="2"> {{__('home.credit')}}</option>
                     </select>
                     @error('start_balance_status')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>  {{__('home.oping')}}   </label>
                     <input name="start_balance" id="start_balance" class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value=0>
                     @error('start_balance')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
               <div id="DATA_CLIENT_Div" class=" row" style="width: 100%;">
                  <div class="col-md-4 ">
                     <label for="inputName" class="control-label parent-label"> {{ __('home.tax_number') }}</label>
                     <input type="number" class="form-control parent-input" id="TaxـNumber" name="TaxـNumber" onkeyup="TaxـNumberConvert()" title="{{ __('supprocesses.TaxـNumber') }}">
                  </div>
                  <div class="col-md-4 ">
                     <label for="inputName" class="control-label parent-label">
                        {{ __('home.addressClient') }}</label>
                     <input type="text" class="form-control parent-input" id="address" name="address" title="{{ __('supprocesses.product_notes') }}">
                  </div>
                  <div class="col-md-4 ">
                     <label for="inputName" class="control-label parent-label"> {{ __('supprocesses.phone') }}</label>
                     <input type="text" class="form-control parent-input" id="phone" name="phone" title="{{ __('supprocesses.phone') }}">
                  </div>



               </div>
               <div class="col-lg-6 mb-2" id="AVT_Div" style="display: none;">
                  <label for="inputName" class="control-label parent-label">{{ __('home.expensesHaveavt') }}</label>
                  <select name="AVT"  id="AVT" class="form-control parent-input">
                     <!--placeholder-->
                     <option value=1 selected> {{__('home.haveAVT')}}</option>
                     <option value=0> {{ __('home.nothaveAVT')}}</option>
                  </select>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label> ملاحظات</label>
                     <input name="notes" id="notes" class="form-control" value="{{ old('notes') }}">
                     @error('notes')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="form-group">
                     <label>  {{__('home.status_active')}}</label>
                     <select name="active" id="active" class="form-control">
                        <option   value="1"> {{__('users.active')}}</option>
                        <option  value="0"> {{__('users.notactive')}}</option>
                     </select>
                     @error('active')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group text-center">
                     <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{__('home.Add')}}</button>
                     <a href="{{ url('/' . ($page = 'financial_accounts')) }}" class="btn btn-sm btn-danger">{{__('home.cancel')}}</a>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
@section('js')

<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

{{-- Select2 --}}
<script>
    
    $('#parent_account_number').select2({
        placeholder: 'ابحث عن المنتج',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: "{{ route('searchfinancial_accounts.search') }}",
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term,
                type: $("#account_type").val() };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                    text: item.name + ' :-' + item.account_number
                    }))
                };
            }
        }
    });
    
   $(document).ready(function() {
      $("#DATA_CLIENT_Div").hide();

      $(document).on('change', '#is_parent', function(e) {
         if ($(this).val() == 0) {
            $("#parentDiv").show();
         } else {
            $("#parentDiv").hide();
         }
      });

 $(document).on('change', '#account_type', function(e) {
         if ($(this).val() == 4) {
            $("#AVT_Div").show();
         }
         
 });
      $(document).on('change', '#parent_account_number', function(e) {
         if ($(this).val() == 8) {
            $("#AVT_Div").show();
         } else {
            $("#AVT_Div").hide();
            $("#DATA_CLIENT_Div").hide();
         }

         if ($(this).val() == 1 || $(this).val() == 2) {
            $("#DATA_CLIENT_Div").show();

         } else {
            // $("#AVT_Div").hide();
            $("#DATA_CLIENT_Div").hide();
         }
      });



      $(document).on('change', '#start_balance_status', function(e) {
         if ($(this).val() == "") {
            $("#start_balance").val("");
         } else {
            if ($(this).val() == 3) {
               $("#start_balance").val(0);
            }
         }
      });
      $(document).on('input', '#start_balance', function(e) {
         var start_balance_status = $("#start_balance_status").val();
         if (start_balance_status == "") {
            alert("من فضلك اختر حالة الحساب اولا");
            $(this).val("");
            return false;
         }
         if ($(this).val() == 0 && start_balance_status != 3) {
            alert("يجب ادخال مبلغ اكبر من الصفر");
            $(this).val("");
            return false;
         }
      });
   })

   $(function() {
var timeout = 4000; // in miliseconds (3*1000)
$('.alert').delay(timeout).fadeOut(500);
});
</script>

@endsection