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
{{ __('home.Financial_accounts') }}@stop
@endsection
@section('page-header')
<div class="card">
   <div class="card-header">
      <h3 class="card-title card_title_center"> تعديل حساب مالي </h3>
   </div>
   <!-- /.card-header -->
   <div class="card-body">
      <form action="" method="post" >
         <div class="row">
            @csrf
            <div class="col-md-6">
               <div class="form-group">
                  <label>اسم  الحساب المالي</label>
                  <input name="name" id="name" class="form-control" value="{{ old('name',$data['name']) }}"    >
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                  <label>  نوع الحساب</label>
                  <select name="account_type" id="account_type" class="form-control ">
                     <option value="">اختر النوع</option>
                     @foreach (App\Models\acounts_type::get()  as $info )
                     <option > {{ $info->name_ar }} </option>
                     @endforeach
                  </select>
                  @error('account_type')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label>   هل الحساب أب</label>
                  <select name="is_parent" id="is_parent" class="form-control">
                     <option value="">اختر الحالة</option>
                     <option   {{  old('account_types',$data['is_parent'])==1 ? 'selected' : ''}} value="1"> نعم</option>
                     <option {{  old('account_types',$data['is_parent'])==0  ? 'selected' : ''}} value="0"> لا</option>
                  </select>
                  @error('is_parent')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-6" id="parentDiv"   @if(old('is_parent',$data['is_parent'])==1 ) style="display: none;" @endif   > 
            <div class="form-group">
               <label>   الحسابات الأب</label>
               <select name="parent_account_number" id="parent_account_number" class="form-control ">
                  <option value="">اختر الحساب الاب</option>
                  @foreach ( App\Models\financial_accounts::get()  as $info )
                  <option    value="{{$info->id}}"> {{App::getLocale()=='ar'?$info->name:$info->name_en}}</option>
                  @endforeach
               </select>
               @error('parent_account_number')
               <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label>   ملاحظات</label>
               <input name="notes" id="notes" class="form-control" value="{{ old('notes',$data['notes']) }}"    >
               @error('notes')
               <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label>  حالة التفعيل</label>
               <select name="active" id="active" class="form-control">
                  <option value="">اختر الحالة</option>
                  <option   value="1"> نعم</option>
                  <option  value="0"> لا</option>
               </select>
               @error('active')
               <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
         </div>
         <div class="col-md-12">
            <div class="form-group text-center">
               <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> تعديل</button>
               <a href="" class="btn btn-sm btn-danger">الغاء</a>    
            </div>
         </div>
   </div>
   </form>  
</div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/admin/js/accounts.js') }}"></script>
@endsection