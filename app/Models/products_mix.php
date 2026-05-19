<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products_mix extends Model
{
    use HasFactory;

    
    protected $fillable = [
     
         'created_at',
         'cost_withoud_tax',
         'mixcode',
          'name',
          'location',
          'branchs_id' 


   ];
}
