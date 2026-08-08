<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HrSetting;

class HrSettingController extends Controller
{
    // عرض صفحة الإعدادات
    public function index()
    {
        $setting = HrSetting::firstOrCreate([], [
            'official_check_in' => '08:00',
            'official_check_out' => '16:00',
            'grace_period_minutes' => 15,
            'weekend_days' => 'friday_saturday',
            'overtime_hour_rate' => 0.00 // القيمة الافتراضية
        ]);

        return view('hr_settings.index', compact('setting'));
    }

    // تحديث الإعدادات
    public function update(Request $request, $id)
    {
        $setting = HrSetting::findOrFail($id);
        
        $setting->update([
            'official_check_in' => $request->official_check_in,
            'official_check_out' => $request->official_check_out,
            'grace_period_minutes' => $request->grace_period_minutes,
            'weekend_days' => $request->weekend_days,
            'overtime_hour_rate' => $request->overtime_hour_rate, // حفظ قيمة الساعة الإضافية
        ]);

        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}