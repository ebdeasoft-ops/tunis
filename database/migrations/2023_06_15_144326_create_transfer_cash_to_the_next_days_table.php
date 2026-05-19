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
        Schema::create('transfer_cash_to_the_next_days', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'user_id' )->default(1);   
            $table->unsignedDouble( 'amount' )->default(0);
            $table->unsignedDouble( 'currentamount' )->default(0);
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
        Schema::dropIfExists('transfer_cash_to_the_next_days');
    }
};
