<?php

namespace App\Exports;

use App\Models\resource_purchases;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Avt;

class Export_invoices_purshase implements FromCollection,WithHeadings
{
    
    
    public $branch;
    public $pay;
    public $startat;
    public $end_at;
    public $supplier;

    // Constructor with 4 parameters
    public function __construct($branch, $pay,$supplier, $startat, $end_at) {
        $this->branch = $branch;
        $this->pay = $pay;
        $this->startat = $startat;
        $this->end_at = $end_at;
        $this->supplier = $supplier;
    }
    
    
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
           $pay =$this->pay;
           $branch = $this->branch;

        $data=[];
             if ($this->supplier == '-') {


            if ($pay == '-' && $branch == '-') {
                $Invoices = resource_purchases::whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();

            } elseif ($pay != '-' && $branch == '-') {
                $Invoices = resource_purchases::where('Pay_Method_Name', $pay)->whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();

            } elseif ($pay == '-' && $branch != '-') {
                $Invoices = resource_purchases::where('branchs_id', $branch)->whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();

            }
            else{
                            $Invoices = resource_purchases::where('branchs_id', $branch)->where('Pay_Method_Name', $pay)->whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();

            }
        } else {

            if ($pay == '-' && $branch == '-') {
                $Invoices = resource_purchases::where('suplier_id', $this->supplier)->whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();

            } elseif ($pay != '-' && $branch == '-') {
                $Invoices = resource_purchases::where('suplier_id', $this->supplier)->where('Pay_Method_Name', $pay)->whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();

            } elseif ($pay == '-' && $branch != '-') {
                $Invoices = resource_purchases::where('suplier_id', $this->supplier)->where('branchs_id', $branch)->whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();
            }
            else{
                            $Invoices = resource_purchases::where('suplier_id', $this->supplier)->where('branchs_id', $branch)->where('Pay_Method_Name', $pay)->whereDate('created_at', '>=',$this->startat)->whereDate('created_at', '<=',$this->end_at)->where('save', 1)->get();

            }

        }
        
        foreach($Invoices as $product){
            
               $pays = '';
                                            if ($product->Pay_Method_Name == 'Cash') {
                                                $pays = __('report.cash');
                                            } elseif ($product->Pay_Method_Name == 'Shabka') {
                                                $pays = __('report.shabka');
                                            } elseif ($product->Pay_Method_Name == "Credit") {
                                                $pays = __('report.credit');
                                            } elseif ($product->Pay_Method_Name == "Bank_transfer") {
                                                $pays = __('home.Bank_transfer');
                                            } else {
                                                $pays = __('home.Partition of the amount');
                                            }




            $data[]=[
                   "id"=> $product->id,
                   "created_at"=>$product->created_at,
                   "customer"=> $product->supllier->name,
                   "tax_no"=> $product->supllier->TaxـNumber,
                   "PAYMENT"=> $pays,
                   "TOTAL"=> $product->In_debt,

                   ];

        }
        
        

        return collect($data);
    }
    public function headings() :array
    {
        return ["INVOICE_ID", "DATE", "SUPPLIER_NAME","TAX_NUMBER", "PAYMENT","AMOUNT_WITH_TAX"];
    }
}