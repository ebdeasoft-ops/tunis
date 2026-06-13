<?php

namespace App\Http\Controllers;

use App\Models\financial_accounts;
use App\Models\branchs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BranchsController extends Controller
{
    public function getChildBranches(Request $request) {
    // جلب الفروع التي يكون الـ branch_id الخاص بها مساوياً للـ id المختار
    $data = branchs::where('branch_id', $request->id)->get();
    return response()->json($data);
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('users.Add_branch');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // التحقق من المدخلات الأساسية
        $request->validate([
            'breanchName' => 'required|string|max:255',
            'branchLoction' => 'nullable|string|max:255',
        ]);

        try {
            // استخدام الترانزأكشن لحماية البيانات في حال حدوث خطأ
            DB::transaction(function () use ($request) {
                
                // 1. إنشاء الفرع
                $createBranch = branchs::create([
                    'name' => $request->breanchName,
                    'place' => $request->branchLoction,
                    'created_at' => Carbon::now()->addHours(3)
                ]);

                // 2. تعريف الحسابات المالية المراد إنشاؤها للفرع بشكل ديناميكي
                $accountsStructure = [
                    ['name' => 'الخزينة فرع ', 'type' => 1, 'parent' => 5],
                    ['name' => 'حساب البنك فرع ', 'type' => 1, 'parent' => 4],
                    ['name' => 'ضريبة القيمة المضافة فرع ', 'type' => 1, 'parent' => 102],
                    ['name' => 'المبيعات فرع ', 'type' => 3, 'parent' => 112],
                    ['name' => 'مردود المبيعات فرع ', 'type' => 3, 'parent' => 184],
                    ['name' => 'مخزون فرع ', 'type' => 1, 'parent' => 181],
                    ['name' => 'تكاليف المبيعات فرع ', 'type' => 1, 'parent' => 183],
                    ['name' => 'مردود المشتريات النقدية فرع ', 'type' => 4, 'parent' => 159],
                    ['name' => 'مردود المشتريات الاجلة فرع ', 'type' => 4, 'parent' => 160],
                    ['name' => 'المشتريات الاجلة فرع ', 'type' => 4, 'parent' => 158],
                    ['name' => 'المشتريات النقدية فرع ', 'type' => 4, 'parent' => 1222],
                ];

                $currentTime = Carbon::now()->addHours(3);

                // 3. إنشاء الحسابات تباعاً مع توليد رقم حساب فريد لكل حساب
                foreach ($accountsStructure as $acc) {
                    
                    // جلب آخر رقم حساب في قاعدة البيانات فوراً قبل الإدخال (لقفل الحساب ومنع الـ Race Condition)
                    $lastAccount = financial_accounts::lockForUpdate()->latest('id')->first();
                    $nextAccountNumber = $lastAccount ? ($lastAccount->account_number + 1) : 1000;

                    financial_accounts::create([
                        'name'                  => $acc['name'] . ' ' . $request->breanchName,
                        'account_type'          => $acc['type'],
                        'parent_account_number' => $acc['parent'],
                        'account_number'        => $nextAccountNumber,
                        'start_balance'         => 0,
                        'current_balance'       => 0,
                        'start_balance_status'  => 3,
                        'other_table_FK'        => NULL,
                        'notes'                 => NULL,
                        'added_by'              => 1, // يفضل مستقبلاً استبدالها بـ auth()->id()
                        'updated_by'            => NULL,
                        'com_code'              => 1,
                        'date'                  => $currentTime,
                        'active'                => 1,
                        'is_parent'             => 0,
                        'orginal_id'            => NULL,
                        'orginal_type'          => NULL,
                        'branchs_id'            => $createBranch->id,
                    ]);
                }
            });

            session()->flash('create', 'تم انشاء الفرع والحسابات المالية التابعة له بنجاح');
        } catch (\Exception $e) {
            // في حال فشل أي جزء، سيتراجع النظام عن كل شيء تلقائياً دون التأثير على قواعد البيانات
            session()->flash('notcreate', 'حدثت مشكلة أثناء إنشاء الفرع: ' . $e->getMessage());
        }

        return redirect()->back();
    }
   public function addwherehouse(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        // التحقق من المدخلات الأساسية
        $request->validate([
            'breanchName' => 'required|string|max:255',
            'branchLoction' => 'nullable|string|max:255',
        ]);

        try {
            // استخدام الترانزأكشن لحماية البيانات في حال حدوث خطأ
            DB::transaction(function () use ($request) {
                
                // 1. إنشاء الفرع
                $createBranch = branchs::create([
                    'name' => $request->breanchName,
                    'place' => $request->branchLoction,
                    'branch_id' => $request->branchs_id,
                    'type' => 1,
                    'created_at' => Carbon::now()->addHours(3)
                ]);

                // 2. تعريف الحسابات المالية المراد إنشاؤها للفرع بشكل ديناميكي
                $accountsStructure = [
                    
                ];

                $currentTime = Carbon::now()->addHours(3);

                // 3. إنشاء الحسابات تباعاً مع توليد رقم حساب فريد لكل حساب
                foreach ($accountsStructure as $acc) {
                    
                    // جلب آخر رقم حساب في قاعدة البيانات فوراً قبل الإدخال (لقفل الحساب ومنع الـ Race Condition)
                    $lastAccount = financial_accounts::lockForUpdate()->latest('id')->first();
                    $nextAccountNumber = $lastAccount ? ($lastAccount->account_number + 1) : 1000;

                    financial_accounts::create([
                        'name'                  => $acc['name'] . ' ' . $request->breanchName,
                        'account_type'          => $acc['type'],
                        'parent_account_number' => $acc['parent'],
                        'account_number'        => $nextAccountNumber,
                        'start_balance'         => 0,
                        'current_balance'       => 0,
                        'start_balance_status'  => 3,
                        'other_table_FK'        => NULL,
                        'notes'                 => NULL,
                        'added_by'              => 1, // يفضل مستقبلاً استبدالها بـ auth()->id()
                        'updated_by'            => NULL,
                        'com_code'              => 1,
                        'date'                  => $currentTime,
                        'active'                => 1,
                        'is_parent'             => 0,
                        'orginal_id'            => NULL,
                        'orginal_type'          => NULL,
                        'branchs_id'            => $createBranch->id,
                    ]);
                }
            });

$message = app()->getLocale() == 'ar' 
    ? 'تم إنشاء المخزن الفرعي بنجاح' 
    : 'The sub-store has been created successfully';

session()->flash('create', $message);


} catch (\Exception $e) {
            // في حال فشل أي جزء، سيتراجع النظام عن كل شيء تلقائياً دون التأثير على قواعد البيانات
            session()->flash('notcreate', 'حدثت مشكلة أثناء إنشاء الفرع: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $branches = branchs::get();
        return view('Branches', compact('branches'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function updatebranch(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $request->validate([
            'id' => 'required|exists:branchs,id',
            'breanchName' => 'required|string|max:255',
            'branchLoction' => 'nullable|string|max:255',
        ]);

        branchs::where('id', $request->id)->update([
            'name'  => $request->breanchName,
            'place' => $request->branchLoction
        ]);

        return redirect()->route('branches.show')->with('success', 'تم تعديل بيانات الفرع بنجاح');
    }
}