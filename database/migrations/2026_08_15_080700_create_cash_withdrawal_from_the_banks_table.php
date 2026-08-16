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
        Schema::create('cash_withdrawal_from_the_banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('from_user_id')->default(1);
            $table->double('amount')->unsigned()->default(0);
            $table->bigInteger('branchs_id')->default(1);
            $table->string('note', 255)->default('-');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_withdrawal_from_the_banks');
    }
};
