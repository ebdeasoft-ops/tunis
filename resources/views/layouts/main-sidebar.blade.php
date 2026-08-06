<style>
/* تحديد ارتفاع ثابت للسايد بار ومنحه سكرول منفصل */
.app-sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    right: 0;
    /* لأن السيستم عندك RTL */
    height: 100vh !important;
    overflow-y: auto !important;
    /* تفعيل السكرول الرأسي */
    z-index: 1000;
}

/* تحسين شكل السكرول بار عشان ميبقاش شكله وحش */
.app-sidebar::-webkit-scrollbar {
    width: 5px;
}

.app-sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

/* كلاسات لضمان ثبات القائمة وعرضها فوراً عند التحميل */
.app-sidebar .slide.is-expanded>.slide-menu {
    display: block !important;
}
</style>


<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">

    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="https://ebdeasoft.com/"><img
                src="{{ URL::asset('assets/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="https://ebdeasoft.com/"><img
                src="{{ URL::asset('assets/img/brand/logo-white.png') }}"'product_sales' class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="https://ebdeasoft.com/"><img
                src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="https://ebdeasoft.com/"><img
                src="{{ URL::asset('assets/img/brand/favicon-white.png') }}" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">


        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround"
                        src="{{ URL::asset('storage/'.Auth::user()->profile_photo_path) }}"><span
                        class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                    <span class="mb-0 text-muted">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>


        <ul class="side-menu">

            @can('Home')
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/dashboard') }}">
                    <svg style="color: green !important;" class="side-menu__icon" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512">
                        <path
                            d="M543.8 287.6c17 0 32-14 32-32.1c1-9-3-17-11-24L309.5 7c-6-5-14-7-21-7s-15 1-22 8L10 231.5c-7 7-10 15-10 24c0 18 14 32.1 32 32.1h32V448c0 35.3 28.7 64 64 64H230.4l-31.3-52.2c-4.1-6.8-2.6-15.5 3.5-20.5L288 368l-60.2-82.8c-10.9-15 8.2-33.5 22.8-22l117.9 92.6c8 6.3 8.2 18.4 .4 24.9L288 448l38.4 64H448.5c35.5 0 64.2-28.8 64-64.3l-.7-160.2h32z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.home') }}</span>
                </a>
            </li>
            @endcan


            @can('Sales')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" class="side-menu__icon"
                        viewBox="0 0 24 24">
                        <g>
                            <rect fill="none" />
                        </g>
                        <g>
                            <g>
                                <path
                                    d="M21,5c-1.11-0.35-2.33-0.5-3.5-0.5c-1.95,0-4.05,0.4-5.5,1.5c-1.45-1.1-3.55-1.5-5.5-1.5S2.45,4.9,1,6v14.65 c0,0.25,0.25,0.5,0.5,0.5c0.1,0,0.15-0.05,0.25-0.05C3.1,20.45,5.05,20,6.5,20c1.95,0,4.05,0.4,5.5,1.5c1.35-0.85,3.8-1.5,5.5-1.5 c1.65,0,3.35,0.3,4.75,1.05c0.1,0.05,0.15,0.05,0.25,0.05c0.25,0,0.5-0.25,0.5-0.5V6C22.4,5.55,21.75,5.25,21,5z M3,18.5V7 c1.1-0.35,2.3-0.5,3.5-0.5c1.34,0,3.13,0.41,4.5,0.99v11.5C9.63,18.41,7.84,18,6.5,18C5.3,18,4.1,18.15,3,18.5z M21,18.5 c-1.1-0.35-2.3-0.5-3.5-0.5c-1.34,0-3.13,0.41-4.5,0.99V7.49c1.37-0.59,3.16-0.99,4.5-0.99c1.2,0,2.4,0.15,3.5,0.5V18.5z" />
                                <path
                                    d="M11,7.49C9.63,6.91,7.84,6.5,6.5,6.5C5.3,6.5,4.1,6.65,3,7v11.5C4.1,18.15,5.3,18,6.5,18 c1.34,0,3.13,0.41,4.5,0.99V7.49z"
                                    opacity=".3" />
                            </g>
                            <g>
                                <path
                                    d="M17.5,10.5c0.88,0,1.73,0.09,2.5,0.26V9.24C19.21,9.09,18.36,9,17.5,9c-1.28,0-2.46,0.16-3.5,0.47v1.57 C14.99,10.69,16.18,10.5,17.5,10.5z" />
                                <path
                                    d="M17.5,13.16c0.88,0,1.73,0.09,2.5,0.26V11.9c-0.79-0.15-1.64-0.24-2.5-0.24c-1.28,0-2.46,0.16-3.5,0.47v1.57 C14.99,13.36,16.18,13.16,17.5,13.16z" />
                                <path
                                    d="M17.5,15.83c0.88,0,1.73,0.09,2.5,0.26v-1.52c-0.79-0.15-1.64-0.24-2.5-0.24c-1.28,0-2.46,0.16-3.5,0.47v1.57 C14.99,16.02,16.18,15.83,17.5,15.83z" />
                            </g>
                        </g>
                    </svg>

                    <span class="side-menu__label">{{ __('home.sales') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Sales products')
                    <li>
                        <a class="slide-item" href="{{ url('/goToSale') }}">{{ __('home.sales') }}</a>
                    </li>
                    @endcan

                    @can('sales return')
                    <li>
                        <a class="slide-item" href="{{ url('/return_sale') }}">{{ __('home.salesـreturned') }}</a>
                    </li>
                    @endcan

                    @can('Previous sales invoices')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/previousSalesInvoices') }}">{{ __('home.previousSalesInvoices') }}</a>
                    </li>
                    @endcan

                    @can('Pending sales invoices')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/pending_invoice_previes') }}">{{ __('home.pending_invoice_previes') }}</a>
                    </li>
                    @endcan

                    @can('Sent sales invoices')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/previousSales_sended_Invoices') }}">{{ __('home.previousSales_sended_Invoices') }}</a>
                    </li>
                    @endcan

                    @can('Not sent sales invoices')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/previousSales_not_sended_Invoices') }}">{{ __('home.previousSales_not_sended_Invoices') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan


            @can('Product delivery to a customer')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" class="side-menu__icon"
                        viewBox="0 0 24 24">
                        <g>
                            <rect fill="none" />
                        </g>
                        <g>
                            <g>
                                <path
                                    d="M21,5c-1.11-0.35-2.33-0.5-3.5-0.5c-1.95,0-4.05,0.4-5.5,1.5c-1.45-1.1-3.55-1.5-5.5-1.5S2.45,4.9,1,6v14.65 c0,0.25,0.25,0.5,0.5,0.5c0.1,0,0.15-0.05,0.25-0.05C3.1,20.45,5.05,20,6.5,20c1.95,0,4.05,0.4,5.5,1.5c1.35-0.85,3.8-1.5,5.5-1.5 c1.65,0,3.35,0.3,4.75,1.05c0.1,0.05,0.15,0.05,0.25,0.05c0.25,0,0.5-0.25,0.5-0.5V6C22.4,5.55,21.75,5.25,21,5z M3,18.5V7 c1.1-0.35,2.3-0.5,3.5-0.5c1.34,0,3.13,0.41,4.5,0.99v11.5C9.63,18.41,7.84,18,6.5,18C5.3,18,4.1,18.15,3,18.5z M21,18.5 c-1.1-0.35-2.3-0.5-3.5-0.5c-1.34,0-3.13,0.41-4.5,0.99V7.49c1.37-0.59,3.16-0.99,4.5-0.99c1.2,0,2.4,0.15,3.5,0.5V18.5z" />
                                <path
                                    d="M11,7.49C9.63,6.91,7.84,6.5,6.5,6.5C5.3,6.5,4.1,6.65,3,7v11.5C4.1,18.15,5.3,18,6.5,18 c1.34,0,3.13,0.41,4.5,0.99V7.49z"
                                    opacity=".3" />
                            </g>
                            <g>
                                <path
                                    d="M17.5,10.5c0.88,0,1.73,0.09,2.5,0.26V9.24C19.21,9.09,18.36,9,17.5,9c-1.28,0-2.46,0.16-3.5,0.47v1.57 C14.99,10.69,16.18,10.5,17.5,10.5z" />
                                <path
                                    d="M17.5,13.16c0.88,0,1.73,0.09,2.5,0.26V11.9c-0.79-0.15-1.64-0.24-2.5-0.24c-1.28,0-2.46,0.16-3.5,0.47v1.57 C14.99,13.36,16.18,13.16,17.5,13.16z" />
                                <path
                                    d="M17.5,15.83c0.88,0,1.73,0.09,2.5,0.26v-1.52c-0.79-0.15-1.64-0.24-2.5-0.24c-1.28,0-2.46,0.16-3.5,0.47v1.57 C14.99,16.02,16.18,15.83,17.5,15.83z" />
                            </g>
                        </g>
                    </svg>

                    <span class="side-menu__label">{{ __('home.sel_product_withoud_tax') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Deliver product to customer shortcut')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/delivery_product_to_customer') }}">{{ __('home.sel_product_withoud_tax') }}</a>
                    </li>
                    @endcan

                    @can('Previous deliver invoices')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/previous_deliver_Invoices') }}">{{ __('home.previous_deliver_Invoices') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan



            @can('Purchases')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 576 512">
                        <path
                            d="M24 0C10.7 0 0 10.7 0 24S10.7 48 24 48H69.5c3.8 0 7.1 2.7 7.9 6.5l51.6 271c6.5 34 36.2 58.5 70.7 58.5H488c13.3 0 24-10.7 24-24s-10.7-24-24-24H199.7c-11.5 0-21.4-8.2-23.6-19.5L170.7 288H459.2c32.6 0 61.1-21.8 69.5-53.3l41-152.3C576.6 57 557.4 32 531.1 32H360V134.1l23-23c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-64 64c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l23 23V32H120.1C111 12.8 91.6 0 69.5 0H24zM176 512a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm336-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.purchases') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Purchases products')
                    <li>
                        <a class="slide-item" href="{{ url('/purchases') }}">{{ __('home.purchases') }}</a>
                    </li>
                    @endcan

                    @can('purchase return')
                    <li>
                        <a class="slide-item" href="{{ url('/Purchase_returns') }}">{{ __('home.purchase_return') }}</a>
                    </li>
                    @endcan

                    @can('purchase order to resources')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/Purchase_order_of_resources') }}">{{ __('home.Purchase_order_of_resources') }}</a>
                    </li>
                    @endcan

                    @can('Previous purchase invoices')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/previousPurchasesInvoices') }}">{{ __('home.previousPurchasesInvoices') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan



            @can('Products')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 576 512">
                        <path
                            d="M248 0H208c-26.5 0-48 21.5-48 48V160c0 35.3 28.7 64 64 64H352c35.3 0 64-28.7 64-64V48c0-26.5-21.5-48-48-48H328V80c0 8.8-7.2 16-16 16H264c-8.8 0-16-7.2-16-16V0zM64 256c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H224c35.3 0 64-28.7 64-64V320c0-35.3-28.7-64-64-64H184v80c0 8.8-7.2 16-16 16H120c-8.8 0-16-7.2-16-16V256H64zM352 512H512c35.3 0 64-28.7 64-64V320c0-35.3-28.7-64-64-64H472v80c0 8.8-7.2 16-16 16H408c-8.8 0-16-7.2-16-16V256H352c-15 0-28.8 5.1-39.7 13.8c4.9 10.4 7.7 22 7.7 34.2V464c0 12.2-2.8 23.8-7.7 34.2C323.2 506.9 337 512 352 512z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.showallproduct') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Produects')
                    <li>
                        <a class="slide-item" href="{{ url('/showAllProducts') }}">{{ __('home.stock') }}</a>
                    </li>
                    @endcan

                    @can('Stock adjustment')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/stockAdjastment') }}">{{ __('supprocesses.stockadjustment') }}</a>
                    </li>
                    @endcan
              
                    @can('Product data change')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/product_movement') }}">{{ __('supprocesses.product_movement') }}</a>
                    </li>
                    @endcan

                    @can('product damage')
                    <li>
                        <a class="slide-item" href="{{ url('/product_damage') }}">{{ __('home.product damage') }}</a>
                    </li>
                    @endcan
      @can('Stock adjustment')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/upload_stock') }}">{{ __('home.upload_stock') }}</a>
                    </li>
                    @endcan

                </ul>
            </li>
            @endcan

            @can('Transferring a product to another branch')
            <!-- القائمة الرئيسية للمستودعات -->
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 512 512">
                        <path
                            d="M0 32C0 14.3 14.3 0 32 0H480c17.7 0 32 14.3 32 32s-14.3 32-32 32V448c17.7 0 32 14.3 32 32s-14.3 32-32 32H304c0 17.7-14.3 32-32 32s-32-14.3-32-32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32V64C14.3 64 0 49.7 0 32zM96 120c0-13.3 10.7-24 24-24h48c13.3 0 24 10.7 24 24v48c0 13.3-10.7 24-24 24H120c-13.3 0-24-10.7-24-24V120zm0 160c0-13.3 10.7-24 24-24h48c13.3 0 24 10.7 24 24v48c0 13.3-10.7 24-24 24H120c-13.3 0-24-10.7-24-24V280zM288 96h48c13.3 0 24 10.7 24 24v48c0 13.3-10.7 24-24 24H288c-13.3 0-24-10.7-24-24V120c0-13.3 10.7-24 24-24zm24 160h48c13.3 0 24 10.7 24 24v48c0 13.3-10.7 24-24 24H312c-13.3 0-24-10.7-24-24V280c0-13.3 10.7-24 24-24zM144 400H368V352H144v48z" />
                    </svg>
                    <span class="side-menu__label">{{ __('home.warehouses') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">

                    <!-- 1. قسم المستودعات الرئيسية (Type 2) -->
                    @if(auth()->user()->branch && auth()->user()->branch->type == 2)
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="#">
                            <span class="side-menu__label">{{ __('home.main_warehouse') }}</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu">
                            @can('Transferring a product to another branch')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/sendProduct?branch_id=' .auth()->user()->branchs_id) }}">{{ __('home.send_product_from_brance') }}</a>
                            </li>
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/reports/sent-movements?branch_id=' .auth()->user()->branchs_id) }}">{{ __('home.sent_movements_report') }}</a>
                            </li>

                            @endcan
                            @can('Transferring a product to another branch')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/my-drafts-movements?branch_id=' .auth()->user()->branchs_id) }}">{{ __('home.pending_invoice_previes') }}</a>
                            </li>
                            @endcan
                            @can('Receiving a product from another branch')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/reciveProduct?branch_id=' .auth()->user()->branchs_id) }}">{{ __('home.recive_product_from_brance') }}</a>
                            </li>
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/reports/received-movements?branch_id=' .auth()->user()->branchs_id) }}">{{ __('home.received_movements_report') }}</a>
                            </li>
                            @endcan


                            @can('Produects')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/showAllProducts_IN_Wherehouse?branch_id=' .auth()->user()->branchs_id) }}">{{ __('home.stock') }}</a>
                            </li>
                            @endcan




                        </ul>
                    </li>
                    @endif

                    <!-- 2. قسم المستودعات الفرعية (Type 1) -->
                    @php
                    $subBranches = collect();
                    if(auth()->user()->branch && auth()->user()->branch->type == 2) {
                    $subBranches = \App\Models\branchs::where('branch_id', auth()->user()->branch->id)->where('type',
                    1)->get();
                    }
                    @endphp

                    @if(auth()->user()->branch && auth()->user()->branch->type == 2 && $subBranches->count() > 0)
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="#">
                            <span class="side-menu__label">{{ __('home.secandry_warehouses') }}</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu">
                            @foreach($subBranches as $subBranch)
                            <li class="slide">
                                <a class="side-menu__item" data-toggle="slide" href="#">
                                    <span class="side-menu__label">{{ $subBranch->name }}</span>
                                    <i class="angle fe fe-chevron-down"></i>
                                </a>
                                <ul class="slide-menu">
                                    @can('Transferring a product to another branch')
                                    <li>
                                        <a class="slide-item"
                                            href="{{ url('/sendProduct?branch_id=' . $subBranch->id) }}">{{ __('home.send_product_from_brance') }}</a>
                                    </li>
                                    <li>
                                        <a class="slide-item"
                                            href="{{ url('/reports/sent-movements?branch_id=' . $subBranch->id) }}">{{ __('home.sent_movements_report') }}</a>
                                    </li>


                                    @endcan
                                    @can('Transferring a product to another branch')
                                    <li>
                                        <a class="slide-item"
                                            href="{{ url('/my-drafts-movements?branch_id=' . $subBranch->id) }}">{{ __('home.pending_invoice_previes') }}</a>
                                    </li>
                                    @endcan
                                    @can('Receiving a product from another branch')
                                    <li>
                                        <a class="slide-item"
                                            href="{{ url('/reciveProduct?branch_id=' . $subBranch->id) }}">{{ __('home.recive_product_from_brance') }}</a>
                                    </li>
                                    <li>
                                        <a class="slide-item"
                                            href="{{ url('/reports/received-movements?branch_id=' .$subBranch->id) }}">{{ __('home.received_movements_report') }}</a>
                                    </li>
                                    @endcan


                                    @can('Produects')
                                    <li>
                                        <a class="slide-item"
                                            href="{{ url('/showAllProducts_IN_Wherehouse?branch_id=' . $subBranch->id) }}">{{ __('home.stock') }}</a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif

                </ul>
            </li>
            @endcan
            @can('Deliveries')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 576 512">
                        <path
                            d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V285.7l-86.8 86.8c-10.3 10.3-17.5 23.1-21 37.2l-18.7 74.9c-2.3 9.2-1.8 18.8 1.3 27.5H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM549.8 235.7l14.4 14.4c15.6 15.6 15.6 40.9 0 56.6l-29.4 29.4-71-71 29.4-29.4c15.6-15.6 40.9-15.6 56.6 0zM311.9 417L441.1 287.8l71 71L382.9 487.9c-4.1 4.1-9.2 7-14.9 8.4l-60.1 15c-5.5 1.4-11.2-.2-15.2-4.2s-5.6-9.7-4.2-15.2l15-60.1c1.4-5.6 4.3-10.8 8.4-14.9z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.deliverys') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Deliver to another supplier')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/deliver_to_anoter_supplier') }}">{{ __('home.dlivery') }}</a>
                    </li>
                    @endcan

                    @can('Confirm delivery')
                    <li>
                        <a class="slide-item" href="{{ url('/confirmdelivery') }}">{{ __('home.confirmdelivery') }}</a>
                    </li>
                    @endcan

                    @can('Recent delivers')
                    <li>
                        <a class="slide-item" href="{{ url('/recent_delivers') }}">{{ __('home.recent_delivers') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan



            @can('Product manufacturing')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 576 512">
                        <path
                            d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm16 80c0-8.8-7.2-16-16-16s-16 7.2-16 16v48H368c-8.8 0-16 7.2-16 16s7.2 16 16 16h48v48c0 8.8 7.2 16 16 16s16-7.2 16-16V384h48c8.8 0 16-7.2 16-16s-7.2-16-16-16H448V304z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.product_mix') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Product_manufacturing')
                    <li>
                        <a class="slide-item" href="{{ url('/product_mix') }}">{{ __('home.product_mix') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan



            @can('Additions')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 576 512">
                        <path
                            d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm16 80c0-8.8-7.2-16-16-16s-16 7.2-16 16v48H368c-8.8 0-16 7.2-16 16s7.2 16 16 16h48v48c0 8.8 7.2 16 16 16s16-7.2 16-16V384h48c8.8 0 16-7.2 16-16s-7.2-16-16-16H448V304z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.Subprocesses') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Add new product')
                    <li>
                        <a class="slide-item" href="{{ url('/addnewProduct') }}">{{ __('supprocesses.addproduct') }}</a>
                    </li>
                    @endcan

                    @can('Show groups')
                    <li>
                        <a class="slide-item" href="{{ url('/show_groups') }}">{{ __('home.groups') }}</a>
                    </li>
                    @endcan

                    @can('Add a new customer')
                    <li>
                        <a class="slide-item mdi mdi-account"
                            href="{{ url('/addnewcustomer') }}">{{ __('home.addnewcustomer') }}</a>
                    </li>
                    @endcan

                    @can('Add new supplier')
                    <li>
                        <a class="slide-item" href="{{ url('/addnewsupplier') }}">{{ __('home.addnewsupplier') }}</a>
                    </li>
                    @endcan

                    @can('Update customer')
                    <li>
                        <a class="slide-item mdi mdi-account"
                            href="{{ url('/updatecustomer') }}">{{ __('home.updatecustome') }}</a>
                    </li>
                    @endcan

                    @can('Update supplier')
                    <li>
                        <a class="slide-item" href="{{ url('/updatesupplier') }}">{{ __('home.updatesupplier') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan


            @can('Quotations')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 576 512">
                        <path
                            d="M312 24V34.5c6.4 1.2 12.6 2.7 18.2 4.2c12.8 3.4 20.4 16.6 17 29.4s-16.6 20.4-29.4 17c-10.9-2.9-21.1-4.9-30.2-5c-7.3-.1-14.7 1.7-19.4 4.4c-2.1 1.3-3.1 2.4-3.5 3c-.3 .5-.7 1.2-.7 2.8c0 .3 0 .5 0 .6c.2 .2 .9 1.2 3.3 2.6c5.8 3.5 14.4 6.2 27.4 10.1l.9 .3 0 0c11.1 3.3 25.9 7.8 37.9 15.3c13.7 8.6 26.1 22.9 26.4 44.9c.3 22.5-11.4 38.9-26.7 48.5c-6.7 4.1-13.9 7-21.3 8.8V232c0 13.3-10.7 24-24 24s-24-10.7-24-24V220.6c-9.5-2.3-18.2-5.3-25.6-7.8c-2.1-.7-4.1-1.4-6-2c-12.6-4.2-19.4-17.8-15.2-30.4s17.8-19.4 30.4-15.2c2.6 .9 5 1.7 7.3 2.5c13.6 4.6 23.4 7.9 33.9 8.3c8 .3 15.1-1.6 19.2-4.1c1.9-1.2 2.8-2.2 3.2-2.9c.4-.6 .9-1.8 .8-4.1l0-.2c0-1 0-2.1-4-4.6c-5.7-3.6-14.3-6.4-27.1-10.3l-1.9-.6c-10.8-3.2-25-7.5-36.4-14.4c-13.5-8.1-26.5-22-26.6-44.1c-.1-22.9 12.9-38.6 27.7-47.4c6.4-3.8 13.3-6.4 20.2-8.2V24c0-13.3 10.7-24 24-24s24 10.7 24 24zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5H192 32c-17.7 0-32-14.3-32-32V416c0-17.7 14.3-32 32-32H68.8l44.9-36c22.7-18.2 50.9-28 80-28H272h16 64c17.7 0 32 14.3 32 32s-14.3 32-32 32H288 272c-8.8 0-16 7.2-16 16s7.2 16 16 16H392.6l119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384l0 0-.9 0c.3 0 .6 0 .9 0z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.Quotations') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('request price from supplier')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/getproductsprice') }}">{{ __('home.Requestـpricesـofـproducts') }}</a>
                    </li>
                    @endcan

                    @can('delivery notes')
                    <li>
                        <a class="slide-item" href="{{ url('/delivery-notes') }}">{{ __('home.delivery-notes') }}</a>
                    </li>
                    @endcan

                    @can('offer price to customer')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/getproductspricetocustomer') }}">{{ __('home.Offerـpricesـtoـcustomer') }}</a>
                    </li>
                    @endcan

                    @can('Previous quotes')
                    <li>
                        <a class="slide-item" href="{{ url('/PreviousQuotes') }}">{{ __('home.recentquotation') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan




            @can('Accounts')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 384 512">
                        <path
                            d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H64zM96 64H288c17.7 0 32 14.3 32 32v32c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V96c0-17.7 14.3-32 32-32zm32 160a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zM96 352a32 32 0 1 1 0-64 32 32 0 1 1 0 64zM64 416c0-17.7 14.3-32 32-32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H96c-17.7 0-32-14.3-32-32zM192 256a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm32 64a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zm64-64a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm32 64a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zM288 448a32 32 0 1 1 0-64 32 32 0 1 1 0 64z" />
                    </svg>

                    <span class="side-menu__label">{{ __('home.accounting') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Account type')
                    <li>
                        <a class="slide-item" href="{{ url('/account_type') }}">{{ __('home.account_type') }}</a>
                    </li>
                    @endcan

                    @can('enpenses_reason')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/expenses_reason') }}">{{ __('report.enpenses_reason') }}</a>
                    </li>
                    @endcan

                    @can('Opening entry')
                    <li>
                        <a class="slide-item" href="{{ url('/Opening_entry') }}">{{ __('home.Opening_entry') }}</a>
                    </li>
                    @endcan

                    @can('Daily record')
                    <li>
                        <a class="slide-item" href="{{ url('/Daily_record') }}">{{ __('home.Daily_record') }}</a>
                    </li>
                    @endcan

                    @can('Voucher')
                    <li>
                        <a class="slide-item" href="{{ url('/voncher') }}">{{ __('home.voucher') }}</a>
                    </li>
                    @endcan

                    @can('Receipt document')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/reciept_decoument') }}">{{ __('home.Receipt document') }}</a>
                    </li>
                    @endcan

                    @can('Add new account')
                    <li>
                        <a class="slide-item" href="{{ url('/create_acount') }}">{{ __('home.add_new_account') }}</a>
                    </li>
                    @endcan

                    @can('Account tree')
                    <li>
                        <a class="slide-item" href="{{ url('/tree') }}">{{ __('home.tree') }}</a>
                    </li>
                    @endcan

              

              



                    @can('Transfer to main branch')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/Transfertomainbranch') }}">{{ __('home.transferMainBranch') }}</a>
                    </li>
                    @endcan

                    @can('Confirm transfer of master branch')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/confirmTransfertomainbranch') }}">{{ __('home.confirmtransferMainBranch') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan




            {{-- ================= التقارير (الأب) ================= --}}
            @can('Reports')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="fe fe-bar-chart-2 side-menu__icon"></i>
                    <span class="side-menu__label">{{ __('home.reports') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    {{-- ================= الحسابات ================= --}}
                    @can('Accounts Reports Section')
                    <li class="slide">
                        <a href="#" class="slide-item" data-toggle="slide">
                            {{ __('home.accounting') }}
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="slide-menu">

                            @can('Daily transactions sheet')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('budgetsheet') }}">{{ __('home.transction_day') }}</a>
                            </li>
                            @endcan

                            @can('Transfer cash next day')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('TransFerCashTothenNextDay') }}">{{ __('home.Transfer cash to the next day') }}</a>
                            </li>
                            @endcan

                            @can('Credit collection report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('credit_collection') }}">{{ __('report.creditcollection') }}</a>
                            </li>
                            @endcan

                            @can('Supplier credit payment report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Supplier_credit_payment') }}">{{ __('report.Supplier credit payment') }}</a>
                            </li>
                            @endcan

                            @can('Supplier debt restructuring')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Supplier_debt_restructuring') }}">{{ __('home.Supplier_debt_restructuring') }}</a>
                            </li>
                            @endcan

                            @can('Customer debt restructuring')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Customer_debt_restructuring') }}">{{ __('home.Customer_debt_restructuring') }}</a>
                            </li>
                            @endcan

                            @can('Cost center report')
                            <li>
                                <a class="slide-item" href="{{ url('cost_center') }}">{{ __('home.cost_center') }}</a>
                            </li>
                            @endcan

                            @can('Account statement report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('account_statement') }}">{{ __('home.account_statement') }}</a>
                            </li>
                            @endcan

                            @can('Daily record report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Daily_record_report') }}">{{ __('home.Daily_record') }}</a>
                            </li>
                            @endcan

           
                            @can('Transactions to master branch report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('transactionsToMasterBranch') }}">{{ __('home.transactionsToMasterBranch') }}</a>
                            </li>
                            @endcan

                            @can('Expenses report')
                            <li>
                                <a class="slide-item" href="{{ url('Expensesreport') }}">{{ __('report.Expenses') }}</a>
                            </li>
                            @endcan

                            @can('List of customers')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/Customerlist') }}">{{ __('home.customer_supplier_account') }}</a>
                    </li>
                    @endcan

                            @can('VAT report')
                            <li>
                                <a class="slide-item" href="{{ url('VAT') }}">{{ __('report.VAT') }}</a>
                            </li>
                            @endcan
                                  @can('Financial accounts')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/financial_accounts') }}">{{ __('home.Financial_accounts') }}</a>
                    </li>
                    @endcan

                    
                                             @can('Profit and lost report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('profit_and_lost') }}">{{ __('home.profit_and_lost') }}</a>
                            </li>
                            @endcan
                            @can('Financial accounts')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/general_budget') }}">{{ __('home.general_budget') }}</a>
                            </li>
                            @endcan
                            @can('Financial accounts')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/Statement_of_Changes_in_Equity_Report') }}">{{ __('home.Statement_of_Changes_in_Equity_Report') }}</a>
                            </li>
                            @endcan
                            @can('Financial accounts')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('/cashFlowStatement') }}">{{ __('home.cashFlowStatement') }}</a>
                            </li>
                            @endcan



                        </ul>
                    </li>
                    @endcan


                    {{-- ================= المبيعات ================= --}}
                    @can('Sales report')
                    <li class="slide">
                        <a href="#" class="slide-item" data-toggle="slide">
                            {{ __('home.sales') }}
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="slide-menu">
                            @can('Sales product by date report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('sales_product_by_date') }}">{{ __('home.sales_product_by_date') }}</a>
                            </li>
                            @endcan

                            @can('Year sales report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('year_sales_report') }}">{{ __('home.year_sales_report') }}</a>
                            </li>
                            @endcan

                            @can('Sales and return report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('sales_and_return') }}">{{ __('home.sales_and_return') }}</a>
                            </li>
                            @endcan

                            @can('History of product sales report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('salesReport') }}">{{ __('home.Historyـofـproductـsales') }}</a>
                            </li>
                            @endcan

                            @can('Sales return report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('report_returns_sale') }}">{{ __('report.report_returns_sale') }}</a>
                            </li>
                            @endcan

                            @can('Customer purchases report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('customerـpurchases') }}">{{ __('report.customerـpurchases') }}</a>
                            </li>
                            @endcan

                            @can('Purchase product to customer report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('purchasproducttocustomer') }}">{{ __('report.purchasproducttocustomer') }}</a>
                            </li>
                            @endcan

                            @can('Product sales report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('product_sales') }}">{{ __('report.product_sales') }}</a>
                            </li>
                            @endcan

                            @can('Best selling product report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Best_selling_products') }}">{{ __('report.Best selling products') }}</a>
                            </li>
                            @endcan

                            @can('Employee sales report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('employeeـsales') }}">{{ __('report.employeeـsales') }}</a>
                            </li>
                            @endcan

                            @can('Sales profits report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('salesـprofits') }}">{{ __('report.salesـprofits') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan


                    {{-- ================= المشتريات ================= --}}
                    @can('Purchases report section')
                    <li class="slide">
                        <a href="#" class="slide-item" data-toggle="slide">
                            {{ __('home.purchases') }}
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="slide-menu">
                            @can('Purchase product by date report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('purchase_product_by_date') }}">{{ __('home.purchase_product_by_date') }}</a>
                            </li>
                            @endcan
                            @can('Purchase product by date report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('SalesPurchaseInPeriode') }}">{{ __('home.SalesPurchaseInPeriode') }}</a>
                            </li>
                            @endcan
                            @can('Purchases from suppliers report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Purchasesـfromـsuppliers') }}">{{ __('home.purchases') }}</a>
                            </li>
                            @endcan

                            @can('Purchases report detail')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('purchasereports') }}">{{ __('home.purchasereports') }}</a>
                            </li>
                            @endcan

                            @can('Refund of resource purchases report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Refundـofـresourceـpurchases') }}">{{ __('report.Refundـofـresourceـpurchases') }}</a>
                            </li>
                            @endcan

                            @can('Delivery notes report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Delivery_notes') }}">{{ __('report.Delivery_notes') }}</a>
                            </li>
                            @endcan

                            @can('Purchase orders from suppliers report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Requestـoffersـfromـsuppliers') }}">{{ __('report.Requestـoffersـfromـsuppliers') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan


                    {{-- ================= المخزون والمنتجات ================= --}}
                    @can('Inventory Main Section')
                    <li class="slide">
                        <a href="#" class="slide-item" data-toggle="slide">
                            {{ __('home.showallproduct') }}
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="slide-menu">
                            @can('Product sales purchases report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('product_sales_purchases') }}">{{ __('home.product_sales_purchases') }}</a>
                            </li>
                            @endcan

                            @can('Low sell products report')
                            <li>
                                <a class="slide-item" href="{{ url('low_sell') }}">{{ __('home.low_sell') }}</a>
                            </li>
                            @endcan

                            @can('Update stock quantity report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('updatestockquentity') }}">{{ __('home.updatestockquentity') }}</a>
                            </li>
                            @endcan

                            @can('Current stock quantity report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('stockquantity') }}">{{ __('report.stockquantity') }}</a>
                            </li>
                            @endcan

                            @can('Stocktaking report')
                            <li>
                                <a class="slide-item" href="{{ url('Stocktaking') }}">{{ __('home.Stocktaking') }}</a>
                            </li>
                            @endcan

                            @can('Database backup privilege')
                            <li>
                                <a class="slide-item" href="{{ url('our_backup_database') }}"
                                    target="_blank">{{ __('home.backup') }}</a>
                            </li>
                            @endcan

                            @can('Product damage reports')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('ProductsDamageReport') }}">{{ __('home.product damage') }}</a>
                            </li>
                            @endcan

                            @can('Transfer of goods report')
                            <li>
                                <a class="slide-item"
                                    href="{{ url('Transfer_products') }}">{{ __('home.Transfer of goods') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan

                </ul>
            </li>
            @endcan



            {{-- ================= الربط مع الزكاة والدخل ================= --}}
            @can('Zakat Linkage Section')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 576 512"
                        fill="currentColor">
                        <path
                            d="M248 0H208c-26.5 0-48 21.5-48 48V160c0 35.3 28.7 64 64 64H352c35.3 0 64-28.7 64-64V48c0-26.5-21.5-48-48-48H328V80c0 8.8-7.2 16-16 16H264c-8.8 0-16-7.2-16-16V0zM64 256c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H224c35.3 0 64-28.7 64-64V320c0-35.3-28.7-64-64-64H184v80c0 8.8-7.2 16-16 16H120c-8.8 0-16-7.2-16-16V256H64zM352 512H512c35.3 0 64-28.7 64-64V320c0-35.3-28.7-64-64-64H472v80c0 8.8-7.2 16-16 16H408c-8.8 0-16-7.2-16-16V256H352c-15 0-28.8 5.1-39.7 13.8c4.9 10.4 7.7 22 7.7 34.2V464c0 12.2-2.8 23.8-7.7 34.2C323.2 506.9 337 512 352 512z" />
                    </svg>
                    <span class="side-menu__label">{{ __('home.Linkage_with_zakat') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Zakat Onboarding Privilege')
                    <li>
                        <a class="slide-item" href="{{ url('/onbourding') }}">
                            <i class="bx bx-slider-alt" style="margin-left: 5px; margin-right: 5px;"></i>
                            {{ __('home.onbourding') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan





            {{-- ================= المستخدمين والفروع والصلاحيات ================= --}}
            @can('User and branches')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                            d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z" />
                    </svg>
                    <span class="side-menu__label">{{ __('home.users') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('add branch')
                    <li>
                        <a class="slide-item" href="{{ url('/showallBranchs') }}">{{ __('report.allBranches') }}</a>
                    </li>
                    @endcan @can('add branch')
                    <li>
                        <a class="slide-item" href="{{ url('/wherehouse') }}">{{ __('home.wherehouse') }}</a>
                    </li>
                    @endcan

                    @can('List of users')
                    <li>
                        <a class="slide-item" href="{{ url('/users') }}">{{ __('users.usersList') }}</a>
                    </li>
                    @endcan

                    @can('Users permissions')
                    <li>
                        <a class="slide-item" href="{{ url('/roles') }}">{{ __('users.Userـpermissions') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan




            {{-- ================= الموارد البشرية (HR) ================= --}}
            @can('Human Resource')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                            d="M335.5 4l288 160c15.4 8.6 21 28.1 12.4 43.5s-28.1 21-43.5 12.4L320 68.6 47.5 220c-15.4 8.6-34.9 3-43.5-12.4s-3-34.9 12.4-43.5L304.5 4c9.7-5.4 21.4-5.4 31.1 0zM320 160a40 40 0 1 1 0 80 40 40 0 1 1 0-80zM144 256a40 40 0 1 1 0 80 40 40 0 1 1 0-80zm312 40a40 40 0 1 1 80 0 40 40 0 1 1 -80 0zM226.9 491.4L200 441.5V480c0 17.7-14.3 32-32 32H120c-17.7 0-32-14.3-32-32V441.5L61.1 491.4c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l37.9-70.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c16.3 0 31.9 4.5 45.4 12.6l33.6-62.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c32.4 0 62.1 17.8 77.5 46.3l33.6 62.3c13.5-8.1 29.1-12.6 45.4-12.6h19.5c32.4 0 62.1 17.8 77.5 46.3l37.9 70.3c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8L552 441.5V480c0 17.7-14.3 32-32 32H472c-17.7 0-32-14.3-32-32V441.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l36.3-67.5c-1.7-1.7-3.2-3.6-4.3-5.8L376 345.5V400c0 17.7-14.3 32-32 32H296c-17.7 0-32-14.3-32-32V345.5l-26.9 49.9c-1.2 2.2-2.6 4.1-4.3 5.8l36.3 67.5c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8z" />
                    </svg>
                    <span class="side-menu__label">{{ __('home.Human Resource Management') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    @can('Employee')
                    <li>
                        <a class="slide-item" href="{{ url('/allEmployees') }}">{{ __('hr.show_employees') }}</a>
                    </li>
                    @endcan

                    @can('Add new employee')
                    <li>
                        <a class="slide-item" href="{{ url('/createNewEmployee') }}">{{ __('hr.add_new_employee') }}</a>
                    </li>
                    @endcan

                    @can('create a department')
                    <li>
                        <a class="slide-item" href="{{ url('/addnewDepartment') }}">{{ __('hr.createdepartment') }}</a>
                    </li>
                    @endcan

                    @can('Increase or deduction')
                    <li>
                        <a class="slide-item"
                            href="{{ url('/Increaseـor_deduction') }}">{{ __('hr.Increaseـor deductionـforـtheـemployee') }}</a>
                    </li>
                    @endcan

                    @can('Employee loans privilege')
                    <li>
                        <a class="slide-item" href="{{ url('/Loans') }}">{{ __('home.Loans') }}</a>
                    </li>
                    @endcan

                    @can('Salary document')
                    <li>
                        <a class="slide-item" href="{{ url('/salarydecoument') }}">{{ __('hr.salarydecoument') }}</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan





            {{-- ================= الإعدادات العامة ================= --}}
            @can('Settings Section')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                            d="M308.5 135.3c7.1-6.3 9.9-16.2 6.2-25c-2.3-5.3-4.8-10.5-7.6-15.5L304 89.4c-3-5-6.3-9.9-9.8-14.6c-5.7-7.6-15.7-10.1-24.7-7.1l-28.2 9.3c-10.7-8.8-23-16-36.2-20.9L199 27.1c-1.9-9.3-9.1-16.7-18.5-17.8C173.9 8.4 167.2 8 160.4 8h-.7c-6.8 0-13.5 .4-20.1 1.2c-9.4 1.1-16.6 8.6-18.5 17.8L115 56.1c-13.3 5-25.5 12.1-36.2 20.9L50.5 67.8c-9-3-19-.5-24.7 7.1c-3.5 4.7-6.8 9.6-9.9 14.6l-3 5.3c-2.8 5-5.3 10.2-7.6 15.6c-3.7 8.7-.9 18.6 6.2 25l22.2 19.8C32.6 161.9 32 168.9 32 176s.6 14.1 1.7 20.9L11.5 216.7c-7.1 6.3-9.9 16.2-6.2 25c2.3 5.3 4.8 10.5 7.6 15.6l3 5.2c3 5.1 6.3 9.9 9.9 14.6c5.7 7.6 15.7 10.1 24.7 7.1l28.2-9.3c10.7 8.8 23 16 36.2 20.9l6.1 29.1c1.9 9.3 9.1 16.7 18.5 17.8c6.7 .8 13.5 1.2 20.4 1.2s13.7-.4 20.4-1.2c9.4-1.1 16.6-8.6 18.5-17.8l6.1-29.1c13.3-5 25.5-12.1 36.2-20.9l28.2 9.3c9 3 19 .5 24.7-7.1c3.5-4.7 6.8-9.5 9.8-14.6l3.1-5.4c2.8-5 5.3-10.2 7.6-15.5c3.7-8.7 .9-18.6-6.2-25l-22.2-19.8c1.1-6.8 1.7-13.8 1.7-20.9s-.6-14.1-1.7-20.9l22.2-19.8zM112 176a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM504.7 500.5c6.3 7.1 16.2 9.9 25 6.2c5.3-2.3 10.5-4.8 15.5-7.6l5.4-3.1c5-3 9.9-6.3 14.6-9.8c7.6-5.7 10.1-15.7 7.1-24.7l-9.3-28.2c8.8-10.7 16-23 20.9-36.2l29.1-6.1c9.3-1.9 16.7-9.1 17.8-18.5c.8-6.7 1.2-13.5 1.2-20.4s-.4-13.7-1.2-20.4c-1.1-9.4-8.6-16.6-17.8-18.5L583.9 307c-5-13.3-12.1-25.5-20.9-36.2l9.3-28.2c3-9 .5-19-7.1-24.7c-4.7-3.5-9.6-6.8-14.6-9.9l-5.3-3c-5-2.8-10.2-5.3-15.6-7.6c-8.7-3.7-18.6-.9-25 6.2l-19.8 22.2c-6.8-1.1-13.8-1.7-20.9-1.7s-14.1 .6-20.9 1.7l-19.8-22.2c-6.3-7.1-16.2-9.9-25-6.2c-5.3 2.3-10.5 4.8-15.6 7.6l-5.2 3c-5.1 3-9.9 6.3-14.6 9.9c-7.6 5.7-10.1 15.7-7.1 24.7l9.3 28.2c-8.8 10.7-16 23-20.9 36.2L315.1 313c-9.3 1.9-16.7 9.1-17.8 18.5c-.8 6.7-1.2 13.5-1.2 20.4s.4 13.7 1.2 20.4c1.1 9.4 8.6 16.6 17.8 18.5l29.1 6.1c5 13.3 12.1 25.5 20.9 36.2l-9.3 28.2c-3 9-.5 19 7.1 24.7c4.7 3.5 9.5 6.8 14.6 9.8l5.4 3.1c5 2.8 10.2 5.3 15.5 7.6c8.7 3.7 18.6 .9 25-6.2l19.8-22.2c6.8 1.1 13.8 1.7 20.9 1.7s14.1-.6 20.9-1.7l19.8 22.2zM464 304a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                    </svg>
                    <span class="side-menu__label">{{ __('home.setting') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    {{-- الملف الشخصي متاح دائماً لكل مستخدم مسجل دخول --}}
                    <li>
                        <a class="slide-item" href="{{ url('/profile') }}">
                            <i class="bx bx-slider-alt" style="margin-left: 5px; margin-right: 5px;"></i>
                            {{ __('auth.setting') }}
                        </a>
                    </li>

                    @can('AVT Control')
                    <li>
                        <a class="slide-item" href="{{ url('/avt') }}">
                            <i class="bx bx-slider-alt" style="margin-left: 5px; margin-right: 5px;"></i>
                            {{ __('home.AVTSHOW') }}
                        </a>
                    </li>
                    @endcan

                    @can('System setting')
                    <li>
                        <a class="slide-item" href="{{ url('/systemSetting') }}">
                            <i class="bx bx-slider-alt" style="margin-left: 5px; margin-right: 5px;"></i>
                            {{ __('home.systemSetting') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan

            {{-- ================= زر تسجيل الخروج المستقل ================= --}}
            <li class="slide">
                <a class="side-menu__item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bx bx-log-out side-menu__icon"></i>
                    <span class="side-menu__label">{{ __('home.logout') }}</span>
                </a>
            </li>



            {{-- ================= التواصل والدعم الفني ================= --}}
            @can('Technical support')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                            d="M256 48C141.1 48 48 141.1 48 256v40c0 13.3-10.7 24-24 24s-24-10.7-24-24V256C0 114.6 114.6 0 256 0S512 114.6 512 256V400.1c0 48.6-39.4 88-88.1 88L313.6 488c-8.3 14.3-23.8 24-41.6 24H240c-26.5 0-48-21.5-48-48s21.5-48 48-48h32c17.8 0 33.3 9.7 41.6 24l110.4 .1c22.1 0 40-17.9 40-40V256c0-114.9-93.1-208-208-208zM144 208h16c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H144c-35.3 0-64-28.7-64-64V272c0-35.3 28.7-64 64-64zm224 0c35.3 0 64 28.7 64 64v48c0 35.3-28.7 64-64 64H352c-17.7 0-32-14.3-32-32V240c0-17.7 14.3-32 32-32h16z" />
                    </svg>
                    <span class="side-menu__label">{{ __('home.For communication and technical support') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    <li>
                        <a class="slide-item" href="https://ebdeasoft.com/" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-globe ml-2" style="font-size: 11px; opacity: 0.8;"></i>
                            {{ __('home.connectwithebdeasoft') }}
                        </a>
                    </li>

                    <li>
                        <a class="slide-item"
                            href="https://api.whatsapp.com/send/?phone=966534544615&text=%D8%A7%D9%84%D8%B3%D9%84%D8%A7%D9%85+%D8%B9%D9%84%D9%8A%D9%83%D9%85+...+%D8%A3%D8%B1%D8%BA%D8%A8+%D8%A8%D8%AE%D8%AF%D9%85%D8%A9+%D8%AA%D8%B3%D9%88%D9%8A%D9%82+%D8%A7%D9%84%D9%86%D8%B4%D8%A7%D8%B7+%D8%A7%D9%84%D8%AA%D8%AC%D8%A7%D8%B1%D9%8A"
                            target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp ml-2 text-success" style="font-size: 13px;"></i>
                            {{ __('home.whatsappcontact') }}
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
        </ul>
    </div>
</aside>
<!-- main-sidebar -->