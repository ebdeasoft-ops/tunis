<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custody extends Model
{
    use HasFactory;

    protected $table = 'custodies';

    protected $fillable = [
        'employee_id',
        'item_name',
        'serial_number',
        'delivery_date',
        'return_date',
        'status',
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}