<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\HrSetting;
use App\Models\employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; 
use Carbon\Carbon; 

class AttendancesImport implements ToModel, WithHeadingRow
{
    public $importedDates = [];

    public function model(array $row)
    {
        $dateValue = $row['date'] ?? $row['التاريخ'];
        if (!$dateValue) {
            return null;
        }
        
        $dateString = is_numeric($dateValue) 
            ? Date::excelToDateTimeObject($dateValue)->format('Y-m-d') 
            : Carbon::parse($dateValue)->format('Y-m-d');

        // جمع التواريخ الفريدة الموجودة في الملف
        if (!in_array($dateString, $this->importedDates)) {
            $this->importedDates[] = $dateString;
        }

        $attendanceDate = Carbon::parse($dateString);

        $checkInString = $row['check_in'] ?? $row['وقت_الحضور'];
        $checkOutString = $row['check_out'] ?? $row['وقت_الانصراف'];

        $setting = HrSetting::first();
        $weekendDaysSetting = $setting ? strtolower($setting->weekend_days) : 'friday';
        $officialCheckoutTime = $setting && $setting->official_check_out ? $setting->official_check_out : '16:00:00';

        $dayName = strtolower($attendanceDate->format('l')); 
        $isWeekend = false;
        if ($weekendDaysSetting === 'friday' && $dayName === 'friday') {
            $isWeekend = true;
        } elseif ($weekendDaysSetting === 'friday_saturday' && ($dayName === 'friday' || $dayName === 'saturday')) {
            $isWeekend = true;
        }

        $type = 'normal'; 
        if ($isWeekend) {
            $type = 'overtime';
        } else {
            if ($checkOutString) {
                $checkOutTime = Carbon::parse($checkOutString);
                $officialEndWork = Carbon::parse($attendanceDate->toDateString() . ' ' . $officialCheckoutTime);
                if ($checkOutTime->greaterThan($officialEndWork)) {
                    $type = 'overtime';
                }
            }
        }

        $employeeId = $row['employee_id'] ?? $row['employee'];

        // التأكد من تسجيل حضور الموظف الحالي الوارد في ملف الإكسيل
        if ($employeeId) {
            // نتحقق أولاً إذا كان له سجل مسبق في نفس اليوم لتجنب التكرار
            $exists = Attendance::where('employee_id', $employeeId)
                ->whereDate('date', $dateString)
                ->exists();

            if (!$exists) {
                Attendance::create([
                    'employee_id' => $employeeId,
                    'date'        => $dateString,
                    'check_in'    => $checkInString,
                    'check_out'   => $checkOutString,
                    'status'      => $row['status'] ?? 'present',
                    'type'        => $type,
                ]);
            }
        }

        // بعد معالجة السطر الحالي، نقوم بعمل الفحص الشامل لكل الموظفين في هذا التاريخ
        // للتأكد من أن أي موظف لم يرد في ملف الإكسيل لهذا اليوم يتم تسجيله "غياب"
        $this->checkMissingEmployeesForDate($dateString);

        return null; // لأننا قمنا بالحفظ يدوياً داخل الـ model لتفادي تكرار السطور أو تعارض الفحص
    }

    /**
     * دالة للتحقق من الموظفين غير المتواجدين في ملف الإكسيل لتاريخ معين وتسجيلهم غياب
     */
/**
     * دالة للتحقق من الموظفين غير المتواجدين في ملف الإكسيل لتاريخ معين وتسجيلهم غياب مع الخصم
     */
    protected function checkMissingEmployeesForDate($dateString)
    {
        // جلب جميع الموظفين من جدول employees
        $allEmployees = employee::get();

        foreach ($allEmployees as $employee) {
            // هل للموظف أي سجل حضور أو غياب في هذا التاريخ؟
            $hasAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $dateString)
                ->exists();

            // إذا لم يكن له أي سجل في هذا اليوم، يتم تسجيله غياب تلقائياً مع حساب خصم يومين
            if (!$hasAttendance) {
                // حساب قيمة اليوم الواحد (على فرض أن الشهر 30 يوم)
                $dailyRate = ($employee->salary > 0) ? ($employee->salary / 30) : 0;
                
                // حساب خصم يومين غياب بدون إذن
                $twoDaysPenalty = $dailyRate * 2;

                Attendance::create([
                    'employee_id'     => $employee->id,
                    'date'            => $dateString,
                    'check_in'        => null,
                    'check_out'       => null,
                    'status'          => 'absent',
                    'type'            => 'normal',
                    'discount_amount' => $twoDaysPenalty, // تأكد من مطابقة اسم العمود في جدول الـ attendances لديك (أو discount)
                ]);
            }
        }
    }
}