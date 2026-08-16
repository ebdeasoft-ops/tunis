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
        Schema::create('end_of_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('join_date');
            $table->date('end_date');
            $table->decimal('service_years', 8, 2);
            $table->decimal('basic_salary', 10, 2);
            $table->enum('reason', ['resignation','termination']);
            $table->decimal('reward_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->index(['employee_id'], 'end_of_services_employee_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_of_services');
    }
};
