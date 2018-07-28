<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixSwellAndWindDirection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Need to do each column in it's own operation to support SQLite
        Schema::table('msw_forecasts', function(Blueprint $table) {
            $table->float('swell_primary_trueDirection')->nullable()->index()->after('swell_primary_direction');
        });

        Schema::table('msw_forecasts', function(Blueprint $table) {
            $table->float('wind_trueDirection')->nullable()->index()->after('wind_direction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //No need for down as messes with refreshing the db in sqlite test db :/
    }
}
