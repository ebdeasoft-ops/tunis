<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishedGoodsReceipt extends Model
{
    use HasFactory;
    protected $table = 'finished_goods_receipts';

    protected $fillable = [
        'receipt_number',
        'manufacturing_order_id',
        'finished_product_id',
        'receipt_date',
        'wip_warehouse_id',
        'finished_goods_warehouse_id',
        'received_quantity',
        'unit_cost',
        'total_cost',
        'notes',
        'created_by',
    ];

    public function manufacturingOrder()
    {
        return $this->belongsTo(ManufacturingOrder::class, 'manufacturing_order_id');
    }

    public function finishedProduct()
    {
        return $this->belongsTo(products::class, 'finished_product_id');
    }

    public function wipWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'wip_warehouse_id');
    }

    public function finishedGoodsWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'finished_goods_warehouse_id');
    }
}
