<?php

namespace Sesh\Msw;

use App\MswCountry;
use App\MswSpot;
use Illuminate\Database\Eloquent\Collection;

class MswSpotRepository
{
    /**
     * @param int $id
     * @return MswSpot
     */
    public function find(int $id) : MswSpot
    {
        return (new MswSpot())->find($id);
    }

    /**
     * @param MswCountry $mswCountry
     * @return \Illuminate\Database\Eloquent\Collection|MswSpot[]
     */
    public function findByCountry(MswCountry $mswCountry) :Collection
    {
        return (new MswSpot)
            ->with('mswSurfArea')
            ->whereHas('mswSurfArea', function($query) use($mswCountry) {
                $query->where('msw_surf_areas.msw_country_id', $mswCountry->id);
            })
            ->get();
    }
}