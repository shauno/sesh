<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSurfsWindDirectionToIntegers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This is only for user data, the seeders have been updated to enter the correct values
        DB::statement('
          UPDATE `surfs` SET `wind_direction` = 
            CASE
              WHEN `wind_direction` = "offshore" THEN 1
              WHEN `wind_direction` = "cross-offshore" THEN 2
              WHEN `wind_direction` = "cross-shore" THEN 3
              WHEN `wind_direction` = "cross-onshore" THEN 4
              WHEN `wind_direction` = "onshore" THEN 5
              ELSE `wind_direction`
            END
        ');

        //For some reason I cannot just ->change() this column to an integer in sqlite
        //I really should research this a bit more but for now this does the trick to keep the tests working
        if (DB::getDriverName() != 'sqlite') {
            Schema::table('surfs', function (Blueprint $table) {
                $table->integer('wind_direction')->unsigned()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() != 'sqlite') {
            Schema::table('surfs', function (Blueprint $table) {
                $table->string('wind_direction')->change();
            });
        }

        DB::statement('
          UPDATE `surfs` SET `wind_direction` = 
            CASE
              WHEN `wind_direction` = 1 THEN "offshore"
              WHEN `wind_direction` = 2 THEN "cross-offshore"
              WHEN `wind_direction` = 3 THEN "cross-shore"
              WHEN `wind_direction` = 4 THEN "cross-onshore"
              WHEN `wind_direction` = 5 THEN "onshore"
              ELSE `wind_direction`
            END
        ');
    }
}
