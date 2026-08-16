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
        Schema::create('transactiontosuplliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('suplier_id');
            $table->double('paidـamount', 8, 2)->default(0.00);
            $table->string('Pay_Method_Name', 255);
            $table->unsignedBigInteger('branchs_id');
            $table->string('note', 255)->default('-');
            $table->double('currentblance')->default(0);
            $table->text('attachments')->nullable();
            $table->integer('orginal_type')->default(0);
            $table->bigInteger('orginal_id')->nullable();
            $table->bigInteger('dely_record')->default(0);
            $table->integer('debtor')->default(0);
            $table->integer('creditor')->default(0);
            $table->index(['user_id'], 'transactiontosuplliers_user_id_foreign');
            $table->index(['branchs_id'], 'transactiontosuplliers_branchs_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactiontosuplliers');
    }
};
