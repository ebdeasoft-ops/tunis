<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyRecord extends Model
{
    // تحديد اسم الجدول إذا كان مختلفاً عن الجمع التلقائي
    protected $table = 'daily_records';

    // الحقول المسموح بتعبئتها (Mass Assignment)
    protected $fillable = [
        'date',
        'general_notes',
        'total_debit',
        'total_credit',
        'user_id'
    ];

    /**
     * علاقة رأس القيد بسطور العمليات (واحد لمتعدد)
     * تسمح لك بجلب السطور كالتالي: $record->transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'record_id');
    }

    /**
     * علاقة القيد بالمستخدم الذي أنشأه
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}