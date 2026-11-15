<?php

namespace App\Support;

/**
 * حساب موحّد لإجماليات عرض السعر / الفاتورة.
 *
 * الطريقة المعتمدة (نفس الشاشة والطباعة والـ PDF):
 *   1) الإجمالي قبل الخصم  = round2( Σ سعر × كمية )
 *   2) إجمالي الخصم        = round2( Σ خصم الأصناف + خصم الفاتورة )
 *   3) الخاضع للضريبة       = (1) − (2)
 *   4) الضريبة              = round2( الخاضع للضريبة × النسبة )   ← مرة واحدة على الإجمالي
 *   5) الإجمالي المستحق     = round2( (3) + (4) )
 *
 * التقريب Round-Half-Up لمنزلتين، مع معالجة أخطاء الـ float (مثل 1.005).
 * أي تعديل هنا يجب أن ينعكس في دالة quoteTotals() في JavaScript.
 */
class QuotationTotals
{
    /** تقريب Round-Half-Up آمن من أخطاء الـ float */
    public static function round2($value): float
    {
        $value = (float) $value;
        // إزالة ضوضاء الـ float أولاً (مثل 207.20499999999998 → 207.205)
        $clean = round($value, 8);
        $sign = $clean < 0 ? -1 : 1;

        return $sign * floor(abs($clean) * 100 + 0.5 + 1e-9) / 100;
    }

    /**
     * @param iterable $items عناصر فيها PriceWithoudTax و quantity و discount
     * @param float $invoiceDiscount خصم الفاتورة بدون ضريبة (offer_price_to_customers.discount)
     * @param float $vatRate نسبة الضريبة (0.15)
     */
    public static function calculate($items, $invoiceDiscount = 0, $vatRate = 0.15): array
    {
        $subTotal = 0.0;
        $itemsDiscount = 0.0;

        foreach ($items as $item) {
            $subTotal += ((float) $item->PriceWithoudTax) * ((float) $item->quantity);
            $itemsDiscount += (float) $item->discount;
        }

        $subTotal = self::round2($subTotal);
        $discount = self::round2($itemsDiscount + (float) $invoiceDiscount);
        $taxable = self::round2($subTotal - $discount);
        $vat = self::round2($taxable * (float) $vatRate);
        $grandTotal = self::round2($taxable + $vat);

        return [
            'sub_total' => $subTotal,
            'items_discount' => self::round2($itemsDiscount),
            'invoice_discount' => self::round2($invoiceDiscount),
            'discount' => $discount,
            'taxable' => $taxable,
            'vat_rate' => (float) $vatRate,
            'vat' => $vat,
            'grand_total' => $grandTotal,
        ];
    }

    /** إجماليات عرض سعر محفوظ برقمه */
    public static function forOffer($orderId, $items = null): array
    {
        $offer = \App\Models\offer_price_to_customer::find($orderId);
        $items = $items ?? \App\Models\offer_price_to_customer_items::where('order_id', $orderId)->get();
        $rate = optional(\App\Models\Avt::find(1))->AVT ?? 0.15;

        return self::calculate($items, $offer->discount ?? 0, $rate);
    }
}
