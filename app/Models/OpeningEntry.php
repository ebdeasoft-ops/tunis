<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpeningEntry extends Model
{
    // تحديد اسم الجدول إذا كان مختلفاً عن الجمع التلقائي
    protected $table = 'opening_entries';

    // الحقول المسموح بتعبئتها
    protected $fillable = [
        'entry_number', 
        'entry_date', 
        'general_note', 
        'total_amount'
    ];

    /**
     * العلاقة: رأس القيد لديه العديد من التفاصيل
     */
    public function items()
    {
        return $this->hasMany(credittransactions::class, 'Opening_entry', 'id');
    }
}