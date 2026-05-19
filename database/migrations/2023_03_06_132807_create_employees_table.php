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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string( 'name_ar' );
            $table->string( 'name_en' );
            $table->string( 'email' );
            $table->string( 'phone' );
            $table->string( 'department' );
            $table->float( 'salary' )->default(0);
            $table->string( 'nationality' );
            $table->integer( 'old' )->default(0);
            $table->string( 'sex' )->default('Male');
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
        Schema::dropIfExists('employees');
    }
};
