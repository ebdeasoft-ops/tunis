<?php

namespace App\Http\Controllers;

use App\Models\ManufacturingExpense;
use App\Models\ManufacturingOrder;
use App\Models\MaterialIssueItem;
use Illuminate\Http\Request;

class ManufacturingReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ManufacturingOrder::with(['finishedProduct', 'bom']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('order_date', [$request->from_date, $request->to_date]);
        }

        $orders = $query->latest()->paginate(15);

        return view('manufacturing_reports.index', compact('orders'));
    }

    // تقرير تكلفة أمر إنتاج تفصيلي
    public function orderCostReport($id)
    {
        $order = ManufacturingOrder::with([
            'finishedProduct',
            'bom',
            'rawMaterialWarehouse',
            'wipWarehouse',
            'finishedGoodsWarehouse'
        ])->findOrFail($id);

        // 1. جلب بنود الخامات المصروفة لهذا الأمر وشحن علاقة المادة الخام (rawMaterial)
        $materialExpenses = MaterialIssueItem::whereHas('issueHeader', function($q) use ($id) {
            $q->where('manufacturing_order_id', $id);
        })->with('rawMaterial')->get();

        // 2. حساب تكلفة الخامات اعتماداً على unit_cost أو سعر الشراء purchasing_price من المادة الخام
        $totalMaterialsCost = $materialExpenses->sum(function($item) {
            $unitCost = $item->unit_cost > 0
                ? $item->unit_cost
                : ($item->rawMaterial->purchasing_price ?? $item->rawMaterial->cost ?? 0);

            return $item->issued_quantity * $unitCost;
        });

        // 3. حساب المصاريف غير المباشرة لهذا الأمر
        $overheadExpenses = ManufacturingExpense::where('manufacturing_order_id', $id)->get();
        $totalOverheadCost = $overheadExpenses->sum('amount');

        // 4. التكلفة الإجمالية وتكلفة القطعة الواحدة
        $grandTotalCost = $totalMaterialsCost + $totalOverheadCost;
        $producedQty = $order->produced_quantity > 0 ? $order->produced_quantity : 1;
        $costPerUnit = $grandTotalCost / $producedQty;

        return view('manufacturing_reports.order_cost', compact(
            'order',
            'materialExpenses',
            'overheadExpenses',
            'totalMaterialsCost',
            'totalOverheadCost',
            'grandTotalCost',
            'costPerUnit'
        ));
    }
}
