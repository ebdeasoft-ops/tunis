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
        Schema::create('acounts_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->default('فارغ');
            $table->string('name_en')->default('empty');
            $table->tinyInteger('active');
            $table->tinyInteger('relatediternalaccounts');
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
        Schema::dropIfExists('acounts_types');
    }
};
