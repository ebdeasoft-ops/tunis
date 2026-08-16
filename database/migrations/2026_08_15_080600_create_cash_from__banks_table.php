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
        Schema::create('cash_from__banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(1);
            $table->bigInteger('branchs_id')->default(1);
            $table->double('the_amount', 8, 2)->default(0.00);
            $table->string('payment_method', 255)->default('-');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_from__banks');
    }
};
