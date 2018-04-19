<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMswForecastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msw_forecasts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('msw_spot_id');
            $table->foreign('msw_spot_id')->references('id')->on('msw_spots');
            $table->integer('timestamp');
            $table->integer('localTimestamp')->index();
            $table->integer('issueTimestamp');
            $table->integer('gfsIssueTimestamp');
            $table->string('threeHourTimeText');

            $table->tinyInteger('fadedRating');
            $table->tinyInteger('solidRating');

            $table->float('swell_minBreakingHeight');
            $table->float('swell_absMinBreakingHeight');
            $table->float('swell_maxBreakingHeight');
            $table->float('swell_absMaxBreakingHeight');
            $table->float('swell_primary_height')->index();
            $table->float('swell_primary_absHeight')->index();
            $table->float('swell_primary_period')->index();
            $table->float('swell_primary_direction')->index();
            $table->string('swell_primary_compassDirection');

            $table->float('wind_speed')->index();
            $table->float('wind_direction')->index();
            $table->string('wind_compassDirection');
            $table->float('wind_gusts');
            $table->string('wind_unit');
            $table->float('wind_rating');
            $table->string('wind_stringDirection');

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
        Schema::dropIfExists('msw_forecasts');
    }
}
