<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesProfitsExport implements FromView {
    protected $data; protected $start; protected $end;

    public function __construct($data, $start, $end) {
        $this->data = $data; $this->start = $start; $this->end = $end;
    }

    public function view(): View {
        // هنا نخبره أن يستخدم ملف الـ Blade المخصص للداتا فقط
        return view('reports.excel_sales_profits', [
            'data' => $this->data,
            'start' => $this->start,
            'end' => $this->end
        ]);
    }
}