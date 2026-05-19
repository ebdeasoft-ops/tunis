<?php

namespace App\Exports;

use App\Models\supllier;
use Maatwebsite\Excel\Concerns\FromCollection;

class supllierExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return supllier::all();
    }
}
