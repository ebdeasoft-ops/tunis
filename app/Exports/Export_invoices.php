<?php

namespace App\Exports;

use App\Models\invoices;
use App\Models\Avt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Export_invoices implements FromCollection, WithHeadings
{
    public $branch, $pay, $startat, $end_at;

    public function __construct($branch, $pay, $startat, $end_at) {
        $this->branch = $branch;
        $this->pay = $pay;
        $this->startat = $startat;
        $this->end_at = $end_at;
    }

    public function collection()
    {
        $data = [];
        
        // عدادات المجاميع النهائية لأسفل الملف
        $total_cash = 0; 
        $total_shabka = 0; 
        $total_credit = 0; 
        $total_transfer = 0; // عداد التحويل البنكي
        $total_all = 0;

        // جلب البيانات مع العميل لتجنب الانهيار والبطء (N+1 Problem)
        $query = invoices::with('customer')
            ->whereDate('created_at', '>=', $this->startat)
            ->whereDate('created_at', '<=', $this->end_at)
            ->where('save', 1);

        if ($this->branch != "-") { $query->where('branchs_id', $this->branch); }
        if ($this->pay != "-") { $query->where('Pay', $this->pay); }

        $Invoices = $query->get();

        $avt = Avt::find(1);
        $saleavt = $avt ? $avt->AVT : 0.15;

        foreach($Invoices as $product) {
            // الحسابات المالية لكل فاتورة
            $total_without_tax = round($product->Price - $product->discount, 2);
            $tax_value = round($total_without_tax * $saleavt, 2);
            $invoice_grand_total = $total_without_tax + $tax_value;

            // جلب القيم من الحقول المحددة في قاعدة البيانات لكل صف
            $current_cash = $product->cashamount ?? 0;        // الكاش
            $current_shabka = $product->bankamount ?? 0;      // الشبكة
            $current_credit = $product->creaditamount ?? 0;   // الآجل
            $current_transfer = $product->Bank_transfer ?? 0; // التحويل البنكي

            // تحديث العدادات النهائية للمجموع الكلي
            $total_cash += $current_cash;
            $total_shabka += $current_shabka;
            $total_credit += $current_credit;
            $total_transfer += $current_transfer;
            $total_all += $invoice_grand_total;

            // إضافة الصف للبيانات مع تفصيل المبالغ في كل صف
            $data[] = [
                "id"            => $product->id,
                "created_at"    => $product->created_at->format('Y-m-d'),
                "customer"      => $product->customer ? $product->customer->name : '-',
                "cash_val"      => $current_cash,
                "shabka_val"    => $current_shabka,
                "credit_val"    => $current_credit,
                "transfer_val"  => $current_transfer, // مبلغ التحويل في الصف
                "grand_total"   => $invoice_grand_total,
            ];
        }

        // سطر الإجمالي النهائي في آخر الملف
        $data[] = [
            "الإجمالي العام", 
            "", 
            "", 
            "كاش: " . $total_cash, 
            "شبكة: " . $total_shabka, 
            "آجل: " . $total_credit, 
            "تحويل: " . $total_transfer, 
            "الصافي: " . $total_all
        ];

        return collect($data);
    }

    public function headings() :array
    {
        return [
            "رقم الفاتورة", 
            "التاريخ", 
            "العميل", 
            "كاش (Cash)", 
            "شبكة (Bank)", 
            "آجل (Credit)", 
            "تحويل بنكي (Transfer)", 
            "الإجمالي شامل الضريبة"
        ];
    }
}