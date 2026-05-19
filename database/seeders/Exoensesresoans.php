<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expenses_reasons;
class Exoensesresoans extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  
        Expenses_reasons::create([
            'expenses_reason'=>'وقود سيارة',
            'created_at'=>\Carbon\Carbon::now()->addHours(3),
                  ]);
    }
}
