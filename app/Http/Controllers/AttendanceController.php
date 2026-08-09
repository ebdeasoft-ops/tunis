<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\employee;
use App\Models\HrSetting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendancesImport;
use App\Exports\AttendancesTemplateExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // تأكد من إضافة هذا السطر في الأعلى

class AttendanceController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new AttendancesTemplateExport, 'attendances_template.xlsx');
    }

  public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Attendance::with('employee');

            // فلترة بالموظف
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // فلترة بالحالة
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // فلترة بالتاريخ
            if ($request->filled('min_date') && $request->filled('max_date')) {
                $query->whereBetween('date', [$request->min_date, $request->max_date]);
            }

            $start = $request->get('start', 0);
            $length = $request->get('length', 10);

            $totalData = Attendance::count();
            $totalFiltered = $query->count();

            $attendances = $query->offset($start)
                ->limit($length)
                ->get();

            $settings = HrSetting::first();
            $officialCheckIn = $settings ? $settings->official_check_in : '08:00';

            $data = [];
            foreach ($attendances as $index => $row) {
                
                // === 1. إذا كان نوع الحضور "إضافي" (Overtime) ===
                if ($row->type === 'overtime') {
                    $overtimeHours = 0;
                    if ($row->check_in && $row->check_out) {
                        $in = Carbon::parse($row->check_in);
                        $out = Carbon::parse($row->check_out);
                        $overtimeHours = round($in->floatDiffInHours($out), 2);
                    }
                    
                    // عرض نوع الحضور إضافي مع عدد الساعات
                    $typeFormatted = '<span class="badge badge-warning px-3 py-2">' . __('attendances.overtime') . ' (' . $overtimeHours . ' ' . __('attendances.hours') . ')</span>';
                    
                    // السجل الإضافي تعتبر حالته حاضر وطبيعي ولن يظهر فيه تأخير صباحي
                    $statusClass = 'badge-success';
                    $statusText = __('attendances.present'); 

                } else {
                    // === 2. إذا كان الحضور "عادي" (Normal) ===
                    $typeFormatted = '<span class="badge badge-success px-3 py-2">' . __('attendances.normal') . '</span>';
                    
                    $statusClass = $row->status == 'present' ? 'badge-success' : ($row->status == 'late' ? 'badge-warning' : 'badge-danger');
                    $statusText = __('attendances.' . $row->status);

                    // حساب التأخير الصباحي فقط للسجلات العادية
                    if ($row->status == 'late' && $row->check_in) {
                        $checkInTime = Carbon::parse($row->check_in);
                        $officialTime = Carbon::parse($officialCheckIn);

                        $diffMinutes = $checkInTime->diffInMinutes($officialTime);
                        $hours = floor($diffMinutes / 60);
                        $minutes = $diffMinutes % 60;

                        $delayString = '';
                        if ($hours > 0) {
                            $delayString .= $hours . ' ' . __('attendances.hour') . ' ';
                        }
                        if ($minutes > 0) {
                            $delayString .= $minutes . ' ' . __('attendances.minute');
                        }

                        if (!empty($delayString)) {
                            $statusText .= ' (' . trim($delayString) . ')';
                        }
                    }
                }

                $editUrl = route('attendances.edit', $row->id);
                $deleteForm = '
                <form action="' . route('attendances.destroy', $row->id) . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'هل أنت متأكد من الحذف؟\');">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
            ';

                $data[] = [
                    'DT_RowIndex' => $start + $index + 1,
                    'employee_name' => $row->employee ? $row->employee->name_ar : '---',
                    'date' => $row->date,
                    'check_in' => '<span class="badge badge-light text-primary">' . $row->check_in . '</span>',
                    'check_out' => '<span class="badge badge-light text-dark">' . ($row->check_out ?? '---') . '</span>',
                    'attendance_type' => $typeFormatted, 
                    'status_formatted' => '<span class="badge ' . $statusClass . '">' . $statusText . '</span>',
                    'action' => '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a> ' . $deleteForm
                ];
            }

            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data
            ]);
        }

        $employees = Employee::all();
        return view('attendances.index', compact('employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        // جلب الإعدادات (افترض أن لديك موديل اسمه HrSetting)
        $settings = HrSetting::first();
        return view('attendances.create', compact('employees', 'settings'));
    }


public function store(Request $request)
    {
        $dateString = $request->date;
        $attendanceDate = Carbon::parse($dateString);
        $checkOutString = $request->check_out;

        // 1. جلب إعدادات الموارد البشرية (أيام الإجازة ووقت الدوام الرسمي)
        $setting = HrSetting::first();

        $weekendDaysSetting = $setting ? strtolower($setting->weekend_days) : 'friday';
        $officialCheckoutTime = $setting && $setting->official_check_out ? $setting->official_check_out : '16:00:00';

        // 2. التحقق مما إذا كان اليوم إجازة أسبوعية بناءً على الإعدادات
        $dayName = strtolower($attendanceDate->format('l')); // saturday, friday...

        $isWeekend = false;
        if ($weekendDaysSetting === 'friday' && $dayName === 'friday') {
            $isWeekend = true;
        } elseif ($weekendDaysSetting === 'friday_saturday' && ($dayName === 'friday' || $dayName === 'saturday')) {
            $isWeekend = true;
        }

        $type = 'normal'; // الافتراضي دوام عادي

        if ($isWeekend) {
            // إذا كان اليوم إجازة أسبوعية، يعتبر إضافي
            $type = 'overtime';
        } else {
            // في الأيام العادية: مقارنة وقت الانصراف بوقت الدوام الرسمي
            if ($checkOutString) {
                $checkOutTime = Carbon::parse($checkOutString);
                $officialEndWork = Carbon::parse($attendanceDate->toDateString() . ' ' . $officialCheckoutTime);

                if ($checkOutTime->greaterThan($officialEndWork)) {
                    $type = 'overtime';
                }
            }
        }

        $data = $request->all();
        $data['type'] = $type;

        // 3. حساب خصم الغياب (يومين) إذا كانت الحالة غياب
        $status = $request->status ?? 'present';
        $discountAmount = 0;

        if ($status === 'absent') {
            // جلب بيانات الموظف لمعرفة راتبه الأساسي
            $employee = employee::find($request->employee_id);
            
            if ($employee && $employee->salary > 0) {
                // قيمة اليوم الواحد (افتراض الشهر 30 يوم)
                $dailyRate = $employee->salary / 30;
                // خصم يومين عن كل يوم غياب
                $discountAmount = $dailyRate * 2;
            }
        }

        // إضافة مبلغ الخصم إلى المصفوفة ليتم حفظه في قاعدة البيانات
        $data['discount_amount'] = $discountAmount; // تأكد من مطابقة اسم العمود لديك (مثل discount أو discount_amount)

        Attendance::create($data);

        return redirect()->route('attendances.index')->with('success', 'تم تسجيل الحضور بنجاح');
    }

    
    // إضافة وظيفة الحذف (تأكد من وجودها)
    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->route('attendances.index')->with('success', 'تم حذف السجل بنجاح');
    }

    // إضافة وظيفة التعديل (تأكد من وجودها)
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $employees = employee::all();
        $settings = HrSetting::first();

        return view('attendances.edit', compact('attendance', 'employees', 'settings'));
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            DB::transaction(function () use ($request) {
                Excel::import(new AttendancesImport, $request->file('file'));
            });

            return redirect()->route('attendances.index')->with('success', 'تم استيراد البيانات بنجاح.');

        } catch (\Exception $e) {
            // في حالة حدوث أي خطأ، يتم التراجع عن كل ما تم حفظه تلقائياً
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()]);
        }
    }
public function update(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);

    $dateString = $request->date;
    $attendanceDate = Carbon::parse($dateString);
    $checkOutString = $request->check_out;

    // 1. جلب إعدادات الموارد البشرية (أيام الإجازة ووقت الدوام الرسمي)
    $setting = HrSetting::first();
    
    $weekendDaysSetting = $setting ? strtolower($setting->weekend_days) : 'friday';
    $officialCheckoutTime = $setting && $setting->official_check_out ? $setting->official_check_out : '16:00:00';

    // 2. التحقق مما إذا كان اليوم إجازة أسبوعية بناءً على الإعدادات
    $dayName = strtolower($attendanceDate->format('l')); // saturday, friday...
    
    $isWeekend = false;
    if ($weekendDaysSetting === 'friday' && $dayName === 'friday') {
        $isWeekend = true;
    } elseif ($weekendDaysSetting === 'friday_saturday' && ($dayName === 'friday' || $dayName === 'saturday')) {
        $isWeekend = true;
    }

    $type = 'normal'; // الافتراضي دوام عادي

    if ($isWeekend) {
        // إذا كان اليوم إجازة أسبوعية، يعتبر إضافي
        $type = 'overtime';
    } else {
        // في الأيام العادية: مقارنة وقت الانصراف بوقت الدوام الرسمي
        if ($checkOutString) {
            $checkOutTime = Carbon::parse($checkOutString);
            $officialEndWork = Carbon::parse($attendanceDate->toDateString() . ' ' . $officialCheckoutTime);

            if ($checkOutTime->greaterThan($officialEndWork)) {
                $type = 'overtime';
            }
        }
    }

    // 3. تجهيز البيانات وتحديث السجل مع النوع المحسوب تلقائياً
    $data = $request->all();
    $data['type'] = $type;

    $attendance->update($data);

    return redirect()->route('attendances.index')->with('success', 'تم تعديل السجل بنجاح');
}
}