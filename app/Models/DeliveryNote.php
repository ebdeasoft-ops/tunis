<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'customer_id', 'quantity', 'code'];

    /**
     * العلاقة مع المنتج
     */
    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }
    public function customer()
    {
        return $this->belongsTo(customers::class, 'customer_id');
    }
           public function items()
    {
        return $this->hasMany(DeliveryNoteItem::class);
    }

}
