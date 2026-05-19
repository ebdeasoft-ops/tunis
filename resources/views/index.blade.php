@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
<style>
/* --- الحاوية الأساسية وشبكة الكروت --- */
.ag-format-container {
    width: 100%;
    padding: 20px;
}

.ag-courses_box {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
}

.ag-courses_item {
    flex-basis: calc(33.33333% - 30px);
    margin: 0 15px 30px;
    overflow: hidden;
    border-radius: 20px;
    background-color: #f8f9fa;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.ag-courses_item:hover {
    transform: translateY(-5px);
}

.ag-courses-item_link {
    display: block;
    padding: 30px 20px;
    background-color: #122147;
    overflow: hidden;
    position: relative;
    text-decoration: none !important;
    height: 100%;
}

/* --- دائرة الخلفية وتأثيرات الألوان عند المرور (Hover) --- */
.ag-courses-item_bg {
    height: 128px;
    width: 128px;
    background-color: #f9b234;
    z-index: 1;
    position: absolute;
    top: -75px;
    right: -75px;
    border-radius: 50%;
    transition: all .5s ease;
}

.ag-courses_item:nth-child(2n) .ag-courses-item_bg { background-color: #3ecd5e; }
.ag-courses_item:nth-child(3n) .ag-courses-item_bg { background-color: #e44002; }
.ag-courses_item:nth-child(4n) .ag-courses-item_bg { background-color: #952aff; }

.ag-courses-item_link:hover .ag-courses-item_bg {
    transform: scale(10);
}

/* --- النصوص والأيقونات داخل الكروت --- */
.ag-courses-item_title {
    min-height: 50px;
    margin: 0 0 25px;
    overflow: hidden;
    font-weight: bold;
    font-size: 18px;
    color: #FFF;
    z-index: 2;
    position: relative;
}

.ag-courses-item_date-box {
    font-size: 22px;
    color: #FFF;
    z-index: 2;
    position: relative;
    display: flex;
    align-items: center;
}

.svg-icon {
    width: 24px;
    height: 24px;
    margin-left: 10px; /* متوافق مع الاتجاه العربي */
    fill: #f9b234;
}

/* --- شاشات الموبايل والتابلت (Responsive) --- */
@media only screen and (max-width: 979px) {
    .ag-courses_item {
        flex-basis: calc(50% - 30px);
    }
}

@media only screen and (max-width: 639px) {
    .ag-courses_item {
        flex-basis: 100%;
    }
}

/* --- أيقونة وقائمة الإشعارات --- */
.nav-link.position-relative {
    display: inline-flex !important;
    padding: 8px !important;
}

.custom-badge {
    position: absolute !important;
    top: -2px;
    right: -2px;
    background-color: #ff4d4f;
    color: white;
    font-size: 11px;
    font-weight: bold;
    min-width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-menu {
    border: none !important;
    border-radius: 10px !important;
    overflow: hidden;
    width: 250px !important;
}

.main-notification-list {
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endsection

@section('title')
{{ __('home.home') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between align-items-center my-4 p-3 bg-white shadow-sm" style="border-radius: 15px;">
    <div class="left-content {{ App::getLocale() == 'en' ? 'text-left' : 'text-right' }}">
        <h2 class="main-content-title tx-24 mg-b-1 welcoming font-weight-bold" style="color: #004d44;">
            {{ __('home.welcome') }}
            <span class="text-dark">{{ Auth::user()->name }}</span> !
        </h2>
    </div>
    
    <div class="dropdown nav-item main-header-notification"></div>
    
    <div class="main-dashboard-header-right">
        <div class="datetime-wrapper d-flex align-items-center px-3 py-2" style="background: #f0f4f4; border-radius: 12px; border: 1px solid #e0e6e6;">
            <div class="ml-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #004d44; border-radius: 10px; color: white;">
                <i class="far fa-calendar-alt" style="font-size: 18px;"></i>
            </div>
            <div class="text-right">
                <div id="display_date" class="font-weight-bold" style="font-size: 14px; color: #004d44;"></div>
                <div id="display_time" class="text-muted" style="font-size: 12px; font-weight: 600;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
@can('Home')
<div class="row row-sm parent-card" style="background-color: white;">
    <div class="ag-format-container">
        <div class="ag-courses_box">
            
            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.salesdoday') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M16.588,3.411h-4.466c0.042-0.116,0.074-0.236,0.074-0.366c0-0.606-0.492-1.098-1.099-1.098H8.901c-0.607,0-1.098,0.492-1.098,1.098c0,0.13,0.033,0.25,0.074,0.366H3.41c-0.606,0-1.098,0.492-1.098,1.098c0,0.607,0.492,1.098,1.098,1.098h0.366V16.59c0,0.808,0.655,1.464,1.464,1.464h9.517c0.809,0,1.466-0.656,1.466-1.464V5.607h0.364c0.607,0,1.1-0.491,1.1-1.098C17.688,3.903,17.195,3.411,16.588,3.411z M8.901,2.679h2.196c0.202,0,0.366,0.164,0.366,0.366S11.3,3.411,11.098,3.411H8.901c-0.203,0-0.366-0.164-0.366-0.366S8.699,2.679,8.901,2.679z M15.491,16.59c0,0.405-0.329,0.731-0.733,0.731H5.241c-0.404,0-0.732-0.326-0.732-0.731V5.607h10.983V16.59z M16.588,4.875H3.41c-0.203,0-0.366-0.164-0.366-0.366S3.208,4.143,3.41,4.143h13.178c0.202,0,0.367,0.164,0.367,0.366S16.79,4.875,16.588,4.875zM6.705,14.027h6.589c0.202,0,0.366-0.164,0.366-0.366s-0.164-0.367-0.366-0.367H6.705c-0.203,0-0.366,0.165-0.366,0.367S6.502,14.027,6.705,14.027z M6.705,11.83h6.589c0.202,0,0.366-0.164,0.366-0.365c0-0.203-0.164-0.367-0.366-0.367H6.705c-0.203,0-0.366,0.164-0.366,0.367C6.339,11.666,6.502,11.83,6.705,11.83z M6.705,9.634h6.589c0.202,0,0.366-0.164,0.366-0.366c0-0.202-0.164-0.366-0.366-0.366H6.705c-0.203,0-0.366,0.164-0.366,0.366C6.339,9.47,6.502,9.634,6.705,9.634z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $todayInvoicesCount }} {{ __('home.invoice') }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.TODAYEARNINGS') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M10.862,6.47H3.968v6.032h6.894V6.47z M10,11.641H4.83V7.332H10V11.641z M12.585,11.641h-0.861v0.861h0.861V11.641z M7.415,14.226h0.862v-0.862H7.415V14.226z M8.707,17.673h2.586c0.237,0,0.431-0.193,0.431-0.432c0-0.237-0.193-0.431-0.431-0.431H8.707c-0.237,0-0.431,0.193-0.431,0.431C8.276,17.479,8.47,17.673,8.707,17.673 M5.691,14.226h0.861v-0.862H5.691V14.226z M4.83,13.363H3.968v0.862H4.83V13.363z M16.895,4.746h-3.017V3.023h1.292c0.476,0,0.862-0.386,0.862-0.862V1.299c0-0.476-0.387-0.862-0.862-0.862H10c-0.476,0-0.862,0.386-0.862,0.862v0.862c0,0.476,0.386,0.862,0.862,0.862h1.293v1.723H3.106c-0.476,0-0.862,0.386-0.862,0.862v12.926c0,0.476,0.386,0.862,0.862,0.862h13.789c0.475,0,0.861-0.387,0.861-0.862V5.608C17.756,5.132,17.369,4.746,16.895,4.746 M10.862,2.161H10V1.299h0.862V2.161zM11.724,1.299h3.446v0.862h-3.446V1.299z M13.016,4.746h-0.861V3.023h0.861V4.746z M16.895,18.534H3.106v-2.585h13.789V18.534zM16.895,15.088H3.106v-9.48h13.789V15.088z M15.17,12.502h0.862v-0.861H15.17V12.502z M13.447,12.502h0.861v-0.861h-0.861V12.502zM15.17,10.778h0.862V9.917H15.17V10.778z M15.17,9.055h0.862V8.193H15.17V9.055z M16.032,6.47h-4.309v0.862h4.309V6.47zM14.309,8.193h-0.861v0.862h0.861V8.193z M12.585,8.193h-0.861v0.862h0.861V8.193z M13.447,14.226h2.585v-0.862h-2.585V14.226zM13.447,10.778h0.861V9.917h-0.861V10.778z M12.585,9.917h-0.861v0.861h0.861V9.917z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $todayEarnings }} {{ __('home.SAR') }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.PRODUCT_number_SOLD') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156 M5.872,17.019h-2.89v-3.117h2.89V17.019zM9.587,17.019h-2.89V7.1h2.89V17.019z M13.303,17.019h-2.89v-6.651h2.89V17.019z M17.019,17.019h-2.891V2.982h2.891V17.019z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $monthInvoicesCount }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.TOTAL_EARNINGS_Month') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                             <path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $monthSales }} {{ __('home.SAR') }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.sel_product_withoud_tax') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                             <path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $todayDeliveryCount }} {{ __('home.invoice') }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.TODAYEARNINGS') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                             <path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $todayDeliveryNet }} {{ __('home.SAR') }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.delivery_current_month') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                             <path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $monthDeliveryCount }} {{ __('home.invoice') }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.TOTAL_EARNINGS_Month') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                             <path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $monthDeliverySales }} {{ __('home.SAR') }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.purchasesdoday') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M18.64,8.279H1.423c-0.475,0-0.861,0.385-0.861,0.86V10c0,0.476,0.386,0.861,0.861,0.861h0.172l1.562,7.421l0.008-0.002c0.047,0.187,0.208,0.328,0.41,0.328h12.912c0.201,0,0.362-0.142,0.409-0.328l0.009,0.002l1.562-7.421h0.173c0.475,0,0.86-0.386,0.86-0.861V9.139C19.5,8.664,19.114,8.279,18.64,8.279 M2.475,10.861h2.958l0.271,1.721H2.837L2.475,10.861z M3.018,13.443h2.823l0.271,1.722H3.38L3.018,13.443z M3.924,17.747l-0.362-1.722h2.687l0.271,1.722H3.924z M9.601,17.747H7.38l-0.271-1.722h2.491V17.747z M9.601,15.165H6.973l-0.271-1.722h2.899V15.165z M9.601,12.582H6.565l-0.271-1.721h3.307V12.582z M12.682,17.747h-2.22v-1.722h2.491L12.682,17.747z M13.09,15.165h-2.628v-1.722h2.899L13.09,15.165z M10.462,12.582v-1.721h3.307l-0.271,1.721H10.462z M16.139,17.747h-2.596l0.271-1.722H16.5L16.139,17.747z M16.683,15.165H13.95l0.271-1.722h2.823L16.683,15.165z M17.226,12.582h-2.867l0.271-1.721h2.958L17.226,12.582z M18.64,10H1.423V9.139H18.64V10z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $todayPurchasesCount }} {{__('home.invoice')}}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.TODAYpurchases') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M6.506,6.98c-0.469,0-0.85,0.381-0.85,0.85s0.381,0.85,0.85,0.85s0.85-0.381,0.85-0.85S6.975,6.98,6.506,6.98z M18.684,4.148h-3.398V0.75H5.656v3.398H1.691c-0.313,0-0.566,0.253-0.566,0.566V14.91c0,0.312,0.253,0.566,0.566,0.566h3.965 v3.398h9.629v-3.398h3.398c0.313,0,0.566-0.254,0.566-0.566V4.714C19.25,4.401,18.997,4.148,18.684,4.148z M6.789,1.882h7.363 v2.266H6.789V1.882z M14.152,17.742H6.789v-5.664h7.363V17.742z M18.117,13.777c0,0.312-0.254,0.566-0.566,0.566h-2.266v-3.399 H5.656v3.399H2.824c-0.313,0-0.566-0.254-0.566-0.566v-7.93c0-0.313,0.253-0.566,0.566-0.566h14.727 c0.312,0,0.566,0.253,0.566,0.566V13.777z M3.674,6.98c-0.469,0-0.85,0.381-0.85,0.85s0.381,0.85,0.85,0.85s0.85-0.381,0.85-0.85 S4.143,6.98,3.674,6.98z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $todayPurchasesTotal }} {{__('home.SAR')}}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.purchases_number_SOLD') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156 M5.872,17.019h-2.89v-3.117h2.89V17.019zM9.587,17.019h-2.89V7.1h2.89V17.019z M13.303,17.019h-2.89v-6.651h2.89V17.019z M17.019,17.019h-2.891V2.982h2.891V17.019z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $monthPurchasesCount }} {{__('home.invoice')}}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.TOTAL_purchases_Month') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M14.781,14.347h1.738c0.24,0,0.436-0.194,0.436-0.435v-1.739c0-0.239-0.195-0.435-0.436-0.435h-1.738c-0.239,0-0.435,0.195-0.435,0.435v1.739C14.347,14.152,14.542,14.347,14.781,14.347 M18.693,3.045H1.307c-0.48,0-0.869,0.39-0.869,0.869v12.17c0,0.479,0.389,0.869,0.869,0.869h17.387c0.479,0,0.869-0.39,0.869-0.869V3.915C19.562,3.435,19.173,3.045,18.693,3.045 M18.693,16.085H1.307V9.13h17.387V16.085z M18.693,5.653H1.307V3.915h17.387V5.653zM3.48,12.608h7.824c0.24,0,0.435-0.195,0.435-0.436c0-0.239-0.194-0.435-0.435-0.435H3.48c-0.24,0-0.435,0.195-0.435,0.435C3.045,12.413,3.24,12.608,3.48,12.608 M3.48,14.347h6.085c0.24,0,0.435-0.194,0.435-0.435s-0.195-0.435-0.435-0.435H3.48c-0.24,0-0.435,0.194-0.435,0.435S3.24,14.347,3.48,14.347"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $monthPurchasesTotal }}{{__('home.SAR')}}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.credit_supplier_amount') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M18.933,3.163h-3.309V1.686c0-0.392-0.316-0.706-0.706-0.706H5.061c-0.39,0-0.706,0.314-0.706,0.706v1.477H1.067c-0.39,0-0.706,0.317-0.706,0.706v7.997c0,0.391,0.316,0.706,0.706,0.706h3.301v5.743c0,0.39,0.316,0.706,0.706,0.706h7.801c0.192,0,0.373-0.077,0.507-0.215l2.054-2.121c0.127-0.131,0.198-0.306,0.198-0.491v-3.622h3.3c0.39,0,0.706-0.315,0.706-0.706V3.869C19.638,3.481,19.323,3.163,18.933,3.163z M14.222,15.908l-1.647,1.701H5.779v-5.037h8.443V15.908z M18.227,11.16h-3.309V4.575h3.309V11.16z"></path>
                        </svg>
                        <span class="ag-courses-item_date">-</span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.creadit_customer_amount') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M17.222,5.041l-4.443-4.414c-0.152-0.151-0.356-0.235-0.571-0.235h-8.86c-0.444,0-0.807,0.361-0.807,0.808v17.602c0,0.448,0.363,0.808,0.807,0.808h13.303c0.448,0,0.808-0.36,0.808-0.808V5.615C17.459,5.399,17.373,5.192,17.222,5.041zM15.843,17.993H4.157V2.007h7.72l3.966,3.942V17.993z"></path>
                        </svg>
                        <span class="ag-courses-item_date">-</span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.numberodreturnsSale') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M17.222,5.041l-4.443-4.414c-0.152-0.151-0.356-0.235-0.571-0.235h-8.86c-0.444,0-0.807,0.361-0.807,0.808v17.602c0,0.448,0.363,0.808,0.807,0.808h13.303c0.448,0,0.808-0.36,0.808-0.808V5.615C17.459,5.399,17.373,5.192,17.222,5.041zM15.843,17.993H4.157V2.007h7.72l3.966,3.942V17.993z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $uniqueReturnSalesCount }} {{__('home.invoice')}}
                        </span>
                    </div>
                </a>
            </div>

            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>
                    <div class="ag-courses-item_title">
                        {{ __('home.numberodreturnsPurchases') }}
                    </div>
                    <div class="ag-courses-item_date-box">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M17.222,5.041l-4.443-4.414c-0.152-0.151-0.356-0.235-0.571-0.235h-8.86c-0.444,0-0.807,0.361-0.807,0.808v17.602c0,0.448,0.363,0.808,0.807,0.808h13.303c0.448,0,0.808-0.36,0.808-0.808V5.615C17.459,5.399,17.373,5.192,17.222,5.041zM15.843,17.993H4.157V2.007h7.72l3.966,3.942V17.993z"></path>
                        </svg>
                        <span class="ag-courses-item_date">
                            {{ $resourcePurchasesCount }} {{__('home.invoice')}}
                        </span>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>
@endcan
@endsection

@section('js')


<script>
    
// --- أولاً: دالة جلب الفواتير المعلقة عبر AJAX ---
function fetchPendingInvoices() {
    const apiUrl = "/get-pending-invoices";

    $.ajax({
        url: apiUrl,
        type: "GET",
        dataType: "json",
        success: function(response) {
            let invoiceList = $('#invoice_list');
            invoiceList.empty();
            let invoices = Array.isArray(response) ? response : [];
            $('#invoice_count').text(invoices.length);

            if (invoices.length > 0) {
                invoices.forEach(function(invoice) {
                    invoiceList.append(`
                        <a class="d-flex p-3 border-bottom" href="{{ url('purchase_details') }}/${invoice.id}">
                            <div class="mr-3 ml-3">
                                <h5 class="notification-label mb-1">رقم الفاتورة: ${invoice.id}</h5>
                            </div>
                        </a>
                    `);
                });
            } else {
                invoiceList.html('<p class="text-center p-3 text-muted">لا توجد فواتير معلقة</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error("خطأ أثناء جلب الفواتير المعلقة:", error);
        }
    });
}

// --- ثانياً: دالة تحديث الوقت والتاريخ تلقائياً ---
function updateDateTime() {
    var now = new Date();
    
    var dateOptions = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    var currentDate = now.toLocaleDateString('ar-SA', dateOptions);

    var timeOptions = {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    };
    var currentTime = now.toLocaleTimeString('ar-SA', timeOptions);

    if (document.getElementById('display_date')) {
        document.getElementById('display_date').innerHTML = currentDate;
    }
    if (document.getElementById('display_time')) {
        document.getElementById('display_time').innerHTML = currentTime;
    }

    setTimeout(updateDateTime, 1000);
}

// --- ثالثاً: عند تحميل مستند الصفحة بالكامل (Document Ready) ---
$(document).ready(function() {
    updateDateTime();
    fetchPendingInvoices();

    setInterval(fetchPendingInvoices, 120000);

    $('#invoice-no-btn').on('click', function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).next('.dropdown-menu').toggleClass('show');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.main-header-notification').length) {
            $('.main-header-notification').removeClass('show');
            $('.dropdown-menu').removeClass('show');
        }
    });
});
</script>

<script>
// --- رابعاً: سكريبت بناء الرسم البياني (Chart.js) ---
const ctx = document.getElementById('myChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
</script>
@endsection