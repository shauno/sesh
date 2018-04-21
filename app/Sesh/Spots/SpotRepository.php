<?php

namespace Sesh\Spots;

use App\Spot;

class SpotRepository
{
    /**
     * @param int $id
     * @return Spot
     */
    public function find(int $id) : Spot
    {
        return (new Spot())->find($id);
    }
}