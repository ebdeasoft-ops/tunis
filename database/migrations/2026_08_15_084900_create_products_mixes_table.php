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
        Schema::create('products_mixes', function (Blueprint $table) {
            $table->id();
            $table->double('cost_withoud_tax')->unsigned()->default(0);
            $table->string('mixcode', 255)->default('mix_');
            $table->string('name', 255)->default('-');
            $table->string('location', 255)->default('-');
            $table->bigInteger('branchs_id')->default(1);
            $table->double('Added_Value', 32, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_mixes');
    }
};
