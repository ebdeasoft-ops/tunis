<?php

namespace App\Exports;

use App\Models\products;
use App\Models\return_sales;
use App\Models\sales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Low_sell_export implements FromCollection,WithHeadings
{
        protected $start;
        protected $end;
        protected $branch;

    public function __construct( $start ,$end,$branch)
    {
        $this->start = $start;
        $this->end = $end;
        $this->branch = $branch;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

 $data=[];
 foreach(products::where('branchs_id', $this->branch)->where('numberofpice','>',0)->get() as $product){
     $number_of_invoice=0;
     $number_of_sales=0;
     $number_of_sales_return=0;
    $sales_data= sales::where('branch_id', $this->branch)->where('product_id', $product->id)->whereDate('created_at', '>=',  $this->start)->whereDate('created_at', '<=',  $this->end)->where('save', 1)->get();
 foreach($sales_data as $sale){
        $number_of_invoice++;
        $number_of_sales=$number_of_sales+$sale->quantity;
     
}

    $sales_data= return_sales::where('branch_id', $this->branch)->where('product_id', $product->id)->whereDate('created_at', '>=',  $this->start)->whereDate('created_at', '<=',  $this->end)->get();
 foreach($sales_data as $sale){
        $number_of_sales_return=$number_of_sales_return+$sale->return_quantity;
     
}

if($number_of_sales){
    
  $data_list[]=[
    'name'=>$product->product_name,
    'Product_Code'=>$product->Product_Code,
    'Product_Location'=>$product->Product_Location,
    'number_of_invoice'=>$number_of_invoice,
    'number_of_sales'=>$number_of_sales,
    'number_of_sales_return'=>$number_of_sales_return,
    'numberofpice'=>$product->numberofpice,

    ];
      
    
}

    
    
 }
     usort($data_list, function ($a, $b) {
    return $a['number_of_sales'] <=> $b['number_of_sales']; // Ascending
});

        // $dates = array();
        
        // foreach ($data_list as $key => $row)
        // {
        //     $dates[$key] = strtotime($row['number_of_sales']);
        // }
        // array_multisort($dates, SORT_ASC, $data_list);
      
      
        return collect($data_list);
    }
    public function headings() :array
    {
        return ["name", "Product_Code", "Product_Location","number_of_invoice", "number_of_sales","number_of_sales_return","QUENTITY"];
    }
}