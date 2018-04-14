<?php

namespace Sesh\Msw;

use App\MswContinent;
use App\MswCountry;
use App\MswRegion;
use App\MswSpot;
use App\MswSurfArea;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Database\Eloquent\Collection;
use Sesh\Lib\HttpClient;

class MswClient
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return Collection
     */
    public function importContinents() : Collection
    {
        $continents = new Collection();

        try {
            $response = $this->client->request('GET', 'http://magicseaweed.com/api/'.getenv('MSW_API_KEY').'/continent');

            if ($response->getStatusCode() === 200) {
                if ($body = json_decode($response->getBody()->getContents())) {
                    foreach ($body as $continent) {
                        $model =
                            (new MswContinent())->updateOrCreate(
                                [
                                    'id' => $continent->_id,
                                ],
                                [
                                    'id' => $continent->_id,
                                    'name' => $continent->name,
                                ]
                            );

                        $continents->add($model);
                    }
                }
            }
        } catch (\Exception $e) {
            // Blank blocks are bad, mmmkay
        }

        return $continents;
    }

    /**
     * @param string $continentName
     * @return Collection
     */
    public function importRegions(string $continentName) : Collection
    {
        $regions = new Collection();

        try {
            //TODO, get this from a repository?
            $continents = MswContinent::where('name', $continentName)->get();

            foreach($continents as $continent) {

                $response = $this->client->request('GET', 'http://magicseaweed.com/api/'.getenv('MSW_API_KEY').'/region?limit=-1&continent_id='.$continent->getId());

                if ($response->getStatusCode() === 200) {
                    if ($body = json_decode($response->getBody()->getContents())) {
                        foreach ($body as $region) {
                            $model = (new MswRegion())->updateOrCreate(
                                [
                                    'id' => $region->_id,
                                ],
                                [
                                    'id' => $region->_id,
                                    'msw_continent_id' => $continent->getId(),
                                    'name' => $region->name,
                                    'latitude_nw' => $region->nw->lat,
                                    'longitude_nw' => $region->nw->lon,
                                    'latitude_se' => $region->se->lat,
                                    'longitude_se' => $region->se->lon,
                                ]
                            );

                            $regions->add($model);

                            foreach ($region->countries as $country) {
                                if(!MswCountry::find($country->_id)) {
                                    (new MswCountry([
                                            'id' => $country->_id,
                                            'msw_region_id' => $region->_id,
                                            'name' => '', //required field, will get updated on country import
                                        ]
                                    ))->save();
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Blank blocks are bad, mmmkay
        }

        return $regions;
    }

    /**
     * @param string $region
     * @return Collection
     */
    public function importCountries(string $region) : Collection
    {
        $countries = new Collection();

        try {
            //TODO, get this from a repository?
            $region = MswRegion::where('name', $region)->first();
            $mswCountries = MswCountry::where('msw_region_id', $region->getId())->get();

            foreach($mswCountries as $country) {
                $response = $this->client->request('GET', 'http://magicseaweed.com/api/'.getenv('MSW_API_KEY').'/country/'.$country->getId());

                if ($response->getStatusCode() === 200) {
                    if ($body = json_decode($response->getBody()->getContents())) {
                        foreach ($body as $country) {
                            $model = (new MswCountry())->updateOrCreate(
                                [
                                    'id' => $country->_id,
                                ],
                                [
                                    'id' => $country->_id,
                                    'name' => $country->name,
                                ]
                            );

                            $countries->add($model);

                            foreach ($country->surfAreas as $area) {
                                if(!MswSurfArea::find($area->_id)) {
                                    (new MswSurfArea([
                                            'id' => $area->_id,
                                            'msw_country_id' => $country->_id,
                                            'name' => '', //required field, will get updated on surf area import
                                            'timezone' => '',
                                            'latitude' => 0,
                                            'longitude' => 0,
                                        ]
                                    ))->save();
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Blank blocks are bad, mmmkay
        }

        return $countries;
    }

    /**
     * @param string $country
     * @return Collection
     */
    public function importSurfAreas(string $country) : Collection
    {
        $surfAreas = new Collection();

        try {
            //TODO, get this from a repository?
            $country = MswCountry::where('name', $country)->first();
            $mswSurfAreas = MswSurfArea::where('msw_country_id', $country->getId())->get();

            foreach($mswSurfAreas as $mswSurfArea) {
                $response = $this->client->request('GET', 'http://magicseaweed.com/api/'.getenv('MSW_API_KEY').'/surfArea/'.$mswSurfArea->getId());

                if ($response->getStatusCode() === 200) {
                    if ($body = json_decode($response->getBody()->getContents())) {
                        foreach ($body as $surfArea) {
                            $model = (new MswSurfArea())->updateOrCreate(
                                [
                                    'id' => $surfArea->_id,
                                ],
                                [
                                    'id' => $surfArea->_id,
                                    'name' => $surfArea->name,
                                    'timezone' => $surfArea->timezone,
                                    'latitude' => isset($surfArea->coordinates->lat) ? $surfArea->coordinates->lat : 0.0,
                                    'longitude' => isset($surfArea->coordinates->lon) ? $surfArea->coordinates->lon : 0.0,
                                ]
                            );

                            $surfAreas->add($model);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Blank blocks are bad, mmmkay
        }

        return $surfAreas;
    }

    /**
     * @param strinf $surfArea
     * @return Collection
     */
    public function importSpots(string $surfArea) : Collection
    {
        $spots = new Collection();

        try {
            //TODO, get this from a repository?
            $surfArea = $surfArea = MswSurfArea::where('name', $surfArea)->first();
            if($surfArea) {
                //The NE -> SW calc gives us +- 400km2 which should have all the spots in the area
                $args = [
                    'ne' => ($surfArea->getLatitude() + 1).','.($surfArea->getLongitude() + 1),
                    'sw' => ($surfArea->getLatitude() - 1).','.($surfArea->getLongitude() - 1),
                    'limit' => -1,
                ];

                $response = $this->client->request('GET', 'https://magicseaweed.com/api/'.getenv('MSW_API_KEY').'/spot?'.build_query($args));

                if ($response->getStatusCode() === 200) {
                    if ($body = json_decode($response->getBody()->getContents())) {
                        foreach ($body as $spot) {
                            //the GPS area calcs means we probably will get spots in that aren't in this specific surf area, ignore them
                            if($surfArea->getid() == $spot->surfAreaId) {
                                $model = (new MswSpot())->updateOrCreate(
                                    [
                                        'id' => $spot->_id,
                                    ],
                                    [
                                        'id' => $spot->_id,
                                        'msw_surf_area_id' => $spot->surfAreaId,
                                        'name' => $spot->name,
                                        'latitude' => $spot->lat,
                                        'longitude' => $spot->lon,
                                        'timezone' => $spot->timezone,
                                        'is_big_wave' => $spot->isBigWave,
                                    ]
                                );

                                $spots->add($model);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Blank blocks are bad, mmmkay
        }

        return $spots;
    }
}
