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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
             
            $table->bigInteger( 'branchs_id' )->unsigned();
            $table->foreign('branchs_id')->references('id')->on('branchs')->onDelete('cascade');
     
            $table->unsignedDouble('purchasingـprice')->default(0);
            $table->unsignedDouble  ('sale_price')->default(0);

            $table->bigInteger('numberofpice')->default(0);
            $table->string('Status', 50);
            $table->bigInteger( 'user_id' )->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
