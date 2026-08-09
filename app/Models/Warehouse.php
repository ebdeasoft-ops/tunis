<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $table = 'warehouses'; // تأكد من اسم الجدول لديك في قواعد البيانات

    protected $fillable = [
        'name_ar',
        'name_en',
        'code',
        'address',
        'is_active',
    ];

    // علاقة المخزن بأوامر الإنتاج كـ (مخزن صرف خام)
    public function rawMaterialOrders()
    {
        return $this->hasMany(ManufacturingOrder::class, 'raw_material_warehouse_id');
    }

    // علاقة المخزن بأوامر الإنتاج كـ (مخزن إنتاج تحت التشغيل WIP)
    public function wipOrders()
    {
        return $this->hasMany(ManufacturingOrder::class, 'wip_warehouse_id');
    }

    // علاقة المخزن بأوامر الإنتاج كـ (مخزن منتج تام)
    public function finishedGoodsOrders()
    {
        return $this->hasMany(ManufacturingOrder::class, 'finished_goods_warehouse_id');
    }
}
