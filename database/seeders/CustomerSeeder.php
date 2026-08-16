<?php

namespace Database\Seeders;

use App\Models\customers;
use App\Models\financial_accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * لكل عميل بيتم إنشاء سجل في جدول customers، وسجل مرتبط في جدول
     * financial_accounts (الحساب الفرعي التابع لحساب العملاء الرئيسي رقم 2)
     * بنفس المنطق المستخدم في الكنترولر عند إضافة عميل جديد.
     *
     * @return void
     */
    public function run()
    {
        // 1. جلب حساب الأب الرئيسي للعملاء (استبدل الـ ID لو حساب العملاء عندك مختلف)
        $parentAccount = financial_accounts::find(2);
        $parentAccountNumber = $parentAccount ? $parentAccount->account_number : 12;

        // 2. تحديد أول رقم حساب فرعي متاح تبع للأب رقم 2
        $maxAccountNumber = financial_accounts::where('parent_account_number', 2)
            ->max('account_number');

        $nextAccountNumber = $maxAccountNumber
            ? $maxAccountNumber + 1
            : (int) ($parentAccountNumber . '1');

        // العميل رقم 1 (cash customer)
        DB::transaction(function () use (&$nextAccountNumber, $parentAccountNumber) {
            $newCustomer = customers::updateOrCreate(
                ['id' => 1],
                [
                    'name' => 'cash customer ',
                    'phone' => '0543577633',
                    'email' => 'Example@gmail.com',
                    'comp_name' => 'Mohamed Adham',
                    'address' => 'Kharj',
                    'notes' => 'لا توجد ملاحظات',
                    'Limit_credit' => '10000.00',
                    'Balance' => '257.50',
                    'grace_period_in_days' => '30',
                    'tax_no' => '0',
                    'opeing_blance' => '0',
                    'postcode' => '11461',
                    'sub_city' => 'Kharj',
                    'street_name' => 'KSA ,Kharj',
                    'building_number' => '0',
                    'plot_identification' => '0',
                    'CRN' => '0',
                ]
            );

            financial_accounts::firstOrCreate(
                ['orginal_id' => $newCustomer->id, 'orginal_type' => 1],
                [
                    'name' => $newCustomer->name,
                    'account_type' => 1,
                    'parent_account_number' => 2,
                    'account_number' => $nextAccountNumber,
                    'start_balance' => 0,
                    'current_balance' => 0,
                    'start_balance_status' => 3,
                    'other_table_FK' => null,
                    'notes' => null,
                    'added_by' => auth()->user()->id ?? 1,
                    'updated_by' => null,
                    'com_code' => 1,
                    'date' => Carbon::now()->addHours(3),
                    'active' => 1,
                    'is_parent' => 0,
                    'tax_no' => '0',
                ]
            );

            $nextAccountNumber++;
        });

     

    }
}