<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function edit($id)
{
    $contract = Contract::findOrFail($id);
    $employees = Employee::all();
    return view('contracts.edit', compact('contract', 'employees'));
}

public function update(Request $request, $id)
{
    $contract = Contract::findOrFail($id);
    
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'contract_type' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'basic_salary' => 'required|numeric',
    ]);

    $contract->update($request->all());

    return redirect()->route('contracts.index')->with('success', 'تم تحديث العقد بنجاح');
}

    public function index()
    {
        $contracts = Contract::with('employee')->latest()->paginate(10);
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('contracts.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'basic_salary' => 'required|numeric',
            'iqama_expiry_date' => 'nullable|date',
            'work_permit_expiry_date' => 'nullable|date',
        ]);

        Contract::create($request->all());

        return redirect()->route('contracts.index')->with('success', 'تم حفظ العقد والوثائق بنجاح.');
    }

    // شاشة التنبيهات الخاصة بالإقامات ورخص العمل التي توشك على الانتهاء (خلال 30 يوماً مثلاً)
    public function documentAlerts()
    {$today = Carbon::today();
    $futureDate = Carbon::today()->addDays(30);

    // 1. الوثائق التي توشك على الانتهاء (من اليوم وحتى 30 يوماً قادمة)
    $expiringIqamas = Contract::with('employee')
        ->whereNotNull('iqama_expiry_date')
        ->whereBetween('iqama_expiry_date', [$today, $futureDate])
        ->get();

    $expiringWorkPermits = Contract::with('employee')
        ->whereNotNull('work_permit_expiry_date')
        ->whereBetween('work_permit_expiry_date', [$today, $futureDate])
        ->get();

    $expiringContracts = Contract::with('employee')
        ->whereBetween('end_date', [$today, $futureDate])
        ->get();

    // 2. الوثائق التي انتهت بالفعل (أقل من تاريخ اليوم)
    $expiredIqamas = Contract::with('employee')
        ->whereNotNull('iqama_expiry_date')
        ->where('iqama_expiry_date', '<', $today)
        ->get();

    $expiredWorkPermits = Contract::with('employee')
        ->whereNotNull('work_permit_expiry_date')
        ->where('work_permit_expiry_date', '<', $today)
        ->get();

    $expiredContracts = Contract::with('employee')
        ->where('end_date', '<', $today)
        ->get();

    return view('contracts.alerts', compact(
        'expiringIqamas', 'expiringWorkPermits', 'expiringContracts',
        'expiredIqamas', 'expiredWorkPermits', 'expiredContracts'
    ));
    
    }
}