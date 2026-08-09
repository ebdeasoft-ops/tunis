<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('end_of_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('join_date'); // تاريخ المباشرة
            $table->date('end_date'); // تاريخ ترك العمل
            $table->decimal('service_years', 8, 2); // عدد سنوات الخدمة المحسوبة
            $table->decimal('basic_salary', 10, 2); // آخر راتب أساسي
            $table->enum('reason', ['resignation', 'termination']); // سبب انتهاء الخدمة (استقالة / إنهاء خدمات)
            $table->decimal('reward_amount', 10, 2); // مبلغ المكافأة المستحق
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('end_of_services');
    }
};