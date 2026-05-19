@extends('layouts.master')
@section('css')

@section('title')
    {{ __('users.usersList') }}
@stop

<!-- Internal Data table css -->

<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.groups') }}</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            <br>
            {{ session('success') }}
        </div>
    @endif

    <!-- row opened -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">

                        <div class="choose-product">
                         
                        <button style="background-color: #23395D;" class="modal-effect btn btn-sm btn-info p-2 m-1 button-eng" data-effect="effect-scale" data-toggle="modal" href="#creategroup" title="تحديد"><i style=" height: 50;font-weight:400 !important;
                                                 width: 65px;
                                                 font-size:13px" class="las"> {{ __('home.create_group') }}</i>

                        </button>
                                
                   
                        
                            </div>
                </div>
                <br>
                <div class="card-body mt-3">
                    <div class="table-responsive hoverable-table pb-2">
                        <table class="table table-hover table-bordered table-striped text-center mb-5" id="example1" data-page-length='50'
                            style=" text-align: center;">
                            <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">{{ __('home.groub_ar') }}</th>
                                    <th class="wd-20p border-bottom-0">{{ __('home.group_en') }} </th>
                                  
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 0; ?>
                                @foreach (App\Models\products_group::get() as  $user)
                                    <?php $i++; ?>

                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $user->group_ar }}</td>
                                        <td>{{ $user->group_en }}</td>
                                     
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/div-->

    <!-- Modal effects -->
    <div class="modal" id="modaldemo8">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف المستخدم</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('users.destroy', 'test') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>هل انت متاكد من عملية الحذف ؟</p><br>
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <input class="form-control" name="username" id="username" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
            </div>
        </div>

    </div>
    <!-- /row -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
</div>



<div class="modal p-3" id="creategroup">
    <div style="margin: 0 9% !important;" class="modal-dialog modal-dialog-centered modal-special" role="document">
        <div class="modal-content modal-content-demo p-3">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title"> {{ __('home.create_group') }} </h6><button aria-label="Close" class="close close-special" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                {{ csrf_field() }}
                <div class="row mb-2">
                    <div class="col mb-2">
                        <label for="inputName" class="control-label parent-label">
                            {{ __('home.groub_ar') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input" id="groub_ar" name="product_name_ar" title="{{ __('supprocesses.product_name_ar') }}" onkeyup="groub_translateNameToEnglish()"  required>
                    </div>
                    
                    
                     <div class="col mb-2">
                                <label for="inputName" class="control-label parent-label">
                                    {{ __('home.group_en') }}</label>
                                <input autocomplete=off type="text" class="form-control parent-input" id="groub_en" name="product_name_en" title="{{ __('supprocesses.product_name_en') }}" onkeyup="groub_translateNameToArbic()"  required>
                            </div>



                </div>

                <br>
                <div class="d-flex justify-content-center">
                    <button style="background-color: #419BB2" class="btn btn-primary p-1" data-dismiss="modal" onclick="createnewgroupajax()">
                        {{ __('supprocesses.save_data') }}
                        <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg>
                    </button>
                </div>
        </div>

    </div>
</div>

                            <input type="hidden" id="token_search" value="{{ csrf_token() }}">



 <div class="modal fade product-selection" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" id="massagesave" name="massagesave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" dir='rtl' aria-hidden="true">
            <div class="modal-dialog modal-xl" style="background-color: rgba(0, 0, 0, 0)!important;color: rgba(0, 0, 0, 0)!important;" role="document">
                <div class="modal-content">
                  
                    <div class="modal-body" style="justify-content: center;">


 <center><img style="width:250px;height:250px;" class="custom_img" src="{{ asset('assets/admin/uploads/done.png') }}" >
                        
</center>



                          
                        </div>

                     
                    </div>


                </div>
            </div>

        </div>
        
        
        
        

@endsection
@section('js')
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<!-- Internal Modal js-->
<script src="{{ URL::asset('assets/js/modal.js') }}"></script>

<script>
function groub_translateNameToArbic(){
   var wordEnglish = $('#groub_en').val();
    
         jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=en&tl=ar&q=" + wordEnglish,
            type: 'get',
            cache: false,

            success: function(request_result) {
                $('#groub_ar').val(request_result[0][0][0])
            },
            error: function() {

            }
        });
    
    
}

function groub_translateNameToEnglish(){
   var wordarbic = $('#groub_ar').val();
    
         jQuery.ajax({
            url: "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t&sl=ar&tl=en&q=" + wordarbic,
            type: 'get',
            cache: false,

            success: function(request_result) {
                $('#groub_en').val(request_result[0][0][0])
            },
            error: function() {

            }
        });
    
    
}


 function createnewgroupajax() {
        console.log('+++++++++++++++++++++++++++++++++create_products_group ++++++++++++++++++++++++++++++++');
        var url = " {{ URL::to('create_products_group') }}";
   
        var token_search = $("#token_search").val();
        if ($('#groub_ar').val() == '') {
            alert("{{ __('home.groub_ar') }}")
        } else if ($('#groub_en').val() == '') {
            alert("{{ __('home.groub_en') }}")
        } else {
console.log($('#groub_ar').val())
console.log($('#groub_en').val())
console.log(token_search)

            $.ajax({
                url: url,
                type: 'post',
                cache: false,
                data: {
                    _token: token_search,
                    groub_ar: $('#groub_ar').val(),
                    groub_en: $('#groub_en').val(),
                },


                    success: function(data) {
                        console.log(data)
                        $('#creategroup').modal('hide');
                        $('#groub_ar').val('');
                        $('#groub_en').val('');
                
          $('#massagesave').modal().show();
 setTimeout(() => {
         $('#massagesave').modal('hide');

        }, 1000); 
        location.reload();

                    },
                      error: function(response) {
console.log(response)
                }
                
            });







        }


    }


</script>


@endsection
