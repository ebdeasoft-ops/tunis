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
        Schema::create('credittransactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->double('recive_amount', 8, 2)->default(0.00);
            $table->string('pay_method', 255);
            $table->string('Pay_Method_Name', 255);
            $table->unsignedBigInteger('branchs_id');
            $table->string('note', 255)->default('-');
            $table->double('currentblance', 20, 2)->default(0.00);
            $table->text('attachments')->nullable();
            $table->integer('orginal_type')->default(0);
            $table->integer('orginal_id')->nullable();
            $table->bigInteger('dely_record')->default(0);
            $table->double('debtor', 8, 2)->default(0.00);
            $table->double('creditor', 8, 2)->default(0.00);
            $table->integer('vat')->default(0);
            $table->text('name')->nullable();
            $table->text('tax')->nullable();
            $table->integer('decument_id')->default(0);
            $table->integer('type_decument')->default(1);
            $table->integer('save')->default(1);
            $table->bigInteger('parent_dely_record')->nullable();
            $table->integer('Opening_entry')->default(0);
            $table->integer('parent_Opening_entry')->default(0);
            $table->date('date_export')->nullable();
            $table->bigInteger('sent_serf_count')->default(0);
            $table->bigInteger('sent_abd_count')->default(0);
            $table->integer('cost_center')->default(0);
            $table->text('type')->nullable();
            $table->text('tax_rate')->nullable();
            $table->index(['user_id'], 'credittransactions_user_id_foreign');
            $table->index(['branchs_id'], 'credittransactions_branchs_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credittransactions');
    }
};
