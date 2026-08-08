<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\employee;

class LeaveController extends Controller
{
    // عرض قائمة الإجازات مع بيانات الموظفين
public function index(Request $request)
{
    $employees = employee::all();

    $query = Leave::with('employee')->latest();

    // فلترة حسب الموظف إذا تم تحديده
    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }

    // فلترة حسب الحالة إذا تم تحديدها
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $leaves = $query->get();

    return view('leaves.index', compact('leaves', 'employees'));
}
    // عرض صفحة إضافة إجازة جديدة مع قائمة الموظفين
    public function create()
    {
        $employees = Employee::all();
        return view('leaves.create', compact('employees'));
    }

    // حفظ طلب الإجازة الجديد في قاعدة البيانات
   // حفظ طلب الإجازة الجديد في قاعدة البيانات مع حساب الخصومات والأيام تلقائياً
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_count' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $daysCount = $request->days_count;
        $deductionAmount = 0;

        // حساب قيمة الخصم المالي حسب نوع الإجازة
        // نفرض أن حقل الراتب في جدول الموظفين اسمه salary أو basic_salary
        $dailySalary = ($employee->salary ?? 0) / 30; // حساب راتب اليوم الواحد تقريباً

        if ($request->leave_type == 'unpaid') {
            // إجازة بدون راتب: يخصم عدد الأيام كاملة من الراتب
            $deductionAmount = $daysCount * $dailySalary;
        } elseif ($request->leave_type == 'unauthorized') {
            // غياب بدون إذن: يخصم ضعف الراتب (يومين عن كل يوم غياب)
            $deductionAmount = $daysCount * $dailySalary * 2;
        } elseif ($request->leave_type == 'annual') {
            // إجازة سنوية: تخصم من الرصيد (21 يوم)
            if (isset($employee->annual_leave_balance)) {
                $employee->annual_leave_balance -= $daysCount;
                $employee->save();
            }
        }

        // حفظ الإجازة في الجدول مع قيمة الخصم المحسوبة
        Leave::create([
            'employee_id' => $request->employee_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_count' => $daysCount,
            'deduction_amount' => $deductionAmount,
            'reason' => $request->reason,
            'status' => 'pending', 
        ]);

        return redirect()->route('leaves.index')->with('success', 'تم إضافة طلب الإجازة وحساب الخصومات بنجاح');
    }
    public function edit($id)
{
    $leave = \App\Models\Leave::findOrFail($id);
    $employees = \App\Models\Employee::all(); // لتعبئة قائمة الموظفين في التعديل
    return view('leaves.edit', compact('leave', 'employees'));
}

public function update(Request $request, $id)
{
    $leave = \App\Models\Leave::findOrFail($id);
    
    $leave->update([
        'employee_id' => $request->employee_id,
        'leave_type' => $request->leave_type,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'days_count' => $request->days_count,
        'reason' => $request->reason,
        'status' => $request->status ?? $leave->status,
    ]);

    return redirect()->route('leaves.index')->with('success', __('leaves.success_message'));
}
public function leaveBalanceReport()
{
    // نفترض أن الموظف لديه حقل total_leave_days في جدول الموظفين
    $employees = Employee::with(['leaves' => function($query) {
        $query->where('status', 'approved'); // نحسب فقط الإجازات المقبولة
    }])->get();

    return view('reports.leave_balance', compact('employees'));
}
}