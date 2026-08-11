<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item" style="background: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.06); border-bottom: 1px solid #f1f5f9;">
    <div class="container-fluid the-main" style="display: flex; align-items: center; justify-content: space-between; padding: 0 15px; min-height: 70px;">

        <!-- القسم الأيمن (الشعار، زر القائمة، والروابط السريعة) -->
        <div class="main-header-left" style="display: flex; align-items: center; gap: 15px;">
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

            <!-- الروابط السريعة المباشرة في الهيدر العلوي -->
         <div class="d-none d-xl-flex align-items-center">
            <ul class="nav header-shortcut-nav mb-0" style="display: flex; gap: 15px; list-style: none; padding: 0; align-items: center;">

                @can('Sales products')
                <li class="nav-item">
                    <a class="nav-link px-2 py-1 d-flex align-items-center" href="{{ url('/goToSale') }}" style="font-size: 1.1rem; font-weight: 600;">
                        <i class="fas fa-cash-register me-2 ms-2 text-primary"></i>
                        <span>{{ __('home.sales') }}</span>
                    </a>
                </li>
                @endcan

                @can('sales return')
                <li class="nav-item">
                    <a class="nav-link px-2 py-1 d-flex align-items-center" href="{{ url('/return_sale') }}" style="font-size: 1.1rem; font-weight: 600;">
                        <i class="fas fa-undo me-2 ms-2 text-danger"></i>
                        <span>{{ __('home.salesـreturned') }}</span>
                    </a>
                </li>
                @endcan

                @can('Purchases products')
                <li class="nav-item">
                    <a class="nav-link px-2 py-1 d-flex align-items-center" href="{{ url('/purchases') }}" style="font-size: 1.1rem; font-weight: 600;">
                        <i class="fas fa-shopping-cart me-2 ms-2 text-success"></i>
                        <span>{{ __('home.purchases') }}</span>
                    </a>
                </li>
                @endcan

                @can('offer price to customer')
                <li class="nav-item">
                    <a class="nav-link px-2 py-1 d-flex align-items-center" href="{{ url('/getproductspricetocustomer') }}" style="font-size: 1.1rem; font-weight: 600;">
                        <i class="fas fa-file-invoice-dollar me-2 ms-2 text-warning"></i>
                        <span>{{ __('home.Offerـpricesـtoـcustomer') }}</span>
                    </a>
                </li>
                @endcan

            </ul>
        </div>
        </div>

        <!-- القسم الأيسر (مجموعة أدوات التحكم متجاورة وبمسافات دقيقة) -->
<div class="main-header-right d-flex align-items-center">
    @php
    $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
    $endOfToday = \Carbon\Carbon::now()->endOfDay();

    $totalUnsent = \App\Models\invoices::where('save', 1)
        ->where('sent_to_zatca', 0)
        ->where('status', 0)
        ->whereBetween('created_at', [$startOfMonth, $endOfToday])
        ->count();
    @endphp

    <div class="d-flex align-items-center ms-auto me-0" style="gap: 12px; position: relative; z-index: 99;">

        <!-- 1. زر تغيير اللغة -->
        <div class="dropdown position-relative" style="margin: 0 !important; padding: 0 !important;">
            <a href="#" class="btn btn-sm d-flex align-items-center gap-2 px-3 dropdown-toggle shadow-none" data-toggle="dropdown" data-bs-toggle="dropdown"
                style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 30px; height: 38px; color: #333; text-decoration: none;">

                <div class="rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 24px; height: 24px; background: rgba(0, 77, 68, 0.1); flex-shrink: 0;">
                    <i class="fas fa-globe text-success" style="font-size: 12px;"></i>
                </div>

                <span class="d-none d-md-inline font-weight-bold" style="font-size: 13px; white-space: nowrap;">
                    {{ LaravelLocalization::getCurrentLocaleName() == 'اللغة العربية' ? __('home.arabic') : LaravelLocalization::getCurrentLocaleName() }}
                </span>
            </a>

            <div class="dropdown-menu shadow-lg border-0 p-2 dropdown-menu-end" style="width: 170px; border-radius: 12px; margin-top: 8px;">
                <div class="px-3 py-1 mb-1 text-muted border-bottom" style="font-size: 11px; font-weight: bold;">
                    {{ __('home.select_language') }}
                </div>
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a class="dropdown-item d-flex align-items-center justify-content-between px-3 py-2 rounded {{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'active bg-light text-success font-weight-bold' : 'text-dark' }}"
                       rel="alternate" hreflang="{{ $localeCode }}"
                       href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       style="font-size: 12px;">
                        <span>{{ $properties['native'] }}</span>
                        @if(LaravelLocalization::getCurrentLocale() == $localeCode)
                            <i class="fas fa-check text-success" style="font-size: 11px;"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 2. زر حالة الربط الضريبي ZATCA -->
        <div class="dropdown position-relative" style="margin: 0 !important; padding: 0 !important;">
            <a class="btn btn-sm d-flex align-items-center gap-2 px-3 dropdown-toggle shadow-none" href="#" data-toggle="dropdown" data-bs-toggle="dropdown"
                style="background: linear-gradient(135deg, #004d44 0%, #00695c 100%); border-radius: 30px; height: 38px; color: #fff; text-decoration: none; border: none;">

                <div class="position-relative d-flex align-items-center justify-content-center">
                    <i class="fas fa-shield-alt text-white" style="font-size: 14px;"></i>
                    @if($totalUnsent > 0)
                    <span class="badge badge-pill badge-danger position-absolute"
                        style="top: -8px; right: -10px; font-size: 9px; padding: 2px 5px; border: 1.5px solid #fff; background-color: #ff3d00;">
                        {{ $totalUnsent }}
                    </span>
                    @endif
                </div>

                <span class="d-none d-md-inline font-weight-bold" style="font-size: 12px; white-space: nowrap;">
                    {{ __('home.tax_integration') }}
                </span>
            </a>

            <div class="dropdown-menu shadow-lg border-0 p-0 dropdown-menu-end" style="width: 290px; border-radius: 14px; margin-top: 8px; overflow: hidden;">
                <div class="p-3 text-white" style="background: linear-gradient(135deg, #004d44 0%, #00695c 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(255,255,255,0.15);">
                                <i class="fas fa-university text-white" style="font-size: 12px;"></i>
                            </div>
                            <h6 class="mb-0 text-white" style="font-size: 13px; font-weight: 700;">{{ __('home.zatca_authority') }}</h6>
                        </div>
                        <span class="badge badge-light text-success" style="font-size: 10px; font-weight: bold; padding: 3px 7px; border-radius: 20px;">
                            {{ __('home.live') }}
                        </span>
                    </div>
                </div>

                <div class="p-3 bg-white text-center">
                    @if($totalUnsent > 0)
                    <div class="mb-3 text-start px-3 py-2 rounded" style="background: #fff5f5; border-right: 4px solid #ff3d00;">
                        <p class="mb-1 text-dark d-flex align-items-center gap-1" style="font-size: 12px; font-weight: 700;">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            <span>{{ __('home.pending_invoices_warning') }}</span>
                        </p>
                        <p class="text-muted mb-0" style="font-size: 11px;">
                            {{ __('home.you_have') }} <b class="text-danger">{{ $totalUnsent }}</b> {{ __('home.invoices_waiting_to_send') }}
                        </p>
                    </div>

                    <a href="{{ url('previousSales_not_sended_Invoices_all_branchs') }}"
                        class="btn btn-sm btn-block text-white py-2 d-flex align-items-center justify-content-center gap-2"
                        style="background: #004d44; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">
                        <span>{{ __('home.send_pending_invoices_now') }}</span>
                        <i class="fas fa-paper-plane" style="font-size: 10px;"></i>
                    </a>
                    @else
                    <div class="py-2">
                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 40px; height: 40px; background: #e8f5e9;">
                            <i class="fas fa-check-double text-success" style="font-size: 16px;"></i>
                        </div>
                        <p class="mb-1 text-dark font-weight-bold" style="font-size: 12px;">{{ __('home.integration_status_stable') }}</p>
                        <small class="text-muted" style="font-size: 10px;">{{ __('home.all_invoices_sent_successfully') }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. زر الشاشة الكاملة -->
        <div class="d-none d-sm-flex align-items-center" style="margin: 0 !important;">
            <a class="nav-link text-dark p-1 d-flex align-items-center justify-content-center" href="#" style="width: 38px; height: 38px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
            </a>
        </div>

        <!-- 4. قائمة المستخدم (Profile) -->
        <div class="dropdown position-relative" style="margin: 0 !important; padding: 0 !important;">
            <a class="d-flex align-items-center p-0" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" style="text-decoration: none;">
                <img alt="user" src="{{ Auth::user()->profile_photo_path ? URL::asset('storage/' . Auth::user()->profile_photo_path) : URL::asset('assets/img/faces/6.jpg') }}"
                    class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #004d44;">
            </a>

            <div class="dropdown-menu shadow-lg border-0 p-0 dropdown-menu-end" style="width: 230px; border-radius: 12px; margin-top: 8px; overflow: hidden;">
                <div class="p-3 text-white" style="background: linear-gradient(135deg, #004d44 0%, #00695c 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <img alt="user" src="{{ Auth::user()->profile_photo_path ? URL::asset('storage/' . Auth::user()->profile_photo_path) : URL::asset('assets/img/faces/6.jpg') }}" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                        <div class="overflow-hidden">
                            <h6 class="mb-0 text-white text-truncate" style="font-size: 12px; font-weight: 600;">{{ Auth::user()->name }}</h6>
                            <span class="text-white-50 text-truncate d-block" style="font-size: 10px;">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-2">
                    <a class="dropdown-item py-2 px-3 rounded text-dark d-flex align-items-center gap-2" href="{{ url('user/profile') }}" style="font-size: 12px;">
                        <i class="bx bx-slider-alt text-muted" style="font-size: 15px;"></i>
                        <span>{{ __('auth.setting') }}</span>
                    </a>
                    <a class="dropdown-item py-2 px-3 rounded text-danger d-flex align-items-center gap-2" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="font-size: 12px;">
                        <i class="bx bx-log-out" style="font-size: 15px;"></i>
                        <span>{{ __('home.logout') }}</span>
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
<!-- /main-header -->
