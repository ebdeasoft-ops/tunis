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
        Schema::create('product_movement_another_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('branch_from')->default(1);
            $table->bigInteger('branch_to')->default(1);
            $table->bigInteger('user_from')->default(1);
            $table->bigInteger('user_to')->default(1);
            $table->double('Totalcost')->unsigned()->default(0);
            $table->bigInteger('reciveInvoiceNumber')->default(0);
            $table->bigInteger('send_invoice_number')->nullable();
            $table->double('cost_withod_tax')->default(0);
            $table->text('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_movement_another_branches');
    }
};
