@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    .table-professional { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 15px; }
    .table-professional thead th { background-color: #f8f9fe; color: #495057; font-weight: 600; padding: 15px; border-bottom: 2px solid #eef2f7; text-align: center; }
    .table-professional tbody td { padding: 12px 15px; border-bottom: 1px solid #eef2f7; vertical-align: middle; text-align: center; color: #6c757d; }
    .table-professional tbody tr:hover { background-color: #fcfcfc; }
    .btn-remove { color: #dc3545; background: #fff5f5; border: 1px solid #ffc9c9; border-radius: 6px; padding: 5px 10px; cursor: pointer; }
    .btn-remove:hover { background: #dc3545; color: #fff; }
</style>
@endsection

@section('title') {{__('home.send_product_from_other_branch_other')}} @stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0">{{__('home.send_product_from_other_branch_other')}}</h4>
    </div>
</div>
@endsection

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
</div>
@endif

@if (session()->has('productupdatedlocation'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('productupdatedlocation') }}</strong>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <form id="movementForm" action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale().'/create_sendProduct') }}" method="POST">
                    {{ csrf_field() }}
                    @if(isset($draftMovement)) <input type="hidden" name="draft_id" value="{{ $draftMovement->id }}"> @endif
                    <input type="hidden" name="status" id="movement_status" value="completed">

                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <label>{{ __('home.choosebranch_reciver') }}</label>
                            <select name="branch" id="branchs_id" onchange="getEmployeesByBranch()" class="form-control">
                                <option value="">{{ __('home.Select_Receiving_Branch') }}</option>
                                @foreach (App\Models\branchs::get() as $section)
                                    @if($section->id != $branchId)
                                        <option value="{{ $section->id }}" {{ isset($draftMovement) && $draftMovement->branch_to == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label class="control-label">{{ __('home.chooseemployeereciver') }}</label>
                            <select name="employeereciver" id="employeereciver" class="form-control">
                                <option value="">{{ __('home.choose_employee_placeholder') ?? 'اختر الموظف' }}</option>
                                @if(isset($draftMovement))
                                    @php $savedUser = \App\Models\User::find($draftMovement->user_to); @endphp
                                    @if($savedUser) <option value="{{ $savedUser->id }}" selected>{{ $savedUser->name }}</option> @endif
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row my-4 justify-content-center">
                        <a class="btn btn-warning" data-toggle="modal" href="#SearchProduct"><i class="las la-search"></i> {{ __('home.chooose product') }} </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table-professional">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('home.product') }}</th>
                                    <th>{{ __('home.quantity') }}</th>
                                    <th>{{ __('home.thecostProduct') }}</th>
                                    <th>{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody id="products_body">
                                @if(isset($draftItems))
                                    @foreach($draftItems as $item)
                                    <tr>
                                        <td>#</td>
                                        <td>{{ $item->product->product_name ?? 'منتج' }}<input type="hidden" name="products[]" value="{{ $item->product_id }}"></td>
                                        <td><input type="number" min="0.01" step="any" name="quantities[]" class="form-control" value="{{ $item->quantity }}" style="width:100px; margin:auto;"></td>
                                        <td>{{ $item->cost_per_each_withoud_tax }}<input type="hidden" name="prices[]" value="{{ $item->cost_per_each_withoud_tax }}"></td>
                                        <td><button type="button" class="btn-remove remove-row"><i class="las la-trash"></i></button></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- قسم الأزرار المطور --}}
                    <div class="d-flex justify-content-center align-items-center mt-4" style="gap: 15px;">
                        @php
                            $isSaved = session()->has('saved_movement_id') || (isset($data) && !empty($data));
                            $invoiceId = session()->get('saved_movement_id') ?? ($data ?? null);
                        @endphp

                        @if(!$isSaved)
                            <button type="button" id="draftBtn" class="btn btn-secondary px-4 shadow-sm" onclick="submitMovementForm('draft')">
                                <i class="las la-file-alt"></i> {{ __('home.Save_As_Draft') }}
                            </button>
                            <button type="button" id="submitBtn" class="btn btn-primary px-5 shadow-sm" style="background-color: #419BB2; border-color: #419BB2;" onclick="submitMovementForm('completed')">
                                <i class="las la-save"></i> {{ __('home.save_data') }}
                            </button>
                        @else
                            <form action="{{ url(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() .'/print_Transfer_items') }}" method="POST" target="_blank">
                                {{ csrf_field() }}
                                <input type="number" name="sprint_invoice_number" value="{{ $invoiceId }}" hidden>
                                <button type="submit" class="btn btn-success px-5 shadow-sm" style="background-color: #28a745; border-color: #28a745;">
                                    <i class="las la-print"></i> {{ __('home.print') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="SearchProduct" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" class="form-control mb-3" placeholder="{{ __('home.searchaboutproduct') }}" id="searchaboutproduct" onkeyup="searchaboutproductfunction()">
                <div id="ajax_responce_serarchDiv"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function getEmployeesByBranch() {
        let branchId = $('#branchs_id').val();
        let employeeSelect = $('#employeereciver');
        if (branchId) {
            $.ajax({
                url: "{{ URL::to('get-branch-employees') }}/" + branchId,
                type: "GET",
                success: function(data) {
                    employeeSelect.empty();
                    $.each(data, function(key, value) { employeeSelect.append('<option value="'+value.id+'">'+value.name+'</option>'); });
                }
            });
        }
    }

    function submitMovementForm(status) {
        if ($('#products_body tr').length === 0) { alert("{{ __('home.Please_Select_At_Least_One_Product') }}"); return; }
        $('#movement_status').val(status);
        $('#submitBtn, #draftBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> جارٍ الحفظ...');
        $('#movementForm').submit();
    }

    function chooseProduct(id, name, sale_price) {
        let row = `<tr><td>#</td><td>${name}<input type="hidden" name="products[]" value="${id}"></td><td><input type="number" name="quantities[]" class="form-control" value="1" style="width:100px; margin:auto;"></td><td>${sale_price}<input type="hidden" name="prices[]" value="${sale_price}"></td><td><button type="button" class="btn-remove remove-row"><i class="las la-trash"></i></button></td></tr>`;
        $('#products_body').append(row);
        $('#SearchProduct').modal('hide');
    }

    $(document).on('click', '.remove-row', function() { $(this).closest('tr').remove(); });
</script>
@endsection