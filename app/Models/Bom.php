<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'finished_product_id',
        'output_quantity',
        'is_active',
        'notes',
    ];

    // المنتج النهائي التام
    public function finishedProduct()
    {
        return $this->belongsTo(products::class, 'finished_product_id');
    }

    // تفاصيل المواد الخام المطلوب صرفها
    public function items()
    {
        return $this->hasMany(BomItem::class, 'bom_id');
    }
}
