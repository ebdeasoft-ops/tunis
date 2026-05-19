<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid the-main">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo.png') }}" class="logo-1"
                        alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/logo-white.png') }}"
                        class="dark-logo-1" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}"
                        class="logo-2" alt="logo"></a>
                <a href="https://ebdeasoft.com/"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}"
                        class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
            <div class="main-header-center mr-3 d-sm-none d-md-none d-lg-block parent-header mb-2">
                @can('Sales products')
                    <div class="nav-link d-inline-block">
                        <a href="{{ url('/' . ($page = 'goToSale')) }}">{{ __('home.sales') }} </a>
                    </div>
                @endcan
                @can('sales return')
                    <div class="nav-link d-inline-block">
                        <a href="{{ url('/' . ($page = 'return_sale')) }}">{{ __('home.salesـreturned') }}</a>
                    </div>
                @endcan
                @can('Purchases products')
                    <div class="nav-link d-inline-block">
                        <a href="{{ url('/' . ($page = 'purchases')) }}"> {{ __('home.purchases') }}</a>
                    </div>
                @endcan
                @can('Add a new customer')
                    <div class="nav-link d-inline-block">
                        <a href="{{ url('/' . ($page = 'getproductspricetocustomer')) }}">{{ __('home.Offerـpricesـtoـcustomer') }}</a>
                    </div>
                @endcan




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
                    ->whereBetween('created_at', [$startOfMonth, $endOfToday]) // شرط الفترة الزمنية
                    ->count();
@endphp

&nbsp;&nbsp;

            <div class="nav nav-item  navbar-nav-right ml-auto">
                <ul class="links">
                    <li class="dropdown">
                        <a href="#" class="trigger-drop main-a lang-a">
                            {{ LaravelLocalization::getCurrentLocaleName() == 'اللغة العربية' ? 'العربية' : LaravelLocalization::getCurrentLocaleName() }}
                            <i class="arrow">
                            </i>
                        </a>
                        <ul class="drop" style="margin-left: 20px">
                            
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

<li class="dropdown nav-item main-header-notification">
    <a class="new nav-link px-2" href="#" data-toggle="dropdown" 
       style="display: flex; align-items: center; background: #004d44; border-radius: 50px; padding: 6px 14px; transition: all 0.3s; box-shadow: 0 2px 6px rgba(0,77,68,0.2);">
        
        <div style="position: relative; display: flex; align-items: center;">
            <i class="fas fa-shield-alt text-white" style="font-size: 16px;"></i>
            
            @if($totalUnsent > 0)
                <span class="badge badge-pill badge-danger shadow-sm" 
                      style="position: absolute; top: -10px; right: -12px; font-size: 10px; padding: 3px 6px; border: 2px solid #fff; background-color: #ff4b2b;">
                    {{ $totalUnsent }}
                </span>
            @endif
        </div>

        <span class="mx-2 text-white d-none d-md-inline" style="font-size: 11px; font-weight: 600;">الربط الضريبي</span>
        
        <i class="fas fa-chevron-down text-white-50 mr-1" style="font-size: 8px;"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-left shadow-lg border-0" style="width: 280px; border-radius: 15px; margin-top: 12px; border: 1px solid #e1e1e1;">
        <div class="p-3" style="background: linear-gradient(135deg, #004d44 0%, #00695c 100%); border-radius: 15px 15px 0 0;">
            <div class="d-flex align-items-center">
                <i class="fas fa-university text-white-50 ml-2"></i>
                <h6 class="mb-0 text-white" style="font-size: 13px; font-weight: 600;">منصة هيئة الزكاة (ZATCA)</h6>
            </div>
        </div>
        
        <div class="p-4 text-center">
            @if($totalUnsent > 0)
                <div class="mb-3 text-right">
                    <p class="mb-1 text-dark" style="font-size: 13px; font-weight: bold;">
                        <i class="fas fa-info-circle text-danger ml-1"></i> إرسال الفواتير: <span class="text-danger">غير مكتمل</span>
                    </p>
                    <p class="text-muted" style="font-size: 12px;">لديك <b class="text-dark">{{ $totalUnsent }}</b> فاتورة بانتظار الإرسال.</p>
                </div>
                <a href="{{ url('previousSales_not_sended_Invoices_all_branchs') }}" 
                   class="btn btn-sm btn-block text-white shadow-sm py-2" 
                   style="background: #004d44; border-radius: 8px; font-size: 12px; font-weight: bold;">
                   إرسال الفواتير الآن <i class="fas fa-paper-plane mr-2"></i>
                </a>
            @else
                <div class="py-2">
                    <div class="bg-success-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #e8f5e9;">
                        <i class="fas fa-check-double text-success" style="font-size: 24px;"></i>
                    </div>
                    <p class="mb-0 text-dark font-weight-bold" style="font-size: 13px;">موقف الربط سليم تماماً</p>
                    <small class="text-muted">تم إرسال كافة الفواتير للهيئة</small>
                </div>
            @endif
        </div>
    </div>
</li>
&nbsp;
&nbsp;
&nbsp;

   


                <div class="nav-item full-screen fullscreen-button left-link">
                    <a class="new nav-link full-screen-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 22 22"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-maximize">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                            </path>
                        </svg>
                    </a>
                </div>
                <div class="dropdown main-profile-menu nav nav-item nav-link left-link">
                    <a class="profile-user d-flex" href="">
                        <img alt="" src="{{ URL::asset('storage/' . Auth::user()->profile_photo_path) }}">
                    </a>
                    <div class="dropdown-menu profile-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user"><img alt=""
                                        src="{{ URL::asset('storage/' . Auth::user()->profile_photo_path) }}"
                                        class=""></div>
                                <div class="mr-3 my-auto">
                                    <h6>{{ Auth::user()->name }}</h6><span>{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>

                        <a class="dropdown-item" href="{{ url('user/profile') }}"><i
                                class="bx bx-slider-alt"></i>{{ __('auth.setting') }}</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                class="bx bx-log-out"></i>تسجيل خروج</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>

                    </div>

                </div>
                <div class="dropdown main-header-message right-toggle left-link shortcut-window">
                    <a class="nav-link pr-0" data-toggle="sidebar-left" data-target=".sidebar-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-menu">
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


@section('js')
    <script>
        function display_ct6() {
            var x = new Date()
            var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
            hours = x.getHours() % 12;
            hours = hours ? hours : 12;
            var x1 = x.getMonth() + 1 + "/" + x.getDate() + "/" + x.getFullYear();
            x1 = x1 + " - " + hours + ":" + x.getMinutes() + ":" + x.getSeconds() + ":" + ampm;
            document.getElementById('ct6').innerHTML = x1;
         
            display_c6();
            
        
            
        }

        function display_c6() {
            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct6()', refresh)
        }



        $(document).ready(function() {


            display_c6()
        });
    </script>
@endsection
