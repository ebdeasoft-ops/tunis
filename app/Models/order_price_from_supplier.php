<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\supllier;

class order_price_from_supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'suplier_id',
        'created_at',
        'updated_at',
    
    ];
  
    public function supllier()
{
    return $this->belongsTo(supllier::class,'suplier_id');
}
}
