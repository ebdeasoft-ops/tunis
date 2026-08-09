<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'bom_id',
        'raw_material_id',
        'quantity',
        'unit_id',
        'scrap_percentage',
        'notes',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(products::class, 'raw_material_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
