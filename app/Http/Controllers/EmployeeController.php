<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\Loans;
use App\Models\HrSetting;
use App\Models\Leave;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use  App\Models\departments;
use App\Models\Increaseـor_deduction_employee;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
     public function addnewDepartment()
     {
         //
         app()->setLocale(LaravelLocalization::getCurrentLocale());
 
         return view('hr.Add_new_department');
     }


     public function salarydecoument()
     {
         //
         app()->setLocale(LaravelLocalization::getCurrentLocale());
 
         return view('hr.salarydecoument');
     }
     
public function print_decument_salary(Request $request)
{
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $request->validate([
        'end_at' => 'required',
    ]);

    $start = $request->end_at . '-01';
    $end = date('Y-m-t', strtotime($start)); // لتحديد نهاية الشهر تلقائياً

    // 1. جلب إعدادات النظام
    $settings = HrSetting::first();
    
    // معامل ضرب الساعات الإضافية (من جدول الإعدادات مثل 2 أو 3)
    $overtimeMultiplier = $settings ? $settings->overtime_hour_rate : 1; 

    // حساب ساعات العمل اليومية بناءً على الدوام الرسمي في الإعدادات
    $dailyWorkingHours = 8; // قيمة افتراضية احتياطية
    if ($settings && $settings->official_check_in && $settings->official_check_out) {
        $checkIn = \Carbon\Carbon::parse($settings->official_check_in);
        $checkOut = \Carbon\Carbon::parse($settings->official_check_out);
        $dailyWorkingHours = max(1, round($checkIn->floatDiffInHours($checkOut), 2)); 
    }

    // إجمالي ساعات العمل الشهرية (افتراض 30 يوماً شهرياً)
    $monthlyWorkingHours = $dailyWorkingHours * 30;

    $list_salary_data = [];
    $employees = employee::get();

    foreach ($employees as $employee) {
        $bounes = 0;
        $discount = 0;
        $Loans = 0;

        // حساب المكافآت والخصومات خلال الشهر
        foreach (Increaseـor_deduction_employee::where('employee_id', $employee->id)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get() as $item) {
            $bounes += $item->increase;
            $discount += $item->deduction; 
        }

        // حساب السلف والقروض خلال الشهر
        foreach (Loans::where('employee_id', $employee->id)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get() as $item) {
            $Loans += $item->Loans_amount;
        }

        // جلب سجلات الحضور والانصراف خلال الشهر المختار
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get();

        // حساب أيام الحضور (حضور طبيعي + متأخر)
        $presentDays = $attendances->where('status', 'present')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $totalPresent = $presentDays + $lateDays;

        // أيام الغياب المسجلة
        $absentDays = $attendances->where('status', 'absent')->count();

        // جلب إجازات الموظف خلال الشهر
        $employeeLeaves = collect();
        $permissionLeaveDays = 0;         // إجازة بإذن
        $withoutPermissionLeaveDays = 0; // إجازة بدون إذن

        if (class_exists('App\Models\Leave')) {
            $employeeLeaves = \App\Models\Leave::where('employee_id', $employee->id)
                ->whereDate('start_date', '>=', $start)
                ->whereDate('start_date', '<=', $end)
                ->get();

            // تصنيف الإجازات بناءً على النوع
            $permissionLeaveDays = $employeeLeaves->where('type', 'casual')->count(); 
            $withoutPermissionLeaveDays = $employeeLeaves->where('type', 'regular')->count();
        }

        // غياب بدون إذن
        $unauthorizedRecords = $attendances->filter(function ($att) use ($employeeLeaves) {
            if ($att->status !== 'absent' && $att->status !== 'unauthorized_absent') {
                return false;
            }

            $hasLeave = $employeeLeaves->contains(function ($leave) use ($att) {
                return $att->date >= $leave->start_date && $att->date <= $leave->end_date;
            });

            return !$hasLeave;
        });

        $unauthorizedAbsentDays = $unauthorizedRecords->count();

        // حساب ساعات الإضافي
        $totalOvertimeHours = 0;
        foreach ($attendances->where('type', 'overtime') as $att) {
            if ($att->check_in && $att->check_out) {
                $in = \Carbon\Carbon::parse($att->check_in);
                $out = \Carbon\Carbon::parse($att->check_out);
                $totalOvertimeHours += round($in->floatDiffInHours($out), 2);
            }
        }

        // حساب أجر الساعة العادية للموظف بناءً على الراتب الأساسي
        $basicSalary = $employee->salary ?? 0;
        $normalHourlyRate = $basicSalary > 0 ? ($basicSalary / $monthlyWorkingHours) : 0;

        // حساب القيمة المالية النهائية للإضافي = (عدد الساعات × أجر الساعة العادية × المعامل)
        $overtimeAmount = round($totalOvertimeHours * $normalHourlyRate * $overtimeMultiplier, 2);

        // تجميع بيانات الموظف كاملة للمطابقة مع العرض في ملف الـ Blade
        $list_salary_data[] = [
            'employeeData' => $employee,
            'bounes' => $bounes,
            'discount' => $discount,
            'Loans' => $Loans,
            'present_days' => $totalPresent,
            'absent_days' => $absentDays,
            'permission_leave_days' => $permissionLeaveDays,
            'without_permission_leave_days' => $withoutPermissionLeaveDays,
            'unauthorized_absent_days' => $unauthorizedAbsentDays,
            'overtime_hours' => $totalOvertimeHours,
            'overtime_amount' => $overtimeAmount,
        ];
    }

    return view('hr.employee_salary_list_print', compact('list_salary_data'))->with('month', $request->end_at);
}
     public function Increaseـor_deduction()
     {
         //
         app()->setLocale(LaravelLocalization::getCurrentLocale());
 
         return view('hr.IncreaseOr_deduction');
     }

     


    public function index()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('hr.Add_employee');
    }

    public function updateEmployee($id)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
       $employee= employee::find($id);
        return view('hr.update_employee',compact('employee'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
{
    //
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $employee = employee::create([
        'name_ar' => $request->employee_name_ar,
        'name_en' => $request->employee_name_en ?? "Name En",
        'email' => $request->email ?? "Example@gmail.com",
        'phone' => $request->phone,
        'department' => $request->department,
        'salary' => $request->salary,
        
        // أضف حقول البدلات هنا مع وضع قيمة افتراضية 0 إذا لم يتم إدخالها
        'housing_allowance' => $request->housing_allowance ?? 0,
        'transportation_allowance' => $request->transportation_allowance ?? 0,
        'other_allowances' => $request->other_allowances ?? 0,

        'nationality' => $request->nationality,
        'personal_identification' => $request->personal_identification,
        'old' => $request->age,
        'sex' => $request->sex,
        'created_at' => \Carbon\Carbon::now()->addHours(3),
    ]);

    if ($employee != null) {
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم إضافة الموظف بنجاح' : ' Employee created successfully';
        session()->flash('create_employee', $message);
    }
    
    return view('hr.Add_employee');
}

 
    public function store(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $this->validate($request, [
          
         
        ]);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $employee=departments::create([
            'name_ar'=>$request->department_name_ar,
            'name_en'=>$request->department_name_en??"Department",

            'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
        );
    
if( $employee!=null){
    $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة القسم بنجاح':'  Department created successfully';
        session()->flash('create_department',$message);
}
        return view('hr.Add_new_department');
    }



    

 




    public function Increaseـor_deduction_add(Request $request)
    {
        //
    // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if($request->increasValue!=0)
        {
            $employee=Increaseـor_deduction_employee::create([
                'employee_id'=>$request->department,
                'deduction'=>$request->decreaseValue,
                'increase'=>$request->increasValue,
                'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
            );
        
    if( $employee!=null){
        $employee= employee::find($request->id);
        $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة مكافأة بنجاح ':' Add bonus successfully ';
            session()->flash('create_department',$message);
    }
            return view('hr.IncreaseOr_deduction');
        }
        
        else{
            $employee=Increaseـor_deduction_employee::create([
                'employee_id'=> $request->department,
                'deduction'=>$request->decreaseValue,
                'increase'=>$request->increasValue,
                'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
            );
        
    if( $employee!=null){
        $employee= employee::find($request->id);
        $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة الخصم بنجاح ':'The discount has been added successfully';
            session()->flash('create_department',$message);
    }
            return view('hr.IncreaseOr_deduction');
        }
    }







    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('hr.show_employee');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request)
{
    //
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    //   return $request;
 
    $employee = employee::where('personal_identification', $request->personal_identification)->update([
        'name_ar' => $request->employee_name_ar,
        'name_en' => $request->employee_name_en,
        'email' => $request->email,
        'phone' => $request->phone,
        'department' => $request->department,
        'salary' => $request->salary,
        
        // أضف حقول البدلات هنا مع قيم افتراضية 0 لتجنب أي أخطاء في حال كانت فارغة
        'housing_allowance' => $request->housing_allowance ?? 0,
        'transportation_allowance' => $request->transportation_allowance ?? 0,
        'other_allowances' => $request->other_allowances ?? 0,

        'nationality' => $request->nationality,
        'personal_identification' => $request->personal_identification,
        'old' => $request->age,
        'sex' => $request->sex,
        'updated_at' => \Carbon\Carbon::now()->addHours(3), // يفضل استخدام updated_at بدلاً من created_at عند التعديل
    ]);
     
    if ($employee) {
        $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'تم تعديل بيانات الموظف بنجاح' : ' Employee updated successfully';
        session()->flash('updated_employee', $message);
    }
    
    $employee = employee::where('personal_identification', $request->personal_identification)->first();
    
    return view('hr.update_employee', compact('employee'));
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(employee $employee)
    {
        //
    }
}