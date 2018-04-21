<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SurfsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testSurfCreationWithOwnPublicSpot()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->post('/api/v1/surf', [
            'spot_id' => 1,
            'date_start' => '2018-04-21T16:00:00.000Z',
            'date_end' => '2018-04-21T17:15:00.000Z',
            'swell_size' => 2,
            'wind_speed' => 5,
            'wind_direction' => 'offshore',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'id' => 1,
                'spot_id' => 1,
                'date_start' => 1524326400,
                'date_end' => 1524330900,
                'swell_size' => 2,
                'wind_speed' => 5,
                'wind_direction' => 'offshore',
            ]);
    }
}