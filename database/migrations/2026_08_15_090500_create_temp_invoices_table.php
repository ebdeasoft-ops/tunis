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
        Schema::create('temp_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branchs_id');
            $table->decimal('Price', 8, 2)->default(0.00);
            $table->decimal('Added_Value', 8, 2)->default(0.00);
            $table->decimal('Number_of_Quantity', 8, 2)->default(0.00);
            $table->string('note', 255)->default('-');
            $table->string('Pay', 255)->default('-');
            $table->integer('status')->default(0);
            $table->double('discountOnInvoice')->default(0);
            $table->double('discount')->default(0);
            $table->double('creaditamount')->default(0);
            $table->double('bankamount')->default(0);
            $table->double('cashamount')->default(0);
            $table->double('Bank_transfer')->default(0);
            $table->integer('save')->default(0);
            $table->integer('morepayment_way')->default(0);
            $table->double('discountOnProduct')->default(0);
            $table->bigInteger('update_invoice')->default(0);
            $table->integer('pending_invoice')->default(1);
            $table->index(['user_id'], 'temp_invoices_user_id_foreign');
            $table->index(['branchs_id'], 'temp_invoices_branch_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_invoices');
    }
};
