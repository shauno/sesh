<?php

namespace Sesh\Surfs;

use App\MswForecast;
use App\Spot;
use App\Surf;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SurfRepository
{
    /**
     * @param Guard $user
     * @param int $limit
     * @return Collection|Surf[]
     */
    public function findAllForUser(Guard $user, int $limit = 5) : Collection
    {
        return (new Surf())
            ->where('user_id', $user->id())
            ->limit($limit)
            ->get();
    }

    /**
     * TODO really need to clean this up :(
     *
     * @param Collection|Spot[] $spots
     * @param \DateTime $date
     * @return array
     */
    public function findMatches(Collection $spots, \DateTime $date)
    {
        $matches = DB::select('
            SELECT 
              surfs.id AS surf_id,
              spots.id AS spot_id,
              todays_forecasts.*,
              (1-abs(todays_forecasts.swell_primary_absHeight - matching_forecasts.swell_primary_absHeight) / ((todays_forecasts.swell_primary_absHeight + matching_forecasts.swell_primary_absHeight) / 2)) * 100 AS swell_height_match,
              (1-abs(todays_forecasts.swell_primary_period - matching_forecasts.swell_primary_period) / ((todays_forecasts.swell_primary_period + matching_forecasts.swell_primary_period) / 2)) * 100 AS swell_period_match,
              (1-abs(todays_forecasts.wind_speed - matching_forecasts.wind_speed) / ((todays_forecasts.wind_speed + matching_forecasts.wind_speed) / 2)) * 100 as wind_speed_match
              
            FROM msw_forecasts AS todays_forecasts
            JOIN msw_forecasts AS matching_forecasts ON (
              todays_forecasts.id != matching_forecasts.id
              AND todays_forecasts.msw_spot_id = todays_forecasts.msw_spot_id
              AND todays_forecasts.swell_primary_absHeight >= matching_forecasts.swell_primary_absHeight - 2
              AND todays_forecasts.swell_primary_absHeight <= matching_forecasts.swell_primary_absHeight + 2
              AND todays_forecasts.swell_primary_period >= matching_forecasts.swell_primary_period - 2
              AND todays_forecasts.swell_primary_period <= matching_forecasts.swell_primary_period + 2
              AND todays_forecasts.wind_speed >= matching_forecasts.wind_speed - 2
              AND todays_forecasts.wind_speed <= matching_forecasts.wind_speed + 2
              AND IF(
                ((matching_forecasts.swell_primary_direction - 10) < 0) OR ((matching_forecasts.swell_primary_direction + 10) >= 0),
                todays_forecasts.swell_primary_direction > matching_forecasts.swell_primary_direction - 10
                  OR todays_forecasts.swell_primary_direction + 10 < matching_forecasts.swell_primary_direction + 10,
                todays_forecasts.swell_primary_direction > matching_forecasts.swell_primary_direction - 10
                  AND todays_forecasts.swell_primary_direction < matching_forecasts.swell_primary_direction + 10
              )
              AND IF(
                ((matching_forecasts.wind_direction - 10) < 0) OR ((matching_forecasts.wind_direction + 10) >= 0),
                todays_forecasts.wind_direction  > matching_forecasts.wind_direction - 10
                  OR todays_forecasts.wind_direction < matching_forecasts.wind_direction + 10,
                todays_forecasts.wind_direction > matching_forecasts.wind_direction - 10
                  AND todays_forecasts.wind_direction < matching_forecasts.wind_direction + 10
              )
            )
            JOIN surfs ON surfs.msw_forecast_id = matching_forecasts.id
            JOIN spots ON spots.id = surfs.spot_id
            WHERE 
            spots.id IN ('.implode(',', $spots->pluck('id')->all()).')
            AND todays_forecasts.localTimestamp >= '.$date->setTime(0,0,0)->getTimestamp().'
            AND todays_forecasts.localTimestamp <= '.$date->setTime(23,59,59)->getTimestamp().'
            ORDER BY todays_forecasts.localTimestamp, spots.id
        ');

        $return = [];
        $surfs = [];
        foreach($matches as $match) {
            $surfs[$match->surf_id] = $surfs[$match->surf_id] ?? Surf::with(['spot', 'mswForecast'])->find($match->surf_id);

            $return[$match->localTimestamp]['msw_forecast'] = MswForecast::find($match->id);
            $return[$match->localTimestamp]['surfs'][] =
                [
                    'surf' => $surfs[$match->surf_id],
                    'matches' => [
                        'swell_height' => $match->swell_height_match,
                        'swell_period' => $match->swell_period_match,
                        'wind_speed' => $match->wind_speed_match,
                    ]
                ];

        }

        return $return;
    }
}