<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'type',
        'status',
        'check_in',   // أضف هذا الحقل
        'check_out',  // أضف هذا الحقل
    ];

    public function employee() 
    {
        return $this->belongsTo(employee::class, 'employee_id');
    }
}