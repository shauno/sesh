<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MswSpotsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testMswSpotListing()
    {
        Passport::actingAs(
            User::find(1),
            []
        );

        $response = $this->get('/api/v1/msw_spot');

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'id' => 4625,
                    'msw_surf_area_id' => 224,
                    'name' => 'Big Bay',
                ],
                [
                    'id' => '87',
                    'msw_surf_area_id' => 228,
                    'name' => 'Durban',
                ],
            ]);
    }
}
