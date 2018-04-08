<?php

namespace Sesh\Msw;

use App\MswContinent;
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
     * @throws \Exception
     */
    public function importContinents() : Collection
    {
        $continents = new Collection();

        try {
            $response = $this->client->request('GET', 'http://magicseaweed.com/api/mdkey/continent');

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
}
