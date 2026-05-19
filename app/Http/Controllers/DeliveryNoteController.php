<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryNoteController extends Controller
{

   public function index()
    {
        $notes = DeliveryNote::with(['product', 'customer'])->orderBy('id', 'desc')->get();
        $customers = customers::all(); // جلب العملاء للقائمة المنسدلة
        $nextCode = (DeliveryNote::max('code') ?? 1000) + 1;

        return view('delivery_notes.index', compact('notes', 'customers','nextCode'));
    }

  public function store(Request $request)
{
    $noteId = $request->note_id;

    // 1. التحقق من البيانات الأساسية والأصناف
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'items'       => 'required|array|min:1', // التأكد من وجود صنف واحد على الأقل
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity'   => 'required|integer|min:1',
    ], [
        'code.unique' => __('home.code_already_exists'),
        'items.required' => __('home.please_add_at_least_one_item'),
    ]);

    try {
        return DB::transaction(function () use ($request, $noteId) {

            // 2. حفظ أو تحديث رأس إذن التسليم (Main Note)
            $deliveryNote = DeliveryNote::updateOrCreate(
                ['id' => $noteId],
                [
                    'customer_id' => $request->customer_id,
                    'code'        => $request->code,
                    // 'user_id' => auth()->id(), // إذا كنت تريد تسجيل من قام بالعملية
                ]
            );

            // 3. التعامل مع الأصناف (Items)
            // في حالة التعديل: نمسح الأصناف القديمة ونضيف الجديدة (الطريقة الأسهل والأضمن)
            if ($noteId) {
                $deliveryNote->items()->delete();
            }

            // 4. إضافة الأصناف الجديدة من المصفوفة المرسلة عبر Ajax
            foreach ($request->items as $item) {
                $deliveryNote->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => __('home.delivery_note_saved_success')
            ]);
        });
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => __('home.error_occurred') . ': ' . $e->getMessage()
        ], 500);
    }
}

    public function searchProducts(Request $request)
    {
        $search = $request->get('q');

        // جلب المنتجات التي تحتوي على نص البحث (بحد أقصى 15 نتيجة للسرعة)
        $products = products::where('product_name', 'LIKE', "%$search%")
            ->orWhere('Product_Code', 'LIKE', "%$search%") // يفضل البحث بالـ Product_Code بدل الـ ID
            ->limit(15)
            ->get();

        // تنسيق البيانات لتناسب Select2
        $response = [];
        foreach($products as $product){
            $response[] = [
                "id"           => $product->id, // القيمة البرمجية للحفظ
                "text"         => $product->product_name, // النص الذي يظهر في قائمة البحث
                "product_code" => $product->Product_Code  // <--- هذا الحقل هو السر! سيسحبه الجافاسكريبت
            ];
        }

        return response()->json($response);
    }
    public function getCustomers(Request $request)
    {
        $search = $request->search;

        $customers = customers::select('id', 'name')
            ->where('name', 'LIKE', "%$search%")
            ->limit(10) // جلب أول 10 نتائج فقط لسرعة الاستجابة
            ->get();

        $response = [];
        foreach($customers as $customer){
            $response[] = [
                "id"   => $customer->id,
                "text" => $customer->name
            ];
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            $note = DeliveryNote::findOrFail($id);
            $note->delete();

            return response()->json([
                'success' => true,
                'message' => __('home.deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function print($id)
    {
        // جلب البيانات مع العلاقات
        $note = DeliveryNote::with('product')->findOrFail($id);

        // عرض صفحة الطباعة (هنعملها في الخطوة الجاية)
        return view('delivery_notes.print', compact('note'));
    }
    public function getItems($id)
    {
        // جلب الأصناف مع بيانات المنتج المرتبط بها
        $items = DeliveryNoteItem::with('product')->where('delivery_note_id', $id)->get();
        return response()->json($items);
    }
}
