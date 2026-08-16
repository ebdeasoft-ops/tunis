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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('contract_type', 255);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('basic_salary', 10, 2);
            $table->date('iqama_expiry_date')->nullable();
            $table->date('work_permit_expiry_date')->nullable();
            $table->index(['employee_id'], 'contracts_employee_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
