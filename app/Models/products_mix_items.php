<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products_mix_items extends Model
{
    use HasFactory;


    protected $fillable = [
   
        'created_at',
         'note',
         'product_id',
         'cost' ,
         'quantity' ,
         'products_mix_id',
         'Added_Value'
  ];


  public function productData()
{
   return $this->belongsTo(products::class,'product_id');
}

}
