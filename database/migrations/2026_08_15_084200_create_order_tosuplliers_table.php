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
        Schema::create('order_tosuplliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suplier_id');
            $table->unsignedBigInteger('user_id');
            $table->string('Limit_credit', 255)->default('');
            $table->double('purchaseـamount', 8, 2)->default(0.00);
            $table->double('added_value', 8, 2)->default(0.00);
            $table->index(['suplier_id'], 'order_tosuplliers_suplier_id_foreign');
            $table->index(['user_id'], 'order_tosuplliers_user_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_tosuplliers');
    }
};
