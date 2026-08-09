<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingExpense extends Model
{
    use HasFactory;
    protected $table = 'manufacturing_expenses';

    protected $fillable = [
        'expense_number',
        'manufacturing_order_id',
        'expense_date',
        'expense_type',
        'amount',
        'notes',
        'created_by',
    ];

    public function manufacturingOrder()
    {
        return $this->belongsTo(ManufacturingOrder::class, 'manufacturing_order_id');
    }
}
