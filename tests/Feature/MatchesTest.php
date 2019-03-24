<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MatchesTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testMatchesIncludingSameDay()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->get('/api/v1/match?date=2018-07-01');

        //TODO add some better seed data to and populate actual test case matches
        $response
            ->assertStatus(200)
            ->assertJson([
                'matches' => [],
                'refs' => [
                    'forecasts' => [],
                    'spots' => [],
                    'surfs' => [
                        1 => [
                            'id' => 1,
                            'spot' => [
                                'id' => 1,
                            ],
                            'msw_forecast' => [
                                'id' => 1,
                                'msw_spot_id' => 4625,
                            ],
                            'photos' => [
                                [
                                    'id' => 1,
                                    'surf_id' => 1
                                ]
                            ]
                        ],
                    ],
                ],
            ]);
    }
}
