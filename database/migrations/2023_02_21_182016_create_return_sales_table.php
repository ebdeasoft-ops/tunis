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
        Schema::create('return_sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'branch_id' )->unsigned();
            $table->foreign('branch_id')->references('id')->on('branchs')->onDelete('cascade');
            $table->bigInteger( 'product_id' )->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger( 'invoice_id' )->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->string('value')->default('empty');
            $table->float('return_Added_Value')->default(0);
            $table->decimal('return_Unit_Price')->default(0);
            $table->bigInteger('return_quantity')->default(0);
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
        Schema::dropIfExists('return_sales');
    }
};
