<!-- main-header opened -->
<style>
    .custom-shortcut-link {
        font-size: 16px !important; /* تكبير حجم الخط */
        font-weight: 800 !important; /* جعل الخط عريضاً */
    }
    .custom-shortcut-icon {
        font-size: 17px !important; /* تكبير حجم الأيقونات */
    }
</style>
<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid the-main">
        <div class="main-header-left">
            <div class="responsive-logo">
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo.png') }}" class="logo-1" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo-white.png') }}" class="dark-logo-1" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="logo-2" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>

            <!-- الروابط السريعة المباشرة في الهيدر العلوي بشكل احترافي -->
          <div class="d-none d-lg-flex align-items-center mr-3">
                <ul class="nav header-shortcut-nav">
                    @can('Sales products')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 custom-shortcut-link" href="{{ url('/goToSale') }}">
                            <i class="fas fa-cash-register ml-1 text-primary custom-shortcut-icon"></i> {{ __('home.sales') }}
                        </a>
                    </li>
                    @endcan

                    @can('sales return')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 custom-shortcut-link" href="{{ url('/return_sale') }}">
                            <i class="fas fa-undo ml-1 text-danger custom-shortcut-icon"></i> {{ __('home.salesـreturned') }}
                        </a>
                    </li>
                    @endcan

                    @can('Purchases products')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 custom-shortcut-link" href="{{ url('/purchases') }}">
                            <i class="fas fa-shopping-cart ml-1 text-success custom-shortcut-icon"></i> {{ __('home.purchases') }}
                        </a>
                    </li>
                    @endcan

                    @can('offer price to customer')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 custom-shortcut-link" href="{{ url('/getproductspricetocustomer') }}">
                            <i class="fas fa-file-invoice-dollar ml-1 text-warning custom-shortcut-icon"></i> {{ __('home.Offerـpricesـtoـcustomer') }}
                        </a>
                    </li>
                    @endcan

                    @can('request price from supplier')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 custom-shortcut-link" href="{{ url('/getproductsprice') }}">
                            <i class="fas fa-tags ml-1 text-info custom-shortcut-icon"></i> {{ __('home.Requestـpricesـofـproducts') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>

        <div class="main-header-right">
            @php
            // تحديد بداية الشهر ونهاية اليوم الحالي
            $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
            $endOfToday = \Carbon\Carbon::now()->endOfDay();

            $totalUnsent = \App\Models\invoices::where('save', 1)
            ->where('sent_to_zatca', 0)
            ->where('status', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfToday])
            ->count();
            @endphp

            <div class="nav nav-item navbar-nav-right ml-auto align-items-center">

                <!-- زر تغيير اللغات الاحترافي -->
                <ul class="links mb-0 list-unstyled">
                    <li class="dropdown">
                        <a href="#" class="trigger-drop main-a lang-a px-2">
                            {{ LaravelLocalization::getCurrentLocaleName() == 'اللغة العربية' ? 'العربية' : LaravelLocalization::getCurrentLocaleName() }}
                            <i class="arrow ml-1"></i>
                        </a>
                        <ul class="drop shadow-sm border-0" style="margin-left: 20px; border-radius: 8px;">
                            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li class="cont-a">
                                <a class="lang-a choice" rel="alternate" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>

                <!-- زر حالة الربط الضريبي ZATCA الاحترافي -->
           <li class="dropdown nav-item main-header-notification list-unstyled mx-1">
                <a class="nav-link p-0 d-flex align-items-center justify-content-center" href="#" data-toggle="dropdown"
                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #004d44 0%, #00695c 100%); border-radius: 50%; transition: all 0.3s ease; box-shadow: 0 2px 6px rgba(0,77,68,0.25);">

                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shield-alt text-white" style="font-size: 16px;"></i>
                        @if($totalUnsent > 0)
                        <span class="badge badge-pill badge-danger shadow-sm"
                            style="position: absolute; top: -10px; right: -12px; font-size: 9px; padding: 3px 5px; border: 2px solid #fff; background-color: #ff3d00;">
                            {{ $totalUnsent }}
                        </span>
                        @endif
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-left shadow-lg border-0 p-0"
                    style="width: 290px; border-radius: 12px; margin-top: 10px; overflow: hidden;">
                    <div class="p-3 text-white"
                        style="background: linear-gradient(135deg, #004d44 0%, #00695c 100%);">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-university text-white-50 ml-2" style="font-size: 16px;"></i>
                            <h6 class="mb-0 text-white" style="font-size: 13px; font-weight: 600;">
                                {{ __('home.ZATCA_Platform') ?? 'منصة هيئة الزكاة (ZATCA)' }}
                            </h6>
                        </div>
                    </div>

                    <div class="p-3 text-center bg-white">
                        @if($totalUnsent > 0)
                        <div class="mb-3 text-right px-2">
                            <p class="mb-1 text-dark" style="font-size: 13px; font-weight: 600;">
                                <i class="fas fa-exclamation-circle text-danger ml-1"></i> {{ __('home.Status') ?? 'حالة الإرسال' }}:
                                <span class="text-danger">{{ __('home.Pending_Invoices') ?? 'يوجد معلق' }}</span>
                            </p>
                            <p class="text-muted mb-0" style="font-size: 12px;">
                                {{ __('home.You_Have') ?? 'لديك' }} <b class="text-dark">{{ $totalUnsent }}</b> {{ __('home.Invoices_Waiting') ?? 'فاتورة بانتظار الإرسال للهيئة.' }}
                            </p>
                        </div>
                        <a href="{{ url('previousSales_not_sended_Invoices_all_branchs') }}"
                            class="btn btn-sm btn-block text-white shadow-sm py-2"
                            style="background: #004d44; border-radius: 6px; font-size: 12px; font-weight: 600; transition: background 0.2s;">
                            {{ __('home.Send_Invoices_Now') ?? 'إرسال الفواتير الآن' }} <i class="fas fa-paper-plane mr-1"></i>
                        </a>
                        @else
                        <div class="py-3">
                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                style="width: 45px; height: 45px; background: #e8f5e9;">
                                <i class="fas fa-check-double text-success" style="font-size: 20px;"></i>
                            </div>
                            <p class="mb-1 text-dark font-weight-bold" style="font-size: 13px;">
                                {{ __('home.Status_Good') ?? 'موقف الربط سليم تماماً' }}
                            </p>
                            <small class="text-muted" style="font-size: 11px;">
                                {{ __('home.All_Invoices_Sent') ?? 'تم إرسال كافة الفواتير بنجاح' }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </li>

                <!-- زر الشاشة الكاملة -->
                <div class="nav-item full-screen fullscreen-button left-link mx-1">
                    <a class="new nav-link full-screen-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 22 22" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-maximize">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                            </path>
                        </svg>
                    </a>
                </div>

                <!-- قائمة المستخدم (Profile) -->
             <div class="dropdown main-profile-menu nav nav-item nav-link left-link">
                <a class="profile-user d-flex" href="">
                        <img alt="user"
                            src="{{ Auth::user()->profile_photo_path ? URL::asset('storage/' . Auth::user()->profile_photo_path) : URL::asset('assets/img/9055398.png') }}"
                            class="rounded-circle shadow-sm"
                            style="width: 38px; height: 38px; object-fit: cover;">
                    </a>

                    <div class="dropdown-menu profile-menu shadow-lg border-0" style="border-radius: 10px; overflow: hidden;">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p align-items-center">
                                <div class="main-img-user">
                                    <img alt="user"
                                        src="{{ Auth::user()->profile_photo_path ? URL::asset('storage/' . Auth::user()->profile_photo_path) : URL::asset('assets/img/9055398.png') }}"
                                        class="rounded-circle"
                                        style="width: 42px; height: 42px; object-fit: cover;">
                                </div>
                                <div class="mr-3 my-auto">
                                    <h6 class="mb-1 text-white" style="font-size: 13px; font-weight: 600;">
                                        {{ Auth::user()->name }}
                                    </h6>
                                    <span class="text-white-50" style="font-size: 11px;">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <a class="dropdown-item py-2 px-3 rounded" href="{{ url('user/profile') }}">
                                <i class="bx bx-slider-alt ml-2"></i>{{ __('auth.setting') }}
                            </a>
                            <a class="dropdown-item py-2 px-3 rounded text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out ml-2"></i>{{ __('home.logout') ?? 'تسجيل خروج' }}
                            </a>
                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

                <!-- زر القائمة الجانبية اليسرى -->
                <div class="dropdown main-header-message right-toggle left-link shortcut-window mx-1">
                    <a class="nav-link pr-0" data-toggle="sidebar-left" data-target=".sidebar-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-menu">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /main-header -->

@section('css')
<style>
/* تنسيق احترافي لروابط العمليات السريعة في الهيدر */
.header-shortcut-nav .nav-link {
    font-size: 13px;
    font-weight: 600;
    color: #333 !important;
    border-radius: 6px;
    transition: all 0.2s ease-in-out;
    margin: 0 3px;
}
.header-shortcut-nav .nav-link:hover {
    background-color: rgba(0, 0, 0, 0.04);
    color: #004d44 !important;
    transform: translateY(-1px);
}
</style>
@endsection

@section('js')
<script>
function display_ct6() {
    var x = new Date()
    var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
    hours = x.getHours() % 12;
    hours = hours ? hours : 12;
    var x1 = x.getMonth() + 1 + "/" + x.getDate() + "/" + x.getFullYear();
    x1 = x1 + " - " + hours + ":" + x.getMinutes() + ":" + x.getSeconds() + ":" + ampm;
    var clockEl = document.getElementById('ct6');
    if (clockEl) {
        clockEl.innerHTML = x1;
    }
    display_c6();
}

function display_c6() {
    var refresh = 1000;
    mytime = setTimeout('display_ct6()', refresh);
}

$(document).ready(function() {
    display_c6();
});
</script>
@endsection
