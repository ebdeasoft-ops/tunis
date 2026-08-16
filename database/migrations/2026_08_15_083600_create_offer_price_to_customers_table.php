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
        Schema::create('offer_price_to_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->bigInteger('branchs_id')->default(1);
            $table->text('notes')->nullable();
            $table->double('discount')->default(0);
            $table->integer('numbershowstatus')->default(0);
            $table->integer('type_of_decument')->default(1);
            $table->index(['customer_id'], 'offer_price_to_customers_customer_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_price_to_customers');
    }
};
