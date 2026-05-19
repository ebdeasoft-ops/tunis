<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\Loans;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use  App\Models\departments;
use App\Models\Increaseـor_deduction_employee;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
     public function addnewDepartment()
     {
         //
         app()->setLocale(LaravelLocalization::getCurrentLocale());
 
         return view('hr.Add_new_department');
     }


     public function salarydecoument()
     {
         //
         app()->setLocale(LaravelLocalization::getCurrentLocale());
 
         return view('hr.salarydecoument');
     }
     


     public function Increaseـor_deduction()
     {
         //
         app()->setLocale(LaravelLocalization::getCurrentLocale());
 
         return view('hr.IncreaseOr_deduction');
     }

     


    public function index()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('hr.Add_employee');
    }

    public function updateEmployee($id)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
       $employee= employee::find($id);
        return view('hr.update_employee',compact('employee'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

      //  return $request;
   
        $employee=employee::create([
            'name_ar'=>$request->employee_name_ar,
            'name_en'=>$request->employee_name_en??"Name En",
            'email'=>$request->email??"Example@gmail.com",
            'phone'=>$request->phone,
            'department'=>$request->department,
            'salary'=>$request->salary,
            'nationality'=>$request->nationality,
            'personal_identification'=>$request->personal_identification,
            'old'=>$request->age,
            'sex'=>$request->sex,
            'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
        );
    
if( $employee!=null){
    $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة الموظف بنجاح':'  Employee created successfully';
        session()->flash('create_employee',$message);
}
return view('hr.Add_employee');

 } /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $this->validate($request, [
          
         
        ]);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $employee=departments::create([
            'name_ar'=>$request->department_name_ar,
            'name_en'=>$request->department_name_en??"Department",

            'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
        );
    
if( $employee!=null){
    $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة القسم بنجاح':'  Department created successfully';
        session()->flash('create_department',$message);
}
        return view('hr.Add_new_department');
    }



    

    public function print_decument_salary(Request $request)
    {
        
    //  return $request;
    app()->setLocale(LaravelLocalization::getCurrentLocale());

    $start=$request->end_at.'-01';
    $end=$request->end_at.'-30';
    // return $end;
    $list_salary_data=[];
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $employees=employee::get();
    foreach($employees as $employee)
    {
$bounes=0;
$discount=0;
$Loans=0;
foreach(Increaseـor_deduction_employee::where('employee_id',$employee->id)->whereDate('created_at','>=', $start) ->whereDate('created_at', '<=', $end) ->get()  as $Increaseـor_deduction_employee)
{
    $bounes+=$Increaseـor_deduction_employee->increase;
    $discount+=$Increaseـor_deduction_employee->deduction; 
}
foreach(Loans::where('employee_id',$employee->id)->whereDate('created_at','>=', $start) ->whereDate('created_at', '<=', $end) ->get()  as $item)
{
    $Loans+=$item->Loans_amount;
}
$list_salary_data[]=[
    'employeeData'=> $employee,
    'bounes'=>$bounes,
    'discount'=>$discount,
    'Loans'=>$Loans
    
        ];
    }

   

        return view('hr.employee_salary_list_print',compact('list_salary_data'))->with('month',$request->end_at);
    }




    public function Increaseـor_deduction_add(Request $request)
    {
        //
    // return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if($request->increasValue!=0)
        {
            $employee=Increaseـor_deduction_employee::create([
                'employee_id'=>$request->department,
                'deduction'=>$request->decreaseValue,
                'increase'=>$request->increasValue,
                'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
            );
        
    if( $employee!=null){
        $employee= employee::find($request->id);
        $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة مكافأة بنجاح ':' Add bonus successfully ';
            session()->flash('create_department',$message);
    }
            return view('hr.IncreaseOr_deduction');
        }
        
        else{
            $employee=Increaseـor_deduction_employee::create([
                'employee_id'=> $request->department,
                'deduction'=>$request->decreaseValue,
                'increase'=>$request->increasValue,
                'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
            );
        
    if( $employee!=null){
        $employee= employee::find($request->id);
        $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم إضافة الخصم بنجاح ':'The discount has been added successfully';
            session()->flash('create_department',$message);
    }
            return view('hr.IncreaseOr_deduction');
        }
    }







    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('hr.show_employee');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

       //  return $request;
  
          $employee=employee::where('personal_identification',$request->personal_identification)->update([
              'name_ar'=>$request->employee_name_ar,
              'name_en'=>$request->employee_name_en,
              'email'=>$request->email,
              'phone'=>$request->phone,
              'department'=>$request->department,
              'salary'=>$request->salary,
              'nationality'=>$request->nationality,
              'personal_identification'=>$request->personal_identification,
              'old'=>$request->age,
              'sex'=>$request->sex,
              'created_at'=> \Carbon\Carbon::now()->addHours(3),        ]
          );
      
  if( $employee!=null){
      $message=LaravelLocalization::getCurrentLocale()=='ar'?'تم تعديل بيانات الموظف بنجاح':'  Employee updated successfully';
          session()->flash('updated_employee',$message);
  }
  $employee= employee::where('personal_identification',$request->personal_identification)->first();
  //return $employee;
        return view('hr.update_employee',compact('employee'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(employee $employee)
    {
        //
    }
}
