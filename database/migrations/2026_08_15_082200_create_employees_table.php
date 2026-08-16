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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 255);
            $table->string('name_en', 255);
            $table->string('email', 255);
            $table->string('phone', 255);
            $table->string('department', 255);
            $table->double('salary', 8, 2)->default(0.00);
            $table->decimal('housing_allowance', 10, 2)->nullable()->default(0.00);
            $table->decimal('transportation_allowance', 10, 2)->nullable()->default(0.00);
            $table->decimal('other_allowances', 10, 2)->nullable()->default(0.00);
            $table->string('nationality', 255);
            $table->integer('old')->default(0);
            $table->string('sex', 255)->default('Male');
            $table->bigInteger('personal_identification')->default(0);
            $table->double('total_leave_days')->default(21);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
