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
        Schema::create('cash_from__banks', function (Blueprint $table) {
            $table->id();

            $table->bigInteger( 'user_id' )->default(1);
            $table->bigInteger( 'branchs_id' )->default(1);
            $table->float('the_amount')->default(0.0);

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
        Schema::dropIfExists('cash_from__banks');
    }
};
