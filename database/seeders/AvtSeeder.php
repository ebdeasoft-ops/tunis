<?php

namespace Database\Seeders;
use App\Models\Avt;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
          Avt::create([
            'name_ar'=>'نسبة ضريبة مبيعات',
            'name_en'=> 'sales tax rate',
            'AVT'=> 0.15  ,
            'created_at'=>\Carbon\Carbon::now()->addHours(3),
                  ]);

                    Avt::create([
                    'name_ar'=>'نسبة ضريبة المشتريات',
                    'name_en'=> 'Purchase tax rate',
                    'AVT'=> 0.15  ,
                    'created_at'=>\Carbon\Carbon::now()->addHours(3),
                          ]);
    }
}
