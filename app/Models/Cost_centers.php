<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Cost_centers extends Model
{
    use HasFactory;
    protected $fillable = [
        'cost_center_ar',
        'cost_center_en', 
        'expensesAvt', 
        'created_at',
        'updated_at',
    ];

    
}
