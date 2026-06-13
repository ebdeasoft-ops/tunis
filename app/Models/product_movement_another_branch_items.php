<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_movement_another_branch_items extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id' ,
        'product_id' ,
        'quantity' ,
        'cost_per_each_withoud_tax' ,
        'created_at' ,
        'updated_at' ,
              'order_id_sender',
        'reciver_branch'
    ];
public function movement()
{
    // افترضنا هنا أن اسم الموديل الخاص بالحركة هو product_movement_another_branch 
    // تأكد من اسم الموديل الصحيح لديك
    return $this->belongsTo(product_movement_another_branch::class, 'order_id');
}
    public function product()
       {
           return $this->belongsTo(products::class,'product_id');
       }

}
