<?php

namespace App\Http\Controllers;

use App\Models\ProductsDamage;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProductsDamageController extends Controller
{
    public function __construct()
    {
        // توحيد اللغة على مستوى الـ Controller بالكامل
        app()->setLocale(LaravelLocalization::getCurrentLocale());
    }

    /**
     * عرض صفحة التقرير مع تفعيل الفلترة الديناميكية
     */
    public function index(Request $request)
    {
        // 1. التحقق من صحة البيانات القادمة (اختياري ولكنه احترافي لمنع الأخطاء)
        $request->validate([
            'start_at'   => 'nullable|date',
            'end_at'     => 'nullable|date',
            'branch'     => 'nullable|string',
            'productNo'  => 'nullable|string',
        ]);

        // 2. بناء الاستعلام مع دعم الـ Eager Loading (لتفادي الـ N+1 Queries في جدول العرض)
        // افترضنا هنا أن العلاقات في موديل ProductsDamage هي 'product' و 'branch'
        $query = ProductsDamage::with(['product', 'branch']);

        // 3. فلترة التاريخ (إذا تم إرسال تواريخ البداية والنهاية)
        if ($request->filled('start_at') && $request->filled('end_at')) {
            $query->whereDate('created_at', '>=', $request->start_at)
                  ->whereDate('created_at', '<=', $request->end_at);
        }

        // 4. فلترة الفرع ديناميكياً (يتم تطبيق الشرط فقط إذا لم يكن اختيار الفرع هو الكل '-')
        $query->when($request->filled('branch') && $request->branch !== '-', function ($q) use ($request) {
            return $q->where('branchs_id', $request->branch);
        });

        // 5. فلترة المنتج ديناميكياً (يتم تطبيق الشرط فقط إذا لم يكن اختيار المنتج هو الكل '-')
        $query->when($request->filled('productNo') && $request->productNo !== '-', function ($q) use ($request) {
            return $q->where('product_id', $request->productNo);
        });

        // 6. جلب البيانات (يمكنك تحويلها إلى ->paginate(50) إذا كانت كمية البيانات ضخمة في الـ ERP)
        $products = $query->orderBy('created_at', 'desc')->get();

        // 7. إرجاع البيانات لنفس الصفحة النظيفة
        return view('reports.product_damage', compact('products'));
    }

    // تفريغ الدوال غير المستخدمة أو الإبقاء عليها قياسية حسب معايير الـ Resource
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit(ProductsDamage $productsDamage) {}
    public function update(Request $request, ProductsDamage $productsDamage) {}
    public function destroy(ProductsDamage $productsDamage) {}
}