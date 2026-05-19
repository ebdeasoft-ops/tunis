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
        Schema::create('product_movement_another_branch_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( 'order_id' )->default(1);
            $table->bigInteger( 'product_id' )->default(1);
            $table->bigInteger( 'quantity' )->default(1);
            $table->unsignedDouble( 'cost_per_each_withoud_tax' )->default(1);
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
        Schema::dropIfExists('product_movement_another_branch_items');
    }
};
