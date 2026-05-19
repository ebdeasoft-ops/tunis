<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer_cash_to_the_next_day extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'currentamount',
        'user_id',
        'branchs_id',
        'note',
        'created_at',
        'updated_at',
    ];
    public function branch()
    {
        return $this->belongsTo(branchs::class, 'branchs_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
