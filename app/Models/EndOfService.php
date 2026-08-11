<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndOfService extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'join_date',
        'end_date',
        'service_years',
        'basic_salary',
        'reason',
        'reward_amount',
        'notes'
    ];

    public function employee()
    {
        return $this->belongsTo(employee::class);
    }
}