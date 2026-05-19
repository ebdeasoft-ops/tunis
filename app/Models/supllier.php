<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supllier extends Model
{
    protected $fillable = [
        'invoice_number',
        'name',
        'phone',
        'location',
        'email',
        'In_debt',
        'comp_name',
        'TaxـNumber',
        'mantob_account_id'
    ];
}
