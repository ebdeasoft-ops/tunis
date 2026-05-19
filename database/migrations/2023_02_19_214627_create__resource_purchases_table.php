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
        Schema::create('resource_purchases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'orderId' )->unsigned();

            $table->bigInteger( 'branchs_id' )->unsigned();
            $table->foreign('branchs_id')->references('id')->on('branchs')->onDelete('cascade');
       
            $table->bigInteger( 'suplier_id' )->unsigned();
            $table->foreign('suplier_id')->references('id')->on('suplliers')->onDelete('cascade');
            $table->float('In_debt')->default(0);
            $table->string('Pay_Method_Name');
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
        Schema::dropIfExists('_resource_purchases');
    }
};
