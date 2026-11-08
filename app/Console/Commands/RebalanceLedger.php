<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * تسوية فروق ميزان المراجعة.
 *
 *   php artisan ledger:rebalance                 معاينة فقط (لا يعدّل أي شيء)
 *   php artisan ledger:rebalance --apply         تنفيذ التسوية (بعد أخذ نسخة احتياطية من الصفوف المتأثرة)
 *   php artisan ledger:rebalance --rollback=FILE التراجع عن تسوية سابقة باستخدام ملف النسخة الاحتياطية
 *
 * ميزان المراجعة يقرأ حركات الحسابات الفرعية فقط (حركات الحساب الرئيسي نسخ تجميعية مكررة).
 * المرحلة 1: الطرف الحقيقي المرحّل على حساب رئيسي يُنقل إلى الحساب الفرعي لنفس الفرع، إذا كان ذلك يجعل المستند متزناً.
 * المرحلة 2: أي مستند ما زال غير متزن يُضاف له سطر تسوية بنفس تاريخه على حساب "فروق ميزان معلّقة للمراجعة".
 */
class RebalanceLedger extends Command
{
    protected $signature = 'ledger:rebalance
        {--apply : تنفيذ التسوية فعلياً (بدونه معاينة فقط)}
        {--rollback= : اسم ملف النسخة الاحتياطية للتراجع عن تسوية سابقة}
        {--suspense= : رقم id لحساب التسوية (افتراضياً يُنشأ حساب "فروق ميزان معلّقة للمراجعة")}
        {--suspense-parent=164 : الحساب الأب لحساب التسوية الجديد}
        {--user= : رقم المستخدم الذي تُسجَّل باسمه سطور التسوية}
        {--report= : حفظ تفاصيل المعاينة في ملف CSV داخل storage/app}';

    protected $description = 'تسوية فروق ميزان المراجعة (معاينة افتراضياً، والتنفيذ مع --apply)';

    const MARK = '[تسوية-ميزان]';
    const SUSPENSE_NAME = 'فروق ميزان معلّقة للمراجعة';

    public function handle()
    {
        if ($this->option('rollback')) {
            return $this->rollback($this->option('rollback'));
        }

        $existing = DB::table('credittransactions')->where('note', 'like', self::MARK . '%')->count();
        if ($existing > 0) {
            $this->error("توجد {$existing} سطر تسوية سابقة. نفّذ --rollback أولاً قبل إعادة التسوية.");
            return 1;
        }

        $this->info('جاري تحميل الحسابات والحركات...');
        [$accounts, $children] = $this->loadAccounts();
        $rows = $this->loadTransactions();
        $this->line('الحسابات: ' . count($accounts) . ' | الحركات المحفوظة: ' . count($rows));

        $kinds = $this->classify($rows, $accounts, $children);
        $docs = $this->groupDocuments($rows, $kinds);

        $before = $this->leafDifference($rows, $kinds);
        $this->line('فرق الميزان الحالي (حسابات فرعية): ' . number_format($before, 2));

        // ---------------- المرحلة 1: نقل الأطراف المرحّلة على حسابات رئيسية
        $branchMain = DB::table('branchs')->pluck('branch_id', 'id')->all();
        $moves = [];
        foreach ($docs as $key => &$doc) {
            if (abs($doc['preal']) < 0.005 || abs($doc['preal'] + $doc['diff']) > 0.01) {
                continue;
            }
            $plan = [];
            foreach ($doc['preal_rows'] as $rid) {
                $r = $rows[$rid];
                $target = $this->branchLeaf($r['a'], $r['b'], $children, $accounts, $branchMain);
                if (!$target) { $plan = null; break; }
                $plan[$rid] = $target;
            }
            if ($plan) {
                foreach ($plan as $rid => $target) {
                    $moves[$rid] = ['from' => $rows[$rid]['a'], 'to' => $target, 'doc' => $key];
                    $kinds[$rid] = 'leaf';
                    $this->addDay($doc, $rows[$rid], $rows[$rid]['d'] - $rows[$rid]['c']);
                }
                $doc['diff'] = round($doc['diff'] + $doc['preal'], 6);
                $doc['moved'] = true;
            }
        }
        unset($doc);

        // ---------------- المرحلة 2: سطر تسوية لكل مستند ما زال غير متزن
        $plugs = [];
        foreach ($docs as $key => $doc) {
            // نسوّي كل يوم غير متزن في المستند، حتى يتزن الميزان في أي فترة يختارها المستخدم.
            // مستند متزن إجمالاً لكن أطرافه في أيام مختلفة (مثل فاتورة عُدّل دفعها لاحقاً) يأخذ سطرين متقابلين صافيهما صفر.
            $hasUnbalancedDay = false;
            foreach ($doc['days'] as $slice) { if (abs($slice['diff']) >= 0.000001) { $hasUnbalancedDay = true; break; } }
            if (abs($doc['diff']) < 0.000001 && !$hasUnbalancedDay) {
                continue;
            }
            foreach ($doc['days'] as $day => $slice) {
                if (abs($slice['diff']) < 0.000001) continue;
                $plugs[] = [
                    'doc' => $key,
                    'amount' => round($slice['diff'], 6), // موجب = المدين أكبر → السطر دائن (بدون تقريب حتى يتزن بدقة)
                    'created_at' => $slice['at'],
                    'branchs_id' => $slice['branch'],
                    'note' => mb_substr(self::MARK . ' ' . $doc['label'] . ' | ' . $doc['note'], 0, 250),
                ];
            }
        }

        $plugTotal = array_sum(array_column($plugs, 'amount'));
        $movedTotal = 0;
        foreach ($docs as $doc) { if (!empty($doc['moved'])) $movedTotal += $doc['preal']; }

        $this->newLine();
        $this->table(['البند', 'العدد', 'المبلغ'], [
            ['المرحلة 1: أطراف تُنقل من حساب رئيسي إلى حساب الفرع', count($moves), number_format($this->movedEffect($moves, $rows), 2)],
            ['المرحلة 2: سطور تسوية على حساب الفروق المعلّقة', count($plugs) . ' سطر لـ ' . count(array_unique(array_column($plugs, 'doc'))) . ' مستند', number_format($plugTotal, 2)],
            ['فرق الميزان قبل', '', number_format($before, 2)],
            ['فرق الميزان المتوقع بعد', '', number_format($before + $this->movedEffect($moves, $rows) - $plugTotal, 2)],
        ]);

        if ($this->option('report')) {
            $this->writeReport($this->option('report'), $docs, $moves, $plugs, $rows, $accounts);
        }

        if (!$this->option('apply')) {
            $this->warn('معاينة فقط — لم يُعدَّل أي شيء. للتنفيذ: php artisan ledger:rebalance --apply');
            return 0;
        }

        return $this->apply($moves, $plugs, $rows);
    }

    // =====================================================================

    private function loadAccounts(): array
    {
        $accounts = [];
        $children = [];
        foreach (DB::table('financial_accounts')->get(['id', 'parent_account_number', 'branchs_id', 'name', 'account_type']) as $a) {
            $accounts[(int) $a->id] = ['p' => (int) $a->parent_account_number, 'b' => $a->branchs_id === null ? null : (int) $a->branchs_id, 'name' => $a->name];
        }
        foreach ($accounts as $id => $a) {
            if ($a['p'] && isset($accounts[$a['p']]) && $a['p'] !== $id) {
                $children[$a['p']][] = $id;
            }
        }
        return [$accounts, $children];
    }

    private function loadTransactions(): array
    {
        $rows = [];
        DB::table('credittransactions')->where('save', 1)
            ->select('id', 'customer_id', 'debtor', 'creditor', 'created_at', 'note', 'branchs_id')
            ->orderBy('id')
            ->chunk(5000, function ($chunk) use (&$rows) {
                foreach ($chunk as $t) {
                    $rows[(int) $t->id] = [
                        'a' => (int) $t->customer_id,
                        'd' => (float) $t->debtor,
                        'c' => (float) $t->creditor,
                        'at' => (string) $t->created_at,
                        'note' => (string) $t->note,
                        'b' => (int) $t->branchs_id,
                    ];
                }
            });
        return $rows;
    }

    /** leaf | parent_mirror | parent_real | unknown */
    private function classify(array $rows, array $accounts, array $children): array
    {
        // نسخ الأب: سطر على حساب أب يقابله سطر مطابق (مبلغ/تاريخ/ملاحظة) على أحد أبنائه المباشرين
        $pool = [];
        foreach ($rows as $r) {
            $p = $accounts[$r['a']]['p'] ?? 0;
            if ($p) {
                $k = $p . '|' . $r['at'] . '|' . round($r['d'], 4) . '|' . round($r['c'], 4) . '|' . $r['note'];
                $pool[$k] = ($pool[$k] ?? 0) + 1;
            }
        }
        $kinds = [];
        foreach ($rows as $id => $r) {
            if (!isset($accounts[$r['a']])) {
                $kinds[$id] = 'unknown';
            } elseif (!empty($children[$r['a']])) {
                $k = $r['a'] . '|' . $r['at'] . '|' . round($r['d'], 4) . '|' . round($r['c'], 4) . '|' . $r['note'];
                if (($pool[$k] ?? 0) > 0) {
                    $pool[$k]--;
                    $kinds[$id] = 'parent_mirror';
                } else {
                    $kinds[$id] = 'parent_real';
                }
            } else {
                $kinds[$id] = 'leaf';
            }
        }
        return $kinds;
    }

    private function category(string $note): array
    {
        $map = [
            ['مرتجع مبيعا', 2, 'مرتجع مبيعات'], ['فاتورة مبيعات', 1, 'فاتورة مبيعات'],
            ['مرتجع مشتر', 4, 'مرتجع مشتريات'], ['مردود', 4, 'مرتجع مشتريات'], ['فاتورة مشتريات', 3, 'فاتورة مشتريات'],
            ['سند صرف', 5, 'سند صرف'], ['صرف سند', 5, 'سند صرف'], ['سند قبض', 6, 'سند قبض'],
            ['قيد يومي', 7, 'قيد يومي'], ['قيد رقم', 7, 'قيد يومي'], ['افتتاحي', 8, 'قيد افتتاحي'],
        ];
        foreach ($map as [$needle, $cat, $label]) {
            if (mb_strpos($note, $needle) !== false) {
                return [$cat, $label];
            }
        }
        return [0, 'أخرى'];
    }

    private function groupDocuments(array $rows, array $kinds): array
    {
        $docs = [];
        foreach ($rows as $id => $r) {
            [$cat, $label] = $this->category($r['note']);
            $pos = mb_strrpos($r['note'], ':');
            $num = $pos === false ? '' : preg_replace('/\D/', '', mb_substr($r['note'], $pos + 1));
            $date = substr($r['at'], 0, 10);
            if ($num !== '' && in_array($cat, [5, 6, 7, 8])) {
                $key = "$cat|$num|$date";
            } elseif ($num !== '' && in_array($cat, [1, 2, 3, 4])) {
                $key = "$cat|$num";
            } else {
                $key = "$cat|n" . md5($r['note']);
            }
            if (!isset($docs[$key])) {
                $docs[$key] = ['label' => $label . ($num !== '' ? " رقم $num" : ''), 'diff' => 0.0, 'preal' => 0.0, 'preal_rows' => [], 'days' => [],
                    'last_at' => $r['at'], 'branch' => $r['b'], 'note' => $r['note'], 'first' => $id];
            }
            $doc = &$docs[$key];
            if ($r['at'] > $doc['last_at']) $doc['last_at'] = $r['at'];
            if ($kinds[$id] === 'leaf') {
                $doc['diff'] += $r['d'] - $r['c'];
                $this->addDay($doc, $r, $r['d'] - $r['c']);
            } elseif ($kinds[$id] === 'parent_real') {
                $doc['preal'] += $r['d'] - $r['c'];
                $doc['preal_rows'][] = $id;
            }
            unset($doc);
        }
        foreach ($docs as &$d) { $d['diff'] = round($d['diff'], 6); $d['preal'] = round($d['preal'], 6); }
        return $docs;
    }

    /** فرق المستند لكل يوم (حتى يتزن الميزان في أي فترة، لا في الإجمالي فقط) */
    private function addDay(array &$doc, array $r, float $amount): void
    {
        $day = substr($r['at'], 0, 10);
        if (!isset($doc['days'][$day])) {
            $doc['days'][$day] = ['diff' => 0.0, 'at' => $r['at'], 'branch' => $r['b']];
        }
        $doc['days'][$day]['diff'] += $amount;
        if ($r['at'] > $doc['days'][$day]['at']) $doc['days'][$day]['at'] = $r['at'];
    }

    private function leafDifference(array $rows, array $kinds): float
    {
        $s = 0.0;
        foreach ($rows as $id => $r) {
            if ($kinds[$id] === 'leaf') $s += $r['d'] - $r['c'];
        }
        return round($s, 2);
    }

    private function movedEffect(array $moves, array $rows): float
    {
        $s = 0.0;
        foreach ($moves as $rid => $m) $s += $rows[$rid]['d'] - $rows[$rid]['c'];
        return round($s, 2);
    }

    /** الحساب الفرعي (بلا أبناء) لنفس الفرع تحت الحساب الرئيسي */
    private function branchLeaf(int $parent, int $branch, array $children, array $accounts, array $branchMain): ?int
    {
        foreach ([$branch, $branchMain[$branch] ?? null] as $b) {
            if (!$b) continue;
            $match = array_values(array_filter($children[$parent] ?? [], function ($c) use ($accounts, $children, $b) {
                return $accounts[$c]['b'] === (int) $b && empty($children[$c]);
            }));
            if (count($match) === 1) return $match[0];
        }
        return null;
    }

    private function suspenseAccount(): int
    {
        if ($this->option('suspense')) {
            $id = (int) $this->option('suspense');
            if (!DB::table('financial_accounts')->where('id', $id)->exists()) {
                throw new \RuntimeException("حساب التسوية $id غير موجود");
            }
            if (DB::table('financial_accounts')->where('parent_account_number', $id)->exists()) {
                throw new \RuntimeException("حساب التسوية $id حساب رئيسي، اختر حساباً فرعياً");
            }
            return $id;
        }
        $found = DB::table('financial_accounts')->where('name', self::SUSPENSE_NAME)->value('id');
        if ($found) return (int) $found;

        $parentId = (int) $this->option('suspense-parent');
        $parent = DB::table('financial_accounts')->where('id', $parentId)->first();
        if (!$parent) throw new \RuntimeException("الحساب الأب $parentId غير موجود، حدّد --suspense-parent أو --suspense");
        $sibling = DB::table('financial_accounts')->where('parent_account_number', $parentId)->orderByDesc('account_number')->first();
        $nextNumber = $sibling ? ((int) $sibling->account_number + 1) : ((int) $parent->account_number * 100 + 1);

        return (int) DB::table('financial_accounts')->insertGetId([
            'name' => self::SUSPENSE_NAME,
            'account_type' => $parent->account_type,
            'is_parent' => 0,
            'parent_account_number' => $parentId,
            'account_number' => $nextNumber,
            'start_balance_status' => 3,
            'start_balance' => 0,
            'current_balance' => 0,
            'notes' => 'أُنشئ بواسطة ledger:rebalance لتجميع فروق الميزان القديمة حتى يراجعها المحاسب',
            'added_by' => $sibling->added_by ?? 1,
            'active' => 1,
            'com_code' => $sibling->com_code ?? 0,
            'date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function apply(array $moves, array $plugs, array $rows): int
    {
        $batch = now()->format('Ymd_His');
        $userId = (int) ($this->option('user') ?: DB::table('credittransactions')->min('user_id'));

        $backup = ['batch' => $batch, 'moves' => [], 'inserted' => [], 'suspense_created' => null];

        DB::beginTransaction();
        try {
            $hadSuspense = DB::table('financial_accounts')->where('name', self::SUSPENSE_NAME)->exists() || $this->option('suspense');
            $suspense = $this->suspenseAccount();
            if (!$hadSuspense) $backup['suspense_created'] = $suspense;

            foreach ($moves as $rid => $m) {
                DB::table('credittransactions')->where('id', $rid)->where('customer_id', $m['from'])->update(['customer_id' => $m['to']]);
                $backup['moves'][] = ['id' => $rid, 'from' => $m['from'], 'to' => $m['to']];
            }

            foreach ($plugs as $p) {
                $amount = $p['amount'];
                $backup['inserted'][] = DB::table('credittransactions')->insertGetId([
                    'user_id' => $userId,
                    'customer_id' => $suspense,
                    'recive_amount' => abs($amount),
                    'pay_method' => 'تسوية',
                    'Pay_Method_Name' => 'تسوية',
                    'created_at' => $p['created_at'],
                    'updated_at' => now(),
                    'branchs_id' => $p['branchs_id'],
                    'note' => $p['note'],
                    'orginal_type' => 0,
                    'debtor' => $amount < 0 ? abs($amount) : 0,
                    'creditor' => $amount > 0 ? $amount : 0,
                    'save' => 1,
                ]);
            }

            Storage::put("rebalance_backup_{$batch}.json", json_encode($backup, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('فشل التنفيذ ولم يُعدَّل شيء: ' . $e->getMessage());
            return 1;
        }

        $after = DB::table('credittransactions as t')
            ->join('financial_accounts as f', 'f.id', '=', 't.customer_id')
            ->where('t.save', 1)
            ->whereNotExists(function ($q) { $q->select(DB::raw(1))->from('financial_accounts as k')->whereColumn('k.parent_account_number', 'f.id'); })
            ->selectRaw('ROUND(SUM(t.debtor) - SUM(t.creditor), 2) as diff')->value('diff');

        $this->info('تمت التسوية. سطور منقولة: ' . count($backup['moves']) . ' | سطور تسوية: ' . count($backup['inserted']));
        $this->info('فرق الميزان الآن: ' . number_format((float) $after, 2));
        $this->info("ملف التراجع: storage/app/rebalance_backup_{$batch}.json");
        $this->line("للتراجع: php artisan ledger:rebalance --rollback=rebalance_backup_{$batch}.json");
        return 0;
    }

    private function rollback(string $file): int
    {
        if (!Storage::exists($file)) {
            $this->error("الملف غير موجود: storage/app/$file");
            return 1;
        }
        $b = json_decode(Storage::get($file), true);
        DB::transaction(function () use ($b) {
            foreach ($b['moves'] as $m) {
                DB::table('credittransactions')->where('id', $m['id'])->where('customer_id', $m['to'])->update(['customer_id' => $m['from']]);
            }
            DB::table('credittransactions')->whereIn('id', $b['inserted'])->where('note', 'like', self::MARK . '%')->delete();
            if (!empty($b['suspense_created'])
                && !DB::table('credittransactions')->where('customer_id', $b['suspense_created'])->exists()) {
                DB::table('financial_accounts')->where('id', $b['suspense_created'])->delete();
            }
        });
        $this->info('تم التراجع: أُعيدت ' . count($b['moves']) . ' سطر إلى حساباتها وحُذفت ' . count($b['inserted']) . ' سطر تسوية.');
        return 0;
    }

    private function writeReport(string $name, array $docs, array $moves, array $plugs, array $rows, array $accounts): void
    {
        $out = fopen('php://temp', 'w+');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['الإجراء', 'المستند', 'التاريخ', 'المبلغ', 'من حساب', 'إلى حساب', 'id السطر']);
        foreach ($moves as $rid => $m) {
            fputcsv($out, ['نقل إلى حساب الفرع', $docs[$m['doc']]['label'], substr($rows[$rid]['at'], 0, 10), $rows[$rid]['d'] - $rows[$rid]['c'],
                $accounts[$m['from']]['name'] ?? $m['from'], $accounts[$m['to']]['name'] ?? $m['to'], $rid]);
        }
        foreach ($plugs as $p) {
            fputcsv($out, ['سطر تسوية معلّق', $docs[$p['doc']]['label'], substr($p['created_at'], 0, 10), round(-$p['amount'], 2), '', self::SUSPENSE_NAME, $docs[$p['doc']]['first']]);
        }
        rewind($out);
        Storage::put($name, stream_get_contents($out));
        $this->info("تفاصيل المعاينة: storage/app/$name");
    }
}
