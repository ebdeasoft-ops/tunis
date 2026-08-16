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
        Schema::create('transfer_cash_to_the_next_days', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(1);
            $table->double('amount')->unsigned()->default(0);
            $table->double('currentamount')->unsigned()->default(0);
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
        Schema::dropIfExists('transfer_cash_to_the_next_days');
    }
};
