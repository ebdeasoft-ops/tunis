<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_ar'
        ,'name_en',
        'email',
        'phone',
        'department',
        'salary',
        'nationality',
        'old',
        'sex',
        'personal_identification',
        'created_at',
         'updated_at',
    ];


    public function departments()
    {
        return $this->belongsTo(departments::class,'department');
    }
}
