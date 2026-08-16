<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('name', 225);
            $table->text('name_en')->nullable();
            $table->integer('account_type');
            $table->boolean('is_parent')->default(0);
            $table->bigInteger('parent_account_number')->nullable();
            $table->bigInteger('account_number');
            $table->tinyInteger('start_balance_status');
            $table->decimal('start_balance', 10, 2);
            $table->decimal('current_balance', 16, 2)->default(0.00);
            $table->bigInteger('other_table_FK')->nullable();
            $table->string('notes', 225)->nullable();
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('com_code');
            $table->date('date');
            $table->bigInteger('orginal_id')->nullable();
            $table->integer('orginal_type')->nullable();
            $table->bigInteger('orginal_supplier')->nullable();
            $table->double('debtor_opening')->default(0);
            $table->double('creditor_opening')->default(0);
            $table->double('creditor_current', 15, 2)->default(0.00);
            $table->double('debtor_current', 15, 2)->default(0.00);
            $table->double('creditor_end')->default(0);
            $table->double('debtor_end')->default(0);
            $table->integer('branchs_id')->nullable();
            $table->text('tax_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_accounts');
    }
};
