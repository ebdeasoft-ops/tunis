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
        Schema::create('suplliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('phone', 255);
            $table->string('location', 255);
            $table->string('email', 255)->default('');
            $table->string('comp_name', 255)->default('');
            $table->double('In_debt', 8, 2)->default(0.00);
            $table->bigInteger('TaxـNumber')->default(0);
            $table->bigInteger('mantob_account_id')->nullable();
            $table->double('opeing_blance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suplliers');
    }
};
