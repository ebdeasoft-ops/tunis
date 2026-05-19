<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_product_to_the_customer extends Model
{
    use HasFactory;

    
            protected $fillable = [
                'userdelivery_id' ,
                'branch_from',
                'branch_to' ,
                'user_from' ,
                'product_id',
                'invoice_id' ,
                'quantity' ,
                'status',
                'created_at',
                'updated_at'
            ];
            public function productData()
            {
                return $this->belongsTo(products::class,'product_id');
            }
            public function branchfrom()
            {
                return $this->belongsTo(branchs::class,'branch_from');
            }
            public function branchto()
            {
                return $this->belongsTo(branchs::class,'branch_to');
            }
            public function userfrom()
            {
                return $this->belongsTo(User::class,'user_from');
            }
          
}
