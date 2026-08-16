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
        Schema::create('resource_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderId');
            $table->unsignedBigInteger('branchs_id');
            $table->unsignedBigInteger('suplier_id');
            $table->double('In_debt', 8, 2)->default(0.00);
            $table->string('Pay_Method_Name', 255);
            $table->string('notes', 255)->default('لا توجد ملاحظات');
            $table->double('recoveredـpieces', 8, 2)->default(0.00);
            $table->decimal('Other expenses', 8, 2)->default(0.00);
            $table->decimal('shipping fee', 8, 2)->default(0.00);
            $table->text('purchase_invoice_no')->nullable();
            $table->text('Purchase_invoice_number')->nullable();
            $table->integer('save')->default(0);
            $table->double('discount')->default(0);
            $table->text('attachments')->nullable();
            $table->bigInteger('count_invoice')->default(0);
            $table->integer('branchMainId')->nullable();
            $table->index(['branchs_id'], 'resource_purchases_branchs_id_foreign');
            $table->index(['suplier_id'], 'resource_purchases_suplier_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_purchases');
    }
};
