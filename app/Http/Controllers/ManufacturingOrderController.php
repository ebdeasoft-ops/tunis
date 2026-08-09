<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\ManufacturingOrder;
use App\Models\MoMaterialIssue;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManufacturingOrderController extends Controller
{
    public function index()
    {
        $orders = ManufacturingOrder::with(['bom', 'finishedProduct', 'wipWarehouse'])
            ->latest()
            ->paginate(15);

        return view('manufacturing_orders.index', compact('orders'));
    }

    public function create()
    {
        $boms = Bom::where('is_active', 1)->with('finishedProduct')->get();

        // جلب المخازن (استبدل باسم الموديل والمخازن المتاحة لديك)
        $warehouses = Warehouse::all();

        return view('manufacturing_orders.create', compact('boms', 'warehouses'));
    }

    public function store(Request $request)
{
    $request->validate([
        'order_number'                => 'required|string|unique:manufacturing_orders,order_number',
        'bom_id'                      => 'required|exists:boms,id',
        'planned_quantity'            => 'required|numeric|min:0.0001',
        'order_date'                  => 'required|date',
        'raw_material_warehouse_id'   => 'required',
        'wip_warehouse_id'            => 'required',
        'finished_goods_warehouse_id' => 'required',
        'items'                       => 'required|array|min:1',
        'items.*.raw_material_id'     => 'required|exists:products,id',
        'items.*.planned_quantity'    => 'required|numeric|min:0.0001',
    ]);

    // قم ببدء المعاملة بدون catch مؤقتاً لمعرفة الخطأ بالضبط
    $bom = Bom::findOrFail($request->bom_id);

    $order = ManufacturingOrder::create([
        'order_number'                => $request->order_number,
        'bom_id'                      => $request->bom_id,
        'finished_product_id'         => $bom->finished_product_id,
        'raw_material_warehouse_id'   => $request->raw_material_warehouse_id,
        'wip_warehouse_id'            => $request->wip_warehouse_id,
        'finished_goods_warehouse_id' => $request->finished_goods_warehouse_id,
        'planned_quantity'            => $request->planned_quantity,
        'order_date'                  => $request->order_date,
        'status'                      => 'draft',
        'notes'                       => $request->notes,
        'created_by'                  => auth()->id(), // أو احذفه إذا لم يكن العمود موجوداً
    ]);

    foreach ($request->items as $item) {
        MoMaterialIssue::create([
            'manufacturing_order_id' => $order->id,
            'raw_material_id'        => $item['raw_material_id'],
            'planned_quantity'       => $item['planned_quantity'],
            'issued_quantity'        => 0,
            'unit_cost'              => 0,
            'total_cost'             => 0,
        ]);
    }

    return redirect()->route('manufacturing_orders.index')->with('success', __('home.save_success'));
}

    public function show($id)
    {
        $order = ManufacturingOrder::with(['bom', 'finishedProduct', 'materialIssues.rawMaterial', 'rawMaterialWarehouse', 'wipWarehouse', 'finishedGoodsWarehouse'])
            ->findOrFail($id);

        return view('manufacturing_orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = ManufacturingOrder::with([
            'bom',
            'finishedProduct',
            'materialIssues.rawMaterial.unit', // جلب الوحدة المربوطة بالمادة الخام
            'rawMaterialWarehouse',
            'wipWarehouse',
            'finishedGoodsWarehouse'
        ])->findOrFail($id);

        // لا يمكن تعديل أمر إنتاج منتهي أو ملغي
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', __('لا يمكن تعديل أمر إنتاج مكتمل أو ملغي'));
        }

        $boms = Bom::where('is_active', 1)->get();
        $warehouses = Warehouse::all();

        return view('manufacturing_orders.edit', compact('order', 'boms', 'warehouses'));
    }

    public function destroy($id)
    {
        $order = ManufacturingOrder::findOrFail($id);
        if ($order->status != 'draft') {
            return redirect()->back()->with('error', __('يمكن حذف أوامر الإنتاج بنمط المسودة فقط'));
        }

        $order->delete();
        return redirect()->route('manufacturing_orders.index')->with('success', __('home.delete_success'));
    }

    // دالة AJAX لجلب بيانات شجرة المكونات وتفاصيل خاماتها لحساب الكميات المخططة فورياً
    public function getBomDetails($id)
    {
        $bom = Bom::with(['finishedProduct', 'items.rawMaterial', 'items.unit'])->find($id);

        if (!$bom) {
            return response()->json(['error' => 'BOM not found'], 404);
        }

        return response()->json([
            'finished_product_id'   => $bom->finished_product_id,
            'finished_product_name' => $bom->finishedProduct->product_name ?? $bom->finishedProduct->name ?? '',
            'output_quantity'       => $bom->output_quantity,
            'items'                 => $bom->items->map(function($item) {
                return [
                    'raw_material_id'   => $item->raw_material_id,
                    'raw_material_name' => $item->rawMaterial->product_name ?? $item->rawMaterial->name ?? '',
                    'unit_name'         => app()->getLocale() == 'ar' ? ($item->unit->name_ar ?? '') : ($item->unit->name_en ?? ''),
                    'quantity'          => $item->quantity, // الكمية للوجبة الواحدة
                    'scrap_percentage'  => $item->scrap_percentage,
                ];
            })
        ]);
    }
}
