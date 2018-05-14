<?php

namespace Sesh\Surfs;

use App\Surf;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;

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
}