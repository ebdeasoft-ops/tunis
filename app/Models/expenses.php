<?php

namespace App\Models;
use App\Models\Expenses_reasons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenses extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id'
        ,'Pay_Method_Name',
        'Reasonforspendingmoney',
        'branchs_id',
        'expensesAvt',
        'notes',
        'created_at',
         'updated_at',
         'Theـamountـpaid',
         'reasonId_id',
         'attachments',
         'Transaction_id',
         'type'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(branchs::class,'branchs_id');
    }
    public function Expenses_reasons()
    {
        return $this->belongsTo(Expenses_reasons::class,'reasonId_id');
    }
}
