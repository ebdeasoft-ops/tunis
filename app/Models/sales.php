<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\products;
use App\Models\invoices;
use App\Models\branchs;

class sales extends Model
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
'unit',
'note',
         'user_id'

];

    public function Invoice()
    {
        return $this->belongsTo(invoices::class,'invoice_id');
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
