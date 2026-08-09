<?php

namespace App\Http\Controllers;

use App\Models\ManufacturingOrder;
use App\Models\FinishedGoodsReceipt;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinishedGoodsReceiptController extends Controller
{
    public function index()
    {
        $receipts = FinishedGoodsReceipt::with([
            'manufacturingOrder.finishedProduct',
            'wipWarehouse',
            'finishedGoodsWarehouse'
        ])
        ->latest()
        ->paginate(15);

        return view('finished_goods_receipts.index', compact('receipts'));
    }

    public function create()
    {
        // جلب أوامر الإنتاج التي في حالة (قيد التشغيل in_progress أو مخطط planned)
        $orders = ManufacturingOrder::whereIn('status', ['in_progress', 'planned'])
            ->with(['finishedProduct'])
            ->get();

        $warehouses = Warehouse::where('is_active', 1)->get();

        return view('finished_goods_receipts.create', compact('orders', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receipt_number'               => 'required|string|unique:finished_goods_receipts,receipt_number',
            'manufacturing_order_id'     => 'required|exists:manufacturing_orders,id',
            'receipt_date'                 => 'required|date',
            'wip_warehouse_id'            => 'required|exists:warehouses,id',
            'finished_goods_warehouse_id' => 'required|exists:warehouses,id',
            'received_quantity'           => 'required|numeric|min:0.0001',
        ]);

        DB::beginTransaction();
        try {
            $order = ManufacturingOrder::findOrFail($request->manufacturing_order_id);

            // 1. إنشاء إذن الاستلام
            $receipt = FinishedGoodsReceipt::create([
                'receipt_number'               => $request->receipt_number,
                'manufacturing_order_id'     => $request->manufacturing_order_id,
                'receipt_date'                 => $request->receipt_date,
                'wip_warehouse_id'            => $request->wip_warehouse_id,
                'finished_goods_warehouse_id' => $request->finished_goods_warehouse_id,
                'finished_product_id'         => $order->finished_product_id,
                'received_quantity'           => $request->received_quantity,
                'unit_cost'                   => $request->unit_cost ?? 0,
                'total_cost'                  => ($request->received_quantity * ($request->unit_cost ?? 0)),
                'notes'                       => $request->notes,
                'created_by'                  => Auth::id() ?? 1,
            ]);

            // 2. تحديث الكميات المنتجة التراكمية في أمر الإنتاج
            $newProducedQty = ($order->produced_quantity ?? 0) + $request->received_quantity;

            // تحديث حالة الأمر إلى "مكتمل completed" إذا وصلت الكمية المنتجة إلى المخططة أو تجاوزتها
            $newStatus = $newProducedQty >= $order->planned_quantity ? 'completed' : 'in_progress';

            $order->update([
                'produced_quantity' => $newProducedQty,
                'status'            => $newStatus,
                'completed_date'    => $newStatus == 'completed' ? $request->receipt_date : $order->completed_date,
            ]);

            $product = \App\Models\products::find($order->finished_product_id);
            if ($product) {
                // إضافة الكمية المستلمة إلى إجمالي رصيد المنتج
                $product->increment('numberofpice', $request->received_quantity);
            }

            DB::commit();
            return redirect()->route('finished_goods_receipts.index')->with('success', __('home.save_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $receipt = FinishedGoodsReceipt::with([
            'manufacturingOrder.finishedProduct',
            'finishedProduct',
            'wipWarehouse',
            'finishedGoodsWarehouse'
        ])->findOrFail($id);

        return view('finished_goods_receipts.show', compact('receipt'));
    }

    public function edit($id)
    {
        $receipt = FinishedGoodsReceipt::with(['manufacturingOrder.finishedProduct'])->findOrFail($id);
        $warehouses = Warehouse::where('is_active', 1)->get();

        return view('finished_goods_receipts.edit', compact('receipt', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $receipt = FinishedGoodsReceipt::findOrFail($id);

        $request->validate([
            'receipt_date'                 => 'required|date',
            'wip_warehouse_id'            => 'required|exists:warehouses,id',
            'finished_goods_warehouse_id' => 'required|exists:warehouses,id',
            'received_quantity'           => 'required|numeric|min:0.0001',
        ]);

        DB::beginTransaction();
        try {
            $order = ManufacturingOrder::findOrFail($receipt->manufacturing_order_id);

            $product = \App\Models\products::find($receipt->finished_product_id);
            if ($product) {
                $product->decrement('numberofpice', $receipt->received_quantity);
            }

            // 1. خصم الكمية المستلمة القديمة من أمر الإنتاج
            $order->decrement('produced_quantity', $receipt->received_quantity);

            // 2. تحديث بيانات إذن الاستلام
            $receipt->update([
                'receipt_date'                 => $request->receipt_date,
                'wip_warehouse_id'            => $request->wip_warehouse_id,
                'finished_goods_warehouse_id' => $request->finished_goods_warehouse_id,
                'received_quantity'           => $request->received_quantity,
                'unit_cost'                   => $request->unit_cost ?? 0,
                'total_cost'                  => ($request->received_quantity * ($request->unit_cost ?? 0)),
                'notes'                       => $request->notes,
            ]);


            if ($product) {
                $product->increment('numberofpice', $request->received_quantity);
            }

            // 3. إعادة إضافة الكمية الجديدة لتحديث إجمالي أمر الإنتاج وحالته
            $order->refresh();
            $newProducedQty = $order->produced_quantity + $request->received_quantity;
            $newStatus = $newProducedQty >= $order->planned_quantity ? 'completed' : 'in_progress';

            $order->update([
                'produced_quantity' => $newProducedQty,
                'status'            => $newStatus,
            ]);

            DB::commit();
            return redirect()->route('finished_goods_receipts.index')->with('success', __('home.edit_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $receipt = FinishedGoodsReceipt::findOrFail($id);
            $order = ManufacturingOrder::find($receipt->manufacturing_order_id);

            if ($order) {
                $order->decrement('produced_quantity', $receipt->received_quantity);
                if ($order->produced_quantity < $order->planned_quantity) {
                    $order->update(['status' => 'in_progress']);
                }
            }

            // خصم الكمية من رصيد المنتج
            $product = \App\Models\products::find($receipt->finished_product_id);
            if ($product) {
                $product->decrement('numberofpice', $receipt->received_quantity);
            }

            $receipt->delete();
            DB::commit();
            return redirect()->back()->with('success', __('home.delete_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // AJAX: جلب تفاصيل أمر الإنتاج لاستكمال شاشة الاستلام
    public function getMoReceiptDetails($id)
    {
        $order = ManufacturingOrder::with(['finishedProduct', 'wipWarehouse', 'finishedGoodsWarehouse'])->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $planned = (float) $order->planned_quantity;
        $produced = (float) ($order->produced_quantity ?? 0);
        $remaining = max(0, $planned - $produced);

        return response()->json([
            'wip_warehouse_id'            => $order->wip_warehouse_id,
            'finished_goods_warehouse_id' => $order->finished_goods_warehouse_id,
            'finished_product_name'       => $order->finishedProduct->product_name ?? $order->finishedProduct->name ?? '---',
            'planned_quantity'            => $planned,
            'already_produced'            => $produced,
            'remaining_quantity'          => $remaining,
        ]);
    }
}
