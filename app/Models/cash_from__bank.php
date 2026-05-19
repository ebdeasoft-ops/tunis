<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cash_from__bank extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'branchs_id',
        'the_amount',
        'payment_method',
        'created_at',
        'updated_at',
        'notes'
    ];
    public function branch()
    {
        return $this->belongsTo(branchs::class, 'branchs_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
