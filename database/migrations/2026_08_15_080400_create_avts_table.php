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
        Schema::create('avts', function (Blueprint $table) {
            $table->id();
            $table->double('AVT', 8, 2)->default(15.00);
            $table->string('name_ar', 255)->default('نسبة ضريبة مبيعات');
            $table->string('name_en', 255)->default('sales tax rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avts');
    }
};
