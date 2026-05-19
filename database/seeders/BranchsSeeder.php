<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\branchs;

class BranchsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        branchs::create([
            'name'=>'الرياض',
            'place'=> 'الرياض',
            'created_at'=>\Carbon\Carbon::now()->addHours(3),
                  ]);

    }
}
