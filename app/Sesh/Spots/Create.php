<?php

namespace Sesh\Spots;

use App\Spot;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Validation\ValidationException;

class Create
{
    /**
     * @var Guard
     */
    protected $user;

    /**
     * @param Guard $user
     */
    public function __construct(Guard $user)
    {
        $this->user = $user;
    }

    /**
     * @param array $data
     * @return Spot
     * @throws ValidationException
     */
    public function __invoke(array $data) : Spot
    {
        $spot = new Spot([
            'msw_spot_id' => $data['msw_spot_id'] ?? null,
            'user_id' => $this->user->id(),
            'name' => $data['name'] ?? null,
            'public' => $data['public'] ?? null,
        ]);

        $spot->save();

        return $spot;
    }
}
