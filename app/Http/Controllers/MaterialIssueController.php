<?php

namespace App\Http\Controllers;

use App\Models\ManufacturingOrder;
use App\Models\MaterialIssueHeader;
use App\Models\MaterialIssueItem;
use App\Models\MoMaterialIssue;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialIssueController extends Controller
{
    public function index()
    {
        $issues = MaterialIssueHeader::with(['manufacturingOrder', 'rawWarehouse', 'wipWarehouse'])
            ->latest()
            ->paginate(15);

        return view('material_issues.index', compact('issues'));
    }

    public function create()
{
    // جلب أوامر الإنتاج القابلة لصرف الخامات مع بنودها ومنتجات الخامات
    $orders = ManufacturingOrder::whereIn('status', ['draft', 'planned', 'in_progress'])
        ->with(['finishedProduct', 'materialIssues.rawMaterial'])
        ->get();

    $warehouses = Warehouse::where('is_active', 1)->get();

    return view('material_issues.create', compact('orders', 'warehouses'));
}

    public function store(Request $request)
{
    $request->validate([
        'issue_number'           => 'required|string|unique:material_issue_headers,issue_number',
        'manufacturing_order_id' => 'required|exists:manufacturing_orders,id',
        'issue_date'             => 'required|date',
        'raw_warehouse_id'       => 'required|exists:warehouses,id',
        'wip_warehouse_id'       => 'required|exists:warehouses,id',
        'items'                  => 'required|array|min:1',
        'items.*.raw_material_id'=> 'required|exists:products,id',
        'items.*.issued_quantity'=> 'required|numeric|min:0.0001',
    ]);

    DB::beginTransaction();
    try {
        // 1. إنشاء رأس إذن الصرف
        $issueHeader = MaterialIssueHeader::create([
            'issue_number'           => $request->issue_number,
            'manufacturing_order_id' => $request->manufacturing_order_id,
            'issue_date'             => $request->issue_date,
            'raw_warehouse_id'       => $request->raw_warehouse_id,
            'wip_warehouse_id'       => $request->wip_warehouse_id,
            'notes'                  => $request->notes,
            'created_by'             => Auth::id() ?? 1,
        ]);

        $totalIssuedBatchCost = 0; // إجمالي تكلفة إذن الصرف الحالي

        // 2. حفظ البنود وحساب التكلفة وتحديث المخزون
        foreach ($request->items as $item) {
            // جلب سعر الشراء المباشر من الموديل أو من حقل الـ request
            $product = \App\Models\products::find($item['raw_material_id']);
            $unitCost = $item['unit_cost'] ?? ($product->purchasingـprice ?? $product->cost ?? 0);
            $totalCost = $item['issued_quantity'] * $unitCost;

            $totalIssuedBatchCost += $totalCost;

            // أ) حفظ البند بتكلفته في جدول material_issue_items
            MaterialIssueItem::create([
                'issue_header_id' => $issueHeader->id,
                'raw_material_id' => $item['raw_material_id'],
                'issued_quantity' => $item['issued_quantity'],
                'unit_cost'       => $unitCost,
                'total_cost'      => $totalCost,
                'notes'           => $item['notes'] ?? null,
            ]);

            // ب) خصم الكمية المصروفة من المخزن الرئيسي للخامات
            if ($product) {
                $product->decrement('numberofpice', $item['issued_quantity']);
            }

            // جـ) تحديث الكميات المصروفة التراكمية لكل خامة في mo_material_issues
            $moIssue = MoMaterialIssue::where('manufacturing_order_id', $request->manufacturing_order_id)
                ->where('raw_material_id', $item['raw_material_id'])
                ->first();

            if ($moIssue) {
                $moIssue->increment('issued_quantity', $item['issued_quantity']);
            }
        }

        // 3. تحديث التكلفة التراكمية للخامات المباشرة والتكلفة الإجمالية في أمر الإنتاج
        $order = ManufacturingOrder::findOrFail($request->manufacturing_order_id);

        $newMaterialsCost = ($order->actual_materials_cost ?? 0) + $totalIssuedBatchCost;
        $newStatus = $order->status != 'completed' ? 'in_progress' : $order->status;

        $order->update([
            'status'                => $newStatus,
            'actual_materials_cost' => $newMaterialsCost,
            'total_actual_cost'    => $newMaterialsCost + ($order->actual_overhead_cost ?? 0),
        ]);

        DB::commit();
        return redirect()->route('material_issues.index')->with('success', __('home.save_success'));
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->with('error', $e->getMessage());
    }
}

    public function show($id)
    {
        $issue = MaterialIssueHeader::with([
            'manufacturingOrder.finishedProduct',
            'rawWarehouse',
            'wipWarehouse',
            'items.rawMaterial'
        ])->findOrFail($id);

        return view('material_issues.show', compact('issue'));
    }


    public function edit($id)
    {
        $issue = MaterialIssueHeader::with([
            'manufacturingOrder.finishedProduct',
            'items.rawMaterial',
            'rawWarehouse',
            'wipWarehouse'
        ])->findOrFail($id);

        // التأكد من أن أمر الإنتاج غير مكتمل
        if ($issue->manufacturingOrder && $issue->manufacturingOrder->status == 'completed') {
            return redirect()->back()->with('error', __('home.cannot_edit_completed_order'));
        }

        $warehouses = Warehouse::where('is_active', 1)->get();

        return view('material_issues.edit', compact('issue', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $issueHeader = MaterialIssueHeader::with('items')->findOrFail($id);

        $request->validate([
            'issue_date'              => 'required|date',
            'raw_warehouse_id'        => 'required|exists:warehouses,id',
            'wip_warehouse_id'        => 'required|exists:warehouses,id',
            'items'                   => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:products,id',
            'items.*.issued_quantity' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. تسوية وتحديث الكميات المصروفة التراكمية في أمر الإنتاج (خصم الكميات القديمة)
            foreach ($issueHeader->items as $oldItem) {
                $moIssue = MoMaterialIssue::where('manufacturing_order_id', $issueHeader->manufacturing_order_id)
                    ->where('raw_material_id', $oldItem->raw_material_id)
                    ->first();

                if ($moIssue) {
                    $moIssue->decrement('issued_quantity', $oldItem->issued_quantity);
                }
            }

            // 2. تحديث الرأس
            $issueHeader->update([
                'issue_date'       => $request->issue_date,
                'raw_warehouse_id' => $request->raw_warehouse_id,
                'wip_warehouse_id' => $request->wip_warehouse_id,
                'notes'            => $request->notes,
            ]);

            // 3. حذف البنود القديمة وإعادة إنشائها بالكميات الجديدة
            $issueHeader->items()->delete();

            foreach ($request->items as $item) {
                MaterialIssueItem::create([
                    'issue_header_id' => $issueHeader->id,
                    'raw_material_id' => $item['raw_material_id'],
                    'issued_quantity' => $item['issued_quantity'],
                    'unit_cost'       => $item['unit_cost'] ?? 0,
                    'total_cost'      => ($item['issued_quantity'] * ($item['unit_cost'] ?? 0)),
                    'notes'           => $item['notes'] ?? null,
                ]);

                // إضافة الكمية الجديدة التراكمية في أمر الإنتاج
                $moIssue = MoMaterialIssue::where('manufacturing_order_id', $issueHeader->manufacturing_order_id)
                    ->where('raw_material_id', $item['raw_material_id'])
                    ->first();

                if ($moIssue) {
                    $moIssue->increment('issued_quantity', $item['issued_quantity']);
                }
            }

            DB::commit();
            return redirect()->route('material_issues.index')->with('success', __('home.edit_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    // AJAX: جلب الخامات والكميات المخططة الخاصة بأمر الإنتاج المحدد
    public function getMoMaterials($id)
    {
        $order = ManufacturingOrder::with([
            'bom.items.unit',
            'materialIssues.rawMaterial',
            'rawMaterialWarehouse',
            'wipWarehouse'
        ])->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $items = $order->materialIssues->map(function($issueItem) use ($order) {
            // جلب الوحدة من الـ BOM
            $bomItem = $order->bom ? $order->bom->items->firstWhere('raw_material_id', $issueItem->raw_material_id) : null;
            $unitName = $bomItem && $bomItem->unit
                ? (app()->getLocale() == 'ar' ? $bomItem->unit->name_ar : $bomItem->unit->name_en)
                : '---';

            // جلب سعر التكلفة من كولوم purchasing_price المخصص في جدول المنتجات
            $unitCost = $issueItem->rawMaterial->purchasingـprice ?? $issueItem->rawMaterial->cost ?? 0;

            return [
                'raw_material_id'   => $issueItem->raw_material_id,
                'raw_material_name' => $issueItem->rawMaterial->product_name ?? $issueItem->rawMaterial->name ?? '',
                'unit_name'         => $unitName,
                'planned_quantity'  => $issueItem->planned_quantity,
                'already_issued'    => $issueItem->issued_quantity ?? 0,
                'remaining_qty'     => max(0, $issueItem->planned_quantity - ($issueItem->issued_quantity ?? 0)),
                'unit_cost'         => $unitCost, // إرسال تكلفة الوحدة للفرونت إند
            ];
        });

        return response()->json([
            'raw_warehouse_id' => $order->raw_material_warehouse_id,
            'wip_warehouse_id' => $order->wip_warehouse_id,
            'items'            => $items
        ]);
    }
}
