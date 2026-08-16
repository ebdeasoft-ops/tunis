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
        Schema::create('offer_price_to_customer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('order_id');
            $table->double('quantity')->default(0);
            $table->double('PriceWithoudTax', 8, 2)->default(0.00);
            $table->double('discount', 8, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->index(['product_id'], 'offer_price_to_customer_items_product_id_foreign');
            $table->index(['order_id'], 'offer_price_to_customer_items_order_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_price_to_customer_items');
    }
};
