<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // جلب المستخدمين مع الأدوار الخاصة بهم لتجنب مشكلة الـ N+1 Query في الـ View
        $data = User::with('roles')->orderBy('id', 'DESC')->paginate(20);
        
        return view('users.show_users', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 20); // تم تعديل الضرب إلى 20 ليتناسب مع الباجينيشن
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.Add_user', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
{
    $this->validate($request, [
        'name'       => 'required|string|max:255',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|same:confirm-password',
        'roles_name' => 'required'
    ]);

    // 1. أضفنا 'roles_name' هنا لكي يتم إرسالها إلى قاعدة البيانات
    $input = $request->only(['name', 'email', 'password', 'roles_name']);
    
    // 2. تشفير كلمة المرور
    $input['password'] = Hash::make($input['password']);

    // 3. الآن User::create ستنجح لأن 'roles_name' موجودة في $input
    $user = User::create($input);
    
    // 4. إسناد الدور (هذا الجزء خاص بـ Spatie Roles)
    $user->assignRole($request->input('roles_name'));

    return redirect()->route('users.index')
                    ->with('success', 'User created successfully');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */





public function getEmployeesByBranch($branch_id)
{
    // جلب المستخدمين الذين يطابق فرعهم الـ branch_id الممرر
    $employees = User::where('branchs_id', $branch_id)->get(['id', 'name']);
    
    // إرجاع البيانات كـ JSON لـجافا سكريبت
    return response()->json($employees);
}



public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // جلب كل الأدوار عدا الأدمن لحماية النظام
        $roles = Role::where('name', '!=', 'Admin')->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $id,
            'password'    => 'nullable|same:confirm-password', // جعلناه nullable في حال لم يرغب بتغييرها
            'roles_name'  => 'required',
            'active'      => 'required',
            'branchs_id'  => 'required',
        ]);

        $user = User::findOrFail($id);

        // جلب البيانات الأساسية للمستخدم فقط من الريكويست
        $input = $request->only(['name', 'email', 'password', 'active', 'branchs_id']);
        
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }

        $user->update($input);

        // استخدام الدالة الاحترافية للحزمة لمزامنة الأدوار وحذف القديم تلقائياً
        $user->syncRoles($request->input('roles_name'));

        return redirect()->route('users.index')
                        ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        
        // حماية نظام: منع المستخدم من حذف نفسه بالخطأ
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')
                            ->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', 'User deleted successfully');
    }
}