<?php

namespace App\Http\Controllers;

use App\Models\system_setting;
use App\Models\settings;
use App\Services\Zatca\OnBoarding;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SystemSettingController extends Controller
{
    /**
     * رفع الصور وحفظها باسم فريد.
     */
    private function uploadImage($folder, $image)
    {
        $extension = strtolower($image->extension());
        $filename = time() . rand(100, 999) . '.' . $extension;
        $image->move(public_path($folder), $filename);
        return $filename;
    }

    public function index()
    {
        $data = system_setting::findOrFail(1);
        return view('system_setting.system_data', compact('data'));
    }

    public function onbourding()
    {
        $data = system_setting::findOrFail(1);
        return view('system_setting.onbourding', compact('data'));
    }

    public function store(Request $request)
    {
        // 1. تحديث جدول الـ system_setting أولاً بالبيانات القادمة من الريكويست
        $systemSetting = system_setting::findOrFail(1);
        $systemSetting->update([
            'invoice_type'  => $request->invoicetype,
            'is_production' => $request->is_production,
            'otp'           => $request->otp,
        ]);

        // 2. جلب جدول الـ settings لتجهيز باقي بيانات المنشأة للـ ZATCA
        $setting = settings::findOrFail(1);

        // تحديد البيئة بناءً على ما تم حفظه وتحديثه للتو
        $env = $systemSetting->is_production ? 'core' : 'simulation';

        // 3. إرسال الطلب إلى خدمة ZATCA مع دمج الـ OTP الجديد والـ invoice_type المحدث
        $response = (new OnBoarding())
            ->setZatcaEnv($env)
            ->setZatcaLang('en')
            ->setEmailAddress($setting->email_address)
            ->setCommonName($setting->common_name)
            ->setCountryCode('SA')
            ->setOrganizationUnitName($setting->organization_unit_name)
            ->setOrganizationName($setting->organization_name)
            ->setEgsSerialNumber('1-SDSA|2-FGDS|3-SDFG') // يفضل جعل هذا ديناميكياً مستقبلاً
            ->setVatNumber($setting->trn)
            ->setInvoiceType($request->invoicetype) 
            ->setRegisteredAddress($setting->registered_address)
            ->setAuthOtp($request->otp) 
            ->setBusinessCategory($setting->business_category)
            ->getAuthorization();

        if (isset($response['success']) && $response['success']) {
            $data = $response['data'];
            
            $setting->update([
                'cnf'                     => $data['configData'] ?? null,
                'private_key'             => $data['privateKey'] ?? null,
                'public_key'              => $data['publicKey'] ?? null,
                'csr_request'             => $data['csrKey'] ?? null,
                'certificate'             => $data['complianceCertificate'] ?? null,
                'secret'                  => $data['complianceSecret'] ?? null,
                'csid'                    => $data['complianceRequestID'] ?? null,
                'production_certificate'  => $data['productionCertificate'] ?? null,
                'production_secret'       => $data['productionCertificateSecret'] ?? null,
                'production_csid'         => $data['productionCertificateRequestID'] ?? null
            ]);

            session()->flash('RegisterDone', 'تم الربط مع الزكاة بنجاح شكرا | The link with Zakat has been completed successfully');
            
            return view('system_setting.onbourding', ['data' => $systemSetting]);
        } else {
            session()->flash('ERROR', $response['message'] ?? 'حدث خطأ أثناء الربط مع زكاة');
            return view('system_setting.onbourding', ['data' => $systemSetting]);
        }
    }

    public function update(Request $request)
    {
        $the_file_path = null;

        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'required|mimes:png,jpg,jpeg|max:2000',
            ]);
            $the_file_path = $this->uploadImage('assets/img/brand', $request->logo);
        }

        // تحديث جدول الـ settings (بيانات الإيرادات والضرائب وعناوين فاتورة زدكا)
        $setting = settings::findOrFail(1);
        $setting->update([
            'name'                   => $request->camName_ar,
            'organization_name'      => $request->camName_ar,
            'organization_unit_name' => $request->camName_ar,
            'common_name'            => $request->camName_ar,
            'building_number'        => $request->buildnumber,
            'street_name'            => $request->StreetName,
            'registered_address'     => $request->StreetName,
            'region'                 => $request->region,
            'city'                   => $request->city,
            'trn'                    => $request->Tax,
            'crn'                    => $request->SR,
            'mobile'                 => $request->TEL,
            'plot_identification'    => $request->scander_number,
            'postal_number'          => $request->postcode,
            'email_address'          => $request->email ?? 'ebdeasoft@gmail.com',
        ]);

        // تحديث جدول الـ system_setting الرئيسي بطلب استعلام واحد فقط للأداء سريع
        $systemSetting = system_setting::findOrFail(1);
        $systemSetting->update([
            'name_ar'             => $request->camName_ar ?? $systemSetting->name_ar,
            'name_en'             => $request->camName_en ?? $systemSetting->name_en,
            'SR'                  => $request->SR ?? $systemSetting->SR,
            'Tax' => $request->Tax ?? $systemSetting->Tax,
            'logo'                => $the_file_path ?? $systemSetting->logo,
            'discount_on_invoice' => $request->discount_on_invoice ?? 100,
            'address_en'          => $request->address_en ?? $systemSetting->address_en,
            'address_ar'          => $request->address_ar,
            'deliveryCost'        => $request->deliveryCost ?? $systemSetting->deliveryCost,
            'serviceCost'         => $request->serviceCost ?? $systemSetting->serviceCost,
            'descriptionarbic'    => $request->descriptionarbic,
            'descriptionenglish'  => $request->descriptionenglish,
            'bank_acount_iban'    => $request->bank_acount_iban,
            'bank_acount_number'  => $request->bank_acount_number,
            'bankname'            => $request->bankname,
        ]);

        return view('system_setting.system_data', ['data' => $systemSetting]);
    }
}