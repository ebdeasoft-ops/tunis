<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_price_from_supplier_items extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id'
       ,'quantity',
       'order_id',
       'created_at',
       'updated_at',
   
   ];
   
     
       public function productData()
       {
           return $this->belongsTo(products::class,'product_id');
       }
}
