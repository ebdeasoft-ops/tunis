<?php

namespace App\Http\Controllers;

use App\Models\ManufacturingExpense;
use App\Models\ManufacturingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManufacturingExpenseController extends Controller
{
    public function index()
    {
        $expenses = ManufacturingExpense::with(['manufacturingOrder.finishedProduct'])
            ->latest()
            ->paginate(15);

        return view('manufacturing_expenses.index', compact('expenses'));
    }

    public function create()
    {
        $orders = ManufacturingOrder::whereIn('status', ['planned', 'in_progress', 'completed'])
            ->with(['finishedProduct'])
            ->get();

        return view('manufacturing_expenses.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_number'         => 'required|string|unique:manufacturing_expenses,expense_number',
            'manufacturing_order_id' => 'required|exists:manufacturing_orders,id',
            'expense_date'           => 'required|date',
            'expense_type'           => 'required|string',
            'amount'                 => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $expense = ManufacturingExpense::create([
                'expense_number'         => $request->expense_number,
                'manufacturing_order_id' => $request->manufacturing_order_id,
                'expense_date'           => $request->expense_date,
                'expense_type'           => $request->expense_type,
                'amount'                 => $request->amount,
                'notes'                  => $request->notes,
                'created_by'             => Auth::id() ?? 1,
            ]);

            // تحديث تكلفة المصاريف غير المباشرة التراكمية والتكلفة الإجمالية في أمر الإنتاج
            $order = ManufacturingOrder::findOrFail($request->manufacturing_order_id);
            $newOverhead = ($order->actual_overhead_cost ?? 0) + $request->amount;
            $newTotalCost = ($order->actual_materials_cost ?? 0) + $newOverhead;

            $order->update([
                'actual_overhead_cost' => $newOverhead,
                'total_actual_cost'    => $newTotalCost,
            ]);

            DB::commit();
            return redirect()->route('manufacturing_expenses.index')->with('success', __('home.save_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $expense = ManufacturingExpense::with(['manufacturingOrder.finishedProduct'])->findOrFail($id);
        return view('manufacturing_expenses.show', compact('expense'));
    }

    public function edit($id)
    {
        $expense = ManufacturingExpense::with(['manufacturingOrder.finishedProduct'])->findOrFail($id);
        $orders = ManufacturingOrder::whereIn('status', ['planned', 'in_progress', 'completed'])->get();

        return view('manufacturing_expenses.edit', compact('expense', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $expense = ManufacturingExpense::findOrFail($id);

        $request->validate([
            'expense_date' => 'required|date',
            'expense_type' => 'required|string',
            'amount'       => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $order = ManufacturingOrder::findOrFail($expense->manufacturing_order_id);

            // 1. خصم القيمة القديمة من التكلفة التراكمية لأمر الإنتاج
            $order->decrement('actual_overhead_cost', $expense->amount);

            // 2. تحديث بيانات المصروف
            $expense->update([
                'expense_date' => $request->expense_date,
                'expense_type' => $request->expense_type,
                'amount'       => $request->amount,
                'notes'        => $request->notes,
            ]);

            // 3. إعادة إضافة القيمة الجديدة وتحديث إجمالي تكلفة أمر الإنتاج
            $order->refresh();
            $newOverhead = $order->actual_overhead_cost + $request->amount;
            $newTotalCost = ($order->actual_materials_cost ?? 0) + $newOverhead;

            $order->update([
                'actual_overhead_cost' => $newOverhead,
                'total_actual_cost'    => $newTotalCost,
            ]);

            DB::commit();
            return redirect()->route('manufacturing_expenses.index')->with('success', __('home.edit_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $expense = ManufacturingExpense::findOrFail($id);
            $order = ManufacturingOrder::find($expense->manufacturing_order_id);

            if ($order) {
                $order->decrement('actual_overhead_cost', $expense->amount);
                $order->refresh();
                $order->update([
                    'total_actual_cost' => ($order->actual_materials_cost ?? 0) + $order->actual_overhead_cost,
                ]);
            }

            $expense->delete();
            DB::commit();
            return redirect()->back()->with('success', __('home.delete_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
