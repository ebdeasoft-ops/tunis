<?php

namespace Database\Seeders;
use App\Models\customers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        customers::create(
            [
                'name'=>'Cash customer',
                'email'=>'Customer@gmail.com',
                'comp_name'=>'Customer',
                'address'=> "Client Address",
                'phone'=>'0555543566'  ,
                'notes'=>"لا توجد ملاحظات ",
                'Limit_credit'=>'100000',
                'grace_period_in_days'=>'30'
            ]
            );
    }
}
