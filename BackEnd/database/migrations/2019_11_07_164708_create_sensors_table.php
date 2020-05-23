<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('device_id')->unsigned()->index();
            $table->float('temperature')->default(0);
            $table->integer('humidity')->default(0);
            $table->integer('light')->default(0);
            $table->float('EC')->default(0);
            $table->integer('PPM')->default(0);
            $table->integer('water')->default(0);
            $table->boolean('pump')->default(0);
            $table->boolean('water_in')->default(0);
            $table->boolean('water_out')->default(0);
            $table->boolean('mix')->default(0);
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
        Schema::dropIfExists('sensors');
    }
}
