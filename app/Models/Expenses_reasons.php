<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses_reasons extends Model
{
    use HasFactory;
    protected $fillable = [
        'expenses_reason',
        'expenses_reason_en', 
        'expensesAvt', 
        'created_at',
        'updated_at',
    ];

    
}
