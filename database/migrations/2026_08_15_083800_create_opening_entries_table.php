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
        Schema::create('opening_entries', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('entry_number');
            $table->date('entry_date');
            $table->text('general_note')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable()->default(0.00);
            $table->integer('created_by')->nullable();
            $table->unique(['entry_number'], 'entry_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_entries');
    }
};
