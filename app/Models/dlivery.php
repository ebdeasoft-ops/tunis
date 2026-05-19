<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dlivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'to_dlivery_id',
        'blance',
        'number_items',
        'last_payment',
        'note',
        'created_at',
        'updated_at',
    ];
    public function supllier()
    {
        return $this->belongsTo(customers::class, 'to_dlivery_id');
    }
}
