<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->get();
        return view('warehouses.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code'    => 'nullable|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string',
        ]);

        Warehouse::create([
            'code'      => $request->code ?? 'WH-' . rand(100, 999),
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'address'   => $request->address,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', __('home.save_success'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code'    => 'nullable|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'nullable|string',
        ]);

        $warehouse->update([
            'code'      => $request->code,
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'address'   => $request->address,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', __('home.edit_success'));
    }

    public function destroy(Warehouse $warehouse)
    {
        try {
            $warehouse->delete();
            return redirect()->back()->with('success', __('home.delete_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('home.delete_warehouse_error'));
        }
    }
}
