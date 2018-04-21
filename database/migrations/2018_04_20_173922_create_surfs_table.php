<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surfs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('spot_id')->unsigned();
            $table->foreign('spot_id')->references('id')->on('spots');
            $table->integer('msw_forecast_id')->unsigned();
            $table->foreign('msw_forecast_id')->references('id')->on('msw_forecasts');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('date_start');
            $table->integer('date_end');
            $table->smallInteger('swell_size');
            $table->smallInteger('wind_speed');
            $table->string('wind_direction');
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
        Schema::dropIfExists('surfs');
    }
}
