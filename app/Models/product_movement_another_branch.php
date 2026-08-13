<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_movement_another_branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_from' ,
        'branch_to' ,
        'user_from' ,
        'reciveInvoiceNumber',
        'user_to' ,
        'Totalcost' ,
        'send_invoice_number',
        'cost_withod_tax',
        'status',
    ];
    public function branchfrom()
    {
        return $this->belongsTo(branchs::class,'branch_from');
    }
    public function branchto()
    {
        return $this->belongsTo(branchs::class,'branch_to');
    }
    public function userfrom()
    {
        return $this->belongsTo(User::class,'user_from');
    }
    public function userto()
    {
        return $this->belongsTo(User::class,'user_to');
    }
    public function items()
    {
        return $this->hasMany(product_movement_another_branch_items::class, 'order_id');
    }
}
