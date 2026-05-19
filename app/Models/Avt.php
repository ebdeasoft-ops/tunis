<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avt extends Model
{
    use HasFactory;
    protected $fillable = [
        'AVT',
        'name_ar',
        'name_en',
        'created_at',
        'updated_at',
     
    ];
}
