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
        Schema::create('dliveries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('to_dlivery_id')->default(1);
            $table->double('blance')->default(0);
            $table->double('number_items')->default(0);
            $table->date('last_payment');
            $table->string('note', 255)->nullable();
            $table->bigInteger('supplier_id')->nullable();
            $table->integer('branchs_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dliveries');
    }
};
