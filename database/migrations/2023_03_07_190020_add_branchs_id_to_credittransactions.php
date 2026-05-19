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
        Schema::table('credittransactions', function (Blueprint $table) {
            //
            $table->bigInteger( 'branchs_id' )->unsigned();
            $table->foreign('branchs_id')->references('id')->on('branchs')->onDelete('cascade');
     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credittransactions', function (Blueprint $table) {
            //
        });
    }
};
