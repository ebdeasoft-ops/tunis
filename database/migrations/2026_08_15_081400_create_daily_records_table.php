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
        Schema::create('daily_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('general_notes')->nullable();
            $table->decimal('total_debit', 15, 2)->nullable()->default(0.00);
            $table->decimal('total_credit', 15, 2)->nullable()->default(0.00);
            $table->unsignedBigInteger('user_id');
            $table->index(['user_id'], 'fk_daily_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_records');
    }
};
