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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('mobile', 255);
            $table->bigInteger('trn');
            $table->bigInteger('crn');
            $table->string('street_name', 255);
            $table->integer('building_number');
            $table->integer('plot_identification');
            $table->string('region', 255);
            $table->string('city', 255);
            $table->integer('postal_number');
            $table->string('egs_serial_number', 255);
            $table->text('business_category')->nullable();
            $table->string('common_name', 255);
            $table->string('organization_unit_name', 255);
            $table->string('organization_name', 255);
            $table->string('country_name', 255)->default('SA');
            $table->string('registered_address', 255);
            $table->string('otp', 255);
            $table->string('email_address', 255);
            $table->enum('invoice_type', ['1100','0100','1000']);
            $table->boolean('is_production')->default(0);
            $table->longText('cnf')->nullable();
            $table->longText('private_key')->nullable();
            $table->longText('public_key')->nullable();
            $table->longText('csr_request')->nullable();
            $table->longText('certificate')->nullable();
            $table->string('secret', 255)->nullable();
            $table->string('csid', 255)->nullable();
            $table->longText('production_certificate')->nullable();
            $table->string('production_secret', 255)->nullable();
            $table->string('production_csid', 255)->nullable();
            $table->unsignedBigInteger('company_id');
            $table->integer('invoices_count')->default(1);
            $table->text('previous_hash_invoice')->nullable();
            $table->integer('branchs_id')->default(0);
            $table->index(['company_id'], 'settings_company_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
