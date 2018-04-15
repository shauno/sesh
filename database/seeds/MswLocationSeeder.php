<?php

use Illuminate\Database\Seeder;

class MswLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('msw_continents')->insert([
            'id' => 2,
            'name' => 'Africa',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('msw_regions')->insert([
            'id' => 3,
            'msw_continent_id' => 2,
            'name' => 'South Africa',
            'latitude_nw' => -25.0,
            'longitude_nw' => 14.0,
            'latitude_se' => -27.0,
            'longitude_se' => 33.5,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('msw_countries')->insert([
            'id' => 7,
            'msw_region_id' => 3,
            'name' => 'South Africa',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('msw_surf_areas')->insert([
            'id' => 224,
            'msw_country_id' => 7,
            'name' => 'Cape Peninsula',
            'timezone' => 'Africa/Johannesburg',
            'latitude' => -32.31360000,
            'longitude' => 18.33070000,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('msw_spots')->insert([
            'id' => 4625,
            'msw_surf_area_id' => 224,
            'name' => 'Big Bay',
            'latitude' => -33.79540000,
            'longitude' => 18.45400000,
            'timezone' => 'Africa/Johannesburg',
            'is_big_wave' => 0,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
