<?php

namespace Database\Seeders;

use App\Models\Expenses_reasons;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * لازم يتشغل قبل ExpensesSeeder، لأن حقل reasonId_id في جدول expenses
     * بيشاور على الـ id هنا.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            ['id' => 36, 'expenses_reason' => 'زيت محرك للسيارة فرع الخرج', 'expensesAvt' => '1', 'expenses_reason_en' => 'Car engine oil Al Kharj branch'],
            ['id' => 37, 'expenses_reason' => 'صيانة كهرباء المحل', 'expensesAvt' => '1', 'expenses_reason_en' => 'صيانة كهرباء المحل'],
            ['id' => 38, 'expenses_reason' => 'الرواتب و الاجور', 'expensesAvt' => '1', 'expenses_reason_en' => 'الرواتب و الاجور'],
            ['id' => 39, 'expenses_reason' => 'مصاريف ادارة عمومية برامج المحاسبة', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف ادارة عمومية برامج المحاسبة'],
            ['id' => 40, 'expenses_reason' => 'مصارف كهرباء', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصارف كهرباء'],
            ['id' => 41, 'expenses_reason' => 'مصاريف ماء', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف ماء'],
            ['id' => 42, 'expenses_reason' => 'ايجار سكن و فنادق', 'expensesAvt' => '1', 'expenses_reason_en' => 'ايجار سكن و فنادق'],
            ['id' => 43, 'expenses_reason' => 'مصاريف هاتف و بريد', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف هاتف و بريد'],
            ['id' => 44, 'expenses_reason' => 'مصروفات ادوات مكتبية', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات ادوات مكتبية'],
            ['id' => 45, 'expenses_reason' => 'الايجارات', 'expensesAvt' => '1', 'expenses_reason_en' => 'الايجارات'],
            ['id' => 46, 'expenses_reason' => 'مصاريف دعاية واعلان', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف دعاية واعلان'],
            ['id' => 47, 'expenses_reason' => 'رسوم بلدية', 'expensesAvt' => '1', 'expenses_reason_en' => 'رسوم بلدية'],
            ['id' => 48, 'expenses_reason' => 'مصاريف الديون المعدومة', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف الديون المعدومة'],
            ['id' => 49, 'expenses_reason' => 'رسوم مخالفات', 'expensesAvt' => '1', 'expenses_reason_en' => 'رسوم مخالفات'],
            ['id' => 50, 'expenses_reason' => 'جوازات و تجديد اقامة و مكتب العمل', 'expensesAvt' => '1', 'expenses_reason_en' => 'جوازات و تجديد اقامة و مكتب العمل'],
            ['id' => 51, 'expenses_reason' => 'مصاريف تشغيل شهرية', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف تشغيل شهرية'],
            ['id' => 52, 'expenses_reason' => 'عمولة مبيعات', 'expensesAvt' => '1', 'expenses_reason_en' => 'عمولة مبيعات'],
            ['id' => 53, 'expenses_reason' => 'مصروفات شحن و تخليص جمركي', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات شحن و تخليص جمركي'],
            ['id' => 54, 'expenses_reason' => 'مصروفات ضيافة', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات ضيافة'],
            ['id' => 55, 'expenses_reason' => 'علاج', 'expensesAvt' => '1', 'expenses_reason_en' => 'علاج'],
            ['id' => 56, 'expenses_reason' => 'بدل اضافي', 'expensesAvt' => '1', 'expenses_reason_en' => 'بدل اضافي'],
            ['id' => 57, 'expenses_reason' => 'بدل تذاكر', 'expensesAvt' => '1', 'expenses_reason_en' => 'بدل تذاكر'],
            ['id' => 58, 'expenses_reason' => 'بدل اجازات', 'expensesAvt' => '1', 'expenses_reason_en' => 'بدل اجازات'],
            ['id' => 59, 'expenses_reason' => 'تصديق غرفة تجارية', 'expensesAvt' => '1', 'expenses_reason_en' => 'تصديق غرفة تجارية'],
            ['id' => 60, 'expenses_reason' => 'تصديق غرفة تجارية', 'expensesAvt' => '1', 'expenses_reason_en' => 'تصديق غرفة تجارية'],
            ['id' => 61, 'expenses_reason' => 'فحص السيارة', 'expensesAvt' => '1', 'expenses_reason_en' => 'فحص السيارة'],
            ['id' => 62, 'expenses_reason' => 'مكافاة نهاية الخدمة', 'expensesAvt' => '1', 'expenses_reason_en' => 'مكافاة نهاية الخدمة'],
            ['id' => 63, 'expenses_reason' => 'مصاريف وقود', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف وقود'],
            ['id' => 64, 'expenses_reason' => 'رسوم حكومية', 'expensesAvt' => '1', 'expenses_reason_en' => 'رسوم حكومية'],
            ['id' => 65, 'expenses_reason' => 'مصاريف التامينات الاجتماعية', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف التامينات الاجتماعية'],
            ['id' => 66, 'expenses_reason' => 'عمولات و مكافات', 'expensesAvt' => '1', 'expenses_reason_en' => 'عمولات و مكافات'],
            ['id' => 67, 'expenses_reason' => 'بدلات سكن', 'expensesAvt' => '1', 'expenses_reason_en' => 'بدلات سكن'],
            ['id' => 68, 'expenses_reason' => 'تامين السيارات', 'expensesAvt' => '1', 'expenses_reason_en' => 'تامين السيارات'],
            ['id' => 69, 'expenses_reason' => 'مستلزمات عمال', 'expensesAvt' => '1', 'expenses_reason_en' => 'مستلزمات عمال'],
            ['id' => 70, 'expenses_reason' => 'صيانة عامة', 'expensesAvt' => '1', 'expenses_reason_en' => 'صيانة عامة'],
            ['id' => 71, 'expenses_reason' => 'قطع غيار', 'expensesAvt' => '1', 'expenses_reason_en' => 'قطع غيار'],
            ['id' => 72, 'expenses_reason' => 'صيانة سيارات', 'expensesAvt' => '1', 'expenses_reason_en' => 'صيانة سيارات'],
            ['id' => 73, 'expenses_reason' => 'تغير زيت للمكينة', 'expensesAvt' => '1', 'expenses_reason_en' => 'تغير زيت للمكينة'],
            ['id' => 74, 'expenses_reason' => 'مصروفات النشاط التجاري', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات النشاط التجاري'],
            ['id' => 75, 'expenses_reason' => 'تكاليف المبيعات', 'expensesAvt' => '1', 'expenses_reason_en' => 'تكاليف المبيعات'],
            ['id' => 76, 'expenses_reason' => 'مردود المبيعات', 'expensesAvt' => '1', 'expenses_reason_en' => 'مردود المبيعات'],
            ['id' => 77, 'expenses_reason' => 'مسموحات وخصميات المبيعات', 'expensesAvt' => '1', 'expenses_reason_en' => 'مسموحات وخصميات المبيعات'],
            ['id' => 78, 'expenses_reason' => 'مصاريف اخري', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف اخري'],
            ['id' => 79, 'expenses_reason' => 'مصاريف اخري', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف اخري'],
            ['id' => 80, 'expenses_reason' => 'نقاط البيع', 'expensesAvt' => '1', 'expenses_reason_en' => 'نقاط البيع'],
            ['id' => 81, 'expenses_reason' => 'مصروفات نقل وشحن', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات نقل وشحن'],
            ['id' => 82, 'expenses_reason' => 'مصاريف عمال', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف عمال'],
            ['id' => 83, 'expenses_reason' => 'نقاط بيع الرياض وخميس مشيط', 'expensesAvt' => '1', 'expenses_reason_en' => 'نقاط بيع الرياض وخميس مشيط'],
            ['id' => 84, 'expenses_reason' => 'نقاط بيع الدمام', 'expensesAvt' => '1', 'expenses_reason_en' => 'نقاط بيع الدمام'],
            ['id' => 85, 'expenses_reason' => 'نقاط بيع جدة', 'expensesAvt' => '1', 'expenses_reason_en' => 'نقاط بيع جدة'],
            ['id' => 86, 'expenses_reason' => 'عموله مبيعات جميع الفروع', 'expensesAvt' => '1', 'expenses_reason_en' => 'عموله مبيعات جميع الفروع'],
            ['id' => 87, 'expenses_reason' => 'عموله مبيعات جدة', 'expensesAvt' => '1', 'expenses_reason_en' => 'عموله مبيعات جدة'],
            ['id' => 88, 'expenses_reason' => 'عموله مبيعات الرياض', 'expensesAvt' => '1', 'expenses_reason_en' => 'عموله مبيعات الرياض'],
            ['id' => 89, 'expenses_reason' => 'عموله مبيعات الدمام', 'expensesAvt' => '1', 'expenses_reason_en' => 'عموله مبيعات الدمام'],
            ['id' => 90, 'expenses_reason' => 'عموله مبيعات خميس', 'expensesAvt' => '1', 'expenses_reason_en' => 'عموله مبيعات خميس'],
            ['id' => 91, 'expenses_reason' => 'عمولات و مكافات', 'expensesAvt' => '1', 'expenses_reason_en' => 'عمولات و مكافات'],
            ['id' => 92, 'expenses_reason' => 'عمولات و مكافات الرياض', 'expensesAvt' => '1', 'expenses_reason_en' => 'عمولات و مكافات الرياض'],
            ['id' => 93, 'expenses_reason' => 'مصاريف اخري فرع الدمام', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصاريف اخري فرع الدمام'],
            ['id' => 94, 'expenses_reason' => 'تسويه حساب', 'expensesAvt' => '1', 'expenses_reason_en' => 'تسويه حساب'],
            ['id' => 95, 'expenses_reason' => 'تسويه حساب فرع الدمام', 'expensesAvt' => '1', 'expenses_reason_en' => 'تسويه حساب فرع الدمام'],
            ['id' => 96, 'expenses_reason' => 'تسويه حساب جده', 'expensesAvt' => '1', 'expenses_reason_en' => 'تسويه حساب جده'],
            ['id' => 97, 'expenses_reason' => 'اقساط بنك', 'expensesAvt' => '1', 'expenses_reason_en' => 'اقساط بنك'],
            ['id' => 98, 'expenses_reason' => 'بنزين سياره', 'expensesAvt' => '1', 'expenses_reason_en' => 'بنزين سياره'],
            ['id' => 99, 'expenses_reason' => 'مردود المتشريات البنكيه', 'expensesAvt' => '1', 'expenses_reason_en' => 'مردود المتشريات البنكيه'],
            ['id' => 100, 'expenses_reason' => 'نضافة عامه', 'expensesAvt' => '1', 'expenses_reason_en' => 'نضافة عامه'],
            ['id' => 101, 'expenses_reason' => 'المشتريات البنكيه', 'expensesAvt' => '1', 'expenses_reason_en' => 'المشتريات البنكيه'],
            ['id' => 102, 'expenses_reason' => 'شحن بين الفروع ج', 'expensesAvt' => '1', 'expenses_reason_en' => 'شحن بين الفروع ج'],
            ['id' => 103, 'expenses_reason' => 'مصروفات خاصه الدمام', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات خاصه الدمام'],
            ['id' => 104, 'expenses_reason' => 'مصروف علي عسيري الدمام', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروف علي عسيري الدمام'],
            ['id' => 105, 'expenses_reason' => 'مصروف علي عسيري جدة', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروف علي عسيري جدة'],
            ['id' => 106, 'expenses_reason' => 'رسوم بنكيه وتحويل', 'expensesAvt' => '1', 'expenses_reason_en' => 'رسوم بنكيه وتحويل'],
            ['id' => 107, 'expenses_reason' => 'نضافه محل', 'expensesAvt' => '1', 'expenses_reason_en' => 'نضافه محل'],
            ['id' => 108, 'expenses_reason' => 'رواتب سعوده', 'expensesAvt' => '1', 'expenses_reason_en' => 'رواتب سعوده'],
            ['id' => 109, 'expenses_reason' => 'مصروف محمد الخضر الرياض', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروف محمد الخضر الرياض'],
            ['id' => 110, 'expenses_reason' => 'مصروف علي عسيري الرياض', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروف علي عسيري الرياض'],
            ['id' => 111, 'expenses_reason' => 'فواتير كهربا', 'expensesAvt' => '1', 'expenses_reason_en' => 'فواتير كهربا'],
            ['id' => 112, 'expenses_reason' => 'مصروفات جدة خاص', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات جدة خاص'],
            ['id' => 113, 'expenses_reason' => 'مصروفات خاصه نظام', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات خاصه نظام'],
            ['id' => 114, 'expenses_reason' => 'التامينات الصحيه', 'expensesAvt' => '1', 'expenses_reason_en' => 'التامينات الصحيه'],
            ['id' => 115, 'expenses_reason' => 'ايجارات فروع ومستودعات', 'expensesAvt' => '1', 'expenses_reason_en' => 'ايجارات فروع ومستودعات'],
            ['id' => 116, 'expenses_reason' => 'فواتير نت وشحن رصيد الفروع', 'expensesAvt' => '1', 'expenses_reason_en' => 'فواتير نت وشحن رصيد الفروع'],
            ['id' => 117, 'expenses_reason' => 'مستحقات نهايه العقد', 'expensesAvt' => '1', 'expenses_reason_en' => 'مستحقات نهايه العقد'],
            ['id' => 118, 'expenses_reason' => 'مصروفات عمومية تعقيب وسعي', 'expensesAvt' => '1', 'expenses_reason_en' => 'مصروفات عمومية تعقيب وسعي'],
        ];

        foreach ($reasons as $reason) {
            Expenses_reasons::updateOrCreate(
                ['id' => $reason['id']],
                $reason
            );
        }
    }
}