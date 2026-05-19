<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    use HasFactory;
    protected $fillable=[
        'name','account_type','parent_account_number','account_number',
        'start_balance','current_balance','other_table_FK','notes'
        ,'created_at','updated_at','added_by','updated_by','com_code','date','active','is_parent','start_balance_status'
        ];

}
