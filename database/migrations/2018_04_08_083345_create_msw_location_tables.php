<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMswLocationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msw_continents', function (Blueprint $table) {
            $table->integer('id')->unique('id', 'msw_continents_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('msw_regions', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->integer('msw_continent_id');
            $table->foreign('msw_continent_id')->references('id')->on('msw_continents');
            $table->string('name');
            $table->decimal('latitude_nw', 10, 8);
            $table->decimal('longitude_nw', 11, 8);
            $table->decimal('latitude_se', 10, 8);
            $table->decimal('longitude_se', 11, 8);
            $table->timestamps();
        });

        Schema::create('msw_countries', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->integer('msw_region_id');
            $table->foreign('msw_region_id')->references('id')->on('msw_regions');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('msw_surf_areas', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->integer('msw_country_id');
            $table->foreign('msw_country_id')->references('id')->on('msw_countries');
            $table->string('name');
            $table->string('timezone');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });

        Schema::create('msw_spots', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->integer('msw_surf_area_id');
            $table->foreign('msw_surf_area_id')->references('id')->on('msw_surf_areas');
            $table->string('name');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('timezone');
            $table->boolean('is_big_wave');
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
        Schema::dropIfExists('msw_spots');
        Schema::dropIfExists('msw_surf_areas');
        Schema::dropIfExists('msw_countries');
        Schema::dropIfExists('msw_regions');
        Schema::dropIfExists('msw_continents');
    }
}
