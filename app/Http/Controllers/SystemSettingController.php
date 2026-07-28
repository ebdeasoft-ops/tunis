<?php

namespace App\Http\Controllers;


use App\Models\system_setting;
use App\Models\settings;
use App\Helpers\General;
use App\Services\Zatca\OnBoarding;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class SystemSettingController extends Controller
{
    function uploadImage($folder, $image)
    {
        $extension = strtolower($image->extension());
        $filename = time() . rand(100, 999) . '.' . $extension;
        $image->getClientOriginalName = $filename;
        $image->move($folder, $filename);
        return $filename;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = system_setting::where('branchs_id',Auth()->user()->branchs_id)->first();
        return view('system_setting.system_data', compact('data'));
    }

 public function onbourding()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = system_setting::where('branchs_id',Auth()->user()->branchs_id)->first();
        return view('system_setting.onbourding', compact('data'));          //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = settings::where('branchs_id',Auth()->user()->branchs_id)->update([

            'invoice_type'=>$request->invoicetype,
            'is_production'=>$request->typeconnect,
            'otp'=>$request->otp,

            ]);


            $setting = settings::where('branchs_id',Auth()->user()->branchs_id)->first();

            //$setting->is_production?'core':'simulation'
                 // first csid;
                 $response = (new OnBoarding())
                 ->setZatcaEnv($setting->is_production?'core':'simulation')
                 ->setZatcaLang('en')
                 ->setEmailAddress( $setting->email_address)
                 ->setCommonName($setting->common_name)
                 ->setCountryCode('SA')
                 ->setOrganizationUnitName($setting->organization_unit_name)
                 ->setOrganizationName($setting->organization_name)
                 ->setEgsSerialNumber('1-SDSA|2-FGDS|3-SDFG')
                 ->setVatNumber($setting->trn)
                 ->setInvoiceType($setting->invoice_type)
                 ->setRegisteredAddress($setting->registered_address)
                 ->setAuthOtp( $setting->otp)
                 ->setBusinessCategory($setting->business_category)
                 ->getAuthorization();

         // return $response['message'];
                 if ($response['success']) {
                     $data = $response['data'];
                     settings::where('branchs_id',Auth()->user()->branchs_id)->update([
                         // from this line value assigned here this mean column name in database
                         'cnf' => $data['configData'],
                         'private_key' => $data['privateKey'],
                         'public_key' => $data['publicKey'],
                         'csr_request' => $data['csrKey'],
                         'certificate' => $data['complianceCertificate'],
                         'secret' => $data['complianceSecret'],
                         'csid' => $data['complianceRequestID'],
                         'production_certificate' =>$data['productionCertificate'] ,
                         'production_secret' =>$data['productionCertificateSecret'] ,
                         'production_csid' =>$data['productionCertificateRequestID']
                     ]);

                     session()->flash('RegisterDone', 'تم الربط مع الزكاة بنجاح شكرا     The link with Zakat has been completed successfully Thank you');
                     return view('system_setting.onbourding', compact('data'));
                 } else {
                     $data = system_setting::where('branchs_id',Auth()->user()->branchs_id)->first();
                 session()->flash('ERROR',$response['message']);

                     return view('system_setting.onbourding', compact('data'));
                 }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function show(system_setting $system_setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function edit(system_setting $system_setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

          if ($request->has('logo')) {
            $request->validate([
                'logo' => 'required|mimes:png,jpg,jpeg|max:2000',
            ]);
            $the_file_path = $this->uploadImage('assets//img//brand', $request->logo);
        }

        $data = settings::where('branchs_id',Auth()->user()->branchs_id)->update([
            'name'=>$request->camName_ar,
            'organization_name'=>$request->camName_ar,
            'organization_unit_name'=>$request->camName_ar,
            'common_name'=>$request->camName_ar,
            'building_number'=>$request->buildnumber,
            'street_name'=>$request->StreetName,
            'registered_address'=>$request->StreetName,
            'region'=>$request->region,
            'city'=>$request->city,
            'trn'=>$request->Tax,
            'crn'=>$request->SR,
            'mobile'=>$request->TEL,
            'plot_identification'=>$request->scander_number,
            'postal_number'=>$request->postcode,
            'business_category'=>$request->descriptionarbic,
            'email_address'=>$request->email??'ebdeasoft@gmail.com',
            ]);



        $data = system_setting::where('branchs_id',Auth()->user()->branchs_id)->first();
        system_setting::where('branchs_id',Auth()->user()->branchs_id)->update([
            'name_ar' => $request->camName_ar ?? $data->name_ar,
            'name_en' => $request->camName_en ?? $data->name_en,
            'SR' => $request->SR ?? $data->SR,
            'Tax' => $request->Tax ?? $data->Tax,
            'logo' => $the_file_path ?? $data->logo,
            'logo' => $the_file_path ?? $data->logo,
            'discount_on_invoice' => $request->discount_on_invoice ?? 100,
            'address_en' => $request->address_en ?? $data->address_en,
            'address_ar' => $request->address_ar ,
            'deliveryCost' => $request->deliveryCost ?? $data->deliveryCost,
            'serviceCost' => $request->serviceCost ?? $data->serviceCost,
            'descriptionarbic' => $request->descriptionarbic,
            'descriptionenglish' =>  $request->descriptionenglish,
            'bank_acount_iban' =>  $request->bank_acount_iban,
            'bank_acount_number' =>  $request->bank_acount_number,
            'bankname' =>  $request->bankname,
        ]);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = system_setting::where('branchs_id',Auth()->user()->branchs_id)->first();
        return view('system_setting.system_data', compact('data'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(system_setting $system_setting)
    {
        //
    }
}