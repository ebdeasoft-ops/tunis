<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            // أنواع الإجازات والجزاءات
            $table->enum('leave_type', [
                'annual',        // سنوية (تخصم من الـ 21 يوم)
                'casual',        // عارضة
                'sick',          // مرضية
                'unpaid',        // بدون راتب (تخصم كأيام عادية من الراتب)
                'unauthorized'   // غياب بدون إذن (يتم خصمها بـ ضعف الراتب / يومين عن كل يوم)
            ])->default('annual');

            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_count'); // عدد الأيام الفعلية للإجازة
            
            // المبلغ المخصوم من الراتب إن وجد
            $table->decimal('deduction_amount', 10, 2)->default(0); 
            
            $table->text('reason')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};