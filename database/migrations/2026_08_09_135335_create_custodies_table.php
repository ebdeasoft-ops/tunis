<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('item_name'); // اسم العهدة (لابتوب، سيارة، موبايل...)
            $table->string('serial_number')->nullable(); // الرقم التسلسلي
            $table->date('delivery_date'); // تاريخ الاستلام
            $table->date('return_date')->nullable(); // تاريخ الإرجاع الفعلي
            $table->enum('status', ['delivered', 'returned'])->default('delivered'); // الحالة
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custodies');
    }
};