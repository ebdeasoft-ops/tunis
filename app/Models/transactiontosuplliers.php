<?php

namespace App\Models;
use App\Models\financial_accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactiontosuplliers extends Model
{
    use HasFactory;
    protected $fillable = [
        'suplier_id',
        'paidـamount',
        'user_id',
        'Pay_Method_Name',
        'branchs_id',
        'note',
        'currentblance',
        'created_at',
        'updated_at',
        'attachments',
        'orginal_type',
        'orginal_id',
        'dely_record',
        'debtor',
        'creditor'

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
          public function financial_accounts_data()
    {
        return $this->belongsTo(financial_accounts::class,'suplier_id');
    }
    public function branch()
    {
        return $this->belongsTo(branchs::class, 'branchs_id');
    }
    public function supllier()
    {
        return $this->belongsTo(supllier::class, 'orginal_id');
    }
}
