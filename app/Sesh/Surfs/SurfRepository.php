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
              todays_forecasts.localTimestamp,
              (1-abs(todays_forecasts.swell_primary_absHeight - matching_forecasts.swell_primary_absHeight) / ((todays_forecasts.swell_primary_absHeight + matching_forecasts.swell_primary_absHeight) / 2)) * 100 AS swell_height_match,
              (1-abs(todays_forecasts.swell_primary_period - matching_forecasts.swell_primary_period) / ((todays_forecasts.swell_primary_period + matching_forecasts.swell_primary_period) / 2)) * 100 AS swell_period_match,
              (1-abs(todays_forecasts.wind_speed - matching_forecasts.wind_speed) / ((todays_forecasts.wind_speed + matching_forecasts.wind_speed) / 2)) * 100 as wind_speed_match
            FROM msw_forecasts AS todays_forecasts
            JOIN msw_forecasts AS matching_forecasts ON (
              matching_forecasts.msw_spot_id = todays_forecasts.msw_spot_id
              AND todays_forecasts.swell_primary_absHeight >= matching_forecasts.swell_primary_absHeight - 2
              AND todays_forecasts.swell_primary_absHeight <= matching_forecasts.swell_primary_absHeight + 2
              AND todays_forecasts.swell_primary_period >= matching_forecasts.swell_primary_period - 2
              AND todays_forecasts.swell_primary_period <= matching_forecasts.swell_primary_period + 2
              AND todays_forecasts.wind_speed >= matching_forecasts.wind_speed - 2
              AND todays_forecasts.wind_speed <= matching_forecasts.wind_speed + 2
              /*Maths FTW https://stackoverflow.com/questions/12234574/calculating-if-an-angle-is-between-two-angles/12234633#12234633*/
              AND (matching_forecasts.wind_trueDirection - todays_forecasts.wind_trueDirection + 180 + 360) % 360 - 180 >= -10
              AND (matching_forecasts.wind_trueDirection - todays_forecasts.wind_trueDirection + 180 + 360) % 360 - 180 <= 10
              AND (matching_forecasts.swell_primary_trueDirection - todays_forecasts.swell_primary_trueDirection + 180 + 360) % 360 - 180 >= -10
              AND (matching_forecasts.swell_primary_trueDirection - todays_forecasts.swell_primary_trueDirection + 180 + 360) % 360 - 180 <= 10
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
            $return['matches'][$match->localTimestamp][$match->spot_id]['forecast_id'] = $match->id;
            $return['matches'][$match->localTimestamp][$match->spot_id]['surfs'][$match->surf_id] = [
                'swell_height_match' => $match->swell_height_match,
                'swell_period_match' => $match->swell_period_match,
                'wind_speed_match' => $match->wind_speed_match,
                'average_match' => ($match->swell_height_match + $match->wind_speed_match + $match->wind_speed_match) / 3,
            ];

            $return['refs']['forecasts'][$match->id] = $return['refs']['forecasts'][$match->id] ?? MswForecast::find($match->id);
            $return['refs']['spots'][$match->spot_id] = $return['refs']['spots'][$match->spot_id] ?? Spot::find($match->spot_id);
            $return['refs']['surfs'][$match->surf_id] = $return['refs']['surfs'][$match->surf_id] ?? Surf::with(['spot', 'mswForecast', 'photos'])->find($match->surf_id);
        }

        if ($return) {
            //calculate averages
            foreach ($return['matches'] as $timestamp => $spots) {
                foreach ($spots as $spot_id => $match) {
                    $avg = [
                        'swell_size' => 0,
                        'wind_speed' => 0,
                        'average_match' => 0,
                    ];
                    foreach ($match['surfs'] as $surf_id => $surf) {
                        $avg['swell_size'] = $avg['swell_size'] + $return['refs']['surfs'][$surf_id]->swell_size;
                        $avg['wind_speed'] = $avg['wind_speed'] + $return['refs']['surfs'][$surf_id]->wind_speed;
                        $avg['average_match'] = $avg['average_match'] + $surf['average_match'];
                    }
                    $return['matches'][$timestamp][$spot_id]['averages'] = [
                        'swell_size' => round($avg['swell_size'] / count($match['surfs'])),
                        'wind_speed' => round($avg['wind_speed'] / count($match['surfs'])),
                        'average_match' => round($avg['average_match'] / count($match['surfs'])),
                    ];
                }
            }
        }

        return $return;
    }
}