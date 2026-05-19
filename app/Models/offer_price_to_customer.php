<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class offer_price_to_customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'created_at',
        'updated_at',
        'notes',
        'discount',
        'numbershowstatus',
        'branchs_id',
        'numbershowstatus'
    
    ];
  
    public function customer()
{
    return $this->belongsTo(customers::class,'customer_id');
}
public function branch()
{
    return $this->belongsTo(branchs::class,'branchs_id');
}
}
