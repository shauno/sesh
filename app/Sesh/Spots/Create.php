<?php

namespace Sesh\Spots;

use App\Spot;
use Illuminate\Contracts\Auth\Guard;

class Create
{
    protected $user;

    public function __construct(Guard $user)
    {
        $this->user = $user;
    }

    public function __invoke(int $mswSpotId, string $name, bool $public = false) : Spot
    {
        return Spot::create([
            'msw_spot_id' => $mswSpotId,
            'user_id' => $this->user->id(),
            'name' => $name,
            'public' => $public,
        ]);
    }
}