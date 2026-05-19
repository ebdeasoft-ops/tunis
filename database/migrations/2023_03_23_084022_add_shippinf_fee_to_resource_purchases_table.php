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
        Schema::table('resource_purchases', function (Blueprint $table) {
            //
            $table->decimal( 'Other expenses' )->default(0);
            $table->decimal( 'shipping fee' )->default(0);
            $table->bigInteger( 'purchase_invoice_no' )->default(0);

         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resource_purchases', function (Blueprint $table) {
            //
        });
    }
};
