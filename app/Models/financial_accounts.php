<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financial_accounts extends Model
{
    use HasFactory;
    protected $fillable=[
        'name','account_type','parent_account_number','account_number',
        'start_balance','current_balance','other_table_FK','notes'
        ,'created_at','updated_at','added_by','updated_by','com_code','date','active','is_parent','start_balance_status','orginal_id',
        'orginal_type',
        'orginal_supplier',
        'debtor_end',
        'creditor_end',
        'debtor_current',
        'creditor_current',
        'debtor_opening',
        'creditor_opening',
        'branchs_id',
        'tax_no'
        ];
public function credittransactions()
{
    // customer_id هو العمود الذي يربط العمليات بالحساب المالي
    return $this->hasMany(credittransactions::class, 'customer_id');
}
        public function acounts_type()
        {
            return $this->belongsTo(acounts_type::class,'account_type');
        }
        public function parent_account()
        {
            return $this->belongsTo(financial_accounts::class,'parent_account_number');
        }
            public function branch()
        {
            return $this->belongsTo(branchs::class,'branchs_id');
        }
}
