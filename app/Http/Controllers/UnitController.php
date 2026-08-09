<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->get();
        return view('units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        Unit::create($request->only('name_ar', 'name_en'));

        return redirect()->back()->with('success', __('home.save_bom'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $unit->update($request->only('name_ar', 'name_en'));

        return redirect()->back()->with('success', __('home.edit'));
    }

    public function destroy(Unit $unit)
    {
        try {
            $unit->delete();
            return redirect()->back()->with('success', __('home.delete'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('لا يمكن حذف الوحدة لارتباطها ببيانات أخرى'));
        }
    }
}
