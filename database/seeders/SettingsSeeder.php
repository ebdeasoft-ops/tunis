<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * ملاحظة أمان مهمة:
     * الحقول دي بتاعة تكامل هيئة الزكاة والضريبة (ZATCA / فاتورة) وفيها بيانات
     * حساسة جداً (المفتاح الخاص، الشهادات، الـ secret، الـ CSID... الخ).
     * لو حطينا القيم دي كنص صريح جوه الملف، أي حد يشوف الكود (على GitHub
     * أو مع أي مطور تاني) هيقدر ياخد المفاتيح دي ويوقّع فواتير باسم منشأتك.
     * لذلك القيم الحساسة هنا بتتقرأ من ملف .env بدل ما تتكتب صريحة.
     *
     * @return void
     */
    public function run()
    {
        settings::updateOrCreate(
            ['id' => 1],
            [
                'name'                    => "مؤسسة دقة الابداع",
                'mobile'                  => "966509828459",
                'trn'                     => "0",
                'crn'                     => "1010584112",
                'street_name'             => "ضرار بن الازور",
                'building_number'         => "4405",
                'plot_identification'     => "7841",
                'region'                  => "حي الخالدية",
                'city'                    => "الرياض",
                'postal_number'           => "12874",
                'egs_serial_number'       => "1-ABD|2-ABC|3-ABC",
                'business_category'       => "بيع قطع غيار شاحنات ومعدات صينيه",
                'common_name'             => "مؤسسة دقة الابداع",
                'organization_unit_name'  => "مؤسسة دقة الابداع",
                'organization_name'       => "مؤسسة دقة الابداع",
                'country_name'            => "SA",
                'registered_address'      => "ضرار بن الازور",
                'email_address'           => "ebdeasoft@gmail.com",
                'invoice_type'            => "1100",
                'is_production'           => 1,
                'company_id'              => 1,
                'invoices_count'          => 5285,
                'previous_hash_invoice'   => "ys29Vd3buRZxked7t834kENhoy4ezK3cKuPFR4sbLEY=",
                'branchs_id'              => 1,

                // ==== القيم الحساسة: بتتجاب من .env ولازم تتحط هناك يدوياً ====
                'otp'                     => env('ZATCA_OTP'),
                'cnf'                     => env('ZATCA_CNF'),
                'private_key'             => env('ZATCA_PRIVATE_KEY'),
                'public_key'              => env('ZATCA_PUBLIC_KEY'),
                'csr_request'             => env('ZATCA_CSR_REQUEST'),
                'certificate'             => env('ZATCA_CERTIFICATE'),
                'secret'                  => env('ZATCA_SECRET'),
                'csid'                    => env('ZATCA_CSID'),
                'production_certificate'  => env('ZATCA_PRODUCTION_CERTIFICATE'),
                'production_secret'       => env('ZATCA_PRODUCTION_SECRET'),
                'production_csid'         => env('ZATCA_PRODUCTION_CSID'),
            ]
        );
    }
}