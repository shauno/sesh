<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SpotsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testSpotCreation()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->post('/api/v1/spot', [
            'msw_spot_id' => 4625,
            'name' => 'Doodles',
            'public' => true,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'msw_spot_id' => 4625,
                'name' => 'Doodles',
                'public' => true,
            ]);
    }

    public function testSpotCreationValidationErrors()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->post('/api/v1/spot', []);

        $response
            ->assertStatus(400)
            ->assertJson([
                'msw_spot_id' => ['The selected msw spot id is invalid.'],
                'name' => ['The name field is required.'],
                'public' => ['The public field must be true or false.'],
            ]);
    }

    public function testSpotListingIncludingOwnPrivateSpot()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->get('/api/v1/spot');

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'id' => 1,
                    'msw_spot_id' => 4625,
                    'user_id' => 1,
                    'name' => 'Doodles',
                    'public' => 1,
                ],
                [
                    'id' => 2,
                    'msw_spot_id' => 4625,
                    'user_id' => 1,
                    'name' => 'Horse Trails',
                    'public' => 0,
                ],
            ]);
    }

    public function testSpotListingIncludingOtherPublicExcludingOtherPrivate()
    {
        Passport::actingAs(
            User::find(2),
            []
        );

        $response = $this->get('/api/v1/spot');

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'id' => 1,
                    'msw_spot_id' => 4625,
                    'user_id' => 1,
                    'name' => 'Doodles',
                    'public' => 1,
                ],
            ]);
    }
}
