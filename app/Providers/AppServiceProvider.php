<?php

namespace App\Providers;
use App\Models\settings;       // تأكد من استدعاء الموديل الصحيح
use App\Models\system_setting; // تأكد من استدعاء الموديل الصحيح
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema; // لا تنس إضافة هذه المكتبة في الأعلى
use Illuminate\Support\Facades\Cache;
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

    // [أداء] كانت هذه الكتلة تنفذ 4 استعلامات في كل طلب (منها فحصان على information_schema).
    // الآن تُحفظ القيم في الكاش، ويتم مسحه تلقائياً عند تعديل الإعدادات من SystemSettingController.
    // لمسحه يدوياً: php artisan cache:clear
    $constants = Cache::rememberForever('app_boot_constants', function () {
        // التحقق من وجود الجدول في قاعدة البيانات قبل الاستعلام عنه لمنع الأخطاء أثناء الـ Migration
        if (!Schema::hasTable('settings') || !Schema::hasTable('system_settings')) {
            return null; // null لا يُخزَّن في الكاش، فيُعاد الفحص لاحقاً
        }

        $setting = settings::find(1);
        $system_setting = system_setting::find(1);

        if (!$setting || !$system_setting) {
            return null;
        }

        return [
            'postal_number' => $setting->postal_number,
            'street_name' => $setting->street_name,
            'building_number' => $setting->building_number,
            'plot_identification' => $setting->plot_identification,
            'region' => $setting->region,
            'city' => $setting->city,

            'PAGINATION_COUNT' => 20,
            'serviceCost' => $system_setting->serviceCost,

            'bank_acount_iban' => $system_setting->bank_acount_iban,
            'bank_acount_number' => $system_setting->bank_acount_number,
            'bankname' => $system_setting->bankname,
            'Namear' => $system_setting->name_ar,
            'describtionar' => $system_setting->descriptionarbic,
            'STar' => ' س . ت  :' . $system_setting->SR,
            'Taxar' => '  الرقم الضريبي : ' . $system_setting->Tax,
            'TaxQrCode' => $system_setting->Tax,
            'sallerQrCode' => $system_setting->name_ar,

            'Nameen' => $system_setting->name_en,
            'describtionen' => $system_setting->descriptionenglish,
            'STen' => '  C.R : ' . $system_setting->SR,
            'Taxen' => 'VAT Number : ' . $system_setting->Tax,
            'addressar' => $system_setting->address_ar,
            'addressen' => $system_setting->address_en,
            'camplogo' => $system_setting->logo,
        ];
    });

    if (is_array($constants)) {
        foreach ($constants as $name => $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }
}

}