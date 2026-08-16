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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('invoice_id');
            $table->double('Discount_Value', 8, 2)->default(0.00);
            $table->unsignedBigInteger('branch_id');
            $table->double('Added_Value', 8, 2)->default(0.00);
            $table->decimal('Unit_Price', 8, 2)->default(0.00);
            $table->double('quantity')->default(0);
            $table->double('discountreturn')->default(0);
            $table->double('quantityreturn')->default(0);
            $table->integer('save')->default(0);
            $table->double('reamingQuantity')->default(0);
            $table->text('unit')->nullable();
            $table->integer('note')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('tax_rate')->nullable();
            $table->text('product_name')->nullable();
            $table->integer('save1')->default(0);
            $table->index(['product_id'], 'sales_product_id_foreign');
            $table->index(['invoice_id'], 'sales_invoice_id_foreign');
            $table->index(['branch_id'], 'sales_branch_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
