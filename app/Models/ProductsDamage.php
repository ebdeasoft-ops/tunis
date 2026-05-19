<?php

namespace App\Models;
use App\Models\branchs;
use App\Models\products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsDamage extends Model
{
    use HasFactory;
    protected $fillable = [
        'damage_quantity',
        'product_id',
        'product_name',
        'branchs_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
    public function branch()
    {
        return $this->belongsTo(branchs::class,'branchs_id');
    }
    public function productData()
    {
        return $this->belongsTo(products::class,'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
