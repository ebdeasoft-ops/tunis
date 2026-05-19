<?php

namespace App\Http\Controllers;

use App\Models\transferMoney_to_mainbranch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class TransferMoneyToMainbranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingtransfers()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
return view('acountes.pendingtransfers');

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
    public function print_Transfer_Main_Branch(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $data=transferMoney_to_mainbranch::find($request->id);
       return view('acountes.print_Transfertomainbranch', compact('data'));



    }

    public function updateTransfertomainbranchnotconfirm(Request $request)
    {
        //
       // return $request;
       $transferMoney_to_mainbranch =transferMoney_to_mainbranch::find($request->transactionId);
            $data=transferMoney_to_mainbranch::find($request->transactionId)->update([
            'amount'=>$request->cashreceivedupdate,
            'to_user_id'=>$request->usertoupdate,
    
            'created_at'=> \Carbon\Carbon::now()->addHours(3),
        ]);
        $data =transferMoney_to_mainbranch::find($request->transactionId);

        $data=[
            'amount'=>$request->cashreceived,
            'created_at'=>$data->created_at->format('d/m/Y'),
            'id'=> $data->id
        ];
        return view('acountes.pendingtransfers');
        //  return view('acountes.print_Transfertomainbranch', compact('data'));
       
    }
    public function updateTransfertomainbranch(Request $request)
    {
        //
       $transferMoney_to_mainbranch =transferMoney_to_mainbranch::find($request->transactionId);
            $data=transferMoney_to_mainbranch::find($request->transactionId)->update([
            'amount'=>$request->cashreceived,
            'to_user_id'=>$request->userto,
            'created_at'=> \Carbon\Carbon::now()->addHours(3),
        ]);
        $data =transferMoney_to_mainbranch::find($request->transactionId);

        $data=[
            'Pay_Method_Name'=>$request->shabka,
            'amount'=>$request->cashreceived,
            'bank_transfer'=>$request->Bank_transferupdate,
            'created_at'=>$data->created_at->format('d/m/Y'),
            'id'=> $data->id
        ];
        return $data;
      //  return view('acountes.print_Transfertomainbranch', compact('data'));
       
    }
    public function store(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
         $data=transferMoney_to_mainbranch::create([
            'Pay_Method_Name'=>$request->pay,
            'amount'=>$request->cashreceived,
            'to_user_id'=>$request->userto,
            'from_user_id'=>Auth()->user()->id,
            'branchs_id'=>Auth()->user()->branchs_id,
            'created_at'=> \Carbon\Carbon::now()->addHours(3),
        ]);
       
        $data=[
            'Pay_Method_Name'=>$request->pay,
            'amount'=>$request->cashreceived,
            'created_at'=>$data->created_at->format('d/m/Y'),
            'id'=> $data->id
        ];
        return $data;
      //  return view('acountes.print_Transfertomainbranch', compact('data'));
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transferMoney_to_mainbranch  $transferMoney_to_mainbranch
     * @return \Illuminate\Http\Response
     */
    public function show( $transferMoney_to_mainbranch)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data=transferMoney_to_mainbranch::where('id',$transferMoney_to_mainbranch)->update([
           
           'status'=>1,
           'updated_at'=> \Carbon\Carbon::now()->addHours(3),
       ]);
       if($data==1){
            $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم  تأكيد عملية التحويل بنجاح':'Transfer confirmation has been modified successfully.';
            session()->flash('transferupdated', $message);
        
        
       }
       $data=[];       
       return view('acountes.confirm_transferTomainBranch',compact('data'));
    }


    public function rejectTransfarToMainBranch( $transferMoney_to_mainbranch)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data=transferMoney_to_mainbranch::where('id',$transferMoney_to_mainbranch)->update([
           
           'status'=>2,
           'updated_at'=> \Carbon\Carbon::now()->addHours(3),
       ]);
       if($data==1){
            $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم  رفض عملية التحويل بنجاح':'Transfer confirmation has been reject successfully.';
            session()->flash('transferupdated', $message);
        
        
       }
       $data=[];       
       return view('acountes.confirm_transferTomainBranch',compact('data'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transferMoney_to_mainbranch  $transferMoney_to_mainbranch
     * @return \Illuminate\Http\Response
     */
    public function edit(transferMoney_to_mainbranch $transferMoney_to_mainbranch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\transferMoney_to_mainbranch  $transferMoney_to_mainbranch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, transferMoney_to_mainbranch $transferMoney_to_mainbranch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transferMoney_to_mainbranch  $transferMoney_to_mainbranch
     * @return \Illuminate\Http\Response
     */
    public function destroy(transferMoney_to_mainbranch $transferMoney_to_mainbranch)
    {
        //
    }
}
