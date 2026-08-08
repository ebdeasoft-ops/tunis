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
        Schema::create('delivery_product_to_the_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'branch_from' )->unsigned();
            $table->bigInteger( 'branch_to' )->unsigned();
            $table->bigInteger( 'user_from' )->unsigned();
            $table->bigInteger( 'product_id' )->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger( 'invoice_id' )->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('status')->default(0);
            $table->bigInteger('user_delivery')->default(0);
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
        Schema::dropIfExists('delivery_product_to_the_customers');
    }
};
