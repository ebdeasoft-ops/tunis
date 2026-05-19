<?php

namespace App\Http\Controllers;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

use App\Models\Cash_withdrawal_from_the_bank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashWithdrawalFromTheBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function Cash_withdrawal_from_the_bank(Request $request)
    {

        //eturn $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = Cash_withdrawal_from_the_bank::create(
            [
                'from_user_id' => Auth()->user()->id,
                'amount' => $request->cashreceived,
                'branchs_id' => $request->branchs_id,
                'note' => $request->notes ?? "-",
                'created_at' => $request->start_at,
            ]
        );
        $data = [
            'user' => Auth()->user()->name,
            'amount' => $request->cashreceived,
            'branchs_id' => $data->branch->name,
            'note' => $request->notes ?? "-",
            'created_at' => $request->start_at,
            'id' => $data->id
        ];
        return $data;
        return view('acountes.convertcashboxToBank', compact('data'));
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
     * @param  \App\Models\Cash_withdrawal_from_the_bank  $cash_withdrawal_from_the_bank
     * @return \Illuminate\Http\Response
     */
    public function show(Cash_withdrawal_from_the_bank $cash_withdrawal_from_the_bank)
    {
        //
    }
    public function printwithdrawal_from_the_bank(Request $request)
    {
        if ($request == null) {
            $data = [];
            session()->flash('nodataprint', '');
            return view('acountes.convertcashboxToBank', compact('data'));
        }

        //eturn $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = Cash_withdrawal_from_the_bank::find($request->id);
      //    return $data;
      return view('acountes.printCash_withdrawal_from_the_bank', compact('data'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cash_withdrawal_from_the_bank  $cash_withdrawal_from_the_bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Cash_withdrawal_from_the_bank $cash_withdrawal_from_the_bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cash_withdrawal_from_the_bank  $cash_withdrawal_from_the_bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cash_withdrawal_from_the_bank $cash_withdrawal_from_the_bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cash_withdrawal_from_the_bank  $cash_withdrawal_from_the_bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cash_withdrawal_from_the_bank $cash_withdrawal_from_the_bank)
    {
        //
    }
}
