<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custody;
use App\Models\Employee;

class CustodyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Custody and Assets');
    }

    public function index()
    {
        $custodies = Custody::with('employee')->latest()->paginate(10);
        return view('hr.custodies.index', compact('custodies'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('hr.custodies.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'item_name' => 'required|string|max:255',
            'delivery_date' => 'required|date',
        ]);

        Custody::create($request->all());

        return redirect()->route('custodies.index')->with('success', 'تم تسجيل العهدة بنجاح');
    }

    public function returnItem($id)
    {
        $custody = Custody::findOrFail($id);
        $custody->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        return back()->with('success', 'تم إثبات إرجاع العهدة بنجاح');
    }
}