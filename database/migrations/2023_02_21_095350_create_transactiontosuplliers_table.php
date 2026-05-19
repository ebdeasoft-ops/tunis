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
        Schema::create('transactiontosuplliers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'user_id' )->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger( 'suplier_id' )->unsigned();
            $table->foreign('suplier_id')->references('id')->on('suplliers')->onDelete('cascade');
            $table->float('paidـamount')->default(0);
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
        Schema::dropIfExists('transactiontosuplliers');
    }
};
