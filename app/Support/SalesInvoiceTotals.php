<?php

namespace App\Support;

/**
 * إجماليات فاتورة المبيعات للطباعة و PDF و QR.
 *
 * فاتورة المبيعات تُحسب في الشاشة بضريبة كل صنف (حتى تبقى الأسعار الشاملة مثل 35.00 كما هي)،
 * وتُحفظ الضريبة في invoices.Added_Value وهي نفس قيمة قيد الضريبة.
 * لذلك الطباعة تعرض القيم المحفوظة ولا تعيد حساب الضريبة، فلا يظهر فرق هللة.
 *
 * إذا كانت بيانات رأس الفاتورة غير متسقة مع المبلغ المدفوع (فواتير قديمة/معدّلة/مرتجعة)
 * يُستخدم السلوك القديم: استخراج الضريبة من المبلغ المدفوع.
 */
class SalesInvoiceTotals
{
    public static function fromInvoice($invoice, $vatRate = null): array
    {
        $rate = $vatRate ?? (optional(\App\Models\Avt::find(1))->AVT ?? 0.15);

        $paid = QuotationTotals::round2(
            (float) $invoice->cashamount + (float) $invoice->bankamount +
            (float) $invoice->Bank_transfer + (float) $invoice->creaditamount
        );

        $net = QuotationTotals::round2((float) $invoice->Price - (float) $invoice->discount);
        $vat = QuotationTotals::round2((float) $invoice->Added_Value);
        $source = 'saved';

        if ($net <= 0 || abs(($net + $vat) - $paid) >= 0.005) {
            // بيانات الرأس غير متسقة: استخراج الضريبة من المبلغ المدفوع
            $vat = QuotationTotals::round2($paid * $rate / (1 + $rate));
            $net = QuotationTotals::round2($paid - $vat);
            $source = 'paid';
        }

        return [
            'net' => $net,                 // الإجمالي بعد الخصم وقبل الضريبة
            'vat' => $vat,                 // الضريبة
            'total' => QuotationTotals::round2($net + $vat),
            'discount' => QuotationTotals::round2((float) $invoice->discount),
            'paid' => $paid,
            'source' => $source,
        ];
    }
}
