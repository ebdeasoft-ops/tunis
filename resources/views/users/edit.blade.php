@extends('layouts.master')
@section('css')
    <!-- Internal Nice-select css  -->
    <link href="{{ URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />
@section('title')
    {{ __('users.updateuser') }}
@stop


@endsection
@section('page-header')
<div class="main-parent">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between parent-heading">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('users.updateuser') }}
                </h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">

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

            <div class="card">
                <div class="card-body p-5">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a style="background-color: #FF4F1F" class="btn btn-primary btn-sm"
                                href="{{ route('users.index') }}">{{ __('users.back') }}</a>
                        </div>
                    </div><br>

                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
                    <div class="">

                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-6" id="fnWrapper">
                                <label class="parent-label">{{ __('users.username') }}<span class="tx-danger">*</span></label>
                                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label class="parent-label">{{ __('users.email') }} <span class="tx-danger">*</span></label>
                                {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="row mg-b-20">
                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label class="parent-label"> {{ __('users.password') }} <span class="tx-danger">*</span></label>
                            {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
                        </div>

                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label class="parent-label"> {{ __('users.confirmـpassword') }} <span class="tx-danger">*</span></label>
                            {!! Form::password('confirm-password', ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>

                    <div class="row row-sm mg-b-20">
                        <div class="col-lg-6">
                            <label class="form-label parent-label"> {{ __('users.Userـstatus') }}</label>
                            <select name="active" id="active" class="form-control parent-input">
                                <option style="font-size:15px" value="{{ $user->Status }}">{{ $user->Status }}</option>
                                <option style="font-size:15px" value=1> {{ __('users.active') }}</option>
                                <option style="font-size:15px" value=0> {{ __('users.disactive') }}</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label parent-label">{{ __('users.branch') }} </label>
                            <select name="branchs_id" id="branchs_id" class="form-control parent-input">
                                @foreach (App\Models\branchs::get() as $section)
                                    <option style="font-size: 15px" value="{{ $section->id }}"> {{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="border-radius: 10px" class="row mg-b-20">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="parent-label">{{ __('users.User_roles') }} </label>
                                {!! Form::select('roles_name[]', $roles, $userRole, ['class' => 'form-control', 'multiple']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="mg-t-30">
                        <button class="btn btn-main-primary pd-x-20 print-style" type="submit">
                            {{ __('roles.update') }}
                            <svg style="width: 20px" class="svg-icon-buttons" viewBox="0 0 20 20">
                                <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </button>
                    </div>
                    {!! Form::close() !!}
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
</div>
@endsection
@section('js')

<!-- Internal Nice-select js-->
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js') }}"></script>

<!--Internal  Parsley.min js -->
<script src="{{ URL::asset('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<!-- Internal Form-validation js -->
<script src="{{ URL::asset('assets/js/form-validation.js') }}"></script>


<script>


Array.prototype.search = function(elem) {
    for(var i = 0; i < this.length; i++) {
        if(this[i] == elem) return i;
    }
    
    return -1;
};

var Multiselect = function(selector) {
    if(!$(selector)) {
        console.error("ERROR: Element %s does not exist.", selector);
        return;
    }

    this.selector = selector;
    this.selections = [];

    (function(that) {
        that.events();
    })(this);
};

Multiselect.prototype = {
    open: function(that) {
        var target = $(that).parent().attr("data-target");

        // If we are not keeping track of this one's entries, then
        // start doing so.
        if(!this.selections) {
            this.selections = [ ];
        }

        $(this.selector + ".multiselect").toggleClass("active");
    },

    close: function() {
        $(this.selector + ".multiselect").removeClass("active");
    },

    events: function() {
        var that = this;

        $(document).on("click", that.selector + ".multiselect > .title", function(e) {
            if(e.target.className.indexOf("close-icon") < 0) {
                that.open();
            }
        });

        $(document).on("click", that.selector + ".multiselect option", function(e) {
            var selection = $(this).attr("value");
            var target = $(this).parent().parent().attr("data-target");

            var io = that.selections.search(selection);

            if(io < 0) that.selections.push(selection);
            else that.selections.splice(io, 1);

            that.selectionStatus();
            that.setSelectionsString();
        });

        $(document).on("click", that.selector + ".multiselect > .title > .close-icon", function(e) {
            that.clearSelections();
        });

        $(document).click(function(e) {
            if(e.target.className.indexOf("title") < 0) {
                if(e.target.className.indexOf("text") < 0) {
                    if(e.target.className.indexOf("-icon") < 0) {
                        if(e.target.className.indexOf("selected") < 0 ||
                           e.target.localName != "option") {
                            that.close();
                        }
                    }
                }
            }
        });
    },

    selectionStatus: function() {
        var obj = $(this.selector + ".multiselect");

        if(this.selections.length) obj.addClass("selection");
        else obj.removeClass("selection");
    },

    clearSelections: function() {
        this.selections = [];
        this.selectionStatus();
        this.setSelectionsString();
    },

    getSelections: function() {
        return this.selections;
    },

    setSelectionsString: function() {
        var selects = this.getSelectionsString().split(", ");
        $(this.selector + ".multiselect > .title").attr("title", selects);

        var opts = $(this.selector + ".multiselect option");

        if(selects.length > 6) {
            var _selects = this.getSelectionsString().split(", ");
            _selects = _selects.splice(0, 6);
            $(this.selector + ".multiselect > .title > .text")
                .text(_selects + " [...]");
        }
        else {
            $(this.selector + ".multiselect > .title > .text")
                .text(selects);
        }

        for(var i = 0; i < opts.length; i++) {
            $(opts[i]).removeClass("selected");
        }

        for(var j = 0; j < selects.length; j++) {
            var select = selects[j];

            for(var i = 0; i < opts.length; i++) {
                if($(opts[i]).attr("value") == select) {
                    $(opts[i]).addClass("selected");
                    break;
                }
            }
        }
    },

    getSelectionsString: function() {
        if(this.selections.length > 0)
            return this.selections.join(", ");
        else return "Select";
    },

    setSelections: function(arr) {
        if(!arr[0]) {
            error("ERROR: This does not look like an array.");
            return;
        }

        this.selections = arr;
        this.selectionStatus();
        this.setSelectionsString();
    },
};

$(document).ready(function() {
    var multi = new Multiselect("#countries");
});


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
