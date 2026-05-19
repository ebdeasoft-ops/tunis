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
        Schema::create('product_movement_another_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'branch_from' )->default(1);
            $table->bigInteger( 'branch_to' )->default(1);
            $table->bigInteger( 'user_from' )->default(1);
            $table->bigInteger( 'user_to' )->default(1);
            $table->unsignedDouble( 'Totalcost' )->default(0);
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
        Schema::dropIfExists('product_movement_another_branches');
    }
};
