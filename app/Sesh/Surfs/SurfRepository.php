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
              todays_forecasts.id as id,
              (1-abs(todays_forecasts.swell_primary_absHeight - matching_forecasts.swell_primary_absHeight) / ((todays_forecasts.swell_primary_absHeight + matching_forecasts.swell_primary_absHeight) / 2)) * 100 AS swell_height_match,
              (1-abs(todays_forecasts.swell_primary_period - matching_forecasts.swell_primary_period) / ((todays_forecasts.swell_primary_period + matching_forecasts.swell_primary_period) / 2)) * 100 AS swell_period_match,
              (1-abs(todays_forecasts.wind_speed - matching_forecasts.wind_speed) / ((todays_forecasts.wind_speed + matching_forecasts.wind_speed) / 2)) * 100 as wind_speed_match,
              (SELECT (swell_height_match + swell_period_match + wind_speed_match) / 3) as average_match              
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

        foreach ($matches as $match) {
            $return['matches'][$match->id][$match->spot_id]['surfs'][$match->surf_id] = [
                'swell_height_match' => $match->swell_height_match,
                'swell_period_match' => $match->swell_period_match,
                'wind_speed_match' => $match->wind_speed_match,
                'average_match' => $match->average_match,
            ];

            $return['refs']['forecasts'][$match->id] = $return['refs']['forecasts'][$match->id] ?? MswForecast::find($match->id);
            $return['refs']['spots'][$match->spot_id] = $return['refs']['spots'][$match->spot_id] ?? Spot::find($match->spot_id);
            $return['refs']['surfs'][$match->surf_id] = $return['refs']['surfs'][$match->surf_id] ?? Surf::with(['spot', 'mswForecast'])->find($match->surf_id);
        }

        //calculate averages
        foreach ($return['matches'] as $forecast_id => $spots) {
            foreach ($spots as $spot_id => $surfs) {
                $avg = [
                    'swell_size' => 0,
                    'wind_speed' => 0,
                    'average_match' => 0,
                ];
                foreach ($surfs['surfs'] as $surf_id => $surf) {
                    $avg['swell_size'] = $avg['swell_size'] + $return['refs']['surfs'][$surf_id]->swell_size;
                    $avg['wind_speed'] = $avg['wind_speed'] + $return['refs']['surfs'][$surf_id]->wind_speed;
                    $avg['average_match'] = $avg['average_match'] + $surf['average_match'];
                }
                $return['matches'][$forecast_id][$spot_id]['averages'] = [
                    'swell_size' => $avg['swell_size'] / count($surfs['surfs']),
                    'wind_speed' => $avg['wind_speed'] / count($surfs['surfs']),
                    'average_match' => $avg['average_match'] / count($surfs['surfs']),
                ];
            }
        }

        return $return;
    }
}