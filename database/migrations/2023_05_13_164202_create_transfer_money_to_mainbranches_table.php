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
        Schema::create('transfer_money_to_mainbranches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'from_user_id' )->default(1);   
            $table->bigInteger( 'to_user_id' )->default(1);
            $table->unsignedDouble( 'amount' )->default(0);
            $table->bigInteger( 'branchs_id' )->default(1);
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
        Schema::dropIfExists('transfer_money_to_mainbranches');
    }
};
