<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans extends Model
{
    use HasFactory;
    protected $fillable=[ 
        'employee_id',
        'Loans_amount',
        'created_at'
           ];
    public function employee()
    {
        return $this->belongsTo(employee::class,'employee_id');
    }
}
