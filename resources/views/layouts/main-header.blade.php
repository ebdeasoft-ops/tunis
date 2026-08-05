<!-- main-header opened -->
<<<<<<< HEAD
<div class="main-header sticky side-header nav nav-item" style="background: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.06); border-bottom: 1px solid #f1f5f9;">
    <div class="container-fluid the-main" style="display: flex; align-items: center; justify-content: space-between; padding: 0 15px; min-height: 70px;">
        
        <!-- القسم الأيمن (الشعار، زر القائمة، والروابط السريعة) -->
        <div class="main-header-left" style="display: flex; align-items: center; gap: 15px;">
=======
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
>>>>>>> f82bda17d3b11503d62f76d20242603faf35e88a
            <div class="responsive-logo">
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo.png') }}" class="logo-1" alt="logo" style="max-height: 40px;"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo-white.png') }}" class="dark-logo-1" alt="logo" style="max-height: 40px;"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="logo-2" alt="logo" style="max-height: 30px;"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="dark-logo-2" alt="logo" style="max-height: 30px;"></a>
            </div>
            
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle text-dark" href="#" style="font-size: 18px;"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle text-dark" href="#" style="font-size: 18px;"><i class="header-icons fe fe-x"></i></a>
            </div>

<<<<<<< HEAD
            <!-- الروابط السريعة المباشرة في الهيدر العلوي -->
            <div class="d-none d-xl-flex align-items-center">
                <ul class="nav header-shortcut-nav mb-0" style="display: flex; gap: 5px; list-style: none; padding: 0;">
=======
            <!-- الروابط السريعة المباشرة في الهيدر العلوي بشكل احترافي -->
          <div class="d-none d-lg-flex align-items-center mr-3">
                <ul class="nav header-shortcut-nav">
>>>>>>> f82bda17d3b11503d62f76d20242603faf35e88a
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

<<<<<<< HEAD
=======
                    @can('request price from supplier')
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 custom-shortcut-link" href="{{ url('/getproductsprice') }}">
                            <i class="fas fa-tags ml-1 text-info custom-shortcut-icon"></i> {{ __('home.Requestـpricesـofـproducts') }}
                        </a>
                    </li>
                    @endcan
>>>>>>> f82bda17d3b11503d62f76d20242603faf35e88a
                </ul>
            </div>
        </div>

        <!-- القسم الأيسر (مجموعة أدوات التحكم متجاورة وبمسافات دقيقة) -->
        <div class="main-header-right" style="display: flex; align-items: center;">
            @php
            $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
            $endOfToday = \Carbon\Carbon::now()->endOfDay();

            $totalUnsent = \App\Models\invoices::where('save', 1)
            ->where('sent_to_zatca', 0)
            ->where('status', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfToday])
            ->count();
            @endphp

            <!-- تم تغيير `ml-auto` وضبط الـ gap لجمع العناصر معاً بجانب بعضها بدون تباعد مزعج -->
            <div class="nav nav-item navbar-nav-right align-items-center" style="display: flex; align-items: center; gap: 8px; margin-right: auto;">

                <!-- زر تغيير اللغات الاحترافي والمحدث -->
                <ul class="links mb-0 list-unstyled p-0 m-0">
                    <li class="dropdown nav-item main-header-notification">
                        <a href="#" class="new nav-link px-3 py-2 dropdown-toggle" data-toggle="dropdown"
                            style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 30px; transition: all 0.3s ease; text-decoration: none;">
                            
                            <div class="rounded-circle d-flex align-items-center justify-content-center ml-2" style="width: 24px; height: 24px; background: rgba(0,77,68,0.1); min-width: 24px;">
                                <i class="fas fa-globe text-success" style="font-size: 12px;"></i>
                            </div>

                            <span class="text-dark d-none d-md-inline" style="font-size: 12px; font-weight: 600;">
                                {{ LaravelLocalization::getCurrentLocaleName() == 'اللغة العربية' ? 'العربية' : LaravelLocalization::getCurrentLocaleName() }}
                            </span>
                            
                            <i class="fas fa-chevron-down text-muted mr-1" style="font-size: 9px;"></i>
                        </a>

                        <div class="dropdown-menu shadow-lg border-0 p-2"
                            style="width: 170px; border-radius: 12px; margin-top: 10px; left: 0 !important; right: auto !important; overflow: hidden;">
                            <div class="px-3 py-1 mb-1 text-muted border-bottom" style="font-size: 11px; font-weight: bold;">
                                اختر لغة العرض
                            </div>
                            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <a class="dropdown-item d-flex align-items-center justify-content-between px-3 py-2 rounded {{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'active bg-light text-success font-weight-bold' : 'text-dark' }}"
                                   rel="alternate" hreflang="{{ $localeCode }}"
                                   href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                   style="font-size: 12px; transition: all 0.2s ease;">
                                    <span>{{ $properties['native'] }}</span>
                                    @if(LaravelLocalization::getCurrentLocale() == $localeCode)
                                        <i class="fas fa-check text-success" style="font-size: 11px;"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </li>
                </ul>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
                <!-- زر حالة الربط الضريبي ZATCA الاحترافي -->
<<<<<<< HEAD
                <div class="dropdown nav-item main-header-notification list-unstyled m-0">
                    <a class="new nav-link px-3 py-2" href="#" data-toggle="dropdown"
                        style="display: flex; align-items: center; background: linear-gradient(135deg, #004d44 0%, #00695c 100%); border-radius: 30px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,77,68,0.25); text-decoration: none;">

                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="fas fa-shield-alt text-white" style="font-size: 14px;"></i>
                            @if($totalUnsent > 0)
                            <span class="badge badge-pill badge-danger shadow-sm pulse-badge"
                                style="position: absolute; top: -8px; right: -10px; font-size: 9px; padding: 2px 5px; border: 2px solid #fff; background-color: #ff3d00; font-weight: bold;">
                                {{ $totalUnsent }}
                            </span>
                            @endif
                        </div>

                        <span class="mx-2 text-white d-none d-md-inline" style="font-size: 12px; font-weight: 600;">الربط الضريبي</span>
                        <i class="fas fa-chevron-down text-white-50 mr-1" style="font-size: 9px;"></i>
                    </a>

                    <div class="dropdown-menu shadow-lg border-0 p-0"
                        style="width: 300px; border-radius: 14px; margin-top: 10px; left: 0 !important; right: auto !important; overflow: hidden;">
                        
                        <div class="p-3 text-white" style="background: linear-gradient(135deg, #004d44 0%, #00695c 100%);">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center ml-2" style="width: 30px; height: 30px; background: rgba(255,255,255,0.15);">
                                        <i class="fas fa-university text-white" style="font-size: 13px;"></i>
                                    </div>
                                    <h6 class="mb-0 text-white" style="font-size: 13px; font-weight: 700;">هيئة الزكاة (ZATCA)</h6>
                                </div>
                                <span class="badge badge-light text-success" style="font-size: 10px; font-weight: bold; padding: 3px 7px; border-radius: 20px;">مباشر</span>
                            </div>
                        </div>

                        <div class="p-3 bg-white text-center">
                            @if($totalUnsent > 0)
                            <div class="mb-3 text-right px-2 py-2 rounded" style="background: #fff5f5; border-right: 4px solid #ff3d00;">
                                <p class="mb-1 text-dark" style="font-size: 12px; font-weight: 700;">
                                    <i class="fas fa-exclamation-triangle text-danger ml-1"></i> تنبيه فواتير معلقة:
                                </p>
                                <p class="text-muted mb-0" style="font-size: 11px;">
                                    لديك <b class="text-danger">{{ $totalUnsent }}</b> فاتورة بانتظار الإرسال للهيئة.
                                </p>
                            </div>
                            
                            <a href="{{ url('previousSales_not_sended_Invoices_all_branchs') }}"
                                class="btn btn-sm btn-block text-white shadow-sm py-2 d-flex align-items-center justify-content-center"
                                style="background: #004d44; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">
                                <span>إرسال الفواتير المعلقة الآن</span>
                                <i class="fas fa-paper-plane mr-2" style="font-size: 10px;"></i>
                            </a>
                            @else
                            <div class="py-2">
                                <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 42px; height: 42px; background: #e8f5e9;">
                                    <i class="fas fa-check-double text-success" style="font-size: 18px;"></i>
                                </div>
                                <p class="mb-1 text-dark font-weight-bold" style="font-size: 12px;">حالة الربط مستقرة وسليمة</p>
                                <small class="text-muted" style="font-size: 10px;">تم إرسال كافة الفواتير بنجاح تام</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
=======
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
>>>>>>> f82bda17d3b11503d62f76d20242603faf35e88a

                <!-- زر الشاشة الكاملة -->
                <div class="nav-item full-screen fullscreen-button d-none d-sm-block m-0">
                    <a class="new nav-link full-screen-link text-dark p-2" href="#" style="display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                    </a>
                </div>

                <!-- قائمة المستخدم (Profile) -->
<<<<<<< HEAD
                <div class="dropdown main-profile-menu nav nav-item m-0">
                    <a class="profile-user d-flex" href="#" data-toggle="dropdown" style="text-decoration: none;">
                        <img alt="user" src="{{ URL::asset('storage/' . Auth::user()->profile_photo_path) }}"
                            class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #004d44;">
                    </a>
                    <div class="dropdown-menu profile-menu shadow-lg border-0"
                        style="width: 240px; border-radius: 12px; margin-top: 10px; left: 0 !important; right: auto !important; overflow: hidden;">
                        <div class="main-header-profile p-3" style="background: linear-gradient(135deg, #004d44 0%, #00695c 100%);">
                            <div class="d-flex align-items-center">
                                <div class="main-img-user ml-2">
                                    <img alt="user" src="{{ URL::asset('storage/' . Auth::user()->profile_photo_path) }}" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                </div>
                                <div class="my-auto overflow-hidden">
                                    <h6 class="mb-1 text-white text-truncate" style="font-size: 12px; font-weight: 600;">{{ Auth::user()->name }}</h6>
                                    <span class="text-white-50 text-truncate d-block" style="font-size: 10px;">{{ Auth::user()->email }}</span>
=======
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
>>>>>>> f82bda17d3b11503d62f76d20242603faf35e88a
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <a class="dropdown-item py-2 px-3 rounded text-dark" href="{{ url('user/profile') }}" style="font-size: 12px; display: flex; align-items: center;">
                                <i class="bx bx-slider-alt ml-2 text-muted" style="font-size: 15px;"></i>{{ __('auth.setting') }}
                            </a>
                            <a class="dropdown-item py-2 px-3 rounded text-danger" href="{{ route('logout') }}"
<<<<<<< HEAD
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="font-size: 12px; display: flex; align-items: center;">
                                <i class="bx bx-log-out ml-2" style="font-size: 15px;"></i>تسجيل خروج
=======
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out ml-2"></i>{{ __('home.logout') ?? 'تسجيل خروج' }}
>>>>>>> f82bda17d3b11503d62f76d20242603faf35e88a
                            </a>
                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<<<<<<< HEAD
<!-- /main-header -->
=======
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
>>>>>>> f82bda17d3b11503d62f76d20242603faf35e88a
