<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class temp_sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id'
       ,'invoice_id',
       'branch_id',
       'save',
       'reamingQuantity',
       'Discount_Value',
       'Added_Value',
        'Unit_Price',
        'quantity',
        'discountreturn',
         'quantityreturn',
         'created_at',
         'unit',
         'note'


   ];
   
       public function Invoice()
       {
           return $this->belongsTo(temp_invoice::class,'invoice_id');
       }
       
       public function productData()
       {
           return $this->belongsTo(products::class,'product_id');
       }
       public function branch()
       {
           return $this->belongsTo(branchs::class,'branch_id');
       }
}
