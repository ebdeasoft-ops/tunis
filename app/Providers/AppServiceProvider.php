<?php

namespace App\Providers;
use App\Models\settings;       // تأكد من استدعاء الموديل الصحيح
use App\Models\system_setting; // تأكد من استدعاء الموديل الصحيح
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema; // لا تنس إضافة هذه المكتبة في الأعلى
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    //

    // في AppServiceProvider أو أي مكان بيتنفذ قبل توليد الـ PDF



   public function boot(): void
{

    // تسجيل خط Cairo مع نفس نسخة dompdf اللي فعليًا بتستخدمها PDF::loadHTML()
    // (مش نسخة منفصلة زي قبل كده) + استخدام realpath() لتوحيد الشرطات في المسار
    $this->app->extend('dompdf.wrapper', function ($pdf, $app) {
        $fontDir = storage_path('fonts' . DIRECTORY_SEPARATOR);

        if (!is_dir($fontDir)) {
            mkdir($fontDir, 0775, true);
        }

        $pdf->getDomPDF()->getOptions()->setFontDir($fontDir);
        $pdf->getDomPDF()->getOptions()->setFontCache($fontDir);

        $fontMetrics = $pdf->getDomPDF()->getFontMetrics();

        $regularFontPath = realpath(public_path('fonts' . DIRECTORY_SEPARATOR . 'Cairo-Regular.ttf'));

        if ($regularFontPath) {
            $fontMetrics->registerFont(
                ['family' => 'Cairo', 'style' => 'normal', 'weight' => 'normal'],
                $regularFontPath
            );

            $fontMetrics->registerFont(
                ['family' => 'Cairo', 'style' => 'normal', 'weight' => 'bold'],
                $regularFontPath
            );
        }

        return $pdf;
    });

    Paginator::useBootstrap();

    // التحقق من وجود الجدول في قاعدة البيانات قبل الاستعلام عنه لمنع الأخطاء أثناء الـ Migration
    if (Schema::hasTable('settings') && Schema::hasTable('system_settings')) {
        $setting = settings::find(1);
        $system_setting = system_setting::find(1);

        if ($setting && $system_setting) {
            define('postal_number', $setting->postal_number);
            define('street_name', $setting->street_name);
            define('building_number', $setting->building_number);
            define('plot_identification', $setting->plot_identification);
            define('region', $setting->region);
            define('city', $setting->city);

            define('PAGINATION_COUNT', 20);
            define('serviceCost', $system_setting->serviceCost);

            define('bank_acount_iban', $system_setting->bank_acount_iban);
            define('bank_acount_number', $system_setting->bank_acount_number);
            define('bankname', $system_setting->bankname);
            define('Namear', $system_setting->name_ar);
            define('describtionar', $system_setting->descriptionarbic);
            define('STar', ' س . ت  :' . $system_setting->SR);
            define('Taxar', '  الرقم الضريبي : ' . $system_setting->Tax);
            define('TaxQrCode', $system_setting->Tax);
            define('sallerQrCode', $system_setting->name_ar);

            define('Nameen', $system_setting->name_en);
            define('describtionen', $system_setting->descriptionenglish);
            define('STen', '  C.R : ' . $system_setting->SR);
            define('Taxen', 'VAT Number : ' . $system_setting->Tax);
            define('addressar', $system_setting->address_ar);
            define('addressen', $system_setting->address_en);
            define('camplogo', $system_setting->logo);
        }
    }
}

}