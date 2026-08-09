<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoMaterialIssue extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'manufacturing_order_id',
        'raw_material_id',
        'planned_quantity',
        'issued_quantity',
        'unit_cost',
        'total_cost',
        'issue_date',
        'notes',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(products::class, 'raw_material_id');
    }
    public function manufacturingOrder()
    {
        return $this->belongsTo(ManufacturingOrder::class, 'manufacturing_order_id');
    }
}
