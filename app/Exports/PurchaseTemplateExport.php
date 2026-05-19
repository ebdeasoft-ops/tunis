<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class PurchaseTemplateExport implements FromArray, WithHeadings
{
    // تحديد عناوين الأعمدة المطلوبة
    public function headings(): array
    {
        return [
            'product_name_ar',  // اسم المنتج عربي
            'product_name_en',  // اسم المنتج إنجليزي
            'product_code',     // رقم المنتج (الكود)
            'sale_price',       // سعر البيع
            'price',            // سعر التكلفة (الشراء)
            'quantity',         // الكمية
            'location',         // الموقع
            'refnumber'         // الرقم المرجعي
        ];
    }

    // نترك البيانات فارغة لأنه مجرد نموذج (Template)
    public function array(): array
    {
        return [];
    }
}