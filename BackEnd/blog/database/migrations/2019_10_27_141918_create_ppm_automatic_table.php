<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpmAutomaticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppm_automatic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index();
            $table->integer('nutrient_id')->unsigned()->index();
            $table->boolean('auto_mode')->default(0);
            $table->boolean('auto_status')->default(0);
            $table->timestamps();
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('nutrient_id')->references('id')->on('nutrients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ppm_automatic');
    }
}
