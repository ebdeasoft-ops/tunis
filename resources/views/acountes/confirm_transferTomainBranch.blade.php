@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ __('home.confirmtransferMainBranch') }}
@endsection

@section('page-header')
<div class="main-parent">
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.confirmtransferMainBranch') }}</h4>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('content')
    
    @if (session()->has('transferupdated'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('transferupdated') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطأ</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20" style="border-radius: 10px;">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap table-bordered table-striped text-center" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __('home.date') }}</th>
                                    <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }} {{ __('report.cash') }}</th>
                                    <th class="border-bottom-0">{{ __('home.total') }}</th>
                                    <th class="border-bottom-0">{{ __('home.branch') }}</th>
                                    <th class="border-bottom-0">{{ __('home.stautes') }}</th>
                                    <th class="border-bottom-0">{{ __('home.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\transferMoney_to_mainbranch::where('status',0)->where('to_user_id', auth()->user()->id)->get() as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at }}</td>
                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                        <td>
                                            {{ number_format(($transaction->bank_transfer ?? 0) + ($transaction->Pay_Method_Name ?? 0) + $transaction->amount, 2) }}
                                        </td>
                                        <td>{{ $transaction->branch->name ?? '' }}</td>
                                        <td>
                                            <span class="badge badge-warning-log px-2 py-1">{{ __('home.Notacceptedyet') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a style="background-color: #419BB2; font-size:13px;" class="btn btn-success btn-sm mx-1" href="{{ url('/confirmTransfarToMainBranch/' . $transaction->id) }}">
                                                    {{ __('home.confirm') }}
                                                    <svg style="width: 14px !important" class="svg-icon-buttons ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                                    </svg>
                                                </a>
                                                <a style="background-color: red; font-size:13px;" class="btn btn-danger btn-sm mx-1" href="{{ url('/rejectTransfarToMainBranch/' . $transaction->id) }}">
                                                    {{ __('home.reject') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // تهيئة الـ DataTable الموحد
        if ($('#example').length) {
            $('#example').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "بحث..."
                }
            });
        }

        // تلاشي التنبيهات تلقائياً بعد 4 ثوانٍ
        setTimeout(function() {
            $('.alert').fadeOut(500);
        }, 4000);

        // مراقبة حدث التغيير لحقول جلب بيانات المورد المشتركة
        $('select[name="clientNosearch"], select[name="clientnamesearch"]').on('change', function() {
            var selectclientid = $(this).val();
            if (selectclientid) {
                $.ajax({
                    url: "{{ URL::to('getsupllier') }}/" + selectclientid,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#debtamount').val(data['In_debt']);
                        $('#clientName').val(data['name']);
                        $('#address').val(data['location']);
                        $('#phonenumber').val(data['phone']);
                        $('#notes').val(data['comp_name']);
                    },
                    error: function() {
                        console.log('فشل في جلب بيانات المورد عبر الـ AJAX');
                    }
                });
            }
        });
    });

    // دالة تحويل الأرقام العربية إلى إنجليزية الموحدة خارج النطاق لضمان جاهزيتها عالمياً
    function moneyconvertToNumber() {
        var input = document.getElementById("cashreceived");
        if(input) {
            input.value = toEnglishNumber(input.value);
        }
    }

    function toEnglishNumber(strNum) {
        var ar = '٠١٢٣٤٥٦٧٨٩'.split('');
        var en = '0123456789'.split('');
        return strNum.replace(/[٠١٢٣٤٥٦٧٨٩]/g, x => en[ar.indexOf(x)]);
    }
</script>
@endsection