<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * حساب أرصدة ميزان المراجعة على شجرة الحسابات.
 *
 * القواعد (حتى يتزن الميزان دائماً طالما القيود نفسها متزنة):
 *  1. الربط بالـ id فقط: parent_account_number يشير إلى id الحساب الأب،
 *     و credittransactions.customer_id يشير إلى id الحساب.
 *  2. رصيد الحساب الفرعي (ليس له أبناء) = حركاته المباشرة.
 *     رصيد الحساب الأب = مجموع أرصدة أبنائه فقط.
 *     لا تُحتسب الحركات المسجّلة مباشرة على الحساب الأب، لأن النظام عند حفظ أي سند
 *     يكرر نفس الحركة على الأب المباشر (نسخة تجميعية بنفس المبلغ)، واحتسابها يضاعف المبالغ.
 *  3. الحساب الذي يشير أبوه إلى حساب غير موجود (يتيم) يُعامل كحساب جذري حتى لا تضيع حركاته.
 */
class TrialBalanceCalculator
{
    /** @var Collection<int, object> */
    private $accounts;

    /** @var array<string, array<int, object>> parent_id => children */
    private $children = [];

    /** @var array<string, object> id => direct balances row */
    private $direct = [];

    /** @var array<string, array> memoized totals */
    private $memo = [];

    /**
     * @param iterable $allAccounts     كل الحسابات (financial_accounts)
     * @param iterable $directBalances  صفوف فيها open_debtor/open_creditor/curr_debtor/curr_creditor
     *                                  مفهرسة (أو تحتوي عمود) customer_id أو account_row_id = id الحساب
     */
    public function __construct($allAccounts, $directBalances)
    {
        $this->accounts = collect($allAccounts)->keyBy(function ($a) {
            return (string) $a->id;
        });

        foreach ($this->accounts as $acc) {
            $p = $acc->parent_account_number;
            if ($p !== null && $p !== '' && (string) $p !== '0' && $this->accounts->has((string) $p) && (string) $p !== (string) $acc->id) {
                $this->children[(string) $p][] = $acc;
            }
        }

        foreach ($directBalances as $key => $row) {
            $id = $row->customer_id ?? $row->account_row_id ?? $key;
            $this->direct[(string) $id] = $row;
        }
    }

    /** هل الحساب جذري (بدون أب، أو أبوه غير موجود)؟ */
    public function isRoot($account): bool
    {
        $p = $account->parent_account_number;
        return $p === null || $p === '' || (string) $p === '0'
            || !$this->accounts->has((string) $p)
            || (string) $p === (string) $account->id;
    }

    public function hasChildren($accountId): bool
    {
        return !empty($this->children[(string) $accountId]);
    }

    /** @return array<int, object> */
    public function childrenOf($accountId): array
    {
        $kids = $this->children[(string) $accountId] ?? [];
        usort($kids, function ($a, $b) {
            return strnatcmp((string) $a->account_number, (string) $b->account_number);
        });
        return $kids;
    }

    /** الحسابات الجذرية (اختيارياً لنوع حساب معين) */
    public function roots($accountType = null): Collection
    {
        return $this->accounts->filter(function ($a) use ($accountType) {
            return $this->isRoot($a) && ($accountType === null || (string) $a->account_type === (string) $accountType);
        })->sortBy('account_number', SORT_NATURAL)->values();
    }

    /**
     * إجماليات الحساب شاملة أبناءه (o_d, o_c, c_d, c_c)
     */
    public function totals($accountId, array $visiting = []): array
    {
        $key = (string) $accountId;
        if (isset($this->memo[$key])) {
            return $this->memo[$key];
        }

        $t = ['o_d' => 0.0, 'o_c' => 0.0, 'c_d' => 0.0, 'c_c' => 0.0];

        // 1) الحركات المباشرة: للحساب الفرعي فقط (حركات الأب نسخ مكررة من حركات أبنائه)
        if (empty($this->children[$key]) && isset($this->direct[$key])) {
            $d = $this->direct[$key];
            $t['o_d'] += (float) $d->open_debtor;
            $t['o_c'] += (float) $d->open_creditor;
            $t['c_d'] += (float) $d->curr_debtor;
            $t['c_c'] += (float) $d->curr_creditor;
        }

        // 2) أرصدة الأبناء (مع حماية من الحلقات في الشجرة)
        $visiting[$key] = true;
        foreach ($this->children[$key] ?? [] as $child) {
            if (isset($visiting[(string) $child->id])) {
                continue;
            }
            $c = $this->totals($child->id, $visiting);
            $t['o_d'] += $c['o_d'];
            $t['o_c'] += $c['o_c'];
            $t['c_d'] += $c['c_d'];
            $t['c_c'] += $c['c_c'];
        }

        // [دقة] لا نقرّب رصيد كل حساب هنا: بعض المبالغ في القاعدة بأكثر من خانتين عشريتين،
        // وجمع أرصدة مقرّبة يُظهر فروقاً وهمية (هللات) في الإجمالي. التقريب عند العرض وفي grandTotals فقط.
        foreach ($t as $k => $v) {
            $t[$k] = round($v, 6);
        }

        return $this->memo[$key] = $t;
    }

    /** نفس totals لكن بصيغة صف أرصدة (open_debtor...) لاستخدامها في الواجهات الحالية */
    public function totalsAsRow($accountId): object
    {
        $t = $this->totals($accountId);
        return (object) [
            'open_debtor' => $t['o_d'],
            'open_creditor' => $t['o_c'],
            'curr_debtor' => $t['c_d'],
            'curr_creditor' => $t['c_c'],
        ];
    }

    /** الإجمالي العام لكل الحسابات الجذرية — يجب أن يتساوى المدين والدائن */
    public function grandTotals(): array
    {
        $g = ['o_d' => 0.0, 'o_c' => 0.0, 'c_d' => 0.0, 'c_c' => 0.0];
        foreach ($this->roots() as $root) {
            foreach ($this->totals($root->id) as $k => $v) {
                $g[$k] += $v;
            }
        }
        foreach ($g as $k => $v) {
            $g[$k] = round($v, 2);
        }
        $g['debtor'] = round($g['o_d'] + $g['c_d'], 2);
        $g['creditor'] = round($g['o_c'] + $g['c_c'], 2);
        $g['difference'] = round($g['debtor'] - $g['creditor'], 2);
        return $g;
    }
}
