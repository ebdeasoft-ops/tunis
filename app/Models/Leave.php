<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'days_count',
        'deduction_amount', // تم إضافته ليتوافق مع جدول قاعدة البيانات
        'reason',
        'status',
    ];

    public function employee() 
    {
        return $this->belongsTo(employee::class);
    }
}