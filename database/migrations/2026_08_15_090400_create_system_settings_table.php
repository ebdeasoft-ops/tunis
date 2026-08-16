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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 255)->default('empty');
            $table->string('name_en', 255)->default('empty');
            $table->string('SR', 255)->default('empty');
            $table->string('Tax', 255)->default('empty');
            $table->string('logo', 255)->default('empty');
            $table->string('address_ar', 255)->default('empty');
            $table->string('address_en', 255)->default('empty');
            $table->double('serviceCost', 8, 2)->default(0.00);
            $table->double('deliveryCost', 8, 2)->default(0.00);
            $table->text('descriptionarbic')->nullable();
            $table->text('descriptionenglish')->nullable();
            $table->double('discount_on_invoice')->default(100);
            $table->text('bank_acount_iban')->nullable();
            $table->text('bank_acount_number')->nullable();
            $table->text('bankname')->nullable();
            $table->integer('branchs_id')->default(1);
            $table->text('previous_hash_invoice')->nullable();
            $table->integer('invoices_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
