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
        Schema::create('dlivery_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->default(1);
            $table->text('product_name')->nullable();
            $table->double('cost')->default(0);
            $table->double('quantity')->default(0);
            $table->string('states', 255)->default('0');
            $table->double('Added_value')->default(0);
            $table->double('discount')->default(0);
            $table->bigInteger('supplier_id')->nullable();
            $table->timestamp('last_payment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dlivery_items');
    }
};
