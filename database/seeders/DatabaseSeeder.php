<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * الترتيب هنا مهم:
     * 1) الصلاحيات والأدوار وأنواع الحسابات (عشان أي حاجة بعد كده تقدر تعتمد عليها).
     * 2) اليوزرز (الأدمن + باقي اليوزرز).
     * 3) الفروع وبيانات الشركة والإعدادات العامة.
     * 4) أسباب المصروفات (لازم قبل أي مصروفات لأنها بتشاور عليها).
     * 5) العملاء (لازم يجي قبل الحسابات المالية).
     * 6) الحسابات المالية (شجرة الحسابات، بتشاور على العملاء والفروع).
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // الصلاحيات والأدوار
            PermissionTableSeeder::class,
            AcountsTypesSeeder::class,

            // اليوزرز
            CreateAdminUserSeeder::class,
            UserSeeder::class,

            // الفروع
            BranchsSeeder::class,

            // بيانات الشركة والإعدادات
            Campanyinfo::class,
            SettingsSeeder::class,
            AvtSeeder::class,

            // أسباب المصروفات (لازم قبل ExpensesSeeder)
            ExpensesReasonsSeeder::class,

            // العملاء (لازم يجي قبل الحسابات المالية)
            CustomerSeeder::class,

            // الحسابات المالية (شجرة الحسابات كاملة)
            FinancialAccountsSeeder::class,
        ]);
    }
}