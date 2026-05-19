<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\supllier;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class orderTosupllier extends Model
{
    use HasFactory;

    protected $fillable = [
        'suplier_id',
        'user_id',
        'Limit_credit',
        'purchaseـamount',
        'added_value'
    
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function supllier()
{
    return $this->belongsTo(supllier::class,'suplier_id');
}
}
