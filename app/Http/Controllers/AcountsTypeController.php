<?php

namespace App\Http\Controllers;

use App\Models\acounts_type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class AcountsTypeController extends Controller
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
        
        return view('acountes.acount_type');  
    
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\acounts_type  $acounts_type
     * @return \Illuminate\Http\Response
     */
    public function show(acounts_type $acounts_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\acounts_type  $acounts_type
     * @return \Illuminate\Http\Response
     */
    public function edit(acounts_type $acounts_type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\acounts_type  $acounts_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, acounts_type $acounts_type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\acounts_type  $acounts_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(acounts_type $acounts_type)
    {
        //
    }
}
