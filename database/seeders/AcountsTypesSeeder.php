<?php

namespace Database\Seeders;

use App\Models\acounts_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcountsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['id' => 1, 'name_ar' => 'الاصول', 'name_en' => 'Main money', 'active' => '1', 'relatediternalaccounts' => '0'],
            ['id' => 2, 'name_ar' => 'الخصوم', 'name_en' => 'Expenses', 'active' => '1', 'relatediternalaccounts' => '1'],
            ['id' => 3, 'name_ar' => 'الايردات', 'name_en' => 'CLIENT', 'active' => '1', 'relatediternalaccounts' => '1'],
            ['id' => 4, 'name_ar' => 'المصروفات', 'name_en' => 'representative', 'active' => '1', 'relatediternalaccounts' => '1'],
            ['id' => 5, 'name_ar' => 'حقوق الملكية', 'name_en' => 'EMPLOYEE', 'active' => '1', 'relatediternalaccounts' => '1'],
        ];

        foreach ($types as $type) {
            acounts_type::updateOrCreate(
                ['id' => $type['id']],
                $type
            );
        }
    }
}