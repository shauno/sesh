<?php

namespace Sesh\Spots;

use App\Spot;

class SpotRepository
{
    /**
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findForUser(int $userId)
    {
        return (new Spot())->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
            ->orWhere('public', true);
        })->get();
    }
}
