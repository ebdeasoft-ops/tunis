<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\employee;
use App\Models\EndOfService;
use Carbon\Carbon;

class EndOfServiceController extends Controller
{
    public function index()
    {
        $records = EndOfService::with('employee')->latest()->paginate(10);
        return view('hr.eos.index', compact('records'));
    }

    public function create()
    {
        $employees = employee::all();
        return view('hr.eos.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'join_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:join_date',
            'basic_salary' => 'required|numeric',
            'reason' => 'required|in:resignation,termination',
        ]);

        $joinDate = Carbon::parse($request->join_date);
        $endDate = Carbon::parse($request->end_date);
        $years = $joinDate->diffInDays($endDate) / 365;

        $salary = $request->basic_salary;
        $reward = 0;

        // المعادلة المبسطة لمكافأة نهاية الخدمة
        if ($request->reason == 'termination') {
            // مكافأة كاملة: أجر نصف شهر عن كل سنة من السنوات الخمس الأولى، وأجر شهر عن الباقي
            if ($years <= 5) {
                $reward = ($salary / 2) * $years;
            } else {
                $reward = (($salary / 2) * 5) + ($salary * ($years - 5));
            }
        } else {
            // الاستقالة (تخضع لنسب تصاعدية حسب نظام العمل: أقل من سنتين لا يستحق، من 2 لـ 5 ثلث، من 5 لـ 10 ثلثين، أكثر من 10 كاملة)
            if ($years >= 2 && $years < 5) {
                $fullReward = ($salary / 2) * $years;
                $reward = $fullReward * (1 / 3);
            } elseif ($years >= 5 && $years < 10) {
                $fullReward = ($salary / 2) * $years;
                $reward = $fullReward * (2 / 3);
            } elseif ($years >= 10) {
                if ($years <= 5) {
                    $reward = ($salary / 2) * $years;
                } else {
                    $reward = (($salary / 2) * 5) + ($salary * ($years - 5));
                }
            }
        }

        EndOfService::create([
            'employee_id' => $request->employee_id,
            'join_date' => $request->join_date,
            'end_date' => $request->end_date,
            'service_years' => round($years, 2),
            'basic_salary' => $salary,
            'reason' => $request->reason,
            'reward_amount' => round($reward, 2),
            'notes' => $request->notes,
        ]);

        return redirect()->route('eos.index')->with('success', 'تم احتساب وحفظ مكافأة نهاية الخدمة بنجاح');
    }
}
