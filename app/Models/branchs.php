<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branchs extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'place',
        'created_at',
        'updated_at',
     
    ];
    
}
