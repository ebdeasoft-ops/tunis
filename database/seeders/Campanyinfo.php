<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\system_setting;

class Campanyinfo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = system_setting::create([
            'name_ar'=>"ايبداع سوفت",
            'name_en'=>"Ebdeasoft ",
            'SR'=>"+++++++++",
            'Tax'=>"3*******************3",
            'logo'=>"logoprintpage.png",
            'address_ar'=>"الرياض - الخرج",
            'address_en'=>"Elkareg",
            
            ]);
    }
}
