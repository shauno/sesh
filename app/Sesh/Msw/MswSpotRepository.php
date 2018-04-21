<?php

namespace Sesh\Msw;

use App\MswSpot;

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
}