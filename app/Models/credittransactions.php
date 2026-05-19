<?php

namespace App\Models;
use App\Models\financial_accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class credittransactions extends Model
{
    use HasFactory;
    protected $fillable = [
    'customer_id',
    'user_id',
    'recive_amount',
    'note',
    'currentblance',
    'pay_method',
    'branchs_id',
    'Pay_Method_Name',
    'created_at',
    'updated_at',
    'attachments',
    'orginal_type',
    'orginal_id',
    'dely_record',
    'parent_dely_record',
    'debtor',
    'creditor',
    'vat',
    'name',
    'tax',
    'decument_id',
    'type_decument',
    'save',
    'Opening_entry',
    'parent_Opening_entry',
    'date_export',
    'sent_abd_count',
    'sent_serf_count',
    'type',
    'cost_center'

];
    public function cost_center_data()
    {
        return $this->belongsTo(Cost_centers::class,'cost_center');
    }
    public function customer()
    {
        return $this->belongsTo(customers::class,'orginal_id');
    }
    
       public function financial_accounts_data()
    {
        return $this->belongsTo(financial_accounts::class,'customer_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(branchs::class, 'branchs_id');
    }  
    
  
}
