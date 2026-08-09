<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\BomItem;
use App\Models\products;
use App\Models\products_group;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    public function index()
    {
        $boms = Bom::with('finishedProduct', 'items.rawMaterial')->latest()->paginate(15);
        return view('boms.index', compact('boms'));
    }

    public function create()
    {
        $finishedProducts = products::all();
        // جلب المنتجات مع الفئة المربوطة بها
        $rawMaterials = products::whereNotNull('product_group')->get();
        $groups = products_group::all(); // جلب كل الفئات
        $units = Unit::all();

        return view('boms.create', compact('finishedProducts', 'rawMaterials', 'groups', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:boms,code',
            'name' => 'required|string|max:255',
            'finished_product_id' => 'required|exists:products,id',
            'output_quantity' => 'required|numeric|min:0.0001',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.scrap_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $bom = Bom::create([
                'code' => $request->code,
                'name' => $request->name,
                'finished_product_id' => $request->finished_product_id,
                'output_quantity' => $request->output_quantity,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                BomItem::create([
                    'bom_id' => $bom->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'scrap_percentage' => $item['scrap_percentage'] ?? 0.00,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('boms.index')->with('success', __('تم حفظ شجرة المنتج بنجاح'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', __('حدث خطأ أثناء الحفظ: ') . $e->getMessage());
        }
    }

    public function show(Bom $bom)
    {
        $bom->load('finishedProduct', 'items.rawMaterial', 'items.unit');
        return view('boms.show', compact('bom'));
    }

    public function edit(Bom $bom)
    {
        $bom->load('items');
        $finishedProducts = products::all();
        $rawMaterials = products::all();
        $units = Unit::all();

        return view('boms.edit', compact('bom', 'finishedProducts', 'rawMaterials', 'units'));
    }

    public function update(Request $request, Bom $bom)
    {
        $request->validate([
            'code' => 'required|string|unique:boms,code,' . $bom->id,
            'name' => 'required|string|max:255',
            'finished_product_id' => 'required|exists:products,id',
            'output_quantity' => 'required|numeric|min:0.0001',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_id' => 'required|exists:units,id',
        ]);

        DB::beginTransaction();
        try {
            $bom->update([
                'code' => $request->code,
                'name' => $request->name,
                'finished_product_id' => $request->finished_product_id,
                'output_quantity' => $request->output_quantity,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'notes' => $request->notes,
            ]);

            // حذف العناصر القديمة وإعادة إدراج المحدثة
            $bom->items()->delete();

            foreach ($request->items as $item) {
                BomItem::create([
                    'bom_id' => $bom->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                    'unit_id' => $item['unit_id'],
                    'scrap_percentage' => $item['scrap_percentage'] ?? 0.00,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('boms.index')->with('success', __('تم تحديث شجرة المنتج بنجاح'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', __('حدث خطأ أثناء التحديث: ') . $e->getMessage());
        }
    }

    public function destroy(Bom $bom)
    {
        try {
            $bom->delete(); // سيحذف الـ items تلقائياً بفضل الـ Cascade في قواعد البيانات
            return redirect()->route('boms.index')->with('success', __('تم حذف شجرة المنتج بنجاح'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('لا يمكن حذف هذه الشجرة لارتباطها ببيانات أخرى'));
        }
    }

    // دالة AJAX لجلب تفاصيل المنتج (الوحدة الأساسية والتكلفة)
    public function getProductDetails($id)
    {
        $product = products::with('unit')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 444);
        }
        return response()->json([
            'unit_id' => $product->unit_id ?? null,
            'unit_name' => $product->unit->name ?? '',
            'purchase_price' => $product->purchase_price ?? 0,
        ]);
    }
}
