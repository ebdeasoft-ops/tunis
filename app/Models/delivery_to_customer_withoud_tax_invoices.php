<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\customers;
use App\Models\User;

class delivery_to_customer_withoud_tax_invoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id'
       ,'user_id',
         'Price',
         'discountOnInvoice',
         'branchs_id',
          'Pay',
          'status',
          'save',
          'morepayment_way',
          'creaditamount',
          'bankamount',
          'cashamount',
          'discountOnProduct',
          'note',
          'Bank_transfer',
        'Added_Value',
        'discount',
        'user_id',
        'Number_of_Quantity'
      , 'created_at',
      'currentblance',
      'NOTICE_Number',
      'issue_time',
      'issue_date',
      'display_number',
      'payment_return',
            'xml',
      'document_type',
      'invoice_type',
      'sent_to_zatca',
      'sent_to_zatca_status',
      'invoiceUUid',
      'signing_time',
      'issue_time',
      'issue_date',
       'issue_time_return',
      'issue_date_return',
      'invoice_number' ,
      'invoice_counter',
      'qr_zatca',
      'hash',
      'xmltags',
    'NOTICE_Number',
        'qr_zatca_return',
      'hash_return',
      'xmltags_return',
      'sent_to_zatca_status_return',
      'xml_return',
       'clearedInvoice',
       'uuid'
];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function branch()
    {
        return $this->belongsTo(branchs::class,'branchs_id');
    }
    public function customer()
{
    return $this->belongsTo(customers::class,'customer_id');
}
}
