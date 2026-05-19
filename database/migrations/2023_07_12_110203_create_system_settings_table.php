<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {

            $table->id();
            $table->string('name_ar')->default('empty');
            $table->string('name_en')->default('empty');
            $table->string('SR')->default('empty');
            $table->string('Tax')->default('empty');
            $table->string('logo')->default('empty');
            $table->string( 'descriptionenglish' )->default('-');
            $table->string( 'descriptionarbic' )->default('-');
            $table->string('address_ar')->default('empty');
            $table->string('address_en')->default('empty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
};
