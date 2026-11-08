<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * [أمان] تنظيف قيمة تاريخ قادمة من الطلب قبل استخدامها داخل استعلام SQL خام (DB::raw)
     * لمنع حقن SQL. يعيد التاريخ بصيغة Y-m-d، أو القيمة الافتراضية إذا كان المدخل غير صالح.
     * القيم الفارغة تبقى فارغة للحفاظ على السلوك السابق.
     */
    protected function safeDate($value, $default = '')
    {
        if ($value === null || $value === '') {
            return $default;
        }
        $ts = strtotime((string) $value);
        return $ts === false ? $default : date('Y-m-d', $ts);
    }

    /**
     * [إصلاح الميزان] أسماء الحسابات "الأب" (التي لها حسابات فرعية) من بين الحسابات المختارة في قيد.
     * ميزان المراجعة يقرأ أرصدة الحسابات الفرعية فقط، فالترحيل على حساب أب يجعل الميزان غير متزن.
     * ملاحظة: نعتمد على وجود أبناء فعليين وليس على عمود is_parent لأنه غير دقيق في البيانات.
     */
    protected function parentAccountsAmong($accountIds): array
    {
        $ids = collect($accountIds)->filter(function ($v) {
            return $v !== null && $v !== '' && (string) $v !== '0';
        })->unique()->values();

        if ($ids->isEmpty()) {
            return [];
        }

        $parentIds = \App\Models\financial_accounts::whereIn('parent_account_number', $ids)
            ->distinct()
            ->pluck('parent_account_number');

        if ($parentIds->isEmpty()) {
            return [];
        }

        return \App\Models\financial_accounts::whereIn('id', $parentIds)->pluck('name')->all();
    }

    /** رسالة موحّدة عند اختيار حساب أب */
    protected function parentAccountsMessage(array $names): string
    {
        return 'لا يمكن الترحيل على حساب رئيسي له حسابات فرعية: ' . implode('، ', $names)
            . ' — اختر الحساب الفرعي المناسب (مثل خزينة أو بنك الفرع).'
            . "\n Posting to a parent account is not allowed: " . implode(', ', $names);
    }
}
