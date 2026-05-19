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
        Schema::create('products_damages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'product_id' )->default(0);
            $table->bigInteger( 'branchs_id' )->default(0);  
            $table->bigInteger( 'user_id' )->default(0);  
            $table->string( 'product_name' )->default('-');    
            $table->bigInteger( 'damage_quantity' )->default(0);
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
        Schema::dropIfExists('products_damages');
    }
};
