<?php

namespace App\Models;
use App\Models\orderTosupllier;
use App\Models\products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
       'order_owner',
       'product_name',
       'purchasingـprice',
       'numberofpice',
       'product_id',
       'Added_Value',
       'sale_price',
       'save',
       'reamingQuantity'
    ];

    public function supllier()
{
    return $this->belongsTo(orderTosupllier::class,'order_owner');
}

public function productData()
{
    return $this->belongsTo(products::class,'product_id');
}
public function orderToSupplier()
{
    return $this->belongsTo(orderTosupllier::class, 'order_owner');
}
public function order()
{
    return $this->belongsTo(resource_purchases::class, 'order_owner', 'id');
}
}
