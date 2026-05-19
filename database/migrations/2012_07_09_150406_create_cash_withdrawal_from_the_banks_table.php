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
        Schema::create('cash_withdrawal_from_the_banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'from_user_id' )->default(1);   
            $table->unsignedDouble( 'amount' )->default(0);
            $table->bigInteger( 'branchs_id' )->default(1);
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
        Schema::dropIfExists('cash_withdrawal_from_the_banks');
    }
};
