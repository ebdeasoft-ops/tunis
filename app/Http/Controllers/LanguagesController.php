<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguagesController extends Controller
{ 
    public function switchLang($lang) 
    {
        // التأكد من أن اللغة المرسلة مدعومة في ملف إعدادات الحزمة laravel-localization
        if (array_key_exists($lang, LaravelLocalization::getSupportedLocales())) {
            
            // جلب الرابط السابق الذي جاء منه المستخدم
            $previousUrl = url()->previous();
            
            // تحويل الرابط السابق ليدعم اللغة الجديدة
            $localizedUrl = LaravelLocalization::getLocalizedURL($lang, $previousUrl, [], true);
            
            return redirect()->to($localizedUrl);
        } 

        return redirect()->back();
    } 
}