<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\HrSetting;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; 
use Carbon\Carbon; 

class AttendancesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. معالجة التاريخ: دعم الأرقام التسلسلية للإكسيل أو النصوص
        $dateValue = $row['date'] ?? $row['التاريخ'];
        
        $dateString = is_numeric($dateValue) 
            ? Date::excelToDateTimeObject($dateValue)->format('Y-m-d') 
            : Carbon::parse($dateValue)->format('Y-m-d');

        $attendanceDate = Carbon::parse($dateString);

        // 2. استخراج أوقات الحضور والانصراف من ملف الإكسيل
        $checkInString = $row['check_in'] ?? $row['وقت_الحضور'];
        $checkOutString = $row['check_out'] ?? $row['وقت_الانصراف'];

        // 3. جلب إعدادات الموارد البشرية (أيام الإجازة ووقت الدوام الرسمي)
        $setting = HrSetting::first();
        
        $weekendDaysSetting = $setting ? strtolower($setting->weekend_days) : 'friday';
        $officialCheckoutTime = $setting && $setting->official_check_out ? $setting->official_check_out : '16:00:00';

        // 4. التحقق مما إذا كان اليوم الحالي يعتبر إجازة أسبوعية بناءً على إعدادات النظام
        $dayName = strtolower($attendanceDate->format('l')); // saturday, friday, monday...
        
        $isWeekend = false;
        if ($weekendDaysSetting === 'friday' && $dayName === 'friday') {
            $isWeekend = true;
        } elseif ($weekendDaysSetting === 'friday_saturday' && ($dayName === 'friday' || $dayName === 'saturday')) {
            $isWeekend = true;
        }

        $type = 'normal'; // الافتراضي دوام عادي

        if ($isWeekend) {
            // إذا حضر الموظف في يوم الإجازة الأسبوعية (مثل الجمعة أو السبت)، يعتبر إضافي بالكامل
            $type = 'overtime';
        } else {
            // في الأيام العادية: مقارنة وقت الانصراف بوقت الانصراف الرسمي من الإعدادات
            if ($checkOutString) {
                $checkOutTime = Carbon::parse($checkOutString);
                $officialEndWork = Carbon::parse($attendanceDate->toDateString() . ' ' . $officialCheckoutTime);

                // إذا انصرف بعد موعد نهاية الدوام الرسمي المحدد، يعتبر إضافي
                if ($checkOutTime->greaterThan($officialEndWork)) {
                    $type = 'overtime';
                }
            }
        }

        return new Attendance([
            'employee_id' => $row['employee_id'] ?? $row['employee'], 
            'date'        => $dateString,
            'check_in'    => $checkInString,
            'check_out'   => $checkOutString,
            'status'      => $row['status'] ?? 'present',
            'type'        => $type, // حفظ النوع تلقائياً (normal أو overtime)
        ]);
    }
}