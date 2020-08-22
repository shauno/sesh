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

            /**
             * This column was orignall created as a string and then later changed to an int. But sqlite doens't like
             * the migration to change the column type, so just changing it on creation. Obviously existing system have
             * already run the alter and new system won't have string data to worry about. The seeders are also updated
             * to set ints
             */
            $table->smallInteger('wind_direction');
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
