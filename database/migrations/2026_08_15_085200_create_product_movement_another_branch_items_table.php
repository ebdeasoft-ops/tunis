<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_movement_another_branch_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->default(1);
            $table->bigInteger('product_id')->default(1);
            $table->bigInteger('quantity')->default(1);
            $table->double('cost_per_each_withoud_tax')->unsigned()->default(1);
            $table->text('order_id_sender')->nullable();
            $table->integer('reciver_branch')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_movement_another_branch_items');
    }
};
