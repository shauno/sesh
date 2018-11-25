<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class SurfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('surfs')->insert([
            'id' => 1,
            'spot_id' => 1,
            'msw_forecast_id' => 1,
            'user_id' => 1,
            'date_start' => 1530424800,
            'date_end' => 1530432900,
            'swell_size' => 4,
            'wind_speed' => 2,
            'wind_direction' => 'offshore',
            'created_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
            'updated_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
        ]);
        DB::table('photos')->insert([
            'id' => 1,
            'surf_id' => 1,
            'user_id' => 1,
            'path' => UploadedFile::fake()->image('rad-surf.png')->store('surfs/'.date('Y/m/d')),
        ]);


        DB::table('surfs')->insert([
            'id' => 2,
            'spot_id' => 1,
            'msw_forecast_id' => 1,
            'user_id' => 2,
            'date_start' => 1530433800,
            'date_end' => 1530441000,
            'swell_size' => 2,
            'wind_speed' => 4,
            'wind_direction' => 'cross-offshore',
            'created_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
            'updated_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
        ]);
        DB::table('photos')->insert([
            'id' => 2,
            'surf_id' => 2,
            'user_id' => 2,
            'path' => UploadedFile::fake()->image('rad-surf-2.png')->store('surfs/'.date('Y/m/d')),
        ]);


        DB::table('surfs')->insert([
            'id' => 3,
            'spot_id' => 2,
            'msw_forecast_id' => 2,
            'user_id' => 1,
            'date_start' => 1530446400,
            'date_end' => 1530450000,
            'swell_size' => 3,
            'wind_speed' => 2,
            'wind_direction' => 'cross-offshore',
            'created_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
            'updated_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
        ]);
    }
}
