<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
    
    $permissions = [
    
        'Home',

        'Sales' ,
        'Sales products',
        'sales return',
        'Previous sales invoices',


        'Purchases',
        'Purchases products',
        'purchase return',
        'purchase order to resources',
        'Previous purchase invoices',


        'Quotations',
        'request price from supplier',
        'offer price to customer',


        'Available quantity',


        'Receipt',
        'Confirm product delivery',
        'Previous receipt documents',


        'Produects',
        'product damage',
        'Stock adjustment',
        'Product data change',
        'Transferring a product to another branch',
        'Receiving a product from another branch',


        'Reports',
        'budget sheet',
        'Transfers to master branch',
        'Customer exceeded grace period',
        'Bank Statement',
        'Transfer cash to a bank Rep',
        'Sales report',
        'Bank transfers',
        'Sales return report',
        'Product sales',
        'Employee sales',
        'Sales profit',
        'Purchase orders from suppliers',
        'A price offer to the customer',
        'Delivery notes',
        'Request a quote from supplier',
        'Puchases from supplier',
       'Refound of resource purchases',
       'Purchases report',
        'Customer purchases',
        'Credit collection',
        'List of suppliers',
        'List of customers',
        'Supplier credit payment',
        'Shift details',
         'Expenses',
        'stok quantity',
        'Product damage reports',
        'Transfer of products',
        'Best selling product',
        'VAT',
    
    


            'Accounts',
            'Receipt document',
            'Voucher',
            'Cash expenses',
            'Expenses for the owner',
            'Add cach from bank',
            'Transfer to main branch',
            'Confirm transfer of master branch',
            'Transfer cash to a bank',
            'Transfer cash to the next day',
    


            'User and branches',
            'Create a new branch',
            'add branch',
            'List of users',
            'Create a vendor',
            'Users permissions',
    

            'Human Resource',
            'Employee',
            'Add new employee',
            'create a department',
            'Increase or deduction',
            'Salary document',
    


            'Subprocesses',
            'Add new product',
            'Add a new customer',
            'Add new supplier',
            'enpenses_reason',

           



            'Setting',
            'AVT',
            'System setting',
            'Branches',
            

            'Technical support',



            'Notification',
    
    ];
    
    $permissions_ar = [
    
        'الرئيسية',

        'المبيعات' ,
        'المبيعات',
        'مرتجع المبيعات',
        'فواتير المبيعات السابقة',

        'المشتريات',
        'المشتريات',
        'مرتجع المشتريات',
        'أمر شراء الي المورد',
        'فواتير المشتريات السابقة',


        'التسعيرات',
        'طلب اسعار من المورد',
        'عرض اسعار للعميل',


        'عرض الكمية المتوفرة للعميل',


        'سند استلام',
        "تاكيد تسليم منتج",
        'المستندات الاستلام السابقة',


        'المنتجات',
        'اتلاف منتج',
        'تعديل كمية المخزون',
        'تغير بيانات المنتج',
        'ارسال منتج الي فرع اخري',
        'استلام منتج من فرع اخري',


        'التقارير',
        'الميزانية العمومية',
        "التحويلات لفرع الرئيسي",
        'العملاء تجاوزة فترة السماح',
        'كشف الحساب البنكي',
        " ايداع من  البنك",
        "تحويل نقدي الصندوق الي البنك",
        'مبيعات المنتجات',
        'مرتجع المبيعات',
        'مبيعات منتج',
        'مبيعات موظف',
       'أرباح المبيعات',
       'أوامر الشراء من الموردين',
        'تقرير عرض أسعار للعميل',
        'ملاحظات التسليم',
        'طلبات عرض سعر من المواردين',
        'مشتريات من مورد',
        'مرتجع مشتريات من  مورد',
         'مشتريات البضاعة',
        'مشتريات العملاء',
        'تحصيل الأجل',
        'قائمة الموردين',
        'قائمة العملاء',
        'الدفع الأجل للمورد',
        'تفاصيل الوردية',
        'المصروفات',
        'كمية وقيمة المخزون',
        ' تقارير اتلاف المنتجات',
        "حركة المنتجات بين الفروع",
        'منتجات الأكثر مبيعا',
        'ضريبة القيمة المضافة',
    
    


            'الحسابات',
            'سند صرف',
            'سند قبض',
            'مصروفات نقدية',
            'مصروفات المالك',
            'اضافة نقدي من البنك',
            'التحويل إلى الفرع الرئيسي',
            'تاكيد التحويل لفرع الرئيسي',
            "تحويل نقدي الصندوق لبنك",
            "ترحيل النقدية ليوم التالي",
    


            'المستخدمين و الفروع',
            'إنشاء فرع جديد',
            'اضافة فرع',
            'قائمة المستخدمين',
            'إنشاء بائع',
            'صلاحيات المستخدمين',
    

            'الموارد البشرية',
            'قائمة الموظفين',
            'اضافة موظف جديد',
            'انشاء قسم جديد',
            'زيادة او خصم للموظف',
            'مستند المرتبات',
    


            'العمليات الفرعية',
            'اضافة منتج جديد',
            'اضافة عميل جديد',
            'اضافة مورد جديد',
            'اضافة غرض الصرف',

           



            'الاعدادت',
            'الضريبة',
            'اعدادات النظام',
            'الفروع',
            

            'التواصل مع الدعم الفني',



            'الاشعارات',
    
    ];
      

    $i=0;
    foreach ($permissions as $permission) {
    
    Permission::create(['name' => $permission,'name_ar'=>$permissions_ar[$i]]);
    $i++;
    }
    
    
    }
    }

