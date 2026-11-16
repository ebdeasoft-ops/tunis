<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * صلاحية "عرض سعر الشراء" في صفحة المبيعات ونافذة اختيار المنتج.
 * تُمنح لأدوار Admin و MANAGER (كان العرض سابقاً مقصوراً على المستخدمين 11 و 17 و 28).
 */
return new class extends Migration
{
    private string $name = 'Show purchase price';

    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $values = ['guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()];
        if (Schema::hasColumn('permissions', 'name_ar')) {
            $values['name_ar'] = 'عرض سعر الشراء';
        }

        DB::table('permissions')->updateOrInsert(['name' => $this->name, 'guard_name' => 'web'], $values);
        $permissionId = DB::table('permissions')->where('name', $this->name)->where('guard_name', 'web')->value('id');

        foreach (DB::table('roles')->whereIn('name', ['Admin', 'MANAGER'])->pluck('id') as $roleId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $roleId,
            ]);
        }

        $this->forgetPermissionCache();
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('name', $this->name)->value('id');
        if ($permissionId) {
            DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('model_has_permissions')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }
        $this->forgetPermissionCache();
    }

    private function forgetPermissionCache(): void
    {
        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
};
