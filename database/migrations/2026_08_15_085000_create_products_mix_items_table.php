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
        Schema::create('products_mix_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('products_mix_id')->default(1);
            $table->double('quantity')->unsigned()->default(0);
            $table->double('cost')->unsigned()->default(0);
            $table->bigInteger('product_id')->default(1);
            $table->string('note', 255)->default('-');
            $table->double('Added_Value')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_mix_items');
    }
};
