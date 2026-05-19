<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\products;
use App\Models\delivery_to_customer_withoud_tax_invoices;
use App\Models\branchs;

class sales_withoud_taxes  extends Model
{
    use HasFactory;

    protected $fillable = [
     'product_id'
    ,'invoice_id',
    'branch_id',
    'save',
    'reamingQuantity',
    'Discount_Value',
    'Added_Value',
     'Unit_Price',
     'quantity',
     'discountreturn',
      'quantityreturn',
      'created_at',
'unit'
];

    public function Invoice()
    {
        return $this->belongsTo(delivery_to_customer_withoud_tax_invoices::class,'invoice_id');
    }
    
    public function productData()
    {
        return $this->belongsTo(products::class,'product_id');
    }
    public function branch()
    {
        return $this->belongsTo(branchs::class,'branch_id');
    }
}
