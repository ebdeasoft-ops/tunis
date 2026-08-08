<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockQuantityExport implements FromView, ShouldAutoSize
{
    protected $products;
    protected $totals;

    // استقبال المنتجات والإجماليات المُجهزة مسبقاً من الكونترولر
    public function __construct($products, $totals)
    {
        $this->products = $products;
        $this->totals = $totals;
    }

    public function view(): View
    {
        // استخدام نفس تصميم الـ Blade أو تصميم مخصص للإكسيل
        return view('reports.exports.stockquantity_excel', [
            'products' => $this->products,
            'totals' => $this->totals
        ]);
    }
}