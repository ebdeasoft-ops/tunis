<?php

namespace App\Exports;

use App\Models\customers;
use Maatwebsite\Excel\Concerns\FromCollection;

class customersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return customers::all();
    }
}
