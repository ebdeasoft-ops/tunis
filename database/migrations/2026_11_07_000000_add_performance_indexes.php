<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * [أداء] إضافة فهارس على الأعمدة الأكثر استخداماً في الاستعلامات (كشوف الحسابات، التقارير، لوحة التحكم).
 *
 * - لا تغيّر أي بيانات ولا أي عمود، فقط تضيف فهارس.
 * - آمنة على قاعدة البيانات الحالية: تتخطى أي فهرس موجود مسبقاً على نفس العمود، وأي عمود غير موجود.
 * - للتشغيل:  php artisan migrate
 * - للتراجع:  php artisan migrate:rollback   (يحذف الفهارس التي أضافها هذا الملف فقط)
 */
return new class extends Migration
{
    /**
     * الجدول => قائمة الفهارس (كل فهرس = مصفوفة أعمدة)
     */
    private array $indexes = [
        'financial_accounts' => [
            ['id'],
            ['parent_account_number'],
            ['account_number'],
            ['orginal_type', 'orginal_id'],
        ],
        'credittransactions' => [
            ['customer_id'],
            ['created_at'],
        ],
        'invoices' => [
            ['branchs_id'],
            ['customer_id'],
            ['created_at'],
        ],
        'sales' => [
            ['created_at'],
        ],
        'products' => [
            ['Product_Code'],
        ],
        'resource_purchases' => [
            ['orderId'],
            ['created_at'],
        ],
        'return_sales' => [
            ['created_at'],
        ],
        'order_details' => [
            ['order_owner'],
        ],
    ];

    private function indexName(string $table, array $columns): string
    {
        return 'perf_' . $table . '_' . implode('_', $columns) . '_idx';
    }

    /** هل يوجد فهرس يبدأ بنفس العمود الأول (MySQL/MariaDB)؟ */
    private function hasIndexOnLeadingColumn(string $table, string $column): bool
    {
        $rows = DB::select("SHOW INDEX FROM `{$table}` WHERE Column_name = ? AND Seq_in_index = 1", [$column]);
        return count($rows) > 0;
    }

    public function up(): void
    {
        if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            return; // الفحص يعتمد على SHOW INDEX الخاص بـ MySQL
        }

        foreach ($this->indexes as $table => $indexList) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            foreach ($indexList as $columns) {
                foreach ($columns as $col) {
                    if (!Schema::hasColumn($table, $col)) {
                        continue 2;
                    }
                }
                // نتخطى الفهرس إذا كان العمود الأول مفهرساً بالفعل (مثلاً مفتاح أساسي أو فهرس قديم)
                if (count($columns) === 1 && $this->hasIndexOnLeadingColumn($table, $columns[0])) {
                    continue;
                }
                $name = $this->indexName($table, $columns);
                if (count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$name])) > 0) {
                    continue;
                }
                $cols = implode(', ', array_map(fn ($c) => "`{$c}`", $columns));
                DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$name}` ({$cols})");
            }
        }
    }

    public function down(): void
    {
        if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            return;
        }

        foreach ($this->indexes as $table => $indexList) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            foreach ($indexList as $columns) {
                $name = $this->indexName($table, $columns);
                if (count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$name])) > 0) {
                    DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$name}`");
                }
            }
        }
    }
};
