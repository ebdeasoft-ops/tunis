<?php

namespace App\Http\Controllers;

use App\Models\Loans;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('hr.Loans');  
        //
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

      
        //return $request;
        $d=Loans::create([
            'employee_id'=>$request->employee_id,
            'Loans_amount'=>$request->Loans_amount,
            'created_at' => \Carbon\Carbon::now()->addHours(3),
        ]);
        if( $d!=null){
            $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة السلفة بنجاح':'  Loans created successfully';
                session()->flash('create_department',$message);
        }
        return view('hr.Loans');  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function show( $loans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function edit( $loans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $loans)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loans  $loans
     * @return \Illuminate\Http\Response
     */
    public function destroy( $loans)
    {
        //
    }
}
