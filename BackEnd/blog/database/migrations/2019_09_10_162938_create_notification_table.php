<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id_send')->unsigned()->index();
            $table->integer('user_id_receive')->unsigned()->default(1)->index();
            $table->string('title', 50);
            $table->string('content', 2000);
            $table->boolean('seen')->default(0);
            $table->timestamps();
            $table->foreign('user_id_send')->references('id')->on('users');
            $table->foreign('user_id_receive')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification');
    }
}
