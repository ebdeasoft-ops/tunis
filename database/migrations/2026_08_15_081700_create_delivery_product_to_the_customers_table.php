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
        Schema::create('delivery_product_to_the_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_from');
            $table->unsignedBigInteger('branch_to');
            $table->unsignedBigInteger('user_from');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('invoice_id');
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('status')->default(0);
            $table->bigInteger('user_delivery')->default(0);
            $table->index(['product_id'], 'delivery_product_to_the_customers_product_id_foreign');
            $table->index(['invoice_id'], 'delivery_product_to_the_customers_invoice_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_product_to_the_customers');
    }
};
