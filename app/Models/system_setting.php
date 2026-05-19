<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class system_setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_ar',
        'name_en',
        'SR',
        'Tax',
        'logo',
        'address_ar',
        'address_en',
       
        'descriptionenglish',
        'descriptionarbic',
        'discount_on_invoice',
        'bankname',
        'bank_acount_number',
        'bank_acount_iban',
      
    ];
}
