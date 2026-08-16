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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('phone', 255);
            $table->string('email', 255)->default('');
            $table->string('comp_name', 255)->default('');
            $table->string('address', 255)->default('لا يوجد عنوان');
            $table->string('notes', 255)->default('لا يوجد ملاحظات');
            $table->decimal('Limit_credit', 8, 2)->default(10000.00);
            $table->decimal('Balance', 8, 2)->default(0.00);
            $table->integer('grace_period_in_days')->default(30);
            $table->bigInteger('tax_no')->default(0);
            $table->bigInteger('mantob_account_id')->nullable();
            $table->double('opeing_blance')->default(0);
            $table->text('postcode')->nullable();
            $table->text('sub_city')->nullable();
            $table->text('street_name')->nullable();
            $table->text('building_number')->nullable();
            $table->text('plot_identification')->nullable();
            $table->text('CRN')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
