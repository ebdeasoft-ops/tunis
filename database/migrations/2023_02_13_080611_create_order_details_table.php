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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            $table->bigInteger( 'product_id' )->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger( 'order_owner' )->unsigned();
            $table->foreign('order_owner')->references('id')->on('order_tosuplliers')->onDelete('cascade');
            $table->string('product_name');
            $table->decimal('purchasingـprice')->default(0);
            $table->decimal('sale_price')->default(0);
            $table->bigInteger('numberofpice')->default(0);
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
        Schema::dropIfExists('order_details');
    }
};
