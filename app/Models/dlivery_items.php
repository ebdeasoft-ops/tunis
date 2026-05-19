<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dlivery_items extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'product_name',
        'quantity',
        'Added_value',
        'states',
        'cost',
        'supplier_id',
        'discount',
        'created_at',
        'updated_at',
    ];
    public function supllier()
    {
        return $this->belongsTo(customers::class, 'to_dlivery_id');
    }
    public function productData()
    {
        return $this->belongsTo(products::class,'product_id');
    }
}
