<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNoteItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_note_id',
        'product_id',
        'quantity'
    ];

    // العلاقة مع إذن التسليم
    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    // العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(products::class);
    }
}
