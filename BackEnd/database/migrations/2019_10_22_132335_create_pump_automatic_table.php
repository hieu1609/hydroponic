<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePumpAutomaticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pump_automatic', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('device_id')->unsigned()->index();
            $table->integer('time_on')->default(5);
            $table->integer('time_off')->default(10);
            $table->boolean('auto')->default(0);
            $table->timestamps();
            $table->foreign('device_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_automatic');
    }
}
