<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrSetting extends Model
{
    use HasFactory;
    protected $fillable = [
    'official_check_in', 
    'official_check_out', 
    'grace_period_minutes',
    'weekend_days',
    'overtime_hour_rate'
];
}
