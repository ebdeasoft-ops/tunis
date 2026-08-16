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
        Schema::create('return_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('value', 255)->default('empty');
            $table->double('return_Added_Value', 8, 2)->default(0.00);
            $table->decimal('return_Unit_Price', 8, 2)->default(0.00);
            $table->double('return_quantity')->default(0);
            $table->double('discountvalue')->default(0);
            $table->double('discountoninvoice')->default(0);
            $table->double('returnshabkavalue')->default(0);
            $table->integer('send_zatca')->default(0);
            $table->integer('user_id')->nullable();
            $table->double('tax_rate')->nullable()->default(0.15);
            $table->index(['branch_id'], 'return_sales_branch_id_foreign');
            $table->index(['product_id'], 'return_sales_product_id_foreign');
            $table->index(['invoice_id'], 'return_sales_invoice_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_sales');
    }
};
