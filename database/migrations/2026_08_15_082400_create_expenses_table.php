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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('Pay_Method_Name', 255);
            $table->string('Reasonforspendingmoney', 255);
            $table->double('Theـamountـpaid', 8, 2)->default(0.00);
            $table->bigInteger('branchs_id')->default(1);
            $table->unsignedBigInteger('reasonId_id');
            $table->bigInteger('expensesAvt')->default(0);
            $table->string('notes', 400)->default('-');
            $table->text('attachments')->nullable();
            $table->bigInteger('Transaction_id')->default(0);
            $table->integer('type')->default(1);
            $table->index(['user_id'], 'expenses_user_id_foreign');
            $table->index(['reasonId_id'], 'expenses_reasonid_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
