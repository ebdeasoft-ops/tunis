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
        Schema::create('avts', function (Blueprint $table) {
            $table->id();
            $table->float( 'AVT' )->default(15);
            $table->string( 'name_ar' )->default('نسبة ضريبة مبيعات');
            $table->string( 'name_en' )->default('sales tax rate');
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
        Schema::dropIfExists('avts');
    }
};
