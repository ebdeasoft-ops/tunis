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
        system_setting::updateOrCreate(
            ['id' => 1],
            [
                'name_ar'              => "مؤسسة دقة الابداع",
                'name_en'               => "Diqat elebdea Est",
                'SR'                    => "1010584112",
                'Tax'                   => "0",
                'logo'                  => "1779005627884.png",
                'address_ar'            => "المملكة العربية السعودية - الرياض - حي الخالدية - شارع ضرار بن الأزور - مبنى رقم 4405- تحويلة رقم 7841 - الرمز البريدي 12874 رقم الفرع الرئيسي - 0509828459",
                'address_en'            => "Kingdom of Saudi Arabia - Riyadh - Al Khalidiyah District Main Branch Number - 0509828459",
                'serviceCost'           => "10.00",
                'deliveryCost'          => "15.00",
                'descriptionarbic'      => "بيع قطع غيار شاحنات ومعدات صينيه",
                'descriptionenglish'    => "Sales spare parts",
                'discount_on_invoice'   => "15",
                'bank_acount_iban'      => "0",
                'bank_acount_number'    => "0",
                'bankname'              => "-",
                'branchs_id'            => 1,
                'previous_hash_invoice' => null,
                'invoices_count'        => 0,
            ]
        );
    }
}