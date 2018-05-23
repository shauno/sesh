<?php

namespace Sesh\Surfs;

use App\Photo;
use App\Surf;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Sesh\Msw\MswForecastRepository;
use Sesh\Msw\MswSpotRepository;
use Sesh\Spots\SpotRepository;

class Create
{
    /**
     * @var Guard
     */
    protected $user;

    /**
     * @var SpotRepository
     */
    protected $spotRepo;

    /**
     * @var MswForecastRepository
     */
    protected $mswForecastRepo;

    /**
     * @param Guard $user
     * @param SpotRepository $spotRepo
     * @param MswForecastRepository $mswForecastRepo
     */
    public function __construct(Guard $user, SpotRepository $spotRepo, MswForecastRepository $mswForecastRepo)
    {
        $this->user = $user;
        $this->spotRepo = $spotRepo;
        $this->mswForecastRepo = $mswForecastRepo;
    }

    /**
     * @param array $data
     * @return Surf
     * @throws ValidationException
     */
    public function __invoke(array $data) : Surf
    {
        $surf = new Surf([
            'spot_id' => $data['spot_id'] ?? null,
            'user_id' => $this->user->id(),
            'date_start' => $data['date_start'] ?? null,
            'date_end' => $data['date_end'] ?? null,
            'swell_size' => $data['swell_size'] ?? null,
            'wind_speed' => $data['wind_speed'] ?? null,
            'wind_direction' => $data['wind_direction'] ?? null,
        ]);

        if ($forecast = $this->mswForecastRepo->findForSurf($surf)) {
           $surf->msw_forecast_id = $forecast->id;
        }

        if($surf->save() && isset($data['photo']) && $data['photo'] instanceof UploadedFile) { //TODO, document this, or improve it :)
            $path = $data['photo']->store('surfs/'.date('Y/m/d'));

            $surf->photos()->create(
                [
                    'user_id' => $this->user->id(),
                    'path' => $path,
                ]
            );
        }

        return $surf->loadMissing(['spot', 'photos']);
    }
}