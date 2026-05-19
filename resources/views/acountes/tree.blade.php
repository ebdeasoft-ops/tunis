@extends('layouts.master')

@section('title')
{{ __('home.tree') }}
@endsection

@section('css')
<style>
    .tree-root,
    .tree-root ul {
        list-style: none;
        padding-right: 20px;
    }

    .tree-children {
        display: none;
        margin-top: 5px;
    }

    .tree-toggle {
        cursor: pointer;
        font-weight: bold;
        position: relative;
        padding-right: 18px;
        display: inline-block;
        margin: 4px 0;
    }

    .tree-toggle::before {
        content: "▶";
        position: absolute;
        right: 0;
        font-size: 12px;
        transition: transform 0.2s ease;
    }

    .tree-toggle.open::before {
        transform: rotate(90deg);
    }

    .level-0 { font-size: 20px; color: black; }
    .level-1 { color: green; font-size: 18px; }
    .level-2 { color: blue; font-size: 16px; }
    .level-3 { color: red; font-size: 15px; }
    .level-4 { color: gray; font-size: 14px; }
    /* حاوية الحساب والسويتش */
.d-flex {
    display: flex !important;
    align-items: center; /* توسيط عمودي */
    gap: 15px; /* مسافة ثابتة بين الاسم والسويتش */
}

/* إلغاء أي هوامش افتراضية للـ label قد تسبب إزاحة */
.custom-control-label::before, 
.custom-control-label::after {
    top: 0.25rem;
}

/* لضمان عدم تمدد السطر بشكل مبالغ فيه */
.tree-root li {
    list-style: none;
    margin-bottom: 8px;
}

/* تعديل بسيط لمكان السهم */
.tree-toggle::before {
    top: 5px;
}
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 my-auto">{{ __('home.tree') }}</h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body p-4">
                <a class="btn btn-primary mb-3" href="{{ url('/create_acount') }}">
                    {{ __('home.add_new_account') }}
                </a>

                <ul class="tree-root">
                @foreach(App\Models\acounts_type::get() as $type)
                    <li>
                        <span class="tree-toggle level-0">
                            {{ app()->getLocale() == 'ar' ? $type->name_ar : $type->name_en }}
                        </span>

                        <ul class="tree-children">
                        @foreach(App\Models\financial_accounts::where('account_type',$type->id)->whereNull('parent_account_number')->get() as $lvl1)
                            <li>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="tree-toggle level-1">
                                        ({{ $lvl1->account_number }}) {{ $lvl1->name }}
                                    </span>
                                    <div class="custom-control custom-switch ms-3">
                                        <input type="checkbox" class="custom-control-input update-status" 
                                               id="switch-{{ $lvl1->id }}" data-id="{{ $lvl1->id }}"
                                               {{ $lvl1->active == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="switch-{{ $lvl1->id }}">({{__('home.status_active')}})</label>
                                    </div>
                                </div>

                                <ul class="tree-children">
                                @foreach(App\Models\financial_accounts::where('parent_account_number',$lvl1->id)->get() as $lvl2)
                                    <li>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="tree-toggle level-2">
                                                ({{ $lvl2->account_number }}) {{ $lvl2->name }}
                                            </span>
                                            <div class="custom-control custom-switch ms-3">
                                                <input type="checkbox" class="custom-control-input update-status" 
                                                       id="switch-{{ $lvl2->id }}" data-id="{{ $lvl2->id }}"
                                                       {{ $lvl2->active == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="switch-{{ $lvl2->id }}">({{__('home.status_active')}})</label>
                                            </div>
                                        </div>

                                        <ul class="tree-children">
                                        @foreach(App\Models\financial_accounts::where('parent_account_number',$lvl2->id)->get() as $lvl3)
                                            <li>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="tree-toggle level-3">
                                                        ({{ $lvl3->account_number }}) {{ $lvl3->name }}
                                                    </span>
                                                    <div class="custom-control custom-switch ms-3">
                                                        <input type="checkbox" class="custom-control-input update-status" 
                                                               id="switch-{{ $lvl3->id }}" data-id="{{ $lvl3->id }}"
                                                               {{ $lvl3->active == 1 ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="switch-{{ $lvl3->id }}">({{__('home.status_active')}})</label>
                                                    </div>
                                                </div>

                                                <ul class="tree-children">
                                                @foreach(App\Models\financial_accounts::where('parent_account_number',$lvl3->id)->get() as $lvl4)
                                                    <li>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="level-4 ps-4"> ({{ $lvl4->account_number }}) {{ $lvl4->name }}
                                                            </span>
                                                            <div class="custom-control custom-switch ms-3">
                                                                <input type="checkbox" class="custom-control-input update-status" 
                                                                       id="switch-{{ $lvl4->id }}" data-id="{{ $lvl4->id }}"
                                                                       {{ $lvl4->active == 1 ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="switch-{{ $lvl4->id }}">({{__('home.status_active')}})</label>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on('change', '.update-status', function() {
    let accountId = $(this).data('id');
    let isActive = $(this).is(':checked') ? 1 : 0;
    let isAr = "{{ app()->getLocale() }}" == 'ar';

    $.ajax({
        url: "{{ url('/update_account_status') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: accountId,
            active: isActive
        },
        success: function(response) {
            if(response.success) {
                // رسالة النجاح
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end', // تظهر أسفل الشاشة (يمين للعربي)
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: 'success',
                    title: isAr ? 'تم تحديث الحالة بنجاح' : 'Status updated successfully'
                });
            }
        },
        error: function(r) {
            console.log(r)
            // رسالة الخطأ
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
            });

            Toast.fire({
                icon: 'error',
                title: isAr ? 'حدث خطأ ما أثناء التحديث' : 'An error occurred during update'
            });
        }
    });
});


$(document).ready(function () {

    $('.tree-toggle').on('click', function (e) {
        e.stopPropagation();

        let $current = $(this);
        let $li = $current.closest('li');
        let $parentUl = $li.parent('ul');

        // اقفل الإخوة في نفس المستوى
        $parentUl.children('li').not($li)
            .find('> .tree-toggle')
            .removeClass('open')
            .end()
            .find('> .tree-children')
            .slideUp(200);

        // toggle الحالي
        $current.toggleClass('open');
        $li.children('.tree-children').slideToggle(200);
    });

});
</script>
@endsection
