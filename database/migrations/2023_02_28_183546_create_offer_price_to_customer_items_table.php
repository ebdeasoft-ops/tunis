<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_price_to_customer_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'product_id' )->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger( 'order_id' )->unsigned();
            $table->foreign('order_id')->references('id')->on('offer_price_to_customers')->onDelete('cascade'); 
            $table->bigInteger('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_price_to_customer_items');
    }
};
