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
        Schema::create('products_mix_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'products_mix_id' )->default(1);   
            $table->unsignedDouble( 'quantity' )->default(0);
            $table->unsignedDouble( 'cost' )->default(0);
            $table->bigInteger( 'product_id' )->default(1);
            $table->string( 'note' )->default('-');  
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
        Schema::dropIfExists('products_mix_items');
    }
};
