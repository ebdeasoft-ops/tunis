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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('order_owner');
            $table->string('product_name', 255);
            $table->decimal('purchasingـprice', 8, 2)->default(0.00);
            $table->double('numberofpice')->default(0);
            $table->double('Added_Value', 8, 3)->default(0.000);
            $table->double('returns_purchase', 8, 2)->default(0.00);
            $table->integer('save')->default(0);
            $table->double('reamingQuantity')->default(0);
            $table->text('unit')->nullable();
            $table->double('sale_price')->default(0);
            $table->index(['product_id'], 'order_details_product_id_foreign');
            $table->index(['order_owner'], 'order_details_order_owner_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
