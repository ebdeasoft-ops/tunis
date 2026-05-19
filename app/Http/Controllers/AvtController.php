<?php

namespace App\Http\Controllers;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

use App\Models\Avt;
use Illuminate\Http\Request;

class AvtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('setting.VAT');
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
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

      $avt=  Avt::create([
'name_ar'=>$request->name_avt_ar,
'name_en'=> $request->name_avt_en ,
'AVT'=> $request->vat_rate /100 ,
'created_at'=>\Carbon\Carbon::now()->addHours(3),
      ]);
      if($avt!=null){
        $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إنشاء الضريبة بنجاح':'  Tax created successfully'
       ;
        session()->flash('create_vat',$message);
      }
    
      
      return view('setting.VAT');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Avt  $avt
     * @return \Illuminate\Http\Response
     */
    public function show(Avt $avt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Avt  $avt
     * @return \Illuminate\Http\Response
     */
    public function edit(Avt $avt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Avt  $avt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
       // return $request;
       app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avt=  Avt::where('id',$request->id)->update([
            'AVT'=> $request->vat_rate /100 ,
            'updated_at'=>\Carbon\Carbon::now()->addHours(3),
                  ]);
                  if($avt!=null){
                    $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم تعديل الضريبة بنجاح':'  Tax updated successfully'
                   ;
                    session()->flash('edit_vat',$message);
                  }
                
                  
                  return view('setting.VAT');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Avt  $avt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
     //   return $request;
     app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avt=  Avt::where('id',$request->id)->delete();
       // return $avt;
                  if($avt==1){
                    $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم حذف الضريبة بنجاح':'  Tax deleted successfully'
                   ;
                    session()->flash('detete_vat',$message);
                  }
                
                  
                  return view('setting.VAT');
    }
}
