<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock_update extends Model
{
    use HasFactory;

    protected $fillable = [
        'productdecrease',
        'productincrease',
        'product_id',
        'branchs_id',
        'product_name',
        'user_id',
        'created_at',
        'updated_at',
        'note'
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
