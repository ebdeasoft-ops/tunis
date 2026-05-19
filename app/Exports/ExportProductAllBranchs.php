<?php

namespace App\Exports;

use App\Models\products;
use Maatwebsite\Excel\Concerns\FromQuery; // استخدام FromQuery بدلاً من FromCollection
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportProductAllBranchs implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $branch_id;

    public function __construct($branch_id)
    {
        $this->branch_id = $branch_id;
    }

    // استخدام Query لتقليل استهلاك الذاكرة
    public function query()
    {
        $query = products::query()->with(['branch', 'product_group_data']);

        if ($this->branch_id && $this->branch_id !== '-') {
            $query->where('branchs_id', $this->branch_id);
        }

        return $query; // نعيد الـ Query وليس الـ Get
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->Product_Code,
            $product->product_name,
            $product->branch->name ?? 'N/A',
            $product->product_group_data->group_ar ?? 'N/A',
            $product->Product_Location,
            $product->numberofpice == 0 ? "0" :$product->numberofpice,
            $product->purchasingـprice=== 0 ? "0" :$product->purchasingـprice,
            $product->Wholesale_price == 0 ? "0" :$product->Wholesale_price,
            $product->sale_price == 0 ? "0" :$product->sale_price,
            $product->refnumber == null ? __('home.notdata') : str_replace("+", " - ", $product->refnumber),
            $product->notes,
        ];
    }

    public function headings(): array
    {
        return [
            "#", "كود المنتج", "اسم المنتج", "الفرع", "المجموعة", "الموقع",
            "الكمية", "سعر الشراء", "سعر الجملة", "سعر البيع", "رقم المرجع", "ملاحظات"
        ];
    }
}