<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'bom_id',
        'finished_product_id',
        'raw_material_warehouse_id',
        'wip_warehouse_id',
        'finished_goods_warehouse_id',
        'planned_quantity',
        'produced_quantity',
        'scrap_quantity',
        'estimated_cost',
        'actual_materials_cost',
        'actual_overhead_cost',
        'total_actual_cost',
        'unit_cost',
        'order_date',
        'start_date',
        'completed_date',
        'status', // draft, planned, in_progress, completed, cancelled
        'notes',
        'created_by',
    ];

    public function bom()
    {
        return $this->belongsTo(Bom::class, 'bom_id');
    }

    public function finishedProduct()
    {
        return $this->belongsTo(products::class, 'finished_product_id');
    }

    public function rawMaterialWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'raw_material_warehouse_id');
    }

    public function wipWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'wip_warehouse_id');
    }

    public function finishedGoodsWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'finished_goods_warehouse_id');
    }

    public function materialIssues()
    {
        return $this->hasMany(MoMaterialIssue::class, 'manufacturing_order_id');
    }
}
