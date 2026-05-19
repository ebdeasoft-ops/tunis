<?php

namespace App\Models;
use App\Models\products_group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $fillable = [
        'refnumber',
        'product_name',
        'purchasingـprice',
        'sale_price',
        'numberofpice',
        'numberـofـsales',
        'Status',
        'user_id',
        'Product_Location',
        'Product_Code',
        'Product_Location',
        'branchs_id',
        'minmum_quantity_stock_alart',
        'unit',
        'name_en',
        'grace_period_in_days',
                'notes',
     'main_product',
          'Wholesale_price'

       ,     'photo',
       'average_cost',
       'products_mix',
       'product_group',
       'CRN',



    ];
    public function branch()
    {
        return $this->belongsTo(branchs::class,'branchs_id');
    }
    
      public function product_group_data()
    {
        return $this->belongsTo(products_group::class,'product_group');
    }
}
