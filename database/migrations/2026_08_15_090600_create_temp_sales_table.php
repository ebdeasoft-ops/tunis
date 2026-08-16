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
        Schema::create('temp_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('invoices_id_delete')->nullable();
            $table->double('Discount_Value', 8, 2)->default(0.00);
            $table->unsignedBigInteger('branch_id');
            $table->double('Added_Value', 8, 2)->default(0.00);
            $table->double('reamingQuantity', 8, 2)->default(0.00);
            $table->double('discountreturn', 8, 2)->default(0.00);
            $table->double('quantityreturn', 8, 2)->default(0.00);
            $table->decimal('Unit_Price', 8, 2)->default(0.00);
            $table->bigInteger('quantity')->default(0);
            $table->integer('save')->default(0);
            $table->bigInteger('invoice_id');
            $table->index(['product_id'], 'temp_sales_product_id_foreign');
            $table->index(['invoices_id_delete'], 'temp_sales_invoice_id_foreign');
            $table->index(['branch_id'], 'temp_sales_branch_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_sales');
    }
};
