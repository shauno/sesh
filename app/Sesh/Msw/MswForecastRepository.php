<?php

namespace Sesh\Msw;

use App\MswForecast;
use App\MswSpot;
use App\Surf;
use Illuminate\Database\Eloquent\Collection;

class MswForecastRepository
{
    /**
     * @param MswSpot $mswSpot
     * @param int $dateStart
     * @param int $dateEnd
     * @return Collection
     */
    public function findByDate(MswSpot $mswSpot, int $dateStart, int $dateEnd) : Collection
    {
        return $mswSpot->mswForecasts()
            ->where('timestamp', '>=', $dateStart)
            ->where('timestamp', '<=', $dateEnd)
            ->get();
    }

    /**
     * @param Surf $surf
     * @return MswForecast|null
     * @throws \Exception
     */
    public function findForSurf(Surf $surf) : ?MswForecast
    {
        if (!$spot = $surf->spot) {
            return null;
        }

        $forecast = $this->findByDate($spot->mswSpot, $surf->date_start, $surf->date_end);

        // If there is no forecast in the range, tack on 3 hours. Forecasts are saved in 3 hour intervals so this should
        // guarantee one unless there are none in a reasonable range due to an import issue
        if ($forecast->isEmpty()) {
            $forecast = $this->findByDate($spot->mswSpot, $surf->date_start, $surf->date_end + (60*60*3));
        }

        return $forecast->last();
    }
}
