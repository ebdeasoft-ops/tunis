<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transferMoney_to_mainbranch extends Model
{
    use HasFactory;
    protected $fillable = [
        'Pay_Method_Name',
        'amount',
        'to_user_id',
        'from_user_id',
        'branchs_id',
        'status',
        'bank_transfer',
        'created_at',
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    public function fromuser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    public function branch()
    {
        return $this->belongsTo(branchs::class, 'branchs_id');
    }
}
