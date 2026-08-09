<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'employee_id',
        'contract_type',
        'start_date',
        'end_date',
        'basic_salary',
        'iqama_expiry_date',
        'work_permit_expiry_date',
    ];

    /**
     * علاقة العقد بالموظف
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}