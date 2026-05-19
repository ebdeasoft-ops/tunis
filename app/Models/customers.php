<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
        ,'phone',
        'email',
        'comp_name',
        'tax_no',
         'address',
         'notes',
         'Limit_credit',
         'Balance',
         'opeing_blance',
         'mantob_account_id',
                  'street_name',
         'building_number',
         'plot_identification',
         'sub_city',
         'postcode',
                'CRN'


    ];

}
