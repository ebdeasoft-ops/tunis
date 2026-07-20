<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class StockUpdateTempleteExport implements FromArray, WithHeadings
{
    // تحديد عناوين الأعمدة المطلوبة
    public function headings(): array
    {
        return [
            'product_name',  // اسم المنتج عربي
            'product_code',     // رقم المنتج (الكود)
            'quantity',         // الكمية
        ];
    }

    // نترك البيانات فارغة لأنه مجرد نموذج (Template)
    public function array(): array
    {
        return [];
    }
}