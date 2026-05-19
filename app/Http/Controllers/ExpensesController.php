<?php

namespace App\Http\Controllers;
use App\Models\Expenses_reasons;
use App\Models\financial_accounts;

use App\Models\expenses;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class ExpensesController extends Controller
{

    function __construct() {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
      }
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateExpenses(Request $request)
    {
      
        $expense=expenses::find($request->transactionId);
        $reason_data=Expenses_reasons::find($request->reasoneupdate);
        if ($request->payupdate== 'Cash') {

            $financial_accounts= financial_accounts::find(5);
            financial_accounts::find(5)->update(
               [
                   'current_balance'=> $financial_accounts->current_balance-$request->cashreceivedupdate+ $expense->Theـamountـpaid
               ]
               ); 
        
            }
            if ($request->payupdate== 'Bank_transfer'||$request->payupdate== 'Shabka') {
        
            $financial_accounts= financial_accounts::find(4);
            financial_accounts::find(4)->update(
             [
                 'current_balance'=> $financial_accounts->current_balance-$request->cashreceivedupdate+ $expense->Theـamountـpaid
             ]
             ); 
            }
        $financial_accounts= financial_accounts::where('orginal_type',3)->where('orginal_id', $expense->reasonId_id )->first();
        financial_accounts::where('orginal_type',3)->where('orginal_id',$expense->reasonId_id )->update(
         [
             'current_balance'=> $financial_accounts->current_balance - $expense->Theـamountـpaid
         ]
         ); 


         $financial_accounts= financial_accounts::where('orginal_type',3)->where('orginal_id', $request->reasoneupdate )->first();
         financial_accounts::where('orginal_type',3)->where('orginal_id',$request->reasoneupdate )->update(
          [
              'current_balance'=> $financial_accounts->current_balance+$request->cashreceivedupdate
          ]
          ); 
     $expense=  expenses::find($request->transactionId)->update([

            'Pay_Method_Name'=>$request->payupdate,
            'Reasonforspendingmoney'=>$reason_data->expenses_reason,
            'reasonId_id'=>$request->reasoneupdate ,
            'expensesAvt'=>$reason_data->expensesAvt ,
            'updated_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'Theـamountـpaid'=>$request->cashreceivedupdate
        ]);
        $expense=expenses::find($request->transactionId);
      
        if ($request->payupdate == 'Cash') {
            $pay = __('report.cash');
        }elseif ($request->payupdate == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data=[
            'id'=>$expense->id,
                'user'=>Auth()->user()->name,
                'Pay_Method_Name'=>$pay,
                'Theـamountـpaid'=>$request->cashreceivedupdate,
                'expense'=>LaravelLocalization::getCurrentLocale()=='ar'? $reason_data->expenses_reason:($reason_data->expenses_reason_en=='-'?$reason_data->expenses_reason:$reason_data->expenses_reason_en) ,

       
        ];
        return $data;
    }

    public function getAndUpdateExpenses( $request)
    {
   
   
        $expense=expenses::find($request);
    if ($expense->Pay_Method_Name == 'Cash') {
            $pay = __('report.cash');
        }elseif ($expense->Pay_Method_Name == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
                $reason_data=Expenses_reasons::find($expense->reasonId_id);


        $data=[
                'id'=>$expense->id,
                'user'=>Auth()->user()->name,
                'Pay_Method_Name'=>$pay,
                'Theـamountـpaid'=>$expense->Theـamountـpaid,
                'expense'=>LaravelLocalization::getCurrentLocale()=='ar'? $reason_data->expenses_reason:($reason_data->expenses_reason_en=='-'?$reason_data->expenses_reason:$reason_data->expenses_reason_en) ,
        ];
        return $data;
    }



    public function store(Request $request)
    {
        //
      //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $this->validate($request, [
            'cashreceived' => 'required|numeric',
           
        ]);
        
        
                        $the_file_path='';
if ($request->has('attachments')) {
// $request->validate([
// 'Item_img' => 'required|mimes:png,jpg,jpeg,pdf|max:2000',
// ]);
$folder='assets//attachments';
$image=$request->attachments;
$extension = $image->extension();
$the_file_path  = time() . rand(100, 999) . '.' . $extension;
$image->getClientOriginalName = $the_file_path
;
$image->move($folder, $the_file_path
);
}
        $reason_data=Expenses_reasons::find($request->reasone);
        $financial_accounts= financial_accounts::where('orginal_type',3)->where('orginal_id', $request->reasone )->first();
        financial_accounts::where('orginal_type',3)->where('orginal_id',$request->reasone )->update(
         [
             'current_balance'=> $financial_accounts->current_balance + $request->cashreceived
         ]
         ); 


         if ($request->pay== 'Cash') {

            $financial_accounts= financial_accounts::find(5);
            financial_accounts::find(5)->update(
               [
                   'current_balance'=> $financial_accounts->current_balance-$request->cashreceived
               ]
               ); 
        
            }
            if ($request->pay== 'Bank_transfer'||$request->pay== 'Shabka') {
        
            $financial_accounts= financial_accounts::find(4);
            financial_accounts::find(4)->update(
             [
                 'current_balance'=> $financial_accounts->current_balance-$request->cashreceived
             ]
             ); 
            }

     $expense=  expenses::create([
              'attachments'=>$the_file_path,

            'user_id'=>Auth()->user()->id,
            'Pay_Method_Name'=>$request->pay,
            'branchs_id'=>Auth()->user()->branchs_id,
            'Reasonforspendingmoney'=>$reason_data->expenses_reason,
            'reasonId_id'=>$request->reasone ,
            'notes'=>$request->notes??'-' ,
            'expensesAvt'=>$reason_data->expensesAvt ,
            'created_at'  =>  $request->date, 
            'updated_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'Theـamountـpaid'=>$request->cashreceived
        ]);
        if ($request->pay == 'Cash') {
            $pay = __('report.cash');
        }  elseif ($request->pay == 'Bank_transfer') {
            $pay = __('home.Bank_transfer');
        } else {
            $pay = __('report.shabka');
        }
        $data=[
            'id'=>$expense->id,
                'user'=>Auth()->user()->name,
                'Pay_Method_Name'=>$pay,
                'Theـamountـpaid'=>$request->cashreceived,
                'expense'=>LaravelLocalization::getCurrentLocale()=='ar'? $reason_data->expenses_reason:($reason_data->expenses_reason_en=='-'?$reason_data->expenses_reason:$reason_data->expenses_reason_en) ,

       
        ];
        return $data;
        return view('acountes.cash expense',compact('data'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function ExpensesOwner(Request $request)
    {
        //
      //  return $request;

      //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $this->validate($request, [
            'cashreceived' => 'required|numeric',
           
        ]);
        $expense=expenses::create([
            'user_id'=>Auth()->user()->id,
            'Pay_Method_Name'=>$request->pay,
            'Reasonforspendingmoney'=>$request->reasone,
            'reasonId_id'=>$request->reasonId_id,
            'created_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'updated_at'  =>  \Carbon\Carbon::now()->addHours(3), 
            'Theـamountـpaid'=>$request->cashreceived
        ]);
        if ($request->pay == 'Cash') {
            $pay = __('report.cash');
        } else {
            $pay = __('report.shabka');
        }
        $data=[
                'id'=>  $expense->id,
                'user'=>Auth()->user()->name,
                'Pay_Method_Name'=>$pay,
                'Theـamountـpaid'=>$request->cashreceived,
                'Reasonforspendingmoney'=>$request->reasone,

         
        ];
        return $data;
        return view('acountes.Expensesowner',compact('data'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit(expenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, expenses $expenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(expenses $expenses)
    {
        //
    }
}
