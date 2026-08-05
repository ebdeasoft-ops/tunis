@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
<!-- مكتبة Animate.css للحركات الاحترافية -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
<style>
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

/* تأثير تكبير ناعم عند الهوفر على الكاردز والبطاقات */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
}

/* تأخير زمني متدرج للحركات (Stagger) */
.animate-delay-1 { animation-delay: 0.1s; }
.animate-delay-2 { animation-delay: 0.2s; }
.animate-delay-3 { animation-delay: 0.3s; }
.animate-delay-4 { animation-delay: 0.4s; }
.animate-delay-5 { animation-delay: 0.5s; }
</style>
@endsection

@section('title')
{{ __('home.home') }}
@stop

@section('page-header')
<!-- هيدر الصفحة مع حركة النزول للأسفل -->
<div class="breadcrumb-header justify-content-between align-items-center my-4 p-3 bg-white shadow-sm animate__animated animate__fadeInDown" style="border-radius: 15px;">
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

<!-- شريط العمليات السريعة مع حركات انسيابية -->
{{-- <div class="row mb-4 animate__animated animate__fadeInUp animate-delay-1">
    <div class="col-12">
        <div class="card p-3 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="fw-bold text-muted"><i class="fas fa-bolt text-warning me-2"></i> {{ __('home.quick_actions') }}</span>
                <div>
                    <a href="{{ url('/goToSale') }}" class="btn btn-sm btn-primary rounded-pill px-3 me-2">
                        <i class="fas fa-plus me-1"></i> {{ __('home.new_sales_invoice') }}
                    </a>
                    <a href="{{ url('/purchases') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 me-2">
                        <i class="fas fa-cart-plus me-1"></i> {{ __('home.purchase_invoice') }}
                    </a>
                    <a href="{{ url('/addnewcustomer') }}" class="btn btn-sm btn-outline-info rounded-pill px-3 me-2">
                        <i class="fas fa-user-plus me-1"></i> {{ __('home.add_customer') }}
                    </a>
                    <a href="{{ url('/getproductspricetocustomer') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="fas fa-chart-line me-1"></i> {{ __('home.customer_price_quote') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('content')
@can('Home')
<!-- 1. صف البطاقات السريعة (KPIs) مع حركات متتابعة -->
<div class="row row-sm mb-4 animate__animated animate__fadeInUp animate-delay-2">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
        <div class="card bg-primary-gradient text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">{{ __('home.salesdoday') }}</p>
                        <h3 class="mb-0 fw-bold">{{ $todayInvoicesCount }} <small class="fs-6">{{ __('home.invoice') }}</small></h3>
                    </div>
                    <div class="card-icon bg-white-20 rounded-circle p-3">
                        <i class="fas fa-file-invoice fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
        <div class="card bg-success-gradient text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">{{ __('home.TODAYEARNINGS') }}</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($todayEarnings, 2) }} <small class="fs-6">{{ __('home.SAR') }}</small></h3>
                    </div>
                    <div class="card-icon bg-white-20 rounded-circle p-3">
                        <i class="fas fa-coins fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
        <div class="card bg-warning-gradient text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">{{ __('home.purchasesdoday') }}</p>
                        <h3 class="mb-0 fw-bold">{{ $todayPurchasesCount }} <small class="fs-6">{{ __('home.invoice') }}</small></h3>
                    </div>
                    <div class="card-icon bg-white-20 rounded-circle p-3">
                        <i class="fas fa-shopping-cart fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3 mb-xl-0">
        <div class="card bg-danger-gradient text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">{{ __('home.TODAYpurchases') }}</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($todayPurchasesTotal, 2) }} <small class="fs-6">{{ __('home.SAR') }}</small></h3>
                    </div>
                    <div class="card-icon bg-white-20 rounded-circle p-3">
                        <i class="fas fa-wallet fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- صف الرسوم البيانية والإحصائيات (كل رسمة مخصصة بحركة Animation مستقلة) -->
<div class="row row-sm mb-4">

    <!-- 1. الرسم البياني الأول (مقارنة الأداء الشهري) مع حركة خاصة -->
    <div class="col-xl-6 col-lg-12 mb-3 mb-xl-0 animate__animated animate__fadeInLeft animate-delay-3">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                    <i class="fas fa-chart-bar text-primary me-2"></i> {{ __('home.sales_purchases_comparison_current_month') }}
                </h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyOverviewChart" style="max-height: 280px;"></canvas>
            </div>
        </div>
    </div>

    <!-- 2. الرسم البياني الدائري (العملاء والموردين) مع حركة صعود -->
    <div class="col-xl-3 col-lg-6 mb-3 mb-xl-0 animate__animated animate__fadeInUp animate-delay-4">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                    <i class="fas fa-users text-primary me-2"></i> {{ __('home.customers_and_suppliers') }}
                </h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-between align-items-center">
                <div style="height: 180px; width: 100%;">
                    <canvas id="partnersDoughnutChart"></canvas>
                </div>
                <div class="d-flex justify-content-around w-100 mt-2 pt-2 border-top">
                    <div class="text-center">
                        <span class="text-muted fs-11 fw-semibold d-block">{{ __('home.total_customers') }}</span>
                        <span class="fw-bold fs-16 text-primary">
                            <i class="fas fa-user-tag fs-13 me-1"></i> {{ $customersCount }}
                        </span>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center">
                        <span class="text-muted fs-11 fw-semibold d-block">{{ __('home.total_suppliers') }}</span>
                        <span class="fw-bold fs-16 text-warning">
                            <i class="fas fa-truck-loading fs-13 me-1"></i> {{ $suppliersCount }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. صندوق ملخص المرتجعات والتسليم (مع حركة يمين لليسار) -->
    <div class="col-xl-3 col-lg-6 animate__animated animate__fadeInRight animate-delay-4">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="card-title mb-0 fw-bold fs-15 text-dark">
                    <i class="fas fa-box text-primary me-2"></i> {{ __('home.returns_and_delivery') }}
                </h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-around p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="p-2.5 bg-info-transparent rounded-circle me-3 text-center d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="fas fa-truck text-info fs-5"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-12 d-block">{{ __('home.sel_product_withoud_tax') }} ({{ __('home.today') }})</span>
                        <h6 class="mb-0 fw-bold fs-13">{{ $todayDeliveryCount }} {{ __('home.invoice') }} <small class="text-success">({{ number_format($todayDeliveryNet, 2) }})</small></h6>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-2">
                    <div class="p-2.5 bg-danger-transparent rounded-circle me-3 text-center d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="fas fa-undo-alt text-danger fs-5"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-12 d-block">{{ __('home.numberodreturnsSale') }}</span>
                        <h6 class="mb-0 fw-bold fs-13">{{ $uniqueReturnSalesCount }} {{ __('home.invoice') }}</h6>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="p-2.5 bg-warning-transparent rounded-circle me-3 text-center d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="fas fa-box-open text-warning fs-5"></i>
                    </div>
                    <div>
                        <span class="text-muted fs-12 d-block">{{ __('home.numberodreturnsPurchases') }}</span>
                        <h6 class="mb-0 fw-bold fs-13">{{ $resourcePurchasesCount }} {{ __('home.invoice') }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- صف أحدث العمليات والجدول مع حركة ظهور متأخرة -->
<div class="row row-sm mb-4 animate__animated animate__fadeInUp animate-delay-5">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-primary-transparent rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fas fa-receipt text-primary fs-5"></i>
                    </div>
                    <h6 class="card-title mb-0 fw-bold fs-15 text-dark">{{ __('home.latest_transactions_today') }}</h6>
                </div>
                <a href="{{ url('/previousSalesInvoices') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                    {{ __('home.view_all') }} <i class="fas fa-arrow-left ms-1 fs-11"></i>
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr style="background-color: #f8fafc; color: #475569; font-size: 13px;">
                                <th class="py-3 px-3 text-start">{{ __('home.invoice_number') }}</th>
                                <th class="py-3 px-3 text-start">{{ __('home.customer') }}</th>
                                <th class="py-3 px-3">{{ __('home.total_amount') }}</th>
                                <th class="py-3 px-3">{{ __('home.payment_method') }}</th>
                                <th class="py-3 px-3">{{ __('home.status') }}</th>
                                <th class="py-3 px-3 text-end">{{ __('home.time') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestInvoices as $invoice)
                                @php
                                    $totalAmount = ($invoice->cashamount ?? 0) + ($invoice->bankamount ?? 0) + ($invoice->creaditamount ?? 0) + ($invoice->Bank_transfer ?? 0);
                                    if($totalAmount == 0 && isset($invoice->Price)) {
                                        $totalAmount = $invoice->Price;
                                    }
                                    $isReturned = $invoice->returnSales && $invoice->returnSales->isNotEmpty();
                                    if ($isReturned) {
                                        $paymentMethod = __('home.sales_return');
                                        $badgeStyle = 'background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;';
                                    } else {
                                        $payValue = strtolower(trim($invoice->Pay ?? ''));
                                        switch ($payValue) {
                                            case 'cash':
                                                $paymentMethod = __('home.cash');
                                                $badgeStyle = 'background-color: #dcfce7; color: #166534; border: 1px solid #86efac;';
                                                break;
                                            case 'shabka': case 'card': case 'bank': case 'network': case 'span':
                                                $paymentMethod = __('home.card_span');
                                                $badgeStyle = 'background-color: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc;';
                                                break;
                                            case 'bank_transfer': case 'transfer':
                                                $paymentMethod = __('home.bank_transfer');
                                                $badgeStyle = 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde047;';
                                                break;
                                            case 'partition': case 'more': case 'multi':
                                                $paymentMethod = __('home.split_payment');
                                                $badgeStyle = 'background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;';
                                                break;
                                            case 'credit': case 'creadit': case 'dept':
                                                $paymentMethod = __('home.credit');
                                                $badgeStyle = 'background-color: #ffe4e6; color: #be123c; border: 1px solid #fda4af;';
                                                break;
                                            default:
                                                if ($invoice->morepayment_way == 1) {
                                                    $paymentMethod = __('home.split_payment');
                                                    $badgeStyle = 'background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;';
                                                } elseif (($invoice->bankamount ?? 0) > 0) {
                                                    $paymentMethod = __('home.card_span');
                                                    $badgeStyle = 'background-color: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc;';
                                                } elseif (($invoice->Bank_transfer ?? 0) > 0) {
                                                    $paymentMethod = __('home.bank_transfer');
                                                    $badgeStyle = 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde047;';
                                                } elseif (($invoice->creaditamount ?? 0) > 0) {
                                                    $paymentMethod = __('home.credit');
                                                    $badgeStyle = 'background-color: #ffe4e6; color: #be123c; border: 1px solid #fda4af;';
                                                } else {
                                                    $paymentMethod = $invoice->Pay ?? __('home.cash');
                                                    $badgeStyle = 'background-color: #dcfce7; color: #166534; border: 1px solid #86efac;';
                                                }
                                                break;
                                        }
                                    }
                                @endphp
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="py-3 px-3 text-start">
                                        <span class="fw-bold px-2 py-1 rounded" style="background-color: #f1f5f9; color: #334155; font-family: monospace; font-size: 13px;">
                                            #{{ $invoice->invoice_number ?? $invoice->id }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-start fw-semibold text-dark fs-14">
                                        <i class="far fa-user text-muted me-1 fs-12"></i>
                                        {{ $invoice->customer->name ?? $invoice->customer->customer_name ?? __('home.cash_customer') }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="fw-extrabold fs-15 {{ $isReturned ? 'text-danger' : 'text-success' }}" style="letter-spacing: 0.3px;">
                                            {{ $isReturned ? '-' : '' }}{{ number_format($totalAmount, 2) }}
                                            <small class="fs-11 text-muted fw-normal me-1">{{ __('home.SAR') }}</small>
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-3 py-1 rounded-pill fw-bold fs-12 d-inline-block" style="{{ $badgeStyle }}">
                                            {{ $paymentMethod }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        @if($isReturned)
                                            <span class="badge bg-danger-transparent text-danger px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                                                <i class="fas fa-undo me-1"></i> {{ __('home.returned') }}
                                            </span>
                                        @elseif($invoice->status == 1 || $invoice->save == 1)
                                            <span class="badge bg-success-transparent text-success px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                                                <i class="fas fa-check-circle me-1"></i> {{ __('home.completed') }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning-transparent text-warning px-2.5 py-1.5 rounded-pill fw-semibold fs-11">
                                                <i class="fas fa-clock me-1"></i> {{ __('home.pending') }}
                                                </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-end text-muted fs-12 fw-medium">
                                        <i class="far fa-clock me-1 text-light-gray"></i>
                                        {{ $invoice->created_at ? $invoice->created_at->diffForHumans() : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="mb-2">
                                            <i class="fas fa-inbox text-light fs-1"></i>
                                        </div>
                                        <p class="mb-0 fw-semibold">{{ __('home.no_transactions_today') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const ctxPartners = document.getElementById('partnersDoughnutChart').getContext('2d');

    new Chart(ctxPartners, {
        type: 'doughnut',
        data: {
            labels: ['العملاء', 'الموردين'],
            datasets: [{
                data: [{{ $customersCount ?? 0 }}, {{ $suppliersCount ?? 0 }}],
                backgroundColor: [
                    '#3b82f6', // أزرق مميز للعملاء
                    '#f59e0b'  // برتقالي/أصفر للموردين
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: 'inherit', size: 12 },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.label + ': ' + context.raw + ' جهة';
                        }
                    }
                }
            },
            cutout: '70%' // تجويف من المنتصف ليعطي شكل Doughnut مودرن
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('monthlyOverviewChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                '{{ __('home.total_sales') }}',
                '{{ __('home.tax_free_deliveries') }}',
                '{{ __('home.total_purchases') }}'
            ],
            datasets: [{
                label: '{{ __('home.amount_sar') }}',
                data: [
                    {{ $monthSales ?? 0 }},
                    {{ $monthDeliverySales ?? 0 }},
                    {{ $monthPurchasesTotal ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.85)',
                    'rgba(23, 162, 184, 0.85)',
                    'rgba(220, 53, 69, 0.85)'
                ],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' ر.س';
                        }
                    },
                    grid: { color: '#f0f0f0' }
                },
                x: { grid: { display: false } }
            }
        }
    });
});

// --- دالة جلب الفواتير المعلقة عبر AJAX ---
function fetchPendingInvoices() {
    const apiUrl = "/get-pending-invoices";

    $.ajax({
        url: apiUrl,
        type: "GET",
        dataType: "json",
        success: function(response) {
            let invoiceList = $('#invoice_list');
            if(invoiceList.length) {
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
            }
        },
        error: function(xhr, status, error) {
            console.error("خطأ أثناء جلب الفواتير المعلقة:", error);
        }
    });
}

// --- دالة تحديث الوقت والتاريخ تلقائياً ---
function updateDateTime() {
    var now = new Date();

    var dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    var currentDate = now.toLocaleDateString('ar-SA', dateOptions);

    var timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
    var currentTime = now.toLocaleTimeString('ar-SA', timeOptions);

    if (document.getElementById('display_date')) {
        document.getElementById('display_date').innerHTML = currentDate;
    }
    if (document.getElementById('display_time')) {
        document.getElementById('display_time').innerHTML = currentTime;
    }

    setTimeout(updateDateTime, 1000);
}

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
@endsection
