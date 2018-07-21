<?php

use Illuminate\Database\Seeder;

class MswForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('msw_forecasts')->insert([
            'id' => 1,
            'msw_spot_id' => 4625, //Big Bay, Cape Peninsula, South Africa, South Africa
            'timestamp' => 1530435600,
            'localTimestamp' => 1530435600, //2018-07-01T09:00:00+00:00
            'issueTimestamp' => 1530435600,
            'gfsIssueTimestamp' => 1530435600,
            'threeHourTimeText' => '9am',
            'fadedRating' => 0,
            'solidRating' => 4,
            'swell_minBreakingHeight' => 5,
            'swell_absMinBreakingHeight' => 5.12,
            'swell_maxBreakingHeight' => 7,
            'swell_absMaxBreakingHeight' => 7.23,
            'swell_primary_height' => 8,
            'swell_primary_absHeight' => 8.12,
            'swell_primary_period' => 13,
            'swell_primary_direction' => 270,
            'swell_primary_compassDirection' => 'W',
            'wind_speed' => 3,
            'wind_direction' => 90,
            'wind_compassDirection' => 'E',
            'wind_gusts' => 5,
            'wind_unit' => 'mph',
            'wind_rating' => 3,
            'wind_stringDirection' => 'Offshore',
            'created_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
            'updated_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
        ]);

        DB::table('msw_forecasts')->insert([
            'id' => 2,
            'msw_spot_id' => 4625, //Big Bay, Cape Peninsula, South Africa, South Africa
            'timestamp' => 1530446400,
            'localTimestamp' => 1530446400, //2018-07-01T12:00:00+00:00
            'issueTimestamp' => 1530446400,
            'gfsIssueTimestamp' => 1530446400,
            'threeHourTimeText' => 'Noon',
            'fadedRating' => 1,
            'solidRating' => 4,
            'swell_minBreakingHeight' => 5,
            'swell_absMinBreakingHeight' => 5.12,
            'swell_maxBreakingHeight' => 7,
            'swell_absMaxBreakingHeight' => 7.23,
            'swell_primary_height' => 8,
            'swell_primary_absHeight' => 8.12,
            'swell_primary_period' => 13,
            'swell_primary_direction' => 270,
            'swell_primary_compassDirection' => 'W',
            'wind_speed' => 5,
            'wind_direction' => 80,
            'wind_compassDirection' => 'ESE',
            'wind_gusts' => 10,
            'wind_unit' => 'mph',
            'wind_rating' => 3,
            'wind_stringDirection' => 'Offshore',
            'created_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
            'updated_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
        ]);
    }
}
