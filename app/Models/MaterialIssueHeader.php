<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIssueHeader extends Model
{
    use HasFactory;
    protected $table = 'material_issue_headers';

    protected $fillable = [
        'issue_number',
        'manufacturing_order_id',
        'issue_date',
        'raw_warehouse_id',
        'wip_warehouse_id',
        'notes',
        'created_by',
    ];

    public function manufacturingOrder()
    {
        return $this->belongsTo(ManufacturingOrder::class, 'manufacturing_order_id');
    }

    public function rawWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'raw_warehouse_id');
    }

    public function wipWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'wip_warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(MaterialIssueItem::class, 'issue_header_id');
    }
}
