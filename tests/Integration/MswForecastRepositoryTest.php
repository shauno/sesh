<?php

namespace Tests\Integration;

use App\Surf;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Sesh\Msw\MswForecastRepository;
use Tests\TestCase;

class MswForecastRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testfFindForSurfReturnsForecastInRange()
    {
        $matchDate = '2018-07-01T09:00:00+00:00';
        // Just need a spot and a date range to find a msw forecast
        $surf = new Surf([
            'spot_id' => 1, //Doodles
            'date_start' => $matchDate,
            'date_end' => $matchDate,
        ]);

        $repo = new MswForecastRepository();

        $forecast = $repo->findForSurf($surf);

        // This forecast should match the timestamp exactly
        $this->assertEquals(strtotime($matchDate), $forecast->timestamp);
    }

    /**
     * If there is no forecast in the date range it should return the next
     */
    public function testfFindForSurfReturnsNextForecastInRange()
    {
        $matchDate = '2018-07-01T08:00:00+00:00'; //no forecast will be found at 8am
        $actualForecastDate = '2018-07-01T09:00:00+00:00'; // the next forecast will be 9am

        // Just need a spot and a date range to find a msw forecast
        $surf = new Surf([
            'spot_id' => 1, //Doodles
            'date_start' => $matchDate,
            'date_end' => $matchDate,
        ]);

        $repo = new MswForecastRepository();

        $forecast = $repo->findForSurf($surf);

        // This forecast should match the timestamp exactly
        $this->assertEquals(strtotime($actualForecastDate), $forecast->timestamp);
    }
}
