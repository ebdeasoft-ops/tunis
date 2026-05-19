<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RoleController extends Controller
{
    /**
     * إعدادات الحماية واللغة للـ Controller بالكامل
     */
    public function __construct()
    {
        // توحيد اللغة بدلاً من تكرارها في كل دالة
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // دمج الصلاحيات في سطر واحد نظيف وسهل القراءة
        $this->middleware('permission:Users permissions');
    }

    /**
     * عرض قائمة الأدوار المتاحة بالنظام
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        
        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * واجهة إنشاء دور جديد
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create', compact('permission'));
    }
    
    /**
     * حفظ الدور الجديد مع ربط صلاحياته
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|unique:roles,name',
            'permission' => 'required|array', // التأكد من أنها مصفوفة
        ]);
    
        // استخدام guard_name الافتراضي مباشرة أو تمريره صراحة
        $role = Role::create([
            'name'       => $request->input('name'),
            'guard_name' => 'web'
        ]);
        
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success', __('Role created successfully'));
    }

    /**
     * عرض تفاصيل دور معين والصلاحيات المرتبطة به
     * تم تطبيق الـ Route Model Binding هنا تلقائياً لتقليل استعلامات الـ find
     */
    public function show(Role $role)
    {
        // الاعتماد على علاقة Eloquent الأساسية بدلاً من الـ Raw Join المكرر
        $rolePermissions = $role->permissions;
    
        return view('roles.show', compact('role', 'rolePermissions'));
    }
    
    /**
     * واجهة تعديل الدور
     */
    public function edit(Role $role)
    {
        $permission = Permission::get();
        
        // جلب معرفات الصلاحيات بطريقة Eloquent السريعة والمباشرة
        $rolePermissions = $role->permissions()->pluck('id', 'id')->all();
    
        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }
    
    /**
     * تحديث بيانات الدور وصلاحياته
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'       => 'required|unique:roles,name,' . $role->id, // استثناء الـ ID الحالي لمنع خطأ التكرار عند الحفظ
            'permission' => 'required|array',
        ]);
    
        $role->update([
            'name'       => $request->input('name'),
            'guard_name' => 'web'
        ]);
    
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success', __('Role updated successfully'));
    }

    /**
     * حذف الدور بشكل آمن ونظيف تماماً
     */
    public function destroy(Role $role)
    {
        // الطريقة القديمة عبر الـ DB Query كانت تترك مخلفات في جداول الصلاحيات الوسيطة
        // الحذف من خلال الـ Model يضمن تفعيل الـ Cascade Deletion لبيانات الـ Relations المرتبطة بالدور
        $role->delete();

        return redirect()->route('roles.index')
                        ->with('success', __('Role deleted successfully'));
    }
}