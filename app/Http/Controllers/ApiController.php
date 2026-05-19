<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class ApiController extends Controller
{
    // البحث البسيط عبر الـ API
    public function search(Request $request)
    {
        $searchText = $request->input('text');

        if (!$searchText) {
            return response()->json(['message' => 'يرجى إدخال نص للبحث'], 400);
        }

        // تجميع شروط البحث لحماية الاستعلام
        $products = products::where(function ($query) use ($searchText) {
            $query->where('product_name', 'LIKE', '%' . $searchText . '%')
                  ->orWhere('Product_Code', 'LIKE', '%' . $searchText . '%');
        })->get();

        return response()->json($products);
    }

    // البحث المتقدم مع الفلترة بالفرع والـ Pagination
    public function searchAllproductpaginatenew_by_post(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $searchTerm = '%' . $request->searchtext . '%';

        if ($request->branchs_id == '-') {
            // البحث في كل الفروع
            $data = products::where(function ($query) use ($searchTerm) {
                $query->where('product_name', 'LIKE', $searchTerm)
                      ->orWhere('Product_Code', 'LIKE', $searchTerm)
                      ->orWhere('notes', 'LIKE', $searchTerm)
                      ->orWhere('refnumber', 'LIKE', $searchTerm);
            })->paginate(20);
        } else {
            // البحث داخل فرع محدد حصراً (تم إصلاح تداخل الـ orWhere)
            $data = products::where('branchs_id', $request->branchs_id)
                ->where(function ($query) use ($searchTerm) {
                    $query->where('product_name', 'LIKE', $searchTerm)
                          ->orWhere('Product_Code', 'LIKE', $searchTerm)
                          ->orWhere('notes', 'LIKE', $searchTerm)
                          ->orWhere('refnumber', 'LIKE', $searchTerm);
                })->paginate(20);
        }

        return view('ajax_search', compact('data'));
    }

    // جلب المنتجات حسب المجموعة مع البحث بالاسم أو الكود
    public function product_group_ajax(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $searchtext = $request->searchtext;
        $groupId = $request->group_id;

        if ($searchtext == '') {
            // جلب كل منتجات المجموعة بدون بحث نسيجي
            $data = products::where('product_group', $groupId)->paginate(20);
        } else {
            // جلب منتجات المجموعة المحددة حصراً التي تطابق نص البحث
            $data = products::where('product_group', $groupId)
                ->where(function ($query) use ($searchtext) {
                    $query->where('product_name', 'LIKE', '%' . $searchtext . '%')
                          ->orWhere('Product_Code', 'LIKE', '%' . $searchtext . '%');
                })->paginate(20);
        }

        return view('ajax_search', compact('data'));
    }
}