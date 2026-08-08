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
 Schema::create('hr_settings', function (Blueprint $table) {
    $table->id();
    $table->time('official_check_in')->default('08:00:00');
    $table->time('official_check_out')->default('16:00:00');
    $table->integer('grace_period_minutes')->default(15);
    $table->string('weekend_days')->default('friday'); // خيارات: friday أو friday_saturday
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
        Schema::dropIfExists('hr_settings');
    }
};
