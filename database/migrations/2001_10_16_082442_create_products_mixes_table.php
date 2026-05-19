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
        Schema::create('products_mixes', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble( 'cost_withoud_tax' )->default(0);
            $table->string( 'mixcode' )->default('mix_');
            $table->string( 'name' )->default('-');  
            $table->string( 'location' )->default('-');  
            $table->bigInteger( 'branchs_id' )->default(1);
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
        Schema::dropIfExists('products_mixes');
    }
};
